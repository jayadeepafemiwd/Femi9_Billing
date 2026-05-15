<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Location;
use App\Models\TransactionSeries;
use App\Models\Invoice;          // ← NEW
use App\Models\ItemStock;        // ← NEW
use App\Models\ItemStockLedger;  // ← NEW
use App\Models\Product;          // ← NEW
use App\Models\History;          // ← NEW

class PaymentsRecordController extends Controller
{
    public function index(Request $request)
    {
       $query = DB::table('payments_record as p')
    ->leftJoin('customers as c', 'c.id', '=', 'p.customer_id')
    ->leftJoin('invoices as inv', 'inv.id', '=', 'p.invoice_id')
    ->select(
        'p.id', 
        'p.customer_id','p.payment_no', 'p.payment_date',
        'p.amount_received', 'p.payment_mode',
        'p.status',
        'c.display_name as customer_name',
        'c.user_code    as customer_code',
        'inv.invoice_number'
    );

        // Filters
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('p.payment_no',     'like', "%{$search}%")
                  ->orWhere('c.display_name', 'like', "%{$search}%")
                  ->orWhere('c.name',         'like', "%{$search}%")
                  ->orWhere('c.user_code',    'like', "%{$search}%");
            });
        }
        if ($status = $request->get('status')) {
            $query->where('p.status', $status);
        }
        if ($mode = $request->get('mode')) {
            $query->where('p.payment_mode', $mode);
        }
        if ($from = $request->get('date_from')) {
            $query->where('p.payment_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->where('p.payment_date', '<=', $to);
        }

        $payments = $query->orderByDesc('p.payment_date')
                          ->orderByDesc('p.id')
                          ->paginate(20)
                          ->withQueryString();

        // Stats
        $stats = [
            'total_count'  => DB::table('payments_record')->count(),
            'total_amount' => DB::table('payments_record')->sum('amount_received'),
            'this_month'   => DB::table('payments_record')
                                ->whereYear('payment_date',  now()->year)
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount_received'),
            'draft_count'  => DB::table('payments_record')->where('status', 'draft')->count(),
        ];

        return view('payments_records.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $customers = Customer::whereNull('deleted_at')->get();
        $locations = Location::whereNull('deleted_at')->get();
        return view('payments_records.create', compact('customers', 'locations'));
    }

    // GET /payments-records/customers?search=xx
    public function getCustomers(Request $request)
    {
        $search = $request->get('search', '');
        $query  = DB::table('customers')
            ->select('id', 'name', 'display_name', 'company_name', 'email', 'phone_number', 'user_code');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name',         'like', "%{$search}%")
                  ->orWhere('display_name','like', "%{$search}%")
                  ->orWhere('user_code',   'like', "%{$search}%");
            });
        }
        return response()->json(['success' => true, 'data' => $query->orderBy('name')->limit(50)->get()]);
    }

    // ══════════════════════════════════════════════════════════════
    // GET /payments-records/customer-defaults?customer_id=xx
    //
    // InvoiceController::getCustomerDefaults() போல் EXACT same logic.
    // Category → Locations → Series (Payment module) → Payment #
    // ══════════════════════════════════════════════════════════════
    public function getCustomerDefaults(Request $request): JsonResponse
    {
        $customerId = $request->integer('customer_id');
        $customer   = Customer::find($customerId);

        if (!$customer || !$customer->customer_category) {
            return response()->json([
                'category'  => null,
                'locations' => [],
                'series'    => [],
            ]);
        }

        // STEP 1: UserCategory — case-insensitive match
        $userCategory = \App\Models\UserCategory::whereRaw(
            'LOWER(TRIM(name)) = ?',
            [strtolower(trim($customer->customer_category))]
        )->first();

        if (!$userCategory) {
            return response()->json([
                'category'  => null,
                'locations' => [],
                'series'    => [],
            ]);
        }

        // STEP 2: இந்த category-க்கு எல்லா TransactionSeries
        $allSeries = TransactionSeries::where('category_id', $userCategory->id)
            ->whereNull('deleted_at')
            ->get();

        // STEP 3: Series-ல் இருந்து location IDs collect
        $locationIds = [];
        foreach ($allSeries as $s) {
            $locIds = $s->location_id ?? [];
            if (is_string($locIds)) $locIds = json_decode($locIds, true) ?? [];
            foreach ((array) $locIds as $lid) {
                if ($lid) $locationIds[] = (int) $lid;
            }
        }
        $locationIds = array_values(array_unique(array_filter($locationIds)));

        // STEP 4: Location records
        $locationRecords = Location::whereIn('id', $locationIds)
            ->whereNull('deleted_at')
            ->get();

        $categorySeriesIds = $allSeries->pluck('id')->toArray();

        
        // STEP 5: Each location → series list build
        $locations = $locationRecords->map(function ($loc) use ($allSeries, $categorySeriesIds) {
            $locSeriesIds = $loc->transaction_series_id ?? [];
            if (is_string($locSeriesIds)) $locSeriesIds = json_decode($locSeriesIds, true) ?? [];
            $locSeriesIds     = array_map('intval', (array) $locSeriesIds);
            $matchedSeriesIds = array_intersect($locSeriesIds, $categorySeriesIds);

            $seriesForLoc = $allSeries
                ->whereIn('id', $matchedSeriesIds)
                ->map(function ($s) {
                    $seriesData = $s->series_data;
                    if (is_string($seriesData)) $seriesData = json_decode($seriesData, true) ?? [];

                    // Payment module series — Payment இல்லன்னா Invoice fallback
                    $paymentSeries = collect($seriesData)->firstWhere('module', 'Payment')
                                  ?? collect($seriesData)->firstWhere('module', 'Invoice');

                    $prefix     = $paymentSeries['prefix']      ?? 'PAY-';
                    $start      = $paymentSeries['start']       ?? '000001';
                    $lastNumber = $paymentSeries['last_number'] ?? null;

                    $next    = $lastNumber !== null ? (int)$lastNumber + 1 : (int)$start;
                    $preview = $prefix . str_pad($next, strlen((string)$start), '0', STR_PAD_LEFT);

                    return [
                        'id'          => $s->id,
                        'name'        => $s->name,
                        'prefix'      => $prefix,
                        'start'       => $start,
                        'last_number' => $lastNumber,
                        'preview'     => $preview,
                    ];
                })
                ->values()
                ->toArray();

            return [
                'id'               => $loc->id,
                'location_name'    => $loc->location_name,
                'location_type'    => $loc->location_type,
                'default_series_id'=> $loc->default_series_id,
                'series'           => $seriesForLoc,
            ];
        })->values()->toArray();

        // STEP 6: Default series for payment # preview
        // Flat series list (blade JS க்கு simple format)
        $flatSeries = $allSeries->map(function ($s) {
            $seriesData = $s->series_data;
            if (is_string($seriesData)) $seriesData = json_decode($seriesData, true) ?? [];

            $paymentSeries = collect($seriesData)->firstWhere('module', 'Payment')
                          ?? collect($seriesData)->firstWhere('module', 'Invoice');

            $locIds = $s->location_id ?? [];
            if (is_string($locIds)) $locIds = json_decode($locIds, true) ?? [];

            return [
                'id'           => $s->id,
                'name'         => $s->name,
                'location_ids' => array_map('intval', (array) $locIds),
                'prefix'       => $paymentSeries['prefix']      ?? 'PAY-',
                'start'        => $paymentSeries['start']       ?? '000001',
                'last_number'  => $paymentSeries['last_number'] ?? null,
            ];
        })->values()->toArray();


        // ── Invoice Setting → advance_payment_categories check ──
$invoiceSetting = \App\Models\SettingHandle::where('category_name', 'invoice')->first();
$invoiceConfig  = $invoiceSetting ? ($invoiceSetting->config ?? []) : [];

$advanceEnabled    = $invoiceConfig['advance_payment_enabled'] ?? false;
$allowedCatIds     = array_map('intval', $invoiceConfig['advance_payment_categories'] ?? []);
$showAdvancePayment = $advanceEnabled && in_array((int)$userCategory->id, $allowedCatIds);

   return response()->json([
    'category' => [
        'id'   => $userCategory->id,
        'name' => $userCategory->name,
    ],
    'locations'             => $locations,
    'series'                => $flatSeries,
    'default_location_id'   => $locations[0]['id'] ?? null,
    'show_advance_payment'  => $showAdvancePayment,  // ✅ NEW
    ]);   
    }

    // GET /payments-records/invoices?customer_id=xx
    public function getInvoices(Request $request)
    {
        $customerId = $request->get('customer_id');
        if (!$customerId) {
            return response()->json(['success' => false, 'error' => 'customer_id required']);
        }

        $invoices = DB::table('invoices')
            ->select('id', 'invoice_number', 'invoice_date', 'due_date',
                     'grand_total as total_amount', 'balance_due as amount_due',
                     'status', 'payment_status', 'location')
            ->where('customer_id', $customerId)
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('invoice_date')
            ->get();

        $count = DB::table('payments_record')->count();

        return response()->json([
            'success'         => true,
            'invoices'        => $invoices,
            'next_payment_no' => 'PAY-' . str_pad($count + 1, 6, '0', STR_PAD_LEFT),
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'customer_id'     => 'required|integer|exists:customers,id',
        'amount_received' => 'required|numeric|min:0.01',
        'payment_date'    => 'required|date',
        'payment_no'      => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        $isAdvance             = (bool) $request->get('is_advance_payment', false);
        $applyToInvoices       = (bool) $request->get('apply_credit_to_invoices', false);
        $creditInvoicePayments = $request->get('credit_invoice_payments', []);

        $paymentId = DB::table('payments_record')->insertGetId([
            'customer_id'        => $request->customer_id,
            'invoice_id'         => $isAdvance ? null : ($request->invoice_payments[0]['invoice_id'] ?? null),
            'payment_no'         => $request->payment_no,
            'payment_date'       => $request->payment_date,
            'amount_received'    => $request->amount_received,
            'bank_charges'       => $request->bank_charges ?? 0,
            'payment_mode'       => $request->payment_mode,
            'deposit_to'         => $request->deposit_to,
            'reference_no'       => $request->reference,
            'notes'              => $request->notes,
            'status'             => $request->status ?? 'paid',
            'is_advance_payment' => $isAdvance ? 1 : 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        if ($isAdvance) {
            // ── எவ்வளவு invoice-க்கு apply ஆகப்போகுதுன்னு முன்னே calculate ──
            $totalCreditApplied = 0;
            if ($applyToInvoices && !empty($creditInvoicePayments)) {
                foreach ($creditInvoicePayments as $cip) {
                    $totalCreditApplied += floatval($cip['amount'] ?? 0);
                }
            }

            // ── Net unused credit = received - applied ──
            $netCredit = floatval($request->amount_received) - $totalCreditApplied;
            if ($netCredit > 0.005) {
                DB::table('customers')
                    ->where('id', $request->customer_id)
                    ->increment('unused_credits', $netCredit);
            }

        } else {
            $totalApplied = 0;
            foreach ($request->invoice_payments ?? [] as $ip) {
                $invId = intval($ip['invoice_id']);
                $amt   = floatval($ip['amount']);
                if (!$invId || $amt <= 0) continue;

                $invoice = DB::table('invoices')->where('id', $invId)->first();
                if (!$invoice) continue;

                $newBalance = max(0, $invoice->balance_due - $amt);
                DB::table('invoices')->where('id', $invId)->update([
                    'balance_due'     => $newBalance,
                    'amount_received' => $invoice->amount_received + $amt,
                    'payment_status'  => $newBalance <= 0 ? 'paid' : 'partial',
                    'updated_at'      => now(),
                ]);
                $totalApplied += $amt;
            }

            $amountReceived = floatval($request->amount_received);
            $excess = $amountReceived - $totalApplied;
            if ($excess > 0.005) {
                DB::table('customers')
                    ->where('id', $request->customer_id)
                    ->increment('unused_credits', $excess);
            }
        }

        // ── Advance → invoice apply ──
        if ($isAdvance && $applyToInvoices && !empty($creditInvoicePayments)) {
            foreach ($creditInvoicePayments as $cip) {
                $cipInvId = intval($cip['invoice_id'] ?? 0);
                $cipAmt   = floatval($cip['amount'] ?? 0);
                if (!$cipInvId || $cipAmt <= 0.005) continue;

                $cipInvoice = DB::table('invoices')->where('id', $cipInvId)->first();
                if (!$cipInvoice) continue;

                // ── NO decrement here — already accounted above ──

                $cipNewBalance = max(0, floatval($cipInvoice->balance_due) - $cipAmt);
                $cipPayStatus  = $cipNewBalance < 0.01 ? 'paid' : 'partial';
                $cipWasPending = in_array($cipInvoice->payment_status, ['unpaid', 'partial']);

                DB::table('invoices')->where('id', $cipInvId)->update([
                    'balance_due'     => $cipNewBalance,
                    'amount_received' => DB::raw("amount_received + {$cipAmt}"),
                    'payment_status'  => $cipPayStatus,
                    'updated_at'      => now(),
                ]);

                $cipPayCount = DB::table('payments_record')->count();
                DB::table('payments_record')->insert([
                    'customer_id'        => $request->customer_id,
                    'invoice_id'         => $cipInvId,
                    'payment_no'         => 'CRED-' . str_pad($cipPayCount + 1, 6, '0', STR_PAD_LEFT),
                    'payment_date'       => $request->payment_date,
                    'amount_received'    => $cipAmt,
                    'payment_mode'       => 'credit_applied',
                    'notes'              => "Credit applied from advance payment #{$request->payment_no}",
                    'status'             => 'paid',
                    'is_advance_payment' => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                // Stock update
                if ($cipPayStatus === 'paid' && $cipWasPending) {
                    $warehouseId = $cipInvoice->warehouse_location ?? $cipInvoice->location ?? null;
                    if ($warehouseId) {
                        $cipItems = DB::table('invoice_items')->where('invoice_id', $cipInvId)->get();
                        foreach ($cipItems as $cipItem) {
                            if (!$cipItem->product_id) continue;
                            $cipQty = floatval($cipItem->quantity);

                            $cipStock = \App\Models\ItemStock::where('item_id', $cipItem->product_id)
                                ->where('location_id', (int)$warehouseId)
                                ->whereNull('deleted_at')->first();
                            if (!$cipStock) continue;

                            $newSOH       = max(0, $cipStock->stock_on_hand - $cipQty);
                            $newCommitted = max(0, $cipStock->committed_stock - $cipQty);

                            $cipStock->update([
                                'stock_on_hand'   => $newSOH,
                                'committed_stock' => $newCommitted,
                            ]);

                            \App\Models\ItemStockLedger::create([
                                'item_id'             => $cipItem->product_id,
                                'location_id'         => (int)$warehouseId,
                                'variant_id'          => null,
                                'transaction_type'    => 'sale',
                                'transaction_date'    => $request->payment_date,
                                'reference_type'      => 'credit_payment',
                                'reference_id'        => $paymentId,
                                'qty_change'          => -$cipQty,
                                'committed_change'    => -$cipQty,
                                'unit_value'          => $cipStock->value_per_unit,
                                'stock_on_hand_after' => $newSOH,
                                'committed_after'     => $newCommitted,
                                'available_after'     => $cipStock->available_for_sale,
                                'notes'               => "Credit from advance — {$cipInvoice->invoice_number}",
                                'created_by'          => auth()->id(),
                            ]);

                            $totalSt = \App\Models\ItemStock::where('item_id', $cipItem->product_id)
                                ->whereNull('deleted_at')->sum('stock_on_hand');
                            \App\Models\Product::withoutEvents(fn() =>
                                \App\Models\Product::where('id', $cipItem->product_id)
                                    ->update(['opening_stock' => $totalSt])
                            );
                        }
                    }
                }
            }
        }

        DB::commit();
        return response()->json(['success' => true, 'payment_id' => $paymentId]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
    // Payment series last_number update
    private function incrementPaymentSeries(int $seriesId, string $paymentNo): void
    {
        $series = TransactionSeries::find($seriesId);
        if (!$series) return;

        $seriesData  = $series->series_data;
        if (is_string($seriesData)) $seriesData = json_decode($seriesData, true) ?? [];

        $numericPart = preg_replace('/[^0-9]/', '', $paymentNo);
        $numericPart = ltrim($numericPart, '0') ?: '0';

        $updated = collect($seriesData)->map(function ($item) use ($numericPart) {
            if (in_array($item['module'] ?? '', ['Payment', 'Invoice'])) {
                $item['last_number'] = $numericPart;
            }
            return $item;
        })->toArray();

        $series->update(['series_data' => $updated]);
    }
     public function getCustomerCredit(Request $request): JsonResponse
{
    $customerId = $request->integer('customer_id');
    if (!$customerId) {
        return response()->json(['success' => false, 'message' => 'customer_id required'], 422);
    }

    $customer = DB::table('customers')
        ->select('id', 'name', 'display_name', 'user_code', 'unused_credits')
        ->where('id', $customerId)
        ->first();

    if (!$customer) {
        return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
    }

    // Total outstanding = sum of all unpaid/partial invoice balance_due
    $totalOutstanding = DB::table('invoices')
        ->where('customer_id', $customerId)
        ->whereIn('payment_status', ['unpaid', 'partial'])
        ->where('balance_due', '>', 0)
        ->sum('balance_due');

    $invoices = DB::table('invoices')
        ->select('id', 'invoice_number', 'invoice_date', 'due_date',
                 'grand_total as total_amount', 'balance_due as amount_due', 'payment_status')
        ->where('customer_id', $customerId)
        ->whereIn('payment_status', ['unpaid', 'partial'])
        ->where('balance_due', '>', 0)
        ->orderBy('invoice_date')
        ->get();

    return response()->json([
        'success'          => true,
        'customer_id'      => $customer->id,
        'customer_name'    => $customer->display_name ?? $customer->name,
        'unused_credits'   => floatval($customer->unused_credits ?? 0),
        'total_outstanding'=> floatval($totalOutstanding),  // ← NEW
        'invoices'         => $invoices,
    ]);
}
public function applyCredit(Request $request): JsonResponse
{
    $request->validate([
        'customer_id' => 'required|integer|exists:customers,id',
        'invoice_id'  => 'required|integer|exists:invoices,id',
        'amount'      => 'required|numeric|min:0.01',
    ]);

    $customerId = $request->integer('customer_id');
    $invoiceId  = $request->integer('invoice_id');
    $amount     = floatval($request->amount);

    DB::beginTransaction();
    try {
        // 1. Customer credit check
        $customer = DB::table('customers')
            ->where('id', $customerId)
            ->lockForUpdate()
            ->first();

        $availableCredit = floatval($customer->unused_credits ?? 0);
        if ($amount > $availableCredit) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Available credit ₹{$availableCredit} மட்டுமே உள்ளது",
            ], 422);
        }

        // 2. Invoice check
        $invoice = DB::table('invoices')
            ->where('id', $invoiceId)
            ->where('customer_id', $customerId)
            ->lockForUpdate()
            ->first();

        if (!$invoice) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }

        $balanceDue = floatval($invoice->balance_due);
        if ($amount > $balanceDue) {
            $amount = $balanceDue; // Cap at balance due
        }

        // 3. Deduct customer credit
        DB::table('customers')
            ->where('id', $customerId)
            ->decrement('unused_credits', $amount);

        // 4. Update invoice balance
        $newBalance       = max(0, $balanceDue - $amount);
        $newPaymentStatus = $newBalance < 0.01 ? 'paid' : 'partial';
        $wasNotFullyPaid  = in_array($invoice->payment_status, ['unpaid', 'partial']);
        $isNowFullyPaid   = $newPaymentStatus === 'paid';

        DB::table('invoices')
            ->where('id', $invoiceId)
            ->update([
                'balance_due'     => $newBalance,
                'amount_received' => DB::raw("amount_received + {$amount}"),
                'payment_status'  => $newPaymentStatus,
                'updated_at'      => now(),
            ]);

        // 5. Payment record
        $payCount = DB::table('payments_record')->count();
        $payNo    = 'CRED-' . str_pad($payCount + 1, 6, '0', STR_PAD_LEFT);

        $paymentId = DB::table('payments_record')->insertGetId([
            'customer_id'        => $customerId,
            'invoice_id'         => $invoiceId,
            'payment_no'         => $payNo,
            'payment_date'       => now()->toDateString(),
            'amount_received'    => $amount,
            'payment_mode'       => 'credit_applied',
            'notes'              => "Credit applied to invoice #{$invoice->invoice_number}",
            'status'             => 'paid',
            'is_advance_payment' => 0,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // ── 6. STOCK UPDATE — credit apply + invoice now fully paid ──────────────
        if ($isNowFullyPaid && $wasNotFullyPaid) {
            $warehouseLocationId = $invoice->warehouse_location ?? $invoice->location ?? null;

            if ($warehouseLocationId) {
                $items = DB::table('invoice_items')
                    ->where('invoice_id', $invoiceId)
                    ->get();

                foreach ($items as $item) {
                    if (!$item->product_id) continue;
                    $qty = floatval($item->quantity);

                    $stock = \App\Models\ItemStock::where('item_id', $item->product_id)
                        ->where('location_id', (int)$warehouseLocationId)
                        ->whereNull('deleted_at')
                        ->first();

                    if (!$stock) continue;

                    // committed → stock_on_hand குறைக்க
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
                        'transaction_date'    => now()->toDateString(),
                        'reference_type'      => 'credit_payment',
                        'reference_id'        => $paymentId,
                        'qty_change'          => -$qty,
                        'committed_change'    => -$qty,
                        'unit_value'          => $stock->value_per_unit,
                        'stock_on_hand_after' => $newStockOnHand,
                        'committed_after'     => $newCommitted,
                        'available_after'     => $stock->available_for_sale,
                        'notes'               => "Credit applied — {$invoice->invoice_number}",
                        'created_by'          => auth()->id(),
                    ]);

                    // Product overall stock sync
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

        DB::commit();

        return response()->json([
            'success'          => true,
            'amount_applied'   => $amount,
            'new_balance_due'  => $newBalance,
            'payment_status'   => $newPaymentStatus,
            'remaining_credit' => max(0, $availableCredit - $amount),
            'payment_no'       => $payNo,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('[applyCredit] Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
// ═══════════════════════════════════════════════════════════════════════════════
// InvoiceController.php-ல் ADD பண்ணுங்க (பழைய methods replace பண்ணுங்க)
// ═══════════════════════════════════════════════════════════════════════════════

// ── EDIT PAYMENT ──────────────────────────────────────────────────────────────
public function editPayment(Request $request, $invoiceId, $paymentId): JsonResponse
{
    $payment = DB::table('payments_record')
        ->where('id', $paymentId)
        ->where('invoice_id', $invoiceId)
        ->first();

    if (!$payment) {
        return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
    }

    return response()->json(['success' => true, 'payment' => $payment]);
}

// ── UPDATE PAYMENT ────────────────────────────────────────────────────────────
public function updatePayment(Request $request, $invoiceId, $paymentId): JsonResponse
{
    $request->validate([
        'amount_received' => 'required|numeric|min:0.01',
        'payment_date'    => 'required|date',
        'payment_mode'    => 'required|string',
        'deposit_to'      => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        $invoice = Invoice::findOrFail($invoiceId);

        $payment = DB::table('payments_record')
            ->where('id', $paymentId)
            ->where('invoice_id', $invoiceId)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        // Update the payment record
        DB::table('payments_record')->where('id', $paymentId)->update([
            'amount_received' => floatval($request->amount_received),
            'payment_date'    => $request->payment_date,
            'payment_mode'    => $request->payment_mode,
            'deposit_to'      => $request->deposit_to,
            'reference_no'    => $request->reference_no,
            'notes'           => $request->notes,
            'updated_at'      => now(),
        ]);

        // Recalculate from all active (non-refunded) payments
        $totalPaid = DB::table('payments_record')
            ->where('invoice_id', $invoiceId)
            ->whereNotIn('status', ['draft', 'refunded'])
            ->sum('amount_received');

        $grandTotal = floatval($invoice->grand_total);
        $balanceDue = max(0, round($grandTotal - $totalPaid, 2));
        $payStatus  = $balanceDue < 0.01 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'amount_received' => $totalPaid,
            'balance_due'     => $balanceDue,
            'payment_status'  => $payStatus,
            'payment_mode'    => $request->payment_mode,
            'deposit_to'      => $request->deposit_to,
        ]);

        \App\Models\History::create([
            'module'    => 'invoice',
            'action'    => 'update',
            'record_id' => $invoiceId,
            'user_id'   => auth()->id(),
            'old_data'  => ['payment_id' => $paymentId, 'old_amount' => $payment->amount_received],
            'new_data'  => ['payment_id' => $paymentId, 'new_amount' => $request->amount_received],
        ]);

        DB::commit();

        return response()->json([
            'success'        => true,
            'message'        => 'Payment updated successfully!',
            'new_balance'    => $balanceDue,
            'payment_status' => $payStatus,
            'total_paid'     => $totalPaid,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

// ── DELETE PAYMENT ─────────────────────────────────────────────────────────────
// $request->action = 'delete' | 'dissociate_credit'
public function deletePayment(Request $request, $invoiceId, $paymentId): JsonResponse
{
    $action = $request->input('action', 'delete');

    DB::beginTransaction();
    try {
        $invoice = Invoice::with('items')->findOrFail($invoiceId);

        $payment = DB::table('payments_record')
            ->where('id', $paymentId)
            ->where('invoice_id', $invoiceId)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        $amount              = floatval($payment->amount_received);
        $wasFullyPaid        = $invoice->payment_status === 'paid';
        $warehouseLocationId = $invoice->warehouse_location ?? $invoice->location ?? null;

        // ── STEP 1: Stock reversal ─────────────────────────────────────────────
        if ($wasFullyPaid && $warehouseLocationId) {
            foreach ($invoice->items as $item) {
                if (!$item->product_id) continue;
                $qty = floatval($item->quantity);

                $stock = \App\Models\ItemStock::where('item_id', $item->product_id)
                    ->where('location_id', (int)$warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$stock) continue;

                // Was fully paid → stock_on_hand was deducted → restore it back
                // Invoice still exists → move qty to committed
                $newStockOnHand = $stock->stock_on_hand + $qty;
                $newCommitted   = $stock->committed_stock + $qty;

                $stock->update([
                    'stock_on_hand'   => $newStockOnHand,
                    'committed_stock' => $newCommitted,
                ]);

                \App\Models\ItemStockLedger::create([
                    'item_id'             => $item->product_id,
                    'location_id'         => (int)$warehouseLocationId,
                    'variant_id'          => null,
                    'transaction_type'    => 'reversal',
                    'transaction_date'    => now()->toDateString(),
                    'reference_type'      => 'payment_delete',
                    'reference_id'        => $paymentId,
                    'qty_change'          => +$qty,
                    'committed_change'    => +$qty,
                    'unit_value'          => $stock->value_per_unit,
                    'stock_on_hand_after' => $newStockOnHand,
                    'committed_after'     => $newCommitted,
                    'available_after'     => $stock->available_for_sale,
                    'notes'               => 'Payment deleted — stock reversed: ' . $invoice->invoice_number,
                    'created_by'          => auth()->id(),
                ]);

                $totalStock = \App\Models\ItemStock::where('item_id', $item->product_id)
                    ->whereNull('deleted_at')->sum('stock_on_hand');
                \App\Models\Product::withoutEvents(function () use ($item, $totalStock) {
                    \App\Models\Product::where('id', $item->product_id)
                        ->update(['opening_stock' => $totalStock]);
                });
            }
        }

        // ── STEP 2: Delete or Dissociate ────────────────────────────────────────
        if ($action === 'dissociate_credit') {
            DB::table('payments_record')->where('id', $paymentId)->update([
                'invoice_id'         => null,
                'is_advance_payment' => 1,
                'notes'              => 'Dissociated from ' . $invoice->invoice_number . ' — added as credit',
                'updated_at'         => now(),
            ]);

            // Add to customer unused_credits
            DB::table('customers')
                ->where('id', $invoice->customer_id)
                ->increment('unused_credits', $amount);

        } else {
            // Hard delete
            DB::table('payments_record')->where('id', $paymentId)->delete();
        }

        // ── STEP 3: Recalculate invoice balance ────────────────────────────────
        $totalPaid = DB::table('payments_record')
            ->where('invoice_id', $invoiceId)
            ->whereNotIn('status', ['draft', 'refunded'])
            ->sum('amount_received');

        $grandTotal = floatval($invoice->grand_total);
        $balanceDue = max(0, round($grandTotal - $totalPaid, 2));
        $payStatus  = $balanceDue < 0.01 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'amount_received' => $totalPaid,
            'balance_due'     => $balanceDue,
            'payment_status'  => $payStatus,
        ]);

        \App\Models\History::create([
            'module'    => 'invoice',
            'action'    => 'update',
            'record_id' => $invoiceId,
            'user_id'   => auth()->id(),
            'old_data'  => null,
            'new_data'  => [
                'action'         => $action,
                'payment_id'     => $paymentId,
                'amount'         => $amount,
                'new_balance'    => $balanceDue,
                'payment_status' => $payStatus,
            ],
        ]);

        DB::commit();

        $msg = $action === 'dissociate_credit'
            ? '₹' . number_format($amount, 2) . ' dissociated and added to customer credit.'
            : 'Payment of ₹' . number_format($amount, 2) . ' deleted permanently.';

        return response()->json([
            'success'        => true,
            'message'        => $msg,
            'new_balance'    => $balanceDue,
            'payment_status' => $payStatus,
            'total_paid'     => $totalPaid,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

// ── REFUND PAYMENT ─────────────────────────────────────────────────────────────
// No separate table — payments_record.status = 'refunded'
// Invoice balance increases back
// Stock reversed
public function refundPayment(Request $request, $invoiceId, $paymentId): JsonResponse
{
    $request->validate([
        'refunded_on'  => 'required|date',
        'payment_mode' => 'required|string',
        'from_account' => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        $invoice = Invoice::with('items')->findOrFail($invoiceId);

        $payment = DB::table('payments_record')
            ->where('id', $paymentId)
            ->where('invoice_id', $invoiceId)
            ->whereNotIn('status', ['refunded', 'draft'])
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found or already refunded'], 404);
        }

        $refundAmount        = floatval($payment->amount_received);
        $warehouseLocationId = $invoice->warehouse_location ?? $invoice->location ?? null;
        $wasFullyPaid        = $invoice->payment_status === 'paid';

        // ── STEP 1: Mark payment as refunded ───────────────────────────────────
        DB::table('payments_record')->where('id', $paymentId)->update([
            'status'      => 'refunded',
            'notes'       => trim(($payment->notes ?? '') . "\nRefunded on " . $request->refunded_on
                             . ' via ' . $request->payment_mode
                             . ($request->reference_no ? ' | Ref: ' . $request->reference_no : '')
                             . ($request->description  ? ' | ' . $request->description : '')),
            'updated_at'  => now(),
        ]);

        // ── STEP 2: Stock reversal ─────────────────────────────────────────────
        if ($wasFullyPaid && $warehouseLocationId) {
            foreach ($invoice->items as $item) {
                if (!$item->product_id) continue;
                $qty = floatval($item->quantity);

                $stock = \App\Models\ItemStock::where('item_id', $item->product_id)
                    ->where('location_id', (int)$warehouseLocationId)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$stock) continue;

                // Refund → add stock back to stock_on_hand, move to committed
                // (Invoice still open → committed, not available yet)
                $newStockOnHand = $stock->stock_on_hand + $qty;
                $newCommitted   = $stock->committed_stock + $qty;

                $stock->update([
                    'stock_on_hand'   => $newStockOnHand,
                    'committed_stock' => $newCommitted,
                ]);

                \App\Models\ItemStockLedger::create([
                    'item_id'             => $item->product_id,
                    'location_id'         => (int)$warehouseLocationId,
                    'variant_id'          => null,
                    'transaction_type'    => 'reversal',
                    'transaction_date'    => $request->refunded_on,
                    'reference_type'      => 'refund',
                    'reference_id'        => $invoiceId,
                    'qty_change'          => +$qty,
                    'committed_change'    => +$qty,
                    'unit_value'          => $stock->value_per_unit,
                    'stock_on_hand_after' => $newStockOnHand,
                    'committed_after'     => $newCommitted,
                    'available_after'     => $stock->available_for_sale,
                    'notes'               => 'Refund — stock reversed for ' . $invoice->invoice_number,
                    'created_by'          => auth()->id(),
                ]);

                $totalStock = \App\Models\ItemStock::where('item_id', $item->product_id)
                    ->whereNull('deleted_at')->sum('stock_on_hand');
                \App\Models\Product::withoutEvents(function () use ($item, $totalStock) {
                    \App\Models\Product::where('id', $item->product_id)
                        ->update(['opening_stock' => $totalStock]);
                });
            }
        }

        // ── STEP 3: Recalculate invoice balance ────────────────────────────────
        // Only count non-refunded, non-draft payments
        $totalPaid = DB::table('payments_record')
            ->where('invoice_id', $invoiceId)
            ->whereNotIn('status', ['draft', 'refunded'])
            ->sum('amount_received');

        $grandTotal = floatval($invoice->grand_total);
        $balanceDue = max(0, round($grandTotal - $totalPaid, 2));
        $payStatus  = $balanceDue < 0.01 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'amount_received' => $totalPaid,
            'balance_due'     => $balanceDue,
            'payment_status'  => $payStatus,
        ]);

        // ── STEP 4: History ────────────────────────────────────────────────────
        \App\Models\History::create([
            'module'    => 'invoice',
            'action'    => 'payment',
            'record_id' => $invoiceId,
            'user_id'   => auth()->id(),
            'old_data'  => null,
            'new_data'  => [
                'action'         => 'refund',
                'payment_id'     => $paymentId,
                'refund_amount'  => $refundAmount,
                'refunded_on'    => $request->refunded_on,
                'payment_status' => $payStatus,
                'new_balance'    => $balanceDue,
            ],
        ]);

        DB::commit();

        return response()->json([
            'success'        => true,
            'message'        => 'Refund of ₹' . number_format($refundAmount, 2) . ' processed. Invoice balance updated to ₹' . number_format($balanceDue, 2) . '.',
            'refund_amount'  => $refundAmount,
            'new_balance'    => $balanceDue,
            'payment_status' => $payStatus,
            'total_paid'     => $totalPaid,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}