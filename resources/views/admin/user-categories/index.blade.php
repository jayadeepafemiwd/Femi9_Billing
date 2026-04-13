{{-- resources/views/admin/user-categories/index.blade.php --}}

@extends('layouts.app')

@section('title', 'User Categories')

@section('content')
<div class="uc-page">

    <div class="uc-header">
        <div>
            <h1 class="uc-title">User categories</h1>
            <p class="uc-sub">Manage hierarchy levels for your distribution network</p>
        </div>
        <button class="uc-btn uc-btn--primary" onclick="openModal()">+ Add category</button>
    </div>

    <div class="uc-card">
        <div class="uc-card-label">Hierarchy flow</div>
        <div class="uc-flow" id="flow-container">
            <span class="uc-loading">Loading...</span>
        </div>
    </div>

    <div class="uc-card">
        <div class="uc-card-label">All categories</div>
        <table class="uc-table">
            <thead>
                <tr>
                    <th>Name</th><th>Code</th><th>Level</th>
                    <th>Parent</th><th>Portal Access</th><th></th>
                </tr>
            </thead>
            <tbody id="category-table-body">
                <tr><td colspan="6" style="text-align:center;color:var(--muted)">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal --}}
<div class="uc-overlay" id="modal-overlay" style="display:none">
    <div class="uc-modal">
        <div class="uc-modal-header">
            <span id="modal-title">Add category</span>
            <button class="uc-close" onclick="closeModal()">✕</button>
        </div>

        <form id="category-form" onsubmit="submitForm(event)">
            @csrf
            <input type="hidden" id="edit-id"/>
            <input type="hidden" id="f-parent"/>

            <div class="uc-form-grid">
                <div class="uc-field">
                    <label>Category name</label>
                    <div class="uc-input-wrap">
                        <input type="text" id="f-name" placeholder="e.g. Super Stockist" required oninput="updateLivePreview()"/>
                        <button type="button" class="uc-eye" onclick="togglePreview()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <div class="uc-field">
                    <label>Category code</label>
                    <div class="uc-input-wrap">
                        <input type="text" id="f-code" placeholder="e.g. SS01" required/>
                    </div>
                </div>
                <div class="uc-field">
                    <label>Level (auto)</label>
                    <div class="uc-input-wrap">
                        <input type="text" id="f-level" readonly style="background:var(--bg-secondary);cursor:default"/>
                    </div>
                </div>
                <div class="uc-field uc-field--full">
                    <label>Description</label>
                    <div class="uc-input-wrap">
                        <textarea id="f-desc" rows="3" placeholder="Brief role description..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Child Category Toggle --}}
            <div class="uc-child-loc-wrap">
                <label class="uc-child-loc-label" for="f-is-child-category">
                    <div class="uc-child-loc-left">
                        <div class="uc-child-loc-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div>
                            <div class="uc-child-loc-title">This is a Child Category</div>
                            <div class="uc-child-loc-sub">Link this category under a parent category</div>
                        </div>
                    </div>
                    <label class="uc-switch">
                        <input type="checkbox" id="f-is-child-category" onchange="onChildCategoryToggle()"/>
                        <span></span>
                    </label>
                </label>
                <div id="child-category-panel" style="display:none">
                    <div class="uc-loc-row">
                        <div class="uc-loc-field">
                            <label class="uc-loc-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                Parent Category
                            </label>
                            <div class="uc-input-wrap">
                                <select id="f-child-parent" onchange="onChildParentChange()">
                                    <option value="">— Select parent category —</option>
                                </select>
                                <svg class="uc-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>
                    </div>
                    <div id="child-category-level-info" class="uc-loc-breadcrumb" style="display:none">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4"/><polyline points="4 20 4 17 20 17"/><line x1="4" y1="10" x2="20" y2="10"/><line x1="4" y1="13" x2="20" y2="13"/></svg>
                        <span id="child-category-level-text"></span>
                    </div>
                </div>
            </div>

            {{-- Location Toggle --}}
            <div class="uc-child-loc-wrap" style="margin-top:10px">
                <label class="uc-child-loc-label" for="f-is-location-linked">
                    <div class="uc-child-loc-left">
                        <div class="uc-child-loc-icon" style="background:#FFF3E0;color:#E65100">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="uc-child-loc-title">Link to Location</div>
                            <div class="uc-child-loc-sub">Associate this category with a country &amp; its layers</div>
                        </div>
                    </div>
                    <label class="uc-switch">
                        <input type="checkbox" id="f-is-location-linked" onchange="onLocationToggle()"/>
                        <span></span>
                    </label>
                </label>

                <div id="location-panel" style="display:none">
                    <div class="uc-loc-row">
                        <div class="uc-loc-field">
                            <label class="uc-loc-label">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                Country
                            </label>
                            <div class="uc-input-wrap">
                                <select id="f-country" onchange="onCountryChange()">
                                    <option value="">— Select country —</option>
                                </select>
                                <svg class="uc-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                        </div>

                        <div id="country-layers-container" style="display:none;margin-top:12px">
                            <div class="uc-loc-field">
                                <label class="uc-loc-label">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4"/><polyline points="4 20 4 17 20 17"/><line x1="4" y1="10" x2="20" y2="10"/><line x1="4" y1="13" x2="20" y2="13"/></svg>
                                    Layer
                                </label>
                                <div class="uc-input-wrap">
                                    <select id="f-layer">
                                        <option value="">— Select layer —</option>
                                    </select>
                                    <svg class="uc-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                                <div id="layer-used-note" style="display:none;margin-top:6px;font-size:11px;color:#E65100">
                                    ⚠ Some layers are already assigned to other categories.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="location-breadcrumb" class="uc-loc-breadcrumb" style="display:none;margin-top:10px">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span id="location-breadcrumb-text"></span>
                    </div>
                </div>
            </div>

            {{-- Toggles --}}
            <div class="uc-toggles">
                <div class="uc-toggle-row">
                    <div>
                        <div class="uc-toggle-label">Portal access</div>
                        <div class="uc-toggle-sub">Allow users in this category to log in</div>
                    </div>
                    <label class="uc-switch"><input type="checkbox" id="f-portal-access" checked/><span></span></label>
                </div>
                <div class="uc-toggle-row">
                    <div>
                        <div class="uc-toggle-label">Visible in hierarchy map</div>
                        <div class="uc-toggle-sub">Show in flow diagram</div>
                    </div>
                    <label class="uc-switch"><input type="checkbox" id="f-visible" checked/><span></span></label>
                </div>
            </div>

            <div class="uc-preview" id="live-preview" style="display:none">
                <div class="uc-preview-label">Hierarchy preview</div>
                <div class="uc-chain" id="preview-chain"></div>
            </div>

            <div class="uc-modal-footer">
                <button type="button" class="uc-btn" onclick="closeModal()">Cancel</button>
                <button type="button" class="uc-btn" onclick="togglePreview()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview
                </button>
                <button type="submit" class="uc-btn uc-btn--primary">Save category</button>
            </div>
        </form>
    </div>
