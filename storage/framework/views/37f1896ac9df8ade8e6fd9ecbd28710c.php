
<?php $__env->startSection('title', 'New Assembly'); ?>

<?php $__env->startPush('styles'); ?>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.assembly-form {
  background: #fff;
  padding: 28px 32px 100px;
  max-width: 1000px;
}

.page-title {
  font-size: 20px;
  font-weight: 700;
  color: #1a2340;
  margin-bottom: 28px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-row {
  display: flex;
  align-items: flex-start;
  margin-bottom: 20px;
  gap: 16px;
}

.form-label {
  width: 160px;
  flex-shrink: 0;
  font-size: 13px;
  color: #c0392b;
  font-weight: 500;
  padding-top: 9px;
}

.form-label.gray { color: #555; }
.form-content { flex: 1; }

.form-input {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  padding: 8px 11px;
  font-size: 13px;
  color: #333;
  outline: none;
  font-family: inherit;
}
.form-input:focus { border-color: #2d5be3; }

.form-select {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  padding: 8px 11px;
  font-size: 13px;
  color: #333;
  outline: none;
  appearance: none;
  background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23999'/%3E%3C/svg%3E") no-repeat right 10px center;
  font-family: inherit;
}

.assembly-num-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
}

.assembly-num-wrap input {
  flex: 1;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  padding: 8px 11px;
  font-size: 13px;
  outline: none;
}

.gear-btn {
  width: 34px; height: 34px;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  background: #f8f9fb;
  cursor: pointer;
  font-size: 16px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

/* ── COMPOSITE SELECT ── */
.composite-select-wrap {
  position: relative;
}
.composite-input-box {
  display: flex;
  align-items: center;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  background: #fff;
  min-height: 36px;
  padding: 0 8px;
  cursor: pointer;
}
.composite-input-box.selected { border-color: #2d5be3; }
.composite-selected-name { flex: 1; font-size: 13px; color: #333; padding: 6px 4px; }
.composite-placeholder   { flex: 1; font-size: 13px; color: #aaa; padding: 6px 4px; }
.composite-clear { color: #e74c3c; cursor: pointer; font-size: 16px; padding: 4px; }
.composite-chevron { color: #888; font-size: 11px; padding: 4px; }

.composite-dd {
  display: none;
  position: absolute;
  top: calc(100% + 2px);
  left: 0; right: 0;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  z-index: 500;
  max-height: 240px;
  overflow-y: auto;
}
.composite-dd.open { display: block; }
.composite-dd-search {
  width: 100%;
  border: none;
  border-bottom: 1px solid #eee;
  padding: 8px 12px;
  font-size: 13px;
  outline: none;
}
.composite-dd-item {
  padding: 9px 14px;
  font-size: 13px;
  cursor: pointer;
  color: #333;
}
.composite-dd-item:hover { background: #f0f5ff; color: #2d5be3; }
.composite-dd-item .dd-sku { font-size: 11px; color: #888; margin-top: 2px; }

.item-sku-sub { font-size: 12px; color: #888; margin-top: 4px; }

/* ── INFO BOX ── */
.info-box {
  background: #f0f5ff;
  border: 1px solid #c5d3f7;
  border-radius: 6px;
  padding: 10px 14px;
  font-size: 13px;
  color: #2d5be3;
  margin-bottom: 16px;
  display: flex;
  align-items: flex-start;
  gap: 8px;
}

/* ── ASSOCIATED TABLE ── */
.assoc-section { margin-top: 28px; }
.assoc-title {
  font-size: 13px;
  font-weight: 600;
  color: #c0392b;
  margin-bottom: 10px;
}

.assoc-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
.assoc-table th {
  background: #f8f9fb;
  border: 1px solid #e2e8f0;
  padding: 9px 12px;
  text-align: left;
  font-weight: 600;
  color: #4a5568;
  font-size: 12px;
}
.assoc-table th.right { text-align: right; }
.assoc-table td {
  border: 1px solid #e2e8f0;
  padding: 8px 12px;
  vertical-align: middle;
}
.assoc-table td.right { text-align: right; }

.item-img {
  width: 38px; height: 38px;
  background: #f0f1f3;
  border: 1px solid #e0e3ea;
  border-radius: 4px;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
  flex-shrink: 0;
}
.item-img img { width: 100%; height: 100%; object-fit: cover; }

.item-cell { display: flex; align-items: center; gap: 10px; }
.item-name { font-size: 13px; color: #333; font-weight: 500; }
.item-sku  { font-size: 11px; color: #888; }

.qty-input {
  width: 80px;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  padding: 5px 8px;
  font-size: 13px;
  text-align: right;
  outline: none;
}
.qty-input:focus { border-color: #2d5be3; }

.qty-avail { font-size: 13px; color: #333; }
.qty-avail.warn { color: #e74c3c; }

.total-qty-cell { font-size: 13px; color: #333; }
.total-qty-sub  { font-size: 11px; color: #888; }

.add-row-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  color: #2d5be3;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  border: none;
  background: none;
  padding: 8px 0;
}
.add-row-btn:hover { text-decoration: underline; }

.cost-row {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  font-size: 12px;
  color: #555;
  background: #fafafa;
  border: 1px solid #e2e8f0;
  border-top: none;
}
.cost-row a { color: #2d5be3; cursor: pointer; }

hr.divider { border: none; border-top: 1px solid #e8eaed; margin: 24px 0; }

/* ── BOTTOM BAR ── */
.bottom-bar {
  position: fixed;
  bottom: 0;
  left: 220px;
  right: 0;
  background: #fff;
  border-top: 1px solid #e0e3ea;
  padding: 12px 32px;
  display: flex;
  gap: 10px;
  z-index: 100;
}

.btn-save-draft {
  background: #fff;
  color: #333;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  padding: 8px 20px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
.btn-assemble {
  background: #2d5be3;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 8px 22px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}
.btn-assemble:hover { background: #1e4acf; }
.btn-cancel {
  background: #fff;
  color: #555;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  padding: 8px 18px;
  font-size: 13px;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}

/* ── AUTO-NUMBER MODAL ── */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.45);
  z-index: 2000;
  align-items: center;
  justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: #fff;
  border-radius: 8px;
  width: 480px;
  max-width: 95vw;
  box-shadow: 0 8px 32px rgba(0,0,0,0.2);
  overflow: hidden;
}
.modal-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid #e0e3ea;
  font-size: 15px;
  font-weight: 700;
}
.modal-x { background: none; border: none; font-size: 20px; color: #e74c3c; cursor: pointer; }
.modal-body { padding: 24px 20px; }
.modal-body p { font-size: 13px; color: #555; margin-bottom: 16px; }
.radio-opt { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; cursor: pointer; font-size: 13px; }
.radio-opt input { accent-color: #2d5be3; width: 15px; height: 15px; }
.modal-foot { padding: 14px 20px; border-top: 1px solid #e0e3ea; display: flex; gap: 10px; }
.btn-modal-save { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 8px 22px; font-size: 13px; font-weight: 600; cursor: pointer; }
.btn-modal-cancel { background: #fff; color: #555; border: 1px solid #d1d5db; border-radius: 5px; padding: 8px 16px; font-size: 13px; cursor: pointer; }

.empty-msg { padding: 20px; text-align: center; color: #aaa; font-size: 13px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="assembly-form">
  <h1 class="page-title">🔩 New Assembly</h1>

  <form id="assemblyForm" method="POST" action="<?php echo e(route('assemblies.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" id="formAction" value="assemble">
    <input type="hidden" name="associated_items_json"    id="associatedItemsJson">
    <input type="hidden" name="associated_services_json" id="associatedServicesJson">

    <?php if(session('error')): ?>
      <div style="background:#fde8e8;color:#c0392b;padding:10px 14px;border-radius:5px;margin-bottom:16px;font-size:13px;">
        <?php echo e(session('error')); ?>

      </div>
    <?php endif; ?>

    
    <div class="form-row">
      <div class="form-label">Composite Item*</div>
      <div class="form-content">
        <input type="hidden" name="composite_item_id" id="compositeItemId" value="<?php echo e($preselectedId ?? ''); ?>">
        <div class="composite-select-wrap">
          <div class="composite-input-box" id="compositeBox" onclick="toggleCompositeDd()">
            <span class="composite-selected-name" id="compositeLabel">
              <?php echo e($preselectedName ?? ''); ?>

            </span>
            <?php if(!$preselectedName): ?>
              <span class="composite-placeholder" id="compositePlaceholder">Select a composite item</span>
            <?php endif; ?>
            <span class="composite-clear" id="compositeClear" 
                  onclick="clearComposite(event)"
                  style="<?php echo e($preselectedId ? '' : 'display:none'); ?>">×</span>
            <span class="composite-chevron">▼</span>
          </div>
          <div class="composite-dd" id="compositeDd">
            <input type="text" class="composite-dd-search" 
                   placeholder="Search composite items..."
                   oninput="filterComposite(this.value)" autocomplete="off">
            <div id="compositeDdList">
              <?php $__currentLoopData = $compositeItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="composite-dd-item" 
                     onclick="selectComposite(<?php echo e($ci->id); ?>, '<?php echo e(addslashes($ci->name)); ?>', '<?php echo e($ci->sku ?? ''); ?>')">
                  <?php echo e($ci->name); ?>

                  <?php if($ci->sku): ?>
                    <div class="dd-sku">SKU: <?php echo e($ci->sku); ?></div>
                  <?php endif; ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
        <div class="item-sku-sub" id="compositeSku" 
             style="<?php echo e($preselectedId ? '' : 'display:none'); ?>">
          SKU: <span id="compositeSkuVal"></span>
        </div>
      </div>
    </div>

    
    <div class="form-row">
      <div class="form-label">Assembly#*</div>
      <div class="form-content">
        <div class="assembly-num-wrap">
          <input type="text" name="assembly_number" id="assemblyNumber"
                 value="<?php echo e($autoNumber); ?>" class="form-input">
          <button type="button" class="gear-btn" onclick="openNumModal()" title="Configure numbering">⚙️</button>
        </div>
      </div>
    </div>

    
    <div class="form-row">
      <div class="form-label gray">Description</div>
      <div class="form-content">
        <textarea name="description" class="form-input" rows="3" 
                  style="resize:vertical;"></textarea>
      </div>
    </div>

    
    <div class="form-row">
      <div class="form-label">Assembled Date*</div>
      <div class="form-content">
        <input type="date" name="assembled_date" class="form-input"
               value="<?php echo e(date('Y-m-d')); ?>" required>
      </div>
    </div>

    
    <div class="form-row">
      <div class="form-label">Quantity to Assemble*</div>
      <div class="form-content">
        <input type="number" name="quantity_to_assemble" id="qtyToAssemble"
               class="form-input" value="1" min="0.0001" step="0.0001"
               oninput="updateTotalQty()" required>
        <div class="item-sku-sub" id="qtyAvailMsg">
          You can Assemble <strong id="maxAssembleQty">0</strong> unit from the available stock.
        </div>
      </div>
    </div>

    
    <div class="form-row">
      <div class="form-label">Location*</div>
      <div class="form-content">
        <select name="location_id" class="form-select" required>
          <option value="">Add Location</option>
          <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($loc->id); ?>"><?php echo e($loc->location_name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
    </div>

    <hr class="divider">

    
    <div class="assoc-section">
      <div class="assoc-title">Associated Items*</div>

      <div class="info-box">
        ℹ️ If you've incurred an addition cost while assembling this item such as rent, labour, or scrap; you can <strong>add it as a service item</strong> to associate that cost to the item.
      </div>

      <table class="assoc-table" id="itemsTable">
        <thead>
          <tr>
            <th style="width:40%;">Item Details</th>
            <th class="right">Quantity Required</th>
            <th class="right">Total Qty required</th>
            <th class="right">Quantity Available</th>
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody id="itemsTbody">
          <tr id="itemsEmpty">
            <td colspan="5" class="empty-msg">Select a composite item to load associated items</td>
          </tr>
        </tbody>
      </table>

      <div style="padding:8px 0;">
        <button type="button" class="add-row-btn" onclick="addItemRow()">
          ➕ Add New Row
        </button>
        &nbsp;&nbsp;
        <button type="button" class="add-row-btn" onclick="addServiceToItems()">
          ➕ Add Services
        </button>
      </div>
    </div>

    
    <div class="assoc-section" style="margin-top:24px;">
      <div class="assoc-title">Associated Services*</div>

      <table class="assoc-table" id="servicesTable">
        <thead>
          <tr>
            <th style="width:40%;">Service Details</th>
            <th class="right">Quantity Required</th>
            <th class="right">Total Qty required</th>
            <th class="right">Cost per unit</th>
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody id="servicesTbody">
          <tr id="servicesEmpty">
            <td colspan="5" class="empty-msg">No services added</td>
          </tr>
        </tbody>
      </table>

      <div style="padding:8px 0;">
        <button type="button" class="add-row-btn" onclick="addServiceRow()">
          ➕ Add New Row
        </button>
      </div>
    </div>

    <div style="height:70px;"></div>
  </form>
</div>


<div class="modal-overlay" id="numModal">
  <div class="modal-box">
    <div class="modal-head">
      <span>Configure Assembly# Preferences</span>
      <button class="modal-x" onclick="closeNumModal()">×</button>
    </div>
    <div class="modal-body">
      <p>You have selected manual assembly numbering. Do you want us to auto-generate it for you?</p>
      <label class="radio-opt">
        <input type="radio" name="num_pref" value="auto" id="numAuto"> 
        Continue auto-generating assembly numbers
      </label>
      <label class="radio-opt">
        <input type="radio" name="num_pref" value="manual" id="numManual" checked> 
        Enter assembly numbers manually
      </label>
    </div>
    <div class="modal-foot">
      <button class="btn-modal-save" onclick="saveNumPref()">Save</button>
      <button class="btn-modal-cancel" onclick="closeNumModal()">Cancel</button>
    </div>
  </div>
</div>


<div class="bottom-bar">
  <button type="button" class="btn-save-draft" onclick="submitForm('draft')">Save as Draft</button>
  <button type="button" class="btn-assemble"   onclick="submitForm('assemble')">Assemble</button>
  <a href="<?php echo e(route('assemblies.index')); ?>" class="btn-cancel">Cancel</a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── DATA ──────────────────────────────────────────────
const COMPOSITE_ITEMS = <?php echo json_encode($compositeItems, 15, 512) ?>;
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

let selectedComposite = null;
let itemRows     = []; // { product_id, name, sku, unit, qty, qty_available, cost, image_url }
let serviceRows  = []; // { product_id, name, sku, unit, qty, cost, image_url }
let numPref      = 'manual'; // 'auto' | 'manual'
let autoNum      = '<?php echo e($autoNumber); ?>';
let rowCounter   = 0;

// ── COMPOSITE DROPDOWN ────────────────────────────────
function toggleCompositeDd() {
  document.getElementById('compositeDd').classList.toggle('open');
}

function filterComposite(q) {
  const list = document.getElementById('compositeDdList');
  q = q.toLowerCase().trim();
  const filtered = COMPOSITE_ITEMS.filter(c =>
    c.name.toLowerCase().includes(q) || (c.sku || '').toLowerCase().includes(q)
  );
  list.innerHTML = filtered.length
    ? filtered.map(c => `
        <div class="composite-dd-item"
             onclick="selectComposite(${c.id},'${escAttr(c.name)}','${escAttr(c.sku||'')}')">
          ${escHtml(c.name)}
          ${c.sku ? `<div class="dd-sku">SKU: ${escHtml(c.sku)}</div>` : ''}
        </div>`).join('')
    : '<div class="empty-msg">No items found</div>';
}

async function selectComposite(id, name, sku) {
  document.getElementById('compositeItemId').value   = id;
  document.getElementById('compositeLabel').textContent = name;
  document.getElementById('compositeLabel').style.display = '';
  document.getElementById('compositePlaceholder') && 
    (document.getElementById('compositePlaceholder').style.display = 'none');
  document.getElementById('compositeClear').style.display = '';
  document.getElementById('compositeSku').style.display  = '';
  document.getElementById('compositeSkuVal').textContent = sku || '—';
  document.getElementById('compositeDd').classList.remove('open');
  document.getElementById('compositeBox').classList.add('selected');

  // Fetch associate items
  await loadCompositeDetails(id);
}

function clearComposite(e) {
  e.stopPropagation();
  document.getElementById('compositeItemId').value = '';
  document.getElementById('compositeLabel').textContent = '';
  document.getElementById('compositeClear').style.display = 'none';
  document.getElementById('compositeSku').style.display   = 'none';
  document.getElementById('compositeBox').classList.remove('selected');
  selectedComposite = null;
  itemRows = []; serviceRows = [];
  renderItems(); renderServices();
}

document.addEventListener('click', e => {
  const wrap = document.querySelector('.composite-select-wrap');
  if (wrap && !wrap.contains(e.target))
    document.getElementById('compositeDd').classList.remove('open');
});

async function loadCompositeDetails(id) {
  try {
    const res  = await fetch(`/assemblies/composite-item/${id}`, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    const data = await res.json();
    if (!data.success) return;

    selectedComposite = data.data;
    itemRows    = data.data.items.map(i => ({ ...i, qty: i.quantity_required }));
    serviceRows = data.data.services.map(s => ({ ...s, qty: s.quantity_required }));
    renderItems();
    renderServices();
    updateMaxQty();
  } catch(e) {
    console.error(e);
  }
}

// ── ITEMS TABLE ────────────────────────────────────────
function renderItems() {
  const tbody = document.getElementById('itemsTbody');
  if (!itemRows.length) {
    tbody.innerHTML = '<tr id="itemsEmpty"><td colspan="5" class="empty-msg">Select a composite item to load associated items</td></tr>';
    return;
  }

  const qta = parseFloat(document.getElementById('qtyToAssemble').value) || 1;

  tbody.innerHTML = itemRows.map((row, idx) => {
    const totalQty = (row.qty * qta).toFixed(4).replace(/\.?0+$/, '');
    const avail    = row.quantity_available ?? 0;
    const isWarn   = avail < (row.qty * qta);
    const imgHtml  = row.image_url
      ? `<img src="${row.image_url}" alt="">`
      : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>`;

    return `
      <tr data-idx="${idx}">
        <td>
          <div class="item-cell">
            <div class="item-img">${imgHtml}</div>
            <div>
              <div class="item-name">${escHtml(row.name)}</div>
              <div class="item-sku">${row.sku ? 'SKU: '+escHtml(row.sku) : ''}</div>
            </div>
          </div>
        </td>
        <td class="right">
          <input class="qty-input" type="number" min="0.0001" step="0.0001"
                 value="${row.qty}"
                 onchange="onItemQtyChange(${idx}, this.value)">
        </td>
        <td class="right">
          <div class="total-qty-cell">${totalQty}</div>
          <div class="total-qty-sub">x ${qta} assemblies</div>
        </td>
        <td class="right">
          <span class="qty-avail ${isWarn ? 'warn' : ''}">
            ${avail} ${escHtml(row.unit || '')}
            ${isWarn ? '⚠️' : ''}
          </span>
        </td>
        <td>
          <span style="color:#e74c3c;cursor:pointer;font-size:18px;font-weight:bold;"
                onclick="removeItemRow(${idx})">×</span>
        </td>
      </tr>
      <tr>
        <td colspan="5" class="cost-row">
          🏷️ Cost Price : <a>View</a>
        </td>
      </tr>`;
  }).join('');
}

function onItemQtyChange(idx, val) {
  itemRows[idx].qty = parseFloat(val) || 0;
  renderItems();
  updateMaxQty();
}

function removeItemRow(idx) {
  itemRows.splice(idx, 1);
  renderItems();
}

function addItemRow() {
  itemRows.push({ product_id: null, name: 'New Item', sku: '', unit: '', qty: 1, quantity_available: 0, cost: 0, image_url: null });
  renderItems();
}

// ── SERVICES TABLE ─────────────────────────────────────
function renderServices() {
  const tbody = document.getElementById('servicesTbody');
  if (!serviceRows.length) {
    tbody.innerHTML = '<tr id="servicesEmpty"><td colspan="5" class="empty-msg">No services added</td></tr>';
    return;
  }

  const qta = parseFloat(document.getElementById('qtyToAssemble').value) || 1;

  tbody.innerHTML = serviceRows.map((row, idx) => {
    const totalQty = (row.qty * qta).toFixed(4).replace(/\.?0+$/, '');
    const imgHtml  = row.image_url
      ? `<img src="${row.image_url}" alt="">`
      : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>`;

    return `
      <tr data-idx="${idx}">
        <td>
          <div class="item-cell">
            <div class="item-img">${imgHtml}</div>
            <div>
              <div class="item-name">${escHtml(row.name)}</div>
              <div class="item-sku">${row.sku ? 'SKU: '+escHtml(row.sku) : ''}</div>
            </div>
          </div>
        </td>
        <td class="right">
          <input class="qty-input" type="number" min="0.0001" step="0.0001"
                 value="${row.qty}"
                 onchange="onSvcQtyChange(${idx}, this.value)">
        </td>
        <td class="right">
          <div class="total-qty-cell">${totalQty}</div>
          <div class="total-qty-sub">x ${qta} assemblies</div>
        </td>
        <td class="right">${parseFloat(row.cost || 0).toFixed(2)}</td>
        <td>
          <span style="color:#e74c3c;cursor:pointer;font-size:18px;font-weight:bold;"
                onclick="removeSvcRow(${idx})">×</span>
        </td>
      </tr>
      <tr>
        <td colspan="5" class="cost-row">
          🏷️ Cost Price : <a>View</a>
        </td>
      </tr>`;
  }).join('');
}

function onSvcQtyChange(idx, val) {
  serviceRows[idx].qty = parseFloat(val) || 0;
  renderServices();
}

function removeSvcRow(idx) {
  serviceRows.splice(idx, 1);
  renderServices();
}

function addServiceRow() {
  serviceRows.push({ product_id: null, name: 'New Service', sku: '', unit: '', qty: 1, cost: 0, image_url: null });
  renderServices();
}

function addServiceToItems() { addServiceRow(); }

// ── QTY TO ASSEMBLE ────────────────────────────────────
function updateTotalQty() {
  renderItems();
  renderServices();
  updateMaxQty();
}

function updateMaxQty() {
  if (!itemRows.length) {
    document.getElementById('maxAssembleQty').textContent = 0;
    return;
  }
  // max assemblies = min(available / qty_required) for all items
  let maxAssemble = Infinity;
  itemRows.forEach(r => {
    if (r.qty > 0) {
      maxAssemble = Math.min(maxAssemble, Math.floor((r.quantity_available ?? 0) / r.qty));
    }
  });
  if (maxAssemble === Infinity) maxAssemble = 0;
  document.getElementById('maxAssembleQty').textContent = maxAssemble;
}

// ── NUMBER MODAL ────────────────────────────────────────
function openNumModal() {
  document.getElementById('numModal').classList.add('open');
  document.getElementById(numPref === 'auto' ? 'numAuto' : 'numManual').checked = true;
}
function closeNumModal() { document.getElementById('numModal').classList.remove('open'); }
function saveNumPref() {
  numPref = document.querySelector('input[name="num_pref"]:checked')?.value || 'manual';
  if (numPref === 'auto') {
    document.getElementById('assemblyNumber').value = autoNum;
    document.getElementById('assemblyNumber').readOnly = true;
    document.getElementById('assemblyNumber').style.background = '#f8f9fb';
  } else {
    document.getElementById('assemblyNumber').readOnly = false;
    document.getElementById('assemblyNumber').style.background = '#fff';
  }
  closeNumModal();
}

// ── FORM SUBMIT ────────────────────────────────────────
function submitForm(action) {
  document.getElementById('formAction').value = action;

  // Build JSON payloads
  document.getElementById('associatedItemsJson').value = JSON.stringify(
    itemRows.map(r => ({
      product_id: r.product_id,
      name: r.name,
      sku: r.sku,
      unit: r.unit,
      quantity: r.qty,
      cost_price: r.cost ?? 0,
    }))
  );
  document.getElementById('associatedServicesJson').value = JSON.stringify(
    serviceRows.map(r => ({
      product_id: r.product_id,
      name: r.name,
      sku: r.sku,
      unit: r.unit,
      quantity: r.qty,
      cost_price: r.cost ?? 0,
    }))
  );

  document.getElementById('assemblyForm').submit();
}

// ── UTILS ──────────────────────────────────────────────
function escHtml(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escAttr(s) { return String(s||'').replace(/'/g,"\\'"); }

// ── INIT — preselected composite item ──────────────────
document.addEventListener('DOMContentLoaded', () => {
  const pid = '<?php echo e($preselectedId ?? ''); ?>';
  if (pid) {
    loadCompositeDetails(pid);
    const ci = COMPOSITE_ITEMS.find(c => c.id == pid);
    if (ci) {
      document.getElementById('compositeSkuVal').textContent = ci.sku || '—';
    }
  }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/assemblies/create.blade.php ENDPATH**/ ?>