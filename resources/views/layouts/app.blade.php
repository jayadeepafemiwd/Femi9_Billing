<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory') | Inventory</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           CSS VARIABLES — Pink + Green Theme
        ═══════════════════════════════════════════ */
        :root {
            --pink:        #e8457a;
            --pink-dk:     #c73265;
            --pink-lt:     #fde8ef;
            --pink-xlt:    #fff5f8;
            --green:       #1db87a;
            --green-dk:    #148f5e;
            --green-lt:    #e6f9f1;
            --green-xlt:   #f2fdf8;

            --sidebar-bg:  #1a1028;
            --sidebar-bg2: #241535;
            --sidebar-border: rgba(255,255,255,0.07);
            --sidebar-text: #c4b8d4;
            --sidebar-muted: #7a6d8a;
            --sidebar-w:   230px;
            --topbar-h:    52px;

            --bg:          #f7f5fb;
            --bg2:         #ffffff;
            --border:      rgba(0,0,0,0.08);
            --text:        #1a1028;
            --muted:       #8a7e9a;
            --radius:      10px;

            --font:        'DM Sans', sans-serif;
            --mono:        'DM Mono', monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font);
            font-size: 13px;
            color: var(--text);
            background: var(--bg);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        .app-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 300;
            transition: width 0.25s ease;
            overflow: hidden;
        }

        /* ── Logo ── */
        .sb-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 18px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sb-logo-mark {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--pink) 0%, var(--green) 100%);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(232,69,122,0.35);
        }
        .sb-logo-text {
            font-size: 15px; font-weight: 700; color: #fff;
            letter-spacing: -0.3px;
            white-space: nowrap;
        }
        .sb-logo-text span {
            color: var(--pink);
        }

        /* ── User Info ── */
        .sb-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sb-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink-dk) 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sb-user-info { min-width: 0; flex: 1; }
        .sb-user-name {
            font-size: 12.5px; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sb-user-role {
            font-size: 10.5px; color: var(--pink);
            font-weight: 500; margin-top: 1px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* ── Nav Menu ── */
        .sb-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .sb-nav::-webkit-scrollbar { width: 4px; }
        .sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sb-section-label {
            font-size: 9.5px;
            font-weight: 700;
            color: var(--sidebar-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 18px 4px;
        }

        .sb-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px;
            font-size: 13px;
            color: var(--sidebar-text);
            text-decoration: none;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.15s;
            position: relative;
            white-space: nowrap;
        }
        .sb-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }
        .sb-item.active {
            color: #fff;
            background: rgba(232,69,122,0.15);
            border-left-color: var(--pink);
            font-weight: 500;
        }
        .sb-item.active .sb-icon { color: var(--pink); }

        .sb-icon {
            width: 18px; height: 18px;
            flex-shrink: 0;
            opacity: 0.85;
            display: flex; align-items: center; justify-content: center;
        }
        .sb-item-arrow {
            margin-left: auto;
            font-size: 10px;
            opacity: 0.5;
            transition: transform 0.2s;
        }
        .sb-item.open .sb-item-arrow { transform: rotate(90deg); }

        /* Sub items */
        .sb-sub {
            display: none;
            padding: 2px 0 4px;
            background: rgba(0,0,0,0.15);
        }
        .sb-sub.open { display: block; }
        .sb-sub-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 18px 7px 46px;
            font-size: 12.5px;
            color: var(--sidebar-muted);
            text-decoration: none;
            transition: all 0.15s;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }
        .sb-sub-item:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .sb-sub-item.active {
            color: var(--green);
            border-left-color: var(--green);
            background: rgba(29,184,122,0.08);
            font-weight: 500;
        }
        .sb-sub-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        /* Badge */
        .sb-badge {
            margin-left: auto;
            font-size: 9px; font-weight: 700;
            padding: 2px 6px;
            border-radius: 8px;
            background: var(--pink);
            color: #fff;
            line-height: 1.4;
        }
        .sb-badge.green {
            background: var(--green);
        }

        /* Collapse button */
        .sb-collapse {
            padding: 12px 18px;
            border-top: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--sidebar-muted);
            cursor: pointer;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .sb-collapse:hover { color: #fff; }

        /* ── Sidebar collapsed ── */
        .app-sidebar.collapsed {
            width: 56px;
        }
        .app-sidebar.collapsed .sb-logo-text,
        .app-sidebar.collapsed .sb-user-info,
        .app-sidebar.collapsed .sb-section-label,
        .app-sidebar.collapsed .sb-item-arrow,
        .app-sidebar.collapsed .sb-badge,
        .app-sidebar.collapsed .sb-item > span:last-of-type,
        .app-sidebar.collapsed .sb-collapse span:last-child,
        .app-sidebar.collapsed .sb-sub { display: none; }
        .app-sidebar.collapsed .sb-item { padding: 10px; justify-content: center; }
        .app-sidebar.collapsed .sb-icon { width: 20px; height: 20px; }
        .app-sidebar.collapsed .sb-user { justify-content: center; }
        .app-sidebar.collapsed .sb-logo { justify-content: center; padding: 18px 12px 16px; }
        .app-sidebar.collapsed .sb-collapse { justify-content: center; }
        .app-sidebar.collapsed .sb-item.active { border-left: none; border-radius: 8px; margin: 2px 6px; }

        /* ═══════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════ */
        .app-topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            z-index: 200;
            transition: left 0.25s ease;
        }
        .app-topbar.collapsed { left: 56px; }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--muted);
        }
        .topbar-breadcrumb a { color: var(--muted); text-decoration: none; }
        .topbar-breadcrumb a:hover { color: var(--text); }
        .topbar-breadcrumb .current { color: var(--text); font-weight: 500; }
        .topbar-breadcrumb .sep { font-size: 11px; opacity: 0.5; }

        .topbar-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 14px;
            width: 240px;
            font-size: 12.5px;
            color: var(--muted);
            cursor: text;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: none; background: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--muted);
            position: relative;
            font-size: 16px;
            transition: background 0.15s;
        }
        .topbar-btn:hover { background: var(--bg); color: var(--text); }
        .topbar-notif-badge {
            position: absolute;
            top: 4px; right: 4px;
            width: 8px; height: 8px;
            background: var(--pink);
            border-radius: 50%;
            border: 2px solid #fff;
        }
        .topbar-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink-dk) 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
            cursor: pointer;
        }

        /* ═══════════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════════ */
        .app-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            flex: 1;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left 0.25s ease;
            padding: 0;
        }
        .app-main.collapsed { margin-left: 56px; }

        /* ─ Alerts ─ */
        .alert-success-bar {
            background: var(--green-lt);
            border-left: 3px solid var(--green);
            color: var(--green-dk);
            padding: 11px 18px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error-bar {
            background: var(--pink-xlt);
            border-left: 3px solid var(--pink);
            color: var(--pink-dk);
            padding: 11px 18px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ─ Pagination ─ */
        .pagination .page-link {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 32px; height: 32px;
            font-size: 13px; padding: 0 8px;
            color: var(--pink); border: 1px solid var(--border);
            border-radius: 6px !important; background: #fff;
            text-decoration: none; font-family: var(--font);
        }
        .pagination .page-link:hover { background: var(--pink-xlt); border-color: var(--pink); }
        .pagination .page-item.active .page-link { background: var(--pink); border-color: var(--pink); color: #fff; }
        .pagination .page-item.disabled .page-link { color: #ccc; background: #fafafa; cursor: default; }
        .pagination .page-link svg { width: 14px !important; height: 14px !important; }
        .pagination { gap: 3px; margin: 0; }

        /* ─ General utility ─ */
        .text-pink   { color: var(--pink); }
        .text-green  { color: var(--green); }
        .bg-pink-lt  { background: var(--pink-lt); }
        .bg-green-lt { background: var(--green-lt); }

        /* ─ Scrollbar ─ */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.2); }
    </style>

    @stack('styles')
</head>
<body>

{{-- ═══════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════ --}}
@php
    $user     = auth()->user();
    $role     = $user?->role ?? 'guest';
    $isAdmin  = $role === 'admin';
    $settings = \App\Models\SettingHandle::getConfig() ?? [];

    // Helper: check permission
    function canAccess(string $module, string $action = 'read'): bool {
        $user = auth()->user();
        if (!$user) return false;
        if ($user->role === 'admin') return true;
        return $user->can_do($module, $action);
    }
@endphp

<aside class="app-sidebar" id="appSidebar">

    {{-- Logo --}}
    <div class="sb-logo">
        <div class="sb-logo-mark">I</div>
        <div class="sb-logo-text">Inven<span>tory</span></div>
    </div>

    {{-- User Info --}}
    @if($user)
    <div class="sb-user">
        <div class="sb-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="sb-user-info">
            <div class="sb-user-name">{{ $user->name }}</div>
            <div class="sb-user-role">{{ $user->role_label ?? ucfirst($role) }}</div>
        </div>
    </div>
    @endif

    {{-- Navigation --}}
    <nav class="sb-nav" id="sbNav">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sb-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </span>
            <span>Dashboard</span>
        </a>

     {{-- ITEMS --}}
<div class="sb-section-label">Items</div>

<div class="sb-item {{ request()->routeIs('products.*','price-lists.*','composite-items.*') ? 'active open' : '' }}"
     onclick="toggleSbMenu('menu-items', this)">
    <span class="sb-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
    </span>
    <span>Products</span>
    <span class="sb-item-arrow">›</span>
</div>
<div class="sb-sub {{ request()->routeIs('products.*','price-lists.*','composite-items.*') ? 'open' : '' }}" id="menu-items">
    <a href="{{ route('products.index') }}"
       class="sb-sub-item {{ request()->routeIs('products.index','products.create','products.edit','products.show') ? 'active' : '' }}">
        <span class="sb-sub-dot"></span> All Items
    </a>
    @if(!empty($settings['enable_price_lists']))
    <a href="{{ route('price-lists.index') }}"
       class="sb-sub-item {{ request()->routeIs('price-lists.*') ? 'active' : '' }}">
        <span class="sb-sub-dot"></span> Price Lists
    </a>
    @endif
    @if(!empty($settings['enable_composite_items']))
    <a href="{{ route('composite-items.index') }}"
       class="sb-sub-item {{ request()->routeIs('composite-items.*') ? 'active' : '' }}">
        <span class="sb-sub-dot"></span> Composite Items
    </a>
    @endif
</div>

{{-- CUSTOMERS --}}
<a href="{{ route('customers.index') }}"
   class="sb-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
    <span class="sb-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
        </svg>
    </span>
    <span>Customers</span>
</a>

{{-- INVOICES --}}
<a href="{{ route('invoices.index') }}"
   class="sb-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
    <span class="sb-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
    </span>
    <span>Invoices</span>
</a>

{{-- LOCATIONS --}}
<a href="{{ route('locations.index') }}"
   class="sb-item {{ request()->routeIs('locations.*') ? 'active' : '' }}">
    <span class="sb-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>
    </span>
    <span>Locations</span>
</a>

        {{-- ADMIN SECTION --}}
        @if($isAdmin)
        <div class="sb-section-label" style="margin-top:8px;">Admin</div>

        <div class="sb-item {{ request()->routeIs('admin.ucp.*','admin.user-categories.*','admin.user-sub-categories.*') ? 'active open' : '' }}"
             onclick="toggleSbMenu('menu-admin', this)">
            <span class="sb-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </span>
            <span>Permissions</span>
            <span class="sb-item-arrow">›</span>
        </div>
        <div class="sb-sub {{ request()->routeIs('admin.ucp.*','admin.user-categories.*','admin.user-sub-categories.*') ? 'open' : '' }}" id="menu-admin">
            <a href="{{ route('admin.ucp.index') }}"
               class="sb-sub-item {{ request()->routeIs('admin.ucp.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> Category Permissions
            </a>
            @if(Route::has('admin.user-categories.index'))
            <a href="{{ route('admin.user-categories.index') }}"
               class="sb-sub-item {{ request()->routeIs('admin.user-categories.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> User Categories
            </a>
            @endif
            @if(Route::has('admin.user-sub-categories.index'))
            <a href="{{ route('admin.user-sub-categories.index') }}"
               class="sb-sub-item {{ request()->routeIs('admin.user-sub-categories.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> Sub Categories
            </a>
            @endif
        </div>

        <div class="sb-item {{ request()->routeIs('setting_handle.*','field_customization.*','lock_configuration.*') ? 'active open' : '' }}"
             onclick="toggleSbMenu('menu-settings', this)">
            <span class="sb-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </span>
            <span>Settings</span>
            <span class="sb-item-arrow">›</span>
        </div>
        <div class="sb-sub {{ request()->routeIs('setting_handle.*','field_customization.*','lock_configuration.*') ? 'open' : '' }}" id="menu-settings">
            <a href="{{ route('setting_handle.create') }}"
               class="sb-sub-item {{ request()->routeIs('setting_handle.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> General Settings
            </a>
            <a href="{{ route('field_customization.index') }}"
               class="sb-sub-item {{ request()->routeIs('field_customization.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> Field Customization
            </a>
            <a href="{{ route('lock_configuration.index') }}"
               class="sb-sub-item {{ request()->routeIs('lock_configuration.*') ? 'active' : '' }}">
                <span class="sb-sub-dot"></span> Lock Configuration
            </a>
        </div>
        @endif

    </nav>

    {{-- Collapse --}}
    <div class="sb-collapse" onclick="toggleSidebar()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="collapseIcon">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span>Collapse</span>
    </div>
</aside>

{{-- ═══════════════════════════════════════
     TOPBAR
═══════════════════════════════════════ --}}
<header class="app-topbar" id="appTopbar">

    {{-- Breadcrumb --}}
    <div class="topbar-breadcrumb">
        @yield('breadcrumb', '<span class="current">Dashboard</span>')
    </div>

    {{-- Search --}}
    <div class="topbar-search" style="margin-left:16px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        Search...
    </div>

    <div class="topbar-right">
        {{-- Notifications --}}
        <button class="topbar-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="topbar-notif-badge"></span>
        </button>

        {{-- Settings --}}
        @if($isAdmin)
        <a href="{{ route('setting_handle.create') }}" class="topbar-btn" style="color:var(--muted);text-decoration:none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
        </a>
        @endif

        {{-- User Avatar --}}
        @if($user)
        <div class="topbar-avatar" title="{{ $user->name }}">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        @endif

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="topbar-btn" title="Logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>
</header>

{{-- ═══════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════ --}}
<main class="app-main" id="appMain">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert-success-bar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-error-bar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

{{-- ═══════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── Sidebar toggle ──
    const sidebar  = document.getElementById('appSidebar');
    const topbar   = document.getElementById('appTopbar');
    const main     = document.getElementById('appMain');
    const colIcon  = document.getElementById('collapseIcon');
    let isCollapsed = localStorage.getItem('sb_collapsed') === '1';

    function applySidebarState() {
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            topbar.classList.add('collapsed');
            main.classList.add('collapsed');
            colIcon.setAttribute('points', '9 18 15 12 9 6');
        } else {
            sidebar.classList.remove('collapsed');
            topbar.classList.remove('collapsed');
            main.classList.remove('collapsed');
            colIcon.setAttribute('points', '15 18 9 12 15 6');
        }
    }
    function toggleSidebar() {
        isCollapsed = !isCollapsed;
        localStorage.setItem('sb_collapsed', isCollapsed ? '1' : '0');
        applySidebarState();
    }
    applySidebarState();

    // ── Sub menu toggle ──
    function toggleSbMenu(menuId, triggerEl) {
        const menu = document.getElementById(menuId);
        if (!menu) return;
        const isOpen = menu.classList.contains('open');
        // Close all
        document.querySelectorAll('.sb-sub.open').forEach(m => m.classList.remove('open'));
        document.querySelectorAll('.sb-item.open').forEach(i => i.classList.remove('open'));
        if (!isOpen) {
            menu.classList.add('open');
            triggerEl.classList.add('open');
        }
    }
</script>

@stack('scripts')
</body>
</html>