</div>

<style>
:root{--bg:#fff;--bg-secondary:#f5f5f3;--border:rgba(0,0,0,0.1);--text:#1a1a1a;--muted:#888;--blue:#185FA5;--blue-light:#E6F1FB;--blue-dark:#0C447C;--radius:8px;--radius-lg:12px}
.uc-page{padding:24px;max-width:960px;margin:0 auto}
.uc-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px}
.uc-title{font-size:20px;font-weight:500;color:var(--text);margin:0}
.uc-sub{font-size:13px;color:var(--muted);margin:4px 0 0}
.uc-card{background:var(--bg);border:0.5px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:16px}
.uc-card-label{font-size:11px;font-weight:500;color:var(--muted);letter-spacing:0.4px;text-transform:uppercase;margin-bottom:12px}
.uc-flow{display:flex;align-items:center;flex-wrap:wrap;gap:8px}
.uc-flow-pill{font-size:12px;padding:5px 14px;border-radius:20px;background:var(--blue-light);color:var(--blue);font-weight:500;cursor:pointer;transition:background .15s}
.uc-flow-pill:hover{background:#B5D4F4}
.uc-flow-arrow{color:var(--muted);font-size:14px}
.uc-table{width:100%;border-collapse:collapse;font-size:13px}
.uc-table th{text-align:left;font-size:11px;font-weight:500;color:var(--muted);padding:0 12px 10px;letter-spacing:0.3px;text-transform:uppercase;border-bottom:0.5px solid var(--border)}
.uc-table td{padding:12px;border-bottom:0.5px solid var(--border);color:var(--text);vertical-align:middle}
.uc-table tr:last-child td{border-bottom:none}
.uc-level-badge{display:inline-block;font-size:11px;padding:2px 8px;border-radius:20px;background:var(--blue-light);color:var(--blue);font-weight:500}
.uc-status{display:inline-flex;align-items:center;gap:4px;font-size:12px}
.uc-status.on{color:#0F6E56}.uc-status.off{color:#993C1D}
.uc-dot{width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block}
.uc-action-btn{background:none;border:0.5px solid var(--border);border-radius:var(--radius);padding:4px 10px;font-size:12px;cursor:pointer;color:var(--muted)}
.uc-action-btn:hover{background:var(--bg-secondary)}
.uc-btn{height:36px;padding:0 16px;border-radius:var(--radius);font-size:13px;font-weight:500;cursor:pointer;border:0.5px solid var(--border);background:transparent;color:var(--muted);display:inline-flex;align-items:center;gap:6px}
.uc-btn:hover{background:var(--bg-secondary)}
.uc-btn--primary{background:var(--blue);color:#fff;border-color:var(--blue)}
.uc-btn--primary:hover{background:var(--blue-dark)}
.uc-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;z-index:1000}
.uc-modal{background:var(--bg);border-radius:var(--radius-lg);width:540px;max-width:95vw;max-height:90vh;overflow-y:auto}
.uc-modal-header{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:0.5px solid var(--border);font-size:15px;font-weight:500}
.uc-close{background:none;border:none;cursor:pointer;font-size:16px;color:var(--muted);line-height:1}
.uc-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;padding:20px 20px 0}
.uc-field{display:flex;flex-direction:column;gap:5px}
.uc-field--full{grid-column:1/-1}
.uc-field label{font-size:11px;font-weight:500;color:var(--muted);letter-spacing:0.3px}
.uc-input-wrap{position:relative;display:flex;align-items:center}
.uc-input-wrap input,.uc-input-wrap select,.uc-input-wrap textarea{width:100%;border:0.5px solid var(--border);border-radius:var(--radius);padding:0 34px 0 10px;font-size:13px;color:var(--text);background:var(--bg);outline:none;appearance:none}
.uc-input-wrap input,.uc-input-wrap select{height:36px}
.uc-input-wrap textarea{height:auto;padding:8px 10px;resize:none;line-height:1.5}
.uc-input-wrap input:focus,.uc-input-wrap select:focus,.uc-input-wrap textarea:focus{border-color:var(--blue);box-shadow:0 0 0 2px rgba(24,95,165,0.12)}
.uc-eye{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);display:flex;align-items:center;padding:2px}
.uc-eye:hover{color:var(--text)}
.uc-chevron{position:absolute;right:8px;pointer-events:none;color:var(--muted)}
.uc-child-loc-wrap{margin:16px 20px 0;border:0.5px solid var(--border);border-radius:var(--radius);overflow:hidden}
.uc-child-loc-label{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;cursor:pointer;background:var(--bg);gap:12px}
.uc-child-loc-label:hover{background:var(--bg-secondary)}
.uc-child-loc-left{display:flex;align-items:center;gap:10px}
.uc-child-loc-icon{width:32px;height:32px;border-radius:8px;background:var(--blue-light);color:var(--blue);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.uc-child-loc-title{font-size:13px;font-weight:500;color:var(--text)}
.uc-child-loc-sub{font-size:11px;color:var(--muted);margin-top:1px}
#child-category-panel{padding:0 14px 14px;border-top:0.5px solid var(--border);background:#FAFAF9}
#location-panel{padding:0 14px 14px;border-top:0.5px solid var(--border);background:#FAFAF9}
.uc-loc-row{margin-top:12px}
.uc-loc-label{font-size:11px;font-weight:500;color:var(--muted);letter-spacing:0.3px;margin-bottom:5px;display:flex;align-items:center;gap:5px}
.uc-loc-field{display:flex;flex-direction:column}
.uc-loc-breadcrumb{display:flex;align-items:center;gap:6px;margin-top:10px;padding:8px 10px;background:var(--blue-light);border-radius:6px;font-size:12px;color:var(--blue);font-weight:500}
.uc-loc-breadcrumb svg{flex-shrink:0;color:var(--blue)}
.uc-toggles{padding:16px 20px;display:flex;flex-direction:column;border-top:0.5px solid var(--border);margin-top:16px}
.uc-toggle-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:0.5px solid var(--border)}
.uc-toggle-row:last-child{border-bottom:none}
.uc-toggle-label{font-size:13px;color:var(--text)}
.uc-toggle-sub{font-size:11px;color:var(--muted);margin-top:2px}
.uc-switch{position:relative;width:34px;height:20px;display:inline-block;flex-shrink:0}
.uc-switch input{opacity:0;width:0;height:0;position:absolute}
.uc-switch span{position:absolute;inset:0;background:var(--border);border-radius:10px;cursor:pointer;transition:background .2s}
.uc-switch span:before{content:'';position:absolute;width:14px;height:14px;background:#fff;border-radius:50%;top:3px;left:3px;transition:transform .2s}
.uc-switch input:checked + span{background:var(--blue)}
.uc-switch input:checked + span:before{transform:translateX(14px)}
.uc-preview{background:var(--bg-secondary);border-radius:var(--radius);padding:12px 14px;margin:0 20px 16px}
.uc-preview-label{font-size:11px;color:var(--muted);margin-bottom:8px;font-weight:500}
.uc-chain{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.uc-chain-pill{font-size:12px;padding:3px 10px;border-radius:20px;background:var(--blue-light);color:var(--blue);font-weight:500}
.uc-chain-pill.current{background:var(--blue);color:#fff}
.uc-chain-arrow{color:var(--muted);font-size:12px}
.uc-modal-footer{display:flex;gap:8px;justify-content:flex-end;padding:14px 20px;border-top:0.5px solid var(--border)}
.uc-loading{color:var(--muted);font-size:13px}
</style>

<script>const CSRF_TOKEN = '{{ csrf_token() }}';</script>
<script>
let allCategories = [];
let lcCountries   = [];
let lcLayers      = [];
let lcValues      = [];
let usedLayerIds  = new Set(); // DB-ல் already assigned layer ids

// ── URLs ────────────────────────────────────────────────────────
const URL_FLAT        = '/user-categories/flat';
const URL_USED_LAYERS = '/user-categories/used-layers';
const URL_STORE       = '/user-categories';
const URL_UPDATE      = (id) => `/user-categories/${id}`;
const URL_LOCATION    = '/assign-location/tree';

// ── Bootstrap ──────────────────────────────────────────────────

async function loadCategories() {
    const res  = await fetch(URL_FLAT, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
    });
    const json = await res.json();
    allCategories = json.data || [];
    renderFlow();
    renderTable();
}

async function loadLocationData() {
    try {
        const res  = await fetch(URL_LOCATION, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
        });
        const json = await res.json();
        lcLayers   = json.layers || [];
        lcValues   = json.values || [];
        const countryLayer = lcLayers.find(l => Number(l.depth) === 0);
        if (countryLayer) {
            lcCountries = lcValues.filter(v => v.layer_id === countryLayer.id);
        }
    } catch (e) {
        console.warn('Location data load failed', e);
    }
}

async function loadUsedLayers() {
    try {
        const res  = await fetch(URL_USED_LAYERS, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
        });
        const json = await res.json();
        usedLayerIds = new Set((json.used_layer_ids || []).map(String));
    } catch (e) {
        console.warn('Used layers load failed', e);
        usedLayerIds = new Set();
    }
}

