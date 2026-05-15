<?php

namespace App\Http\Controllers;

use App\Models\InventoryAdjustment;
use App\Models\InventoryAdjustmentItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\ItemStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventoryAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = InventoryAdjustment::with('location')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('inventory.adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $locations = Location::whereNull('deleted_at')
            ->orderBy('location_name')
            ->get();

        $items = Product::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $accounts = InventoryAdjustment::$accounts;
        $reasons  = InventoryAdjustment::$reasons;
         $productRules = [
        '100kg only allow the free shipping',
        '50kg standard rate',
        'Fragile items - handle with care',
        'Refrigerated goods only',
        'Hazardous materials',
        'No free shipping',
    ];
$itemsJson = [];

foreach ($items as $p) {
    if ($p->item_variant_type === 'contains_variants' && $p->variants_data) {
        // Variants decode
        $variantsData = is_array($p->variants_data)
            ? $p->variants_data
            : (json_decode($p->variants_data, true) ?? []);
foreach (($variantsData['variants'] ?? []) as $variant) {
    $variantId = \App\Models\ItemVariant::where('item_id', $p->id)
        ->where('name', $variant['name'] ?? '')
        ->value('id');

    $itemsJson[] = [
        'id'               => $p->id . '_' . ($variant['name'] ?? ''), // ← unique id
        'product_id'       => $p->id,
        'variant_id'       => $variantId,
        'variant_name'     => $variant['name'] ?? '',                   // ← variant name
        'name'             => $p->name . ' - ' . ($variant['name'] ?? ''),
        'sku'              => $variant['sku'] ?? $p->sku ?? '',
        'unit'             => $p->unit ?? '',
        'cost_price'       => (float) ($variant['cost_price'] ?? $p->cost_price ?? 0),
        'quantity_on_hand' => 0,  // ← location select ஆனா fetch ஆகும்
        'current_value'    => 0,
    ];
}
    } else {
        // Single item
        $itemsJson[] = [
            'id'               => $p->id,
            'product_id'       => $p->id,
            'variant_name'     => null,
            'name'             => $p->name,
            'sku'              => $p->sku ?? '',
            'unit'             => $p->unit ?? '',
            'cost_price'       => (float) $p->cost_price,
            'quantity_on_hand' => (float) ($p->opening_stock ?? 0),
            'current_value'    => (float) (($p->opening_stock ?? 0) * ($p->cost_price ?? 0)),
        ];
    }
}

        return view('inventory.adjustments.create', compact(
            'locations', 'items', 'accounts', 'reasons', 'itemsJson','productRules'
        ));
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'mode'             => ['required', Rule::in(['quantity', 'value'])],
        'reference_number' => ['nullable', 'string', 'max:255'],
        'date'             => ['required', 'date'],
        'account'          => ['required', 'string', 'max:255'],
        'reason'           => ['required', 'string', 'max:255'],
        'location_id'      => ['required', 'exists:locations,id'],
        'description'      => ['nullable', 'string', 'max:500'],
        'product_rules'    => ['nullable', 'string', 'max:255'],
        'status'           => ['required', Rule::in(['draft', 'adjusted'])],

        'items'                          => ['required', 'array', 'min:1'],
        'items.*.item_id'                => ['required'],
        'items.*.variant_id'             => ['nullable'],
        'items.*.variant_name'           => ['nullable', 'string'],
        'items.*.quantity_available'     => ['nullable', 'numeric'],
        'items.*.new_quantity_on_hand'   => ['nullable', 'numeric'],
        'items.*.quantity_adjusted'      => ['nullable', 'numeric'],
        'items.*.current_value'          => ['nullable', 'numeric'],
        'items.*.changed_value'          => ['nullable', 'numeric'],
        'items.*.adjusted_value'         => ['nullable', 'numeric'],
    ]);

    DB::transaction(function () use ($validated) {

        $adjustment = InventoryAdjustment::create([
            'reference_number' => $validated['reference_number'] ?? null,
            'mode'             => $validated['mode'],
            'date'             => $validated['date'],
            'account'          => $validated['account'],
            'reason'           => $validated['reason'],
            'location_id'      => $validated['location_id'],
            'description'      => $validated['description'] ?? null,
            'product_rules'    => $validated['product_rules'] ?? null,
            'status'           => $validated['status'],
        ]);

        $locationId = (int) $validated['location_id'];

        foreach ($validated['items'] as $row) {

            $product   = Product::find($row['item_id']);
            if (!$product) continue;

            $variantId = !empty($row['variant_id']) ? (int)$row['variant_id'] : null;
            $costPrice = (float) ($product->cost_price ?? 0);

            // ── Location-specific current stock ──────────────────────
            $currentStock = ItemStock::where('item_id', $product->id)
                ->where('location_id', $locationId)
                ->where('variant_id', $variantId)
                ->whereNull('deleted_at')
                ->value('stock_on_hand') ?? 0;

            $quantityAdjusted = isset($row['quantity_adjusted'])
                ? (float) $row['quantity_adjusted']
                : null;

            // ── AdjustmentItem save ───────────────────────────────────
            InventoryAdjustmentItem::create([
                'inventory_adjustment_id' => $adjustment->id,
                'item_id'                 => $row['item_id'],
                'quantity_available'      => $row['quantity_available'] ?? $currentStock,
                'new_quantity_on_hand'    => $row['new_quantity_on_hand'] ?? null,
                'quantity_adjusted'       => $quantityAdjusted,
                'current_value'           => $row['current_value'] ?? ($currentStock * $costPrice),
                'changed_value'           => $row['changed_value']  ?? null,
                'adjusted_value'          => $row['adjusted_value'] ?? null,
            ]);

            // Draft ஆனா stock update வேண்டாம்
            if ($validated['status'] !== 'adjusted') continue;

            // ── Quantity Adjustment ───────────────────────────────────
            if ($validated['mode'] === 'quantity') {

                $newQty = isset($row['new_quantity_on_hand'])
                    ? (float) $row['new_quantity_on_hand']
                    : null;
                $adjQty = $quantityAdjusted;

                if (is_null($newQty) && is_null($adjQty)) continue;

                // உள்ளே stock இருக்கா பாரு (soft-deleted-உம் சேர்த்து)
                $stock = ItemStock::withTrashed()
                    ->where('item_id',     $product->id)
                    ->where('location_id', $locationId)
                    ->where('variant_id',  $variantId)
                    ->first();

                if ($stock) {
                    // Soft-deleted ஆனா restore பண்ணு
                    if ($stock->trashed()) $stock->restore();

                    $updatedStock = !is_null($newQty)
                        ? $newQty
                        : ((float) $stock->stock_on_hand + $adjQty);

                } else {
                    // புதுசா stock record இல்ல — create பண்ணு
                    $updatedStock = !is_null($newQty) ? $newQty : (float)($adjQty ?? 0);
                }

                $updatedStock = max(0, $updatedStock); // negative stock வேண்டாம்
                $committed    = (float) ($stock->committed_stock ?? 0);
                $updatedAvail = max(0, $updatedStock - $committed);

                if ($stock && !$stock->trashed()) {
                    $stock->update([
                        'stock_on_hand'      => $updatedStock,
                        'available_for_sale' => $updatedAvail,
                        'total_value'        => $updatedStock * $costPrice,
                    ]);
                } else {
                    ItemStock::create([
                        'item_id'            => $product->id,
                        'variant_id'         => $variantId,
                        'location_id'        => $locationId,
                        'opening_stock'      => $updatedStock,
                        'stock_on_hand'      => $updatedStock,
                        'committed_stock'    => 0,
                        'available_for_sale' => $updatedAvail,
                        'value_per_unit'     => $costPrice,
                        'total_value'        => $updatedStock * $costPrice,
                    ]);
                }

                // ── Product opening_stock update (all locations total) ──
                if ($variantId) {
                    $totalStock = ItemStock::where('item_id', $product->id)
                        ->where('variant_id', $variantId)
                        ->whereNull('deleted_at')
                        ->sum('stock_on_hand');
                } else {
                    $totalStock = ItemStock::where('item_id', $product->id)
                        ->whereNull('variant_id')
                        ->whereNull('deleted_at')
                        ->sum('stock_on_hand');
                }

                Product::withoutEvents(fn() =>
                    $product->update(['opening_stock' => $totalStock])
                );

                // ── Ledger ────────────────────────────────────────────
                if (class_exists(\App\Models\ItemStockLedger::class)) {
                    $changeQty = !is_null($adjQty)
                        ? $adjQty
                        : ($updatedStock - $currentStock);

                    \App\Models\ItemStockLedger::create([
                        'item_id'             => $product->id,
                        'location_id'         => $locationId,
                        'variant_id'          => $variantId,  // ← null இல்ல, variantId use பண்ணு
                        'transaction_type'    => 'adjustment',
                        'transaction_date'    => now()->toDateString(),
                        'qty_change'          => $changeQty,
                        'committed_change'    => 0,
                        'unit_value'          => $costPrice,
                        'stock_on_hand_after' => $updatedStock,
                        'committed_after'     => $committed,
                        'available_after'     => $updatedAvail,
                        'created_by'          => Auth::id(),
                    ]);
                }
            }
            // Value adjustment — qty change இல்ல
        }
    });

    $msg = $validated['status'] === 'draft'
        ? 'Inventory adjustment saved as draft.'
        : 'Inventory adjustment converted to adjusted successfully.';

    return redirect()->route('inventory.adjustments.index')->with('success', $msg);
}

