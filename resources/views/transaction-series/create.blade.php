@extends('layouts.app')

@section('title', 'New Transaction Series | Settings')

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

    .create-page { padding: 24px 32px; max-width: 800px; }

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
        transition: background 0.15s;
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

    /* Multi-select loc tag */
    .loc-tag {
        display:inline-flex; align-items:center; gap:4px;
        background:#E8F0FE; color:var(--zoho-blue);
        border:1px solid #C5D8FC; border-radius:3px;
        padding:2px 6px; font-size:12px;
    }
    .loc-tag-remove {
        cursor:pointer; font-size:11px; line-height:1;
        color:var(--zoho-text-muted);
    }
    .loc-tag-remove:hover { color:var(--zoho-red); }

    /* Price-List style Category Dropdown */
    .ts-cat-dropdown { position:relative; width:100%; }
    .ts-cat-selected {
        display:flex; justify-content:space-between; align-items:center;
        padding:7px 10px; border:1px solid var(--zoho-input-border); border-radius:4px;
        background:var(--zoho-white); cursor:pointer; font-size:13px; min-height:36px;
    }
    .ts-cat-selected:hover { border-color:var(--zoho-blue); }
    .ts-cat-list {
        display:none; position:absolute; top:calc(100% + 3px); left:0; right:0;
        background:var(--zoho-white); border:1px solid var(--zoho-border); border-radius:4px;
        box-shadow:0 6px 20px rgba(0,0,0,0.12); z-index:600; max-height:220px; overflow-y:auto;
    }
    .ts-cat-list.open { display:block; }
    .ts-cat-item {
        display:flex; justify-content:space-between; align-items:center;
        padding:9px 12px; cursor:pointer; font-size:13px;
        border-bottom:1px solid #f0f0f0;
    }
    .ts-cat-item:last-child { border-bottom:none; }
    .ts-cat-item:hover { background:#F0F4FF; }
    .ts-cat-item.selected { background:#E8F0FE; }
    .ts-cat-name { font-weight:500; color:var(--zoho-text); }
    .ts-cat-loc {
        font-size:11px; color:#fff; background:var(--zoho-blue);
        padding:2px 8px; border-radius:10px; white-space:nowrap;
    }
    .ts-cat-arrow { font-size:11px; color:#999; margin-left:6px; }
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
                <span>New Series</span>
            </div>

            <h2 class="page-title">New Transaction Series</h2>
            <p class="page-subtitle">Set a name and configure the numbering prefix and starting number for each module.</p>

            <div id="js-alert" class="alert"></div>

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
                               oninput="clearNameError()">
                        <div class="field-error" id="name-error">Series name is required</div>
                    </div>
                </div>

                {{-- Location Multi-Select --}}
                <div class="series-name-row" style="margin-top:14px; align-items:flex-start;">
                    <label style="color:var(--zoho-text); padding-top:6px;">Location</label>
                    <div style="flex:1; max-width:400px;">

                        <div id="loc-box"
                             onclick="toggleLocDropdown()"
                             style="min-height:36px; padding:4px 28px 4px 8px; border:1px solid var(--zoho-input-border);
                                    border-radius:4px; cursor:pointer; background:var(--zoho-white); position:relative;
                                    display:flex; flex-wrap:wrap; gap:4px; align-items:center;">
                            <span id="loc-placeholder" style="color:#9CA3AF; font-size:13px;">— Select Locations —</span>
                            <span style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                         color:var(--zoho-text-muted);pointer-events:none;">▾</span>
                        </div>

                        <div id="loc-dropdown"
                             style="display:none; position:absolute; z-index:200; background:var(--zoho-white);
                                    border:1px solid var(--zoho-border); border-radius:4px;
                                    box-shadow:0 4px 12px rgba(0,0,0,0.1); width:400px; max-height:220px; overflow-y:auto;">
                            <div style="padding:8px; border-bottom:1px solid var(--zoho-border);">
                                <input type="text"
                                       id="loc-search"
                                       onclick="event.stopPropagation()"
                                       oninput="filterLocs()"
                                       placeholder="Search locations..."
                                       style="width:100%; padding:5px 8px; border:1px solid var(--zoho-input-border);
                                              border-radius:4px; font-size:12px; outline:none;">
                            </div>
                            <div id="loc-options">
                                @foreach($locations ?? [] as $loc)
                                <label id="loc-opt-{{ $loc->id }}"
                                       onclick="event.stopPropagation()"
                                       style="display:flex; align-items:center; gap:8px; padding:8px 12px;
                                              cursor:pointer; font-size:13px;"
                                       onmouseenter="this.style.background='var(--zoho-row-hover)'"
                                       onmouseleave="this.style.background=''">
                                    <input type="checkbox"
                                           value="{{ $loc->id }}"
                                           data-name="{{ $loc->location_name }}"
                                           onchange="onLocChange(this)"
                                           style="accent-color:var(--zoho-blue); width:14px; height:14px;">
                                    {{ $loc->location_name }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Category Dropdown (Blade-rendered, Price List style) --}}
                <div class="series-name-row" style="margin-top:14px;">
                    <label style="color:var(--zoho-text);">Category</label>
                    <div style="flex:1; max-width:400px;">

                        <input type="hidden" id="cat-hid-id"   value="">
                        <input type="hidden" id="cat-hid-name" value="">

                        <div class="ts-cat-dropdown" id="tsCatDropdown">
                            <div class="ts-cat-selected" id="tsCatSelected" onclick="toggleTsCatList()">
                                <span id="tsCatSelectedText" style="color:#9CA3AF;">— Select Category —</span>
                                <span class="ts-cat-arrow">▾</span>
                            </div>
                            <div class="ts-cat-list" id="tsCatList">
                                {{-- ✅ Blank option --}}
                                <div class="ts-cat-item"
                                     data-id=""
                                     data-name=""
                                     data-loc=""
                                     onclick="selectTsCat(this)">
                                    <span class="ts-cat-name" style="color:#9CA3AF;">— Select Category —</span>
                                </div>
                                {{-- ✅ Blade-rendered categories with location badge --}}
                                @foreach($categories ?? [] as $cat)
                                <div class="ts-cat-item"
                                     data-id="{{ $cat->id }}"
                                     data-name="{{ $cat->name }}"
                                     data-loc="{{ $cat->location_label ?? '' }}"
                                     onclick="selectTsCat(this)">
                                    <span class="ts-cat-name">{{ $cat->name }}</span>
                                    @if(!empty($cat->location_label))
                                        <span class="ts-cat-loc">{{ $cat->location_label }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- end form-card-body --}}

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
                            {{-- Rendered by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <button class="btn-primary" id="save-btn" onclick="saveSeries()">
                    <span class="spinner" id="save-spinner"></span>
                    Save
                </button>
                <a href="{{ route('transaction-series.index') }}" class="btn-secondary">Cancel</a>
            </div>

        </div>
    </div>
</div>

{{-- GENERATE SERIES FORMAT MODAL --}}
<div id="gen-modal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:5000; align-items:flex-start; justify-content:center; padding-top:60px;">
    <div style="background:#fff; border-radius:8px; width:900px; max-width:96vw;
                max-height:85vh; display:flex; flex-direction:column;
                box-shadow:0 8px 32px rgba(0,0,0,0.2); overflow:hidden;">

        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:16px 24px; border-bottom:1px solid #eee; flex-shrink:0;">
            <span id="gen-modal-title" style="font-size:16px; font-weight:700; color:#222;">
                Generate Series - MODULE
            </span>
            <button onclick="closeGenModal()"
                    style="background:none; border:none; font-size:22px;
                           color:#888; cursor:pointer; font-weight:700;">✕</button>
        </div>

        <div style="flex:1; overflow-y:auto; padding:24px;">

            <p style="font-size:13px; color:#666; margin-bottom:20px; display:flex; align-items:center; gap:6px;">
                Select attributes to generate the series format
                <span style="color:#aaa; font-size:14px; cursor:pointer;" title="Use these to build your numbering format">ⓘ</span>
            </p>

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
                <tbody id="gen-rows-tbody"></tbody>
            </table>

            <button type="button" onclick="genAddRow()"
                    style="display:inline-flex; align-items:center; gap:5px; color:var(--zoho-blue);
                           font-size:13px; font-weight:500; cursor:pointer; border:none;
                           background:none; padding:8px 0; margin-top:8px;">
                + Add Attribute
            </button>

            <div style="margin-top:20px;">
                <div style="font-size:12px; font-weight:700; color:#555; margin-bottom:8px;">
                    Series Preview
                </div>
                <div id="gen-preview-box"
                     style="background:#fffbea; border:2px dashed #f0c040; border-radius:6px;
                            padding:20px; text-align:center; font-size:18px; font-weight:700;
                            color:#333; letter-spacing:2px; min-height:60px;
                            display:flex; align-items:center; justify-content:center;">
                    —
                </div>
            </div>
        </div>

        <div style="padding:14px 24px; border-top:1px solid #eee;
                    display:flex; gap:10px; flex-shrink:0;">
            <button onclick="applyGenFormat()"
                    style="background:var(--zoho-blue); color:#fff; border:none; border-radius:6px;
                           padding:9px 24px; font-weight:700; font-size:13px; cursor:pointer;">
                Generate Series
            </button>
            <button onclick="closeGenModal()"
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

// ── Helpers ──
function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escHtmlCat(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ══════════════════════════════════════
// DOMContentLoaded
// ══════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {

    // Modules Table render
    const tbody = document.getElementById('modules-tbody');
    tbody.innerHTML = TRANS_MODULES.map((m, i) => `
        <tr>
            <td class="module-name">
                ${esc(m.name)}
                <div style="margin-top:4px;">
                    <button type="button"
                            onclick="openGenModal(${i})"
                            style="font-size:11px; color:var(--zoho-blue); background:none;
                                   border:none; cursor:pointer; padding:0;
                                   display:inline-flex; align-items:center; gap:3px;">
                        ＋ Add New Series
                    </button>
                </div>
            </td>
            <td><input type="text" id="prefix-${i}" value="${esc(m.prefix)}"
                       placeholder="e.g. INV-" oninput="updatePreview(${i})"></td>
            <td><input type="text" id="start-${i}"  value="${esc(m.start)}"
                       placeholder="00001"    oninput="updatePreview(${i})"></td>
            <td><span class="preview-badge" id="preview-${i}">${esc(m.prefix + m.start)}</span></td>
        </tr>
    `).join('');

    // URL param clean
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('created') === '1') {
        history.replaceState({}, '', window.location.pathname);
    }
});

// ── Preview update ──
function updatePreview(i) {
    const p = document.getElementById(`prefix-${i}`).value;
    const s = document.getElementById(`start-${i}`).value;
    document.getElementById(`preview-${i}`).textContent = p + s;
}

// ── Validation ──
function clearNameError() {
    document.getElementById('series-name').classList.remove('error');
    document.getElementById('name-error').classList.remove('show');
}

// ══════════════════════════════════════
// LOCATION MULTI-SELECT
// ══════════════════════════════════════
const selectedLocs = {};

function toggleLocDropdown() {
    const dd = document.getElementById('loc-dropdown');
    const isOpen = dd.style.display === 'block';
    dd.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) document.getElementById('loc-search').focus();
}

