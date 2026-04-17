<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Location;             
use App\Models\TransactionSeries;    
use Illuminate\Http\JsonResponse; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // ── List all invoices ──────────────────────────────────
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(20);
        return view('invoices.index', compact('invoices'));
    }

    // ── Show create form ───────────────────────────────────

public function create()
{
    $customers  = Customer::all();
    $products   = Product::all();
    $locations  = Location::all();

    // Default invoice number — location select பண்ணாம இருக்கும்போது fallback
    $invoiceNumber = $this->generateInvoiceNumber(null);

    return view('invoices.create', compact(
        'customers', 'products', 'locations', 'invoiceNumber'
    ));
}
private function generateInvoiceNumber(?int $locationId, ?int $seriesId = null): string
{
    if (!$locationId) {
        $last = Invoice::max('invoice_number_int') ?? 0;
        return 'INV-' . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }

    $location = Location::find($locationId);
    if (!$location) return 'INV-000001';

    $seriesIds = $location->transaction_series_id ?? [];
    if (is_string($seriesIds)) {
        $seriesIds = json_decode($seriesIds, true) ?? [];
    }
    if (empty($seriesIds)) return 'INV-000001';

    // User selected series இருந்தால் அதை use பண்ணு
    $useSeriesId = ($seriesId && in_array($seriesId, $seriesIds))
        ? $seriesId
        : ($location->default_series_id ?? $seriesIds[0] ?? null);

    if (!$useSeriesId) return 'INV-000001';

    $series = TransactionSeries::find($useSeriesId);
    if (!$series) return 'INV-000001';

    $seriesData = $series->series_data;
    if (is_string($seriesData)) {
        $seriesData = json_decode($seriesData, true) ?? [];
    }

    $invoiceSeries = collect($seriesData)->firstWhere('module', 'Invoice');
    if (!$invoiceSeries) return 'INV-000001';

    $prefix     = $invoiceSeries['prefix']      ?? 'INV-';
    $start      = $invoiceSeries['start']       ?? '000001';
    $lastNumber = $invoiceSeries['last_number'] ?? null;

    if ($lastNumber === null) {
        return $prefix . $start;
    }

    $next = (int) $lastNumber + 1;
    return $prefix . str_pad($next, strlen((string)$start), '0', STR_PAD_LEFT);
}
private function getFormatPreview(?int $locationId): string
{
    if (!$locationId) return '';
    
    $location = Location::find($locationId);
    $seriesIds = $location->transaction_series_id ?? [];
    if (empty($seriesIds)) return '';

    $series = TransactionSeries::find($seriesIds[0]);
    $invoiceSeries = collect($series->series_data ?? [])
        ->firstWhere('module', 'Invoice');

    if (!$invoiceSeries) return '';

    $prefix = $invoiceSeries['prefix'] ?? '';
    $start  = $invoiceSeries['start']  ?? '000001';
    $x      = str_repeat('X', strlen($start));

    return $prefix . $x; // e.g. "INV-XXXXXX"
}

