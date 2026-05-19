{{-- resources/views/admin/user-category-permissions/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="cr-page">

    {{-- Header --}}
    <div class="cr-header">
        <div class="cr-breadcrumb">
            <a href="{{ route('admin.ucp.index') }}">Category Permissions</a>
            <span>›</span>
            <span>Create Role</span>
        </div>
    </div>

    <div class="cr-layout">

        {{-- LEFT: Form --}}
        <div class="cr-main">

            <div class="cr-card">
                <div class="cr-card-title">Role Details</div>

                {{-- Permission Name --}}
                <div class="cr-field">
                    <label class="cr-label">Permission Name <span class="cr-required">*</span></label>
                    <input type="text" id="f-name" class="cr-input" placeholder="e.g. Super Stockist Role" required>
                    <div class="cr-hint">A unique name to identify this role</div>
                </div>

                {{-- Select Category --}}
                <div class="cr-field" style="margin-top:20px">
                    <label class="cr-label">Select Category</label>
                    <div class="cr-select-wrap">
                        <select id="f-category" class="cr-select" onchange="onCategoryChange(this.value)">
                            <option value="">— Select a user category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    Level {{ $cat->level }} — {{ $cat->name }}
                                    @if($cat->code) ({{ $cat->code }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <svg class="cr-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                    <div class="cr-hint">Selecting a category will auto-fill the permissions below</div>
                </div>

                {{-- Loading indicator --}}
                <div id="cr-loading" style="display:none" class="cr-loading">
                    <div class="cr-spinner"></div>
                    Loading permissions...
                </div>

                {{-- Category info badge (shown after selection) --}}
                <div id="cr-cat-badge" style="display:none" class="cr-cat-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span id="cr-cat-badge-text"></span>
                </div>
            </div>

            {{-- Permissions Table (hidden until category selected) --}}
            <div class="cr-card" id="cr-perms-card" style="display:none">
                <div class="cr-card-title-row">
                    <div class="cr-card-title">Module Permissions</div>
                    <div class="cr-card-actions">
                        <button type="button" class="cr-btn cr-btn--sm" onclick="toggleAll(true)">Enable all</button>
                        <button type="button" class="cr-btn cr-btn--sm cr-btn--danger" onclick="toggleAll(false)">Disable all</button>
                    </div>
                </div>

                <div class="cr-table-wrap">
                    <table class="cr-table" id="cr-perms-table">
                        <thead>
                            <tr>
                                <th class="cr-th-module">Module</th>
                                <th>Access Scope</th>
                                <th class="cr-center">Create</th>
                                <th class="cr-center">Read</th>
                                <th class="cr-center">Edit</th>
                                <th class="cr-center">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="cr-perms-body">
                            {{-- Filled via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer buttons --}}
            <div class="cr-footer" id="cr-footer" style="display:none">
                <a href="{{ route('admin.ucp.index') }}" class="cr-btn">Cancel</a>
                <button type="button" class="cr-btn cr-btn--primary" onclick="saveRole()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Role
                </button>
            </div>

        </div>

        {{-- RIGHT: Summary panel --}}
        <div class="cr-sidebar">
            <div class="cr-card cr-summary-card">
                <div class="cr-card-title">Summary</div>
                <div id="cr-summary-empty" class="cr-summary-empty">
                    Select a category to see permission summary
                </div>
                <div id="cr-summary-content" style="display:none">
                    <div class="cr-summary-name" id="cr-summary-name">—</div>
                    <div class="cr-summary-list" id="cr-summary-list"></div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Pass modules list to JS --}}
<script>
const MODULES = @json($modules);
const CSRF    = '{{ csrf_token() }}';
const FETCH_URL  = '{{ url("admin/user-category-permissions/by-category") }}';
const STORE_URL  = '{{ route("admin.ucp.store") }}';
</script>

<style>
:root {
    --cr-blue:    #185FA5;
    --cr-blue-lt: #E6F1FB;
    --cr-blue-dk: #0C447C;
    --cr-green:   #0F6E56;
    --cr-red:     #993C1D;
    --cr-red-lt:  #FAECE7;
    --cr-border:  rgba(0,0,0,0.08);
    --cr-text:    #1a1a1a;
    --cr-muted:   #888;
    --cr-bg:      #fff;
    --cr-bg2:     #f8f8f6;
    --cr-radius:  10px;
}

.cr-page        { padding: 24px; max-width: 1200px; margin: 0 auto; }
.cr-header      { margin-bottom: 20px; }
.cr-breadcrumb  { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--cr-muted); }
.cr-breadcrumb a{ color: var(--cr-blue); text-decoration: none; }
.cr-breadcrumb a:hover { text-decoration: underline; }

.cr-layout      { display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; }
@media (max-width: 768px) { .cr-layout { grid-template-columns: 1fr; } }

/* Cards */
.cr-card        { background: var(--cr-bg); border: 0.5px solid var(--cr-border); border-radius: var(--cr-radius); padding: 20px; margin-bottom: 16px; }
.cr-card-title  { font-size: 14px; font-weight: 500; color: var(--cr-text); margin-bottom: 16px; }
.cr-card-title-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 8px; }
.cr-card-actions { display: flex; gap: 6px; }

