@extends('layouts.app')

@section('title', 'Edit Transaction Series | Settings')

@push('styles')
<style>
    :root {
        --zoho-blue: #0073E6;
        --zoho-blue-hover: #0060C0;
        --zoho-red: #E74C3C;
        --zoho-green: #27AE60;
        --zoho-gray-bg: #F4F5F7;
        --zoho-border: #DDE1E7;
        --zoho-text: #1A1A2E;
        --zoho-text-muted: #6B7280;
        --zoho-sidebar-bg: #FFFFFF;
        --zoho-white: #FFFFFF;
        --zoho-input-border: #C9D0D9;
        --zoho-label-red: #E74C3C;
        --zoho-row-hover: #F0F4FF;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 13px; color: var(--zoho-text);
        background: var(--zoho-gray-bg); margin: 0;
    }

    .settings-wrapper { display: flex; height: 100vh; overflow: hidden; background: var(--zoho-white); }

    .settings-sidebar {
        width: 260px; min-width: 260px;
        background: var(--zoho-sidebar-bg);
        border-right: 1px solid var(--zoho-border);
        overflow-y: auto; display: flex; flex-direction: column;
    }
    .sidebar-header {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 16px 12px; border-bottom: 1px solid var(--zoho-border);
    }
    .sidebar-back-btn {
        width: 28px; height: 28px; border: 1px solid var(--zoho-border);
        border-radius: 4px; background: var(--zoho-white); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted); font-size: 14px; text-decoration: none;
    }
    .sidebar-title    { font-size: 14px; font-weight: 600; }
    .sidebar-subtitle { font-size: 11px; color: var(--zoho-text-muted); }
    .sidebar-search { padding: 10px 12px; border-bottom: 1px solid var(--zoho-border); }
    .sidebar-search input {
        width: 100%; padding: 6px 10px 6px 30px;
        border: 1px solid var(--zoho-input-border); border-radius: 4px;
        font-size: 12px; outline: none;
        background: var(--zoho-gray-bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
    }
    .sidebar-section-label {
        padding: 10px 16px 4px; font-size: 11px; font-weight: 600;
        color: var(--zoho-text-muted); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .sidebar-nav-item {
        display: block; padding: 8px 16px; font-size: 13px;
        color: var(--zoho-text); text-decoration: none;
        cursor: pointer; transition: background 0.15s;
    }
    .sidebar-nav-item:hover { background: var(--zoho-row-hover); }
    .sidebar-nav-item.active {
        background: #E8F0FE; color: var(--zoho-blue);
        font-weight: 500; position: relative;
    }
    .sidebar-nav-item.active::before {
        content: ''; position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px; background: var(--zoho-blue);
        border-radius: 0 2px 2px 0;
    }
    .badge-new {
        display: inline-block; background: var(--zoho-blue); color: white;
        font-size: 9px; font-weight: 700; padding: 1px 5px;
        border-radius: 3px; margin-left: 6px; vertical-align: middle;
    }

    .settings-content { flex: 1; overflow-y: auto; display: flex; flex-direction: column; }

    .content-topbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 24px; border-bottom: 1px solid var(--zoho-border);
        background: var(--zoho-white); position: sticky; top: 0; z-index: 10;
    }

    .close-settings-btn {
        background: none; border: none; font-size: 13px;
        color: var(--zoho-text-muted); cursor: pointer;
        display: flex; align-items: center; gap: 4px;
    }

   .create-page { padding: 24px 32px 80px; max-width: 800px; }

    .breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; color: var(--zoho-text-muted);
        margin-bottom: 16px;
    }
    .breadcrumb a { color: var(--zoho-blue); text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }
    .breadcrumb-sep { color: var(--zoho-border); }

    .page-title { font-size: 20px; font-weight: 600; margin-bottom: 4px; color: var(--zoho-text); }
    .page-subtitle { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 24px; }

    .form-card {
        background: var(--zoho-white);
        border: 1px solid var(--zoho-border);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .form-card-header {
        padding: 14px 20px;
        background: var(--zoho-gray-bg);
        border-bottom: 1px solid var(--zoho-border);
        font-size: 13px; font-weight: 600; color: var(--zoho-text);
    }
    .form-card-body { padding: 20px; }

    .series-name-row {
        display: flex; align-items: center; gap: 16px; margin-bottom: 4px;
    }
    .series-name-row label {
        font-size: 13px; font-weight: 500;
        color: var(--zoho-label-red); white-space: nowrap; min-width: 110px;
    }
    .series-name-row label::after { content: '*'; margin-left: 2px; }

    /* Location row */
    .series-location-row {
        display: flex; align-items: center; gap: 16px; margin-top: 14px;
    }
    .series-location-row label {
        font-size: 13px; font-weight: 500;
        color: var(--zoho-text); white-space: nowrap; min-width: 110px;
    }

    .form-control {
        padding: 7px 10px; border: 1px solid var(--zoho-input-border);
        border-radius: 4px; font-size: 13px; outline: none;
        transition: border-color 0.15s; color: var(--zoho-text);
        background: var(--zoho-white); font-family: inherit;
    }
    .form-control:focus {
        border-color: var(--zoho-blue);
        box-shadow: 0 0 0 2px rgba(0,115,230,0.1);
    }
    .form-control.error { border-color: var(--zoho-red); }
    .form-control-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 28px;
        cursor: pointer;
    }

    .field-error { font-size: 11px; color: var(--zoho-red); margin-top: 4px; display: none; }
    .field-error.show { display: block; }

    .trans-table {
        width: 100%; border-collapse: collapse;
        border: 1px solid var(--zoho-border);
        font-size: 13px;
    }
    .trans-table th {
        background: var(--zoho-gray-bg); padding: 10px 14px;
        text-align: left; font-weight: 600; color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase; font-size: 11px; letter-spacing: 0.3px;
    }
    .trans-table td {
        padding: 9px 14px; border-bottom: 1px solid var(--zoho-border);
        vertical-align: middle;
    }
    .trans-table tr:last-child td { border-bottom: none; }
    .trans-table tr:hover td { background: var(--zoho-row-hover); }
    .trans-table .module-name { font-weight: 500; font-size: 13px; }
    .trans-table input {
        width: 100%; padding: 6px 10px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px; font-size: 13px; outline: none;
        font-family: inherit; background: var(--zoho-white);
        transition: border-color 0.15s;
    }
    .trans-table input:focus { border-color: var(--zoho-blue); }

    .preview-badge {
        display: inline-block;
        background: var(--zoho-gray-bg);
        border: 1px solid var(--zoho-border);
        padding: 4px 10px; border-radius: 4px;
        font-size: 12px; color: var(--zoho-text-muted);
        font-family: monospace; min-width: 90px;
    }

    .form-actions {
        display: flex; gap: 10px; align-items: center;
        padding: 16px 0;
    }

    .btn-primary {
        background: var(--zoho-blue); color: white; border: none;
        padding: 9px 20px; border-radius: 4px; font-size: 13px;
        font-weight: 500; cursor: pointer; transition: background 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-primary:hover { background: var(--zoho-blue-hover); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

    .btn-secondary {
        background: var(--zoho-white); color: var(--zoho-text);
        border: 1px solid var(--zoho-border); padding: 8px 18px;
        border-radius: 4px; font-size: 13px; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center;
        transition: background 0.15s;
    }
    .btn-secondary:hover { background: var(--zoho-gray-bg); }

    .alert {
        padding: 10px 14px; border-radius: 4px; font-size: 13px;
        margin-bottom: 16px; display: none; align-items: center; gap: 8px;
    }
    .alert.show    { display: flex; }
    .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .alert-error   { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }

    .spinner {
        width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4);
        border-top-color: white; border-radius: 50%;
        animation: spin 0.6s linear infinite; display: none;
    }
    .spinner.show { display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="settings-wrapper">

    {{-- Sidebar --}}
    <div class="settings-sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-back-btn">‹</a>
            <div>
                <div class="sidebar-title">All Settings</div>
                <div class="sidebar-subtitle">{{ auth()->user()->name ?? 'Admin' }}</div>
            </div>
        </div>
        <div class="sidebar-search">
            <input type="text" placeholder="Search settings ( / )">
        </div>
        <div class="sidebar-section-label">Organization Settings</div>
        <div style="padding:4px 0;">
            <a class="sidebar-nav-item" href="#">▾ Organization</a>
            <div style="padding-left:12px;">
                <a class="sidebar-nav-item" href="#">Profile</a>
                <a class="sidebar-nav-item" href="#">Branding</a>
                <a class="sidebar-nav-item" href="{{ route('locations.index') }}">
                    Locations <span class="badge-new">NEW</span>
                </a>
                <a class="sidebar-nav-item" href="#">Manage Subscription</a>
            </div>
        </div>
        <div style="padding:4px 0;">
            <a class="sidebar-nav-item" href="#">▾ Setup &amp; Configurations</a>
            <div style="padding-left:12px;">
                <a class="sidebar-nav-item active" href="{{ route('transaction-series.index') }}">
                    Transaction Series
                </a>
            </div>
        </div>
        <a class="sidebar-nav-item" href="#">▸ Users &amp; Roles</a>
        <a class="sidebar-nav-item" href="#">▸ Taxes &amp; Compliance</a>
        <a class="sidebar-nav-item" href="#">▸ Customization</a>
        <a class="sidebar-nav-item" href="#">▸ Automation</a>
        <div class="sidebar-section-label" style="margin-top:8px;">Module Settings</div>
        <a class="sidebar-nav-item" href="#">▸ Items</a>
        <a class="sidebar-nav-item" href="#">▸ Sales</a>
        <a class="sidebar-nav-item" href="#">▸ Purchases</a>
        <a class="sidebar-nav-item" href="#">▸ Inventory</a>
    </div>

    {{-- Main Content --}}
    <div class="settings-content">
        <div class="content-topbar">
            <span style="font-size:13px; color:var(--zoho-text-muted);">Search settings</span>
            <button class="close-settings-btn" onclick="window.history.back()">Close Settings ✕</button>
        </div>

        <div class="create-page">

            {{-- Breadcrumb --}}
            <div class="breadcrumb">
                <a href="{{ route('transaction-series.index') }}">Transaction Series</a>
                <span class="breadcrumb-sep">›</span>
                <span>{{ $transactionSeries->name }}</span>
            </div>

            <h2 class="page-title">Edit Transaction Series</h2>
            <p class="page-subtitle">Update the name, location, and numbering prefix for each module.</p>

            <div id="js-alert" class="alert"></div>

            {{-- Series Details Card --}}
            <div class="form-card">
                <div class="form-card-header">Series Details</div>
                <div class="form-card-body">

                    {{-- Series Name --}}
                    <div class="series-name-row">
                        <label for="series-name">Series Name</label>
                        <div style="flex:1;">
                            <input type="text"
                                   id="series-name"
                                   class="form-control"
                                   placeholder="e.g. Default Transaction Series"
                                   style="width:100%; max-width:400px;"
                                   value="{{ $transactionSeries->name }}"
                                   oninput="clearNameError()">
                            <div class="field-error" id="name-error">Series name is required</div>
                        </div>
                    </div>

                    {{-- Location --}}
                   {{-- Location --}}
<div class="series-location-row">
    <label>Location</label>
    <div style="flex:1;">
        <select id="series-location"
                class="form-control"
                multiple
                style="width:100%; max-width:400px; height:120px;">
            @foreach($locations ?? [] as $loc)
                @php
                    $linkedIds = is_array($transactionSeries->location_id)
                        ? $transactionSeries->location_id
                        : [];
                @endphp
                <option value="{{ $loc->id }}"
                    {{ in_array($loc->id, $linkedIds) ? 'selected' : '' }}>
                    {{ $loc->location_name }}
                </option>
            @endforeach
        </select>
        <div style="font-size:11px; color:var(--zoho-text-muted); margin-top:4px;">
            Ctrl/Cmd + Click to select multiple locations
        </div>
    </div>
</div>
                </div>
            </div>

            {{-- Modules Table Card --}}
            <div class="form-card">
                <div class="form-card-header">Module Numbering Preferences</div>
                <div class="form-card-body" style="padding:0;">
                    <table class="trans-table">
                        <thead>
                            <tr>
                                <th style="width:30%;">Module</th>
                                <th style="width:25%;">Prefix</th>
                                <th style="width:25%;">Starting Number</th>
                                <th style="width:20%;">Preview</th>
                            </tr>
                        </thead>
                        <tbody id="modules-tbody">
                            {{-- Pre-filled by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <button class="btn-primary" id="save-btn" onclick="updateSeries()">
                    <span class="spinner" id="save-spinner"></span>
                    Save
                </button>
                <a href="{{ route('transaction-series.index') }}" class="btn-secondary">Cancel</a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Existing data from backend ─────────────────────────────
const SERIES_ID      = {{ $transactionSeries->id }};
const EXISTING_DATA  = @json($transactionSeries->series_data ?? []);

const TRANS_MODULES = [
    { name: 'Credit Note',       prefix: 'CN-',   start: '00001'  },
    { name: 'Customer Payment',  prefix: '',       start: '1'      },
    { name: 'Purchase Order',    prefix: 'PO-',   start: '00001'  },
    { name: 'Sales Order',       prefix: 'SO-',   start: '00001'  },
    { name: 'Vendor Payment',    prefix: '',       start: '1'      },
    { name: 'Retainer Invoice',  prefix: 'RET-',  start: '00001'  },
    { name: 'Bill Of Supply',    prefix: 'BOS-',  start: '000001' },
    { name: 'Invoice',           prefix: 'INV-',  start: '000001' },
    { name: 'Sales Return',      prefix: 'RMA-',  start: '00001'  },
    { name: 'Delivery Challan',  prefix: 'DC-',   start: '00001'  },
];

// ── Render table with existing values ─────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.getElementById('modules-tbody');
    tbody.innerHTML = TRANS_MODULES.map((m, i) => {
        // Find existing saved data for this module
        const saved  = EXISTING_DATA.find(e => e.module === m.name) ?? {};
        const prefix = saved.prefix ?? m.prefix;
      const start  = saved.start ?? saved.starting_number ?? m.start;
        return `
        <tr>
            <td class="module-name">${m.name}</td>
            <td>
                <input type="text"
                       id="prefix-${i}"
                       value="${esc(prefix)}"
                       placeholder="e.g. INV-"
                       oninput="updatePreview(${i})">
            </td>
            <td>
                <input type="text"
                       id="start-${i}"
                       value="${esc(start)}"
                       placeholder="00001"
                       oninput="updatePreview(${i})">
            </td>
            <td>
                <span class="preview-badge" id="preview-${i}">${esc(prefix + start)}</span>
            </td>
        </tr>`;
    }).join('');
});

function updatePreview(i) {
    const p = document.getElementById(`prefix-${i}`).value;
    const s = document.getElementById(`start-${i}`).value;
    document.getElementById(`preview-${i}`).textContent = p + s;
}

// ── Validation ─────────────────────────────────────────────
function clearNameError() {
    document.getElementById('series-name').classList.remove('error');
    document.getElementById('name-error').classList.remove('show');
}

// ── Update (PUT) ───────────────────────────────────────────
function updateSeries() {
    const name = document.getElementById('series-name').value.trim();

    if (!name) {
        document.getElementById('series-name').classList.add('error');
        document.getElementById('name-error').classList.add('show');
        document.getElementById('series-name').focus();
        return;
    }

    clearNameError();

    const series = TRANS_MODULES.map((m, i) => ({
        module:  m.name,
        prefix:  document.getElementById(`prefix-${i}`)?.value ?? m.prefix,
        start:   document.getElementById(`start-${i}`)?.value  ?? m.start,
        preview: (document.getElementById(`prefix-${i}`)?.value ?? m.prefix) +
                 (document.getElementById(`start-${i}`)?.value  ?? m.start),
    }));

    const locationSelect = document.getElementById('series-location');
const locationIds = Array.from(locationSelect.selectedOptions)
                        .map(o => o.value)
                        .filter(Boolean);

    const btn     = document.getElementById('save-btn');
    const spinner = document.getElementById('save-spinner');
    btn.disabled  = true;
    spinner.classList.add('show');

    fetch(`/transaction-series/${SERIES_ID}`, {
        method: 'PUT',
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
        },
      body: JSON.stringify({ name, series, location_ids: locationIds }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        spinner.classList.remove('show');

        if (data.success) {
            window.location.href = '{{ route("transaction-series.index") }}';
        } else {
            showAlert(data.message ?? 'Failed to update', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        spinner.classList.remove('show');
        showAlert('Server error. Please try again.', 'error');
    });
}

function showAlert(msg, type) {
    const el = document.getElementById('js-alert');
    el.className   = `alert alert-${type} show`;
    el.textContent = (type === 'success' ? '✓ ' : '✕ ') + msg;
    setTimeout(() => el.classList.remove('show'), 5000);
}

function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush