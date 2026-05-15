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
       

        $invoiceCustomFields = \DB::table('additional_setting')
        ->where('category_name', 'invoice')
        ->where('status', 'active')
        ->get();

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
            'variants', 'invoiceNumber', 'userCategories', 'customerCategoryMap',  'userCategories','invoiceCustomFields'
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


    // ── Payment calculations — BEFORE Invoice::create() ──
$paymentReceived = $request->input('payment_received') === '1';
// இதை replace பண்ணுங்க:
$amountReceived  = $paymentReceived 
    ? floatval($request->input('amount_received', 0)) 
    : 0.0;

$isFullyPaid     = $paymentReceived && $amountReceived > 0 
                   && (abs($amountReceived - $grandTotal) < 0.01);
$isPartiallyPaid = $paymentReceived && $amountReceived > 0 
                   && $amountReceived < $grandTotal - 0.01;

$paymentStatus = 'unpaid';
if ($isFullyPaid)        $paymentStatus = 'paid';
elseif ($isPartiallyPaid) $paymentStatus = 'partial';

$balanceDue = max(0, round($grandTotal - $amountReceived, 2));
    
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
    'courier_charges'    => $courierCharges,
    'extra_charges'      => !empty($extraChargesData) ? $extraChargesData : null,
    'grand_total'        => $grandTotal,
    'customer_notes'     => $request->customer_notes,
    'terms_conditions'   => $request->terms_conditions,
    'status'             => $status,
    'additional_data'    => null,
    // ✅ இந்த 5 fields add பண்ணுங்க:
    'payment_status'     => $paymentStatus,
    'amount_received'    => $amountReceived,
    'balance_due'        => $balanceDue,
    'payment_mode'       => $paymentReceived ? $request->input('payment_mode') : null,
    'deposit_to'         => $paymentReceived ? $request->input('deposit_to') : null,
]);

