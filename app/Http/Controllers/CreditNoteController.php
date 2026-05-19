<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Salesperson;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreditNoteController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $creditNotes = CreditNote::with('customer')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->search, function ($q, $search) {
                $q->where('credit_note_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('display_name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('credit_notes.index', compact('creditNotes'));
    }

// ── 1. create() method — priceLists add பண்ணு ──────────────
public function create(): View|\Illuminate\Http\RedirectResponse
{
    $invoiceId     = request('invoice_id');
    $sourceInvoice = null;
   if ($invoiceId) {
        $sourceInvoice = \App\Models\Invoice::with(['customer', 'items.product'])
                            ->find($invoiceId);
   if ($invoiceId) {
    $sourceInvoice = \App\Models\Invoice::with(['customer', 'items.product'])
                        ->find($invoiceId);
    // No blocking — credit note always allowed for any invoice
}
    }

    // ✅ இங்கே add பண்ணு
    $products = \App\Models\Product::where('access_product', false)
                    ->orderBy('name')->get();
    $variants = \App\Models\ItemVariant::whereNull('deleted_at')
                    ->get()->groupBy('item_id');

    return view('credit_notes.create', [
        'customers'        => Customer::orderBy('display_name')->get(['id', 'display_name', 'company_name', 'email']),
        'salespersons'     => Salesperson::orderBy('name')->get(['id', 'name']),
        'products'         => $products,
        'variants'         => $variants,
        'nextCreditNumber' => CreditNote::generateNextNumber(),
        'locations'        => $this->locationList(),
        'productsJson'     => $this->buildProductsJson(),
        'priceLists'       => \App\Models\PriceList::whereNull('deleted_at')->get(['id', 'name']),
        // ✅ NEW
        'sourceInvoice'    => $sourceInvoice,
    ]);
}

public function edit(CreditNote $creditNote): View
{
    abort_if(in_array($creditNote->status, ['void', 'closed']), 403, 'This credit note cannot be edited.');
    $creditNote->load('items');

    $products  = \App\Models\Product::where('access_product', false)
                    ->orderBy('name')
                    ->get();
    $variants  = \App\Models\ItemVariant::whereNull('deleted_at')
                    ->get()
                    ->groupBy('item_id');

    return view('credit_notes.create', [
        'creditNote'       => $creditNote,
        'customers'        => Customer::orderBy('display_name')->get(['id', 'display_name', 'company_name', 'email']),
        'salespersons'     => Salesperson::orderBy('name')->get(['id', 'name']),
        'products'         => $products,
        'variants'         => $variants,
        'nextCreditNumber' => $creditNote->credit_note_number,
        'locations'        => $this->locationList(),
        'productsJson'     => $this->buildProductsJson(),
        'priceLists'       => \App\Models\PriceList::whereNull('deleted_at')->get(['id', 'name']),
    ]);
}


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
 
    // ── UserCategory (case-insensitive match) ──────────────
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
 
    // ── இந்த category-க்கு எல்லா TransactionSeries ─────────
    $allSeries = \App\Models\TransactionSeries::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get();
 
    // ── Series-ல் இருந்து location IDs collect ─────────────
    $locationIds = [];
    foreach ($allSeries as $s) {
        $locIds = $s->location_id ?? [];
        if (is_string($locIds)) $locIds = json_decode($locIds, true) ?? [];
        foreach ((array)$locIds as $lid) {
            if ($lid) $locationIds[] = (int)$lid;
        }
    }
    $locationIds = array_values(array_unique(array_filter($locationIds)));
 
    // ── Location records ────────────────────────────────────
    $locationRecords = \App\Models\Location::whereIn('id', $locationIds)
        ->whereNull('deleted_at')
        ->get();
 
    $categorySeriesIds = $allSeries->pluck('id')->toArray();
 
    $locations = $locationRecords->map(function ($loc) use ($allSeries, $categorySeriesIds) {
        $locSeriesIds = $loc->transaction_series_id ?? [];
        if (is_string($locSeriesIds)) $locSeriesIds = json_decode($locSeriesIds, true) ?? [];
        $locSeriesIds     = array_map('intval', (array)$locSeriesIds);
        $matchedSeriesIds = array_intersect($locSeriesIds, $categorySeriesIds);
 
        $seriesForLoc = $allSeries
            ->whereIn('id', $matchedSeriesIds)
            ->map(function ($s) {
                $seriesData = $s->series_data;
                if (is_string($seriesData)) $seriesData = json_decode($seriesData, true) ?? [];
 
                // ── Credit Note series module filter ─────────
                // series_data-ல் 'Credit Note' or 'credit_note' module பாரு
                $cnSeries = collect($seriesData)->first(function ($item) {
                    $mod = strtolower($item['module'] ?? '');
                    return in_array($mod, ['credit note', 'credit_note', 'creditnote']);
                });
 
                // Credit Note series இல்லன்னா Invoice series use பண்ணு (fallback)
                if (!$cnSeries) {
                    $cnSeries = collect($seriesData)->first(function ($item) {
                        return strtolower($item['module'] ?? '') === 'invoice';
                    });
                }
 
                // Still nothing → first item use பண்ணு
                if (!$cnSeries && !empty($seriesData)) {
                    $cnSeries = $seriesData[0];
                }
 
                if (!$cnSeries) return null;
 
                $prefix     = $cnSeries['prefix']      ?? 'CN-';
                $start      = $cnSeries['start']       ?? '00001';
                $lastNumber = $cnSeries['last_number'] ?? null;
 
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
                    'preview'     => $preview,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
 
        return [
            'id'                => $loc->id,
            'location_name'     => $loc->location_name,
            'location_type'     => $loc->location_type,
            'default_series_id' => $loc->default_series_id,
            'series'            => $seriesForLoc,
        ];
    })->values()->toArray();
 
    // ── Price Lists for this category ──────────────────────
    $priceLists = \App\Models\PriceList::where('category_id', $userCategory->id)
        ->whereNull('deleted_at')
        ->get(['id', 'name', 'price_list_type', 'markup_type', 'percentage'])
        ->toArray();
 
    // ── Default selections ──────────────────────────────────
    $defaultLocationId = $locations[0]['id'] ?? null;
    $defaultSeriesId   = null;
    if ($defaultLocationId && isset($locations[0])) {
        $firstLoc        = $locations[0];
        $defaultSeriesId = $firstLoc['default_series_id']
                        ?? ($firstLoc['series'][0]['id'] ?? null);
    }
 
    return response()->json([
        'category' => [
            'id'   => $userCategory->id,
            'name' => $userCategory->name,
        ],
        'locations'           => $locations,
        'price_lists'         => $priceLists,
        'default_location_id' => $defaultLocationId,
        'default_series_id'   => $defaultSeriesId,
    ]);
}


private function buildProductsJson(): string
{
    $products = Product::where('access_product', false)
        ->orderBy('name')
        ->get(['id', 'name', 'sku', 'type', 'unit', 'selling_price', 'additional_data']);

    return json_encode($products->map(fn($p) => [
        'id'          => $p->id,
        'name'        => $p->name,
        'sku'         => $p->sku,
        'type'        => $p->type,
        'unit'        => $p->unit,
        'rate'        => number_format((float)($p->selling_price ?? 0), 2, '.', ''), // ✅ string, no precision loss
        'account'     => $p->additional_data['account'] ?? '',
        'description' => $p->additional_data['sales_description'] ?? '',
    ])->values());
}
  

    // ── Store ──────────────────────────────────────────────────────────────────
public function store(Request $request): RedirectResponse
{
    $validated = $this->validateRequest($request);
    $status    = $request->input('action') === 'save_open' ? 'open' : 'draft';
 
    $creditNote = null;
 
    DB::transaction(function () use ($validated, $status, $request, &$creditNote) {
        $header = $validated['header'];
        $header['adjustment'] = $header['adjustment'] ?? 0;
 
        // ✅ Duplicate number check — auto-increment if taken
        $cnNumber = $header['credit_note_number'];
        while (CreditNote::where('credit_note_number', $cnNumber)->exists()) {
            preg_match('/(\d+)$/', $cnNumber, $m);
            if ($m) {
                $next     = (int)$m[1] + 1;
                $prefix   = preg_replace('/\d+$/', '', $cnNumber);
                $cnNumber = $prefix . str_pad($next, strlen($m[1]), '0', STR_PAD_LEFT);
            } else {
                $cnNumber .= '-1';
            }
        }
        $header['credit_note_number'] = $cnNumber;
 
        $creditNote = CreditNote::create(array_merge(
            $header,
            ['status' => $status, 'user_id' => auth()->id()]
        ));
 
        // ✅ Update series last_number
        if (!empty($header['series_id'])) {
            $series = \App\Models\TransactionSeries::find($header['series_id']);
            if ($series) {
                $seriesData = is_string($series->series_data)
                    ? json_decode($series->series_data, true)
                    : ($series->series_data ?? []);
                foreach ($seriesData as &$item) {
                    $mod = strtolower($item['module'] ?? '');
                    if (in_array($mod, ['credit note', 'credit_note', 'creditnote', 'invoice'])) {
                        $item['last_number'] = (int)($item['last_number'] ?? 0) + 1;
                        break;
                    }
                }
                unset($item);
                $series->update(['series_data' => json_encode($seriesData)]);
            }
        }
 
        $this->syncItems($creditNote, $validated['items']);
        $creditNote->recalculateTotals();
 
        // ══════════════════════════════════════════════════════════════
        // SOURCE INVOICE இருந்தா மட்டும் — stock + credit apply
        // ══════════════════════════════════════════════════════════════
        if ($status === 'open' && $request->filled('source_invoice_id')) {
 
            $invoice = \App\Models\Invoice::find($request->source_invoice_id);
 
            if ($invoice) {
                $creditTotal = floatval($creditNote->total);
 
                // ── Location resolve ──────────────────────────────────
                $warehouseLocationId = $invoice->warehouse_location
                                    ?? $invoice->location
                                    ?? null;
 
                // ════════════════════════════════════════════════════
                // STEP A: Stock return
                // ════════════════════════════════════════════════════
                if ($warehouseLocationId) {
                    foreach ($creditNote->items as $cnItem) {
                        if (!$cnItem->product_id) continue;
                        $returnQty = floatval($cnItem->quantity);
                        if ($returnQty <= 0) continue;
 
                        $stock = \App\Models\ItemStock::where('item_id', $cnItem->product_id)
                            ->where('location_id', (int)$warehouseLocationId)
                            ->whereNull('deleted_at')
                            ->first();
 
                        if (!$stock) {
                            $stock = \App\Models\ItemStock::create([
                                'item_id'            => $cnItem->product_id,
                                'location_id'        => (int)$warehouseLocationId,
                                'variant_id'         => null,
                                'opening_stock'      => 0,
                                'stock_on_hand'      => 0,
                                'committed_stock'    => 0,
                                'available_for_sale' => 0,
                                'value_per_unit'     => 0,
                                'total_value'        => 0,
                            ]);
                        }
 
                        $newStockOnHand = $stock->stock_on_hand + $returnQty;
                        $newAvailable   = $stock->available_for_sale + $returnQty;
 
                        $stock->update([
                            'stock_on_hand'      => $newStockOnHand,
                            'available_for_sale' => $newAvailable,
                        ]);
 
                        \App\Models\ItemStockLedger::create([
                            'item_id'             => $cnItem->product_id,
                            'location_id'         => (int)$warehouseLocationId,
                            'variant_id'          => null,
                            'transaction_type'    => 'sale_return',
                            'transaction_date'    => $creditNote->credit_note_date,
                            'reference_type'      => 'credit_note',
                            'reference_id'        => $creditNote->id,
                            'qty_change'          => +$returnQty,
                            'committed_change'    => 0,
                            'unit_value'          => $stock->value_per_unit,
                            'stock_on_hand_after' => $newStockOnHand,
                            'committed_after'     => $stock->committed_stock,
                            'available_after'     => $newAvailable,
                            'notes'               => 'Sale return — CN: ' . $creditNote->credit_note_number
                                                   . ' | INV: ' . $invoice->invoice_number,
                            'created_by'          => auth()->id(),
                        ]);
 
                        $totalStock = \App\Models\ItemStock::where('item_id', $cnItem->product_id)
                            ->whereNull('deleted_at')
                            ->sum('stock_on_hand');
 
                        \App\Models\Product::withoutEvents(function () use ($cnItem, $totalStock) {
                            \App\Models\Product::where('id', $cnItem->product_id)
                                ->update(['opening_stock' => $totalStock]);
                        });
                    }
                }
 
                // ════════════════════════════════════════════════════
                // STEP B: Credit apply — balance > 0 OR balance = 0
                // எல்லா cases-லயும் payment_record insert பண்ணு
                // ════════════════════════════════════════════════════
                if ($creditTotal > 0) {
 
                    $grandTotal     = floatval($invoice->grand_total);
                    $alreadyPaid    = floatval($invoice->amount_received ?? 0);
                    $currentBalance = max(0, floatval(
                        $invoice->balance_due ?? ($grandTotal - $alreadyPaid)
                    ));
 
                    // Default: full credit applied, no unused
                    $appliedAmount = $creditTotal;
                    $unusedAmount  = 0;
                    $newBalance    = $currentBalance;
                    $newPayStatus  = $invoice->payment_status;
 
                    // ── Case 1: Balance > 0 → apply to invoice ──
                    if ($currentBalance > 0.009) {
                        $appliedAmount  = min($creditTotal, $currentBalance);
                        $unusedAmount   = round($creditTotal - $appliedAmount, 2);
                        $newBalance     = round($currentBalance - $appliedAmount, 2);
                        $newAmtReceived = $alreadyPaid + $appliedAmount;
                        $newPayStatus   = $newBalance < 0.01 ? 'paid'
                                        : ($newAmtReceived > 0 ? 'partial' : 'unpaid');
 
                        $invoice->update([
                            'balance_due'     => $newBalance,
                            'amount_received' => $newAmtReceived,
                            'payment_status'  => $newPayStatus,
                        ]);
                    }
                    // ── Case 2: Balance = 0 (already paid) ──
                    // invoice unchanged, applied_amount = full creditTotal
                    // invoice show page-ல் credits applied section-ல் காட்டும்
 
                    // ✅ Always insert payment_record — both cases
                    \DB::table('payments_record')->insert([
                        'customer_id'     => $invoice->customer_id,
                        'invoice_id'      => $invoice->id,
                        'payment_no'      => 'CN-' . $creditNote->credit_note_number,
                        'payment_date'    => $creditNote->credit_note_date,
                        'amount_received' => $appliedAmount,
                        'payment_mode'    => 'credit_note',
                        'deposit_to'      => 'Credit Note',
                        'reference_no'    => $creditNote->credit_note_number,
                        'notes'           => 'Credit Note applied: ' . $creditNote->credit_note_number,
                        'status'          => 'paid',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
 
                    // Unused → customer credit
                    if ($unusedAmount > 0) {
                        \App\Models\Customer::where('id', $invoice->customer_id)
                            ->increment('unused_credits', $unusedAmount);
                    }
 
                    // ✅ Always set invoice_id on credit note — show page-ல் காட்ட
                    $creditNote->update([
                        'invoice_id'     => $invoice->id,
                        'applied_amount' => $appliedAmount,
                        'unused_amount'  => $unusedAmount,
                    ]);
 
                    \App\Models\History::create([
                        'module'    => 'invoice',
                        'action'    => 'payment',
                        'record_id' => $invoice->id,
                        'user_id'   => auth()->id(),
                        'old_data'  => null,
                        'new_data'  => [
                            'action'         => 'credit_note_applied',
                            'credit_note'    => $creditNote->credit_note_number,
                            'credit_total'   => $creditTotal,
                            'applied_amount' => $appliedAmount,
                            'unused_amount'  => $unusedAmount,
                            'new_balance'    => $newBalance,
                            'payment_status' => $newPayStatus,
                        ],
                    ]);
                }
 
            } // end if ($invoice)
        } // end if source_invoice_id
 
    }); // end DB::transaction
 
    return redirect()
        ->route('credit-notes.show', $creditNote)
        ->with('success', "Credit Note {$creditNote->credit_note_number} saved as " . ucfirst($status) . '.');
}
    // ── Show ───────────────────────────────────────────────────────────────────

    public function show(CreditNote $creditNote): View
    {
        $creditNote->load('customer', 'salesperson', 'items.product');
        return view('credit_notes.show', compact('creditNote'));
    }



    // ── Update ─────────────────────────────────────────────────────────────────

    public function update(Request $request, CreditNote $creditNote): RedirectResponse
    {
        abort_if(
            in_array($creditNote->status, ['void', 'closed']),
            403,
            'This credit note cannot be edited.'
        );

        $validated = $this->validateRequest($request);
        $status    = $request->input('action') === 'save_open' ? 'open' : 'draft';

        DB::transaction(function () use ($validated, $status, $creditNote) {
            $creditNote->update(array_merge($validated['header'], ['status' => $status]));
            $creditNote->items()->delete();
            $this->syncItems($creditNote, $validated['items']);
            $creditNote->recalculateTotals();
        });

        return redirect()
            ->route('credit-notes.show', $creditNote)
            ->with('success', "Credit Note {$creditNote->credit_note_number} updated.");
    }

    // ── Void ───────────────────────────────────────────────────────────────────

    public function void(CreditNote $creditNote): RedirectResponse
    {
        abort_unless($creditNote->status === 'open', 403, 'Only open credit notes can be voided.');

        $creditNote->update(['status' => 'void']);

        return back()->with('success', "Credit Note {$creditNote->credit_note_number} has been voided.");
    }

    // ── Delete ─────────────────────────────────────────────────────────────────

    public function destroy(CreditNote $creditNote): RedirectResponse
    {
        abort_unless($creditNote->status === 'draft', 403, 'Only draft credit notes can be deleted.');

        $creditNote->delete();

        return redirect()
            ->route('credit-notes.index')
            ->with('success', "Credit Note {$creditNote->credit_note_number} deleted.");
    }

    // ── AJAX: Product details for line-item autofill ───────────────────────────

    public function getProductDetails(Product $product): JsonResponse
    {
        return response()->json($product->toCreditNoteLineSnapshot());
    }

    // ── AJAX: Customer search ──────────────────────────────────────────────────

    public function searchCustomers(Request $request): JsonResponse
    {
        $term = $request->input('q', '');

        $customers = Customer::where('display_name', 'like', "%{$term}%")
            ->orWhere('company_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->limit(20)
            ->get(['id', 'display_name', 'company_name', 'email', 'unused_credits']);

        return response()->json($customers->map(fn($c) => [
            'id'             => $c->id,
            'label'          => $c->display_name,
            'company'        => $c->company_name,
            'email'          => $c->email,
            'unused_credits' => $c->unused_credits,
        ]));
    }

    // ── Private Helpers ────────────────────────────────────────────────────────

    private function validateRequest(Request $request): array
    {
        $data = $request->validate([
            'credit_note_number'          => 'required|string|max:50',
            'customer_id'                 => 'required|exists:customers,id',
            'salesperson_id'              => 'nullable|exists:salespersons,id',
            'location'                    => 'required|string|max:100',
            'warehouse_location'          => 'nullable|string|max:100',
            'reference_number'            => 'nullable|string|max:100',
            'credit_note_date'            => 'required|date',
            'subject'                     => 'nullable|string|max:255',
            'price_list'                  => 'nullable|string|max:100',
            'discount_percentage'         => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'nullable|in:TDS,TCS,tds,tcs',
            'tax_id'                      => 'nullable|string|max:100',
            'adjustment' => 'nullable|numeric|min:-999999',
            'customer_notes'              => 'nullable|string',
            'terms_and_conditions'        => 'nullable|string',
            'pdf_template'                => 'nullable|string|max:100',

            'line_items'                          => 'required|array|min:1',
            'line_items.*.product_id'             => 'nullable|exists:products,id',
            'line_items.*.item_name'              => 'required|string|max:255',
            'line_items.*.item_sku'               => 'nullable|string|max:100',
            'line_items.*.item_type'              => 'nullable|string|max:50',
            'line_items.*.unit'                   => 'nullable|string|max:50',
            'line_items.*.account'                => 'nullable|string|max:100',
            'line_items.*.quantity'               => 'required|numeric|min:0',
            'line_items.*.rate'                   => 'required|numeric|min:0',
            'line_items.*.discount_percentage'    => 'nullable|numeric|min:0|max:100',
            'line_items.*.tax_percentage'         => 'nullable|numeric|min:0|max:100',
            'line_items.*.description'            => 'nullable|string',
        ]);

        return [
            'header' => collect($data)->except('line_items')->toArray(),
            'items'  => $data['line_items'],
        ];
    }

    private function syncItems(CreditNote $creditNote, array $lineItems): void
    {
        foreach ($lineItems as $index => $row) {
            CreditNoteItem::create([
                'credit_note_id'      => $creditNote->id,
                'product_id'          => $row['product_id'] ?? null,
                'item_name'           => $row['item_name'],
                'item_sku'            => $row['item_sku'] ?? null,
                'item_type'           => $row['item_type'] ?? null,
                'unit'                => $row['unit'] ?? null,
                'account'             => $row['account'] ?? null,
                'quantity'            => $row['quantity'],
                'rate'                => $row['rate'],
                'discount_percentage' => $row['discount_percentage'] ?? 0,
                'tax_percentage'      => $row['tax_percentage'] ?? 0,
                'description'         => $row['description'] ?? null,
                'sort_order'          => $index,
            ]);
        }
    }

    private function locationList(): array
    {
        return ['Head Office', 'Branch 1', 'Branch 2'];
    }
}