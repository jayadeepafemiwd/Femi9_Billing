
<?php $__env->startSection('title', 'Assemblies'); ?>

<?php $__env->startSection('content'); ?>
<div style="padding:28px 32px;">
  <?php if(session('success')): ?>
    <div style="background:#d4edda;color:#155724;padding:10px 16px;border-radius:5px;margin-bottom:16px;font-size:13px;">
      <?php echo e(session('success')); ?>

    </div>
  <?php endif; ?>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h2 style="font-size:20px;font-weight:700;">Assemblies</h2>
    <a href="<?php echo e(route('assemblies.create')); ?>"
       style="background:#2d5be3;color:#fff;border:none;border-radius:5px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">
      + New Assembly
    </a>
  </div>

  <div style="background:#fff;border-radius:8px;box-shadow:0 1px 6px rgba(0,0,0,.07);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="background:#f8fafd;">
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Assembly#</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Composite Item</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Date</th>
          <th style="padding:10px 14px;text-align:right;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Qty</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Status</th>
          <th style="padding:10px 14px;border-bottom:2px solid #e2e8f0;"></th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $assemblies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr style="border-bottom:1px solid #f0f2f7;">
          <td style="padding:10px 14px;font-weight:600;color:#2d5be3;"><?php echo e($a->assembly_number); ?></td>
          <td style="padding:10px 14px;"><?php echo e($a->composite_item_name); ?></td>
          <td style="padding:10px 14px;color:#555;"><?php echo e($a->assembled_date->format('d M Y')); ?></td>
          <td style="padding:10px 14px;text-align:right;"><?php echo e($a->quantity_to_assemble); ?></td>
          <td style="padding:10px 14px;">
            <span style="padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;
              background:<?php echo e($a->status === 'assembled' ? '#d1fae5' : '#fff3cd'); ?>;
              color:<?php echo e($a->status === 'assembled' ? '#065f46' : '#856404'); ?>;">
              <?php echo e(ucfirst($a->status)); ?>

            </span>
          </td>
          <td style="padding:10px 14px;">
            <form method="POST" action="<?php echo e(route('assemblies.destroy', $a->id)); ?>" style="display:inline;">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button type="submit" 
                      style="background:#fee2e2;color:#ef4444;border:none;border-radius:4px;padding:4px 10px;cursor:pointer;font-size:12px;"
                      onclick="return confirm('Delete this assembly?')">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
          <td colspan="6" style="padding:40px;text-align:center;color:#888;">
            No assemblies yet. <a href="<?php echo e(route('assemblies.create')); ?>" style="color:#2d5be3;">Create one</a>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <?php if(method_exists($assemblies, 'links')): ?>
    <div style="padding:12px 14px;"><?php echo e($assemblies->links()); ?></div>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/assembly/index.blade.php ENDPATH**/ ?>