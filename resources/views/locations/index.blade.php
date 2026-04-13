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

    .settings-wrapper {
        display: flex;
        height: 100vh;
        overflow: hidden;
        background: var(--zoho-white);
    }

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

    .sidebar-title    { font-size: 14px; font-weight: 600; }
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
    }

    .settings-content {
        flex: 1;
        overflow-y: auto;
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

    .type-business  { background: #E8F5E9; color: #2E7D32; }
    .type-warehouse { background: #E3F2FD; color: #1565C0; }

    .location-status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .dot-active   { background: var(--zoho-green); }
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

    .icon-btn:hover        { background: var(--zoho-row-hover); border-color: var(--zoho-blue); color: var(--zoho-blue); }
    .icon-btn.delete:hover { background: #FEE; border-color: var(--zoho-red); color: var(--zoho-red); }

    /* ── Drawer ── */
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
        to   { transform: translateX(0); }
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

    .type-card:hover    { border-color: var(--zoho-blue); background: #F0F4FF; }
    .type-card.selected { border-color: var(--zoho-blue); background: #EEF4FF; }

    .type-card input[type="radio"] {
        position: absolute;
        top: 12px; left: 12px;
        accent-color: var(--zoho-blue);
    }

    .type-card-content { padding-left: 22px; }
    .type-card-title   { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    .type-card-desc    { font-size: 11px; color: var(--zoho-text-muted); line-height: 1.5; }

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
    .address-grid { display: flex; flex-direction: column; gap: 8px; }

    /* ── Logo UI ── */
    .logo-ui-wrap { display: flex; align-items: flex-start; gap: 14px; }

    .logo-thumb {
        width: 80px; height: 60px;
        border: 1px solid var(--zoho-border);
        border-radius: 6px;
        background: #f8f9fa;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; flex-shrink: 0; position: relative;
    }

    .logo-thumb .logo-org-img {
        max-width: 100%; max-height: 100%;
        object-fit: contain; display: block;
        opacity: 1; transition: opacity 0.2s;
    }

    .logo-thumb .logo-location-img {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: contain; display: none; background: #fff;
    }

    .logo-thumb.has-location-logo .logo-location-img { display: block; }
    .logo-thumb.has-location-logo .logo-org-img      { opacity: 0.15; }

    .logo-ui-right { flex: 1; }

    .logo-current-label {
        font-size: 11px; color: var(--zoho-text-muted);
        margin-bottom: 7px; line-height: 1.4;
    }

    .logo-org-tag {
        display: inline-flex; align-items: center; gap: 4px;
        background: #EEF4FF; border: 1px solid #C3D8FF;
        color: var(--zoho-blue); font-size: 11px; font-weight: 500;
        padding: 2px 8px; border-radius: 20px; margin-bottom: 8px;
    }

    .btn-upload-logo {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px;
        border: 1px dashed var(--zoho-blue);
        border-radius: 4px; background: #F0F6FF;
        color: var(--zoho-blue); font-size: 12px; font-weight: 500;
        cursor: pointer; transition: all 0.15s; white-space: nowrap;
    }

    .btn-upload-logo:hover { background: #dbeafe; border-style: solid; }

    .logo-file-row {
        display: none; align-items: center; gap: 8px;
        margin-top: 6px; font-size: 11px; color: var(--zoho-text-muted);
    }

    .logo-file-row.show { display: flex; }

    .logo-remove-btn {
        background: none; border: none; color: var(--zoho-red);
        font-size: 11px; cursor: pointer; padding: 0;
        display: flex; align-items: center; gap: 2px;
    }

    /* ── Trans Series saved badge ── */
    .series-saved-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #E8F5E9;
        border: 1px solid #A5D6A7;
        color: #2E7D32;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 4px;
        margin-top: 6px;
    }

    .series-saved-badge .badge-change {
        color: var(--zoho-blue);
        cursor: pointer;
        font-size: 11px;
        text-decoration: underline;
        background: none;
        border: none;
        padding: 0;
    }

    /* ── Trans Table ── */
    .trans-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--zoho-border);
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
        color: var(--zoho-text);
        background: var(--zoho-white);
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

    .btn-gen-series {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border: 1px dashed var(--zoho-blue);
        border-radius: 4px;
        background: #F0F6FF;
        color: var(--zoho-blue);
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s;
    }

    .btn-gen-series:hover { background: #dbeafe; }

    /* ── Loading spinner ── */
    .btn-spinner {
        display: inline-block;
        width: 12px; height: 12px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        vertical-align: middle;
        margin-right: 4px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Generate Series Modal ── */
    .gen-series-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 5000;
        align-items: center;
        justify-content: center;
    }

    .gen-series-modal.active { display: flex; }

    .gen-series-box {
        background: var(--zoho-white);
        border-radius: 8px;
        width: 740px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        display: flex;
        flex-direction: column;
    }

    .gen-series-header {
        padding: 16px 22px;
        border-bottom: 1px solid var(--zoho-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: var(--zoho-white);
        z-index: 2;
    }

    .gen-series-title { font-size: 15px; font-weight: 600; }
    .gen-series-body { padding: 20px 22px; flex: 1; }

    .attr-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--zoho-border);
        font-size: 12px;
        border-radius: 6px;
        overflow: hidden;
    }

    .attr-table th {
        background: var(--zoho-gray-bg);
        padding: 9px 10px;
        text-align: left;
        font-weight: 600;
        font-size: 11px;
        color: var(--zoho-text-muted);
        border-bottom: 1px solid var(--zoho-border);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .attr-table td {
        padding: 9px 10px;
        border-bottom: 1px solid var(--zoho-border);
        vertical-align: middle;
    }

    .attr-table tr:last-child td { border-bottom: none; }

    .attr-select, .attr-input {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid var(--zoho-input-border);
        border-radius: 4px;
        font-size: 12px;
        outline: none;
        color: var(--zoho-text);
        background: var(--zoho-white);
        cursor: pointer;
    }

    .attr-select:focus, .attr-input:focus { border-color: var(--zoho-blue); }

    .show-col { display: flex; align-items: center; gap: 5px; }
    .show-col select { width: 72px; }
    .show-col input  { width: 48px; text-align: center; }

    .sep-col { display: flex; align-items: center; gap: 4px; }
    .sep-col select { width: 68px; }

    .icon-btn-x {
        width: 24px; height: 24px;
        border: none; background: none;
        cursor: pointer; color: var(--zoho-text-muted);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; border-radius: 3px;
    }

    .icon-btn-x:hover { color: var(--zoho-blue); background: var(--zoho-row-hover); }

    .icon-btn-del {
        width: 26px; height: 26px;
        border: 1px solid var(--zoho-border);
        border-radius: 50%;
        background: var(--zoho-white);
        cursor: pointer;
        color: var(--zoho-text-muted);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        transition: all 0.15s;
    }

    .icon-btn-del:hover {
        border-color: var(--zoho-red);
        color: var(--zoho-red);
        background: #FEE;
    }

    .btn-add-attr-row {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border: 1px dashed var(--zoho-border);
        border-radius: 4px;
        background: none;
        color: var(--zoho-blue);
        font-size: 12px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.15s;
    }

    .btn-add-attr-row:hover { background: var(--zoho-row-hover); border-color: var(--zoho-blue); }

    .sku-preview-box {
        background: #FFFBEC;
        border: 1px dashed #D4A017;
        border-radius: 6px;
        padding: 14px 20px;
        text-align: center;
        margin-top: 18px;
    }

    .sku-preview-label {
        font-size: 11px;
        color: var(--zoho-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .sku-preview-value {
        font-size: 20px;
        font-weight: 600;
        color: var(--zoho-text);
        font-family: monospace;
        letter-spacing: 1px;
    }

    .gen-series-footer {
        padding: 12px 22px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        position: sticky;
        bottom: 0;
        background: var(--zoho-white);
    }

    /* ── Access Box ── */
    .access-box { border: 1px solid var(--zoho-border); border-radius: 4px; overflow: hidden; }

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

    .drawer-footer {
        padding: 14px 24px;
        border-top: 1px solid var(--zoho-border);
        display: flex;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: var(--zoho-white);
    }

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

    .confirm-title   { font-size: 15px; font-weight: 600; margin-bottom: 8px; }
    .confirm-text    { font-size: 13px; color: var(--zoho-text-muted); margin-bottom: 20px; line-height: 1.5; }
    .confirm-actions { display: flex; gap: 10px; justify-content: flex-end; }

    /* ── Transaction Series Modal ── */
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
        width: 720px;
        max-height: 82vh;
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
        z-index: 2;
    }

    .trans-series-title { font-size: 15px; font-weight: 600; }
    .trans-series-body  { padding: 20px 24px; }

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
        position: sticky;
        bottom: 0;
        background: var(--zoho-white);
    }

    .alert {
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 16px;
        display: none;
        align-items: center;
        gap: 8px;
    }

    .alert.show    { display: flex; }
    .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .alert-error   { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--zoho-text-muted); }
    .empty-icon  { font-size: 48px; margin-bottom: 12px; }
    .empty-title { font-size: 15px; font-weight: 600; color: var(--zoho-text); margin-bottom: 6px; }
    .empty-text  { font-size: 13px; }
</style>
@endpush

@section('content')
<div class="settings-wrapper">

    {{-- ── Sidebar ── --}}
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

        <div style="padding: 4px 0;">
            <a class="sidebar-nav-item" href="#">▾ Organization</a>
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

        <div class="sidebar-section-label" style="margin-top:8px;">Module Settings</div>
        <a class="sidebar-nav-item" href="#">▸ Items</a>
        <a class="sidebar-nav-item" href="#">▸ Sales</a>
        <a class="sidebar-nav-item" href="#">▸ Purchases</a>
        <a class="sidebar-nav-item" href="#">▸ Inventory</a>
    </div>

    {{-- ── Main Content ── --}}
    <div class="settings-content">
        <div class="content-topbar">
            <span style="font-size:13px; color:var(--zoho-text-muted);">Search settings</span>
            <button class="close-settings-btn" onclick="window.history.back()">Close Settings ✕</button>
        </div>

        <div class="locations-page">
            <h2 class="page-title">Locations</h2>

            @if(session('success'))
                <div class="alert alert-success show">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error show">✕ {{ session('error') }}</div>
            @endif

            <div id="js-alert" class="alert"></div>

            <div class="locations-toolbar">
                <div style="font-size:13px; color:var(--zoho-text-muted);">
                    Manage your business and warehouse locations
                </div>
                <div style="display:flex; gap:12px; align-items:center;">
                    <a href="{{ route('transaction-series.index') }}"
                       class="btn-link"
                       style="font-size:13px;">
                        Transaction Series Preferences
                    </a>
                    <button class="btn-primary" onclick="openAddDrawer()">+ New Location</button>
                </div>
            </div>

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
                        <td style="color:var(--zoho-text-muted); font-size:12px;">
                            @if($location->address_details)
                                {{ $location->address_details['city'] ?? '' }}
                                @if($location->address_details['state'] ?? '')
                                    , {{ $location->address_details['state'] }}
                                @endif
                            @else —
                            @endif
                        </td>
                        <td>
                            <span class="location-status-dot dot-active"></span> Active
                        </td>
                        <td>
                            <div class="action-icons">
                                <button class="icon-btn" onclick="openEditDrawer({{ $location->id }})" title="Edit">✎</button>
                                <button class="icon-btn delete" onclick="openDeleteConfirm({{ $location->id }}, '{{ $location->location_name }}')" title="Delete">🗑</button>
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

{{-- ═══════════════════════════════════════
     LOCATION DRAWER
═══════════════════════════════════════ --}}
<div class="modal-overlay" id="location-drawer-overlay" onclick="closeDrawerOnOverlay(event)">
    <div class="side-drawer">

        <div class="drawer-header">
            <span class="drawer-title" id="drawer-title">Add Location</span>
            <button class="drawer-close" onclick="closeDrawer()">✕</button>
        </div>

        <div class="drawer-body">

            <div class="form-section-title">Location Type</div>
            <div class="location-type-cards">
                <div class="type-card selected" id="card-business" onclick="selectLocationType('business')">
                    <input type="radio" name="location_type" value="business" id="type-business" checked>
                    <div class="type-card-content">
                        <div class="type-card-title">Business Location</div>
                        <div class="type-card-desc">Represents your organization's operational location.</div>
                    </div>
                </div>
                <div class="type-card" id="card-warehouse" onclick="selectLocationType('warehouse')">
                    <input type="radio" name="location_type" value="warehouse" id="type-warehouse">
                    <div class="type-card-content">
                        <div class="type-card-title">Warehouse Only Location</div>
                        <div class="type-card-desc">Refers to where your items are stored.</div>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div id="logo-section">
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <div>
                        <div class="logo-ui-wrap">
                            <div class="logo-thumb" id="logo-thumb">
                                <img class="logo-org-img" id="logo-org-img"
                                    src="{{ $orgLogoUrl ?? asset('images/org_logo.png') }}"
                                    alt="Organization Logo"
                                    onerror="this.style.display='none'; document.getElementById('logo-org-fallback').style.display='flex';">
                                <span id="logo-org-fallback" style="display:none; font-size:24px; color:#ccc;">🏢</span>
                                <img class="logo-location-img" id="logo-location-img" src="" alt="Location Logo">
                            </div>
                            <div class="logo-ui-right">
                                <div id="logo-org-tag" class="logo-org-tag">🏢 Using Organization Logo</div>
                                <div class="logo-current-label" id="logo-current-label" style="display:none;"></div>
                                <button type="button" class="btn-upload-logo"
                                        onclick="document.getElementById('logo-file-input').click()">
                                    📤 Upload as Logo
                                </button>
                                <input type="file" id="logo-file-input"
                                       accept="image/png,image/jpeg,image/webp"
                                       style="display:none"
                                       onchange="handleLogoFileSelect(this)">
                            </div>
                        </div>
                        <div class="logo-file-row" id="logo-file-row">
                            <span style="font-size:12px;">📎</span>
                            <span id="logo-file-name-span" style="max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></span>
                            <button type="button" class="logo-remove-btn" onclick="clearLogoUpload()">✕ Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Name</label>
                <input type="text" class="form-control" id="location-name" name="location_name" placeholder="Location Name">
            </div>

            <div class="form-group" id="child-location-row" style="display:none;">
                <label class="form-label"></label>
                <label class="checkbox-label">
                    <input type="checkbox" id="is-child" onchange="toggleParentLocation()">
                    This is a Child Location
                </label>
            </div>

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

            <div class="form-section-title">Address</div>
            <div class="address-grid">
                <input type="text" class="form-control" name="attention" placeholder="Attention">
                <input type="text" class="form-control" name="street1"   placeholder="Street 1">
                <input type="text" class="form-control" name="street2"   placeholder="Street 2">
                <div class="form-row-2">
                    <input type="text" class="form-control" name="city"    placeholder="City">
                    <input type="text" class="form-control" name="pincode" placeholder="Pin Code">
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
                <input type="text" class="form-control" name="fax"     placeholder="Fax Number">
                <input type="text" class="form-control" name="website" placeholder="Website URL">
            </div>

            <div id="business-only-fields">
                <div class="form-section-title">Additional Info</div>
                <div class="form-group">
                    <label class="form-label required">Primary Contact</label>
                    <select class="form-control form-control-select" name="primary_contact">
                        <option>{{ auth()->user()->name ?? 'Admin' }}</option>
                    </select>
                </div>

                {{-- ══ TRANSACTION NUMBER SERIES ══ --}}
                <div class="form-group">
                    <label class="form-label required">Transaction Number Series</label>
                    <div id="trans-series-field-wrap">
                        {{-- Default: show dropdown --}}
                        <div id="trans-series-select-wrap">
                            <select class="form-control form-control-select" id="trans-series-select">
                                <option value="">— Select Series —</option>
                                @foreach($transactionSeries ?? [] as $series)
                                    <option value="{{ $series->id }}">{{ $series->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-link" style="margin-top:6px; font-size:12px;" onclick="openTransSeriesModal()">
                                + Create New Transaction Series
                            </button>
                        </div>

                        {{-- After series created: show badge --}}
                        <div id="trans-series-saved-wrap" style="display:none;">
                            <div class="series-saved-badge">
                                ✓ <span id="trans-series-saved-name"></span>
                                <button type="button" class="badge-change" onclick="changeTransSeries()">Change</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Default Transaction Number Series</label>
                    <select class="form-control form-control-select" id="default-trans-series-select">
                        <option value="">— Select Default Series —</option>
                        @foreach($transactionSeries ?? [] as $series)
                            <option value="{{ $series->id }}">{{ $series->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-section-title">Location Access</div>
                <div class="access-box">
                    <div class="access-header">
                        <div>
                            <span class="access-dot"></span>
                            <strong>1 user(s) selected</strong>
                            <div style="font-size:11px; color:var(--zoho-text-muted); margin-left:14px;">
                                Selected users can create and access transactions for this location.
                            </div>
                        </div>
                        <label class="checkbox-label">
                            <input type="checkbox"> Provide access to all users
                        </label>
                    </div>
                    <table class="access-users-table">
                        <thead>
                            <tr><th>Users</th><th>Role</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="user-avatar">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                    </span>
                                    <div style="display:inline-block; vertical-align:middle;">
                                        <div style="font-weight:500;">{{ auth()->user()->name ?? 'Admin' }}</div>
                                        <div style="font-size:11px; color:var(--zoho-text-muted);">{{ auth()->user()->email ?? '' }}</div>
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

        </div>

        <div class="drawer-footer">
            <button class="btn-primary" id="save-location-btn" onclick="saveLocation()">Save</button>
            <button class="btn-secondary" onclick="closeDrawer()">Cancel</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     DELETE CONFIRM MODAL
═══════════════════════════════════════ --}}
<div class="confirm-modal" id="delete-modal">
    <div class="confirm-box">
        <div class="confirm-title">Delete Location</div>
        <div class="confirm-text" id="delete-confirm-text">
            Are you sure you want to delete this location?<br>This action cannot be undone.
        </div>
        <div class="confirm-actions">
            <button class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger"    onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     TRANSACTION SERIES MODAL
     — Saves directly to DB on "Save"
═══════════════════════════════════════ --}}
<div class="trans-series-modal" id="trans-series-modal">
    <div class="trans-series-box">
        <div class="trans-series-header">
            <span class="trans-series-title">Create Transaction Series</span>
            <button class="drawer-close" onclick="closeTransSeriesModal()">✕</button>
        </div>
        <div class="trans-series-body">
            <div class="series-name-group">
                <label>Series Name *</label>
                <input type="text" class="form-control" id="series-name"
                       placeholder="e.g. Default Transaction Series" style="max-width:300px;">
            </div>
            <table class="trans-table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Prefix</th>
                        <th>Starting Number</th>
                        <th>Preview</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="trans-series-tbody"></tbody>
            </table>
        </div>
        <div class="trans-series-footer">
            <button class="btn-primary" id="save-series-btn" onclick="saveTransSeriesDirectly()">Save Series</button>
            <button class="btn-secondary" onclick="closeTransSeriesModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     GENERATE SERIES FORMAT MODAL
═══════════════════════════════════════ --}}
<div id="gen-series-modal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55);
            z-index:5000; align-items:flex-start; justify-content:center; padding-top:60px;">
    <div style="background:#fff; border-radius:8px; width:900px; max-width:96vw;
                max-height:85vh; display:flex; flex-direction:column;
                box-shadow:0 8px 32px rgba(0,0,0,0.2); overflow:hidden;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:16px 24px; border-bottom:1px solid #eee; flex-shrink:0;">
            <span id="gen-series-title" style="font-size:16px; font-weight:700; color:#222;">
                Generate Series
            </span>
            <button onclick="closeGenSeriesModal()"
                    style="background:none; border:none; font-size:22px;
                           color:#888; cursor:pointer; font-weight:700;">✕</button>
        </div>

        {{-- Body --}}
        <div style="flex:1; overflow-y:auto; padding:24px;">
            <p style="font-size:13px; color:#666; margin-bottom:20px;">
                Select attributes to generate the series format
            </p>

            {{-- Rows Table --}}
            <table style="width:100%; border-collapse:collapse; border:1px solid #e2e8f0;">
                <thead>
                    <tr style="background:#f8fafd;">
                        <th style="padding:10px 12px; font-size:11px; font-weight:700; color:#4a5568;
                                   text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0;
                                   text-align:left; width:28%;">Select Attribute</th>
                        <th style="padding:10px 12px; font-size:11px; font-weight:700; color:#4a5568;
                                   text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0;
                                   text-align:left; width:22%;">Show</th>
                        <th style="padding:10px 12px; font-size:11px; font-weight:700; color:#4a5568;
                                   text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0;
                                   text-align:left; width:24%;">Letter Case</th>
                        <th style="padding:10px 12px; font-size:11px; font-weight:700; color:#4a5568;
                                   text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0;
                                   text-align:left; width:18%;">Separator</th>
                        <th style="width:8%; border-bottom:2px solid #e2e8f0;"></th>
                    </tr>
                </thead>
                <tbody id="gen-series-tbody"></tbody>
            </table>

            <button type="button" onclick="addGenRow()"
                    style="display:inline-flex; align-items:center; gap:5px; color:#0073E6;
                           font-size:13px; font-weight:500; cursor:pointer; border:none;
                           background:none; padding:8px 0; margin-top:8px;">
                + Add Attribute
            </button>

            {{-- Preview --}}
            <div style="margin-top:20px;">
                <div style="font-size:12px; font-weight:700; color:#555; margin-bottom:8px;">
                    Series Preview
                </div>
                <div id="gen-preview-value"
                     style="background:#fffbea; border:2px dashed #f0c040; border-radius:6px;
                            padding:20px; text-align:center; font-size:18px; font-weight:700;
                            color:#333; letter-spacing:2px; min-height:60px;
                            display:flex; align-items:center; justify-content:center;">
                    —
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:14px 24px; border-top:1px solid #eee;
                    display:flex; gap:10px; flex-shrink:0;">
            <button onclick="applyGenSeries()"
                    style="background:#0073E6; color:#fff; border:none; border-radius:6px;
                           padding:9px 24px; font-weight:700; font-size:13px; cursor:pointer;">
                Apply
            </button>
            <button onclick="closeGenSeriesModal()"
                    style="background:#fff; color:#555; border:1px solid #dde1e7;
                           border-radius:6px; padding:9px 18px; font-size:13px; cursor:pointer;">
                Cancel
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════════════════
var currentEditId         = null;
var deleteTargetId        = null;
var currentLocationType   = 'business';
var savedTransSeriesId    = null;   // ✅ Stores the DB-saved series ID
var savedTransSeriesName  = '';     // ✅ Stores the series name for display

// Logo state
var logoFileBase64 = null;
var logoFileName   = null;
var logoMimeType   = null;
var logoRemoved    = false;

// Transaction modules configuration
var transModules = [
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

var transModuleDefaults = [
    { prefix: 'CN-',  start: '00001'  },
    { prefix: '',     start: '1'      },
    { prefix: 'PO-',  start: '00001'  },
    { prefix: 'SO-',  start: '00001'  },
    { prefix: '',     start: '1'      },
    { prefix: 'RET-', start: '00001'  },
    { prefix: 'BOS-', start: '000001' },
    { prefix: 'INV-', start: '000001' },
    { prefix: 'RMA-', start: '00001'  },
    { prefix: 'DC-',  start: '00001'  },
];

// Generate Series modal state
var genTargetModuleIndex = null;
var genRows = [];

var GEN_CASES = ['Upper Case', 'Lower Case', 'As Entered'];
var GEN_SEPS  = ['-', '_', '.', '/', '(none)'];

// ═══════════════════════════════════════════════════════
//  DRAWER — OPEN / CLOSE
// ═══════════════════════════════════════════════════════
function openAddDrawer() {
    currentEditId        = null;
    savedTransSeriesId   = null;
    savedTransSeriesName = '';

    document.getElementById('drawer-title').textContent = 'Add Location';
    document.getElementById('location-name').value = '';

    resetAddressFields();
    resetLogoUI();
    resetTransSeriesField();
    selectLocationType('business');

    // Load existing series into dropdown
    loadExistingSeriesIntoSelect('trans-series-select');
    loadExistingSeriesIntoSelect('default-trans-series-select');

    document.getElementById('location-drawer-overlay').classList.add('active');
}

function loadExistingSeriesIntoSelect(selectId) {
    var select = document.getElementById(selectId);
    if (!select) return;

    // Keep first placeholder option, remove rest
    while (select.options.length > 1) {
        select.remove(1);
    }

    fetch('/transaction-series/list', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.series && data.series.length) {
            data.series.forEach(function(series) {
                var opt = new Option(series.name, series.id);
                select.appendChild(opt);
            });
        }
    })
    .catch(function(err) { console.error('Error loading series:', err); });
}

function openEditDrawer(id) {
    currentEditId        = id;
    savedTransSeriesId   = null;
    savedTransSeriesName = '';

    document.getElementById('drawer-title').textContent = 'Edit Location';

    fetch('/locations/' + id + '/edit', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        document.getElementById('location-name').value = data.location_name || '';
        resetLogoUI();
        resetTransSeriesField();
        selectLocationType(data.location_type || 'business');

        if (data.location_type === 'warehouse') {
            var isChild = data.is_child || false;
            document.getElementById('is-child').checked = isChild;
            if (isChild && data.parent_location_id) {
                document.getElementById('parent-location-row').style.display = '';
                document.getElementById('parent-location-select').value = data.parent_location_id;
            }
        }

        var addr = data.address_details || {};
        document.querySelector('[name="attention"]').value = addr.attention || '';
        document.querySelector('[name="street1"]').value   = addr.street1   || '';
        document.querySelector('[name="street2"]').value   = addr.street2   || '';
        document.querySelector('[name="city"]').value      = addr.city      || '';
        document.querySelector('[name="pincode"]').value   = addr.pincode   || '';
        document.querySelector('[name="phone"]').value     = addr.phone     || '';
        document.querySelector('[name="fax"]').value       = addr.fax       || '';
        document.querySelector('[name="website"]').value   = addr.website   || '';

        var imgData = (data.additional_data && data.additional_data.location_image)
            ? data.additional_data.location_image : null;
        if (imgData && imgData.url) {
            _showLocationLogoPreview(imgData.url, imgData.file_name || 'Uploaded Logo');
        }

        // Load series dropdown & pre-select if location has one
       // Load series dropdown & pre-select if location has one
loadExistingSeriesIntoSelect('trans-series-select');
loadExistingSeriesIntoSelect('default-trans-series-select');

if (data.transaction_series_id && data.transaction_series_name) {
    // ✅ Badge-ஆ காட்டு — series name-உம் வரும்
    showSeriesSavedBadge(data.transaction_series_id, data.transaction_series_name);
} else if (data.transaction_series_id) {
    // Name இல்லன்னா dropdown-ல் select பண்ணு
    setTimeout(function() {
        var sel = document.getElementById('trans-series-select');
        if (sel) sel.value = data.transaction_series_id;
    }, 300);
}

        document.getElementById('location-drawer-overlay').classList.add('active');
    })
    .catch(function() { showAlert('Failed to load location data', 'error'); });
}

function closeDrawer() {
    document.getElementById('location-drawer-overlay').classList.remove('active');
}

function closeDrawerOnOverlay(e) {
    if (e.target === document.getElementById('location-drawer-overlay')) {
        closeDrawer();
    }
}

// ═══════════════════════════════════════════════════════
//  TRANSACTION SERIES FIELD HELPERS
// ═══════════════════════════════════════════════════════
function resetTransSeriesField() {
    savedTransSeriesId   = null;
    savedTransSeriesName = '';
    document.getElementById('trans-series-select-wrap').style.display  = '';
    document.getElementById('trans-series-saved-wrap').style.display   = 'none';
    document.getElementById('trans-series-saved-name').textContent     = '';
    var sel = document.getElementById('trans-series-select');
    if (sel) sel.value = '';
}

function showSeriesSavedBadge(id, name) {
    savedTransSeriesId   = id;
    savedTransSeriesName = name;
    document.getElementById('trans-series-select-wrap').style.display  = 'none';
    document.getElementById('trans-series-saved-wrap').style.display   = '';
    document.getElementById('trans-series-saved-name').textContent     = name;

    // Also add to default dropdown
    var defSel = document.getElementById('default-trans-series-select');
    if (defSel) {
        // Check if option already exists
        var exists = false;
        for (var i = 0; i < defSel.options.length; i++) {
            if (defSel.options[i].value == id) { exists = true; break; }
        }
        if (!exists) {
            var opt = new Option(name, id);
            defSel.appendChild(opt);
        }
        defSel.value = id;
    }
}

function changeTransSeries() {
    savedTransSeriesId   = null;
    savedTransSeriesName = '';
    document.getElementById('trans-series-select-wrap').style.display = '';
    document.getElementById('trans-series-saved-wrap').style.display  = 'none';
    loadExistingSeriesIntoSelect('trans-series-select');
}

// ═══════════════════════════════════════════════════════
//  LOCATION TYPE SELECTION
// ═══════════════════════════════════════════════════════
function selectLocationType(type) {
    currentLocationType = type;

    document.getElementById('card-business').classList.toggle('selected', type === 'business');
    document.getElementById('card-warehouse').classList.toggle('selected', type === 'warehouse');
    document.getElementById('type-business').checked  = (type === 'business');
    document.getElementById('type-warehouse').checked = (type === 'warehouse');

    document.getElementById('logo-section').style.display         = (type === 'business') ? '' : 'none';
    document.getElementById('business-only-fields').style.display = (type === 'business') ? '' : 'none';
    document.getElementById('child-location-row').style.display   = (type === 'warehouse') ? 'grid' : 'none';

    if (type === 'business') {
        document.getElementById('is-child').checked = false;
        document.getElementById('parent-location-row').style.display = 'none';
    }
}

function toggleParentLocation() {
    document.getElementById('parent-location-row').style.display =
        document.getElementById('is-child').checked ? '' : 'none';
}

// ═══════════════════════════════════════════════════════
//  LOGO HANDLING
// ═══════════════════════════════════════════════════════
function handleLogoFileSelect(input) {
    if (input.files && input.files[0]) {
        processLogoFile(input.files[0]);
    }
    input.value = '';
}

function processLogoFile(file) {
    var allowed = ['image/png', 'image/jpeg', 'image/webp'];
    if (allowed.indexOf(file.type) === -1) {
        showAlert('Only PNG, JPG, WEBP images are allowed', 'error');
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        showAlert('Image must be less than 2 MB', 'error');
        return;
    }
    logoFileName = file.name;
    logoMimeType = file.type;
    logoRemoved  = false;

    var reader = new FileReader();
    reader.onload = function(e) {
        logoFileBase64 = e.target.result;
        _showLocationLogoPreview(logoFileBase64, file.name);
    };
    reader.readAsDataURL(file);
}

function _showLocationLogoPreview(src, name) {
    var thumb    = document.getElementById('logo-thumb');
    var locImg   = document.getElementById('logo-location-img');
    var orgTag   = document.getElementById('logo-org-tag');
    var curLabel = document.getElementById('logo-current-label');
    var fileRow  = document.getElementById('logo-file-row');
    var fileName = document.getElementById('logo-file-name-span');

    locImg.src = src;
    thumb.classList.add('has-location-logo');
    orgTag.style.display   = 'none';
    curLabel.style.display = '';
    curLabel.textContent   = name;
    fileName.textContent   = name;
    fileRow.classList.add('show');
}

function clearLogoUpload() {
    logoRemoved = true; logoFileBase64 = null; logoFileName = null; logoMimeType = null;
    _revertToOrgLogoDisplay();
}

function resetLogoUI() {
    logoFileBase64 = null; logoFileName = null; logoMimeType = null;
    _revertToOrgLogoDisplay();
}

function _revertToOrgLogoDisplay() {
    var thumb    = document.getElementById('logo-thumb');
    var locImg   = document.getElementById('logo-location-img');
    var orgTag   = document.getElementById('logo-org-tag');
    var curLabel = document.getElementById('logo-current-label');
    var fileRow  = document.getElementById('logo-file-row');
    var fileName = document.getElementById('logo-file-name-span');

    if (locImg)   { locImg.src = ''; }
    if (thumb)    { thumb.classList.remove('has-location-logo'); }
    if (orgTag)   { orgTag.style.display = ''; }
    if (curLabel) { curLabel.style.display = 'none'; curLabel.textContent = ''; }
    if (fileRow)  { fileRow.classList.remove('show'); }
    if (fileName) { fileName.textContent = ''; }
}

function getSeriesDataFromForm() {
    return transModules.map(function(m, i) {
        var pEl = document.getElementById('prefix-' + i);
        var sEl = document.getElementById('start-' + i);
        return {
            module:          m.name,
            prefix:          pEl ? pEl.value : m.prefix,
            starting_number: sEl ? sEl.value : m.start
        };
    });
}
// ═══════════════════════════════════════════════════════
//  SAVE LOCATION
// ═══════════════════════════════════════════════════════
function saveLocation() {
    var name = document.getElementById('location-name').value.trim();
    if (!name) {
        showAlert('Location name is required', 'error');
        return;
    }

    // ── Resolve series ID ──────────────────────────────
    var transSeriesId = savedTransSeriesId;
    if (!transSeriesId) {
        var sel = document.getElementById('trans-series-select');
        transSeriesId = (sel && sel.value) ? sel.value : null;
    }

    // ── Build payload FIRST ───────────────────────────
    var payload = {
        location_name: name,
        location_type: currentLocationType,
        logo_removed:  logoRemoved,
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
        }
    };

    // ── Attach series to payload ───────────────────────
    if (transSeriesId) {
        payload.transaction_series_id = transSeriesId;
    } else if (currentLocationType === 'business') {
        var seriesNameEl = document.getElementById('series-name');
        var seriesName   = seriesNameEl ? seriesNameEl.value.trim() : '';
        if (seriesName) {
            payload.new_series = {
                name:        seriesName,
                series_data: getSeriesDataFromForm()
            };
        }
    }

    if (logoFileBase64) {
        payload.location_image = {
            data:      logoFileBase64,
            file_name: logoFileName,
            mime_type: logoMimeType
        };
    }

    if (currentLocationType === 'warehouse') {
        payload.is_child = document.getElementById('is-child')
            ? document.getElementById('is-child').checked : false;
        payload.parent_location_id = document.getElementById('parent-location-select')
            ? (document.getElementById('parent-location-select').value || null) : null;
    }

    var url    = currentEditId ? '/locations/' + currentEditId : '/locations';
    var method = currentEditId ? 'PUT' : 'POST';

    var saveBtn = document.getElementById('save-location-btn');
    saveBtn.disabled  = true;
    saveBtn.innerHTML = '<span class="btn-spinner"></span> Saving...';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        saveBtn.disabled  = false;
        saveBtn.innerHTML = 'Save';
        if (data.success) {
            showAlert(data.message || 'Saved successfully', 'success');
            savedTransSeriesId   = null;
            savedTransSeriesName = '';
            logoRemoved          = false;
            closeDrawer();
            setTimeout(function() { location.reload(); }, 800);
        } else {
            showAlert(data.message || 'Failed to save', 'error');
        }
    })
    .catch(function(error) {
        saveBtn.disabled  = false;
        saveBtn.innerHTML = 'Save';
        console.error('Error:', error);
        showAlert('Server error. Please try again.', 'error');
    });
}

