<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Femi9 — Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --rose:        #c9637a;
            --rose-light:  #f2c4ce;
            --rose-deep:   #8b3a50;
            --blush:       #fde8ed;
            --cream:       #fdf6f0;
            --warm:        #f9ede8;
            --mauve:       #b57a8c;
            --dusty:       #d4a0ad;
            --text:        #3d2030;
            --muted:       #9a6b78;
            --border:      rgba(201,99,122,0.15);
            --sidebar-w:   260px;
            --ok:          #5a9e7a;
            --ok-bg:       #edf7f2;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ═══════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            padding: 0;
            overflow: hidden;
        }

        .sb-brand {
            padding: 1.6rem 1.5rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sb-logo {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--rose), var(--mauve));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .sb-brand-text .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem; font-weight: 700;
            color: var(--rose-deep);
        }
        .sb-brand-text .brand-sub {
            font-size: 0.7rem; color: var(--muted);
            font-style: italic;
        }

        .sb-user {
            margin: 1.2rem 1rem;
            background: var(--warm);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sb-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--rose-light), var(--rose));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700;
            color: var(--rose-deep);
            flex-shrink: 0;
        }
        .sb-user-info .sb-name {
            font-weight: 600; font-size: 0.88rem; color: var(--text);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 130px;
        }
        .sb-user-info .sb-role {
            font-size: 0.68rem; color: var(--muted);
        }
        .role-badge {
            margin-left: auto;
            font-size: 0.6rem; padding: 2px 7px;
            border-radius: 20px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .role-admin  { background: #fde8ed; color: var(--rose-deep); }
        .role-mod    { background: #e8f0fd; color: #3a508b; }
        .role-member { background: var(--warm); color: var(--muted); }

        .sb-nav { flex: 1; padding: 0.5rem 0.75rem; overflow-y: auto; }
        .sb-section-label {
            font-size: 0.62rem; color: var(--dusty);
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 0.8rem 0.6rem 0.4rem;
            font-weight: 500;
        }
        .sb-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.62rem 0.8rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.85rem;
            transition: all 0.2s;
            margin-bottom: 2px;
            position: relative;
        }
        .sb-link:hover { background: var(--blush); color: var(--rose-deep); }
        .sb-link.active {
            background: linear-gradient(135deg, var(--blush), #fde8f3);
            color: var(--rose-deep);
            font-weight: 600;
        }
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--rose);
            border-radius: 0 3px 3px 0;
        }
        .sb-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .notif-dot {
            margin-left: auto;
            background: var(--rose);
            color: #fff;
            font-size: 0.62rem;
            font-weight: 600;
            min-width: 18px; height: 18px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
        }

        .sb-bottom {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--border);
        }
        .sb-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 0.62rem 0.8rem;
            border-radius: 10px;
            color: var(--muted);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sb-logout:hover { background: #fff0f2; color: #c0394f; }
        .sb-logout svg { width: 18px; height: 18px; }

        /* ═══════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top bar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0; z-index: 50;
        }
        .topbar-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--rose-deep);
        }
        .topbar-left p {
            font-size: 0.75rem;
            color: var(--muted);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .tb-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--blush);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--muted);
            transition: all 0.2s;
            position: relative;
        }
        .tb-btn:hover { background: var(--rose-light); color: var(--rose-deep); }
        .tb-btn svg { width: 18px; height: 18px; }
        .tb-notif-badge {
            position: absolute;
            top: 5px; right: 5px;
            width: 8px; height: 8px;
            background: var(--rose);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }
        .tb-greeting {
            font-size: 0.82rem;
            color: var(--muted);
        }
        .tb-greeting strong { color: var(--rose-deep); }

        /* Content area */
        .content {
            padding: 2rem;
            flex: 1;
        }

        /* ── Welcome Banner ── */
        .welcome-banner {
            background: linear-gradient(135deg, var(--rose-deep) 0%, var(--mauve) 60%, var(--rose) 100%);
            border-radius: 20px;
            padding: 1.8rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .wb-petal {
            position: absolute;
            border-radius: 60% 40% 70% 30% / 50% 60% 40% 50%;
            opacity: 0.1;
            background: #fff;
        }
        .wb-p1 { width:180px; height:180px; top:-60px; right:80px; transform: rotate(20deg); }
        .wb-p2 { width:120px; height:120px; bottom:-40px; right:20px; transform: rotate(-15deg); }

        .wb-text { position: relative; z-index: 2; }
        .wb-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem; font-weight: 700;
            color: #fff;
            margin-bottom: 0.3rem;
        }
        .wb-text h2 em { font-style: italic; }
        .wb-text p { color: rgba(255,255,255,0.75); font-size: 0.85rem; }
        .wb-emoji {
            font-size: 3.5rem;
            position: relative; z-index: 2;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.2rem 1.3rem;
            transition: transform 0.2s, box-shadow 0.2s;
            animation: fadeUp 0.5s ease both;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(201,99,122,0.1);
        }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.10s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.20s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.8rem;
            font-size: 18px;
        }
        .si-rose  { background: var(--blush);  }
        .si-teal  { background: #e0f5ef; }
        .si-amber { background: #fef3e2; }
        .si-blue  { background: #e5f0fd; }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--rose-deep);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.73rem; color: var(--muted);
            margin-top: 0.3rem;
        }
        .stat-change {
            font-size: 0.7rem; margin-top: 0.5rem;
            display: flex; align-items: center; gap: 4px;
        }
        .change-up   { color: var(--ok); }
        .change-down { color: #c0394f; }

        /* ── Two column layout ── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.5rem;
        }

        /* ── Feed ── */
        .section-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
        }
        .sc-header {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .sc-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 600;
            color: var(--rose-deep);
        }
        .sc-see-all {
            font-size: 0.75rem; color: var(--rose);
            text-decoration: none; font-weight: 500;
        }
        .sc-see-all:hover { color: var(--rose-deep); }

        /* Post items */
        .post-item {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            animation: fadeUp 0.4s ease both;
        }
        .post-item:last-child { border-bottom: none; }
        .post-item:hover { background: var(--cream); }
        .post-item:nth-child(1) { animation-delay: 0.1s; }
        .post-item:nth-child(2) { animation-delay: 0.18s; }
        .post-item:nth-child(3) { animation-delay: 0.26s; }

        .pi-top {
            display: flex; align-items: center;
            gap: 10px; margin-bottom: 0.7rem;
        }
        .pi-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--rose-light), var(--dusty));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 0.85rem; font-weight: 700;
            color: var(--rose-deep);
            flex-shrink: 0;
        }
        .pi-meta { flex: 1; }
        .pi-name { font-weight: 600; font-size: 0.85rem; color: var(--text); }
        .pi-time { font-size: 0.7rem; color: var(--dusty); }
        .community-tag {
            font-size: 0.65rem; padding: 2px 8px;
            border-radius: 20px;
            background: var(--blush); color: var(--rose-deep);
            font-weight: 500;
        }
        .pi-content {
            font-size: 0.85rem; color: var(--text);
            line-height: 1.6;
            margin-bottom: 0.8rem;
        }
        .pi-actions {
            display: flex; gap: 1.2rem;
        }
        .pi-action {
            display: flex; align-items: center; gap: 5px;
            font-size: 0.75rem; color: var(--muted);
            cursor: pointer; transition: color 0.2s;
            background: none; border: none; padding: 0;
            font-family: 'DM Sans', sans-serif;
        }
        .pi-action:hover { color: var(--rose); }
        .pi-action svg { width: 15px; height: 15px; }

        /* ── Right column ── */
        /* Notifications */
        .notif-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex; gap: 10px;
            align-items: flex-start;
            transition: background 0.2s;
            animation: fadeUp 0.4s ease both;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: var(--cream); }
        .notif-item:nth-child(1) { animation-delay: 0.1s; }
        .notif-item:nth-child(2) { animation-delay: 0.16s; }
        .notif-item:nth-child(3) { animation-delay: 0.22s; }
        .notif-item:nth-child(4) { animation-delay: 0.28s; }

        .ni-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--rose);
            margin-top: 6px;
            flex-shrink: 0;
        }
        .ni-dot.read { background: var(--border); }
        .ni-text { font-size: 0.8rem; color: var(--text); line-height: 1.5; }
        .ni-text strong { color: var(--rose-deep); }
        .ni-time { font-size: 0.68rem; color: var(--dusty); margin-top: 2px; }

        /* Quick actions */
        .qa-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 0.7rem; padding: 1.2rem 1.5rem;
        }
        .qa-btn {
            background: var(--warm);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.8rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 500;
            display: flex; flex-direction: column;
            align-items: center; gap: 6px;
            text-decoration: none;
        }
        .qa-btn:hover { background: var(--blush); color: var(--rose-deep); border-color: var(--rose-light); transform: translateY(-2px); }
        .qa-btn svg { width: 20px; height: 20px; }

        /* Members online */
        .members-list { padding: 1rem 1.5rem; }
        .member-row {
            display: flex; align-items: center; gap: 10px;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
        }
        .member-row:last-child { border-bottom: none; }
        .m-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--rose-light), var(--mauve));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700;
            color: var(--rose-deep);
            flex-shrink: 0;
            position: relative;
        }
        .online-dot {
            position: absolute; bottom: 0; right: 0;
            width: 9px; height: 9px;
            border-radius: 50%;
            background: var(--ok);
            border: 1.5px solid #fff;
        }
        .m-name { font-size: 0.82rem; color: var(--text); font-weight: 500; flex: 1; }
        .m-community { font-size: 0.68rem; color: var(--muted); }

        /* ── Admin/Mod panel ── */
        .admin-banner {
            background: linear-gradient(135deg, #1a1030, #2d1040);
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border: 1px solid rgba(201,99,122,0.3);
        }
        .ab-left { display: flex; align-items: center; gap: 12px; }
        .ab-icon {
            width: 38px; height: 38px;
            background: rgba(201,99,122,0.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .ab-text h3 { font-size: 0.9rem; font-weight: 600; color: #fff; }
        .ab-text p  { font-size: 0.72rem; color: rgba(255,255,255,0.5); }
        .ab-actions { display: flex; gap: 0.5rem; }
        .ab-btn {
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            font-size: 0.75rem; font-weight: 500;
            cursor: pointer; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }
        .ab-btn-primary {
            background: var(--rose);
            color: #fff; border: none;
        }
        .ab-btn-primary:hover { background: var(--rose-deep); }
        .ab-btn-secondary {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.15);
        }
        .ab-btn-secondary:hover { background: rgba(255,255,255,0.15); }

        /* Responsive */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .two-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-logo">🌸</div>
        <div class="sb-brand-text">
            <div class="brand-name">Femi9</div>
            <div class="brand-sub">Your safe space</div>
        </div>
    </div>

    <div class="sb-user">
        <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="sb-user-info">
            <div class="sb-name">{{ auth()->user()->name }}</div>
            <div class="sb-role">{{ auth()->user()->phone_number }}</div>
        </div>
        @php $role = auth()->user()->role; @endphp
        <span class="role-badge role-{{ $role }}">{{ $role }}</span>
    </div>

    <nav class="sb-nav">
        <div class="sb-section-label">Main</div>

        <a href="{{ route('dashboard') }}" class="sb-link active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-1M3 6a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H9l-4 4V6z"/></svg>
            Community Feed
            <span class="notif-dot">3</span>
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Communities
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
            <span class="notif-dot">5</span>
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            My Profile
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Messages
        </a>

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'mod')
        <div class="sb-section-label">Moderation</div>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Analytics
        </a>

        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Manage Members
        </a>
        @endif

        @if(auth()->user()->role === 'admin')
        <a href="#" class="sb-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Settings
        </a>
        @endif

    </nav>

    <div class="sb-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN ═══ --}}
<div class="main">

    {{-- Top bar --}}
    <header class="topbar">
        <div class="topbar-left">
            <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 🌸</h1>
            <p>{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="topbar-right">
            <span class="tb-greeting">Welcome back, <strong>{{ auth()->user()->role }}</strong></span>
            <div class="tb-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="tb-btn" style="position:relative">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="tb-notif-badge"></span>
            </div>
            <div class="sb-avatar" style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--rose-light),var(--rose));display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:0.85rem;font-weight:700;color:var(--rose-deep);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- Content --}}
    <div class="content">

        {{-- Admin/Mod Banner --}}
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'mod')
        <div class="admin-banner">
            <div class="ab-left">
                <div class="ab-icon">
                    @if(auth()->user()->role === 'admin') 👑 @else 🛡️ @endif
                </div>
                <div class="ab-text">
                    <h3>{{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'Moderator Panel' }}</h3>
                    <p>{{ auth()->user()->role === 'admin' ? '3 pending member reports · 1 community approval' : '2 posts need review · 1 report pending' }}</p>
                </div>
            </div>
            <div class="ab-actions">
                <a href="#" class="ab-btn ab-btn-primary">View Reports</a>
                <a href="#" class="ab-btn ab-btn-secondary">Manage</a>
            </div>
        </div>
        @endif

        {{-- Welcome banner --}}
        <div class="welcome-banner">
            <div class="wb-petal wb-p1"></div>
            <div class="wb-petal wb-p2"></div>
            <div class="wb-text">
                <h2>Welcome back, <em>{{ explode(' ', auth()->user()->name)[0] }}</em>!</h2>
                <p>You have 5 new notifications and 3 community updates waiting for you.</p>
            </div>
            <div class="wb-emoji">🌸</div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon si-rose">💬</div>
                <div class="stat-num">38</div>
                <div class="stat-label">Posts this month</div>
                <div class="stat-change change-up">↑ 12% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-teal">❤️</div>
                <div class="stat-num">247</div>
                <div class="stat-label">Hearts received</div>
                <div class="stat-change change-up">↑ 8% from last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-amber">👥</div>
                <div class="stat-num">6</div>
                <div class="stat-label">Communities joined</div>
                <div class="stat-change">Same as last month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-blue">✨</div>
                <div class="stat-num">92</div>
                <div class="stat-label">Days streak</div>
                <div class="stat-change change-up">Personal best! 🎉</div>
            </div>
        </div>

        {{-- Two column --}}
        <div class="two-col">

            {{-- Feed --}}
            <div>
                <div class="section-card">
                    <div class="sc-header">
                        <div class="sc-title">Community Feed</div>
                        <a href="#" class="sc-see-all">See all →</a>
                    </div>

                    <div class="post-item">
                        <div class="pi-top">
                            <div class="pi-avatar">P</div>
                            <div class="pi-meta">
                                <div class="pi-name">Priya Ramesh</div>
                                <div class="pi-time">2 hours ago</div>
                            </div>
                            <span class="community-tag">Wellness</span>
                        </div>
                        <div class="pi-content">
                            Starting my morning with a 10-minute meditation and journaling routine has changed everything. My anxiety has reduced so much over the past month. Highly recommend this to everyone here! 💕
                        </div>
                        <div class="pi-actions">
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                42
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                8 replies
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Share
                            </button>
                        </div>
                    </div>

                    <div class="post-item">
                        <div class="pi-top">
                            <div class="pi-avatar" style="background: linear-gradient(135deg, #c4e4f2, #7ab5d4);">N</div>
                            <div class="pi-meta">
                                <div class="pi-name">Nithya Chandran</div>
                                <div class="pi-time">5 hours ago</div>
                            </div>
                            <span class="community-tag" style="background:#e8f0fd;color:#3a508b;">Career</span>
                        </div>
                        <div class="pi-content">
                            Just got promoted to Senior Engineer! 🎉 This community kept me sane through the tough times. Thank you all for the constant support and encouragement. Femi9 is truly special.
                        </div>
                        <div class="pi-actions">
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                118
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                24 replies
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Share
                            </button>
                        </div>
                    </div>

                    <div class="post-item">
                        <div class="pi-top">
                            <div class="pi-avatar" style="background: linear-gradient(135deg, #d4f2c4, #8fd47a);">L</div>
                            <div class="pi-meta">
                                <div class="pi-name">Lakshmi Venkat</div>
                                <div class="pi-time">Yesterday</div>
                            </div>
                            <span class="community-tag" style="background:#edf7f2;color:#2e6b50;">Parenting</span>
                        </div>
                        <div class="pi-content">
                            My daughter took her first steps today! 👶 Being a single mom has its challenges but moments like these make everything worth it. Grateful for this community that listens without judgement.
                        </div>
                        <div class="pi-actions">
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                96
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                15 replies
                            </button>
                            <button class="pi-action">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Share
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right column --}}
            <div style="display:flex;flex-direction:column;gap:1.5rem;">

                {{-- Notifications --}}
                <div class="section-card">
                    <div class="sc-header">
                        <div class="sc-title">Notifications</div>
                        <a href="#" class="sc-see-all">Mark all read</a>
                    </div>
                    <div class="notif-item">
                        <div class="ni-dot"></div>
                        <div>
                            <div class="ni-text"><strong>Priya Ramesh</strong> loved your post in Wellness</div>
                            <div class="ni-time">2 mins ago</div>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="ni-dot"></div>
                        <div>
                            <div class="ni-text"><strong>Deepa Krishnan</strong> replied to your comment</div>
                            <div class="ni-time">1 hour ago</div>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="ni-dot"></div>
                        <div>
                            <div class="ni-text">You were invited to <strong>Mental Health Circle</strong></div>
                            <div class="ni-time">3 hours ago</div>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="ni-dot read"></div>
                        <div>
                            <div class="ni-text"><strong>Meena Selvam</strong> started following you</div>
                            <div class="ni-time">Yesterday</div>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="section-card">
                    <div class="sc-header">
                        <div class="sc-title">Quick Actions</div>
                    </div>
                    <div class="qa-grid">
                        <a href="#" class="qa-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            New Post
                        </a>
                        <a href="#" class="qa-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Find Community
                        </a>
                        <a href="#" class="qa-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Send Message
                        </a>
                        <a href="#" class="qa-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Edit Profile
                        </a>
                    </div>
                </div>

                {{-- Online members --}}
                <div class="section-card">
                    <div class="sc-header">
                        <div class="sc-title">Members Online</div>
                        <span style="font-size:0.72rem;color:var(--ok);font-weight:600;">● 847 active</span>
                    </div>
                    <div class="members-list">
                        @foreach([['P','Priya Ramesh','Wellness'],['K','Kavitha Nair','Career'],['A','Anitha Suresh','Parenting'],['N','Nithya Chandran','Mental Health']] as $m)
                        <div class="member-row">
                            <div class="m-avatar">
                                {{ $m[0] }}
                                <span class="online-dot"></span>
                            </div>
                            <div>
                                <div class="m-name">{{ $m[1] }}</div>
                                <div class="m-community">{{ $m[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>