/**
 * Show the edit form for a draft adjustment.
 */
public function edit(InventoryAdjustment $adjustment)
{
    // Only draft adjustments can be edited
    if ($adjustment->status !== 'draft') {
        return redirect()->route('inventory.adjustments.show', $adjustment)
                         ->with('error', 'Only draft adjustments can be edited.');
    }
 
    // Same data the create() method passes ─────────────────────────────
    $accounts     = $this->getAccounts();   // your existing helper / array
    $reasons      = $this->getReasons();    // your existing helper / array
    $locations    = \App\Models\Location::orderBy('location_name')->get();
    $productRules = $this->getProductRules(); // your existing helper / array
 
    // Items scoped to the adjustment's location (for the inline search / bulk modal)
    $itemsJson = $this->getItemsByLocation($adjustment->location_id);
 
    // Load existing line items with eager-loaded product
    $adjustment->load('items.product');
 
    return view('inventory.adjustments.edit', compact(
        'adjustment',
        'accounts',
        'reasons',
        'locations',
        'productRules',
        'itemsJson'
    ));
}
 
/**
 * Update (save draft) or convert-to-adjusted.
 */
public function update(Request $request, InventoryAdjustment $adjustment)
{
    if ($adjustment->status !== 'draft') {
        return redirect()->route('inventory.adjustments.show', $adjustment)
                         ->with('error', 'Only draft adjustments can be edited.');
    }
 
    $request->validate([
        'date'          => 'required|date',
        'account'       => 'required|string',
        'reason'        => 'required|string',
        'location_id'   => 'required|exists:locations,id',
        'description'   => 'nullable|string|max:500',
        'product_rules' => 'nullable|string',
        'items'         => 'required|array|min:1',
        'items.*.item_id' => 'required|exists:products,id',
    ]);
 
    $status = $request->input('status', 'draft'); // 'draft' or 'adjusted'
 
    // ── Update header fields ───────────────────────────────────────────
    $adjustment->update([
        'mode'             => $request->mode,
        'reference_number' => $request->reference_number,
        'date'             => $request->date,
        'account'          => $request->account,
        'reason'           => $request->reason,
        'location_id'      => $request->location_id,
        'description'      => $request->description,
        'product_rules'    => $request->product_rules,
        'status'           => $status,
    ]);
 
    // ── Replace line items ─────────────────────────────────────────────
    $adjustment->items()->delete();
 
    foreach ($request->items as $row) {
        $adjustment->items()->create([
            'product_id'           => $row['item_id'],
            'variant_id'           => $row['variant_id']           ?? null,
            'variant_name'         => $row['variant_name']         ?? null,
            'quantity_available'   => $row['quantity_available']   ?? null,
            'new_quantity_on_hand' => $row['new_quantity_on_hand'] ?? null,
            'quantity_adjusted'    => $row['quantity_adjusted']    ?? null,
            'current_value'        => $row['current_value']        ?? null,
            'changed_value'        => $row['changed_value']        ?? null,
            'adjusted_value'       => $row['adjusted_value']       ?? null,
        ]);
    }
 
    // ── If converting, update actual stock ────────────────────────────
    if ($status === 'adjusted') {
        foreach ($adjustment->items as $item) {
            if ($adjustment->mode === 'quantity' && $item->quantity_adjusted !== null) {
                // Update your stock table here, e.g.:
                // Stock::where('product_id', $item->product_id)
                //       ->where('location_id', $adjustment->location_id)
                //       ->increment('quantity', $item->quantity_adjusted);
            }
        }
    }
 
    $message = $status === 'adjusted'
        ? 'Adjustment converted to Adjusted successfully.'
        : 'Adjustment saved successfully.';
 
    return redirect()->route('inventory.adjustments.show', $adjustment)
                     ->with('success', $message);
}

