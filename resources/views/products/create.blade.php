@extends('layouts.app')

@section('title', 'New Item')

@section('breadcrumb')
    <a href="{{ route('products.index') }}">Items</a>
    <span class="sep">›</span>
    <span class="current">New Item</span>
@endsection

@section('content')
<div style="padding: 24px; max-width: 1200px;">

    @if(session('success'))
        <div style="background:#e6f9f1;border-left:3px solid #1db87a;color:#148f5e;padding:11px 18px;font-size:13px;margin-bottom:16px;border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fff5f8;border-left:3px solid #e8457a;color:#c73265;padding:11px 18px;font-size:13px;margin-bottom:16px;border-radius:6px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="pc-form-card">
        <div class="pc-form-header">
            <h2 class="pc-form-title">New Item</h2>
            <a href="{{ url('/products') }}" class="pc-close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </a>
        </div>

        @if($errors->any())
            <div style="background:#fff5f8;border:1px solid #f4b8cc;color:#c73265;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @php
            $weightUnit = $settings['weight_unit'] ?? 'kg';
            $dimUnit    = $settings['dimension_unit'] ?? 'cm';
            $decRate    = (int)($settings['decimal_rate'] ?? 2);
            $wStep      = $decRate > 0 ? '0.' . str_repeat('0', $decRate - 1) . '1' : '1';
        @endphp

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
            @csrf

            {{-- TOP ROW: fields + image --}}
            <div style="display:flex;gap:28px;margin-bottom:24px;">
                <div style="flex:1;">

                    {{-- Name --}}
                    <div class="pc-row">
                        <label class="pc-label pc-label--req">Name*</label>
                        <div class="pc-field">
                            <input type="text" name="name" class="pc-input pc-input--focus" value="{{ old('name') }}" required />
                            @error('name')<span class="pc-err">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="pc-row">
                        <label class="pc-label">Type</label>
                        <div class="pc-field">
                            <div class="pc-radio-group">
                                <label class="pc-radio {{ old('type','goods')=='goods'?'pc-radio--on':'' }}">
                                    <input type="radio" name="type" value="goods" {{ old('type','goods')=='goods'?'checked':'' }} onchange="updateRadio(this,'type')" /> Goods
                                </label>
                                <label class="pc-radio {{ old('type')=='service'?'pc-radio--on':'' }}">
                                    <input type="radio" name="type" value="service" {{ old('type')=='service'?'checked':'' }} onchange="updateRadio(this,'type')" /> Service
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="pc-row">
                        <label class="pc-label">Category</label>
                        <div class="pc-field">
                            <input type="hidden" name="category_id"   id="cat-hid-id"   value="{{ old('category_id') }}" />
                            <input type="hidden" name="category_name" id="cat-hid-name" value="{{ old('category_name') }}" />
                            <div class="cat-dd-wrap">
                                <div class="cat-dd-box" id="cat-dd-box" onclick="toggleCatDd()">
                                    <span class="cat-dd-label" id="cat-dd-lbl">Select a category</span>
                                    <span class="cat-dd-chev" id="cat-dd-chev">▼</span>
                                </div>
                                <div class="cat-dd-panel" id="cat-dd-panel">
                                    <div class="cat-dd-sr">
                                        <span style="color:#bbb;font-size:13px;">🔍</span>
                                        <input type="text" id="cat-dd-q" placeholder="Search" oninput="filterCatDd(this.value)" autocomplete="off" />
                                    </div>
                                    <div class="cat-dd-list" id="cat-dd-list"><div class="cat-dd-empty">Loading...</div></div>
                                    <div class="cat-dd-manage" onclick="openCatModal();closeCatDd();">⚙️ Manage Categories</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Brand --}}
                    <div class="pc-row">
                        <label class="pc-label">Brand</label>
                        <div class="pc-field">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="pc-select-wrap" style="flex:1;">
                                    <select name="brand_id" id="brand-select" class="pc-select">
                                        <option value="">— Select Brand —</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="pc-chev">▼</span>
                                </div>
                                <button type="button" class="pc-gear-btn" onclick="openBrandModal()" title="Manage Brands">⚙️</button>
                            </div>
                        </div>
                    </div>

                    {{-- Admin only checkbox --}}
                    <div class="pc-row" style="margin-top:4px;">
                        <label class="pc-label"></label>
                        <div class="pc-field">
                            <label class="pc-admin-check">
                                <input type="checkbox" name="access_product" id="access_product" value="1" {{ old('access_product') ? 'checked' : '' }} />
                                🔒 This product show only for the <strong style="color:var(--pink);">Admin</strong>
                            </label>
                        </div>
                    </div>

                </div>

                {{-- Image Panel --}}
                <div class="pc-image-panel">
                    <div class="pc-image-label">Product Image</div>
                    <input type="file" id="front_image" name="front_image" accept="image/jpeg,image/png,image/jpg,image/gif" style="display:none;" onchange="handleFileSelect(this)">
                    <div class="pc-drop-zone" id="drop-zone"
                         ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)"
                         onclick="document.getElementById('front_image').click()">
                        <div class="pc-upload-icon">↑</div>
                        <div class="pc-drop-title">Drag & Drop Image</div>
                        <div class="pc-drop-sub">or <span style="color:var(--pink);text-decoration:underline;cursor:pointer;">browse</span><br>JPG, PNG, GIF · max 5MB</div>
                    </div>
                    <div id="front-preview-wrap" style="display:none;margin-top:10px;position:relative;border-radius:8px;overflow:hidden;border:1px solid var(--border);">
                        <img id="front-preview-img" src="" alt="Preview" style="width:100%;max-height:160px;object-fit:cover;display:block;" />
                        <div style="position:absolute;top:6px;right:6px;">
                            <button type="button" onclick="clearFrontImage()" style="background:rgba(0,0,0,0.55);color:#fff;border:none;border-radius:4px;padding:3px 8px;font-size:11px;cursor:pointer;">✕ Remove</button>
                        </div>
                    </div>
                    <div id="front-file-name" style="font-size:11px;color:#888;margin-top:6px;text-align:center;"></div>
                    @error('front_image')<span class="pc-err">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Custom Fields --}}
            @if(isset($customFields) && $customFields->count() > 0)
                <hr class="pc-divider" />
                <h3 class="pc-section-title">Additional Information</h3>
                @foreach($customFields as $field)
                    <div class="pc-row">
                        <label class="pc-label {{ $field->mandatory == 'yes' ? 'pc-label--req' : '' }}">
                            {{ $field->name }}@if($field->mandatory == 'yes') *@endif
                        </label>
                        <div class="pc-field">
                            @php
                                $config    = $field->additional_config ?? [];
                                $fieldName = 'additional_fields[' . $field->id . ']';
                                $oldValue  = old('additional_fields.' . $field->id);
                            @endphp
                            @switch($field->data_type)
                                @case('integer') @case('decimal') @case('float') @case('currency') @case('percentage')
                                    <input type="number" name="{{ $fieldName }}" class="pc-input" value="{{ $oldValue ?? $config['default_value'] ?? '' }}" step="{{ in_array($field->data_type, ['decimal','float','currency','percentage']) ? '0.01' : '1' }}" placeholder="{{ $config['help_text'] ?? '' }}" {{ $field->mandatory == 'yes' ? 'required' : '' }} />
                                @break
                                @case('date')
                                    <input type="date" name="{{ $fieldName }}" class="pc-input" value="{{ $oldValue ?? '' }}" {{ $field->mandatory == 'yes' ? 'required' : '' }} />
                                @break
                                @case('datetime')
                                    <input type="datetime-local" name="{{ $fieldName }}" class="pc-input" value="{{ $oldValue ?? '' }}" {{ $field->mandatory == 'yes' ? 'required' : '' }} />
                                @break
                                @case('boolean')
                                    <div class="pc-radio-group">
                                        <label class="pc-radio"><input type="radio" name="{{ $fieldName }}" value="1" {{ ($oldValue ?? $config['default_value'] ?? '') == '1' ? 'checked' : '' }} /> Yes</label>
                                        <label class="pc-radio"><input type="radio" name="{{ $fieldName }}" value="0" {{ ($oldValue ?? $config['default_value'] ?? '0') == '0' ? 'checked' : '' }} /> No</label>
                                    </div>
                                @break
                                @case('array')
                                    <div class="pc-select-wrap">
                                        <select name="{{ $fieldName }}" class="pc-select" {{ $field->mandatory == 'yes' ? 'required' : '' }}>
                                            <option value="">-- Select --</option>
                                            @if(!empty($config['options']))
                                                @foreach(explode("\n", $config['options']) as $opt)
                                                    @if(trim($opt))
                                                        <option value="{{ trim($opt) }}" {{ ($oldValue ?? $config['default_value'] ?? '') == trim($opt) ? 'selected' : '' }}>{{ trim($opt) }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="pc-chev">▼</span>
                                    </div>
                                @break
                                @default
                                    <input type="{{ in_array($field->data_type, ['email']) ? 'email' : (in_array($field->data_type, ['phone']) ? 'tel' : 'text') }}" name="{{ $fieldName }}" class="pc-input" value="{{ $oldValue ?? $config['default_value'] ?? '' }}" placeholder="{{ $config['help_text'] ?? '' }}" {{ $field->mandatory == 'yes' ? 'required' : '' }} @if(!empty($config['char_limit'])) maxlength="{{ $config['char_limit'] }}" @endif />
                            @endswitch
                        </div>
                    </div>
                @endforeach
            @endif

            <hr class="pc-divider" />

            {{-- Item Details --}}
            <h3 class="pc-section-title">Item Details</h3>
            <div class="pc-row" style="margin-bottom:18px;">
                <label class="pc-label">Item Type</label>
                <div class="pc-field">
                    <div style="display:flex;gap:12px;">
                        <div class="pc-type-btn {{ old('item_variant_type','single')=='single'?'pc-type-btn--on':'' }}" onclick="selectItemType('single',this)">
                            <div class="pc-dot {{ old('item_variant_type','single')=='single'?'pc-dot--on':'' }}" id="dot-single"><div class="pc-dot-inner"></div></div>
                            Single Item
                        </div>
                        <div class="pc-type-btn {{ old('item_variant_type')=='contains_variants'?'pc-type-btn--on':'' }}" onclick="selectItemType('variants',this)">
                            <div class="pc-dot {{ old('item_variant_type')=='contains_variants'?'pc-dot--on':'' }}" id="dot-variants"></div>
                            Contains Variants
                        </div>
                    </div>
                    <input type="hidden" name="item_variant_type" id="item_variant_type" value="{{ old('item_variant_type','single') }}">
                </div>
            </div>

            {{-- Unit + SKU row --}}
            <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:0;">
                <div class="pc-row" style="flex:1;margin-bottom:0;">
                    <label class="pc-label pc-label--req">Unit*</label>
                    <div class="pc-field">
                        <input type="hidden" name="unit" id="unit-hidden" value="{{ old('unit') }}" />
                        <div class="unit-dropdown-wrap">
                            <div class="unit-input-box" id="unit-input-box">
                                <input type="text" id="unit-search" placeholder="Select or type to add" autocomplete="off" oninput="filterUnits(this.value)" onfocus="openUnitDropdown()" />
                                <span class="unit-chevron" id="unit-chevron">▼</span>
                            </div>
                            <div class="unit-dropdown-menu" id="unit-dropdown"></div>
                        </div>
                        @error('unit')<span class="pc-err">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="pc-row single-only" id="sku-row" style="flex:1;margin-bottom:0;">
                    <label class="pc-label" style="width:80px;">SKU</label>
                    <div class="pc-field">
                        <input type="text" name="sku" class="pc-input" value="{{ old('sku') }}" />
                    </div>
                </div>
            </div>

            {{-- Add Identifier --}}
            <div class="add-identifier-wrap single-only" id="identifier-wrap" style="margin-top:10px;margin-left:152px;">
                <span class="pc-link" id="add-identifier-btn" onclick="toggleIdentifiers(this)">➕ Add Identifier</span>
            </div>
            <div class="identifier-fields single-only" id="identifier-fields" style="display:none;margin-top:14px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px 32px;">
                    <div style="display:flex;align-items:center;gap:12px;"><label style="width:55px;font-size:13px;color:#555;font-weight:500;flex-shrink:0;text-align:right;">UPC</label><input type="text" name="upc" class="pc-input" value="{{ old('upc') }}" /></div>
                    <div style="display:flex;align-items:center;gap:12px;"><label style="width:55px;font-size:13px;color:#555;font-weight:500;flex-shrink:0;text-align:right;">MPN</label><input type="text" name="mpn" class="pc-input" value="{{ old('mpn') }}" /></div>
                    <div style="display:flex;align-items:center;gap:12px;"><label style="width:55px;font-size:13px;color:#555;font-weight:500;flex-shrink:0;text-align:right;">EAN</label><input type="text" name="ean" class="pc-input" value="{{ old('ean') }}" /></div>
                    <div style="display:flex;align-items:center;gap:12px;"><label style="width:55px;font-size:13px;color:#555;font-weight:500;flex-shrink:0;text-align:right;">ISBN</label><input type="text" name="isbn" class="pc-input" value="{{ old('isbn') }}" /></div>
                </div>
            </div>

            {{-- Item Description --}}
            <div class="pc-row" style="margin-top:16px;">
                <label class="pc-label">Description</label>
                <div class="pc-field">
                    <textarea name="items_description" class="pc-textarea" placeholder="Enter item description...">{{ old('items_description') }}</textarea>
                </div>
            </div>

            {{-- VARIATIONS SECTION --}}
            <div id="variations-section" style="display:none;">
                <hr class="pc-divider" />
                <h3 class="pc-section-title">Variations</h3>
                <div id="variation-rows-wrap"></div>
                <button type="button" class="pc-link-btn" onclick="addVariationRow()">➕ Add another attribute</button>
                <div id="variants-table-wrap" style="display:none;margin-top:20px;">
                    <h3 class="pc-section-title" style="margin-top:20px;">Variants</h3>
                    <div style="overflow-x:auto;">
                        <table class="variants-tbl">
                            <thead>
                                <tr>
                                    <th style="width:26%;">ITEM NAME*</th>
                                    <th>SKU <span class="gen-sku-btn" onclick="generateAllSKU()">🔗 Generate SKU</span></th>
                                    <th>COST PRICE (₹)* <span class="copy-all" onclick="copyToAll('cost')">COPY TO ALL</span></th>
                                    <th>SELLING PRICE (₹)* <span class="copy-all" onclick="copyToAll('sell')">COPY TO ALL</span></th>
                                    <th style="width:70px;color:#555;font-size:12px;">IMAGE</th>
                                    <th style="width:80px;"></th>
                                </tr>
                            </thead>
                            <tbody id="variants-tbody"></tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="variants_json" id="variants-json" value="" />
            </div>

            <hr class="pc-divider" />

            {{-- Sales Information --}}
            <div class="pc-section-toggle">
                <input type="checkbox" id="chk-sales" name="has_sales" value="1" {{ old('has_sales',true)?'checked':'' }} onchange="toggleSection('chk-sales','sales-body')" class="pc-toggle-chk" />
                <h3 class="pc-toggle-title">Sales Information</h3>
            </div>
            <div class="{{ old('has_sales',true)?'':'hidden' }}" id="sales-body">
                <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:14px;" class="single-only" id="selling-price-row">
                    <div class="pc-row" style="margin-bottom:0;flex:1;">
                        <label class="pc-label pc-label--req">Selling Price*</label>
                        <div class="pc-field">
                            <div class="pc-inr-wrap"><span class="pc-inr-prefix">INR</span><input type="number" name="selling_price" step="0.01" value="{{ old('selling_price') }}" style="flex:1;border:none;padding:8px 12px;font-size:13px;outline:none;font-family:inherit;" /></div>
                        </div>
                    </div>
                </div>
                <div class="pc-row">
                    <label class="pc-label">Sales Description</label>
                    <div class="pc-field">
                        <textarea name="sales_description" class="pc-textarea" placeholder="Description shown on sales documents...">{{ old('sales_description') }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="pc-divider" />

            {{-- Purchase Information --}}
            <div class="pc-section-toggle">
                <input type="checkbox" id="chk-purchase" name="has_purchase" value="1" {{ old('has_purchase',true)?'checked':'' }} onchange="toggleSection('chk-purchase','purchase-body')" class="pc-toggle-chk" />
                <h3 class="pc-toggle-title">Purchase Information</h3>
            </div>
            <div class="{{ old('has_purchase',true)?'':'hidden' }}" id="purchase-body">
                <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:14px;" class="single-only" id="cost-price-row">
                    <div class="pc-row" style="margin-bottom:0;flex:1;">
                        <label class="pc-label pc-label--req">Cost Price*</label>
                        <div class="pc-field">
                            <div class="pc-inr-wrap"><span class="pc-inr-prefix">INR</span><input type="number" name="cost_price" step="0.01" value="{{ old('cost_price') }}" style="flex:1;border:none;padding:8px 12px;font-size:13px;outline:none;font-family:inherit;" /></div>
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:32px;flex-wrap:wrap;">
                    <div class="pc-row" style="flex:1;">
                        <label class="pc-label">Purchase Description</label>
                        <div class="pc-field"><textarea name="purchase_description" class="pc-textarea" placeholder="Description on purchase documents...">{{ old('purchase_description') }}</textarea></div>
                    </div>
                    <div class="pc-row" style="flex:1;margin-bottom:0;">
                        <label class="pc-label">Preferred Vendor</label>
                        <div class="pc-field">
                            <div class="pc-select-wrap">
                                <select name="preferred_vendor_id" class="pc-select">
                                    <option value="">Select Vendor</option>
                                    <optgroup label="Suppliers">
                                        <option value="vendor_1">ABC Suppliers</option>
                                        <option value="vendor_2">XYZ Traders</option>
                                        <option value="vendor_3">Global Imports</option>
                                    </optgroup>
                                </select>
                                <span class="pc-chev">▼</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="pc-divider" />

            {{-- Track Inventory --}}
            <div class="pc-section-toggle">
                <input type="checkbox" id="chk-track" name="track_inventory" value="1" {{ old('track_inventory',true)?'checked':'' }} onchange="toggleSection('chk-track','track-body')" class="pc-toggle-chk" />
                <h3 class="pc-toggle-title">Track Inventory for this item</h3>
            </div>
            <p style="color:#888;font-size:12px;margin-left:26px;margin-bottom:16px;">You cannot enable/disable inventory tracking once you've created transactions for this item</p>
            <div class="{{ old('track_inventory',true)?'':'hidden' }}" id="track-body">
                <div class="pc-row" style="margin-bottom:16px;">
                    <label class="pc-label" style="width:200px;text-align:left;">Bin Location Tracking</label>
                    <div class="pc-field">
                        <div class="pc-radio-group">
                            <label class="pc-radio"><input type="radio" name="bin_location_tracking" value="1" {{ old('bin_location_tracking')=='1'?'checked':'' }} /> Yes</label>
                            <label class="pc-radio {{ old('bin_location_tracking')!='1'?'pc-radio--on':'' }}"><input type="radio" name="bin_location_tracking" value="0" {{ old('bin_location_tracking')!='1'?'checked':'' }} /> No</label>
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:32px;flex-wrap:wrap;margin-bottom:16px;">
                    <div class="pc-row" style="flex:1;margin-bottom:0;">
                        <label class="pc-label pc-label--req" style="width:180px;">Inventory Account*</label>
                        <div class="pc-field">
                            <div class="pc-select-wrap">
                                <select name="inventory_account_id" class="pc-select">
                                    <option value="">Select an account</option>
                                    <optgroup label="Assets">
                                        <option value="inventory_asset">Inventory Asset</option>
                                        <option value="raw_material">Raw Material</option>
                                        <option value="finished_goods">Finished Goods</option>
                                    </optgroup>
                                </select>
                                <span class="pc-chev">▼</span>
                            </div>
                        </div>
                    </div>
                    <div class="pc-row" style="flex:1;margin-bottom:0;">
                        <label class="pc-label pc-label--req" style="width:200px;">Valuation Method*</label>
                        <div class="pc-field">
                            <div class="pc-select-wrap">
                                <select name="inventory_valuation_method" class="pc-select">
                                    <option value="">Select method</option>
                                    <option value="FIFO" {{ old('inventory_valuation_method')=='FIFO'?'selected':'' }}>FIFO</option>
                                    <option value="Weighted Average" {{ old('inventory_valuation_method')=='Weighted Average'?'selected':'' }}>Weighted Average</option>
                                </select>
                                <span class="pc-chev">▼</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pc-row">
                    <label class="pc-label" style="width:160px;">Reorder Point</label>
                    <div class="pc-field"><input type="text" name="reorder_point" class="pc-input" style="max-width:300px;" value="{{ old('reorder_point') }}" /></div>
                </div>
            </div>

            <hr class="pc-divider" />

            {{-- Cancellation --}}
            <h3 class="pc-section-title">Cancellation and Returns</h3>
            <div class="pc-row">
                <label class="pc-label" style="width:160px;text-align:left;">Returnable Item</label>
                <div class="pc-field">
                    <div class="pc-radio-group">
                        <label class="pc-radio {{ old('is_returnable','1')=='1'?'pc-radio--on':'' }}"><input type="radio" name="is_returnable" value="1" {{ old('is_returnable','1')=='1'?'checked':'' }} /> Yes</label>
                        <label class="pc-radio {{ old('is_returnable')=='0'?'pc-radio--on':'' }}"><input type="radio" name="is_returnable" value="0" {{ old('is_returnable')=='0'?'checked':'' }} /> No</label>
                    </div>
                </div>
            </div>

            <hr class="pc-divider" />

            {{-- Fulfilment Details — Single only --}}
            <div class="single-only" id="fulfilment-wrap">
                <h3 class="pc-section-title">Fulfilment Details</h3>
                <div style="display:flex;gap:32px;flex-wrap:wrap;">
                    <div style="flex:1;">
                        <div class="pc-row" style="margin-bottom:4px;">
                            <label class="pc-label">Dimensions</label>
                            <div class="pc-field">
                                <div style="display:flex;gap:6px;align-items:center;">
                                    <input type="number" name="custom_field[length]" step="0.01" style="width:80px;" class="pc-input" value="{{ old('custom_field.length') }}" placeholder="L" />
                                    <span style="color:#aaa;">x</span>
                                    <input type="number" name="custom_field[width]"  step="0.01" style="width:80px;" class="pc-input" value="{{ old('custom_field.width') }}"  placeholder="W" />
                                    <span style="color:#aaa;">x</span>
                                    <input type="number" name="custom_field[height]" step="0.01" style="width:80px;" class="pc-input" value="{{ old('custom_field.height') }}" placeholder="H" />
                                    <div class="pc-select-wrap" style="width:70px;flex:none;">
                                        <select name="custom_field[dimension_unit]" class="pc-select">
                                            <option value="cm" {{ old('custom_field.dimension_unit',$dimUnit)=='cm'?'selected':'' }}>cm</option>
                                            <option value="in" {{ old('custom_field.dimension_unit',$dimUnit)=='in'?'selected':'' }}>in</option>
                                            <option value="m"  {{ old('custom_field.dimension_unit',$dimUnit)=='m' ?'selected':'' }}>m</option>
                                            <option value="ft" {{ old('custom_field.dimension_unit',$dimUnit)=='ft'?'selected':'' }}>ft</option>
                                            <option value="mm" {{ old('custom_field.dimension_unit',$dimUnit)=='mm'?'selected':'' }}>mm</option>
                                        </select>
                                        <span class="pc-chev">▼</span>
                                    </div>
                                </div>
                                <p style="color:#aaa;font-size:12px;margin-top:4px;">(Length × Width × Height)</p>
                            </div>
                        </div>
                    </div>
                    <div class="pc-row" style="flex:1;margin-bottom:0;">
                        <label class="pc-label" style="width:80px;">Weight</label>
                        <div class="pc-field" style="display:flex;gap:6px;align-items:center;">
                            <input type="number" name="custom_field[weight]" step="{{ $wStep }}" style="width:180px;" class="pc-input" value="{{ old('custom_field.weight') }}" />
                            <div class="pc-select-wrap" style="width:70px;flex:none;">
                                <select name="custom_field[weight_unit]" class="pc-select">
                                    <option value="kg" {{ old('custom_field.weight_unit',$weightUnit)=='kg'?'selected':'' }}>kg</option>
                                    <option value="g"  {{ old('custom_field.weight_unit',$weightUnit)=='g' ?'selected':'' }}>g</option>
                                    <option value="lb" {{ old('custom_field.weight_unit',$weightUnit)=='lb'?'selected':'' }}>lb</option>
                                    <option value="oz" {{ old('custom_field.weight_unit',$weightUnit)=='oz'?'selected':'' }}>oz</option>
                                    <option value="mg" {{ old('custom_field.weight_unit',$weightUnit)=='mg'?'selected':'' }}>mg</option>
                                </select>
                                <span class="pc-chev">▼</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="pc-divider" />
            </div>

            {{-- Save / Cancel --}}
            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="button" class="pc-btn-save" onclick="handleFormSubmit()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Item
                </button>
                <a href="{{ url('/products') }}" class="pc-btn-cancel">Cancel</a>
            </div>

        </form>
    </div>{{-- end pc-form-card --}}

</div>{{-- end padding wrapper --}}

{{-- ══════════ MODALS (same as before) ══════════ --}}

<!-- MANAGE CATEGORIES MODAL -->
<div class="cat-modal-overlay" id="cat-modal">
    <div class="cat-modal-box">
        <div class="cat-modal-head">
            <span>Manage Categories</span>
            <button class="cat-modal-x" onclick="closeCatModal()">✕</button>
        </div>
        <div class="cat-modal-body">
            <div class="cat-new-form" id="cat-new-form">
                <div class="cat-form-row">
                    <label class="req">Category Name*</label>
                    <input type="text" id="cat-name-inp" onkeydown="if(event.key==='Enter'){event.preventDefault();saveCat();}" />
                </div>
                <div class="cat-form-row">
                    <label>Parent Category</label>
                    <div class="cat-psw"><select id="cat-par-sel"><option value="">— None —</option></select><span class="arr">▼</span></div>
                </div>
                <div class="cat-fe" id="cat-fe"></div>
                <div class="cat-form-btns">
                    <button class="btn-cs" id="btn-cs" onclick="saveCat()">Save</button>
                    <button class="btn-cc" onclick="hideCatForm()">Cancel</button>
                </div>
            </div>
            <div class="cat-list-head">
                <span class="cat-list-title">CATEGORIES</span>
                <button class="btn-anc" id="btn-anc" onclick="showCatForm()"><span class="anc-circle">+</span> Add New Category</button>
            </div>
            <div class="cat-ms" id="cat-ms"></div>
            <div id="cat-li-wrap"><div class="cat-li-empty">Loading...</div></div>
        </div>
        <div class="cat-modal-foot"><button class="btn-cf" onclick="closeCatModal()">Cancel</button></div>
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
                <input type="text" id="new-brand-input" placeholder="Enter brand name..." onkeydown="if(event.key==='Enter'){event.preventDefault();addBrand();}" />
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

<!-- ADDITIONAL INFO MODAL (per variant) -->
<div class="addl-modal-overlay" id="addl-modal">
    <div class="addl-modal-box">
        <div class="addl-modal-head"><span>Additional Information</span><button class="addl-modal-x" onclick="closeAddlModal()">✕</button></div>
        <div class="addl-modal-body">
            <div class="addl-section-title">Identifiers</div>
            <div class="addl-grid">
                <div class="addl-field-row"><label>UPC</label><input type="text" id="addl-upc" /></div>
                <div class="addl-field-row"><label>MPN</label><input type="text" id="addl-mpn" /></div>
                <div class="addl-field-row"><label>EAN</label><input type="text" id="addl-ean" /></div>
                <div class="addl-field-row"><label>ISBN</label><input type="text" id="addl-isbn" /></div>
            </div>
            @if(isset($customFields) && $customFields->count() > 0)
                <hr class="addl-divider" />
                <div class="addl-section-title">Custom Fields</div>
                @foreach($customFields as $field)
                    <div class="addl-custom-row"><label>{{ $field->name }}</label><input type="text" id="addl-cf-{{ $field->id }}" data-field-id="{{ $field->id }}" /></div>
                @endforeach
            @endif
        </div>
        <div class="addl-modal-foot">
            <button class="btn-addl-save" onclick="saveAddlModal()">Save</button>
            <button class="btn-addl-cancel" onclick="closeAddlModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- GENERATE SKU MODAL -->
<div class="sku-modal-overlay" id="sku-modal">
    <div class="sku-modal-box">
        <div class="sku-modal-head"><span>Generate SKU - <span id="sku-modal-title"></span></span><button class="sku-modal-x" onclick="closeSkuModal()">✕</button></div>
        <div class="sku-modal-body">
            <div class="sku-sub">Select attributes to generate SKU from</div>
            <table class="sku-tbl">
                <thead><tr><th style="width:28%;">SELECT ATTRIBUTE</th><th style="width:22%;">SHOW</th><th style="width:24%;">LETTER CASE</th><th style="width:18%;">SEPARATOR</th><th style="width:8%;"></th></tr></thead>
                <tbody id="sku-rows-tbody"></tbody>
            </table>
            <button type="button" class="sku-add-attr" onclick="skuAddRow()">+ Add Attribute</button>
            <div class="sku-preview-wrap"><div class="sku-preview-label">SKU Preview</div><div class="sku-preview-box" id="sku-preview-box">—</div></div>
        </div>
        <div class="sku-modal-foot">
            <button class="btn-sku-gen" onclick="applyGeneratedSKU()">Generate SKU</button>
            <button class="btn-sku-cancel" onclick="closeSkuModal()">Cancel</button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ══ Page-specific styles ══ */
.hidden { display: none !important; }

/* Form card */
.pc-form-card { background:#fff; border-radius:12px; border:0.5px solid var(--border); padding:28px 32px; }
.pc-form-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
.pc-form-title  { font-size:20px; font-weight:600; color:var(--text); }
.pc-close { color:#aaa; text-decoration:none; display:flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:6px; }
.pc-close:hover { background:var(--bg); color:var(--text); }

/* Form rows */
.pc-row   { display:flex; align-items:flex-start; margin-bottom:16px; gap:16px; }
.pc-label { width:140px; font-size:12px; font-weight:500; color:var(--muted); text-transform:uppercase; letter-spacing:0.3px; flex-shrink:0; padding-top:9px; text-align:right; }
.pc-label--req { color:var(--pink); }
.pc-field { flex:1; }
.pc-err   { color:var(--pink); font-size:12px; margin-top:4px; display:block; }

/* Inputs */
.pc-input { width:100%; height:36px; padding:0 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; color:var(--text); outline:none; font-family:var(--font); background:#fff; transition:border-color 0.15s; }
.pc-input:focus, .pc-input--focus { border-color:var(--pink) !important; box-shadow:0 0 0 3px rgba(232,69,122,0.08); }
.pc-input:focus { border-color:var(--pink); box-shadow:0 0 0 3px rgba(232,69,122,0.08); }
.pc-textarea { width:100%; min-height:70px; padding:8px 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; color:var(--text); outline:none; font-family:var(--font); resize:vertical; }
.pc-textarea:focus { border-color:var(--pink); }

.pc-select-wrap { position:relative; }
.pc-select { width:100%; height:36px; padding:0 28px 0 12px; border:1px solid var(--border); border-radius:8px; font-size:13px; color:var(--text); outline:none; background:#fff; appearance:none; font-family:var(--font); cursor:pointer; }
.pc-select:focus { border-color:var(--pink); }
.pc-chev { position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--muted); font-size:11px; }

.pc-inr-wrap   { display:flex; border:1px solid var(--border); border-radius:8px; overflow:hidden; }
.pc-inr-prefix { background:var(--bg); padding:0 12px; border-right:1px solid var(--border); color:var(--muted); font-weight:600; font-size:13px; display:flex; align-items:center; }

/* Radio */
.pc-radio-group { display:flex; gap:16px; padding-top:6px; }
.pc-radio { display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px; color:var(--muted); font-weight:500; user-select:none; }
.pc-radio input[type="radio"] { accent-color:var(--pink); width:15px; height:15px; cursor:pointer; }
.pc-radio--on { color:var(--pink); }

/* Item type buttons */
.pc-type-btn { border:2px solid var(--border); border-radius:8px; padding:9px 20px; cursor:pointer; display:flex; align-items:center; gap:8px; font-weight:500; font-size:13px; color:var(--muted); background:#fff; min-width:145px; justify-content:center; transition:all 0.15s; user-select:none; }
.pc-type-btn--on { border-color:var(--pink); color:var(--pink); background:var(--pink-xlt); }
.pc-dot { width:16px; height:16px; border-radius:50%; border:2px solid #ccc; background:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pc-dot--on { border-color:var(--pink); background:var(--pink); }
.pc-dot-inner { width:6px; height:6px; border-radius:50%; background:#fff; }

/* Admin check */
.pc-admin-check { display:flex; align-items:center; gap:8px; cursor:pointer; user-select:none; background:var(--pink-xlt); border:1px solid #f4b8cc; border-radius:8px; padding:8px 14px; width:fit-content; font-size:13px; color:var(--text); }
.pc-admin-check input { width:15px; height:15px; accent-color:var(--pink); cursor:pointer; }

/* Image panel */
.pc-image-panel { width:260px; border:1px solid var(--border); border-radius:10px; padding:16px; background:var(--bg); flex-shrink:0; }
.pc-image-label { font-weight:600; margin-bottom:10px; color:var(--muted); font-size:12px; text-transform:uppercase; letter-spacing:0.3px; }
.pc-drop-zone   { border:2px dashed var(--border); border-radius:10px; min-height:170px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; transition:border-color 0.2s, background 0.2s; padding:20px 12px; text-align:center; gap:8px; }
.pc-drop-zone:hover { border-color:var(--pink); background:var(--pink-xlt); }
.pc-upload-icon { width:38px; height:38px; background:linear-gradient(135deg,var(--pink) 0%,var(--pink-dk) 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; margin-bottom:4px; }
.pc-drop-title  { font-weight:600; color:var(--text); font-size:13px; }
.pc-drop-sub    { color:var(--muted); font-size:11px; line-height:1.5; }

/* Section titles */
.pc-divider       { border:none; border-top:1px solid var(--border); margin:20px 0; }
.pc-section-title { font-size:14px; font-weight:600; color:var(--text); margin-bottom:16px; }
.pc-section-toggle { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
.pc-toggle-chk    { width:16px; height:16px; accent-color:var(--pink); cursor:pointer; }
.pc-toggle-title  { font-size:14px; font-weight:600; color:var(--text); margin:0; }
.pc-link    { color:var(--pink); cursor:pointer; font-size:13px; display:inline-flex; align-items:center; gap:4px; }
.pc-link:hover { text-decoration:underline; }
.pc-link-btn { display:inline-flex; align-items:center; gap:6px; color:var(--pink); font-size:13px; font-weight:500; cursor:pointer; border:none; background:none; padding:6px 0; margin-top:4px; }
.pc-link-btn:hover { text-decoration:underline; }
.pc-gear-btn { width:36px; height:36px; border:1px solid var(--border); border-radius:8px; background:var(--bg); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; color:var(--muted); }
.pc-gear-btn:hover { background:var(--pink-xlt); border-color:var(--pink); }

/* Buttons */
.pc-btn-save   { height:38px; padding:0 22px; background:linear-gradient(135deg,var(--pink) 0%,var(--pink-dk) 100%); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:7px; box-shadow:0 3px 12px rgba(232,69,122,0.3); font-family:var(--font); }
.pc-btn-save:hover { box-shadow:0 4px 16px rgba(232,69,122,0.45); transform:translateY(-1px); }
.pc-btn-cancel { height:38px; padding:0 18px; background:#fff; color:var(--muted); border:1px solid var(--border); border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; font-family:var(--font); }
.pc-btn-cancel:hover { background:var(--bg); }

/* Single/variant toggles */
.single-only { }
.single-only.hide { display:none !important; }
.variant-only { display:none; }
.variant-only.show { display:block; }

/* Variant table */
.variants-tbl { width:100%; border-collapse:collapse; }
.variants-tbl thead tr { background:var(--bg); }
.variants-tbl thead th { padding:10px 12px; font-size:11px; font-weight:700; color:var(--pink); text-align:left; text-transform:uppercase; letter-spacing:0.4px; border-bottom:2px solid var(--border); }
.variants-tbl thead th.th-sku { color:var(--muted); }
.variants-tbl thead th .copy-all { color:var(--pink); font-size:11px; font-weight:500; cursor:pointer; display:block; text-decoration:underline; margin-top:2px; }
.variants-tbl thead th .gen-sku-btn { color:var(--pink); font-size:11px; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:3px; text-decoration:underline; margin-top:2px; }
.variants-tbl tbody tr { border-bottom:1px solid var(--border); }
.variants-tbl tbody tr:hover { background:var(--pink-xlt); }
.var-row-name   { padding:10px 12px; font-size:13px; font-weight:500; color:var(--text); }
.var-row-input  { padding:6px 8px; }
.var-row-input input { width:100%; border:1px solid var(--border); border-radius:6px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; background:#fff; }
.var-row-input input:focus { border-color:var(--pink); }
.var-row-actions { padding:6px 8px; white-space:nowrap; }
.btn-var-info { background:none; border:none; cursor:pointer; color:var(--pink); font-size:16px; padding:4px 6px; border-radius:4px; }
.btn-var-info:hover { background:var(--pink-xlt); }
.btn-var-del  { background:none; border:none; cursor:pointer; color:#e74c3c; font-size:16px; padding:4px 6px; border-radius:4px; }
.btn-var-del:hover { background:#fde8e8; }
.var-img-cell { padding:6px 8px; vertical-align:middle; }
.var-img-wrap { position:relative; display:inline-block; }
.var-img-trigger { width:52px; height:52px; border:2px dashed #ccc; border-radius:8px; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; background:var(--bg); transition:border-color 0.15s; overflow:hidden; }
.var-img-trigger:hover { border-color:var(--pink); background:var(--pink-xlt); }
.var-img-trigger.has-img { border-style:solid; border-color:var(--pink); padding:0; background:transparent; }
.vit-icon  { font-size:16px; color:#ccc; pointer-events:none; }
.vit-label { font-size:9px; color:#ccc; line-height:1.1; margin-top:2px; pointer-events:none; }
img.vit-preview { width:100%; height:100%; object-fit:cover; display:none; border-radius:6px; }
.has-img img.vit-preview { display:block; }
.has-img .vit-icon, .has-img .vit-label { display:none; }
.var-img-remove { display:none; position:absolute; top:-5px; right:-5px; width:16px; height:16px; background:#e74c3c; color:#fff; border:none; border-radius:50%; cursor:pointer; font-size:10px; align-items:center; justify-content:center; line-height:1; z-index:10; padding:0; }
.var-img-wrap:hover .var-img-remove { display:flex; }
.var-img-file { display:none; }
.reporting-tags-toggle { display:inline-flex; align-items:center; gap:5px; color:var(--muted); font-size:12px; cursor:pointer; user-select:none; }
.reporting-tags-toggle:hover { color:var(--pink); }
.variation-row { display:flex; align-items:flex-start; gap:12px; margin-bottom:12px; padding:14px 16px; background:var(--bg); border:1px solid var(--border); border-radius:8px; }
.var-options-wrap { flex:1; }
.tag-input-box { display:flex; flex-wrap:wrap; gap:6px; border:1px solid var(--border); border-radius:8px; padding:6px 10px; background:#fff; min-height:40px; cursor:text; }
.tag-input-box:focus-within { border-color:var(--pink); }
.tag-chip { display:inline-flex; align-items:center; gap:4px; background:var(--pink-xlt); color:var(--pink); border-radius:4px; padding:3px 8px; font-size:12px; font-weight:500; }
.tag-chip button { background:none; border:none; cursor:pointer; color:var(--pink); font-size:14px; line-height:1; padding:0 1px; }
.tag-chip button:hover { color:#c73265; }
.tag-real-input { border:none; outline:none; font-size:13px; font-family:var(--font); min-width:80px; flex:1; padding:2px 4px; }
.var-del-btn { background:none; border:none; cursor:pointer; color:#ccc; font-size:18px; padding:6px; flex-shrink:0; border-radius:4px; }
.var-del-btn:hover { color:#e74c3c; background:#fde8e8; }
.var-attr-input { width:100%; border:1px solid var(--border); border-radius:8px; padding:8px 12px; font-size:13px; font-family:var(--font); outline:none; }
.var-attr-input:focus { border-color:var(--pink); }

/* Category dropdown */
.cat-dd-wrap { position:relative; }
.cat-dd-box  { display:flex; align-items:center; justify-content:space-between; border:1px solid var(--border); border-radius:8px; background:#fff; padding:8px 12px; cursor:pointer; user-select:none; min-height:36px; }
.cat-dd-box:hover, .cat-dd-box.open { border-color:var(--pink); }
.cat-dd-label { font-size:13px; color:var(--muted); }
.cat-dd-label.has-val { color:var(--text); }
.cat-dd-chev  { font-size:11px; color:var(--muted); }
.cat-dd-panel { display:none; position:absolute; top:calc(100% + 3px); left:0; width:100%; background:#fff; border:1px solid var(--border); border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.1); z-index:600; }
.cat-dd-panel.open { display:block; }
.cat-dd-sr    { display:flex; align-items:center; gap:6px; padding:7px 10px; border-bottom:1px solid var(--border); }
.cat-dd-sr input { flex:1; border:none; outline:none; font-size:13px; font-family:var(--font); background:transparent; }
.cat-dd-list  { max-height:200px; overflow-y:auto; }
.cat-dd-item  { display:flex; align-items:center; gap:8px; padding:9px 14px; font-size:13px; color:var(--text); cursor:pointer; }
.cat-dd-item:hover { background:var(--pink-xlt); color:var(--pink); }
.cat-dd-item.sel { background:var(--pink); color:#fff; }
.cat-parent-hint { font-size:11px; color:var(--muted); margin-left:2px; }
.cat-dd-item.sel .cat-parent-hint { color:#fde8ef; }
.cat-dd-empty  { padding:12px 14px; font-size:13px; color:var(--muted); text-align:center; }
.cat-dd-manage { display:flex; align-items:center; gap:7px; padding:10px 14px; font-size:13px; color:var(--pink); font-weight:500; cursor:pointer; border-top:1px solid var(--border); }
.cat-dd-manage:hover { background:var(--pink-xlt); }

/* Category modal */
.cat-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.35); z-index:2000; align-items:flex-start; justify-content:center; padding-top:60px; }
.cat-modal-overlay.open { display:flex; }
.cat-modal-box  { background:#fff; border-radius:10px; width:700px; max-width:96vw; max-height:80vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden; }
.cat-modal-head { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--border); flex-shrink:0; }
.cat-modal-head span { font-size:16px; font-weight:600; color:var(--text); }
.cat-modal-x    { background:none; border:none; font-size:22px; color:var(--pink); cursor:pointer; font-weight:700; line-height:1; }
.cat-modal-body { flex:1; overflow-y:auto; padding:24px; }
.cat-new-form   { display:none; margin-bottom:20px; padding-bottom:20px; border-bottom:2px solid var(--border); }
.cat-new-form.show { display:block; }
.cat-form-row   { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
.cat-form-row label { width:140px; font-size:13px; font-weight:500; text-align:right; flex-shrink:0; color:var(--muted); }
.cat-form-row label.req { color:var(--pink); }
.cat-form-row input[type="text"] { flex:1; border:2px solid var(--pink); border-radius:8px; padding:7px 12px; font-size:14px; font-family:var(--font); outline:none; }
.cat-psw { flex:1; position:relative; }
.cat-psw select { width:100%; border:1px solid var(--border); border-radius:8px; padding:7px 12px; font-size:14px; background:#fff; appearance:none; font-family:var(--font); outline:none; color:var(--text); }
.cat-psw .arr   { position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--muted); font-size:11px; }
.cat-form-btns  { display:flex; gap:8px; margin-left:152px; }
.btn-cs { background:var(--pink); color:#fff; border:none; border-radius:8px; padding:8px 22px; font-weight:600; font-size:13px; cursor:pointer; }
.btn-cs:hover   { background:var(--pink-dk); }
.btn-cs:disabled { background:#f4b8cc; cursor:not-allowed; }
.btn-cc { background:#fff; color:var(--muted); border:1px solid var(--border); border-radius:8px; padding:8px 16px; font-size:13px; cursor:pointer; }
.cat-fe { color:var(--pink); font-size:12px; margin-left:152px; margin-top:-8px; margin-bottom:8px; display:none; }
.cat-list-head  { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
.cat-list-title { font-size:12px; font-weight:700; color:var(--muted); letter-spacing:0.6px; text-transform:uppercase; }
.btn-anc { display:flex; align-items:center; gap:5px; background:none; border:none; color:var(--pink); font-size:13px; font-weight:600; cursor:pointer; padding:4px 8px; border-radius:4px; }
.btn-anc:hover  { background:var(--pink-xlt); }
.anc-circle     { width:18px; height:18px; border-radius:50%; background:var(--pink); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:16px; font-weight:700; line-height:1; }
.cat-li  { display:flex; align-items:center; gap:10px; padding:10px 4px; border-bottom:1px solid var(--border); }
.cat-li:last-child { border-bottom:none; }
.cat-li .li-icon { font-size:16px; color:var(--pink); }
.cat-li .li-name { flex:1; font-size:13px; font-weight:500; color:var(--text); }
.cat-li .li-parent { font-size:11px; color:var(--muted); margin-left:4px; }
.cat-li .li-acts button { background:none; border:none; cursor:pointer; padding:4px 7px; border-radius:4px; font-size:13px; }
.li-edit { color:var(--pink); } .li-edit:hover { background:var(--pink-xlt); }
.li-del  { color:#e74c3c; }    .li-del:hover  { background:#fde8e8; }
.cat-li-empty { text-align:center; padding:24px; color:var(--muted); font-size:13px; }
.cat-ms       { background:var(--green-lt); color:var(--green-dk); font-size:12px; padding:8px 12px; border-radius:4px; margin-bottom:12px; display:none; }
.cat-modal-foot { padding:12px 24px; border-top:1px solid var(--border); flex-shrink:0; }
.btn-cf { background:#fff; color:var(--muted); border:1px solid var(--border); border-radius:8px; padding:8px 20px; font-size:13px; cursor:pointer; }

/* Brand modal */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
.modal-box    { background:#fff; border-radius:12px; width:480px; max-width:95vw; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden; }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--border); font-weight:700; font-size:15px; }
.modal-close  { cursor:pointer; font-size:18px; color:var(--muted); background:none; border:none; }
.modal-body   { padding:20px; }
.add-brand-row { display:flex; gap:8px; margin-bottom:8px; }
.add-brand-row input { flex:1; border:1px solid var(--border); border-radius:8px; padding:8px 12px; font-size:14px; outline:none; font-family:var(--font); }
.add-brand-row input:focus { border-color:var(--pink); }
.btn-add-brand { background:var(--pink); color:#fff; border:none; border-radius:8px; padding:8px 18px; font-weight:600; cursor:pointer; font-size:13px; white-space:nowrap; }
.btn-add-brand:hover { background:var(--pink-dk); }
.btn-add-brand:disabled { background:#f4b8cc; cursor:not-allowed; }
.brand-error  { color:var(--pink); font-size:12px; margin-bottom:10px; display:none; }
.brand-success { color:var(--green); font-size:12px; margin-bottom:10px; display:none; padding:8px; background:var(--green-lt); border-radius:4px; }
.brand-list   { max-height:260px; overflow-y:auto; margin-top:12px; }
.brand-empty  { color:var(--muted); font-size:13px; text-align:center; padding:20px 0; }
.brand-list-item { display:flex; align-items:center; justify-content:space-between; padding:8px 10px; border-radius:6px; font-size:13px; }
.brand-list-item:hover { background:var(--bg); }
.brand-actions { display:flex; gap:4px; }
.btn-del-brand { background:none; border:none; cursor:pointer; padding:4px 8px; border-radius:4px; font-size:14px; color:#e74c3c; }
.btn-del-brand:hover { background:#fde8e8; }
.btn-edit-brand { background:none; border:none; cursor:pointer; padding:4px 8px; border-radius:4px; font-size:14px; color:var(--pink); }
.btn-edit-brand:hover { background:var(--pink-xlt); }
.brand-list-item.editing { background:#fff3cd; border-left:3px solid #ffc107; }

/* Addl modal */
.addl-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:3000; align-items:center; justify-content:center; }
.addl-modal-overlay.open { display:flex; }
.addl-modal-box  { background:#fff; border-radius:10px; width:680px; max-width:96vw; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden; }
.addl-modal-head { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--border); flex-shrink:0; }
.addl-modal-head span { font-size:15px; font-weight:700; color:var(--text); }
.addl-modal-x    { background:none; border:none; font-size:22px; color:var(--pink); cursor:pointer; font-weight:700; }
.addl-modal-body { flex:1; overflow-y:auto; padding:24px; }
.addl-section-title { font-size:14px; font-weight:700; color:var(--text); margin-bottom:16px; }
.addl-grid   { display:grid; grid-template-columns:1fr 1fr; gap:14px 32px; margin-bottom:24px; }
.addl-field-row { display:flex; align-items:center; gap:12px; }
.addl-field-row label { width:60px; font-size:13px; color:var(--muted); font-weight:500; flex-shrink:0; }
.addl-field-row input { flex:1; border:1px solid var(--border); border-radius:8px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; }
.addl-field-row input:focus { border-color:var(--pink); }
.addl-divider  { border:none; border-top:1px solid var(--border); margin:20px 0; }
.addl-custom-row { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
.addl-custom-row label { width:120px; font-size:13px; color:var(--muted); font-weight:500; flex-shrink:0; }
.addl-custom-row input { flex:1; border:1px solid var(--border); border-radius:8px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; }
.addl-modal-foot { padding:14px 24px; border-top:1px solid var(--border); display:flex; gap:10px; flex-shrink:0; }
.btn-addl-save   { background:var(--pink); color:#fff; border:none; border-radius:8px; padding:9px 24px; font-weight:700; font-size:13px; cursor:pointer; }
.btn-addl-save:hover { background:var(--pink-dk); }
.btn-addl-cancel { background:#fff; color:var(--muted); border:1px solid var(--border); border-radius:8px; padding:9px 18px; font-size:13px; cursor:pointer; }

/* Unit dropdown */
.unit-dropdown-wrap { position:relative; flex:1; }
.unit-input-box { display:flex; align-items:center; border:1px solid var(--border); border-radius:8px; background:#fff; cursor:pointer; }
.unit-input-box.focused { border-color:var(--pink); }
.unit-input-box input { flex:1; border:none; outline:none; padding:8px 12px; font-size:13px; font-family:var(--font); background:transparent; cursor:text; color:var(--text); min-width:0; }
.unit-chevron { padding:8px 10px; color:var(--muted); font-size:11px; pointer-events:none; user-select:none; flex-shrink:0; }
.unit-dropdown-menu { display:none; position:absolute; top:calc(100% + 3px); left:0; width:100%; background:#fff; border:1px solid var(--border); border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.1); z-index:500; max-height:260px; overflow-y:auto; }
.unit-dropdown-menu.open { display:block; }
.unit-option { display:flex; align-items:center; justify-content:space-between; padding:9px 14px; font-size:13px; color:var(--text); cursor:pointer; }
.unit-option:hover { background:var(--pink-xlt); }
.unit-option.selected { background:var(--pink-xlt); color:var(--pink); font-weight:600; }
.unit-option .unit-del { display:none; background:none; border:none; color:#e74c3c; cursor:pointer; font-size:12px; padding:2px 7px; border-radius:4px; line-height:1; }
.unit-option:hover .unit-del { display:inline-flex; align-items:center; }
.unit-no-result { padding:10px 14px; color:var(--muted); font-size:13px; }
.unit-add-new   { padding:9px 14px; font-size:13px; color:var(--pink); cursor:pointer; border-top:1px solid var(--border); display:flex; align-items:center; gap:6px; font-weight:500; }
.unit-add-new:hover { background:var(--pink-xlt); }

/* SKU modal */
.sku-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:5000; align-items:flex-start; justify-content:center; padding-top:60px; }
.sku-modal-overlay.open { display:flex; }
.sku-modal-box  { background:#fff; border-radius:8px; width:900px; max-width:96vw; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.2); overflow:hidden; }
.sku-modal-head { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--border); flex-shrink:0; }
.sku-modal-head span { font-size:16px; font-weight:700; color:var(--text); }
.sku-modal-x    { background:none; border:none; font-size:22px; color:var(--muted); cursor:pointer; font-weight:700; }
.sku-modal-body { flex:1; overflow-y:auto; padding:24px; }
.sku-sub   { font-size:13px; color:var(--muted); margin-bottom:20px; }
.sku-tbl   { width:100%; border-collapse:collapse; }
.sku-tbl thead tr  { background:var(--bg); }
.sku-tbl thead th  { padding:10px 12px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid var(--border); text-align:left; }
.sku-tbl tbody tr  { border-bottom:1px solid var(--border); }
.sku-tbl td        { padding:8px 12px; vertical-align:middle; }
.sku-sel  { width:100%; border:1px solid var(--border); border-radius:6px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; background:#fff; }
.sku-sel:focus     { border-color:var(--pink); }
.sku-show-wrap     { display:flex; align-items:center; gap:6px; }
.sku-show-wrap select { width:70px; border:1px solid var(--border); border-radius:6px; padding:7px 8px; font-size:13px; font-family:var(--font); outline:none; }
.sku-show-wrap input[type=number] { width:56px; border:1px solid var(--border); border-radius:6px; padding:7px 8px; font-size:13px; text-align:center; outline:none; }
.sku-case-wrap     { display:flex; align-items:center; gap:4px; }
.sku-case-sel      { flex:1; border:1px solid var(--pink); border-radius:6px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; color:var(--pink); }
.sku-case-x, .sku-sep-x { background:none; border:none; color:#e74c3c; font-size:16px; cursor:pointer; padding:0 4px; }
.sku-sep-wrap      { display:flex; align-items:center; gap:4px; }
.sku-sep-sel       { flex:1; border:1px solid var(--pink); border-radius:6px; padding:7px 10px; font-size:13px; font-family:var(--font); outline:none; color:var(--pink); }
.sku-row-del       { background:none; border:none; cursor:pointer; color:#e74c3c; font-size:18px; padding:4px 6px; border-radius:50%; }
.sku-row-del:hover { background:#fde8e8; }
.sku-add-attr      { display:inline-flex; align-items:center; gap:5px; color:var(--pink); font-size:13px; font-weight:500; cursor:pointer; border:none; background:none; padding:6px 0; margin-top:8px; }
.sku-add-attr:hover { text-decoration:underline; }
.sku-preview-wrap  { margin-top:20px; }
.sku-preview-label { font-size:12px; font-weight:700; color:var(--muted); margin-bottom:8px; }
.sku-preview-box   { background:#fffbea; border:2px dashed #f0c040; border-radius:6px; padding:20px; text-align:center; font-size:18px; font-weight:700; color:var(--text); letter-spacing:2px; min-height:60px; display:flex; align-items:center; justify-content:center; }
.sku-modal-foot    { padding:14px 24px; border-top:1px solid var(--border); display:flex; gap:10px; flex-shrink:0; }
.btn-sku-gen       { background:var(--pink); color:#fff; border:none; border-radius:8px; padding:9px 24px; font-weight:700; font-size:13px; cursor:pointer; }
.btn-sku-gen:hover { background:var(--pink-dk); }
.btn-sku-cancel    { background:#fff; color:var(--muted); border:1px solid var(--border); border-radius:8px; padding:9px 18px; font-size:13px; cursor:pointer; }
</style>
@endpush

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ══ CATEGORY ══
let _cats=[], _catOpen=false, _selCatId=null, _selCatNm='', _editCatId=null;
(async function initCats(){
  await _fetchCats();
  const oid='{{ old("category_id") }}', onm='{{ old("category_name") }}';
  if(oid&&onm){_selCatId=parseInt(oid);_selCatNm=onm;const l=document.getElementById('cat-dd-lbl');l.textContent=onm;l.classList.add('has-val');}
})();
async function _fetchCats(){try{const r=await fetch('/categories/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await r.json();if(j.success){_cats=j.data;_renderDdList('');}}catch{}}
function toggleCatDd(){_catOpen?closeCatDd():openCatDd();}
function openCatDd(){_catOpen=true;document.getElementById('cat-dd-panel').classList.add('open');document.getElementById('cat-dd-box').classList.add('open');document.getElementById('cat-dd-chev').textContent='▲';document.getElementById('cat-dd-q').value='';_renderDdList('');setTimeout(()=>document.getElementById('cat-dd-q').focus(),20);}
function closeCatDd(){_catOpen=false;document.getElementById('cat-dd-panel').classList.remove('open');document.getElementById('cat-dd-box').classList.remove('open');document.getElementById('cat-dd-chev').textContent='▼';}
function filterCatDd(q){_renderDdList(q);}
function _renderDdList(q){const el=document.getElementById('cat-dd-list');q=(q||'').trim().toLowerCase();const list=q?_cats.filter(c=>c.name.toLowerCase().includes(q)||(c.parent_name||'').toLowerCase().includes(q)):_cats;if(!list.length){el.innerHTML='<div class="cat-dd-empty">No categories found</div>';return;}el.innerHTML=list.map(c=>`<div class="cat-dd-item${_selCatId===c.id?' sel':''}" onclick="selCat(${c.id},'${c.name.replace(/'/g,"\\'")}')"><span>📁</span><span>${c.name}</span>${c.parent_name?`<span class="cat-parent-hint">(${c.parent_name})</span>`:''}</div>`).join('');}
function selCat(id,name){_selCatId=id;_selCatNm=name;document.getElementById('cat-hid-id').value=id;document.getElementById('cat-hid-name').value=name;const l=document.getElementById('cat-dd-lbl');l.textContent=name;l.classList.add('has-val');closeCatDd();}
document.addEventListener('click',function(e){if(!document.querySelector('.cat-dd-wrap')?.contains(e.target))closeCatDd();});
function openCatModal(){document.getElementById('cat-modal').classList.add('open');hideCatForm();_renderModalList();}
function closeCatModal(){document.getElementById('cat-modal').classList.remove('open');hideCatForm();}
document.getElementById('cat-modal').addEventListener('click',e=>{if(e.target.id==='cat-modal')closeCatModal();});
function showCatForm(eId,eNm,ePar){_editCatId=eId||null;document.getElementById('cat-new-form').classList.add('show');document.getElementById('cat-name-inp').value=eNm||'';document.getElementById('cat-fe').style.display='none';document.getElementById('btn-cs').textContent=eId?'Update':'Save';document.getElementById('btn-anc').style.display='none';const sel=document.getElementById('cat-par-sel');sel.innerHTML='<option value="">— None —</option>';_cats.forEach(c=>{if(c.id!==eId){const o=document.createElement('option');o.value=c.id;o.textContent=c.full_name||c.name;if(c.id===ePar)o.selected=true;sel.appendChild(o);}});setTimeout(()=>document.getElementById('cat-name-inp').focus(),30);}
function hideCatForm(){document.getElementById('cat-new-form').classList.remove('show');document.getElementById('btn-anc').style.display='flex';document.getElementById('cat-name-inp').value='';document.getElementById('cat-fe').style.display='none';_editCatId=null;}
async function saveCat(){const name=document.getElementById('cat-name-inp').value.trim();const par=document.getElementById('cat-par-sel').value||null;const fe=document.getElementById('cat-fe');const btn=document.getElementById('btn-cs');fe.style.display='none';if(!name){fe.textContent='Category name cannot be empty.';fe.style.display='block';return;}btn.disabled=true;btn.textContent=_editCatId?'Updating...':'Saving...';try{const res=await fetch(_editCatId?`/categories/${_editCatId}`:'/categories',{method:_editCatId?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name,parent_id:par})});const j=await res.json();if(!res.ok){fe.textContent=j.message||'Failed.';fe.style.display='block';}else{hideCatForm();_showCatMsg(_editCatId?'Category updated!':'Category added!');await _fetchCats();_renderModalList();}}catch{fe.textContent='Network error.';fe.style.display='block';}finally{btn.disabled=false;btn.textContent=_editCatId?'Update':'Save';}}
function _renderModalList(){const el=document.getElementById('cat-li-wrap');if(!_cats.length){el.innerHTML='<div class="cat-li-empty">No categories added yet</div>';return;}el.innerHTML=_cats.map(c=>`<div class="cat-li" id="cli-${c.id}"><span class="li-icon">📁</span><span class="li-name">${c.name}${c.parent_name?`<span class="li-parent">(${c.parent_name})</span>`:''}</span><div class="li-acts"><button class="li-edit" onclick="showCatForm(${c.id},'${c.name.replace(/'/g,"\\'")}',${c.parent_id||'null'})">✏️</button><button class="li-del" onclick="delCat(${c.id},'${c.name.replace(/'/g,"\\'")}')">🗑️</button></div></div>`).join('');}
async function delCat(id,name){if(!confirm(`Delete category "${name}"?`))return;try{const res=await fetch(`/categories/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();if(res.ok&&j.success){if(_selCatId===id){_selCatId=null;_selCatNm='';document.getElementById('cat-hid-id').value='';document.getElementById('cat-hid-name').value='';const l=document.getElementById('cat-dd-lbl');l.textContent='Select a category';l.classList.remove('has-val');}_showCatMsg('Category deleted!');await _fetchCats();_renderModalList();}else{alert(j.message||'Failed to delete');}}catch{alert('Network error');}}
function _showCatMsg(m){const el=document.getElementById('cat-ms');el.textContent=m;el.style.display='block';setTimeout(()=>el.style.display='none',3000);}

// ══ HELPERS ══
function toggleSection(id,bodyId){document.getElementById(bodyId).classList.toggle('hidden',!document.getElementById(id).checked);}
function updateRadio(input,name){document.querySelectorAll(`input[name="${name}"]`).forEach(r=>r.closest('.pc-radio').classList.remove('pc-radio--on'));input.closest('.pc-radio').classList.add('pc-radio--on');}

// ══ ITEM TYPE ══
function selectItemType(type,el){
  document.querySelectorAll('.pc-type-btn').forEach(b=>b.classList.remove('pc-type-btn--on'));
  document.querySelectorAll('.pc-dot').forEach(d=>{d.classList.remove('pc-dot--on');d.innerHTML='';});
  el.classList.add('pc-type-btn--on');
  const dot=document.getElementById('dot-'+type);
  dot.classList.add('pc-dot--on');
  dot.innerHTML='<div class="pc-dot-inner"></div>';
  document.getElementById('item_variant_type').value=type==='single'?'single':'contains_variants';
  const isVariant=type==='variants';
  document.querySelectorAll('.single-only').forEach(e=>isVariant?e.classList.add('hide'):e.classList.remove('hide'));
  document.querySelectorAll('.variant-only').forEach(e=>isVariant?e.classList.add('show'):e.classList.remove('show'));
  const varSec=document.getElementById('variations-section');
  if(isVariant){varSec.style.display='block';if(!document.querySelectorAll('.variation-row').length)addVariationRow();}
  else{varSec.style.display='none';}
}

// ══ IMAGE ══
function onDragOver(e){e.preventDefault();document.getElementById('drop-zone').style.borderColor='var(--pink)';}
function onDragLeave(){document.getElementById('drop-zone').style.borderColor='';}
function onDrop(e){e.preventDefault();document.getElementById('drop-zone').style.borderColor='';if(e.dataTransfer.files[0])applyFile(e.dataTransfer.files[0]);}
function handleFileSelect(input){if(input.files[0])applyFile(input.files[0]);}
const MAX_SIZE_BYTES=5*1024*1024,MAX_WIDTH=1920,MAX_HEIGHT=1920,JPEG_QUALITY=0.92,TARGET_MAX_KB=800;
function applyFile(file){
  if(!file.type.match(/image\/(jpeg|png|jpg|gif)/)){alert('Please upload a JPG, PNG or GIF image.');return;}
  if(file.size>MAX_SIZE_BYTES){alert('Image must be under 5 MB.');return;}
  document.getElementById('front-file-name').textContent='Compressing…';
  const reader=new FileReader();
  reader.onload=ev=>{
    const img=new Image();
    img.onload=()=>{
      let{width,height}=img;
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
      document.getElementById('front-file-name').textContent=origName+' — '+sizeKB+' KB';
    };
    img.src=ev.target.result;
  };
  reader.readAsDataURL(file);
}
function clearFrontImage(){document.getElementById('front_image').value='';document.getElementById('front-preview-img').src='';document.getElementById('front-preview-wrap').style.display='none';document.getElementById('drop-zone').style.display='flex';document.getElementById('front-file-name').textContent='';}

// ══ UNIT DROPDOWN ══
const DEFAULT_UNITS=[{code:'BOX',name:'box'},{code:'CMS',name:'cm'},{code:'DOZ',name:'dz'},{code:'FTS',name:'ft'},{code:'GMS',name:'g'},{code:'INC',name:'in'},{code:'KGS',name:'kg'},{code:'KME',name:'km'},{code:'LBS',name:'lb'},{code:'MGS',name:'mg'},{code:'PCS',name:'pcs'},{code:'LTR',name:'l'},{code:'MLT',name:'ml'},{code:'MTR',name:'m'},{code:'NOS',name:'nos'},{code:'OZS',name:'oz'},{code:'TNE',name:'ton'},{code:'YDS',name:'yd'}];
let unitOpen=false,selectedUnit=null;
function getCustomUnits(){try{return JSON.parse(localStorage.getItem('custom_units')||'[]');}catch{return[];}}
function saveCustomUnits(units){localStorage.setItem('custom_units',JSON.stringify(units));}
function allUnits(){return[...DEFAULT_UNITS,...getCustomUnits()];}
function renderUnitDropdown(filter){filter=(filter||'').trim().toLowerCase();const menu=document.getElementById('unit-dropdown');const customCodes=getCustomUnits().map(u=>u.code);const matched=allUnits().filter(u=>!filter||u.code.toLowerCase().includes(filter)||u.name.toLowerCase().includes(filter));let html='';if(!matched.length){html='<div class="unit-no-result">No match — type to add new unit</div>';}else{html=matched.map(u=>{const isSel=selectedUnit&&selectedUnit.code===u.code;const isCustom=customCodes.includes(u.code);const delBtn=isCustom?`<button class="unit-del" type="button" onclick="deleteCustomUnit(event,'${u.code}')">✕</button>`:'';return`<div class="unit-option${isSel?' selected':''}" onclick="selectUnit('${u.code}','${u.name}')"><span>${u.code} - ${u.name}</span>${delBtn}</div>`;}).join('');}const exists=allUnits().some(u=>u.code.toLowerCase()===filter||u.name.toLowerCase()===filter);if(filter&&!exists){html+=`<div class="unit-add-new" onclick="addCustomUnit('${filter.replace(/'/g,"\\'")}')">➕ Add "${filter}"</div>`;}menu.innerHTML=html;}
function openUnitDropdown(){if(unitOpen)return;unitOpen=true;document.getElementById('unit-dropdown').classList.add('open');document.getElementById('unit-chevron').textContent='▲';document.getElementById('unit-input-box').classList.add('focused');renderUnitDropdown(document.getElementById('unit-search').value);}
function closeUnitDropdown(){if(!unitOpen)return;unitOpen=false;document.getElementById('unit-dropdown').classList.remove('open');document.getElementById('unit-chevron').textContent='▼';document.getElementById('unit-input-box').classList.remove('focused');const s=document.getElementById('unit-search');s.value=selectedUnit?`${selectedUnit.code} - ${selectedUnit.name}`:'';} 
function filterUnits(val){if(!unitOpen)openUnitDropdown();renderUnitDropdown(val);}
function selectUnit(code,name){selectedUnit={code,name};document.getElementById('unit-hidden').value=name;document.getElementById('unit-search').value=`${code} - ${name}`;closeUnitDropdown();}
function addCustomUnit(input){const code=input.toUpperCase().replace(/\s+/g,'').slice(0,8);const name=input.toLowerCase().trim();const custom=getCustomUnits();if(!custom.find(u=>u.code===code)){custom.push({code,name});saveCustomUnits(custom);}selectUnit(code,name);}
function deleteCustomUnit(e,code){e.stopPropagation();if(!confirm(`"${code}" delete பண்ணவா?`))return;const updated=getCustomUnits().filter(u=>u.code!==code);saveCustomUnits(updated);if(selectedUnit&&selectedUnit.code===code){selectedUnit=null;document.getElementById('unit-hidden').value='';document.getElementById('unit-search').value='';}renderUnitDropdown(document.getElementById('unit-search').value);}
document.addEventListener('click',function(e){const wrap=document.querySelector('.unit-dropdown-wrap');if(wrap&&!wrap.contains(e.target))closeUnitDropdown();});
document.getElementById('unit-input-box').addEventListener('click',function(e){if(e.target.id==='unit-search')return;unitOpen?closeUnitDropdown():openUnitDropdown();});
(function initUnit(){const oldVal='{{ old("unit") }}';if(oldVal){const found=allUnits().find(u=>u.name===oldVal||u.code===oldVal.toUpperCase());if(found){selectedUnit=found;document.getElementById('unit-hidden').value=found.name;document.getElementById('unit-search').value=`${found.code} - ${found.name}`;}}})(  );

// ══ IDENTIFIERS ══
function toggleIdentifiers(btn){const fields=document.getElementById('identifier-fields');const isOpen=fields.style.display==='block';if(isOpen){fields.style.display='none';btn.innerHTML='➕ Add Identifier';}else{fields.style.display='block';btn.innerHTML='➖ Hide Identifier';fields.querySelector('input').focus();}}

// ══ VARIATIONS ══
let varRowCount=0;
function addVariationRow(attrVal='',optionTags=[]){
  varRowCount++;
  const rowId='var-row-'+varRowCount;
  const wrap=document.getElementById('variation-rows-wrap');
  const div=document.createElement('div');
  div.className='variation-row';div.id=rowId;
  const tagsHtml=optionTags.map(t=>`<span class="tag-chip">${escHtml(t)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button></span>`).join('');
  div.innerHTML=`<div style="width:200px;flex-shrink:0;"><input type="text" class="var-attr-input" placeholder="eg: Color, Size..." value="${escHtml(attrVal)}" oninput="regenerateVariants()" /></div><div class="var-options-wrap"><div class="tag-input-box" id="tags-${rowId}" onclick="focusTagInput('${rowId}')">${tagsHtml}<input class="tag-real-input" id="input-${rowId}" type="text" placeholder="Type and press Enter or comma" onkeydown="handleTagKey(event,'${rowId}')" oninput="handleTagInput(event,'${rowId}')" /></div></div><button type="button" class="var-del-btn" onclick="removeVariationRow('${rowId}')">🗑</button>`;
  wrap.appendChild(div);
}
function focusTagInput(rowId){document.getElementById('input-'+rowId)?.focus();}
function handleTagKey(e,rowId){if(e.key==='Enter'||e.key===','){e.preventDefault();const input=e.target;const val=input.value.trim().replace(/,$/,'');if(val)addTag(rowId,val);input.value='';}else if(e.key==='Backspace'&&e.target.value===''){const box=document.getElementById('tags-'+rowId);const chips=box.querySelectorAll('.tag-chip');if(chips.length)chips[chips.length-1].remove();regenerateVariants();}}
function handleTagInput(e,rowId){const val=e.target.value;if(val.endsWith(',')){const clean=val.slice(0,-1).trim();if(clean)addTag(rowId,clean);e.target.value='';}}
function addTag(rowId,val){const box=document.getElementById('tags-'+rowId);const input=document.getElementById('input-'+rowId);const existing=[...box.querySelectorAll('.tag-chip')].map(c=>c.textContent.replace('×','').trim());if(existing.includes(val))return;const chip=document.createElement('span');chip.className='tag-chip';chip.innerHTML=`${escHtml(val)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button>`;box.insertBefore(chip,input);regenerateVariants();}
function removeTag(btn,rowId){btn.closest('.tag-chip').remove();regenerateVariants();}
function removeVariationRow(rowId){document.getElementById(rowId)?.remove();regenerateVariants();}
function getVariationData(){const rows=document.querySelectorAll('.variation-row');const data=[];rows.forEach(row=>{const attr=row.querySelector('.var-attr-input')?.value.trim()||'';const chips=[...row.querySelectorAll('.tag-chip')].map(c=>c.textContent.replace('×','').trim());if(attr&&chips.length)data.push({attribute:attr,options:chips});});return data;}

let variantAdditionalData={};
const variantImageData={};
function handleVariantImageChange(input,variantName){const file=input.files[0];if(!file)return;if(!file.type.match(/image\/(jpeg|png|jpg|gif)/)){alert('JPG/PNG/GIF மட்டும்.');return;}if(file.size>5*1024*1024){alert('5MB limit.');return;}const reader=new FileReader();reader.onload=ev=>{const img=new Image();img.onload=()=>{let{width,height}=img;const MAX=1200;if(width>MAX||height>MAX){const r=Math.min(MAX/width,MAX/height);width=Math.round(width*r);height=Math.round(height*r);}const canvas=document.createElement('canvas');canvas.width=width;canvas.height=height;const ctx=canvas.getContext('2d');ctx.fillStyle='#fff';ctx.fillRect(0,0,width,height);ctx.drawImage(img,0,0,width,height);let quality=0.88,dataURL=canvas.toDataURL('image/jpeg',quality),sizeKB=Math.round((dataURL.length*3)/4/1024);while(sizeKB>600&&quality>0.5){quality-=0.05;dataURL=canvas.toDataURL('image/jpeg',quality);sizeKB=Math.round((dataURL.length*3)/4/1024);}const safeFilename=variantName.replace(/[^a-zA-Z0-9_\-]/g,'_').toLowerCase()+'_'+Date.now()+'.jpg';variantImageData[variantName]={base64:dataURL,filename:safeFilename};const triggerEl=document.querySelector(`[data-variant-img="${CSS.escape(variantName)}"]`);if(triggerEl){triggerEl.classList.add('has-img');const previewImg=triggerEl.querySelector('img.vit-preview');if(previewImg)previewImg.src=dataURL;}};img.src=ev.target.result;};reader.readAsDataURL(file);}
function removeVariantImage(variantName){delete variantImageData[variantName];const triggerEl=document.querySelector(`[data-variant-img="${CSS.escape(variantName)}"]`);if(triggerEl){triggerEl.classList.remove('has-img');const p=triggerEl.querySelector('img.vit-preview');if(p)p.src='';}const safeId='vif_'+variantName.replace(/[^a-zA-Z0-9]/g,'_');const fi=document.getElementById(safeId);if(fi)fi.value='';}
function cartesian(arrays){if(!arrays.length)return[[]];return arrays.reduce((a,b)=>a.flatMap(x=>b.map(y=>[...x,y])),[[]]);}
function regenerateVariants(){const data=getVariationData();const tbody=document.getElementById('variants-tbody');const tableWrap=document.getElementById('variants-table-wrap');if(!data.length){tbody.innerHTML='';tableWrap.style.display='none';return;}const combos=cartesian(data.map(d=>d.options.map(o=>({attr:d.attribute,val:o}))));if(!combos.length||!combos[0].length){tbody.innerHTML='';tableWrap.style.display='none';return;}tableWrap.style.display='block';const existingRows={};tbody.querySelectorAll('tr.variant-data-row').forEach(tr=>{const name=tr.dataset.variantName;existingRows[name]={sku:tr.querySelector('.var-sku')?.value||'',cost:tr.querySelector('.var-cost')?.value||'',sell:tr.querySelector('.var-sell')?.value||''};});tbody.innerHTML='';combos.forEach((combo,idx)=>{const name=combo.map(c=>c.val).join(' - ');const prev=existingRows[name]||{};const safeId='vif_'+name.replace(/[^a-zA-Z0-9]/g,'_');const hasImg=!!variantImageData[name];const tr=document.createElement('tr');tr.className='variant-data-row';tr.dataset.variantName=name;tr.dataset.variantIdx=idx;tr.innerHTML=`<td class="var-row-name">${escHtml(name)}</td><td class="var-row-input"><input type="text" class="var-sku" value="${escHtml(prev.sku||'')}" /></td><td class="var-row-input"><input type="number" class="var-cost" value="${escHtml(prev.cost||'')}" step="0.01" min="0" /></td><td class="var-row-input"><input type="number" class="var-sell" value="${escHtml(prev.sell||'0')}" step="0.01" min="0" /></td><td class="var-row-actions"><button type="button" class="btn-var-info" onclick="openAddlModal('${escAttr(name)}')">✏️</button><button type="button" class="btn-var-del" onclick="deleteVariantRow(this,'${escAttr(name)}')">⊗</button></td><td class="var-img-cell"><input type="file" class="var-img-file" id="${safeId}" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="handleVariantImageChange(this,'${escAttr(name)}')" /><div class="var-img-wrap"><div class="var-img-trigger${hasImg?' has-img':''}" data-variant-img="${escHtml(name)}" onclick="document.getElementById('${safeId}').click()"><img class="vit-preview" src="${hasImg?escHtml(variantImageData[name].base64):''}" alt="" /><span class="vit-icon">🖼</span><span class="vit-label">Add<br>Image</span></div><button class="var-img-remove" type="button" onclick="removeVariantImage('${escAttr(name)}')">✕</button></div></td>`;tbody.appendChild(tr);const tr2=document.createElement('tr');tr2.className='reporting-tags-row';tr2.innerHTML=`<td colspan="5"><span class="reporting-tags-toggle" onclick="this.parentElement.querySelector('.rt-content').style.display=this.parentElement.querySelector('.rt-content').style.display==='none'?'block':'none';">🏷️ Reporting Tags ▾</span><div class="rt-content" style="display:none;padding:6px 0 2px;"><input type="text" style="width:220px;border:1px solid var(--border);border-radius:5px;padding:5px 8px;font-size:12px;" placeholder="Add tags..." /></div></td>`;tbody.appendChild(tr2);});}
function deleteVariantRow(btn,name){const tr=btn.closest('tr.variant-data-row');const tr2=tr.nextElementSibling;tr.remove();if(tr2&&tr2.classList.contains('reporting-tags-row'))tr2.remove();delete variantAdditionalData[name];delete variantImageData[name];}
function generateAllSKU(){openSkuModal();}
function copyToAll(field){const selector=field==='cost'?'.var-cost':'.var-sell';const inputs=document.querySelectorAll(selector);if(!inputs.length)return;const first=inputs[0].value;if(!first){alert('Fill the first row value');return;}inputs.forEach(inp=>inp.value=first);}

// ══ ADDITIONAL INFO MODAL ══
let _addlCurrentVariant=null;
function openAddlModal(variantName){_addlCurrentVariant=variantName;const data=variantAdditionalData[variantName]||{};document.getElementById('addl-upc').value=data.upc||'';document.getElementById('addl-mpn').value=data.mpn||'';document.getElementById('addl-ean').value=data.ean||'';document.getElementById('addl-isbn').value=data.isbn||'';document.querySelectorAll('[id^="addl-cf-"]').forEach(inp=>{inp.value=data['cf_'+inp.dataset.fieldId]||'';});document.getElementById('addl-modal').classList.add('open');}
function closeAddlModal(){document.getElementById('addl-modal').classList.remove('open');_addlCurrentVariant=null;}
function saveAddlModal(){if(!_addlCurrentVariant){closeAddlModal();return;}const data={upc:document.getElementById('addl-upc').value.trim(),mpn:document.getElementById('addl-mpn').value.trim(),ean:document.getElementById('addl-ean').value.trim(),isbn:document.getElementById('addl-isbn').value.trim()};document.querySelectorAll('[id^="addl-cf-"]').forEach(inp=>{data['cf_'+inp.dataset.fieldId]=inp.value.trim();});variantAdditionalData[_addlCurrentVariant]=data;closeAddlModal();}
document.getElementById('addl-modal').addEventListener('click',e=>{if(e.target.id==='addl-modal')closeAddlModal();});

// ══ PACK & SUBMIT ══
function packVariants(){const isVariant=document.getElementById('item_variant_type').value==='contains_variants';if(!isVariant)return;const variants=[];document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr=>{const name=tr.dataset.variantName;const imgData=variantImageData[name];variants.push({name,sku:tr.querySelector('.var-sku')?.value||'',cost_price:tr.querySelector('.var-cost')?.value||'',selling_price:tr.querySelector('.var-sell')?.value||'',additional:variantAdditionalData[name]||{},product_image:imgData?'product_img/'+imgData.filename:null,product_image_base64:imgData?imgData.base64:null});});const variationData=getVariationData();document.getElementById('variants-json').value=JSON.stringify({attributes:variationData,variants});}
function handleFormSubmit(){const unitVal=document.getElementById('unit-hidden').value;if(!unitVal){alert('Please select a Unit.');document.getElementById('unit-search').focus();return;}const nameVal=document.querySelector('input[name="name"]').value.trim();if(!nameVal){alert('Please enter Item Name.');return;}packVariants();document.getElementById('main-form').submit();}

// ══ BRAND MODAL ══
function openBrandModal(){document.getElementById('brand-modal').classList.add('open');document.getElementById('new-brand-input').value='';document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';cancelEdit();loadBrands();}
function closeBrandModal(){document.getElementById('brand-modal').classList.remove('open');cancelEdit();}
document.getElementById('brand-modal').addEventListener('click',e=>{if(e.target.id==='brand-modal')closeBrandModal();});
async function loadBrands(){const list=document.getElementById('brand-list');list.innerHTML='<div class="brand-empty">Loading...</div>';try{const res=await fetch('/brands/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const json=await res.json();if(json.success)renderBrandList(json.data);else list.innerHTML='<div class="brand-empty" style="color:var(--pink);">Failed to load</div>';}catch{list.innerHTML='<div class="brand-empty" style="color:var(--pink);">Failed to load</div>';}}
function renderBrandList(brands){const list=document.getElementById('brand-list');if(!brands||!brands.length){list.innerHTML='<div class="brand-empty">No brands yet</div>';return;}list.innerHTML=brands.map(b=>{const s=esc(b.name);return`<div class="brand-list-item" id="brand-row-${b.id}"><span>${s}</span><div class="brand-actions"><button class="btn-edit-brand" onclick="editBrand(${b.id},'${s.replace(/'/g,"\\'")}')">✏️</button><button class="btn-del-brand" onclick="deleteBrand(${b.id},'${s.replace(/'/g,"\\'")}')">🗑️</button></div></div>`;}).join('');}
function editBrand(id,name){document.getElementById('add-brand-form').style.display='none';document.getElementById('edit-brand-form').style.display='flex';document.getElementById('edit-brand-id').value=id;document.getElementById('edit-brand-input').value=name;document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));document.getElementById(`brand-row-${id}`)?.classList.add('editing');document.getElementById('edit-brand-input').focus();}
function cancelEdit(){document.getElementById('edit-brand-form').style.display='none';document.getElementById('add-brand-form').style.display='flex';document.getElementById('edit-brand-id').value='';document.getElementById('edit-brand-input').value='';document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));}
async function updateBrand(){const id=document.getElementById('edit-brand-id').value;const name=document.getElementById('edit-brand-input').value.trim();const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-update-brand');errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';return;}btn.disabled=true;btn.textContent='Updating...';try{const res=await fetch(`/brands/${id}`,{method:'PUT',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const json=await res.json();if(!res.ok){errEl.textContent=json.message||'Failed';errEl.style.display='block';}else{sucEl.textContent='Updated!';sucEl.style.display='block';const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt)opt.textContent=name;loadBrands();cancelEdit();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='✓ Update';}}
async function addBrand(){const input=document.getElementById('new-brand-input');const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-add-brand');const name=input.value.trim();errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';input.focus();return;}btn.disabled=true;btn.textContent='Adding...';try{const res=await fetch('/brands',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const json=await res.json();if(!res.ok){errEl.textContent=json.message||'Failed';errEl.style.display='block';}else{input.value='';sucEl.textContent='Added!';sucEl.style.display='block';const sel=document.getElementById('brand-select');const opt=document.createElement('option');opt.value=json.data.id;opt.textContent=json.data.name;opt.selected=true;sel.appendChild(opt);loadBrands();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='+ Add New';}}
async function deleteBrand(id,name){if(!confirm(`Delete "${name}"?`))return;try{const res=await fetch(`/brands/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const json=await res.json();if(res.ok&&json.success){document.getElementById(`brand-row-${id}`)?.remove();const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt){if(opt.selected)document.getElementById('brand-select').value='';opt.remove();}if(!document.getElementById('brand-list').querySelector('.brand-list-item'))document.getElementById('brand-list').innerHTML='<div class="brand-empty">No brands yet</div>';const sucEl=document.getElementById('brand-success');sucEl.textContent='Deleted!';sucEl.style.display='block';setTimeout(()=>sucEl.style.display='none',3000);if(document.getElementById('edit-brand-id').value==id)cancelEdit();}else{alert(json.message||'Failed');}}catch{alert('Failed to delete brand');}}

// ══ ESCAPE HELPERS ══
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function escAttr(s){return String(s).replace(/'/g,"\\'");}

// ══ SKU MODAL ══
let _skuRowCount=0;
function getSkuAttrOptions(){const attrs=['Item Name','Custom Text'];document.querySelectorAll('.variation-row .var-attr-input').forEach(inp=>{const v=inp.value.trim();if(v&&!attrs.includes(v))attrs.push(v);});return attrs;}
function openSkuModal(){const itemName=document.querySelector('input[name="name"]')?.value||'ITEM';document.getElementById('sku-modal-title').textContent=itemName;document.getElementById('sku-rows-tbody').innerHTML='';_skuRowCount=0;skuAddRow('Item Name','First',3,'Upper Case','-');const firstAttr=document.querySelector('.variation-row .var-attr-input')?.value.trim();if(firstAttr)skuAddRow(firstAttr,'First',3,'Upper Case','');skuUpdatePreview();document.getElementById('sku-modal').classList.add('open');}
function closeSkuModal(){document.getElementById('sku-modal').classList.remove('open');}
function skuAddRow(attr='Item Name',showType='First',showCount=3,letterCase='Upper Case',separator='-'){_skuRowCount++;const n=_skuRowCount;const attrOpts=getSkuAttrOptions();const attrHtml=attrOpts.map(a=>`<option value="${escHtml(a)}" ${a===attr?'selected':''}>${escHtml(a)}</option>`).join('');const showInput=attr==='Custom Text'?`<input type="text" id="sku-custom-${n}" placeholder="Custom text" value="" style="border:1px solid var(--border);border-radius:6px;padding:7px 10px;font-size:13px;outline:none;width:100%;" oninput="skuUpdatePreview()" />`:`<div class="sku-show-wrap"><select id="sku-show-type-${n}" onchange="skuUpdatePreview()"><option ${showType==='First'?'selected':''}>First</option><option ${showType==='Last'?'selected':''}>Last</option></select><input type="number" id="sku-show-count-${n}" value="${showCount}" min="1" max="20" oninput="skuUpdatePreview()" /></div>`;const sepOpts=['-','_','/','.',''].map(s=>`<option value="${s}" ${s===separator?'selected':''}>${s===''?'(none)':s}</option>`).join('');const tr=document.createElement('tr');tr.id='sku-row-'+n;tr.innerHTML=`<td><select class="sku-sel" id="sku-attr-${n}" onchange="skuAttrChanged(${n});skuUpdatePreview()">${attrHtml}</select></td><td id="sku-show-cell-${n}">${showInput}</td><td><div class="sku-case-wrap"><select class="sku-case-sel" id="sku-case-${n}" onchange="skuUpdatePreview()"><option ${letterCase==='Upper Case'?'selected':''}>Upper Case</option><option ${letterCase==='Lower Case'?'selected':''}>Lower Case</option><option ${letterCase==='None'?'selected':''}>None</option></select><button class="sku-case-x" type="button" onclick="skuClearCase(${n})">✕</button></div></td><td><div class="sku-sep-wrap"><select class="sku-sep-sel" id="sku-sep-${n}" onchange="skuUpdatePreview()">${sepOpts}</select><button class="sku-sep-x" type="button" onclick="skuClearSep(${n})">✕</button></div></td><td><button class="sku-row-del" type="button" onclick="skuDelRow(${n})">⊗</button></td>`;document.getElementById('sku-rows-tbody').appendChild(tr);skuUpdatePreview();}
function skuAttrChanged(n){const attr=document.getElementById('sku-attr-'+n)?.value;const cell=document.getElementById('sku-show-cell-'+n);if(!cell)return;if(attr==='Custom Text'){cell.innerHTML=`<input type="text" id="sku-custom-${n}" placeholder="Custom text" value="" style="border:1px solid var(--border);border-radius:6px;padding:7px 10px;font-size:13px;outline:none;width:100%;" oninput="skuUpdatePreview()" />`;}else{cell.innerHTML=`<div class="sku-show-wrap"><select id="sku-show-type-${n}" onchange="skuUpdatePreview()"><option>First</option><option>Last</option></select><input type="number" id="sku-show-count-${n}" value="3" min="1" max="20" oninput="skuUpdatePreview()" /></div>`;}skuUpdatePreview();}
function skuClearCase(n){const sel=document.getElementById('sku-case-'+n);if(sel)sel.value='None';skuUpdatePreview();}
function skuClearSep(n){const sel=document.getElementById('sku-sep-'+n);if(sel)sel.value='';skuUpdatePreview();}
function skuDelRow(n){document.getElementById('sku-row-'+n)?.remove();skuUpdatePreview();}
function skuGetPartValue(n,variantName){const attr=document.getElementById('sku-attr-'+n)?.value||'';const caseType=document.getElementById('sku-case-'+n)?.value||'None';let raw='';if(attr==='Item Name'){raw=document.querySelector('input[name="name"]')?.value||'';const showType=document.getElementById('sku-show-type-'+n)?.value||'First';const count=parseInt(document.getElementById('sku-show-count-'+n)?.value||3);raw=showType==='First'?raw.slice(0,count):raw.slice(-count);}else if(attr==='Custom Text'){raw=document.getElementById('sku-custom-'+n)?.value||'';}else{const varData=getVariationData();const attrIdx=varData.findIndex(d=>d.attribute===attr);if(attrIdx>=0&&variantName){const parts=variantName.split(' - ');raw=parts[attrIdx]||attr;}else{raw=attr;}const showType=document.getElementById('sku-show-type-'+n)?.value||'First';const count=parseInt(document.getElementById('sku-show-count-'+n)?.value||3);raw=showType==='First'?raw.slice(0,count):raw.slice(-count);}if(caseType==='Upper Case')raw=raw.toUpperCase();else if(caseType==='Lower Case')raw=raw.toLowerCase();return raw;}
function skuBuildForVariant(variantName){const rows=document.querySelectorAll('#sku-rows-tbody tr');let result='';rows.forEach((tr,idx)=>{const n=tr.id.replace('sku-row-','');const part=skuGetPartValue(n,variantName);const sep=document.getElementById('sku-sep-'+n)?.value||'';result+=part;if(idx<rows.length-1)result+=sep;});return result;}
function skuUpdatePreview(){const firstVariant=document.querySelector('#variants-tbody tr.variant-data-row')?.dataset.variantName||'';const preview=skuBuildForVariant(firstVariant);document.getElementById('sku-preview-box').textContent=preview||'—';}
function applyGeneratedSKU(){document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr=>{const variantName=tr.dataset.variantName||'';const sku=skuBuildForVariant(variantName);const skuInput=tr.querySelector('.var-sku');if(skuInput)skuInput.value=sku;});closeSkuModal();}
document.getElementById('sku-modal').addEventListener('click',e=>{if(e.target.id==='sku-modal')closeSkuModal();});
</script>
@endpush