/* Form fields */
.cr-field       { display: flex; flex-direction: column; gap: 5px; }
.cr-label       { font-size: 11px; font-weight: 500; color: var(--cr-muted); letter-spacing: 0.3px; text-transform: uppercase; }
.cr-required    { color: #e53935; }
.cr-hint        { font-size: 11px; color: var(--cr-muted); margin-top: 3px; }
.cr-input       { height: 38px; padding: 0 12px; border: 0.5px solid var(--cr-border); border-radius: 8px; font-size: 13px; color: var(--cr-text); outline: none; background: var(--cr-bg); }
.cr-input:focus { border-color: var(--cr-blue); box-shadow: 0 0 0 2px rgba(24,95,165,0.1); }

.cr-select-wrap { position: relative; }
.cr-select      { width: 100%; height: 38px; padding: 0 32px 0 12px; border: 0.5px solid var(--cr-border); border-radius: 8px; font-size: 13px; color: var(--cr-text); outline: none; background: var(--cr-bg); appearance: none; cursor: pointer; }
.cr-select:focus { border-color: var(--cr-blue); box-shadow: 0 0 0 2px rgba(24,95,165,0.1); }
.cr-chevron     { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--cr-muted); }

/* Loading */
.cr-loading     { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--cr-muted); margin-top: 12px; }
.cr-spinner     { width: 16px; height: 16px; border: 2px solid var(--cr-border); border-top-color: var(--cr-blue); border-radius: 50%; animation: spin .7s linear infinite; flex-shrink: 0; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Category badge */
.cr-cat-badge   { display: flex; align-items: center; gap: 6px; margin-top: 12px; padding: 8px 12px; background: var(--cr-blue-lt); border-radius: 6px; font-size: 12px; color: var(--cr-blue); font-weight: 500; }

/* Table */
.cr-table-wrap  { overflow-x: auto; margin: 0 -20px; padding: 0 20px; }
.cr-table       { width: 100%; border-collapse: collapse; font-size: 13px; }
.cr-table thead tr { background: var(--cr-bg2); }
.cr-table th    { padding: 9px 14px; text-align: left; font-size: 11px; font-weight: 500; color: var(--cr-muted); border-bottom: 0.5px solid var(--cr-border); white-space: nowrap; letter-spacing: 0.3px; text-transform: uppercase; }
.cr-table td    { padding: 10px 14px; border-bottom: 0.5px solid var(--cr-border); vertical-align: middle; }
.cr-table tr:last-child td { border-bottom: none; }
.cr-table tr:hover td { background: #fafaf9; }
.cr-th-module   { width: 200px; }
.cr-center      { text-align: center; }
.cr-module-name { font-weight: 500; color: var(--cr-text); }

/* Scope select inside table */
.cr-scope-sel   { padding: 4px 24px 4px 8px; border: 0.5px solid var(--cr-border); border-radius: 6px; font-size: 12px; color: var(--cr-text); background: var(--cr-bg); outline: none; appearance: none; cursor: pointer; min-width: 140px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 6px center; }
.cr-scope-sel:focus { border-color: var(--cr-blue); }

/* Toggle switch */
.cr-toggle       { position: relative; width: 34px; height: 20px; display: inline-block; }
.cr-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.cr-toggle span  { position: absolute; inset: 0; background: var(--cr-border); border-radius: 10px; cursor: pointer; transition: background .2s; }
.cr-toggle span:before { content: ''; position: absolute; width: 14px; height: 14px; background: #fff; border-radius: 50%; top: 3px; left: 3px; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
.cr-toggle input:checked + span { background: var(--cr-blue); }
.cr-toggle input:checked + span:before { transform: translateX(14px); }

/* Buttons */
.cr-btn         { height: 36px; padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; border: 0.5px solid var(--cr-border); background: var(--cr-bg); color: var(--cr-muted); display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.cr-btn:hover   { background: var(--cr-bg2); }
.cr-btn--sm     { height: 26px; padding: 0 10px; font-size: 11px; }
.cr-btn--primary { background: var(--cr-blue); color: #fff; border-color: var(--cr-blue); }
.cr-btn--primary:hover { background: var(--cr-blue-dk); }
.cr-btn--danger { color: var(--cr-red); border-color: #F0997B; }
.cr-btn--danger:hover { background: var(--cr-red-lt); }

/* Footer */
.cr-footer      { display: flex; justify-content: flex-end; gap: 8px; padding-top: 4px; }

/* Sidebar summary */
.cr-summary-card { position: sticky; top: 20px; }
.cr-summary-empty { font-size: 12px; color: var(--cr-muted); text-align: center; padding: 20px 0; }
.cr-summary-name  { font-size: 14px; font-weight: 500; color: var(--cr-text); margin-bottom: 12px; padding-bottom: 10px; border-bottom: 0.5px solid var(--cr-border); }
.cr-summary-list  { display: flex; flex-direction: column; gap: 6px; }
.cr-summary-row   { display: flex; align-items: center; justify-content: space-between; font-size: 12px; }
.cr-summary-mod   { color: var(--cr-muted); }
.cr-summary-pills { display: flex; gap: 3px; flex-wrap: wrap; justify-content: flex-end; }
.cr-pill          { font-size: 10px; padding: 1px 6px; border-radius: 10px; font-weight: 500; }
.cr-pill--on      { background: #E1F5EE; color: #0F6E56; }
.cr-pill--off     { background: var(--cr-bg2); color: var(--cr-muted); }
</style>

<script>
// Current permissions state — modified by user toggles
let currentPerms = {};

// ── Called when category dropdown changes ──────────────────────
async function onCategoryChange(categoryId) {
    const badge   = document.getElementById('cr-cat-badge');
    const loading = document.getElementById('cr-loading');
    const card    = document.getElementById('cr-perms-card');
    const footer  = document.getElementById('cr-footer');

    badge.style.display = 'none';
    card.style.display  = 'none';
    footer.style.display = 'none';

    if (!categoryId) {
        resetSummary();
        return;
    }

    // Show loading
    loading.style.display = 'flex';

    try {
        const res  = await fetch(`${FETCH_URL}/${categoryId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const json = await res.json();

        if (json.success) {
            // Store permissions keyed by module
            currentPerms = {};
            MODULES.forEach(m => {
                const p = json.permissions[m] ?? null;
                currentPerms[m] = {
                    scope:      p?.scope      ?? 'none',
                    can_create: p?.can_create ?? false,
                    can_read:   p?.can_read   ?? false,
                    can_edit:   p?.can_edit   ?? false,
                    can_delete: p?.can_delete ?? false,
                };
            });

            renderTable();
            renderSummary(json.category_name);

            // Show badge
            document.getElementById('cr-cat-badge-text').textContent =
                `Permissions loaded from "${json.category_name}"`;
            badge.style.display = 'flex';
            card.style.display  = 'block';
            footer.style.display = 'flex';
        }
    } catch (e) {
        console.error(e);
    } finally {
        loading.style.display = 'none';
    }
}

// ── Render the permissions table ───────────────────────────────
function renderTable() {
    const tbody = document.getElementById('cr-perms-body');
    tbody.innerHTML = MODULES.map(module => {
        const p = currentPerms[module];
        const label = module.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        return `
        <tr>
            <td><span class="cr-module-name">${label}</span></td>
            <td>
                <select class="cr-scope-sel" onchange="updatePerm('${module}', 'scope', this.value)">
                    <option value="none" ${p.scope === 'none' ? 'selected' : ''}>No access</option>
                    <option value="own"  ${p.scope === 'own'  ? 'selected' : ''}>Own records only</option>
                    <option value="all"  ${p.scope === 'all'  ? 'selected' : ''}>Team</option>
                    <option value="all"  ${p.scope === 'all'  ? 'selected' : ''}>All records</option>
                </select>
            </td>
            <td class="cr-center">
                <label class="cr-toggle">
                    <input type="checkbox" ${p.can_create ? 'checked' : ''}
                           onchange="updatePerm('${module}', 'can_create', this.checked)">
                    <span></span>
                </label>
            </td>
            <td class="cr-center">
                <label class="cr-toggle">
                    <input type="checkbox" ${p.can_read ? 'checked' : ''}
                           onchange="updatePerm('${module}', 'can_read', this.checked)">
                    <span></span>
                </label>
            </td>
            <td class="cr-center">
                <label class="cr-toggle">
                    <input type="checkbox" ${p.can_edit ? 'checked' : ''}
                           onchange="updatePerm('${module}', 'can_edit', this.checked)">
                    <span></span>
                </label>
            </td>
            <td class="cr-center">
                <label class="cr-toggle">
                    <input type="checkbox" ${p.can_delete ? 'checked' : ''}
                           onchange="updatePerm('${module}', 'can_delete', this.checked)">
                    <span></span>
                </label>
            </td>
        </tr>`;
    }).join('');
}

// ── Update local state when user changes a toggle/select ───────
function updatePerm(module, field, value) {
    if (!currentPerms[module]) currentPerms[module] = {};
    currentPerms[module][field] = value;
    renderSummary();
}

// ── Enable / Disable all ───────────────────────────────────────
function toggleAll(enable) {
    MODULES.forEach(m => {
        currentPerms[m].scope      = enable ? 'all'  : 'none';
        currentPerms[m].can_create = enable;
        currentPerms[m].can_read   = enable;
        currentPerms[m].can_edit   = enable;
        currentPerms[m].can_delete = enable;
    });
    renderTable();
    renderSummary();
}

// ── Render right sidebar summary ───────────────────────────────
function renderSummary(catName) {
    const nameEl   = document.getElementById('cr-summary-name');
    const listEl   = document.getElementById('cr-summary-list');
    const emptyEl  = document.getElementById('cr-summary-empty');
    const contentEl= document.getElementById('cr-summary-content');

    if (!Object.keys(currentPerms).length) { resetSummary(); return; }

    if (catName) nameEl.textContent = catName;
    emptyEl.style.display   = 'none';
    contentEl.style.display = 'block';

    listEl.innerHTML = MODULES.map(m => {
        const p     = currentPerms[m];
        const label = m.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        const pills = ['can_create','can_read','can_edit','can_delete'].map(f => {
            const short = f.replace('can_','').charAt(0).toUpperCase() + f.replace('can_','').slice(1);
            return `<span class="cr-pill ${p[f] ? 'cr-pill--on' : 'cr-pill--off'}">${short}</span>`;
        }).join('');
        return `<div class="cr-summary-row">
            <span class="cr-summary-mod">${label}</span>
            <div class="cr-summary-pills">${pills}</div>
        </div>`;
    }).join('');
}

function resetSummary() {
    document.getElementById('cr-summary-empty').style.display   = 'block';
    document.getElementById('cr-summary-content').style.display = 'none';
    currentPerms = {};
}

// ── Save role ──────────────────────────────────────────────────
async function saveRole() {
    const name       = document.getElementById('f-name').value.trim();
    const categoryId = document.getElementById('f-category').value;

    if (!name)       { alert('Please enter a permission name.'); return; }
    if (!categoryId) { alert('Please select a category.'); return; }

    const btn = document.querySelector('.cr-btn--primary');
    btn.disabled    = true;
    btn.textContent = 'Saving...';

    const permissions = MODULES.map(m => ({
        module:     m,
        ...currentPerms[m]
    }));

    try {
        const res  = await fetch(STORE_URL, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
            body: JSON.stringify({
                name,
                category_id: categoryId,
                permissions,
            }),
        });
        const json = await res.json();

        if (json.success) {
            window.location.href = '{{ route("admin.ucp.index") }}';
        } else {
            alert(json.message || 'Save failed.');
            btn.disabled    = false;
            btn.innerHTML   = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Role`;
        }
    } catch (e) {
        alert('Network error.');
        btn.disabled = false;
    }
}
</script>

@endsection