public function show(InventoryAdjustment $adjustment)
    {
        $adjustment->load('location', 'items.product');
        return view('inventory.adjustments.show', compact('adjustment'));
    }

    public function getItems(Request $request)
    {
        $search = $request->get('search', '');

        $items = Product::whereNull('deleted_at')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'opening_stock', 'unit', 'cost_price']);

        return response()->json($items->map(fn($p) => [
            'id'               => $p->id,
            'name'             => $p->name,
            'sku'              => $p->sku ?? '',
            'unit'             => $p->unit ?? '',
            'cost_price'       => (float) $p->cost_price,
            'quantity_on_hand' => (float) ($p->opening_stock ?? 0),
            'current_value'    => (float) (($p->opening_stock ?? 0) * ($p->cost_price ?? 0)),
        ]));
    }

    public function getItemsByLocation(Request $request)
{
    $locationId = $request->get('location_id');
    $search     = $request->get('search', '');

    $items = Product::whereNull('deleted_at')
        ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%"))
        ->orderBy('name')
        ->get();

    return response()->json($items->flatMap(function ($p) use ($locationId) {

        if ($p->item_variant_type === 'contains_variants' && $p->variants_data) {
            $variantsData = is_array($p->variants_data)
                ? $p->variants_data
                : (json_decode($p->variants_data, true) ?? []);

            return collect($variantsData['variants'] ?? [])->map(function ($variant) use ($p, $locationId) {
                $variantId = \App\Models\ItemVariant::where('item_id', $p->id)
                    ->where('name', $variant['name'] ?? '')
                    ->value('id');

                // ← Location-specific stock
                $stock = $locationId
                    ? \App\Models\ItemStock::where('item_id', $p->id)
                        ->where('variant_id', $variantId)
                        ->where('location_id', $locationId)
                        ->whereNull('deleted_at')
                        ->value('stock_on_hand') ?? 0
                    : 0;

                $costPrice = (float)($variant['cost_price'] ?? $p->cost_price ?? 0);

                return [
                    'id'               => $p->id . '_' . ($variant['name'] ?? ''),
                    'product_id'       => $p->id,
                    'variant_id'       => $variantId,
                    'variant_name'     => $variant['name'] ?? '',
                    'name'             => $p->name . ' - ' . ($variant['name'] ?? ''),
                    'sku'              => $variant['sku'] ?? $p->sku ?? '',
                    'unit'             => $p->unit ?? '',
                    'cost_price'       => $costPrice,
                    'quantity_on_hand' => (float)$stock,
                    'current_value'    => (float)($stock * $costPrice),
                ];
            });
        }

        // Single item — location-specific stock
        $stock = $locationId
            ? \App\Models\ItemStock::where('item_id', $p->id)
                ->whereNull('variant_id')
                ->where('location_id', $locationId)
                ->whereNull('deleted_at')
                ->value('stock_on_hand') ?? 0
            : 0;

        $costPrice = (float)($p->cost_price ?? 0);

        return [[
            'id'               => $p->id,
            'product_id'       => $p->id,
            'variant_id'       => null,
            'variant_name'     => null,
            'name'             => $p->name,
            'sku'              => $p->sku ?? '',
            'unit'             => $p->unit ?? '',
            'cost_price'       => $costPrice,
            'quantity_on_hand' => (float)$stock,
            'current_value'    => (float)($stock * $costPrice),
        ]];
    }));
}

