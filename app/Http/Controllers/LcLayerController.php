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

        // ── RULE 1: Duplicate name at same depth + same parent ──
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

        // ── RULE 2: Only ONE specific layer allowed per parent value ──
        // Example: India already has "state" layer → cannot create another layer under India
        // USA can still create its own "state" layer because parent_value_id is different
 // ── RULE 2: Only ONE specific layer allowed per parent value ──
// ── RULE 2: Same layer name cannot exist in the same country's tree ──
if (!$isGlobal) {
    // Find the root ancestor value of the current parent
    // to check if same-named layer already exists in this country's branch
    if (!is_null($parentValueId)) {
        
        // Get the root country value id (trace up to depth=0 layer)
        $rootCountryValueId = $this->getRootAncestor($parentValueId);
        
        // Check if a layer with same name already exists anywhere 
        // under this same root country
        $sameNameExists = $this->layerExistsUnderCountry($name, $rootCountryValueId);
        
        if ($sameNameExists) {
            return response()->json([
                'success' => false,
                'message' => "A '{$name}' layer already exists in this country's hierarchy. Cannot create duplicate.",
            ]);
        }
        
        // Also block: only ONE layer per parent+depth
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
}

        // ── RULE 3: Only ONE global layer per depth ──
        // Example: depth=1 already has a global "district" → block another global at depth=1
        if ($isGlobal) {
            if (LcLayer::where('depth', $depth)
                       ->where('is_global', true)
                       ->exists()) {
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
            return response()->json([
                'success' => false,
                'message' => "'{$value}' already exists!",
            ]);
        }

        $val = LcLayerValue::create([
            'layer_id'        => $layerId,
            'parent_value_id' => $parentValueId,
            'value'           => $value,
        ]);

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
            // Scoped delete: only values under this specific parent value
            $values = LcLayerValue::where('layer_id', $layer->id)
                ->where('parent_value_id', $request->parent_value_id)
                ->get();

            foreach ($values as $val) {
                $this->deleteValueRecursive($val->id);
            }
        } else {
            // Full delete: remove all values + the layer record itself
            $rootValues = LcLayerValue::where('layer_id', $layer->id)
                ->whereNull('parent_value_id')
                ->get();

            foreach ($rootValues as $val) {
                $this->deleteValueRecursive($val->id);
            }

            // Safety: delete any remaining values for this layer
            LcLayerValue::where('layer_id', $layer->id)->delete();
            $layer->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
 * Trace up to find the root country value id
 */
private function getRootAncestor(int $valueId): int
{
    $value = LcLayerValue::find($valueId);
    if (!$value || is_null($value->parent_value_id)) {
        return $valueId; // This IS the root
    }
    return $this->getRootAncestor($value->parent_value_id);
}

/**
 * Check if a layer with given name exists anywhere under this country
 */
private function layerExistsUnderCountry(string $name, int $rootValueId): bool
{
    // Get ALL value ids under this root country (recursively)
    $allValueIds = $this->getAllDescendantIds($rootValueId);
    $allValueIds[] = $rootValueId;
    
    // Check if any layer with this name has parent_value_id in this set
    return LcLayer::where('name', $name)
                  ->where('is_global', false)
                  ->whereIn('parent_value_id', $allValueIds)
                  ->exists();
}

/**
 * Get all descendant value ids recursively
 */
private function getAllDescendantIds(int $valueId): array
{
    $children = LcLayerValue::where('parent_value_id', $valueId)->pluck('id')->toArray();
    $all = $children;
    foreach ($children as $childId) {
        $all = array_merge($all, $this->getAllDescendantIds($childId));
    }
    return $all;
}
}