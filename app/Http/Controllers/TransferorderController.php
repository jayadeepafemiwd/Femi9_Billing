<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\TransferOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ItemStock; 

class TransferOrderController extends Controller
{
   public function index()
{
    $transferOrders = TransferOrder::with(['sourceLocation', 'destinationLocation'])
                        ->latest()
                        ->paginate(15); // ✅ இப்போ ->total() வேலை செய்யும்

    $locations = Location::orderBy('location_name')->get();
    $products  = Product::orderBy('name')->get();

    return view('transfer-order.index', compact('transferOrders', 'locations', 'products'));
}

    public function create()
    {
        $locations      = Location::orderBy('location_name')->get();
        $products       = Product::orderBy('name')->get();
        $nextOrderNumber = $this->generateOrderNumber();
        $productsForJs  = $this->buildProductsForJs($products);
        $locationsForJs = $this->buildLocationsForJs($locations);

        return view('transfer-order.create', compact(
            'locations', 'products', 'nextOrderNumber', 'productsForJs', 'locationsForJs'
        ));
    }

 public function store(Request $request)
{
    try {
  $validated = $request->validate([
    'transfer_order_number'   => 'required|string',
    'date'                    => 'required|date',
    'source_location_id'      => 'required|exists:locations,id',
    'destination_location_id' => 'required|exists:locations,id',
    'reason'                  => 'nullable|string',
    'items'                   => 'required|array|min:1',
    'items.*.product_id'      => 'required|exists:products,id',
    'items.*.variant_id'      => 'nullable',  // ✅ exists check எடுங்க
    'items.*.quantity'        => 'required|numeric|min:0.0001',
]);

        $action = $request->input('action', 'initiate'); // 'draft', 'initiate', 'mark_as_transfer'

        $status = match($action) {
            'draft'            => 'draft',
            'mark_as_transfer' => 'transferred',
            default            => 'initiated',
        };

        $order = TransferOrder::create([
            'transfer_order_number'   => $validated['transfer_order_number'],
            'date'                    => $validated['date'],
            'source_location_id'      => $validated['source_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'reason'                  => $validated['reason'] ?? null,
            'status'                  => $status,
        ]);
foreach ($validated['items'] as $item) {
    $order->items()->create([
        'product_id' => $item['product_id'],
        'variant_id' => !empty($item['variant_id']) ? $item['variant_id'] : null, // ✅
        'quantity'   => $item['quantity'],
    ]);
}
if ($action === 'initiate') {
    $this->holdCommittedStock($order->load('items')); // ✅ ADD
}


        // Stock logic
     if ($action === 'mark_as_transfer') {


    $this->executeStockTransfer($order->load('items'));  // ✅
}

        return redirect()->route('transfer-orders.index')
                         ->with('success', 'Transfer Order created!');

    } catch (\Exception $e) {
        Log::error('Transfer order error: ' . $e->getMessage());
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
 public function show($id)
{
    try {
        $order = TransferOrder::with([
            'items.product',
            'sourceLocation',
            'destinationLocation'
        ])->findOrFail($id);
 
        // Left panel-க்கு all orders (latest first, items eager loaded)
        $allOrders = TransferOrder::with([
            'items.product',
            'sourceLocation',
            'destinationLocation'
        ])->latest()->get();
 
        return view('transfer-order.show', compact('order', 'allOrders'));
 
    } catch (\Exception $e) {
        return redirect()->route('transfer-orders.index')
                         ->withErrors(['error' => 'Transfer Order not found']);
    }
}
    public function edit($id)
{
    $transferOrder   = TransferOrder::with('items.product')->findOrFail($id);
    $locations       = Location::orderBy('location_name')->get();
    $products        = Product::orderBy('name')->get();
    $productsForJs   = $this->buildProductsForJs($products);
    $locationsForJs  = $this->buildLocationsForJs($locations);
    $nextOrderNumber = $transferOrder->transfer_order_number; // edit-ல் existing number

    return view('transfer-order.edit', compact(
        'transferOrder', 'locations', 'products',
        'productsForJs', 'locationsForJs', 'nextOrderNumber'
    ));
}

public function update(Request $request, $id)
{
    try {
        $order  = TransferOrder::with('items')->findOrFail($id);
        $action = $request->input('action', 'initiate');
        
        // ✅ Status-ஐ முன்னாடியே capture பண்ணு
        $previousStatus = $order->status;

        if ($previousStatus === 'transferred' && $action === 'mark_as_transfer') {
            return back()->withErrors(['error' => 'This order is already transferred.']);
        }

        if ($previousStatus === 'transferred') {
            $action = 'transferred';
        }

        $validated = $request->validate([
            'transfer_order_number'   => 'required|string',
            'date'                    => 'required|date',
            'source_location_id'      => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'reason'                  => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.product_id'      => 'required|exists:products,id',
            'items.*.variant_id'      => 'nullable',
            'items.*.quantity'        => 'required|numeric|min:0.0001',
        ]);

        $status = match($action) {
            'draft'            => 'draft',
            'mark_as_transfer' => 'transferred',
            'transferred'      => 'transferred',
            default            => 'initiated',
        };

        $order->update([
            'transfer_order_number'   => $validated['transfer_order_number'],
            'date'                    => $validated['date'],
            'source_location_id'      => $validated['source_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'reason'                  => $validated['reason'] ?? null,
            'status'                  => $status,
        ]);

        $order->items()->delete();
        foreach ($validated['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'variant_id' => !empty($item['variant_id']) ? $item['variant_id'] : null,
                'quantity'   => $item['quantity'],
            ]);
        }

        // ✅ previousStatus use பண்ணு — transferred order-ல் stock touch பண்ணாதே
        if ($action === 'initiate' && $previousStatus !== 'transferred') {
            $this->holdCommittedStock($order->fresh('items'));
        }

        if ($action === 'mark_as_transfer' && $previousStatus !== 'transferred') {
            $this->executeStockTransfer($order->fresh(['items']));
        }

        return redirect()->route('transfer-orders.index')
                         ->with('success', 'Transfer Order updated!');

    } catch (\Exception $e) {
        Log::error('Transfer order update error: ' . $e->getMessage());
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


public function markTransferred($id)
{
    try {
        $order = TransferOrder::with('items')->findOrFail($id);
 
        if ($order->status === 'transferred') {
            return back()->withErrors(['error' => 'Already transferred.']);
        }
 
        $previousStatus = $order->status;
        $order->update(['status' => 'transferred']);
 
        $this->executeStockTransfer($order->fresh('items'));
 
        return redirect()->route('transfer-orders.show', $id)
                         ->with('success', 'Transfer Order marked as Transferred!');
 
    } catch (\Exception $e) {
        Log::error('markTransferred error: ' . $e->getMessage());
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
 

    public function destroy($id)
    {
        try {
            $order = TransferOrder::findOrFail($id);
            $order->delete();

            return redirect()->route('transfer-orders.index')
                             ->with('success', 'Transfer Order deleted!');
        } catch (\Exception $e) {
            Log::error('Transfer order delete error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ── AJAX: GET /transfer-orders/stock ──────────────────────────────────────
    public function getStock(Request $request)
    {
        $request->validate([
            'product_id'              => 'required|exists:products,id',
            'source_location_id'      => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
        ]);

        $vid = !empty($request->variant_id) ? $request->variant_id : null;

        return response()->json([
            'source_stock'      => $this->stockValue($request->product_id, $request->source_location_id, $vid),
            'destination_stock' => $this->stockValue($request->product_id, $request->destination_location_id, $vid),
        ]);
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function stockValue($productId, $locationId, $variantId = null): float
    {
        $query = DB::table('item_stock')
            ->where('item_id', $productId)
            ->where('location_id', $locationId)
            ->whereNull('deleted_at');

        if ($variantId) {
            $query->where('variant_id', $variantId);
        } else {
            $query->whereNull('variant_id');
        }

        return (float) ($query->value('stock_on_hand') ?? 0);
    }

    private function buildProductsForJs($products): array
    {
        return $products->map(function ($p) {
            $hasVariants  = false;
            $variantsList = [];
            $variantsData = $p->variants_data ?? null;

            if (is_string($variantsData)) {
                $variantsData = json_decode($variantsData, true);
            }
            $rawVariants = is_array($variantsData) ? ($variantsData['variants'] ?? $variantsData) : [];

            if (!empty($rawVariants)) {
                $dbVariants = DB::table('item_variants')
                    ->where('item_id', $p->id)
                    ->whereNull('deleted_at')
                    ->get();

                if ($dbVariants->isNotEmpty()) {
                    $hasVariants = true;
                    foreach ($dbVariants as $vRow) {
                        $stockOnHand = (float) DB::table('item_stock')
                            ->where('item_id', $p->id)
                            ->where('variant_id', $vRow->id)
                            ->whereNull('deleted_at')
                            ->sum('stock_on_hand');

                        $variantsList[] = [
                            'id'            => $vRow->id,
                            'name'          => (string) ($vRow->name ?? ''),
                            'sku'           => (string) ($vRow->sku ?? $p->sku ?? ''),
                            'stock_on_hand' => $stockOnHand,
                        ];
                    }
                }
            }

            $productStock = $hasVariants ? 0.0 : (float) DB::table('item_stock')
                ->where('item_id', $p->id)
                ->whereNull('variant_id')
                ->whereNull('deleted_at')
                ->sum('stock_on_hand');

            return [
                'id'            => $p->id,
                'name'          => (string) ($p->name ?? ''),
                'sku'           => (string) ($p->sku ?? ''),
                'unit'          => (string) ($p->unit ?? 'box'),
                'image_url'     => $p->image ? asset('storage/' . $p->image) : '',
                'has_variants'  => $hasVariants,
                'variants'      => $variantsList,
                'stock_on_hand' => $productStock,
            ];
        })->values()->toArray();
    }

    private function buildLocationsForJs($locations): array
    {
        return $locations->map(function ($loc) {
            $addrRaw = $loc->address_details ?? null;

            if (is_array($addrRaw)) {
                $parts   = array_filter([
                    $addrRaw['address'] ?? ($addrRaw[0] ?? ''),
                    $addrRaw['city']    ?? ($addrRaw[1] ?? ''),
                    $addrRaw['state']   ?? ($addrRaw[2] ?? ''),
                    $addrRaw['zip']     ?? ($addrRaw[3] ?? ''),
                ]);
                $addrStr = implode(', ', $parts);
            } elseif (is_string($addrRaw)) {
                $decoded = json_decode($addrRaw, true);
                if (is_array($decoded)) {
                    $parts   = array_filter([
                        $decoded['address'] ?? ($decoded[0] ?? ''),
                        $decoded['city']    ?? ($decoded[1] ?? ''),
                        $decoded['state']   ?? ($decoded[2] ?? ''),
                        $decoded['zip']     ?? ($decoded[3] ?? ''),
                    ]);
                    $addrStr = implode(', ', $parts);
                } else {
                    $addrStr = $addrRaw;
                }
            } else {
                $addrStr = '';
            }

            return [
                'id'      => $loc->id,
                'name'    => (string) ($loc->location_name ?? ''),
                'address' => $addrStr,
            ];
        })->values()->toArray();
    }

    private function generateOrderNumber(): string
    {
        $year   = now()->year;
        $prefix = "TO-{$year}-";
        $last   = TransferOrder::where('transfer_order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('transfer_order_number');
        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }



private function holdCommittedStock(TransferOrder $order): void
{
    foreach ($order->items as $item) {
        $qty = $item->quantity;
        $vid = $item->variant_id ?: null;

        Log::info('holdCommittedStock', [
            'product_id'  => $item->product_id,
            'variant_id'  => $vid,
            'quantity'    => $qty,
            'location_id' => $order->source_location_id,
        ]);

        DB::table('item_stock')
            ->where('item_id', $item->product_id)
            ->where('location_id', $order->source_location_id)
            ->where(fn($q) => $vid ? $q->where('variant_id', $vid) : $q->whereNull('variant_id'))
            ->whereNull('deleted_at')
            ->update([
                // ✅ stock_on_hand — TOUCH பண்ணாதே
                'committed_stock'    => DB::raw("committed_stock + {$qty}"),
                'available_for_sale' => DB::raw("GREATEST(available_for_sale - {$qty}, 0)"),
                'updated_at'         => now(),
            ]);
    }
}
private function executeStockTransfer(TransferOrder $order): void
{
    foreach ($order->items as $item) {
        $qty = $item->quantity;
        $vid = $item->variant_id ?: null;

        $srcRow = DB::table('item_stock')
            ->where('item_id', $item->product_id)
            ->where('location_id', $order->source_location_id)
            ->where(fn($q) => $vid ? $q->where('variant_id', $vid) : $q->whereNull('variant_id'))
            ->whereNull('deleted_at')
            ->first();

        if ($srcRow) {
            $wasInitiated = floatval($srcRow->committed_stock) >= $qty;

            if ($wasInitiated) {
    // committed release + stock_on_hand -qty இரண்டும் வேணும்
    DB::table('item_stock')->where('id', $srcRow->id)->update([
        'stock_on_hand'   => DB::raw("GREATEST(stock_on_hand - {$qty}, 0)"),  // ✅ ADD
        'committed_stock' => DB::raw("GREATEST(committed_stock - {$qty}, 0)"),
        'updated_at'      => now(),
    ]);
} else {
                // ✅ Direct mark as transfer:
                // stock_on_hand -qty, available -qty
                DB::table('item_stock')->where('id', $srcRow->id)->update([
                    'stock_on_hand'      => DB::raw("GREATEST(stock_on_hand - {$qty}, 0)"),
                    'available_for_sale' => DB::raw("GREATEST(available_for_sale - {$qty}, 0)"),
                    'updated_at'         => now(),
                ]);
            }
        }

        // Destination
        $dstRow = DB::table('item_stock')
            ->where('item_id', $item->product_id)
            ->where('location_id', $order->destination_location_id)
            ->where(fn($q) => $vid ? $q->where('variant_id', $vid) : $q->whereNull('variant_id'))
            ->whereNull('deleted_at')
            ->first();

        if ($dstRow) {
            DB::table('item_stock')->where('id', $dstRow->id)->update([
                'stock_on_hand'      => DB::raw("stock_on_hand + {$qty}"),
                'available_for_sale' => DB::raw("available_for_sale + {$qty}"),
                'updated_at'         => now(),
            ]);
        } else {
            DB::table('item_stock')->insert([
                'item_id'            => $item->product_id,
                'variant_id'         => $vid,
                'location_id'        => $order->destination_location_id,
                'stock_on_hand'      => $qty,
                'committed_stock'    => 0,
                'available_for_sale' => $qty,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}
}
