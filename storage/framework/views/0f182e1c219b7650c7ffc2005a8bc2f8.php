<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>Price Lists</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Arial,sans-serif;background:#f5f5f5;color:#333}
.wrap{max-width:900px;margin:0 auto;padding:1.5rem 1rem}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem}
h2{font-size:20px;font-weight:600;color:#333}
.btn-new{background:#378ADD;color:#fff;border:none;border-radius:6px;padding:8px 18px;font-size:14px;cursor:pointer;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.btn-new:hover{background:#2a6db5}
.card{background:#fff;border-radius:10px;border:1px solid #e0e0e0;overflow:hidden}
table{width:100%;border-collapse:collapse;font-size:13px}
th{background:#f9f9f9;padding:10px 14px;text-align:left;font-weight:600;font-size:12px;color:#666;border-bottom:1px solid #ddd}
td{padding:10px 14px;border-bottom:1px solid #eee;color:#333;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.badge{display:inline-block;font-size:11px;border-radius:4px;padding:2px 8px;font-weight:600}
.badge-sales{background:#e6f7ee;color:#1a7a3f}
.badge-purchase{background:#fff3e0;color:#c76b00}
.badge-all{background:#EAF4FD;color:#378ADD}
.badge-ind{background:#f3f0ff;color:#6c47d4}
.action-btn{background:none;border:none;cursor:pointer;font-size:13px;padding:4px 10px;border-radius:5px}
.btn-edit{color:#378ADD}
.btn-edit:hover{background:#EAF4FD}
.btn-del{color:#d44}
.btn-del:hover{background:#fdecea}
.empty-state{text-align:center;padding:48px 20px;color:#aaa}
.empty-state p{font-size:14px;margin-top:8px}
.alert{padding:10px 16px;border-radius:6px;margin-bottom:1rem;font-size:14px}
.alert-success{background:#e6f7ee;color:#1a7a3f;border-left:3px solid #1a7a3f}
.alert-error{background:#fdecea;color:#c0392b;border-left:3px solid #c0392b}
</style>
</head>
<body>
<div class="wrap">

  
  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if(session('error')): ?>
    <div class="alert alert-error"><?php echo e(session('error')); ?></div>
  <?php endif; ?>

  <div class="page-header">
    <h2>Price Lists</h2>
    <a href="<?php echo e(route('price-lists.create')); ?>" class="btn-new">+ New Price List</a>
  </div>

  <div class="card">
    <?php if($priceLists->isEmpty()): ?>
      <div class="empty-state">
        <div style="font-size:36px">🏷️</div>
        <p>No price lists yet. Create your first one!</p>
        <a href="<?php echo e(route('price-lists.create')); ?>" class="btn-new" style="margin-top:12px;display:inline-flex">+ New Price List</a>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Transaction</th>
            <th>Type</th>
            <th>Scheme</th>
            <th>Currency</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $priceLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td style="color:#aaa;font-size:12px"><?php echo e($loop->iteration); ?></td>
            <td>
              <div style="font-weight:600"><?php echo e($pl->name); ?></div>
              <?php if($pl->description): ?>
                <div style="font-size:11px;color:#aaa;margin-top:2px"><?php echo e(Str::limit($pl->description, 50)); ?></div>
              <?php endif; ?>
            </td>
            <td>
              <span class="badge <?php echo e($pl->transaction_type === 'sales' ? 'badge-sales' : 'badge-purchase'); ?>">
                <?php echo e(ucfirst($pl->transaction_type)); ?>

              </span>
            </td>
            <td>
              <span class="badge <?php echo e($pl->price_list_type === 'all_items' ? 'badge-all' : 'badge-ind'); ?>">
                <?php echo e($pl->price_list_type === 'all_items' ? 'All Items' : 'Individual'); ?>

              </span>
            </td>
            <td style="color:#666">
              <?php if($pl->price_list_type === 'all_items'): ?>
                <?php echo e(ucfirst($pl->markup_type ?? '—')); ?>

                <?php if($pl->percentage): ?>
                  <?php echo e($pl->percentage); ?>%
                <?php endif; ?>
              <?php else: ?>
                <?php echo e(ucfirst($pl->pricing_scheme ?? '—')); ?>

              <?php endif; ?>
            </td>
            <td style="color:#666"><?php echo e($pl->currency ?? 'INR'); ?></td>
            <td>
              <a href="<?php echo e(route('price-lists.edit', $pl->id)); ?>" class="action-btn btn-edit">Edit</a>
              <form method="POST" action="<?php echo e(route('price-lists.destroy', $pl->id)); ?>"
                    style="display:inline"
                    onsubmit="return confirm('Delete this price list?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="action-btn btn-del">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</div>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/pricelistindex.blade.php ENDPATH**/ ?>