// ✅ Invoice creation-ல் payment இருந்தா payments_record-ல் save பண்ணு
if ($paymentReceived && $amountReceived > 0) {
    $lastPmtNo = \DB::table('payments_record')->orderByDesc('id')->value('payment_no');
    $nextPmtNum = $lastPmtNo 
        ? (int) preg_replace('/[^0-9]/', '', $lastPmtNo) + 1 
        : 1;

    \DB::table('payments_record')->insert([
        'customer_id'     => $invoice->customer_id,
        'invoice_id'      => $invoice->id,
        'payment_no'      => 'PMT-' . str_pad($nextPmtNum, 6, '0', STR_PAD_LEFT),
        'payment_date'    => $request->invoice_date,
        'amount_received' => $amountReceived,
        'bank_charges'    => 0,
        'payment_mode'    => $request->input('payment_mode', 'Cash'),
        'deposit_to'      => $request->input('deposit_to', 'Cash'),
        'reference_no'    => null,
        'notes'           => 'Payment recorded at invoice creation',
        'status' => $isFullyPaid ? 'paid' : 'draft',
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);
}
// ✅ Custom Fields — invoices.additional_data JSON-ல் save
$customFields = $request->input('custom_fields', []);
$additionalData = [];
foreach ($customFields as $settingId => $value) {
    if ($value === null || $value === '') continue;
    $setting = \DB::table('additional_setting')->find($settingId);
    if (!$setting) continue;
    $additionalData[$setting->name] = $value;
}
if (!empty($additionalData)) {
    $invoice->update(['additional_data' => $additionalData]);
}
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
      // ✅ STEP 3.5: Stock deduction based on payment status
$warehouseLocationId = $request->warehouse_location_id 
                    ?? $request->location_id 
                    ?? null;

if ($warehouseLocationId) {
    foreach ($request->items as $item) {
        $productId = $item['product_id'] ?? null;
        $variantId = $item['variant_id'] ?? null;
        $qty       = floatval($item['quantity'] ?? 0);

        if (!$productId || $qty <= 0) continue;

        $stock = \App\Models\ItemStock::where('item_id', $productId)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

        if (!$stock) {
            $stock = \App\Models\ItemStock::create([
                'item_id'            => $productId,
                'location_id'        => $warehouseLocationId,
                'variant_id'         => $variantId ?: null,
                'opening_stock'      => 0,
                'stock_on_hand'      => 0,
                'committed_stock'    => 0,
                'available_for_sale' => 0,
                'value_per_unit'     => 0,
                'total_value'        => 0,
            ]);
        }

        // ══════════════════════════════════════════
        // SCENARIO 1: FULL PAID at creation
        // stock_on_hand → -qty
        // committed_stock → unchanged
        // available_for_sale → -qty
        // ══════════════════════════════════════════
        if ($isFullyPaid) {

            $newStockOnHand  = max(0, $stock->stock_on_hand - $qty);
            $newAvailable    = max(0, $stock->available_for_sale - $qty);
            // committed_stock — touch இல்லை

            $stock->update([
                'stock_on_hand'      => $newStockOnHand,
                'available_for_sale' => $newAvailable,
            ]);

            \App\Models\ItemStockLedger::create([
                'item_id'             => $productId,
                'location_id'         => $warehouseLocationId,
                'variant_id'          => $variantId ?: null,
                'transaction_type'    => 'sale',
                'transaction_date'    => $request->invoice_date,
                'reference_type'      => 'invoice',
                'reference_id'        => $invoice->id,
                'qty_change'          => -$qty,
                'committed_change'    => 0,
                'unit_value'          => $stock->value_per_unit,
                'stock_on_hand_after' => $newStockOnHand,
                'committed_after'     => $stock->committed_stock, // unchanged
                'available_after'     => $newAvailable,
                'notes'               => 'Full payment at creation — ' . $invoice->invoice_number,
                'created_by'          => auth()->id(),
            ]);

        // ══════════════════════════════════════════
        // SCENARIO 2: SENT, NO PAYMENT
        // stock_on_hand → unchanged
        // committed_stock → +qty
        // available_for_sale → -qty
        // ══════════════════════════════════════════
        } elseif ($status === 'Sent' && !$paymentReceived) {

            $newCommitted = $stock->committed_stock + $qty;
            $newAvailable = max(0, $stock->available_for_sale - $qty);
            // stock_on_hand — touch இல்லை

            $stock->update([
                'committed_stock'    => $newCommitted,
                'available_for_sale' => $newAvailable,
            ]);

            \App\Models\ItemStockLedger::create([
                'item_id'             => $productId,
                'location_id'         => $warehouseLocationId,
                'variant_id'          => $variantId ?: null,
                'transaction_type'    => 'sale',
                'transaction_date'    => $request->invoice_date,
                'reference_type'      => 'invoice',
                'reference_id'        => $invoice->id,
                'qty_change'          => 0,
                'committed_change'    => $qty,
                'unit_value'          => $stock->value_per_unit,
                'stock_on_hand_after' => $stock->stock_on_hand, // unchanged
                'committed_after'     => $newCommitted,
                'available_after'     => $newAvailable,
                'notes'               => 'Committed (sent, no payment) — ' . $invoice->invoice_number,
                'created_by'          => auth()->id(),
            ]);

        // ══════════════════════════════════════════
        // SCENARIO 3: PARTIAL PAYMENT
        // stock_on_hand → unchanged
        // committed_stock → +qty
        // available_for_sale → -qty
        // ══════════════════════════════════════════
        } elseif ($isPartiallyPaid) {

            $newCommitted = $stock->committed_stock + $qty;
            $newAvailable = max(0, $stock->available_for_sale - $qty);
            // stock_on_hand — touch இல்லை

            $stock->update([
                'committed_stock'    => $newCommitted,
                'available_for_sale' => $newAvailable,
            ]);

            \App\Models\ItemStockLedger::create([
                'item_id'             => $productId,
                'location_id'         => $warehouseLocationId,
                'variant_id'          => $variantId ?: null,
                'transaction_type'    => 'sale',
                'transaction_date'    => $request->invoice_date,
                'reference_type'      => 'invoice',
                'reference_id'        => $invoice->id,
                'qty_change'          => 0,
                'committed_change'    => $qty,
                'unit_value'          => $stock->value_per_unit,
                'stock_on_hand_after' => $stock->stock_on_hand, // unchanged
                'committed_after'     => $newCommitted,
                'available_after'     => $newAvailable,
                'notes'               => 'Committed (partial payment ₹' . $amountReceived . ') — ' . $invoice->invoice_number,
                'created_by'          => auth()->id(),
            ]);

        }
        // ══════════════════════════════════════════
        // SCENARIO 4: DRAFT
        // stock_on_hand → unchanged
        // committed_stock → unchanged
        // available_for_sale → unchanged
        // (எந்த action-உம் இல்லை)
        // ══════════════════════════════════════════

        // Product overall stock sync — ONLY for full paid
        if ($isFullyPaid) {
            $totalStock = \App\Models\ItemStock::where('item_id', $productId)
                            ->whereNull('deleted_at')
                            ->sum('stock_on_hand');

            \App\Models\Product::withoutEvents(function () use ($productId, $totalStock) {
                \App\Models\Product::where('id', $productId)
                    ->update(['opening_stock' => $totalStock]);
            });
        }

    } // end foreach items
} // end if warehouseLocationId
      // ✅ STEP 4: Series increment — AFTER:
if ($request->location_id) {
    $this->incrementInvoiceSeriesById(
        $request->series_id,       // form-ல் select பண்ண series id
        $request->invoice_number
    );
}
        // ── STEP 5.5: Auto-apply credit if requested ──────────────────────
$applyCreditAmt = floatval($request->input('apply_credit_amount', 0));

if ($applyCreditAmt > 0.005) {
    // Customer credit check
    $freshCustomer = DB::table('customers')
        ->where('id', $request->customer_id)
        ->first();

    $availableCredit = floatval($freshCustomer->unused_credits ?? 0);
    $creditToApply   = min($applyCreditAmt, $availableCredit, $grandTotal);

    if ($creditToApply > 0.005) {
        $newBalanceDue = max(0, round($grandTotal - $amountReceived - $creditToApply, 2));
        $newAmtReceived = $amountReceived + $creditToApply;
        $newPayStatus  = $newBalanceDue < 0.01 ? 'paid' : ($newAmtReceived > 0 ? 'partial' : 'unpaid');

        // Deduct credit from customer
        DB::table('customers')
            ->where('id', $request->customer_id)
            ->decrement('unused_credits', $creditToApply);

        // Update invoice
        DB::table('invoices')
            ->where('id', $invoice->id)
            ->update([
                'balance_due'     => $newBalanceDue,
                'amount_received' => $newAmtReceived,
                'payment_status'  => $newPayStatus,
            ]);

        // Payment record for credit
        $credPayCount = DB::table('payments_record')->count();
        DB::table('payments_record')->insert([
            'customer_id'        => $invoice->customer_id,
            'invoice_id'         => $invoice->id,
            'payment_no'         => 'CRED-' . str_pad($credPayCount + 1, 6, '0', STR_PAD_LEFT),
            'payment_date'       => $request->invoice_date,
            'amount_received'    => $creditToApply,
            'payment_mode'       => 'credit_applied',
            'notes'              => "Credit auto-applied at invoice creation",
            'status'             => 'paid',
            'is_advance_payment' => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // ── Stock update if now fully paid ──
        if ($newPayStatus === 'paid' && $warehouseLocationId) {
            foreach ($request->items as $item) {
                $productId = $item['product_id'] ?? null;
                $qty       = floatval($item['quantity'] ?? 0);
                if (!$productId || $qty <= 0) continue;

                $stock = \App\Models\ItemStock::where('item_id', $productId)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

                if ($stock) {
                    $stock->update([
                        'stock_on_hand'      => max(0, $stock->stock_on_hand - $qty),
                        'available_for_sale' => max(0, $stock->available_for_sale - $qty),
                    ]);
                }
            }
        }
    }
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
    
    $paymentRecords = \DB::table('payments_record')
    ->where('invoice_id', $invoice->id)
    ->orderBy('payment_date', 'asc')
    ->get();
    // ── ADD THESE 3 LINES ──
    $lastPayNo     = \DB::table('payments_record')->orderByDesc('id')->value('payment_no');
    $nextNum       = $lastPayNo ? (int) preg_replace('/[^0-9]/', '', $lastPayNo) + 1 : 1;
    $paymentNumber = 'PAY-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

    $balanceDue = max(0, floatval($invoice->balance_due ?? $invoice->grand_total));
    // ── ADD locations ──
    $locations = \App\Models\Location::whereNull('deleted_at')->get();

    $customerInvoiceIds = Invoice::where('customer_id', $invoice->customer_id)->pluck('id');

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
            'invoice_number' => Invoice::find($h->record_id)?->invoice_number ?? '',
        ]);
        $comments = \App\Models\Comment::forModule('invoice', $invoice->id)
                    ->with('user:id,name')
                    ->latest()
                    ->get()
                    ->map(fn($c) => $c->toApiArray());


    return view('invoices.show', compact(
        'invoice', 'locationName', 'histories',
        'paymentNumber', 'balanceDue', 'locations','paymentRecords','comments'
    ));
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

            $stock = \App\Models\ItemStock::where('item_id', $p->id)
                ->where('location_id', $locationId)
                ->whereNull('deleted_at')
                ->first();

            $imgData = is_string($p->product_image)
                ? json_decode($p->product_image, true)
                : ($p->product_image ?? []);
            $imgPath = $imgData['front_image'] ?? null;

            $additionalData = is_string($p->additional_data)
                ? json_decode($p->additional_data, true)
                : ($p->additional_data ?? []);

            // ── Variant stock (per variant, per location) ──
           $pvariants = \App\Models\ItemVariant::where('item_id', $p->id)
    ->whereNull('deleted_at')
    ->get()
    ->map(function ($v) use ($locationId) {

        // ✅ item_id = variant's own id, variant_id = NULL
        $vStock = \App\Models\ItemStock::where('item_id', $v->id)
            ->where('location_id', $locationId)
            ->whereNull('deleted_at')
            ->first();

        return [
            'id'            => $v->id,
            'name'          => $v->name,
            'sku'           => $v->sku ?? '',
            'rate'          => (float)($v->selling_price ?? 0),
            'stock_on_hand' => $vStock
                                ? (float)$vStock->available_for_sale
                                : 0.0,
        ];
    })
    ->toArray();
            return [
                'id'            => $p->id,
                'name'          => $p->name,
                'rate'          => (float)($p->selling_price ?? 0),
                'sku'           => $p->sku ?? '',
                'unit'          => $p->unit ?? '',
                'gst'           => (float)($additionalData['gst'] ?? 0),
                'img'           => $imgPath ? asset($imgPath) : '',
                'stock_on_hand' => $stock ? (float)$stock->available_for_sale : 0.0,
                'location_id'   => $locationId,
                'variants'      => $pvariants, // ← இதை add பண்றோம்
            ];
        });

    return response()->json(['products' => $products]);
}

