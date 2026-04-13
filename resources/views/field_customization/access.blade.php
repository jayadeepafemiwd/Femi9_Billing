<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Access Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            padding: 20px;
        }
        .main-container {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
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
            padding: 20px 30px;
            border-radius: 16px 16px 0 0 !important;
        }
        .card-body {
            padding: 30px;
        }
        .field-info {
            background: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 25px;
        }
        .field-info-item {
            display: inline-block;
            margin-right: 40px;
        }
        .field-info-label {
            color: #6b7280;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .field-info-value {
            font-weight: 600;
            color: #111827;
            font-size: 1.1rem;
        }
        .permission-table {
            width: 100%;
            border-collapse: collapse;
        }
        .permission-table th {
            background: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px;
            border-bottom: 2px solid #e5e7eb;
        }
        .permission-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .role-name {
            font-weight: 600;
            color: #111827;
        }
        .permission-option {
            display: flex;
            justify-content: center;
        }
        .permission-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
        }
        .permission-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
        }
        .badge-read-write {
            background: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .badge-read-only {
            background: #f59e0b;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .badge-hide {
            background: #ef4444;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .note-box {
            background: #fef9c3;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 16px;
            margin: 25px 0;
            color: #854d0e;
            font-size: 0.95rem;
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
        <div class="sidebar">
            <div class="settings-sidebar">
                <h6>ORGANIZATION SETTINGS</h6>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#">Organization</a>
                    <a class="nav-link" href="#">Users & Roles</a>
                    <a class="nav-link" href="#">Taxes & Compliance</a>
                    <a class="nav-link" href="#">Setup & Configurations</a>
                    <a class="nav-link active" href="#">Customization</a>
                    <a class="nav-link" href="#">Automation</a>
                </nav>

                <div class="module-settings">
                    <h6>MODULE SETTINGS</h6>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#">General</a>
                        <a class="nav-link" href="#">Customers and Vendors</a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>READ / Write Access
                    </h5>
                    <a href="{{ route('field_customization.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Fields
                    </a>
                </div>
                <div class="card-body">
                    
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

                    <!-- Field Info -->
                    <div class="field-info">
                        <div class="field-info-item">
                            <span class="field-info-label">Field Name</span>
                            <div class="field-info-value">{{ $field->additional_fields }}</div>
                        </div>
                        <div class="field-info-item">
                            <span class="field-info-label">Data Type</span>
                            <div class="field-info-value">{{ $field->data_type }}</div>
                        </div>
                        <div class="field-info-item">
                            <span class="field-info-label">Mandatory</span>
                            <div class="field-info-value">{{ ucfirst($field->mandatory) }}</div>
                        </div>
                    </div>

                    <form action="{{ route('field_customization.updateAccess', $field->id) }}" method="POST">
                        @csrf

                        <h6 class="mb-3 fw-bold">ROLE-BASED PERMISSIONS</h6>
                        
                        <table class="table table-bordered">
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
                                @foreach($roles as $index => $role)
                                @php
                                    $roleAccess = collect($accessSettings)->firstWhere('role_id', $role['id']);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $role['name'] }}</td>
                                    
                                    <!-- Read & Write -->
                                    <td class="text-center">
                                        <input type="radio" 
                                               name="access[{{ $role['id'] }}][permission]" 
                                               value="read_write"
                                               class="form-check-input"
                                               {{ old("access.{$role['id']}.permission", $roleAccess['permission'] ?? '') == 'read_write' ? 'checked' : '' }}
                                               {{ !$roleAccess && $loop->first ? 'checked' : '' }}>
                                    </td>
                                    
                                    <!-- Read Only -->
                                    <td class="text-center">
                                        <input type="radio" 
                                               name="access[{{ $role['id'] }}][permission]" 
                                               value="read_only"
                                               class="form-check-input"
                                               {{ old("access.{$role['id']}.permission", $roleAccess['permission'] ?? '') == 'read_only' ? 'checked' : '' }}>
                                    </td>
                                    
                                    <!-- Hide Field -->
                                    <td class="text-center">
                                        <input type="radio" 
                                               name="access[{{ $role['id'] }}][permission]" 
                                               value="hide"
                                               class="form-check-input"
                                               {{ old("access.{$role['id']}.permission", $roleAccess['permission'] ?? '') == 'hide' ? 'checked' : '' }}>
                                    </td>
                                    
                                    <!-- Admin Checkbox -->
                                    <td class="text-center">
                                        <input type="checkbox" 
                                               name="access[{{ $role['id'] }}][is_admin]"
                                               class="form-check-input"
                                               value="1"
                                               {{ old("access.{$role['id']}.is_admin", $roleAccess['is_admin'] ?? false) ? 'checked' : '' }}>
                                    </td>
                                    
                                    <!-- Hidden fields -->
                                    <input type="hidden" name="access[{{ $role['id'] }}][role_id]" value="{{ $role['id'] }}">
                                    <input type="hidden" name="access[{{ $role['id'] }}][role_name]" value="{{ $role['name'] }}">
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Note -->
                        <div class="note-box">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> If you select Read Only or Hide Field for a role, 
                            users under that role will not be able to update the <strong>{{ $field->additional_fields }}</strong> field.
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('field_customization.index') }}" class="btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-save text-white">
                                <i class="fas fa-save me-1"></i> Save Permissions
                            </button>
                        </div>
                    </form>

                    <!-- Existing Fields Table -->
                    <div class="mt-5">
                        <h6 class="mb-3 fw-bold">EXISTING CUSTOM FIELDS</h6>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Module</th>
                                    <th>Field Name</th>
                                    <th>Data Type</th>
                                    <th>Required</th>
                                    <th>Unique</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Items</td>
                                    <td>{{ $field->additional_fields }}</td>
                                    <td>{{ $field->data_type }}</td>
                                    <td>{{ ucfirst($field->mandatory) }}</td>
                                    <td>No</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td>Category Name</td>
                                    <td>Text Box (Single Line)</td>
                                    <td>No</td>
                                    <td>No</td>
                                    <td><span class="badge bg-secondary">Inactive</span></td>
                                </tr>
                                <tr>
                                    <td>Product</td>
                                    <td>MRP</td>
                                    <td>Decimal</td>
                                    <td>No</td>
                                    <td>No</td>
                                    <td><span class="badge bg-secondary">Inactive</span></td>
                                </tr>
                                <tr>
                                    <td>Product</td>
                                    <td>Alias Name</td>
                                    <td>Text Box (Single Line)</td>
                                    <td>No</td>
                                    <td>No</td>
                                    <td><span class="badge bg-secondary">Inactive</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>