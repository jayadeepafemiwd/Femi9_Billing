<?php

namespace App\Http\Controllers;

use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UserCategoryController extends Controller
{
    // ── Flat list ────────────────────────────────────────────────
public function index()
{
    $categories = \App\Models\UserCategory::orderBy('name')
        ->get(['id', 'name', 'assign_fix_location', 'country_id', 'location_label']);

    return response()->json([
        'success' => true,
        'data'    => $categories,
    ]);
}
   public function flat(): JsonResponse
{
    $categories = UserCategory::orderBy('level')->orderBy('sort_order')->get([
        'id', 'name', 'code', 'level', 'parent_id',
        'portal_access', 'visible_in_hierarchy',
        'country_id',
        'assign_fix_location',   // ← இது இல்லாம போச்சு — இதுதான் bug
        'location_label',
    ]);
 
    return response()->json(['success' => true, 'data' => $categories]);
}

    // Frontend uses this to disable those layers in the dropdown

    public function usedLayers(): JsonResponse
    {
        $used = UserCategory::whereNotNull('assign_fix_location')
            ->pluck('assign_fix_location')
            ->map(fn($v) => (string) $v)
            ->values()
            ->toArray();

        return response()->json(['success' => true, 'used_layer_ids' => $used]);
    }

    // ── Store ────────────────────────────────────────────────────

   public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'name'                 => 'required|string|max:100',
        'code'                 => 'required|string|max:20|unique:user_categories,code',
        'description'          => 'nullable|string|max:500',
        'parent_id'            => 'nullable|exists:user_categories,id',
        'portal_access'        => 'boolean',
        'visible_in_hierarchy' => 'boolean',
        'country_id'           => 'nullable|integer',
        // ✅ unique validation எடுத்துட்டோம் — same layer multiple categories use பண்ணலாம்
        'assign_fix_location'  => 'nullable|integer',
    ]);
 
    $level = 1;
    if (!empty($validated['parent_id'])) {
        $parent = UserCategory::findOrFail($validated['parent_id']);
        $level  = $parent->level + 1;
    }
 
    $validated['level']      = $level;
    $validated['sort_order'] = UserCategory::where('parent_id', $validated['parent_id'] ?? null)
                                           ->max('sort_order') + 1;
 
    if (!empty($validated['assign_fix_location'])) {
        $validated['location_label'] = $this->buildLayerLabel(
            $validated['assign_fix_location'],
            $validated['country_id'] ?? null
        );
    }
 
    $category = UserCategory::create($validated);
 
    return response()->json([
        'success' => true,
        'message' => 'Category created successfully.',
        'data'    => $category,
    ], 201);
}

    // ── Show ─────────────────────────────────────────────────────

    public function show(UserCategory $userCategory): JsonResponse
    {
        $userCategory->load('parent', 'children');
        return response()->json([
            'success'    => true,
            'data'       => $userCategory,
            'breadcrumb' => $userCategory->breadcrumb,
        ]);
    }

    // ── Update ───────────────────────────────────────────────────

   public function update(Request $request, UserCategory $userCategory): JsonResponse
{
    $validated = $request->validate([
        'name'                 => 'sometimes|string|max:100',
        'code'                 => ['sometimes', 'string', 'max:20',
                                   Rule::unique('user_categories')->ignore($userCategory->id)],
        'description'          => 'nullable|string|max:500',
        'parent_id'            => 'nullable|exists:user_categories,id',
        'portal_access'        => 'boolean',
        'visible_in_hierarchy' => 'boolean',
        'country_id'           => 'nullable|integer',
        // ✅ unique validation எடுத்துட்டோம்
        'assign_fix_location'  => 'nullable|integer',
    ]);
 
    if (array_key_exists('assign_fix_location', $validated)) {
        $validated['location_label'] = $validated['assign_fix_location']
            ? $this->buildLayerLabel(
                $validated['assign_fix_location'],
                $validated['country_id'] ?? $userCategory->country_id
              )
            : null;
    }
 
    if (isset($validated['parent_id']) && $validated['parent_id'] != $userCategory->parent_id) {
        $newParent          = $validated['parent_id'] ? UserCategory::find($validated['parent_id']) : null;
        $validated['level'] = $newParent ? $newParent->level + 1 : 1;
        $userCategory->update($validated);
        $userCategory->recalculateDescendantLevels();
    } else {
        $userCategory->update($validated);
    }
 
    return response()->json([
        'success' => true,
        'message' => 'Category updated successfully.',
        'data'    => $userCategory->fresh(),
    ]);
}

    // ── Delete ───────────────────────────────────────────────────

    public function destroy(UserCategory $userCategory): JsonResponse
    {
        if ($userCategory->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a category that has children.',
            ], 422);
        }

        $userCategory->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    // ── Private helpers ──────────────────────────────────────────

    private function buildLayerLabel(int $layerId, ?int $countryValueId): string
    {
        $layer = \DB::table('lc_layers')->where('id', $layerId)->first();
        if (!$layer) return '';

        $parts = [$layer->name];

        if ($countryValueId) {
            $country = \DB::table('lc_layer_values')->where('id', $countryValueId)->first();
            if ($country) array_unshift($parts, $country->value);
        }

        return implode(' → ', $parts);
    }
}