
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Products List | Inventory</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }

    /* Sidebar */
    .sidebar { width: 220px; background: #1a2340; color: #b0b8cc; display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-logo { padding: 18px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #2a3556; }
    .sidebar-logo-icon { width: 32px; height: 32px; background: #2d5be3; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .sidebar-logo span { color: #fff; font-weight: 600; font-size: 16px; }
    .sidebar-menu { flex: 1; overflow-y: auto; padding: 10px 0; }
    .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; cursor: pointer; color: #b0b8cc; transition: background 0.15s; }
    .sidebar-item:hover { background: #243060; }
    .sidebar-item.active { background: #2d5be3; color: #fff; font-weight: 600; }
    .sidebar-item .arrow { margin-left: auto; font-size: 11px; }
    .sidebar-sub { padding-left: 48px; padding-bottom: 4px; }
    .sidebar-sub-active { color: #2d5be3; font-size: 13px; padding: 5px 10px; font-weight: 600; background: #1e2d52; border-radius: 4px; cursor: pointer; }
    .sidebar-sub-item { color: #b0b8cc; font-size: 13px; padding: 5px 10px; cursor: pointer; }
    .sidebar-apps-label { padding: 14px 20px 4px; color: #6b7a99; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
    .sidebar-collapse { padding: 10px 20px; border-top: 1px solid #2a3556; color: #6b7a99; font-size: 12px; cursor: pointer; }

    /* Top Bar */
    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }

    /* Main */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; padding: 28px 32px; }

    /* Banner */
    .banner { background: #fff8e6; border: 1px solid #f5d38a; border-radius: 6px; padding: 10px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
    .btn-refresh { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 12px; white-space: nowrap; margin-left: 16px; }

    /* Table Card */
    .table-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); padding: 20px; max-width: 100%; overflow-x: auto; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
    .table-header h2 { font-size: 22px; font-weight: 700; }
    .btn-add { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
    .btn-add:hover { background: #1e4acf; }

    /* Table */
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 10px; background: #f8fafd; color: #4a5568; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
    td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; }
    tr:hover { background: #f8fafd; }

    /* ── TYPE BADGES ── */
    .badge { padding: 3px 9px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
    .badge-goods      { background: #e6f0ff; color: #2d5be3; }
    .badge-service    { background: #e6f7e6; color: #10b981; }
    .badge-assembly   { background: #fef3e2; color: #d97706; }
    .badge-kit        { background: #f3e8ff; color: #7c3aed; }
    .badge-composite  { background: #fff0f0; color: #dc2626; }
    .badge-variant    { background: #f0f0f0; color: #666; }

    .btn-action { padding: 4px 8px; border-radius: 4px; text-decoration: none; margin: 0 2px; display: inline-block; font-size: 12px; }
    .btn-view { background: #e6f0ff; color: #2d5be3; }
    .btn-edit { background: #fff3e6; color: #f59e0b; }
    .btn-delete { background: #fee9e9; color: #ef4444; border: none; cursor: pointer; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state img { width: 120px; margin-bottom: 20px; opacity: 0.5; }
    .empty-state h3 { color: #4a5568; margin-bottom: 10px; }
    .empty-state p { color: #718096; margin-bottom: 20px; }

    .pagination { margin-top: 20px; display: flex; justify-content: center; }
    .pagination a, .pagination span { padding: 8px 12px; margin: 0 4px; border: 1px solid #e2e8f0; border-radius: 4px; text-decoration: none; color: #4a5568; }
    .pagination a:hover { background: #f8fafd; }
    .pagination .active { background: #2d5be3; color: #fff; border-color: #2d5be3; }

    .alert-success { background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; }

    /* Variant row styling */
    .variant-row { background: #f9fafb; font-size: 13px; }
    .variant-row:hover { background: #f3f4f6; }
    .toggle-icon { display: inline-block; cursor: pointer; font-size: 12px; color: #2d5be3; transition: transform 0.2s; width: 20px; text-align: center; }
    .toggle-icon.expanded { transform: rotate(90deg); }
    .parent-row { cursor: pointer; }
    .parent-row:hover { background: #f8fafd; }
    .variant-indent { padding-left: 25px; color: #666; font-size: 13px; }
    .variant-sku { color: #888; font-size: 11px; margin-left: 8px; }
    .variant-badge { background: #f0f0f0; color: #666; font-size: 11px; padding: 2px 6px; border-radius: 12px; margin-left: 8px; }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">I</div>
    <span>Inventory</span>
  </div>
  <div class="sidebar-menu">
    <div class="sidebar-item"><span>🏠</span><span>Home</span></div>
    <div class="sidebar-item active"><span>📦</span><span>Items</span><span class="arrow">▼</span></div>
    <div class="sidebar-sub">
      <div class="sidebar-sub-active">Items +</div>
      <div class="sidebar-sub-item">Price Lists</div>
    </div>
    <div class="sidebar-item"><span>🏪</span><span>Inventory</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>💼</span><span>Sales</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>🛒</span><span>Purchases</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>📊</span><span>Reports</span></div>
    <div class="sidebar-item"><span>📄</span><span>Documents</span></div>
    <div class="sidebar-item"><span>⚙️</span><span>Custom Modules</span><span class="arrow">▶</span></div>
    <div class="sidebar-apps-label">APPS</div>
    <div class="sidebar-item"><span>💳</span><span>Zoho Payments</span></div>
  </div>
  <div class="sidebar-collapse">◀ Collapse</div>
</div>

<!-- MAIN -->
<div class="main">
  <!-- TOP BAR -->
  <div class="topbar">
    <div class="search-box">🔍 <span>Search in Items ( / )</span></div>
    <div class="topbar-right">
      <span style="color:#e67e00;font-size:12px;">Your premi...</span>
      <button class="btn-subscribe">Subscribe</button>
      <span style="font-weight:600;">Jayadeepa ▼</span>
      <div class="topbar-avatar" style="background:#2d5be3;">+</div>
      <span style="cursor:pointer;">👥</span>
      <div class="notif-wrap">🔔<span class="notif-badge">1</span></div>
      <span style="cursor:pointer;">⚙️</span>
      <div class="topbar-avatar" style="background:#e74c3c;">J</div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">
    <?php if(session('success')): ?>
    <div class="alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="table-card">
      <div class="table-header">
        <h2>Items List</h2>
        <div style="display:flex; gap:10px;">
            <a href="<?php echo e(route('field_customization.index')); ?>?from=products"
               class="btn-add" style="background:#6c757d;">
                ⚙️ Preferences
            </a>
            <a href="<?php echo e(url('/products/create')); ?>" class="btn-add">
                + New Item
            </a>
        </div>
      </div>

      <?php if($products->count() > 0): ?>
      <table>
        <thead>
           <tr>
            <th style="width: 30px;"></th>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>SKU</th>
            <th>Unit</th>
            <th>Selling Price</th>
            <th>Cost Price</th>
            <th>Actions</th>
           </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isVariantParent = ($product->item_variant_type === 'contains_variants');
                $isComposite     = ($product->item_type === 'composite_item');
                $variants        = [];

                if ($isVariantParent && $product->variants_data) {
                    $variantsData = is_string($product->variants_data)
                        ? json_decode($product->variants_data, true)
                        : $product->variants_data;

                    if (isset($variantsData['variants']) && is_array($variantsData['variants'])) {
                        $variants = $variantsData['variants'];
                    }
                }

                // ── Determine badge ──────────────────────────────────
                // item_type = 'composite_item' → check type field
                // item_type = 'item'           → check type field (goods / service)
                if ($isComposite) {
                    $badgeClass = match($product->type) {
                        'assembly_item' => 'badge-assembly',
                        'kit_item'      => 'badge-kit',
                        default         => 'badge-composite',
                    };
                    $badgeLabel = match($product->type) {
                        'assembly_item' => '🔩 Assembly',
                        'kit_item'      => '📦 Kit',
                        default         => 'Composite',
                    };
                } else {
                    $badgeClass = match($product->type) {
                        'goods'   => 'badge-goods',
                        'service' => 'badge-service',
                        default   => 'badge-variant',
                    };
                    $badgeLabel = match($product->type) {
                        'goods'   => 'Goods',
                        'service' => 'Service',
                        default   => ucfirst($product->type ?? '—'),
                    };
                }
                // ────────────────────────────────────────────────────
            ?>

            <!-- Parent Row -->
            <tr class="parent-row" data-parent-id="<?php echo e($product->id); ?>">
                <td style="text-align: center;">
                    <?php if($isVariantParent && count($variants) > 0): ?>
                        <span class="toggle-icon" data-parent="<?php echo e($product->id); ?>">▶</span>
                    <?php endif; ?>
                </td>
                <td>#<?php echo e($product->id); ?></td>
                <td>
                    <strong><?php echo e($product->name); ?></strong>
                    <?php if($isVariantParent && count($variants) > 0): ?>
                        <span class="variant-badge"><?php echo e(count($variants)); ?> variants</span>
                    <?php endif; ?>
                </td>

                
                <td>
                    <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeLabel); ?></span>
                </td>

                <td><?php echo e($product->sku ?? '—'); ?></td>
                <td><?php echo e($product->unit); ?></td>
                <td>₹<?php echo e(number_format($product->selling_price, 2)); ?></td>
                <td>₹<?php echo e(number_format($product->cost_price, 2)); ?></td>
               <td>
  <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn-action btn-view">View</a>
  <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn-action btn-edit">Edit</a>

  <?php if($isComposite): ?>
    <a href="<?php echo e(route('composite-items.show', $product->id)); ?>" 
       class="btn-action" 
       style="background:#f3e8ff;color:#7c3aed;">🔩 Assemblies</a>
  <?php endif; ?>

  <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" style="display:inline;">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this item?')">Delete</button>
  </form>
</td>
            </tr>

            <!-- Variant Rows (Hidden by default) -->
            <?php if($isVariantParent && count($variants) > 0): ?>
                <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variantIndex => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="variant-row variant-parent-<?php echo e($product->id); ?>" style="display: none;">
                    <td style="border-left: 2px solid #2d5be3;"></td>
                    <td>↳</td>
                    <td class="variant-indent">
                        <?php echo e($variant['name'] ?? 'Variant ' . ($variantIndex + 1)); ?>

                        <?php if(isset($variant['sku'])): ?>
                            <span class="variant-sku">SKU: <?php echo e($variant['sku']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-variant">Variant</span></td>
                    <td><?php echo e($variant['sku'] ?? '—'); ?></td>
                    <td><?php echo e($product->unit); ?></td>
                    <td>₹<?php echo e(number_format((float)($variant['selling_price'] ?? $product->selling_price ?? 0), 2)); ?></td>
                    <td>₹<?php echo e(number_format((float)($variant['cost_price']    ?? $product->cost_price    ?? 0), 2)); ?></td>
                    <td>
                        <a href="<?php echo e(url('/products/' . $product->id)); ?>" class="btn-action btn-view">View</a>
                        <a href="<?php echo e(url('/products/' . $product->id . '/edit')); ?>" class="btn-action btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>

      <?php if(method_exists($products, 'links')): ?>
      <div class="pagination"><?php echo e($products->links()); ?></div>
      <?php endif; ?>

      <?php else: ?>
      <div class="empty-state">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" alt="No items">
        <h3>No Items Found</h3>
        <p>Get started by creating your first inventory item</p>
        <a href="<?php echo e(url('/products/create')); ?>" class="btn-add">+ Create New Item</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const parentRows = document.querySelectorAll('.parent-row');

    parentRows.forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' ||
                e.target.tagName === 'BUTTON' ||
                e.target.closest('.btn-action') ||
                e.target.closest('form')) return;

            const parentId   = this.getAttribute('data-parent-id');
            const variantRows = document.querySelectorAll(`.variant-parent-${parentId}`);
            const toggleIcon  = this.querySelector('.toggle-icon');

            if (variantRows.length > 0) {
                const isHidden = variantRows[0].style.display === 'none';
                variantRows.forEach(r => { r.style.display = isHidden ? 'table-row' : 'none'; });
                if (toggleIcon) {
                    toggleIcon.classList.toggle('expanded', isHidden);
                    toggleIcon.textContent = isHidden ? '▼' : '▶';
                }
            }
        });
    });

    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            this.closest('.parent-row')?.click();
        });
    });
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/index.blade.php ENDPATH**/ ?>