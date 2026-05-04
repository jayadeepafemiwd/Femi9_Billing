<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>New Customer | Inventory</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #333; background: #f5f5f5; }
  .app-shell { display: flex; height: 100vh; overflow: hidden; }
  .sidebar { width: 220px; background: #1a1f2e; color: #ccc; flex-shrink: 0; display: flex; flex-direction: column; }
  .sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 16px 18px; border-bottom: 1px solid #2e3448; }
  .sidebar-logo .logo-icon { width: 28px; height: 28px; background: #e8f0fe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
  .sidebar-logo span { font-size: 15px; font-weight: 600; color: #fff; }
  .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
  .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 18px; cursor: pointer; font-size: 13px; color: #aab; transition: background 0.15s; }
  .nav-item:hover { background: #252b3d; color: #fff; }
  .nav-item .arrow { margin-left: auto; font-size: 10px; }
  .sidebar-bottom { padding: 12px; border-top: 1px solid #2e3448; }
  .collapse-btn { display: flex; align-items: center; justify-content: center; color: #666; cursor: pointer; padding: 4px; }
  .nav-sub { padding-left: 42px; font-size: 12.5px; color: #aab; padding-top: 7px; padding-bottom: 7px; cursor: pointer; }
  .nav-sub:hover { color: #fff; background: #252b3d; }
  .nav-sub.active { color: #fff; background: #3b6cf8; }
  .topbar { display: flex; align-items: center; gap: 12px; padding: 0 20px; height: 52px; background: #fff; border-bottom: 1px solid #e8e8e8; }
  .topbar-search { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 6px; padding: 6px 12px; min-width: 280px; }
  .topbar-search input { border: none; background: transparent; outline: none; font-size: 13px; color: #333; width: 200px; }
  .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
  .topbar-btn { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; position: relative; }
  .topbar-btn:hover { background: #f5f5f5; }
  .topbar-btn .badge { position: absolute; top: 2px; right: 2px; background: #e53935; color: #fff; border-radius: 50%; width: 14px; height: 14px; font-size: 9px; display: flex; align-items: center; justify-content: center; }
  .avatar { width: 32px; height: 32px; border-radius: 50%; background: #3b6cf8; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 13px; cursor: pointer; }
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
  .content { flex: 1; overflow-y: auto; }
  .page { background: #fff; min-height: 100%; }
  .page-header { padding: 20px 24px 0; border-bottom: 1px solid #e8e8e8; }
  .page-title { font-size: 20px; font-weight: 600; color: #222; margin-bottom: 14px; }
  .prefill-banner { background: #e8f4fd; border: 1px solid #b8d9f0; border-radius: 6px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; margin-bottom: 14px; font-size: 12.5px; color: #444; }
  .prefill-banner a { color: #3b6cf8; text-decoration: none; font-weight: 500; }
  .tabs { display: flex; gap: 0; border-bottom: 2px solid #e8e8e8; }
  .tab { padding: 10px 18px; font-size: 13px; color: #666; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; }
  .tab:hover { color: #333; }
  .tab.active { color: #3b6cf8; border-bottom-color: #3b6cf8; font-weight: 500; }
  .form-body { padding: 20px 24px; }
  .form-row { display: flex; align-items: flex-start; margin-bottom: 16px; gap: 16px; }
  .form-label { width: 180px; min-width: 180px; font-size: 13px; color: #555; padding-top: 8px; display: flex; align-items: center; gap: 5px; }
  .form-label.required { color: #e53935; }
  .info-icon { color: #aaa; font-size: 12px; cursor: pointer; }
  .form-control { flex: 1; max-width: 520px; }
  .form-control input, .form-control select, .form-control textarea { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; background: #fff; outline: none; transition: border 0.15s; appearance: none; -webkit-appearance: none; }
  .form-control input:focus, .form-control select:focus, .form-control textarea:focus { border-color: #3b6cf8; box-shadow: 0 0 0 2px rgba(59,108,248,0.1); }
  .form-control select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }
  .form-control textarea { resize: vertical; min-height: 80px; }
  .phone-group { display: flex; gap: 8px; }
  .phone-country { display: flex; align-items: center; gap: 4px; border: 1px solid #d0d0d0; border-radius: 4px; padding: 7px 8px; background: #fff; }
  .phone-country select { border: none; outline: none; background: transparent; font-size: 13px; padding: 0; cursor: pointer; }
  .phone-input { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; }
  .phone-input:focus { border-color: #3b6cf8; }
  .inline-group { display: flex; gap: 8px; }
  .inline-group select { flex: 0 0 140px; }
  .inline-group input { flex: 1; }
  .radio-group { display: flex; gap: 12px; padding-top: 4px; }
  .radio-option { display: flex; align-items: center; gap: 8px; padding: 7px 16px; border: 2px solid #d0d0d0; border-radius: 6px; cursor: pointer; user-select: none; transition: all 0.15s; background: #fff; font-size: 13px; color: #555; font-weight: 500; }
  .radio-option:hover { border-color: #3b6cf8; color: #3b6cf8; background: #f0f4ff; }
  .radio-option.selected { border-color: #3b6cf8; background: #3b6cf8; color: #fff; }
  .radio-option input[type=radio] { display: none; }
  input[type=checkbox] { display: inline-block !important; appearance: auto !important; -webkit-appearance: checkbox !important; pointer-events: auto !important; cursor: pointer; }
  .radio-dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid currentColor; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .radio-option.selected .radio-dot { background: #fff; border-color: #fff; }
  .category-wrapper { display: flex; gap: 8px; align-items: center; }
  .category-wrapper select { flex: 1; }
  .manage-cat-btn { white-space: nowrap; padding: 7px 12px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 12.5px; color: #3b6cf8; cursor: pointer; background: #f0f4ff; font-weight: 500; transition: all 0.15s; }
  .manage-cat-btn:hover { background: #3b6cf8; color: #fff; border-color: #3b6cf8; }
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center; }
  .modal-overlay.open { display: flex; }
  .modal { background: #fff; border-radius: 10px; width: 500px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; animation: modalIn 0.2s ease; }
  @keyframes modalIn { from { transform: scale(0.93) translateY(-10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
  .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e8e8e8; background: #f8f9ff; }
  .modal-header h3 { font-size: 15px; font-weight: 600; color: #222; }
  .modal-close { width: 28px; height: 28px; border-radius: 50%; border: none; background: #eee; color: #555; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .modal-close:hover { background: #e53935; color: #fff; }
  .modal-body { padding: 20px; }
  .modal-footer { padding: 14px 20px; border-top: 1px solid #e8e8e8; display: flex; justify-content: flex-end; gap: 8px; }
  .add-cat-row { display: flex; gap: 8px; margin-bottom: 8px; }
  .add-cat-row input { flex: 1; padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 6px; font-size: 13px; outline: none; }
  .add-cat-row input:focus { border-color: #3b6cf8; }
  .btn-add-cat { padding: 8px 16px; background: #3b6cf8; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; white-space: nowrap; }
  .btn-add-cat:hover { background: #2b5ce0; }
  .btn-add-cat:disabled { background: #a0b4f0; cursor: not-allowed; }
  .cat-error { color: #e53935; font-size: 12px; margin-bottom: 8px; display: none; }
  .cat-list { max-height: 280px; overflow-y: auto; border: 1px solid #e8e8e8; border-radius: 6px; }
  .cat-empty { padding: 32px; text-align: center; color: #aaa; font-size: 13px; }
  .cat-item { display: flex; align-items: center; padding: 10px 14px; border-bottom: 1px solid #f0f0f0; gap: 10px; }
  .cat-item:last-child { border-bottom: none; }
  .cat-item:hover { background: #f8f9ff; }
  .cat-name-display { flex: 1; font-size: 13px; color: #333; font-weight: 500; }
  .cat-name-input { flex: 1; padding: 5px 8px; border: 1px solid #3b6cf8; border-radius: 4px; font-size: 13px; outline: none; box-shadow: 0 0 0 2px rgba(59,108,248,0.1); }
  .cat-actions { display: flex; gap: 4px; flex-shrink: 0; }
  .cat-btn { padding: 4px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; border: 1px solid #d0d0d0; background: #fff; transition: all 0.15s; }
  .cat-btn.edit { color: #3b6cf8; border-color: #b8d0ff; }
  .cat-btn.edit:hover { background: #3b6cf8; color: #fff; }
  .cat-btn.save { color: #16a34a; border-color: #86efac; }
  .cat-btn.save:hover { background: #16a34a; color: #fff; }
  .cat-btn.cancel-edit { color: #888; }
  .cat-btn.cancel-edit:hover { background: #f5f5f5; }
  .cat-btn.del { color: #e53935; border-color: #fca5a5; }
  .cat-btn.del:hover { background: #e53935; color: #fff; }
  .cat-count { font-size: 11px; color: #999; margin-bottom: 10px; }
  .email-wrapper { position: relative; }
  .email-wrapper input { padding-left: 34px !important; }
  .email-wrapper .email-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 14px; }
  .address-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
  .address-section h4 { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
  .address-section h4 a { font-size: 12px; font-weight: 400; color: #3b6cf8; text-decoration: none; }
  .address-form-row { margin-bottom: 12px; }
  .address-form-row label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; }
  .address-form-row input, .address-form-row select, .address-form-row textarea { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; background: #fff; outline: none; appearance: none; -webkit-appearance: none; }
  .address-form-row select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }
  .address-form-row textarea { min-height: 60px; resize: vertical; }
  .address-note { background: #fffbf0; border-left: 3px solid #f5a623; padding: 10px 14px; margin-top: 16px; border-radius: 0 4px 4px 0; font-size: 12px; color: #444; }
  .address-note ul { margin-left: 14px; margin-top: 4px; }
  .contact-table { width: 100%; border-collapse: collapse; }
  .contact-table th { background: #f5f5f5; font-size: 11px; font-weight: 600; color: #666; padding: 8px 10px; text-align: left; border-bottom: 1px solid #e0e0e0; text-transform: uppercase; letter-spacing: 0.4px; }
  .contact-table td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  .contact-table td input, .contact-table td select { width: 100%; padding: 5px 8px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 12.5px; background: #fff; outline: none; appearance: none; -webkit-appearance: none; }
  .contact-table td.action-col { width: 36px; text-align: center; }
  .remove-row-btn { background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px; line-height: 1; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; }
  .remove-row-btn:hover { background: #fff0f0; color: #e53935; }
  .add-person-btn { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; color: #3b6cf8; font-size: 13px; cursor: pointer; border: none; background: none; padding: 0; }
  .phone-cell { display: flex; gap: 4px; align-items: center; }
  .phone-cell select { width: 70px !important; flex-shrink: 0; }
  .phone-cell input { flex: 1; }
  .form-footer { padding: 16px 24px; border-top: 1px solid #e8e8e8; display: flex; gap: 10px; background: #fff; position: sticky; bottom: 0; }
  .btn-save { background: #3b6cf8; color: #fff; border: none; border-radius: 4px; padding: 8px 24px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-save:hover { background: #2b5ce0; }
  .btn-cancel { background: #fff; color: #333; border: 1px solid #d0d0d0; border-radius: 4px; padding: 8px 20px; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
  .btn-cancel:hover { background: #f5f5f5; }
  .right-icons { width: 36px; background: #fff; border-left: 1px solid #e8e8e8; display: flex; flex-direction: column; padding: 8px 0; gap: 2px; }
  .right-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #777; font-size: 14px; border-radius: 4px; }
  .right-icon:hover { background: #f0f0f0; }
  .right-icon.orange { background: #f97316; color: #fff; }
  .portal-check { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #444; cursor: pointer; }
  .portal-check input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; accent-color: #3b6cf8; flex-shrink: 0; }
  .website-wrapper { position: relative; }
  .website-wrapper input { padding-left: 34px !important; }
  .website-wrapper .globe-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 13px; }
  .social-icon-box { width: 32px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border: 1px solid #d0d0d0; border-right: none; border-radius: 4px 0 0 4px; font-size: 13px; }
  .social-input { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 0 4px 4px 0; font-size: 13px; color: #333; background: #fff; outline: none; }
  .social-row { display: flex; }
  .add-more-btn { display: inline-flex; align-items: center; gap: 6px; color: #3b6cf8; font-size: 13px; cursor: pointer; border: none; background: none; padding: 4px 0; margin-bottom: 8px; }
  .tab-content { display: none; }
  .alert-danger { background: #fde8e8; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
  .alert-danger ul { margin-left: 16px; margin-top: 4px; }.sub-chip { display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border:2px solid #d0d8ff;border-radius:20px;font-size:12.5px;color:#555;background:#fff;cursor:pointer;transition:all 0.15s;user-select:none; }
.sub-chip:hover { border-color:#3b6cf8;color:#3b6cf8; }
.sub-chip.selected { border-color:#3b6cf8;background:#3b6cf8;color:#fff; }
.sub-chip-dot { width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0; }

  /* ═══════════════════════════════════════════
     DISPLAY NAME DROPDOWN  (simple, no tags)
  ═══════════════════════════════════════════ */
  .display-name-wrapper {
    position: relative;
    width: 100%;
  }
  .display-name-wrapper input {
    width: 100%;
  }
  /* The dropdown list */
  .dn-dropdown {
    position: absolute;
    top: calc(100% + 3px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #c8d6ff;
    border-radius: 6px;
    box-shadow: 0 6px 20px rgba(59,108,248,0.13);
    z-index: 500;
    max-height: 210px;
    overflow-y: auto;
    display: none;
  }
  .dn-dropdown.open { display: block; }
  .dn-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    cursor: pointer;
    font-size: 13px;
    color: #333;
    transition: background 0.12s;
    border-bottom: 1px solid #f4f4f4;
  }
  .dn-item:last-child { border-bottom: none; }
  .dn-item:hover, .dn-item.active {
    background: #f0f4ff;
    color: #3b6cf8;
  }
  .dn-avatar {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: #dce8ff;
    color: #3b6cf8;
    font-size: 11px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .dn-bold { font-weight: 700; color: #3b6cf8; }
  .dn-empty {
    padding: 14px 12px;
    font-size: 12px;
    color: #aaa;
    text-align: center;
  }
  /* ── LOCATION CASCADE ── */
.loc-wrap { min-width: 180px; }
.loc-row  { display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }
.loc-label { font-size: 11px; color: #666; font-weight: 600; min-width: 60px; text-transform: capitalize; }
.loc-select {
    flex: 1; padding: 4px 7px; border: 1px solid #d0d0d0;
    border-radius: 4px; font-size: 12px; color: #333;
    background: #fff; outline: none; appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 7px center; padding-right: 22px;
}
.loc-select:focus { border-color: #3b6cf8; }
.loc-loading { font-size: 11px; color: #aaa; }
</style>
</head>
<body>
<div class="app-shell">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">📦</div>
      <span>Inventory</span>
    </div>
    <div class="sidebar-nav">
      <div class="nav-item">🏠 Home</div>
      <div class="nav-item">📦 Items <span class="arrow">›</span></div>
      <div class="nav-item">🏪 Inventory <span class="arrow">›</span></div>
      <div class="nav-item" style="color:#fff;background:#252b3d;">🛒 Sales <span class="arrow">›</span></div>
      <div class="nav-sub active">Customers</div>
      <div class="nav-sub">Sales Orders</div>
      <div class="nav-sub">Invoices</div>
      <div class="nav-sub">Payments Received</div>
      <div class="nav-sub">Sales Returns</div>
      <div class="nav-item">🛍️ Purchases <span class="arrow">›</span></div>
      <div class="nav-item">📊 Reports</div>
      <div class="nav-item">📄 Documents</div>
    </div>
    <div class="sidebar-bottom"><div class="collapse-btn">⟨ Collapse</div></div>
  </div>

  <div class="main">
    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-search">
        <span>🔍</span>
        <input placeholder="Search in Customers ( / )" />
      </div>
      <div class="topbar-right">
        <div class="topbar-btn">🔔<span class="badge">1</span></div>
        <div class="topbar-btn">⚙️</div>
        <div class="avatar">V</div>
      </div>
    </div>

    <div class="content">
      <div class="page">

        <form id="customer-form" action="<?php echo e(route('customers.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="page-header">
          <div class="page-title">New Customer</div>

          <?php if($errors->any()): ?>
            <div class="alert-danger">
              <strong>Please fix the following errors:</strong>
              <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
          <?php endif; ?>

          <div class="form-body" style="padding:16px 0 0;">

            
            <div class="form-row">
              <div class="form-label">Customer Type <span class="info-icon">ℹ</span></div>
              <div class="form-control">
                <div class="radio-group">
<label class="radio-option <?php echo e(old('customer_type', $config['default_customer_type'] ?? 'business') == 'business' ? 'selected' : ''); ?>" id="opt-business" onclick="selectCustomerType('business')">                   
<input type="radio" name="customer_type" value="business" <?php echo e(old('customer_type', $config['default_customer_type'] ?? 'business') == 'business' ? 'checked' : ''); ?>>
                    <span class="radio-dot"></span> Business
                  </label>
<label class="radio-option <?php echo e(old('customer_type', $config['default_customer_type'] ?? 'business') == 'individual' ? 'selected' : ''); ?>" id="opt-individual" onclick="selectCustomerType('individual')">                 
<input type="radio" name="customer_type" value="individual" <?php echo e(old('customer_type', $config['default_customer_type'] ?? 'business') == 'individual' ? 'checked' : ''); ?>>                    <span class="radio-dot"></span> Individual
                  </label>
                </div>
              </div>
            </div>

        
<div class="form-row" id="row-customerCategory" style="<?php echo e(old('customer_type')=='individual'?'display:none':''); ?>">
  <div class="form-label">Customer Category <span class="info-icon">ℹ</span></div>
  <div class="form-control" style="max-width:420px;">

    <input type="hidden" name="customer_category" id="customerCategoryValue" 
           value="<?php echo e(old('customer_category')); ?>">
    <input type="hidden" name="customer_sub_category_id" id="customerSubCatId" value="<?php echo e(old('customer_sub_category_id')); ?>">
    
    <div class="pl-cat-dropdown" id="custCatDropdown">
      <div class="pl-cat-selected" id="custCatSelected" onclick="toggleCustCatList()"
           style="display:flex;justify-content:space-between;align-items:center;
                  padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;
                  background:#fff;cursor:pointer;font-size:13px;min-height:36px;">
        <span id="custCatSelectedText" style="color:#999;">-- Select Category --</span>
        <span style="font-size:11px;color:#999;">▾</span>
      </div>
      <div id="custCatList"
           style="display:none;position:absolute;background:#fff;border:1px solid #d0d0d0;
                  border-radius:6px;box-shadow:0 6px 20px rgba(0,0,0,0.12);z-index:200;
                  max-height:220px;overflow-y:auto;width:420px;">
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f0f0f0;color:#999;"
             data-name="" data-layer-id="" data-country-id="" data-loc=""
             onclick="selectCustCat(this)">
          <span>-- Select Category --</span>
        </div>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f0f0f0;"
            data-name="<?php echo e($cat->name); ?>"
            data-id="<?php echo e($cat->id); ?>"
            data-layer-id="<?php echo e($cat->assign_fix_location); ?>"
             data-country-id="<?php echo e($cat->country_id); ?>"
             data-loc="<?php echo e($cat->location_label); ?>"
             onclick="selectCustCat(this)"
             onmouseover="this.style.background='#f0f4ff'"
             onmouseout="this.style.background='#fff'">
          <span style="font-weight:500;color:#333;"><?php echo e($cat->name); ?></span>
          <?php if($cat->location_label): ?>
            <span style="font-size:11px;color:#fff;background:#3b6cf8;
                         padding:2px 8px;border-radius:10px;white-space:nowrap;">
              <?php echo e($cat->location_label); ?>

            </span>
          <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

<div id="subCatWrap" style="display:none;margin-top:8px;">
  <div style="background:#f8f9ff;border:1px solid #dce8ff;border-radius:8px;padding:10px 12px;">
    <div style="font-size:11px;font-weight:600;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:8px;">Sub-Category</div>
    <div id="subCatChips" style="display:flex;flex-wrap:wrap;gap:6px;">
      <span style="font-size:12px;color:#aaa;">Loading...</span>
    </div>
  </div>
</div>
  </div>
</div>
            
            <div class="form-row">
              <div class="form-label">Primary Contact <span class="info-icon">ℹ</span></div>
              <div class="form-control">
                <div class="inline-group">
                  <select name="salutation" style="flex:0 0 130px;">
                    <option value="">Salutation</option>
                    <option value="Mr."   <?php echo e(old('salutation')=='Mr.'  ?'selected':''); ?>>Mr.</option>
                    <option value="Mrs."  <?php echo e(old('salutation')=='Mrs.' ?'selected':''); ?>>Mrs.</option>
                    <option value="Ms."   <?php echo e(old('salutation')=='Ms.'  ?'selected':''); ?>>Ms.</option>
                    <option value="Dr."   <?php echo e(old('salutation')=='Dr.'  ?'selected':''); ?>>Dr.</option>
                    <option value="Prof." <?php echo e(old('salutation')=='Prof.'?'selected':''); ?>>Prof.</option>
                  </select>
                  <input type="text" name="first_name" id="firstName" placeholder="First Name" value="<?php echo e(old('first_name')); ?>" oninput="refreshDisplaySuggestions()" style="flex:1;">
                  <input type="text" name="last_name"  id="lastName"  placeholder="Last Name"  value="<?php echo e(old('last_name')); ?>"  oninput="refreshDisplaySuggestions()" style="flex:1;">
                </div>
              </div>
            </div>

            
            <div class="form-row" id="row-companyName" style="<?php echo e(old('customer_type')=='individual'?'display:none':''); ?>">
              <div class="form-label">Company Name</div>
              <div class="form-control">
                <input type="text" name="company_name" id="companyName"
                       value="<?php echo e(old('company_name')); ?>"
                       oninput="refreshDisplaySuggestions()"
                       autocomplete="off">
              </div>
            </div>

            
            <div class="form-row">
              <div class="form-label required">Display Name* <span class="info-icon">ℹ</span></div>
              <div class="form-control">
                <div class="display-name-wrapper" id="dnWrapper">

                  
                  <input
                    type="text"
                    name="display_name"
                    id="displayName"
                    value="<?php echo e(old('display_name')); ?>"
                    placeholder="Select or type display name"
                    autocomplete="off"
                    required
                    oninput="onDisplayNameType()"
                    onfocus="refreshDisplaySuggestions()"
                  >

                  
                  <div class="dn-dropdown" id="dnDropdown"></div>
                </div>
              </div>
            </div>

            
            <div class="form-row">
              <div class="form-label">Email Address</div>
              <div class="form-control">
                <div class="email-wrapper">
                  <span class="email-icon">✉</span>
                  <input type="email" name="email" value="<?php echo e(old('email')); ?>">
                </div>
              </div>
            </div>

            
            <div class="form-row">
              <div class="form-label">Phone</div>
              <div class="form-control">
                <div class="phone-group">
                  <div class="phone-country"><select><option>+91</option><option>+1</option><option>+44</option></select></div>
                  <input type="text" name="phone_number" placeholder="Work Phone" class="phone-input" value="<?php echo e(old('phone_number')); ?>">
                  <div class="phone-country"><select><option>+91</option><option>+1</option><option>+44</option></select></div>
                  <input type="text" name="mobile" placeholder="Mobile" class="phone-input" value="<?php echo e(old('mobile')); ?>">
                </div>
              </div>
            </div>

            
            <div class="form-row">
              <div class="form-label">Customer Language</div>
              <div class="form-control" style="max-width:320px;">
                <select name="language">
                  <option value="English" <?php echo e(old('language','English')=='English'?'selected':''); ?>>English</option>
                  <option value="Tamil"   <?php echo e(old('language')=='Tamil'  ?'selected':''); ?>>Tamil</option>
                  <option value="Hindi"   <?php echo e(old('language')=='Hindi'  ?'selected':''); ?>>Hindi</option>
                  <option value="Telugu"  <?php echo e(old('language')=='Telugu' ?'selected':''); ?>>Telugu</option>
                  <option value="Kannada" <?php echo e(old('language')=='Kannada'?'selected':''); ?>>Kannada</option>
                </select>
              </div>
            </div>

          </div>

         <div class="tabs">
        <div class="tab active" onclick="showTab('otherDetails',this)">Other Details</div>
        <div class="tab" onclick="showTab('address',this)">Address</div>
        <div class="tab" onclick="showTab('contactPersons',this)">Contact Persons</div>
        <div class="tab" onclick="showTab('assignLocation',this)">Assign Location</div>  
        <div class="tab" onclick="showTab('remarks',this)">Remarks</div>
        <div class="tab" onclick="showTab('customFields',this)">Custom Fields</div>
      </div>
        
        

        
        <div id="tab-otherDetails" class="form-body tab-content" style="display:block;">
          <div class="form-row">
            <div class="form-label">PAN</div>
            <div class="form-control"><input type="text" name="pan" maxlength="10" style="max-width:200px;text-transform:uppercase;" value="<?php echo e(old('pan')); ?>"></div>
          </div>
          <div class="form-row">
            <div class="form-label">Currency</div>
            <div class="form-control" style="max-width:320px;">
              <select name="currency">
                <option value="INR" <?php echo e(old('currency','INR')=='INR'?'selected':''); ?>>INR - Indian Rupee</option>
                <option value="USD" <?php echo e(old('currency')=='USD'?'selected':''); ?>>USD - US Dollar</option>
                <option value="EUR" <?php echo e(old('currency')=='EUR'?'selected':''); ?>>EUR - Euro</option>
                <option value="GBP" <?php echo e(old('currency')=='GBP'?'selected':''); ?>>GBP - British Pound</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-label">Payment Terms</div>
            <div class="form-control" style="max-width:320px;">
              <select name="payment_terms">
                <option value="Due on Receipt" <?php echo e(old('payment_terms','Due on Receipt')=='Due on Receipt'?'selected':''); ?>>Due on Receipt</option>
                <option value="Net 15" <?php echo e(old('payment_terms')=='Net 15'?'selected':''); ?>>Net 15</option>
                <option value="Net 30" <?php echo e(old('payment_terms')=='Net 30'?'selected':''); ?>>Net 30</option>
                <option value="Net 45" <?php echo e(old('payment_terms')=='Net 45'?'selected':''); ?>>Net 45</option>
                <option value="Net 60" <?php echo e(old('payment_terms')=='Net 60'?'selected':''); ?>>Net 60</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-label">Enable Portal?</div>
            <div class="form-control">
              <label class="portal-check">
                <input type="checkbox" name="enable_portal" value="1" <?php echo e(old('enable_portal')?'checked':''); ?>>
                Allow portal access for this customer
              </label>
            </div>
          </div>
          <button type="button" class="add-more-btn" id="addMoreBtn" onclick="toggleMoreDetails()">＋ Add More Details</button>
          <div id="moreDetailsSection" style="display:none;">
            <div class="form-row">
              <div class="form-label">Website URL</div>
              <div class="form-control">
                <div class="website-wrapper">
                  <span class="globe-icon">🌐</span>
                  <input type="url" name="website" placeholder="ex: https://www.example.com" value="<?php echo e(old('website')); ?>">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Department</div>
              <div class="form-control"><input type="text" name="department" value="<?php echo e(old('department')); ?>"></div>
            </div>
            <div class="form-row">
              <div class="form-label">Designation</div>
              <div class="form-control"><input type="text" name="designation" value="<?php echo e(old('designation')); ?>"></div>
            </div>
            <div class="form-row">
              <div class="form-label">X (Twitter)</div>
              <div class="form-control">
                <div class="social-row">
                  <div class="social-icon-box" style="background:#000;border-color:#000;color:#fff;font-weight:700;font-size:12px;">𝕏</div>
                  <input type="text" name="twitter" class="social-input" value="<?php echo e(old('twitter')); ?>">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Skype</div>
              <div class="form-control">
                <div class="social-row">
                  <div class="social-icon-box" style="color:#00aff0;">🅢</div>
                  <input type="text" name="skype" class="social-input" value="<?php echo e(old('skype')); ?>">
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Facebook</div>
              <div class="form-control">
                <div class="social-row">
                  <div class="social-icon-box" style="color:#1877f2;font-weight:700;">f</div>
                  <input type="text" name="facebook" class="social-input" value="<?php echo e(old('facebook')); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <div id="tab-address" class="form-body tab-content">
          <div class="address-grid">
            <div class="address-section">
              <h4>Billing Address</h4>
              <div class="address-form-row"><label>Attention</label><input type="text" name="billing[attention]" value="<?php echo e(old('billing.attention')); ?>"></div>
              <div class="address-form-row"><label>Country/Region</label>
                <select name="billing[country]">
                  <option value="">Select</option>
                  <option value="India" <?php echo e(old('billing.country')=='India'?'selected':''); ?>>India</option>
                  <option value="USA"   <?php echo e(old('billing.country')=='USA'  ?'selected':''); ?>>USA</option>
                  <option value="UK"    <?php echo e(old('billing.country')=='UK'   ?'selected':''); ?>>UK</option>
                </select>
              </div>
              <div class="address-form-row"><label>Street 1</label><textarea name="billing[street1]"><?php echo e(old('billing.street1')); ?></textarea></div>
              <div class="address-form-row"><label>Street 2</label><textarea name="billing[street2]"><?php echo e(old('billing.street2')); ?></textarea></div>
              <div class="address-form-row"><label>City</label><input type="text" name="billing[city]" value="<?php echo e(old('billing.city')); ?>"></div>
              <div class="address-form-row"><label>State</label>
                <select name="billing[state]">
                  <option value="">Select</option>
                  <option value="Tamil Nadu"  <?php echo e(old('billing.state')=='Tamil Nadu' ?'selected':''); ?>>Tamil Nadu</option>
                  <option value="Karnataka"   <?php echo e(old('billing.state')=='Karnataka'  ?'selected':''); ?>>Karnataka</option>
                  <option value="Maharashtra" <?php echo e(old('billing.state')=='Maharashtra'?'selected':''); ?>>Maharashtra</option>
                  <option value="Delhi"       <?php echo e(old('billing.state')=='Delhi'      ?'selected':''); ?>>Delhi</option>
                </select>
              </div>
              <div class="address-form-row"><label>Pin Code</label><input type="text" name="billing[pincode]" value="<?php echo e(old('billing.pincode')); ?>"></div>
              <div class="address-form-row"><label>Phone</label><input type="text" name="billing[phone]" value="<?php echo e(old('billing.phone')); ?>"></div>
              <div class="address-form-row"><label>Fax</label><input type="text" name="billing[fax]" value="<?php echo e(old('billing.fax')); ?>"></div>
            </div>
            <div class="address-section">
              <h4>Shipping Address <a href="#" onclick="copyBilling();return false;">↓ Copy billing address</a></h4>
              <div class="address-form-row"><label>Attention</label><input type="text" name="shipping[attention]" value="<?php echo e(old('shipping.attention')); ?>"></div>
              <div class="address-form-row"><label>Country/Region</label>
                <select name="shipping[country]">
                  <option value="">Select</option>
                  <option value="India" <?php echo e(old('shipping.country')=='India'?'selected':''); ?>>India</option>
                  <option value="USA"   <?php echo e(old('shipping.country')=='USA'  ?'selected':''); ?>>USA</option>
                  <option value="UK"    <?php echo e(old('shipping.country')=='UK'   ?'selected':''); ?>>UK</option>
                </select>
              </div>
              <div class="address-form-row"><label>Street 1</label><textarea name="shipping[street1]"><?php echo e(old('shipping.street1')); ?></textarea></div>
              <div class="address-form-row"><label>Street 2</label><textarea name="shipping[street2]"><?php echo e(old('shipping.street2')); ?></textarea></div>
              <div class="address-form-row"><label>City</label><input type="text" name="shipping[city]" value="<?php echo e(old('shipping.city')); ?>"></div>
              <div class="address-form-row"><label>State</label>
                <select name="shipping[state]">
                  <option value="">Select</option>
                  <option value="Tamil Nadu"  <?php echo e(old('shipping.state')=='Tamil Nadu' ?'selected':''); ?>>Tamil Nadu</option>
                  <option value="Karnataka"   <?php echo e(old('shipping.state')=='Karnataka'  ?'selected':''); ?>>Karnataka</option>
                  <option value="Maharashtra" <?php echo e(old('shipping.state')=='Maharashtra'?'selected':''); ?>>Maharashtra</option>
                  <option value="Delhi"       <?php echo e(old('shipping.state')=='Delhi'      ?'selected':''); ?>>Delhi</option>
                </select>
              </div>
              <div class="address-form-row"><label>Pin Code</label><input type="text" name="shipping[pincode]" value="<?php echo e(old('shipping.pincode')); ?>"></div>
              <div class="address-form-row"><label>Phone</label><input type="text" name="shipping[phone]" value="<?php echo e(old('shipping.phone')); ?>"></div>
              <div class="address-form-row"><label>Fax</label><input type="text" name="shipping[fax]" value="<?php echo e(old('shipping.fax')); ?>"></div>
            </div>
          </div>
          <div class="address-note"><strong>Note:</strong><ul><li>Enter your Billing and Shipping address here.</li></ul></div>
        </div>

       
<div id="tab-contactPersons" class="form-body tab-content">
  <table class="contact-table">
    <thead>
      <tr>
        <th>Salutation</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email Address</th>
        <th>Work Phone</th>
        <th>Mobile</th>
        
        <th></th>
      </tr>
    </thead>
    <tbody id="contactPersonsBody"></tbody>
  </table>
  <button type="button" class="add-person-btn" onclick="addContactRow()">⊕ Add Contact Person</button>
</div>

<div id="tab-customFields" class="form-body tab-content">
  <?php if($customFields->isEmpty()): ?>
    <p style="color:#888;font-size:13px;">
      No custom fields yet. 
      <a href="<?php echo e(route('field_customization.create')); ?>?from=customers" 
         style="color:#3b6cf8;">
        + Add Custom Fields
      </a>
    </p>
  <?php else: ?>
    <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="form-row">

        
        <div class="form-label <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>">
          <?php echo e($field->name); ?>

          <?php if($field->mandatory == 'yes'): ?>
            <span style="color:#e53935;">*</span>
          <?php endif; ?>
        </div>

        
        <div class="form-control">
          <?php
            $fieldConfig = $field->additional_config ?? [];
            $fieldName   = 'custom_fields[' . $field->id . ']';
            $oldVal      = old('custom_fields.' . $field->id);
          ?>

          <?php switch($field->data_type):

            case ('string'): ?>
            <?php case ('char'): ?>
            <?php case ('email'): ?>
            <?php case ('phone'): ?>
            <?php case ('url'): ?>
            <?php case ('ip_address'): ?>
              <input
                type="<?php echo e($field->data_type == 'email' ? 'email' : ($field->data_type == 'url' ? 'url' : 'text')); ?>"
                name="<?php echo e($fieldName); ?>"
                value="<?php echo e($oldVal ?? ($fieldConfig['default_value'] ?? '')); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                <?php if(!empty($fieldConfig['char_limit'])): ?> maxlength="<?php echo e($fieldConfig['char_limit']); ?>" <?php endif; ?>
                placeholder="<?php echo e($fieldConfig['help_text'] ?? ''); ?>"
              >
            <?php break; ?>

            <?php case ('text'): ?>
            <?php case ('longtext'): ?>
              <textarea
                name="<?php echo e($fieldName); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                placeholder="<?php echo e($fieldConfig['help_text'] ?? ''); ?>"
              ><?php echo e($oldVal ?? ($fieldConfig['default_value'] ?? '')); ?></textarea>
            <?php break; ?>

            <?php case ('integer'): ?>
            <?php case ('biginteger'): ?>
            <?php case ('smallinteger'): ?>
            <?php case ('tinyinteger'): ?>
            <?php case ('decimal'): ?>
            <?php case ('float'): ?>
            <?php case ('double'): ?>
            <?php case ('currency'): ?>
            <?php case ('percentage'): ?>
              <input
                type="number"
                name="<?php echo e($fieldName); ?>"
                value="<?php echo e($oldVal ?? ($fieldConfig['default_value'] ?? '')); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                placeholder="<?php echo e($fieldConfig['help_text'] ?? ''); ?>"
                step="<?php echo e(in_array($field->data_type, ['decimal','float','double','currency','percentage']) ? 'any' : '1'); ?>"
              >
            <?php break; ?>

            <?php case ('boolean'): ?>
              <label class="portal-check">
                <input
                  type="checkbox"
                  name="<?php echo e($fieldName); ?>"
                  value="1"
                  <?php echo e(($oldVal ?? ($fieldConfig['default_value'] ?? '')) == '1' ? 'checked' : ''); ?>

                >
                <?php echo e($fieldConfig['help_text'] ?? $field->name); ?>

              </label>
            <?php break; ?>

            <?php case ('date'): ?>
              <input
                type="date"
                name="<?php echo e($fieldName); ?>"
                value="<?php echo e($oldVal ?? ($fieldConfig['default_date'] ?? '')); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                style="max-width:200px;"
              >
            <?php break; ?>

            <?php case ('datetime'): ?>
            <?php case ('timestamp'): ?>
              <input
                type="datetime-local"
                name="<?php echo e($fieldName); ?>"
                value="<?php echo e($oldVal ?? ''); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                style="max-width:260px;"
              >
            <?php break; ?>

            <?php case ('time'): ?>
              <input
                type="time"
                name="<?php echo e($fieldName); ?>"
                value="<?php echo e($oldVal ?? ($fieldConfig['default_time'] ?? '')); ?>"
                <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>

                style="max-width:160px;"
              >
            <?php break; ?>

            <?php case ('array'): ?>
              <?php
                $options = array_filter(array_map('trim',
                  explode("\n", $fieldConfig['options'] ?? '')
                ));
              ?>
              <?php if(count($options)): ?>
                <select name="<?php echo e($fieldName); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>>
                  <option value="">-- Select --</option>
                  <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($opt); ?>"
                      <?php echo e(($oldVal ?? ($fieldConfig['default_value'] ?? '')) == $opt ? 'selected' : ''); ?>>
                      <?php echo e($opt); ?>

                    </option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              <?php else: ?>
                <input type="text" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldVal ?? ''); ?>">
              <?php endif; ?>
            <?php break; ?>

            <?php default: ?>
              <input type="text" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldVal ?? ''); ?>">

          <?php endswitch; ?>

          
          <?php if(!empty($fieldConfig['help_text'])): ?>
            <div style="font-size:11px;color:#999;margin-top:4px;">
              <?php echo e($fieldConfig['help_text']); ?>

            </div>
          <?php endif; ?>

        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
</div>


        
        <div id="tab-reportingTags" class="form-body tab-content">
          <p style="color:#888;font-size:13px;">You've not created any Reporting Tags. Start creating by going to <em>More Settings ➡ Reporting Tags</em>.</p>
        </div>

<div id="tab-assignLocation" class="form-body tab-content">
  <div id="assign-loc-wrap">
    <span class="loc-loading">Loading...</span>
  </div>
</div>

        
        <div id="tab-remarks" class="form-body tab-content">
          <div class="form-row">
            <div class="form-label">Remarks <span style="color:#999;font-size:12px;">(Internal Use)</span></div>
            <div class="form-control" style="max-width:600px;"><textarea name="remarks" rows="6"><?php echo e(old('remarks')); ?></textarea></div>
          </div>
        </div>

        <div class="form-footer">
          <button type="submit" class="btn-save">Save</button>
          <a href="<?php echo e(route('customers.index')); ?>" class="btn-cancel">Cancel</a>
        </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var defaultType = "<?php echo e($config['default_customer_type'] ?? 'business'); ?>";
    var savedType   = "<?php echo e(old('customer_type')); ?>";
    selectCustomerType(savedType || defaultType);
});
</script>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ════════════════════════════════════════════════════
//  DISPLAY NAME — SIMPLE DROPDOWN (no tags)
// ════════════════════════════════════════════════════

var dnActiveIdx = -1;

// Generate name variants from company / first+last
function generateVariants() {
    var company = (document.getElementById('companyName')?.value || '').trim();
    var first   = (document.getElementById('firstName')?.value  || '').trim();
    var last    = (document.getElementById('lastName')?.value   || '').trim();

    var variants = [];

    // ── Company-based variants ──
    if (company) {
        variants.push(company);                          // Full company name

        var words = company.split(/\s+/);

        if (words.length > 1) variants.push(words[0]);  // First word

        if (words.length > 2)                           // First + Last word
            variants.push(words[0] + ' ' + words[words.length - 1]);

        // Strip common suffixes
        var suffixes = ['Private Limited','Pvt Ltd','Pvt. Ltd.','Pvt. Ltd','Ltd','LLP','Inc','Corp','Co'];
        suffixes.forEach(function(s) {
            var reg = new RegExp('\\s*' + s.replace(/\./g,'\\.') + '\\.?$', 'i');
            var stripped = company.replace(reg,'').trim();
            if (stripped && stripped !== company) variants.push(stripped);
        });

        // Acronym  e.g. "Techvolt Pvt Ltd" → "TPL"
        var acronym = words.map(function(w){ return w[0]; }).join('').toUpperCase();
        if (acronym.length >= 2 && acronym.length <= 5) variants.push(acronym);
    }

    // ── Person-based variants ──
    if (first || last) {
        var full = (first + ' ' + last).trim();
        if (full) variants.push(full);
        if (first) variants.push(first);
        if (last)  variants.push(last);
    }

    // Deduplicate + filter empty
    return variants.filter(function(v, i, a){ return v && a.indexOf(v) === i; });
}

// Highlight matched portion
function boldMatch(text, query) {
    if (!query) return escHtml(text);
    var idx = text.toLowerCase().indexOf(query.toLowerCase());
    if (idx === -1) return escHtml(text);
    return escHtml(text.slice(0, idx))
         + '<span class="dn-bold">' + escHtml(text.slice(idx, idx + query.length)) + '</span>'
         + escHtml(text.slice(idx + query.length));
}

function onCategoryChange() {
    var sel       = document.getElementById('customerCategory');
    var opt       = sel.options[sel.selectedIndex];
    var layerId   = opt ? opt.dataset.layerId   : '';
    var countryId = opt ? opt.dataset.countryId : '';

    renderAssignLocation(layerId || null, countryId || null);
}

function toggleCustCatList() {
  var list = document.getElementById('custCatList');
  list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

// ── Used value ids cache ──
var _usedValueIds        = [];
var _currentCatLayerId   = '';  
var _currentCatCountryId = '';  

function selectCustCat(el) {
    var name      = el.dataset.name;
    var layerId   = el.dataset.layerId;
    var countryId = el.dataset.countryId;
    var loc       = el.dataset.loc;

    // ✅ Cache store
    _currentCatLayerId   = layerId   || '';
    _currentCatId   = el.dataset.id || null; 
    _currentCatName = name || '';
    _currentCatCountryId = countryId || '';
    
             
    document.getElementById('customerCategoryValue').value = name;
document.getElementById('customerSubCatId').value = '';
if (_currentCatId) {
    loadSubCats(_currentCatId);
} else {
    document.getElementById('subCatWrap').style.display = 'none';
}
    var txt = document.getElementById('custCatSelectedText');
    if (name) {
        txt.style.color = '#333';
        if (loc) {
            txt.innerHTML = '<span style="font-weight:500;">' + name + '</span>'
                + ' <span style="font-size:11px;color:#fff;background:#3b6cf8;'
                + 'padding:2px 8px;border-radius:10px;margin-left:6px;">' + loc + '</span>';
        } else {
            txt.textContent = name;
        }
    } else {
        txt.style.color = '#999';
        txt.textContent = '-- Select Category --';
    }

    document.getElementById('custCatList').style.display = 'none';

    if (name && layerId) {
        fetchUsedLocations(name).then(function() {
            renderAssignLocation(layerId || null, countryId || null);
        });
    } else {
        _usedValueIds = [];
        renderAssignLocation(layerId || null, countryId || null);
    }
}

// ✅ அந்த category-ல already used value_ids fetch பண்ணு
async function fetchUsedLocations(categoryName) {
    try {
        var res = await fetch(
            '/customers/used-locations?category=' + encodeURIComponent(categoryName),
            { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } }
        );
        var json = await res.json();
        _usedValueIds = json.used_value_ids || [];
    } catch(e) {
        console.error('fetchUsedLocations failed', e);
        _usedValueIds = [];
    }
}
// Render dropdown
function renderDnDropdown(variants, query) {
    var dd = document.getElementById('dnDropdown');
    dnActiveIdx = -1;

    if (!variants.length) {
        dd.innerHTML = '<div class="dn-empty">No suggestions</div>';
        dd.classList.add('open');
        return;
    }

    dd.innerHTML = variants.map(function(name) {
        return '<div class="dn-item" data-value="' + escAttr(name) + '">' +
               '<div class="dn-avatar">' + escHtml(name[0].toUpperCase()) + '</div>' +
               '<span>' + boldMatch(name, query) + '</span>' +
               '</div>';
    }).join('');

    // Click to select
    dd.querySelectorAll('.dn-item').forEach(function(item) {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            selectDisplayName(this.dataset.value);
        });
    });

    dd.classList.add('open');
}

function closeDnDropdown() {
    var dd = document.getElementById('dnDropdown');
    dd.classList.remove('open');
    dd.innerHTML = '';
    dnActiveIdx = -1;
}

// Set value and close
function selectDisplayName(value) {
    document.getElementById('displayName').value = value;
    closeDnDropdown();
}

// Called whenever company name / first / last changes
function refreshDisplaySuggestions() {
    var dn      = document.getElementById('displayName');
    var variants = generateVariants();

    // Auto-fill display name if still empty
    if (!dn.value && variants.length) {
        dn.value = variants[0];
    }

    // Show dropdown filtered by current display name value
    var query    = dn.value.trim();
    var filtered = variants.filter(function(v) {
        return v.toLowerCase().includes(query.toLowerCase());
    });
    if (filtered.length) renderDnDropdown(filtered, query);
    else closeDnDropdown();
}

// Called when user manually types in display name field
function onDisplayNameType() {
    var query    = document.getElementById('displayName').value.trim();
    var variants = generateVariants();

    if (!query) {
        // Show all variants
        if (variants.length) renderDnDropdown(variants, '');
        else closeDnDropdown();
        return;
    }

    // Filter variants that match typed query
    var filtered = variants.filter(function(v) {
        return v.toLowerCase().includes(query.toLowerCase());
    });

    if (filtered.length) renderDnDropdown(filtered, query);
    else closeDnDropdown();
}

// Keyboard navigation on display name input
document.getElementById('displayName').addEventListener('keydown', function(e) {
    var items = document.querySelectorAll('#dnDropdown .dn-item');
    if (!items.length) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        dnActiveIdx = Math.min(dnActiveIdx + 1, items.length - 1);
        items.forEach(function(el, i){ el.classList.toggle('active', i === dnActiveIdx); });
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        dnActiveIdx = Math.max(dnActiveIdx - 1, 0);
        items.forEach(function(el, i){ el.classList.toggle('active', i === dnActiveIdx); });
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (dnActiveIdx >= 0 && items[dnActiveIdx]) {
            selectDisplayName(items[dnActiveIdx].dataset.value);
        }
    } else if (e.key === 'Escape') {
        closeDnDropdown();
    }
});

// Close dropdown on outside click
document.addEventListener('mousedown', function(e) {
    var wrapper = document.getElementById('dnWrapper');
    if (wrapper && !wrapper.contains(e.target)) closeDnDropdown();
});

// ════════════════════════════════════════════════════
//  CATEGORY FUNCTIONS
// ════════════════════════════════════════════════════

// ════════════════════════════════════════════════════
//  OTHER HELPERS
// ════════════════════════════════════════════════════

function selectCustomerType(type) {
    document.getElementById('opt-business').classList.toggle('selected',   type === 'business');
    document.getElementById('opt-individual').classList.toggle('selected', type === 'individual');
    document.querySelector('input[value="business"]').checked   = (type === 'business');
    document.querySelector('input[value="individual"]').checked = (type === 'individual');

    var isBusiness = (type === 'business');
    document.getElementById('row-companyName').style.display = isBusiness ? 'flex' : 'none';

  
}
function showTab(tabId, el) {
    document.querySelectorAll('.tab-content').forEach(function(t){ t.style.display='none'; });
    document.querySelectorAll('.tab').forEach(function(t){ t.classList.remove('active'); });
    document.getElementById('tab-'+tabId).style.display = 'block';
    el.classList.add('active');

    if (tabId === 'assignLocation') {
        // ✅ customerCategory select இல்லை — cache use பண்ணு
        var layerId   = _currentCatLayerId   || '';
        var countryId = _currentCatCountryId || '';

        if (!_lcLayers.length) {
            loadLcLayers().then(function() {
                renderAssignLocation(layerId || null, countryId || null);
            });
        } else {
            renderAssignLocation(layerId || null, countryId || null);
        }
    }
} 
function toggleMoreDetails() {
    var s=document.getElementById('moreDetailsSection'), b=document.getElementById('addMoreBtn');
    var h=s.style.display==='none'; s.style.display=h?'block':'none';
    b.textContent=h?'－ Show Less':'＋ Add More Details';
}


var contactRowIndex = 0;
// ════════════════════════════════════════════════════
//  LOCATION CASCADE — load once, cascade in memory
// ════════════════════════════════════════════════════
// ════════════════════════════════════════════════════
//  LOCATION CASCADE — FIXED
// ════════════════════════════════════════════════════
var _lcLayers = [];
var _lcValues = [];

async function loadLcLayers() {
    try {
        var res = await fetch('/lc-tree', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        var json = await res.json();
        if (!json.success) return;

        _lcLayers = (json.layers || []).map(function(l) {
            return {
                id:              String(l.id),
                name:            l.name,
                depth:           parseInt(l.depth),
                is_global:       !!l.is_global,
                parent_value_id: l.parent_value_id != null ? String(l.parent_value_id) : null,
            };
        });

        _lcValues = (json.values || []).map(function(v) {
            return {
                id:              String(v.id),
                layer_id:        String(v.layer_id),
                parent_value_id: v.parent_value_id != null ? String(v.parent_value_id) : null,
                value:           v.value,
            };
        });

        // ✅ Load முடிஞ்சதும் current category render பண்ணு
        var sel       = document.getElementById('customerCategory');
        var opt       = sel ? sel.options[sel.selectedIndex] : null;
        var layerId   = opt ? opt.dataset.layerId   : '';
        var countryId = opt ? opt.dataset.countryId : '';
        if (layerId) renderAssignLocation(layerId, countryId);

    } catch(e) { console.error('lc-tree load failed', e); }
}

// Root layer = depth 0, parent_value_id null, not global
function getRootLayer() {
    return _lcLayers.find(function(l) {
        return l.depth === 0 && l.parent_value_id === null && !l.is_global;
    }) || null;
}

// Get values for a layer filtered by parent_value_id
function getLcValues(layerId, parentValueId) {
    return _lcValues.filter(function(v) {
        if (v.layer_id !== String(layerId)) return false;
        if (parentValueId != null)
            return v.parent_value_id === String(parentValueId);
        return v.parent_value_id === null;
    });
}

// KEY FIX: Find child layer where lc_layers.parent_value_id = selected lc_layer_values.id
// e.g. select india(id:8) → find layer where parent_value_id = '8' → state layer
// e.g. select Tamilnadu(id:10) → find layer where parent_value_id = '10' → district layer
function getChildLayer(selectedValueId) {
    // Specific child layer for this exact value
    var specific = _lcLayers.find(function(l) {
        return !l.is_global && l.parent_value_id === String(selectedValueId);
    });
    if (specific) return specific;

    // Global fallback — find global layer at the next depth
    // First find the current depth by looking up the selected value's layer
    var selectedValue = _lcValues.find(function(v) {
        return v.id === String(selectedValueId);
    });
    if (!selectedValue) return null;

    var currentLayer = _lcLayers.find(function(l) {
        return l.id === selectedValue.layer_id;
    });
    if (!currentLayer) return null;

    var nextDepth = currentLayer.depth + 1;
    return _lcLayers.find(function(l) {
        return l.is_global && l.depth === nextDepth;
    }) || null;
}

// Build one dropdown row
function buildLocSelect(rowIndex, layer, values, depth) {
    var selectId = 'loc_' + rowIndex + '_d' + depth;
    var name     = 'contact_persons[' + rowIndex + '][location][' + layer.id + ']';

    var opts = '<option value="">-- ' + capFirst(layer.name) + ' --</option>';
    values.forEach(function(v) {
        opts += '<option value="' + escAttr(v.id) + '">' + escHtml(v.value) + '</option>';
    });

    return '<div class="loc-row" id="' + selectId + '_row"'
         + ' style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">'
         + '<span style="font-size:11px;color:#666;min-width:56px;font-weight:600;text-transform:capitalize;">'
         + escHtml(layer.name) + '</span>'
         + '<select id="' + selectId + '" name="' + name + '"'
         + ' data-row="' + rowIndex + '"'
         + ' data-layer-id="' + layer.id + '"'
         + ' data-depth="' + depth + '"'
         + ' onchange="onLocChange(this)"'
         + ' style="flex:1;padding:4px 7px;border:1px solid #d0d0d0;border-radius:4px;font-size:12px;">'
         + opts + '</select></div>';
}

// Render root layer dropdown for a contact row
function renderLocCell(rowIndex) {
    var cell = document.getElementById('loc-cell-' + rowIndex);
    if (!cell) return;

    var rootLayer = getRootLayer();
    if (!rootLayer) {
        cell.innerHTML = '<span style="font-size:11px;color:#aaa;">No location layers</span>';
        return;
    }

    var rootValues = getLcValues(rootLayer.id, null);
    cell.innerHTML = '<div id="loc-wrap-' + rowIndex + '" style="min-width:180px;">'
                   + buildLocSelect(rowIndex, rootLayer, rootValues, 0)
                   + '</div>';
}

// On change — cascade child dropdowns using VALUE ID as key
function onLocChange(sel) {
    var rowIndex     = parseInt(sel.dataset.row);
    var changedDepth = parseInt(sel.dataset.depth);
    var selectedId   = sel.value; // this is lc_layer_values.id

    var wrap = document.getElementById('loc-wrap-' + rowIndex);
    if (!wrap) return;

    // Remove all dropdowns deeper than current
    wrap.querySelectorAll('.loc-row').forEach(function(row) {
        var s = row.querySelector('select');
        if (s && parseInt(s.dataset.depth) > changedDepth) row.remove();
    });

    if (!selectedId) return;

    // Find child layer where lc_layers.parent_value_id = selectedId
    var childLayer = getChildLayer(selectedId);
    if (!childLayer) return;

    // Get values for child layer filtered by selected value as parent
    var childValues = getLcValues(childLayer.id, selectedId);
    if (!childValues.length) return;

    wrap.insertAdjacentHTML('beforeend',
        buildLocSelect(rowIndex, childLayer, childValues, changedDepth + 1)
    );
}

// Contact row with location column
var contactRowIndex = 0;

function makeContactRow() {
    var i  = contactRowIndex++;
    var tr = document.createElement('tr');
    tr.id  = 'contact-row-' + i;
    tr.innerHTML =
        '<td><select name="contact_persons['+i+'][salutation]" style="width:88px;">'
        + '<option></option><option>Mr.</option><option>Mrs.</option>'
        + '<option>Ms.</option><option>Dr.</option>'
        + '</select></td>'
        + '<td><input type="text"  name="contact_persons['+i+'][first_name]"></td>'
        + '<td><input type="text"  name="contact_persons['+i+'][last_name]"></td>'
        + '<td><input type="email" name="contact_persons['+i+'][email]"></td>'
        + '<td><input type="text"  name="contact_persons['+i+'][work_phone]"></td>'
        + '<td><input type="text"  name="contact_persons['+i+'][mobile]"></td>'
        + '<td class="action-col">'
        +   '<button type="button" class="remove-row-btn" onclick="removeContactRow(this)">×</button>'
        + '</td>';
    return tr;
}

function addContactRow() {
    document.getElementById('contactPersonsBody').appendChild(makeContactRow());
}

function removeContactRow(btn) {
    var row   = btn.closest('tr');
    var tbody = document.getElementById('contactPersonsBody');
    if (tbody.rows.length > 1) {
        row.remove();
    } else {
        row.querySelectorAll('input').forEach(function(i){ i.value = ''; });
        row.querySelectorAll('select').forEach(function(s){ s.selectedIndex = 0; });
    }
}

var _currentCatId = null;

function loadSubCats(catId) {
    document.getElementById('subCatWrap').style.display = 'block';
    document.getElementById('subCatChips').innerHTML = '<span style="font-size:12px;color:#aaa;">Loading...</span>';
    fetch('/user-sub-categories/by-category/' + catId, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(function(r) { return r.json(); })
    .then(function(json) {
        var subs = json.data || [];
        if (!subs.length) {
            document.getElementById('subCatChips').innerHTML = '<span style="font-size:12px;color:#aaa;font-style:italic;">No sub-categories found.</span>';
            return;
        }
        renderSubChips(subs);
    })
    .catch(function() {
        document.getElementById('subCatChips').innerHTML = '<span style="color:#e53935;font-size:12px;">Failed to load.</span>';
    });
}

function renderSubChips(subs) {
    var selected = document.getElementById('customerSubCatId').value;
    document.getElementById('subCatChips').innerHTML = subs.map(function(s) {
        var isSel = String(s.id) === String(selected);
        return '<div class="sub-chip' + (isSel ? ' selected' : '') + '" onclick="toggleSubChip(' + s.id + ', this)" data-id="' + s.id + '">'
             + '<span class="sub-chip-dot"></span>'
             + escHtml(s.name)
             + (s.code ? ' <span style="font-size:10px;opacity:0.75;">(' + escHtml(s.code) + ')</span>' : '')
             + '</div>';
    }).join('');
}

function toggleSubChip(id, el) {
    var h = document.getElementById('customerSubCatId');
    document.querySelectorAll('#subCatChips .sub-chip').forEach(function(c) { c.classList.remove('selected'); });
    if (h.value === String(id)) {
        h.value = '';
    } else {
        h.value = id;
        el.classList.add('selected');
    }
}
function renderAssignLocation(categoryLayerId, categoryCountryId) {
    var wrap = document.getElementById('assign-loc-wrap');
    if (!wrap) return;

    if (!categoryLayerId) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">Please select a Customer Category first.</p>';
        return;
    }

    var targetLayer = _lcLayers.find(function(l) {
        return String(l.id) === String(categoryLayerId);
    });

    if (!targetLayer) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">Layer not found.</p>';
        return;
    }

    var layerValues = [];
    if (categoryCountryId) {
        layerValues = _lcValues.filter(function(v) {
            return String(v.layer_id)        === String(categoryLayerId)
                && String(v.parent_value_id) === String(categoryCountryId);
        });
    }
    if (!layerValues.length) {
        layerValues = _lcValues.filter(function(v) {
            return String(v.layer_id) === String(categoryLayerId);
        });
    }

    if (!layerValues.length) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">No values found.</p>';
        return;
    }

    // ✅ Used values disable — available values enable
    var allUsed = layerValues.every(function(v) {
        return _usedValueIds.includes(String(v.id));
    });

    var opts = '<option value="">-- Select ' + capFirst(targetLayer.name) + ' --</option>';
    layerValues.forEach(function(v) {
        var isUsed = _usedValueIds.includes(String(v.id));
        opts += '<option value="' + escAttr(v.id) + '"'
             + (isUsed ? ' disabled style="color:#ccc;"' : '')
             + '>'
             + escHtml(v.value)
             + (isUsed ? ' (Already Assigned)' : '')
             + '</option>';
    });

    // ✅ எல்லாமே used ஆயிட்டா warning காட்டு
    var warningHtml = allUsed
        ? '<div style="margin-top:8px;padding:8px 12px;background:#fff3cd;border:1px solid #ffc107;'
        + 'border-radius:4px;font-size:12px;color:#856404;">'
        + '⚠️ All locations for this category are already assigned to other customers.'
        + '</div>'
        : '';

    wrap.innerHTML =
        '<div class="form-row">'
      + '<div class="form-label" style="text-transform:capitalize;">'
      +   escHtml(targetLayer.name)
      + '</div>'
      + '<div class="form-control" style="max-width:320px;">'
      + '<select id="assign-loc-select"'
      + '  name="assign_location_value_id"'
      + '  data-layer-id="' + escAttr(categoryLayerId) + '"'
      + '  data-country-id="' + escAttr(categoryCountryId || '') + '"'
      + '  onchange="onAssignLocChange(this)"'
      + (allUsed ? ' disabled' : '') + '>'
      + opts
      + '</select>'
      + warningHtml
      + '</div></div>'
      + '<input type="hidden" name="assign_location[layer_id]"  id="al_layer_id"  value="">'
      + '<input type="hidden" name="assign_location[value_id]"  id="al_value_id"  value="">'
      + '<input type="hidden" name="assign_location[path]"      id="al_path"      value="">'
      + '<input type="hidden" name="assign_location[value_ids]" id="al_value_ids" value="">';
}
// Select change → path calculate பண்ணு
function onAssignLocChange(sel) {
    var selectedValueId = sel.value;

    if (!selectedValueId) {
        document.getElementById('al_layer_id').value  = '';
        document.getElementById('al_value_id').value  = '';
        document.getElementById('al_path').value      = '';
        document.getElementById('al_value_ids').value = '';
        return;
    }

    // ✅ Selected value-இல் இருந்து root வரைக்கும் parent chain trace பண்ணு
    var chain = buildValueChain(selectedValueId);

    var path     = chain.map(function(v) { return v.value; }).join(' → ');
    var valueIds = chain.map(function(v) { return v.id; });

    document.getElementById('al_layer_id').value  = sel.dataset.layerId;
    document.getElementById('al_value_id').value  = selectedValueId;
    document.getElementById('al_path').value      = path;
    document.getElementById('al_value_ids').value = JSON.stringify(valueIds);
}

// ✅ Root → Leaf order-ல parent chain build பண்ணு
function buildValueChain(valueId) {
    var chain = [];
    var current = _lcValues.find(function(v) {
        return String(v.id) === String(valueId);
    });

    while (current) {
        chain.unshift(current); // front-ல add பண்ணு (root first)
        if (!current.parent_value_id) break;

        current = _lcValues.find(function(v) {
            return String(v.id) === String(current.parent_value_id);
        });
    }

    return chain;
}

function capFirst(s) { return String(s).charAt(0).toUpperCase() + String(s).slice(1); }
function copyBilling() {
    ['attention','street1','street2','city','pincode','phone','fax'].forEach(function(f){
        var s=document.querySelector('[name="billing['+f+']"]'), d=document.querySelector('[name="shipping['+f+']"]');
        if(s&&d) d.value=s.value;
    });
    var sc=document.querySelector('[name="billing[country]"]'), dc=document.querySelector('[name="shipping[country]"]'); if(sc&&dc) dc.value=sc.value;
    var ss=document.querySelector('[name="billing[state]"]'),   ds=document.querySelector('[name="shipping[state]"]');   if(ss&&ds) ds.value=ss.value;
}

function escHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function escAttr(s){ return String(s).replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

// Init
// Init
loadLcLayers().then(function() {
    document.getElementById('contactPersonsBody').innerHTML = '';
    addContactRow();
    addContactRow();
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/customers/create.blade.php ENDPATH**/ ?>