// ═══════════════════════════════════════════════════════
//  DELETE
// ═══════════════════════════════════════════════════════
function openDeleteConfirm(id, name) {
    deleteTargetId = id;
    document.getElementById('delete-confirm-text').innerHTML =
        'Are you sure you want to delete <strong>"' + name + '"</strong>?<br>This action cannot be undone.';
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
    deleteTargetId = null;
}

function confirmDelete() {
    if (!deleteTargetId) return;

    fetch('/locations/' + deleteTargetId, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        closeDeleteModal();
        if (data.success) {
            var row = document.getElementById('row-' + deleteTargetId);
            if (row) row.remove();
            showAlert('Location deleted successfully', 'success');
        } else {
            showAlert(data.message || 'Failed to delete', 'error');
        }
    })
    .catch(function() { showAlert('Server error', 'error'); });
}

// ═══════════════════════════════════════════════════════
//  TRANSACTION SERIES MODAL — DIRECT DB SAVE
// ═══════════════════════════════════════════════════════
function openTransSeriesModal() {
    // Reset to defaults
    transModules.forEach(function(m, i) {
        m.prefix = transModuleDefaults[i].prefix;
        m.start  = transModuleDefaults[i].start;
    });

    document.getElementById('series-name').value = '';
    renderTransSeriesRows();
    document.getElementById('trans-series-modal').classList.add('active');
}

