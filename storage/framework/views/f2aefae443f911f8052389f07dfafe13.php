<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>Customers | Inventory</title>
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
  .topbar { display: flex; align-items: center; gap: 12px; padding: 0 20px; height: 52px; background: #fff; border-bottom: 1px solid #e8e8e8; flex-shrink: 0; }
  .topbar-search { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 6px; padding: 6px 12px; min-width: 280px; }
  .topbar-search input { border: none; background: transparent; outline: none; font-size: 13px; color: #333; width: 200px; }
  .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
  .topbar-btn { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; position: relative; }
  .topbar-btn:hover { background: #f5f5f5; }
  .avatar { width: 32px; height: 32px; border-radius: 50%; background: #3b6cf8; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 13px; cursor: pointer; }
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
  .content { flex: 1; overflow-y: auto; padding: 24px; }
  .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .page-title { font-size: 20px; font-weight: 700; color: #222; }

  /* ── Header right actions ── */
  .header-actions { display: flex; align-items: center; gap: 8px; }
  .btn-preferences {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fff; color: #555; border: 1px solid #d0d0d0;
    border-radius: 6px; padding: 7px 14px; font-size: 13px;
    font-weight: 500; cursor: pointer; text-decoration: none;
    transition: all 0.15s;
  }
  .btn-preferences:hover { background: #f5f5f5; border-color: #aaa; color: #333; }
  .btn-new { display: inline-flex; align-items: center; gap: 6px; background: #3b6cf8; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; }
  .btn-new:hover { background: #2b5ce0; }

  .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
  .alert-danger  { background: #fde8e8; color: #991b1b; border: 1px solid #fca5a5; padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
  .filter-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
  .filter-search { display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #d0d0d0; border-radius: 6px; padding: 7px 12px; min-width: 260px; }
  .filter-search input { border: none; outline: none; font-size: 13px; color: #333; width: 100%; background: transparent; }
  .filter-select { padding: 7px 12px; border: 1px solid #d0d0d0; border-radius: 6px; font-size: 13px; background: #fff; outline: none; cursor: pointer; color: #333; appearance: none; padding-right: 28px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; }
  .filter-count { margin-left: auto; font-size: 12px; color: #888; }
  .table-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); overflow: hidden; }
  .data-table { width: 100%; border-collapse: collapse; }
  .data-table thead tr { background: #f8f9fc; border-bottom: 2px solid #e8eaf0; }
  .data-table thead th { padding: 11px 14px; font-size: 11px; font-weight: 700; color: #555; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; white-space: nowrap; }
  .data-table thead th.sortable { cursor: pointer; user-select: none; }
  .data-table thead th.sortable:hover { color: #3b6cf8; }
  .data-table tbody tr { border-bottom: 1px solid #f0f2f7; transition: background 0.1s; }
  .data-table tbody tr:last-child { border-bottom: none; }
  .data-table tbody tr:hover { background: #f8f9ff; }
  .data-table tbody td { padding: 11px 14px; font-size: 13px; color: #333; vertical-align: middle; }
  .cust-avatar { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0; }
  .cust-name-cell { display: flex; align-items: center; gap: 10px; }
  .cust-name-info { display: flex; flex-direction: column; }
  .cust-name-main { font-weight: 600; color: #222; }
  .cust-name-sub  { font-size: 11px; color: #888; margin-top: 1px; }
  .badge { display: inline-block; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .badge-business    { background: #e8efff; color: #2d5be3; }
  .badge-individual  { background: #fef3e2; color: #b45309; }
  .action-wrap { display: flex; gap: 4px; opacity: 0; transition: opacity 0.15s; }
  .data-table tbody tr:hover .action-wrap { opacity: 1; }
  .btn-action { background: none; border: 1px solid #d0d0d0; border-radius: 5px; padding: 4px 10px; font-size: 12px; cursor: pointer; color: #555; transition: all 0.15s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
  .btn-action:hover { background: #f0f4ff; border-color: #3b6cf8; color: #3b6cf8; }
  .btn-action.del:hover { background: #fff0f0; border-color: #e53935; color: #e53935; }
  .empty-state { text-align: center; padding: 60px 20px; color: #888; }
  .empty-state .empty-icon { font-size: 48px; margin-bottom: 16px; opacity: 0.4; }
  .empty-state h3 { font-size: 16px; font-weight: 600; color: #555; margin-bottom: 8px; }
  .empty-state p { font-size: 13px; margin-bottom: 20px; }
  .pagination-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-top: 1px solid #f0f2f7; background: #fff; }
  .pagination-info { font-size: 12px; color: #888; }
  .pagination-links { display: flex; gap: 4px; }
  .page-link { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 5px; border: 1px solid #d0d0d0; font-size: 12px; color: #555; cursor: pointer; text-decoration: none; transition: all 0.15s; }
  .page-link:hover { background: #f0f4ff; border-color: #3b6cf8; color: #3b6cf8; }
  .page-link.active { background: #3b6cf8; border-color: #3b6cf8; color: #fff; }
  .page-link.disabled { color: #ccc; cursor: not-allowed; pointer-events: none; }
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center; }
  .modal-overlay.open { display: flex; }
  .modal { background: #fff; border-radius: 10px; width: 420px; max-width: 95vw; box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden; }
  .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e8e8e8; }
  .modal-header h3 { font-size: 15px; font-weight: 600; color: #222; }
  .modal-close { width: 28px; height: 28px; border-radius: 50%; border: none; background: #eee; color: #555; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .modal-close:hover { background: #e53935; color: #fff; }
  .modal-body { padding: 20px; font-size: 13px; color: #555; line-height: 1.6; }
  .modal-body strong { color: #222; }
  .modal-footer { padding: 14px 20px; border-top: 1px solid #e8e8e8; display: flex; justify-content: flex-end; gap: 8px; }
  .btn-confirm-del { background: #e53935; color: #fff; border: none; border-radius: 6px; padding: 8px 20px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-confirm-del:hover { background: #c0392b; }
  .btn-modal-cancel { background: #fff; color: #555; border: 1px solid #d0d0d0; border-radius: 6px; padding: 8px 16px; font-size: 13px; cursor: pointer; }
  .btn-modal-cancel:hover { background: #f5f5f5; }
  .right-icons { width: 36px; background: #fff; border-left: 1px solid #e8e8e8; display: flex; flex-direction: column; padding: 8px 0; gap: 2px; }
  .right-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #777; font-size: 14px; border-radius: 4px; }
  .right-icon:hover { background: #f0f0f0; }
  .right-icon.orange { background: #f97316; color: #fff; }
</style>
</head>
<body>
<div class="app-shell">

  <!-- Sidebar -->
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

  <!-- Main -->
  <div class="main">
    <div class="topbar">
      <div class="topbar-search">
        <span>🔍</span>
        <input placeholder="Search in Customers ( / )" />
      </div>
      <div class="topbar-right">
        <div class="topbar-btn">🔔<span style="position:absolute;top:2px;right:2px;background:#e53935;color:#fff;border-radius:50%;width:14px;height:14px;font-size:9px;display:flex;align-items:center;justify-content:center;">1</span></div>
        <div class="topbar-btn">⚙️</div>
        <div class="avatar">V</div>
      </div>
    </div>

    <div class="content">

      <?php if(session('success')): ?>
        <div class="alert-success">✓ <?php echo e(session('success')); ?></div>
      <?php endif; ?>
      <?php if(session('error')): ?>
        <div class="alert-danger">✕ <?php echo e(session('error')); ?></div>
      <?php endif; ?>

      <!-- Page Header -->
      <div class="page-header">
        <div class="page-title">Customers</div>
        <div class="header-actions">
          
         <a href="<?php echo e(route('field_customization.create')); ?>?from=customers"
   class="btn-preferences"
   title="Customize customer form fields">
  ⚙️ Preferences
</a>
          <a href="<?php echo e(route('customers.create')); ?>" class="btn-new">+ New Customer</a>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <div class="filter-search">
          <span style="color:#aaa;">🔍</span>
          <input type="text" id="searchInput" placeholder="Search by name, email, phone..." oninput="filterTable()" />
        </div>
        <select class="filter-select" id="typeFilter" onchange="filterTable()">
          <option value="">All Types</option>
          <option value="business">Business</option>
          <option value="individual">Individual</option>
        </select>
        <div class="filter-count" id="filterCount">
          <?php echo e($customers->total()); ?> customer<?php echo e($customers->total() != 1 ? 's' : ''); ?>

        </div>
      </div>

      <!-- Table -->
      <div class="table-card">
        <?php if($customers->count() > 0): ?>
          <table class="data-table" id="customersTable">
            <thead>
              <tr>
                <th style="width:40px;"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="cursor:pointer;"></th>
                <th class="sortable">Customer Name</th>
                <th>Type</th>
                <th>Category</th>
                <th>Email</th>
                <th>Phone</th>
                <th>PAN</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $initials = strtoupper(substr($customer->display_name ?? 'C', 0, 1));
                  $colors   = ['#3b6cf8','#e74c3c','#2ecc71','#f39c12','#9b59b6','#1abc9c','#e67e22'];
                  $color    = $colors[$customer->id % count($colors)];
                ?>
                <tr data-name="<?php echo e(strtolower($customer->display_name)); ?>"
                    data-email="<?php echo e(strtolower($customer->email ?? '')); ?>"
                    data-phone="<?php echo e($customer->phone_number ?? ''); ?>"
                    data-type="<?php echo e($customer->customer_type); ?>">

                  <td><input type="checkbox" class="row-check" value="<?php echo e($customer->id); ?>" style="cursor:pointer;"></td>

                  <td>
                    <div class="cust-name-cell">
                      <div class="cust-avatar" style="background:<?php echo e($color); ?>;"><?php echo e($initials); ?></div>
                      <div class="cust-name-info">
                        <span class="cust-name-main"><?php echo e($customer->display_name); ?></span>
                        <?php if($customer->company_name): ?>
                          <span class="cust-name-sub"><?php echo e($customer->company_name); ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>

                  <td>
                    <span class="badge badge-<?php echo e($customer->customer_type); ?>">
                      <?php echo e(ucfirst($customer->customer_type)); ?>

                    </span>
                  </td>

                  <td><?php echo e($customer->customer_category ?? '—'); ?></td>

                  <td>
                    <?php if($customer->email): ?>
                      <a href="mailto:<?php echo e($customer->email); ?>" style="color:#3b6cf8;text-decoration:none;">
                        <?php echo e($customer->email); ?>

                      </a>
                    <?php else: ?>
                      <span style="color:#ccc;">—</span>
                    <?php endif; ?>
                  </td>

                  <td><?php echo e($customer->phone_number ?? '—'); ?></td>
                  <td><?php echo e($customer->pan ?? '—'); ?></td>

                  <td>
                    <div class="action-wrap">
                      <a href="<?php echo e(route('customers.show', $customer->id)); ?>" class="btn-action" title="View">👁 View</a>
                      <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" class="btn-action" title="Edit">✏ Edit</a>
                      <button type="button" class="btn-action del"
                              onclick="confirmDelete(<?php echo e($customer->id); ?>, '<?php echo e(addslashes($customer->display_name)); ?>')"
                              title="Delete">🗑</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>

          <div class="pagination-wrap">
            <div class="pagination-info">
              Showing <?php echo e($customers->firstItem()); ?>–<?php echo e($customers->lastItem()); ?> of <?php echo e($customers->total()); ?> customers
            </div>
            <div class="pagination-links">
              <?php if($customers->onFirstPage()): ?>
                <span class="page-link disabled">‹</span>
              <?php else: ?>
                <a href="<?php echo e($customers->previousPageUrl()); ?>" class="page-link">‹</a>
              <?php endif; ?>

              <?php $__currentLoopData = $customers->getUrlRange(max(1, $customers->currentPage()-2), min($customers->lastPage(), $customers->currentPage()+2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $customers->currentPage()): ?>
                  <span class="page-link active"><?php echo e($page); ?></span>
                <?php else: ?>
                  <a href="<?php echo e($url); ?>" class="page-link"><?php echo e($page); ?></a>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              <?php if($customers->hasMorePages()): ?>
                <a href="<?php echo e($customers->nextPageUrl()); ?>" class="page-link">›</a>
              <?php else: ?>
                <span class="page-link disabled">›</span>
              <?php endif; ?>
            </div>
          </div>

        <?php else: ?>
          <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>No customers yet</h3>
            <p>Add your first customer to get started.</p>
            <a href="<?php echo e(route('customers.create')); ?>" class="btn-new">+ New Customer</a>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="right-icons">
    <div class="right-icon orange">?</div>
    <div class="right-icon">📝</div>
    <div class="right-icon">📺</div>
    <div class="right-icon">💬</div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal" onclick="if(event.target===this)closeDeleteModal()">
  <div class="modal">
    <div class="modal-header">
      <h3>Delete Customer</h3>
      <button type="button" class="modal-close" onclick="closeDeleteModal()">×</button>
    </div>
    <div class="modal-body">
      Are you sure you want to delete <strong id="deleteCustomerName"></strong>?
      This action cannot be undone.
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn-confirm-del">Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteCustomerName').textContent = name;
    document.getElementById('deleteForm').action = '/customers/' + id;
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
function toggleAll(master) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = master.checked);
}
function filterTable() {
    const q    = document.getElementById('searchInput').value.toLowerCase().trim();
    const type = document.getElementById('typeFilter').value;
    const rows = document.querySelectorAll('#customersTable tbody tr');
    let visible = 0;
    rows.forEach(row => {
        const matchSearch = !q || (row.dataset.name||'').includes(q) || (row.dataset.email||'').includes(q) || (row.dataset.phone||'').includes(q);
        const matchType   = !type || row.dataset.type === type;
        row.style.display = (matchSearch && matchType) ? '' : 'none';
        if (matchSearch && matchType) visible++;
    });
    document.getElementById('filterCount').textContent = visible + ' customer' + (visible !== 1 ? 's' : '');
}
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/customers/index.blade.php ENDPATH**/ ?>