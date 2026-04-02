<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Customization - Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
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
            padding: 20px 25px;
            border-radius: 16px 16px 0 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }
        .card-body {
            padding: 25px;
        }
        .btn-primary {
            background-color: #4f46e5;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 15px 12px;
            border-bottom-width: 1px;
        }
        .table td {
            padding: 15px 12px;
            vertical-align: middle;
            color: #1f2937;
        }
        .badge-active {
            background-color: #10b981;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge-inactive {
            background-color: #ef4444;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge-yes {
            background-color: #10b981;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        .badge-no {
            background-color: #9ca3af;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn-action {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            background: white;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-action:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        .btn-action i {
            margin-right: 4px;
            font-size: 0.8rem;
        }
        .btn-edit {
            color: #4f46e5;
            border-color: #c7d2fe;
        }
        .btn-edit:hover {
            background-color: #eef2ff;
        }
        .btn-delete {
            color: #ef4444;
            border-color: #fecaca;
        }
        .btn-delete:hover {
            background-color: #fee2e2;
        }
        .btn-access {
            color: #8b5cf6;
            border-color: #ddd6fe;
        }
        .btn-access:hover {
            background-color: #f5f3ff;
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6b7280;
        }
        .search-box i {
            font-size: 1rem;
        }
        .close-settings {
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .close-settings i {
            margin-right: 5px;
        }
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .status-toggle .form-check-input {
            width: 40px;
            height: 20px;
            margin-right: 5px;
        }
        .footer-links {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 20px;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .footer-links i {
            margin-right: 5px;
        }

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
            <div class="d-flex align-items-center gap-4">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <span>Search settings (/)</span>
                </div>
                <div class="close-settings">
                    <i class="fas fa-times"></i> Close Settings
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <?php echo $__env->make('layouts.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5>Items</h5>
                        <div class="subtitle">Jayadeepa</div>
                    </div>
                    <a href="<?php echo e(route('field_customization.create')); ?>?from=<?php echo e($category); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Field
                    </a>
                </div>
                <div class="card-body">
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                                            <!-- ===== 1. QUICK NAVIGATION ===== -->
                        <div class="setting-group">
                            <div class="setting-group-title">Quick Navigation</div>
                            <ul class="feature-list">
                                <li>
                                    <a href="<?php echo e(route('setting_handle.create')); ?>">
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
                    <!-- Custom Fields Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>FIELD NAME</th>
                                    <th>DATA TYPE</th>
                                    <th>MANDATORY</th>
                                    <th>SHOW IN ALL PDFs</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $config = $field->additional_config ?? [];
                                    $showInPdfs = $config['show_in_pdfs'] ?? false;
                                    $status = $field->status ?? 'active';
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($field->name); ?></td>
                                    <td>
                                        <?php switch($field->data_type):
                                            case ('Number'): ?> <span><i class="fas fa-hashtag me-1 text-primary"></i>Number</span> <?php break; ?>
                                            <?php case ('Decimal'): ?> <span><i class="fas fa-divide me-1 text-primary"></i>Decimal</span> <?php break; ?>
                                            <?php case ('Text'): ?> <span><i class="fas fa-font me-1 text-primary"></i>Text Box (Single Line)</span> <?php break; ?>
                                            <?php case ('Date'): ?> <span><i class="fas fa-calendar me-1 text-primary"></i>Date</span> <?php break; ?>
                                            <?php case ('DateTime'): ?> <span><i class="fas fa-calendar-alt me-1 text-primary"></i>Date and Time</span> <?php break; ?>
                                            <?php case ('Boolean'): ?> <span><i class="fas fa-check-square me-1 text-primary"></i>Boolean</span> <?php break; ?>
                                            <?php case ('AutoNumber'): ?> <span><i class="fas fa-sort-numeric-up me-1 text-primary"></i>Auto-Generate Number</span> <?php break; ?>
                                            <?php case ('Image'): ?> <span><i class="fas fa-image me-1 text-primary"></i>Image</span> <?php break; ?>
                                            <?php case ('File'): ?> <span><i class="fas fa-file me-1 text-primary"></i>File</span> <?php break; ?>
                                            <?php case ('Dropdown'): ?> <span><i class="fas fa-chevron-down me-1 text-primary"></i>Dropdown</span> <?php break; ?>
                                            <?php case ('MultiSelect'): ?> <span><i class="fas fa-list me-1 text-primary"></i>Multi-Select</span> <?php break; ?>
                                            <?php case ('Phone'): ?> <span><i class="fas fa-phone me-1 text-primary"></i>Phone Number</span> <?php break; ?>
                                            <?php case ('Email'): ?> <span><i class="fas fa-envelope me-1 text-primary"></i>Email</span> <?php break; ?>
                                            <?php case ('URL'): ?> <span><i class="fas fa-link me-1 text-primary"></i>URL</span> <?php break; ?>
                                            <?php case ('Currency'): ?> <span><i class="fas fa-dollar-sign me-1 text-primary"></i>Currency</span> <?php break; ?>
                                            <?php case ('Percentage'): ?> <span><i class="fas fa-percent me-1 text-primary"></i>Percentage</span> <?php break; ?>
                                            <?php case ('Time'): ?> <span><i class="fas fa-clock me-1 text-primary"></i>Time</span> <?php break; ?>
                                            <?php default: ?> <span><?php echo e($field->data_type); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <?php if($field->mandatory == 'yes'): ?>
                                            <span class="badge-yes">Yes</span>
                                        <?php else: ?>
                                            <span class="badge-no">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($showInPdfs): ?>
                                            <span class="badge-yes">Yes</span>
                                        <?php else: ?>
                                            <span class="badge-no">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($status == 'active'): ?>
                                            <span class="badge-active">Active</span>
                                        <?php else: ?>
                                            <span class="badge-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <!-- Edit Button -->
                                            <a href="<?php echo e(route('field_customization.edit', $field->id)); ?>" class="btn-action btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            
                                            <!-- Status Toggle (Active/Inactive) -->
                                            <form action="<?php echo e(route('field_customization.toggle-status', $field->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn-action <?php echo e($status == 'active' ? 'btn-warning' : 'btn-success'); ?>" title="<?php echo e($status == 'active' ? 'Mark as Inactive' : 'Mark as Active'); ?>">
                                                    <i class="fas <?php echo e($status == 'active' ? 'fa-pause-circle' : 'fa-play-circle'); ?>"></i> 
                                                    <?php echo e($status == 'active' ? 'Mark Inactive' : 'Mark Active'); ?>

                                                </button>
                                            </form>
                                            
                                            <!-- Show in All PDFs Toggle -->
                                            <form action="<?php echo e(route('field_customization.toggle-pdf', $field->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn-action" title="Toggle PDF visibility">
                                                    <i class="fas fa-file-pdf"></i> 
                                                    <?php echo e($showInPdfs ? 'Hide PDF' : 'Show PDF'); ?>

                                                </button>
                                            </form>
                                            
                                            <!-- Configure Access Button -->
                                            <a href="<?php echo e(route('field_customization.access', $field->id)); ?>" class="btn-action btn-access" title="Configure Access">
                                                <i class="fas fa-lock"></i> Access
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="<?php echo e(route('field_customization.destroy', $field->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this field?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-action btn-delete" title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No custom fields found. Click "Add New Field" to create one.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if(method_exists($fields, 'links')): ?>
                        <div class="mt-4">
                            <?php echo e($fields->links()); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Footer Links (Items, Inventory) -->
                    <div class="footer-links">
                        <span><i class="fas fa-box"></i> Items</span>
                        <span><i class="fas fa-warehouse"></i> Inventory</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/field_customization/index.blade.php ENDPATH**/ ?>