function renderTransSeriesRows() {
    var tbody = document.getElementById('trans-series-tbody');
    tbody.innerHTML = transModules.map(function(m, i) {
        return '<tr id="ts-row-' + i + '">' +
            '<td style="font-weight:500;">' + m.name + '</td>' +
            '<td><input type="text" value="' + escHtml(m.prefix) + '" id="prefix-' + i + '" oninput="updateTransPreview(' + i + ')"></td>' +
            '<td><input type="text" value="' + escHtml(m.start)  + '" id="start-'  + i + '" oninput="updateTransPreview(' + i + ')"></td>' +
            '<td><span class="preview-badge" id="preview-' + i + '">' + escHtml(m.prefix + m.start) + '</span></td>' +
            '<td><button class="btn-gen-series" onclick="openGenSeriesModal(' + i + ')">✦ Customize</button></td>' +
            '</tr>';
    }).join('');
}

function updateTransPreview(i) {
    var p = document.getElementById('prefix-' + i).value;
    var s = document.getElementById('start-' + i).value;
    document.getElementById('preview-' + i).textContent = p + s;
    transModules[i].prefix = p;
    transModules[i].start  = s;
}

function closeTransSeriesModal() {
    document.getElementById('trans-series-modal').classList.remove('active');
}

/**
 * ✅ KEY FUNCTION: Save transaction series directly to DB via API.
 * On success: store the returned ID, show badge in drawer, close modal.
 */