// ── Render ─────────────────────────────────────────────────────

function renderFlow() {
    const container = document.getElementById('flow-container');
    const visible   = allCategories.filter(c => c.visible_in_hierarchy);
    if (!visible.length) {
        container.innerHTML = '<span class="uc-loading">No categories yet</span>';
        return;
    }
    container.innerHTML = visible.map((c, i) =>
        `<span class="uc-flow-pill" onclick="openEdit(${c.id})">${c.name}</span>` +
        (i < visible.length - 1 ? '<span class="uc-flow-arrow">→</span>' : '')
    ).join('');
}

function renderTable() {
    const tbody = document.getElementById('category-table-body');
    if (!allCategories.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted)">No categories found.</td></tr>';
        return;
    }
    tbody.innerHTML = allCategories.map(c => `
        <tr>
            <td style="font-weight:500">${c.name}</td>
            <td style="color:var(--muted)">${c.code}</td>
            <td><span class="uc-level-badge">Level ${c.level}</span></td>
            <td style="color:var(--muted)">${c.parent_id ? (allCategories.find(x => x.id === c.parent_id)?.name || '—') : '—'}</td>
            <td><span class="uc-status ${c.portal_access ? 'on' : 'off'}"><span class="uc-dot"></span>${c.portal_access ? 'Enabled' : 'Disabled'}</span></td>
            <td><button class="uc-action-btn" onclick="openEdit(${c.id})">Edit</button></td>
        </tr>
    `).join('');
}

