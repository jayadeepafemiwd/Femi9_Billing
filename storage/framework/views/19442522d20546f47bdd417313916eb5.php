

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
<div class="main-container">
        <?php echo $__env->make("layouts.nav", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
<div class="container-fluid py-4">

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-0 fw-bold">Items — Record Locking</h5>
                <small class="text-muted">Preferences › Items › Record Locking</small>
            </div>
            <a href="<?php echo e(route('lock_configuration.create')); ?>" class="btn btn-primary btn-sm">
                + New Lock Configuration
            </a>
        </div>
        <div class="card-body p-0">
            <?php if($configs->isEmpty()): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fs-1">🔓</i>
                    <p class="mt-2">No lock configurations yet. Click "New Lock Configuration" to add one.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>LOCK CONFIGURATION NAME</th>
                                <th>ACTIONS RESTRICTED</th>
                                <th>FIELDS</th>
                                <th>LOCK RECORDS FOR</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $configs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                
                                <td>
                                    <strong><?php echo e($config->name); ?></strong>
                                    <?php if($config->description): ?>
                                        <br><small class="text-muted"><?php echo e($config->description); ?></small>
                                    <?php endif; ?>
                                </td>

                                
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($config->action_type_label); ?></span>
                                    <?php if($config->selected_actions && count($config->selected_actions)): ?>
                                        <br>
                                        <?php $__currentLoopData = $config->selected_actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-warning text-dark mt-1"><?php echo e($action); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($config->field_type_label); ?></span>
                                    <?php if($config->selected_fields && count($config->selected_fields)): ?>
                                        <br>
                                        <?php $__currentLoopData = array_slice($config->selected_fields, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-info text-dark mt-1">
                                                <?php echo e($availableFields[$field] ?? $field); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(count($config->selected_fields) > 3): ?>
                                            <span class="badge bg-light text-dark mt-1">
                                                +<?php echo e(count($config->selected_fields) - 3); ?> more
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($config->lock_for_type_label); ?></span>
                                    <?php if($config->roles && count($config->roles)): ?>
                                        <br>
                                        <?php $__currentLoopData = $config->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-light text-dark mt-1 border">
                                                <?php echo e($availableRoles[$role] ?? $role); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>

                                
                                <td>
                                    <form action="<?php echo e(route('lock_configuration.toggle', $config->id)); ?>"
                                          method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit"
                                            class="badge border-0 <?php echo e($config->status === 'active' ? 'bg-success' : 'bg-danger'); ?>"
                                            style="cursor:pointer; font-size:0.8rem; padding:0.4em 0.8em;">
                                            <?php echo e(ucfirst($config->status)); ?>

                                        </button>
                                    </form>
                                </td>

                                
                                <td>
                                    <a href="<?php echo e(route('lock_configuration.edit', $config->id)); ?>"
                                       class="btn btn-sm btn-outline-primary me-1">Edit</a>

                                    <form action="<?php echo e(route('lock_configuration.destroy', $config->id)); ?>"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this lock configuration?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/lock_configuration/index.blade.php ENDPATH**/ ?>