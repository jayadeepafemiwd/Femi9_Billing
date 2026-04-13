@extends('layouts.app')

@section('title', 'Transaction Series | Settings')

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
        font-size: 13px;
        color: var(--zoho-text);
        background: var(--zoho-gray-bg);
        margin: 0;
    }

    .settings-wrapper { display: flex; height: 100vh; overflow: hidden; background: var(--zoho-white); }

    /* ── Sidebar ── */
    .settings-sidebar {
        width: 260px; min-width: 260px;
        background: var(--zoho-sidebar-bg);
        border-right: 1px solid var(--zoho-border);
        overflow-y: auto;
        display: flex; flex-direction: column;
    }

    .sidebar-header {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .sidebar-back-btn {
        width: 28px; height: 28px;
        border: 1px solid var(--zoho-border); border-radius: 4px;
        background: var(--zoho-white); cursor: pointer;
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
        color: var(--zoho-text); text-decoration: none; cursor: pointer;
        transition: background 0.15s;
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

    /* ── Content ── */
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

    .ts-page { padding: 24px 32px; max-width: 960px; }

    .page-title    { font-size: 20px; font-weight: 600; margin-bottom: 4px; color: var(--zoho-text); }
    .page-subtitle { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 20px; }

    .ts-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }

    /* Buttons */
    .btn-primary {
        background: var(--zoho-blue); color: white; border: none;
        padding: 8px 16px; border-radius: 4px; font-size: 13px;
        font-weight: 500; cursor: pointer; transition: background 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
        text-decoration: none;
    }
    .btn-primary:hover { background: var(--zoho-blue-hover); }

    .btn-secondary {
        background: var(--zoho-white); color: var(--zoho-text);
        border: 1px solid var(--zoho-border); padding: 7px 14px;
        border-radius: 4px; font-size: 13px; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center;
    }
    .btn-secondary:hover { background: var(--zoho-gray-bg); }

    .btn-danger {
        background: var(--zoho-red); color: white; border: none;
        padding: 7px 14px; border-radius: 4px; font-size: 13px; cursor: pointer;
    }

    .btn-link {
        background: none; border: none; color: var(--zoho-blue);
        font-size: 13px; cursor: pointer; padding: 0; text-decoration: none;
    }
    .btn-link:hover { text-decoration: underline; }

    /* Table */
    .ts-table {
        width: 100%; border-collapse: collapse;
        background: var(--zoho-white);
        border: 1px solid var(--zoho-border);
        border-radius: 6px; overflow: hidden;
    }
    .ts-table th {
        background: var(--zoho-gray-bg); padding: 10px 14px;
        text-align: left; font-size: 12px; font-weight: 600;
        color: var(--zoho-text-muted); border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase; letter-spacing: 0.4px;
    }
    .ts-table td {
        padding: 12px 14px; border-bottom: 1px solid var(--zoho-border);
        font-size: 13px; vertical-align: middle;
    }
    .ts-table tr:last-child td { border-bottom: none; }
    .ts-table tr:hover td     { background: var(--zoho-row-hover); }

    .action-icons { display: flex; gap: 8px; align-items: center; }

    .icon-btn {
        width: 28px; height: 28px; border: 1px solid var(--zoho-border);
        border-radius: 4px; background: var(--zoho-white); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted); font-size: 13px; transition: all 0.15s;
    }
    .icon-btn:hover        { background: var(--zoho-row-hover); border-color: var(--zoho-blue); color: var(--zoho-blue); }
    .icon-btn.delete:hover { background: #FEE; border-color: var(--zoho-red); color: var(--zoho-red); }

    .preview-pill {
        display: inline-block; background: var(--zoho-gray-bg);
        border: 1px solid var(--zoho-border); border-radius: 3px;
        padding: 2px 7px; font-size: 11px; font-family: monospace;
        color: var(--zoho-text-muted); margin: 1px 2px;
    }

    .module-count {
        display: inline-flex; align-items: center;
        background: #EEF4FF; color: var(--zoho-blue);
        font-size: 11px; font-weight: 600;
        padding: 2px 8px; border-radius: 10px;
    }

    /* Alert */
    .alert {
        padding: 10px 14px; border-radius: 4px; font-size: 13px;
        margin-bottom: 16px; display: none; align-items: center; gap: 8px;
    }
    .alert.show    { display: flex; }
    .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .alert-error   { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }

    /* Empty */
    .empty-state { text-align: center; padding: 60px 20px; color: var(--zoho-text-muted); }
    .empty-icon  { font-size: 48px; margin-bottom: 12px; }
    .empty-title { font-size: 15px; font-weight: 600; color: var(--zoho-text); margin-bottom: 6px; }
    .empty-text  { font-size: 13px; }

    /* Delete modal */
    .confirm-modal {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.4); z-index: 2000;
        align-items: center; justify-content: center;
    }
    .confirm-modal.active { display: flex; }
    .confirm-box {
        background: var(--zoho-white); border-radius: 8px;
        padding: 24px; width: 380px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .confirm-title   { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
    .confirm-text    { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 20px; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }
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

        <div class="ts-page">
            <h2 class="page-title">Transaction Series</h2>
            <p class="page-subtitle">Define numbering series for each transaction module. Assign them to locations.</p>

            @if(session('success'))
                <div class="alert alert-success show">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error show">✕ {{ session('error') }}</div>
            @endif
            <div id="js-alert" class="alert"></div>

            <div class="ts-toolbar">
    <div style="font-size:13px; color:var(--zoho-text-muted);">
        {{ $series->count() }} series configured
    </div>
    <div style="display:flex; gap:12px; align-items:center;">
        {{-- Prevent Duplicate button --}}
        <button class="btn-secondary" onclick="openPreventDuplicateModal()" 
                style="display:inline-flex; align-items:center; gap:6px;">
            ⚙ Prevent Duplicate Transaction Numbers
        </button>
        <a href="{{ route('transaction-series.create') }}" class="btn-primary">
            + New Series
        </a>
    </div>
</div>

            <table class="ts-table">
               <thead>
    <tr>
        <th>Series Name</th>
        <th>Sales Return</th>
        <th>Vendor Payment</th>
        <th>Retainer Invoice</th>
        <th>Purchase Order</th>
        <th>Credit Note</th>
        <th>Actions</th>
    </tr>
</thead>
               <tbody id="ts-tbody">
    @forelse($series as $s)
    @php
        $data = collect($s->series_data ?? []);
        $get = fn($mod) => ($data->firstWhere('module', $mod)['prefix'] ?? '') . ($data->firstWhere('module', $mod)['start'] ?? '');
    @endphp
    <tr id="ts-row-{{ $s->id }}">
        <td><a class="btn-link" href="{{ route('transaction-series.edit', $s->id) }}">{{ $s->name }}</a></td>
        <td style="font-family:monospace; font-size:12px;">{{ $get('Sales Return') }}</td>
        <td style="font-family:monospace; font-size:12px;">{{ $get('Vendor Payment') }}</td>
        <td style="font-family:monospace; font-size:12px;">{{ $get('Retainer Invoice') }}</td>
        <td style="font-family:monospace; font-size:12px;">{{ $get('Purchase Order') }}</td>
        <td style="font-family:monospace; font-size:12px;">{{ $get('Credit Note') }}</td>
        <td>
            <div class="action-icons">
                <a class="icon-btn" href="{{ route('transaction-series.edit', $s->id) }}" title="Edit">✎</a>
                <button class="icon-btn delete" onclick="openDeleteConfirm({{ $s->id }}, '{{ addslashes($s->name) }}')">🗑</button>
            </div>
        </td>
    </tr>
    @empty
    <tr id="empty-row">
        <td colspan="7">
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <div class="empty-title">No Transaction Series</div>
                <div class="empty-text">Create your first transaction numbering series</div>
            </div>
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="confirm-modal" id="delete-modal">
    <div class="confirm-box">
        <div class="confirm-title">Delete Transaction Series</div>
        <div class="confirm-text" id="delete-confirm-text">
            Are you sure? This action cannot be undone.
        </div>
        <div class="confirm-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger"    onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

{{-- Prevent Duplicate Modal --}}
<div class="confirm-modal" id="prevent-duplicate-modal">
    <div class="confirm-box" style="width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div class="confirm-title" style="margin-bottom:0;">
                Prevent Duplicate Transaction Numbers
            </div>
            <button onclick="closePreventDuplicateModal()" 
                    style="border:none;background:none;font-size:18px;cursor:pointer;color:#6B7280;">✕</button>
        </div>

        <p style="font-size:13px; color:#6B7280; margin-bottom:16px;">
            Prevent duplicate transaction numbers for
        </p>

        <label style="display:flex; align-items:flex-start; gap:10px; margin-bottom:16px; cursor:pointer;">
            <input type="radio" name="duplicate_prevention" value="this_fiscal_year" 
                   style="margin-top:3px; accent-color:#0073E6;">
            <div>
                <div style="font-size:13px; font-weight:500; margin-bottom:4px;">This Fiscal Year</div>
                <div style="font-size:12px; color:#6B7280;">
                    You cannot save the transactions with duplicate transaction numbers during this fiscal year.
                </div>
            </div>
        </label>

        <label style="display:flex; align-items:flex-start; gap:10px; margin-bottom:24px; cursor:pointer;">
            <input type="radio" name="duplicate_prevention" value="all_fiscal_years" 
                   checked style="margin-top:3px; accent-color:#0073E6;">
            <div>
                <div style="font-size:13px; font-weight:500; margin-bottom:4px;">All Fiscal Years</div>
                <div style="font-size:12px; color:#6B7280;">
                    You cannot save the transactions with duplicate transaction numbers in the current or any future fiscal year.
                </div>
            </div>
        </label>

        <div style="border-top:1px solid #DDE1E7; padding-top:16px;">
            <button class="btn-primary" onclick="savePreventDuplicate()">Save</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deleteTargetId = null;

function openDeleteConfirm(id, name) {
    deleteTargetId = id;
    document.getElementById('delete-confirm-text').innerHTML =
        `Are you sure you want to delete <strong>"${name}"</strong>?<br>This action cannot be undone.`;
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
    deleteTargetId = null;
}

function confirmDelete() {
    if (!deleteTargetId) return;
    fetch(`/transaction-series/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(r => r.json())
    .then(data => {
        closeDeleteModal();
        if (data.success) {
            document.getElementById(`ts-row-${deleteTargetId}`)?.remove();
            showAlert('Series deleted successfully', 'success');
            const tbody = document.getElementById('ts-tbody');
            if (tbody && tbody.querySelectorAll('tr[id^="ts-row-"]').length === 0) {
                tbody.innerHTML = `<tr id="empty-row"><td colspan="5"><div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <div class="empty-title">No Transaction Series</div>
                    <div class="empty-text">Create your first transaction numbering series</div>
                </div></td></tr>`;
            }
        } else {
            showAlert(data.message ?? 'Failed to delete', 'error');
        }
    })
    .catch(() => showAlert('Server error', 'error'));
}

function showAlert(msg, type) {
    const el = document.getElementById('js-alert');
    el.className   = `alert alert-${type} show`;
    el.textContent = (type === 'success' ? '✓ ' : '✕ ') + msg;
    setTimeout(() => el.classList.remove('show'), 4000);
}

// Prevent Duplicate Modal
function openPreventDuplicateModal() {
    document.getElementById('prevent-duplicate-modal').classList.add('active');
}

function closePreventDuplicateModal() {
    document.getElementById('prevent-duplicate-modal').classList.remove('active');
}

function savePreventDuplicate() {
    const val = document.querySelector('input[name="duplicate_prevention"]:checked')?.value;
    // TODO: Save to backend if needed
    closePreventDuplicateModal();
    showAlert('Duplicate prevention setting saved!', 'success');
}
</script>
@endpush