// ── Live preview ────────────────────────────────────────────────

function updateLivePreview() {
    const name      = document.getElementById('f-name').value || 'New category';
    const parentId  = document.getElementById('f-parent').value;
    const parent    = allCategories.find(c => c.id == parentId);
    const ancestors = [];
    let cur = parent;
    while (cur) { ancestors.unshift(cur); cur = allCategories.find(c => c.id == cur.parent_id); }
    ancestors.push({ name, isCurrent: true });
    document.getElementById('preview-chain').innerHTML = ancestors.map((a, i) =>
        `<span class="uc-chain-pill ${a.isCurrent ? 'current' : ''}">${a.name}</span>` +
        (i < ancestors.length - 1 ? '<span class="uc-chain-arrow">→</span>' : '')
    ).join('');
}

function togglePreview() {
    updateLivePreview();
    const p = document.getElementById('live-preview');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}

// ── Child Category ──────────────────────────────────────────────

function onChildCategoryToggle() {
    const checked = document.getElementById('f-is-child-category').checked;
    document.getElementById('child-category-panel').style.display = checked ? 'block' : 'none';
    if (checked) {
        populateChildParentDropdown();
    } else {
        document.getElementById('f-level').value                           = 1;
        document.getElementById('f-parent').value                          = '';
        document.getElementById('child-category-level-info').style.display = 'none';
        // Parent deselect ஆனா → layer dropdown-ல் parent-ஓட layer restriction நீக்கணும்
        refreshLayerDropdown();
        updateLivePreview();
    }
}