function saveTransSeriesDirectly() {
    var name = document.getElementById('series-name').value.trim();
    if (!name) {
        var inp = document.getElementById('series-name');
        inp.focus();
        inp.style.borderColor = '#E74C3C';
        setTimeout(function() { inp.style.borderColor = ''; }, 2000);
        showAlert('Series name is required', 'error');
        return;
    }

    // Sync latest input values
    transModules.forEach(function(m, i) {
        var pEl = document.getElementById('prefix-' + i);
        var sEl = document.getElementById('start-' + i);
        if (pEl) m.prefix = pEl.value;
        if (sEl) m.start  = sEl.value;
    });

    var seriesData = transModules.map(function(m) {
        return {
            module:          m.name,
            prefix:          m.prefix,
            starting_number: m.start
        };
    });

    var saveBtn = document.getElementById('save-series-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="btn-spinner"></span> Saving...';

    // ✅ POST to /transaction-series — saves to transaction_series table
    fetch('/transaction-series', {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name:        name,
            series: seriesData
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Series';

        if (data.success || data.id) {
            var newId   = data.id || data.series_id || data.data.id;
            var newName = data.name || name;

            // Close modal
            closeTransSeriesModal();

            // ✅ Show the saved badge in drawer with the DB id
            showSeriesSavedBadge(newId, newName);

            showAlert('Transaction series "' + newName + '" saved successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to save series', 'error');
        }
    })
    .catch(function(error) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save Series';
        console.error('Error saving series:', error);
        showAlert('Server error saving series. Please try again.', 'error');
    });
}
// ═══════════════════════════════════════════════════════
//  GENERATE SERIES MODAL — create.blade போல் same logic
// ═══════════════════════════════════════════════════════
var _genModuleIdx = null;
var _genRowCount  = 0;

