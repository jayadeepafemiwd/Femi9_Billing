

<?php $__env->startSection('title', 'Composite Items'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div style="padding:20px;">

    
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;">Composite Items</h1>
        <a href="<?php echo e(route('composite-items.create')); ?>" class="btn-save">+ New Composite Item</a>
    </div>

    
    <form method="GET" action="<?php echo e(route('composite-items.index')); ?>"
          style="display:flex;gap:10px;margin-bottom:16px;">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
               placeholder="Search by name or SKU..."
               class="form-input" style="max-width:280px;">
        <select name="type" class="form-select" style="max-width:180px;">
            <option value="">All Types</option>
            <option value="assembly_item" <?php echo e(request('type') === 'assembly_item' ? 'selected' : ''); ?>>Assembly Item</option>
            <option value="kit_item"      <?php echo e(request('type') === 'kit_item'      ? 'selected' : ''); ?>>Kit Item</option>
        </select>
        <button type="submit" class="btn-save">Search</button>
        <?php if(request('search') || request('type')): ?>
            <a href="<?php echo e(route('composite-items.index')); ?>" class="btn-cancel">Clear</a>
        <?php endif; ?>
    </form>

    
    <div style="background:#fff;border-radius:6px;border:1px solid #e0e3ea;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f8f9fb;">
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">#</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Name</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Type</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">SKU</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Unit</th>
                    <th style="padding:10px 14px;text-align:right;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Selling Price</th>
                    <th style="padding:10px 14px;text-align:right;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Cost Price</th>
                    <th style="padding:10px 14px;text-align:center;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom:1px solid #f0f1f3;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <td style="padding:10px 14px;color:#888;"><?php echo e($loop->iteration); ?></td>
                        <td style="padding:10px 14px;">
                            <div style="font-weight:500;color:#222;"><?php echo e($product->name); ?></div>
                            <?php if($product->brand): ?>
                                <div style="font-size:11px;color:#888;"><?php echo e($product->brand); ?></div>
                            <?php endif; ?>
                        </td>
                        <td style="padding:10px 14px;">
                            <?php if($product->type === 'assembly_item'): ?>
                                <span style="background:#e8f0fe;color:#2563eb;padding:3px 8px;border-radius:12px;font-size:11px;font-weight:500;">Assembly</span>
                            <?php else: ?>
                                <span style="background:#fef3e2;color:#e08c00;padding:3px 8px;border-radius:12px;font-size:11px;font-weight:500;">Kit</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:10px 14px;color:#555;"><?php echo e($product->sku ?? '—'); ?></td>
                        <td style="padding:10px 14px;color:#555;"><?php echo e($product->unit); ?></td>
                        <td style="padding:10px 14px;text-align:right;color:#333;">
                            ₹<?php echo e(number_format($product->selling_price ?? 0, 2)); ?>

                        </td>
                        <td style="padding:10px 14px;text-align:right;color:#333;">
                            ₹<?php echo e(number_format($product->cost_price ?? 0, 2)); ?>

                        </td>
                        <td style="padding:10px 14px;text-align:center;">
                            <div style="display:flex;gap:8px;justify-content:center;">
                                
                                <a href="<?php echo e(route('products.show', $product->id)); ?>"
                                   style="color:#2563eb;font-size:12px;text-decoration:none;">View</a>
                                <a href="<?php echo e(route('composite-items.edit', $product->id)); ?>"
                                   style="color:#555;font-size:12px;text-decoration:none;">Edit</a>
                                <form action="<?php echo e(route('composite-items.destroy', $product->id)); ?>"
                                      method="POST"
                                      onsubmit="return confirm('Delete this item?')"
                                      style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            style="background:none;border:none;color:#e74c3c;font-size:12px;cursor:pointer;padding:0;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" style="padding:40px;text-align:center;color:#aaa;">
                            <div style="font-size:15px;margin-bottom:8px;">No composite items found</div>
                            <a href="<?php echo e(route('composite-items.create')); ?>" style="color:#2563eb;font-size:13px;">
                                + Create your first composite item
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($products->hasPages()): ?>
        <div style="margin-top:16px;">
            <?php echo e($products->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/composite/index.blade.php ENDPATH**/ ?>