function populateChildParentDropdown(selectedId = null) {
    const sel     = document.getElementById('f-child-parent');
    const editId  = document.getElementById('edit-id').value;
    const options = allCategories.filter(c => String(c.id) !== String(editId));
    sel.innerHTML = '<option value="">— Select parent category —</option>' +
        options.map(c =>
            `<option value="${c.id}" ${selectedId == c.id ? 'selected' : ''}>
                ${'&nbsp;'.repeat((c.level - 1) * 3)}${c.name}
            </option>`
        ).join('');
    if (selectedId) onChildParentChange();
}

/**
 * FIX: When a parent category is selected, collect ALL assign_fix_location values
 * used by the entire ancestor chain (parent + grandparent + ...) and add them
 * to a temporary "extra blocked" set so they won't appear selectable in the
 * layer dropdown for this new child category.
 *
 * This is purely a UI filter — usedLayerIds (from DB) is never mutated.
 * We rebuild effectiveUsedLayerIds = usedLayerIds + ancestorLayerIds each time.
 */
function onChildParentChange() {
    const parentId = document.getElementById('f-child-parent').value;
    const info     = document.getElementById('child-category-level-info');

    if (!parentId) {
        info.style.display                        = 'none';
        document.getElementById('f-level').value  = 1;
        document.getElementById('f-parent').value = '';
        refreshLayerDropdown();
        updateLivePreview();
        return;
    }

    const parent = allCategories.find(c => c.id == parentId);
    if (!parent) return;

    const newLevel = parent.level + 1;
    document.getElementById('f-parent').value = parentId;
    document.getElementById('f-level').value  = newLevel;
    document.getElementById('child-category-level-text').textContent =
        `Will be placed at Level ${newLevel} → under "${parent.name}"`;
    info.style.display = 'flex';

    // Collect ancestor layer IDs
    const ancestorLayerIds = new Set();
    let cur = parent;
    while (cur) {
        if (cur.assign_fix_location) {
            ancestorLayerIds.add(String(cur.assign_fix_location));
        }
        cur = allCategories.find(c => c.id == cur.parent_id);
    }

    // ✅ KEY FIX: If country is already selected, re-run onCountryChange
    // so the layer dropdown fully rebuilds with ancestor blocks applied
    const countryValueId = document.getElementById('f-country').value;
    if (countryValueId) {
        // Re-render the entire country section with ancestor blocking
        const layerSel   = document.getElementById('f-layer');
        const ownLayerId = layerSel.dataset.ownLayerId || '';
        const container  = document.getElementById('country-layers-container');
        const breadcrumb = document.getElementById('location-breadcrumb');

        const layers = collectAllLayersForCountry(countryValueId);
        let hasUsed  = false;

        layerSel.innerHTML = layers.length
            ? '<option value="">— Select layer —</option>' + layers.map(l => {
                const lid    = String(l.id);
                const isUsed = (usedLayerIds.has(lid) || ancestorLayerIds.has(lid))
                               && lid !== String(ownLayerId);
                if (isUsed) hasUsed = true;
                return `<option value="${l.id}" ${isUsed ? 'disabled style="color:#bbb"' : ''}>${l.name}${isUsed ? ' (assigned)' : ''}</option>`;
            }).join('')
            : '<option value="">— No layers defined —</option>';

        document.getElementById('layer-used-note').style.display = hasUsed ? 'block' : 'none';
        container.style.display = 'block';

        const country = lcCountries.find(c => String(c.id) === String(countryValueId));
        if (country) {
            document.getElementById('location-breadcrumb-text').textContent =
                `Linked to: ${country.value} (${layers.length} layer${layers.length !== 1 ? 's' : ''})`;
            breadcrumb.style.display = 'flex';
        }
    } else {
        // Country not yet selected — just store the blocks for when country is chosen
        refreshLayerDropdown(ancestorLayerIds);
    }

    updateLivePreview();
}