function openGenSeriesModal(moduleIndex) {
    _genModuleIdx = moduleIndex;
    _genRowCount  = 0;

    var mod = transModules[moduleIndex];
    document.getElementById('gen-series-title').textContent = 'Generate Series — ' + mod.name;
    document.getElementById('gen-series-tbody').innerHTML = '';

    // Default 2 rows
    genAddRow('Module Name', 'First', 3, 'Upper Case', '-');
    genAddRow('Custom Text', '',      0, 'Upper Case', '');

    genUpdatePreview();
    document.getElementById('gen-series-modal').style.display = 'flex';
}

function closeGenSeriesModal() {
    document.getElementById('gen-series-modal').style.display = 'none';
    _genModuleIdx = null;
}

function genAddRow(attr, showType, showCount, letterCase, separator) {
    _genRowCount++;
    var n = _genRowCount;

    var attrOptions = ['Module Name', 'Custom Text', 'Series Number', 'Financial Year'];
    var attrHtml = attrOptions.map(function(a) {
        return '<option value="' + a + '"' + (a === (attr || 'Module Name') ? ' selected' : '') + '>' + a + '</option>';
    }).join('');

    var isCustom = (attr === 'Custom Text');

    var showCell = isCustom
        ? '<input type="text" id="gen-custom-' + n + '" placeholder="Custom text" oninput="genUpdatePreview()" style="width:100%; border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; outline:none;">'
        : '<div style="display:flex; align-items:center; gap:4px;">' +
            '<select id="gen-show-type-' + n + '" onchange="genUpdatePreview()" style="border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; outline:none; background:#fff;">' +
                '<option ' + ((showType||'First')==='First'?'selected':'') + '>First</option>' +
                '<option ' + (showType==='Last'?'selected':'') + '>Last</option>' +
            '</select>' +
            '<input type="number" id="gen-show-count-' + n + '" value="' + (showCount||3) + '" min="1" max="20" oninput="genUpdatePreview()" style="width:52px; border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; text-align:center; outline:none;">' +
          '</div>';

    var caseOptions = ['Upper Case', 'Lower Case', 'None'].map(function(c) {
        return '<option' + (c === (letterCase||'Upper Case') ? ' selected' : '') + '>' + c + '</option>';
    }).join('');

    var sepOptions = ['-', '_', '/', '.', ''].map(function(s) {
        return '<option value="' + s + '"' + (s === (separator !== undefined ? separator : '-') ? ' selected' : '') + '>' + (s===''?'(none)':s) + '</option>';
    }).join('');

    var tr = document.createElement('tr');
    tr.id = 'gen-row-' + n;
    tr.style.borderBottom = '1px solid #f0f2f7';
    tr.innerHTML =
        '<td style="padding:8px 12px;">' +
            '<select id="gen-attr-' + n + '" onchange="genAttrChanged(' + n + '); genUpdatePreview()" style="width:100%; border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; outline:none; background:#fff;">' + attrHtml + '</select>' +
        '</td>' +
        '<td style="padding:8px 12px;" id="gen-show-cell-' + n + '">' + showCell + '</td>' +
        '<td style="padding:8px 12px;">' +
            '<div style="display:flex; align-items:center; gap:4px;">' +
                '<select id="gen-case-' + n + '" onchange="genUpdatePreview()" style="flex:1; border:1px solid #0073E6; border-radius:4px; padding:6px 8px; font-size:13px; color:#0073E6; outline:none; background:#fff;">' + caseOptions + '</select>' +
                '<button type="button" onclick="genClearCase(' + n + ')" style="background:none; border:none; color:#E74C3C; font-size:16px; cursor:pointer; padding:0 4px;">✕</button>' +
            '</div>' +
        '</td>' +
        '<td style="padding:8px 12px;">' +
            '<div style="display:flex; align-items:center; gap:4px;">' +
                '<select id="gen-sep-' + n + '" onchange="genUpdatePreview()" style="flex:1; border:1px solid #0073E6; border-radius:4px; padding:6px 8px; font-size:13px; color:#0073E6; outline:none; background:#fff;">' + sepOptions + '</select>' +
                '<button type="button" onclick="genClearSep(' + n + ')" style="background:none; border:none; color:#E74C3C; font-size:16px; cursor:pointer; padding:0 4px;">✕</button>' +
            '</div>' +
        '</td>' +
        '<td style="padding:8px 12px; text-align:center;">' +
            '<button type="button" onclick="genDelRow(' + n + ')" style="background:none; border:none; cursor:pointer; color:#E74C3C; font-size:18px; padding:4px 6px; border-radius:50%;">⊗</button>' +
        '</td>';

    document.getElementById('gen-series-tbody').appendChild(tr);
    genUpdatePreview();
}

