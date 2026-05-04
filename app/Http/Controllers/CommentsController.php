<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentSetting;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    // ── Allowed modules whitelist ─────────────────────────────────────
    private const ALLOWED_MODULES = ['customer', 'product', 'invoice'];

    // ================================================================
    //  INDEX — get all comments for a record
    //  GET /comments?module=customer&record_id=5
    // ================================================================
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'module'    => 'required|in:' . implode(',', self::ALLOWED_MODULES),
            'record_id' => 'required|integer|min:1',
        ]);

        $cfg = CommentSetting::configFor($request->module);

        if (!$cfg['enabled']) {
            return response()->json(['success' => false, 'message' => 'Comments disabled for this module.'], 403);
        }

        $comments = Comment::forModule($request->module, $request->record_id)
                        ->with('user:id,name')
                        ->latest()
                        ->get()
                        ->map(fn($c) => $c->toApiArray());

        return response()->json([
            'success'  => true,
            'comments' => $comments,
            'config'   => $cfg,
        ]);
    }

    // ================================================================
    //  STORE — add a new comment
    //  POST /{module}/{record_id}/comments
    //  e.g. POST /customers/5/comments
    //       POST /products/10/comments
    //       POST /invoices/3/comments
    // ================================================================
   public function store(Request $request, string $module, int $recordId): JsonResponse
{
    $module = rtrim($module, 's'); // customers → customer

    if (!in_array($module, self::ALLOWED_MODULES)) {  // இந்த ONE check மட்டும் வை
        return response()->json(['success' => false, 'message' => 'Invalid module.'], 422);
    }

        if (!in_array($module, self::ALLOWED_MODULES)) {
            return response()->json(['success' => false, 'message' => 'Invalid module.'], 422);
        }

        $cfg = CommentSetting::configFor($module);

        if (!$cfg['enabled']) {
            return response()->json(['success' => false, 'message' => 'Comments disabled.'], 403);
        }

        $request->validate([
            'content' => ['required', 'string', 'max:' . $cfg['max_length']],
        ]);

        // Strip HTML if not allowed
        $content = $cfg['allow_html']
            ? $request->content
            : strip_tags($request->content);

        // Basic XSS — allow safe inline formatting tags only
        if ($cfg['allow_html']) {
            $content = strip_tags($content, '<b><i><u><strong><em><br><p><ul><ol><li><a>');
        }

        try {
            $comment = Comment::create([
                'module'    => $module,
                'record_id' => $recordId,
                'content'   => $content,
                'user_id'   => Auth::id(),
                'user_name' => Auth::user()?->name ?? 'User',
            ]);

            // History log
            $this->logHistory($module, $recordId, 'comment_added', [
                'comment_id'      => $comment->id,
                'comment_preview' => mb_substr(strip_tags($content), 0, 80),
            ]);

            return response()->json([
                'success' => true,
                'comment' => $comment->toApiArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('[CommentsController:store] ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save comment.'], 500);
        }
    }

    // ================================================================
    //  DESTROY — soft-delete a comment
    //  DELETE /{module}/{record_id}/comments/{id}
    // ================================================================
    public function destroy(string $module, int $recordId, int $commentId): JsonResponse
    {
        $module = rtrim($module, 's');
        if (!in_array($module, self::ALLOWED_MODULES)) {
            return response()->json(['success' => false, 'message' => 'Invalid module.'], 422);
        }

        $cfg = CommentSetting::configFor($module);

        $comment = Comment::forModule($module, $recordId)->findOrFail($commentId);

        // Only owner or admin can delete
        if (!$cfg['allow_delete']) {
            return response()->json(['success' => false, 'message' => 'Deletion not allowed.'], 403);
        }

        if ($comment->user_id !== Auth::id() && !Auth::user()?->is_admin) {
            return response()->json(['success' => false, 'message' => 'Not authorized.'], 403);
        }

        try {
            $comment->delete();   // soft delete

            $this->logHistory($module, $recordId, 'comment_deleted', [
                'comment_id' => $commentId,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('[CommentsController:destroy] ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete.'], 500);
        }
    }

    // ================================================================
    //  SETTINGS — get/update per-module config (admin only)
    //  GET  /comment-settings/{module}
    //  POST /comment-settings/{module}
    // ================================================================
    public function getSettings(string $module): JsonResponse
    {
        return response()->json([
            'success' => true,
            'config'  => CommentSetting::configFor($module),
        ]);
    }

    public function updateSettings(Request $request, string $module): JsonResponse
    {
        if (!in_array($module, self::ALLOWED_MODULES)) {
            return response()->json(['success' => false, 'message' => 'Invalid module.'], 422);
        }

        $request->validate([
            'enabled'      => 'boolean',
            'max_length'   => 'integer|min:100|max:50000',
            'allow_delete' => 'boolean',
            'allow_html'   => 'boolean',
            'label'        => 'string|max:50',
        ]);

        $row = \App\Models\CommentSetting::firstOrNew(['module' => $module]);
        $row->configuration = array_merge(
            $row->configuration ?? CommentSetting::defaultConfig(),
            $request->only(['enabled', 'max_length', 'allow_delete', 'allow_html', 'label'])
        );
        $row->save();

        return response()->json(['success' => true, 'config' => $row->configuration]);
    }

    // ── Private: write to History ─────────────────────────────────────
    private function logHistory(string $module, int $recordId, string $action, array $newData): void
    {
        try {
            History::create([
                'module'    => $module,
                'record_id' => $recordId,
                'action'    => $action,
                'user_id'   => Auth::id(),
                'old_data'  => [],
                'new_data'  => $newData,
            ]);
        } catch (\Exception $e) {
            Log::warning('[CommentsController:logHistory] ' . $e->getMessage());
        }
    }
}