public function edit($id)
{
    $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
 
    $customers   = Customer::all();
    $products    = Product::all();
    $locations   = Location::all();
    $variants    = \App\Models\ItemVariant::all()->groupBy('item_id');
    $priceLists  = \App\Models\PriceList::all();
 
    $invoiceCustomFields = \DB::table('additional_setting')
        ->where('category_name', 'invoice')
        ->where('status', 'active')
        ->get();
 
    $userCategories = \App\Models\UserCategory::all()
        ->pluck('id', 'name')
        ->toArray();
 
    $customerCategoryMap = Customer::whereNotNull('customer_category')
        ->get(['id', 'customer_category'])
        ->mapWithKeys(function ($c) use ($userCategories) {
            $catName = trim($c->customer_category);
            $catId   = $userCategories[$catName] ?? null;
            if (!$catId) {
                foreach ($userCategories as $name => $idVal) {
                    if (strtolower(trim($name)) === strtolower($catName)) {
                        $catId = $idVal;
                        break;
                    }
                }
            }
            return [$c->id => $catId];
        })
        ->filter()
        ->toArray();
 
    // additional_data JSON → array for custom fields
    if (is_string($invoice->additional_data)) {
        $invoice->additional_data = json_decode($invoice->additional_data, true) ?? [];
    }
 
    return view('invoices.edit', compact(
        'invoice', 'customers', 'locations', 'priceLists',
        'products', 'variants', 'userCategories',
        'customerCategoryMap', 'invoiceCustomFields'
    ));
}

