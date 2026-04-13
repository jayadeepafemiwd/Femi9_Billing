@extends('layouts.app')

@section('title', 'Locations | Settings')

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

    /* ── Settings Layout ─────────────────── */
    .settings-wrapper {
        display: flex;
        height: 100vh;
        overflow: hidden;
        background: var(--zoho-white);
    }

    /* ── Left Sidebar ─────────────────────── */
    .settings-sidebar {
        width: 260px;
        min-width: 260px;
        background: var(--zoho-sidebar-bg);
        border-right: 1px solid var(--zoho-border);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .sidebar-back-btn {
        width: 28px; height: 28px;
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        background: var(--zoho-white);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted);
        font-size: 14px;
        text-decoration: none;
    }

    .sidebar-title { font-size: 14px; font-weight: 600; }
    .sidebar-subtitle { font-size: 11px; color: var(--zoho-text-muted); }

    .sidebar-search {
        padding: 10px 12px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .sidebar-search input {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px;
        font-size: 12px;
        background: var(--zoho-gray-bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
        outline: none;
    }

    .sidebar-section-label {
        padding: 10px 16px 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar-nav-item {
        display: block;
        padding: 8px 16px;
        font-size: 13px;
        color: var(--zoho-text);
        text-decoration: none;
        cursor: pointer;
        border-radius: 0;
        transition: background 0.15s;
    }

    .sidebar-nav-item:hover { background: var(--zoho-row-hover); }

    .sidebar-nav-item.active {
        background: #E8F0FE;
        color: var(--zoho-blue);
        font-weight: 500;
        position: relative;
    }

    .sidebar-nav-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--zoho-blue);
        border-radius: 0 2px 2px 0;
    }

    .badge-new {
        display: inline-block;
        background: var(--zoho-blue);
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 3px;
        margin-left: 6px;
        vertical-align: middle;
        letter-spacing: 0.3px;
    }

    /* ── Main Content ─────────────────────── */
    .settings-content {
        flex: 1;
        overflow-y: auto;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .content-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 24px;
        border-bottom: 1px solid var(--zoho-border);
        background: var(--zoho-white);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .close-settings-btn {
        background: none;
        border: none;
        font-size: 13px;
        color: var(--zoho-text-muted);
        cursor: pointer;
        display: flex; align-items: center; gap: 4px;
    }

    /* ── Locations List ────────────────────── */
    .locations-page {
        padding: 24px 32px;
        max-width: 900px;
    }

    .page-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--zoho-text);
    }

    .locations-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .btn-primary {
        background: var(--zoho-blue);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
    }

    .btn-primary:hover { background: var(--zoho-blue-hover); }

    .btn-secondary {
        background: var(--zoho-white);
        color: var(--zoho-text);
        border: 1px solid var(--zoho-border);
        padding: 7px 14px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-secondary:hover { background: var(--zoho-gray-bg); }

    .btn-danger {
        background: var(--zoho-red);
        color: white;
        border: none;
        padding: 7px 14px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-link {
        background: none;
        border: none;
        color: var(--zoho-blue);
        font-size: 13px;
        cursor: pointer;
        padding: 0;
        text-decoration: none;
    }

    .btn-link:hover { text-decoration: underline; }

    /* Locations Table */
    .locations-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--zoho-white);
        border: 1px solid var(--zoho-border);
        border-radius: 6px;
        overflow: hidden;
    }

    .locations-table th {
        background: var(--zoho-gray-bg);
        padding: 10px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .locations-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--zoho-border);
        font-size: 13px;
        vertical-align: middle;
    }

    .locations-table tr:last-child td { border-bottom: none; }

    .locations-table tr:hover td { background: var(--zoho-row-hover); }

    .location-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }

    .type-business { background: #E8F5E9; color: #2E7D32; }
    .type-warehouse { background: #E3F2FD; color: #1565C0; }

    .location-status-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot-active { background: var(--zoho-green); }
    .dot-inactive { background: #ccc; }

    .action-icons { display: flex; gap: 8px; align-items: center; }

    .icon-btn {
        width: 28px; height: 28px;
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        background: var(--zoho-white);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--zoho-text-muted);
        font-size: 13px;
        transition: all 0.15s;
    }

    .icon-btn:hover { background: var(--zoho-row-hover); border-color: var(--zoho-blue); color: var(--zoho-blue); }
    .icon-btn.delete:hover { background: #FEE; border-color: var(--zoho-red); color: var(--zoho-red); }

    /* ── Modal Overlay ──────────────────────── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
        align-items: flex-start;
        justify-content: flex-end;
    }

    .modal-overlay.active { display: flex; }

    /* ── Side Drawer (Add/Edit) ──────────────── */
    .side-drawer {
        width: 520px;
        height: 100vh;
        background: var(--zoho-white);
        overflow-y: auto;
        box-shadow: -4px 0 20px rgba(0,0,0,0.15);
        animation: slideIn 0.25s ease;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }

    .drawer-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--zoho-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        background: var(--zoho-white);
        z-index: 5;
    }

    .drawer-title { font-size: 16px; font-weight: 600; }

    .drawer-close {
        width: 28px; height: 28px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 18px;
        color: var(--zoho-text-muted);
        display: flex; align-items: center; justify-content: center;
        border-radius: 4px;
    }

    .drawer-close:hover { background: var(--zoho-gray-bg); }

    .drawer-body { padding: 20px 24px; }

    /* Location Type Cards */
    .location-type-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .type-card {
        border: 2px solid var(--zoho-border);
        border-radius: 6px;
        padding: 14px;
        cursor: pointer;
        transition: all 0.15s;
        position: relative;
    }

    .type-card:hover { border-color: var(--zoho-blue); background: #F0F4FF; }

    .type-card.selected {
        border-color: var(--zoho-blue);
        background: #EEF4FF;
    }

    .type-card input[type="radio"] {
        position: absolute;
        top: 12px; left: 12px;
        accent-color: var(--zoho-blue);
    }

    .type-card-content { padding-left: 22px; }
    .type-card-title { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    .type-card-desc { font-size: 11px; color: var(--zoho-text-muted); line-height: 1.5; }

    /* Form Fields */
    .form-section-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--zoho-text);
        margin: 20px 0 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--zoho-border);
    }

    .form-group {
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: flex-start;
        gap: 10px;
    }

    .form-label {
        font-size: 12px;
        color: var(--zoho-text);
        padding-top: 7px;
        text-align: right;
    }

    .form-label.required::after {
        content: '*';
        color: var(--zoho-label-red);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.15s;
        color: var(--zoho-text);
        background: var(--zoho-white);
    }

    .form-control:focus { border-color: var(--zoho-blue); box-shadow: 0 0 0 2px rgba(0,115,230,0.1); }

    .form-control-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 28px;
        cursor: pointer;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--zoho-text);
        cursor: pointer;
        padding-top: 4px;
    }

    .checkbox-label input { accent-color: var(--zoho-blue); }

    /* Address section */
    .address-grid { display: flex; flex-direction: column; gap: 8px; }

    /* Logo select */
    .logo-select-wrap { position: relative; }

    /* Transaction Series Table */
    .trans-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        overflow: hidden;
        font-size: 12px;
    }

    .trans-table th {
        background: var(--zoho-gray-bg);
        padding: 8px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.3px;
    }

    .trans-table td {
        padding: 8px 12px;
        border-bottom: 1px solid var(--zoho-border);
        vertical-align: middle;
    }

    .trans-table tr:last-child td { border-bottom: none; }

    .trans-table input {
        width: 100%;
        padding: 5px 8px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 3px;
        font-size: 12px;
        outline: none;
    }

    .trans-table input:focus { border-color: var(--zoho-blue); }

    .preview-badge {
        background: var(--zoho-gray-bg);
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 11px;
        color: var(--zoho-text-muted);
        font-family: monospace;
    }

    /* Location Access */
    .access-box {
        border: 1px solid var(--zoho-border);
        border-radius: 4px;
        overflow: hidden;
    }

    .access-header {
        padding: 10px 14px;
        background: var(--zoho-gray-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .access-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--zoho-blue); display: inline-block; margin-right: 6px; }

    .access-users-table { width: 100%; border-collapse: collapse; }

    .access-users-table th {
        padding: 8px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
    }

    .access-users-table td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--zoho-border);
        font-size: 12px;
    }

    .user-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: var(--zoho-blue);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
    }

    /* Drawer Footer */
    .drawer-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: var(--zoho-white);
    }

    /* Delete Confirm Modal */
    .confirm-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .confirm-modal.active { display: flex; }

    .confirm-box {
        background: var(--zoho-white);
        border-radius: 8px;
        padding: 24px;
        width: 380px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .confirm-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
    .confirm-text { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 20px; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }

    /* Transaction Series Modal */
    .trans-series-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 3000;
        align-items: center;
        justify-content: center;
    }

    .trans-series-modal.active { display: flex; }

    .trans-series-box {
        background: var(--zoho-white);
        border-radius: 8px;
        width: 680px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .trans-series-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--zoho-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: var(--zoho-white);
    }

    .trans-series-title { font-size: 15px; font-weight: 600; }

    .trans-series-body { padding: 20px 24px; }

    .series-name-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .series-name-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--zoho-label-red);
        white-space: nowrap;
        min-width: 100px;
    }

    .trans-series-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* Alert */
    .alert {
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 16px;
        display: none;
        align-items: center;
        gap: 8px;
    }

    .alert.show { display: flex; }
    .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--zoho-text-muted);
    }

    .empty-icon { font-size: 48px; margin-bottom: 12px; }
    .empty-title { font-size: 15px; font-weight: 600; color: var(--zoho-text); margin-bottom: 6px; }
    .empty-text { font-size: 13px; }
    .unit-dropdown-wrap { position: relative; flex: 1; }
