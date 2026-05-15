{{-- resources/views/inventory/adjustments/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Adjustment')

@push('styles')
<style>
    /* ── Layout ─────────────────────────────────────────── */
    .adj-wrapper {
        background: #fff;
        border: 1px solid #dde3ee;
        border-radius: 6px;
        padding: 24px 32px 32px;
        max-width: 1100px;
        margin: 20px auto;
    }
    .adj-title {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 24px;
    }

    /* ── Form fields ─────────────────────────────────────── */
    .form-group { margin-bottom: 16px; display: flex; align-items: flex-start; gap: 16px; }
    .form-group label {
        min-width: 160px;
        font-size: 13px;
        color: #333;
        padding-top: 8px;
    }
    .form-group label.required::after { content: ' *'; color: #e05252; }
    .form-group label.custom-required { color: #e05252; }
    .form-group label.custom-required::after { content: ' *'; color: #e05252; }
    .form-group .field { flex: 1; max-width: 320px; }
    .form-control {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #c8d0e0;
        border-radius: 4px;
        font-size: 13px;
        color: #333;
        background: #fff;
        outline: none;
        transition: border-color .15s;
    }
    .form-control:focus { border-color: #3d8ef8; box-shadow: 0 0 0 2px rgba(61,142,248,.15); }
    textarea.form-control { resize: vertical; min-height: 80px; }
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23888'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }

    /* Radio group */
    .radio-group { display: flex; flex-direction: column; gap: 6px; padding-top: 4px; }
    .radio-group label { min-width: unset; padding: 0; display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: #333; }
    .radio-group input[type=radio] { accent-color: #3d8ef8; width: 15px; height: 15px; }

    /* ── Item Table ───────────────────────────────────────── */
    .item-table-section { margin-top: 24px; }
    .item-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .item-table-header h3 { font-size: 14px; font-weight: 600; color: #333; }
    .bulk-actions-btn {
        font-size: 13px;
        color: #3d8ef8;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .item-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .item-table thead tr { background: #f5f7fc; }
    .item-table thead th {
        padding: 9px 12px;
        text-align: left;
        font-weight: 500;
        color: #555;
        font-size: 12px;
        border: 1px solid #e4e8f0;
    }
    .item-table thead th:first-child { width: 36px; }
    .item-table tbody td {
        padding: 10px 12px;
        border: 1px solid #e4e8f0;
        vertical-align: top;
    }
    .item-table tbody tr:hover { background: #fafbff; }

    /* Item details cell */
    .item-details-cell { display: flex; align-items: flex-start; gap: 10px; }
    .item-thumb {
        width: 36px;
        height: 36px;
        border: 1px solid #dde3ee;
        border-radius: 3px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f0f2f8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 18px;
    }
    .item-info .item-name { font-weight: 500; color: #222; }
    .item-info .item-sku  { font-size: 11px; color: #888; margin-top: 2px; }
    .item-row-actions { display: flex; gap: 4px; margin-left: auto; }
    .item-row-actions button {
        background: none;
        border: none;
        cursor: pointer;
        color: #bbb;
        padding: 2px;
        font-size: 15px;
        line-height: 1;
    }
    .item-row-actions button:hover { color: #e05252; }

    /* Qty / value cells */
    .qty-cell { text-align: right; color: #333; font-weight: 500; }
    .qty-unit  { font-size: 11px; color: #999; }
    .qty-input {
        width: 110px;
        padding: 5px 8px;
        border: 1px solid #c8d0e0;
        border-radius: 3px;
        font-size: 13px;
        text-align: right;
        color: #333;
        outline: none;
    }
    .qty-input:focus { border-color: #3d8ef8; }
    .qty-placeholder { color: #bbb; font-size: 12px; }

    /* Reporting tags row */
    .reporting-row td {
        border-top: none !important;
        padding-top: 4px !important;
        padding-bottom: 8px !important;
    }
    .reporting-tag-btn {
        background: none;
        border: none;
        font-size: 12px;
        color: #888;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0;
    }
    .cost-price-info { font-size: 12px; color: #888; }
    .cost-price-info a { color: #3d8ef8; text-decoration: none; }

    /* Add row */
    .add-row-actions { display: flex; gap: 16px; margin-top: 12px; }
    .add-row-btn {
        background: none;
        border: none;
        font-size: 13px;
        color: #3d8ef8;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0;
    }
    .add-row-btn:hover { text-decoration: underline; }

    /* Placeholder search row */
    .search-item-input {
        border: none;
        outline: none;
        font-size: 13px;
        width: 100%;
        color: #aaa;
        background: transparent;
    }
    .search-item-input:focus { color: #333; }

    /* Attachment */
    .attach-section { margin-top: 28px; }
    .attach-section h4 { font-size: 13px; font-weight: 500; color: #555; margin-bottom: 8px; }
    .upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #c8d0e0;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 13px;
        cursor: pointer;
        color: #333;
        background: #fff;
    }
    .upload-btn:hover { background: #f5f7fc; }
    .attach-note { font-size: 11px; color: #aaa; margin-top: 6px; }

    /* Footer buttons */
    .form-footer { display: flex; gap: 10px; margin-top: 28px; align-items: center; }
    .btn-primary {
        background: #3d8ef8;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 9px 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-primary:hover { background: #2d7de0; }
    .btn-secondary {
        background: #fff;
        color: #333;
        border: 1px solid #c8d0e0;
        border-radius: 4px;
        padding: 9px 20px;
        font-size: 13px;
        cursor: pointer;
    }
    .btn-secondary:hover { background: #f5f7fc; }
    .btn-link { background: none; border: none; font-size: 13px; color: #888; cursor: pointer; padding: 0; }
    .btn-link:hover { color: #333; }

    /* ── Bulk Modal ───────────────────────────────────────── */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 6px;
        width: 800px;
        max-width: 95vw;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #e4e8f0;
    }
    .modal-header h2 { font-size: 16px; font-weight: 600; color: #222; margin: 0; }
    .modal-close { background: none; border: none; font-size: 20px; cursor: pointer; color: #888; }
    .modal-body { display: flex; flex: 1; overflow: hidden; }
    .modal-left {
        width: 55%;
        border-right: 1px solid #e4e8f0;
        overflow-y: auto;
        padding: 12px 0;
    }
    .modal-search { padding: 8px 14px; margin-bottom: 8px; }
    .modal-search input {
        width: 100%;
        padding: 7px 12px;
        border: 1px solid #c8d0e0;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
    }
    .modal-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f2f8;
    }
    .modal-item-row:hover, .modal-item-row.selected { background: #eef4ff; }
    .modal-item-name { font-size: 13px; font-weight: 500; color: #3d8ef8; }
    .modal-item-sku  { font-size: 11px; color: #999; }
    .modal-item-stock { text-align: right; }
    .modal-item-stock .label { font-size: 11px; color: #888; }
    .modal-item-stock .qty   { font-size: 13px; font-weight: 500; }
    .modal-item-stock .qty.low { color: #e05252; }
    .modal-item-stock .unit  { font-size: 11px; color: #999; }
    .modal-check { color: #3d8ef8; font-size: 18px; }
    .modal-right { width: 45%; padding: 16px; overflow-y: auto; }
    .modal-right-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .modal-right-header h3 { font-size: 14px; font-weight: 500; color: #333; }
    .total-qty { font-size: 13px; color: #555; }
    .selected-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #f0f2f8;
        font-size: 13px;
    }
    .selected-placeholder { color: #bbb; font-size: 13px; margin-top: 40px; text-align: center; }
    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding: 12px 16px;
        border-top: 1px solid #e4e8f0;
    }

    /* Validation errors */
    .error-msg { color: #e05252; font-size: 12px; margin-top: 3px; }
    .is-invalid { border-color: #e05252 !important; }
</style>
@endpush

@section('content')
<div class="adj-wrapper">
    <div class="adj-title">Edit Adjustment</div>

    <form id="adjustmentForm"
          action="{{ route('inventory.adjustments.update', $adjustment) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── Mode ──────────────────────────────────────────── --}}
        <div class="form-group">
            <label>Mode of adjustment</label>
            <div class="field">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="mode" value="quantity" id="modeQty"
                            {{ old('mode', $adjustment->mode) === 'quantity' ? 'checked' : '' }}>
                        Quantity Adjustment
                    </label>
                    <label>
                        <input type="radio" name="mode" value="value" id="modeVal"
                            {{ old('mode', $adjustment->mode) === 'value' ? 'checked' : '' }}>
                        Value Adjustment
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Reference Number ─────────────────────────────── --}}
        <div class="form-group">
            <label for="reference_number">Reference Number</label>
            <div class="field">
                <input type="text" id="reference_number" name="reference_number"
                    class="form-control @error('reference_number') is-invalid @enderror"
                    value="{{ old('reference_number', $adjustment->reference_number) }}">
                @error('reference_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Date ─────────────────────────────────────────── --}}
        <div class="form-group">
            <label for="date" class="required">Date</label>
            <div class="field">
                <input type="date" id="date" name="date"
                    class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', \Carbon\Carbon::parse($adjustment->date)->format('Y-m-d')) }}">
                @error('date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Account ───────────────────────────────────────── --}}
        <div class="form-group">
            <label for="account" class="required">Account</label>
            <div class="field">
                <select id="account" name="account"
                    class="form-control @error('account') is-invalid @enderror">
                    <option value="">-- Select Account --</option>
                    @foreach($accounts as $group => $accountList)
                        <optgroup label="{{ $group }}">
                            @foreach($accountList as $acc)
                                <option value="{{ $acc }}"
                                    {{ old('account', $adjustment->account) === $acc ? 'selected' : '' }}>
                                    {{ $acc }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('account')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Reason ────────────────────────────────────────── --}}
        <div class="form-group">
            <label for="reason" class="required">Reason</label>
            <div class="field">
                <select id="reason" name="reason"
                    class="form-control @error('reason') is-invalid @enderror">
                    <option value="">-- Select Reason --</option>
                    @foreach($reasons as $r)
                        <option value="{{ $r }}"
                            {{ old('reason', $adjustment->reason) === $r ? 'selected' : '' }}>
                            {{ $r }}
                        </option>
                    @endforeach
                </select>
                @error('reason')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Location ──────────────────────────────────────── --}}
        <div class="form-group">
            <label for="location_id" class="required">Location</label>
            <div class="field">
                <select id="location_id" name="location_id" class="form-control">
                    <option value="">-- Select Location --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}"
                            {{ old('location_id', $adjustment->location_id) == $loc->id ? 'selected' : '' }}>
                            {{ $loc->location_name }}
                        </option>
                    @endforeach
                </select>
                @error('location_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Description ───────────────────────────────────── --}}
        <div class="form-group">
            <label for="description">Description</label>
            <div class="field">
                <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Max. 500 characters"
                    maxlength="500">{{ old('description', $adjustment->description) }}</textarea>
                @error('description')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Product Rules ─────────────────────────────────── --}}
        <div class="form-group">
            <label for="product_rules" class="custom-required">product rules</label>
            <div class="field">
                <select id="product_rules" name="product_rules"
                    class="form-control @error('product_rules') is-invalid @enderror">
                    <option value="">-- Select Product Rule --</option>
                    @foreach($productRules as $rule)
                        <option value="{{ $rule }}"
                            {{ old('product_rules', $adjustment->product_rules) === $rule ? 'selected' : '' }}>
                            {{ $rule }}
                        </option>
                    @endforeach
                </select>
                @error('product_rules')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Item Table ─────────────────────────────────────── --}}
        <div class="item-table-section">
            <div class="item-table-header">
                <h3>Item Table</h3>
                <button type="button" class="bulk-actions-btn" onclick="openBulkModal()">
                    ⚙ Bulk Actions
                </button>
            </div>

            <table class="item-table" id="itemTable">
                <thead>
                    <tr id="tableHead">
                        <th></th>
                        <th>ITEM DETAILS</th>
                        <th id="col3Head" style="text-align:right">QUANTITY AVAILABLE</th>
                        <th id="col4Head" style="text-align:right">NEW QUANTITY ON HAND</th>
                        <th id="col5Head" style="text-align:right">QUANTITY ADJUSTED</th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                    {{-- Rows injected by JS --}}
                </tbody>
            </table>

            <div class="add-row-actions">
                <button type="button" class="add-row-btn" onclick="addBlankRow()">
                    ➕ Add New Row
                </button>
                <button type="button" class="add-row-btn" onclick="openBulkModal()">
                    ➕ Add Items in Bulk
                </button>
            </div>
        </div>

        {{-- ── Attachments ───────────────────────────────────── --}}
        <div class="attach-section">
            <h4>Attach File(s) to inventory adjustment</h4>
            <label class="upload-btn">
                ↑ Upload File
                <input type="file" name="attachments[]" multiple accept="*/*"
                    style="display:none">
                <span>▾</span>
            </label>
            <div class="attach-note">You can upload a maximum of 5 files, 10MB each</div>
        </div>

        {{-- ── Footer Buttons ────────────────────────────────── --}}
        <div class="form-footer">
            <button type="submit" name="status" value="draft" class="btn-primary">
                Save
            </button>
            <button type="submit" name="status" value="adjusted" class="btn-secondary"
                onclick="return confirm('Convert this draft to Adjusted? Stock will be updated.')">
                Convert to Adjusted
            </button>
            <a href="{{ route('inventory.adjustments.show', $adjustment) }}" class="btn-link">Cancel</a>
        </div>

    </form>
</div>

{{-- ── Bulk Modal ────────────────────────────────────────────────────────── --}}
<div class="modal-overlay" id="bulkModal">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Add Items in Bulk</h2>
            <button class="modal-close" onclick="closeBulkModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-left">
                <div class="modal-search">
                    <input type="text" id="modalSearch"
                        placeholder="Type to search or scan the barcode of the item"
                        oninput="filterModalItems(this.value)">
                </div>
                <div id="modalItemList"></div>
            </div>
            <div class="modal-right">
                <div class="modal-right-header">
                    <h3>Selected Items <span id="selectedCount"
                        style="background:#e4e8f0;border-radius:50%;padding:1px 7px;font-size:12px;">0</span>
                    </h3>
                    <span class="total-qty">Total Quantity: <strong id="totalQty">0</strong></span>
                </div>
                <div id="selectedItemsPanel">
                    <div class="selected-placeholder">
                        Click the item names from the left pane to select them
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-primary" onclick="confirmBulkAdd()">Add Items</button>
            <button class="btn-secondary" onclick="closeBulkModal()">Cancel</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Data from controller ──────────────────────────────────────────────────
let ALL_ITEMS = {!! json_encode($itemsJson) !!};

// Existing saved items (pre-load from DB)
// Each entry: { item: {...}, rowIndex, savedNewQty, savedQtyAdjusted, savedChangedValue, savedAdjustedValue }
let tableItems = [];
let bulkSelected = new Set();

let currentMode = document.querySelector('input[name="mode"]:checked').value;

// ─── Existing adjustment items from DB ────────────────────────────────────
// Controller must pass $existingItems as JSON:
// [{ product_id, variant_id, variant_name, new_quantity_on_hand, quantity_adjusted,
//    changed_value, adjusted_value, current_value, quantity_available }, ...]
let EXISTING_ITEMS = {!! json_encode(
    $adjustment->items->map(function($item) {
        return [
            'product_id'           => $item->product_id,
            'variant_id'           => $item->variant_id,
            'variant_name'         => $item->variant_name,
            'new_quantity_on_hand' => $item->new_quantity_on_hand,
            'quantity_adjusted'    => $item->quantity_adjusted,
            'changed_value'        => $item->changed_value,
            'adjusted_value'       => $item->adjusted_value,
        ];
    })
) !!};

// ─── Mode switch ──────────────────────────────────────────────────────────
document.querySelectorAll('input[name="mode"]').forEach(r => {
    r.addEventListener('change', function () {
        currentMode = this.value;
        updateTableHeaders();
        rerenderAllRows();
    });
});

function updateTableHeaders() {
    if (currentMode === 'quantity') {
        document.getElementById('col3Head').textContent = 'QUANTITY AVAILABLE';
        document.getElementById('col4Head').textContent = 'NEW QUANTITY ON HAND';
        document.getElementById('col5Head').textContent = 'QUANTITY ADJUSTED';
    } else {
        document.getElementById('col3Head').textContent = 'CURRENT VALUE';
        document.getElementById('col4Head').textContent = 'CHANGED VALUE';
        document.getElementById('col5Head').textContent = 'ADJUSTED VALUE';
    }
}

// ─── Build a single item row ───────────────────────────────────────────────
function buildRow(entry) {
    const item      = entry.item;
    const rowIndex  = entry.rowIndex;
    const isQty     = currentMode === 'quantity';

    // Saved values (for editing existing rows)
    const savedNewQty   = entry.savedNewQty   ?? '';
    const savedQtyAdj   = entry.savedQtyAdj   ?? '';
    const savedChanged  = entry.savedChanged  ?? '0.00';
    const savedAdjVal   = entry.savedAdjVal   ?? '';

    const col3Val = isQty
        ? `<div class="qty-cell">${Number(item.quantity_on_hand).toFixed(4)}<br><span class="qty-unit">${item.unit}</span></div>`
        : `<div class="qty-cell">₹${Number(item.current_value).toLocaleString('en-IN', {minimumFractionDigits:2})}</div>`;

    const col4Input = isQty
        ? `<input type="number" step="any" class="qty-input"
               name="items[${rowIndex}][new_quantity_on_hand]"
               placeholder="Eg. +10, -10"
               value="${escAttr(savedNewQty)}"
               oninput="calcAdjusted(${rowIndex})">`
        : `<input type="number" step="any" class="qty-input"
               name="items[${rowIndex}][changed_value]"
               placeholder="Eg. +10, -10"
               value="${escAttr(savedChanged)}"
               oninput="calcAdjustedVal(${rowIndex})">`;

    const col5Input = isQty
        ? `<input type="number" step="any" class="qty-input"
               name="items[${rowIndex}][quantity_adjusted]"
               placeholder="Eg. +10, -10"
               value="${escAttr(savedQtyAdj)}"
               oninput="calcNewQty(${rowIndex})">`
        : `<input type="number" step="any" class="qty-input"
               name="items[${rowIndex}][adjusted_value]"
               placeholder="Eg. +10, -10"
               value="${escAttr(savedAdjVal)}"
               oninput="calcChangedVal(${rowIndex})">`;

    return `
    <tr class="item-data-row" id="row_${rowIndex}">
        <td>
            <div style="display:flex;align-items:center;gap:4px;">
                <span style="color:#ccc;cursor:grab;">⠿</span>
            </div>
        </td>
        <td>
            <div class="item-details-cell">
                <div class="item-thumb">📦</div>
                <div class="item-info">
                    <div class="item-name">${escHtml(item.name)}</div>
                    ${item.sku ? `<div class="item-sku">SKU: ${escHtml(item.sku)}</div>` : ''}
                    ${item.variant_name ? `<div class="item-sku">${escHtml(item.variant_name)}</div>` : ''}
                </div>
                <div class="item-row-actions">
                    <button type="button" onclick="removeRow(${rowIndex})" title="Remove">✕</button>
                </div>
            </div>
            <input type="hidden" name="items[${rowIndex}][item_id]"    value="${item.product_id}">
            <input type="hidden" name="items[${rowIndex}][variant_name]" value="${item.variant_name ?? ''}">
            <input type="hidden" name="items[${rowIndex}][variant_id]"  value="${item.variant_id ?? ''}">
            ${isQty
                ? `<input type="hidden" name="items[${rowIndex}][quantity_available]" value="${item.quantity_on_hand}">`
                : `<input type="hidden" name="items[${rowIndex}][current_value]"      value="${item.current_value}">`
            }
        </td>
        <td>${col3Val}</td>
        <td>${col4Input}</td>
        <td>${col5Input}</td>
    </tr>
    <tr class="reporting-row" id="reporting_${rowIndex}">
        <td></td>
        <td colspan="4" style="border-top:none;padding-top:4px;padding-bottom:10px;">
            <div style="display:flex;gap:24px;align-items:center;">
                <button type="button" class="reporting-tag-btn">🏷 Reporting Tags ▾</button>
                <span class="cost-price-info">
                    💰 Cost Price: ₹${Number(item.cost_price).toFixed(2)}
                    <a href="#" onclick="return false;">Edit</a>
                </span>
            </div>
        </td>
    </tr>`;
}

// ─── Quantity calculations ─────────────────────────────────────────────────
function calcAdjusted(rowIndex) {
    const newQtyInput = document.querySelector(`[name="items[${rowIndex}][new_quantity_on_hand]"]`);
    const adjInput    = document.querySelector(`[name="items[${rowIndex}][quantity_adjusted]"]`);
    const availHidden = document.querySelector(`[name="items[${rowIndex}][quantity_available]"]`);
    if (!newQtyInput || !adjInput || !availHidden) return;
    const avail  = parseFloat(availHidden.value) || 0;
    const newQty = parseFloat(newQtyInput.value);
    if (!isNaN(newQty)) adjInput.value = (newQty - avail).toFixed(4);
}

function calcNewQty(rowIndex) {
    const newQtyInput = document.querySelector(`[name="items[${rowIndex}][new_quantity_on_hand]"]`);
    const adjInput    = document.querySelector(`[name="items[${rowIndex}][quantity_adjusted]"]`);
    const availHidden = document.querySelector(`[name="items[${rowIndex}][quantity_available]"]`);
    if (!newQtyInput || !adjInput || !availHidden) return;
    const avail = parseFloat(availHidden.value) || 0;
    const adj   = parseFloat(adjInput.value);
    if (!isNaN(adj)) newQtyInput.value = (avail + adj).toFixed(4);
}

function calcAdjustedVal(rowIndex) {
    const changedInput  = document.querySelector(`[name="items[${rowIndex}][changed_value]"]`);
    const adjInput      = document.querySelector(`[name="items[${rowIndex}][adjusted_value]"]`);
    const currentHidden = document.querySelector(`[name="items[${rowIndex}][current_value]"]`);
    if (!changedInput || !adjInput || !currentHidden) return;
    const current = parseFloat(currentHidden.value) || 0;
    const changed = parseFloat(changedInput.value);
    if (!isNaN(changed)) adjInput.value = (current + changed).toFixed(2);
}

function calcChangedVal(rowIndex) {
    const changedInput  = document.querySelector(`[name="items[${rowIndex}][changed_value]"]`);
    const adjInput      = document.querySelector(`[name="items[${rowIndex}][adjusted_value]"]`);
    const currentHidden = document.querySelector(`[name="items[${rowIndex}][current_value]"]`);
    if (!changedInput || !adjInput || !currentHidden) return;
    const current  = parseFloat(currentHidden.value) || 0;
    const adjusted = parseFloat(adjInput.value);
    if (!isNaN(adjusted)) changedInput.value = (adjusted - current).toFixed(2);
}

// ─── Re-render all rows ────────────────────────────────────────────────────
function rerenderAllRows() {
    const tbody = document.getElementById('itemRows');
    tbody.innerHTML = '';
    tableItems.forEach((entry, idx) => {
        entry.rowIndex = idx;
        tbody.insertAdjacentHTML('beforeend', buildRow(entry));
    });
    addSearchRow();
}

// ─── Search row at bottom ──────────────────────────────────────────────────
function addSearchRow() {
    const tbody = document.getElementById('itemRows');
    tbody.insertAdjacentHTML('beforeend', `
    <tr id="searchRow">
        <td></td>
        <td><input class="search-item-input" type="text"
            placeholder="Type or click to select an item."
            oninput="filterInlineSearch(this.value)"
            onfocus="showInlineDropdown(this)"></td>
        <td><span class="qty-placeholder">0.00</span></td>
        <td></td>
        <td><span class="qty-placeholder">Eg. +10, -10</span></td>
    </tr>
    <tr id="searchRow_reporting">
        <td></td>
        <td colspan="4" style="padding-top:4px;padding-bottom:8px;border-top:none;">
            <button type="button" class="reporting-tag-btn">🏷 Reporting Tags ▾</button>
        </td>
    </tr>
    <tr id="inlineDropdownRow" style="display:none;">
        <td colspan="5" style="padding:0;border-top:none;">
            <div id="inlineDropdown" style="background:#fff;border:1px solid #dde3ee;
                border-radius:0 0 4px 4px;max-height:200px;overflow-y:auto;"></div>
        </td>
    </tr>`);
}

function filterInlineSearch(val) {
    const q    = val.toLowerCase();
    const list = q ? ALL_ITEMS.filter(i =>
        i.name.toLowerCase().includes(q) || (i.sku && i.sku.toLowerCase().includes(q))
    ) : ALL_ITEMS;
    renderInlineDropdown(list);
    document.getElementById('inlineDropdownRow').style.display = list.length ? '' : 'none';
}

function showInlineDropdown(input) {
    renderInlineDropdown(ALL_ITEMS.slice(0, 20));
    document.getElementById('inlineDropdownRow').style.display = '';
}

function renderInlineDropdown(list) {
    const dd = document.getElementById('inlineDropdown');
    if (!dd) return;
    dd.innerHTML = list.map(i => `
        <div onclick="selectInlineItem('${i.id}')"
            style="padding:8px 16px;cursor:pointer;border-bottom:1px solid #f0f2f8;font-size:13px;"
            onmouseover="this.style.background='#eef4ff'" onmouseout="this.style.background=''">
            <strong style="color:#3d8ef8">${escHtml(i.name)}</strong>
            ${i.sku ? `<span style="color:#999;margin-left:8px;font-size:11px;">SKU: ${escHtml(i.sku)}</span>` : ''}
        </div>`).join('');
}

function selectInlineItem(itemId) {
    const locationId = document.getElementById('location_id').value;
    if (!locationId) {
        alert('Please select a Location first.');
        return;
    }
    const item = ALL_ITEMS.find(i => i.id == itemId);
    if (!item) return;

    fetch(`/inventory/adjustments/items-by-location?location_id=${locationId}`)
        .then(r => r.json())
        .then(data => {
            ALL_ITEMS = data;
            const freshItem = ALL_ITEMS.find(i => i.id == itemId);
            if (freshItem) addItemToTable(freshItem);
        })
        .catch(() => { addItemToTable(item); });

    document.getElementById('inlineDropdownRow').style.display = 'none';
    const searchRow = document.getElementById('searchRow');
    if (searchRow) searchRow.querySelector('input').value = '';
}

function addItemToTable(item, savedData) {
    ['searchRow','searchRow_reporting','inlineDropdownRow'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
    const rowIndex = tableItems.length;
    const entry = {
        item,
        rowIndex,
        savedNewQty:  savedData?.new_quantity_on_hand ?? '',
        savedQtyAdj:  savedData?.quantity_adjusted    ?? '',
        savedChanged: savedData?.changed_value        ?? '0.00',
        savedAdjVal:  savedData?.adjusted_value       ?? '',
    };
    tableItems.push(entry);
    const tbody = document.getElementById('itemRows');
    tbody.insertAdjacentHTML('beforeend', buildRow(entry));
    addSearchRow();
}

function removeRow(rowIndex) {
    document.getElementById(`row_${rowIndex}`)?.remove();
    document.getElementById(`reporting_${rowIndex}`)?.remove();
    tableItems = tableItems.filter(e => e.rowIndex !== rowIndex);
    tableItems = tableItems.map((e, i) => ({ ...e, rowIndex: i }));
    rerenderAllRows();
}

function addBlankRow() {
    const inp = document.querySelector('.search-item-input');
    if (inp) inp.focus();
}

// ─── Bulk Modal ────────────────────────────────────────────────────────────
function openBulkModal() {
    bulkSelected = new Set(tableItems.map(e => e.item.id));
    renderModalList(ALL_ITEMS);
    renderSelectedPanel();
    document.getElementById('bulkModal').classList.add('open');
    document.getElementById('modalSearch').value = '';
}

function closeBulkModal() {
    document.getElementById('bulkModal').classList.remove('open');
}

function filterModalItems(q) {
    const lq   = q.toLowerCase();
    const list = q ? ALL_ITEMS.filter(i =>
        i.name.toLowerCase().includes(lq) || (i.sku && i.sku.toLowerCase().includes(lq))
    ) : ALL_ITEMS;
    renderModalList(list);
}

function renderModalList(list) {
    const container = document.getElementById('modalItemList');
    container.innerHTML = list.map(item => {
        const sel = bulkSelected.has(item.id);
        const qtyClass = item.quantity_on_hand <= 0 ? 'low' : '';
        return `
        <div class="modal-item-row ${sel ? 'selected' : ''}" onclick="toggleBulkItem('${item.id}')">
            <div>
                <div class="modal-item-name">${escHtml(item.name)}</div>
                ${item.sku ? `<div class="modal-item-sku">SKU: ${escHtml(item.sku)}</div>` : ''}
            </div>
            <div class="modal-item-stock" style="display:flex;align-items:center;gap:8px;">
                <div>
                    <div class="label">Stock on Hand</div>
                    <div class="qty ${qtyClass}">${Number(item.quantity_on_hand).toFixed(4)} <span class="unit">${item.unit}</span></div>
                </div>
                ${sel ? '<span class="modal-check">✓</span>' : ''}
            </div>
        </div>`;
    }).join('');
}

function toggleBulkItem(itemId) {
    if (bulkSelected.has(itemId)) {
        bulkSelected.delete(itemId);
    } else {
        bulkSelected.add(itemId);
    }
    renderModalList(ALL_ITEMS.filter(i => {
        const q = document.getElementById('modalSearch').value.toLowerCase();
        return !q || i.name.toLowerCase().includes(q) || (i.sku && i.sku.toLowerCase().includes(q));
    }));
    renderSelectedPanel();
}

function renderSelectedPanel() {
    const panel = document.getElementById('selectedItemsPanel');
    const count = bulkSelected.size;
    document.getElementById('selectedCount').textContent = count;
    const selectedItems = ALL_ITEMS.filter(i => bulkSelected.has(i.id));
    const totalQty = selectedItems.reduce((s, i) => s + i.quantity_on_hand, 0);
    document.getElementById('totalQty').textContent = totalQty.toFixed(4);

    if (count === 0) {
        panel.innerHTML = '<div class="selected-placeholder">Click the item names from the left pane to select them</div>';
        return;
    }
    panel.innerHTML = selectedItems.map(i => `
        <div class="selected-item-row">
            <span>${escHtml(i.name)}</span>
            <span style="color:#888;font-size:12px;">${Number(i.quantity_on_hand).toFixed(4)} ${i.unit}</span>
        </div>`).join('');
}

function confirmBulkAdd() {
    tableItems = [];
    ALL_ITEMS.filter(i => bulkSelected.has(i.id)).forEach(item => {
        // Preserve saved values if item was already in table
        const existing = EXISTING_ITEMS.find(e => e.product_id == item.product_id);
        tableItems.push({
            item,
            rowIndex: tableItems.length,
            savedNewQty:  existing?.new_quantity_on_hand ?? '',
            savedQtyAdj:  existing?.quantity_adjusted    ?? '',
            savedChanged: existing?.changed_value        ?? '0.00',
            savedAdjVal:  existing?.adjusted_value       ?? '',
        });
    });
    rerenderAllRows();
    closeBulkModal();
}

// ─── Helpers ───────────────────────────────────────────────────────────────
function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str ?? '';
    return d.innerHTML;
}
function escAttr(val) {
    return String(val ?? '').replace(/"/g, '&quot;');
}

// ─── Init ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    updateTableHeaders();

    // ── Pre-load existing items ──────────────────────────────────────────
    // Match EXISTING_ITEMS against ALL_ITEMS to get full item data
    EXISTING_ITEMS.forEach(saved => {
        const item = ALL_ITEMS.find(i => i.product_id == saved.product_id || i.id == saved.product_id);
        if (!item) return;
        addItemToTable(item, saved);
    });

    // If no items pre-loaded yet (ALL_ITEMS might be location-scoped), just render search row
    if (tableItems.length === 0) {
        rerenderAllRows();
    }

    // ── Location change → fetch fresh stock ──────────────────────────────
    document.getElementById('location_id').addEventListener('change', function () {
        const locationId = this.value;
        if (!locationId) {
            ALL_ITEMS = [];
            tableItems = [];
            rerenderAllRows();
            return;
        }

        // Keep existing tableItems but refresh stock values
        document.getElementById('itemRows').innerHTML = `
            <tr><td colspan="5" style="text-align:center;color:#888;padding:20px;">
                ⏳ Loading items for selected location...
            </td></tr>`;

        fetch(`/inventory/adjustments/items-by-location?location_id=${locationId}`)
            .then(r => r.json())
            .then(data => {
                ALL_ITEMS = data;
                // Update quantity_on_hand in existing tableItems
                tableItems = tableItems.map(entry => {
                    const fresh = ALL_ITEMS.find(i => i.product_id == entry.item.product_id || i.id == entry.item.product_id);
                    if (fresh) entry.item = { ...entry.item, ...fresh };
                    return entry;
                });
                rerenderAllRows();
            })
            .catch(err => {
                console.error('Stock fetch error:', err);
                document.getElementById('itemRows').innerHTML = `
                    <tr><td colspan="5" style="text-align:center;color:#e05252;padding:20px;">
                        ❌ Failed to load items. Please try again.
                    </td></tr>`;
            });
    });

    // Close inline dropdown on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#inlineDropdownRow') && !e.target.classList.contains('search-item-input')) {
            const row = document.getElementById('inlineDropdownRow');
            if (row) row.style.display = 'none';
        }
    });

    // Close bulk modal on overlay click
    document.getElementById('bulkModal').addEventListener('click', function (e) {
        if (e.target === this) closeBulkModal();
    });
});
</script>
@endpush