document.addEventListener('click', function(e) {
    const box = document.getElementById('loc-box');
    const dd  = document.getElementById('loc-dropdown');
    if (box && dd && !box.contains(e.target) && !dd.contains(e.target)) {
        dd.style.display = 'none';
    }
});

function onLocChange(checkbox) {
    const id   = checkbox.value;
    const name = checkbox.dataset.name;
    if (checkbox.checked) selectedLocs[id] = name;
    else delete selectedLocs[id];
    renderLocTags();
}

function removeLocTag(id) {
    delete selectedLocs[id];
    const cb = document.querySelector(`#loc-options input[value="${id}"]`);
    if (cb) cb.checked = false;
    renderLocTags();
}

function renderLocTags() {
    const box = document.getElementById('loc-box');
    const ph  = document.getElementById('loc-placeholder');
    box.querySelectorAll('.loc-tag').forEach(t => t.remove());
    const ids = Object.keys(selectedLocs);
    ph.style.display = ids.length ? 'none' : 'inline';
    ids.forEach(id => {
        const tag = document.createElement('span');
        tag.className = 'loc-tag';
        tag.innerHTML = `${esc(selectedLocs[id])}
            <span class="loc-tag-remove"
                  onclick="event.stopPropagation();removeLocTag('${id}')">×</span>`;
        box.insertBefore(tag, box.lastElementChild);
    });
}

