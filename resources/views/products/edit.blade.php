@extends('layouts.app')

@section('title', ($displayProductName ?? $product->name))

@section('breadcrumb')
    <a href="{{ route('products.index') }}">Items</a>
    <span class="sep">›</span>
    <span class="current">{{ $displayProductName ?? $product->name }}</span>
@endsection
@push('styles')
<style>
  /* show page styles */
  .content { display: flex; }
  .product-list-panel { width: 280px; background: #fff; border-right: 1px solid var(--border); display: flex; flex-direction: column; flex-shrink: 0; overflow: hidden; }

      * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }
    .sidebar { width: 220px; background: #1a2340; color: #b0b8cc; display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-logo { padding: 18px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #2a3556; }
    .sidebar-logo-icon { width: 32px; height: 32px; background: #2d5be3; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .sidebar-logo span { color: #fff; font-weight: 600; font-size: 16px; }
    .sidebar-menu { flex: 1; overflow-y: auto; padding: 10px 0; }
    .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; cursor: pointer; color: #b0b8cc; transition: background 0.15s; }
    .sidebar-item:hover { background: #243060; }
    .sidebar-item.active { background: #2d5be3; color: #fff; font-weight: 600; }
    .sidebar-item .arrow { margin-left: auto; font-size: 11px; }
    .sidebar-sub { padding-left: 48px; padding-bottom: 4px; }
    .sidebar-sub-active { color: #2d5be3; font-size: 13px; padding: 5px 10px; font-weight: 600; background: #1e2d52; border-radius: 4px; cursor: pointer; }
    .sidebar-sub-item { color: #b0b8cc; font-size: 13px; padding: 5px 10px; cursor: pointer; }
    .sidebar-apps-label { padding: 14px 20px 4px; color: #6b7a99; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
    .sidebar-collapse { padding: 10px 20px; border-top: 1px solid #2a3556; color: #6b7a99; font-size: 12px; cursor: pointer; }
    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 28px 32px; }
    .form-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); padding: 28px 32px; width: 100%; box-sizing: border-box; max-width: 1100px; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .form-header h2 { font-size: 22px; font-weight: 700; }
    .form-close { font-size: 20px; color: #aaa; cursor: pointer; text-decoration: none; }
    hr.divider { border: none; border-top: 1px solid #e8eaf0; margin: 20px 0; }
    .form-row { display: flex; align-items: center; margin-bottom: 16px; gap: 16px; }
    .form-row label.field-label { width: 140px; font-weight: 500; color: #444; text-align: right; flex-shrink: 0; }
    .form-row label.field-label.required { color: #c0392b; }
    .form-row input[type="text"], .form-row input[type="number"], .form-row textarea { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; font-family: inherit; outline: none; }
    .form-row input:focus, .form-row textarea:focus { border-color: #2d5be3; }
    .name-input { border: 2px solid #2d5be3 !important; }
    .select-wrap { flex: 1; position: relative; }
    .select-wrap select { width: 100%; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; background: #fff; appearance: none; color: #333; font-family: inherit; outline: none; }
    .select-wrap select:focus { border-color: #2d5be3; }
    .select-wrap .arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #888; }
    .brand-row-wrap { display: flex; align-items: center; gap: 8px; flex: 1; }
    .brand-row-wrap .select-wrap { flex: 1; }
    .btn-gear { width: 34px; height: 34px; border: 1px solid #d0d4de; border-radius: 6px; background: #f5f6fa; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; color: #555; transition: background 0.15s; }
    .btn-gear:hover { background: #e8eaf0; }
    .radio-group { display: flex; gap: 20px; }
    .radio-option { display: flex; align-items: center; gap: 6px; cursor: pointer; color: #555; font-weight: 500; user-select: none; }
    .radio-option input[type="radio"] { accent-color: #2d5be3; width: 16px; height: 16px; cursor: pointer; }
    .radio-option.checked { color: #2d5be3; }
    .item-type-group { display: flex; gap: 14px; }
    .item-type-btn { border: 2px solid #d0d4de; border-radius: 8px; padding: 10px 22px; cursor: not-allowed; display: flex; align-items: center; gap: 8px; font-weight: 500; color: #555; background: #fff; min-width: 150px; justify-content: center; user-select: none; pointer-events: none; }
    .item-type-btn.active { border-color: #2d5be3; color: #2d5be3; background: #f0f4ff; opacity: 1; }
    .item-type-btn:not(.active) { opacity: 0.45; }
    .radio-dot { width: 16px; height: 16px; border-radius: 50%; border: 2px solid #aaa; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .radio-dot.active { border-color: #2d5be3; background: #2d5be3; }
    .radio-dot-inner { width: 6px; height: 6px; border-radius: 50%; background: #fff; }
    .section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .section-title h3 { font-weight: 700; font-size: 15px; color: #222; }
    .section-title input[type="checkbox"] { width: 16px; height: 16px; accent-color: #2d5be3; cursor: pointer; }
    h3.plain-title { font-weight: 700; font-size: 15px; color: #222; margin-bottom: 16px; }
    .two-col { display: flex; gap: 32px; flex-wrap: wrap; }
    .two-col > * { flex: 1; }
    .inr-wrap { display: flex; flex: 1; border: 1px solid #d0d4de; border-radius: 6px; overflow: hidden; }
    .inr-prefix { background: #f0f1f5; padding: 8px 12px; border-right: 1px solid #d0d4de; color: #555; font-weight: 600; }
    .inr-wrap input { flex: 1; border: none; padding: 8px 12px; font-size: 14px; outline: none; font-family: inherit; }
    .image-panel { width: 280px; border: 1px solid #e0e3ea; border-radius: 10px; padding: 16px; background: #fafbfc; flex-shrink: 0; }
    .upload-label { font-weight: 600; margin-bottom: 10px; color: #444; font-size: 13px; }
    .drop-zone { border: 2px dashed #d0d4de; border-radius: 10px; min-height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: border-color 0.2s, background 0.2s; padding: 20px 12px; text-align: center; gap: 8px; }
    .drop-zone:hover, .drop-zone.dragover { border-color: #2d5be3; background: #f0f4ff; }
    .drop-zone .upload-icon { width: 40px; height: 40px; background: #2d5be3; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; margin-bottom: 4px; }
    .drop-zone .drop-title { font-weight: 700; color: #333; font-size: 13px; }
    .drop-zone .drop-sub { color: #999; font-size: 11px; line-height: 1.5; }
    .drop-zone .browse-link { color: #2d5be3; text-decoration: underline; cursor: pointer; font-size: 12px; }
    .existing-img-wrap { margin-bottom: 10px; position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #e0e3ea; }
    .existing-img-wrap img { width: 100%; max-height: 160px; object-fit: cover; display: block; }
    .btn-remove-existing { position: absolute; top: 6px; right: 6px; background: rgba(0,0,0,0.55); color: #fff; border: none; border-radius: 4px; padding: 3px 8px; font-size: 11px; cursor: pointer; }
    .btn-remove-existing:hover { background: rgba(220,30,30,0.8); }
    #front-preview-wrap { margin-top: 10px; display: none; position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #e0e3ea; }
    #front-preview-wrap img { width: 100%; max-height: 160px; object-fit: cover; display: block; }
    #front-preview-wrap .preview-actions { position: absolute; top: 6px; right: 6px; display: flex; gap: 4px; }
    #front-preview-wrap .preview-actions button { background: rgba(0,0,0,0.55); color: #fff; border: none; border-radius: 4px; padding: 3px 8px; font-size: 11px; cursor: pointer; }
    #front-preview-wrap .preview-actions button:hover { background: rgba(220,30,30,0.8); }
    #front-file-name { font-size: 11px; color: #888; margin-top: 6px; text-align: center; }
    .track-note { color: #888; font-size: 12px; margin-left: 26px; margin-bottom: 16px; }
    .btn-save { background: #2d5be3; color: #fff; border: none; border-radius: 7px; padding: 10px 28px; font-weight: 700; font-size: 14px; cursor: pointer; }
    .btn-save:hover { background: #1e4acf; }
    .btn-cancel { background: #fff; color: #555; border: 1px solid #d0d4de; border-radius: 7px; padding: 10px 28px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-cancel:hover { background: #f5f6fa; }
    .btn-group { display: flex; gap: 12px; margin-top: 8px; }
    .dim-hint { color: #aaa; font-size: 12px; margin-top: 4px; }
    .section-body { display: block; }
    .section-body.hidden { display: none; }
    textarea { resize: vertical; min-height: 60px; }
    .text-danger { color: #dc3545; font-size: 12px; margin-top: 4px; display: block; }
    .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
    .alert-danger { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
    /* Brand Modal */
    .brand-success { color: #28a745; font-size: 12px; margin-bottom: 10px; display: none; padding: 8px; background: #d4edda; border-radius: 4px; }
    .brand-list-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 6px; font-size: 13px; }
    .brand-list-item:hover { background: #f5f6fa; }
    .brand-actions { display: flex; gap: 4px; }
    .btn-del-brand, .btn-edit-brand { background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 14px; }
    .btn-edit-brand { color: #2d5be3; } .btn-edit-brand:hover { background: #e8f0fe; }
    .btn-del-brand { color: #e74c3c; } .btn-del-brand:hover { background: #fde8e8; }
    #btn-update-brand { background: #28a745; } #btn-update-brand:hover { background: #218838; }
    #btn-cancel-edit { background: #6c757d; } #btn-cancel-edit:hover { background: #5a6268; }
    .brand-list-item.editing { background: #fff3cd; border-left: 3px solid #ffc107; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; width: 480px; max-width: 95vw; box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e8eaf0; font-weight: 700; font-size: 15px; }
    .modal-close { cursor: pointer; font-size: 18px; color: #aaa; background: none; border: none; }
    .modal-body { padding: 20px; }
    .add-brand-row { display: flex; gap: 8px; margin-bottom: 8px; }
    .add-brand-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; outline: none; font-family: inherit; }
    .add-brand-row input:focus { border-color: #2d5be3; }
    .btn-add-brand { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-weight: 600; cursor: pointer; font-size: 13px; white-space: nowrap; }
    .btn-add-brand:hover { background: #1e4acf; } .btn-add-brand:disabled { background: #a0b4f0; cursor: not-allowed; }
    .brand-error { color: #dc3545; font-size: 12px; margin-bottom: 10px; display: none; }
    .brand-list { max-height: 260px; overflow-y: auto; margin-top: 12px; }
    .brand-empty { color: #aaa; font-size: 13px; text-align: center; padding: 20px 0; }
    /* Unit Dropdown */
    .unit-dropdown-wrap { position: relative; flex: 1; }
    .unit-input-box { display: flex; align-items: center; border: 1px solid #d0d4de; border-radius: 6px; background: #fff; cursor: pointer; transition: border-color 0.15s; }
    .unit-input-box.focused { border-color: #2d5be3; }
    .unit-input-box input { flex: 1; border: none; outline: none; padding: 8px 12px; font-size: 14px; font-family: inherit; background: transparent; cursor: text; color: #333; min-width: 0; }
    .unit-input-box input::placeholder { color: #aaa; }
    .unit-chevron { padding: 8px 10px; color: #888; font-size: 11px; pointer-events: none; user-select: none; flex-shrink: 0; }
    .unit-dropdown-menu { display: none; position: absolute; top: calc(100% + 3px); left: 0; width: 100%; background: #fff; border: 1px solid #d0d4de; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); z-index: 500; max-height: 260px; overflow-y: auto; }
    .unit-dropdown-menu.open { display: block; }
    .unit-option { display: flex; align-items: center; justify-content: space-between; padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer; transition: background 0.1s; user-select: none; }
    .unit-option:hover { background: #f0f4ff; }
    .unit-option.selected { background: #e8efff; color: #2d5be3; font-weight: 600; }
    .unit-option .unit-del { display: none; background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 12px; padding: 2px 7px; border-radius: 4px; line-height: 1; }
    .unit-option:hover .unit-del { display: inline-flex; align-items: center; }
    .unit-no-result { padding: 10px 14px; color: #aaa; font-size: 13px; }
    .unit-add-new { padding: 9px 14px; font-size: 13px; color: #2d5be3; cursor: pointer; border-top: 1px solid #e8eaf0; display: flex; align-items: center; gap: 6px; font-weight: 500; }
    .unit-add-new:hover { background: #f0f4ff; }
    /* Identifier */
    .add-identifier-wrap { margin-top: 10px; margin-left: 156px; }
    .add-link { color: #2d5be3; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; user-select: none; }
    .add-link:hover { text-decoration: underline; }
    .identifier-fields { display: none; margin-top: 14px; }
    .identifier-fields.show { display: block; }
    .identifier-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 32px; }
    .identifier-row { display: flex; align-items: center; gap: 12px; }
    .identifier-row label { width: 55px; font-size: 13px; color: #555; font-weight: 500; flex-shrink: 0; text-align: right; }
    .identifier-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; }
    .identifier-row input:focus { border-color: #2d5be3; }
    /* Variants styles */
    #variations-section { display: none; }
    #variations-section.show { display: block; }
    .variation-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; padding: 14px 16px; background: #f8f9fc; border: 1px solid #e8eaf0; border-radius: 8px; }
    .var-attr-wrap { width: 200px; flex-shrink: 0; }
    .var-options-wrap { flex: 1; }
    .tag-input-box { display: flex; flex-wrap: wrap; gap: 6px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 10px; background: #fff; min-height: 40px; cursor: text; }
    .tag-input-box:focus-within { border-color: #2d5be3; }
    .tag-chip { display: inline-flex; align-items: center; gap: 4px; background: #e8efff; color: #2d5be3; border-radius: 4px; padding: 3px 8px; font-size: 12px; font-weight: 500; }
    .tag-chip button { background: none; border: none; cursor: pointer; color: #2d5be3; font-size: 14px; line-height: 1; padding: 0 1px; }
    .tag-chip button:hover { color: #e74c3c; }
    .tag-real-input { border: none; outline: none; font-size: 13px; font-family: inherit; min-width: 80px; flex: 1; padding: 2px 4px; }
    .var-del-btn { background: none; border: none; cursor: pointer; color: #ccc; font-size: 18px; padding: 6px; flex-shrink: 0; border-radius: 4px; transition: color 0.15s; }
    .var-del-btn:hover { color: #e74c3c; background: #fde8e8; }
    .add-variation-btn { display: inline-flex; align-items: center; gap: 6px; color: #2d5be3; font-size: 13px; font-weight: 500; cursor: pointer; border: none; background: none; padding: 6px 0; margin-top: 4px; }
    .add-variation-btn:hover { text-decoration: underline; }
    #variants-table-wrap { display: none; margin-top: 20px; }
    #variants-table-wrap.show { display: block; }
    .variants-tbl { width: 100%; border-collapse: collapse; }
    .variants-tbl thead tr { background: #f5f6fa; border-bottom: 2px solid #e0e3ea; }
    .variants-tbl thead th { padding: 10px 12px; font-size: 12px; font-weight: 700; color: #e74c3c; text-align: left; text-transform: uppercase; letter-spacing: 0.4px; }
    .variants-tbl thead th.th-sku { color: #555; }
    .variants-tbl thead th .copy-all { color: #2d5be3; font-size: 11px; font-weight: 500; cursor: pointer; display: block; text-decoration: underline; margin-top: 2px; }
    .variants-tbl tbody tr { border-bottom: 1px solid #f0f2f7; }
    .variants-tbl tbody tr:hover { background: #fafbff; }
    .var-row-name { padding: 10px 12px; font-size: 13px; font-weight: 500; color: #333; }
    .var-row-input { padding: 6px 8px; }
    .var-row-input input { width: 100%; border: 1px solid #e0e3ea; border-radius: 5px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; background: #fff; }
    .var-row-input input:focus { border-color: #2d5be3; }
    .var-row-actions { padding: 6px 8px; white-space: nowrap; }
    .btn-var-del { background: none; border: none; cursor: pointer; color: #e74c3c; font-size: 16px; padding: 4px 6px; border-radius: 4px; }
    .btn-var-del:hover { background: #fde8e8; }
    .var-attr-input { width: 100%; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; font-family: inherit; outline: none; }
    .var-attr-input:focus { border-color: #2d5be3; }
    /* single-only / variant-only */
    .single-only.v-hide { display: none !important; }
</style>
@endpush

@section('content')
<div style="display:flex; height:calc(100vh - var(--topbar-h)); overflow:hidden;">
  <div class="content">
    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-danger">{{ session('error') }}</div>@endif

    <div class="form-card">
      <div class="form-header">
        <h2>Edit Item — {{ $product->name }}</h2>
        <a href="{{ url('/products') }}" class="form-close">✕</a>
      </div>

      @php
        /* ── additional_data ── */
        $addData = is_array($product->additional_data)
            ? $product->additional_data
            : (json_decode($product->additional_data ?? '{}', true) ?? []);

        /* ── product_image → URL ──
           DB column la store aana format:
           {"front_image":"storage:products/xxx.jpg"}  OR
           plain path "products/xxx.jpg"               OR
           null / empty
        */
        $rawImg      = $product->product_image ?? '';
$frontImgUrl = null;

if ($rawImg) {
    // JSON decode try
    $imgJson = json_decode($rawImg, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($imgJson['front_image'])) {
        $path = $imgJson['front_image'];
    } else {
        $path = $rawImg;
    }

    if ($path) {
        // storage: prefix strip
        $path = str_replace('storage:', '', $path);
        $path = ltrim($path, '/');
        $frontImgUrl = asset('storage/' . $path);
    }
}

        /* ── settings ── */
        $weightUnit = $settings['weight_unit']    ?? 'kg';
        $dimUnit    = $settings['dimension_unit'] ?? 'cm';
        $decRate    = (int)($settings['decimal_rate'] ?? 2);
        $wStep      = $decRate > 0 ? '0.' . str_repeat('0', $decRate - 1) . '1' : '1';

        /* ── descriptions ── */
        $savedDesc         = $addData['description'] ?? [];
        $savedItemsDesc    = old('items_description',    $savedDesc['items_description']    ?? '');
        $savedSalesDesc    = old('sales_description',    $savedDesc['sales_description']    ?? '');
        $savedPurchaseDesc = old('purchase_description', $savedDesc['purchase_description'] ?? '');

        /* ── variants_data column ── */
        $variantsRaw  = $product->variants_data ?? '{}';
        $variantsData = is_array($variantsRaw)
            ? $variantsRaw
            : (json_decode($variantsRaw, true) ?? []);
        $savedAttributes = $variantsData['attributes'] ?? [];
        $savedVariants   = $variantsData['variants']   ?? [];

        /* ── item type ── */
        $curVariantType = old('item_variant_type', $product->item_variant_type ?? 'single');
        $isVariant      = $curVariantType === 'contains_variants';
      @endphp

      <form action="{{ route('products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data" id="main-form">
        @csrf
        @method('PUT')

        <div style="display:flex;gap:32px;margin-bottom:28px;">
          <div style="flex:1;">

            <!-- Name -->
            <div class="form-row">
              <label class="field-label required">Name*</label>
              <input type="text" name="name" class="name-input"
                     value="{{ old('name', $product->name) }}" required />
              @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <!-- Type -->
            <div class="form-row">
              <label class="field-label">Type</label>
              @php $curType = old('type', $product->type ?? 'goods'); @endphp
              <div class="radio-group">
                <label class="radio-option {{ $curType=='goods'?'checked':'' }}">
                  <input type="radio" name="type" value="goods"
                         {{ $curType=='goods'?'checked':'' }} onchange="updateRadio(this,'type')" /> Goods
                </label>
                <label class="radio-option {{ $curType=='service'?'checked':'' }}">
                  <input type="radio" name="type" value="service"
                         {{ $curType=='service'?'checked':'' }} onchange="updateRadio(this,'type')" /> Service
                </label>
              </div>
            </div>

            <!-- Brand -->
            <div class="form-row">
              <label class="field-label">Brand</label>
              <div class="brand-row-wrap">
                <div class="select-wrap">
                  <select name="brand_id" id="brand-select">
                    <option value="">— Select Brand —</option>
                    @foreach($brands as $brand)
                      <option value="{{ $brand->id }}"
                        @if(old('brand_id'))
                          {{ old('brand_id') == $brand->id ? 'selected' : '' }}
                        @else
                          {{ $product->brand === $brand->name ? 'selected' : '' }}
                        @endif
                      >{{ $brand->name }}</option>
                    @endforeach
                  </select>
                  <span class="arrow">▼</span>
                </div>
                <button type="button" class="btn-gear" onclick="openBrandModal()">⚙️</button>
              </div>
            </div>
  <!-- Admin Only Access -->
<div class="form-row" style="margin-top: 4px;">
    <label class="field-label" style="width:140px;"></label>
    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; user-select:none;">
        <input 
            type="checkbox" 
            name="access_product" 
            id="access_product"
            value="1"
            {{ old('access_product', $product->access_product) ? 'checked' : '' }}
            style="width:16px; height:16px; accent-color:#2d5be3; cursor:pointer;"
        />
        <span style="font-size:13px; color:#555;">
            This product show only for the <strong>Admin</strong>
        </span>
    </label>
</div>
          </div>

          <!-- ══ IMAGE PANEL ══ -->
          <div class="image-panel">
            <div class="upload-label">Product Image</div>
            <input type="file" id="front_image" name="front_image"
                   accept="image/jpeg,image/png,image/jpg,image/gif"
                   style="display:none;" onchange="handleFileSelect(this)">
            <input type="hidden" name="remove_front_image" id="remove-image-flag" value="0" />

            @if($frontImgUrl)
              {{-- Existing image show --}}
              <div class="existing-img-wrap" id="existing-img-wrap">
                <img src="{{ $frontImgUrl }}" alt="Current Image" />
                <button type="button" class="btn-remove-existing"
                        onclick="removeExistingImage()">✕ Remove</button>
              </div>
              <div class="drop-zone" id="drop-zone" style="display:none;"
                   ondragover="onDragOver(event)" ondragleave="onDragLeave(event)"
                   ondrop="onDrop(event)"
                   onclick="document.getElementById('front_image').click()">
                <div class="upload-icon">↑</div>
                <div class="drop-title">Drag & Drop Image</div>
                <div class="drop-sub">or <span class="browse-link">browse</span><br>JPG, PNG, GIF · max 5MB</div>
              </div>
            @else
              {{-- No image — show upload zone --}}
              <div class="drop-zone" id="drop-zone"
                   ondragover="onDragOver(event)" ondragleave="onDragLeave(event)"
                   ondrop="onDrop(event)"
                   onclick="document.getElementById('front_image').click()">
                <div class="upload-icon">↑</div>
                <div class="drop-title">Drag & Drop Image</div>
                <div class="drop-sub">or <span class="browse-link">browse</span><br>JPG, PNG, GIF · max 5MB</div>
              </div>
            @endif

            <div id="front-preview-wrap">
              <img id="front-preview-img" src="" alt="Preview" />
              <div class="preview-actions">
                <button type="button" onclick="clearFrontImage()">✕ Remove</button>
              </div>
            </div>
            <div id="front-file-name"></div>
            @error('front_image')<span class="text-danger">{{ $message }}</span>@enderror
          </div>
        </div>

        {{-- Custom Fields --}}
        @if(isset($customFields) && $customFields->count() > 0)
          <hr class="divider" />
          <h3 class="plain-title">Additional Information</h3>
          @foreach($customFields as $field)
            <div class="form-row">
              <label class="field-label {{ $field->mandatory=='yes'?'required':'' }}">
                {{ $field->name }}@if($field->mandatory=='yes') *@endif
              </label>
              @php
                $cfg          = $field->additional_config ?? [];
                $fieldName    = 'additional_fields[' . $field->id . ']';
                $oldValue     = old('additional_fields.' . $field->id);
                $fieldKey     = strtolower(str_replace(' ', '_', $field->name));
                $savedValue   = $addData[$fieldKey] ?? null;
                $currentValue = $oldValue ?? $savedValue ?? ($cfg['default_value'] ?? '');
              @endphp
              @switch($field->data_type)
                @case('integer') @case('decimal') @case('float') @case('currency') @case('percentage')
                  <input type="number" name="{{ $fieldName }}" value="{{ $currentValue }}"
                         step="{{ in_array($field->data_type,['decimal','float','currency','percentage'])?'0.01':'1' }}"
                         placeholder="{{ $cfg['help_text']??'' }}"
                         {{ $field->mandatory=='yes'?'required':'' }} style="flex:1;" />
                @break
                @case('date')
                  <input type="date" name="{{ $fieldName }}" value="{{ $currentValue }}"
                         {{ $field->mandatory=='yes'?'required':'' }} style="flex:1;" />
                @break
                @case('datetime')
                  <input type="datetime-local" name="{{ $fieldName }}" value="{{ $currentValue }}"
                         {{ $field->mandatory=='yes'?'required':'' }} style="flex:1;" />
                @break
                @case('boolean')
                  <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="{{ $fieldName }}" value="1" {{ $currentValue=='1'?'checked':'' }} /> Yes</label>
                    <label class="radio-option"><input type="radio" name="{{ $fieldName }}" value="0" {{ ($currentValue==='0'||$currentValue===''||is_null($currentValue))?'checked':'' }} /> No</label>
                  </div>
                @break
                @case('array')
                  <div class="select-wrap">
                    <select name="{{ $fieldName }}" {{ $field->mandatory=='yes'?'required':'' }}>
                      <option value="">-- Select --</option>
                      @if(!empty($cfg['options']))
                        @foreach(explode("\n",$cfg['options']) as $opt)
                          @if(trim($opt))
                            <option value="{{ trim($opt) }}" {{ $currentValue==trim($opt)?'selected':'' }}>{{ trim($opt) }}</option>
                          @endif
                        @endforeach
                      @endif
                    </select>
                    <span class="arrow">▼</span>
                  </div>
                @break
                @default
                  <input type="{{ $field->data_type==='email'?'email':($field->data_type==='phone'?'tel':'text') }}"
                         name="{{ $fieldName }}" value="{{ $currentValue }}"
                         placeholder="{{ $cfg['help_text']??'' }}"
                         {{ $field->mandatory=='yes'?'required':'' }}
                         @if(!empty($cfg['char_limit'])) maxlength="{{ $cfg['char_limit'] }}" @endif
                         style="flex:1;" />
              @endswitch
            </div>
          @endforeach
        @endif

        <hr class="divider" />

        <!-- Item Details -->
        <h3 class="plain-title">Item Details</h3>
        <div class="form-row" style="margin-bottom:18px;">
          <label class="field-label">Item Type</label>
          <div class="item-type-group">
            {{-- Buttons are CSS locked (pointer-events:none) — no onclick needed --}}
            <div class="item-type-btn {{ $curVariantType=='single'?'active':'' }}">
              <div class="radio-dot {{ $curVariantType=='single'?'active':'' }}" id="dot-single">
                @if($curVariantType=='single')<div class="radio-dot-inner"></div>@endif
              </div>Single Item
            </div>
            <div class="item-type-btn {{ $curVariantType=='contains_variants'?'active':'' }}">
              <div class="radio-dot {{ $curVariantType=='contains_variants'?'active':'' }}" id="dot-variants">
                @if($curVariantType=='contains_variants')<div class="radio-dot-inner"></div>@endif
              </div>Contains Variants
            </div>
          </div>
          <input type="hidden" name="item_variant_type" id="item_variant_type" value="{{ $curVariantType }}">
        </div>

        <!-- Unit + SKU -->
        <div class="two-col" style="margin-bottom:0;">
          <div class="form-row" style="margin-bottom:0;">
            <label class="field-label required" style="color:#c0392b;">Unit*</label>
            <input type="hidden" name="unit" id="unit-hidden" value="{{ old('unit', $product->unit) }}" />
            <div class="unit-dropdown-wrap">
              <div class="unit-input-box" id="unit-input-box">
                <input type="text" id="unit-search" placeholder="Select or type to add"
                       autocomplete="off" oninput="filterUnits(this.value)" onfocus="openUnitDropdown()" />
                <span class="unit-chevron" id="unit-chevron">▼</span>
              </div>
              <div class="unit-dropdown-menu" id="unit-dropdown"></div>
            </div>
          </div>
          {{-- SKU — hide when variants --}}
          <div class="form-row single-only" id="sku-row" style="margin-bottom:0;">
            <label class="field-label" style="width:80px;">SKU</label>
            <input type="text" name="sku" style="flex:1;" value="{{ old('sku', $product->sku) }}" />
          </div>
        </div>

        {{-- Identifier — hide when variants --}}
        @php $hasIdentifiers = !empty($addData['upc'])||!empty($addData['mpn'])||!empty($addData['ean'])||!empty($addData['isbn']); @endphp
        <div class="add-identifier-wrap single-only" id="identifier-wrap">
          <span class="add-link" id="add-identifier-btn" onclick="toggleIdentifiers(this)">
            @if($hasIdentifiers) ➖ Hide Identifier @else ➕ Add Identifier @endif
          </span>
        </div>
        <div class="identifier-fields single-only {{ $hasIdentifiers?'show':'' }}" id="identifier-fields">
          <div class="identifier-grid">
            <div class="identifier-row"><label>UPC</label><input type="text" name="upc" value="{{ old('upc',$addData['upc']??'') }}" /></div>
            <div class="identifier-row"><label>MPN</label><input type="text" name="mpn" value="{{ old('mpn',$addData['mpn']??'') }}" /></div>
            <div class="identifier-row"><label>EAN</label><input type="text" name="ean" value="{{ old('ean',$addData['ean']??'') }}" /></div>
            <div class="identifier-row"><label>ISBN</label><input type="text" name="isbn" value="{{ old('isbn',$addData['isbn']??'') }}" /></div>
          </div>
        </div>

        <!-- Item Description -->
        <div class="form-row" style="margin-top:14px;">
          <label class="field-label">Item Description</label>
          <textarea name="items_description" style="flex:1;"
                    placeholder="Enter item description...">{{ $savedItemsDesc }}</textarea>
        </div>

        <!-- ══ VARIATIONS SECTION (variants mode only) ══ -->
        <div id="variations-section">
          <hr class="divider" />
          <h3 class="plain-title">Variations</h3>
          <div id="variation-rows-wrap"></div>
          <button type="button" class="add-variation-btn" onclick="addVariationRow()">
            ➕ Add another attribute
          </button>
          <div id="variants-table-wrap">
            <h3 class="plain-title" style="margin-top:20px;">Variants</h3>
            <table class="variants-tbl">
              <thead>
                <tr>
                  <th style="width:32%;">ITEM NAME*</th>
                  <th class="th-sku" style="color:#555;">SKU</th>
                  <th>COST PRICE (₹)*
                    <span style="color:#2d5be3;font-size:11px;font-weight:500;cursor:pointer;display:block;text-decoration:underline;margin-top:2px;" onclick="copyToAll('cost')">COPY TO ALL</span>
                  </th>
                  <th>SELLING PRICE (₹)*
                    <span style="color:#2d5be3;font-size:11px;font-weight:500;cursor:pointer;display:block;text-decoration:underline;margin-top:2px;" onclick="copyToAll('sell')">COPY TO ALL</span>
                  </th>
                  <th style="width:60px;"></th>
                </tr>
              </thead>
              <tbody id="variants-tbody"></tbody>
            </table>
          </div>
          <input type="hidden" name="variants_json" id="variants-json" value="" />
        </div>

        <hr class="divider" />

        <!-- Sales Information -->
        @php $hasSales = old('has_sales', $product->selling_price ? true : ($isVariant ? true : false)); @endphp
        <div class="section-title">
          <input type="checkbox" id="chk-sales" name="has_sales" value="1"
                 {{ $hasSales?'checked':'' }} onchange="toggleSection('chk-sales','sales-body')" />
          <h3>Sales Information</h3>
        </div>
        <div class="section-body {{ $hasSales?'':'hidden' }}" id="sales-body">
          {{-- Single only: Selling Price --}}
          <div class="two-col single-only" id="selling-price-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required">Selling Price*</label>
              <div class="inr-wrap">
                <span class="inr-prefix">INR</span>
                <input type="number" name="selling_price" step="0.01"
                       value="{{ old('selling_price', $product->selling_price) }}" />
              </div>
            </div>
          </div>
          <div class="form-row">
            <label class="field-label">Sales Description</label>
            <textarea name="sales_description" style="flex:1;"
                      placeholder="Description shown on sales documents...">{{ $savedSalesDesc }}</textarea>
          </div>
        </div>

        <hr class="divider" />

        <!-- Purchase Information -->
        @php $hasPurchase = old('has_purchase', $product->cost_price ? true : ($isVariant ? true : false)); @endphp
        <div class="section-title">
          <input type="checkbox" id="chk-purchase" name="has_purchase" value="1"
                 {{ $hasPurchase?'checked':'' }} onchange="toggleSection('chk-purchase','purchase-body')" />
          <h3>Purchase Information</h3>
        </div>
        <div class="section-body {{ $hasPurchase?'':'hidden' }}" id="purchase-body">
          {{-- Single only: Cost Price --}}
          <div class="two-col single-only" id="cost-price-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required">Cost Price*</label>
              <div class="inr-wrap">
                <span class="inr-prefix">INR</span>
                <input type="number" name="cost_price" step="0.01"
                       value="{{ old('cost_price', $product->cost_price) }}" />
              </div>
            </div>
          </div>
          <div class="two-col">
            <div class="form-row" style="align-items:flex-start;margin-bottom:0;">
              <label class="field-label" style="padding-top:8px;">Purchase Description</label>
              <textarea name="purchase_description" style="flex:1;"
                        placeholder="Description shown on purchase documents...">{{ $savedPurchaseDesc }}</textarea>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label">Preferred Vendor</label>
              @php $savedVendor = $addData['account_details']['preferred_vendor'] ?? ''; @endphp
              <div class="select-wrap">
                <select name="preferred_vendor_id" style="width:220px;">
                  <option value="">Select Vendor</option>
                  <optgroup label="Suppliers">
                    <option value="vendor_1" {{ old('preferred_vendor_id',$savedVendor)=='vendor_1'?'selected':'' }}>ABC Suppliers</option>
                    <option value="vendor_2" {{ old('preferred_vendor_id',$savedVendor)=='vendor_2'?'selected':'' }}>XYZ Traders</option>
                    <option value="vendor_3" {{ old('preferred_vendor_id',$savedVendor)=='vendor_3'?'selected':'' }}>Global Imports</option>
                    <option value="vendor_4" {{ old('preferred_vendor_id',$savedVendor)=='vendor_4'?'selected':'' }}>Local Wholesale</option>
                  </optgroup>
                  <optgroup label="Manufacturers">
                    <option value="vendor_5" {{ old('preferred_vendor_id',$savedVendor)=='vendor_5'?'selected':'' }}>Direct Manufacturer A</option>
                    <option value="vendor_6" {{ old('preferred_vendor_id',$savedVendor)=='vendor_6'?'selected':'' }}>Direct Manufacturer B</option>
                  </optgroup>
                  <optgroup label="Distributors">
                    <option value="vendor_7" {{ old('preferred_vendor_id',$savedVendor)=='vendor_7'?'selected':'' }}>National Distributor</option>
                    <option value="vendor_8" {{ old('preferred_vendor_id',$savedVendor)=='vendor_8'?'selected':'' }}>Regional Distributor</option>
                  </optgroup>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
        </div>

        <hr class="divider" />

        <!-- Track Inventory -->
        @php $hasTrack = old('track_inventory', $product->track_inventory ?? true); @endphp
        <div class="section-title">
          <input type="checkbox" id="chk-track" name="track_inventory" value="1"
                 {{ $hasTrack?'checked':'' }} onchange="toggleSection('chk-track','track-body')" />
          <h3>Track Inventory for this item</h3>
        </div>
        <p class="track-note">You cannot enable/disable inventory tracking once you've created transactions for this item</p>
        <div class="section-body {{ $hasTrack?'':'hidden' }}" id="track-body">
          @php $binTracking = old('bin_location_tracking', $product->bin_location_tracking ?? '0'); @endphp
          <div class="form-row" style="margin-bottom:16px;">
            <label class="field-label" style="width:200px;text-align:left;">Bin Location Tracking</label>
            <div class="radio-group">
              <label class="radio-option {{ $binTracking=='1'?'checked':'' }}"><input type="radio" name="bin_location_tracking" value="1" {{ $binTracking=='1'?'checked':'' }} /> Yes</label>
              <label class="radio-option {{ $binTracking!='1'?'checked':'' }}"><input type="radio" name="bin_location_tracking" value="0" {{ $binTracking!='1'?'checked':'' }} /> No</label>
            </div>
          </div>
          <div class="two-col" style="margin-bottom:16px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="width:180px;">Inventory Account*</label>
              @php $savedInvAcc = old('inventory_account_id', $addData['account_details']['inventory_account'] ?? ''); @endphp
              <div class="select-wrap">
                <select name="inventory_account_id">
                  <option value="">Select an account</option>
                  <optgroup label="Assets">
                    <option value="inventory_asset"  {{ $savedInvAcc=='inventory_asset'?'selected':'' }}>Inventory Asset</option>
                    <option value="raw_material"     {{ $savedInvAcc=='raw_material'?'selected':'' }}>Raw Material</option>
                    <option value="work_in_progress" {{ $savedInvAcc=='work_in_progress'?'selected':'' }}>Work In Progress</option>
                    <option value="finished_goods"   {{ $savedInvAcc=='finished_goods'?'selected':'' }}>Finished Goods</option>
                  </optgroup>
                  <optgroup label="Cost of Goods Sold">
                    <option value="cogs"              {{ $savedInvAcc=='cogs'?'selected':'' }}>Cost of Goods Sold</option>
                    <option value="purchase_discount" {{ $savedInvAcc=='purchase_discount'?'selected':'' }}>Purchase Discount</option>
                    <option value="purchase_returns"  {{ $savedInvAcc=='purchase_returns'?'selected':'' }}>Purchase Returns</option>
                  </optgroup>
                  <optgroup label="Income">
                    <option value="sales"          {{ $savedInvAcc=='sales'?'selected':'' }}>Sales</option>
                    <option value="sales_discount" {{ $savedInvAcc=='sales_discount'?'selected':'' }}>Sales Discount</option>
                    <option value="sales_returns"  {{ $savedInvAcc=='sales_returns'?'selected':'' }}>Sales Returns</option>
                  </optgroup>
                  <optgroup label="Expenses">
                    <option value="freight_expense"  {{ $savedInvAcc=='freight_expense'?'selected':'' }}>Freight Expense</option>
                    <option value="purchase_expense" {{ $savedInvAcc=='purchase_expense'?'selected':'' }}>Purchase Expense</option>
                  </optgroup>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="width:220px;">Inventory Valuation Method*</label>
              @php $ivm = old('inventory_valuation_method', $product->inventory_valuation_method ?? ''); @endphp
              <div class="select-wrap">
                <select name="inventory_valuation_method">
                  <option value="">Select the valuation method</option>
                  <option value="FIFO" {{ $ivm=='FIFO'?'selected':'' }}>FIFO</option>
                  <option value="Weighted Average" {{ $ivm=='Weighted Average'?'selected':'' }}>Weighted Average</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
          <div class="form-row">
            <label class="field-label" style="width:160px;">Reorder Point</label>
            <input type="number" name="reorder_point" step="0.01" style="width:300px;"
                   value="{{ old('reorder_point', $product->reorder_point) }}" />
          </div>
        </div>

        <hr class="divider" />

        <!-- Cancellation -->
        @php $isReturnable = old('is_returnable', $product->is_returnable ?? '1'); @endphp
        <h3 class="plain-title">Cancellation and Returns</h3>
        <div class="form-row">
          <label class="field-label" style="width:160px;text-align:left;">Returnable Item</label>
          <div class="radio-group">
            <label class="radio-option {{ $isReturnable=='1'?'checked':'' }}"><input type="radio" name="is_returnable" value="1" {{ $isReturnable=='1'?'checked':'' }} /> Yes</label>
            <label class="radio-option {{ $isReturnable=='0'?'checked':'' }}"><input type="radio" name="is_returnable" value="0" {{ $isReturnable=='0'?'checked':'' }} /> No</label>
          </div>
        </div>

        <hr class="divider" />

        <!-- Fulfilment Details — Single only -->
        <div class="single-only" id="fulfilment-wrap">
          <h3 class="plain-title">Fulfilment Details</h3>
          <div class="two-col">
            <div>
              <div class="form-row" style="margin-bottom:4px;">
                <label class="field-label">Dimensions</label>
                @php $selDimUnit = old('custom_field.dimension_unit', $addData['dimension_unit'] ?? $dimUnit); @endphp
                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                  <input type="number" name="custom_field[length]" step="0.01" style="width:80px;"
                         value="{{ old('custom_field.length', $addData['length']??'') }}" placeholder="L" />
                  <span style="color:#aaa;">x</span>
                  <input type="number" name="custom_field[width]" step="0.01" style="width:80px;"
                         value="{{ old('custom_field.width', $addData['width']??'') }}" placeholder="W" />
                  <span style="color:#aaa;">x</span>
                  <input type="number" name="custom_field[height]" step="0.01" style="width:80px;"
                         value="{{ old('custom_field.height', $addData['height']??'') }}" placeholder="H" />
                  <div class="select-wrap" style="flex:none;width:70px;">
                    <select name="custom_field[dimension_unit]">
                      <option value="cm" {{ $selDimUnit=='cm'?'selected':'' }}>cm</option>
                      <option value="in" {{ $selDimUnit=='in'?'selected':'' }}>in</option>
                      <option value="m"  {{ $selDimUnit=='m'?'selected':'' }}>m</option>
                      <option value="ft" {{ $selDimUnit=='ft'?'selected':'' }}>ft</option>
                      <option value="mm" {{ $selDimUnit=='mm'?'selected':'' }}>mm</option>
                    </select>
                    <span class="arrow">▼</span>
                  </div>
                </div>
              </div>
              <p class="dim-hint" style="margin-left:156px;">(Length × Width × Height)</p>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              @php $selWtUnit = old('custom_field.weight_unit', $addData['weight_unit'] ?? $weightUnit); @endphp
              <label class="field-label" style="width:80px;">Weight</label>
              <input type="number" name="custom_field[weight]" step="{{ $wStep }}" style="width:180px;"
                     value="{{ old('custom_field.weight', $addData['weight']??'') }}" />
              <div class="select-wrap" style="flex:none;width:70px;margin-left:6px;">
                <select name="custom_field[weight_unit]">
                  <option value="kg" {{ $selWtUnit=='kg'?'selected':'' }}>kg</option>
                  <option value="g"  {{ $selWtUnit=='g'?'selected':'' }}>g</option>
                  <option value="lb" {{ $selWtUnit=='lb'?'selected':'' }}>lb</option>
                  <option value="oz" {{ $selWtUnit=='oz'?'selected':'' }}>oz</option>
                  <option value="mg" {{ $selWtUnit=='mg'?'selected':'' }}>mg</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
          <hr class="divider" />
        </div>

        <div class="btn-group">
          <button type="button" class="btn-save" onclick="handleFormSubmit()">Update</button>
          <a href="{{ url('/products') }}" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- BRAND MODAL -->
<div class="modal-overlay" id="brand-modal">
  <div class="modal-box">
    <div class="modal-header">
      <span>⚙️ Manage Brands</span>
      <button class="modal-close" onclick="closeBrandModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="add-brand-row" id="add-brand-form">
        <input type="text" id="new-brand-input" placeholder="Enter brand name..."
               onkeydown="if(event.key==='Enter'){event.preventDefault();addBrand();}" />
        <button class="btn-add-brand" id="btn-add-brand" onclick="addBrand()">+ Add New</button>
      </div>
      <div class="add-brand-row" id="edit-brand-form" style="display:none;">
        <input type="text" id="edit-brand-input" placeholder="Edit brand name..." />
        <input type="hidden" id="edit-brand-id" value="" />
        <button class="btn-add-brand" id="btn-update-brand" onclick="updateBrand()" style="background:#28a745;">✓ Update</button>
        <button class="btn-add-brand" id="btn-cancel-edit" onclick="cancelEdit()" style="background:#6c757d;">✕ Cancel</button>
      </div>
      <div class="brand-error" id="brand-error"></div>
      <div class="brand-success" id="brand-success"></div>
      <div class="brand-list" id="brand-list"><div class="brand-empty">Loading...</div></div>
    </div>
  </div>
</div>
@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ══════════════════════════════════
// PAGE INIT — Item Type + Variants
// ══════════════════════════════════
const _ITEM_TYPE   = '{{ $curVariantType }}';
const _IS_VARIANT  = _ITEM_TYPE === 'contains_variants';

// Saved variants from DB (variants_data column)
const _SAVED_ATTRIBUTES = @json($savedAttributes);
const _SAVED_VARIANTS   = @json($savedVariants);

document.addEventListener('DOMContentLoaded', function () {

  // 1. Apply single/variant UI state
  if (_IS_VARIANT) {
    // Hide single-only fields
    document.querySelectorAll('.single-only').forEach(el => el.classList.add('v-hide'));
    // Show variations section
    document.getElementById('variations-section').classList.add('show');
    // Load saved attribute rows + variants table
    loadSavedVariants();
  }

  // 2. Unit pre-select
  initUnit();
});

// ══════════════════════════════════
// LOAD SAVED VARIANTS (Edit mode)
// ══════════════════════════════════
let varRowCount = 0;
let variantAdditionalData = {};

function loadSavedVariants() {
  if (!_SAVED_ATTRIBUTES || !_SAVED_ATTRIBUTES.length) return;

  // Pre-fill additional data
  _SAVED_VARIANTS.forEach(v => {
    if (v.additional) variantAdditionalData[v.name] = v.additional;
  });

  // Add attribute rows
  _SAVED_ATTRIBUTES.forEach(attr => {
    addVariationRow(attr.attribute || '', attr.options || []);
  });

  // Generate variants table
  regenerateVariants();

  // Fill saved SKU / cost / selling price
  setTimeout(() => {
    document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr => {
      const name  = tr.dataset.variantName;
      const saved = _SAVED_VARIANTS.find(v => v.name === name);
      if (!saved) return;
      const skuEl  = tr.querySelector('.var-sku');
      const costEl = tr.querySelector('.var-cost');
      const sellEl = tr.querySelector('.var-sell');
      if (skuEl  && saved.sku)           skuEl.value  = saved.sku;
      if (costEl && saved.cost_price)    costEl.value = saved.cost_price;
      if (sellEl && saved.selling_price) sellEl.value = saved.selling_price;
    });
  }, 50);
}

// ══════════════════════════════════
// VARIATION ROWS
// ══════════════════════════════════
function addVariationRow(attrVal = '', optionTags = []) {
  varRowCount++;
  const rowId = 'var-row-' + varRowCount;
  const wrap  = document.getElementById('variation-rows-wrap');
  const div   = document.createElement('div');
  div.className = 'variation-row';
  div.id = rowId;

  const tagsHtml = optionTags.map(t =>
    `<span class="tag-chip">${escHtml(t)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button></span>`
  ).join('');

  div.innerHTML = `
    <div class="var-attr-wrap">
      <input type="text" class="var-attr-input"
             placeholder="eg: Color, Size..."
             value="${escHtml(attrVal)}"
             oninput="regenerateVariants()" />
    </div>
    <div class="var-options-wrap">
      <div class="tag-input-box" id="tags-${rowId}" onclick="focusTagInput('${rowId}')">
        ${tagsHtml}
        <input class="tag-real-input" id="input-${rowId}" type="text"
               placeholder="Type and press Enter or comma"
               onkeydown="handleTagKey(event,'${rowId}')"
               oninput="handleTagInput(event,'${rowId}')" />
      </div>
    </div>
    <button type="button" class="var-del-btn" onclick="removeVariationRow('${rowId}')">🗑</button>
  `;
  wrap.appendChild(div);
}

function focusTagInput(rowId) { document.getElementById('input-' + rowId)?.focus(); }

function handleTagKey(e, rowId) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    const v = e.target.value.trim().replace(/,$/, '');
    if (v) addTag(rowId, v);
    e.target.value = '';
  } else if (e.key === 'Backspace' && e.target.value === '') {
    const chips = document.getElementById('tags-' + rowId).querySelectorAll('.tag-chip');
    if (chips.length) chips[chips.length - 1].remove();
    regenerateVariants();
  }
}
function handleTagInput(e, rowId) {
  if (e.target.value.endsWith(',')) {
    const v = e.target.value.slice(0, -1).trim();
    if (v) addTag(rowId, v);
    e.target.value = '';
  }
}
function addTag(rowId, val) {
  const box   = document.getElementById('tags-' + rowId);
  const input = document.getElementById('input-' + rowId);
  const exist = [...box.querySelectorAll('.tag-chip')].map(c => c.textContent.replace('×','').trim());
  if (exist.includes(val)) return;
  const chip = document.createElement('span');
  chip.className = 'tag-chip';
  chip.innerHTML = `${escHtml(val)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button>`;
  box.insertBefore(chip, input);
  regenerateVariants();
}
function removeTag(btn, rowId) { btn.closest('.tag-chip').remove(); regenerateVariants(); }
function removeVariationRow(rowId) { document.getElementById(rowId)?.remove(); regenerateVariants(); }

function getVariationData() {
  return [...document.querySelectorAll('.variation-row')].reduce((acc, row) => {
    const attr  = row.querySelector('.var-attr-input')?.value.trim() || '';
    const chips = [...row.querySelectorAll('.tag-chip')].map(c => c.textContent.replace('×','').trim());
    if (attr && chips.length) acc.push({ attribute: attr, options: chips });
    return acc;
  }, []);
}

// ══════════════════════════════════
// VARIANTS TABLE (Cartesian)
// ══════════════════════════════════
function cartesian(arrays) {
  if (!arrays.length) return [[]];
  return arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...x, y])), [[]]);
}

function regenerateVariants() {
  const data      = getVariationData();
  const tbody     = document.getElementById('variants-tbody');
  const tableWrap = document.getElementById('variants-table-wrap');

  if (!data.length) { tbody.innerHTML = ''; tableWrap.classList.remove('show'); return; }

  const combos = cartesian(data.map(d => d.options));
  if (!combos.length || !combos[0].length) { tbody.innerHTML = ''; tableWrap.classList.remove('show'); return; }

  tableWrap.classList.add('show');

  // Keep existing user-typed values
  const existing = {};
  tbody.querySelectorAll('tr.variant-data-row').forEach(tr => {
    existing[tr.dataset.variantName] = {
      sku:  tr.querySelector('.var-sku')?.value  || '',
      cost: tr.querySelector('.var-cost')?.value || '',
      sell: tr.querySelector('.var-sell')?.value || '',
    };
  });

  tbody.innerHTML = '';
  combos.forEach((combo, idx) => {
    const name = combo.join(' - ');
    const prev = existing[name] || {};
    const tr   = document.createElement('tr');
    tr.className = 'variant-data-row';
    tr.dataset.variantName = name;
    tr.dataset.variantIdx  = idx;
    tr.innerHTML = `
      <td class="var-row-name">${escHtml(name)}</td>
      <td class="var-row-input"><input type="text"   class="var-sku"  value="${escHtml(prev.sku||'')}" /></td>
      <td class="var-row-input"><input type="number" class="var-cost" value="${escHtml(prev.cost||'')}" step="0.01" min="0" /></td>
      <td class="var-row-input"><input type="number" class="var-sell" value="${escHtml(prev.sell||'')}" step="0.01" min="0" /></td>
      <td class="var-row-actions">
        <button type="button" class="btn-var-del" onclick="deleteVariantRow(this,'${escAttr(name)}')" title="Delete">⊗</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function deleteVariantRow(btn, name) {
  btn.closest('tr')?.remove();
  delete variantAdditionalData[name];
}

function copyToAll(field) {
  const sel    = field === 'cost' ? '.var-cost' : '.var-sell';
  const inputs = document.querySelectorAll(sel);
  if (!inputs.length) return;
  const first  = inputs[0].value;
  if (!first) { alert('Fill the first row value'); return; }
  inputs.forEach(i => i.value = first);
}

// ══════════════════════════════════
// PACK + SUBMIT
// ══════════════════════════════════
function packVariants() {
  if (!_IS_VARIANT) return;
  const variants = [];
  document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr => {
    const name = tr.dataset.variantName;
    variants.push({
      name,
      sku:           tr.querySelector('.var-sku')?.value  || '',
      cost_price:    tr.querySelector('.var-cost')?.value || '',
      selling_price: tr.querySelector('.var-sell')?.value || '',
      additional:    variantAdditionalData[name] || {},
    });
  });
  document.getElementById('variants-json').value = JSON.stringify({
    attributes: getVariationData(),
    variants,
  });
}
function handleFormSubmit() {
  packVariants();
  document.getElementById('main-form').submit();
}

// ══════════════════════════════════
// GENERAL
// ══════════════════════════════════
function toggleSection(id, bodyId) {
  document.getElementById(bodyId).classList.toggle('hidden', !document.getElementById(id).checked);
}
function updateRadio(input, name) {
  document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.closest('.radio-option').classList.remove('checked'));
  input.closest('.radio-option').classList.add('checked');
}
function toggleIdentifiers(btn) {
  const fields = document.getElementById('identifier-fields');
  const isOpen = fields.classList.contains('show');
  if (isOpen) { fields.classList.remove('show'); btn.innerHTML = '➕ Add Identifier'; }
  else        { fields.classList.add('show');    btn.innerHTML = '➖ Hide Identifier'; fields.querySelector('input').focus(); }
}

// ══════════════════════════════════
// IMAGE
// ══════════════════════════════════
function removeExistingImage() {
  const wrap = document.getElementById('existing-img-wrap');
  if (wrap) wrap.style.display = 'none';
  document.getElementById('remove-image-flag').value = '1';
  document.getElementById('drop-zone').style.display = 'flex';
}
function onDragOver(e) { e.preventDefault(); document.getElementById('drop-zone').classList.add('dragover'); }
function onDragLeave(e) { document.getElementById('drop-zone').classList.remove('dragover'); }
function onDrop(e) { e.preventDefault(); document.getElementById('drop-zone').classList.remove('dragover'); if (e.dataTransfer.files[0]) applyFile(e.dataTransfer.files[0]); }
function handleFileSelect(input) { if (input.files[0]) applyFile(input.files[0]); }
const MAX_SIZE_BYTES=5*1024*1024,MAX_WIDTH=1920,MAX_HEIGHT=1920,JPEG_QUALITY=0.92,TARGET_MAX_KB=800;
function applyFile(file) {
  if (!file.type.match(/image\/(jpeg|png|jpg|gif)/)) { alert('Please upload a JPG, PNG or GIF image.'); return; }
  if (file.size>MAX_SIZE_BYTES) { alert('Image must be under 5 MB.'); return; }
  document.getElementById('front-file-name').textContent='Compressing…';
  const reader=new FileReader();
  reader.onload=ev=>{
    const img=new Image();
    img.onload=()=>{
      let {width,height}=img;
      if(width>MAX_WIDTH||height>MAX_HEIGHT){const r=Math.min(MAX_WIDTH/width,MAX_HEIGHT/height);width=Math.round(width*r);height=Math.round(height*r);}
      const canvas=document.createElement('canvas');canvas.width=width;canvas.height=height;
      const ctx=canvas.getContext('2d');ctx.fillStyle='#ffffff';ctx.fillRect(0,0,width,height);ctx.drawImage(img,0,0,width,height);
      let quality=JPEG_QUALITY,dataURL=canvas.toDataURL('image/jpeg',quality);
      let sizeKB=Math.round((dataURL.length*3)/4/1024);
      while(sizeKB>TARGET_MAX_KB&&quality>0.5){quality-=0.05;dataURL=canvas.toDataURL('image/jpeg',quality);sizeKB=Math.round((dataURL.length*3)/4/1024);}
      const byteStr=atob(dataURL.split(',')[1]),ab=new ArrayBuffer(byteStr.length),ia=new Uint8Array(ab);
      for(let i=0;i<byteStr.length;i++)ia[i]=byteStr.charCodeAt(i);
      const blob=new Blob([ab],{type:'image/jpeg'});
      const origName=file.name.replace(/\.[^.]+$/,'')+'.jpg';
      const dt=new DataTransfer();dt.items.add(new File([blob],origName,{type:'image/jpeg'}));
      document.getElementById('front_image').files=dt.files;
      document.getElementById('front-preview-img').src=dataURL;
      document.getElementById('front-preview-wrap').style.display='block';
      document.getElementById('drop-zone').style.display='none';
      const ew=document.getElementById('existing-img-wrap');if(ew)ew.style.display='none';
      document.getElementById('front-file-name').textContent=origName+' — '+sizeKB+' KB';
    };
    img.src=ev.target.result;
  };
  reader.readAsDataURL(file);
}
function clearFrontImage() {
  document.getElementById('front_image').value='';
  document.getElementById('front-preview-img').src='';
  document.getElementById('front-preview-wrap').style.display='none';
  document.getElementById('drop-zone').style.display='flex';
  document.getElementById('front-file-name').textContent='';
}

// ══════════════════════════════════
// UNIT DROPDOWN
// ══════════════════════════════════
const DEFAULT_UNITS=[
  {code:'BOX',name:'box'},{code:'CMS',name:'cm'},{code:'DOZ',name:'dz'},{code:'FTS',name:'ft'},
  {code:'GMS',name:'g'},{code:'INC',name:'in'},{code:'KGS',name:'kg'},{code:'KME',name:'km'},
  {code:'LBS',name:'lb'},{code:'MGS',name:'mg'},{code:'PCS',name:'pcs'},{code:'LTR',name:'l'},
  {code:'MLT',name:'ml'},{code:'MTR',name:'m'},{code:'NOS',name:'nos'},{code:'OZS',name:'oz'},
  {code:'TNE',name:'ton'},{code:'YDS',name:'yd'},
];
let unitOpen=false,selectedUnit=null;
function getCustomUnits(){try{return JSON.parse(localStorage.getItem('custom_units')||'[]');}catch{return[];}}
function saveCustomUnits(u){localStorage.setItem('custom_units',JSON.stringify(u));}
function allUnits(){return[...DEFAULT_UNITS,...getCustomUnits()];}
function renderUnitDropdown(filter){
  filter=(filter||'').trim().toLowerCase();
  const menu=document.getElementById('unit-dropdown');
  const cc=getCustomUnits().map(u=>u.code);
  const matched=allUnits().filter(u=>!filter||u.code.toLowerCase().includes(filter)||u.name.toLowerCase().includes(filter));
  let html=matched.length===0?'<div class="unit-no-result">No match — type to add</div>'
    :matched.map(u=>{const isSel=selectedUnit&&selectedUnit.code===u.code;const del=cc.includes(u.code)?`<button class="unit-del" type="button" onclick="deleteCustomUnit(event,'${u.code}')">✕</button>`:'';return`<div class="unit-option${isSel?' selected':''}" onclick="selectUnit('${u.code}','${u.name}')"><span>${u.code} - ${u.name}</span>${del}</div>`;}).join('');
  const exists=allUnits().some(u=>u.code.toLowerCase()===filter||u.name.toLowerCase()===filter);
  if(filter&&!exists)html+=`<div class="unit-add-new" onclick="addCustomUnit('${filter.replace(/'/g,"\\'")}')">➕ Add "${filter}"</div>`;
  menu.innerHTML=html;
}
function openUnitDropdown(){if(unitOpen)return;unitOpen=true;document.getElementById('unit-dropdown').classList.add('open');document.getElementById('unit-chevron').textContent='▲';document.getElementById('unit-input-box').classList.add('focused');renderUnitDropdown(document.getElementById('unit-search').value);}
function closeUnitDropdown(){if(!unitOpen)return;unitOpen=false;document.getElementById('unit-dropdown').classList.remove('open');document.getElementById('unit-chevron').textContent='▼';document.getElementById('unit-input-box').classList.remove('focused');document.getElementById('unit-search').value=selectedUnit?`${selectedUnit.code} - ${selectedUnit.name}`:'';}
function filterUnits(val){if(!unitOpen)openUnitDropdown();renderUnitDropdown(val);}
function selectUnit(code,name){selectedUnit={code,name};document.getElementById('unit-hidden').value=name;document.getElementById('unit-search').value=`${code} - ${name}`;closeUnitDropdown();}
function addCustomUnit(input){const code=input.toUpperCase().replace(/\s+/g,'').slice(0,8);const name=input.toLowerCase().trim();const c=getCustomUnits();if(!c.find(u=>u.code===code)){c.push({code,name});saveCustomUnits(c);}selectUnit(code,name);}
function deleteCustomUnit(e,code){e.stopPropagation();if(!confirm(`"${code}" delete?`))return;const u=getCustomUnits().filter(u=>u.code!==code);saveCustomUnits(u);if(selectedUnit?.code===code){selectedUnit=null;document.getElementById('unit-hidden').value='';document.getElementById('unit-search').value='';}renderUnitDropdown(document.getElementById('unit-search').value);}
document.addEventListener('click',e=>{const w=document.querySelector('.unit-dropdown-wrap');if(w&&!w.contains(e.target))closeUnitDropdown();});
document.getElementById('unit-input-box').addEventListener('click',e=>{if(e.target.id==='unit-search')return;unitOpen?closeUnitDropdown():openUnitDropdown();});

function initUnit(){
  const val=document.getElementById('unit-hidden').value;
  if(!val)return;
  const found=allUnits().find(u=>u.name===val||u.code===val.toUpperCase());
  if(found){selectedUnit=found;document.getElementById('unit-search').value=`${found.code} - ${found.name}`;}
  else{const code=val.toUpperCase();document.getElementById('unit-search').value=`${code} - ${val}`;selectedUnit={code,name:val};}
}

// ══════════════════════════════════
// BRAND MODAL
// ══════════════════════════════════
function openBrandModal(){document.getElementById('brand-modal').classList.add('open');document.getElementById('new-brand-input').value='';document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';cancelEdit();loadBrands();}
function closeBrandModal(){document.getElementById('brand-modal').classList.remove('open');cancelEdit();}
document.getElementById('brand-modal').addEventListener('click',e=>{if(e.target.id==='brand-modal')closeBrandModal();});
async function loadBrands(){const list=document.getElementById('brand-list');list.innerHTML='<div class="brand-empty">Loading...</div>';try{const res=await fetch('/brands/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();if(j.success)renderBrandList(j.data);else list.innerHTML='<div class="brand-empty" style="color:#e74c3c;">Failed</div>';}catch{list.innerHTML='<div class="brand-empty" style="color:#e74c3c;">Failed</div>';}}
function renderBrandList(brands){const list=document.getElementById('brand-list');if(!brands?.length){list.innerHTML='<div class="brand-empty">No brands</div>';return;}list.innerHTML=brands.map(b=>{const s=esc(b.name);return`<div class="brand-list-item" id="brand-row-${b.id}"><span>${s}</span><div class="brand-actions"><button class="btn-edit-brand" onclick="editBrand(${b.id},'${s.replace(/'/g,"\\'")}')">✏️</button><button class="btn-del-brand" onclick="deleteBrand(${b.id},'${s.replace(/'/g,"\\'")}')">🗑️</button></div></div>`;}).join('');}
function editBrand(id,name){document.getElementById('add-brand-form').style.display='none';document.getElementById('edit-brand-form').style.display='flex';document.getElementById('edit-brand-id').value=id;document.getElementById('edit-brand-input').value=name;document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));document.getElementById(`brand-row-${id}`)?.classList.add('editing');document.getElementById('edit-brand-input').focus();document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';}
function cancelEdit(){document.getElementById('edit-brand-form').style.display='none';document.getElementById('add-brand-form').style.display='flex';document.getElementById('edit-brand-id').value='';document.getElementById('edit-brand-input').value='';document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));}
async function updateBrand(){const id=document.getElementById('edit-brand-id').value,name=document.getElementById('edit-brand-input').value.trim(),errEl=document.getElementById('brand-error'),sucEl=document.getElementById('brand-success'),btn=document.getElementById('btn-update-brand');errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';return;}btn.disabled=true;btn.textContent='Updating...';try{const res=await fetch(`/brands/${id}`,{method:'PUT',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const j=await res.json();if(!res.ok){errEl.textContent=j.errors?.name?.[0]||j.message||'Failed';errEl.style.display='block';}else{sucEl.textContent='Brand updated!';sucEl.style.display='block';const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt)opt.textContent=name;loadBrands();cancelEdit();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='✓ Update';}}
async function addBrand(){const input=document.getElementById('new-brand-input'),errEl=document.getElementById('brand-error'),sucEl=document.getElementById('brand-success'),btn=document.getElementById('btn-add-brand'),name=input.value.trim();errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';input.focus();return;}btn.disabled=true;btn.textContent='Adding...';try{const res=await fetch('/brands',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const j=await res.json();if(!res.ok){errEl.textContent=j.message||'Failed';errEl.style.display='block';}else{input.value='';sucEl.textContent='Brand added!';sucEl.style.display='block';const sel=document.getElementById('brand-select'),opt=document.createElement('option');opt.value=j.data.id;opt.textContent=j.data.name;opt.selected=true;sel.appendChild(opt);loadBrands();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='+ Add New';}}
async function deleteBrand(id,name){if(!confirm(`Delete brand "${name}"?`))return;try{const res=await fetch(`/brands/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();if(res.ok&&j.success){document.getElementById(`brand-row-${id}`)?.remove();const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt){if(opt.selected)document.getElementById('brand-select').value='';opt.remove();}if(!document.getElementById('brand-list').querySelector('.brand-list-item'))document.getElementById('brand-list').innerHTML='<div class="brand-empty">No brands</div>';const s=document.getElementById('brand-success');s.textContent='Brand deleted!';s.style.display='block';setTimeout(()=>s.style.display='none',3000);if(document.getElementById('edit-brand-id').value==id)cancelEdit();}else{alert(j.message||'Failed');}}catch{alert('Failed');}}

// ══════════════════════════════════
// HELPERS
// ══════════════════════════════════
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function escAttr(s){return String(s).replace(/'/g,"\\'");}
</script>
@endpush
</div>
@endsection