

<?php $__env->startSection('title', 'Locations | Settings'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --zoho-blue: #0073E6;
        --zoho-blue-hover: #0060C0;
        --zoho-red: #E74C3C;
        --zoho-green: #27AE60;
        --zoho-gray-bg: #F4F5F7;
        --zoho-border: #DDE1E7;
        --zoho-text: #1A1A2E;
        --zoho-text-muted: #6B7280;
        --zoho-sidebar-bg: #FFFFFF;
        --zoho-white: #FFFFFF;
        --zoho-input-border: #C9D0D9;
        --zoho-label-red: #E74C3C;
        --zoho-row-hover: #F0F4FF;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 13px;
        color: var(--zoho-text);
        background: var(--zoho-gray-bg);
        margin: 0;
    }

    .settings-wrapper {
        display: flex;
        height: 100vh;
        overflow: hidden;
        background: var(--zoho-white);
    }

    .settings-sidebar {
        width: 260px;
        min-width: 260px;
        background: var(--zoho-sidebar-bg);
        border-right: 1px solid var(--zoho-border);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .sidebar-back-btn {
        width: 28px; height: 28px;
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        background: var(--zoho-white);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted);
        font-size: 14px;
        text-decoration: none;
    }

    .sidebar-title    { font-size: 14px; font-weight: 600; }
    .sidebar-subtitle { font-size: 11px; color: var(--zoho-text-muted); }

    .sidebar-search {
        padding: 10px 12px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .sidebar-search input {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px;
        font-size: 12px;
        background: var(--zoho-gray-bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
        outline: none;
    }

    .sidebar-section-label {
        padding: 10px 16px 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar-nav-item {
        display: block;
        padding: 8px 16px;
        font-size: 13px;
        color: var(--zoho-text);
        text-decoration: none;
        cursor: pointer;
        transition: background 0.15s;
    }

    .sidebar-nav-item:hover { background: var(--zoho-row-hover); }

    .sidebar-nav-item.active {
        background: #E8F0FE;
        color: var(--zoho-blue);
        font-weight: 500;
        position: relative;
    }

    .sidebar-nav-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--zoho-blue);
        border-radius: 0 2px 2px 0;
    }

    .badge-new {
        display: inline-block;
        background: var(--zoho-blue);
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        margin-left: 6px;
        vertical-align: middle;
    }

    .settings-content {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .content-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 24px;
        border-bottom: 1px solid var(--zoho-border);
        background: var(--zoho-white);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .close-settings-btn {
        background: none;
        border: none;
        font-size: 13px;
        color: var(--zoho-text-muted);
        cursor: pointer;
        display: flex; align-items: center; gap: 4px;
    }

    .locations-page {
        padding: 24px 32px;
        max-width: 900px;
    }

    .page-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--zoho-text);
    }

    .locations-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .btn-primary {
        background: var(--zoho-blue);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
    }

    .btn-primary:hover { background: var(--zoho-blue-hover); }

    .btn-secondary {
        background: var(--zoho-white);
        color: var(--zoho-text);
        border: 1px solid var(--zoho-border);
        padding: 7px 14px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-secondary:hover { background: var(--zoho-gray-bg); }

    .btn-danger {
        background: var(--zoho-red);
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-link {
        background: none;
        border: none;
        color: var(--zoho-blue);
        font-size: 13px;
        cursor: pointer;
        padding: 0;
        text-decoration: none;
    }

    .btn-link:hover { text-decoration: underline; }

    .locations-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--zoho-white);
        border: 1px solid var(--zoho-border);
        border-radius: 6px;
        overflow: hidden;
    }

    .locations-table th {
        background: var(--zoho-gray-bg);
        padding: 10px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .locations-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--zoho-border);
        font-size: 13px;
        vertical-align: middle;
    }

    .locations-table tr:last-child td { border-bottom: none; }
    .locations-table tr:hover td { background: var(--zoho-row-hover); }

    .location-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .type-business  { background: #E8F5E9; color: #2E7D32; }
    .type-warehouse { background: #E3F2FD; color: #1565C0; }

    .location-status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .dot-active   { background: var(--zoho-green); }
    .dot-inactive { background: #ccc; }

    .action-icons { display: flex; gap: 8px; align-items: center; }

    .icon-btn {
        width: 28px; height: 28px;
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        background: var(--zoho-white);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted);
        font-size: 13px;
        transition: all 0.15s;
    }

    .icon-btn:hover        { background: var(--zoho-row-hover); border-color: var(--zoho-blue); color: var(--zoho-blue); }
    .icon-btn.delete:hover { background: #FEE; border-color: var(--zoho-red); color: var(--zoho-red); }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
        align-items: flex-start;
        justify-content: flex-end;
    }

    .modal-overlay.active { display: flex; }

    .side-drawer {
        width: 520px;
        height: 100vh;
        background: var(--zoho-white);
        overflow-y: auto;
        box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        animation: slideIn 0.25s ease;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); }
        to   { transform: translateX(0);    }
    }

    .drawer-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--zoho-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        background: var(--zoho-white);
        z-index: 5;
    }

    .drawer-title { font-size: 16px; font-weight: 600; }

    .drawer-close {
        width: 28px; height: 28px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 18px;
        color: var(--zoho-text-muted);
        display: flex; align-items: center; justify-content: center;
        border-radius: 4px;
    }

    .drawer-close:hover { background: var(--zoho-gray-bg); }
    .drawer-body { padding: 20px 24px; }

    .location-type-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .type-card {
        border: 2px solid var(--zoho-border);
        border-radius: 6px;
        padding: 14px;
        cursor: pointer;
        transition: all 0.15s;
        position: relative;
    }

    .type-card:hover    { border-color: var(--zoho-blue); background: #F0F4FF; }
    .type-card.selected { border-color: var(--zoho-blue); background: #EEF4FF; }

    .type-card input[type="radio"] {
        position: absolute;
        top: 12px; left: 12px;
        accent-color: var(--zoho-blue);
    }

    .type-card-content { padding-left: 22px; }
    .type-card-title   { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    .type-card-desc    { font-size: 11px; color: var(--zoho-text-muted); line-height: 1.5; }

    .form-section-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--zoho-text);
        margin: 20px 0 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .form-group {
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: flex-start;
        gap: 10px;
    }

    .form-label {
        font-size: 12px;
        color: var(--zoho-text);
        padding-top: 7px;
        text-align: right;
    }

    .form-label.required::after {
        content: '*';
        color: var(--zoho-label-red);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.15s;
        color: var(--zoho-text);
        background: var(--zoho-white);
    }

    .form-control:focus { border-color: var(--zoho-blue); box-shadow: 0 0 0 2px rgba(0,115,230,0.1); }

    .form-control-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 28px;
        cursor: pointer;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--zoho-text);
        cursor: pointer;
        padding-top: 4px;
    }

    .checkbox-label input { accent-color: var(--zoho-blue); }
    .address-grid { display: flex; flex-direction: column; gap: 8px; }

    /* ── Logo UI ─────────────────────────────── */
    .logo-ui-wrap {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    /* Thumbnail box */
    .logo-thumb {
        width: 80px;
        height: 60px;
        border: 1px solid var(--zoho-border);
        border-radius: 6px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }

    /* Org logo (default background, always present) */
    .logo-thumb .logo-org-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
        opacity: 1;
        transition: opacity 0.2s;
    }

    /* Location-specific logo (layered on top when set) */
    .logo-thumb .logo-location-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
        background: #fff;
    }

    /* Show location logo on top when it has src */
    .logo-thumb.has-location-logo .logo-location-img { display: block; }
    .logo-thumb.has-location-logo .logo-org-img      { opacity: 0.15; }

    /* Right side */
    .logo-ui-right { flex: 1; }

    /* Label above button */
    .logo-current-label {
        font-size: 11px;
        color: var(--zoho-text-muted);
        margin-bottom: 7px;
        line-height: 1.4;
    }

    /* "Using Organization Logo" tag */
    .logo-org-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #EEF4FF;
        border: 1px solid #C3D8FF;
        color: var(--zoho-blue);
        font-size: 11px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    /* Upload button */
    .btn-upload-logo {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 13px;
        border: 1px dashed var(--zoho-blue);
        border-radius: 4px;
        background: #F0F6FF;
        color: var(--zoho-blue);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }

    .btn-upload-logo:hover { background: #dbeafe; border-style: solid; }

    /* File info row (shown after upload) */
    .logo-file-row {
        display: none;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        font-size: 11px;
        color: var(--zoho-text-muted);
    }

    .logo-file-row.show { display: flex; }

    .logo-remove-btn {
        background: none;
        border: none;
        color: var(--zoho-red);
        font-size: 11px;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 2px;
    }

    /* Trans table */
    .trans-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--zoho-border);
        overflow: hidden;
        font-size: 12px;
    }

    .trans-table th {
        background: var(--zoho-gray-bg);
        padding: 8px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
        font-size: 11px;
    }

    .trans-table td {
        padding: 8px 12px;
        border-bottom: 1px solid var(--zoho-border);
        vertical-align: middle;
    }

    .trans-table tr:last-child td { border-bottom: none; }

    .trans-table input {
        width: 100%;
        padding: 5px 8px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 3px;
        font-size: 12px;
        outline: none;
    }

    .trans-table input:focus { border-color: var(--zoho-blue); }

    .preview-badge {
        background: var(--zoho-gray-bg);
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        color: var(--zoho-text-muted);
        font-family: monospace;
    }

    .access-box { border: 1px solid var(--zoho-border); border-radius: 4px; overflow: hidden; }

    .access-header {
        padding: 10px 14px;
        background: var(--zoho-gray-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .access-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--zoho-blue); display: inline-block; margin-right: 6px; }

    .access-users-table { width: 100%; border-collapse: collapse; }

    .access-users-table th {
        padding: 8px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
    }

    .access-users-table td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--zoho-border);
        font-size: 12px;
    }

    .user-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: var(--zoho-blue);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
    }

    .drawer-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: var(--zoho-white);
    }

    .confirm-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .confirm-modal.active { display: flex; }

    .confirm-box {
        background: var(--zoho-white);
        border-radius: 8px;
        padding: 24px;
        width: 380px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .confirm-title   { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
    .confirm-text    { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 20px; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }

    .trans-series-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 3000;
        align-items: center;
        justify-content: center;
    }

    .trans-series-modal.active { display: flex; }

    .trans-series-box {
        background: var(--zoho-white);
        border-radius: 8px;
        width: 680px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .trans-series-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--zoho-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: var(--zoho-white);
    }

    .trans-series-title { font-size: 15px; font-weight: 600; }
    .trans-series-body  { padding: 20px 24px; }

    .series-name-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .series-name-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--zoho-label-red);
        white-space: nowrap;
        min-width: 100px;
    }

    .trans-series-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .alert {
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 16px;
        display: none;
        align-items: center;
        gap: 8px;
    }

    .alert.show    { display: flex; }
    .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .alert-error   { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--zoho-text-muted); }
    .empty-icon  { font-size: 48px; margin-bottom: 12px; }
    .empty-title { font-size: 15px; font-weight: 600; color: var(--zoho-text); margin-bottom: 6px; }
    .empty-text  { font-size: 13px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="settings-wrapper">

    
    <div class="settings-sidebar">
        <div class="sidebar-header">
            <a href="<?php echo e(url('/')); ?>" class="sidebar-back-btn">‹</a>
            <div>
                <div class="sidebar-title">All Settings</div>
                <div class="sidebar-subtitle"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
            </div>
        </div>

        <div class="sidebar-search">
            <input type="text" placeholder="Search settings ( / )">
        </div>

        <div class="sidebar-section-label">Organization Settings</div>

        <div style="padding: 4px 0;">
            <a class="sidebar-nav-item" href="#">▾ Organization</a>
            <div style="padding-left: 12px;">
                <a class="sidebar-nav-item" href="#">Profile</a>
                <a class="sidebar-nav-item" href="#">Branding</a>
                <a class="sidebar-nav-item active" href="#">
                    Locations <span class="badge-new">NEW</span>
                </a>
                <a class="sidebar-nav-item" href="#">Manage Subscription</a>
            </div>
        </div>

        <a class="sidebar-nav-item" href="#">▸ Users &amp; Roles</a>
        <a class="sidebar-nav-item" href="#">▸ Taxes &amp; Compliance</a>
        <a class="sidebar-nav-item" href="#">▸ Setup &amp; Configurations</a>
        <a class="sidebar-nav-item" href="#">▸ Customization</a>
        <a class="sidebar-nav-item" href="#">▸ Automation</a>

        <div class="sidebar-section-label" style="margin-top:8px;">Module Settings</div>
        <a class="sidebar-nav-item" href="#">▸ Items</a>
        <a class="sidebar-nav-item" href="#">▸ Sales</a>
        <a class="sidebar-nav-item" href="#">▸ Purchases</a>
        <a class="sidebar-nav-item" href="#">▸ Inventory</a>
    </div>

    
    <div class="settings-content">
        <div class="content-topbar">
            <span style="font-size:13px; color:var(--zoho-text-muted);">Search settings</span>
            <button class="close-settings-btn" onclick="window.history.back()">Close Settings ✕</button>
        </div>

        <div class="locations-page">
            <h2 class="page-title">Locations</h2>

            <?php if(session('success')): ?>
                <div class="alert alert-success show">✓ <?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-error show">✕ <?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <div id="js-alert" class="alert"></div>

            <div class="locations-toolbar">
                <div style="font-size:13px; color:var(--zoho-text-muted);">
                    Manage your business and warehouse locations
                </div>
                <button class="btn-primary" onclick="openAddDrawer()">+ New Location</button>
            </div>

            <table class="locations-table">
                <thead>
                    <tr>
                        <th>Location Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="locations-tbody">
                    <?php $__empty_1 = true; $__currentLoopData = $locations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="row-<?php echo e($location->id); ?>">
                        <td>
                            <button class="btn-link" onclick="openEditDrawer(<?php echo e($location->id); ?>)">
                                <?php echo e($location->location_name); ?>

                            </button>
                        </td>
                        <td>
                            <span class="location-type-badge <?php echo e($location->location_type === 'business' ? 'type-business' : 'type-warehouse'); ?>">
                                <?php echo e($location->location_type === 'business' ? '🏢 Business' : '🏭 Warehouse'); ?>

                            </span>
                        </td>
                        <td style="color:var(--zoho-text-muted); font-size:12px;">
                            <?php if($location->address_details): ?>
                                <?php echo e($location->address_details['city'] ?? ''); ?>

                                <?php if($location->address_details['state'] ?? ''): ?>
                                    , <?php echo e($location->address_details['state']); ?>

                                <?php endif; ?>
                            <?php else: ?> —
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="location-status-dot dot-active"></span> Active
                        </td>
                        <td>
                            <div class="action-icons">
                                <button class="icon-btn" onclick="openEditDrawer(<?php echo e($location->id); ?>)" title="Edit">✎</button>
                                <button class="icon-btn delete" onclick="openDeleteConfirm(<?php echo e($location->id); ?>, '<?php echo e($location->location_name); ?>')" title="Delete">🗑</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">📍</div>
                                <div class="empty-title">No Locations Added</div>
                                <div class="empty-text">Add your first business or warehouse location</div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal-overlay" id="location-drawer-overlay" onclick="closeDrawerOnOverlay(event)">
    <div class="side-drawer">

        <div class="drawer-header">
            <span class="drawer-title" id="drawer-title">Add Location</span>
            <button class="drawer-close" onclick="closeDrawer()">✕</button>
        </div>

        <div class="drawer-body">

            
            <div class="form-section-title">Location Type</div>
            <div class="location-type-cards">
                <div class="type-card selected" id="card-business" onclick="selectLocationType('business')">
                    <input type="radio" name="location_type" value="business" id="type-business" checked>
                    <div class="type-card-content">
                        <div class="type-card-title">Business Location</div>
                        <div class="type-card-desc">Represents your organization's operational location.</div>
                    </div>
                </div>
                <div class="type-card" id="card-warehouse" onclick="selectLocationType('warehouse')">
                    <input type="radio" name="location_type" value="warehouse" id="type-warehouse">
                    <div class="type-card-content">
                        <div class="type-card-title">Warehouse Only Location</div>
                        <div class="type-card-desc">Refers to where your items are stored.</div>
                    </div>
                </div>
            </div>

            
            <div id="logo-section">
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <div>
                        <div class="logo-ui-wrap">

                            
                            <div class="logo-thumb" id="logo-thumb">

                                
                                <img
                                    class="logo-org-img"
                                    id="logo-org-img"
                                    src="<?php echo e($orgLogoUrl ?? asset('images/org_logo.png')); ?>"
                                    alt="Organization Logo"
                                    onerror="this.style.display='none'; document.getElementById('logo-org-fallback').style.display='flex';"
                                >
                                
                                <span id="logo-org-fallback" style="display:none; font-size:24px; color:#ccc;">🏢</span>

                                
                                <img
                                    class="logo-location-img"
                                    id="logo-location-img"
                                    src=""
                                    alt="Location Logo"
                                >
                            </div>

                            <div class="logo-ui-right">

                                
                                <div id="logo-org-tag" class="logo-org-tag">
                                    🏢 Using Organization Logo
                                </div>

                                <div class="logo-current-label" id="logo-current-label" style="display:none;">
                                    
                                </div>

                                
                                <button type="button"
                                        class="btn-upload-logo"
                                        onclick="document.getElementById('logo-file-input').click()">
                                    📤 Upload as Logo
                                </button>

                                
                                <input type="file"
                                       id="logo-file-input"
                                       accept="image/png,image/jpeg,image/webp"
                                       style="display:none"
                                       onchange="handleLogoFileSelect(this)">
                            </div>
                        </div>

                        
                        <div class="logo-file-row" id="logo-file-row">
                            <span style="font-size:12px;">📎</span>
                            <span id="logo-file-name-span" style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></span>
                            <button type="button" class="logo-remove-btn" onclick="clearLogoUpload()">
                                ✕ Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            

            
            <div class="form-group">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" id="location-name" name="location_name" placeholder="Location Name">
            </div>

            
            <div class="form-group" id="child-location-row" style="display:none;">
                <label class="form-label"></label>
                <label class="checkbox-label">
                    <input type="checkbox" id="is-child" onchange="toggleParentLocation()">
                    This is a Child Location
                </label>
            </div>

            
            <div class="form-group" id="parent-location-row" style="display:none;">
                <label class="form-label required">Parent Location</label>
                <select class="form-control form-control-select" id="parent-location-select">
                    <option value="">— Select Parent Location —</option>
                    <?php $__currentLoopData = $locations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($loc->id); ?>">
                            <?php echo e($loc->location_name); ?>

                            (<?php echo e($loc->location_type === 'business' ? '🏢 Business' : '🏭 Warehouse'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="form-section-title">Address</div>
            <div class="address-grid">
                <input type="text" class="form-control" name="attention" placeholder="Attention">
                <input type="text" class="form-control" name="street1"   placeholder="Street 1">
                <input type="text" class="form-control" name="street2"   placeholder="Street 2">
                <div class="form-row-2">
                    <input type="text" class="form-control" name="city"    placeholder="City">
                    <input type="text" class="form-control" name="pincode" placeholder="Pin Code">
                </div>
                <select class="form-control form-control-select" name="country">
                    <option>India</option>
                    <option>United States</option>
                    <option>United Kingdom</option>
                </select>
                <div class="form-row-2">
                    <select class="form-control form-control-select" name="state">
                        <option value="">State/Union Territory</option>
                        <option>Tamil Nadu</option>
                        <option>Karnataka</option>
                        <option>Kerala</option>
                        <option>Andhra Pradesh</option>
                        <option>Maharashtra</option>
                        <option>Delhi</option>
                    </select>
                    <input type="text" class="form-control" name="phone" placeholder="Phone">
                </div>
                <input type="text" class="form-control" name="fax"     placeholder="Fax Number">
                <input type="text" class="form-control" name="website" placeholder="Website URL">
            </div>

            
            <div id="business-only-fields">
                <div class="form-section-title">Additional Info</div>
                <div class="form-group">
                    <label class="form-label required">Primary Contact</label>
                    <select class="form-control form-control-select" name="primary_contact">
                        <option><?php echo e(auth()->user()->name ?? 'Admin'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Transaction Number Series</label>
                    <div>
                        <select class="form-control form-control-select" id="trans-series-select">
                            <option value="">Add Transaction Series</option>
                            <?php $__currentLoopData = $transactionSeries ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($series->id); ?>"><?php echo e($series->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button class="btn-link" style="margin-top:6px; font-size:12px;" onclick="openTransSeriesModal()">
                            + Add Transaction Series
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Default Transaction Number Series</label>
                    <select class="form-control form-control-select">
                        <option value="">Add Transaction Series</option>
                    </select>
                </div>

                <div class="form-section-title">Location Access</div>
                <div class="access-box">
                    <div class="access-header">
                        <div>
                            <span class="access-dot"></span>
                            <strong>1 user(s) selected</strong>
                            <div style="font-size:11px; color:var(--zoho-text-muted); margin-left:14px;">
                                Selected users can create and access transactions for this location.
                            </div>
                        </div>
                        <label class="checkbox-label">
                            <input type="checkbox"> Provide access to all users
                        </label>
                    </div>
                    <table class="access-users-table">
                        <thead>
                            <tr><th>Users</th><th>Role</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="user-avatar">
                                        <?php echo e(strtoupper(substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                                    </span>
                                    <div style="display:inline-block; vertical-align:middle;">
                                        <div style="font-weight:500;"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                                        <div style="font-size:11px; color:var(--zoho-text-muted);"><?php echo e(auth()->user()->email ?? ''); ?></div>
                                    </div>
                                </td>
                                <td>Admin</td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control form-control-select" style="max-width:200px;">
                                        <option>Select users</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-select">
                                        <option>User's Role</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="drawer-footer">
            <button class="btn-primary"   onclick="saveLocation()">Save</button>
            <button class="btn-secondary" onclick="closeDrawer()">Cancel</button>
        </div>
    </div>
</div>


<div class="confirm-modal" id="delete-modal">
    <div class="confirm-box">
        <div class="confirm-title">Delete Location</div>
        <div class="confirm-text" id="delete-confirm-text">
            Are you sure you want to delete this location?<br>This action cannot be undone.
        </div>
        <div class="confirm-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger"    onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>


<div class="trans-series-modal" id="trans-series-modal">
    <div class="trans-series-box">
        <div class="trans-series-header">
            <span class="trans-series-title">Transaction Series Preferences</span>
            <button class="drawer-close" onclick="closeTransSeriesModal()">✕</button>
        </div>
        <div class="trans-series-body">
            <div class="series-name-group">
                <label>Series Name*</label>
                <input type="text" class="form-control" id="series-name"
                       placeholder="e.g. Default Transaction Series" style="max-width:300px;">
            </div>
            <table class="trans-table">
                <thead>
                    <tr>
                        <th>Module</th><th>Prefix</th><th>Starting Number</th><th>Preview</th>
                    </tr>
                </thead>
                <tbody id="trans-series-tbody"></tbody>
            </table>
        </div>
        <div class="trans-series-footer">
            <button class="btn-primary"   onclick="saveTransSeries()">Save</button>
            <button class="btn-secondary" onclick="closeTransSeriesModal()">Cancel</button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── State ─────────────────────────────────────────────────────────────────
let currentEditId       = null;
let deleteTargetId      = null;
let currentLocationType = 'business';

// Logo state
let logoFileBase64 = null;   // full data URL  "data:image/png;base64,…"
let logoFileName   = null;
let logoMimeType   = null;
let logoRemoved    = false;  // true = user explicitly removed location logo

const transModules = [
    { name: 'Credit Note',       prefix: 'CN-',   start: '00001'  },
    { name: 'Customer Payment',  prefix: '',       start: '1'      },
    { name: 'Purchase Order',    prefix: 'PO-',   start: '00001'  },
    { name: 'Sales Order',       prefix: 'SO-',   start: '00001'  },
    { name: 'Vendor Payment',    prefix: '',       start: '1'      },
    { name: 'Retainer Invoice',  prefix: 'RET-',  start: '00001'  },
    { name: 'Bill Of Supply',    prefix: 'BOS-',  start: '000001' },
    { name: 'Invoice',           prefix: 'INV-',  start: '000001' },
    { name: 'Sales Return',      prefix: 'RMA-',  start: '00001'  },
    { name: 'Delivery Challan',  prefix: 'DC-',   start: '00001'  },
];

// ── Drawer ─────────────────────────────────────────────────────────────────
function openAddDrawer() {
    currentEditId = null;
    document.getElementById('drawer-title').textContent = 'Add Location';
    document.getElementById('location-name').value = '';
    resetAddressFields();
    resetLogoUI();          // clears location logo → shows org logo as default
    selectLocationType('business');
    document.getElementById('location-drawer-overlay').classList.add('active');
}

function openEditDrawer(id) {
    currentEditId = id;
    document.getElementById('drawer-title').textContent = 'Edit Location';

    fetch(`/locations/${id}/edit`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('location-name').value = data.location_name ?? '';
        resetLogoUI();
        selectLocationType(data.location_type ?? 'business');

        // Warehouse child fields
        if (data.location_type === 'warehouse') {
            const isChild = data.is_child ?? false;
            document.getElementById('is-child').checked = isChild;
            if (isChild && data.parent_location_id) {
                document.getElementById('parent-location-row').style.display = '';
                document.getElementById('parent-location-select').value = data.parent_location_id;
            }
        }

        // Address fill
        const addr = data.address_details ?? {};
        document.querySelector('[name="attention"]').value = addr.attention ?? '';
        document.querySelector('[name="street1"]').value   = addr.street1   ?? '';
        document.querySelector('[name="street2"]').value   = addr.street2   ?? '';
        document.querySelector('[name="city"]').value      = addr.city      ?? '';
        document.querySelector('[name="pincode"]').value   = addr.pincode   ?? '';
        document.querySelector('[name="phone"]').value     = addr.phone     ?? '';
        document.querySelector('[name="fax"]').value       = addr.fax       ?? '';
        document.querySelector('[name="website"]').value   = addr.website   ?? '';

        // If this location already has its own logo, show it
        const imgData = data.additional_data?.location_image ?? null;
        if (imgData && imgData.url) {
            _showLocationLogoPreview(imgData.url, imgData.file_name ?? 'Uploaded Logo');
        }
        // else: org logo stays as default (resetLogoUI already set that)

        document.getElementById('location-drawer-overlay').classList.add('active');
    })
    .catch(() => showAlert('Failed to load location data', 'error'));
}

function closeDrawer() {
    document.getElementById('location-drawer-overlay').classList.remove('active');
}

function closeDrawerOnOverlay(e) {
    if (e.target === document.getElementById('location-drawer-overlay')) closeDrawer();
}

// ── Type Selection ─────────────────────────────────────────────────────────
function selectLocationType(type) {
    currentLocationType = type;
    document.getElementById('card-business').classList.toggle('selected', type === 'business');
    document.getElementById('card-warehouse').classList.toggle('selected', type === 'warehouse');
    document.getElementById('type-business').checked  = type === 'business';
    document.getElementById('type-warehouse').checked = type === 'warehouse';

    document.getElementById('logo-section').style.display         = type === 'business' ? '' : 'none';
    document.getElementById('business-only-fields').style.display = type === 'business' ? '' : 'none';
    document.getElementById('child-location-row').style.display   = type === 'warehouse' ? 'grid' : 'none';

    if (type === 'business') {
        document.getElementById('is-child').checked = false;
        document.getElementById('parent-location-row').style.display = 'none';
    }
}

function toggleParentLocation() {
    document.getElementById('parent-location-row').style.display =
        document.getElementById('is-child').checked ? '' : 'none';
}

// ── Logo Upload ────────────────────────────────────────────────────────────

/**
 * Called when user picks a file via the hidden <input type="file">
 */
function handleLogoFileSelect(input) {
    if (input.files && input.files[0]) {
        processLogoFile(input.files[0]);
    }
    input.value = '';   // allow re-selecting same file later
}

function processLogoFile(file) {
    const allowed = ['image/png', 'image/jpeg', 'image/webp'];
    if (!allowed.includes(file.type)) {
        showAlert('Only PNG, JPG, WEBP images are allowed', 'error');
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        showAlert('Image must be less than 2 MB', 'error');
        return;
    }

    logoFileName = file.name;
    logoMimeType = file.type;
    logoRemoved  = false;

    const reader = new FileReader();
    reader.onload = e => {
        logoFileBase64 = e.target.result;           // "data:image/png;base64,..."
        _showLocationLogoPreview(logoFileBase64, file.name);
    };
    reader.readAsDataURL(file);
}

/**
 * Overlay the location-specific logo on top of the org logo thumbnail.
 * Shows file name row and hides the "Using Organization Logo" tag.
 */
function _showLocationLogoPreview(src, name) {
    const thumb    = document.getElementById('logo-thumb');
    const locImg   = document.getElementById('logo-location-img');
    const orgTag   = document.getElementById('logo-org-tag');
    const curLabel = document.getElementById('logo-current-label');
    const fileRow  = document.getElementById('logo-file-row');
    const fileName = document.getElementById('logo-file-name-span');

    locImg.src = src;
    thumb.classList.add('has-location-logo');

    orgTag.style.display   = 'none';
    curLabel.style.display = '';
    curLabel.textContent   = name;

    fileName.textContent = name;
    fileRow.classList.add('show');
}

/**
 * Remove the uploaded location logo → revert to org logo display.
 */
function clearLogoUpload() {
    logoRemoved    = true;    // tells backend to wipe existing logo
    logoFileBase64 = null;
    logoFileName   = null;
    logoMimeType   = null;

    _revertToOrgLogoDisplay();
}

/**
 * Reset the entire logo UI back to "Using Organization Logo" state.
 * Called on drawer open (both add & edit before data loads).
 */
function resetLogoUI() {
    logoFileBase64 = null;
    logoFileName   = null;
    logoMimeType   = null;
    // Note: do NOT reset logoRemoved here — clearLogoUpload() owns that flag

    _revertToOrgLogoDisplay();
}

function _revertToOrgLogoDisplay() {
    const thumb    = document.getElementById('logo-thumb');
    const locImg   = document.getElementById('logo-location-img');
    const orgTag   = document.getElementById('logo-org-tag');
    const curLabel = document.getElementById('logo-current-label');
    const fileRow  = document.getElementById('logo-file-row');
    const fileName = document.getElementById('logo-file-name-span');

    if (locImg)   { locImg.src = ''; }
    if (thumb)    { thumb.classList.remove('has-location-logo'); }
    if (orgTag)   { orgTag.style.display = ''; }           // show "Using Organization Logo"
    if (curLabel) { curLabel.style.display = 'none'; curLabel.textContent = ''; }
    if (fileRow)  { fileRow.classList.remove('show'); }
    if (fileName) { fileName.textContent = ''; }
}

// ── Save ──────────────────────────────────────────────────────────────────
function saveLocation() {
    const name = document.getElementById('location-name').value.trim();
    if (!name) {
        showAlert('Location name is required', 'error');
        return;
    }

    const payload = {
        location_name: name,
        location_type: currentLocationType,
        logo_removed:  logoRemoved,
        address_details: {
            attention: document.querySelector('[name="attention"]').value,
            street1:   document.querySelector('[name="street1"]').value,
            street2:   document.querySelector('[name="street2"]').value,
            city:      document.querySelector('[name="city"]').value,
            pincode:   document.querySelector('[name="pincode"]').value,
            state:     document.querySelector('[name="state"]').value,
            country:   document.querySelector('[name="country"]').value,
            phone:     document.querySelector('[name="phone"]').value,
            fax:       document.querySelector('[name="fax"]').value,
            website:   document.querySelector('[name="website"]').value,
        }
    };

    // Attach new logo only when user picked a file
    if (logoFileBase64) {
        payload.location_image = {
            data:      logoFileBase64,
            file_name: logoFileName,
            mime_type: logoMimeType,
        };
    }

    // Warehouse-specific fields
    if (currentLocationType === 'warehouse') {
        const childEl  = document.getElementById('is-child');
        const parentEl = document.getElementById('parent-location-select');
        payload.is_child           = childEl  ? childEl.checked          : false;
        payload.parent_location_id = parentEl ? (parentEl.value || null) : null;
    }

    const url    = currentEditId ? `/locations/${currentEditId}` : '/locations';
    const method = currentEditId ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message ?? 'Saved successfully', 'success');
            logoRemoved = false;
            closeDrawer();
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert(data.message ?? 'Failed to save', 'error');
        }
    })
    .catch(() => showAlert('Server error. Please try again.', 'error'));
}

// ── Helpers ───────────────────────────────────────────────────────────────
function resetAddressFields() {
    ['attention','street1','street2','city','pincode','phone','fax','website'].forEach(n => {
        const el = document.querySelector(`[name="${n}"]`);
        if (el) el.value = '';
    });
}

// ── Delete ────────────────────────────────────────────────────────────────
function openDeleteConfirm(id, name) {
    deleteTargetId = id;
    document.getElementById('delete-confirm-text').innerHTML =
        `Are you sure you want to delete <strong>"${name}"</strong>?<br>This action cannot be undone.`;
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
    deleteTargetId = null;
}

function confirmDelete() {
    if (!deleteTargetId) return;

    fetch(`/locations/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        closeDeleteModal();
        if (data.success) {
            document.getElementById(`row-${deleteTargetId}`)?.remove();
            showAlert('Location deleted successfully', 'success');
        } else {
            showAlert(data.message ?? 'Failed to delete', 'error');
        }
    })
    .catch(() => showAlert('Server error', 'error'));
}

// ── Trans Series ──────────────────────────────────────────────────────────
function openTransSeriesModal() {
    const tbody = document.getElementById('trans-series-tbody');
    tbody.innerHTML = transModules.map((m, i) => `
        <tr>
            <td>${m.name}</td>
            <td><input type="text" value="${m.prefix}" id="prefix-${i}" oninput="updatePreview(${i})"></td>
            <td><input type="text" value="${m.start}"  id="start-${i}"  oninput="updatePreview(${i})"></td>
            <td><span class="preview-badge" id="preview-${i}">${m.prefix}${m.start}</span></td>
        </tr>
    `).join('');
    document.getElementById('trans-series-modal').classList.add('active');
}

function updatePreview(i) {
    document.getElementById(`preview-${i}`).textContent =
        document.getElementById(`prefix-${i}`).value +
        document.getElementById(`start-${i}`).value;
}

function closeTransSeriesModal() {
    document.getElementById('trans-series-modal').classList.remove('active');
}

function saveTransSeries() {
    const name = document.getElementById('series-name').value.trim();
    if (!name) {
        document.getElementById('series-name').focus();
        document.getElementById('series-name').style.borderColor = 'red';
        return;
    }

    const series = transModules.map((m, i) => ({
        module: m.name,
        prefix: document.getElementById(`prefix-${i}`)?.value ?? m.prefix,
        start:  document.getElementById(`start-${i}`)?.value  ?? m.start,
    }));

    fetch('/transaction-series', {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, series })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeTransSeriesModal();
            showAlert('Transaction series saved!', 'success');
            const opt = new Option(name, data.id);
            document.getElementById('trans-series-select').appendChild(opt);
            document.getElementById('trans-series-select').value = data.id;
        }
    })
    .catch(() => showAlert('Failed to save series', 'error'));
}

// ── Alert ─────────────────────────────────────────────────────────────────
function showAlert(msg, type) {
    const el = document.getElementById('js-alert');
    el.className   = `alert alert-${type} show`;
    el.textContent = (type === 'success' ? '✓ ' : '✕ ') + msg;
    setTimeout(() => el.classList.remove('show'), 4000);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/locations/index.blade.php ENDPATH**/ ?>