// ── Location ────────────────────────────────────────────────────

function onLocationToggle() {
    const checked = document.getElementById('f-is-location-linked').checked;
    document.getElementById('location-panel').style.display = checked ? 'block' : 'none';
    if (!checked) {
        document.getElementById('f-country').value                        = '';
        document.getElementById('f-layer').value                          = '';
        document.getElementById('country-layers-container').style.display = 'none';
        document.getElementById('location-breadcrumb').style.display      = 'none';
        document.getElementById('layer-used-note').style.display          = 'none';
    }
}

function populateCountryDropdown(selectedId = null) {
    const sel = document.getElementById('f-country');
    sel.innerHTML = '<option value="">— Select country —</option>' +
        lcCountries.map(c =>
            `<option value="${c.id}" ${selectedId == c.id ? 'selected' : ''}>${c.value}</option>`
        ).join('');
    if (selectedId) onCountryChange(selectedId);
}

function collectAllLayersForCountry(countryValueId) {
    const collected = [], ids = new Set();
    let queue = [String(countryValueId)];
    const visited = new Set();
    while (queue.length) {
        const next = [];
        for (const vid of queue) {
            if (visited.has(vid)) continue;
            visited.add(vid);
            for (const l of lcLayers.filter(l => String(l.parent_value_id) === vid)) {
                if (!ids.has(l.id)) {
                    ids.add(l.id);
                    collected.push(l);
                    lcValues.filter(v => v.layer_id === l.id).forEach(v => next.push(String(v.id)));
                }
            }
        }
        queue = next;
    }
    return collected.sort((a, b) => (a.depth || 0) - (b.depth || 0));
}

/**
 * Rebuild the layer <select> for the currently selected country.
 * extraBlockedIds = Set of layer ids to additionally disable
 *   (used when a parent category is chosen — its ancestor layers are blocked).
 */
