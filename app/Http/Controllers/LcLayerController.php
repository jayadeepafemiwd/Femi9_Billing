<?php

namespace App\Http\Controllers;

use App\Models\LcLayer;
use App\Models\LcLayerValue;
use Illuminate\Http\Request;

class LcLayerController extends Controller
{
    public function index()
    {
        return view('assign_location.locationcreate');
    }

    /* ── GET TREE ── */
    public function getTree()
    {
        $layers = LcLayer::orderBy('depth')->get()->map(fn($l) => [
            'id'              => $l->id,
            'name'            => $l->name,
            'is_global'       => $l->is_global,
            'depth'           => $l->depth,
            'parent_value_id' => $l->parent_value_id,
        ]);

        $values = LcLayerValue::all()->map(fn($v) => [
            'id'              => $v->id,
            'layer_id'        => $v->layer_id,
            'parent_value_id' => $v->parent_value_id,
            'value'           => $v->value,
        ]);

        return response()->json([
            'success' => true,
            'layers'  => $layers,
            'values'  => $values,
        ]);
    }

    /* ── ADD LAYER ── */
    public function addLayer(Request $request)
    {
        $name          = strtolower(trim($request->name ?? ''));
        $isGlobal      = (bool) $request->is_global;
        $depth         = (int) $request->depth;
        $parentValueId = $isGlobal ? null : ($request->parent_value_id ?? null);

        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Layer name is required.']);
        }

        // Duplicate name at same depth + same parent
        if (LcLayer::where('name', $name)
                   ->where('depth', $depth)
                   ->where('is_global', $isGlobal)
                   ->where('parent_value_id', $parentValueId)
                   ->exists()) {
            return response()->json([
                'success' => false,
                'message' => "A layer named '{$name}' already exists here!",
            ]);
        }