function filterLocs() {
    const q = document.getElementById('loc-search').value.toLowerCase();
    document.querySelectorAll('#loc-options label').forEach(label => {
        label.style.display = label.textContent.toLowerCase().includes(q) ? 'flex' : 'none';
    });
}

// ══════════════════════════════════════
// CATEGORY DROPDOWN (Blade-rendered, Price List style)
// ══════════════════════════════════════
function toggleTsCatList() {
    document.getElementById('tsCatList').classList.toggle('open');
}

function selectTsCat(el) {
    const id   = el.dataset.id;
    const name = el.dataset.name;
    const loc  = el.dataset.loc;

    document.getElementById('cat-hid-id').value   = id;
    document.getElementById('cat-hid-name').value = name;

    const txt = document.getElementById('tsCatSelectedText');
    if (name) {
        txt.style.color = 'var(--zoho-text)';
        txt.innerHTML = escHtmlCat(name) + (loc
            ? ` <span class="ts-cat-loc" style="margin-left:6px;">${escHtmlCat(loc)}</span>`
            : '');
    } else {
        txt.style.color = '#9CA3AF';
        txt.textContent = '— Select Category —';
    }

    document.querySelectorAll('.ts-cat-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('tsCatList').classList.remove('open');
}

// Outside click → close category dropdown
document.addEventListener('click', function(e) {
    const dd = document.getElementById('tsCatDropdown');
    if (dd && !dd.contains(e.target)) {
        document.getElementById('tsCatList').classList.remove('open');
    }
});

// ══════════════════════════════════════
// SAVE
// ══════════════════════════════════════
function saveSeries() {
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

    const locationIds  = Object.keys(selectedLocs);
    const categoryId   = document.getElementById('cat-hid-id').value   || null;
    const categoryName = document.getElementById('cat-hid-name').value  || null;

    const btn     = document.getElementById('save-btn');
    const spinner = document.getElementById('save-spinner');
    btn.disabled  = true;
    spinner.classList.add('show');

    fetch('/transaction-series', {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            name,
            series,
            location_id:   locationIds,
            category_id:   categoryId,
            category_name: categoryName,
        }),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        spinner.classList.remove('show');
        if (data.success) {
            window.location.href = '{{ route("transaction-series.index") }}?created=1';
        } else {
            showAlert(data.message ?? 'Failed to save', 'error');
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

// ══════════════════════════════════════
// GENERATE SERIES FORMAT MODAL
// ══════════════════════════════════════
let _genModuleIdx = null;
let _genRowCount  = 0;

function openGenModal(moduleIdx) {
    _genModuleIdx = moduleIdx;
    _genRowCount  = 0;

    const moduleName = TRANS_MODULES[moduleIdx].name;
    document.getElementById('gen-modal-title').textContent = 'Generate Series - ' + moduleName;
    document.getElementById('gen-rows-tbody').innerHTML = '';

    genAddRow('Module Name', 'First', 3, 'Upper Case', '-');
    genAddRow('Custom Text', '',      0, 'Upper Case', '');

    genUpdatePreview();
    document.getElementById('gen-modal').style.display = 'flex';
}

function closeGenModal() {
    document.getElementById('gen-modal').style.display = 'none';
    _genModuleIdx = null;
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('gen-modal');
    if (modal && e.target === modal) closeGenModal();
});

function genAddRow(attr, showType, showCount, letterCase, separator) {
    _genRowCount++;
    const n = _genRowCount;

    const attrOptions = ['Module Name', 'Custom Text', 'Series Number'];
    const attrHtml = attrOptions.map(a =>
        `<option value="${a}" ${a === (attr || 'Module Name') ? 'selected' : ''}>${a}</option>`
    ).join('');

    const isCustom = (attr === 'Custom Text');

    const showCell = isCustom
        ? `<input type="text" id="gen-custom-${n}"
                  placeholder="Custom text"
                  oninput="genUpdatePreview()"
                  style="width:100%; border:1px solid #d0d4de; border-radius:4px;
                         padding:6px 8px; font-size:13px; outline:none;">`
        : `<div style="display:flex; align-items:center; gap:4px;">
               <select id="gen-show-type-${n}" onchange="genUpdatePreview()"
                       style="border:1px solid #d0d4de; border-radius:4px; padding:6px 8px;
                              font-size:13px; outline:none; background:#fff;">
                   <option ${(showType||'First')==='First'?'selected':''}>First</option>
                   <option ${showType==='Last'?'selected':''}>Last</option>
               </select>
               <input type="number" id="gen-show-count-${n}"
                      value="${showCount||3}" min="1" max="20"
                      oninput="genUpdatePreview()"
                      style="width:52px; border:1px solid #d0d4de; border-radius:4px;
                             padding:6px 8px; font-size:13px; text-align:center; outline:none;">
           </div>`;

    const caseOptions = ['Upper Case', 'Lower Case', 'None'].map(c =>
        `<option ${c === (letterCase||'Upper Case') ? 'selected' : ''}>${c}</option>`
    ).join('');

    const sepOptions = ['-', '_', '/', '.', ''].map(s =>
        `<option value="${s}" ${s === (separator??'-') ? 'selected' : ''}>${s===''?'(none)':s}</option>`
    ).join('');

    const tr = document.createElement('tr');
    tr.id = 'gen-row-' + n;
    tr.style.borderBottom = '1px solid #f0f2f7';
    tr.innerHTML = `
        <td style="padding:8px 12px;">
            <select id="gen-attr-${n}" onchange="genAttrChanged(${n}); genUpdatePreview()"
                    style="width:100%; border:1px solid #d0d4de; border-radius:4px;
                           padding:6px 8px; font-size:13px; outline:none; background:#fff;">
                ${attrHtml}
            </select>
        </td>
        <td style="padding:8px 12px;" id="gen-show-cell-${n}">${showCell}</td>
        <td style="padding:8px 12px;">
            <div style="display:flex; align-items:center; gap:4px;">
                <select id="gen-case-${n}" onchange="genUpdatePreview()"
                        style="flex:1; border:1px solid var(--zoho-blue); border-radius:4px;
                               padding:6px 8px; font-size:13px; color:var(--zoho-blue);
                               outline:none; background:#fff;">
                    ${caseOptions}
                </select>
                <button type="button" onclick="genClearCase(${n})"
                        style="background:none; border:none; color:var(--zoho-red);
                               font-size:16px; cursor:pointer; padding:0 4px;">✕</button>
            </div>
        </td>
        <td style="padding:8px 12px;">
            <div style="display:flex; align-items:center; gap:4px;">
                <select id="gen-sep-${n}" onchange="genUpdatePreview()"
                        style="flex:1; border:1px solid var(--zoho-blue); border-radius:4px;
                               padding:6px 8px; font-size:13px; color:var(--zoho-blue);
                               outline:none; background:#fff;">
                    ${sepOptions}
                </select>
                <button type="button" onclick="genClearSep(${n})"
                        style="background:none; border:none; color:var(--zoho-red);
                               font-size:16px; cursor:pointer; padding:0 4px;">✕</button>
            </div>
        </td>
        <td style="padding:8px 12px; text-align:center;">
            <button type="button" onclick="genDelRow(${n})"
                    style="background:none; border:none; cursor:pointer; color:var(--zoho-red);
                           font-size:18px; padding:4px 6px; border-radius:50%;">⊗</button>
        </td>
    `;
    document.getElementById('gen-rows-tbody').appendChild(tr);
    genUpdatePreview();
}

function genAttrChanged(n) {
    const attr = document.getElementById('gen-attr-' + n)?.value;
    const cell = document.getElementById('gen-show-cell-' + n);
    if (!cell) return;

    if (attr === 'Custom Text') {
        cell.innerHTML = `<input type="text" id="gen-custom-${n}"
                                 placeholder="Custom text"
                                 oninput="genUpdatePreview()"
                                 style="width:100%; border:1px solid #d0d4de; border-radius:4px;
                                        padding:6px 8px; font-size:13px; outline:none;">`;
    } else {
        cell.innerHTML = `<div style="display:flex; align-items:center; gap:4px;">
            <select id="gen-show-type-${n}" onchange="genUpdatePreview()"
                    style="border:1px solid #d0d4de; border-radius:4px; padding:6px 8px;
                           font-size:13px; outline:none; background:#fff;">
                <option>First</option><option>Last</option>
            </select>
            <input type="number" id="gen-show-count-${n}" value="3" min="1" max="20"
                   oninput="genUpdatePreview()"
                   style="width:52px; border:1px solid #d0d4de; border-radius:4px;
                          padding:6px 8px; font-size:13px; text-align:center; outline:none;">
        </div>`;
    }
    genUpdatePreview();
}

function genClearCase(n) {
    const s = document.getElementById('gen-case-' + n);
    if (s) s.value = 'None';
    genUpdatePreview();
}

function genClearSep(n) {
    const s = document.getElementById('gen-sep-' + n);
    if (s) s.value = '';
    genUpdatePreview();
}

function genDelRow(n) {
    document.getElementById('gen-row-' + n)?.remove();
    genUpdatePreview();
}

function genGetPartValue(n) {
    const attr      = document.getElementById('gen-attr-' + n)?.value || '';
    const caseType  = document.getElementById('gen-case-' + n)?.value || 'None';
    const modName   = _genModuleIdx !== null ? TRANS_MODULES[_genModuleIdx].name : 'MODULE';

    let raw = '';

    if (attr === 'Custom Text') {
        raw = document.getElementById('gen-custom-' + n)?.value || '';
    } else if (attr === 'Series Number') {
        const start = _genModuleIdx !== null
            ? (document.getElementById('start-' + _genModuleIdx)?.value || '00001')
            : '00001';
        raw = start;
    } else {
        const showType  = document.getElementById('gen-show-type-' + n)?.value || 'First';
        const showCount = parseInt(document.getElementById('gen-show-count-' + n)?.value || 3);
        raw = showType === 'First' ? modName.slice(0, showCount) : modName.slice(-showCount);
    }

    if (caseType === 'Upper Case') raw = raw.toUpperCase();
    else if (caseType === 'Lower Case') raw = raw.toLowerCase();

    return raw;
}

function genBuildPreview() {
    const rows = document.querySelectorAll('#gen-rows-tbody tr');
    let result = '';
    rows.forEach((tr, idx) => {
        const n   = tr.id.replace('gen-row-', '');
        const sep = document.getElementById('gen-sep-' + n)?.value || '';
        result += genGetPartValue(n);
        if (idx < rows.length - 1) result += sep;
    });
    return result;
}

function genUpdatePreview() {
    const preview = genBuildPreview();
    document.getElementById('gen-preview-box').textContent = preview || '—';
}

function applyGenFormat() {
    if (_genModuleIdx === null) return;
    const format = genBuildPreview();
    const prefixInput = document.getElementById('prefix-' + _genModuleIdx);
    if (prefixInput) {
        prefixInput.value = format;
        updatePreview(_genModuleIdx);
    }
    closeGenModal();
}
</script>
@endpush