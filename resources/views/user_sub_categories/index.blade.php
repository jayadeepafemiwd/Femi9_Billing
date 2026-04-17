<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sub Categories | Inventory</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #333; background: #f5f5f5; }
  .app-shell { display: flex; height: 100vh; overflow: hidden; }
  .sidebar { width: 220px; background: #1a1f2e; color: #ccc; flex-shrink: 0; display: flex; flex-direction: column; }
  .sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 16px 18px; border-bottom: 1px solid #2e3448; }
  .sidebar-logo .logo-icon { width: 28px; height: 28px; background: #e8f0fe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
  .sidebar-logo span { font-size: 15px; font-weight: 600; color: #fff; }
  .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
  .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 18px; cursor: pointer; font-size: 13px; color: #aab; }
  .nav-item:hover { background: #252b3d; color: #fff; }
  .nav-sub { padding-left: 42px; font-size: 12.5px; color: #aab; padding-top: 7px; padding-bottom: 7px; cursor: pointer; }
  .nav-sub:hover { color: #fff; background: #252b3d; }
  .nav-sub.active { color: #fff; background: #3b6cf8; }
  .sidebar-bottom { padding: 12px; border-top: 1px solid #2e3448; }
  .topbar { display: flex; align-items: center; gap: 12px; padding: 0 20px; height: 52px; background: #fff; border-bottom: 1px solid #e8e8e8; }
  .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
  .avatar { width: 32px; height: 32px; border-radius: 50%; background: #3b6cf8; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 13px; }
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
  .content { flex: 1; overflow-y: auto; padding: 24px; }
  .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .page-title { font-size: 20px; font-weight: 600; color: #222; }
  .page-subtitle { font-size: 13px; color: #888; margin-top: 2px; }
  .btn-primary { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background: #3b6cf8; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-primary:hover { background: #2b5ce0; }
  .filter-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
  .filter-bar select { padding: 7px 12px; border: 1px solid #d0d0d0; border-radius: 6px; font-size: 13px; background: #fff; outline: none; min-width: 200px; }
  .filter-bar select:focus { border-color: #3b6cf8; }
  .search-box { display: flex; align-items: center; gap: 6px; border: 1px solid #d0d0d0; border-radius: 6px; padding: 7px 12px; background: #fff; flex: 1; max-width: 320px; }
  .search-box input { border: none; outline: none; font-size: 13px; width: 100%; }
  .card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); overflow: hidden; }
  .card-table { width: 100%; border-collapse: collapse; }
  .card-table th { background: #f8f9fc; font-size: 11px; font-weight: 600; color: #666; padding: 10px 16px; text-align: left; border-bottom: 1px solid #e8e8e8; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }
  .card-table td { padding: 11px 16px; border-bottom: 1px solid #f2f2f2; vertical-align: middle; }
  .card-table tr:last-child td { border-bottom: none; }
  .card-table tr:hover td { background: #fafbff; }
  .card-table td input { width: 100%; padding: 5px 8px; border: 1px solid #3b6cf8; border-radius: 4px; font-size: 13px; outline: none; box-shadow: 0 0 0 2px rgba(59,108,248,0.1); }
  .card-table td select { padding: 5px 8px; border: 1px solid #3b6cf8; border-radius: 4px; font-size: 13px; outline: none; }
  .badge { display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
  .badge-active   { background: #dcfce7; color: #16a34a; }
  .badge-inactive { background: #f3f4f6; color: #888; }
  .badge-cat { background: #e8f0fe; color: #3b6cf8; font-size: 11px; padding: 2px 8px; border-radius: 10px; }
  .act-btn { padding: 4px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; border: 1px solid #d0d0d0; background: #fff; transition: all 0.15s; margin-left: 3px; }
  .act-btn.edit   { color: #3b6cf8; border-color: #b8d0ff; }
  .act-btn.edit:hover   { background: #3b6cf8; color: #fff; }
  .act-btn.save   { color: #16a34a; border-color: #86efac; }
  .act-btn.save:hover   { background: #16a34a; color: #fff; }
  .act-btn.cancel { color: #888; }
  .act-btn.cancel:hover { background: #f5f5f5; }
  .act-btn.del    { color: #e53935; border-color: #fca5a5; }
  .act-btn.del:hover    { background: #e53935; color: #fff; }
  .add-panel { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,0.07); padding: 20px 24px; margin-bottom: 20px; border-left: 4px solid #3b6cf8; }
  .add-panel h3 { font-size: 14px; font-weight: 600; color: #3b6cf8; margin-bottom: 16px; }
  .form-field label { display: block; font-size: 11px; font-weight: 600; color: #666; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.4px; }
  .form-field input, .form-field select { width: 100%; padding: 8px 10px; border: 1px solid #d0d0d0; border-radius: 6px; font-size: 13px; outline: none; }
  .form-field input:focus, .form-field select:focus { border-color: #3b6cf8; box-shadow: 0 0 0 2px rgba(59,108,248,0.1); }
  .err-msg { color: #e53935; font-size: 12px; margin-top: 8px; display: none; }
  .empty-state { padding: 48px; text-align: center; color: #aaa; }
  .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
  .empty-state p { font-size: 14px; }
  .alert { padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; display: none; }
  .alert.success { background: #dcfce7; border: 1px solid #86efac; color: #16a34a; }
  .alert.error   { background: #fde8e8; border: 1px solid #fca5a5; color: #991b1b; }
  .stats-row { display: flex; gap: 12px; margin-bottom: 20px; }
  .stat-card { background: #fff; border-radius: 8px; padding: 14px 18px; flex: 1; box-shadow: 0 1px 4px rgba(0,0,0,0.07); }
  .stat-card .stat-val { font-size: 22px; font-weight: 700; color: #222; }
  .stat-card .stat-lbl { font-size: 11px; color: #888; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.4px; }
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
      <div class="nav-item">📦 Items</div>
      <div class="nav-item">🏪 Inventory</div>
      <div class="nav-item" style="color:#fff;background:#252b3d;">🛒 Sales</div>
      <div class="nav-sub"><a href="{{ route('customers.index') }}" style="color:inherit;text-decoration:none;">Customers</a></div>
      <div class="nav-sub active">Sub Categories</div>
    </div>
    <div class="sidebar-bottom" style="color:#666;font-size:12px;cursor:pointer;">⟨ Collapse</div>
  </div>

  <div class="main">
    <!-- TOPBAR -->
    <div class="topbar">
      <span style="font-size:13px;color:#888;">
        <a href="{{ route('customers.index') }}" style="color:#3b6cf8;text-decoration:none;">Customers</a>
        › Sub Categories
      </span>
      <div class="topbar-right">
        <div class="avatar">V</div>
      </div>
    </div>

    <div class="content">

      <!-- Alert -->
      <div class="alert" id="pageAlert"></div>

      <!-- Page header -->
      <div class="page-header">
        <div>
          <div class="page-title">Customer Sub Categories</div>
          <div class="page-subtitle">Manage sub-categories under each Customer Category</div>
        </div>
      </div>

      <!-- Stats -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-val" id="statTotal">{{ $subCategories->count() }}</div>
          <div class="stat-lbl">Total Sub-Categories</div>
        </div>
        <div class="stat-card">
          <div class="stat-val" id="statActive">{{ $subCategories->where('status','active')->count() }}</div>
          <div class="stat-lbl">Active</div>
        </div>
        <div class="stat-card">
          <div class="stat-val">{{ $categories->count() }}</div>
          <div class="stat-lbl">Parent Categories</div>
        </div>
      </div>

      <!-- Add form -->
      <div class="add-panel">
        <h3>＋ Add New Sub-Category</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr; gap:12px;">
          <div class="form-field">
            <label>Parent Category <span style="color:#e53935;">*</span></label>
            <select id="addCatId">
              <option value="">-- Select --</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-field">
            <label>Sub-Category Name <span style="color:#e53935;">*</span></label>
            <input type="text" id="addName" placeholder="e.g. Super_urban">
          </div>
          <div class="form-field">
            <label>Description</label>
            <input type="text" id="addDesc" placeholder="Short description">
          </div>
          <div class="form-field">
            <label>Target Amount</label>
            <input type="number" id="addTargetAmount" placeholder="e.g. 50000" min="0" step="0.01">
          </div>
          <div class="form-field">
            <label>Reference</label>
            <input type="text" id="addReference" placeholder="e.g. REF-001">
          </div>
          <div class="form-field">
            <label>Coupon</label>
            <input type="text" id="addCoupon" placeholder="e.g. SAVE10">
          </div>
        </div>

        <div class="err-msg" id="addErr"></div>
        <div style="margin-top:12px;display:flex;gap:8px;">
          <button class="btn-primary" id="addBtn" onclick="addSubCat()">Save Sub-Category</button>
          <button class="act-btn cancel" onclick="clearAddForm()">Clear</button>
        </div>
      </div>

      <!-- Filter bar -->
      <div class="filter-bar">
        <select id="filterCat" onchange="filterTable()">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
          @endforeach
        </select>
        <div class="search-box">
          <span>🔍</span>
          <input type="text" id="searchInput" placeholder="Search sub-categories..." oninput="filterTable()">
        </div>
        <span id="rowCount" style="color:#888;font-size:12px;margin-left:auto;"></span>
      </div>

      <!-- Table -->
      <div class="card">
        <table class="card-table" id="mainTable">
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>Sub-Category Name</th>
              <th>Description</th>
              <th>Target Amount</th>
              <th>Reference</th>
              <th>Coupon</th>
              <th>Parent Category</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @forelse($subCategories as $i => $sub)
            <tr id="row-{{ $sub->id }}" data-cat-id="{{ $sub->user_category_id }}" data-name="{{ strtolower($sub->name) }}">

              {{-- # column — no edit needed --}}
              <td style="color:#aaa;">{{ $i + 1 }}</td>

              {{-- NAME --}}
              <td id="disp-name-{{ $sub->id }}" style="font-weight:500;">{{ $sub->name }}</td>
              <td id="edit-name-{{ $sub->id }}" style="display:none;">
                <input type="text" value="{{ $sub->name }}" id="inp-name-{{ $sub->id }}">
              </td>

              {{-- DESCRIPTION --}}
              <td id="disp-desc-{{ $sub->id }}" style="color:#888;">{{ $sub->description ?: '—' }}</td>
              <td id="edit-desc-{{ $sub->id }}" style="display:none;">
                <input type="text" value="{{ $sub->description }}" id="inp-desc-{{ $sub->id }}">
              </td>

              {{-- TARGET AMOUNT --}}
              <td id="disp-target-{{ $sub->id }}" style="color:#333;">{{ $sub->target_amount ? number_format($sub->target_amount, 2) : '—' }}</td>
              <td id="edit-target-{{ $sub->id }}" style="display:none;">
                <input type="number" value="{{ $sub->target_amount }}" id="inp-target-{{ $sub->id }}" min="0" step="0.01">
              </td>

              {{-- REFERENCE --}}
              <td id="disp-ref-{{ $sub->id }}" style="color:#888;">{{ $sub->reference ?: '—' }}</td>
              <td id="edit-ref-{{ $sub->id }}" style="display:none;">
                <input type="text" value="{{ $sub->reference }}" id="inp-ref-{{ $sub->id }}">
              </td>

              {{-- COUPON --}}
              <td id="disp-coupon-{{ $sub->id }}" style="color:#888;">{{ $sub->coupon ?: '—' }}</td>
              <td id="edit-coupon-{{ $sub->id }}" style="display:none;">
                <input type="text" value="{{ $sub->coupon }}" id="inp-coupon-{{ $sub->id }}">
              </td>

              {{-- PARENT CATEGORY --}}
              <td id="disp-cat-{{ $sub->id }}"><span class="badge badge-cat">{{ $sub->category->name ?? '—' }}</span></td>
              <td id="edit-cat-{{ $sub->id }}" style="display:none;">
                <span class="badge badge-cat">{{ $sub->category->name ?? '—' }}</span>
              </td>

              {{-- STATUS --}}
              <td id="disp-status-{{ $sub->id }}">
                <span class="badge badge-{{ $sub->status }}">{{ $sub->status }}</span>
              </td>
              <td id="edit-status-{{ $sub->id }}" style="display:none;">
                <select id="inp-status-{{ $sub->id }}">
                  <option value="active"   {{ $sub->status === 'active'   ? 'selected' : '' }}>active</option>
                  <option value="inactive" {{ $sub->status === 'inactive' ? 'selected' : '' }}>inactive</option>
                </select>
              </td>

              {{-- ACTIONS --}}
              <td id="disp-actions-{{ $sub->id }}" style="text-align:right;white-space:nowrap;">
                <button class="act-btn edit" onclick="startEdit({{ $sub->id }})">Edit</button>
                <button class="act-btn del"  onclick="deleteSub({{ $sub->id }})">Delete</button>
              </td>
              <td id="edit-actions-{{ $sub->id }}" style="display:none;text-align:right;white-space:nowrap;">
                <button class="act-btn save"   onclick="saveEdit({{ $sub->id }})">Save</button>
                <button class="act-btn cancel" onclick="cancelEdit({{ $sub->id }})">Cancel</button>
              </td>

            </tr>
            @empty
            <tr id="emptyRow">
              <td colspan="9">
                <div class="empty-state">
                  <div class="icon">🗂️</div>
                  <p>No sub-categories yet. Add one above.</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>{{-- content --}}
  </div>{{-- main --}}
</div>{{-- app-shell --}}

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let rowCount = {{ $subCategories->count() }};

// ── Alert helper ──────────────────────────────────────────────
function showAlert(msg, type) {
    var el = document.getElementById('pageAlert');
    el.textContent = msg;
    el.className = 'alert ' + type;
    el.style.display = 'block';
    setTimeout(function() { el.style.display = 'none'; }, 3500);
}

// ── Filter ────────────────────────────────────────────────────
function filterTable() {
    var catFilter = document.getElementById('filterCat').value;
    var searchVal = document.getElementById('searchInput').value.toLowerCase();
    var rows      = document.querySelectorAll('#tableBody tr[id^="row-"]');
    var visible   = 0;

    rows.forEach(function(row) {
        var catMatch  = !catFilter || row.dataset.catId === catFilter;
        var nameMatch = !searchVal || row.dataset.name.includes(searchVal);
        var show      = catMatch && nameMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('rowCount').textContent = visible + ' result' + (visible !== 1 ? 's' : '');
}

// ── Add ───────────────────────────────────────────────────────
function addSubCat() {
    var catId        = document.getElementById('addCatId').value;
    var name         = document.getElementById('addName').value.trim();
    var desc         = document.getElementById('addDesc').value.trim();
    var targetAmount = document.getElementById('addTargetAmount').value;
    var reference    = document.getElementById('addReference').value.trim();
    var coupon       = document.getElementById('addCoupon').value.trim();
    var err          = document.getElementById('addErr');

    if (!catId) { showErr(err, 'Please select a parent category.'); return; }
    if (!name)  { showErr(err, 'Sub-category name is required.'); return; }
    err.style.display = 'none';

    var btn = document.getElementById('addBtn');
    btn.disabled = true; btn.textContent = 'Saving...';

    fetch('/user-sub-categories', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            user_category_id: catId,
            name:             name,
            description:      desc         || null,
            target_amount:    targetAmount || null,
            reference:        reference    || null,
            coupon:           coupon       || null,
            status:           'active'
        })
    })
    .then(r => r.json())
    .then(function(json) {
        btn.disabled = false; btn.textContent = 'Save Sub-Category';
        if (json.success) {
            clearAddForm();
            appendRow(json.data);
            rowCount++;
            updateStats();
            showAlert('Sub-category "' + json.data.name + '" added successfully!', 'success');
        } else {
            showErr(err, json.message || 'Failed to save.');
        }
    })
    .catch(function() {
        btn.disabled = false; btn.textContent = 'Save Sub-Category';
        showErr(err, 'Network error. Please try again.');
    });
}

// ── Append Row ────────────────────────────────────────────────
function appendRow(s) {
    var emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();

    var tbody   = document.getElementById('tableBody');
    var idx     = tbody.querySelectorAll('tr[id^="row-"]').length + 1;
    var catName = document.getElementById('addCatId').selectedOptions[0]?.text || '—';
    var target  = s.target_amount
        ? parseFloat(s.target_amount).toLocaleString('en-IN', {minimumFractionDigits:2})
        : '—';

    var tr = document.createElement('tr');
    tr.id = 'row-' + s.id;
    tr.dataset.catId = s.user_category_id;
    tr.dataset.name  = s.name.toLowerCase();

    tr.innerHTML =
        '<td style="color:#aaa;">' + idx + '</td>'

      // NAME
      + '<td id="disp-name-'   + s.id + '" style="font-weight:500;">' + esc(s.name) + '</td>'
      + '<td id="edit-name-'   + s.id + '" style="display:none;"><input type="text" value="' + esc(s.name) + '" id="inp-name-' + s.id + '"></td>'

      // DESC
      + '<td id="disp-desc-'   + s.id + '" style="color:#888;">' + esc(s.description || '—') + '</td>'
      + '<td id="edit-desc-'   + s.id + '" style="display:none;"><input type="text" value="' + esc(s.description||'') + '" id="inp-desc-' + s.id + '"></td>'

      // TARGET
      + '<td id="disp-target-' + s.id + '">' + target + '</td>'
      + '<td id="edit-target-' + s.id + '" style="display:none;"><input type="number" value="' + (s.target_amount||'') + '" id="inp-target-' + s.id + '" min="0" step="0.01"></td>'

      // REFERENCE
      + '<td id="disp-ref-'    + s.id + '" style="color:#888;">' + esc(s.reference || '—') + '</td>'
      + '<td id="edit-ref-'    + s.id + '" style="display:none;"><input type="text" value="' + esc(s.reference||'') + '" id="inp-ref-' + s.id + '"></td>'

      // COUPON
      + '<td id="disp-coupon-' + s.id + '" style="color:#888;">' + esc(s.coupon || '—') + '</td>'
      + '<td id="edit-coupon-' + s.id + '" style="display:none;"><input type="text" value="' + esc(s.coupon||'') + '" id="inp-coupon-' + s.id + '"></td>'

      // CATEGORY
      + '<td id="disp-cat-'    + s.id + '"><span class="badge badge-cat">' + esc(catName) + '</span></td>'
      + '<td id="edit-cat-'    + s.id + '" style="display:none;"><span class="badge badge-cat">' + esc(catName) + '</span></td>'

      // STATUS
      + '<td id="disp-status-' + s.id + '"><span class="badge badge-active">active</span></td>'
      + '<td id="edit-status-' + s.id + '" style="display:none;"><select id="inp-status-' + s.id + '"><option value="active" selected>active</option><option value="inactive">inactive</option></select></td>'

      // ACTIONS
      + '<td id="disp-actions-'+ s.id + '" style="text-align:right;white-space:nowrap;">'
      +   '<button class="act-btn edit" onclick="startEdit(' + s.id + ')">Edit</button>'
      +   '<button class="act-btn del"  onclick="deleteSub(' + s.id + ')">Delete</button>'
      + '</td>'
      + '<td id="edit-actions-'+ s.id + '" style="display:none;text-align:right;white-space:nowrap;">'
      +   '<button class="act-btn save" onclick="saveEdit(' + s.id + ')">Save</button>'
      +   '<button class="act-btn cancel" onclick="cancelEdit(' + s.id + ')">Cancel</button>'
      + '</td>';

    tbody.appendChild(tr);
    filterTable();
}

// ── Edit ──────────────────────────────────────────────────────
var fields = ['name','desc','target','ref','coupon','cat','status','actions'];

function startEdit(id) {
    fields.forEach(function(f) {
        var d = document.getElementById('disp-' + f + '-' + id);
        var e = document.getElementById('edit-' + f + '-' + id);
        if (d) d.style.display = 'none';
        if (e) e.style.display = '';
    });
    document.getElementById('inp-name-' + id)?.focus();
}

function cancelEdit(id) {
    fields.forEach(function(f) {
        var d = document.getElementById('disp-' + f + '-' + id);
        var e = document.getElementById('edit-' + f + '-' + id);
        if (d) d.style.display = '';
        if (e) e.style.display = 'none';
    });
}

function saveEdit(id) {
    var name   = document.getElementById('inp-name-'   + id)?.value.trim();
    var desc   = document.getElementById('inp-desc-'   + id)?.value.trim();
    var target = document.getElementById('inp-target-' + id)?.value;
    var ref    = document.getElementById('inp-ref-'    + id)?.value.trim();
    var coupon = document.getElementById('inp-coupon-' + id)?.value.trim();
    var status = document.getElementById('inp-status-' + id)?.value;

    if (!name) { alert('Name is required.'); return; }

    fetch('/user-sub-categories/' + id, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            name:          name,
            description:   desc   || null,
            target_amount: target || null,
            reference:     ref    || null,
            coupon:        coupon || null,
            status:        status
        })
    })
    .then(r => r.json())
    .then(function(json) {
        if (json.success) {
            document.getElementById('disp-name-'   + id).textContent = json.data.name;
            document.getElementById('disp-desc-'   + id).textContent = json.data.description || '—';
            document.getElementById('disp-target-' + id).textContent = json.data.target_amount
                ? parseFloat(json.data.target_amount).toLocaleString('en-IN', {minimumFractionDigits:2})
                : '—';
            document.getElementById('disp-ref-'    + id).textContent = json.data.reference || '—';
            document.getElementById('disp-coupon-' + id).textContent = json.data.coupon     || '—';
            document.getElementById('disp-status-' + id).innerHTML =
                '<span class="badge badge-' + json.data.status + '">' + json.data.status + '</span>';
            document.getElementById('row-' + id).dataset.name = json.data.name.toLowerCase();
            cancelEdit(id);
            showAlert('Updated successfully!', 'success');
        } else {
            alert(json.message || 'Update failed.');
        }
    })
    .catch(function() { alert('Network error.'); });
}

// ── Delete ────────────────────────────────────────────────────
function deleteSub(id) {
    if (!confirm('Delete this sub-category? This cannot be undone.')) return;

    fetch('/user-sub-categories/' + id, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(function(json) {
        if (json.success) {
            document.getElementById('row-' + id)?.remove();
            rowCount--;
            updateStats();
            showAlert('Sub-category deleted.', 'success');
            filterTable();
        } else {
            alert(json.message || 'Delete failed.');
        }
    })
    .catch(function() { alert('Network error.'); });
}

// ── Helpers ───────────────────────────────────────────────────
function clearAddForm() {
    document.getElementById('addCatId').value        = '';
    document.getElementById('addName').value         = '';
    document.getElementById('addDesc').value         = '';
    document.getElementById('addTargetAmount').value = '';
    document.getElementById('addReference').value    = '';
    document.getElementById('addCoupon').value       = '';
    document.getElementById('addErr').style.display  = 'none';
}

function showErr(el, msg) {
    el.textContent = msg;
    el.style.display = 'block';
}

function updateStats() {
    document.getElementById('statTotal').textContent = rowCount;
    var active = document.querySelectorAll('#tableBody .badge-active').length;
    document.getElementById('statActive').textContent = active;
}

function esc(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

filterTable();
</script>
</body>
</html>