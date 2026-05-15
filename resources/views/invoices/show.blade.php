<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Invoice {{ $invoice->invoice_number }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --navy:    #1a2332;
    --navy2:   #243447;
    --navy3:   #2d3f55;
    --blue:    #4a90d9;
    --blue2:   #3a7bc8;
    --text:    #1a1a2e;
    --muted:   #6b7280;
    --light:   #9ba8b8;
    --border:  #e3e6ea;
    --bg:      #f0f2f5;
    --white:   #ffffff;
    --red:     #e05050;
    --green:   #166534;
    --topbar-h: 48px;
    --sidenav-w: 220px;
    --listpanel-w: 340px;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 13px;
    color: var(--text);
    background: var(--bg);
    overflow: hidden;
}

/* ── TOPBAR ── */
.topbar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 400;
    height: var(--topbar-h);
    background: var(--navy);
    display: flex; align-items: center;
    padding: 0 14px; gap: 12px;
}
.topbar-logo {
    color: #fff; font-size: 14px; font-weight: 600;
    display: flex; align-items: center; gap: 8px; white-space: nowrap;
}
.topbar-search {
    flex: 1; max-width: 360px; margin: 0 auto;
    background: var(--navy2); border-radius: 5px;
    height: 32px; display: flex; align-items: center;
    gap: 8px; padding: 0 12px;
}
.topbar-search span { font-size: 12px; color: #6b8097; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }
.topbar-right .sub-text { color: #9ba8b8; font-size: 12px; white-space: nowrap; }
.topbar-right .sub-text a { color: var(--blue); text-decoration: none; }
.topbar-icons { display: flex; gap: 10px; align-items: center; color: #9ba8b8; font-size: 17px; }
.topbar-icons span { cursor: pointer; position: relative; }
.notif-dot {
    position: absolute; top: -4px; right: -4px;
    background: var(--red); color: #fff;
    font-size: 9px; border-radius: 50%;
    width: 14px; height: 14px;
    display: flex; align-items: center; justify-content: center;
}
.topbar-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--blue); color: #fff;
    font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}

/* ── ROOT LAYOUT ── */
.root-layout {
    display: flex;
    margin-top: var(--topbar-h);
    height: calc(100vh - var(--topbar-h));
}

/* ── SIDENAV ── */
.sidenav {
    width: var(--sidenav-w);
    background: var(--navy);
    height: 100%; flex-shrink: 0;
    overflow-y: auto;
}
.snav-section { padding: 12px 0 2px; }
.snav-label {
    font-size: 10px; color: #5a6a7e;
    padding: 4px 14px; text-transform: uppercase; letter-spacing: 0.6px;
}
.snav-item {
    font-size: 13px; color: #9ba8b8;
    padding: 7px 14px; cursor: pointer;
    border-left: 3px solid transparent;
    display: flex; align-items: center; gap: 8px;
    text-decoration: none; transition: background 0.15s;
}
.snav-item:hover { background: var(--navy2); color: #cdd5df; }
.snav-item.active { color: #fff; background: var(--navy3); border-left-color: var(--blue); }
.snav-item svg { width: 15px; height: 15px; opacity: 0.75; flex-shrink: 0; }

/* ── INVOICE LIST PANEL ── */
.list-panel {
    width: var(--listpanel-w);
    background: var(--white);
    border-right: 1px solid var(--border);
    height: 100%; flex-shrink: 0;
    display: flex; flex-direction: column;
}
.lp-header {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.lp-title {
    font-size: 14px; font-weight: 600; color: var(--text);
    display: flex; align-items: center; gap: 4px;
}
.lp-actions { display: flex; gap: 5px; align-items: center; }
.lp-btn {
    width: 28px; height: 28px; border-radius: 4px; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 16px;
    background: var(--blue); color: #fff;
}
.lp-btn.sec { background: #e8f0fe; color: var(--blue); font-size: 12px; }
.lp-btn.dots { background: none; color: #888; font-size: 18px; }
.lp-search { padding: 8px 12px; border-bottom: 1px solid #f0f2f4; flex-shrink: 0; }
.lp-search input {
    width: 100%; height: 30px;
    border: 1px solid #d0d5dd; border-radius: 5px;
    padding: 0 10px 0 28px; font-size: 12px;
    outline: none; color: #333;
    background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%23aaa' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
}
.lp-scroll { flex: 1; overflow-y: auto; }

/* ── LIST ITEM ── */
.list-item {
    padding: 10px 12px 10px 36px;
    border-bottom: 1px solid #f0f2f4;
    cursor: pointer; transition: background 0.1s;
    position: relative;
}
.list-item:hover { background: #fafbff; }
.list-item.active { background: #eef4ff; border-left: 3px solid var(--blue); padding-left: 33px; }
.li-check { position: absolute; left: 12px; top: 13px; width: 14px; height: 14px; }
.li-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px; }
.li-name { font-size: 13px; font-weight: 500; color: var(--text); }
.li-amount { font-size: 13px; font-weight: 600; color: var(--text); }
.li-meta { font-size: 11px; color: #888; display: flex; gap: 5px; }
.li-meta .dot { color: #ccc; }
.li-status { font-size: 10px; font-weight: 700; margin-top: 3px; letter-spacing: 0.3px; }
.s-sent    { color: #1e40af; }
.s-paid    { color: var(--green); }
.s-draft   { color: #888; }
.s-overdue { color: var(--red); }
.s-partial { color: #92400e; }

/* ── DETAIL AREA ── */
.detail-area {
    flex: 1; min-width: 0;
    display: flex; flex-direction: column;
    height: 100%; overflow: hidden;
}

/* ── DETAIL TOOLBAR ── */
.detail-toolbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 6px 12px; min-height: 50px;
    flex-shrink: 0;
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 6px;
}
.dt-left { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.dt-inv-num { font-size: 15px; font-weight: 700; color: var(--text); }
.dt-loc { font-size: 10px; color: var(--muted); margin-bottom: 1px; }
.dt-right { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }

/* ── ACTION BUTTONS ── */
.ab {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0 10px; height: 30px;
    border: 1px solid var(--border); border-radius: 5px;
    background: var(--white); color: #444;
    font-size: 12px; font-weight: 500; cursor: pointer;
    white-space: nowrap; text-decoration: none;
    transition: background 0.1s;
}
.ab:hover { background: #f5f6f8; }
.ab svg { width: 13px; height: 13px; flex-shrink: 0; }
.ab.icon { width: 30px; padding: 0; justify-content: center; }
.ab.primary { background: var(--blue); color: #fff; border-color: var(--blue); }
.ab.primary:hover { background: var(--blue2); }
.ab.danger { color: var(--red); border-color: #fecaca; }
.ab.danger:hover { background: #fef2f2; }
.ab-group { display: inline-flex; }
.ab-group .ab:first-child { border-radius: 5px 0 0 5px; border-right: none; }
.ab-group .ab-caret {
    width: 22px; height: 30px; padding: 0;
    border: 1px solid var(--border); border-left: none; border-radius: 0 5px 5px 0;
    background: var(--white); cursor: pointer; color: #666; font-size: 11px;
    display: inline-flex; align-items: center; justify-content: center;
}
.ab-group.primary .ab:first-child { border-right: 1px solid rgba(255,255,255,0.25); }
.ab-group.primary .ab-caret { background: var(--blue); color: #fff; border-color: var(--blue); }
.ab-group.primary .ab-caret:hover { background: var(--blue2); }
.ab-divider { width: 1px; height: 22px; background: var(--border); margin: 0 2px; }

/* ── DETAIL SCROLL ── */
.detail-scroll {
    flex: 1;
    overflow-x: hidden; overflow-y: auto;
    padding: 14px 16px 40px;
    background: var(--bg);
    min-width: 0; width: 100%;
}

/* ── BANNERS ── */
.next-banner {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 8px; padding: 12px 16px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; margin-bottom: 10px;
}
.nb-left { display: flex; align-items: flex-start; gap: 10px; }
.nb-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.nb-title { font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 2px; }
.nb-body { font-size: 12px; color: var(--muted); }
.nb-body a { color: var(--blue); text-decoration: none; }
.btn-record {
    background: var(--blue); color: #fff; border: none;
    border-radius: 6px; padding: 7px 14px;
    font-size: 12px; font-weight: 500; cursor: pointer; white-space: nowrap;
}
.btn-record:hover { background: var(--blue2); }

.upi-row {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 8px; padding: 9px 14px;
    font-size: 12px; color: var(--muted);
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 12px;
}
.upi-row svg { width: 15px; height: 15px; flex-shrink: 0; }
.upi-row a { color: var(--blue); text-decoration: none; }

/* ── INVOICE PAPER ── */
.invoice-paper {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 6px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    position: relative;
}

/* ── Paper Header ── */
.paper-header {
    padding: 28px 36px 20px;
    display: flex; justify-content: space-between; align-items: flex-start;
}
.company-name { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
.company-addr { font-size: 12px; color: var(--muted); line-height: 1.7; }
.tax-inv-lbl  { font-size: 22px; font-weight: 800; color: var(--navy); letter-spacing: 0.5px; }
.inv-status-badge { margin-top: 6px; text-align: right; }

/* ── Meta table ── */
.meta-wrap { padding: 0 36px 18px; }
.meta-tbl { width: 100%; border-collapse: collapse; }
.meta-tbl td { font-size: 12px; padding: 2px 4px; color: #555; }
.meta-tbl .k   { color: #999; padding-right: 6px; white-space: nowrap; }
.meta-tbl .sep { color: #bbb; padding: 0 2px; }
.meta-tbl .v   { font-weight: 500; color: var(--text); }
.meta-tbl .gap { padding-left: 40px; }

/* ── Bill To / Ship To ── */
.addr-row {
    display: grid; grid-template-columns: 1fr 1fr;
    border-top: 1px solid #dde2e8;
    border-bottom: 1px solid #dde2e8;
    padding: 16px 36px;
}
.addr-col + .addr-col { border-left: 1px solid #dde2e8; padding-left: 24px; }
.addr-lbl { font-size: 10px; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; }
.addr-cust-name { font-size: 13px; font-weight: 600; color: var(--blue); margin-bottom: 4px; }
.addr-lines { font-size: 12px; color: #444; line-height: 1.85; }

/* ── Items table ── */
.items-tbl { width: 100%; border-collapse: collapse; font-size: 12px; table-layout: fixed; }
.items-tbl thead tr { background: #f5f7fa; }
.items-tbl th {
    padding: 10px 12px; text-align: left;
    font-size: 11px; font-weight: 600; color: #555;
    border-top: 1px solid #dde2e8; border-bottom: 1px solid #dde2e8;
    text-transform: uppercase; letter-spacing: 0.35px;
    white-space: nowrap; overflow: hidden;
}
.items-tbl th:first-child { padding-left: 36px; width: 56px; }
.items-tbl th:last-child  { padding-right: 36px; width: 120px; }
.items-tbl th.r { text-align: right; }
.items-tbl td { padding: 11px 12px; border-bottom: 1px solid #f0f2f4; vertical-align: top; overflow: hidden; }
.items-tbl td:first-child { padding-left: 36px; }
.items-tbl td:last-child  { padding-right: 36px; }
.items-tbl td.r   { text-align: right; }
.items-tbl td.num { font-size: 11px; color: #bbb; padding-top: 13px; }
.item-name { font-size: 13px; color: #333; font-weight: 500; }
.item-sub  { font-size: 11px; color: #bbb; margin-top: 2px; }

/* ── Bottom: words + totals ── */
.bottom-section {
    display: flex; align-items: flex-start;
    padding: 18px 36px;
    border-top: 1px solid #dde2e8;
    gap: 24px;
}
.words-block { flex: 1; }
.words-lbl { font-size: 11px; color: #999; margin-bottom: 5px; }
.words-val { font-size: 12px; font-style: italic; color: #444; font-weight: 500; line-height: 1.6; }
.totals-block { min-width: 260px; }
.tot-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 5px 0; font-size: 12px; color: #666;
}
.tot-row .tl { color: #999; }
.tot-row.grand {
    font-size: 13px; font-weight: 700; color: var(--text);
    border-top: 1px solid #dde2e8; padding-top: 9px; margin-top: 4px;
}

/* ── Notes + Signature ── */
.notes-sig {
    display: flex; justify-content: space-between; align-items: flex-end;
    padding: 18px 36px;
    border-top: 1px solid #f0f2f4;
}
.notes-lbl  { font-size: 11px; color: #999; margin-bottom: 4px; }
.notes-text { font-size: 12px; color: #555; line-height: 1.6; max-width: 320px; }
.sig-block  { text-align: right; }
.sig-line {
    width: 160px; border-top: 1px solid #aaa;
    margin-left: auto; padding-top: 6px;
    font-size: 11px; color: #888; text-align: center;
}

/* ── BADGE ── */
.badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; }
.badge-sent    { background: #dbeafe; color: #1e40af; }
.badge-paid    { background: #dcfce7; color: var(--green); }
.badge-draft   { background: #f1f5f9; color: #64748b; }
.badge-overdue { background: #fef2f2; color: #991b1b; }

/* ── COMMENTS PANEL ── */
.comments-panel {
    width: 340px; background: var(--white);
    border-left: 1px solid var(--border);
    height: 100%; flex-shrink: 0;
    display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.25s ease;
    position: absolute; right: 0; top: 0; bottom: 0; z-index: 200;
}
.comments-panel.open { transform: translateX(0); }
.cp-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.cp-title { font-size: 14px; font-weight: 600; color: var(--text); }
.cp-close { background: none; border: none; font-size: 18px; color: var(--red); cursor: pointer; }
.cp-editor { padding: 10px 14px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
.cp-toolbar { display: flex; gap: 4px; margin-bottom: 7px; }
.cp-fmt {
    width: 25px; height: 25px; border: 1px solid var(--border);
    border-radius: 4px; background: var(--white);
    font-size: 12px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; color: #555;
}
.cp-textarea {
    width: 100%; min-height: 58px;
    border: 1px solid var(--border); border-radius: 5px;
    padding: 7px 10px; font-size: 13px; resize: none;
    outline: none; color: #333; font-family: inherit;
}
.cp-textarea:focus { border-color: var(--blue); }
.cp-add { margin-top: 7px; background: none; border: none; color: var(--blue); font-size: 12px; cursor: pointer; }
.cp-section-lbl {
    padding: 10px 14px 6px;
    font-size: 10px; font-weight: 700; color: #666;
    text-transform: uppercase; letter-spacing: 0.4px;
    display: flex; align-items: center; gap: 8px;
}
.cp-badge {
    background: var(--blue); color: #fff; border-radius: 50%;
    width: 17px; height: 17px; font-size: 10px;
    display: flex; align-items: center; justify-content: center;
}
.cp-scroll { flex: 1; overflow-y: auto; padding: 6px 14px 14px; }
.cp-item { display: flex; gap: 9px; margin-bottom: 14px; }
.cp-avatar {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 600; flex-shrink: 0;
}
.cp-meta { display: flex; align-items: center; gap: 6px; margin-bottom: 3px; }
.cp-name { font-size: 12px; font-weight: 600; color: var(--text); }
.cp-time { font-size: 11px; color: #aaa; }
.cp-text { font-size: 12px; color: #555; line-height: 1.5; }

/* ── PRINT ── */
@media print {
    .topbar, .sidenav, .list-panel, .detail-toolbar,
    .next-banner, .upi-row, .comments-panel,
    .payments-received-section { display: none !important; }
    .root-layout { margin-top: 0; }
    .detail-area { height: auto; overflow: visible; }
    .detail-scroll { padding: 0; overflow: visible; }
    .invoice-paper { border: none; box-shadow: none; }
}

@keyframes modalIn {
    from { transform: scale(0.95); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}
</style>
</head>
<body>

{{-- ── TOPBAR ── --}}
<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 20 20" width="18" height="18" fill="#fff">
            <rect x="1" y="1" width="8" height="8" rx="1.5"/>
            <rect x="11" y="1" width="8" height="8" rx="1.5"/>
            <rect x="1" y="11" width="8" height="8" rx="1.5"/>
            <rect x="11" y="11" width="8" height="8" rx="1.5"/>
        </svg>
        Inventory
    </div>
    <div class="topbar-search">
        <svg width="13" height="13" fill="none" stroke="#6b8097" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
        <span>Search in Invoices ( / )</span>
    </div>
    <div class="topbar-right">
        <span class="sub-text">Your premi… <a href="#">Subscribe</a></span>
        <div class="topbar-icons">
            <span>&#8635;</span>
            <span style="position:relative">&#128276;<span class="notif-dot">1</span></span>
            <span>&#9881;</span>
        </div>
        <div class="topbar-avatar">J</div>
    </div>
</div>

<div class="root-layout">

    {{-- ── SIDENAV ── --}}
    <nav class="sidenav">
        <div class="snav-section">
            <a href="#" class="snav-item">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Home
            </a>
            <a href="#" class="snav-item">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                Items
            </a>
            <a href="#" class="snav-item">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                Inventory
            </a>
        </div>
        <div class="snav-section">
            <div class="snav-label">Sales</div>
            <a href="#" class="snav-item">Customers</a>
            <a href="#" class="snav-item">Sales Orders</a>
            <a href="{{ route('invoices.index') }}" class="snav-item active">Invoices</a>
            <a href="#" class="snav-item">Delivery Challans</a>
            <a href="#" class="snav-item">Payments Received</a>
            <a href="#" class="snav-item">Sales Returns</a>
            <a href="#" class="snav-item">Credit Notes</a>
        </div>
        <div class="snav-section">
            <div class="snav-label">Purchases</div>
            <div class="snav-label">Reports</div>
            <div class="snav-label">Documents</div>
            <div class="snav-label">Apps</div>
            <a href="#" class="snav-item">Zoho Payments</a>
        </div>
    </nav>

    {{-- ── LIST PANEL ── --}}
    <div class="list-panel">
        <div class="lp-header">
            <div class="lp-title">
                All Invoices
                <svg width="13" height="13" fill="none" stroke="#4a90d9" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="lp-actions">
                <button class="lp-btn" onclick="window.location='{{ route('invoices.create') }}'">+</button>
                <button class="lp-btn dots">···</button>
            </div>
        </div>
        <div class="lp-search">
            <input type="text" placeholder="Search invoices...">
        </div>
        <div class="lp-scroll">

            {{-- ✅ Active invoice (current) --}}
            <div class="list-item active">
                <input type="checkbox" class="li-check">
                <div class="li-top">
                    <span class="li-name">{{ $invoice->customer->display_name ?? 'Customer' }}</span>
                    <span class="li-amount">₹{{ number_format($invoice->grand_total, 2) }}</span>
                </div>
                <div class="li-meta">
                    <span>{{ $invoice->invoice_number }}</span>
                    <span class="dot">•</span>
                    <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                </div>
                {{-- ✅ Show payment status in list panel --}}
                @php
                    $curPayStatus = $invoice->payment_status ?? 'unpaid';
                    if ($curPayStatus === 'paid') {
                        $liStatusText  = 'PAID';
                        $liStatusClass = 's-paid';
                    } elseif ($curPayStatus === 'partial') {
                        $liStatusText  = 'PARTIALLY PAID';
                        $liStatusClass = 's-partial';
                    } elseif ($invoice->status === 'Overdue') {
                        $liStatusText  = 'OVERDUE';
                        $liStatusClass = 's-overdue';
                    } elseif ($invoice->status === 'Draft') {
                        $liStatusText  = 'DRAFT';
                        $liStatusClass = 's-draft';
                    } else {
                        $liStatusText  = 'SENT';
                        $liStatusClass = 's-sent';
                    }
                @endphp
                <div class="li-status {{ $liStatusClass }}">{{ $liStatusText }}</div>
            </div>

            {{-- ✅ Other invoices with payment status --}}
            @php
                $otherInvoices = \App\Models\Invoice::with('customer')
                    ->where('id', '!=', $invoice->id)
                    ->latest()->take(15)->get();
            @endphp
            @foreach($otherInvoices as $other)
            @php
                $otherPayStatus = $other->payment_status ?? 'unpaid';
                if ($otherPayStatus === 'paid') {
                    $otherStatusText  = 'PAID';
                    $otherStatusClass = 's-paid';
                } elseif ($otherPayStatus === 'partial') {
                    $otherStatusText  = 'PARTIALLY PAID';
                    $otherStatusClass = 's-partial';
                } elseif ($other->status === 'Overdue') {
                    $otherStatusText  = 'OVERDUE';
                    $otherStatusClass = 's-overdue';
                } elseif ($other->status === 'Draft') {
                    $otherStatusText  = 'DRAFT';
                    $otherStatusClass = 's-draft';
                } else {
                    $otherStatusText  = 'SENT';
                    $otherStatusClass = 's-sent';
                }
            @endphp
            <div class="list-item" onclick="window.location='{{ route('invoices.show', $other->id) }}'">
                <input type="checkbox" class="li-check" onclick="event.stopPropagation()">
                <div class="li-top">
                    <span class="li-name">{{ $other->customer->display_name ?? 'Customer' }}</span>
                    <span class="li-amount">₹{{ number_format($other->grand_total, 2) }}</span>
                </div>
                <div class="li-meta">
                    <span>{{ $other->invoice_number }}</span>
                    <span class="dot">•</span>
                    <span>{{ \Carbon\Carbon::parse($other->invoice_date)->format('d/m/Y') }}</span>
                </div>
                <div class="li-status {{ $otherStatusClass }}">{{ $otherStatusText }}</div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- ── DETAIL AREA ── --}}
    <div class="detail-area">

        {{-- ── TOOLBAR ── --}}
        <div class="detail-toolbar">
            <div class="dt-left">
                <div>
                    <div class="dt-loc">Location: {{ $locationName ?? 'Head Office' }}</div>
                    <div class="dt-inv-num">{{ $invoice->invoice_number }}</div>
                </div>
                <span class="badge badge-{{ strtolower($invoice->status) }}">{{ $invoice->status }}</span>
            </div>
            <div class="dt-right">
                <button class="ab icon" title="Attachments">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="14" height="14">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                    </svg>
                </button>
                <button class="ab icon" title="Comments" onclick="toggleComments()">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="14" height="14">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </button>
                <div class="ab-divider"></div>
                <a href="#" class="ab">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </a>
                <button class="ab-caret">▾</button>
                <button class="ab">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                        <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
                    </svg>
                    Share
                </button>
                <div class="ab-group">
                    <button class="ab">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        Remind
                    </button>
                    <button class="ab-caret">▾</button>
                </div>
                <div class="ab-group">
                    <button class="ab" onclick="window.print()">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                            <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF/Print
                    </button>
                    <button class="ab-caret">▾</button>
                </div>
                <div class="ab-group primary">
                    {{-- NEW --}}
<button class="ab primary" onclick="openPaymentModal()">
    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
        <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
    </svg>
    Record Payment
</button>
                    <button class="ab-caret">▾</button>
                </div>
                <button class="ab icon">···</button>
                <button class="ab icon danger" onclick="history.back()">✕</button>
            </div>
        </div>

        {{-- ── SCROLLABLE CONTENT ── --}}
        <div class="detail-scroll">

            @if(session('success'))
            <div style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;
                        padding:10px 14px;border-radius:6px;margin-bottom:12px;font-size:13px;">
                {{ session('success') }}
            </div>
            @endif

            {{-- ✅ PHP variables — top-ல் ஒரே ஒரு முறை define --}}
            @php
                $amountReceived = floatval($invoice->amount_received ?? 0);
                $balanceDue     = floatval($invoice->balance_due ?? $invoice->grand_total);
                $payStatus      = $invoice->payment_status ?? 'unpaid';
            @endphp

            {{-- ✅ What's Next Banner — paid-ஆ இருந்தா காட்டாதே --}}
            @if(in_array($invoice->status, ['Overdue','Sent']) && $payStatus !== 'paid')
            <div class="next-banner">
                <div class="nb-left">
                    <div class="nb-icon">✦</div>
                    <div>
                        <div class="nb-title">WHAT'S NEXT?</div>
                        <div class="nb-body">
                            @if($payStatus === 'partial')
                                Invoice has been partially paid. Record payment for the remaining amount.
                            @elseif($invoice->status === 'Overdue')
                                Payment is overdue. Send a payment reminder or record the payment.
                            @else
                                Payment is pending. Send a payment reminder or record the payment.
                            @endif
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                </div>
              <button class="btn-record" onclick="openPaymentModal()">Record Payment</button>
            </div>
            @endif

            {{-- UPI Row --}}
            <div class="upi-row">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                </svg>
                Get paid faster by
                <a href="#" style="margin:0 4px">setting up payment gateways</a> or
                <a href="#" style="margin:0 4px">display a UPI QR code</a>.
            </div>

            {{-- ✅ Payments Received — invoice paper வெளியே, UPI-க்கு கீழே --}}
           @if(isset($paymentRecords) && $paymentRecords->count() > 0)
<div class="payments-received-section"
     style="background:#fff;border:1px solid #e3e6ea;border-radius:8px;
            margin-bottom:12px;overflow:hidden;">
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:12px 16px;cursor:pointer;user-select:none;"
         onclick="var p=this.nextElementSibling;
                  p.style.display=p.style.display==='none'?'block':'none';
                  this.querySelector('.pr-arrow').style.transform=
                  p.style.display==='block'?'rotate(90deg)':'rotate(0deg)'">
        <span style="font-size:13px;font-weight:600;color:#1a1a2e;">
            Payments Received
            <span style="display:inline-flex;align-items:center;justify-content:center;
                         width:20px;height:20px;background:#e8f0fe;color:#4a90d9;
                         border-radius:50%;font-size:11px;margin-left:6px;font-weight:700;">
                {{ $paymentRecords->count() }}
            </span>
        </span>
        <svg class="pr-arrow" width="12" height="12" fill="none" stroke="#888"
             stroke-width="2" viewBox="0 0 24 24"
             style="transition:transform 0.2s;flex-shrink:0;">
            <path d="M9 18l6-6-6-6"/>
        </svg>
    </div>
    <div style="display:none;border-top:1px solid #f0f2f4;">
        <table style="width:100%;border-collapse:collapse;font-size:12px;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th style="padding:9px 16px;text-align:left;color:#666;font-weight:600;
                               font-size:11px;text-transform:uppercase;
                               border-bottom:1px solid #e8eaed;">Date</th>
                    <th style="padding:9px 12px;text-align:left;color:#666;font-weight:600;
                               font-size:11px;text-transform:uppercase;
                               border-bottom:1px solid #e8eaed;">Payment #</th>
                    <th style="padding:9px 12px;text-align:left;color:#666;font-weight:600;
                               font-size:11px;text-transform:uppercase;
                               border-bottom:1px solid #e8eaed;">Mode</th>
                    <th style="padding:9px 16px;text-align:right;color:#666;font-weight:600;
                               font-size:11px;text-transform:uppercase;
                               border-bottom:1px solid #e8eaed;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentRecords as $pmt)
                <tr>
                    <td style="padding:10px 16px;color:#333;">
                        {{ \Carbon\Carbon::parse($pmt->payment_date)->format('d/m/Y') }}
                    </td>
                    <td style="padding:10px 12px;">
                        <span style="color:#4a90d9;font-weight:500;">
                            {{ $pmt->payment_no }}
                        </span>
                    </td>
                    <td style="padding:10px 12px;color:#555;text-transform:capitalize;">
                        {{ $pmt->payment_mode ?? 'Cash' }}
                    </td>
                    <td style="padding:10px 16px;text-align:right;font-weight:600;color:#166534;">
                        ₹{{ number_format($pmt->amount_received, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════
     இந்த ENTIRE BLOCK-ஐ உங்கள் show.blade.php-ல்
     UPI row-க்கு கீழே, invoice-paper div-க்கு மேலே paste பண்ணுங்க
     ═══════════════════════════════════════════════════════ --}}

{{-- ── Payments Table (only when payments exist) ── --}}
@if(isset($paymentRecords) && $paymentRecords->count() > 0)
<div style="background:#fff;border:1px solid #e3e6ea;border-radius:8px;
            margin-bottom:14px;overflow:hidden;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:12px 16px;border-bottom:1px solid #e3e6ea;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:13px;font-weight:700;color:#1a1a2e;">Payments Received</span>
            <span style="background:#4a90d9;color:#fff;border-radius:50%;
                         width:18px;height:18px;font-size:10px;font-weight:700;
                         display:inline-flex;align-items:center;justify-content:center;"
                  id="payment-count">{{ $paymentRecords->count() }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:12px;color:#6b7280;">Balance Due:</span>
            <strong id="balance-due-display"
                    style="font-size:14px;
                           color:{{ ($invoice->payment_status ?? 'unpaid') === 'paid' ? '#166534' : '#dc2626' }}">
                ₹{{ number_format($invoice->balance_due ?? $invoice->grand_total, 2) }}
            </strong>
            <span id="payment-status-badge"
                  style="padding:2px 10px;border-radius:10px;font-size:11px;font-weight:700;
                  background:{{ match($invoice->payment_status ?? 'unpaid') {
                      'paid' => '#dcfce7', 'partial' => '#fef3c7', default => '#fef2f2'
                  } }};
                  color:{{ match($invoice->payment_status ?? 'unpaid') {
                      'paid' => '#166534', 'partial' => '#92400e', default => '#991b1b'
                  } }}">
                {{ ucfirst($invoice->payment_status ?? 'unpaid') }}
            </span>
        </div>
    </div>

    {{-- Table --}}
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
        <thead>
            <tr style="background:#f8f9fb;">
                <th style="padding:9px 16px;text-align:left;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Date</th>
                <th style="padding:9px 8px;text-align:left;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Payment #</th>
                <th style="padding:9px 8px;text-align:left;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Reference #</th>
                <th style="padding:9px 8px;text-align:left;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Mode</th>
                <th style="padding:9px 8px;text-align:left;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Status</th>
                <th style="padding:9px 16px 9px 8px;text-align:right;color:#6b7280;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:0.3px;border-bottom:1px solid #e3e6ea;">Amount</th>
                <th style="padding:9px 8px;width:60px;border-bottom:1px solid #e3e6ea;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentRecords as $pmt)
            <tr id="pmt-row-{{ $pmt->id }}"
                style="border-bottom:1px solid #f0f2f4;
                       {{ $pmt->status === 'refunded' ? 'opacity:0.55;text-decoration:line-through;' : '' }}
                       transition:opacity 0.3s;">
                <td style="padding:10px 16px;color:#374151;">
                    {{ \Carbon\Carbon::parse($pmt->payment_date)->format('d/m/Y') }}
                </td>
                <td style="padding:10px 8px;">
                    <span style="font-weight:600;color:{{ $pmt->status === 'refunded' ? '#9ca3af' : '#4a90d9' }}">
                        {{ $pmt->payment_no }}
                    </span>
                </td>
                <td style="padding:10px 8px;color:#9ca3af;">
                    {{ $pmt->reference_no ?? '—' }}
                </td>
                <td style="padding:10px 8px;color:#6b7280;">
                    {{ $pmt->payment_mode }}
                </td>
                <td style="padding:10px 8px;">
                    @if($pmt->status === 'refunded')
                        <span style="background:#f3f4f6;color:#6b7280;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">Refunded</span>
                    @elseif($pmt->status === 'draft')
                        <span style="background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">Draft</span>
                    @else
                        <span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700;">Paid</span>
                    @endif
                </td>
                <td style="padding:10px 16px 10px 8px;text-align:right;font-weight:600;color:#1a1a2e;">
                    @if($pmt->status === 'refunded')
                        <span style="color:#9ca3af;text-decoration:line-through;">₹{{ number_format($pmt->amount_received, 2) }}</span>
                        <span style="background:#fef2f2;color:#dc2626;font-size:10px;padding:1px 6px;border-radius:8px;margin-left:4px;font-weight:700;">Refunded</span>
                    @else
                        ₹{{ number_format($pmt->amount_received, 2) }}
                    @endif
                </td>
                <td style="padding:10px 8px;text-align:center;">
                    @if($pmt->status !== 'refunded')
                    {{-- 3-dot dropdown --}}
                    <div style="position:relative;display:inline-block;" class="pmt-dropdown-wrap">
                        <button onclick="togglePmtDropdown({{ $pmt->id }})"
                                style="background:none;border:1px solid #e3e6ea;border-radius:4px;
                                       padding:2px 8px;cursor:pointer;color:#666;font-size:16px;
                                       line-height:1;">···</button>
                        <div id="pmt-drop-{{ $pmt->id }}"
                             style="display:none;position:fixed;background:#fff;
                                    border:1px solid #e3e6ea;border-radius:6px;
                                    box-shadow:0 4px 16px rgba(0,0,0,0.12);
                                    z-index:9999;min-width:130px;overflow:hidden;">
                            <button onclick="closePmtDropdowns();openEditModal({{ $pmt->id }},'{{ $pmt->payment_date }}',{{ $pmt->amount_received }},'{{ $pmt->payment_mode }}','{{ $pmt->deposit_to ?? 'Cash' }}','{{ $pmt->reference_no ?? '' }}',@js($pmt->notes ?? ''))"
                                    style="display:flex;align-items:center;gap:8px;width:100%;
                                           padding:9px 14px;background:none;border:none;
                                           font-size:13px;cursor:pointer;color:#374151;text-align:left;">
                                ✏️ Edit
                            </button>
                            <button onclick="closePmtDropdowns();openRefundModal({{ $pmt->id }},{{ $pmt->amount_received }},'{{ $pmt->payment_mode }}')"
                                    style="display:flex;align-items:center;gap:8px;width:100%;
                                           padding:9px 14px;background:none;border:none;
                                           font-size:13px;cursor:pointer;color:#374151;text-align:left;">
                                🔄 Refund
                            </button>
                            <div style="height:1px;background:#f0f2f4;margin:2px 0;"></div>
                            <button onclick="closePmtDropdowns();openDeleteModal({{ $pmt->id }},{{ $pmt->amount_received }})"
                                    style="display:flex;align-items:center;gap:8px;width:100%;
                                           padding:9px 14px;background:none;border:none;
                                           font-size:13px;cursor:pointer;color:#dc2626;text-align:left;">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>

        {{-- Footer totals --}}
        @php
            $activeTotal   = $paymentRecords->where('status','!=','refunded')->sum('amount_received');
            $refundedTotal = $paymentRecords->where('status','refunded')->sum('amount_received');
        @endphp
        <tfoot>
            @if($refundedTotal > 0)
            <tr style="background:#fef2f2;">
                <td colspan="5" style="padding:7px 16px;text-align:right;font-size:12px;color:#6b7280;">Total Refunded</td>
                <td style="padding:7px 16px 7px 8px;text-align:right;font-size:12px;font-weight:600;color:#dc2626;">
                    − ₹{{ number_format($refundedTotal, 2) }}
                </td>
                <td></td>
            </tr>
            @endif
            <tr style="background:#f8fffe;">
                <td colspan="5" style="padding:7px 16px;text-align:right;font-size:12px;color:#6b7280;font-weight:600;">Total Paid (Active)</td>
                <td style="padding:7px 16px 7px 8px;text-align:right;font-size:12px;font-weight:700;color:#166534;">
                    ₹{{ number_format($activeTotal, 2) }}
                </td>
                <td></td>
            </tr>
            <tr style="background:#f8f9fb;border-top:2px solid #e3e6ea;">
                <td colspan="5" style="padding:9px 16px;text-align:right;font-size:12px;color:#374151;font-weight:700;">Balance Due</td>
                <td style="padding:9px 16px 9px 8px;text-align:right;font-size:13px;font-weight:800;
                            color:{{ ($invoice->payment_status ?? '') === 'paid' ? '#166534' : '#dc2626' }}">
                    ₹{{ number_format($invoice->balance_due ?? 0, 2) }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════
     MODALS — @if block-க்கு வெளியே, always render
     (Bootstrap JS-ல் இருந்து control ஆகும்)
     ═══════════════════════════════════════════════════════ --}}

{{-- MODAL 1: EDIT PAYMENT --}}
<div id="editPaymentOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);
            z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:560px;max-height:90vh;
                overflow-y:auto;box-shadow:0 12px 40px rgba(0,0,0,0.2);
                animation:modalIn 0.2s ease;padding:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid #e3e6ea;">
            <h5 style="margin:0;font-size:15px;font-weight:700;color:#1a1a2e;">✏️ Edit Payment</h5>
            <button onclick="document.getElementById('editPaymentOverlay').style.display='none'"
                    style="background:none;border:none;font-size:20px;color:#888;cursor:pointer;">✕</button>
        </div>
        <div style="padding:20px;">
            <input type="hidden" id="edit_payment_id">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Amount Received *</label>
                    <div style="display:flex;">
                        <span style="background:#f3f4f6;border:1px solid #d1d5db;border-right:none;
                                     border-radius:5px 0 0 5px;padding:0 10px;display:flex;
                                     align-items:center;font-size:13px;color:#6b7280;">₹</span>
                        <input type="number" id="edit_amount_received" step="0.01" min="0.01"
                               style="flex:1;height:36px;border:1px solid #d1d5db;border-radius:0 5px 5px 0;
                                      padding:0 10px;font-size:13px;outline:none;">
                    </div>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Payment Date *</label>
                    <input type="date" id="edit_payment_date"
                           style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                  padding:0 10px;font-size:13px;outline:none;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Payment Mode *</label>
                    <select id="edit_payment_mode"
                            style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                   padding:0 10px;font-size:13px;outline:none;">
                        @foreach(['Cash','Bank Transfer','Bank Remittance','Cheque','Credit Card','UPI'] as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Deposit To *</label>
                    <select id="edit_deposit_to"
                            style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                   padding:0 10px;font-size:13px;outline:none;">
                        @foreach(['Cash','Petty Cash','Bank'] as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Reference #</label>
                    <input type="text" id="edit_reference_no" placeholder="Optional"
                           style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                  padding:0 10px;font-size:13px;outline:none;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:5px;">Notes</label>
                    <textarea id="edit_notes" rows="2"
                              style="width:100%;border:1px solid #d1d5db;border-radius:5px;
                                     padding:8px 10px;font-size:13px;outline:none;resize:none;"></textarea>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;padding:14px 20px;border-top:1px solid #e3e6ea;background:#fafbfc;">
            <button onclick="document.getElementById('editPaymentOverlay').style.display='none'"
                    style="height:36px;padding:0 20px;background:#fff;border:1px solid #d1d5db;
                           border-radius:5px;font-size:13px;cursor:pointer;">Cancel</button>
            <button onclick="saveEditPayment()"
                    style="height:36px;padding:0 20px;background:#4a90d9;color:#fff;
                           border:none;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;"
                    id="editSaveBtn">
                <span id="editSpinner" style="display:none;">⏳ </span>Save Changes
            </button>
        </div>
    </div>
</div>

{{-- MODAL 2: REFUND PAYMENT (Zoho exact style) --}}
<div id="refundPaymentOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);
            z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:680px;max-height:90vh;
                overflow-y:auto;box-shadow:0 12px 40px rgba(0,0,0,0.2);
                animation:modalIn 0.2s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 24px;border-bottom:1px solid #e3e6ea;">
            <h5 style="margin:0;font-size:15px;font-weight:700;color:#1a1a2e;">Payment Refund</h5>
            <button onclick="document.getElementById('refundPaymentOverlay').style.display='none'"
                    style="background:none;border:none;font-size:22px;color:#dc2626;cursor:pointer;line-height:1;">✕</button>
        </div>
        <div style="padding:24px;">
            <input type="hidden" id="refund_payment_id">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                {{-- Payment Amount --}}
                <div>
                    <label style="font-size:12px;color:#374151;font-weight:500;display:block;margin-bottom:5px;">Payment Amount</label>
                    <div style="display:flex;">
                        <span style="background:#f3f4f6;border:1px solid #d1d5db;border-right:none;
                                     border-radius:5px 0 0 5px;padding:0 10px;display:flex;
                                     align-items:center;font-size:13px;color:#6b7280;">₹</span>
                        <input type="text" id="refund_amount_display" readonly
                               style="flex:1;height:36px;border:1px solid #d1d5db;border-radius:0 5px 5px 0;
                                      padding:0 10px;font-size:13px;background:#f9fafb;font-weight:600;">
                    </div>
                </div>
                {{-- Refunded On --}}
                <div>
                    <label style="font-size:12px;color:#dc2626;font-weight:600;display:block;margin-bottom:5px;">Refunded On *</label>
                    <input type="date" id="refund_refunded_on"
                           style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                  padding:0 10px;font-size:13px;outline:none;">
                </div>
                {{-- Payment Mode --}}
                <div>
                    <label style="font-size:12px;color:#374151;font-weight:500;display:block;margin-bottom:5px;">Payment Mode</label>
                    <select id="refund_payment_mode"
                            style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                   padding:0 10px;font-size:13px;outline:none;">
                        @foreach(['Cash','Bank Transfer','Bank Remittance','Cheque','Credit Card','UPI'] as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Reference # --}}
                <div>
                    <label style="font-size:12px;color:#374151;font-weight:500;display:block;margin-bottom:5px;">Reference #</label>
                    <input type="text" id="refund_reference_no" placeholder="Optional"
                           style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                  padding:0 10px;font-size:13px;outline:none;">
                </div>
                {{-- From Account --}}
                <div>
                    <label style="font-size:12px;color:#dc2626;font-weight:600;display:block;margin-bottom:5px;">From Account *</label>
                    <select id="refund_from_account"
                            style="width:100%;height:36px;border:1px solid #d1d5db;border-radius:5px;
                                   padding:0 10px;font-size:13px;outline:none;">
                        @foreach(['Petty Cash','Cash','Bank'] as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Description --}}
                <div>
                    <label style="font-size:12px;color:#374151;font-weight:500;display:block;margin-bottom:5px;">Description</label>
                    <textarea id="refund_description" rows="2" placeholder="Reason for refund..."
                              style="width:100%;border:1px solid #d1d5db;border-radius:5px;
                                     padding:8px 10px;font-size:13px;outline:none;resize:none;"></textarea>
                </div>
            </div>

            {{-- Invoice row table (like Zoho) --}}
            <table style="width:100%;border-collapse:collapse;border:1px solid #e3e6ea;border-radius:6px;overflow:hidden;font-size:12px;">
                <thead>
                    <tr style="background:#f8f9fb;">
                        <th style="padding:9px 14px;text-align:left;color:#6b7280;font-weight:600;text-transform:uppercase;font-size:11px;border-bottom:1px solid #e3e6ea;">Invoice #</th>
                        <th style="padding:9px 14px;text-align:left;color:#6b7280;font-weight:600;text-transform:uppercase;font-size:11px;border-bottom:1px solid #e3e6ea;">Invoice Date</th>
                        <th style="padding:9px 14px;text-align:right;color:#6b7280;font-weight:600;text-transform:uppercase;font-size:11px;border-bottom:1px solid #e3e6ea;">Refund Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:10px 14px;font-weight:600;color:#1a1a2e;">{{ $invoice->invoice_number }}</td>
                        <td style="padding:10px 14px;color:#374151;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</td>
                        <td style="padding:10px 14px;text-align:right;font-weight:700;color:#374151;" id="refund_row_amount">—</td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size:12px;color:#6b7280;margin-top:14px;margin-bottom:0;line-height:1.6;">
                Note: Once you save this refund, the payment received will be dissociated from the related invoice(s), changing the invoice status to Unpaid.
            </p>
        </div>
        <div style="display:flex;gap:10px;padding:14px 24px;border-top:1px solid #e3e6ea;background:#fafbfc;">
            <button onclick="saveRefund()"
                    style="height:36px;padding:0 24px;background:#4a90d9;color:#fff;
                           border:none;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;"
                    id="refundSaveBtn">
                <span id="refundSpinner" style="display:none;">⏳ </span>Save
            </button>
            <button onclick="document.getElementById('refundPaymentOverlay').style.display='none'"
                    style="height:36px;padding:0 20px;background:#fff;border:1px solid #d1d5db;
                           border-radius:5px;font-size:13px;cursor:pointer;">Cancel</button>
        </div>
    </div>
</div>

{{-- MODAL 3: DELETE PAYMENT (2-step, Zoho exact) --}}
<div id="deletePaymentOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);
            z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:480px;
                box-shadow:0 12px 40px rgba(0,0,0,0.2);animation:modalIn 0.2s ease;overflow:hidden;">

        {{-- Step 1: Choose --}}
        <div id="del-step-1">
            <div style="display:flex;align-items:center;justify-content:space-between;
                        padding:16px 20px;border-bottom:1px solid #e3e6ea;">
                <h5 style="margin:0;font-size:15px;font-weight:700;color:#1a1a2e;">Delete Recorded Payment?</h5>
                <button onclick="closeDeleteModal()"
                        style="background:none;border:none;font-size:18px;color:#888;cursor:pointer;">✕</button>
            </div>
            <div style="padding:20px;">
                <div style="display:flex;gap:12px;align-items:flex-start;
                            background:#fefce8;border:1px solid #fde047;border-radius:8px;
                            padding:14px;margin-bottom:20px;">
                    <span style="font-size:20px;flex-shrink:0;">⚠️</span>
                    <p style="margin:0;font-size:13px;color:#6b7280;line-height:1.5;">
                        You're deleting a payment of
                        <strong style="color:#1a1a2e;" id="del_amount_display">₹0.00</strong>.
                        You can either dissociate this payment from this invoice and add it as a
                        credit to the customer or you can delete this payment entirely.
                    </p>
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <button onclick="showDelStep2('dissociate')"
                            style="display:flex;align-items:center;justify-content:space-between;
                                   padding:14px 16px;border:1px solid #e3e6ea;border-radius:8px;
                                   background:#fff;cursor:pointer;text-align:left;width:100%;">
                        <span style="font-size:13px;color:#374151;font-weight:500;">Dissociate &amp; Add As Credit</span>
                        <span style="color:#f97316;font-size:16px;">›</span>
                    </button>
                    <button onclick="showDelStep2('delete')"
                            style="display:flex;align-items:center;justify-content:space-between;
                                   padding:14px 16px;border:1px solid #e3e6ea;border-radius:8px;
                                   background:#fff;cursor:pointer;text-align:left;width:100%;">
                        <span style="font-size:13px;color:#374151;font-weight:500;">Delete Payment</span>
                        <span style="color:#f97316;font-size:16px;">›</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Step 2a: Dissociate --}}
        <div id="del-step-2-dissociate" style="display:none;">
            <div style="padding:24px;">
                <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;">
                    <span style="font-size:24px;flex-shrink:0;">⚠️</span>
                    <div>
                        <h5 style="margin:0 0 8px;font-size:15px;font-weight:700;color:#1a1a2e;">
                            Dissociate and record it as an advance payment?
                        </h5>
                        <p style="margin:0;font-size:13px;color:#6b7280;line-height:1.5;">
                            This payment will be dissociated from this invoice and will be recorded as
                            an advance payment from the customer.
                        </p>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:10px;padding:14px 24px;border-top:1px solid #e3e6ea;background:#fafbfc;">
                <button onclick="confirmDelete('dissociate_credit')"
                        style="height:36px;padding:0 20px;background:#4a90d9;color:#fff;
                               border:none;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;"
                        id="dissociateConfirmBtn">
                    <span id="dissociateSpinner" style="display:none;">⏳ </span>
                    Dissociate &amp; Add As Credit
                </button>
                <button onclick="showDelStep1()"
                        style="height:36px;padding:0 16px;background:#fff;border:1px solid #d1d5db;
                               border-radius:5px;font-size:13px;cursor:pointer;">Cancel</button>
            </div>
        </div>

        {{-- Step 2b: Hard Delete --}}
        <div id="del-step-2-delete" style="display:none;">
            <div style="padding:24px;">
                <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:16px;">
                    <span style="font-size:24px;flex-shrink:0;">⚠️</span>
                    <div>
                        <h5 style="margin:0 0 8px;font-size:15px;font-weight:700;color:#1a1a2e;">Delete payment?</h5>
                        <p style="margin:0;font-size:13px;color:#6b7280;">
                            Once you delete this payment, you will not be able to retrieve it.
                        </p>
                    </div>
                </div>
            </div>
            <div style="display:flex;gap:10px;padding:14px 24px;border-top:1px solid #e3e6ea;background:#fafbfc;">
                <button onclick="confirmDelete('delete')"
                        style="height:36px;padding:0 20px;background:#dc2626;color:#fff;
                               border:none;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;"
                        id="deleteConfirmBtn">
                    <span id="deleteSpinner" style="display:none;">⏳ </span>Delete
                </button>
                <button onclick="showDelStep1()"
                        style="height:36px;padding:0 16px;background:#fff;border:1px solid #d1d5db;
                               border-radius:5px;font-size:13px;cursor:pointer;">Cancel</button>
            </div>
        </div>

    </div>
</div>

{{-- Toast container --}}
<div id="_pmt_toast_wrap"
     style="position:fixed;bottom:20px;right:20px;z-index:9999;
            display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>


{{-- ═══════════════════════════════════════════════
     JAVASCRIPT for all 3 modals
     ═══════════════════════════════════════════════ --}}
<script>
const INV_ID   = {{ $invoice->id }};
const CSRF_TOK = document.querySelector('meta[name="csrf-token"]').content;

// ── Dropdown ─────────────────────────────────────────────────────────────────
function togglePmtDropdown(id) {
    const drop = document.getElementById('pmt-drop-' + id);
    const btn  = drop.previousElementSibling;
    const rect = btn.getBoundingClientRect();

    // Close others
    document.querySelectorAll('[id^="pmt-drop-"]').forEach(d => {
        if (d.id !== 'pmt-drop-' + id) d.style.display = 'none';
    });

    if (drop.style.display === 'none') {
        drop.style.display = 'block';
        // Position below button
        drop.style.top  = (rect.bottom + window.scrollY + 4) + 'px';
        drop.style.left = (rect.right - 130 + window.scrollX) + 'px';
    } else {
        drop.style.display = 'none';
    }
}

function closePmtDropdowns() {
    document.querySelectorAll('[id^="pmt-drop-"]').forEach(d => d.style.display = 'none');
}

// Close on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('.pmt-dropdown-wrap')) closePmtDropdowns();
});

// ── Toast ─────────────────────────────────────────────────────────────────────
function pmtToast(msg, type = 'success') {
    const wrap = document.getElementById('_pmt_toast_wrap');
    const id   = 'pt_' + Date.now();
    const bg   = type === 'success' ? '#166534' : '#991b1b';
    const bg2  = type === 'success' ? '#dcfce7' : '#fef2f2';
    const el   = document.createElement('div');
    el.id = id;
    el.style.cssText = `background:${bg2};color:${bg};border:1px solid ${bg};
        border-radius:8px;padding:12px 16px;font-size:13px;font-weight:500;
        box-shadow:0 4px 12px rgba(0,0,0,0.12);pointer-events:all;
        min-width:280px;display:flex;align-items:center;gap:8px;`;
    el.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span><span>${msg}</span>
        <button onclick="this.parentElement.remove()"
                style="margin-left:auto;background:none;border:none;cursor:pointer;color:${bg};font-size:16px;">✕</button>`;
    wrap.appendChild(el);
    setTimeout(() => el.remove(), 5000);
}

// ── Refresh balance UI ────────────────────────────────────────────────────────
function refreshPaymentUI(data) {
    const balEl   = document.getElementById('balance-due-display');
    const badgeEl = document.getElementById('payment-status-badge');
    const s       = data.payment_status;

    if (balEl) {
        balEl.textContent = '₹' + parseFloat(data.new_balance).toLocaleString('en-IN',
            { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        balEl.style.color = s === 'paid' ? '#166534' : '#dc2626';
    }
    if (badgeEl) {
        badgeEl.textContent = s.charAt(0).toUpperCase() + s.slice(1);
        const colors = {
            paid:    { bg:'#dcfce7', fg:'#166534' },
            partial: { bg:'#fef3c7', fg:'#92400e' },
            unpaid:  { bg:'#fef2f2', fg:'#991b1b' },
        };
        const c = colors[s] ?? colors.unpaid;
        badgeEl.style.background = c.bg;
        badgeEl.style.color      = c.fg;
    }
}

// ═══════════════════════════════════════════════
// EDIT
// ═══════════════════════════════════════════════
function openEditModal(id, date, amount, mode, depositTo, refNo, notes) {
    document.getElementById('edit_payment_id').value      = id;
    document.getElementById('edit_payment_date').value    = date;
    document.getElementById('edit_amount_received').value = amount;
    document.getElementById('edit_reference_no').value    = refNo;
    document.getElementById('edit_notes').value           = notes;

    ['edit_payment_mode','edit_deposit_to'].forEach(selId => {
        const val = selId === 'edit_payment_mode' ? mode : depositTo;
        const sel = document.getElementById(selId);
        [...sel.options].forEach(o => o.selected = (o.value === val));
    });

    document.getElementById('editPaymentOverlay').style.display = 'flex';
}

function saveEditPayment() {
    const id     = document.getElementById('edit_payment_id').value;
    const amount = parseFloat(document.getElementById('edit_amount_received').value);
    const btn    = document.getElementById('editSaveBtn');
    const spin   = document.getElementById('editSpinner');

    if (!amount || amount <= 0) { pmtToast('Enter a valid amount', 'error'); return; }

    spin.style.display = 'inline'; btn.disabled = true;

    fetch(`/invoices/${INV_ID}/payments/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOK },
        body: JSON.stringify({
            _method:         'PUT',
            amount_received: amount,
            payment_date:    document.getElementById('edit_payment_date').value,
            payment_mode:    document.getElementById('edit_payment_mode').value,
            deposit_to:      document.getElementById('edit_deposit_to').value,
            reference_no:    document.getElementById('edit_reference_no').value,
            notes:           document.getElementById('edit_notes').value,
        }),
    })
    .then(r => r.json())
    .then(d => {
        spin.style.display = 'none'; btn.disabled = false;
        if (d.success) {
            document.getElementById('editPaymentOverlay').style.display = 'none';
            pmtToast(d.message);
            refreshPaymentUI(d);
            setTimeout(() => location.reload(), 1200);
        } else {
            pmtToast(d.message || 'Update failed', 'error');
        }
    })
    .catch(() => { spin.style.display = 'none'; btn.disabled = false; pmtToast('Network error', 'error'); });
}

// ═══════════════════════════════════════════════
// REFUND
// ═══════════════════════════════════════════════
function openRefundModal(id, amount, mode) {
    document.getElementById('refund_payment_id').value     = id;
    document.getElementById('refund_amount_display').value = parseFloat(amount).toFixed(2);
    document.getElementById('refund_row_amount').textContent =
        '₹' + parseFloat(amount).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    document.getElementById('refund_refunded_on').value    = new Date().toISOString().split('T')[0];
    document.getElementById('refund_reference_no').value   = '';
    document.getElementById('refund_description').value    = '';

    const sel = document.getElementById('refund_payment_mode');
    [...sel.options].forEach(o => o.selected = (o.value === mode));

    document.getElementById('refundPaymentOverlay').style.display = 'flex';
}

function saveRefund() {
    const id   = document.getElementById('refund_payment_id').value;
    const date = document.getElementById('refund_refunded_on').value;
    const btn  = document.getElementById('refundSaveBtn');
    const spin = document.getElementById('refundSpinner');

    if (!date) { pmtToast('Please select refund date', 'error'); return; }

    spin.style.display = 'inline'; btn.disabled = true;

    fetch(`/invoices/${INV_ID}/payments/${id}/refund`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOK },
        body: JSON.stringify({
            refunded_on:  date,
            payment_mode: document.getElementById('refund_payment_mode').value,
            from_account: document.getElementById('refund_from_account').value,
            reference_no: document.getElementById('refund_reference_no').value,
            description:  document.getElementById('refund_description').value,
        }),
    })
    .then(r => r.json())
    .then(d => {
        spin.style.display = 'none'; btn.disabled = false;
        if (d.success) {
            document.getElementById('refundPaymentOverlay').style.display = 'none';
            pmtToast(d.message);
            refreshPaymentUI(d);
            // Visually mark row as refunded
            const row = document.getElementById('pmt-row-' + id);
            if (row) {
                row.style.opacity = '0.55';
                row.style.textDecoration = 'line-through';
                const dropCell = row.querySelector('[id^="pmt-drop-"]');
                if (dropCell) dropCell.parentElement.innerHTML = '';
            }
            setTimeout(() => location.reload(), 1800);
        } else {
            pmtToast(d.message || 'Refund failed', 'error');
        }
    })
    .catch(() => { spin.style.display = 'none'; btn.disabled = false; pmtToast('Network error', 'error'); });
}

// ═══════════════════════════════════════════════
// DELETE (2-step)
// ═══════════════════════════════════════════════
let _delId = null;

function openDeleteModal(id, amount) {
    _delId = id;
    document.getElementById('del_amount_display').textContent =
        '₹' + parseFloat(amount).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    showDelStep1();
    document.getElementById('deletePaymentOverlay').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deletePaymentOverlay').style.display = 'none';
}

function showDelStep1() {
    document.getElementById('del-step-1').style.display             = 'block';
    document.getElementById('del-step-2-dissociate').style.display  = 'none';
    document.getElementById('del-step-2-delete').style.display      = 'none';
}

function showDelStep2(type) {
    document.getElementById('del-step-1').style.display             = 'none';
    document.getElementById('del-step-2-dissociate').style.display  = type === 'dissociate' ? 'block' : 'none';
    document.getElementById('del-step-2-delete').style.display      = type === 'delete'     ? 'block' : 'none';
}

function confirmDelete(action) {
    const spinnerId = action === 'dissociate_credit' ? 'dissociateSpinner' : 'deleteSpinner';
    const btnId     = action === 'dissociate_credit' ? 'dissociateConfirmBtn' : 'deleteConfirmBtn';
    const spin = document.getElementById(spinnerId);
    const btn  = document.getElementById(btnId);

    spin.style.display = 'inline'; btn.disabled = true;

    fetch(`/invoices/${INV_ID}/payments/${_delId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOK },
        body: JSON.stringify({ _method: 'DELETE', action }),
    })
    .then(r => r.json())
    .then(d => {
        spin.style.display = 'none'; btn.disabled = false;
        if (d.success) {
            closeDeleteModal();
            pmtToast(d.message);
            refreshPaymentUI(d);
            const row = document.getElementById('pmt-row-' + _delId);
            if (row) {
                row.style.transition = 'opacity 0.4s';
                row.style.opacity    = '0';
                setTimeout(() => {
                    row.remove();
                    const cnt = document.getElementById('payment-count');
                    if (cnt) cnt.textContent = Math.max(0, parseInt(cnt.textContent || '1') - 1);
                }, 400);
            }
            setTimeout(() => location.reload(), 1500);
        } else {
            pmtToast(d.message || 'Action failed', 'error');
        }
    })
    .catch(() => { spin.style.display = 'none'; btn.disabled = false; pmtToast('Network error', 'error'); });
}

// Close modals on overlay click
['editPaymentOverlay','refundPaymentOverlay','deletePaymentOverlay'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function(e) {
        if (e.target === this) {
            if (id === 'deletePaymentOverlay') closeDeleteModal();
            else this.style.display = 'none';
        }
    });
});
</script>

            {{-- ── INVOICE PAPER ── --}}
            <div class="invoice-paper">

                {{-- ✅ Corner Ribbon — paid / partially paid / overdue --}}
                @php
                    $ribbonText  = null;
                    $ribbonColor = null;
                    if ($payStatus === 'paid') {
                        $ribbonText  = 'Paid';
                        $ribbonColor = '#22c55e';
                    } elseif ($payStatus === 'partial') {
                        $ribbonText  = 'Partially Paid';
                        $ribbonColor = '#14b8a6';
                    } elseif ($invoice->status === 'Overdue') {
                        $ribbonText  = 'Overdue';
                        $ribbonColor = '#f59e0b';
                    }
                @endphp

                @if($ribbonText)
                <div style="position:absolute;top:0;left:0;
                            width:130px;height:130px;
                            overflow:hidden;pointer-events:none;z-index:10;">
                    <div style="position:absolute;
                                top:30px;left:-35px;
                                width:170px;
                                background:{{ $ribbonColor }};
                                color:#fff;
                                font-size:10px;font-weight:800;
                                padding:8px 0;
                                text-align:center;
                                transform:rotate(-45deg);
                                letter-spacing:0.5px;
                                text-transform:uppercase;
                                box-shadow:0 2px 8px rgba(0,0,0,0.25);">
                        {{ $ribbonText }}
                    </div>
                </div>
                @endif

                {{-- ① Company + TAX INVOICE --}}
                <div class="paper-header">
                    <div>
                        <div class="company-name">{{ config('app.name', 'Your Company') }}</div>
                        @php $loc = \App\Models\Location::find($invoice->location); @endphp
                        @if($loc)
                        <div class="company-addr">
                            @if($loc->state){{ $loc->state }}<br>@endif
                            @if($loc->country){{ $loc->country }}<br>@endif
                            @if($loc->email){{ $loc->email }}@endif
                        </div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div class="tax-inv-lbl">TAX INVOICE</div>
                        <div class="inv-status-badge">
                            <span class="badge badge-{{ strtolower($invoice->status) }}">{{ $invoice->status }}</span>
                        </div>
                    </div>
                </div>

                {{-- ② Invoice Meta --}}
                <div class="meta-wrap">
                    <table class="meta-tbl">
                        <tr>
                            <td class="k">#</td>
                            <td class="sep">:</td>
                            <td class="v">{{ $invoice->invoice_number }}</td>
                            <td class="k gap">Invoice Date</td>
                            <td class="sep">:</td>
                            <td class="v">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="k">Terms</td>
                            <td class="sep">:</td>
                            <td class="v">{{ $invoice->terms ?? 'Due on Receipt' }}</td>
                            <td class="k gap">Due Date</td>
                            <td class="sep">:</td>
                            <td class="v">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>

                {{-- ③ Bill To / Ship To --}}
                @php
                    $custAddr = $invoice->customer->common_address ?? null;
                    if (is_string($custAddr)) $custAddr = json_decode($custAddr, true);
                    $billing  = $custAddr['billing']  ?? null;
                    $shipping = $custAddr['shipping'] ?? null;

                    if (!function_exists('formatAddrLines')) {
                        function formatAddrLines($addr) {
                            if (!$addr) return '—';
                            $parts = array_filter([
                                $addr['attention'] ?? null,
                                $addr['street1']   ?? null,
                                $addr['street2']   ?? null,
                                trim(($addr['city'] ?? '') . ' ' . ($addr['pincode'] ?? '')),
                                $addr['state']     ?? null,
                                $addr['country']   ?? null,
                            ]);
                            return implode('<br>', $parts) ?: '—';
                        }
                    }
                @endphp
                <div class="addr-row">
                    <div class="addr-col">
                        <div class="addr-lbl">Bill To</div>
                        <div class="addr-cust-name">{{ $invoice->customer->display_name ?? '—' }}</div>
                        <div class="addr-lines">{!! formatAddrLines($billing) !!}</div>
                    </div>
                    <div class="addr-col">
                        <div class="addr-lbl">Ship To</div>
                        <div class="addr-lines">{!! formatAddrLines($shipping) !!}</div>
                    </div>
                </div>

                {{-- ④ Items Table --}}
                <table class="items-tbl">
                    <colgroup>
                        <col style="width:52px"><col>
                        <col style="width:60px"><col style="width:80px">
                        <col style="width:90px"><col style="width:90px">
                        <col style="width:110px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item &amp; Description</th>
                            <th class="r">Qty</th>
                            <th class="r">Rate</th>
                            <th class="r">GST%</th>
                            <th class="r">GST Amt</th>
                            <th class="r">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $i => $item)
                        @php
                            $gst = is_string($item->gst_data)
                                ? json_decode($item->gst_data, true)
                                : ($item->gst_data ?? []);
                            $gstValue  = $gst['value']  ?? 0;
                            $gstType   = $gst['type']   ?? '%';
                            $gstAmount = $gst['amount'] ?? 0;
                        @endphp
                        <tr>
                            <td class="num">{{ $i + 1 }}</td>
                            <td>
                                <div class="item-name">{{ $item->item_name }}</div>
                                @if($item->product && $item->product->unit)
                                <div class="item-sub">{{ $item->product->unit }}</div>
                                @endif
                            </td>
                            <td class="r">{{ number_format($item->quantity, 2) }}</td>
                            <td class="r">{{ number_format($item->rate, 2) }}</td>
                            <td class="r">
                                @if($gstValue > 0) {{ $gstValue }}{{ $gstType }} @else — @endif
                            </td>
                            <td class="r" style="color:#27ae60;font-size:11px">
                                @if($gstAmount > 0) ₹{{ number_format($gstAmount, 2) }} @else — @endif
                            </td>
                            <td class="r" style="font-weight:600">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ⑤ Totals + Words --}}
                <div class="bottom-section">
                    <div class="words-block">
                        <div class="words-lbl">Total In Words</div>
                        <div class="words-val">{{ numberToWords($invoice->grand_total) }}</div>
                        @if($invoice->customer_notes)
                        <div style="margin-top:16px;">
                            <div class="notes-lbl">Notes</div>
                            <div class="notes-text">{{ $invoice->customer_notes }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="totals-block">

                        <div class="tot-row">
                            <span class="tl">Sub Total</span>
                            <span>{{ number_format($invoice->subtotal, 2) }}</span>
                        </div>

                        @if(($invoice->discount_amount ?? 0) > 0)
                        <div class="tot-row">
                            <span class="tl">Discount</span>
                            <span>− {{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>
                        @endif

                        @if(($invoice->tax_amount ?? 0) > 0)
                        <div class="tot-row">
                            <span class="tl">{{ $invoice->tax_type }} ({{ $invoice->tax_percent }}%)</span>
                            <span>{{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        @endif

                        @if(($invoice->courier_charges ?? 0) > 0)
                        <div class="tot-row">
                            <span class="tl">🚚 Courier Charges</span>
                            <span>{{ number_format($invoice->courier_charges, 2) }}</span>
                        </div>
                        @endif

                        @if(!empty($invoice->extra_charges))
                        @php
                            $extraCharges = is_string($invoice->extra_charges)
                                ? json_decode($invoice->extra_charges, true)
                                : $invoice->extra_charges;
                        @endphp
                        @if(is_array($extraCharges))
                            @foreach($extraCharges as $label => $amount)
                            @if($amount != 0)
                            <div class="tot-row">
                                <span class="tl">{{ $label }}</span>
                                <span>{{ $amount < 0 ? '− '.number_format(abs($amount),2) : number_format($amount,2) }}</span>
                            </div>
                            @endif
                            @endforeach
                        @endif
                        @endif

                        {{-- Grand Total --}}
                        <div class="tot-row grand">
                            <span>Total</span>
                            <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
                        </div>

                        {{-- ✅ Payment Made row --}}
                        @if($amountReceived > 0)
                        <div style="display:flex;justify-content:space-between;
                                    padding:5px 0;font-size:12px;
                                    border-top:1px solid #dde2e8;margin-top:4px;">
                            <span style="color:#555;font-weight:500;">Payment Made</span>
                            <span style="color:#e05050;font-weight:600;">
                                (-) {{ number_format($amountReceived, 2) }}
                            </span>
                        </div>
                        @endif

                        {{-- ✅ Balance Due --}}
                        <div style="display:flex;justify-content:space-between;align-items:center;
                                    padding:9px 0 6px;margin-top:3px;
                                    border-top:2px solid #1a2332;
                                    font-size:14px;font-weight:800;
                                    color:{{ $balanceDue == 0 ? '#166534' : '#1a1a2e' }};">
                            <span>Balance Due</span>
                            <span>₹{{ number_format($balanceDue, 2) }}</span>
                        </div>

                        {{-- ✅ Payment Status Badge --}}
                        @if($payStatus === 'paid')
                        <div style="text-align:right;margin-top:8px;">
                            <span style="background:#dcfce7;color:#166534;
                                         padding:4px 14px;border-radius:4px;
                                         font-size:11px;font-weight:700;
                                         border:1px solid #22c55e;">
                                ✓ FULLY PAID
                            </span>
                        </div>
                        @elseif($payStatus === 'partial')
                        <div style="text-align:right;margin-top:8px;">
                            <span style="background:#fef3c7;color:#92400e;
                                         padding:4px 14px;border-radius:4px;
                                         font-size:11px;font-weight:700;
                                         border:1px solid #f59e0b;">
                                ½ PARTIALLY PAID
                            </span>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- ⑥ Signature --}}
                <div class="notes-sig">
                    <div></div>
                    <div class="sig-block">
                        <div class="sig-line">Authorized Signature</div>
                    </div>
                </div>

            </div>{{-- end .invoice-paper --}}

        </div>{{-- end .detail-scroll --}}
    </div>{{-- end .detail-area --}}

    {{-- ── COMMENTS PANEL ── --}}
    <div class="comments-panel" id="comments-panel">
        <div class="cp-header">
            <div class="cp-title">Comments &amp; History</div>
            <button class="cp-close" onclick="toggleComments()">✕</button>
        </div>
        <div class="cp-editor">
            <div class="cp-toolbar">
                <button class="cp-fmt"><strong>B</strong></button>
                <button class="cp-fmt"><em>I</em></button>
                <button class="cp-fmt"><u>U</u></button>
            </div>
            <textarea class="cp-textarea" id="comment-input" placeholder="Write a comment..."></textarea>
            <button class="cp-add" onclick="addComment()">Add Comment</button>
        </div>
        <div class="cp-section-lbl">
            ALL COMMENTS &amp; HISTORY
            <span class="cp-badge" id="history-count">{{ $histories->count() }}</span>
        </div>
        <div class="cp-scroll" id="history-scroll">
            @forelse($histories as $h)
            @php
                $colorBg = [
                    'green'  => '#dcfce7', 'blue'   => '#dbeafe',
                    'orange' => '#fef3c7', 'red'    => '#fef2f2',
                    'purple' => '#f3e8ff', 'teal'   => '#ccfbf1',
                    'gray'   => '#f1f5f9',
                ];
                $colorFg = [
                    'green'  => '#166534', 'blue'   => '#1e40af',
                    'orange' => '#92400e', 'red'    => '#991b1b',
                    'purple' => '#6b21a8', 'teal'   => '#0f766e',
                    'gray'   => '#475569',
                ];
                $hBg = $colorBg[$h['color_class']] ?? '#f1f5f9';
                $hFg = $colorFg[$h['color_class']] ?? '#475569';
            @endphp
            <div class="cp-item">
                <div class="cp-avatar"
                     style="background:{{ $hBg }};color:{{ $hFg }};
                            width:28px;height:28px;border-radius:6px;
                            display:flex;align-items:center;justify-content:center;
                            font-size:12px;font-weight:600;flex-shrink:0;">
                    {{ $h['user_initials'] }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="cp-meta">
                        <span class="cp-name">{{ $h['user'] }}</span>
                        <span class="cp-time" title="{{ $h['time'] }}">• {{ $h['time_human'] }}</span>
                    </div>
                    @if($h['invoice_number'])
                    <div style="font-size:10px;color:#4a90d9;font-weight:600;margin-bottom:3px;">
                        📄 {{ $h['invoice_number'] }}
                    </div>
                    @endif
                    <div class="cp-text">{!! $h['description'] !!}</div>
                    <div style="font-size:10px;color:#bbb;margin-top:2px;">{{ $h['time'] }}</div>
                </div>
            </div>
            @empty
            <div id="empty-history" style="text-align:center;padding:30px;color:#aaa;font-size:13px;">
                <div style="font-size:24px;margin-bottom:8px;">📋</div>
                No history yet.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── RECORD PAYMENT MODAL ── --}}
<div id="payment-modal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
            z-index:1000;align-items:center;justify-content:center;">
<div style="background:#fff;border-radius:10px;width:580px;max-height:90vh;
            overflow-y:auto;box-shadow:0 12px 40px rgba(0,0,0,0.2);
            animation:modalIn 0.2s ease;">

    {{-- Modal Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:16px 24px;border-bottom:1px solid #e3e6ea;">
        <div>
            <div style="font-size:16px;font-weight:700;color:#1a1a2e;">
                Payment for {{ $invoice->invoice_number }}
            </div>
        </div>
        <button onclick="closePaymentModal()"
                style="background:none;border:none;font-size:20px;color:#888;
                       cursor:pointer;line-height:1;">✕</button>
    </div>

    {{-- Balance Due Banner --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                padding:10px 24px;background:#fefce8;border-bottom:1px solid #fef08a;">
        <span style="font-size:12px;color:#92400e;font-weight:500;">Balance Due</span>
        <span style="font-size:16px;font-weight:700;color:#92400e;" id="modal-balance-due">
            ₹{{ number_format($balanceDue, 2) }}
        </span>
    </div>

    {{-- Form Body --}}
    <div style="padding:24px;" id="payment-modal-body">

        <div id="modal-error" style="display:none;background:#fef2f2;color:#991b1b;
             border:1px solid #fecaca;padding:10px 14px;border-radius:6px;
             margin-bottom:16px;font-size:13px;"></div>

        <div id="modal-success" style="display:none;background:#ecfdf5;color:#065f46;
             border:1px solid #a7f3d0;padding:10px 14px;border-radius:6px;
             margin-bottom:16px;font-size:13px;"></div>

        {{-- Row 1: Customer | Payment # --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Customer Name</label>
                <input type="text" value="{{ $invoice->customer->display_name ?? '' }}"
                       readonly style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;background:#f8f9fa;color:#666;width:100%;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Payment #<span style="color:#e05050;margin-left:2px;">*</span></label>
                <input type="text" id="modal-payment-number"
                       value="{{ $paymentNumber }}"
                       style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
            </div>
        </div>

        {{-- Row 2: Location --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Location</label>
                <select id="modal-location"
                        style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                        padding:0 10px;font-size:13px;width:100%;outline:none;">
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}"
                        {{ $loc->id == $invoice->location ? 'selected' : '' }}>
                        {{ $loc->location_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div></div>
        </div>

        <hr style="border:none;border-top:1px solid #e3e6ea;margin:4px 0 20px;">

        {{-- Row 3: Amount | Bank Charges --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#e05050;">Amount Received (INR)<span style="color:#e05050;margin-left:2px;">*</span></label>
                <input type="number" id="modal-amount" step="0.01" min="0.01"
                       value="{{ number_format($balanceDue, 2, '.', '') }}"
                       style="height:36px;border:2px solid #4a90d9;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
                @if($invoice->customer->pan_number ?? null)
                <span style="font-size:11px;color:#4a90d9;margin-top:3px;">
                    PAN: {{ $invoice->customer->pan_number }}
                </span>
                @endif
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Bank Charges (if any)</label>
                <input type="number" id="modal-bank-charges" step="0.01" min="0" value="0"
                       style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
            </div>
        </div>

        {{-- Row 4: Tax deducted --}}
        <div style="margin-bottom:20px;">
            <label style="font-size:12px;font-weight:600;color:#6b7280;display:block;margin-bottom:8px;">Tax deducted?</label>
            <div style="display:flex;align-items:center;gap:20px;">
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;">
                    <input type="radio" name="modal_tax" value="no" checked style="accent-color:#4a90d9;width:15px;height:15px;">
                    No Tax deducted
                </label>
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;">
                    <input type="radio" name="modal_tax" value="tds" style="accent-color:#4a90d9;width:15px;height:15px;">
                    Yes, TDS (Income Tax)
                </label>
            </div>
        </div>

        <hr style="border:none;border-top:1px solid #e3e6ea;margin:4px 0 20px;">

        {{-- Row 5: Payment Date | Payment Mode --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#e05050;">Payment Date<span style="color:#e05050;margin-left:2px;">*</span></label>
                <input type="date" id="modal-payment-date" value="{{ date('Y-m-d') }}"
                       style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Payment Mode</label>
                <select id="modal-payment-mode"
                        style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                        padding:0 10px;font-size:13px;width:100%;outline:none;">
                    <option value="Cash" selected>Cash</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="UPI">UPI</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Net Banking">Net Banking</option>
                </select>
            </div>
        </div>

        {{-- Row 6: Received On | Deposit To --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Payment Received On</label>
                <input type="date" id="modal-received-on"
                       style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#e05050;">Deposit To<span style="color:#e05050;margin-left:2px;">*</span></label>
                <select id="modal-deposit-to"
                        style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                        padding:0 10px;font-size:13px;width:100%;outline:none;">
                    <option value="Petty Cash" selected>Petty Cash</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank">Bank</option>
                    <option value="Savings Account">Savings Account</option>
                    <option value="Current Account">Current Account</option>
                </select>
            </div>
        </div>

        {{-- Row 7: Reference | Notes --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Reference#</label>
                <input type="text" id="modal-reference"
                       style="height:36px;border:1px solid #e3e6ea;border-radius:5px;
                       padding:0 10px;font-size:13px;width:100%;outline:none;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:12px;font-weight:600;color:#6b7280;">Notes</label>
                <textarea id="modal-notes"
                          style="height:72px;border:1px solid #e3e6ea;border-radius:5px;
                          padding:8px 10px;font-size:13px;width:100%;outline:none;resize:vertical;"></textarea>
            </div>
        </div>

        {{-- Thank you checkbox --}}
        <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
            <input type="checkbox" id="modal-thankyou" value="1"
                   style="accent-color:#4a90d9;width:15px;height:15px;">
            <label for="modal-thankyou" style="font-size:13px;color:#1a1a2e;cursor:pointer;">
                Send a "Thank you" note for this payment
            </label>
        </div>

    </div>{{-- end modal body --}}

    {{-- Modal Footer --}}
    <div style="display:flex;align-items:center;gap:10px;padding:16px 24px;
                border-top:1px solid #e3e6ea;background:#fafbfc;">
        <button onclick="submitPayment('draft')"
                style="height:36px;padding:0 20px;background:#fff;color:#444;
                       border:1px solid #e3e6ea;border-radius:5px;font-size:13px;
                       font-weight:500;cursor:pointer;"
                id="modal-draft-btn">
            Save as Draft
        </button>
        <button onclick="submitPayment('paid')"
                style="height:36px;padding:0 20px;background:#4a90d9;color:#fff;
                       border:none;border-radius:5px;font-size:13px;font-weight:600;cursor:pointer;"
                id="modal-paid-btn">
            Save as Paid
        </button>
        <button onclick="closePaymentModal()"
                style="height:36px;padding:0 16px;background:none;color:#e05050;
                       border:none;font-size:13px;cursor:pointer;margin-left:auto;">
            Cancel
        </button>
    </div>

</div>
</div>
</div>{{-- end .root-layout --}}

<script>
const INVOICE_ID = {{ $invoice->id }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
const PAYMENT_STORE_URL = '{{ route("invoices.payment.store", $invoice->id) }}';

function toggleComments() {
    document.getElementById('comments-panel').classList.toggle('open');
}

function addComment() {
    const textarea = document.getElementById('comment-input');
    const text     = textarea.value.trim();
    if (!text) { textarea.focus(); textarea.style.borderColor = '#e05050'; return; }
    textarea.style.borderColor = '';

    fetch(`/invoices/${INVOICE_ID}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type':     'application/json',
            'X-CSRF-TOKEN':     CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ comment: text }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            prependHistoryItem({
                action:        'comment',
                user:          data.comment.user_name || 'You',
                user_initials: (data.comment.user_name || 'U').charAt(0).toUpperCase(),
                time_human:    'Just now',
                time:          data.comment.created_at,
                description:   escapeHtml(text),
                color_class:   'purple',
            });
            textarea.value = '';
        } else {
            alert(data.message || 'Failed to add comment.');
        }
    })
    .catch(() => alert('Network error.'));
}

function prependHistoryItem(h) {
    const scroll  = document.getElementById('history-scroll');
    const emptyEl = document.getElementById('empty-history');
    const countEl = document.getElementById('history-count');
    if (emptyEl) emptyEl.remove();

    const colorBg = { green:'#dcfce7', blue:'#dbeafe', orange:'#fef3c7', red:'#fef2f2', purple:'#f3e8ff', teal:'#ccfbf1', gray:'#f1f5f9' };
    const colorFg = { green:'#166534', blue:'#1e40af', orange:'#92400e', red:'#991b1b', purple:'#6b21a8', teal:'#0f766e', gray:'#475569' };
    const bg = colorBg[h.color_class] ?? colorBg.gray;
    const fg = colorFg[h.color_class] ?? colorFg.gray;

    const div = document.createElement('div');
    div.className = 'cp-item';
    div.innerHTML = `
        <div style="background:${bg};color:${fg};width:28px;height:28px;border-radius:6px;
                    display:flex;align-items:center;justify-content:center;
                    font-size:12px;font-weight:600;flex-shrink:0;">
            ${h.user_initials}
        </div>
        <div style="flex:1;min-width:0;">
            <div class="cp-meta">
                <span class="cp-name">${escapeHtml(h.user)}</span>
                <span class="cp-time">• ${h.time_human}</span>
            </div>
            <div class="cp-text">${h.description}</div>
            <div style="font-size:10px;color:#bbb;margin-top:2px;">${h.time}</div>
        </div>`;

    div.style.cssText = 'display:flex;gap:9px;margin-bottom:14px;opacity:0;transform:translateY(-8px);';
    scroll.prepend(div);
    requestAnimationFrame(() => {
        div.style.transition = 'opacity 0.25s, transform 0.25s';
        div.style.opacity    = '1';
        div.style.transform  = 'translateY(0)';
    });
    if (countEl) countEl.textContent = parseInt(countEl.textContent || '0') + 1;
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

@php
function numberToWords(float $number): string {
    $number = round($number, 2);
    $parts  = explode('.', number_format($number, 2, '.', ''));
    $rupees = (int)$parts[0];
    $paise  = (int)$parts[1];

    $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
             'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
             'Seventeen','Eighteen','Nineteen'];
    $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];

    function convertGroup(int $n, array $ones, array $tens): string {
        if ($n === 0) return '';
        if ($n < 20)  return $ones[$n] . ' ';
        if ($n < 100) return $tens[(int)($n/10)] . ($n%10 ? ' '.$ones[$n%10] : '') . ' ';
        return $ones[(int)($n/100)] . ' Hundred ' . convertGroup($n%100, $ones, $tens);
    }

    if ($rupees === 0) { $result = 'Zero'; }
    else {
        $crore = (int)($rupees / 10000000); $rupees %= 10000000;
        $lakh  = (int)($rupees / 100000);   $rupees %= 100000;
        $thou  = (int)($rupees / 1000);     $rupees %= 1000;
        $result = '';
        if ($crore)  $result .= trim(convertGroup($crore,  $ones, $tens)) . ' Crore ';
        if ($lakh)   $result .= trim(convertGroup($lakh,   $ones, $tens)) . ' Lakh ';
        if ($thou)   $result .= trim(convertGroup($thou,   $ones, $tens)) . ' Thousand ';
        if ($rupees) $result .= trim(convertGroup($rupees, $ones, $tens));
        $result = trim($result);
    }

    $words = 'Indian Rupee ' . $result . ' Only';
    if ($paise > 0) {
        $words = 'Indian Rupee ' . $result . ' and ' .
                 trim(convertGroup($paise, $ones, $tens)) . 'Paise Only';
    }
    return $words;
}
@endphp

function openPaymentModal() {
    document.getElementById('payment-modal-overlay').style.display = 'flex';
    document.getElementById('modal-error').style.display   = 'none';
    document.getElementById('modal-success').style.display = 'none';
}

function closePaymentModal() {
    document.getElementById('payment-modal-overlay').style.display = 'none';
}

// Close on overlay click
document.getElementById('payment-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});

async function submitPayment(action) {
    const amount      = document.getElementById('modal-amount').value;
    const payDate     = document.getElementById('modal-payment-date').value;
    const payMode     = document.getElementById('modal-payment-mode').value;
    const depositTo   = document.getElementById('modal-deposit-to').value;
    const payNumber   = document.getElementById('modal-payment-number').value;
    const bankCharges = document.getElementById('modal-bank-charges').value;
    const reference   = document.getElementById('modal-reference').value;
    const notes       = document.getElementById('modal-notes').value;
    const locationId  = document.getElementById('modal-location').value;
    const taxDeducted = document.querySelector('input[name="modal_tax"]:checked')?.value || 'no';

    if (!amount || parseFloat(amount) <= 0) { showModalError('Please enter a valid amount.'); return; }
    if (!payDate)   { showModalError('Please select payment date.'); return; }
    if (!payNumber) { showModalError('Please enter payment number.'); return; }

    const paidBtn  = document.getElementById('modal-paid-btn');
    const draftBtn = document.getElementById('modal-draft-btn');
    paidBtn.disabled  = true;
    draftBtn.disabled = true;
    paidBtn.textContent = 'Saving...';

    try {
        const formData = new FormData();
        formData.append('_token',          CSRF_TOKEN);
        formData.append('action',          action);
        formData.append('payment_number',  payNumber);
        formData.append('amount_received', amount);
        formData.append('payment_date',    payDate);
        formData.append('payment_mode',    payMode);
        formData.append('deposit_to',      depositTo);
        formData.append('bank_charges',    bankCharges || '0');
        formData.append('reference',       reference);
        formData.append('notes',           notes);
        formData.append('location_id',     locationId);
        formData.append('tax_deducted',    taxDeducted);

        const res = await fetch(PAYMENT_STORE_URL, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });

        // ✅ இங்கே தான் place பண்ணணும்
        const data = await res.json();
        if (data.success) {
            showModalSuccess(data.message || 'Payment recorded successfully!');
            setTimeout(() => window.location.reload(), 1200);
        } else {
            showModalError(data.message || 'Failed to save payment.');
        }

    } catch(e) {
        showModalError('Network error. Please try again.');
    } finally {
        paidBtn.disabled    = false;
        draftBtn.disabled   = false;
        paidBtn.textContent = 'Save as Paid';
    }
}

function showModalError(msg) {
    const el = document.getElementById('modal-error');
    el.textContent    = msg;
    el.style.display  = 'block';
    document.getElementById('modal-success').style.display = 'none';
}

function showModalSuccess(msg) {
    const el = document.getElementById('modal-success');
    el.textContent    = msg;
    el.style.display  = 'block';
    document.getElementById('modal-error').style.display = 'none';
}

</script>
</body>
</html>