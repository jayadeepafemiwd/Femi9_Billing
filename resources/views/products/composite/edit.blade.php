{{-- resources/views/products/composite/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Composite Item')

@push('styles')
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f5f8; color: #333; font-size: 13px; }
  .form-panel { background: #fff; padding: 24px 32px 100px; max-width: 1100px; }
  .form-title { font-size: 20px; font-weight: 600; color: #1a1a2e; margin-bottom: 24px; }
  .form-row { display: flex; align-items: flex-start; margin-bottom: 18px; gap: 12px; }
  .form-label { width: 140px; flex-shrink: 0; font-size: 13px; color: #333; padding-top: 8px; }
  .form-label.req::after { content: ' *'; color: #c0392b; }
  .form-content { flex: 1; }
  .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 7px 10px; font-size: 13px; color: #333; outline: none; background: #fff; transition: border-color .15s; }
  .form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,.1); }
  .form-select { width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 7px 10px; font-size: 13px; color: #555; outline: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23999'/%3E%3C/svg%3E") no-repeat right 10px center; cursor: pointer; }
  .radio-group { display: flex; flex-direction: column; gap: 10px; }
  .radio-option { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; }
  .radio-option input[type="radio"] { margin-top: 3px; accent-color: #2563eb; width: 15px; height: 15px; }
  .radio-label { font-size: 13px; font-weight: 600; color: #222; }
  .radio-desc  { font-size: 12px; color: #888; margin-top: 2px; }
  .image-upload-area { border: 2px dashed #d1d5db; border-radius: 6px; width: 220px; min-height: 170px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #aaa; text-align: center; padding: 16px; flex-shrink: 0; cursor: pointer; transition: border-color .2s, background .2s; position: relative; overflow: hidden; }
  .image-upload-area:hover { border-color: #2563eb; background: #f0f5ff; }
  .image-upload-area.dragover { border-color: #2563eb; background: #e0edff; }
  .image-upload-area svg { color: #ccc; margin-bottom: 8px; }
  .image-upload-area .browse { color: #2563eb; font-size: 13px; text-decoration: underline; }
  .image-upload-area .img-info { font-size: 11px; color: #aaa; margin-top: 6px; line-height: 1.4; }
  #imagePreview { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; display: none; border-radius: 4px; }
  .image-remove-btn { position: absolute; top: 6px; right: 6px; background: rgba(0,0,0,.5); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 13px; cursor: pointer; display: none; align-items: center; justify-content: center; z-index: 10; }
  .checkbox-row { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; padding-left: 152px; }
  .checkbox-row input[type="checkbox"] { accent-color: #2563eb; width: 14px; height: 14px; }
  .checkbox-row label { font-size: 13px; color: #333; cursor: pointer; }
  .section-header { font-size: 13px; font-weight: 600; color: #c0392b; margin: 22px 0 12px; }
  .assoc-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .assoc-table th { background: #f8f9fb; border: 1px solid #e8eaed; padding: 8px 10px; text-align: left; font-weight: 500; color: #555; }
  .assoc-table th:not(:first-child) { text-align: right; }
  .assoc-table td { border: 1px solid #e8eaed; padding: 8px 10px; vertical-align: middle; }
  .assoc-table td:not(:first-child) { text-align: right; }
  .item-thumb { width: 36px; height: 36px; background: #f0f1f3; border: 1px solid #d1d5db; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: #bbb; flex-shrink: 0; overflow: hidden; }
  .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .item-cell { display: flex; align-items: center; gap: 10px; }
  .drag-handle { color: #ccc; cursor: grab; font-size: 16px; user-select: none; }
  .item-selector-wrap { position: relative; flex: 1; }
  .item-selector-input { width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px 10px; font-size: 13px; outline: none; cursor: pointer; background: #fff; }
  .item-selector-input:focus { border-color: #2563eb; }
  .item-selector-input::placeholder { color: #aaa; }
  .item-dropdown { position: absolute; top: calc(100% + 2px); left: 0; right: 0; background: #fff; border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,.12); z-index: 999; max-height: 220px; overflow-y: auto; display: none; }
  .item-dropdown.open { display: block; }
  .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; cursor: pointer; transition: background .1s; }
  .dropdown-item:hover { background: #f0f5ff; }
  .dropdown-item .d-thumb { width: 30px; height: 30px; background: #f0f1f3; border-radius: 3px; border: 1px solid #e0e3ea; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; color: #ccc; }
  .dropdown-item .d-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .dropdown-item .d-info { flex: 1; }
  .dropdown-item .d-name { font-size: 13px; color: #333; font-weight: 500; }
  .dropdown-item .d-sku  { font-size: 11px; color: #888; }
  .dropdown-item .d-price { font-size: 12px; color: #555; white-space: nowrap; }
  .dropdown-empty { padding: 12px; text-align: center; color: #aaa; font-size: 13px; }
  .qty-input { width: 60px; border: 1px solid #d1d5db; border-radius: 4px; padding: 5px 6px; text-align: right; font-size: 13px; outline: none; }
  .qty-input:focus { border-color: #2563eb; }
  .price-sub { font-size: 11px; color: #888; }
  .action-x { color: #e74c3c; cursor: pointer; font-size: 18px; font-weight: bold; line-height: 1; padding: 2px 4px; border-radius: 3px; transition: background .15s; }
  .action-x:hover { background: #fde8e8; }
  .table-total td { font-weight: 600; background: #fafafa; color: #333; }
  .add-row-btn { display: inline-flex; align-items: center; gap: 5px; color: #2563eb; font-size: 12.5px; cursor: pointer; padding: 0; border: none; background: none; font-weight: 500; }
  .add-row-btn:hover { text-decoration: underline; }
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 18px; }
  .col-title { font-size: 13.5px; font-weight: 600; color: #333; margin-bottom: 12px; display: flex; align-items: center; gap: 10px; }
  .col-title .col-check { display: flex; align-items: center; gap: 6px; margin-left: auto; font-weight: 400; }
  .col-field { margin-bottom: 14px; }
  .col-label { font-size: 12px; color: #c0392b; margin-bottom: 4px; display: flex; align-items: center; justify-content: space-between; }
  .col-label.gray { color: #555; }
  .copy-link { color: #2563eb; font-size: 11.5px; cursor: pointer; }
  .copy-link:hover { text-decoration: underline; }
  .dim-row { display: flex; align-items: center; gap: 6px; }
  .dim-input { flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 7px 8px; font-size: 13px; outline: none; }
  .dim-input:focus { border-color: #2563eb; }
  .dim-sep { color: #aaa; }
  .dim-unit { border: 1px solid #d1d5db; border-radius: 4px; padding: 7px 8px; font-size: 12px; outline: none; background: #f8f9fb; color: #555; }
  .track-bin { display: flex; align-items: flex-start; gap: 10px; padding: 14px 0; border-top: 1px solid #e8eaed; }
  .track-bin-title { font-size: 13px; color: #333; font-weight: 500; }
  .track-bin-sub   { font-size: 12px; color: #888; }
  .inv-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 14px; }
  hr.divider { border: none; border-top: 1px solid #e8eaed; margin: 18px 0; }
  .info-icon { color: #999; font-size: 11px; cursor: help; }
  .bottom-bar { position: fixed; bottom: 0; left: 220px; right: 0; background: #fff; border-top: 1px solid #e0e3ea; padding: 12px 32px; display: flex; gap: 10px; z-index: 100; }
  .btn-save { background: #2563eb; color: #fff; border: none; border-radius: 4px; padding: 8px 22px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-save:hover { background: #1d4ed8; }
  .btn-cancel { background: #fff; color: #555; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px 18px; font-size: 13px; cursor: pointer; }
  .btn-cancel:hover { background: #f4f5f8; }
  .invalid-feedback { color: #c0392b; font-size: 12px; margin-top: 3px; }
  .is-invalid { border-color: #e74c3c !important; }
</style>
@endpush

@section('content')

{{-- ══ PARSE ALL DATA FROM JSON COLUMNS ══ --}}
@php
  // ── Image ──
  $productImageJson  = is_string($product->product_image)
      ? json_decode($product->product_image, true)
      : ($product->product_image ?? []);
  $existingImagePath = $productImageJson['front_image'] ?? null;

  // ── Additional data ──
  $additionalData = is_string($product->additional_data)
      ? json_decode($product->additional_data, true)
      : ($product->additional_data ?? []);

  // ── Dimensions ──
  $dimUnit    = $additionalData['dimension_unit'] ?? $settings['dimension_unit'] ?? 'cm';
  $weightUnit = $additionalData['weight_unit']    ?? $settings['weight_unit']    ?? 'kg';
  $cfLength   = $additionalData['length']  ?? '';
  $cfWidth    = $additionalData['width']   ?? '';
  $cfHeight   = $additionalData['height']  ?? '';
  $cfWeight   = $additionalData['weight']  ?? '';

  // ── Category ──
  $catId   = $additionalData['category']['id']   ?? null;
  $catName = $additionalData['category']['name'] ?? null;

  // ── Descriptions ──
  $salesDesc    = $additionalData['description']['sales_description']    ?? '';
  $purchaseDesc = $additionalData['description']['purchase_description'] ?? '';

  // ── Extra fields ──
  $upc  = $additionalData['upc']  ?? '';
  $mpn  = $additionalData['mpn']  ?? '';
  $ean  = $additionalData['ean']  ?? '';
  $isbn = $additionalData['isbn'] ?? '';
@endphp

<div class="form-panel">
  <h1 class="form-title">Edit Composite Item</h1>

  <form id="compositeForm"
        method="POST"
        action="{{ route('composite-items.update', $product->id) }}"
        enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="associate_items_json" id="associateItemsJson">

    @if($errors->any())
      <div style="background:#fde8e8;color:#c0392b;padding:10px 14px;border-radius:5px;margin-bottom:16px;font-size:13px;">
        <ul style="margin:0;padding-left:18px;">
          @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif

    @if(session('error'))
      <div style="background:#fde8e8;color:#c0392b;padding:10px 14px;border-radius:5px;margin-bottom:16px;font-size:13px;">
        {{ session('error') }}
      </div>
    @endif

    {{-- ══ Name + Image ══ --}}
    <div style="display:flex; gap:24px; align-items:flex-start;">

      {{-- LEFT COLUMN --}}
      <div style="flex:1;">

        {{-- Name --}}
        <div class="form-row">
          <div class="form-label req">Name</div>
          <div class="form-content">
            <input class="form-input @error('name') is-invalid @enderror"
                   type="text" name="name" autocomplete="off"
                   value="{{ old('name', $product->name) }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Item Type --}}
        <div class="form-row" style="align-items:flex-start;">
          <div class="form-label req">Item Type</div>
          <div class="form-content">
            <div class="radio-group">
              <label class="radio-option">
                <input type="radio" name="type" value="assembly_item"
                  {{ old('type', $product->type) === 'assembly_item' ? 'checked' : '' }}
                  onchange="toggleKitFields(this.value)">
                <div>
                  <div class="radio-label">Assembly Item</div>
                  <div class="radio-desc">A group of items combined together to be tracked and managed as a single item.</div>
                </div>
              </label>
              <label class="radio-option">
                <input type="radio" name="type" value="kit_item"
                  {{ old('type', $product->type) === 'kit_item' ? 'checked' : '' }}
                  onchange="toggleKitFields(this.value)">
                <div>
                  <div class="radio-label">Kit Item</div>
                  <div class="radio-desc">Individual items sold together as one kit.</div>
                </div>
              </label>
            </div>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- SKU --}}
        <div class="form-row">
          <div class="form-label">SKU <span class="info-icon" title="Stock Keeping Unit">?</span></div>
          <div class="form-content">
            <input class="form-input" type="text" name="sku" autocomplete="off"
                   value="{{ old('sku', $product->sku) }}">
            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Unit --}}
        <div class="form-row">
          <div class="form-label req">Unit <span class="info-icon" title="Unit of measurement">?</span></div>
          <div class="form-content">
            <select class="form-select @error('unit') is-invalid @enderror" name="unit" required>
              <option value="">Select or type to add</option>
              @foreach(['pcs','kg','box','set','pair','dozen','ltr','m','cm','ft','g','mg'] as $u)
                <option value="{{ $u }}"
                  {{ old('unit', $product->unit) === $u ? 'selected' : '' }}>{{ $u }}</option>
              @endforeach
            </select>
            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Category --}}
        <div class="form-row">
          <div class="form-label">Category</div>
          <div class="form-content">
            <input type="hidden" name="category_id"   id="cat-hid-id"
                   value="{{ old('category_id', $catId) }}" />
            <input type="hidden" name="category_name" id="cat-hid-name"
                   value="{{ old('category_name', $catName) }}" />
            <div class="cat-dd-wrap" style="position:relative;">
              <div class="cat-dd-box" id="cat-dd-box" onclick="toggleCatDd()"
                   style="display:flex;align-items:center;justify-content:space-between;border:1px solid #d1d5db;border-radius:4px;background:#fff;padding:7px 10px;cursor:pointer;min-height:34px;transition:border-color .15s;user-select:none;">
                <span class="cat-dd-label" id="cat-dd-lbl"
                      style="font-size:13px;color:{{ $catId ? '#333' : '#aaa' }};">
                  {{ $catName ?? 'Select a category' }}
                </span>
                <span id="cat-dd-chev" style="font-size:11px;color:#888;">▼</span>
              </div>
              <div class="cat-dd-panel" id="cat-dd-panel"
                   style="display:none;position:absolute;top:calc(100% + 3px);left:0;width:100%;background:#fff;border:1px solid #d1d5db;border-radius:4px;box-shadow:0 4px 16px rgba(0,0,0,.13);z-index:600;">
                <div style="display:flex;align-items:center;gap:6px;padding:7px 10px;border-bottom:1px solid #eee;">
                  <span style="color:#bbb;font-size:13px;">🔍</span>
                  <input type="text" id="cat-dd-q" placeholder="Search"
                         oninput="filterCatDd(this.value)" autocomplete="off"
                         style="flex:1;border:none;outline:none;font-size:13px;" />
                </div>
                <div id="cat-dd-list" style="max-height:200px;overflow-y:auto;">
                  <div style="padding:12px;text-align:center;color:#aaa;font-size:13px;">Loading...</div>
                </div>
                <div onclick="openCatModal();closeCatDd();"
                     style="display:flex;align-items:center;gap:7px;padding:10px 14px;font-size:13px;color:#2563eb;font-weight:500;cursor:pointer;border-top:1px solid #eee;">
                  ⚙️ Manage Categories
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>{{-- end LEFT COLUMN --}}

      {{-- RIGHT COLUMN: Image --}}
      <div>
        <div class="image-upload-area" id="imageDropZone">
          <button type="button" class="image-remove-btn" id="imageRemoveBtn">✕</button>
          <img id="imagePreview" alt="Preview"
            @if($existingImagePath) src="/{{ $existingImagePath }}" @endif>
          <div id="imageUploadPlaceholder"
            @if($existingImagePath) style="display:none;" @endif>
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <path d="M21 15l-5-5L5 21"/>
            </svg>
            <div>Drag image here or</div>
            <span class="browse">Browse images</span>
            <div class="img-info">Up to 15 images · max 5MB · 7000×7000px</div>
          </div>
          <input type="file" id="frontImageInput" name="front_image"
                 accept="image/jpeg,image/png,image/jpg,image/gif" style="display:none;">
          <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
        </div>
        @if($existingImagePath)
          <div style="font-size:11px;color:#888;text-align:center;margin-top:6px;">Current image</div>
        @endif
      </div>

    </div>{{-- end flex --}}

    {{-- Returnable --}}
    <div class="checkbox-row">
      <input type="checkbox" id="returnable" name="is_returnable" value="1"
             {{ old('is_returnable', $product->is_returnable) ? 'checked' : '' }}>
      <label for="returnable">Returnable Item <span class="info-icon" title="Can this item be returned?">?</span></label>
    </div>

    <hr class="divider">

    {{-- ══ Associate Items ══ --}}
    <div class="section-header">Associate Items*</div>
    <table class="assoc-table" id="itemsTable">
      <thead>
        <tr>
          <th style="width:40%;">Item Details</th>
          <th>Quantity</th>
          <th>Selling Price (₹)</th>
          <th>Cost Price (₹)</th>
          <th style="width:50px;"></th>
        </tr>
      </thead>
      <tbody id="itemsBody"></tbody>
      <tfoot>
        <tr class="table-total">
          <td>
            <div style="display:flex;gap:12px;">
              <button type="button" class="add-row-btn" onclick="addItemRow('items')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Add New Row
              </button>
              <button type="button" class="add-row-btn" onclick="addServiceToItemTable()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Add Services
              </button>
            </div>
          </td>
          <td>Total (₹) :</td>
          <td id="itemsTotalSP">0.00</td>
          <td id="itemsTotalCP">0.00</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    {{-- ══ Associate Services ══ --}}
    <div class="section-header" style="margin-top:24px;">Associate Services</div>
    <table class="assoc-table" id="servicesTable">
      <thead>
        <tr>
          <th style="width:40%;">Service Details</th>
          <th>Quantity</th>
          <th>Selling Price (₹)</th>
          <th>Cost Price (₹)</th>
          <th style="width:50px;"></th>
        </tr>
      </thead>
      <tbody id="servicesBody"></tbody>
      <tfoot>
        <tr class="table-total">
          <td>
            <button type="button" class="add-row-btn" onclick="addItemRow('services')">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
              Add New Row
            </button>
          </td>
          <td>Total (₹) :</td>
          <td id="servicesTotalSP">0.00</td>
          <td id="servicesTotalCP">0.00</td>
          <td></td>
        </tr>
      </tfoot>
    </table>

    <hr class="divider">

    {{-- ══ Sales & Purchase ══ --}}
    <div class="two-col">
      <div>
        <div class="col-title">
          Sales Information
          <div class="col-check">
            <input type="checkbox" id="sellable" name="sellable" value="1"
                   {{ old('sellable', $product->sellable ?? true) ? 'checked' : '' }} style="accent-color:#2563eb;">
            <label for="sellable" style="font-size:13px;">Sellable</label>
          </div>
        </div>
        <div class="col-field">
          <div class="col-label">Selling Price (INR)* <span class="copy-link" onclick="copyFromTotal('sp')">Copy from total</span></div>
          <input class="form-input @error('selling_price') is-invalid @enderror"
                 type="number" step="0.01" min="0" name="selling_price" id="sellingPriceInput"
                 value="{{ old('selling_price', $product->selling_price) }}">
          @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-field">
          <div class="col-label gray">Description</div>
          <textarea class="form-input" name="sales_description" rows="3" style="resize:vertical;">{{ old('sales_description', $salesDesc) }}</textarea>
        </div>
      </div>

      <div id="purchase-info-section">
        <div class="col-title">
          Purchase Information
          <div class="col-check">
            <input type="checkbox" id="purchasable" name="purchasable" value="1"
                   {{ old('purchasable', $product->purchasable ?? true) ? 'checked' : '' }} style="accent-color:#2563eb;">
            <label for="purchasable" style="font-size:13px;">Purchasable</label>
          </div>
        </div>
        <div class="col-field">
          <div class="col-label">Cost Price (INR)* <span class="copy-link" onclick="copyFromTotal('cp')">Copy from total</span></div>
          <input class="form-input @error('cost_price') is-invalid @enderror"
                 type="number" step="0.01" min="0" name="cost_price" id="costPriceInput"
                 value="{{ old('cost_price', $product->cost_price) }}">
          @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-field">
          <div class="col-label gray">Description</div>
          <textarea class="form-input" name="purchase_description" rows="3" style="resize:vertical;">{{ old('purchase_description', $purchaseDesc) }}</textarea>
        </div>
        <div class="col-field">
          <div class="col-label gray">Preferred Vendor</div>
          <select class="form-select" name="preferred_vendor_id"><option value=""></option></select>
        </div>
      </div>
    </div>

    <hr class="divider">

    {{-- ══ Dimensions & More ══ --}}
    <div class="two-col">
      <div>
        <div class="col-field">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">Dimensions</div>
          <div class="dim-row">
            <input class="dim-input" type="number" step="0.01" min="0" name="custom_field[length]"
                   value="{{ old('custom_field.length', $cfLength) }}" placeholder="L">
            <span class="dim-sep">x</span>
            <input class="dim-input" type="number" step="0.01" min="0" name="custom_field[width]"
                   value="{{ old('custom_field.width', $cfWidth) }}" placeholder="W">
            <span class="dim-sep">x</span>
            <input class="dim-input" type="number" step="0.01" min="0" name="custom_field[height]"
                   value="{{ old('custom_field.height', $cfHeight) }}" placeholder="H">
            <select class="dim-unit" name="custom_field[dimension_unit]">
              @foreach(['cm','m','in','ft','mm'] as $u)
                <option value="{{ $u }}" {{ old('custom_field.dimension_unit', $dimUnit) === $u ? 'selected' : '' }}>{{ $u }}</option>
              @endforeach
            </select>
          </div>
          <div style="font-size:11px;color:#888;margin-top:4px;">(Length × Width × Height)</div>
        </div>

        <div class="col-field" style="margin-top:14px;">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">Weight</div>
          <div class="dim-row">
            <input class="dim-input" type="number" step="0.01" min="0" name="custom_field[weight]"
                   value="{{ old('custom_field.weight', $cfWeight) }}" placeholder="0">
            <select class="dim-unit" name="custom_field[weight_unit]">
              @foreach(['kg','g','lb','oz','mg'] as $u)
                <option value="{{ $u }}" {{ old('custom_field.weight_unit', $weightUnit) === $u ? 'selected' : '' }}>{{ $u }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-field">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">UPC <span class="info-icon" title="Universal Product Code">?</span></div>
          <input class="form-input" type="text" name="upc" value="{{ old('upc', $upc) }}">
        </div>

        <div class="col-field">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">EAN <span class="info-icon" title="European Article Number">?</span></div>
          <input class="form-input" type="text" name="ean" value="{{ old('ean', $ean) }}">
        </div>
      </div>

      <div>
        <div class="col-field" style="margin-top:14px;">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">Brand</div>
          <div style="display:flex;align-items:center;gap:8px;">
            <select class="form-select" name="brand_id" id="brand-select" style="flex:1;">
              <option value="">— Select Brand —</option>
              @foreach($brands as $brand)
                <option value="{{ $brand->id }}"
                  {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                  {{ $brand->name }}
                </option>
              @endforeach
            </select>
            <button type="button" onclick="openBrandModal()" title="Manage Brands"
                    style="width:34px;height:34px;border:1px solid #d1d5db;border-radius:4px;background:#f8f9fb;cursor:pointer;font-size:16px;color:#555;flex-shrink:0;display:flex;align-items:center;justify-content:center;">⚙️</button>
          </div>
        </div>

        <div class="col-field">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">MPN <span class="info-icon" title="Manufacturer Part Number">?</span></div>
          <input class="form-input" type="text" name="mpn" value="{{ old('mpn', $mpn) }}">
        </div>

        <div class="col-field">
          <div style="color:#555;font-size:13px;margin-bottom:4px;">ISBN <span class="info-icon" title="International Standard Book Number">?</span></div>
          <input class="form-input" type="text" name="isbn" value="{{ old('isbn', $isbn) }}">
        </div>
      </div>
    </div>

    <hr class="divider">

    {{-- ══ Inventory ══ --}}
    <div class="track-bin" id="track-bin-section">
      <input type="checkbox" id="trackbin" name="bin_location_tracking" value="1"
             {{ old('bin_location_tracking', $product->bin_location_tracking) ? 'checked' : '' }}
             style="margin-top:3px;accent-color:#2563eb;">
      <div>
        <div class="track-bin-title">Track Bin location for this item</div>
        <div class="track-bin-sub">Enable this option if you want to track the bin locations for this item while creating transactions</div>
      </div>
    </div>

    <div class="inv-two-col" id="inventory-section">
      <div class="col-field">
        <div class="col-label">Inventory Account*</div>
        <select class="form-select" name="inventory_account_id">
          <option value="">Select an account</option>
        </select>
      </div>
      <div class="col-field">
        <div class="col-label">Inventory Valuation Method*</div>
        <select class="form-select" name="inventory_valuation_method">
          <option value="">Select the valuation method</option>
          <option value="FIFO" {{ old('inventory_valuation_method', $product->inventory_valuation_method) === 'FIFO' ? 'selected' : '' }}>FIFO</option>
          <option value="Weighted Average" {{ old('inventory_valuation_method', $product->inventory_valuation_method) === 'Weighted Average' ? 'selected' : '' }}>Weighted Average</option>
        </select>
      </div>
      <div class="col-field">
        <div style="color:#555;font-size:13px;margin-bottom:4px;">Reorder Point</div>
        <input class="form-input" type="number" step="0.01" min="0" name="reorder_point"
               value="{{ old('reorder_point', $product->reorder_point) }}">
      </div>
    </div>

    {{-- ══ Custom Fields ══ --}}
    @if($customFields->count())
      <hr class="divider">
      @foreach($customFields as $field)
        <div class="form-row">
          <div class="form-label">{{ $field->label ?? $field->name }}</div>
          <div class="form-content">
            <input class="form-input" type="text"
                   name="custom_fields[{{ $field->id }}]"
                   value="{{ old('custom_fields.'.$field->id) }}">
          </div>
        </div>
      @endforeach
    @endif

    <div style="height:70px;"></div>
  </form>

  {{-- ══ CATEGORIES MODAL ══ --}}
  <div id="cat-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:2000;align-items:flex-start;justify-content:center;padding-top:60px;">
    <div style="background:#fff;border-radius:8px;width:700px;max-width:96vw;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 8px 32px rgba(0,0,0,.18);overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 24px;border-bottom:1px solid #eee;flex-shrink:0;">
        <span style="font-size:16px;font-weight:600;color:#222;">Manage Categories</span>
        <button onclick="closeCatModal()" style="background:none;border:none;font-size:22px;color:#e74c3c;cursor:pointer;font-weight:700;line-height:1;">✕</button>
      </div>
      <div style="flex:1;overflow-y:auto;padding:24px;">
        <div id="cat-new-form" style="display:none;margin-bottom:20px;padding-bottom:20px;border-bottom:2px solid #e8eaed;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <label style="width:140px;font-size:13px;font-weight:500;text-align:right;flex-shrink:0;color:#c0392b;">Category Name*</label>
            <input type="text" id="cat-name-inp" style="flex:1;border:2px solid #2563eb;border-radius:4px;padding:7px 12px;font-size:14px;font-family:inherit;outline:none;"
                   onkeydown="if(event.key==='Enter'){event.preventDefault();saveCat();}" />
          </div>
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <label style="width:140px;font-size:13px;font-weight:500;text-align:right;flex-shrink:0;color:#444;">Parent Category</label>
            <div style="flex:1;position:relative;">
              <select id="cat-par-sel" style="width:100%;border:1px solid #d1d5db;border-radius:4px;padding:7px 12px;font-size:14px;appearance:none;font-family:inherit;outline:none;color:#333;">
                <option value="">— None —</option>
              </select>
              <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#888;font-size:11px;">▼</span>
            </div>
          </div>
          <div id="cat-fe" style="color:#dc3545;font-size:12px;margin-left:152px;margin-bottom:8px;display:none;"></div>
          <div style="display:flex;gap:8px;margin-left:152px;">
            <button id="btn-cs" onclick="saveCat()" style="background:#2563eb;color:#fff;border:none;border-radius:4px;padding:8px 22px;font-weight:600;font-size:13px;cursor:pointer;">Save</button>
            <button onclick="hideCatForm()" style="background:#fff;color:#555;border:1px solid #d1d5db;border-radius:4px;padding:8px 16px;font-size:13px;cursor:pointer;">Cancel</button>
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:12px;font-weight:700;color:#4a5568;letter-spacing:.6px;text-transform:uppercase;">CATEGORIES</span>
          <button id="btn-anc" onclick="showCatForm()" style="display:flex;align-items:center;gap:5px;background:none;border:none;color:#2563eb;font-size:13px;font-weight:600;cursor:pointer;padding:4px 8px;border-radius:4px;">
            <span style="width:18px;height:18px;border-radius:50%;background:#2563eb;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:16px;line-height:1;">+</span>
            Add New Category
          </button>
        </div>
        <div id="cat-ms" style="background:#d4edda;color:#155724;font-size:12px;padding:8px 12px;border-radius:4px;margin-bottom:12px;display:none;"></div>
        <div id="cat-li-wrap"><div style="text-align:center;padding:24px;color:#aaa;font-size:13px;">Loading...</div></div>
      </div>
      <div style="padding:12px 24px;border-top:1px solid #eee;flex-shrink:0;">
        <button onclick="closeCatModal()" style="background:#fff;color:#555;border:1px solid #d1d5db;border-radius:4px;padding:8px 20px;font-size:13px;cursor:pointer;">Cancel</button>
      </div>
    </div>
  </div>

  {{-- ══ BRANDS MODAL ══ --}}
  <div id="brand-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;width:480px;max-width:95vw;box-shadow:0 8px 32px rgba(0,0,0,.18);overflow:hidden;">
      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #e8eaed;font-weight:700;font-size:15px;">
        <span>⚙️ Manage Brands</span>
        <button onclick="closeBrandModal()" style="cursor:pointer;font-size:18px;color:#aaa;background:none;border:none;">✕</button>
      </div>
      <div style="padding:20px;">
        <div id="add-brand-form" style="display:flex;gap:8px;margin-bottom:8px;">
          <input type="text" id="new-brand-input" placeholder="Enter brand name..."
                 style="flex:1;border:1px solid #d1d5db;border-radius:4px;padding:8px 12px;font-size:14px;outline:none;font-family:inherit;"
                 onkeydown="if(event.key==='Enter'){event.preventDefault();addBrand();}" />
          <button onclick="addBrand()" id="btn-add-brand"
                  style="background:#2563eb;color:#fff;border:none;border-radius:4px;padding:8px 18px;font-weight:600;cursor:pointer;font-size:13px;white-space:nowrap;">+ Add New</button>
        </div>
        <div id="edit-brand-form" style="display:none;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
          <input type="text" id="edit-brand-input" placeholder="Edit brand name..."
                 style="flex:1;border:1px solid #d1d5db;border-radius:4px;padding:8px 12px;font-size:14px;outline:none;font-family:inherit;min-width:140px;" />
          <input type="hidden" id="edit-brand-id" />
          <button id="btn-update-brand" onclick="updateBrand()" style="background:#28a745;color:#fff;border:none;border-radius:4px;padding:8px 18px;font-weight:600;cursor:pointer;font-size:13px;">✓ Update</button>
          <button onclick="cancelBrandEdit()" style="background:#6c757d;color:#fff;border:none;border-radius:4px;padding:8px 14px;font-size:13px;cursor:pointer;">✕ Cancel</button>
        </div>
        <div id="brand-error"   style="color:#dc3545;font-size:12px;margin-bottom:10px;display:none;"></div>
        <div id="brand-success" style="color:#155724;font-size:12px;margin-bottom:10px;background:#d4edda;padding:8px;border-radius:4px;display:none;"></div>
        <div id="brand-list" style="max-height:260px;overflow-y:auto;margin-top:12px;">
          <div style="color:#aaa;font-size:13px;text-align:center;padding:20px;">Loading...</div>
        </div>
      </div>
    </div>
  </div>

</div>{{-- .form-panel --}}

<div class="bottom-bar">
  <button type="button" class="btn-save" onclick="submitForm()">Update</button>
  <a href="{{ route('composite-items.index') }}" class="btn-cancel">Cancel</a>
</div>
@endsection

@push('scripts')
<script>
function toggleKitFields(type) {
  const isKit = type === 'kit_item';
  ['purchase-info-section', 'track-bin-section', 'inventory-section'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = isKit ? 'none' : '';
  });
}

const AVAILABLE_ITEMS    = @json($availableItems);
const AVAILABLE_SERVICES = @json($availableServices);
const EXISTING_ITEMS     = @json($existingItems ?? []);
const EXISTING_SERVICES  = @json($existingServices ?? []);

let associateRows = { items: [], services: [] };
let rowCounter = 0;

// ── IMAGE ──
const dropZone    = document.getElementById('imageDropZone');
const fileInput   = document.getElementById('frontImageInput');
const preview     = document.getElementById('imagePreview');
const placeholder = document.getElementById('imageUploadPlaceholder');
const removeBtn   = document.getElementById('imageRemoveBtn');

if (preview.src && preview.src !== window.location.href) {
  preview.style.display = 'block';
  removeBtn.style.display = 'flex';
  placeholder.style.display = 'none';
}

dropZone.addEventListener('click', (e) => { if (e.target !== removeBtn) fileInput.click(); });
dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', (e) => {
  e.preventDefault(); dropZone.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) { applyCompositeImage(file); }
});
fileInput.addEventListener('change', () => { if (fileInput.files[0]) applyCompositeImage(fileInput.files[0]); });

function applyCompositeImage(file) {
  if (!file.type.match(/image\/(jpeg|png|jpg|gif)/)) { alert('JPG, PNG, GIF மட்டும்.'); return; }
  if (file.size > 5 * 1024 * 1024) { alert('Image 5MB-க்கு மேல கூடாது.'); return; }
  const reader = new FileReader();
  reader.onload = ev => {
    const img = new Image();
    img.onload = () => {
      let { width, height } = img;
      if (width > 1920 || height > 1920) {
        const r = Math.min(1920/width, 1920/height);
        width = Math.round(width*r); height = Math.round(height*r);
      }
      const canvas = document.createElement('canvas');
      canvas.width = width; canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#fff'; ctx.fillRect(0,0,width,height);
      ctx.drawImage(img, 0, 0, width, height);
      let quality = 0.92;
      let dataURL = canvas.toDataURL('image/jpeg', quality);
      let sizeKB  = Math.round((dataURL.length*3)/4/1024);
      while (sizeKB > 800 && quality > 0.5) { quality -= 0.05; dataURL = canvas.toDataURL('image/jpeg', quality); sizeKB = Math.round((dataURL.length*3)/4/1024); }
      const byteStr = atob(dataURL.split(',')[1]);
      const ab = new ArrayBuffer(byteStr.length);
      const ia = new Uint8Array(ab);
      for (let i=0;i<byteStr.length;i++) ia[i]=byteStr.charCodeAt(i);
      const blob = new Blob([ab], {type:'image/jpeg'});
      const dt = new DataTransfer();
      dt.items.add(new File([blob], file.name.replace(/\.[^.]+$/,'')+'.jpg', {type:'image/jpeg'}));
      fileInput.files = dt.files;
      preview.src = dataURL; preview.style.display = 'block';
      placeholder.style.display = 'none'; removeBtn.style.display = 'flex';
      document.getElementById('removeImageFlag').value = '0';
    };
    img.src = ev.target.result;
  };
  reader.readAsDataURL(file);
}

removeBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  preview.src = ''; preview.style.display = 'none';
  placeholder.style.display = 'flex'; removeBtn.style.display = 'none';
  fileInput.value = '';
  document.getElementById('removeImageFlag').value = '1';
});

// ── CATEGORY ──
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
let _cats = [], _catOpen = false, _selCatId = {{ $catId ?? 'null' }}, _editCatId = null;

(async function initCats() { await _fetchCats(); })();

async function _fetchCats() {
  try {
    const r = await fetch('/categories/list', { headers: {'Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'} });
    const j = await r.json();
    if (j.success) { _cats = j.data; _renderDdList(''); }
  } catch {}
}

function toggleCatDd() { _catOpen ? closeCatDd() : openCatDd(); }

function openCatDd() {
  _catOpen = true;
  document.getElementById('cat-dd-panel').style.display = 'block';
  document.getElementById('cat-dd-chev').textContent = '▲';
  document.getElementById('cat-dd-q').value = '';
  _renderDdList('');
  setTimeout(() => document.getElementById('cat-dd-q').focus(), 20);
}

function closeCatDd() {
  _catOpen = false;
  document.getElementById('cat-dd-panel').style.display = 'none';
  document.getElementById('cat-dd-chev').textContent = '▼';
}

function filterCatDd(q) { _renderDdList(q); }

function _renderDdList(q) {
  const el = document.getElementById('cat-dd-list');
  q = (q||'').trim().toLowerCase();
  const list = q ? _cats.filter(c => c.name.toLowerCase().includes(q)||(c.parent_name||'').toLowerCase().includes(q)) : _cats;
  if (!list.length) { el.innerHTML = '<div style="padding:12px;text-align:center;color:#aaa;font-size:13px;">No categories found</div>'; return; }
  el.innerHTML = list.map(c => `
    <div onclick="selCat(${c.id},'${c.name.replace(/'/g,"\\'")}')"
         style="display:flex;align-items:center;gap:8px;padding:9px 14px;font-size:13px;color:${_selCatId===c.id?'#2563eb':'#333'};background:${_selCatId===c.id?'#e8efff':'#fff'};cursor:pointer;"
         onmouseover="this.style.background='#f0f5ff'" onmouseout="this.style.background='${_selCatId===c.id?'#e8efff':'#fff'}'">
      <span>📁</span><span>${c.name}</span>
      ${c.parent_name?`<span style="font-size:11px;color:#aaa;">(${c.parent_name})</span>`:''}
    </div>`).join('');
}

function selCat(id, name) {
  _selCatId = id;
  document.getElementById('cat-hid-id').value = id;
  document.getElementById('cat-hid-name').value = name;
  const l = document.getElementById('cat-dd-lbl');
  l.textContent = name; l.style.color = '#333';
  closeCatDd();
}

document.addEventListener('click', function(e) {
  const wrap = document.querySelector('.cat-dd-wrap');
  if (wrap && !wrap.contains(e.target)) closeCatDd();
});

function openCatModal() { document.getElementById('cat-modal').style.display='flex'; hideCatForm(); _renderModalList(); }
function closeCatModal() { document.getElementById('cat-modal').style.display='none'; hideCatForm(); }
document.getElementById('cat-modal').addEventListener('click', e => { if(e.target===document.getElementById('cat-modal')) closeCatModal(); });

function showCatForm(eId, eNm, ePar) {
  _editCatId = eId||null;
  document.getElementById('cat-new-form').style.display='block';
  document.getElementById('cat-name-inp').value = eNm||'';
  document.getElementById('cat-fe').style.display='none';
  document.getElementById('btn-cs').textContent = eId?'Update':'Save';
  document.getElementById('btn-anc').style.display='none';
  const sel = document.getElementById('cat-par-sel');
  sel.innerHTML = '<option value="">— None —</option>';
  _cats.forEach(c => { if(c.id!==eId){ const o=document.createElement('option'); o.value=c.id; o.textContent=c.full_name||c.name; if(c.id===ePar)o.selected=true; sel.appendChild(o); } });
  setTimeout(() => document.getElementById('cat-name-inp').focus(), 30);
}

function hideCatForm() {
  document.getElementById('cat-new-form').style.display='none';
  document.getElementById('btn-anc').style.display='flex';
  document.getElementById('cat-name-inp').value='';
  document.getElementById('cat-fe').style.display='none';
  _editCatId=null;
}

async function saveCat() {
  const name=document.getElementById('cat-name-inp').value.trim();
  const par=document.getElementById('cat-par-sel').value||null;
  const fe=document.getElementById('cat-fe');
  const btn=document.getElementById('btn-cs');
  fe.style.display='none';
  if(!name){fe.textContent='Category name cannot be empty.';fe.style.display='block';return;}
  btn.disabled=true; btn.textContent=_editCatId?'Updating...':'Saving...';
  try {
    const res=await fetch(_editCatId?`/categories/${_editCatId}`:'/categories',{method:_editCatId?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name,parent_id:par})});
    const j=await res.json();
    if(!res.ok){fe.textContent=j.message||'Failed.';fe.style.display='block';}
    else{hideCatForm();_showCatMsg(_editCatId?'Category updated!':'Category added!');await _fetchCats();_renderModalList();}
  } catch{fe.textContent='Network error.';fe.style.display='block';}
  finally{btn.disabled=false;btn.textContent=_editCatId?'Update':'Save';}
}

function _renderModalList() {
  const el=document.getElementById('cat-li-wrap');
  if(!_cats.length){el.innerHTML='<div style="text-align:center;padding:24px;color:#aaa;font-size:13px;">No categories added yet</div>';return;}
  el.innerHTML=_cats.map(c=>`
    <div style="display:flex;align-items:center;gap:10px;padding:10px 4px;border-bottom:1px solid #f0f2f7;">
      <span style="font-size:16px;color:#5b8dee;">📁</span>
      <span style="flex:1;font-size:13px;font-weight:500;color:#333;">${c.name}${c.parent_name?`<span style="font-size:11px;color:#aaa;margin-left:4px;">(${c.parent_name})</span>`:''}</span>
      <div style="display:flex;gap:2px;">
        <button onclick="showCatForm(${c.id},'${c.name.replace(/'/g,"\\'")}',${c.parent_id||'null'})" style="color:#2563eb;background:none;border:none;cursor:pointer;padding:4px 7px;border-radius:4px;font-size:13px;">✏️ Edit</button>
        <button onclick="delCat(${c.id},'${c.name.replace(/'/g,"\\'")}'')" style="color:#e74c3c;background:none;border:none;cursor:pointer;padding:4px 7px;border-radius:4px;font-size:13px;">🗑️ Delete</button>
      </div>
    </div>`).join('');
}

async function delCat(id, name) {
  if(!confirm(`Delete category "${name}"?`))return;
  try{
    const res=await fetch(`/categories/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'}});
    const j=await res.json();
    if(res.ok&&j.success){if(_selCatId===id){_selCatId=null;document.getElementById('cat-hid-id').value='';document.getElementById('cat-hid-name').value='';const l=document.getElementById('cat-dd-lbl');l.textContent='Select a category';l.style.color='#aaa';}_showCatMsg('Category deleted!');await _fetchCats();_renderModalList();}else{alert(j.message||'Failed to delete');}
  }catch{alert('Network error');}
}

function _showCatMsg(m){const el=document.getElementById('cat-ms');el.textContent=m;el.style.display='block';setTimeout(()=>el.style.display='none',3000);}

// ── BRAND ──
function openBrandModal(){document.getElementById('brand-modal').style.display='flex';document.getElementById('new-brand-input').value='';document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';cancelBrandEdit();loadBrands();}
function closeBrandModal(){document.getElementById('brand-modal').style.display='none';cancelBrandEdit();}
document.getElementById('brand-modal').addEventListener('click',e=>{if(e.target===document.getElementById('brand-modal'))closeBrandModal();});

async function loadBrands(){
  const list=document.getElementById('brand-list');
  list.innerHTML='<div style="text-align:center;padding:20px;color:#aaa;">Loading...</div>';
  try{const res=await fetch('/brands/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();if(j.success)renderBrandList(j.data);else list.innerHTML='<div style="color:#e74c3c;text-align:center;padding:20px;">Failed to load</div>';}
  catch{list.innerHTML='<div style="color:#e74c3c;text-align:center;padding:20px;">Failed to load</div>';}
}

function renderBrandList(brands){
  const list=document.getElementById('brand-list');
  if(!brands||!brands.length){list.innerHTML='<div style="color:#aaa;font-size:13px;text-align:center;padding:20px;">No brands added yet</div>';return;}
  list.innerHTML=brands.map(b=>`<div id="brand-row-${b.id}" style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:6px;font-size:13px;"><span>${escHtml(b.name)}</span><div style="display:flex;gap:4px;"><button onclick="editBrand(${b.id},'${escHtml(b.name).replace(/'/g,"\\'")}')" style="color:#2563eb;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:4px;font-size:14px;">✏️</button><button onclick="deleteBrand(${b.id},'${escHtml(b.name).replace(/'/g,"\\'")}')" style="color:#e74c3c;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:4px;font-size:14px;">🗑️</button></div></div>`).join('');
}

function editBrand(id,name){document.getElementById('add-brand-form').style.display='none';document.getElementById('edit-brand-form').style.display='flex';document.getElementById('edit-brand-id').value=id;document.getElementById('edit-brand-input').value=name;document.getElementById('edit-brand-input').focus();document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';}
function cancelBrandEdit(){document.getElementById('edit-brand-form').style.display='none';document.getElementById('add-brand-form').style.display='flex';document.getElementById('edit-brand-id').value='';document.getElementById('edit-brand-input').value='';}

async function updateBrand(){
  const id=document.getElementById('edit-brand-id').value;const name=document.getElementById('edit-brand-input').value.trim();
  const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-update-brand');
  errEl.style.display='none';sucEl.style.display='none';
  if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';return;}
  btn.disabled=true;btn.textContent='Updating...';
  try{const res=await fetch(`/brands/${id}`,{method:'PUT',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const j=await res.json();
    if(!res.ok){errEl.textContent=j.errors?.name?.[0]||j.message||'Failed';errEl.style.display='block';}
    else{sucEl.textContent='Brand updated!';sucEl.style.display='block';const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt)opt.textContent=name;loadBrands();cancelBrandEdit();setTimeout(()=>sucEl.style.display='none',3000);}
  }catch{errEl.textContent='Network error.';errEl.style.display='block';}
  finally{btn.disabled=false;btn.textContent='✓ Update';}
}

async function addBrand(){
  const input=document.getElementById('new-brand-input');const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-add-brand');const name=input.value.trim();
  errEl.style.display='none';sucEl.style.display='none';
  if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';input.focus();return;}
  btn.disabled=true;btn.textContent='Adding...';
  try{const res=await fetch('/brands',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const j=await res.json();
    if(!res.ok){errEl.textContent=j.message||'Failed';errEl.style.display='block';}
    else{input.value='';sucEl.textContent='Brand added!';sucEl.style.display='block';const sel=document.getElementById('brand-select');const opt=document.createElement('option');opt.value=j.data.id;opt.textContent=j.data.name;opt.selected=true;sel.appendChild(opt);loadBrands();setTimeout(()=>sucEl.style.display='none',3000);}
  }catch{errEl.textContent='Network error.';errEl.style.display='block';}
  finally{btn.disabled=false;btn.textContent='+ Add New';}
}

async function deleteBrand(id,name){
  if(!confirm(`Delete brand "${name}"?`))return;
  try{const res=await fetch(`/brands/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();
    if(res.ok&&j.success){document.getElementById(`brand-row-${id}`)?.remove();const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt){if(opt.selected)document.getElementById('brand-select').value='';opt.remove();}const sucEl=document.getElementById('brand-success');sucEl.textContent='Brand deleted!';sucEl.style.display='block';setTimeout(()=>sucEl.style.display='none',3000);if(document.getElementById('edit-brand-id').value==id)cancelBrandEdit();}
    else{alert(j.message||'Failed to delete brand');}
  }catch{alert('Failed to delete brand');}
}

// ── ROW MANAGEMENT ──
function addItemRow(tableType, existingData=null) {
  const rowId = ++rowCounter;
  const row = { id:rowId, productId:existingData?.product_id||null, productData:existingData?.productData||null, qty:existingData?.quantity||1, sp:existingData?.selling_price||0, cp:existingData?.cost_price||0 };
  associateRows[tableType].push(row);
  renderRow(tableType, row);
  updateTotals(tableType);
}

function addServiceToItemTable() { addItemRow('services'); }

function removeRow(tableType, rowId) {
  associateRows[tableType] = associateRows[tableType].filter(r => r.id !== rowId);
  document.getElementById(`row-${tableType}-${rowId}`)?.remove();
  updateTotals(tableType);
}

function renderRow(tableType, row) {
  const tbody    = document.getElementById(`${tableType}Body`);
  const products = tableType === 'items' ? AVAILABLE_ITEMS : AVAILABLE_SERVICES;
  const tr = document.createElement('tr');
  tr.id = `row-${tableType}-${row.id}`;
  tr.innerHTML = buildRowHTML(tableType, row, products);
  tbody.appendChild(tr);
  bindRowEvents(tableType, row.id, products);
}

function buildRowHTML(tableType, row, products) {
  const thumbHTML = row.productData
    ? `<div style="width:100%;height:100%;background:#e8efff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">${(row.productData.name||'?').charAt(0).toUpperCase()}</div>`
    : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>`;

  return `
    <td><div class="item-cell">
      <div class="drag-handle">⋮⋮</div>
      <div class="item-thumb" id="thumb-${tableType}-${row.id}">${thumbHTML}</div>
      <div class="item-selector-wrap">
        <input type="text" class="item-selector-input" id="selectorInput-${tableType}-${row.id}"
               placeholder="Click to select an item"
               value="${row.productData ? escHtml(row.productData.name) : ''}" autocomplete="off">
        <div class="item-dropdown" id="dropdown-${tableType}-${row.id}">${buildDropdownItems(products,'')}</div>
      </div>
    </div></td>
    <td><div style="text-align:right;">
      <input class="qty-input" type="number" min="0" step="0.01"
             value="${row.qty}" id="qty-${tableType}-${row.id}"
             onchange="onQtyChange('${tableType}',${row.id},this.value)">
      ${row.productData ? `<div class="price-sub">₹${fmt(row.sp)} per unit</div>` : ''}
    </div></td>
    <td id="sp-${tableType}-${row.id}">${fmt(row.sp * row.qty)}</td>
    <td id="cp-${tableType}-${row.id}">${fmt(row.cp * row.qty)}</td>
    <td><span class="action-x" onclick="removeRow('${tableType}',${row.id})" title="Remove">✕</span></td>`;
}

function buildDropdownItems(products, query) {
  const q = (query||'').toLowerCase().trim();
  const filtered = q ? products.filter(p=>(p.name||'').toLowerCase().includes(q)||(p.sku||'').toLowerCase().includes(q)) : products;
  if (!filtered.length) return '<div class="dropdown-empty">No items found</div>';
  return filtered.map(p=>`
    <div class="dropdown-item" data-product-id="${p.id}">
      <div class="d-thumb"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>
      <div class="d-info"><div class="d-name">${escHtml(p.name)}</div><div class="d-sku">${p.sku?'SKU: '+escHtml(p.sku):''}</div></div>
      <div class="d-price">₹${fmt(p.selling_price)}</div>
    </div>`).join('');
}

function bindRowEvents(tableType, rowId, products) {
  const input    = document.getElementById(`selectorInput-${tableType}-${rowId}`);
  const dropdown = document.getElementById(`dropdown-${tableType}-${rowId}`);
  if (!input||!dropdown) return;
  input.addEventListener('focus', () => { filterDropdown(tableType, rowId, input.value, products); dropdown.classList.add('open'); });
  input.addEventListener('input', () => { filterDropdown(tableType, rowId, input.value, products); dropdown.classList.add('open'); });
  document.addEventListener('click', (e) => { if(!input.contains(e.target)&&!dropdown.contains(e.target)) dropdown.classList.remove('open'); }, { capture: true });
  dropdown.addEventListener('click', (e) => {
    const item = e.target.closest('.dropdown-item');
    if (!item) return;
    const product = products.find(p => p.id === parseInt(item.dataset.productId));
    if (product) selectProduct(tableType, rowId, product);
    dropdown.classList.remove('open');
  });
}

function filterDropdown(tableType, rowId, query, products) {
  const dropdown = document.getElementById(`dropdown-${tableType}-${rowId}`);
  if (dropdown) dropdown.innerHTML = buildDropdownItems(products, query);
}

function selectProduct(tableType, rowId, product) {
  const rowObj = associateRows[tableType].find(r => r.id === rowId);
  if (!rowObj) return;
  rowObj.productId = product.id; rowObj.productData = product;
  rowObj.sp = parseFloat(product.selling_price)||0;
  rowObj.cp = parseFloat(product.cost_price)||0;
  const input = document.getElementById(`selectorInput-${tableType}-${rowId}`);
  if (input) input.value = product.name;
  const thumb = document.getElementById(`thumb-${tableType}-${rowId}`);
  if (thumb) {
    let imgPath = null;
    if (product.product_image) {
      try { const imgObj = typeof product.product_image==='string'?JSON.parse(product.product_image):product.product_image; imgPath=imgObj?.front_image||null; } catch(e){}
    }
    if (imgPath) { thumb.innerHTML=`<img src="/${imgPath}" alt="${escHtml(product.name)}" style="width:100%;height:100%;object-fit:cover;" onerror="this.parentElement.innerHTML='📦'">`;
    } else { const letter=(product.name||'?').charAt(0).toUpperCase(); thumb.innerHTML=`<div style="width:100%;height:100%;background:#e8efff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">${letter}</div>`; }
  }
  refreshRowPrices(tableType, rowId);
  updateTotals(tableType);
  buildJsonPayload();
}

function refreshRowPrices(tableType, rowId) {
  const row = associateRows[tableType].find(r=>r.id===rowId);
  if (!row) return;
  const spCell=document.getElementById(`sp-${tableType}-${rowId}`);
  const cpCell=document.getElementById(`cp-${tableType}-${rowId}`);
  const qtyEl=document.getElementById(`qty-${tableType}-${rowId}`);
  if (qtyEl) row.qty = parseFloat(qtyEl.value)||1;
  if (spCell) spCell.textContent = fmt(row.sp*row.qty);
  if (cpCell) cpCell.textContent = fmt(row.cp*row.qty);
  if (row.productData) {
    const td = qtyEl?.parentElement;
    if (td) { let sub=td.querySelector('.price-sub'); if(!sub){sub=document.createElement('div');sub.className='price-sub';td.appendChild(sub);} sub.textContent=`₹${fmt(row.sp)} per unit`; }
  }
}

function onQtyChange(tableType, rowId, val) {
  const row = associateRows[tableType].find(r=>r.id===rowId);
  if (!row) return;
  row.qty = parseFloat(val)||0;
  refreshRowPrices(tableType, rowId);
  updateTotals(tableType);
  buildJsonPayload();
}

function updateTotals(tableType) {
  const rows = associateRows[tableType];
  document.getElementById(`${tableType}TotalSP`).textContent = fmt(rows.reduce((s,r)=>s+r.sp*r.qty,0));
  document.getElementById(`${tableType}TotalCP`).textContent = fmt(rows.reduce((s,r)=>s+r.cp*r.qty,0));
}

function buildJsonPayload() {
  const map = type => associateRows[type].filter(r=>r.productId).map(r=>({product_id:r.productId,quantity:r.qty,selling_price:r.sp,cost_price:r.cp}));
  document.getElementById('associateItemsJson').value = JSON.stringify({items:map('items'),services:map('services')});
}

function copyFromTotal(type) {
  const allRows = [...associateRows.items,...associateRows.services];
  if (type==='sp') document.getElementById('sellingPriceInput').value = allRows.reduce((s,r)=>s+r.sp*r.qty,0).toFixed(2);
  else             document.getElementById('costPriceInput').value    = allRows.reduce((s,r)=>s+r.cp*r.qty,0).toFixed(2);
}

function submitForm() {
  const btn = document.querySelector('.btn-save');
  btn.textContent = 'Updating...'; btn.disabled = true;
  buildJsonPayload();
  document.getElementById('compositeForm').submit();
}

function fmt(n) { return parseFloat(n||0).toFixed(2); }
function escHtml(str) { if(!str)return''; return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

// ── INIT ──
document.addEventListener('DOMContentLoaded', () => {
  const checkedType = document.querySelector('input[name="type"]:checked')?.value;
  if (checkedType) toggleKitFields(checkedType);

  if (EXISTING_ITEMS.length) {
    EXISTING_ITEMS.forEach(item => {
      const productData = AVAILABLE_ITEMS.find(p=>p.id===item.product_id) || { id:item.product_id, name:item.name||'Unknown', sku:item.sku||'', selling_price:item.selling_price||0, cost_price:item.cost_price||0, product_image:null };
      addItemRow('items', { product_id:item.product_id, productData, quantity:item.quantity, selling_price:item.selling_price||0, cost_price:item.cost_price||0 });
    });
  } else { addItemRow('items'); }

  if (EXISTING_SERVICES.length) {
    EXISTING_SERVICES.forEach(svc => {
      const productData = AVAILABLE_SERVICES.find(p=>p.id===svc.product_id) || { id:svc.product_id, name:svc.name||'Unknown', sku:svc.sku||'', selling_price:svc.selling_price||0, cost_price:svc.cost_price||0, product_image:null };
      addItemRow('services', { product_id:svc.product_id, productData, quantity:svc.quantity, selling_price:svc.selling_price||0, cost_price:svc.cost_price||0 });
    });
  } else { addItemRow('services'); }

  buildJsonPayload();
});
</script>
@endpush