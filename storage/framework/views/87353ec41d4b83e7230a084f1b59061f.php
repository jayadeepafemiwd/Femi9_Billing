
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Assign Location</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; min-height: 100vh; }
        .page-wrap  { padding: 30px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 24px; }
        .page-title span { color: #6366f1; }
        .card { background: #fff; border-radius: 12px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); min-height: 560px; }
        .top-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1.5px solid #f1f5f9; flex-wrap: wrap; gap: 10px; }
        .breadcrumb { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
        .bc-item { font-size: 13px; color: #64748b; cursor: pointer; padding: 4px 8px; border-radius: 6px; transition: all 0.15s; }
        .bc-item:hover { background: #eef2ff; color: #6366f1; }
        .bc-item.active { color: #1e293b; font-weight: 700; cursor: default; background: #f1f5f9; }
        .bc-sep { color: #94a3b8; font-size: 15px; }
        .top-bar-right { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .btn { padding: 8px 18px; border-radius: 7px; border: none; cursor: pointer; font-size: 13px; font-weight: 600; transition: all 0.2s; }
        .btn-primary { background: #6366f1; color: #fff; }
        .btn-primary:hover:not(:disabled) { background: #4f46e5; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover:not(:disabled) { background: #059669; }
        .btn-outline { background: #fff; color: #6366f1; border: 1.5px solid #6366f1; }
        .btn-outline:hover { background: #eef2ff; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover:not(:disabled) { background: #d97706; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        .btn:disabled { opacity: 0.45; cursor: not-allowed; }
        .center-area { display: flex; flex-direction: column; align-items: center; padding: 16px 0 24px; }
        .level-title { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px; text-align: center; text-transform: capitalize; }
        .level-hint  { font-size: 13px; color: #94a3b8; margin-bottom: 24px; text-align: center; }
        .items-grid { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; max-width: 700px; width: 100%; margin-bottom: 24px; }

        /* ── Chip styles ── */
        .item-chip { display: inline-flex; align-items: center; gap: 0; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #1e293b; transition: all 0.18s; overflow: hidden; }
        .item-chip:hover { border-color: #6366f1; }
        .chip-main { display: flex; align-items: center; gap: 7px; padding: 9px 12px 9px 16px; flex: 1; }
        .item-chip:hover .chip-main { background: #eef2ff; color: #6366f1; }
        .chip-arrow { color: #6366f1; font-size: 14px; pointer-events: none; }
        .chip-actions { display: flex; align-items: stretch; border-left: 1.5px solid #f1f5f9; }
        .chip-edit { display: flex; align-items: center; justify-content: center; padding: 0 9px; color: #6366f1; font-size: 12px; cursor: pointer; opacity: 0.5; background: transparent; border: none; transition: all 0.15s; height: 100%; }
        .chip-edit:hover { opacity: 1; background: #eef2ff; color: #4f46e5; }
        .chip-del { display: flex; align-items: center; justify-content: center; padding: 0 9px; color: #ef4444; font-size: 11px; cursor: pointer; opacity: 0.45; background: transparent; border: none; border-left: 1px solid #f1f5f9; transition: all 0.15s; height: 100%; }
        .chip-del:hover { opacity: 1; background: #fef2f2; }

        /* ── Inline edit chip ── */
        .chip-edit-inline { display: inline-flex; align-items: center; gap: 6px; background: #fff; border: 2px solid #6366f1; border-radius: 10px; padding: 6px 10px; font-size: 13px; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .chip-edit-inp { border: none; outline: none; background: transparent; font-size: 13px; font-weight: 600; color: #1e293b; width: 120px; }
        .chip-save-btn { background: #10b981; color: #fff; border: none; border-radius: 5px; padding: 3px 8px; font-size: 11px; font-weight: 700; cursor: pointer; }
        .chip-save-btn:hover { background: #059669; }
        .chip-cancel-btn { background: transparent; color: #94a3b8; border: none; font-size: 13px; cursor: pointer; padding: 0 2px; }
        .chip-cancel-btn:hover { color: #ef4444; }

        /* ── Form ── */
        .center-form { width: 100%; max-width: 560px; background: #f8fafc; border: 1px dashed #c7d2fe; border-radius: 12px; padding: 22px 24px; display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .center-form-title { font-size: 14px; font-weight: 700; color: #374151; }
        .center-form-hint  { font-size: 12px; color: #94a3b8; text-align: center; margin-top: -8px; }
        .simple-inp { padding: 9px 14px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; background: #fff; color: #1e293b; width: 260px; }
        .simple-inp:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,0.15); }
        .global-check-wrap { display: flex; align-items: center; gap: 10px; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 8px; padding: 10px 16px; width: 100%; max-width: 360px; cursor: pointer; }
        .global-check-wrap input[type=checkbox] { width: 17px; height: 17px; accent-color: #10b981; cursor: pointer; }
        .global-check-label { font-size: 13px; font-weight: 600; color: #374151; }
        .global-check-label span { color: #10b981; }
        .global-hint-box { font-size: 12px; color: #059669; background: #dcfce7; border-radius: 6px; padding: 8px 14px; width: 100%; max-width: 360px; text-align: center; display: none; }
        .tag-box { width: 100%; min-height: 52px; border: 1.5px solid #d1d5db; border-radius: 8px; background: #fff; padding: 7px 10px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; cursor: text; transition: border-color 0.2s; }
        .tag-box:focus-within { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,0.12); }
        .ptag { display: inline-flex; align-items: center; gap: 5px; background: #eef2ff; border: 1px solid #c7d2fe; color: #4f46e5; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 6px; }
        .ptag-del { cursor: pointer; color: #818cf8; font-size: 11px; }
        .ptag-del:hover { color: #ef4444; }
        .tag-real-inp { border: none; outline: none; background: transparent; font-size: 13px; color: #1e293b; min-width: 140px; flex: 1; padding: 3px 4px; }
        .form-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; justify-content: center; }
        .empty-state { text-align: center; padding: 44px 20px; color: #94a3b8; }
        .empty-state .icon { font-size: 42px; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; line-height: 1.9; }
        .global-badge { font-size: 10px; font-weight: 700; color: #059669; background: #dcfce7; border-radius: 4px; padding: 2px 6px; margin-left: 2px; }

        /* ── Edit Layer inline form ── */
        .edit-layer-banner { width: 100%; max-width: 560px; background: #fffbeb; border: 1.5px solid #fcd34d; border-radius: 10px; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px; }
        .edit-layer-title { font-size: 13px; font-weight: 700; color: #92400e; }
        .edit-layer-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    </style>
</head>
<body>
<div class="page-wrap">
    <p class="page-title">Assign <span>Location</span></p>
    <div class="card">
        <div class="top-bar">
            <div class="breadcrumb" id="breadcrumb"></div>
            <div class="top-bar-right" id="topBarRight">
                <button class="btn btn-primary btn-sm" id="mainAddBtn" onclick="handleMainAdd()">+ Add Layer</button>
            </div>
        </div>
        <div class="center-area" id="centerArea"></div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

async function api(url, data) {
    const opts = {
        method : data ? 'POST' : 'GET',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    };
    if (data) opts.body = JSON.stringify(data);
    const res = await fetch(url, opts);
    return res.json();
}

let layers      = [];
let values      = [];
let navPath     = [];
let addingLayer = false;
let addingValue = false;
let editingLayer = false;   // NEW: editing layer name / global toggle
let editingValueId = null;  // NEW: which value chip is being edited inline
let pendingTags = [];

loadData();

async function loadData() {
    const res = await api('/assign-location/tree');
    layers = res.layers || [];
    values = res.values || [];
    render();
}

function currentDepth() { return navPath.length; }

function getCurrentLayer() {
    const depth = navPath.length;

    if (depth === 0) {
        return layers.find(l => Number(l.depth) === 0 && !l.is_global) || null;
    }

    const parentValueId = Number(navPath[depth - 1].valueId);

    const specific = layers.find(
        l => !l.is_global
          && Number(l.depth) === depth
          && Number(l.parent_value_id) === parentValueId
    );
    if (specific) return specific;

    return layers.find(l => l.is_global && Number(l.depth) === depth) || null;
}

function getCurrentValues() {
    const layer = getCurrentLayer();
    if (!layer) return [];

    if (navPath.length === 0) {
        return values.filter(v => Number(v.layer_id) === Number(layer.id) && v.parent_value_id == null);
    }

    const parentValueId = Number(navPath[navPath.length - 1].valueId);
    return values.filter(v => Number(v.layer_id) === Number(layer.id) && Number(v.parent_value_id) === parentValueId);
}

function getParentLayerName() {
    if (navPath.length === 0) return null;
    const parentLayerId = navPath[navPath.length - 1].layerId;
    const parentLayer   = layers.find(l => l.id == parentLayerId);
    return parentLayer ? parentLayer.name : null;
}

function getSiblingValues() {
    if (navPath.length === 0) return [];

    if (navPath.length === 1) {
        const rootLayer = layers.find(l => l.depth === 0 && !l.is_global);
        if (!rootLayer) return [];
        return values.filter(v => v.layer_id == rootLayer.id && v.parent_value_id == null).map(v => v.value);
    }

    const grandParentValueId = navPath[navPath.length - 2].valueId;
    const grandParentLayerId = navPath[navPath.length - 2].layerId;
    return values.filter(v => v.layer_id == grandParentLayerId && v.parent_value_id == grandParentValueId).map(v => v.value);
}

function layerAlreadyExistsHere() {
    const depth = navPath.length;

    if (depth === 0) {
        return layers.some(l => Number(l.depth) === 0 && !l.is_global);
    }

    const parentValueId = Number(navPath[depth - 1].valueId);

    const hasSpecific = layers.some(
        l => !l.is_global && Number(l.depth) === depth && Number(l.parent_value_id) === parentValueId
    );
    if (hasSpecific) return true;

    return layers.some(l => l.is_global && Number(l.depth) === depth);
}

function navigateTo(index) {
    navPath = navPath.slice(0, index);
    cancelForms();
}

function drillInto(valueId, valueName, layerId) {
    navPath.push({ valueId, valueName, layerId });
    cancelForms();
}

function handleMainAdd() {
    const layer = getCurrentLayer();
    pendingTags = [];

    if (!layer) {
        addingLayer = true;
        addingValue = false;
    } else {
        addingValue = true;
        addingLayer = false;
    }

    editingLayer   = false;
    editingValueId = null;
    render();
    setTimeout(() => {
        const el = document.getElementById('layerNameInp') || document.getElementById('tagRealInp');
        if (el) el.focus();
    }, 80);
}

/* ──────────────────────────────────────────────
   EDIT LAYER (rename + toggle global)
────────────────────────────────────────────── */
function handleEditLayer() {
    editingLayer   = true;
    addingLayer    = false;
    addingValue    = false;
    editingValueId = null;
    pendingTags    = [];
    render();
    setTimeout(() => document.getElementById('editLayerNameInp')?.focus(), 80);
}

async function saveEditLayer() {
    const newName = (document.getElementById('editLayerNameInp')?.value || '').trim();
    const chk     = document.getElementById('editGlobalChk');
    const newGlobal = chk ? chk.checked : false;

    if (!newName) { alert('Please enter a layer name!'); return; }

    const layer = getCurrentLayer();
    if (!layer) return;

    const btn = document.getElementById('saveEditLayerBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Saving...'; }

    const parentValueId = (!newGlobal && navPath.length > 0)
        ? navPath[navPath.length - 1].valueId
        : null;

    const res = await api('/assign-location/edit-layer', {
        layer_id        : layer.id,
        name            : newName,
        is_global       : newGlobal,
        parent_value_id : parentValueId,
    });

    if (!res.success) { alert(res.message || 'Failed to save.'); if (btn) { btn.disabled = false; btn.textContent = 'Save Changes'; } return; }

    editingLayer = false;
    await loadData();
}

/* ──────────────────────────────────────────────
   EDIT VALUE (inline rename)
────────────────────────────────────────────── */
function startEditValue(valueId) {
    editingValueId = valueId;
    addingLayer    = false;
    addingValue    = false;
    editingLayer   = false;
    pendingTags    = [];
    renderCenter();
    setTimeout(() => document.getElementById(`editInp_${valueId}`)?.focus(), 60);
}

function cancelEditValue() {
    editingValueId = null;
    renderCenter();
}

async function saveEditValue(valueId) {
    const inp = document.getElementById(`editInp_${valueId}`);
    const newVal = (inp?.value || '').trim();
    if (!newVal) { alert('Name cannot be empty!'); return; }

    const saveBtn = document.getElementById(`saveEditBtn_${valueId}`);
    if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = '...'; }

    const res = await api('/assign-location/edit-value', {
        value_id : valueId,
        value    : newVal,
    });

    if (!res.success) { alert(res.message || 'Failed to save.'); if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = '✓'; } return; }

    editingValueId = null;
    await loadData();
}

function toggleEditGlobalHint(chk) {
    const box = document.getElementById('editGlobalHintBox');
    if (box) box.style.display = chk.checked ? 'block' : 'none';
}

/* ──────────────────────────────────────────────
   ORIGINAL METHODS (unchanged logic)
────────────────────────────────────────────── */
function toggleGlobalHint(chk) {
    const box = document.getElementById('globalHintBox');
    if (box) box.style.display = chk.checked ? 'block' : 'none';
}

async function saveLayer() {
    const val      = (document.getElementById('layerNameInp')?.value || '').trim();
    const chk      = document.getElementById('globalChk');
    const isGlobal = chk ? chk.checked : false;

    if (!val) { alert('Please enter a layer name!'); return; }

    if (!isGlobal && layerAlreadyExistsHere()) {
        alert('A layer already exists under this item. Delete it first before creating a new one.');
        return;
    }

    const parentValueId = (!isGlobal && navPath.length > 0)
        ? navPath[navPath.length - 1].valueId
        : null;

    const res = await api('/assign-location/add-layer', {
        name            : val,
        is_global       : isGlobal,
        depth           : currentDepth(),
        parent_value_id : parentValueId,
    });

    if (!res.success) { alert(res.message || 'Failed to save.'); return; }
    addingLayer = false;
    await loadData();
}

function onTagKeydown(e) {
    const inp = e.target;
    const raw = inp.value;
    if (e.key === 'Enter' || e.key === ' ' || e.key === ',') {
        e.preventDefault();
        const v = raw.replace(/,/g, '').trim();
        if (v) addPendingTag(v);
        inp.value = '';
        return;
    }
    if (e.key === 'Backspace' && raw === '' && pendingTags.length > 0) {
        pendingTags.pop();
        renderCenter();
        setTimeout(() => document.getElementById('tagRealInp')?.focus(), 30);
    }
}

function onTagInput(e) {
    const inp = e.target;
    if (inp.value.includes(',')) {
        inp.value.split(',').map(s => s.trim()).filter(Boolean).forEach(addPendingTag);
        inp.value = '';
    }
}

function addPendingTag(name) {
    const clean = name.trim();
    if (!clean) return;
    const saved = getCurrentValues().map(v => v.value.toLowerCase());
    const pend  = pendingTags.map(t => t.toLowerCase());
    if (saved.includes(clean.toLowerCase()) || pend.includes(clean.toLowerCase())) return;
    pendingTags.push(clean);
    renderCenter();
    setTimeout(() => document.getElementById('tagRealInp')?.focus(), 30);
}

function removePendingTag(idx) {
    pendingTags.splice(idx, 1);
    renderCenter();
    setTimeout(() => document.getElementById('tagRealInp')?.focus(), 30);
}

async function saveAllTags() {
    const inp = document.getElementById('tagRealInp');
    if (inp && inp.value.trim()) { addPendingTag(inp.value.trim()); inp.value = ''; }
    if (pendingTags.length === 0) { alert('Please type at least one name!'); return; }

    const layer = getCurrentLayer();
    if (!layer) return;

    const btn = document.getElementById('saveTagsBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Saving...'; }

    const parentValueId = navPath.length > 0 ? navPath[navPath.length - 1].valueId : null;

    for (const name of pendingTags) {
        const res = await api('/assign-location/add-value', {
            layer_id        : layer.id,
            parent_value_id : parentValueId,
            value           : name,
        });
        if (!res.success) { alert(res.message || `Failed to save "${name}"`); break; }
    }

    pendingTags = [];
    addingValue = false;
    await loadData();
}

async function deleteValue(valueId) {
    if (!confirm('Delete this item and all sub-items?')) return;
    navPath = navPath.filter(p => p.valueId != valueId);
    await api('/assign-location/delete-value', { value_id: valueId });
    await loadData();
}

async function handleDeleteLayer() {
    const layer = getCurrentLayer();
    if (!layer) return;

    const parentValueId = navPath.length > 0 ? navPath[navPath.length - 1].valueId : null;

    let msg;
    if (layer.is_global && parentValueId) {
        const itemName = navPath[navPath.length - 1].valueName;
        msg = `Delete "${cap(layer.name)}" values for "${itemName}" only?\n\nAll values under ${itemName} will be deleted.`;
    } else if (layer.is_global) {
        msg = `Delete the GLOBAL "${cap(layer.name)}" layer?\n\nThis removes it for ALL items!`;
    } else {
        msg = `Delete the "${cap(layer.name)}" layer?\n\nAll values inside will be permanently deleted.`;
    }

    if (!confirm(msg)) return;

    const payload = { layer_id: layer.id };
    if (layer.is_global && parentValueId) payload.parent_value_id = parentValueId;

    const res = await api('/assign-location/delete-layer', payload);
    if (!res.success) { alert('Failed to delete layer.'); return; }
    cancelForms();
    await loadData();
}

function cancelForms() {
    addingLayer    = false;
    addingValue    = false;
    editingLayer   = false;
    editingValueId = null;
    pendingTags    = [];
    render();
}

/* ══ RENDER ══ */
function render() {
    renderBreadcrumb();
    renderTopButtons();
    renderCenter();
}

function renderBreadcrumb() {
    const bc = document.getElementById('breadcrumb');
    let html = `<span class="bc-item ${navPath.length === 0 ? 'active' : ''}" onclick="navigateTo(0)">Assign Location</span>`;
    navPath.forEach((step, i) => {
        const isLast = i === navPath.length - 1;
        html += `<span class="bc-sep">›</span>
                 <span class="bc-item ${isLast ? 'active' : ''}" onclick="navigateTo(${i + 1})">${esc(step.valueName)}</span>`;
    });
    bc.innerHTML = html;
}

function renderTopButtons() {
    const layer      = getCurrentLayer();
    const alreadyHas = layerAlreadyExistsHere();
    const addBtn     = document.getElementById('mainAddBtn');

    if (layer) {
        addBtn.textContent = `+ Add ${cap(layer.name)}`;
        addBtn.disabled    = false;
    } else if (alreadyHas) {
        addBtn.textContent = '+ Add Layer';
        addBtn.disabled    = true;
    } else {
        addBtn.textContent = '+ Add Layer';
        addBtn.disabled    = false;
    }

    const rightBar = document.getElementById('topBarRight');

    // Remove old dynamic buttons
    const oldDel  = document.getElementById('deleteLayerBtn');
    const oldEdit = document.getElementById('editLayerBtn');
    if (oldDel)  oldDel.remove();
    if (oldEdit) oldEdit.remove();

    if (layer) {
        // Edit layer button
        const editBtn       = document.createElement('button');
        editBtn.id          = 'editLayerBtn';
        editBtn.className   = 'btn btn-warning btn-sm';
        editBtn.onclick     = handleEditLayer;
        editBtn.textContent = `✏️ Edit "${cap(layer.name)}" Layer`;
        rightBar.appendChild(editBtn);

        // Delete layer button
        const delBtn       = document.createElement('button');
        delBtn.id          = 'deleteLayerBtn';
        delBtn.className   = 'btn btn-danger btn-sm';
        delBtn.onclick     = handleDeleteLayer;
        delBtn.textContent = `🗑 Delete "${cap(layer.name)}" Layer`;
        rightBar.appendChild(delBtn);
    }
}

function renderCenter() {
    const ca      = document.getElementById('centerArea');
    const layer   = getCurrentLayer();
    const curVals = getCurrentValues();

    let title, hint;
    if (navPath.length === 0) {
        title = layer ? cap(layer.name) : 'Assign Location';
        hint  = layer
            ? `Click any ${layer.name} to go deeper`
            : 'Start by adding a layer (e.g. Country)';
    } else {
        title = navPath[navPath.length - 1].valueName;
        hint  = layer
            ? (layer.is_global
                ? `🌐 This ${layer.name} layer applies to all — click to drill deeper`
                : `Select a ${layer.name} to drill deeper`)
            : 'Add a sub-layer to organise further';
    }

    /* ── chips ── */
    let chipsHtml = '';
    if (layer) {
        chipsHtml = curVals.map(item => {
            const vname = item.value.replace(/'/g, "\\'");

            // Inline editing mode for this chip
            if (editingValueId == item.id) {
                return `<div class="chip-edit-inline">
                    <input type="text" id="editInp_${item.id}" class="chip-edit-inp"
                           value="${esc(item.value)}"
                           onkeydown="if(event.key==='Enter') saveEditValue(${item.id}); if(event.key==='Escape') cancelEditValue();" />
                    <button class="chip-save-btn" id="saveEditBtn_${item.id}" onclick="saveEditValue(${item.id})">✓</button>
                    <button class="chip-cancel-btn" onclick="cancelEditValue()">✕</button>
                </div>`;
            }

            return `<div class="item-chip">
                        <div class="chip-main" onclick="drillInto(${item.id},'${vname}',${item.layer_id})">
                            <span>${esc(item.value)}</span>
                            <span class="chip-arrow">›</span>
                        </div>
                        <div class="chip-actions">
                            <button class="chip-edit" title="Edit" onclick="event.stopPropagation();startEditValue(${item.id})">✏</button>
                            <button class="chip-del"  title="Delete" onclick="event.stopPropagation();deleteValue(${item.id})">✕</button>
                        </div>
                    </div>`;
        }).join('');
    }

    /* ── Edit Layer form ── */
    let editLayerHtml = '';
    if (editingLayer && layer) {
        const showGlobal      = navPath.length > 0;
        const parentLayerName = getParentLayerName();
        const siblings        = getSiblingValues();
        const siblingLabel    = siblings.length > 0
            ? siblings.slice(0, 3).join(', ') + (siblings.length > 3 ? '...' : '')
            : (parentLayerName ? `all ${parentLayerName}s` : '');

        // Pre-check if layer is currently global
        const isCurrentlyGlobal = layer.is_global;
        const hintDisplay = isCurrentlyGlobal ? 'block' : 'none';

        editLayerHtml = `
        <div class="edit-layer-banner">
            <div class="edit-layer-title">✏️ Edit Layer</div>
            <div class="edit-layer-row">
                <input type="text" id="editLayerNameInp" class="simple-inp"
                       value="${esc(layer.name)}" placeholder="Layer name..." style="width:200px;" />
                ${showGlobal ? `
                <label class="global-check-wrap" for="editGlobalChk" style="width:auto; padding: 8px 14px;">
                    <input type="checkbox" id="editGlobalChk"
                           ${isCurrentlyGlobal ? 'checked' : ''}
                           onchange="toggleEditGlobalHint(this)">
                    <span class="global-check-label">
                        Apply to all <span>${esc(cap(parentLayerName || 'items'))}</span>
                    </span>
                </label>` : ''}
                <button id="saveEditLayerBtn" class="btn btn-success btn-sm" onclick="saveEditLayer()">Save Changes</button>
                <button class="btn btn-outline btn-sm" onclick="cancelForms()">Cancel</button>
            </div>
            ${showGlobal ? `
            <div class="global-hint-box" id="editGlobalHintBox" style="display:${hintDisplay}; max-width:100%;">
                ✅ This layer will be shared across: <strong>${esc(siblingLabel)}</strong>
            </div>` : ''}
        </div>`;
    }

    /* ── Add Layer form ── */
    let formHtml = '';
    if (addingLayer) {
        const parentLayerName = getParentLayerName();
        const siblings        = getSiblingValues();
        const showGlobal      = navPath.length > 0;
        const siblingLabel    = siblings.length > 0
            ? siblings.slice(0, 3).join(', ') + (siblings.length > 3 ? '...' : '')
            : (parentLayerName ? `all ${parentLayerName}s` : '');

        formHtml = `
        <div class="center-form">
            <div class="center-form-title">What do you want to call this layer?</div>
            <div class="center-form-hint">e.g. Country, State, District, Zone, Area...</div>
            <input type="text" id="layerNameInp" class="simple-inp" placeholder="Layer name..." />
            ${showGlobal ? `
            <label class="global-check-wrap" for="globalChk">
                <input type="checkbox" id="globalChk" onchange="toggleGlobalHint(this)">
                <span class="global-check-label">
                    Apply to all <span>${esc(cap(parentLayerName || 'items'))}</span>
                </span>
            </label>
            <div class="global-hint-box" id="globalHintBox">
                ✅ This layer will be shared across: <strong>${esc(siblingLabel)}</strong>
            </div>` : ''}
            <div class="form-actions">
                <button class="btn btn-success btn-sm" onclick="saveLayer()">Save Layer</button>
                <button class="btn btn-outline btn-sm" onclick="cancelForms()">Cancel</button>
            </div>
        </div>`;
    }

    else if (addingValue && layer) {
        const pendHtml = pendingTags.map((t, i) =>
            `<span class="ptag">${esc(t)}<span class="ptag-del" onclick="removePendingTag(${i})">✕</span></span>`
        ).join('');

        formHtml = `
        <div class="center-form">
            <div class="center-form-title">Add ${cap(layer.name)} names</div>
            <div class="center-form-hint">
                Type a name and press <strong>Space</strong>, <strong>Comma</strong>, or <strong>Enter</strong> to add.
            </div>
            <div class="tag-box" onclick="document.getElementById('tagRealInp').focus()">
                ${pendHtml}
                <input type="text" id="tagRealInp" class="tag-real-inp"
                       placeholder="${pendingTags.length === 0 ? 'Type ' + layer.name + ' name...' : 'Add more...'}"
                       onkeydown="onTagKeydown(event)" oninput="onTagInput(event)" />
            </div>
            <div class="form-actions">
                <button id="saveTagsBtn" class="btn btn-success btn-sm" onclick="saveAllTags()"
                        ${pendingTags.length === 0 ? 'disabled' : ''}>
                    Save ${pendingTags.length > 0 ? '(' + pendingTags.length + ')' : 'All'}
                </button>
                <button class="btn btn-outline btn-sm" onclick="cancelForms()">Cancel</button>
            </div>
        </div>`;
    }

    let emptyHtml = '';
    if (!layer && !addingLayer) {
        emptyHtml = `<div class="empty-state">
            <div class="icon">🌍</div>
            <p>No layer defined here yet.<br>Click <strong>"+ Add Layer"</strong> to get started.</p>
        </div>`;
    } else if (layer && curVals.length === 0 && !addingValue) {
        emptyHtml = `<div class="empty-state">
            <div class="icon">📍</div>
            <p>No ${esc(layer.name)}s added yet.<br>
            Click <strong>"+ Add ${esc(cap(layer.name))}"</strong> to add one.</p>
        </div>`;
    }

    ca.innerHTML = `
        <div class="level-title">${esc(title)}</div>
        <div class="level-hint">${hint}</div>
        ${editLayerHtml}
        <div class="items-grid">${chipsHtml}</div>
        ${formHtml}
        ${emptyHtml}
    `;

    if (addingLayer)  setTimeout(() => document.getElementById('layerNameInp')?.focus(), 60);
    if (addingValue)  setTimeout(() => document.getElementById('tagRealInp')?.focus(), 60);
    if (editingLayer) setTimeout(() => document.getElementById('editLayerNameInp')?.focus(), 60);
}

function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function cap(s) { return String(s).charAt(0).toUpperCase() + String(s).slice(1); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cancelForms();
    if (e.key === 'Enter' && addingLayer && document.activeElement?.id === 'layerNameInp') saveLayer();
    if (e.key === 'Enter' && editingLayer && document.activeElement?.id === 'editLayerNameInp') saveEditLayer();
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/assign_location/locationcreate.blade.php ENDPATH**/ ?>