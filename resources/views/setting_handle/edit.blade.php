<!-- resources/views/setting_handle/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Settings - {{ $setting->process}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Same styles as create.blade.php */
        body { background-color: #f5f5f5; }
        .settings-header { background: white; padding: 15px 30px; border-bottom: 1px solid #e0e0e0; margin-bottom: 20px; }
        .settings-header h4 { margin: 0; color: #333; font-weight: 500; }
        .main-container { display: flex; max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        .sidebar { width: 280px; margin-right: 30px; }
        .content { flex: 1; }
        .settings-card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .settings-card-header { padding: 15px 25px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .settings-card-header h5 { margin: 0; color: #333; font-weight: 500; }
        .settings-card-body { padding: 25px; }
        .btn-save { background-color: #0d6efd; color: white; border: none; padding: 10px 30px; border-radius: 4px; font-weight: 500; cursor: pointer; }
        .btn-save:hover { background-color: #0b5ed7; }
        .form-control { max-width: 400px; }
        select.form-control { max-width: 200px; }
    </style>
</head>
<body>
    <div class="settings-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-cog me-2"></i>All Settings</h4>
        </div>
    </div>

    <div class="main-container">
        <div class="content">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h5>Edit Settings - {{ $setting->process}}</h5>
                </div>
                <div class="settings-card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
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

                    @php
                        $config = $setting->config ?? [];
                    @endphp

                    <form action="{{ route('setting_handle.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Configuration Name -->
                        <div class="setting-group">
                            <div class="setting-group-title">Configuration Name</div>
                            <div class="setting-item">
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $setting->process) }}">
                            </div>
                        </div>

                        <!-- Decimal Rate -->
                        <div class="setting-group">
                            <div class="setting-group-title">Decimal Rate</div>
                            <div class="setting-item">
                                <input type="number" 
                                       name="decimal_rate" 
                                       class="form-control @error('decimal_rate') is-invalid @enderror" 
                                       value="{{ old('decimal_rate', $config['decimal_rate'] ?? 2) }}" 
                                       min="0" max="6" step="1">
                            </div>
                        </div>

                        <!-- Dimensions -->
                        <div class="setting-group">
                            <div class="setting-group-title">Dimensions</div>
                            <div class="setting-item">
                                <select name="dimension_unit" class="form-control @error('dimension_unit') is-invalid @enderror">
                                    <option value="cm" {{ (old('dimension_unit', $config['dimension_unit'] ?? '')) == 'cm' ? 'selected' : '' }}>cm</option>
                                    <option value="m" {{ (old('dimension_unit', $config['dimension_unit'] ?? '')) == 'm' ? 'selected' : '' }}>m</option>
                                    <option value="inch" {{ (old('dimension_unit', $config['dimension_unit'] ?? '')) == 'inch' ? 'selected' : '' }}>inch</option>
                                    <option value="ft" {{ (old('dimension_unit', $config['dimension_unit'] ?? '')) == 'ft' ? 'selected' : '' }}>ft</option>
                                    <option value="mm" {{ (old('dimension_unit', $config['dimension_unit'] ?? '')) == 'mm' ? 'selected' : '' }}>mm</option>
                                </select>
                            </div>
                        </div>

                        <!-- Weight -->
                        <div class="setting-group">
                            <div class="setting-group-title">Weight</div>
                            <div class="setting-item">
                                <select name="weight_unit" class="form-control @error('weight_unit') is-invalid @enderror">
                                    <option value="kg" {{ (old('weight_unit', $config['weight_unit'] ?? '')) == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="g" {{ (old('weight_unit', $config['weight_unit'] ?? '')) == 'g' ? 'selected' : '' }}>g</option>
                                    <option value="lb" {{ (old('weight_unit', $config['weight_unit'] ?? '')) == 'lb' ? 'selected' : '' }}>lb</option>
                                    <option value="oz" {{ (old('weight_unit', $config['weight_unit'] ?? '')) == 'oz' ? 'selected' : '' }}>oz</option>
                                    <option value="mg" {{ (old('weight_unit', $config['weight_unit'] ?? '')) == 'mg' ? 'selected' : '' }}>mg</option>
                                </select>
                            </div>
                        </div>

                        <!-- Barcode Scanning -->
                        <div class="setting-group">
                            <div class="setting-group-title">Barcode Scanning</div>
                            <div class="setting-item">
                                <select name="barcode_field" class="form-control @error('barcode_field') is-invalid @enderror">
                                    <option value="sku" {{ (old('barcode_field', $config['barcode_field'] ?? '')) == 'sku' ? 'selected' : '' }}>SKU</option>
                                    <option value="upc" {{ (old('barcode_field', $config['barcode_field'] ?? '')) == 'upc' ? 'selected' : '' }}>UPC</option>
                                    <option value="ean" {{ (old('barcode_field', $config['barcode_field'] ?? '')) == 'ean' ? 'selected' : '' }}>EAN</option>
                                    <option value="isbn" {{ (old('barcode_field', $config['barcode_field'] ?? '')) == 'isbn' ? 'selected' : '' }}>ISBN</option>
                                </select>
                            </div>
                        </div>

                        <!-- Inventory Start Date -->
                        <div class="setting-group">
                            <div class="setting-group-title">Inventory Start Date*</div>
                            <div class="setting-item">
                                <input type="date" 
                                       name="inventory_start_date" 
                                       class="form-control @error('inventory_start_date') is-invalid @enderror" 
                                       value="{{ old('inventory_start_date', $config['inventory_start_date'] ?? date('Y-m-d')) }}">
                            </div>
                        </div>

                        <!-- Allow Duplicate Item Name -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="duplicate_item_name" 
                                       id="duplicate_item_name"
                                       value="1"
                                       {{ old('duplicate_item_name', $config['duplicate_item_name'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="duplicate_item_name">
                                    Allow duplicate item names
                                </label>
                            </div>
                        </div>

                        <!-- Enhanced Item Search -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enhanced_item_search" 
                                       id="enhanced_item_search"
                                       value="1"
                                       {{ old('enhanced_item_search', $config['enhanced_item_search'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enhanced_item_search">
                                    Enable Enhanced Item Search
                                </label>
                            </div>
                        </div>

                        <!-- Price Lists -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_price_lists" 
                                       id="enable_price_lists"
                                       value="1"
                                       {{ old('enable_price_lists', $config['enable_price_lists'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_price_lists">
                                    Enable Price Lists
                                </label>
                            </div>
                        </div>

                        <!-- Composite Items -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_composite_items" 
                                       id="enable_composite_items"
                                       value="1"
                                       {{ old('enable_composite_items', $config['enable_composite_items'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_composite_items">
                                    Enable Composite Items
                                </label>
                            </div>
                        </div>

                        <!-- Advanced Inventory Tracking -->
                        <div class="setting-group">
                            <div class="setting-group-title">Advanced Inventory Tracking</div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_serial_number" 
                                       id="enable_serial_number"
                                       value="1"
                                       {{ old('enable_serial_number', $config['enable_serial_number'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_serial_number">Enable Serial Number Tracking</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="enable_batch_tracking" 
                                       id="enable_batch_tracking"
                                       value="1"
                                       {{ old('enable_batch_tracking', $config['enable_batch_tracking'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_batch_tracking">Enable Batch Tracking</label>
                            </div>
                        </div>

                        <!-- Stock Prevention -->
                        <div class="setting-group">
                            <div class="setting-group-title">Prevent stock from going below zero</div>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" 
                                           name="stock_prevention_level" 
                                           value="organization"
                                           {{ old('stock_prevention_level', $config['stock_prevention_level'] ?? 'organization') == 'organization' ? 'checked' : '' }}>
                                    <label>Organization level</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" 
                                           name="stock_prevention_level" 
                                           value="location"
                                           {{ old('stock_prevention_level', $config['stock_prevention_level'] ?? '') == 'location' ? 'checked' : '' }}>
                                    <label>Location level</label>
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
                                       {{ old('show_out_of_stock_warning', $config['show_out_of_stock_warning'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_out_of_stock_warning">
                                    Show an Out of Stock warning when an item's stock drops below zero
                                </label>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="notify_reorder_point" 
                                       id="notify_reorder_point"
                                       value="1"
                                       {{ old('notify_reorder_point', $config['notify_reorder_point'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_reorder_point">
                                    Notify me if an item's quantity reaches the reorder point
                                </label>
                            </div>

                            <div id="notifyEmailField" class="email-input" style="{{ (old('notify_reorder_point', $config['notify_reorder_point'] ?? false)) ? 'display: block;' : 'display: none;' }}">
                                <label>Notify to*</label>
                                <input type="email" 
                                       name="notify_email" 
                                       class="form-control" 
                                       value="{{ old('notify_email', $config['notify_email'] ?? 'jayadeepafemiwd@gmail.com') }}">
                            </div>
                        </div>

                        <!-- Track Landed Cost -->
                        <div class="setting-group">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="track_landed_cost" 
                                       id="track_landed_cost"
                                       value="1"
                                       {{ old('track_landed_cost', $config['track_landed_cost'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_landed_cost">Track landed cost on items</label>
                            </div>
                        </div>

                        <!-- Replenishments -->
                        <!-- <div class="setting-group">
                            <div class="setting-group-title">Replenishments</div>
                            <select name="replenishments" class="form-control">
                                <option value="disabled" {{ old('replenishments', $config['replenishments'] ?? 'disabled') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                <option value="enabled" {{ old('replenishments', $config['replenishments'] ?? '') == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                <option value="auto" {{ old('replenishments', $config['replenishments'] ?? '') == 'auto' ? 'selected' : '' }}>Automatic</option>
                            </select>
                        </div> -->

                        <hr>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>Update Settings
                            </button>
                            <a href="{{ route('setting_handle.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEmailField(checkbox) {
            document.getElementById('notifyEmailField').style.display = 
                checkbox.checked ? 'block' : 'none';
        }

        document.getElementById('notify_reorder_point').addEventListener('change', function() {
            toggleEmailField(this);
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('notify_reorder_point').checked) {
                document.getElementById('notifyEmailField').style.display = 'block';
            }
        });
    </script>
</body>
</html>