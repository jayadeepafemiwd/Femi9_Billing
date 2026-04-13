@extends('layouts.app')
@section('title', 'New Assembly')

@push('styles')
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
.composite-select-wrap { position: relative; }
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

/* ── STOCK ALERT ── */
.stock-alert {
  background: #fef3cd;
  border: 1px solid #ffc107;
  border-radius: 6px;
  padding: 12px 16px;
  font-size: 13px;
  color: #856404;
  margin-bottom: 16px;
  display: none;
}
.stock-alert.show { display: block; }
.stock-alert-title { font-weight: 600; margin-bottom: 6px; }
.stock-alert ul { margin: 0; padding-left: 18px; }
.stock-alert li { margin-bottom: 2px; }

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
.qty-avail.warn { color: #e74c3c; font-weight: 600; }

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

/* ── PRODUCT SEARCH DD (for new rows) ── */
.product-search-wrap { position: relative; }
.product-search-dd {
  display: none;
  position: absolute;
  top: calc(100% + 2px);
  left: 0; right: 0;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  z-index: 400;
  max-height: 200px;
  overflow-y: auto;
}
.product-search-dd.open { display: block; }
.product-search-item {
  padding: 8px 12px;
  font-size: 13px;
  cursor: pointer;
  color: #333;
}
.product-search-item:hover { background: #f0f5ff; color: #2d5be3; }
.product-search-item .ps-sku { font-size: 11px; color: #888; }

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
@endpush

@section('content')
<div class="assembly-form">
  <h1 class="page-title">🔩 New Assembly</h1>

  {{-- SESSION ERROR (server-side stock shortage, etc.) --}}
  @if(session('error'))
    <div style="background:#fde8e8;color:#c0392b;padding:10px 14px;border-radius:5px;margin-bottom:16px;font-size:13px;white-space:pre-line;">
      {{ session('error') }}
    </div>
  @endif

  {{-- CLIENT-SIDE STOCK ALERT (shown before submit) --}}
  <div class="stock-alert" id="stockAlert">
    <div class="stock-alert-title">⚠️ Insufficient Stock — Assembly not possible</div>
    <ul id="stockAlertList"></ul>
  </div>

  <form id="assemblyForm" method="POST" action="{{ route('assemblies.store') }}">
    @csrf
    <input type="hidden" name="action" id="formAction" value="assemble">
    <input type="hidden" name="associated_items_json"    id="associatedItemsJson">
    <input type="hidden" name="associated_services_json" id="associatedServicesJson">

    {{-- Composite Item --}}
    <div class="form-row">
      <div class="form-label">Composite Item*</div>
      <div class="form-content">
        <input type="hidden" name="composite_item_id" id="compositeItemId" value="{{ $preselectedId ?? '' }}">
        <div class="composite-select-wrap">
          <div class="composite-input-box" id="compositeBox" onclick="toggleCompositeDd()">
            <span class="composite-selected-name" id="compositeLabel">{{ $preselectedName ?? '' }}</span>
            @if(!$preselectedName)
              <span class="composite-placeholder" id="compositePlaceholder">Select a composite item</span>
            @endif
            <span class="composite-clear" id="compositeClear"
                  onclick="clearComposite(event)"
                  style="{{ $preselectedId ? '' : 'display:none' }}">×</span>
            <span class="composite-chevron">▼</span>
          </div>
          <div class="composite-dd" id="compositeDd">
            <input type="text" class="composite-dd-search"
                   placeholder="Search composite items..."
                   oninput="filterComposite(this.value)" autocomplete="off">
            <div id="compositeDdList">
              @foreach($compositeItems as $ci)
                <div class="composite-dd-item"
                     onclick="selectComposite({{ $ci->id }}, '{{ addslashes($ci->name) }}', '{{ $ci->sku ?? '' }}')">
                  {{ $ci->name }}
                  @if($ci->sku)
                    <div class="dd-sku">SKU: {{ $ci->sku }}</div>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="item-sku-sub" id="compositeSku" style="{{ $preselectedId ? '' : 'display:none' }}">
          SKU: <span id="compositeSkuVal"></span>
        </div>
      </div>
    </div>

    {{-- Assembly Number --}}
    <div class="form-row">
      <div class="form-label">Assembly#*</div>
      <div class="form-content">
        <div class="assembly-num-wrap">
          <input type="text" name="assembly_number" id="assemblyNumber"
                 value="{{ $autoNumber }}" class="form-input">
          <button type="button" class="gear-btn" onclick="openNumModal()" title="Configure numbering">⚙️</button>
        </div>
      </div>
    </div>

    {{-- Description --}}
    <div class="form-row">
      <div class="form-label gray">Description</div>
      <div class="form-content">
        <textarea name="description" class="form-input" rows="3" style="resize:vertical;"></textarea>
      </div>
    </div>

    {{-- Assembled Date --}}
    <div class="form-row">
      <div class="form-label">Assembled Date*</div>
      <div class="form-content">
        <input type="date" name="assembled_date" class="form-input"
               value="{{ date('Y-m-d') }}" required>
      </div>
    </div>

    {{-- Quantity to Assemble --}}
    <div class="form-row">
      <div class="form-label">Quantity to Assemble*</div>
      <div class="form-content">
        <input type="number" name="quantity_to_assemble" id="qtyToAssemble"
               class="form-input" value="1" min="0.0001" step="0.0001"
               oninput="updateTotalQty()" required>
        <div class="item-sku-sub" id="qtyAvailMsg">
          You can assemble <strong id="maxAssembleQty">0</strong> unit(s) from available stock.
        </div>
      </div>
    </div>

    {{-- Location --}}
    <div class="form-row">
      <div class="form-label">Location*</div>
      <div class="form-content">
        <select name="location_id" id="locationSelect" class="form-select" required>
          <option value="">Select Location</option>
          @foreach($locations as $loc)
            <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <hr class="divider">

    {{-- Associated Items --}}
    <div class="assoc-section">
      <div class="assoc-title">Associated Items*</div>

      <div class="info-box">
        ℹ️ If you've incurred an additional cost while assembling (e.g. rent, labour, scrap), add it as a <strong>service item</strong>.
      </div>

      <table class="assoc-table" id="itemsTable">
        <thead>
          <tr>
            <th style="width:38%;">Item Details</th>
            <th class="right">Qty Required</th>
            <th class="right">Total Qty</th>
            <th class="right">Qty Available</th>
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
        <button type="button" class="add-row-btn" onclick="addItemRow()">➕ Add New Row</button>
        &nbsp;&nbsp;
        <button type="button" class="add-row-btn" onclick="addServiceRow()">➕ Add Services</button>
      </div>
    </div>

    {{-- Associated Services --}}
    <div class="assoc-section" style="margin-top:24px;">
      <div class="assoc-title">Associated Services</div>

      <table class="assoc-table" id="servicesTable">
        <thead>
          <tr>
            <th style="width:38%;">Service Details</th>
            <th class="right">Qty Required</th>
            <th class="right">Total Qty</th>
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
        <button type="button" class="add-row-btn" onclick="addServiceRow()">➕ Add New Row</button>
      </div>
    </div>

    <div style="height:70px;"></div>
  </form>
</div>

{{-- Assembly Number Modal --}}
<div class="modal-overlay" id="numModal">
  <div class="modal-box">
    <div class="modal-head">
      <span>Configure Assembly# Preferences</span>
      <button class="modal-x" onclick="closeNumModal()">×</button>
    </div>
    <div class="modal-body">
      <p>Do you want us to auto-generate assembly numbers?</p>
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

{{-- Bottom Bar --}}
<div class="bottom-bar">
  <button type="button" class="btn-save-draft" onclick="submitForm('draft')">Save as Draft</button>
  <button type="button" class="btn-assemble"   onclick="submitForm('assemble')">Assemble</button>
  <a href="{{ route('assemblies.index') }}" class="btn-cancel">Cancel</a>
</div>
@endsection

@push('scripts')
<script>
// ── DATA ──────────────────────────────────────────────
const COMPOSITE_ITEMS = @json($compositeItems);
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

let itemRows    = [];
let serviceRows = [];
let numPref     = 'manual';
let autoNum     = '{{ $autoNumber }}';

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
  document.getElementById('compositeItemId').value = id;
  document.getElementById('compositeLabel').textContent = name;
  document.getElementById('compositePlaceholder') &&
    (document.getElementById('compositePlaceholder').style.display = 'none');
  document.getElementById('compositeClear').style.display = '';
  document.getElementById('compositeSku').style.display  = '';
  document.getElementById('compositeSkuVal').textContent = sku || '—';
  document.getElementById('compositeDd').classList.remove('open');
  document.getElementById('compositeBox').classList.add('selected');
  await loadCompositeDetails(id);
}

function clearComposite(e) {
  e.stopPropagation();
  document.getElementById('compositeItemId').value = '';
  document.getElementById('compositeLabel').textContent = '';
  document.getElementById('compositeClear').style.display = 'none';
  document.getElementById('compositeSku').style.display   = 'none';
  document.getElementById('compositeBox').classList.remove('selected');
  itemRows = []; serviceRows = [];
  renderItems(); renderServices();
  hideStockAlert();
}

document.addEventListener('click', e => {
  const wrap = document.querySelector('.composite-select-wrap');
  if (wrap && !wrap.contains(e.target))
    document.getElementById('compositeDd').classList.remove('open');
});

async function loadCompositeDetails(id) {
  try {
    const locationId = document.getElementById('locationSelect')?.value || '';
    const res  = await fetch(`/assemblies/composite-item/${id}?location_id=${locationId}`, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    const data = await res.json();
    if (!data.success) return;

    itemRows    = data.data.items.map(i => ({ ...i, qty: i.quantity_required }));
    serviceRows = data.data.services.map(s => ({ ...s, qty: s.quantity_required }));
    renderItems();
    renderServices();
    updateMaxQty();
    hideStockAlert();
  } catch(e) {
    console.error(e);
  }
}

// ── ITEMS TABLE ────────────────────────────────────────
function renderItems() {
  const tbody = document.getElementById('itemsTbody');
  if (!itemRows.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="empty-msg">Select a composite item to load associated items</td></tr>';
    return;
  }

  const qta = parseFloat(document.getElementById('qtyToAssemble').value) || 1;

  tbody.innerHTML = itemRows.map((row, idx) => {
    const totalQty = (row.qty * qta).toFixed(4).replace(/\.?0+$/, '');
    const avail    = parseFloat(row.quantity_available ?? 0);
    const needed   = row.qty * qta;
    const isWarn   = avail < needed;
    const imgHtml  = row.image_url
      ? `<img src="${row.image_url}" alt="">`
      : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>`;

    // New row (no product_id yet) — show search input
    const itemCell = row.product_id
      ? `<div class="item-cell">
           <div class="item-img">${imgHtml}</div>
           <div>
             <div class="item-name">${escHtml(row.name)}</div>
             <div class="item-sku">${row.sku ? 'SKU: '+escHtml(row.sku) : ''}</div>
           </div>
         </div>`
      : `<div class="product-search-wrap">
           <input type="text" class="form-input" placeholder="Search product..."
                  oninput="searchProduct(${idx}, this.value, 'item', this)"
                  autocomplete="off">
           <div class="product-search-dd" id="psDd_item_${idx}"></div>
         </div>`;

    return `
      <tr data-idx="${idx}">
        <td>${itemCell}</td>
        <td class="right">
          <input class="qty-input" type="number" min="0.0001" step="0.0001"
                 value="${row.qty}"
                 onchange="onItemQtyChange(${idx}, this.value)">
        </td>
        <td class="right">
          <div class="total-qty-cell">${totalQty}</div>
          <div class="total-qty-sub">× ${qta} assemblies</div>
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
        <td colspan="5" class="cost-row">🏷️ Cost Price: <a>View</a></td>
      </tr>`;
  }).join('');
}

function onItemQtyChange(idx, val) {
  itemRows[idx].qty = parseFloat(val) || 0;
  renderItems();
  updateMaxQty();
  checkStockBeforeAlert();
}

function removeItemRow(idx) {
  itemRows.splice(idx, 1);
  renderItems();
  checkStockBeforeAlert();
}

function addItemRow() {
  itemRows.push({ product_id: null, name: '', sku: '', unit: '', qty: 1, quantity_available: 0, cost: 0, image_url: null });
  renderItems();
}

// ── SERVICES TABLE ─────────────────────────────────────
function renderServices() {
  const tbody = document.getElementById('servicesTbody');
  if (!serviceRows.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="empty-msg">No services added</td></tr>';
    return;
  }

  const qta = parseFloat(document.getElementById('qtyToAssemble').value) || 1;

  tbody.innerHTML = serviceRows.map((row, idx) => {
    const totalQty = (row.qty * qta).toFixed(4).replace(/\.?0+$/, '');
    const imgHtml  = row.image_url
      ? `<img src="${row.image_url}" alt="">`
      : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>`;

    const svcCell = row.product_id
      ? `<div class="item-cell">
           <div class="item-img">${imgHtml}</div>
           <div>
             <div class="item-name">${escHtml(row.name)}</div>
             <div class="item-sku">${row.sku ? 'SKU: '+escHtml(row.sku) : ''}</div>
           </div>
         </div>`
      : `<div class="product-search-wrap">
           <input type="text" class="form-input" placeholder="Search service..."
                  oninput="searchProduct(${idx}, this.value, 'service', this)"
                  autocomplete="off">
           <div class="product-search-dd" id="psDd_service_${idx}"></div>
         </div>`;

    return `
      <tr data-idx="${idx}">
        <td>${svcCell}</td>
        <td class="right">
          <input class="qty-input" type="number" min="0.0001" step="0.0001"
                 value="${row.qty}"
                 onchange="onSvcQtyChange(${idx}, this.value)">
        </td>
        <td class="right">
          <div class="total-qty-cell">${totalQty}</div>
          <div class="total-qty-sub">× ${qta} assemblies</div>
        </td>
        <td class="right">${parseFloat(row.cost || 0).toFixed(2)}</td>
        <td>
          <span style="color:#e74c3c;cursor:pointer;font-size:18px;font-weight:bold;"
                onclick="removeSvcRow(${idx})">×</span>
        </td>
      </tr>
      <tr>
        <td colspan="5" class="cost-row">🏷️ Cost Price: <a>View</a></td>
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
  serviceRows.push({ product_id: null, name: '', sku: '', unit: '', qty: 1, cost: 0, image_url: null });
  renderServices();
}

// ── PRODUCT SEARCH (for new rows) ─────────────────────
let searchTimer = null;
async function searchProduct(idx, q, type, inputEl) {
  const ddId = `psDd_${type}_${idx}`;
  const dd   = document.getElementById(ddId);
  if (!dd) return;

  clearTimeout(searchTimer);
  if (!q.trim()) { dd.classList.remove('open'); return; }

  searchTimer = setTimeout(async () => {
    try {
      const productType = type === 'service' ? 'service' : 'goods';
      const res  = await fetch(`/composite-items/search-products?q=${encodeURIComponent(q)}&type=${productType}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
      const data = await res.json();
      const list = data.data || data.products || data || [];

      if (!list.length) {
        dd.innerHTML = '<div class="product-search-item" style="color:#aaa;">No results</div>';
      } else {
        dd.innerHTML = list.slice(0, 10).map(p => `
          <div class="product-search-item"
               onclick="selectProductRow(${idx}, ${JSON.stringify(p).replace(/"/g,'&quot;')}, '${type}')">
            ${escHtml(p.name)}
            ${p.sku ? `<div class="ps-sku">SKU: ${escHtml(p.sku)}</div>` : ''}
          </div>`).join('');
      }
      dd.classList.add('open');
    } catch(e) { console.error(e); }
  }, 300);
}

async function selectProductRow(idx, product, type) {
  const locationId = document.getElementById('locationSelect')?.value || '';
  let availStock = 0;

  if (type === 'item' && product.id) {
    try {
      const res  = await fetch(`/assemblies/composite-item/${document.getElementById('compositeItemId').value}?location_id=${locationId}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
    } catch(e) {}
    // Fetch stock directly
    try {
      const res2 = await fetch(`/products/${product.id}?format=json`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
      });
    } catch(e) {}
    availStock = product.stock_on_hand ?? 0;
  }

  if (type === 'item') {
    itemRows[idx] = {
      product_id: product.id,
      name: product.name,
      sku:  product.sku || '',
      unit: product.unit || '',
      qty:  1,
      quantity_available: availStock,
      cost: product.cost_price || 0,
      image_url: null,
    };
    renderItems();
    updateMaxQty();
    checkStockBeforeAlert();
  } else {
    serviceRows[idx] = {
      product_id: product.id,
      name: product.name,
      sku:  product.sku || '',
      unit: product.unit || '',
      qty:  1,
      cost: product.cost_price || 0,
      image_url: null,
    };
    renderServices();
  }
}

// Close product search dd on outside click
document.addEventListener('click', e => {
  if (!e.target.closest('.product-search-wrap')) {
    document.querySelectorAll('.product-search-dd').forEach(d => d.classList.remove('open'));
  }
});

// ── QTY TO ASSEMBLE ────────────────────────────────────
function updateTotalQty() {
  renderItems();
  renderServices();
  updateMaxQty();
  checkStockBeforeAlert();
}

function updateMaxQty() {
  if (!itemRows.length) {
    document.getElementById('maxAssembleQty').textContent = 0;
    return;
  }
  let maxAssemble = Infinity;
  itemRows.forEach(r => {
    if (r.qty > 0 && r.product_id) {
      maxAssemble = Math.min(maxAssemble, Math.floor((r.quantity_available ?? 0) / r.qty));
    }
  });
  if (maxAssemble === Infinity) maxAssemble = 0;
  document.getElementById('maxAssembleQty').textContent = maxAssemble;
}

// ── STOCK ALERT (client-side) ─────────────────────────
function checkStockBeforeAlert() {
  const qta = parseFloat(document.getElementById('qtyToAssemble').value) || 1;
  const shortages = [];

  itemRows.forEach(r => {
    if (!r.product_id) return;
    const needed = r.qty * qta;
    const avail  = parseFloat(r.quantity_available ?? 0);
    if (avail < needed) {
      shortages.push({
        name:    r.name,
        needed:  needed,
        avail:   avail,
        unit:    r.unit || '',
      });
    }
  });

  if (shortages.length) {
    const list = document.getElementById('stockAlertList');
    list.innerHTML = shortages.map(s =>
      `<li><strong>${escHtml(s.name)}</strong>: Need <strong>${s.needed}</strong> ${escHtml(s.unit)} — Available only <strong>${s.avail}</strong> ${escHtml(s.unit)}</li>`
    ).join('');
    document.getElementById('stockAlert').classList.add('show');
  } else {
    hideStockAlert();
  }

  return shortages.length === 0;
}

function hideStockAlert() {
  document.getElementById('stockAlert').classList.remove('show');
}

// ── NUMBER MODAL ────────────────────────────────────────
function openNumModal() { document.getElementById('numModal').classList.add('open'); }
function closeNumModal() { document.getElementById('numModal').classList.remove('open'); }
function saveNumPref() {
  numPref = document.querySelector('input[name="num_pref"]:checked')?.value || 'manual';
  if (numPref === 'auto') {
    document.getElementById('assemblyNumber').value     = autoNum;
    document.getElementById('assemblyNumber').readOnly  = true;
    document.getElementById('assemblyNumber').style.background = '#f8f9fb';
  } else {
    document.getElementById('assemblyNumber').readOnly  = false;
    document.getElementById('assemblyNumber').style.background = '#fff';
  }
  closeNumModal();
}

// ── FORM SUBMIT ────────────────────────────────────────
function submitForm(action) {
  document.getElementById('formAction').value = action;

  const compositeId = document.getElementById('compositeItemId').value;
  if (!compositeId) { alert('Please select a Composite Item first!'); return; }

  const locationId = document.getElementById('locationSelect').value;
  if (!locationId) { alert('Please select a Location!'); return; }

  // For "assemble" action — check stock first (client-side)
  if (action === 'assemble') {
    const stockOk = checkStockBeforeAlert();
    if (!stockOk) {
      // Scroll to alert
      document.getElementById('stockAlert').scrollIntoView({ behavior: 'smooth', block: 'center' });
      return; // Block submit — don't send to server
    }
  }

  document.getElementById('associatedItemsJson').value = JSON.stringify(
    itemRows.map(r => ({
      product_id: r.product_id,
      name:       r.name,
      sku:        r.sku,
      unit:       r.unit,
      quantity:   r.qty,
      cost_price: r.cost ?? 0,
    }))
  );
  document.getElementById('associatedServicesJson').value = JSON.stringify(
    serviceRows.map(r => ({
      product_id: r.product_id,
      name:       r.name,
      sku:        r.sku,
      unit:       r.unit,
      quantity:   r.qty,
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

// ── INIT ───────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const pid = '{{ $preselectedId ?? '' }}';
  if (pid) {
    loadCompositeDetails(pid);
    const ci = COMPOSITE_ITEMS.find(c => c.id == pid);
    if (ci) document.getElementById('compositeSkuVal').textContent = ci.sku || '—';
  }

  // Reload stock when location changes
  document.getElementById('locationSelect')?.addEventListener('change', function() {
    const compositeId = document.getElementById('compositeItemId').value;
    if (compositeId) loadCompositeDetails(compositeId);
  });

  // Also re-check when qty changes
  document.getElementById('qtyToAssemble')?.addEventListener('input', checkStockBeforeAlert);
});
</script>
@endpush