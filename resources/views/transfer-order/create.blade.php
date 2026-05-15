@extends('layouts.app')

@section('title', isset($transferOrder) ? 'Edit Transfer Order' : 'New Transfer Order')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; color: #333; font-size: 13px; }

.page-header { display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-bottom: 1px solid #e8e8e8; background: #fff; }
.page-header h2 { font-size: 16px; font-weight: 600; color: #1a1a2e; }
.close-btn { margin-left: auto; background: none; border: none; font-size: 20px; cursor: pointer; color: #888; padding: 2px 8px; border-radius: 4px; }
.close-btn:hover { background: #f0f0f0; }

.form-container { background: #fff; padding: 24px; }

/* Basic Fields */
.basic-fields { display: grid; grid-template-columns: 180px 1fr; gap: 12px 0; max-width: 540px; margin-bottom: 20px; }
.field-label { display: flex; align-items: center; font-size: 13px; color: #444; padding-top: 7px; }
.field-label .req { color: #e74c3c; margin-left: 2px; }
.field-input input, .field-input textarea {
    width: 100%; padding: 6px 10px; border: 1px solid #d0d0d0; border-radius: 4px;
    font-size: 13px; color: #333; background: #fff; outline: none; transition: border-color 0.2s;
}
.field-input input:focus, .field-input textarea:focus { border-color: #4a90d9; box-shadow: 0 0 0 2px rgba(74,144,217,0.15); }
.field-input textarea { resize: vertical; min-height: 58px; }
.input-with-icon { position: relative; display: flex; align-items: center; }
.input-with-icon input { padding-right: 30px; }
.input-icon { position: absolute; right: 8px; color: #aaa; cursor: pointer; font-size: 13px; }

.section-divider { border: none; border-top: 1px solid #e8e8e8; margin: 18px 0; }

/* Location Row */
.location-row { display: flex; align-items: flex-start; gap: 0; margin-bottom: 20px; }
.location-block { flex: 1; max-width: 300px; }
.location-block > label { display: block; font-size: 12px; font-weight: 600; color: #e74c3c; margin-bottom: 5px; }
.address-info { font-size: 11px; color: #666; margin-top: 5px; line-height: 1.5; min-height: 14px; }

.swap-btn { display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: none; border: none; cursor: pointer; color: #888; font-size: 20px; margin: 20px 14px 0; border-radius: 50%; transition: all 0.2s; }
.swap-btn:hover { background: #f0f0f0; color: #4a90d9; }

/* Searchable Select */
.searchable-select { position: relative; }
.sel-display {
    width: 100%; padding: 6px 28px 6px 10px; border: 1px solid #d0d0d0; border-radius: 4px;
    font-size: 13px; color: #333; background: #fff; cursor: pointer; user-select: none;
    display: flex; align-items: center; justify-content: space-between; transition: border-color 0.2s; min-height: 32px;
}
.sel-display:hover, .sel-display.open { border-color: #4a90d9; }
.sel-display .arrow { color: #888; font-size: 10px; position: absolute; right: 10px; transition: transform 0.2s; }
.sel-display.open .arrow { transform: rotate(180deg); }
.sel-placeholder { color: #aaa; }

.sel-dropdown {
    display: none; position: absolute; top: 100%; left: 0; z-index: 1000;
    background: #fff; border: 1px solid #d0d0d0; border-radius: 4px; width: 100%; min-width: 240px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12); margin-top: 2px;
}
.sel-dropdown.show { display: block; }
.sel-search-wrap { padding: 8px; border-bottom: 1px solid #f0f0f0; position: relative; }
.sel-search-wrap input {
    width: 100%; padding: 5px 8px 5px 26px; border: 1px solid #d0d0d0; border-radius: 3px;
    font-size: 12px; outline: none; background: #fafafa;
}
.sel-search-wrap input:focus { border-color: #4a90d9; background: #fff; }
.sel-search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 12px; pointer-events: none; }
.sel-options { max-height: 200px; overflow-y: auto; }
.sel-option { padding: 8px 12px; cursor: pointer; font-size: 13px; color: #333; transition: background 0.1s; }
.sel-option:hover, .sel-option.highlighted { background: #e8f1fb; color: #1a5fa0; }
.sel-option.selected { background: #ddeeff; font-weight: 600; }
.sel-empty { padding: 10px 12px; font-size: 12px; color: #aaa; text-align: center; }

/* Item Table */
.item-table-section { border: 1px solid #e0e0e0; border-radius: 5px; overflow: visible; margin-bottom: 20px; }
.item-table-header { display: flex; justify-content: space-between; align-items: center; padding: 9px 14px; background: #fafafa; border-bottom: 1px solid #e8e8e8; }
.item-table-header .title { font-size: 13px; font-weight: 600; color: #333; }
.item-table-header .actions { display: flex; gap: 10px; }
.item-table-header .actions button { display: flex; align-items: center; gap: 4px; background: none; border: none; font-size: 12px; color: #4a90d9; cursor: pointer; padding: 4px 8px; border-radius: 3px; }
.item-table-header .actions button:hover { background: #eaf2fb; }

table.items-table { width: 100%; border-collapse: collapse; }
table.items-table thead tr { background: #f5f6fa; }
table.items-table th { padding: 8px 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; color: #777; text-align: left; border-bottom: 1px solid #e8e8e8; }
table.items-table td { padding: 9px 12px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; font-size: 13px; }
table.items-table tbody tr:last-child td { border-bottom: none; }
table.items-table tbody tr:hover { background: #fafcff; }

.item-detail-cell { display: flex; align-items: center; gap: 9px; }
.item-thumb { width: 32px; height: 32px; border: 1px solid #e0e0e0; border-radius: 3px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 16px; flex-shrink: 0; overflow: hidden; }
.item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.item-name { font-weight: 600; color: #222; font-size: 13px; }
.item-sku { font-size: 11px; color: #888; margin-top: 1px; }
.item-variant-badge { display: inline-block; font-size: 10px; background: #eaf2fb; color: #2d7dd2; border-radius: 3px; padding: 1px 5px; margin-top: 2px; }

.stock-cell { font-size: 12px; color: #444; white-space: nowrap; }
.stock-val { font-weight: 600; color: #222; }
.stock-unit { color: #888; font-size: 11px; margin-left: 2px; }

.qty-input { width: 80px; padding: 5px 8px; border: 1px solid #d0d0d0; border-radius: 3px; font-size: 13px; text-align: right; outline: none; }
.qty-input:focus { border-color: #4a90d9; box-shadow: 0 0 0 2px rgba(74,144,217,0.12); }

.row-act { display: flex; gap: 4px; align-items: center; }
.row-act button { background: none; border: none; cursor: pointer; color: #ccc; font-size: 14px; padding: 3px 4px; border-radius: 3px; transition: all 0.15s; }
.row-act .del-btn:hover { color: #e74c3c; background: #fdf0ee; }
.row-act .copy-btn:hover { color: #4a90d9; background: #eaf2fb; }

/* Item Search Row */
.item-search-row td { background: #fafcff; position: relative; }
.item-search-input { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 3px; font-size: 13px; color: #333; outline: none; }
.item-search-input:focus { border-color: #4a90d9; box-shadow: 0 0 0 2px rgba(74,144,217,0.12); }
.item-search-input::placeholder { color: #aaa; }

.item-dropdown {
    display: none; position: absolute; top: 100%; left: 0; z-index: 999;
    background: #fff; border: 1px solid #d0d0d0; border-radius: 4px;
    width: 480px; max-height: 260px; overflow-y: auto;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}
.item-dropdown.show { display: block; }
.item-opt { padding: 9px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5; transition: background 0.1s; }
.item-opt:hover { background: #eaf2fb; }
.item-opt-name { font-weight: 600; font-size: 13px; color: #222; }
.item-opt-sku { font-size: 11px; color: #888; margin-left: 6px; }
.item-opt-variant { font-size: 11px; color: #4a90d9; margin-top: 2px; }

.add-row-section { display: flex; gap: 14px; padding: 9px 12px; border-top: 1px solid #f0f0f0; }
.add-row-section button { display: flex; align-items: center; gap: 4px; background: none; border: none; font-size: 12px; color: #4a90d9; cursor: pointer; padding: 3px 6px; border-radius: 3px; }
.add-row-section button:hover { background: #eaf2fb; }

/* Bulk Modal */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 2000; align-items: center; justify-content: center; }
.modal-overlay.show { display: flex; }
.bulk-modal { background: #fff; border-radius: 6px; width: 760px; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
.bulk-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e8e8e8; }
.bulk-modal-header h3 { font-size: 15px; font-weight: 600; color: #1a1a2e; }
.bulk-modal-close { background: none; border: none; font-size: 20px; cursor: pointer; color: #888; padding: 2px 6px; border-radius: 3px; }
.bulk-modal-close:hover { background: #f0f0f0; }
.bulk-modal-search { padding: 12px 20px; border-bottom: 1px solid #f0f0f0; }
.bulk-modal-search input { width: 100%; padding: 7px 12px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; }
.bulk-modal-search input:focus { border-color: #4a90d9; }
.bulk-modal-body { display: flex; flex: 1; overflow: hidden; min-height: 300px; }
.bulk-left { flex: 1; overflow-y: auto; border-right: 1px solid #e8e8e8; }
.bulk-right { width: 280px; overflow-y: auto; padding: 12px 16px; }
.bulk-right-title { font-size: 12px; font-weight: 600; color: #555; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.count-badge { background: #2d7dd2; color: #fff; border-radius: 10px; padding: 1px 7px; font-size: 11px; }
.bulk-total { font-size: 12px; color: #888; margin-left: auto; }
.bulk-item-row { display: flex; align-items: center; padding: 10px 16px; cursor: pointer; border-bottom: 1px solid #f5f5f5; transition: background 0.1s; }
.bulk-item-row:hover { background: #fafcff; }
.bulk-item-row.selected { background: #eaf2fb; }
.bulk-item-info { flex: 1; }
.bulk-item-name { font-size: 13px; font-weight: 600; color: #222; }
.bulk-item-sku { font-size: 11px; color: #888; margin-top: 1px; }
.bulk-item-stock { font-size: 11px; color: #555; margin-top: 2px; }
.bulk-item-stock span { font-weight: 600; color: #27ae60; }
.bulk-check { width: 16px; height: 16px; accent-color: #2d7dd2; margin-right: 10px; flex-shrink: 0; }
.bulk-selected-item { display: flex; align-items: center; padding: 6px 0; border-bottom: 1px solid #f5f5f5; font-size: 12px; }
.bulk-selected-item .b-name { flex: 1; font-weight: 600; color: #333; }
.bulk-selected-remove { background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 14px; padding: 0 2px; }
.bulk-empty { padding: 24px; text-align: center; color: #aaa; font-size: 12px; }
.bulk-modal-footer { padding: 12px 20px; border-top: 1px solid #e8e8e8; display: flex; align-items: center; justify-content: flex-end; gap: 10px; }
.btn-bulk-add { background: #2d7dd2; color: #fff; border: none; padding: 7px 20px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-bulk-add:hover { background: #246bb8; }
.btn-bulk-cancel { background: #fff; color: #555; border: 1px solid #d0d0d0; padding: 7px 16px; border-radius: 4px; font-size: 13px; cursor: pointer; }
.btn-bulk-cancel:hover { background: #f5f5f5; }

/* Upload */
.upload-section { margin-bottom: 22px; }
.upload-section h4 { font-size: 12px; font-weight: 600; color: #555; margin-bottom: 7px; }
.upload-btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1px solid #d0d0d0; border-radius: 4px; background: #fff; font-size: 12px; color: #555; cursor: pointer; }
.upload-btn:hover { background: #f5f5f5; }
.upload-note { font-size: 11px; color: #aaa; margin-top: 5px; }

/* Footer */
.form-footer { display: flex; align-items: center; gap: 10px; padding: 14px 24px; border-top: 1px solid #e8e8e8; background: #fff; position: sticky; bottom: 0; }
.btn-secondary { background: #fff; color: #555; border: 1px solid #d0d0d0; padding: 7px 16px; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-secondary:hover { background: #f5f5f5; }
.btn-primary-group { display: flex; align-items: stretch; border-radius: 4px; overflow: visible; }
.btn-primary-group .btn-main { padding: 7px 16px; background: #2d7dd2; color: #fff; border: none; font-size: 13px; font-weight: 500; cursor: pointer; border-radius: 4px 0 0 4px; }
.btn-primary-group .btn-main:hover { background: #246bb8; }
.btn-primary-group .btn-split { padding: 7px 10px; background: #2d7dd2; color: #fff; border: none; border-left: 1px solid rgba(255,255,255,0.3); cursor: pointer; font-size: 11px; border-radius: 0 4px 4px 0; }
.btn-primary-group .btn-split:hover { background: #1e5fa0; }
.btn-cancel-link { background: none; border: none; color: #888; cursor: pointer; font-size: 13px; padding: 7px 10px; }
.btn-cancel-link:hover { color: #333; text-decoration: underline; }

/* Alerts */
.alert-danger { background: #fdf0ee; border: 1px solid #f5c6cb; border-radius: 4px; padding: 10px 14px; margin-bottom: 14px; color: #721c24; font-size: 13px; }
.alert-success { background: #eafaf1; border: 1px solid #b7e4c7; border-radius: 4px; padding: 10px 14px; margin-bottom: 14px; color: #155724; font-size: 13px; }
.error-msg { color: #e74c3c; font-size: 11px; margin-top: 3px; }
.btn-primary-group { position: relative; }
</style>
@endpush

@section('content')
<div class="page-header">
    <span style="color:#888; font-size:15px;">&#9776;</span>
    <h2>{{ isset($transferOrder) ? 'Edit Transfer Order' : 'New Transfer Order' }}</h2>
    <button class="close-btn" onclick="window.history.back()">&times;</button>
</div>

<div class="form-container">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        </div>
    @endif

    <form id="toForm" method="POST"
          action="{{ isset($transferOrder) ? route('transfer-orders.update', $transferOrder->id) : route('transfer-orders.store') }}"
          enctype="multipart/form-data">
        @csrf
        @isset($transferOrder) @method('PUT') @endisset

        {{-- Basic Fields --}}
        <div class="basic-fields">

            <div class="field-label">Transfer Order#<span class="req">*</span></div>
            <div class="field-input">
                <div class="input-with-icon">
                    <input type="text" name="transfer_order_number" id="transfer_order_number"
                           value="{{ old('transfer_order_number', $transferOrder->transfer_order_number ?? $nextOrderNumber ?? '') }}"
                           required>
                    <span class="input-icon">&#9881;</span>
                </div>
                @error('transfer_order_number')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field-label">Date</div>
            <div class="field-input">
                <input type="date" name="date"
                       value="{{ old('date', isset($transferOrder) ? $transferOrder->date : date('Y-m-d')) }}">
                @error('date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="field-label">Reason</div>
            <div class="field-input">
                <textarea name="reason" rows="3">{{ old('reason', $transferOrder->reason ?? '') }}</textarea>
                @error('reason')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

        </div>

        <hr class="section-divider">

        {{-- Locations --}}
        <div class="location-row">

            {{-- Source Location --}}
            <div class="location-block">
                <label>Source Location<span class="req">*</span></label>
                <div class="searchable-select" id="srcSelectWrap">
                    <div class="sel-display" id="srcDisplay" onclick="toggleSelect('src')">
                        <span id="srcLabel" class="sel-placeholder">-- Select --</span>
                        <span class="arrow">&#9660;</span>
                    </div>
                    <div class="sel-dropdown" id="srcDropdown">
                        <div class="sel-search-wrap">
                            <span class="sel-search-icon">&#128269;</span>
                            <input type="text" placeholder="Search..." id="srcSearch" oninput="filterSelect('src', this.value)">
                        </div>
                        <div class="sel-options" id="srcOptions"></div>
                    </div>
                    <input type="hidden" name="source_location_id" id="source_location_id"
                           value="{{ old('source_location_id', $transferOrder->source_location_id ?? '') }}">
                </div>
                <div class="address-info" id="src_address"></div>
                @error('source_location_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <button type="button" class="swap-btn" onclick="swapLocations()" title="Swap">&#8646;</button>

            {{-- Destination Location --}}
            <div class="location-block">
                <label>Destination Location<span class="req">*</span></label>
                <div class="searchable-select" id="dstSelectWrap">
                    <div class="sel-display" id="dstDisplay" onclick="toggleSelect('dst')">
                        <span id="dstLabel" class="sel-placeholder">-- Select --</span>
                        <span class="arrow">&#9660;</span>
                    </div>
                    <div class="sel-dropdown" id="dstDropdown">
                        <div class="sel-search-wrap">
                            <span class="sel-search-icon">&#128269;</span>
                            <input type="text" placeholder="Search..." id="dstSearch" oninput="filterSelect('dst', this.value)">
                        </div>
                        <div class="sel-options" id="dstOptions"></div>
                    </div>
                    <input type="hidden" name="destination_location_id" id="destination_location_id"
                           value="{{ old('destination_location_id', $transferOrder->destination_location_id ?? '') }}">
                </div>
                <div class="address-info" id="dst_address"></div>
                @error('destination_location_id')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

        </div>

        {{-- Item Table --}}
        <div class="item-table-section">
            <div class="item-table-header">
                <span class="title">Item Table</span>
                <div class="actions">
                    <button type="button" onclick="alert('Scan feature coming soon.')">&#128269; Scan Item</button>
                    <button type="button" onclick="openBulkModal()">&#9776; Bulk Actions</button>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:40%">Item Details</th>
                        <th style="width:18%">Source Stock</th>
                        <th style="width:18%">Destination Stock</th>
                        <th style="width:14%; text-align:right;">Transfer Quantity</th>
                        <th style="width:10%"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody">

                    {{-- Edit mode: existing items --}}
                    @isset($transferOrder)
                        @foreach($transferOrder->items as $idx => $item)
                        <tr class="item-row" data-row="{{ $idx }}">
                            <td>
                                <div class="item-detail-cell">
                                    <div class="item-thumb">
                                        @if($item->product->image ?? null)
                                            <img src="{{ asset('storage/'.$item->product->image) }}">
                                        @else &#128247; @endif
                                    </div>
                                    <div>
                                        <div class="item-name">{{ $item->product->name }}</div>
                                        <div class="item-sku">SKU: {{ $item->product->sku ?? '' }}</div>
                                        @if($item->variant_id)
                                            <span class="item-variant-badge">{{ $item->variantName ?? '' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="items[{{ $idx }}][variant_id]" value="{{ $item->variant_id ?? '' }}">
                            </td>
                            <td class="stock-cell" data-type="src">
                                <span class="stock-val">{{ number_format($item->source_stock ?? 0, 4) }}</span>
                                <span class="stock-unit">{{ $item->product->unit ?? '' }}</span>
                            </td>
                            <td class="stock-cell" data-type="dst">
                                <span class="stock-val">{{ number_format($item->destination_stock ?? 0, 4) }}</span>
                                <span class="stock-unit">{{ $item->product->unit ?? '' }}</span>
                            </td>
                            <td style="text-align:right;">
                                <input type="number" class="qty-input"
                                       name="items[{{ $idx }}][quantity]"
                                       value="{{ old('items.'.$idx.'.quantity', $item->quantity) }}"
                                       min="0" step="0.0001">
                            </td>
                            <td>
                                <div class="row-act">
                                    <button type="button" class="copy-btn" onclick="cloneRow(this)" title="Clone">&#128203;</button>
                                    <button type="button" class="del-btn"  onclick="removeRow(this)"  title="Delete">&#128465;</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endisset

                    {{-- Search row --}}
                    <tr class="item-search-row" id="newItemRow">
                        <td colspan="2" style="position:relative;">
                            <input type="text" class="item-search-input" id="itemSearchInput"
                                   placeholder="Type or click to select an item..."
                                   autocomplete="off"
                                   oninput="searchItems(this.value)"
                                   onfocus="openItemDropdown()">
                            <div class="item-dropdown" id="itemDropdown"></div>
                        </td>
                        <td class="stock-cell" id="niSrcStock" style="color:#aaa; font-size:12px;">-</td>
                        <td class="stock-cell" id="niDstStock" style="color:#aaa; font-size:12px;">-</td>
                        <td></td>
                        <td></td>
                    </tr>

                </tbody>
            </table>

            <div class="add-row-section">
                <button type="button" onclick="focusSearch()">&#43; Add New Row</button>
                <button type="button" onclick="openBulkModal()">&#43; Add Items in Bulk</button>
            </div>
        </div>

        {{-- Upload --}}
        <div class="upload-section">
            <h4>Attach File(s) to Transfer Order</h4>
            <label class="upload-btn">
                &#8679; Upload File
                <input type="file" name="attachments[]" multiple style="display:none;"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xlsx">
            </label>
            <p class="upload-note">You can upload a maximum of 5 files, 10MB each</p>
        </div>

        {{-- Footer --}}
{{-- Footer --}}
<div class="form-footer">
    <button type="button" class="btn-secondary" onclick="submitDraft()">Save as Draft</button>
    <div class="btn-primary-group" style="position:relative;">
        <button type="submit" name="action" value="initiate" class="btn-main">Initiate transfer</button>
        <button type="button" class="btn-split" id="splitDropBtn" onclick="toggleSplitDrop()">&#9660;</button>
        <div class="split-dropdown" id="splitDropdown" style="display:none; position:absolute; bottom:110%; right:0;
            background:#fff; border:1px solid #d0d0d0; border-radius:4px;
            box-shadow:0 4px 16px rgba(0,0,0,0.12); min-width:180px; z-index:100;">

            {{-- ✅ @isset எடுத்துட்டோம் - create + edit இரண்டுலயும் show ஆகும் --}}
            <div class="split-opt" onclick="submitAction('mark_as_transfer')"
                style="padding:9px 16px; cursor:pointer; font-size:13px; color:#333; border-bottom:1px solid #f0f0f0;"
                onmouseover="this.style.background='#eaf2fb'" onmouseout="this.style.background=''">
                &#8644; Mark as Transfer
            </div>

            <div class="split-opt" onclick="submitAction('initiate')"
                style="padding:9px 16px; cursor:pointer; font-size:13px; color:#333;"
                onmouseover="this.style.background='#eaf2fb'" onmouseout="this.style.background=''">
                &#9654; Initiate Transfer
            </div>
        </div>
    </div>
    <button type="button" class="btn-cancel-link" onclick="window.history.back()">Cancel</button>
</div>

    </form>
</div>

{{-- Bulk Modal --}}
<div class="modal-overlay" id="bulkModal">
    <div class="bulk-modal">
        <div class="bulk-modal-header">
            <h3>Add Items in Bulk</h3>
            <button class="bulk-modal-close" onclick="closeBulkModal()">&times;</button>
        </div>
        <div class="bulk-modal-search">
            <input type="text" id="bulkSearch"
                   placeholder="Type to search or scan the barcode of the item"
                   oninput="filterBulkItems(this.value)">
        </div>
        <div class="bulk-modal-body">
            <div class="bulk-left" id="bulkItemList"></div>
            <div class="bulk-right">
                <div class="bulk-right-title">
                    Selected Items
                    <span class="count-badge" id="bulkCount">0</span>
                    <span class="bulk-total">Total Quantity: <span id="bulkTotalQty">0</span></span>
                </div>
                <div id="bulkSelectedList">
                    <div class="bulk-empty">Click the item names from the left pane to select them</div>
                </div>
            </div>
        </div>
        <div class="bulk-modal-footer">
            <button class="btn-bulk-add" onclick="confirmBulkAdd()">Add Items</button>
            <button class="btn-bulk-cancel" onclick="closeBulkModal()">Cancel</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Server data ───────────────────────────────────────────────────────────────
const ALL_PRODUCTS  = @json($productsForJs  ?? []);
const ALL_LOCATIONS = @json($locationsForJs ?? []);

let rowIndex = {{ isset($transferOrder) ? $transferOrder->items->count() : 0 }};

// ── Utilities ─────────────────────────────────────────────────────────────────

// HTML-escape for display inside innerHTML / data-* attributes
function eh(s) {
    return String(s || '')
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;');
}

function fmt(n) { return parseFloat(n || 0).toFixed(4); }

// ── SEARCHABLE LOCATION SELECT ────────────────────────────────────────────────

function buildLocationOptions(type) {
    const container = document.getElementById(type + 'Options');
    container.innerHTML = '';
    ALL_LOCATIONS.forEach(loc => {
        const div = document.createElement('div');
        div.className     = 'sel-option';
        div.dataset.val   = loc.id;
        div.dataset.label = loc.name;
        div.dataset.addr  = loc.address || '';
        div.textContent   = loc.name;
        div.onclick = () => pickLocation(type, div);
        container.appendChild(div);
    });
}

function toggleSelect(type) {
    const dd   = document.getElementById(type + 'Dropdown');
    const disp = document.getElementById(type + 'Display');
    const open = dd.classList.contains('show');
    closeAllSelects();
    if (!open) {
        dd.classList.add('show');
        disp.classList.add('open');
        document.getElementById(type + 'Search').value = '';
        filterSelect(type, '');
        document.getElementById(type + 'Search').focus();
    }
}

function closeAllSelects() {
    ['src','dst'].forEach(t => {
        document.getElementById(t+'Dropdown')?.classList.remove('show');
        document.getElementById(t+'Display')?.classList.remove('open');
    });
}

function filterSelect(type, q) {
    q = q.toLowerCase();
    document.querySelectorAll('#'+type+'Options .sel-option').forEach(o => {
        o.style.display = o.dataset.label.toLowerCase().includes(q) ? '' : 'none';
    });
}

function pickLocation(type, el) {
    const hiddenId = type === 'src' ? 'source_location_id' : 'destination_location_id';
    document.getElementById(hiddenId).value = el.dataset.val;

    const labelEl = document.getElementById(type + 'Label');
    labelEl.textContent = el.dataset.label;
    labelEl.classList.remove('sel-placeholder');

    document.getElementById(type === 'src' ? 'src_address' : 'dst_address').textContent = el.dataset.addr || '';

    document.querySelectorAll('#'+type+'Options .sel-option').forEach(o =>
        o.classList.toggle('selected', o.dataset.val === el.dataset.val)
    );

    closeAllSelects();
    refreshAllStocks();
}

function swapLocations() {
    const srcHidden = document.getElementById('source_location_id');
    const dstHidden = document.getElementById('destination_location_id');
    const srcLabel  = document.getElementById('srcLabel');
    const dstLabel  = document.getElementById('dstLabel');
    const srcAddr   = document.getElementById('src_address');
    const dstAddr   = document.getElementById('dst_address');

    [srcHidden.value, dstHidden.value] = [dstHidden.value, srcHidden.value];
    [srcLabel.textContent, dstLabel.textContent] = [dstLabel.textContent, srcLabel.textContent];
    [srcAddr.textContent, dstAddr.textContent]   = [dstAddr.textContent, srcAddr.textContent];

    srcLabel.classList.toggle('sel-placeholder', !srcHidden.value);
    dstLabel.classList.toggle('sel-placeholder', !dstHidden.value);

    ['src','dst'].forEach(t => {
        const currentVal = document.getElementById(t === 'src' ? 'source_location_id' : 'destination_location_id').value;
        document.querySelectorAll('#'+t+'Options .sel-option').forEach(o =>
            o.classList.toggle('selected', o.dataset.val === currentVal)
        );
    });

    refreshAllStocks();
}

// Close dropdowns on outside click
document.addEventListener('click', e => {
    if (!e.target.closest('.searchable-select')) closeAllSelects();
    if (!e.target.closest('#newItemRow'))
        document.getElementById('itemDropdown').classList.remove('show');
});

// ── ITEM SEARCH DROPDOWN ──────────────────────────────────────────────────────

function openItemDropdown() {
    renderItemDropdown('');
    document.getElementById('itemDropdown').classList.add('show');
}

function searchItems(q) {
    renderItemDropdown(q);
    document.getElementById('itemDropdown').classList.add('show');
}

// KEY FIX: use data-* attributes instead of inline onclick with esc()
function renderItemDropdown(q) {
    q = (q || '').toLowerCase();
    const dd = document.getElementById('itemDropdown');
    let html = '';
    let count = 0;

    ALL_PRODUCTS.forEach(p => {
        const matchP = !q || p.name.toLowerCase().includes(q) || (p.sku||'').toLowerCase().includes(q);

        if (p.has_variants && p.variants && p.variants.length) {
            p.variants.forEach(v => {
                const matchV = !q || v.name.toLowerCase().includes(q) || (v.sku||'').toLowerCase().includes(q);
                if (matchP || matchV) {
                    count++;
                    html += `<div class="item-opt item-opt-selectable"
                        data-pid="${p.id}"
                        data-vid="${v.id}"
                        data-name="${eh(p.name)}"
                        data-vname="${eh(v.name)}"
                        data-sku="${eh(v.sku||p.sku||'')}"
                        data-unit="${eh(p.unit||'box')}"
                        data-image="${eh(p.image_url||'')}">
                        <span class="item-opt-name">${eh(p.name)}</span>
                        <span class="item-opt-sku">SKU: ${eh(v.sku||p.sku||'N/A')}</span>
                        <div class="item-opt-variant">&#9642; ${eh(v.name)}</div>
                    </div>`;
                }
            });
        } else if (matchP) {
            count++;
            html += `<div class="item-opt item-opt-selectable"
                data-pid="${p.id}"
                data-vid=""
                data-name="${eh(p.name)}"
                data-vname=""
                data-sku="${eh(p.sku||'')}"
                data-unit="${eh(p.unit||'box')}"
                data-image="${eh(p.image_url||'')}">
                <span class="item-opt-name">${eh(p.name)}</span>
                <span class="item-opt-sku">SKU: ${eh(p.sku||'N/A')}</span>
            </div>`;
        }
    });

    dd.innerHTML = count
        ? html
        : '<div class="item-opt" style="color:#aaa;cursor:default;">No items found</div>';
}

function focusSearch() {
    document.getElementById('itemSearchInput').focus();
}

// ── STOCK AJAX ────────────────────────────────────────────────────────────────

function fetchStock(pid, vid, srcId, dstId, cb) {
    if (!pid || !srcId || !dstId) { cb(0, 0); return; }

    const params = new URLSearchParams({
        product_id:              pid,
        source_location_id:      srcId,
        destination_location_id: dstId
    });
    if (vid) params.append('variant_id', vid);

    const token = document.querySelector('meta[name="csrf-token"]');
    fetch('/transfer-orders/stock?' + params, {
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': token ? token.content : ''
        }
    })
    .then(r => r.json())
    .then(d => cb(d.source_stock ?? 0, d.destination_stock ?? 0))
    .catch(() => cb(0, 0));
}

// ── ADD / REMOVE / CLONE ROWS ─────────────────────────────────────────────────

function addItemRow(pid, vid, name, vname, sku, unit, image, srcStock, dstStock) {
    const tbody  = document.getElementById('itemsBody');
    const newRow = document.getElementById('newItemRow');

    const imgHtml      = image ? `<img src="${eh(image)}" style="width:32px;height:32px;object-fit:cover;">` : '&#128247;';
    const variantBadge = vname ? `<span class="item-variant-badge">${eh(vname)}</span>` : '';

    const tr = document.createElement('tr');
    tr.className   = 'item-row';
    tr.dataset.row = rowIndex;

    tr.innerHTML = `
        <td>
            <div class="item-detail-cell">
                <div class="item-thumb">${imgHtml}</div>
                <div>
                    <div class="item-name">${eh(name)}</div>
                    <div class="item-sku">SKU: ${eh(sku)}</div>
                    ${variantBadge}
                </div>
            </div>
            <input type="hidden" name="items[${rowIndex}][product_id]" value="${pid}">
            <input type="hidden" name="items[${rowIndex}][variant_id]"  value="${vid||''}">
        </td>
        <td class="stock-cell" data-type="src">
            <span class="stock-val">${fmt(srcStock)}</span>
            <span class="stock-unit">${eh(unit)}</span>
        </td>
        <td class="stock-cell" data-type="dst">
            <span class="stock-val">${fmt(dstStock)}</span>
            <span class="stock-unit">${eh(unit)}</span>
        </td>
        <td style="text-align:right;">
            <input type="number" class="qty-input" name="items[${rowIndex}][quantity]"
                   value="0.00" min="0" step="0.0001">
        </td>
        <td>
            <div class="row-act">
                <button type="button" class="copy-btn" onclick="cloneRow(this)" title="Clone">&#128203;</button>
                <button type="button" class="del-btn"  onclick="removeRow(this)"  title="Delete">&#128465;</button>
            </div>
        </td>`;

    tbody.insertBefore(tr, newRow);
    rowIndex++;
}

function removeRow(btn) {
    btn.closest('tr').remove();
    reindex();
}

function cloneRow(btn) {
    const tr    = btn.closest('tr');
    const clone = tr.cloneNode(true);
    clone.dataset.row = rowIndex;
    clone.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/items\[\d+\]/, `items[${rowIndex}]`);
    });
    tr.after(clone);
    rowIndex++;
}

function reindex() {
    document.querySelectorAll('#itemsBody .item-row').forEach((tr, i) => {
        tr.dataset.row = i;
        tr.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
        });
    });
    rowIndex = document.querySelectorAll('#itemsBody .item-row').length;
}

// ── REFRESH ALL ROW STOCKS when location changes ──────────────────────────────

function refreshAllStocks() {
    const srcId = document.getElementById('source_location_id').value;
    const dstId = document.getElementById('destination_location_id').value;
    if (!srcId || !dstId) return;

    document.querySelectorAll('#itemsBody .item-row').forEach(tr => {
        const pid = tr.querySelector('input[name*="[product_id]"]')?.value;
        const vid = tr.querySelector('input[name*="[variant_id]"]')?.value || '';
        if (!pid) return;
        fetchStock(pid, vid, srcId, dstId, (s, d) => {
            const srcCell = tr.querySelector('[data-type="src"] .stock-val');
            const dstCell = tr.querySelector('[data-type="dst"] .stock-val');
            if (srcCell) srcCell.textContent = fmt(s);
            if (dstCell) dstCell.textContent = fmt(d);
        });
    });
}

// ── BULK MODAL ────────────────────────────────────────────────────────────────

let bulkSelected = {};

function openBulkModal() {
    bulkSelected = {};
    document.getElementById('bulkSearch').value = '';
    renderBulkList('');
    renderBulkSelected();
    document.getElementById('bulkModal').classList.add('show');
}

function closeBulkModal() {
    document.getElementById('bulkModal').classList.remove('show');
}

function filterBulkItems(q) {
    renderBulkList(q);
}

// KEY FIX: use data-* attributes instead of inline onclick with esc()
function renderBulkList(q) {
    q = (q || '').toLowerCase();
    let html = '';

    ALL_PRODUCTS.forEach(p => {
        const matchP = !q || p.name.toLowerCase().includes(q) || (p.sku||'').toLowerCase().includes(q);

        if (p.has_variants && p.variants && p.variants.length) {
            p.variants.forEach(v => {
                const matchV = !q || v.name.toLowerCase().includes(q) || (v.sku||'').toLowerCase().includes(q);
                if (matchP || matchV) {
                    const key   = p.id + '_' + v.id;
                    const isChk = !!bulkSelected[key];
                    html += `<div class="bulk-item-row${isChk?' selected':''} bulk-item-selectable"
                        data-key="${key}"
                        data-pid="${p.id}"
                        data-vid="${v.id}"
                        data-name="${eh(p.name)}"
                        data-vname="${eh(v.name)}"
                        data-sku="${eh(v.sku||p.sku||'')}"
                        data-unit="${eh(p.unit||'box')}"
                        data-image="${eh(p.image_url||'')}">
                        <input type="checkbox" class="bulk-check" ${isChk?'checked':''} tabindex="-1">
                        <div class="bulk-item-info">
                            <div class="bulk-item-name">${eh(p.name)} <span style="font-size:11px;color:#4a90d9;font-weight:400;">(${eh(v.name)})</span></div>
                            <div class="bulk-item-sku">SKU: ${eh(v.sku||p.sku||'N/A')}</div>
                            <div class="bulk-item-stock">Stock on Hand <span>${fmt(v.stock_on_hand||0)}</span> ${eh(p.unit||'box')}</div>
                        </div>
                    </div>`;
                }
            });
        } else if (matchP) {
            const key   = p.id + '_';
            const isChk = !!bulkSelected[key];
            html += `<div class="bulk-item-row${isChk?' selected':''} bulk-item-selectable"
                data-key="${key}"
                data-pid="${p.id}"
                data-vid=""
                data-name="${eh(p.name)}"
                data-vname=""
                data-sku="${eh(p.sku||'')}"
                data-unit="${eh(p.unit||'box')}"
                data-image="${eh(p.image_url||'')}">
                <input type="checkbox" class="bulk-check" ${isChk?'checked':''} tabindex="-1">
                <div class="bulk-item-info">
                    <div class="bulk-item-name">${eh(p.name)}</div>
                    <div class="bulk-item-sku">SKU: ${eh(p.sku||'N/A')}</div>
                    <div class="bulk-item-stock">Stock on Hand <span>${fmt(p.stock_on_hand||0)}</span> ${eh(p.unit||'box')}</div>
                </div>
            </div>`;
        }
    });

    document.getElementById('bulkItemList').innerHTML = html || '<div class="bulk-empty">No items found</div>';
}

function renderBulkSelected() {
    const keys = Object.keys(bulkSelected);
    document.getElementById('bulkCount').textContent    = keys.length;
    document.getElementById('bulkTotalQty').textContent = keys.length;

    if (!keys.length) {
        document.getElementById('bulkSelectedList').innerHTML =
            '<div class="bulk-empty">Click the item names from the left pane to select them</div>';
        return;
    }

    document.getElementById('bulkSelectedList').innerHTML = keys.map(k => {
        const it      = bulkSelected[k];
        const display = it.vname ? `${it.name} (${it.vname})` : it.name;
        return `<div class="bulk-selected-item">
            <span class="b-name">${eh(display)}</span>
            <button type="button" class="bulk-selected-remove bulk-deselect" data-key="${k}">&times;</button>
        </div>`;
    }).join('');
}

function confirmBulkAdd() {
    const keys  = Object.keys(bulkSelected);
    const srcId = document.getElementById('source_location_id').value;
    const dstId = document.getElementById('destination_location_id').value;

    keys.forEach(k => {
        const it = bulkSelected[k];
        fetchStock(it.pid, it.vid, srcId, dstId, (s, d) => {
            addItemRow(it.pid, it.vid, it.name, it.vname, it.sku, it.unit, it.image, s, d);
        });
    });

    closeBulkModal();
}
function toggleSplitDrop() {
    const dd = document.getElementById('splitDropdown');
    dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
}

function submitAction(action) {
    const form = document.getElementById('toForm');
    // Remove existing action inputs
    form.querySelectorAll('input[name="action"]').forEach(e => e.remove());
    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = 'action';
    inp.value = action;
    form.appendChild(inp);
    form.submit();
}

// Close split dropdown on outside click
document.addEventListener('click', e => {
    if (!e.target.closest('.btn-primary-group'))
        document.getElementById('splitDropdown').style.display = 'none';
});
// ── DRAFT SUBMIT ──────────────────────────────────────────────────────────────

function submitDraft() {
    const form = document.getElementById('toForm');
    form.querySelectorAll('input[name="action"]').forEach(e => e.remove()); // ✅ முதல்ல clear
    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = 'action';
    inp.value = 'draft';
    form.appendChild(inp);
    form.submit();
}
// ── INIT ──────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    buildLocationOptions('src');
    buildLocationOptions('dst');

    // Pre-select locations if editing
    const srcId = document.getElementById('source_location_id').value;
    const dstId = document.getElementById('destination_location_id').value;
    if (srcId) {
        const opt = document.querySelector(`#srcOptions [data-val="${srcId}"]`);
        if (opt) pickLocation('src', opt);
    }
    if (dstId) {
        const opt = document.querySelector(`#dstOptions [data-val="${dstId}"]`);
        if (opt) pickLocation('dst', opt);
    }

    // ── Event delegation: item search dropdown ──────────────────────────────
    document.getElementById('itemDropdown').addEventListener('click', function(e) {
        const row = e.target.closest('.item-opt-selectable');
        if (!row) return;
        const { pid, vid, name, vname, sku, unit, image } = row.dataset;
        const srcId = document.getElementById('source_location_id').value;
        const dstId = document.getElementById('destination_location_id').value;
        fetchStock(pid, vid, srcId, dstId, (s, d) => {
            addItemRow(pid, vid, name, vname, sku, unit, image, s, d);
        });
        document.getElementById('itemSearchInput').value = '';
        document.getElementById('itemDropdown').classList.remove('show');
    });

    // ── Event delegation: bulk item list ────────────────────────────────────
    document.getElementById('bulkItemList').addEventListener('click', function(e) {
        const row = e.target.closest('.bulk-item-selectable');
        if (!row) return;
        const { key, pid, vid, name, vname, sku, unit, image } = row.dataset;
        if (bulkSelected[key]) {
            delete bulkSelected[key];
        } else {
            bulkSelected[key] = { pid, vid, name, vname, sku, unit, image };
        }
        renderBulkList(document.getElementById('bulkSearch').value);
        renderBulkSelected();
    });

    // ── Event delegation: bulk selected remove buttons ──────────────────────
    document.getElementById('bulkSelectedList').addEventListener('click', function(e) {
        const btn = e.target.closest('.bulk-deselect');
        if (!btn) return;
        const key = btn.dataset.key;
        delete bulkSelected[key];
        renderBulkList(document.getElementById('bulkSearch').value);
        renderBulkSelected();
    });
});
</script>
@endpush