function genAttrChanged(n) {
    var attr = document.getElementById('gen-attr-' + n) ? document.getElementById('gen-attr-' + n).value : '';
    var cell = document.getElementById('gen-show-cell-' + n);
    if (!cell) return;

    if (attr === 'Custom Text') {
        cell.innerHTML = '<input type="text" id="gen-custom-' + n + '" placeholder="Custom text" oninput="genUpdatePreview()" style="width:100%; border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; outline:none;">';
    } else {
        cell.innerHTML =
            '<div style="display:flex; align-items:center; gap:4px;">' +
                '<select id="gen-show-type-' + n + '" onchange="genUpdatePreview()" style="border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; outline:none; background:#fff;">' +
                    '<option>First</option><option>Last</option>' +
                '</select>' +
                '<input type="number" id="gen-show-count-' + n + '" value="3" min="1" max="20" oninput="genUpdatePreview()" style="width:52px; border:1px solid #d0d4de; border-radius:4px; padding:6px 8px; font-size:13px; text-align:center; outline:none;">' +
            '</div>';
    }
    genUpdatePreview();
}

function genClearCase(n) {
    var s = document.getElementById('gen-case-' + n);
    if (s) s.value = 'None';
    genUpdatePreview();
}

function genClearSep(n) {
    var s = document.getElementById('gen-sep-' + n);
    if (s) s.value = '';
    genUpdatePreview();
}

