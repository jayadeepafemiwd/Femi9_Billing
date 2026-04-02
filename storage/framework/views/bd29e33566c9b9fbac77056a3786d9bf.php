<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Custom Field - Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        .main-container {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .sidebar {
            width: 280px;
            margin-right: 30px;
        }
        .content {
            flex: 1;
            max-width: 700px;
        }
        .settings-sidebar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .settings-sidebar h6 {
            color: #6b7280;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        .settings-sidebar .nav-link {
            color: #4b5563;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 2px;
            font-size: 0.95rem;
        }
        .settings-sidebar .nav-link:hover {
            background-color: #f3f4f6;
        }
        .settings-sidebar .nav-link.active {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 500;
        }
        .module-settings {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: white;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 24px 30px;
            border-radius: 16px 16px 0 0 !important;
        }
        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        .card-header .subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .card-body {
            padding: 30px;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        .form-label .required {
            color: #ef4444;
        }
        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .remaining-fields {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .radio-group {
            display: flex;
            gap: 30px;
            padding: 10px 0;
        }
        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .radio-item input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
        }
        .privacy-section {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
        }
        .privacy-warning {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 12px 16px;
            color: #991b1b;
            font-size: 0.95rem;
            margin-top: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .privacy-warning i {
            color: #dc2626;
            font-size: 1.1rem;
            margin-top: 2px;
        }
        .privacy-info {
            background: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 8px;
            padding: 12px 16px;
            color: #075985;
            font-size: 0.95rem;
            margin-top: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .privacy-info i {
            color: #0284c7;
            font-size: 1.1rem;
            margin-top: 2px;
        }
        .checkbox-group {
            display: flex;
            gap: 30px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
        }
        .info-note {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 12px 16px;
            color: #0369a1;
            font-size: 0.9rem;
            margin-top: 15px;
        }
        .btn-save {
            background-color: #4f46e5;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 500;
        }
        .btn-save:hover {
            background-color: #4338ca;
        }
        .btn-cancel {
            background: white;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 500;
        }
        .btn-cancel:hover {
            background: #f9fafb;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Left Sidebar -->
       <?php echo $__env->make('layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Main Content -->
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h5>New Custom Field - Items</h5>
                    <div class="subtitle"># All Settings · Jayadeepa</div>
                </div>
                <div class="card-body">
                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                      
                    <form action="<?php echo e(route('field_customization.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="category_name" value="<?php echo e($category); ?>" />
                        <!-- Label Name Field (Added as per screenshot) -->
                        <div class="mb-4">
                            <label class="form-label">Label Name <span class="required">*</span></label>
                            <input type="text" 
                                   name="label_name" 
                                   class="form-control <?php $__errorArgs = ['label_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('label_name')); ?>" 
                                   placeholder="Enter field label"
                                   required>
                            <?php $__errorArgs = ['label_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Data Type Selection -->
                        <div class="mb-4">
                            <label class="form-label">Data Type <span class="required">*</span></label>
                            <select name="data_type" id="dataType" class="form-select" required onchange="toggleFields()">
                                <option value="Number">Number</option>
                                <option value="Text">Text</option>
                                <option value="Decimal">Decimal</option>
                                <option value="Date">Date</option>
                                <option value="DateTime" selected>Date and Time</option>
                                <option value="Boolean">Boolean</option>
                                <option value="AutoNumber">Auto-Generate Number</option>
                                <option value="Image">Image</option>
                                <option value="File">File</option>
                                <option value="Dropdown">Dropdown</option>
                                <option value="MultiSelect">Multi-Select</option>
                                <option value="Phone">Phone Number</option>
                                <option value="Email">Email</option>
                                <option value="URL">URL</option>
                                <option value="Currency">Currency</option>
                                <option value="Percentage">Percentage</option>
                                <option value="Time">Time</option>
                            </select>
                            <div class="remaining-fields" id="remainingFields">Remaining Fields: 34</div>
                        </div>

                        <!-- Dynamic Fields Container -->
                        <div id="dynamicFieldsContainer"></div>

                      <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>Field Access Permissions
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAccessSection()">
                                    <i class="fas fa-cog"></i> Configure Access
                                </button>
                            </div>
                            
                            <!-- Access Configuration Section (Hidden by Default) -->
                            <div id="accessSection" style="display: none;" class="border rounded p-3 bg-light mb-3">
                                <div class="mb-3">
                                    <div class="form-text mb-2">
                                        <i class="fas fa-info-circle text-info"></i> 
                                        Configure which roles can view/edit this field
                                    </div>
                                </div>
                                
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 30%">ROLE</th>
                                            <th style="width: 20%" class="text-center">READ AND WRITE</th>
                                            <th style="width: 20%" class="text-center">READ ONLY</th>
                                            <th style="width: 20%" class="text-center">HIDE FIELD</th>
                                            <th style="width: 10%" class="text-center">Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $roles = [
                                                ['id' => 1, 'name' => 'Admin'],
                                                ['id' => 2, 'name' => 'Manager'],
                                                ['id' => 3, 'name' => 'Staff'],
                                                ['id' => 4, 'name' => 'Viewer'],
                                            ];
                                        ?>
                                        
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo e($role['name']); ?></td>
                                            
                                            <!-- Read & Write -->
                                            <td class="text-center">
                                                <input type="radio" 
                                                       name="access[<?php echo e($role['id']); ?>][permission]" 
                                                       value="read_write"
                                                       id="rw_<?php echo e($role['id']); ?>"
                                                       class="form-check-input"
                                                       <?php echo e($index == 0 ? 'checked' : ''); ?>>
                                            </td>
                                            
                                            <!-- Read Only -->
                                            <td class="text-center">
                                                <input type="radio" 
                                                       name="access[<?php echo e($role['id']); ?>][permission]" 
                                                       value="read_only"
                                                       id="ro_<?php echo e($role['id']); ?>"
                                                       class="form-check-input">
                                            </td>
                                            
                                            <!-- Hide Field -->
                                            <td class="text-center">
                                                <input type="radio" 
                                                       name="access[<?php echo e($role['id']); ?>][permission]" 
                                                       value="hide"
                                                       id="hide_<?php echo e($role['id']); ?>"
                                                       class="form-check-input">
                                            </td>
                                            
                                            <!-- Admin Checkbox -->
                                            <td class="text-center">
                                                <input type="checkbox" 
                                                       name="access[<?php echo e($role['id']); ?>][is_admin]"
                                                       id="admin_<?php echo e($role['id']); ?>"
                                                       class="form-check-input"
                                                       value="1"
                                                       <?php echo e($role['name'] == 'Admin' ? 'checked' : ''); ?>>
                                            </td>
                                            
                                            <!-- Hidden fields -->
                                            <input type="hidden" name="access[<?php echo e($role['id']); ?>][role_id]" value="<?php echo e($role['id']); ?>">
                                            <input type="hidden" name="access[<?php echo e($role['id']); ?>][role_name]" value="<?php echo e($role['name']); ?>">
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                
                                <div class="alert alert-warning py-2 small mb-0">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Note:</strong> If you select Read Only or Hide Field for a role, 
                                    users under that role will not be able to update this field.
                                </div>
                            </div>
                        </div>
                        <!-- ===== ACCESS CONFIGURATION SECTION END ===== -->

                        <!-- Common Fields for All Types -->
                        <div class="mt-4">
                            <!-- Is Mandatory -->
                            <div class="mb-4">
                                <label class="form-label">Is Mandatory</label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="mandatory" value="yes"> Yes
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="mandatory" value="no" checked> No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                            <button type="submit" class="btn btn-save text-white">Save Field</button>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates for different data types -->
    <div style="display: none;">
        <!-- Number/Decimal Template with PII functionality -->
        <div id="numberTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckbox" onchange="handlePIIChange(this)"> PII
                    </label>
                </div>
                
                <!-- Warning message for PII (hidden by default) -->
                <div id="piiWarning" class="privacy-warning" style="display: none;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong><br>
                        Only users with access to protected data will be able to view the details, and this field cannot be used to perform an advanced search.
                    </div>
                </div>

                <!-- Info message for non-PII (visible by default) -->
                <div id="nonPIIInfo" class="privacy-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong><br>
                        Data will be stored without encryption and will be visible to all users.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Input Format <span class="required">*</span></label>
                <select name="input_format" class="form-select">
                    <option value="default">Default Value</option>
                    <option value="range">Range</option>
                    <option value="limit">Limit</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <input type="number" name="default_value" class="form-control" step="any">
            </div>
        </div>

        <!-- Date and Time Template with PII functionality -->
        <div id="dateTimeTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxDateTime" onchange="handlePIIChange(this)"> PII
                    </label>
                </div>
                
                <div id="piiWarningDateTime" class="privacy-warning" style="display: none;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong><br>
                        Only users with access to protected data will be able to view the details, and this field cannot be used to perform an advanced search.
                    </div>
                </div>

                <div id="nonPIIInfoDateTime" class="privacy-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong><br>
                        Data will be stored without encryption and will be visible to all users.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="default_date" class="form-control" placeholder="dd/MM/yyyy" value="dd/MM/yyyy">
                    </div>
                    <div class="col-6">
                        <input type="text" name="default_time" class="form-control" placeholder="HH:MM" value="HH:MM">
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-Generate Number Template -->
        <div id="autoNumberTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label">Prefix</label>
                    <input type="text" name="prefix" class="form-control" placeholder="Prefix">
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">Starting Number <span class="required">*</span></label>
                    <input type="number" name="starting_number" class="form-control" value="1">
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">Suffix</label>
                    <input type="text" name="suffix" class="form-control" placeholder="Suffix">
                </div>
            </div>

            <div class="info-note">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="add_to_existing" id="addToExisting">
                    <label class="form-check-label" for="addToExisting">
                        Add this custom field to all the existing items and auto-generate the number in all of them.
                    </label>
                </div>
                <div class="mt-2 small text-muted">
                    This is a one-time setup and you cannot edit this setting later.
                </div>
            </div>
        </div>

        <!-- Image Template -->
        <div id="imageTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <div>
                    <button type="button" class="btn btn-outline-secondary">Upload File</button>
                    <small class="text-muted ms-2">Supported: JPG, PNG, GIF (Max: 5MB)</small>
                </div>
            </div>
        </div>

        <!-- Text Template with PII functionality -->
        <div id="textTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxText" onchange="handlePIIChange(this)"> PII
                    </label>
                </div>
                
                <div id="piiWarningText" class="privacy-warning" style="display: none;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong><br>
                        Only users with access to protected data will be able to view the details, and this field cannot be used to perform an advanced search.
                    </div>
                </div>

                <div id="nonPIIInfoText" class="privacy-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong><br>
                        Data will be stored without encryption and will be visible to all users.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <input type="text" name="default_value" class="form-control" placeholder="Enter default text">
            </div>

            <div class="mb-4">
                <label class="form-label">Character Limit</label>
                <input type="number" name="char_limit" class="form-control" placeholder="e.g., 255">
            </div>
        </div>

        <!-- Dropdown Template -->
        <div id="dropdownTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="Enter some text to help users understand the purpose of this custom field.">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxDropdown" onchange="handlePIIChange(this)"> PII
                    </label>
                </div>
                
                <div id="piiWarningDropdown" class="privacy-warning" style="display: none;">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong><br>
                        Only users with access to protected data will be able to view the details, and this field cannot be used to perform an advanced search.
                    </div>
                </div>

                <div id="nonPIIInfoDropdown" class="privacy-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong><br>
                        Data will be stored without encryption and will be visible to all users.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Options</label>
                <textarea name="options" class="form-control" rows="4" placeholder="Enter each option on a new line&#10;Option 1&#10;Option 2&#10;Option 3"></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <select name="default_value" class="form-select">
                    <option value="">Select default option</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handlePIIChange(checkbox) {
            // Get the container where this checkbox exists
            const container = checkbox.closest('.privacy-section');
            
            // Find warning and info elements within this container
            const warning = container.querySelector('.privacy-warning');
            const info = container.querySelector('.privacy-info');
            
            if (checkbox.checked) {
                // Show warning, hide info
                if (warning) warning.style.display = 'flex';
                if (info) info.style.display = 'none';
            } else {
                // Hide warning, show info
                if (warning) warning.style.display = 'none';
                if (info) info.style.display = 'flex';
            }
        }

       function toggleFields() {
    const dataType = document.getElementById('dataType').value;
    const container = document.getElementById('dynamicFieldsContainer');
    
    // Update remaining fields
    const remainingFields = document.getElementById('remainingFields');
    const fieldCounts = {
        'Number': '34', 'Text': '34', 'Decimal': '34', 'Date': '34',
        'DateTime': '34', 'Boolean': '34', 'AutoNumber': '1',
        'Image': '4', 'File': '4', 'Dropdown': '34', 'MultiSelect': '34',
        'Phone': '34', 'Email': '34', 'URL': '34', 'Currency': '34',
        'Percentage': '34', 'Time': '34'
    };
    remainingFields.textContent = `Remaining Fields: ${fieldCounts[dataType] || '34'}`;

    // Clear container
    container.innerHTML = '';

    // Help Text messages for different data types
    const helpTextMessages = {
        'Number': 'Enter numeric value only (e.g., 100, 250.50)',
        'Decimal': 'Enter decimal value (e.g., 10.50, 99.99)',
        'Currency': 'Enter amount in numbers (e.g., 1000, 500.75)',
        'Percentage': 'Enter percentage without % symbol (e.g., 15, 25.5)',
        'Text': 'Enter text (max 255 characters)',
        'Phone': 'Enter 10-digit mobile number (e.g., 9876543210)',
        'Email': 'Enter valid email address (e.g., user@example.com)',
        'URL': 'Enter valid URL (e.g., https://example.com)',
        'Date': 'Select date in DD/MM/YYYY format',
        'DateTime': 'Select date and time in DD/MM/YYYY HH:MM format',
        'Time': 'Select time in HH:MM format',
        'Boolean': 'Select Yes/No or True/False',
        'AutoNumber': 'Number will be auto-generated based on prefix/suffix',
        'Image': 'Upload image file (JPG, PNG, GIF - Max 5MB)',
        'File': 'Upload file (PDF, DOC, etc - Max 10MB)',
        'Dropdown': 'Select one option from the dropdown',
        'MultiSelect': 'Select multiple options from the list'
    };

    // Show appropriate template based on data type
    switch(dataType) {
        case 'Number':
        case 'Decimal':
        case 'Currency':
        case 'Percentage':
            container.innerHTML = document.getElementById('numberTemplate').innerHTML;
            // Set specific help text for number types
            const numberHelpText = container.querySelector('input[name="help_text"]');
            if (numberHelpText) numberHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'DateTime':
        case 'Date':
        case 'Time':
            container.innerHTML = document.getElementById('dateTimeTemplate').innerHTML;
            const dateHelpText = container.querySelector('input[name="help_text"]');
            if (dateHelpText) dateHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'AutoNumber':
            container.innerHTML = document.getElementById('autoNumberTemplate').innerHTML;
            const autoHelpText = container.querySelector('input[name="help_text"]');
            if (autoHelpText) autoHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'Image':
        case 'File':
            container.innerHTML = document.getElementById('imageTemplate').innerHTML;
            const imageHelpText = container.querySelector('input[name="help_text"]');
            if (imageHelpText) imageHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'Text':
        case 'Phone':
        case 'Email':
        case 'URL':
            container.innerHTML = document.getElementById('textTemplate').innerHTML;
            const textHelpText = container.querySelector('input[name="help_text"]');
            if (textHelpText) textHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'Dropdown':
        case 'MultiSelect':
            container.innerHTML = document.getElementById('dropdownTemplate').innerHTML;
            const dropdownHelpText = container.querySelector('input[name="help_text"]');
            if (dropdownHelpText) dropdownHelpText.value = helpTextMessages[dataType];
            break;
            
        case 'Boolean':
            container.innerHTML = `
                <div class="mb-4">
                    <label class="form-label">Help Text</label>
                    <input type="text" name="help_text" class="form-control" 
                           value="${helpTextMessages[dataType]}">
                </div>
                <div class="mb-4">
                    <label class="form-label">Default Value</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="default_value" value="1"> True/Yes
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="default_value" value="0"> False/No
                        </label>
                    </div>
                </div>
            `;
            break;
    }
}

 function toggleAccessSection() {
            const section = document.getElementById('accessSection');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
        });

       
    </script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/field_customization/create.blade.php ENDPATH**/ ?>