// Route: GET /invoices/invoice-number?location_id=5&series_id=2
public function getInvoiceNumber(Request $request): JsonResponse
{
    $locationId = $request->integer('location_id');
    $seriesId   = $request->integer('series_id') ?: null;

    \Log::info('Invoice number fetch', [
        'location_id' => $locationId,
        'series_id'   => $seriesId,
    ]);

    $number = $this->generateInvoiceNumber($locationId ?: null, $seriesId);

    // Series list build
    $seriesList = [];
    if ($locationId) {
        $location = Location::find($locationId);
        if ($location) {
            $seriesIds = $location->transaction_series_id ?? [];
            if (is_string($seriesIds)) {
                $seriesIds = json_decode($seriesIds, true) ?? [];
            }
            if (!empty($seriesIds)) {
                $seriesList = TransactionSeries::whereIn('id', $seriesIds)
                    ->get(['id', 'name', 'series_data'])
                    ->map(function ($s) {
                        $seriesData = $s->series_data;
                        if (is_string($seriesData)) {
                            $seriesData = json_decode($seriesData, true) ?? [];
                        }
                        $invoiceSeries = collect($seriesData)
                            ->firstWhere('module', 'Invoice');
                        return [
                            'id'          => $s->id,
                            'name'        => $s->name,
                            'prefix'      => $invoiceSeries['prefix']      ?? 'INV-',
                            'start'       => $invoiceSeries['start']       ?? '000001',
                            'last_number' => $invoiceSeries['last_number'] ?? null,
                        ];
                    })->toArray();
            }
        }
    }

    return response()->json([
        'invoice_number' => $number,
        'format_preview' => $this->getFormatPreview($locationId ?: null),
        'series_list'    => $seriesList,
    ]);
}
    // ── Save invoice ───────────────────────────────────────
  public function store(Request $request)
{
    $request->validate([
        'customer_id'       => 'required|exists:customers,id',
        'invoice_number'    => 'required|unique:invoices,invoice_number',
        'invoice_date'      => 'required|date',
        'due_date'          => 'required|date',
        'items'             => 'required|array|min:1',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity'  => 'required|numeric|min:0.01',
        'items.*.rate'      => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        // ✅ STEP 1: subtotal — closure வெளியே calculate பண்ணு
        $subtotal = 0;
        foreach ($request->items as $item) {
            $qty     = floatval($item['quantity'] ?? 0);
            $rate    = floatval($item['rate'] ?? 0);
            $gstData = $item['gst_data'] ?? ['value' => 0, 'type' => '%', 'amount' => 0];
            $gstAmt  = floatval($gstData['amount'] ?? 0);
            $subtotal += ($qty * $rate) + $gstAmt;  // ✅ GST சேர்த்த subtotal
        }

        $discountPercent = floatval($request->discount_percent ?? 0);
        $discountAmount  = round($subtotal * $discountPercent / 100, 2);
        $afterDiscount   = $subtotal - $discountAmount;
        $taxPercent      = floatval($request->tax_percent ?? 0);
        $taxAmount       = round($afterDiscount * $taxPercent / 100, 2);
        $adjustment      = floatval($request->adjustment ?? 0);
        $grandTotal      = round($afterDiscount + $taxAmount + $adjustment, 2);
        $status          = ($request->action === 'send') ? 'Sent' : 'Draft';

        // ✅ STEP 2: Invoice create
        $invoice = Invoice::create([
            'invoice_number'     => $request->invoice_number,
            'invoice_number_int' => (int) preg_replace('/[^0-9]/', '', $request->invoice_number),
            'customer_id'        => $request->customer_id,
            'referral_id'        => $request->referral_id ?: null,
            'location'           => $request->location_id,
            'order_number'       => $request->order_number,
            'invoice_date'       => $request->invoice_date,
            'terms'              => $request->terms ?? 'Due on Receipt',
            'due_date'           => $request->due_date,
            'salesperson'        => $request->salesperson,
            'subject'            => $request->subject,
            'warehouse_location' => $request->warehouse_location_id ?? null,
            'subtotal'           => $subtotal,
            'discount_percent'   => $discountPercent,
            'discount_amount'    => $discountAmount,
            'tax_type'           => $request->tax_type ?? 'TDS',
            'tax_percent'        => $taxPercent,
            'tax_amount'         => $taxAmount,
            'adjustment'         => $adjustment,
            'grand_total'        => $grandTotal,
            'customer_notes'     => $request->customer_notes,
            'terms_conditions'   => $request->terms_conditions,
            'status'             => $status,
        ]);

        // ✅ STEP 3: Items create — gst_data சரியா save பண்ணு
        foreach ($request->items as $item) {
            $qty      = floatval($item['quantity'] ?? 1);
            $rate     = floatval($item['rate'] ?? 0);
            $gstValue = floatval($item['gst_value'] ?? 0);
            $gstType  = $item['gst_type'] ?? '%';
            $baseAmt  = $qty * $rate;
            $gstAmt   = $gstType === '%'
                ? round($baseAmt * $gstValue / 100, 2)
                : $gstValue;
            $amount   = $baseAmt + $gstAmt;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'] ?? null,
                'item_name'  => $item['item_name'],
                'quantity'   => $qty,
                'rate'       => $rate,
                'gst_data'   => [          // ✅ JSON save
                    'value'  => $gstValue,
                    'type'   => $gstType,
                    'amount' => $gstAmt,
                ],
                'amount'     => $amount,
            ]);
        }
        // ✅ STEP 3.5: Stock deduction — warehouse location-ல் குறை
// ✅ STEP 3.5-ல் இந்த condition check பண்ணு
$warehouseLocationId = $request->warehouse_location_id 
                    ?? $request->location_id 
                    ?? null;

if ($warehouseLocationId) {
    foreach ($request->items as $item) {
        $productId = $item['product_id'] ?? null;
        $qty       = floatval($item['quantity'] ?? 0);

        if (!$productId || $qty <= 0) continue;

        $stock = \App\Models\Stock::where('product_id', $productId)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

        if (!$stock) {
            // Stock record இல்லன்னா — new record create பண்ணு (negative ஆகாம)
            \App\Models\Stock::create([
                'product_id'      => $productId,
                'location_id'     => $warehouseLocationId,
                'opening_stock'   => 0,
                'stock_on_hand'   => 0,
                'committed_stock' => 0,
                'available_stock' => max(0, -$qty),
                'value_per_unit'  => 0,
                'total_value'     => 0,
                'type'            => 'sale',
                'source_type'     => 'invoice',
                'source_id'       => $invoice->id,
            ]);
            continue;
        }

        $newStockOnHand   = max(0, $stock->stock_on_hand   - $qty);
        $newAvailableStock= max(0, $stock->available_stock - $qty);

        $stock->update([
            'stock_on_hand'   => $newStockOnHand,
            'available_stock' => $newAvailableStock,
            'committed_stock' => max(0, $stock->committed_stock), // unchanged
            'total_value'     => $newStockOnHand * $stock->value_per_unit,
            'type'            => 'sale',
            'source_type'     => 'invoice',
            'source_id'       => $invoice->id,
        ]);

        // Product table-லயும் opening_stock update பண்ணு (overall total)
        $totalStock = \App\Models\Stock::where('product_id', $productId)
                        ->whereNull('deleted_at')
                        ->sum('stock_on_hand');

        \App\Models\Product::withoutEvents(function () use ($productId, $totalStock) {
            \App\Models\Product::where('id', $productId)
                ->update(['opening_stock' => $totalStock]);
        });
            }
          }
        // ✅ STEP 4: Series increment
        if ($request->location_id) {
            $this->incrementInvoiceSeries(
                $request->location_id,
                $request->invoice_number
            );
        }

        DB::commit();

        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice ' . $invoice->invoice_number . ' created!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
 private function incrementInvoiceSeries(int $locationId, string $invoiceNumber): void
{
    $location  = Location::find($locationId);
    $seriesIds = $location->transaction_series_id ?? [];
    if (empty($seriesIds)) return;

    $defaultId = $location->default_series_id ?? $seriesIds[0];
    $series    = TransactionSeries::find($defaultId);
    if (!$series) return;

    $seriesData = $series->series_data ?? [];

    // Invoice number-ல் இருந்து numeric part எடு
    // e.g. "INV-000003" → 3
    $numericPart = preg_replace('/[^0-9]/', '', $invoiceNumber);
    $numericPart = ltrim($numericPart, '0') ?: '0';

    // series_data array update பண்ணு
    $updated = collect($seriesData)->map(function ($item) use ($numericPart) {
        if ($item['module'] === 'Invoice') {
            $item['last_number'] = $numericPart;
        }
        return $item;
    })->toArray();

    $series->update(['series_data' => $updated]);
}

    // ── Show single invoice ────────────────────────────────
 public function show($id)
{
    $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
    $locationName = \App\Models\Location::find($invoice->location)?->location_name 
                    ?? $invoice->location;
    
    return view('invoices.show', compact('invoice', 'locationName'));
}

    // ── AJAX: get product details by ID ───────────────────
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'id'    => $product->id,
            'name'  => $product->name,
            'rate'  => $product->rate,
            'unit'  => $product->unit,
            'stock' => $product->stock,
        ]);
    }

   public function getLocationStock(Request $request): JsonResponse
{
    $locationId = $request->integer('location_id');
    
    if (!$locationId) {
        return response()->json(['products' => []]);
    }

    // ✅ Products table base — stocks இல்லாட்டாலும் எல்லா products காட்டு
    $products = \App\Models\Product::whereNull('deleted_at')
        ->get()
        ->map(function ($p) use ($locationId) {

            // இந்த product-க்கு இந்த location-ல் stock இருக்கா check பண்ணு
            $stock = \App\Models\Stock::where('product_id', $p->id)
                        ->where('location_id', $locationId)
                        ->whereNull('deleted_at')
                        ->first();

            $stockOnHand = $stock ? (float) $stock->stock_on_hand : 0.0;

            $imgData = is_string($p->product_image)
                ? json_decode($p->product_image, true)
                : ($p->product_image ?? []);
            $imgPath = $imgData['front_image'] ?? null;

            $additionalData = is_string($p->additional_data)
                ? json_decode($p->additional_data, true)
                : ($p->additional_data ?? []);

            return [
                'id'            => $p->id,
                'name'          => $p->name,
                'rate'          => (float) ($p->selling_price ?? 0),
                'sku'           => $p->sku ?? '',
                'unit'          => $p->unit ?? '',
                'gst'           => (float)($additionalData['gst'] ?? 0),
                'img'           => $imgPath ? asset($imgPath) : '',
                'stock_on_hand' => $stockOnHand,
                'location_id'   => $locationId,
            ];
        });

    return response()->json(['products' => $products]);
}
   
}