function genDelRow(n) {
    var row = document.getElementById('gen-row-' + n);
    if (row) row.remove();
    genUpdatePreview();
}

function addGenRow() {
    genAddRow('Module Name', 'First', 3, 'Upper Case', '');
}

function genGetPartValue(n) {
    var attr     = document.getElementById('gen-attr-' + n) ? document.getElementById('gen-attr-' + n).value : '';
    var caseType = document.getElementById('gen-case-' + n) ? document.getElementById('gen-case-' + n).value : 'None';
    var modName  = _genModuleIdx !== null ? transModules[_genModuleIdx].name : 'MODULE';

    var raw = '';
    if (attr === 'Custom Text') {
        raw = document.getElementById('gen-custom-' + n) ? (document.getElementById('gen-custom-' + n).value || '') : '';
    } else if (attr === 'Series Number') {
        raw = _genModuleIdx !== null
            ? (document.getElementById('start-' + _genModuleIdx) ? document.getElementById('start-' + _genModuleIdx).value : '00001')
            : '00001';
    } 
    else if (attr === 'Financial Year') {
        // ── Financial Year கணக்கிடு ──
        // India financial year: April 1 – March 31
        var today    = new Date();
        var month    = today.getMonth(); // 0=Jan … 11=Dec
        var year     = today.getFullYear();
        var fyStart  = month >= 3 ? year      : year - 1;  // April(3) முதல் புது FY
        var fyEnd    = month >= 3 ? year + 1  : year;
        // e.g. "2425" or "2024-25"
        raw = String(fyStart).slice(-2) + '-' + String(fyEnd).slice(-2); // → "2526"

    } else {
        var showType  = document.getElementById('gen-show-type-' + n)  ? document.getElementById('gen-show-type-' + n).value  : 'First';
        var showCount = parseInt(document.getElementById('gen-show-count-' + n) ? document.getElementById('gen-show-count-' + n).value : 3);
        raw = showType === 'First' ? modName.slice(0, showCount) : modName.slice(-showCount);
    }

    if (caseType === 'Upper Case') raw = raw.toUpperCase();
    else if (caseType === 'Lower Case') raw = raw.toLowerCase();

    return raw;
}