        // Only ONE specific layer per parent value + depth
        if (!$isGlobal && !is_null($parentValueId)) {
            $rootCountryValueId = $this->getRootAncestor($parentValueId);
            $sameNameExists     = $this->layerExistsUnderCountry($name, $rootCountryValueId);

            if ($sameNameExists) {
                return response()->json([
                    'success' => false,
                    'message' => "A '{$name}' layer already exists in this country's hierarchy.",
                ]);
            }

            if (LcLayer::where('depth', $depth)
                       ->where('is_global', false)
                       ->where('parent_value_id', $parentValueId)
                       ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A layer already exists under this item.',
                ]);
            }
        }

        // Only ONE global layer per depth
        if ($isGlobal) {
            if (LcLayer::where('depth', $depth)->where('is_global', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A global layer already exists at this level. Please delete it first.',
                ]);
            }
        }

        $layer = LcLayer::create([
            'name'            => $name,
            'is_global'       => $isGlobal,
            'depth'           => $depth,
            'parent_value_id' => $parentValueId,
        ]);

        return response()->json(['success' => true, 'layer' => $layer]);
    }

    /* ── EDIT LAYER (rename + toggle global) ── */
    public function editLayer(Request $request)
    {
        $request->validate(['layer_id' => 'required|integer']);

        $layer    = LcLayer::findOrFail($request->layer_id);
        $newName  = strtolower(trim($request->name ?? ''));
        $newGlobal = (bool) $request->is_global;

        if (!$newName) {
            return response()->json(['success' => false, 'message' => 'Layer name is required.']);
        }

        // If name changed, check for duplicates at same depth+parent
        if ($newName !== $layer->name || $newGlobal !== (bool) $layer->is_global) {
            $parentValueId = $newGlobal ? null : ($request->parent_value_id ?? $layer->parent_value_id);

            $duplicate = LcLayer::where('name', $newName)
                ->where('depth', $layer->depth)
                ->where('is_global', $newGlobal)
                ->where('parent_value_id', $parentValueId)
                ->where('id', '!=', $layer->id)
                ->exists();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => "A layer named '{$newName}' already exists here!",
                ]);
            }
        }

        $layer->name      = $newName;
        $layer->is_global = $newGlobal;

        // If switching to global, clear parent_value_id
        if ($newGlobal) {
            $layer->parent_value_id = null;
        }

        $layer->save();

        return response()->json(['success' => true, 'layer' => $layer]);
    }

    /* ── ADD VALUE ── */
    public function addValue(Request $request)
    {
        $request->validate([
            'layer_id'        => 'required|integer',
            'parent_value_id' => 'nullable|integer',
            'value'           => 'required|string|max:200',
        ]);

        $layerId       = $request->layer_id;
        $parentValueId = $request->parent_value_id ?? null;
        $value         = trim($request->value);

        $exists = LcLayerValue::where('layer_id', $layerId)
            ->where('parent_value_id', $parentValueId)
            ->whereRaw('LOWER(value) = ?', [strtolower($value)])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => "'{$value}' already exists!"]);
        }

        $val = LcLayerValue::create([
            'layer_id'        => $layerId,
            'parent_value_id' => $parentValueId,
            'value'           => $value,
        ]);

        return response()->json(['success' => true, 'value' => $val]);
    }

    /* ── EDIT VALUE (rename) ── */
    public function editValue(Request $request)
    {
        $request->validate([
            'value_id' => 'required|integer',
            'value'    => 'required|string|max:200',
        ]);

        $val     = LcLayerValue::findOrFail($request->value_id);
        $newName = trim($request->value);

        // Check duplicate: same layer + same parent + same name (excluding self)
        $exists = LcLayerValue::where('layer_id', $val->layer_id)
            ->where('parent_value_id', $val->parent_value_id)
            ->whereRaw('LOWER(value) = ?', [strtolower($newName)])
            ->where('id', '!=', $val->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "'{$newName}' already exists here!",
            ]);
        }

        $val->value = $newName;
        $val->save();

        return response()->json(['success' => true, 'value' => $val]);
    }

    /* ── DELETE VALUE ── */
    public function deleteValue(Request $request)
    {
        $request->validate(['value_id' => 'required|integer']);

        $val = LcLayerValue::findOrFail($request->value_id);
        $this->deleteValueRecursive($val->id);

        return response()->json(['success' => true]);
    }

    private function deleteValueRecursive(int $valueId): void
    {
        $children = LcLayerValue::where('parent_value_id', $valueId)->get();
        foreach ($children as $child) {
            $this->deleteValueRecursive($child->id);
        }
        LcLayerValue::where('id', $valueId)->delete();
    }

    /* ── DELETE LAYER ── */
    public function deleteLayer(Request $request)
    {
        $request->validate([
            'layer_id'        => 'required|integer',
            'parent_value_id' => 'nullable|integer',
        ]);

        $layer = LcLayer::findOrFail($request->layer_id);

        if ($request->filled('parent_value_id')) {
            $values = LcLayerValue::where('layer_id', $layer->id)
                ->where('parent_value_id', $request->parent_value_id)
                ->get();

            foreach ($values as $val) {
                $this->deleteValueRecursive($val->id);
            }
        } else {
            $rootValues = LcLayerValue::where('layer_id', $layer->id)
                ->whereNull('parent_value_id')
                ->get();

            foreach ($rootValues as $val) {
                $this->deleteValueRecursive($val->id);
            }

            LcLayerValue::where('layer_id', $layer->id)->delete();
            $layer->delete();
        }

        return response()->json(['success' => true]);
    }

    /* ── HELPERS ── */
    private function getRootAncestor(int $valueId): int
    {
        $value = LcLayerValue::find($valueId);
        if (!$value || is_null($value->parent_value_id)) {
            return $valueId;
        }
        return $this->getRootAncestor($value->parent_value_id);
    }

    private function layerExistsUnderCountry(string $name, int $rootValueId): bool
    {
        $allValueIds   = $this->getAllDescendantIds($rootValueId);
        $allValueIds[] = $rootValueId;

        return LcLayer::where('name', $name)
                      ->where('is_global', false)
                      ->whereIn('parent_value_id', $allValueIds)
                      ->exists();
    }

    private function getAllDescendantIds(int $valueId): array
    {
        $children = LcLayerValue::where('parent_value_id', $valueId)->pluck('id')->toArray();
        $all      = $children;
        foreach ($children as $childId) {
            $all = array_merge($all, $this->getAllDescendantIds($childId));
        }
        return $all;
    }
}