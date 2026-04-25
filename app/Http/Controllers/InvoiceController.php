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
        $customers     = Customer::all();
        $products      = Product::all();
        $locations     = Location::all();
        $variants      = \App\Models\ItemVariant::all()->groupBy('item_id');
        $priceLists    = \App\Models\PriceList::all(); // ADD THIS
        $invoiceNumber = $this->generateInvoiceNumber(null);
       
        // create() method-ல இதை மாத்துங்க:

$userCategories = \App\Models\UserCategory::all()
    ->pluck('id', 'name')  // ['Super_stockist' => 1, 'Stockist' => 2]
    ->toArray();

$customerCategoryMap = Customer::whereNotNull('customer_category')
    ->get(['id', 'customer_category'])
    ->mapWithKeys(function ($c) use ($userCategories) {
        // Trim + case-insensitive match
        $catName = trim($c->customer_category);
        
        // Exact match முதல் try
        $catId = $userCategories[$catName] ?? null;
        
        // Exact match இல்லன்னா case-insensitive try
        if (!$catId) {
            foreach ($userCategories as $name => $id) {
                if (strtolower(trim($name)) === strtolower($catName)) {
                    $catId = $id;
                    break;
                }
            }
        }
        
        return [$c->id => $catId];
    })
    ->filter()
    ->toArray();
    
        return view('invoices.create', compact(
            'customers', 'locations', 'priceLists', 'products',
            'variants', 'invoiceNumber', 'userCategories', 'customerCategoryMap',  'userCategories'
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

// InvoiceController.php — new method
public function categoryLocations(Request $request)
{
    $cat = \App\Models\UserCategory::with('locations')->find($request->category_id);
    
    \Log::info('Category locations', [
        'category_id' => $request->category_id,
        'found' => $cat ? $cat->name : 'NULL',
        'locations' => $cat ? $cat->locations->count() : 0,
    ]);
    
    if (!$cat) return response()->json(['locations' => [], 'category_name' => '']);

    return response()->json([
        'category_name' => $cat->name,
        'locations'     => $cat->locations->map(fn($l) => [
            'id'            => $l->id,
            'location_name' => $l->location_name,
            'location_type' => $l->location_type,
        ]),
    ]);
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
        $gstValue = floatval($item['gst_value'] ?? 0);
$gstType  = $item['gst_type'] ?? '%';
$baseAmt  = $qty * $rate;
$gstAmt   = $gstType === '%'
    ? round($baseAmt * $gstValue / 100, 2)
    : ($gstValue * $qty);
$subtotal += $baseAmt + $gstAmt;
        }

        $discountPercent = floatval($request->discount_percent ?? 0);
        $discountAmount  = round($subtotal * $discountPercent / 100, 2);
        $afterDiscount   = $subtotal - $discountAmount;
        $taxPercent      = floatval($request->tax_percent ?? 0);
        $taxAmount       = round($afterDiscount * $taxPercent / 100, 2);
        // $adjustment      = floatval($request->adjustment ?? 0);
        $courierCharges  = floatval($request->courier_charges ?? 0);
        $courierCharges = floatval($request->courier_charges ?? 0);

// ── Extra Charges ──────────────────────────────────────
$extraChargesRaw = $request->input('extra_charges', []);
$extraChargesData = [];
$extraChargesTotal = 0;

foreach ($extraChargesRaw as $charge) {
    $label  = trim($charge['label'] ?? '');
    $amount = floatval($charge['amount'] ?? 0);
   if ($label === '' || $amount == 0) continue;

    $extraChargesData[$label] = $amount;   // ["Packing" => 50, "Loading" => 30]
    $extraChargesTotal += $amount;
}
    //    $grandTotal = round($afterDiscount + $taxAmount + $adjustment + $courierCharges + $extraChargesTotal, 2);
      
           $grandTotal = round($afterDiscount + $taxAmount + $courierCharges + $extraChargesTotal, 2);

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
            // 'adjustment'         => $adjustment,
            'courier_charges' => $courierCharges,
            'extra_charges'   => !empty($extraChargesData) ? $extraChargesData : null,
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
       // ✅ STEP 3.5: Stock deduction — ItemStock table use பண்ணு
$warehouseLocationId = $request->warehouse_location_id 
                    ?? $request->location_id 
                    ?? null;

if ($warehouseLocationId) {
    foreach ($request->items as $item) {
        $productId = $item['product_id'] ?? null;
        $variantId = $item['variant_id'] ?? null;
        $qty       = floatval($item['quantity'] ?? 0);

        if (!$productId || $qty <= 0) continue;

        // ItemStock record எடு
        $stock = \App\Models\ItemStock::where('item_id', $productId)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

        if (!$stock) {
            // Record இல்லன்னா create பண்ணு
            $stock = \App\Models\ItemStock::create([
                'item_id'          => $productId,
                'location_id'      => $warehouseLocationId,
                'variant_id'       => $variantId ?: null,
                'opening_stock'    => 0,
                'stock_on_hand'    => 0,
                'committed_stock'  => 0,
                'available_for_sale' => 0,
                'value_per_unit'   => 0,
                'total_value'      => 0,
            ]);
        }

        $newStockOnHand    = max(0, $stock->stock_on_hand    - $qty);
        $newAvailableForSale = max(0, $stock->available_for_sale - $qty);

        $stock->update([
            'stock_on_hand'      => $newStockOnHand,
            'available_for_sale' => $newAvailableForSale,
            'total_value'        => $newStockOnHand * $stock->value_per_unit,
        ]);

        // ✅ Ledger entry — ஒவ்வொரு sale-க்கும் record போடு
        \App\Models\ItemStockLedger::create([
            'item_id'              => $productId,
            'location_id'          => $warehouseLocationId,
            'variant_id'           => $variantId ?: null,
            'transaction_type'     => 'sale',
            'transaction_date'     => $request->invoice_date,
            'reference_type'       => 'invoice',
            'reference_id'         => $invoice->id,
            'qty_change'           => -$qty,
            'committed_change'     => 0,
            'unit_value'           => $stock->value_per_unit,
            'stock_on_hand_after'  => $newStockOnHand,
            'committed_after'      => $stock->committed_stock,
            'available_after'      => $newAvailableForSale,
            'notes'                => 'Sale via Invoice ' . $invoice->invoice_number,
            'created_by'           => auth()->id(),
        ]);

        // Product table overall stock update
        $totalStock = \App\Models\ItemStock::where('item_id', $productId)
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

    // ── இந்த customer-ன் எல்லா invoice ids எடு ──
    $customerInvoiceIds = Invoice::where('customer_id', $invoice->customer_id)
                            ->pluck('id');

    // ── அந்த எல்லா invoices-ன் history காட்டு ──
    $histories = \App\Models\History::where('module', 'invoice')
        ->whereIn('record_id', $customerInvoiceIds)
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(fn($h) => [
            'action'         => $h->action,
            'user'           => $h->user?->name ?? 'System',
            'user_initials'  => strtoupper(substr($h->user?->name ?? 'S', 0, 1)),
            'time'           => $h->created_at->format('d/m/Y h:i A'),
            'time_human'     => $h->created_at->diffForHumans(),
            'description'    => $this->historyDescription($h->action, $h->old_data, $h->new_data),
            'color_class'    => $this->historyColor($h->action),
            // ── எந்த invoice என்று தெரிய invoice number add பண்றோம் ──
            'invoice_number' => Invoice::find($h->record_id)?->invoice_number ?? '',
        ]);

    return view('invoices.show', compact('invoice', 'locationName', 'histories'));
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

    $products = \App\Models\Product::whereNull('deleted_at')
        ->get()
        ->map(function ($p) use ($locationId) {

            // ✅ ItemStock use பண்ணு (Stock இல்ல)
            $stock = \App\Models\ItemStock::where('item_id', $p->id)
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
public function getPriceListRates(Request $request): JsonResponse
{
    $priceListId = $request->integer('price_list_id');
    
    if (!$priceListId) {
        return response()->json(['rates' => [], 'scheme' => null]);
    }

    $pl = \App\Models\PriceList::find($priceListId);
    if (!$pl) {
        return response()->json(['rates' => [], 'scheme' => null]);
    }

    // All Items type
    if ($pl->price_list_type === 'all_items') {
        $rates = [];
        $products = \App\Models\Product::whereNull('deleted_at')->get();
        
        foreach ($products as $p) {
            $baseRate     = (float)($p->selling_price ?? 0);
            $pct          = (float)($pl->percentage ?? 0);
            $adjustedRate = $pl->markup_type === 'markup'
                ? $baseRate * (1 + $pct / 100)
                : $baseRate * (1 - $pct / 100);
            
            $rates[$p->id] = [
                'rate'       => max(0, round($adjustedRate, 2)),
                'markup_pct' => $pct,
                'type'       => $pl->markup_type,
            ];
        }
        
        return response()->json([
            'rates'  => $rates,
            'scheme' => $pl->markup_type,
            'name'   => $pl->name,
        ]);
    }

    // Individual Items type — unit or volume
    if ($pl->price_list_type === 'individual_items') {
        $rates = [];

        // pricing_scheme: 'unit' → individual_items_unit
        // pricing_scheme: 'volume' → individual_items_volume
        $scheme   = $pl->pricing_scheme ?? 'unit';
        $jsonData = $scheme === 'volume'
            ? $pl->individual_items_volume
            : $pl->individual_items_unit;

        // JSON decode if string
        if (is_string($jsonData)) {
            $jsonData = json_decode($jsonData, true) ?? [];
        }

        if (!empty($jsonData)) {
            foreach ($jsonData as $productId => $productData) {
                $ranges     = $productData['ranges'] ?? [];
                $customRate = 0;

                if ($scheme === 'volume') {
                    // Volume: first range's custom_rate (default qty=1)
                    foreach ($ranges as $range) {
                        $startQty = $range['start_qty'] ?? 1;
                        $endQty   = $range['end_qty']   ?? 999999;
                        // qty=1 க்கு match ஆகற range
                        if ($startQty !== null && 1 >= (int)$startQty) {
                            $customRate = (float)($range['custom_rate'] ?? 0);
                            break;
                        }
                    }
                } else {
                    // Unit: first range custom_rate
                    $customRate = (float)($ranges[0]['custom_rate'] ?? 0);
                }

                if ($customRate > 0) {
                    $rates[$productId] = [
                        'rate'         => $customRate,
                        'scheme'       => $scheme,
                        'product_name' => $productData['product_name'] ?? '',
                    ];
                }
            }
        }

        return response()->json([
            'rates'  => $rates,
            'scheme' => $scheme,
            'name'   => $pl->name,
        ]);
    }

    return response()->json(['rates' => [], 'scheme' => null, 'name' => $pl->name]);
}
// ── இதை getPriceListRates() method-க்கு கீழே, கடைசி } முன்னால் add பண்ணுங்க ──

private function historyDescription(string $action, $oldData, $newData): string
{
    $old = is_array($oldData) ? $oldData : (json_decode($oldData ?? '{}', true) ?? []);
    $new = is_array($newData) ? $newData : (json_decode($newData ?? '{}', true) ?? []);

    return match ($action) {
        'create' => 'Invoice created' .
                    (isset($new['invoice_number']) ? ' #' . $new['invoice_number'] : '') .
                    (isset($new['grand_total']) ? ' for ₹' . number_format($new['grand_total'], 2) : ''),

        'update' => $this->buildUpdateDescription($old, $new),

        'status_changed' => 'Status changed from <strong>' . ($old['status'] ?? '?') .
                            '</strong> to <strong>' . ($new['status'] ?? '?') . '</strong>',

        'delete' => 'Invoice deleted' .
                    (isset($old['invoice_number']) ? ' #' . $old['invoice_number'] : ''),

        'comment' => $new['comment'] ?? 'Comment added',

        'payment' => 'Payment of <strong>₹' . number_format($new['amount'] ?? 0, 2) . '</strong> recorded',

        default => ucfirst(str_replace('_', ' ', $action)),
    };
}

private function buildUpdateDescription(array $old, array $new): string
{
    $fieldLabels = [
        'grand_total'     => 'Total',
        'subtotal'        => 'Subtotal',
        'discount_amount' => 'Discount',
        'tax_amount'      => 'Tax',
        'courier_charges' => 'Courier charges',
        'invoice_date'    => 'Invoice date',
        'due_date'        => 'Due date',
        'terms'           => 'Terms',
        'customer_notes'  => 'Notes',
        'subject'         => 'Subject',
    ];

    $changes = [];
    foreach ($new as $key => $val) {
        if (!isset($fieldLabels[$key])) continue;
        if ((string)($old[$key] ?? '') === (string)$val) continue;
        $label = $fieldLabels[$key];
        if (in_array($key, ['grand_total','subtotal','discount_amount','tax_amount','courier_charges'])) {
            $changes[] = "{$label} updated to <strong>₹" . number_format((float)$val, 2) . "</strong>";
        } else {
            $changes[] = "{$label} updated";
        }
    }

    return $changes ? implode(', ', $changes) : 'Invoice updated';
}

private function historyColor(string $action): string
{
    return match ($action) {
        'create'         => 'green',
        'update'         => 'blue',
        'status_changed' => 'orange',
        'delete'         => 'red',
        'comment'        => 'purple',
        'payment'        => 'teal',
        default          => 'gray',
    };
}
// ── AJAX: Add Comment ──────────────────────────────────
public function addComment(Request $request, $id): JsonResponse
{
    $invoice = Invoice::findOrFail($id);

    $request->validate(['comment' => 'required|string|max:1000']);

    \App\Models\History::create([
        'module'    => 'invoice',
        'action'    => 'comment',
        'record_id' => $invoice->id,
        'user_id'   => \Illuminate\Support\Facades\Auth::id(),
        'old_data'  => null,
        'new_data'  => ['comment' => $request->comment],
    ]);

    return response()->json(['success' => true]);
}
// Add this method to InvoiceController
public function getCustomerDefaults(Request $request): JsonResponse
{
    $customerId = $request->integer('customer_id');
    $customer   = Customer::find($customerId);

    if (!$customer || !$customer->customer_category) {
        return response()->json([
            'category'    => null,
            'locations'   => [],
            'series'      => [],
            'price_lists' => [],
        ]);
    }

    // 1. Find the UserCategory by name (case-insensitive)
    $userCategory = \App\Models\UserCategory::whereRaw(
        'LOWER(TRIM(name)) = ?', [strtolower(trim($customer->customer_category))]
    )->first();

    if (!$userCategory) {
        return response()->json([
            'category'    => null,
            'locations'   => [],
            'series'      => [],
            'price_lists' => [],
        ]);
    }

    // 2. Get TransactionSeries for this category
    $allSeries = \App\Models\TransactionSeries::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get();

    // 3. Collect all location IDs from series
    $locationIds = [];
    foreach ($allSeries as $series) {
        $locIds = $series->location_id ?? [];
        if (is_string($locIds)) $locIds = json_decode($locIds, true) ?? [];
        foreach ($locIds as $lid) {
            $locationIds[] = (int) $lid;
        }
    }
    $locationIds = array_unique(array_filter($locationIds));

    // 4. Fetch those locations
    $locations = \App\Models\Location::whereIn('id', $locationIds)
        ->whereNull('deleted_at')
        ->get(['id', 'location_name', 'location_type', 'transaction_series_id', 'default_series_id'])
        ->map(fn($l) => [
            'id'                    => $l->id,
            'location_name'         => $l->location_name,
            'location_type'         => $l->location_type,
            'transaction_series_id' => is_string($l->transaction_series_id)
                                        ? json_decode($l->transaction_series_id, true)
                                        : ($l->transaction_series_id ?? []),
            'default_series_id'     => $l->default_series_id,
        ]);

    // 5. Build series list with invoice number preview
    $seriesList = $allSeries->map(function ($s) {
        $seriesData = $s->series_data;
        if (is_string($seriesData)) $seriesData = json_decode($seriesData, true) ?? [];

        $invoiceSeries = collect($seriesData)->firstWhere('module', 'Invoice');

        $locIds = $s->location_id ?? [];
        if (is_string($locIds)) $locIds = json_decode($locIds, true) ?? [];

        return [
            'id'          => $s->id,
            'name'        => $s->name,
            'location_ids'=> array_map('intval', $locIds),
            'prefix'      => $invoiceSeries['prefix']      ?? 'INV-',
            'start'       => $invoiceSeries['start']       ?? '000001',
            'last_number' => $invoiceSeries['last_number'] ?? null,
        ];
    });

    // 6. Get PriceLists for this category
    $priceLists = \App\Models\PriceList::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get(['id', 'name', 'price_list_type', 'markup_type', 'percentage']);

    return response()->json([
        'category' => [
            'id'   => $userCategory->id,
            'name' => $userCategory->name,
        ],
        'locations'   => $locations,
        'series'      => $seriesList,
        'price_lists' => $priceLists,
    ]);
}

}
