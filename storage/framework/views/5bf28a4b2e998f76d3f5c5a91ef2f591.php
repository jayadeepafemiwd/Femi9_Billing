<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Custom Field</title>
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
            background: #ffc107;
            border-bottom: 1px solid #e5e7eb;
            padding: 24px 30px;
            border-radius: 16px 16px 0 0 !important;
        }
        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        .card-header .subtitle {
            color: #666;
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
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
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
            accent-color: #ffc107;
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
            accent-color: #ffc107;
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
            background-color: #ffc107;
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 500;
            color: #333;
        }
        .btn-save:hover {
            background-color: #e0a800;
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
        .existing-fields-section {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .existing-field-item {
            background: white;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid #ffe69c;
            display: inline-block;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .existing-field-item i {
            color: #ffc107;
            margin-right: 5px;
        }
        .stats-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin: 15px 0;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .stat-item {
            flex: 1;
        }
        .stat-value {
            font-size: 1.3rem;
            font-weight: bold;
            color: #ffc107;
        }
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Left Sidebar -->
        <?php echo $__env->make('layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="content">
            <!-- Show Other Existing Fields Section -->
            <?php if(isset($otherFields) && count($otherFields) > 0): ?>
            <div class="existing-fields-section">
                <h6 class="mb-2"><i class="fas fa-list me-2"></i>Other Custom Fields (<?php echo e(count($otherFields)); ?>)</h6>
                <div class="mb-2">
                    <?php $__currentLoopData = $otherFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="existing-field-item">
                        <i class="fas fa-tag"></i>
                        <?php echo e($otherField->Name); ?> 
                        <span class="badge bg-secondary text-white ms-1"><?php echo e($otherField->data_type); ?></span>
                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Statistics Box -->
            <?php if(isset($fieldStats)): ?>
            <div class="stats-box">
                <div class="stat-item">
                    <div class="stat-value"><?php echo e($fieldStats['total']); ?></div>
                    <div class="stat-label">Total Fields</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo e($fieldStats['same_type']); ?></div>
                    <div class="stat-label">Same Type</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?php echo e($fieldStats['mandatory']); ?></div>
                    <div class="stat-label">Mandatory</div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5>Edit Custom Field - Items</h5>
                    <div class="subtitle">Editing: <strong><?php echo e($field->name); ?></strong> · Last updated: <?php echo e($field->updated_at->format('d M Y, h:i A')); ?></div>
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

                    <form action="<?php echo e(route('field_customization.update', $field->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <!-- Label Name Field -->
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
                                   value="<?php echo e(old('label_name', $field->label_name ?? $field->name)); ?>" 
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

                        <!-- Additional Field -->
                        <div class="mb-4">
                            <label class="form-label">Additional Field <span class="required">*</span></label>
                            <input type="text" 
                                   name="additional_fields" 
                                   class="form-control <?php $__errorArgs = ['additional_fields'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('additional_fields', $field->name)); ?>" 
                                   placeholder="Enter field name"
                                   list="fieldSuggestions"
                                   required>
                            <datalist id="fieldSuggestions">
                                <?php if(isset($allFieldNames)): ?>
                                    <?php $__currentLoopData = $allFieldNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fieldname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($fieldname); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </datalist>
                            <?php $__errorArgs = ['additional_fields'];
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
                                <option value="Number" <?php echo e($field->data_type == 'Number' ? 'selected' : ''); ?>>Number</option>
                                <option value="Text" <?php echo e($field->data_type == 'Text' ? 'selected' : ''); ?>>Text</option>
                                <option value="Decimal" <?php echo e($field->data_type == 'Decimal' ? 'selected' : ''); ?>>Decimal</option>
                                <option value="Date" <?php echo e($field->data_type == 'Date' ? 'selected' : ''); ?>>Date</option>
                                <option value="DateTime" <?php echo e($field->data_type == 'DateTime' ? 'selected' : ''); ?>>Date and Time</option>
                                <option value="Boolean" <?php echo e($field->data_type == 'Boolean' ? 'selected' : ''); ?>>Boolean</option>
                                <option value="AutoNumber" <?php echo e($field->data_type == 'AutoNumber' ? 'selected' : ''); ?>>Auto-Generate Number</option>
                                <option value="Image" <?php echo e($field->data_type == 'Image' ? 'selected' : ''); ?>>Image</option>
                                <option value="File" <?php echo e($field->data_type == 'File' ? 'selected' : ''); ?>>File</option>
                                <option value="Dropdown" <?php echo e($field->data_type == 'Dropdown' ? 'selected' : ''); ?>>Dropdown</option>
                                <option value="MultiSelect" <?php echo e($field->data_type == 'MultiSelect' ? 'selected' : ''); ?>>Multi-Select</option>
                                <option value="Phone" <?php echo e($field->data_type == 'Phone' ? 'selected' : ''); ?>>Phone Number</option>
                                <option value="Email" <?php echo e($field->data_type == 'Email' ? 'selected' : ''); ?>>Email</option>
                                <option value="URL" <?php echo e($field->data_type == 'URL' ? 'selected' : ''); ?>>URL</option>
                                <option value="Currency" <?php echo e($field->data_type == 'Currency' ? 'selected' : ''); ?>>Currency</option>
                                <option value="Percentage" <?php echo e($field->data_type == 'Percentage' ? 'selected' : ''); ?>>Percentage</option>
                                <option value="Time" <?php echo e($field->data_type == 'Time' ? 'selected' : ''); ?>>Time</option>
                            </select>
                            <div class="remaining-fields" id="remainingFields">
                                Remaining Fields: <?php echo e($remainingFields ?? 34); ?>

                            </div>
                        </div>

                        <!-- Dynamic Fields Container -->
                        <div id="dynamicFieldsContainer"></div>

                        <!-- Access Permissions Section -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>Field Access Permissions
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleAccessSection()">
                                    <i class="fas fa-cog"></i> Configure Access
                                </button>
                            </div>
                            
                            <!-- Access Configuration Section -->
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
                                            $accessConfig = $field->access_config ?? [];
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
                                                       <?php echo e(($accessConfig[$role['id']]['permission'] ?? 'read_write') == 'read_write' ? 'checked' : ''); ?>>
                                            </td>
                                            
                                            <!-- Read Only -->
                                            <td class="text-center">
                                                <input type="radio" 
                                                       name="access[<?php echo e($role['id']); ?>][permission]" 
                                                       value="read_only"
                                                       id="ro_<?php echo e($role['id']); ?>"
                                                       class="form-check-input"
                                                       <?php echo e(($accessConfig[$role['id']]['permission'] ?? '') == 'read_only' ? 'checked' : ''); ?>>
                                            </td>
                                            
                                            <!-- Hide Field -->
                                            <td class="text-center">
                                                <input type="radio" 
                                                       name="access[<?php echo e($role['id']); ?>][permission]" 
                                                       value="hide"
                                                       id="hide_<?php echo e($role['id']); ?>"
                                                       class="form-check-input"
                                                       <?php echo e(($accessConfig[$role['id']]['permission'] ?? '') == 'hide' ? 'checked' : ''); ?>>
                                            </td>
                                            
                                            <!-- Admin Checkbox -->
                                            <td class="text-center">
                                                <input type="checkbox" 
                                                       name="access[<?php echo e($role['id']); ?>][is_admin]"
                                                       id="admin_<?php echo e($role['id']); ?>"
                                                       class="form-check-input"
                                                       value="1"
                                                       <?php echo e(($accessConfig[$role['id']]['is_admin'] ?? false) || $role['name'] == 'Admin' ? 'checked' : ''); ?>>
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

                        <!-- Is Mandatory -->
                        <div class="mt-4">
                            <div class="mb-4">
                                <label class="form-label">Is Mandatory</label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="mandatory" value="yes" <?php echo e($field->mandatory == 'yes' ? 'checked' : ''); ?>> Yes
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="mandatory" value="no" <?php echo e($field->mandatory == 'no' ? 'checked' : ''); ?>> No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="<?php echo e(route('field_customization.index')); ?>" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn btn-save text-dark">Update Field</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates for different data types (Hidden) -->
    <div style="display: none;">
        <!-- Number/Decimal Template -->
        <div id="numberTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Enter numeric value only')); ?>">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckbox" onchange="handlePIIChange(this)" <?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'checked' : ''); ?>> PII
                    </label>
                </div>
                
                <div id="piiWarning" class="privacy-warning" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: flex;' : 'display: none;'); ?>">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong><br>
                        Only users with access to protected data will be able to view the details.
                    </div>
                </div>

                <div id="nonPIIInfo" class="privacy-info" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: none;' : 'display: flex;'); ?>">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong><br>
                        Data will be stored without encryption.
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Input Format</label>
                <select name="input_format" class="form-select">
                    <option value="default" <?php echo e(($field->additional_config['input_format'] ?? '') == 'default' ? 'selected' : ''); ?>>Default Value</option>
                    <option value="range" <?php echo e(($field->additional_config['input_format'] ?? '') == 'range' ? 'selected' : ''); ?>>Range</option>
                    <option value="limit" <?php echo e(($field->additional_config['input_format'] ?? '') == 'limit' ? 'selected' : ''); ?>>Limit</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <input type="number" name="default_value" class="form-control" step="any" value="<?php echo e(old('default_value', $field->additional_config['default_value'] ?? '')); ?>">
            </div>
        </div>

        <!-- Text Template -->
        <div id="textTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Enter text value')); ?>">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxText" onchange="handlePIIChange(this)" <?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'checked' : ''); ?>> PII
                    </label>
                </div>
                
                <div id="piiWarningText" class="privacy-warning" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: flex;' : 'display: none;'); ?>">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong>
                    </div>
                </div>

                <div id="nonPIIInfoText" class="privacy-info" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: none;' : 'display: flex;'); ?>">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <input type="text" name="default_value" class="form-control" value="<?php echo e(old('default_value', $field->additional_config['default_value'] ?? '')); ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Character Limit</label>
                <input type="number" name="char_limit" class="form-control" value="<?php echo e(old('char_limit', $field->additional_config['char_limit'] ?? '')); ?>">
            </div>
        </div>

        <!-- DateTime Template -->
        <div id="dateTimeTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Select date and time')); ?>">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxDateTime" onchange="handlePIIChange(this)" <?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'checked' : ''); ?>> PII
                    </label>
                </div>
                
                <div id="piiWarningDateTime" class="privacy-warning" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: flex;' : 'display: none;'); ?>">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong>
                    </div>
                </div>

                <div id="nonPIIInfoDateTime" class="privacy-info" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: none;' : 'display: flex;'); ?>">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="default_date" class="form-control" placeholder="dd/MM/yyyy" value="<?php echo e(old('default_date', $field->additional_config['default_date'] ?? '')); ?>">
                    </div>
                    <div class="col-6">
                        <input type="text" name="default_time" class="form-control" placeholder="HH:MM" value="<?php echo e(old('default_time', $field->additional_config['default_time'] ?? '')); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- AutoNumber Template -->
        <div id="autoNumberTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Auto-generated number')); ?>">
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <label class="form-label">Prefix</label>
                    <input type="text" name="prefix" class="form-control" value="<?php echo e(old('prefix', $field->additional_config['prefix'] ?? '')); ?>">
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">Starting Number</label>
                    <input type="number" name="starting_number" class="form-control" value="<?php echo e(old('starting_number', $field->additional_config['starting_number'] ?? '1')); ?>">
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">Suffix</label>
                    <input type="text" name="suffix" class="form-control" value="<?php echo e(old('suffix', $field->additional_config['suffix'] ?? '')); ?>">
                </div>
            </div>

            <div class="info-note">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="add_to_existing" id="addToExisting" <?php echo e(($field->additional_config['add_to_existing'] ?? false) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="addToExisting">
                        Add this custom field to all the existing items
                    </label>
                </div>
            </div>
        </div>

        <!-- Dropdown Template -->
        <div id="dropdownTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Select an option')); ?>">
            </div>

            <div class="privacy-section">
                <label class="form-label">Data Privacy</label>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="privacy_pii" id="piiCheckboxDropdown" onchange="handlePIIChange(this)" <?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'checked' : ''); ?>> PII
                    </label>
                </div>
                
                <div id="piiWarningDropdown" class="privacy-warning" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: flex;' : 'display: none;'); ?>">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Sensitive data. Encrypt and store it.</strong>
                    </div>
                </div>

                <div id="nonPIIInfoDropdown" class="privacy-info" style="<?php echo e(($field->additional_config['privacy_pii'] ?? false) ? 'display: none;' : 'display: flex;'); ?>">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Not sensitive data. Store it without encryption.</strong>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Options</label>
                <textarea name="options" class="form-control" rows="4"><?php echo e(old('options', $field->additional_config['options'] ?? '')); ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <select name="default_value" class="form-select">
                    <option value="">Select default option</option>
                    <?php if(isset($field->additional_config['options'])): ?>
                        <?php $__currentLoopData = explode("\n", $field->additional_config['options']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(trim($option)): ?>
                                <option value="<?php echo e(trim($option)); ?>" <?php echo e(($field->additional_config['default_value'] ?? '') == trim($option) ? 'selected' : ''); ?>><?php echo e(trim($option)); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Boolean Template -->
        <div id="booleanTemplate">
            <div class="mb-4">
                <label class="form-label">Help Text</label>
                <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Select Yes/No')); ?>">
            </div>
            <div class="mb-4">
                <label class="form-label">Default Value</label>
                <div class="radio-group">
                    <label class="radio-item">
                        <input type="radio" name="default_value" value="1" <?php echo e(($field->additional_config['default_value'] ?? '') == '1' ? 'checked' : ''); ?>> True/Yes
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="default_value" value="0" <?php echo e(($field->additional_config['default_value'] ?? '') == '0' ? 'checked' : ''); ?>> False/No
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function handlePIIChange(checkbox) {
            const container = checkbox.closest('.privacy-section');
            const warning = container.querySelector('.privacy-warning');
            const info = container.querySelector('.privacy-info');
            
            if (checkbox.checked) {
                if (warning) warning.style.display = 'flex';
                if (info) info.style.display = 'none';
            } else {
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
            remainingFields.innerHTML = `Remaining Fields: ${fieldCounts[dataType] || '34'}`;

            container.innerHTML = '';

            switch(dataType) {
                case 'Number':
                case 'Decimal':
                case 'Currency':
                case 'Percentage':
                    container.innerHTML = document.getElementById('numberTemplate').innerHTML;
                    break;
                    
                case 'DateTime':
                case 'Date':
                case 'Time':
                    container.innerHTML = document.getElementById('dateTimeTemplate').innerHTML;
                    break;
                    
                case 'AutoNumber':
                    container.innerHTML = document.getElementById('autoNumberTemplate').innerHTML;
                    break;
                    
                case 'Text':
                case 'Phone':
                case 'Email':
                case 'URL':
                    container.innerHTML = document.getElementById('textTemplate').innerHTML;
                    break;
                    
                case 'Dropdown':
                case 'MultiSelect':
                    container.innerHTML = document.getElementById('dropdownTemplate').innerHTML;
                    break;
                    
                case 'Boolean':
                    container.innerHTML = document.getElementById('booleanTemplate').innerHTML;
                    break;
                    
                case 'Image':
                case 'File':
                    container.innerHTML = `
                                               <div class="mb-4">
                            <label class="form-label">Help Text</label>
                            <input type="text" name="help_text" class="form-control" value="<?php echo e(old('help_text', $field->additional_config['help_text'] ?? 'Upload file')); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Default Value</label>
                            <div>
                                <button type="button" class="btn btn-outline-secondary">Upload File</button>
                                <small class="text-muted ms-2">Supported: JPG, PNG, PDF (Max: 5MB)</small>
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
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/field_customization/edit.blade.php ENDPATH**/ ?>