

<?php $__env->startSection('content'); ?>
 
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
<!-- ===== 1. QUICK NAVIGATION ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Quick Navigation</div>
                            <ul class="feature-list">
                                <li>
                                    <a href="<?php echo e(route('setting_handle.create')); ?>" class="active">
                                        <i class="fas fa-cog"></i> General
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('field_customization.index')); ?>">
                                        <i class="fas fa-pencil-alt"></i> Field Customization
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('lock_configuration.create')); ?>">
                                        <i class="fas fa-lock"></i> Record Locking
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('custom.buttons')); ?>">
                                        <i class="fas fa-buttons"></i> Custom Buttons
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('related.lists')); ?>">
                                        <i class="fas fa-list"></i> Related Lists
                                    </a>
                                </li>
                            </ul>
                        </div>
<div class="container-fluid py-4">
    <div class="card shadow-sm" style="max-width: 780px; margin: auto;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
            <h5 class="mb-0 fw-bold">New Lock Configuration</h5>
            <a href="<?php echo e(route('lock_configuration.index')); ?>" class="text-muted text-decoration-none">
                Close Settings ✕
            </a>
        </div>

        <div class="card-body py-4 px-4">

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('lock_configuration.store')); ?>" method="POST" id="lockForm">
                <?php echo csrf_field(); ?>

                
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Lock Configuration Name*
                    </label>


                    <div class="col-sm-8">
                        <input type="text"
                               name="name"
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('name')); ?>"
                               placeholder="e.g. phone_number">
                        <?php $__errorArgs = ['name'];
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
                </div>

                
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label">Description</label>
                    <div class="col-sm-8">
                        <textarea name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="testing"><?php echo e(old('description')); ?></textarea>
                    </div>
                </div>

                <hr class="my-3">

                
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Allow or Restrict Actions*
                        <span class="text-muted fw-normal" style="font-size:0.8rem;" title="Control what actions are restricted">ⓘ</span>
                    </label>
                    <div class="col-sm-8">
                        
                        <select name="action_type" id="actionTypeSelect" class="form-select mb-2">
                            <option value="restrict_all"      <?php echo e(old('action_type') == 'restrict_all'      ? 'selected' : ''); ?>>Restrict All Actions</option>
                            <option value="restrict_selected" <?php echo e(old('action_type', 'restrict_selected') == 'restrict_selected' ? 'selected' : ''); ?>>Restrict Selected Actions</option>
                            <option value="allow_selected"    <?php echo e(old('action_type') == 'allow_selected'    ? 'selected' : ''); ?>>Allow Selected Actions</option>
                        </select>

                        
                        <div id="actionsSubSection">
                            <select id="actionsDropdownTrigger" class="form-select mb-1" style="display:none">
                                
                            </select>
                            <div id="actionsCheckboxList" class="border rounded p-2 bg-white">
                                <div class="text-muted small px-1 pb-1" id="actionsLabel">Select restricted actions</div>
                                <div class="form-check py-1 border-bottom">
                                    <input class="form-check-input" type="checkbox"
                                           name="selected_actions[]" value="Edit"
                                           id="actionEdit"
                                           <?php echo e(in_array('Edit', old('selected_actions', [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="actionEdit">Edit</label>
                                </div>
                                <div class="form-check py-1">
                                    <input class="form-check-input" type="checkbox"
                                           name="selected_actions[]" value="Delete"
                                           id="actionDelete"
                                           <?php echo e(in_array('Delete', old('selected_actions', [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="actionDelete">Delete</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Allow or Restrict Fields*
                        <span class="text-muted fw-normal" style="font-size:0.8rem;" title="Control which fields are editable">ⓘ</span>
                    </label>
                    <div class="col-sm-8">
                        
                        <select name="field_type" id="fieldTypeSelect" class="form-select mb-2">
                            <option value="restrict_all"      <?php echo e(old('field_type') == 'restrict_all'      ? 'selected' : ''); ?>>Restrict All Fields</option>
                            <option value="restrict_selected" <?php echo e(old('field_type') == 'restrict_selected' ? 'selected' : ''); ?>>Restrict Selected Fields</option>
                            <option value="allow_selected"    <?php echo e(old('field_type', 'allow_selected') == 'allow_selected' ? 'selected' : ''); ?>>Allow Selected Fields</option>
                        </select>

                        
                        <div id="fieldsSubSection">
                            <div class="border rounded bg-white">
                                <div class="p-2 border-bottom">
                                    <input type="text" id="fieldSearch"
                                           class="form-control form-control-sm"
                                           placeholder="🔍 Search fields...">
                                </div>
                                <div style="max-height: 220px; overflow-y: auto;" id="fieldsList">
                                    <div class="text-muted small px-2 py-1" id="fieldsLabel">Select allowed fields</div>
                                    <?php $__currentLoopData = $availableFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check px-3 py-1 field-item" data-label="<?php echo e(strtolower($label)); ?>">
                                            <input class="form-check-input" type="checkbox"
                                                   name="selected_fields[]"
                                                   value="<?php echo e($key); ?>"
                                                   id="field_<?php echo e($key); ?>"
                                                   <?php echo e(in_array($key, old('selected_fields', [])) ? 'checked' : ''); ?>>
                                            <label class="form-check-label small" for="field_<?php echo e($key); ?>">
                                                <?php echo e($label); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">Lock Records For*</label>
                    <div class="col-sm-8">
                        <select name="lock_for_type" id="lockForTypeSelect" class="form-select mb-2">
                            <option value="all_roles"        <?php echo e(old('lock_for_type', 'all_roles') == 'all_roles'        ? 'selected' : ''); ?>>All Roles</option>
                            <option value="all_roles_except" <?php echo e(old('lock_for_type') == 'all_roles_except'              ? 'selected' : ''); ?>>All Roles Except</option>
                            <option value="selected_roles"   <?php echo e(old('lock_for_type') == 'selected_roles'                ? 'selected' : ''); ?>>Selected Roles</option>
                        </select>

                        
                        <div id="rolesSubSection" style="display:none;">
                            <div class="border rounded bg-white">
                                <div class="p-2 border-bottom">
                                    <small class="text-muted" id="rolesLabel">Select Roles</small>
                                </div>
                                <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check px-3 py-2 border-bottom">
                                        <input class="form-check-input" type="checkbox"
                                               name="roles[]"
                                               value="<?php echo e($roleKey); ?>"
                                               id="role_<?php echo e($roleKey); ?>"
                                               <?php echo e(in_array($roleKey, old('roles', [])) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="role_<?php echo e($roleKey); ?>">
                                            <?php echo e($roleLabel); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                    <a href="<?php echo e(route('lock_configuration.index')); ?>"
                       class="btn btn-outline-secondary px-4">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. ACTION TYPE DROPDOWN ────────────────────────────────────────────
    const actionTypeSelect    = document.getElementById('actionTypeSelect');
    const actionsSubSection   = document.getElementById('actionsSubSection');
    const actionsLabel        = document.getElementById('actionsLabel');
    const actionCheckboxes    = document.querySelectorAll('#actionsCheckboxList input[type="checkbox"]');

    function updateActionsUI(value) {
        if (value === 'restrict_all') {
            // Hide checkboxes - no need to select
            actionsSubSection.style.display = 'none';
            actionCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
        } else {
            actionsSubSection.style.display = 'block';
            actionCheckboxes.forEach(cb => { cb.disabled = false; });
            // Update label text to match selection
            actionsLabel.textContent = value === 'allow_selected'
                ? 'Select allowed actions'
                : 'Select restricted actions';
        }
    }

    actionTypeSelect.addEventListener('change', function () {
        updateActionsUI(this.value);
    });

    // Set initial state on page load
    updateActionsUI(actionTypeSelect.value);


    // ── 2. FIELD TYPE DROPDOWN ─────────────────────────────────────────────
    const fieldTypeSelect   = document.getElementById('fieldTypeSelect');
    const fieldsSubSection  = document.getElementById('fieldsSubSection');
    const fieldsLabel       = document.getElementById('fieldsLabel');
    const fieldCheckboxes   = document.querySelectorAll('#fieldsList input[type="checkbox"]');

    function updateFieldsUI(value) {
        if (value === 'restrict_all') {
            // Hide field picker when all fields restricted
            fieldsSubSection.style.display = 'none';
            fieldCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
        } else {
            fieldsSubSection.style.display = 'block';
            fieldCheckboxes.forEach(cb => { cb.disabled = false; });
            fieldsLabel.textContent = value === 'allow_selected'
                ? 'Select allowed fields'
                : 'Select restricted fields';
        }
    }

    fieldTypeSelect.addEventListener('change', function () {
        updateFieldsUI(this.value);
    });

    // Set initial state
    updateFieldsUI(fieldTypeSelect.value);


    // ── 3. LOCK RECORDS FOR DROPDOWN ──────────────────────────────────────
    const lockForTypeSelect = document.getElementById('lockForTypeSelect');
    const rolesSubSection   = document.getElementById('rolesSubSection');
    const rolesLabel        = document.getElementById('rolesLabel');
    const roleCheckboxes    = document.querySelectorAll('#rolesSubSection input[type="checkbox"]');

    function updateRolesUI(value) {
        if (value === 'all_roles') {
            rolesSubSection.style.display = 'none';
            roleCheckboxes.forEach(cb => { cb.checked = false; });
        } else {
            rolesSubSection.style.display = 'block';
            rolesLabel.textContent = value === 'all_roles_except'
                ? 'Except these roles'
                : 'Select Roles';
        }
    }

    lockForTypeSelect.addEventListener('change', function () {
        updateRolesUI(this.value);
    });

    // Set initial state
    updateRolesUI(lockForTypeSelect.value);


    // ── 4. FIELD SEARCH ───────────────────────────────────────────────────
    document.getElementById('fieldSearch').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.field-item').forEach(function (item) {
            const label = item.getAttribute('data-label');
            item.style.display = label.includes(term) ? 'block' : 'none';
        });
    });

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/lock_configuration/create.blade.php ENDPATH**/ ?>