<?php

namespace App\Http\Controllers;

use App\Models\Assembly;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AssemblyController extends Controller
{
    public function index(Request $request)
    {
        $assemblies = Assembly::with('compositeItem', 'location')->latest()->paginate(15);
        return view('assemblies.index', compact('assemblies'));
    }

    public function create(Request $request)
    {
        $compositeItems = Product::where('item_type', 'composite_item')->get();
        $locations      = Location::all();
        $lastAssembly   = Assembly::latest()->first();
        $autoNumber     = 'ASM-' . str_pad(($lastAssembly ? $lastAssembly->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        $preselectedId  = $request->query('composite_item_id');
        $preselectedName = null;
        if ($preselectedId) {
            $item = Product::find($preselectedId);
            $preselectedName = $item?->name;
        }
        return view('assemblies.create', compact('compositeItems','locations','autoNumber','preselectedId','preselectedName'));
    }

    // AJAX — composite item details + stock by location
    public function getCompositeItem($id, Request $request)
    {
        $product    = Product::findOrFail($id);
        $locationId = $request->query('location_id');

        // Parse associate_item_details
        $raw = $product->associate_item_details;
        if (is_string($raw)) {
            $associateData = json_decode($raw, true) ?? [];
        } elseif (is_array($raw)) {
            $associateData = $raw;
        } else {
            $associateData = [];
        }

        $rawItems    = $associateData['items']    ?? [];
        $rawServices = $associateData['services'] ?? [];

        // Flat array support (old format — split by type)
        if (empty($rawItems) && empty($rawServices) && !empty($associateData) && isset($associateData[0])) {
            foreach ($associateData as $entry) {
                $type = $entry['type'] ?? 'goods';
                if ($type === 'service') {
                    $rawServices[] = $entry;
                } else {
                    $rawItems[] = $entry;
                }
            }
        }

        // Build items with stock
        $items = collect($rawItems)->map(function ($item) use ($locationId) {
            $productId      = $item['product_id'] ?? $item['id'] ?? null;
            $availableStock = 0;

            if ($productId) {
                $stockQ = \App\Models\ItemStock::where('item_id', $productId)->whereNull('deleted_at');
                if ($locationId) {
                    $availableStock = (float)($stockQ->where('location_id', $locationId)->value('available_for_sale') ?? 0);
                } else {
                    $availableStock = (float)$stockQ->sum('available_for_sale');
                }              
            }

            return [
                'product_id'         => $productId,
                'name'               => $item['name']       ?? $item['item_name'] ?? '',
                'sku'                => $item['sku']        ?? '',
                'unit'               => $item['unit']       ?? '',
                'quantity_required'  => (float)($item['quantity'] ?? $item['qty'] ?? 1),
                'quantity_available' => $availableStock,
                'cost'               => (float)($item['cost_price'] ?? $item['cost'] ?? 0),
                'image_url'          => $item['image_url']  ?? null,
            ];
        })->values()->toArray();

        // Build services (no stock check)
        $services = collect($rawServices)->map(function ($svc) {
            return [
                'product_id'        => $svc['product_id'] ?? $svc['id'] ?? null,
                'name'              => $svc['name']       ?? $svc['item_name'] ?? '',
                'sku'               => $svc['sku']        ?? '',
                'unit'              => $svc['unit']       ?? '',
                'quantity_required' => (float)($svc['quantity'] ?? $svc['qty'] ?? 1),
                'cost'              => (float)($svc['cost_price'] ?? $svc['cost'] ?? 0),
                'image_url'         => $svc['image_url']  ?? null,
            ];
        })->values()->toArray();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'       => $product->id,
                'name'     => $product->name,
                'sku'      => $product->sku,
                'items'    => $items,
                'services' => $services,
            ]
        ]);
    }

   public function store(Request $request)
{
    $request->validate([
        'composite_item_id'    => 'required|exists:products,id',
        'assembled_date'       => 'required|date',
        'quantity_to_assemble' => 'required|numeric|min:0.0001',
        'location_id'          => 'required|exists:locations,id',
    ]);

    DB::beginTransaction();
    try {
        $product  = Product::findOrFail($request->composite_item_id);
        $qty      = (float)$request->quantity_to_assemble;
        $locId    = $request->location_id;
        $isDraft  = $request->action === 'draft';

        $associatedItems    = json_decode($request->associated_items_json    ?? '[]', true) ?: [];
        $associatedServices = json_decode($request->associated_services_json ?? '[]', true) ?: [];

        // ✅ Stock shortage check
        if (!$isDraft) {
            $shortages = [];
            foreach ($associatedItems as $item) {
                if (empty($item['product_id'])) continue;
                $needed    = (float)($item['quantity'] ?? 0) * $qty;
                $stock     = \App\Models\ItemStock::where('item_id', $item['product_id'])
                                  ->where('location_id', $locId)
                                  ->whereNull('deleted_at')->first();
                $available = (float)($stock?->available_for_sale ?? 0);
                if ($available < $needed) {
                    $shortages[] = "• {$item['name']}: Need {$needed}, Available {$available}";
                }
            }
            if (!empty($shortages)) {
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', "⚠️ Insufficient stock:\n" . implode("\n", $shortages));
            }
        }

        $assemblyNumber = $request->assembly_number
            ?: 'ASM-' . str_pad((Assembly::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        // ✅ Assembly create பண்ணு
        $assembly = Assembly::create([
            'assembly_number'      => $assemblyNumber,
            'composite_item_id'    => $request->composite_item_id,
            'composite_item_name'  => $product->name,
            'composite_item_sku'   => $product->sku ?? '',
            'description'          => $request->description ?? '',
            'assembled_date'       => $request->assembled_date,
            'quantity_to_assemble' => $qty,
            'location_id'          => $locId,
            'associated_items'     => $associatedItems,
            'associated_services'  => $associatedServices,
            'status'               => $isDraft ? 'draft' : 'assembled',
            'created_by'           => Auth::id(),
        ]);

        // ✅ Stock update — item_stock table
        if (!$isDraft) {

            // Component stock deduct
            foreach ($associatedItems as $item) {
                if (empty($item['product_id'])) continue;
                $deductQty = (float)($item['quantity'] ?? 0) * $qty;

                $stock = \App\Models\ItemStock::where('item_id', $item['product_id'])
                              ->where('location_id', $locId)
                              ->whereNull('deleted_at')->first();

                if ($stock) {
                    $newStockOnHand = max(0, $stock->stock_on_hand   - $deductQty);
                    $newAvailable   = max(0, $stock->available_for_sale - $deductQty);

                    $stock->update([
                        'stock_on_hand'      => $newStockOnHand,
                        'available_for_sale' => $newAvailable,
                        'total_value'        => $newStockOnHand * ($stock->value_per_unit ?? 0),
                    ]);

                    // Ledger
                    \App\Models\ItemStockLedger::create([
                        'item_id'             => $item['product_id'],
                        'location_id'         => $locId,
                        'variant_id'          => null,
                        'transaction_type'    => 'adjustment',
                        'transaction_date'    => now()->toDateString(),
                        'qty_change'          => -$deductQty,
                        'committed_change'    => 0,
                        'unit_value'          => $stock->value_per_unit ?? 0,
                        'stock_on_hand_after' => $newStockOnHand,
                        'committed_after'     => $stock->committed_stock ?? 0,
                        'available_after'     => $newAvailable,
                        'notes'               => 'Assembly: ' . $assembly->assembly_number,
                        'created_by'          => Auth::id(),
                    ]);
                }
            }

            // Assembled product stock add
            $assembledStock = \App\Models\ItemStock::where('item_id', $request->composite_item_id)
                ->where('location_id', $locId)
                ->whereNull('deleted_at')
                ->first();

            if ($assembledStock) {
                $newQty = $assembledStock->stock_on_hand + $qty;
                $newAvail = $assembledStock->available_for_sale + $qty;
                $assembledStock->update([
                    'stock_on_hand'      => $newQty,
                    'available_for_sale' => $newAvail,
                    'total_value'        => $newQty * ($assembledStock->value_per_unit ?? 0),
                ]);
            } else {
                $newQty   = $qty;
                $newAvail = $qty;
                \App\Models\ItemStock::create([
                    'item_id'            => $request->composite_item_id,
                    'location_id'        => $locId,
                    'variant_id'         => null,
                    'opening_stock'      => 0,
                    'stock_on_hand'      => $qty,
                    'committed_stock'    => 0,
                    'available_for_sale' => $qty,
                    'value_per_unit'     => 0,
                    'total_value'        => 0,
                ]);
            }

            // Assembled product ledger
            \App\Models\ItemStockLedger::create([
                'item_id'             => $request->composite_item_id,
                'location_id'         => $locId,
                'variant_id'          => null,
                'transaction_type'    => 'adjustment',
                'transaction_date'    => now()->toDateString(),
                'qty_change'          => $qty,
                'committed_change'    => 0,
                'unit_value'          => 0,
                'stock_on_hand_after' => $newQty,
                'committed_after'     => 0,
                'available_after'     => $newAvail,
                'notes'               => 'Assembly created: ' . $assembly->assembly_number,
                'created_by'          => Auth::id(),
            ]);
        }

        DB::commit();
        $msg = $isDraft ? 'Assembly saved as draft!' : 'Assembly created & stock updated!';
        return redirect()->route('assemblies.index')->with('success', $msg);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}
    public function show($id)
    {
        $assembly = Assembly::with('compositeItem', 'location')->findOrFail($id);
        return view('assemblies.show', compact('assembly'));
    }

    public function destroy($id)
    {
        Assembly::findOrFail($id)->delete();
        return redirect()->route('assemblies.index')->with('success', 'Assembly deleted.');
    }
}