.unit-input-box {
  display: flex; align-items: center;
  border: 1px solid #d0d4de; border-radius: 6px;
  background: #fff; cursor: pointer; padding: 0;
  transition: border-color 0.15s;
}
.unit-input-box:focus-within { border-color: #2d5be3; }
.unit-input-box input {
  flex: 1; border: none; outline: none; padding: 8px 12px;
  font-size: 14px; font-family: inherit; background: transparent;
  cursor: pointer; color: #333;
}
.unit-input-box input::placeholder { color: #aaa; }
.unit-chevron {
  padding: 8px 10px; color: #888; font-size: 11px;
  pointer-events: none; user-select: none;
}
.unit-dropdown-menu {
  display: none; position: absolute; top: calc(100% + 2px); left: 0;
  width: 100%; background: #fff; border: 1px solid #d0d4de;
  border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.10);
  z-index: 200; max-height: 260px; overflow-y: auto;
}
.unit-dropdown-menu.open { display: block; }
.unit-option {
  display: flex; align-items: center; justify-content: space-between;
  padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer;
  transition: background 0.1s;
}
.unit-option:hover { background: #f0f4ff; }
.unit-option.selected { background: #e8efff; color: #2d5be3; font-weight: 600; }
.unit-option .unit-del {
  display: none; background: none; border: none;
  color: #e74c3c; cursor: pointer; font-size: 13px;
  padding: 2px 6px; border-radius: 4px;
  line-height: 1;
}
.unit-option:hover .unit-del { display: inline-flex; align-items: center; }
.unit-option .unit-del:hover { background: #fde8e8; }
.unit-no-result { padding: 10px 14px; color: #aaa; font-size: 13px; }
.unit-add-new {
  padding: 9px 14px; font-size: 13px; color: #2d5be3;
  cursor: pointer; border-top: 1px solid #e8eaf0;
  display: flex; align-items: center; gap: 6px; font-weight: 500;
}
.unit-add-new:hover { background: #f0f4ff; }

/* ── Add Identifier ───────────────────────────────── */
.identifier-fields { display: none; margin-top: 14px; }
.identifier-fields.show { display: block; }
.identifier-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 12px 32px;
}
.identifier-row { display: flex; align-items: center; gap: 12px; }
.identifier-row label {
  width: 60px; font-size: 13px; color: #555; font-weight: 500;
  flex-shrink: 0; text-align: right;
}
.identifier-row input {
  flex: 1; border: 1px solid #d0d4de; border-radius: 6px;
  padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none;
}
.identifier-row input:focus { border-color: #2d5be3; }

</style>
@endpush

@section('content')
<div class="settings-wrapper">

    {{-- ── Left Sidebar ── --}}
    <div class="settings-sidebar">
        <div class="sidebar-header">
<a href="{{ url('/') }}" class="sidebar-back-btn">‹</a>            <div>
                <div class="sidebar-title">All Settings</div>
                <div class="sidebar-subtitle">{{ auth()->user()->name ?? 'Admin' }}</div>
            </div>
        </div>

        <div class="sidebar-search">
            <input type="text" placeholder="Search settings ( / )">
        </div>

        <div class="sidebar-section-label">Organization Settings</div>

        <div style="padding: 4px 0;">
            <a class="sidebar-nav-item" href="#">
                <span>▾</span> Organization
            </a>
            <div style="padding-left: 12px;">
                <a class="sidebar-nav-item" href="#">Profile</a>
                <a class="sidebar-nav-item" href="#">Branding</a>
                <a class="sidebar-nav-item active" href="#">
                    Locations <span class="badge-new">NEW</span>
                </a>
                <a class="sidebar-nav-item" href="#">Manage Subscription</a>
            </div>
        </div>

        <a class="sidebar-nav-item" href="#">▸ Users &amp; Roles</a>
        <a class="sidebar-nav-item" href="#">▸ Taxes &amp; Compliance</a>
        <a class="sidebar-nav-item" href="#">▸ Setup &amp; Configurations</a>
        <a class="sidebar-nav-item" href="#">▸ Customization</a>
        <a class="sidebar-nav-item" href="#">▸ Automation</a>

        <div class="sidebar-section-label" style="margin-top: 8px;">Module Settings</div>
        <a class="sidebar-nav-item" href="#">▸ Items</a>
        <a class="sidebar-nav-item" href="#">▸ Sales</a>
        <a class="sidebar-nav-item" href="#">▸ Purchases</a>
        <a class="sidebar-nav-item" href="#">▸ Inventory</a>
    </div>

    {{-- ── Main Content ── --}}
    <div class="settings-content">

        {{-- Top Bar --}}
        <div class="content-topbar">
            <span style="font-size:13px; color: var(--zoho-text-muted);">Search settings</span>
            <button class="close-settings-btn" onclick="window.history.back()">
                Close Settings ✕
            </button>
        </div>

        {{-- Locations Page --}}
        <div class="locations-page">
            <h2 class="page-title">Locations</h2>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="alert alert-success show">✓ {{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-error show">✕ {{ session('error') }}</div>
            @endif

            <div id="js-alert" class="alert"></div>

            {{-- Toolbar --}}
            <div class="locations-toolbar">
                <div style="font-size:13px; color: var(--zoho-text-muted);">
                    Manage your business and warehouse locations
                </div>
                <button class="btn-primary" onclick="openAddDrawer()">
                    + New Location
                </button>
            </div>

            {{-- Locations Table --}}
            <table class="locations-table">
                <thead>
                    <tr>
                        <th>Location Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="locations-tbody">
                    @forelse($locations ?? [] as $location)
                    <tr id="row-{{ $location->id }}">
                        <td>
                            <button class="btn-link" onclick="openEditDrawer({{ $location->id }})">
                                {{ $location->location_name }}
                            </button>
                        </td>
                        <td>
                            <span class="location-type-badge {{ $location->location_type === 'business' ? 'type-business' : 'type-warehouse' }}">
                                {{ $location->location_type === 'business' ? '🏢 Business' : '🏭 Warehouse' }}
                            </span>
                        </td>
                       <td style="color: var(--zoho-text-muted); font-size:12px;">
    @if($location->address_details)
        {{ $location->address_details['city'] ?? '' }}
        @if($location->address_details['state'] ?? '')
            , {{ $location->address_details['state'] }}
        @endif
    @else
        —
    @endif
</td>
                        <td>
                            <span class="location-status-dot dot-active"></span>
                            Active
                        </td>
                        <td>
                            <div class="action-icons">
                                <button class="icon-btn" title="Edit" onclick="openEditDrawer({{ $location->id }})">✎</button>
                                <button class="icon-btn delete" title="Delete" onclick="openDeleteConfirm({{ $location->id }}, '{{ $location->location_name }}')">🗑</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">📍</div>
                                <div class="empty-title">No Locations Added</div>
                                <div class="empty-text">Add your first business or warehouse location</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ADD / EDIT DRAWER
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="location-drawer-overlay" onclick="closeDrawerOnOverlay(event)">
    <div class="side-drawer" id="location-drawer">

        <div class="drawer-header">
            <span class="drawer-title" id="drawer-title">Add Location</span>
            <button class="drawer-close" onclick="closeDrawer()">✕</button>
        </div>

        <div class="drawer-body">

            {{-- Location Type --}}
            <div class="form-section-title">Location Type</div>
            <div class="location-type-cards">
                <div class="type-card selected" id="card-business" onclick="selectLocationType('business')">
                    <input type="radio" name="location_type" value="business" id="type-business" checked>
                    <div class="type-card-content">
                        <div class="type-card-title">Business Location</div>
                        <div class="type-card-desc">Represents your organization or office's operational location. Used for transactions and regional performance.</div>
                    </div>
                </div>
                <div class="type-card" id="card-warehouse" onclick="selectLocationType('warehouse')">
                    <input type="radio" name="location_type" value="warehouse" id="type-warehouse">
                    <div class="type-card-content">
                        <div class="type-card-title">Warehouse Only Location</div>
                        <div class="type-card-desc">Refers to where your items are stored. Helps track and monitor stock levels.</div>
                    </div>
                </div>
            </div>

            {{-- Logo (Business only) --}}
            <div id="logo-section">
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <div>
                        <select class="form-control form-control-select" name="logo" id="logo-select">
                            <option>Same as Organization Logo</option>
                            <option>Upload Custom Logo</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Name --}}
            <div class="form-group">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" id="location-name" name="location_name" placeholder="Location Name">
            </div>

            {{-- Child Location checkbox --}}
            <div class="form-group" id="child-location-row" style="display:none;">
                <label class="form-label"></label>
                <label class="checkbox-label">
                    <input type="checkbox" id="is-child" onchange="toggleParentLocation()">
                    This is a Child Location
                </label>
            </div>

            {{-- Parent Location (warehouse only + child checked) --}}
            <div class="form-group" id="parent-location-row" style="display:none;">
    <label class="form-label required">Parent Location</label>
    <select class="form-control form-control-select" id="parent-location-select">
        <option value="">— Select Parent Location —</option>
        @foreach($locations ?? [] as $loc)
            <option value="{{ $loc->id }}">
                {{ $loc->location_name }}
                ({{ $loc->location_type === 'business' ? '🏢 Business' : '🏭 Warehouse' }})
            </option>
        @endforeach
    </select>
</div>

            {{-- Address --}}
            <div class="form-section-title">Address</div>
            <div class="address-grid">
                <input type="text" class="form-control" name="attention" placeholder="Attention">
                <input type="text" class="form-control" name="street1" placeholder="Street 1">
                <input type="text" class="form-control" name="street2" placeholder="Street 2">
                <div class="form-row-2">
                    <input type="text" class="form-control" name="city" id="addr-city" placeholder="City">
                    <input type="text" class="form-control" name="pincode" id="addr-pincode" placeholder="Pin Code">
                </div>
                <select class="form-control form-control-select" name="country">
                    <option>India</option>
                    <option>United States</option>
                    <option>United Kingdom</option>
                </select>
                <div class="form-row-2">
                    <select class="form-control form-control-select" name="state">
                        <option value="">State/Union Territory</option>
                        <option>Tamil Nadu</option>
                        <option>Karnataka</option>
                        <option>Kerala</option>
                        <option>Andhra Pradesh</option>
                        <option>Maharashtra</option>
                        <option>Delhi</option>
                    </select>
                    <input type="text" class="form-control" name="phone" placeholder="Phone">
                </div>
                <input type="text" class="form-control" name="fax" placeholder="Fax Number">
                <input type="text" class="form-control" name="website" placeholder="Website URL">
            </div>

            {{-- Primary Contact (Business only) --}}
            <div id="business-only-fields">
                <div class="form-section-title">Additional Info</div>
                <div class="form-group">
                    <label class="form-label required">Primary Contact</label>
                    <select class="form-control form-control-select" name="primary_contact">
                        <option>{{ auth()->user()->name ?? 'Admin' }}</option>
                    </select>
                </div>

               {{-- Transaction Number Series --}}
<div class="form-group">
    <label class="form-label required">Transaction Number Series</label>
    <div>
        {{-- Dropdown to pick an existing series --}}
        <div style="display:flex;gap:8px;align-items:center;">
            <select class="form-control form-control-select" id="trans-series-select" style="flex:1;">
                <option value="">— Select Series to Attach —</option>
                @foreach($transactionSeries ?? [] as $series)
                <option value="{{ $series->id }}">{{ $series->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn-secondary" onclick="attachSelectedSeries()" style="white-space:nowrap;">
                Attach
            </button>
        </div>
 
        {{-- List of attached series (rendered by JS renderAttachedSeriesList()) --}}
        <div id="attached-series-list" style="margin-top:4px;"></div>
 
        {{-- Link to create new series --}}
        <button class="btn-link" style="margin-top:8px;font-size:12px;" onclick="openTransSeriesModal()">
            + Create New Transaction Series
        </button>
    </div>
</div>
 
{{-- Default Transaction Number Series --}}
{{-- This is now controlled by the radio buttons inside attached-series-list
     But we keep a hidden select to send to backend if needed --}}
<div class="form-group" style="display:none;">
    <label class="form-label required">Default Transaction Number Series</label>
    <select class="form-control form-control-select" id="default-trans-series-select">
        <option value="">— Select Default Series —</option>
    </select>
</div>
                {{-- Location Access --}}
                <div class="form-section-title">Location Access</div>
                <div class="access-box">
                    <div class="access-header">
                        <div>
                            <span class="access-dot"></span>
                            <strong>1 user(s) selected</strong>
                            <div style="font-size:11px; color: var(--zoho-text-muted); margin-left:14px;">
                                Selected users can create and access transactions for this location.
                            </div>
                        </div>
                        <label class="checkbox-label">
                            <input type="checkbox"> Provide access to all users
                        </label>
                    </div>
                    <table class="access-users-table">
                        <thead>
                            <tr>
                                <th>Users</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                                    <div style="display:inline-block; vertical-align:middle;">
                                        <div style="font-weight:500;">{{ auth()->user()->name ?? 'Admin' }}</div>
                                        <div style="font-size:11px; color: var(--zoho-text-muted);">{{ auth()->user()->email ?? '' }}</div>
                                    </div>
                                </td>
                                <td>Admin</td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control form-control-select" style="max-width:200px;">
                                        <option>Select users</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-select">
                                        <option>User's Role</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- /drawer-body --}}

        <div class="drawer-footer">
            <button class="btn-primary" onclick="saveLocation()">Save</button>
            <button class="btn-secondary" onclick="closeDrawer()">Cancel</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════════════ --}}
<div class="confirm-modal" id="delete-modal">
    <div class="confirm-box">
        <div class="confirm-title">Delete Location</div>
        <div class="confirm-text" id="delete-confirm-text">
            Are you sure you want to delete this location?<br>
            This action cannot be undone.
        </div>
        <div class="confirm-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TRANSACTION SERIES MODAL
══════════════════════════════════════════════ --}}
<div class="trans-series-modal" id="trans-series-modal">
    <div class="trans-series-box">
        <div class="trans-series-header">
            <span class="trans-series-title">Transaction Series Preferences</span>
            <button class="drawer-close" onclick="closeTransSeriesModal()">✕</button>
        </div>
        <div class="trans-series-body">
            <div class="series-name-group">
                <label>Series Name*</label>
                <input type="text" class="form-control" id="series-name" placeholder="e.g. Default Transaction Series" style="max-width:300px;">
            </div>

            <table class="trans-table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Prefix</th>
                        <th>Starting Number</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody id="trans-series-tbody">
                </tbody>
            </table>
        </div>
        {{-- Series Name Card-ல் இதை add பண்ணுங்கள் --}}
<div class="series-name-row" style="margin-top: 12px;">
    <label for="series-location" style="color: var(--zoho-text);">Location</label>
    <div style="flex:1;">
        <select id="series-location" 
                class="form-control" 
                style="width:100%; max-width:400px;">
            <option value="">Add Location</option>
            @foreach($locations ?? [] as $loc)
                <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
            @endforeach
        </select>
    </div>
</div>
        <div class="trans-series-footer">
            <button class="btn-primary" onclick="saveTransSeries()">Save</button>
            <button class="btn-secondary" onclick="closeTransSeriesModal()">Cancel</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── State ──────────────────────────────────────────
let currentEditId        = null;
let deleteTargetId       = null;
let currentLocationType  = 'business';
 
// Tracks series attached to current location in drawer
// { seriesId: { id, name, is_default } }
let attachedSeriesMap = {};
 
// Transaction modules
const transModules = [
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
 
// ── Drawer Open/Close ──────────────────────────────
function openAddDrawer() {
    currentEditId     = null;
    attachedSeriesMap = {};
    document.getElementById('drawer-title').textContent = 'Add Location';
    document.getElementById('location-name').value = '';
    selectLocationType('business');
    renderAttachedSeriesList();
    document.getElementById('location-drawer-overlay').classList.add('active');
}
 
function openEditDrawer(id) {
    currentEditId     = id;
    attachedSeriesMap = {};
    document.getElementById('drawer-title').textContent = 'Edit Location';
 
    fetch(`/locations/${id}/edit`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => {
        document.getElementById('location-name').value = data.location_name ?? '';
        selectLocationType(data.location_type ?? 'business');
 
        // Warehouse: child / parent
        if (data.location_type === 'warehouse') {
            const isChild = data.is_child ?? false;
            document.getElementById('is-child').checked = isChild;
            if (isChild && data.parent_location_id) {
                document.getElementById('parent-location-row').style.display = '';
                document.getElementById('parent-location-select').value = data.parent_location_id;
            }
        }
 
        // ⭐ Restore attached series
        const defaultId = data.default_series_id;
        (data.transaction_series_ids ?? []).forEach(sid => {
            attachedSeriesMap[sid] = {
                id:         sid,
                name:       `Series #${sid}`,   // placeholder; enriched below
                is_default: String(sid) === String(defaultId),
            };
        });
 
        // Enrich names from existing <select> options
        const opts = document.getElementById('trans-series-select').options;
        for (let o of opts) {
            if (attachedSeriesMap[o.value]) {
                attachedSeriesMap[o.value].name = o.text;
            }
        }
 
        renderAttachedSeriesList();
        document.getElementById('location-drawer-overlay').classList.add('active');
    })
    .catch(() => showAlert('Failed to load location data', 'error'));
}
 
function closeDrawer() {
    document.getElementById('location-drawer-overlay').classList.remove('active');
}
 
function closeDrawerOnOverlay(e) {
    if (e.target === document.getElementById('location-drawer-overlay')) closeDrawer();
}
 
// ── Location Type ──────────────────────────────────
function selectLocationType(type) {
    currentLocationType = type;
    document.getElementById('card-business').classList.toggle('selected', type === 'business');
    document.getElementById('card-warehouse').classList.toggle('selected', type === 'warehouse');
    document.getElementById('type-business').checked = type === 'business';
    document.getElementById('type-warehouse').checked = type === 'warehouse';
    document.getElementById('logo-section').style.display          = type === 'business' ? '' : 'none';
    document.getElementById('business-only-fields').style.display  = type === 'business' ? '' : 'none';
    document.getElementById('child-location-row').style.display    = type === 'warehouse' ? 'grid' : 'none';
    if (type === 'business') {
        document.getElementById('is-child').checked = false;
        document.getElementById('parent-location-row').style.display = 'none';
    }
}
 
function toggleParentLocation() {
    const checked = document.getElementById('is-child').checked;
    document.getElementById('parent-location-row').style.display = checked ? '' : 'none';
}
 
// ── Transaction Series — Attach from Dropdown ──────
/**
 * Call this when user picks a series from #trans-series-select and clicks "Attach"
 * OR you can auto-attach on change — see below.
 */
function attachSelectedSeries() {
    const sel = document.getElementById('trans-series-select');
    const id  = sel.value;
    const nm  = sel.options[sel.selectedIndex]?.text;
    if (!id) return;
 
    if (!attachedSeriesMap[id]) {
        attachedSeriesMap[id] = { id, name: nm, is_default: Object.keys(attachedSeriesMap).length === 0 };
    }
 
    sel.value = '';   // reset dropdown
    renderAttachedSeriesList();
    syncDefaultSeriesDropdown();
}
 
function setDefaultSeries(id) {
    Object.keys(attachedSeriesMap).forEach(k => {
        attachedSeriesMap[k].is_default = (String(k) === String(id));
    });
    renderAttachedSeriesList();
}
 
function removeAttachedSeries(id) {
    delete attachedSeriesMap[id];
    renderAttachedSeriesList();
    syncDefaultSeriesDropdown();
}
 
/**
 * Render the list of attached series under "Transaction Number Series"
 */
function renderAttachedSeriesList() {
    const container = document.getElementById('attached-series-list');
    if (!container) return;
 
    const items = Object.values(attachedSeriesMap);
    if (items.length === 0) {
        container.innerHTML = '<div style="font-size:12px;color:#aaa;margin-top:6px;">No series attached yet.</div>';
        return;
    }
 
    container.innerHTML = items.map(s => `
        <div style="display:flex;align-items:center;gap:8px;margin-top:6px;padding:6px 10px;
                    border:1px solid #dde1e7;border-radius:4px;background:#fafafa;">
            <input type="radio" name="default_series" ${s.is_default ? 'checked' : ''}
                   onchange="setDefaultSeries(${s.id})" title="Set as default">
            <span style="flex:1;font-size:13px;">${s.name}</span>
            ${s.is_default ? '<span style="font-size:11px;color:#2e7d32;font-weight:600;">Default</span>' : ''}
            <button onclick="removeAttachedSeries(${s.id})" title="Remove"
                    style="border:none;background:none;cursor:pointer;color:#e74c3c;font-size:14px;line-height:1;">✕</button>
        </div>
    `).join('');
}
 
/**
 * Sync the "Default Transaction Number Series" dropdown
 */
function syncDefaultSeriesDropdown() {
    const sel  = document.getElementById('default-trans-series-select');
    if (!sel) return;
    const prev = sel.value;
    sel.innerHTML = '<option value="">— Select Default Series —</option>';
    Object.values(attachedSeriesMap).forEach(s => {
        const o = new Option(s.name, s.id);
        sel.appendChild(o);
    });
    sel.value = prev || Object.values(attachedSeriesMap).find(s => s.is_default)?.id || '';
}
 
// ── Save Location ──────────────────────────────────
function saveLocation() {
    const name = document.getElementById('location-name').value.trim();
    if (!name) { showAlert('Location name is required', 'error'); return; }
 
    const payload = {
        location_name: name,
        location_type: currentLocationType,
        address_details: {
            attention: document.querySelector('[name="attention"]').value,
            street1:   document.querySelector('[name="street1"]').value,
            street2:   document.querySelector('[name="street2"]').value,
            city:      document.querySelector('[name="city"]').value,
            pincode:   document.querySelector('[name="pincode"]').value,
            state:     document.querySelector('[name="state"]').value,
            country:   document.querySelector('[name="country"]').value,
            phone:     document.querySelector('[name="phone"]').value,
            fax:       document.querySelector('[name="fax"]').value,
            website:   document.querySelector('[name="website"]').value,
        },
        // ⭐ Transaction series
        transaction_series_ids: Object.keys(attachedSeriesMap).map(Number),
        default_series_id: Object.values(attachedSeriesMap).find(s => s.is_default)?.id ?? null,
    };
 
    // Warehouse child
    if (currentLocationType === 'warehouse') {
        payload.is_child           = document.getElementById('is-child').checked;
        payload.parent_location_id = document.getElementById('parent-location-select').value || null;
    }
 
    const url    = currentEditId ? `/locations/${currentEditId}` : '/locations';
    const method = currentEditId ? 'PUT' : 'POST';
 
    fetch(url, {
        method,
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message ?? 'Saved successfully', 'success');
            closeDrawer();
            setTimeout(() => location.reload(), 800);
        } else {
            showAlert(data.message ?? 'Failed to save', 'error');
        }
    })
    .catch(() => showAlert('Server error. Please try again.', 'error'));
}
 
// ── Delete ─────────────────────────────────────────
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
    fetch(`/locations/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        closeDeleteModal();
        if (data.success) {
            const row = document.getElementById(`row-${deleteTargetId}`);
            if (row) row.remove();
            showAlert('Location deleted successfully', 'success');
        } else {
            showAlert(data.message ?? 'Failed to delete', 'error');
        }
    })
    .catch(() => showAlert('Server error', 'error'));
}
 
// ── Transaction Series Modal ───────────────────────
function openTransSeriesModal() {
    document.getElementById('series-name').value = '';
    document.getElementById('series-name').style.borderColor = '';
 
    const tbody = document.getElementById('trans-series-tbody');
    tbody.innerHTML = transModules.map((m, i) => `
        <tr>
            <td>${m.name}</td>
            <td><input type="text" value="${m.prefix}" id="prefix-${i}" oninput="updatePreview(${i})"></td>
            <td><input type="text" value="${m.start}"  id="start-${i}"  oninput="updatePreview(${i})"></td>
            <td><span class="preview-badge" id="preview-${i}">${m.prefix}${m.start}</span></td>
        </tr>
    `).join('');
 
    document.getElementById('trans-series-modal').classList.add('active');
}
 
function updatePreview(i) {
    const prefix = document.getElementById(`prefix-${i}`).value;
    const start  = document.getElementById(`start-${i}`).value;
    document.getElementById(`preview-${i}`).textContent = prefix + start;
}
 
function closeTransSeriesModal() {
    document.getElementById('trans-series-modal').classList.remove('active');
}
 
function saveTransSeries() {
    const name = document.getElementById('series-name').value.trim();
    if (!name) {
        document.getElementById('series-name').focus();
        document.getElementById('series-name').style.borderColor = 'red';
        return;
    }
 
    const series = transModules.map((m, i) => ({
        module: m.name,
        prefix: document.getElementById(`prefix-${i}`)?.value ?? m.prefix,
        start:  document.getElementById(`start-${i}`)?.value  ?? m.start,
    }));
 
    fetch('/transaction-series', {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, series })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeTransSeriesModal();
            showAlert('Transaction series saved!', 'success');
 
            // ⭐ Add to dropdown
            const opt = new Option(name, data.id);
            document.getElementById('trans-series-select').appendChild(opt);
 
            // ⭐ Auto-attach to current location drawer
            attachedSeriesMap[data.id] = {
                id:         data.id,
                name:       name,
                is_default: Object.keys(attachedSeriesMap).length === 0,
            };
            renderAttachedSeriesList();
            syncDefaultSeriesDropdown();
        } else {
            showAlert(data.message ?? 'Failed to save series', 'error');
        }
    })
    .catch(() => showAlert('Failed to save series', 'error'));
}
 
// ── Alert Helper ───────────────────────────────────
function showAlert(msg, type) {
    const el = document.getElementById('js-alert');
    el.className = `alert alert-${type} show`;
    el.textContent = (type === 'success' ? '✓ ' : '✕ ') + msg;
    setTimeout(() => el.classList.remove('show'), 4000);
}
</script>
@endpush