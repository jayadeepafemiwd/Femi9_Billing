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
        .btn-primary:hover { background: #4f46e5; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-outline { background: #fff; color: #6366f1; border: 1.5px solid #6366f1; }
        .btn-outline:hover { background: #eef2ff; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .center-area { display: flex; flex-direction: column; align-items: center; padding: 16px 0 24px; }
        .level-title { font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 6px; text-align: center; text-transform: capitalize; }
        .level-hint  { font-size: 13px; color: #94a3b8; margin-bottom: 24px; text-align: center; }
        .items-grid { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; max-width: 700px; width: 100%; margin-bottom: 24px; }
        .item-chip { display: inline-flex; align-items: center; gap: 7px; background: #fff; border: 1.5px solid #e2e8f0; padding: 9px 16px; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #1e293b; transition: all 0.18s; }
        .item-chip:hover { border-color: #6366f1; background: #eef2ff; color: #6366f1; }
        .chip-arrow { color: #6366f1; font-size: 14px; pointer-events: none; }
        .chip-del { color: #ef4444; font-size: 11px; cursor: pointer; opacity: 0.45; margin-left: 1px; }
        .chip-del:hover { opacity: 1; }
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
    const depth = currentDepth();

    if (navPath.length === 0) {
        return layers.find(l => l.depth === 0 && !l.is_global) || null;
    }

    const parentValueId = navPath[navPath.length - 1].valueId;

    // 1. Specific layer for this exact parent
    const specificLayer = layers.find(
        l => !l.is_global && l.depth === depth && l.parent_value_id == parentValueId
    );
    if (specificLayer) return specificLayer;

    // 2. Global layer at this depth
    const globalLayer = layers.find(l => l.is_global && l.depth === depth);
    if (globalLayer) return globalLayer;

    return null;
}


function getCurrentValues() {
    const layer = getCurrentLayer();
    if (!layer) return [];

    if (navPath.length === 0) {
        return values.filter(v => v.layer_id == layer.id && v.parent_value_id == null);
    }

    const parentValueId = navPath[navPath.length - 1].valueId;
    return values.filter(v => v.layer_id == layer.id && v.parent_value_id == parentValueId);
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
    if (!layer) { addingLayer = true; addingValue = false; }
    else         { addingValue = true; addingLayer = false; }
    render();
    setTimeout(() => {
        const el = document.getElementById('layerNameInp') || document.getElementById('tagRealInp');
        if (el) el.focus();
    }, 80);
}

function toggleGlobalHint(chk) {
    const box = document.getElementById('globalHintBox');
    if (box) box.style.display = chk.checked ? 'block' : 'none';
}

async function saveLayer() {
    const val      = (document.getElementById('layerNameInp')?.value || '').trim();
    const chk      = document.getElementById('globalChk');
    const isGlobal = chk ? chk.checked : false;
    if (!val) { alert('Please enter a layer name!'); return; }

    // Specific layer — current parent value id pass பண்ணணும்
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
    addingLayer = false;
    addingValue = false;
    pendingTags = [];
    render();
}

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
    const layer  = getCurrentLayer();
    const addBtn = document.getElementById('mainAddBtn');
    addBtn.textContent = layer ? `+ Add ${cap(layer.name)}` : '+ Add Layer';

    const rightBar = document.getElementById('topBarRight');
    const old = document.getElementById('deleteLayerBtn');
    if (old) old.remove();

    if (layer) {
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
        hint  = layer ? `Click any ${layer.name} to go deeper` : 'Start by adding a layer (e.g. Country)';
    } else {
        title = navPath[navPath.length - 1].valueName;
        hint  = layer
            ? (layer.is_global
                ? `🌐 This ${layer.name} layer applies to all — click to drill deeper`
                : `Select a ${layer.name} to drill deeper`)
            : 'Add a sub-layer to organise further';
    }

    let chipsHtml = '';
    if (layer) {
        chipsHtml = curVals.map(item => {
            const vname = item.value.replace(/'/g, "\\'");
            return `<div class="item-chip" onclick="drillInto(${item.id},'${vname}',${item.layer_id})">
                        <span>${esc(item.value)}</span>
                        <span class="chip-arrow">›</span>
                        <span class="chip-del" onclick="event.stopPropagation();deleteValue(${item.id})">✕</span>
                    </div>`;
        }).join('');
    }

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
                <span class="global-check-label">Apply to all <span>${esc(cap(parentLayerName || 'items'))}</span></span>
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
        emptyHtml = `<div class="empty-state"><div class="icon">🌍</div>
            <p>No layer defined here yet.<br>Click <strong>"+ Add Layer"</strong> to get started.</p></div>`;
    } else if (layer && curVals.length === 0 && !addingValue) {
        emptyHtml = `<div class="empty-state"><div class="icon">📍</div>
            <p>No ${esc(layer.name)}s added yet.<br>
            Click <strong>"+ Add ${esc(cap(layer.name))}"</strong> to add one.</p></div>`;
    }

    ca.innerHTML = `
        <div class="level-title">${esc(title)}</div>
        <div class="level-hint">${hint}</div>
        <div class="items-grid">${chipsHtml}</div>
        ${formHtml}
        ${emptyHtml}
    `;

    if (addingLayer) setTimeout(() => document.getElementById('layerNameInp')?.focus(), 60);
    if (addingValue) setTimeout(() => document.getElementById('tagRealInp')?.focus(), 60);
}

function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function cap(s) { return String(s).charAt(0).toUpperCase() + String(s).slice(1); }

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cancelForms();
    if (e.key === 'Enter' && addingLayer && document.activeElement?.id === 'layerNameInp') saveLayer();
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/assign_location/locationcreate.blade.php ENDPATH**/ ?>