<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>New Price List</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Arial,sans-serif;background:#f5f5f5;color:#333}
.wrap{max-width:780px;margin:0 auto;padding:1.5rem 1rem;background:#fff;min-height:100vh}
.form-row{display:flex;align-items:flex-start;gap:1rem;margin-bottom:1.2rem}
.form-label{min-width:150px;font-size:14px;color:#666;padding-top:8px}
.form-label.req{color:#d44}
.form-control{flex:1}
input[type=text],textarea,select{width:100%;font-size:14px;padding:7px 10px;border:1px solid #ccc;border-radius:6px;background:#fff;color:#333}
textarea{resize:vertical;min-height:70px}
.radio-group{display:flex;gap:1.5rem;padding-top:6px}
.radio-group label{display:flex;align-items:center;gap:6px;font-size:14px;cursor:pointer}
.card-group{display:flex;gap:12px;padding-top:4px;flex-wrap:wrap}
.type-card{border:1.5px solid #ddd;border-radius:10px;padding:12px 16px;cursor:pointer;width:240px;transition:border-color 0.15s}
.type-card.active{border-color:#378ADD;background:#EAF4FD}
.tc-title{font-size:14px;font-weight:600;color:#333;display:flex;align-items:center;gap:8px}
.tc-sub{font-size:12px;color:#888;margin-top:3px}
.check-circle{width:18px;height:18px;border-radius:50%;border:2px solid #aaa;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.check-circle.on{border-color:#378ADD;background:#378ADD}
.check-circle.on::after{content:'';width:6px;height:6px;border-radius:50%;background:#fff}
.pct-row{display:flex;align-items:center;gap:8px;padding-top:4px}
.pct-row select{width:130px;flex:none}
.pct-row input{width:100px;flex:none}
.pct-row span{font-size:14px;color:#666}
.divider{height:1px;background:#eee;margin:1.5rem 0}
.section-title{font-size:16px;font-weight:600;color:#333;margin-bottom:1rem}
.table-wrap{overflow-x:auto;border:1px solid #ddd;border-radius:8px}
table{width:100%;border-collapse:collapse;font-size:13px}
th{background:#f9f9f9;padding:8px 12px;text-align:left;font-weight:600;font-size:12px;color:#666;border-bottom:1px solid #ddd}
td{padding:8px 12px;border-bottom:1px solid #eee;color:#333;vertical-align:top}
tr:last-child td{border-bottom:none}
.item-name{font-weight:600;font-size:13px}
.item-sku{font-size:11px;color:#aaa}
td input[type=number]{width:90px;font-size:13px;padding:4px 8px;border:1px solid #ccc;border-radius:6px}
.add-range{font-size:12px;color:#378ADD;cursor:pointer;margin-top:4px;display:inline-flex;align-items:center;gap:4px}
.btn-del{background:none;border:none;cursor:pointer;color:#d44;font-size:16px;padding:2px 6px}
.btn-row{display:flex;gap:10px;margin-top:1.5rem}
.btn-save{background:#378ADD;color:#fff;border:none;border-radius:6px;padding:8px 22px;font-size:14px;cursor:pointer;font-weight:600}
.btn-cancel{background:none;border:1px solid #ccc;border-radius:6px;padding:8px 22px;font-size:14px;cursor:pointer;color:#333;text-decoration:none;display:inline-flex;align-items:center}
.badge-vol{display:inline-block;background:#EAF4FD;color:#378ADD;font-size:11px;border-radius:4px;padding:2px 7px;margin-left:6px}
.rounding-popup{position:absolute;z-index:10;background:#fff;border:1px solid #ccc;border-radius:10px;padding:1rem;min-width:320px;top:36px;left:0;box-shadow:0 4px 12px rgba(0,0,0,0.1)}
.rounding-popup table th{background:#f9f9f9;font-size:11px}
.rel{position:relative}
.view-eg{font-size:12px;color:#378ADD;cursor:pointer;margin-top:4px;display:inline-block}
.discount-note{font-size:12px;color:#666;margin-top:4px;padding:6px 10px;background:#f5f5f5;border-radius:6px;border-left:2px solid #378ADD}
.import-toggle{display:flex;align-items:center;gap:10px;font-size:13px;color:#666}
.toggle-sw{width:36px;height:20px;border-radius:10px;background:#ccc;cursor:pointer;position:relative;transition:background 0.2s;flex-shrink:0}
.toggle-sw.on{background:#378ADD}
.toggle-sw::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;border-radius:50%;background:#fff;transition:left 0.2s}
.toggle-sw.on::after{left:19px}
.import-section{margin-top:1rem;padding:1rem;border:1px solid #ddd;border-radius:8px;background:#f9f9f9}
.import-section p{font-size:13px;color:#666;margin-bottom:8px}
.btn-export{background:#fff;border:1px solid #ccc;border-radius:6px;padding:6px 14px;font-size:13px;cursor:pointer;color:#333;display:inline-flex;align-items:center;gap:6px;margin-right:8px;margin-top:4px}
.toast{display:none;margin-top:1rem;padding:10px 16px;border-radius:6px;font-size:14px}
.toast.success{background:#e6f7ee;color:#1a7a3f}
.toast.error{background:#fdecea;color:#c0392b}
h2{font-size:20px;font-weight:600;margin-bottom:1.5rem;color:#333}
.empty-row td{text-align:center;padding:24px;color:#aaa;font-size:13px}
.alert-error{background:#fdecea;color:#c0392b;padding:10px 16px;border-radius:6px;margin-bottom:1rem;font-size:13px}
</style>
</head>
<body>
<div class="wrap">

<?php if($errors->any()): ?>
<div class="alert-error">
  <ul style="margin:0;padding-left:1rem">
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php endif; ?>

<h2>New Price List</h2>

<form method="POST" action="<?php echo e(route('price-lists.store')); ?>" id="pl-form">
<?php echo csrf_field(); ?>


<div class="form-row">
  <div class="form-label req">Name*</div>
  <div class="form-control">
    <input type="text" name="name" id="pl-name" placeholder="Price list name" value="<?php echo e(old('name')); ?>">
  </div>
</div>


<div class="form-row">
  <div class="form-label">Transaction Type</div>
  <div class="form-control">
    <div class="radio-group">
      <label>
        <input type="radio" name="transaction_type" value="sales"
          <?php echo e(old('transaction_type','sales') === 'sales' ? 'checked' : ''); ?>

          onchange="renderItems()"> Sales
      </label>
      <label>
        <input type="radio" name="transaction_type" value="purchase"
          <?php echo e(old('transaction_type') === 'purchase' ? 'checked' : ''); ?>

          onchange="renderItems()"> Purchase
      </label>
    </div>
  </div>
</div>


<div class="form-row">
  <div class="form-label">Price List Type</div>
  <div class="form-control">
    <input type="hidden" name="price_list_type" id="price_list_type"
           value="<?php echo e(old('price_list_type','all_items')); ?>">
    <div class="card-group">
      <div class="type-card <?php echo e(old('price_list_type','all_items') === 'all_items' ? 'active' : ''); ?>"
           id="card-all" onclick="selectType('all')">
        <div class="tc-title">
          <span class="check-circle <?php echo e(old('price_list_type','all_items') === 'all_items' ? 'on' : ''); ?>" id="cc-all"></span>
          All Items
        </div>
        <div class="tc-sub">Mark up or mark down the rates of all items</div>
      </div>
      <div class="type-card <?php echo e(old('price_list_type') === 'individual_items' ? 'active' : ''); ?>"
           id="card-ind" onclick="selectType('ind')">
        <div class="tc-title">
          <span class="check-circle <?php echo e(old('price_list_type') === 'individual_items' ? 'on' : ''); ?>" id="cc-ind"></span>
          Individual Items
        </div>
        <div class="tc-sub">Customize the rate of each item</div>
      </div>
    </div>
  </div>
</div>


<div class="form-row">
  <div class="form-label">Description</div>
  <div class="form-control">
    <textarea name="description" placeholder="Enter the description"><?php echo e(old('description')); ?></textarea>
  </div>
</div>


<div id="all-fields" style="<?php echo e(old('price_list_type','all_items') === 'individual_items' ? 'display:none' : ''); ?>">

  <div class="form-row">
    <div class="form-label req">Percentage*</div>
    <div class="form-control">
      <div class="pct-row">
        <select name="markup_type" id="markup-type">
          <option value="markup"   <?php echo e(old('markup_type','markup') === 'markup'   ? 'selected':''); ?>>Markup</option>
          <option value="markdown" <?php echo e(old('markup_type') === 'markdown'          ? 'selected':''); ?>>Markdown</option>
        </select>
        <input type="number" name="percentage" placeholder="0" min="0" max="100"
               value="<?php echo e(old('percentage')); ?>">
        <span>%</span>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="form-label req">Round Off To*</div>
    <div class="form-control rel">
      <select name="round_off" style="width:200px">
        <?php $__currentLoopData = ['Never mind','Nearest whole number','0.99','0.50','0.49','Decimal Places']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($opt); ?>" <?php echo e(old('round_off') === $opt ? 'selected':''); ?>><?php echo e($opt); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <div class="view-eg" onclick="toggleRounding()">View Examples</div>
      <div class="rounding-popup" id="round-popup" style="display:none">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
          <span style="font-weight:600;font-size:14px">Rounding Examples</span>
          <span style="cursor:pointer;color:#888;font-size:16px" onclick="toggleRounding()">&#10005;</span>
        </div>
        <table>
          <thead><tr><th>Round off to</th><th>Input value</th><th>Rounded value</th></tr></thead>
          <tbody>
            <tr><td>Never mind</td><td>1000.678</td><td>1000.678</td></tr>
            <tr><td>Nearest whole number</td><td>1000.678</td><td>1001</td></tr>
            <tr><td>0.99</td><td>1000.678</td><td>1000.99</td></tr>
            <tr><td>0.50</td><td>1000.678</td><td>1000.50</td></tr>
            <tr><td>0.49</td><td>1000.678</td><td>1000.49</td></tr>
            <tr><td>Decimal Places</td><td>1000.678</td><td>1000.68</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>


<div id="ind-fields" style="<?php echo e(old('price_list_type') === 'individual_items' ? '' : 'display:none'); ?>">

  <div class="form-row">
    <div class="form-label">Pricing Scheme</div>
    <div class="form-control">
      <div class="radio-group">
        <label>
          <input type="radio" name="pricing_scheme" value="unit"
            <?php echo e(old('pricing_scheme') === 'unit' ? 'checked':''); ?>

            onchange="renderItems()"> Unit Pricing
        </label>
        <label>
          <input type="radio" name="pricing_scheme" value="volume"
            <?php echo e(old('pricing_scheme','volume') === 'volume' ? 'checked':''); ?>

            onchange="renderItems()"> Volume Pricing
          <span class="badge-vol">Volume</span>
        </label>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="form-label">Currency</div>
    <div class="form-control">
      <select name="currency" style="width:220px">
        <option value="INR" <?php echo e(old('currency','INR')==='INR'?'selected':''); ?>>INR - Indian Rupee</option>
        <option value="USD" <?php echo e(old('currency')==='USD'?'selected':''); ?>>USD - US Dollar</option>
        <option value="EUR" <?php echo e(old('currency')==='EUR'?'selected':''); ?>>EUR - Euro</option>
      </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-label">Discount</div>
    <div class="form-control">
      <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
        <input type="checkbox" name="include_discount" id="disc-chk" value="1"
               onchange="toggleDiscount()"
               <?php echo e(old('include_discount') ? 'checked':''); ?>>
        I want to include discount percentage for the items
      </label>
      <div id="disc-note" class="discount-note" style="<?php echo e(old('include_discount') ? '':'display:none'); ?>">
        When a price list is applied, the discount percentage will be applied only if discount is enabled at the line-item level.
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
    <div class="section-title" style="margin-bottom:0">Customise Rates in Bulk</div>
    <div class="import-toggle">
      <span>Import Price List for Items</span>
      <div class="toggle-sw" id="import-toggle" onclick="toggleImport()"></div>
    </div>
  </div>

  <div id="items-table-section">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:35%">Item Details</th>
            <th>Sales Rate</th>
            <th>Start Qty</th>
            <th>End Qty</th>
            <th>Custom Rate</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="items-tbody"></tbody>
      </table>
    </div>
  </div>

  <div id="import-section" style="display:none">
    <div class="import-section">
      <p><strong>1. Export items as XLS file</strong><br>Export all items or filter specific items, export them to an XLS file, update the rates, and import the file back.</p>
      <button type="button" class="btn-export">&#8593; Export All Items</button>
      <button type="button" class="btn-export">&#8593; Export Filtered Items</button>
    </div>
    <div class="import-section" style="margin-top:12px">
      <p><strong>2. Import items as XLS file</strong><br>Import the CSV or XLS file that you've exported and updated with the customised rates.</p>
      <p style="margin-top:8px;font-size:12px"><strong>NOTE:</strong> Column names must be in English: Item Name, SKU, Start Quantity, End Quantity, PriceList Rate</p>
      <p style="font-size:12px;margin-top:4px">Once imported, existing items and rates will be replaced with the data in the import file.</p>
      <button type="button" class="btn-export" style="margin-top:10px">&#8595; Import Items</button>
    </div>
  </div>

</div>

<div class="btn-row">
  <button type="submit" class="btn-save">Save</button>
  <a href="<?php echo e(route('price-lists.index')); ?>" class="btn-cancel">Cancel</a>
</div>

</form>
<div id="toast" class="toast"></div>
</div>

<script>
<?php
$productJson = isset($items) ? $items->map(function($p) {
    return [
        'id'            => $p->id,
        'name'          => $p->name,
        'sku'           => $p->sku ?? '',
        'selling_price' => (float)($p->selling_price ?? 0),
        'cost_price'    => (float)($p->cost_price    ?? 0),
    ];
})->values()->toJson() : '[]';
?>
const ALL_PRODUCTS = <?php echo $productJson; ?>;

let extraIdx = 10000;

function renderItems() {
  const tbody    = document.getElementById('items-tbody');
  const isVolume = (document.querySelector('input[name="pricing_scheme"]:checked')?.value ?? 'volume') === 'volume';
  const txn      =  document.querySelector('input[name="transaction_type"]:checked')?.value ?? 'sales';

  if (!ALL_PRODUCTS.length) {
    tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No products found.</td></tr>`;
    return;
  }

  tbody.innerHTML = '';

  ALL_PRODUCTS.forEach((item, idx) => {
    const rate = txn === 'purchase' ? item.cost_price : item.selling_price;
    const rateDisplay = rate > 0
      ? `&#8377;${rate.toLocaleString('en-IN',{minimumFractionDigits:2})}`
      : `<span style="color:#aaa">—</span>`;

    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <input type="hidden" name="items[${idx}][item_id]" value="${item.id}">
        <div class="item-name">${escHtml(item.name)}</div>
        ${item.sku ? `<div class="item-sku">SKU: ${escHtml(item.sku)}</div>` : ''}
      </td>
      <td>${rateDisplay}</td>
      <td>${isVolume ? `<input type="number" name="items[${idx}][start_quantity]" min="1" style="width:70px">` : '&mdash;'}</td>
      <td>${isVolume ? `<input type="number" name="items[${idx}][end_quantity]"   min="1" style="width:70px">` : '&mdash;'}</td>
      <td><input type="number" name="items[${idx}][custom_rate]" placeholder="0" min="0" style="width:90px"></td>
      <td><button type="button" class="btn-del" onclick="removeRow(this)">&#10005;</button></td>`;
    tbody.appendChild(row);

    if (isVolume) {
      const rr = document.createElement('tr');
      rr.className = 'range-hint-row';
      rr.dataset.parentId = item.id;
      rr.innerHTML = `<td></td><td></td><td colspan="4">
        <span class="add-range" onclick="addRange(this,${item.id})">+ Add New Range</span>
      </td>`;
      tbody.appendChild(rr);
    }
  });
}

function addRange(el, itemId) {
  const i  = extraIdx++;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td><input type="hidden" name="items[${i}][item_id]" value="${itemId}"></td>
    <td></td>
    <td><input type="number" name="items[${i}][start_quantity]" min="1" style="width:70px"></td>
    <td><input type="number" name="items[${i}][end_quantity]"   min="1" style="width:70px"></td>
    <td><input type="number" name="items[${i}][custom_rate]" placeholder="0" min="0" style="width:90px"></td>
    <td><button type="button" class="btn-del" onclick="this.closest('tr').remove()">&#10005;</button></td>`;
  el.closest('tr').before(tr);
}

function removeRow(btn) {
  const tr   = btn.closest('tr');
  const next = tr.nextElementSibling;
  if (next?.classList.contains('range-hint-row')) next.remove();
  tr.remove();
}

function selectType(t) {
  const isInd = t === 'ind';
  document.getElementById('card-all').classList.toggle('active', !isInd);
  document.getElementById('card-ind').classList.toggle('active',  isInd);
  document.getElementById('cc-all').className = 'check-circle' + (!isInd ? ' on' : '');
  document.getElementById('cc-ind').className = 'check-circle' + ( isInd ? ' on' : '');
  document.getElementById('price_list_type').value      = isInd ? 'individual_items' : 'all_items';
  document.getElementById('all-fields').style.display   = isInd ? 'none' : '';
  document.getElementById('ind-fields').style.display   = isInd ? ''     : 'none';
  if (isInd) renderItems();
}

function toggleRounding() {
  const p = document.getElementById('round-popup');
  p.style.display = p.style.display === 'none' ? 'block' : 'none';
}

function toggleDiscount() {
  document.getElementById('disc-note').style.display =
    document.getElementById('disc-chk').checked ? 'block' : 'none';
}

function toggleImport() {
  const tog = document.getElementById('import-toggle');
  tog.classList.toggle('on');
  const on = tog.classList.contains('on');
  document.getElementById('items-table-section').style.display = on ? 'none'  : 'block';
  document.getElementById('import-section').style.display      = on ? 'block' : 'none';
}

function escHtml(s) {
  return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Page load — old() value-க்கு ஏத்தமாதிரி show பண்ணு
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('price_list_type').value === 'individual_items') {
    selectType('ind');
  }
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/pricelist.blade.php ENDPATH**/ ?>