function refreshLayerDropdown(extraBlockedIds = new Set()) {
    const countryValueId = document.getElementById('f-country').value;
    const layerSel       = document.getElementById('f-layer');
    const ownLayerId     = layerSel.dataset.ownLayerId || '';
    const parentId       = document.getElementById('f-parent').value;
 
    if (!countryValueId) return;
 
    const layers = collectAllLayersForCountry(countryValueId);
    let hasUsed  = false;
 
    layerSel.innerHTML = layers.length
        ? '<option value="">— Select layer —</option>' + layers.map(l => {
            const lid = String(l.id);
 
            let isUsed = false;
 
            if (parentId) {
                // Parent selected → block ancestor layers (from DB usedLayerIds filtered to ancestor chain)
                isUsed = extraBlockedIds.has(lid) && lid !== String(ownLayerId);
            }
            // No parent → never block (option A: all layers selectable)
 
            if (isUsed) hasUsed = true;
            return `<option value="${l.id}" ${isUsed ? 'disabled style="color:#bbb"' : ''}>${l.name}${isUsed ? ' (assigned)' : ''}</option>`;
        }).join('')
        : '<option value="">— No layers defined —</option>';
 
    document.getElementById('layer-used-note').style.display = hasUsed ? 'block' : 'none';
}

function onCountryChange(preSelectedId = null) {
    const countryValueId = preSelectedId || document.getElementById('f-country').value;
    const container      = document.getElementById('country-layers-container');
    const breadcrumb     = document.getElementById('location-breadcrumb');
    const layerSel       = document.getElementById('f-layer');
    const ownLayerId     = layerSel.dataset.ownLayerId || '';
    const parentId       = document.getElementById('f-parent').value;
 
    if (!countryValueId) {
        container.style.display  = 'none';
        breadcrumb.style.display = 'none';
        layerSel.innerHTML       = '<option value="">— Select layer —</option>';
        return;
    }
 
    // Collect ancestor layer ids ONLY if a parent is selected
    const ancestorLayerIds = new Set();
    if (parentId) {
        let cur = allCategories.find(c => c.id == parentId);
        while (cur) {
            if (cur.assign_fix_location) ancestorLayerIds.add(String(cur.assign_fix_location));
            cur = allCategories.find(c => c.id == cur.parent_id);
        }
    }
 
    const layers = collectAllLayersForCountry(countryValueId);
    let hasUsed  = false;
 
    layerSel.innerHTML = layers.length
        ? '<option value="">— Select layer —</option>' + layers.map(l => {
            const lid = String(l.id);
 
            let isUsed = false;
 
            if (parentId) {
                // Parent selected → block only ancestor-assigned layers
                isUsed = ancestorLayerIds.has(lid) && lid !== String(ownLayerId);
            }
            // No parent → show all, block nothing (Option A)
 
            if (isUsed) hasUsed = true;
            return `<option value="${l.id}" ${isUsed ? 'disabled style="color:#bbb"' : ''}>${l.name}${isUsed ? ' (assigned)' : ''}</option>`;
        }).join('')
        : '<option value="">— No layers defined —</option>';
 
    document.getElementById('layer-used-note').style.display = hasUsed ? 'block' : 'none';
    container.style.display = 'block';
 
    const country = lcCountries.find(c => String(c.id) === String(countryValueId));
    if (country) {
        document.getElementById('location-breadcrumb-text').textContent =
            `Linked to: ${country.value} (${layers.length} layer${layers.length !== 1 ? 's' : ''})`;
        breadcrumb.style.display = 'flex';
    }
}
// ── Modal ───────────────────────────────────────────────────────

async function openModal() {
    await loadUsedLayers();

    document.getElementById('modal-title').textContent = 'Add category';
    document.getElementById('category-form').reset();
    document.getElementById('edit-id').value                           = '';
    document.getElementById('f-level').value                           = 1;
    document.getElementById('f-parent').value                          = '';
    document.getElementById('live-preview').style.display              = 'none';
    document.getElementById('f-is-child-category').checked             = false;
    document.getElementById('child-category-panel').style.display      = 'none';
    document.getElementById('child-category-level-info').style.display = 'none';
    document.getElementById('f-is-location-linked').checked            = false;
    document.getElementById('location-panel').style.display            = 'none';
    document.getElementById('country-layers-container').style.display  = 'none';
    document.getElementById('location-breadcrumb').style.display       = 'none';
    document.getElementById('layer-used-note').style.display           = 'none';
    document.getElementById('f-portal-access').checked                 = true;
    document.getElementById('f-visible').checked                       = true;
    document.getElementById('f-layer').dataset.ownLayerId              = '';
    populateCountryDropdown();
    document.getElementById('modal-overlay').style.display = 'flex';
}