public function convert(InventoryAdjustment $adjustment)
{
    if ($adjustment->status !== 'draft') {
        return back()->with('error', 'Already adjusted.');
    }

    // Draft-ஐ adjusted-ஆ மாத்தி stock update பண்ணு
    $adjustment->update(['status' => 'adjusted']);

    $locationId = (int) $adjustment->location_id;

    DB::transaction(function () use ($adjustment, $locationId) {
        foreach ($adjustment->items as $item) {
            $product   = Product::find($item->item_id);
            if (!$product) continue;

            $variantId = $item->variant_id ?? null;
            $costPrice = (float) ($product->cost_price ?? 0);
            $newQty    = $item->new_quantity_on_hand;
            $adjQty    = $item->quantity_adjusted;

            if (is_null($newQty) && is_null($adjQty)) continue;

            $stock = ItemStock::withTrashed()
                ->where('item_id',     $product->id)
                ->where('location_id', $locationId)
                ->where('variant_id',  $variantId)
                ->first();

            if ($stock) {
                if ($stock->trashed()) $stock->restore();
                $updatedStock = !is_null($newQty)
                    ? (float)$newQty
                    : ((float)$stock->stock_on_hand + (float)$adjQty);
            } else {
                $updatedStock = !is_null($newQty) ? (float)$newQty : (float)($adjQty ?? 0);
            }

            $updatedStock = max(0, $updatedStock);
            $committed    = (float)($stock->committed_stock ?? 0);
            $updatedAvail = max(0, $updatedStock - $committed);

            if ($stock && !$stock->trashed()) {
                $stock->update([
                    'stock_on_hand'      => $updatedStock,
                    'available_for_sale' => $updatedAvail,
                    'total_value'        => $updatedStock * $costPrice,
                ]);
            } else {
                ItemStock::create([
                    'item_id'            => $product->id,
                    'variant_id'         => $variantId,
                    'location_id'        => $locationId,
                    'opening_stock'      => $updatedStock,
                    'stock_on_hand'      => $updatedStock,
                    'committed_stock'    => 0,
                    'available_for_sale' => $updatedAvail,
                    'value_per_unit'     => $costPrice,
                    'total_value'        => $updatedStock * $costPrice,
                ]);
            }

            $totalStock = ItemStock::where('item_id', $product->id)
                ->when($variantId, fn($q) => $q->where('variant_id', $variantId),
                                   fn($q) => $q->whereNull('variant_id'))
                ->whereNull('deleted_at')
                ->sum('stock_on_hand');

            Product::withoutEvents(fn() =>
                $product->update(['opening_stock' => $totalStock])
            );
        }
    });

    return redirect()->route('inventory.adjustments.show', $adjustment)
        ->with('success', 'Inventory adjustment converted to adjusted successfully.');
}

public function destroy(InventoryAdjustment $adjustment)
{
    if ($adjustment->status === 'adjusted') {
        return back()->with('error', 'Cannot delete an adjusted record.');
    }
    $adjustment->items()->delete();
    $adjustment->delete();

    return redirect()->route('inventory.adjustments.index')
        ->with('success', 'Adjustment deleted successfully.');
}

}