// ── Update Invoice ─────────────────────────────────────
public function update(Request $request, $id)
{
    $invoice = Invoice::with('items')->findOrFail($id);
 
    $request->validate([
        'customer_id'       => 'required|exists:customers,id',
        'invoice_number'    => 'required|unique:invoices,invoice_number,' . $id, // ignore self
        'invoice_date'      => 'required|date',
        'due_date'          => 'required|date',
        'items'             => 'required|array|min:1',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity'  => 'required|numeric|min:0.01',
        'items.*.rate'      => 'required|numeric|min:0',
    ]);
 
    \DB::beginTransaction();
 
    try {
        // ── STEP 1: Subtotal recalculate ─────────────────────────────
        $subtotal = 0;
        foreach ($request->items as $item) {
            $qty      = floatval($item['quantity'] ?? 0);
            $rate     = floatval($item['rate'] ?? 0);
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
        $courierCharges  = floatval($request->courier_charges ?? 0);
 
        // Extra Charges
        $extraChargesRaw   = $request->input('extra_charges', []);
        $extraChargesData  = [];
        $extraChargesTotal = 0;
        foreach ($extraChargesRaw as $charge) {
            $label  = trim($charge['label'] ?? '');
            $amount = floatval($charge['amount'] ?? 0);
            if ($label === '' || $amount == 0) continue;
            $extraChargesData[$label] = $amount;
            $extraChargesTotal += $amount;
        }
 
        $grandTotal = round($afterDiscount + $taxAmount + $courierCharges + $extraChargesTotal, 2);
        $status     = ($request->action === 'send') ? 'Sent' : 'Draft';
 
        // ── STEP 2: Stock reversal — undo old committed/deducted stock ──
        // (Reverse the stock effects of the OLD invoice items before applying new ones)
        $oldStatus        = $invoice->status;
        $oldPaymentStatus = $invoice->payment_status;
        $warehouseLocationId = $invoice->warehouse_location ?? $invoice->location;
 
        if ($warehouseLocationId) {
            foreach ($invoice->items as $oldItem) {
                if (!$oldItem->product_id) continue;
                $oldQty = floatval($oldItem->quantity);
 
                $stock = \App\Models\ItemStock::where('item_id', $oldItem->product_id)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();
 
                if (!$stock) continue;
 
                // Reverse what was done at creation
                if ($oldPaymentStatus === 'paid') {
                    // Was fully paid: stock_on_hand was reduced — restore it
                    $stock->update([
                        'stock_on_hand'      => $stock->stock_on_hand + $oldQty,
                        'available_for_sale' => $stock->available_for_sale + $oldQty,
                    ]);
                } elseif (in_array($oldStatus, ['Sent']) || in_array($oldPaymentStatus, ['partial', 'unpaid'])) {
                    // Was committed: restore committed & available
                    $stock->update([
                        'committed_stock'    => max(0, $stock->committed_stock - $oldQty),
                        'available_for_sale' => $stock->available_for_sale + $oldQty,
                    ]);
                }
                // Draft: no stock was changed, nothing to reverse
            }
        }
 
        // ── STEP 3: Update main invoice fields ───────────────────────
        $oldData = $invoice->toArray();
 
        $invoice->update([
            'customer_id'      => $request->customer_id,
            'referral_id'      => $request->referral_id ?: null,
            'location'         => $request->location_id,
            'order_number'     => $request->order_number,
            'invoice_date'     => $request->invoice_date,
            'terms'            => $request->terms ?? 'Due on Receipt',
            'due_date'         => $request->due_date,
            'subject'          => $request->subject,
            'subtotal'         => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount'  => $discountAmount,
            'tax_type'         => $request->tax_type ?? 'TDS',
            'tax_percent'      => $taxPercent,
            'tax_amount'       => $taxAmount,
            'courier_charges'  => $courierCharges,
            'extra_charges'    => !empty($extraChargesData) ? $extraChargesData : null,
            'grand_total'      => $grandTotal,
            'customer_notes'   => $request->customer_notes,
            'terms_conditions' => $request->terms_conditions,
            'status'           => $status,
            // Keep existing payment status — don't reset it on edit
            'balance_due'      => max(0, round($grandTotal - floatval($invoice->amount_received ?? 0), 2)),
        ]);
 
        // ── STEP 4: Custom fields ─────────────────────────────────────
        $customFields   = $request->input('custom_fields', []);
        $additionalData = [];
        foreach ($customFields as $settingId => $value) {
            if ($value === null || $value === '') continue;
            $setting = \DB::table('additional_setting')->find($settingId);
            if (!$setting) continue;
            $additionalData[$setting->name] = $value;
        }
        if (!empty($additionalData)) {
            $invoice->update(['additional_data' => $additionalData]);
        }
 
        // ── STEP 5: Delete old items & recreate ──────────────────────
        \App\Models\InvoiceItem::where('invoice_id', $invoice->id)->delete();
 
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
 
            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'] ?? null,
                'item_name'  => $item['item_name'],
                'quantity'   => $qty,
                'rate'       => $rate,
                'gst_data'   => [
                    'value'  => $gstValue,
                    'type'   => $gstType,
                    'amount' => $gstAmt,
                ],
                'amount'     => $amount,
            ]);
        }
 
        // ── STEP 6: Re-apply stock for new items ─────────────────────
        $newPaymentStatus = $invoice->fresh()->payment_status;
        $isFullyPaid      = $newPaymentStatus === 'paid';
        $isPartiallyPaid  = $newPaymentStatus === 'partial';
 
        if ($warehouseLocationId) {
            foreach ($request->items as $item) {
                $productId = $item['product_id'] ?? null;
                $variantId = $item['variant_id'] ?? null;
                $qty       = floatval($item['quantity'] ?? 0);
                if (!$productId || $qty <= 0) continue;
 
                $stock = \App\Models\ItemStock::where('item_id', $productId)
                    ->where('location_id', $warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->firstOrCreate(
                        ['item_id' => $productId, 'location_id' => $warehouseLocationId],
                        [
                            'opening_stock'      => 0,
                            'stock_on_hand'      => 0,
                            'committed_stock'    => 0,
                            'available_for_sale' => 0,
                            'value_per_unit'     => 0,
                            'total_value'        => 0,
                        ]
                    );
 
                if ($isFullyPaid) {
                    // Fully paid: deduct stock_on_hand immediately
                    $newStockOnHand = max(0, $stock->stock_on_hand - $qty);
                    $newAvailable   = max(0, $stock->available_for_sale - $qty);
                    $stock->update([
                        'stock_on_hand'      => $newStockOnHand,
                        'available_for_sale' => $newAvailable,
                    ]);
                } elseif ($status === 'Sent' || $isPartiallyPaid) {
                    // Sent or partial: commit stock
                    $newCommitted = $stock->committed_stock + $qty;
                    $newAvailable = max(0, $stock->available_for_sale - $qty);
                    $stock->update([
                        'committed_stock'    => $newCommitted,
                        'available_for_sale' => $newAvailable,
                    ]);
                }
                // Draft: no stock change
 
                // Sync product overall stock
                if ($isFullyPaid) {
                    $totalStock = \App\Models\ItemStock::where('item_id', $productId)
                        ->whereNull('deleted_at')
                        ->sum('stock_on_hand');
                    \App\Models\Product::withoutEvents(function () use ($productId, $totalStock) {
                        \App\Models\Product::where('id', $productId)
                            ->update(['opening_stock' => $totalStock]);
                    });
                }
            }
        }
 
        // ── STEP 7: History log ───────────────────────────────────────
        \App\Models\History::create([
            'module'    => 'invoice',
            'action'    => 'update',
            'record_id' => $invoice->id,
            'user_id'   => auth()->id(),
            'old_data'  => $oldData,
            'new_data'  => $invoice->fresh()->toArray(),
        ]);
 
        \DB::commit();
 
        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice ' . $invoice->invoice_number . ' updated successfully!');
 
    } catch (\Exception $e) {
        \DB::rollBack();
        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
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

    // Individual Items type
    if ($pl->price_list_type === 'individual_items') {
        $rates = [];

        $scheme   = $pl->pricing_scheme ?? 'unit';
        $jsonData = $scheme === 'volume'
            ? $pl->individual_items_volume
            : $pl->individual_items_unit;

        if (is_string($jsonData)) {
            $jsonData = json_decode($jsonData, true) ?? [];
        }

        if (!empty($jsonData)) {
            foreach ($jsonData as $compositeKey => $productData) {
                $customRate = 0;
                $ranges     = $productData['ranges'] ?? [];

                // Individual Items type — volume scheme
if ($scheme === 'volume') {
    // Return ALL ranges so JS can pick the right one
    $rates[$compositeKey] = [
        'ranges'       => $ranges,   // ← return full ranges array
        'scheme'       => 'volume',
        'product_name' => $productData['product_name'] ?? '',
        'variant_name' => $productData['variant_name'] ?? null,
    ];
} else {
    // unit pricing — single rate
    $customRate = (float)($ranges[0]['custom_rate'] ?? 0);
    if ($customRate <= 0) continue;
    $rates[$compositeKey] = [
        'rate'         => $customRate,
        'scheme'       => 'unit',
        'product_name' => $productData['product_name'] ?? '',
    ];
}

                if ($customRate <= 0) continue;

                // ── KEY POINT: compositeKey = "5__330/3PI/26-27" format ──
                // இதை invoice JS-ல match பண்ண:
                // item.id = "5__330/3PI/26-27" (variant item)
                // item.id = 5 (normal product)

                // compositeKey-ல "__" இருந்தா variant → key as-is store பண்ணு
                // இல்லன்னா normal product id

                $rates[$compositeKey] = [
                    'rate'         => $customRate,
                    'scheme'       => $scheme,
                    'product_name' => $productData['product_name'] ?? '',
                    'variant_name' => $productData['variant_name'] ?? null,
                ];
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

private function incrementInvoiceSeriesById(?int $seriesId, string $invoiceNumber): void
{
    if (!$seriesId) return;

    $series = TransactionSeries::find($seriesId);
    if (!$series) return;

    $seriesData = $series->series_data ?? [];
    if (is_string($seriesData)) {
        $seriesData = json_decode($seriesData, true) ?? [];
    }

    // Invoice number-ல் இருந்து numeric part மட்டும் எடு
    // e.g. "INV-S-0001" → "1"
    $numericPart = preg_replace('/[^0-9]/', '', $invoiceNumber);
    $numericPart = ltrim($numericPart, '0') ?: '0';

    $updated = collect($seriesData)->map(function ($item) use ($numericPart) {
        if (($item['module'] ?? '') === 'Invoice') {
            $item['last_number'] = $numericPart;
        }
        return $item;
    })->toArray();

    $series->update(['series_data' => $updated]);
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
public function addComment(Request $request, $id): JsonResponse
{
    $invoice = Invoice::findOrFail($id);
    $request->validate(['comment' => 'required|string|max:5000']);

    // ✅ comments table-ல save பண்ணு
    $comment = \App\Models\Comment::create([
        'module'    => 'invoice',
        'record_id' => $invoice->id,
        'content'   => strip_tags($request->comment, '<b><i><u><strong><em><br><p>'),
        'user_id'   => \Illuminate\Support\Facades\Auth::id(),
        'user_name' => \Illuminate\Support\Facades\Auth::user()?->name ?? 'User',
    ]);

    // History-லயும் log பண்ணு (panel-ல காட்ட)
    \App\Models\History::create([
        'module'    => 'invoice',
        'action'    => 'comment',
        'record_id' => $invoice->id,
        'user_id'   => \Illuminate\Support\Facades\Auth::id(),
        'old_data'  => null,
        'new_data'  => ['comment' => $request->comment, 'comment_id' => $comment->id],
    ]);

    return response()->json([
        'success'  => true,
        'comment'  => $comment->toApiArray(),
    ]);
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
 
    // ── STEP 1: UserCategory பாரு (case-insensitive) ──────────
    $userCategory = \App\Models\UserCategory::whereRaw(
        'LOWER(TRIM(name)) = ?',
        [strtolower(trim($customer->customer_category))]
    )->first();
 
    if (!$userCategory) {
        return response()->json([
            'category'    => null,
            'locations'   => [],
            'series'      => [],
            'price_lists' => [],
        ]);
    }
 
    // ── STEP 2: இந்த category-க்கு எல்லா TransactionSeries எடு ──
    $allSeries = \App\Models\TransactionSeries::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get();
 
    // ── STEP 3: Series-ல இருந்து location IDs collect ──────────
    // (TransactionSeries.location_id is a JSON array of location IDs)
    $locationIds = [];
    foreach ($allSeries as $s) {
        $locIds = $s->location_id ?? [];
        if (is_string($locIds)) {
            $locIds = json_decode($locIds, true) ?? [];
        }
        foreach ((array)$locIds as $lid) {
            if ($lid) $locationIds[] = (int)$lid;
        }
    }
    $locationIds = array_values(array_unique(array_filter($locationIds)));
 
    // ── STEP 4: Location records fetch ──────────────────────────
    $locationRecords = \App\Models\Location::whereIn('id', $locationIds)
        ->whereNull('deleted_at')
        ->get();
 
    // ── STEP 5: Each location-க்கு — அதோட series list build ──
    // Location.transaction_series_id = JSON array of series IDs
    // We only include series that ALSO belong to this category
    $categorySeriesIds = $allSeries->pluck('id')->toArray();
 
    $locations = $locationRecords->map(function ($loc) use ($allSeries, $categorySeriesIds) {
 
        // இந்த location-க்கு assigned series IDs (from location table)
        $locSeriesIds = $loc->transaction_series_id ?? [];
        if (is_string($locSeriesIds)) {
            $locSeriesIds = json_decode($locSeriesIds, true) ?? [];
        }
        $locSeriesIds = array_map('intval', (array)$locSeriesIds);
 
        // Category series-ல இந்த location-க்கு belong பண்றவை மட்டும்
        $matchedSeriesIds = array_intersect($locSeriesIds, $categorySeriesIds);
 
        // Build series details for this location
        $seriesForLoc = $allSeries
            ->whereIn('id', $matchedSeriesIds)
            ->map(function ($s) {
                $seriesData = $s->series_data;
                if (is_string($seriesData)) {
                    $seriesData = json_decode($seriesData, true) ?? [];
                }
 
                // Invoice module-க்கு series data எடு
                $invoiceSeries = collect($seriesData)->firstWhere('module', 'Invoice');
 
                $prefix     = $invoiceSeries['prefix']      ?? 'INV-';
                $start      = $invoiceSeries['start']       ?? '000001';
                $lastNumber = $invoiceSeries['last_number'] ?? null;
 
                // Next invoice number preview
                if ($lastNumber !== null) {
                    $next    = (int)$lastNumber + 1;
                    $preview = $prefix . str_pad($next, strlen((string)$start), '0', STR_PAD_LEFT);
                } else {
                    $preview = $prefix . $start;
                }
 
                return [
                    'id'          => $s->id,
                    'name'        => $s->name,
                    'prefix'      => $prefix,
                    'start'       => $start,
                    'last_number' => $lastNumber,
                    'preview'     => $preview,   // e.g. "INV-000007"
                ];
            })
            ->values()
            ->toArray();
 
        return [
            'id'                    => $loc->id,
            'location_name'         => $loc->location_name,
            'location_type'         => $loc->location_type,   // 'business' | 'warehouse'
            'default_series_id'     => $loc->default_series_id,
            'series'                => $seriesForLoc,          // ← series FOR THIS location
        ];
    })->values()->toArray();
 
    // ── STEP 6: Price Lists for this category ──────────────────
    $priceLists = \App\Models\PriceList::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get(['id', 'name', 'price_list_type', 'markup_type', 'percentage'])
        ->toArray();
 
    // ── STEP 7: Default selections ─────────────────────────────
    // First location, its default/first series
    $defaultLocationId = $locations[0]['id'] ?? null;
    $defaultSeriesId   = null;
 
    if ($defaultLocationId) {
        $firstLoc        = $locations[0];
        $defaultSeriesId = $firstLoc['default_series_id']
                        ?? ($firstLoc['series'][0]['id'] ?? null);
    }
 
    return response()->json([
        'category' => [
            'id'   => $userCategory->id,
            'name' => $userCategory->name,
        ],
        'locations'          => $locations,      // each has ->series[]
        'price_lists'        => $priceLists,
        'default_location_id'=> $defaultLocationId,
        'default_series_id'  => $defaultSeriesId,
    ]);
}
// ── Store Payment ─────────────────────────────────────────────
public function showPaymentForm($id)
{
    $invoice   = Invoice::with('customer')->findOrFail($id);
    $locations = \App\Models\Location::whereNull('deleted_at')->get();
 
    // Auto-generate next payment number from payments_record table
    $lastPayNo = \DB::table('payments_record')->orderByDesc('id')->value('payment_no');
    $nextNum   = $lastPayNo
        ? (int) preg_replace('/[^0-9]/', '', $lastPayNo) + 1
        : 1;
    $paymentNumber = 'PAY-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    $balanceDue = max(0, floatval($invoice->balance_due ?? $invoice->grand_total));
    return view('invoices.payment', compact('invoice', 'locations', 'paymentNumber', 'balanceDue'));
}
 
// ── Store Payment ─────────────────────────────────────────────────────────────
public function storePayment(Request $request, $id)
{
    $invoice = Invoice::with('items')->findOrFail($id);
 
    $request->validate([
        'amount_received' => 'required|numeric|min:0.01',
        'payment_date'    => 'required|date',
        'payment_mode'    => 'required|string',
        'deposit_to'      => 'required|string',
    ]);
 
    \DB::beginTransaction();
    try {
        $amountReceived = floatval($request->amount_received);
        $grandTotal     = floatval($invoice->grand_total);
 
        // How much was already paid against this invoice
        $currentBalanceDue = floatval($invoice->balance_due ?? $invoice->grand_total);
$balanceDue        = max(0, round($currentBalanceDue - $amountReceived, 2));
$totalPaid         = floatval($invoice->amount_received ?? 0) + $amountReceived;

 // paid concept
       $isFullyPaid     = $balanceDue < 0.01;
$payStatus       = $isFullyPaid ? 'paid' : 'partial';
$wasNotFullyPaid = in_array($invoice->payment_status, ['unpaid', 'partial']); 

        // ── STEP 1: Insert into payments_record (shows in Payments Received index) ──
       $paymentId = \DB::table('payments_record')->insertGetId([
    'customer_id'     => $invoice->customer_id,
    'invoice_id'      => $invoice->id,          // ← already column இருக்கு, use பண்ணுங்க
    'payment_no'      => $request->payment_number ?? ('PAY-' . str_pad(
                            \DB::table('payments_record')->count() + 1, 6, '0', STR_PAD_LEFT
                         )),
    'payment_date'    => $request->payment_date,
    'amount_received' => $amountReceived,
    'bank_charges'    => floatval($request->bank_charges ?? 0),
    'payment_mode'    => $request->payment_mode,
    'deposit_to'      => $request->deposit_to,
    'reference_no'    => $request->reference ?: null,
    'notes'           => $request->notes ?: null,
    'status'          => $request->action === 'draft' ? 'draft' : 'paid',
    'created_at'      => now(),
    'updated_at'      => now(),
]);
 
 
        // ── STEP 3: Update invoice payment status ──────────────────────────────────
        $invoice->update([
            'amount_received' => $totalPaid,
            'balance_due'     => $balanceDue,
            'payment_status'  => $payStatus,
            'payment_mode'    => $request->payment_mode,
            'deposit_to'      => $request->deposit_to,
        ]);
 
        // ── STEP 4: Stock update — committed → stock_on_hand when fully paid ───────
if ($isFullyPaid && $wasNotFullyPaid) {
    // ✅ warehouse_location இல்லன்னா location use பண்ணு
    $warehouseLocationId = $invoice->warehouse_location 
                        ?? $invoice->location 
                        ?? null;
    
    // ✅ location integer-ஆ இருக்கா check பண்ணு
    if (!$warehouseLocationId) {
        \Log::warning('No warehouse location for invoice', ['id' => $invoice->id]);
    } else {
        foreach ($invoice->items as $item) {
            if (!$item->product_id) continue;
            $qty = floatval($item->quantity);

            $stock = \App\Models\ItemStock::where('item_id', $item->product_id)
                ->where('location_id', (int)$warehouseLocationId)
                ->whereNull('deleted_at')
                ->first();

            \Log::info('Stock found', [
                'product_id'  => $item->product_id,
                'location_id' => $warehouseLocationId,
                'stock'       => $stock?->toArray(),
            ]);

            if ($stock) {
                $newStockOnHand = max(0, $stock->stock_on_hand - $qty);
                $newCommitted   = max(0, $stock->committed_stock - $qty);

                $stock->update([
                    'stock_on_hand'   => $newStockOnHand,
                    'committed_stock' => $newCommitted,
                ]);

                \App\Models\ItemStockLedger::create([
                    'item_id'             => $item->product_id,
                    'location_id'         => (int)$warehouseLocationId,
                    'variant_id'          => null,
                    'transaction_type'    => 'sale',
                    'transaction_date'    => $request->payment_date,
                    'reference_type'      => 'payment',
                    'reference_id'        => $paymentId,
                    'qty_change'          => -$qty,
                    'committed_change'    => -$qty,
                    'unit_value'          => $stock->value_per_unit,
                    'stock_on_hand_after' => $newStockOnHand,
                    'committed_after'     => $newCommitted,
                    'available_after'     => $stock->available_for_sale,
                    'notes'               => 'Full payment received — ' . $invoice->invoice_number,
                    'created_by'          => auth()->id(),
                ]);

                $totalStock = \App\Models\ItemStock::where('item_id', $item->product_id)
                                ->whereNull('deleted_at')
                                ->sum('stock_on_hand');
                \App\Models\Product::withoutEvents(function () use ($item, $totalStock) {
                    \App\Models\Product::where('id', $item->product_id)
                        ->update(['opening_stock' => $totalStock]);
                });
            }
        }
    }
}
 
        // ── STEP 5: History log ────────────────────────────────────────────────────
        \App\Models\History::create([
            'module'    => 'invoice',
            'action'    => 'payment',
            'record_id' => $invoice->id,
            'user_id'   => auth()->id(),
            'old_data'  => null,
            'new_data'  => [
                'amount'       => $amountReceived,
                'payment_no'   => $request->payment_number,
                'payment_mode' => $request->payment_mode,
                'status'       => $payStatus,
                'payment_id'   => $paymentId,
            ],
        ]);
 
        // storePayment() கடைசியில்
            \DB::commit();

            // AJAX request-ஆ check பண்ணு
            if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment of ₹' . number_format($amountReceived, 2) . ' recorded successfully!',
                ]);
            }

            return redirect()
                ->route('invoices.show', $invoice->id)
                ->with('success', 'Payment recorded!');
               } catch (\Exception $e) {
    \DB::rollBack();
    if ($request->ajax() || $request->hasHeader('X-Requested-With')) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
    return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
               }
}

}
