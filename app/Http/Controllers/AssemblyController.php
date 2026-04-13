<?php

namespace App\Http\Controllers;

use App\Models\Assembly;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
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
                $stockQ = Stock::where('product_id', $productId)->whereNull('deleted_at');
                if ($locationId) {
                    $availableStock = (float)($stockQ->where('location_id', $locationId)->value('available_stock') ?? 0);
                } else {
                    $availableStock = (float)$stockQ->sum('available_stock');
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

            // Stock shortage check (assemble only, not draft)
            if (!$isDraft) {
                $shortages = [];
                foreach ($associatedItems as $item) {
                    if (empty($item['product_id'])) continue;
                    $needed    = (float)($item['quantity'] ?? 0) * $qty;
                    $stock     = Stock::where('product_id', $item['product_id'])
                                      ->where('location_id', $locId)
                                      ->whereNull('deleted_at')->first();
                    $available = (float)($stock?->available_stock ?? 0);
                    if ($available < $needed) {
                        $shortages[] = "• {$item['name']}: Need {$needed}, Available {$available}";
                    }
                }
                if (!empty($shortages)) {
                    return redirect()->back()->withInput()
                        ->with('error', "⚠️ Insufficient stock:\n" . implode("\n", $shortages));
                }
            }

            $assemblyNumber = $request->assembly_number
                ?: 'ASM-' . str_pad((Assembly::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

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

            if (!$isDraft) {
                // Deduct component stock
                foreach ($associatedItems as $item) {
                    if (empty($item['product_id'])) continue;
                    $deductQty = (float)($item['quantity'] ?? 0) * $qty;
                    $stock = Stock::where('product_id', $item['product_id'])
                                  ->where('location_id', $locId)
                                  ->whereNull('deleted_at')->first();
                    if ($stock) {
                        $stock->stock_on_hand   = max(0, $stock->stock_on_hand   - $deductQty);
                        $stock->available_stock = max(0, $stock->available_stock - $deductQty);
                        $stock->total_value     = $stock->stock_on_hand * ($stock->value_per_unit ?? 0);
                        $stock->source_type     = 'assembly';
                        $stock->source_id       = $assembly->id;
                        $stock->save();
                    }
                }

                // Add assembled product stock
                $assembledStock = Stock::firstOrNew([
                    'product_id'  => $request->composite_item_id,
                    'location_id' => $locId,
                ]);
                $assembledStock->opening_stock   = $assembledStock->opening_stock   ?? 0;
                $assembledStock->stock_on_hand   = ($assembledStock->stock_on_hand  ?? 0) + $qty;
                $assembledStock->available_stock = ($assembledStock->available_stock ?? 0) + $qty;
                $assembledStock->committed_stock = $assembledStock->committed_stock  ?? 0;
                $assembledStock->value_per_unit  = $assembledStock->value_per_unit   ?? 0;
                $assembledStock->total_value     = $assembledStock->stock_on_hand * ($assembledStock->value_per_unit ?? 0);
                $assembledStock->type            = 'assembly';
                $assembledStock->source_type     = 'assembly';
                $assembledStock->source_id       = $assembly->id;
                $assembledStock->save();
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