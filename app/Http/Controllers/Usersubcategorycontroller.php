<?php

namespace App\Http\Controllers;

use App\Models\UserCategory;
use App\Models\UserSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSubCategoryController extends Controller
{
    // ================================================================
    //  INDEX  — list all sub-categories (with their parent category)
    // ================================================================
    public function index(Request $request)
    {
        $subCategories = UserSubCategory::with('category')
            ->when($request->category_id, fn($q) => $q->where('user_category_id', $request->category_id))
            ->whereNull('deleted_at')
            ->orderBy('user_category_id')
            ->orderBy('name')
            ->get();

        $categories = UserCategory::whereNull('deleted_at')->orderBy('name')->get();

        return view('user_sub_categories.index', compact('subCategories', 'categories'));
    }

    // ================================================================
    //  STORE
    // ================================================================
    public function store(Request $request)
    {
       $request->validate([
    'user_category_id' => 'required|exists:user_categories,id',
    'name'             => 'required|string|max:100',
    'description'      => 'nullable|string|max:255',
    'status'           => 'nullable|in:active,inactive',
    'target_amount'    => 'nullable|numeric|min:0',
    'reference'        => 'nullable|string|max:100',
    'coupon'           => 'nullable|string|max:50',
]);

        // Duplicate check within the same parent
        $exists = UserSubCategory::where('user_category_id', $request->user_category_id)
            ->where('name', $request->name)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A sub-category with this name already exists under the selected category.',
            ], 422);
        }

        try {
           $sub = UserSubCategory::create([
    'user_category_id' => $request->user_category_id,
    'name'             => trim($request->name),
    'description'      => $request->description,
    'status'           => $request->status ?? 'active',
    'target_amount'    => $request->target_amount,
    'reference'        => $request->reference,
    'coupon'           => $request->coupon,
]);
            $sub->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Sub-category created successfully.',
                'data'    => $sub,
            ], 201);

        } catch (\Exception $e) {
            Log::error('[UserSubCategoryController:STORE] ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create.'], 500);
        }
    }

    // ================================================================
    //  UPDATE
    // ================================================================
    public function update(Request $request, $id)
    {
        $sub = UserSubCategory::findOrFail($id);

        $request->validate([
    'name'          => 'required|string|max:100',
    'description'   => 'nullable|string|max:255',
    'status'        => 'nullable|in:active,inactive',
    'target_amount' => 'nullable|numeric|min:0',
    'reference'     => 'nullable|string|max:100',
    'coupon'        => 'nullable|string|max:50',
]);

        // Duplicate check — exclude self
        $exists = UserSubCategory::where('user_category_id', $sub->user_category_id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Another sub-category with this name already exists.',
            ], 422);
        }

        try {
            $sub->update([
    'name'          => trim($request->name),
    'description'   => $request->description,
    'status'        => $request->status ?? $sub->status,
    'target_amount' => $request->target_amount,
    'reference'     => $request->reference,
    'coupon'        => $request->coupon,
]);
            return response()->json([
                'success' => true,
                'message' => 'Sub-category updated successfully.',
                'data'    => $sub->fresh()->load('category'),
            ]);

        } catch (\Exception $e) {
            Log::error('[UserSubCategoryController:UPDATE] ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update.'], 500);
        }
    }

    // ================================================================
    //  DESTROY
    // ================================================================
    public function destroy($id)
    {
        try {
            $sub = UserSubCategory::findOrFail($id);
            $sub->delete();

            return response()->json(['success' => true, 'message' => 'Sub-category deleted.']);

        } catch (\Exception $e) {
            Log::error('[UserSubCategoryController:DESTROY] ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete.'], 500);
        }
    }

    // ================================================================
    //  BY CATEGORY  — called via AJAX when customer category changes
    // ================================================================
public function byCategory($categoryId)
{
    $subs = UserSubCategory::where('user_category_id', $categoryId)
        ->where('status', 'active')
        ->whereNull('deleted_at')
        ->orderBy('name')
        ->get(['id', 'name']); 

    return response()->json(['success' => true, 'data' => $subs]);
}
}