async function openEdit(id) {
    await loadUsedLayers();

    const cat = allCategories.find(c => c.id === id);
    if (!cat) return;

    document.getElementById('modal-title').textContent    = 'Edit category';
    document.getElementById('edit-id').value              = cat.id;
    document.getElementById('f-name').value               = cat.name;
    document.getElementById('f-code').value               = cat.code;
    document.getElementById('f-desc').value               = cat.description || '';
    document.getElementById('f-level').value              = cat.level;
    document.getElementById('f-parent').value             = cat.parent_id || '';
    document.getElementById('f-portal-access').checked    = cat.portal_access;
    document.getElementById('f-visible').checked          = cat.visible_in_hierarchy;
    document.getElementById('live-preview').style.display = 'none';
    document.getElementById('f-layer').dataset.ownLayerId = cat.assign_fix_location
        ? String(cat.assign_fix_location) : '';

   const isChild = !!cat.parent_id;
document.getElementById('f-is-child-category').checked             = isChild;
document.getElementById('child-category-panel').style.display      = isChild ? 'block' : 'none';
document.getElementById('child-category-level-info').style.display = 'none';

// ✅ Set f-parent BEFORE populating dropdowns
if (isChild) {
    document.getElementById('f-parent').value = cat.parent_id;
    populateChildParentDropdown(cat.parent_id); // just fills the <select>
}

const hasLocation = !!cat.country_id;
document.getElementById('f-is-location-linked').checked           = hasLocation;
document.getElementById('location-panel').style.display           = hasLocation ? 'block' : 'none';
document.getElementById('country-layers-container').style.display = 'none';
document.getElementById('location-breadcrumb').style.display      = 'none';
document.getElementById('layer-used-note').style.display          = 'none';

// ✅ Populate country first (sets f-country value)
populateCountryDropdown(hasLocation ? cat.country_id : null);

// ✅ Now that BOTH f-parent and f-country are set, trigger parent change
// This calls refreshLayerDropdown() with ancestor layer IDs properly
if (isChild && cat.parent_id) {
    onChildParentChange(); // now country is ready → state gets blocked ✓
}

// ✅ Then restore the saved layer selection for this category
if (hasLocation && cat.assign_fix_location) {
    document.getElementById('f-layer').value = cat.assign_fix_location;
}
    document.getElementById('modal-overlay').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
}

// ── Submit ──────────────────────────────────────────────────────

async function submitForm(e) {
    e.preventDefault();
    const id     = document.getElementById('edit-id').value;
    const url    = id ? URL_UPDATE(id) : URL_STORE;
    const method = id ? 'PUT' : 'POST';

    const isLocationLinked  = document.getElementById('f-is-location-linked').checked;
    const countryId         = isLocationLinked ? (document.getElementById('f-country').value || null) : null;
    const assignFixLocation = isLocationLinked ? (document.getElementById('f-layer').value   || null) : null;

    const body = {
        name:                 document.getElementById('f-name').value,
        code:                 document.getElementById('f-code').value,
        description:          document.getElementById('f-desc').value,
        parent_id:            document.getElementById('f-parent').value || null,
        portal_access:        document.getElementById('f-portal-access').checked,
        visible_in_hierarchy: document.getElementById('f-visible').checked,
        country_id:           countryId,
        assign_fix_location:  assignFixLocation,
    };

    const res  = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify(body),
    });

    const json = await res.json();
    if (json.success) {
        closeModal();
        await Promise.all([loadCategories(), loadUsedLayers()]);
    } else {
        alert(json.message || 'Something went wrong.');
    }
}

// ── Init ────────────────────────────────────────────────────────

document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

Promise.all([loadLocationData(), loadCategories(), loadUsedLayers()]);
</script>
@endsection