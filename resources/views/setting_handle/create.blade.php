<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items - General Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .settings-header {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }
        .settings-header h4 {
            margin: 0;
            color: #333;
            font-weight: 500;
        }
        .main-container {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .sidebar {
            width: 280px;
            margin-right: 30px;
        }
        .content {
            flex: 1;
        }
        .org-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .org-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .org-menu {
            list-style: none;
            padding: 10px 0;
            margin: 0;
        }
        .org-menu li {
            padding: 8px 20px;
            color: #333;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .org-menu li:hover {
            background-color: #f0f7ff;
        }
        .org-menu li.active {
            background-color: #e6f2ff;
            border-left: 3px solid #0d6efd;
            color: #0d6efd;
            font-weight: 500;
        }
        .org-menu li i {
            width: 20px;
            color: #666;
            margin-right: 10px;
        }
        .module-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .module-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .settings-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .settings-card-header {
            padding: 15px 25px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .settings-card-header h5 {
            margin: 0;
            color: #333;
            font-weight: 500;
        }
        .settings-card-header .badge {
            background: #e6f2ff;
            color: #0d6efd;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .settings-card-body {
            padding: 25px;
        }
        .setting-group {
            margin-bottom: 30px;
        }
        .setting-group-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        .setting-item {
            margin-bottom: 20px;
        }
        .setting-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        .setting-description {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 10px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .feature-list li a {
            display: inline-block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
            border-radius: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        .feature-list li a:hover {
            background-color: #e6f2ff;
            border-color: #0d6efd;
            color: #0d6efd;
        }
        .feature-list li a.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .feature-list li a i {
            margin-right: 6px;
        }
        .date-input {
            max-width: 200px;
        }
        .radio-group {
            display: flex;
            gap: 30px;
            margin-top: 10px;
        }
        .radio-item {
            display: flex;
            align-items: center;
        }
        .radio-item input[type="radio"] {
            margin-right: 8px;
        }
        .warning-box {
            background-color: #fff8e6;
            border-left: 3px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
        .info-box {
            background-color: #e6f2ff;
            border-left: 3px solid #0d6efd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .alert-warning-custom {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn-save {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-save:hover {
            background-color: #0b5ed7;
        }
        .btn-light {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #333;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-light:hover {
            background-color: #e9ecef;
        }
        .search-box {
            padding: 10px 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .search-box input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .close-settings {
            color: #999;
            cursor: pointer;
        }
        .user-info {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 500;
            color: #333;
        }
        .user-info i {
            margin-right: 10px;
            color: #0d6efd;
        }
        .sku-warning {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            color: #495057;
        }
        .sku-warning i {
            color: #ffc107;
            margin-right: 8px;
        }
        .email-input {
            max-width: 300px;
            margin-top: 10px;
        }
        .form-control {
            max-width: 400px;
        }
        select.form-control {
            max-width: 200px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-cog me-2"></i>All Settings</h4>
            <div class="d-flex align-items-center">
                <span class="me-3"><i class="fas fa-search"></i> Jayadeepa</span>
                <span class="close-settings"><i class="fas fa-times"></i> Close Settings</span>
            </div>
        </div>
    </div> 

    <div class="main-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="org-section">
                <div class="org-header">
                    <i class="fas fa-building me-2"></i>ORGANIZATION SETTINGS
                </div>
                <ul class="org-menu">
                    <li><i class="fas fa-building"></i> Organization</li>
                    <li><i class="fas fa-users"></i> Users & Roles</li>
                    <li><i class="fas fa-file-invoice"></i> Taxes & Compliance</li>
                    <li><i class="fas fa-cog"></i> Setup & Configurations</li>
                    <li class="active"><i class="fas fa-pencil-alt"></i> Customization</li>
                    <li><i class="fas fa-robot"></i> Automation</li>
                </ul>
            </div>

            <div class="module-section">
                <div class="module-header">
                    <i class="fas fa-cubes me-2"></i>MODULE SETTINGS
                </div>
                <ul class="org-menu">
                    <li class="active"><i class="fas fa-box"></i> Items</li>
                    <li><i class="fas fa-tag"></i> General</li>
                    <li><i class="fas fa-users"></i> Customers and Vendors</li>
                </ul>
            </div>
        </div>

        <div class="content">
            <!-- Items Settings Card -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <h5>Items</h5>
                    <span class="badge">General</span>
                </div>
                <div class="settings-card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Last Saved Time (Optional) -->
                    @if(session('last_saved'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-clock me-2"></i>Last saved: {{ session('last_saved') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    {{-- ⭐ General Settings defaults --}}
@php
    $defaultDimUnit    = $config['dimension_unit'] ?? 'cm';
    $defaultWeightUnit = $config['weight_unit']    ?? 'kg';
    $decimalRate       = $config['decimal_rate']   ?? 2;
    $weightStep        = $decimalRate > 0
                            ? '0.' . str_repeat('0', $decimalRate - 1) . '1'
                            : '1';
@endphp

                    <form action="{{ route('setting_handle.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- ===== 1. QUICK NAVIGATION ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Quick Navigation</div>
                            <ul class="feature-list">
                                <li>
                                    <a href="{{ route('setting_handle.create') }}" class="active">
                                        <i class="fas fa-cog"></i> General
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('field_customization.index') }}">
                                        <i class="fas fa-pencil-alt"></i> Field Customization
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('lock_configuration.create') }}">
                                        <i class="fas fa-lock"></i> Record Locking
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('custom.buttons') }}">
                                        <i class="fas fa-buttons"></i> Custom Buttons
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('related.lists') }}">
                                        <i class="fas fa-list"></i> Related Lists
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- ===== 2. CONFIGURATION NAME ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Configuration Name</div>
                            <div class="setting-item">
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $setting->process ?? '') }}"
                                       placeholder="Enter configuration name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ===== 3. BASIC SETTINGS ===== -->
                        <!-- Decimal Rate -->
                        <div class="setting-group">
                            <div class="setting-group-title">Decimal Rate</div>
                            <div class="setting-item">
                                <div class="setting-description">Set a decimal rate for your item quantity</div>
                                <input type="number" 
                                       name="decimal_rate" 
                                       class="form-control @error('decimal_rate') is-invalid @enderror" 
                                       value="{{ old('decimal_rate', $config['decimal_rate'] ?? 2) }}" 
                                       min="0" max="6" step="1">
                                @error('decimal_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dimensions -->
                        <div class="setting-group">
                            <div class="setting-group-title">Dimensions</div>
                            <div class="setting-item">
                                <div class="setting-description">Measure item dimensions in:</div>
                                @php $selectedDim = old('custom_field.dimension_unit', $defaultDimUnit); @endphp
                                <select name="custom_field[dimension_unit]" style="width:180px;">
                                    <option value="cm"   {{ $selectedDim == 'cm'   ? 'selected' : '' }}>cm</option>
                                    <option value="in"   {{ $selectedDim == 'in'   ? 'selected' : '' }}>in</option>
                                    <option value="m"    {{ $selectedDim == 'm'    ? 'selected' : '' }}>m</option>
                                    <option value="ft"   {{ $selectedDim == 'ft'   ? 'selected' : '' }}>ft</option>
                                    <option value="mm"   {{ $selectedDim == 'mm'   ? 'selected' : '' }}>mm</option>
                                </select>
                               @error('custom_field.dimension_unit')
                         <div class="text-danger small mt-1">{{ $message }}</div>
                               @enderror
                            </div>
                        </div>

                        <!-- Weight -->
                        <div class="setting-group">
                            <div class="setting-group-title">Weight</div>
                            <div class="setting-item">
                                <div class="setting-description">Measure item weights in:</div>
                                @php $selectedWeight = old('custom_field.weight_unit', $defaultWeightUnit); @endphp
                            <!-- <input type="number" name="custom_field[weight]"
                                step="{{ $weightStep }}"
                                style="width:180px;"
                                value="{{ old('custom_field.weight') }}" /> -->
                            <select name="custom_field[weight_unit]" style="width:180px;">
                                <option value="kg" {{ $selectedWeight == 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="g"  {{ $selectedWeight == 'g'  ? 'selected' : '' }}>g</option>
                                <option value="lb" {{ $selectedWeight == 'lb' ? 'selected' : '' }}>lb</option>
                                <option value="oz" {{ $selectedWeight == 'oz' ? 'selected' : '' }}>oz</option>
                                <option value="mg" {{ $selectedWeight == 'mg' ? 'selected' : '' }}>mg</option>
                            </select>
                               @error('custom_field.weight_unit')
    <div class="text-danger small mt-1">{{ $message }}</div>
@enderror
                            </div>
                        </div>

                        <!-- Barcode Scanning -->
                        <div class="setting-group">
                            <div class="setting-group-title">Barcode Scanning</div>
                            <div class="setting-item">
                                <div class="setting-description">Select items when barcodes are scanned using:</div>
                                <select name="barcode_field" class="form-control @error('barcode_field') is-invalid @enderror">
                                    <option value="sku" {{ old('barcode_field') == 'sku' ? 'selected' : '' }}>SKU</option>
                                    <option value="upc" {{ old('barcode_field') == 'upc' ? 'selected' : '' }}>UPC</option>
                                    <option value="ean" {{ old('barcode_field') == 'ean' ? 'selected' : '' }}>EAN</option>
                                    <option value="isbn" {{ old('barcode_field') == 'isbn' ? 'selected' : '' }}>ISBN</option>
                                </select>
                                @error('barcode_field')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Inventory Start Date - FIXED: Added value attribute -->
                        <div class="setting-group">
                            <div class="setting-group-title">Inventory Start Date*</div>
                            <div class="setting-item">
                                <input type="date" 
                                       name="inventory_start_date" 
                                       class="form-control @error('inventory_start_date') is-invalid @enderror" 
                                       value="{{ old('inventory_start_date', date('Y-m-d')) }}">
                                @error('inventory_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ===== 4. ALLOW DUPLICATE ITEM NAME ===== -->
                        <div class="setting-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="duplicate_item_name" 
                                       id="duplicate_item_name"
                                       value="1"
                                       {{ old('duplicate_item_name') ? 'checked' : '' }}
                                       onchange="toggleDuplicateWarning(this)">
                                <label class="form-check-label fw-bold" for="duplicate_item_name">
                                    Allow duplicate item names
                                </label>
                            </div>
                            <div class="setting-description ms-4">
                                If you allow duplicate item names, all imports involving items will use SKU as the primary field for mapping.
                            </div>

                            <!-- SKU Warning Message -->
                            <div id="skuWarning" class="sku-warning" style="{{ old('duplicate_item_name') ? 'display: block;' : 'display: none;' }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Before you enable this option, make the SKU field active and mandatory.</strong>
                            </div>
                        </div>

                        <!-- ===== 5. OTHER FEATURES ===== -->
                        <!-- Enhanced Item Search -->
                        <div class="setting-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enhanced_item_search" 
                                       id="enhanced_item_search"
                                       value="1"
                                       {{ old('enhanced_item_search') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enhanced_item_search">
                                    Enable Enhanced Item Search
                                </label>
                            </div>
                            <div class="setting-description ms-4">
                                Enabling this option makes it easier to find any item using relevant keywords in any order.
                            </div>
                        </div>

                        <!-- Price Lists -->
                        <div class="setting-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_price_lists" 
                                       id="enable_price_lists"
                                       value="1"
                                       {{ old('enable_price_lists', $config['enable_price_lists'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enable_price_lists">
                                    Enable Price Lists
                                </label>
                            </div>
                            <div class="setting-description ms-4">
                                Price Lists enables you to customise the rates of the items in your sales and purchase transactions.
                            </div>
                        </div>

                        <!-- Composite Items -->
                        <div class="setting-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_composite_items" 
                                       id="enable_composite_items"
                                       value="1"
                                       {{ old('enable_composite_items', $config['enable_composite_items'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enable_composite_items">
                                    Enable Composite Items
                                </label>
                            </div>
                        </div>

                        <!-- ===== 6. ADVANCED INVENTORY TRACKING ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Advanced Inventory Tracking</div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_serial_number" 
                                       id="enable_serial_number"
                                       value="1"
                                       {{ old('enable_serial_number') ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_serial_number">
                                    Enable Serial Number Tracking
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_batch_tracking" 
                                       id="enable_batch_tracking"
                                       value="1"
                                       {{ old('enable_batch_tracking') ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_batch_tracking">
                                    Enable Batch Tracking
                                </label>
                            </div>
                        </div>

                        <!-- ===== 7. STOCK PREVENTION ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Prevent stock from going below zero</div>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" 
                                           name="stock_prevention_level" 
                                           id="org_level" 
                                           value="organization"
                                           {{ old('stock_prevention_level', 'organization') == 'organization' ? 'checked' : '' }}>
                                    <label for="org_level">Organization level</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" 
                                           name="stock_prevention_level" 
                                           id="location_level" 
                                           value="location"
                                           {{ old('stock_prevention_level') == 'location' ? 'checked' : '' }}>
                                    <label for="location_level">Location level</label>
                                </div>
                            </div>
                        </div>

                        <!-- Out of Stock Warning -->
                        <div class="warning-box">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="show_out_of_stock_warning" 
                                       id="show_out_of_stock_warning"
                                       value="1"
                                       {{ old('show_out_of_stock_warning', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_out_of_stock_warning">
                                    Show an Out of Stock warning when an item's stock drops below zero
                                </label>
                            </div>
                        </div>

                        <!-- ===== 8. ADDITIONAL SETTINGS ===== -->
                        <div class="setting-group mt-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="notify_reorder_point" 
                                       id="notify_reorder_point"
                                       value="1"
                                       {{ old('notify_reorder_point') ? 'checked' : '' }}
                                       onchange="toggleEmailField(this)">
                                <label class="form-check-label" for="notify_reorder_point">
                                    Notify me if an item's quantity reaches the reorder point
                                </label>
                            </div>

                            <!-- Email for notifications -->
                            <div id="notifyEmailField" class="email-input" style="{{ old('notify_reorder_point') ? 'display: block;' : 'display: none;' }}">
                                <label class="setting-label">Notify to*</label>
                                <input type="email" 
                                       name="notify_email" 
                                       class="form-control @error('notify_email') is-invalid @enderror" 
                                       value="{{ old('notify_email', 'jayadeepafemiwd@gmail.com') }}"
                                       placeholder="Enter email address">
                                @error('notify_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="track_landed_cost" 
                                       id="track_landed_cost"
                                       value="1"
                                       {{ old('track_landed_cost') ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_landed_cost">
                                    Track landed cost on items
                                </label>
                            </div>

                            <!-- Replenishments -->
                            <!-- <div class="setting-item mt-3">
                                <label class="setting-label">Replenishments</label>
                                <select name="replenishments" class="form-control @error('replenishments') is-invalid @enderror" style="max-width: 200px;">
                                    <option value="disabled" {{ old('replenishments', 'disabled') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                    <option value="enabled" {{ old('replenishments') == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                    <option value="auto" {{ old('replenishments') == 'auto' ? 'selected' : '' }}>Automatic</option>
                                </select>
                                @error('replenishments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> -->

                        <hr>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle SKU Warning based on Duplicate Item Name checkbox
        function toggleDuplicateWarning(checkbox) {
            const warning = document.getElementById('skuWarning');
            if (checkbox.checked) {
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
        }

        // Toggle Email field based on Notify checkbox
        function toggleEmailField(checkbox) {
            const emailField = document.getElementById('notifyEmailField');
            if (checkbox.checked) {
                emailField.style.display = 'block';
            } else {
                emailField.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize duplicate warning
            const dupCheckbox = document.getElementById('duplicate_item_name');
            if (dupCheckbox && dupCheckbox.checked) {
                document.getElementById('skuWarning').style.display = 'block';
            }
            
            // Initialize email field
            const notifyCheckbox = document.getElementById('notify_reorder_point');
            if (notifyCheckbox && notifyCheckbox.checked) {
                document.getElementById('notifyEmailField').style.display = 'block';
            }
        });
    </script>
</body>
</html>