function genUpdatePreview() {
    var rows   = document.querySelectorAll('#gen-series-tbody tr');
    var result = '';
    rows.forEach(function(tr, idx) {
        var n   = tr.id.replace('gen-row-', '');
        var sep = document.getElementById('gen-sep-' + n) ? (document.getElementById('gen-sep-' + n).value || '') : '';
        result += genGetPartValue(n);
        if (idx < rows.length - 1) result += sep;
    });
    document.getElementById('gen-preview-value').textContent = result || '—';
}

function applyGenSeries() {
    if (_genModuleIdx === null) return;
    var format = '';
    var rows   = document.querySelectorAll('#gen-series-tbody tr');
    rows.forEach(function(tr, idx) {
        var n   = tr.id.replace('gen-row-', '');
        var sep = document.getElementById('gen-sep-' + n) ? (document.getElementById('gen-sep-' + n).value || '') : '';
        format += genGetPartValue(n);
        if (idx < rows.length - 1) format += sep;
    });

    // Prefix field-ல் set பண்ணு
    var prefixInput = document.getElementById('prefix-' + _genModuleIdx);
    if (prefixInput) {
        prefixInput.value = format;
        updateTransPreview(_genModuleIdx);
    }
    closeGenSeriesModal();
}

// ═══════════════════════════════════════════════════════
//  HELPERS
// ═══════════════════════════════════════════════════════
function resetAddressFields() {
    ['attention','street1','street2','city','pincode','phone','fax','website'].forEach(function(n) {
        var el = document.querySelector('[name="' + n + '"]');
        if (el) el.value = '';
    });
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function showAlert(msg, type) {
    var el = document.getElementById('js-alert');
    el.className = 'alert alert-' + type + ' show';
    el.textContent = (type === 'success' ? '✓ ' : '✕ ') + msg;
    setTimeout(function() { el.classList.remove('show'); }, 4000);
}
</script>
@endpush