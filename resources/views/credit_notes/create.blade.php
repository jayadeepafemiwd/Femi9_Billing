@extends('layouts.app')

@section('title', isset($creditNote) ? 'Edit Credit Note' : 'New Credit Note')

@push('styles')
<style>
    /* ── Zoho-style form ─────────────────────────────────── */
    body { background: #f5f6fa; }

    .cn-page-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 16px 60px;
    }

    /* Top breadcrumb bar */
    .cn-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 0 10px;
        font-size: 13px;
        color: #888;
    }
    .cn-breadcrumb a { color: #1a73e8; text-decoration: none; }
    .cn-breadcrumb span { color: #333; font-weight: 600; font-size: 15px; }

    .cn-form-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 28px 32px 24px;
        margin-bottom: 0;
    }

    /* Section title (e.g. "Item Table") */
    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #444;
        margin-bottom: 12px;
        margin-top: 24px;
        border-bottom: 1px solid #eee;
        padding-bottom: 6px;
        letter-spacing: .3px;
    }

    /* Two-column header grid */
    .cn-grid {
        display: grid;
        grid-template-columns: 200px 1fr;
        align-items: center;
        gap: 10px 0;
        margin-bottom: 4px;
    }
    .cn-grid label {
        font-size: 13px;
        color: #555;
        padding-right: 12px;
    }
    .cn-grid label .req { color: #c0392b; }
    .cn-grid .field-wrap { max-width: 420px; }

    .cn-form-control {
        width: 100%;
        height: 34px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 0 10px;
        font-size: 13px;
        color: #333;
        background: #fff;
        outline: none;
        transition: border-color .15s;
    }
    .cn-form-control:focus { border-color: #1a73e8; box-shadow: 0 0 0 2px rgba(26,115,232,.12); }
    .cn-form-control.is-invalid { border-color: #c0392b; }
    .invalid-feedback { font-size: 11px; color: #c0392b; margin-top: 3px; }

    select.cn-form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23888' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }

    textarea.cn-form-control { height: auto; padding: 8px 10px; resize: vertical; }

    /* Divider row */
    .cn-divider { border: none; border-top: 1px solid #eee; margin: 18px 0; }

    /* Warehouse + price list row */
    .warehouse-row {
        display: flex;
        gap: 24px;
        align-items: center;
        margin-bottom: 14px;
        font-size: 13px;
        color: #555;
    }
    .warehouse-row .wh-field { display: flex; align-items: center; gap: 8px; }
    .warehouse-row select { height: 30px; border: none; border-bottom: 1px dashed #aaa; background: transparent; font-size: 13px; color: #1a73e8; padding: 0 20px 0 0; appearance: none; cursor: pointer; }

    /* Item table */
    .item-table-wrap { border: 1px solid #e0e0e0; border-radius: 4px; overflow: hidden; }
    .item-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .item-table thead tr { background: #f8f9fa; }
    .item-table thead th {
        padding: 9px 10px;
        font-weight: 600;
        color: #555;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        border-bottom: 1px solid #e0e0e0;
        white-space: nowrap;
    }
    .item-table tbody tr { border-bottom: 1px solid #f0f0f0; }
    .item-table tbody tr:last-child { border-bottom: none; }
    .item-table tbody td { padding: 8px 10px; vertical-align: middle; }

    .item-table input[type=number],
    .item-table input[type=text] {
        border: none;
        border-bottom: 1px solid transparent;
        background: transparent;
        font-size: 13px;
        color: #333;
        width: 100%;
        outline: none;
        padding: 2px 0;
    }
    .item-table input:focus { border-bottom-color: #1a73e8; }
    .item-table .item-search-cell { min-width: 220px; }
    .item-search-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .item-thumb {
        width: 32px; height: 32px;
        border: 1px solid #ddd;
        border-radius: 3px;
        display: flex; align-items: center; justify-content: center;
        color: #bbb; flex-shrink: 0;
        font-size: 16px;
    }
    .item-search-input {
        flex: 1;
        border: none;
        border-bottom: 1px solid transparent;
        font-size: 13px;
        color: #555;
        outline: none;
        background: transparent;
        padding: 2px 0;
    }
    .item-search-input:focus { border-bottom-color: #1a73e8; color: #333; }
    .item-search-input::placeholder { color: #aaa; }

    .account-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 24px 4px 8px;
        font-size: 12px;
        color: #555;
        background: #fff url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23888' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 6px center;
        appearance: none;
        min-width: 140px;
    }

    .remove-row-btn {
        background: none;
        border: none;
        color: #bbb;
        font-size: 18px;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
    }
    .remove-row-btn:hover { color: #e74c3c; }

    /* Reporting tags */
    .reporting-tags-row {
        padding: 8px 12px;
        background: #fafafa;
        border-top: 1px solid #f0f0f0;
        font-size: 12px;
        color: #1a73e8;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    /* Add row buttons */
    .add-row-area {
        padding: 10px 12px;
        display: flex;
        gap: 12px;
        border-top: 1px solid #f0f0f0;
    }
    .btn-add-row {
        font-size: 12px;
        color: #1a73e8;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
        padding: 0;
    }
    .btn-add-row:hover { text-decoration: underline; }
    .btn-add-row .icon {
        width: 18px; height: 18px;
        border-radius: 50%;
        background: #1a73e8;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        line-height: 1;
    }

    /* Totals section */
    .totals-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }
    .totals-box {
        width: 380px;
        font-size: 13px;
    }
    .totals-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .totals-row:last-child { border-bottom: none; }
    .totals-row .label { color: #555; }
    .totals-row .value { font-weight: 500; color: #333; }
    .totals-row.total-final { padding-top: 12px; }
    .totals-row.total-final .label { font-size: 14px; font-weight: 700; color: #222; }
    .totals-row.total-final .value { font-size: 16px; font-weight: 700; color: #222; }

    .discount-inline {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .discount-inline input {
        width: 64px;
        height: 28px;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-align: right;
        padding: 0 6px;
        font-size: 13px;
    }
    .discount-inline .pct-badge {
        font-size: 12px;
        color: #888;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 2px 6px;
        background: #f8f9fa;
    }

    /* TDS/TCS row */
    .tds-tcs-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 7px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }
    .tds-tcs-row label { display: flex; align-items: center; gap: 5px; color: #555; cursor: pointer; }
    .tds-tcs-row select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 3px 24px 3px 8px;
        font-size: 12px;
        color: #555;
        background: #fff url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23888' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 6px center;
        appearance: none;
        min-width: 140px;
        margin-left: 8px;
    }

    /* Adjustment row */
    .adjustment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .adjustment-row .adj-label-wrap { display: flex; align-items: center; gap: 6px; }
    .adjustment-row input {
        width: 100px;
        height: 28px;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 0 8px;
        font-size: 13px;
        text-align: right;
    }

    /* Bottom notes + T&C */
    .notes-tc-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 20px;
    }
    .notes-tc-grid label { font-size: 12px; color: #888; margin-bottom: 4px; display: block; }
    .notes-tc-grid textarea {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 10px 12px;
        font-size: 13px;
        color: #555;
        resize: vertical;
        min-height: 90px;
        outline: none;
    }
    .notes-tc-grid textarea::placeholder { color: #bbb; }
    .notes-tc-grid textarea:focus { border-color: #1a73e8; }

    /* Additional fields hint */
    .additional-fields-hint {
        margin-top: 20px;
        font-size: 12px;
        color: #888;
    }
    .additional-fields-hint strong { color: #555; }

    /* Sticky footer */
    .cn-footer {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #e0e0e0;
        padding: 10px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 100;
    }
    .btn-save-draft {
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 7px 18px;
        font-size: 13px;
        color: #333;
        cursor: pointer;
    }
    .btn-save-draft:hover { background: #f5f5f5; }
    .btn-save-open {
        background: #1a73e8;
        border: none;
        border-radius: 4px;
        padding: 7px 18px;
        font-size: 13px;
        color: #fff;
        cursor: pointer;
        font-weight: 500;
    }
    .btn-save-open:hover { background: #1558b0; }
    .btn-cancel {
        background: none;
        border: none;
        font-size: 13px;
        color: #555;
        cursor: pointer;
        padding: 7px 8px;
    }
    .btn-cancel:hover { color: #c0392b; }
    .pdf-template-hint {
        margin-left: auto;
        font-size: 12px;
        color: #888;
    }
    .pdf-template-hint a { color: #1a73e8; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="cn-page-wrap">

    {{-- Breadcrumb --}}
    <div class="cn-breadcrumb">
        <a href="{{ route('credit-notes.index') }}">Credit Notes</a>
        <span>/</span>
        <span>{{ isset($creditNote) ? 'Edit ' . $creditNote->credit_note_number : 'New Credit Note' }}</span>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif


    {{-- Products JSON for JS --}}
<script id="cn-products-data" type="application/json">
@php
$productsForJs = $products->map(function($p) use ($variants) {
    $pVariants = $variants[$p->id] ?? collect();
    $additionalData = is_string($p->additional_data)
        ? json_decode($p->additional_data, true)
        : ($p->additional_data ?? []);
    $pGst  = (float)($additionalData['gst'] ?? 0);
    $imgD  = is_string($p->product_image)
        ? json_decode($p->product_image, true)
        : ($p->product_image ?? []);
    $img   = $imgD['front_image'] ?? '';

    return [
        'id'       => $p->id,
        'name'     => $p->name,
        'sku'      => $p->sku ?? '',
        'unit'     => $p->unit ?? '',
        'rate'     => (float)($p->selling_price ?? 0),
        'gst'      => $pGst,
        'img'      => $img ? asset($img) : '',
        'variants' => $pVariants->map(fn($v) => [
            'id'   => $v->id,
            'name' => $v->name,
            'sku'  => $v->sku ?? '',
            'rate' => (float)($v->selling_price ?? 0),
        ])->values()->toArray(),
    ];
})->values()->toArray();
echo json_encode($productsForJs);
@endphp
</script>
    <form method="POST"
          action="{{ isset($creditNote) ? route('credit-notes.update', $creditNote) : route('credit-notes.store') }}">
        @csrf
        @if(isset($creditNote)) @method('PUT') @endif

        <div class="cn-form-card">

            {{-- ── Header fields ──────────────────────────────────────── --}}
            <div class="cn-grid">

                <label>Customer Name <span class="req">*</span></label>
                <div class="field-wrap">
                   <select name="customer_id" id="customer_id"
        class="cn-form-control @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select or add a customer</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}"
                                @selected(old('customer_id', $creditNote->customer_id ?? '') == $c->id)>
                                {{ $c->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    {{-- Customer Address Display --}}
<div id="customer-address-block" style="display:none;margin-top:8px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;
                background:#f8f9fa;border:1px solid #e0e0e0;
                border-radius:4px;padding:12px 16px;font-size:12px;">
        <div>
            <div style="font-size:10px;font-weight:700;color:#888;
                        text-transform:uppercase;margin-bottom:6px;">
                🏠 BILLING ADDRESS
            </div>
            <div id="billing-address" style="color:#444;line-height:1.8;"></div>
        </div>
        <div style="border-left:1px solid #e0e0e0;padding-left:12px;">
            <div style="font-size:10px;font-weight:700;color:#888;
                        text-transform:uppercase;margin-bottom:6px;">
                🚚 SHIPPING ADDRESS
            </div>
            <div id="shipping-address" style="color:#444;line-height:1.8;"></div>
        </div>
    </div>
    {{-- Category badge --}}
    <div style="margin-top:6px;display:flex;align-items:center;gap:8px;">
        <span style="font-size:11px;color:#888;">Category:</span>
        <span id="customer-category"
              style="background:#e8f0fe;color:#1a73e8;
                     padding:2px 10px;border-radius:10px;
                     font-size:11px;font-weight:600;"></span>
    </div>
</div>
<input type="hidden" id="customer-category-value" name="customer_category_value">
                </div>

                <label>Location <span class="req">*</span></label>
                <div class="field-wrap">
                   <select name="location" id="location_select"
        class="cn-form-control @error('location') is-invalid @enderror" required>
    <option value="">— Select —</option>
    {{-- JS dynamically populate பண்ணும் --}}
</select>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <label>Invoice Series</label>
<div class="field-wrap">
    <select id="series_select" name="series_id" class="cn-form-control">
        <option value="">— Select Series —</option>
    </select>
</div>
                <label>Credit Note # <span class="req">*</span></label>
                <div class="field-wrap">
                    <input type="text" name="credit_note_number"
                           class="cn-form-control @error('credit_note_number') is-invalid @enderror"
                           value="{{ old('credit_note_number', $nextCreditNumber) }}" required>
                    @error('credit_note_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <label>Reference #</label>
                <div class="field-wrap">
                    <input type="text" name="reference_number" class="cn-form-control"
                           value="{{ old('reference_number', $creditNote->reference_number ?? '') }}">
                </div>

                <label>Credit Note Date <span class="req">*</span></label>
                <div class="field-wrap">
                    <input type="date" name="credit_note_date"
                           class="cn-form-control @error('credit_note_date') is-invalid @enderror"
                           value="{{ old('credit_note_date', isset($creditNote) ? $creditNote->credit_note_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    @error('credit_note_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <label>Salesperson</label>
                <div class="field-wrap">
                    <select name="salesperson_id" class="cn-form-control">
                        <option value="">Select or Add Salesperson</option>
                        @foreach($salespersons as $sp)
                            <option value="{{ $sp->id }}"
                                @selected(old('salesperson_id', $creditNote->salesperson_id ?? '') == $sp->id)>
                                {{ $sp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <label>Subject</label>
                <div class="field-wrap">
                    <textarea name="subject" class="cn-form-control" rows="2"
                              placeholder="Let your customer know what this Credit Note is for">{{ old('subject', $creditNote->subject ?? '') }}</textarea>
                </div>

            </div>

            <hr class="cn-divider">

            {{-- ── Item Table ──────────────────────────────────────────── --}}
            <div class="section-title">Item Table</div>

            {{-- Warehouse + Price List --}}
            <div class="warehouse-row">
                <div class="wh-field">
                    <span>Warehouse Location</span>
                    <select name="warehouse_location">
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}"
                                @selected(old('warehouse_location', $creditNote->location ?? '') === $loc)>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="wh-field">
                    <span>📋</span>
                    <select name="price_list_id">
                        <option value="">Select Price List</option>
                        {{-- @foreach($priceLists as $pl) <option value="{{ $pl->id }}">{{ $pl->name }}</option> @endforeach --}}
                    </select>
                </div>
            </div>

            <div class="item-table-wrap">
                <table class="item-table" id="line-items-table">
          <thead>
  <tr>
    <th class="item-search-cell">Item Details</th>
    <th style="min-width:160px">Account</th>
    <th style="width:80px;text-align:right">Orig Qty</th>   <!-- NEW -->
    <th style="width:90px;text-align:right">Return Qty</th> <!-- RENAMED -->
    <th style="width:100px;text-align:right">Rate</th>
    <th style="width:110px;text-align:right">Amount</th>
    <th style="width:30px"></th>
  </tr>
</thead>
<tbody id="line-items-body">
    @if(isset($creditNote) && $creditNote->items->count())
        @foreach($creditNote->items as $i => $item)
        <tr class="line-item-row" data-index="{{ $i }}">
            <td class="item-search-cell">
                <input type="hidden" name="line_items[{{ $i }}][product_id]" value="{{ $item->product_id }}">
                <input type="hidden" name="line_items[{{ $i }}][item_sku]"   value="{{ $item->item_sku }}">
                <input type="hidden" name="line_items[{{ $i }}][item_type]"  value="{{ $item->item_type }}">
                <input type="hidden" name="line_items[{{ $i }}][unit]"       value="{{ $item->unit }}">
                <input type="hidden" name="line_items[{{ $i }}][item_name]"  id="cn-iname-{{ $i }}" value="{{ $item->item_name }}">
                <input type="hidden" name="line_items[{{ $i }}][variant_id]" id="cn-ivariant-{{ $i }}" value="">
                <div class="item-search-wrap">
                    <div class="item-thumb" id="cn-iimg-{{ $i }}">🖼</div>
                    <div style="flex:1">
                        <select id="cn-psel-{{ $i }}"
                                onchange="cnFillProduct({{ $i }}, this)"
                                style="width:100%;border:none;border-bottom:1px solid #e0e0e0;
                                       background:transparent;font-size:13px;color:#333;outline:none;padding:2px 0;">
                        </select>
                        <div style="font-size:11px;color:#888;margin-top:2px;" id="cn-imeta-{{ $i }}">
                            @if($item->item_sku)SKU: <strong>{{ $item->item_sku }}</strong>@endif
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <select name="line_items[{{ $i }}][account_id]" class="account-select">
                    <option value="">Select an account</option>
                </select>
            </td>
            <td style="text-align:right">
                <input type="number" name="line_items[{{ $i }}][quantity]"
                       class="qty" style="text-align:right"
                       value="{{ $item->quantity }}" step="0.0001" min="0" required>
            </td>
            <td style="text-align:right">
                <input type="number" name="line_items[{{ $i }}][rate]"
                       class="rate" style="text-align:right"
                       value="{{ $item->rate }}" step="0.01" min="0" required>
            </td>
            <td style="text-align:right">
                <span class="row-amount" style="font-size:13px;font-weight:500">
                    {{ number_format($item->amount, 2) }}
                </span>
            </td>
            <td><button type="button" class="remove-row-btn" title="Remove">×</button></td>
        </tr>
        @endforeach
    @else
    <tr class="line-item-row" data-index="0">
        <td class="item-search-cell">
            <input type="hidden" name="line_items[0][product_id]" value="">
            <input type="hidden" name="line_items[0][item_sku]"   value="">
            <input type="hidden" name="line_items[0][item_type]"  value="">
            <input type="hidden" name="line_items[0][unit]"       value="">
            <input type="hidden" name="line_items[0][item_name]"  id="cn-iname-0" value="">
            <input type="hidden" name="line_items[0][variant_id]" id="cn-ivariant-0" value="">
            <div class="item-search-wrap">
                <div class="item-thumb" id="cn-iimg-0">🖼</div>
                <div style="flex:1">
                    <select id="cn-psel-0"
                            onchange="cnFillProduct(0, this)"
                            style="width:100%;border:none;border-bottom:1px solid #e0e0e0;
                                   background:transparent;font-size:13px;color:#555;outline:none;padding:2px 0;">
                    </select>
                    <div style="font-size:11px;color:#888;margin-top:2px;" id="cn-imeta-0"></div>
                </div>
            </div>
        </td>
        <td>
            <select name="line_items[0][account_id]" class="account-select">
                <option value="">Select an account</option>
            </select>
        </td>
        <td style="text-align:right">
            <input type="number" name="line_items[0][quantity]" class="qty"
                   style="text-align:right" value="1.00" step="0.0001" min="0" required>
        </td>
        <td style="text-align:right">
            <input type="number" name="line_items[0][rate]" class="rate"
                   style="text-align:right" value="0.00" step="0.01" min="0" required>
        </td>
        <td style="text-align:right">
            <span class="row-amount" style="font-size:13px;font-weight:500">0.00</span>
        </td>
        <td><button type="button" class="remove-row-btn" title="Remove">×</button></td>
    </tr>
    @endif
</tbody>
  </table>

                {{-- Reporting Tags --}}
                <div class="reporting-tags-row">
                    🏷 Reporting Tags ▾
                </div>

                {{-- Add row buttons --}}
                <div class="add-row-area">
                    <button type="button" id="add-row" class="btn-add-row">
                        <span class="icon">+</span> Add New Row
                    </button>
                    <button type="button" class="btn-add-row">
                        <span class="icon">+</span> Add Items in Bulk
                    </button>
                </div>
            </div>

            {{-- ── Totals ──────────────────────────────────────────────── --}}
            <div class="totals-section">
                <div class="totals-box">

                    <div class="totals-row">
                        <span class="label">Sub Total</span>
                        <span class="value" id="summary-subtotal">0.00</span>
                    </div>

                    <div class="totals-row">
                        <span class="label">Discount</span>
                        <div class="discount-inline">
                            <input type="number" name="discount_percentage" id="discount-pct"
                                   value="{{ old('discount_percentage', $creditNote->discount_percentage ?? 0) }}"
                                   step="0.01" min="0" max="100">
                            <span class="pct-badge">%</span>
                        </div>
                        <span class="value" id="summary-discount">0.00</span>
                    </div>

                    {{-- TDS / TCS --}}
                    <div class="tds-tcs-row">
                    <label><input type="radio" name="tax_type" value="TDS" checked> TDS</label>
                    <label><input type="radio" name="tax_type" value="TCS"> TCS</label>
                        <select name="tax_id">
                            <option value="">Select a Tax</option>
                        </select>
                        <span class="value ms-auto" style="color:#555">- 0.00</span>
                    </div>

                    {{-- Adjustment --}}
                    <div class="adjustment-row">
                        <div class="adj-label-wrap">
                            <span style="font-size:13px;color:#555">Adjustment</span>
                            <span style="font-size:11px;color:#aaa" title="Add a positive or negative adjustment">ℹ</span>
                        </div>
                        <input type="number" name="adjustment" id="adjustment"
                               value="{{ old('adjustment', $creditNote->adjustment ?? '') }}"
                               step="0.01" placeholder="0.00">
                        <span class="value" id="summary-adjustment">0.00</span>
                    </div>

                    <div class="totals-row total-final">
                        <span class="label">Total (₹)</span>
                        <span class="value" id="summary-total">0.00</span>
                    </div>

                </div>
            </div>

            <hr class="cn-divider">

            {{-- ── Customer Notes + Terms ──────────────────────────────── --}}
            <div class="notes-tc-grid">
                <div>
                    <label>Customer Notes</label>
                    <textarea name="customer_notes"
                              placeholder="Will be displayed on the credit note">{{ old('customer_notes', $creditNote->customer_notes ?? '') }}</textarea>
                </div>
                <div>
                    <label>Terms & Conditions</label>
                    <textarea name="terms_and_conditions"
                              placeholder="Enter the terms and conditions of your business to be displayed in your transaction">{{ old('terms_and_conditions', $creditNote->terms_and_conditions ?? '') }}</textarea>
                </div>
            </div>

            {{-- Additional fields hint --}}
            <div class="additional-fields-hint">
                <strong>Additional Fields:</strong>
                Start adding custom fields for your credit notes by going to
                <em>Settings ➡ Sales ➡ Credit Notes.</em>
            </div>

        </div>{{-- /cn-form-card --}}

  {{-- ── Sticky footer ── --}}
<div class="cn-footer">
    <button type="submit" name="action" value="save_draft" class="btn-save-draft"
            onclick="return validateBeforeSave(event)">Save as Draft</button>
    <button type="submit" name="action" value="save_open"  class="btn-save-open"
            onclick="return validateBeforeSave(event)">Save as Open</button>
    <button type="button" class="btn-cancel"
            onclick="window.location='{{ route('credit-notes.index') }}'">Cancel</button>
    <span class="pdf-template-hint">
        PDF Template: 'Spreadsheet Template' &nbsp;<a href="#">Change</a>
    </span>
</div>

    </form>

</div>
@endsection

@push('scripts')
<script>
// ── Products data from PHP ────────────────────────────────────────
const CN_PRODUCTS = JSON.parse(
    document.getElementById('cn-products-data').textContent
);

// ── Build select options HTML from products data ──────────────────
function buildProductOptions(selectedProductId, selectedSku) {
    let html = '<option value="">Type or click to select an item</option>';
    CN_PRODUCTS.forEach(p => {
        if (p.variants && p.variants.length > 0) {
            html += `<optgroup label="${p.name.replace(/"/g,'&quot;')}">`;
            p.variants.forEach(v => {
                const sel = (String(p.id) === String(selectedProductId) && v.sku === selectedSku)
                    ? 'selected' : '';
                html += `<option value="${p.id}" ${sel}
                    data-variant-id="${v.id}"
                    data-name="${(p.name+' - '+v.name).replace(/"/g,'&quot;')}"
                    data-rate="${v.rate}"
                    data-sku="${v.sku}"
                    data-unit="${p.unit}"
                    data-gst="${p.gst}"
                    data-img="${p.img}">
                    ${v.name}${v.sku ? ' ['+v.sku+']' : ''} — ₹${parseFloat(v.rate).toFixed(2)}
                </option>`;
            });
            html += `</optgroup>`;
        } else {
            const sel = String(p.id) === String(selectedProductId) ? 'selected' : '';
            html += `<option value="${p.id}" ${sel}
                data-variant-id=""
                data-name="${p.name.replace(/"/g,'&quot;')}"
                data-rate="${p.rate.toFixed ? p.rate.toFixed(2) : p.rate}"
                data-sku="${p.sku}"
                data-unit="${p.unit}"
                data-gst="${p.gst}"
                data-img="${p.img}">
                ${p.name}${p.sku ? ' ['+p.sku+']' : ''} — ₹${parseFloat(p.rate).toFixed(2)}
            </option>`;
        }
    });
    return html;
}

// ── Populate all existing selects on page load ────────────────────
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.line-item-row').forEach(row => {
        const idx      = row.dataset.index;
        const sel      = document.getElementById('cn-psel-' + idx);
        if (!sel) return;

        const hiddenPid = row.querySelector('[name*="[product_id]"]');
        const hiddenSku = row.querySelector('[name*="[item_sku]"]');
        const pid       = hiddenPid?.value || '';
        const sku       = hiddenSku?.value || '';

        sel.innerHTML = buildProductOptions(pid, sku);

        // If edit mode — show meta
        if (pid) {
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.value) {
                updateRowMeta(idx, opt);
            }
        }
    });
});

// ── Fill product when selected ────────────────────────────────────
function cnFillProduct(idx, sel) {
    const opt = sel.options[sel.selectedIndex];
    const pid = sel.value;
    // Hidden fields
    const row      = sel.closest('tr');
    const hidPid   = row.querySelector('[name*="[product_id]"]');
    const hidSku   = row.querySelector('[name*="[item_sku]"]');
    const hidUnit  = row.querySelector('[name*="[unit]"]');
    const hidName  = document.getElementById('cn-iname-'    + idx);
    const hidVar   = document.getElementById('cn-ivariant-' + idx);

    if (!pid) {
        if (hidPid)  hidPid.value  = '';
        if (hidSku)  hidSku.value  = '';
        if (hidUnit) hidUnit.value = '';
        if (hidName) hidName.value = '';
        if (hidVar)  hidVar.value  = '';
        document.getElementById('cn-imeta-' + idx).innerHTML = '';
        document.getElementById('cn-iimg-'  + idx).innerHTML = '🖼';
        row.querySelector('.rate').value = '0.00';
        cnRecalcRow(row);
        return;
    }

    const name      = opt.dataset.name      || '';
    const rate = opt.dataset.rate || '0.00'; 
    const sku       = opt.dataset.sku       || '';
    const unit      = opt.dataset.unit      || '';
    const variantId = opt.dataset.variantId || '';
    const img       = opt.dataset.img       || '';

    if (hidPid)  hidPid.value  = pid;
    if (hidSku)  hidSku.value  = sku;
    if (hidUnit) hidUnit.value = unit;
    if (hidName) hidName.value = name;
    if (hidVar)  hidVar.value  = variantId;

   row.querySelector('.rate').value = rate; 
    const rateInput = row.querySelector('.rate');
    if (!rateInput.readOnly) {  // source invoice rows are readonly
        rateInput.value = rate.toFixed(2);
    }
    
    updateRowMeta(idx, opt);
    cnRecalcRow(row);
    cnRecalcAll();
}

function updateRowMeta(idx, opt) {
    const sku  = opt.dataset.sku  || '';
    const unit = opt.dataset.unit || '';
    const img  = opt.dataset.img  || '';

    const imgBox = document.getElementById('cn-iimg-' + idx);
    if (imgBox) {
        imgBox.innerHTML = img
            ? `<img src="${img}" style="width:100%;height:100%;object-fit:cover;border-radius:3px;">`
            : '🖼';
    }

    const metaEl = document.getElementById('cn-imeta-' + idx);
    if (metaEl) {
        const parts = [];
        if (sku)  parts.push(`SKU: <strong>${sku}</strong>`);
        if (unit) parts.push(`Unit: ${unit}`);
        metaEl.innerHTML = parts.join(' &nbsp;|&nbsp; ');
    }
}

// ── Row calculation ───────────────────────────────────────────────
function cnRecalcRow(row) {
    const qty  = parseFloat(row.querySelector('.qty')?.value)  || 0;
    const rate = parseFloat(row.querySelector('.rate')?.value) || 0;
    row.querySelector('.row-amount').textContent = (qty * rate).toFixed(2);
}

function cnRecalcAll() {
    let sub = 0;
    document.querySelectorAll('.line-item-row').forEach(row => {
        cnRecalcRow(row);
        sub += parseFloat(row.querySelector('.row-amount')?.textContent) || 0;
    });
    const disc    = parseFloat(document.getElementById('discount-pct')?.value) || 0;
    const adj     = parseFloat(document.getElementById('adjustment')?.value)   || 0;
    const discAmt = sub * disc / 100;
    const total   = sub - discAmt + adj;
    document.getElementById('summary-subtotal').textContent   = sub.toFixed(2);
    document.getElementById('summary-discount').textContent   = discAmt.toFixed(2);
    document.getElementById('summary-adjustment').textContent = adj.toFixed(2);
    document.getElementById('summary-total').textContent      = total.toFixed(2);

    // ✅ NEW: Max returnable warning
    if (typeof window.SOURCE_REMAINING_RETURNABLE !== 'undefined') {
        const maxRet = window.SOURCE_REMAINING_RETURNABLE;
        const already = window.SOURCE_ALREADY_CREDITED || 0;
        let warnEl = document.getElementById('max-return-warn');

        if (total > maxRet + 0.01) {
            if (!warnEl) {
                warnEl = document.createElement('div');
                warnEl.id = 'max-return-warn';
               warnEl.style.cssText = 'background:#fef2f2;color:#dc2626;padding:6px 10px;border-radius:5px;font-size:11px;margin-top:8px;border:1px solid #fecaca;font-weight:500;display:inline-block;';
             document.querySelector('.totals-box')?.appendChild(warnEl);
            }
            warnEl.textContent = '⚠️ Maximum returnable: ₹' + maxRet.toFixed(2)
                + (already > 0 ? ' (₹' + already.toFixed(2) + ' already credited)' : '');
        } else {
            warnEl?.remove();
        }
    }
}
function validateBeforeSave(e) {
    if (typeof window.SOURCE_REMAINING_RETURNABLE === 'undefined') return true;

    const maxRet = window.SOURCE_REMAINING_RETURNABLE;
    const totalEl = document.getElementById('summary-total');
    const total = parseFloat(totalEl?.textContent || '0');

    if (total > maxRet + 0.01) {
        e.preventDefault();
        // Warning already showing — shake it
        const warnEl = document.getElementById('max-return-warn');
        if (warnEl) {
            warnEl.style.transition = 'transform 0.1s';
            warnEl.style.transform = 'scale(1.05)';
            setTimeout(() => warnEl.style.transform = 'scale(1)', 200);
        }
        alert('⚠️ Credit amount ₹' + total.toFixed(2) + ' exceeds maximum returnable ₹' + maxRet.toFixed(2) + '. Please reduce the return quantity.');
        return false;
    }
    return true;
}


// ── Add new row ───────────────────────────────────────────────────
document.getElementById('add-row').addEventListener('click', function () {
    const idx = document.querySelectorAll('.line-item-row').length;
    const tr  = document.createElement('tr');
    tr.className     = 'line-item-row';
    tr.dataset.index = idx;
    tr.innerHTML = `
        <td class="item-search-cell">
            <input type="hidden" name="line_items[${idx}][product_id]" value="">
            <input type="hidden" name="line_items[${idx}][item_sku]"   value="">
            <input type="hidden" name="line_items[${idx}][item_type]"  value="">
            <input type="hidden" name="line_items[${idx}][unit]"       value="">
            <input type="hidden" name="line_items[${idx}][item_name]"  id="cn-iname-${idx}" value="">
            <input type="hidden" name="line_items[${idx}][variant_id]" id="cn-ivariant-${idx}" value="">
            <div class="item-search-wrap">
                <div class="item-thumb" id="cn-iimg-${idx}">🖼</div>
                <div style="flex:1">
                    <select id="cn-psel-${idx}"
                            onchange="cnFillProduct(${idx}, this)"
                            style="width:100%;border:none;border-bottom:1px solid #e0e0e0;
                                   background:transparent;font-size:13px;color:#555;outline:none;padding:2px 0;">
                    </select>
                    <div style="font-size:11px;color:#888;margin-top:2px;" id="cn-imeta-${idx}"></div>
                </div>
            </div>
        </td>
        <td>
            <select name="line_items[${idx}][account_id]" class="account-select">
                <option value="">Select an account</option>
            </select>
        </td>
        <td style="text-align:right;color:#aaa;font-size:12px;">—</td>
        <td style="text-align:right">
            <input type="number" name="line_items[${idx}][quantity]"
                   class="qty" style="text-align:right"
                   value="1.00" step="0.0001" min="0" required
                   oninput="cnRecalcAll()">
        </td>
        <td style="text-align:right">
            <input type="number" name="line_items[${idx}][rate]"
                   class="rate" style="text-align:right"
                   value="0.00" step="0.01" min="0" required
                   oninput="cnRecalcAll()">
        </td>
        <td style="text-align:right">
            <span class="row-amount" style="font-size:13px;font-weight:500">0.00</span>
        </td>
        <td><button type="button" class="remove-row-btn">×</button></td>`;

    document.getElementById('line-items-body').appendChild(tr);

    // Populate select
    document.getElementById('cn-psel-' + idx).innerHTML = buildProductOptions('', '');
    cnRecalcAll();
});

// ── Remove row ────────────────────────────────────────────────────
document.getElementById('line-items-body').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row-btn')) {
        const rows = document.querySelectorAll('.line-item-row');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            cnRecalcAll();
        }
    }
});

// ── Discount + Adjustment listeners ──────────────────────────────
document.getElementById('discount-pct')?.addEventListener('input', cnRecalcAll);
document.getElementById('adjustment')?.addEventListener('input',   cnRecalcAll);

// ── Qty/Rate listeners for existing rows ─────────────────────────
document.querySelectorAll('.line-item-row').forEach(row => {
    row.querySelectorAll('.qty, .rate').forEach(inp => {
        inp.addEventListener('input', cnRecalcAll);
    });
});

// ── Customer → Locations → Series ────────────────────────────────
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('customer_id').addEventListener('change', function () {
    const customerId = this.value;
    document.getElementById('customer-address-block').style.display = 'none';
    resetLocations();
    if (!customerId) return;

    fetch(`/customers/${customerId}/details`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        const hasAddress = data.billing && Object.values(data.billing).some(v => v);
        if (hasAddress) {
            document.getElementById('billing-address').innerHTML  = formatAddress(data.billing, data.phone);
            document.getElementById('shipping-address').innerHTML = formatAddress(data.shipping, null);
            document.getElementById('customer-address-block').style.display = 'block';
        }
        document.getElementById('customer-category').textContent = data.category || '';
        document.getElementById('customer-category-value').value = data.category || '';
    })
    .catch(() => {});

    fetch(`/credit-notes/customer-defaults?customer_id=${customerId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.category || !data.locations || !data.locations.length) {
            resetLocations(); return;
        }
        const locSel = document.getElementById('location_select');
        locSel.innerHTML = '<option value="">— Select Location —</option>';
        data.locations.forEach(loc => {
            const opt       = document.createElement('option');
            opt.value       = loc.id;
            opt.textContent = loc.location_name;
            locSel.appendChild(opt);
        });
        window._cnLocations = data.locations;

        if (data.locations.length >= 1) {
            const firstLoc = data.locations[0];
            locSel.value   = firstLoc.id;
            buildSeriesDropdown(firstLoc.series, firstLoc.default_series_id);
            const defaultId = firstLoc.default_series_id
                || (firstLoc.series && firstLoc.series[0] ? firstLoc.series[0].id : null);
            if (defaultId) {
                document.getElementById('series_select').value = defaultId;
                setCNNumber(firstLoc.series, defaultId);
            }
        }
    })
    .catch(err => console.error('customer-defaults error:', err));
});

document.getElementById('location_select').addEventListener('change', function () {
    const locationId = this.value;
    if (!locationId) { resetSeries(); return; }
    const locs       = window._cnLocations || [];
    const matchedLoc = locs.find(l => String(l.id) === String(locationId));
    if (matchedLoc && matchedLoc.series && matchedLoc.series.length > 0) {
        buildSeriesDropdown(matchedLoc.series, matchedLoc.default_series_id);
        const defaultId = matchedLoc.default_series_id || matchedLoc.series[0].id;
        document.getElementById('series_select').value = defaultId;
        setCNNumber(matchedLoc.series, defaultId);
    } else {
        resetSeries();
    }
});

document.getElementById('series_select').addEventListener('change', function () {
    const seriesId   = this.value;
    const locationId = document.getElementById('location_select').value;
    const locs       = window._cnLocations || [];
    const loc        = locs.find(l => String(l.id) === String(locationId));
    if (loc && loc.series) setCNNumber(loc.series, seriesId);
});

function buildSeriesDropdown(seriesList, defaultId) {
    const sel = document.getElementById('series_select');
    sel.innerHTML = '<option value="">— Select Series —</option>';
    if (!seriesList || !seriesList.length) return;
    seriesList.forEach(s => {
        const opt       = document.createElement('option');
        opt.value       = s.id;
        opt.textContent = s.name + (s.preview ? '  (' + s.preview + ')' : '');
        opt.dataset.preview = s.preview || '';
        if (String(s.id) === String(defaultId)) opt.selected = true;
        sel.appendChild(opt);
    });
}

function setCNNumber(seriesList, seriesId) {
    if (!seriesList || !seriesId) return;
    const found = seriesList.find(s => String(s.id) === String(seriesId));
    if (found && found.preview) {
        document.querySelector('input[name="credit_note_number"]').value = found.preview;
    }
}

function resetLocations() {
    document.getElementById('location_select').innerHTML = '<option value="">— Select Location —</option>';
    window._cnLocations = [];
    resetSeries();
}

function resetSeries() {
    document.getElementById('series_select').innerHTML = '<option value="">— Select Series —</option>';
}

function formatAddress(addr, phone) {
    if (!addr) return '<span style="color:#bbb">—</span>';
    const parts = [
        addr.attention, addr.street1, addr.street2,
        [addr.city, addr.pincode].filter(Boolean).join(' - '),
        addr.state, addr.country,
        phone ? ('📞 ' + phone) : null,
    ].filter(Boolean);
    return parts.length ? parts.join('<br>') : '<span style="color:#bbb">No address on file</span>';
}
@if(isset($creditNote) && $creditNote->customer_id)
window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('customer_id').value = '{{ $creditNote->customer_id }}';
    document.getElementById('customer_id').dispatchEvent(new Event('change'));
});
@endif

// ── Pre-fill from Invoice (3-dot → Create Credit Note) ──────────────
@if(isset($sourceInvoice) && $sourceInvoice)
@php
    // ✅ NEW: Already applied credits கழித்து remaining கணக்கிடு
    $alreadyAppliedCredit = \DB::table('payments_record')
        ->where('invoice_id', $sourceInvoice->id)
        ->where('payment_mode', 'credit_note')
        ->where('status', '!=', 'refunded')
        ->sum('amount_received');

    $sourceGrandTotal    = floatval($sourceInvoice->grand_total);
    $remainingReturnable = max(0, $sourceGrandTotal - $alreadyAppliedCredit);

    $invoiceItemsJson = $sourceInvoice->items->map(fn($item) => [
        'product_id' => $item->product_id,
        'item_name'  => $item->item_name,
        'item_sku'   => $item->item_sku ?? '',
        'item_type'  => $item->item_type ?? '',
        'unit'       => $item->unit ?? '',
        'quantity'   => $item->quantity,
        'rate'       => $item->rate,
    ]);
@endphp

window.SOURCE_REMAINING_RETURNABLE = {{ $remainingReturnable }};
window.SOURCE_ALREADY_CREDITED = {{ $alreadyAppliedCredit }};
window.addEventListener('DOMContentLoaded', function () {

    const custSel = document.getElementById('customer_id');
    custSel.value = '{{ $sourceInvoice->customer_id }}';
    custSel.dispatchEvent(new Event('change'));

    const refInput = document.querySelector('input[name="reference_number"]');
    if (refInput) refInput.value = '{{ $sourceInvoice->invoice_number }}';

    const form = document.querySelector('form');
    const hidInv = document.createElement('input');
    hidInv.type  = 'hidden';
    hidInv.name  = 'source_invoice_id';
    hidInv.value = '{{ $sourceInvoice->id }}';
    form.appendChild(hidInv);


    const SOURCE_INVOICE_ITEMS = @json($invoiceItemsJson);

    setTimeout(function () {
        const body  = document.getElementById('line-items-body');
        body.innerHTML = '';

        const items = SOURCE_INVOICE_ITEMS;

        items.forEach(function(item, idx) {
            const tr = document.createElement('tr');
            tr.className     = 'line-item-row';
            tr.dataset.index = idx;
            tr.innerHTML = `
                <td class="item-search-cell">
                    <input type="hidden" name="line_items[${idx}][product_id]" value="${item.product_id || ''}">
                    <input type="hidden" name="line_items[${idx}][item_sku]"   value="${item.item_sku || ''}">
                    <input type="hidden" name="line_items[${idx}][item_type]"  value="${item.item_type || ''}">
                    <input type="hidden" name="line_items[${idx}][unit]"       value="${item.unit || ''}">
                    <input type="hidden" name="line_items[${idx}][item_name]"  id="cn-iname-${idx}"    value="${item.item_name || ''}">
                    <input type="hidden" name="line_items[${idx}][variant_id]" id="cn-ivariant-${idx}" value="">
                    <div class="item-search-wrap">
                        <div class="item-thumb" id="cn-iimg-${idx}">🖼</div>
                        <div style="flex:1">
                            <select id="cn-psel-${idx}"
                                    onchange="cnFillProduct(${idx}, this)"
                                    style="width:100%;border:none;border-bottom:1px solid #e0e0e0;
                                           background:transparent;font-size:13px;color:#333;
                                           outline:none;padding:2px 0;">
                            </select>
                            <div style="font-size:11px;color:#888;margin-top:2px;"
                                 id="cn-imeta-${idx}"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <select name="line_items[${idx}][account_id]" class="account-select">
                        <option value="">Select an account</option>
                    </select>
                </td>
                <td style="text-align:right;color:#aaa;font-size:12px;">
                    <span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;">
                        ${parseFloat(item.quantity).toFixed(2)}
                    </span>
                </td>
                <td style="text-align:right">
                <input type="number" name="line_items[${idx}][quantity]"
                    class="qty" style="text-align:right"
                    value="${parseFloat(item.quantity).toFixed(2)}"
                    step="0.0001" min="0"
                    max="${parseFloat(item.quantity)}"
                    oninput="validateReturnQty(this); cnRecalcAll()" required>
            </td>
                <td style="text-align:right">
    <input type="number" name="line_items[${idx}][rate]"
           class="rate"
           value="${parseFloat(item.rate).toFixed(2)}"
           step="0.01" min="0" required
           readonly
           oninput="cnRecalcAll()"
           style="text-align:right;background:#f8f9fa;color:#555;border:none;width:100%;">
</td>
                <td style="text-align:right">
                    <span class="row-amount" style="font-size:13px;font-weight:500">
                        ${(parseFloat(item.quantity) * parseFloat(item.rate)).toFixed(2)}
                    </span>
                </td>
                <td><button type="button" class="remove-row-btn">×</button></td>`;

            body.appendChild(tr);

        const sel = document.getElementById('cn-psel-' + idx);
            sel.innerHTML = buildProductOptions(item.product_id, item.item_sku || '');

            // ✅ Find the selected option and update meta + hidden fields
            let selectedOpt = null;
            for (let i = 0; i < sel.options.length; i++) {
                if (String(sel.options[i].value) === String(item.product_id) && sel.options[i].value !== '') {
                    sel.selectedIndex = i;
                    selectedOpt = sel.options[i];
                    break;
                }
            }

            if (selectedOpt) {
                // Update hidden item_name with product name from option
                const hidName = document.getElementById('cn-iname-' + idx);
                if (hidName && !hidName.value) hidName.value = selectedOpt.dataset.name || item.item_name || '';

                updateRowMeta(idx, selectedOpt);
            }
        });

        cnRecalcAll();
    }, 800);
});
@endif

function validateReturnQty(input) {
    const max = parseFloat(input.max);
    if (parseFloat(input.value) > max) {
        input.value = max.toFixed(4);
    }
    if (parseFloat(input.value) < 0) {
        input.value = 0;
    }
}
cnRecalcAll();
</script>
@endpush