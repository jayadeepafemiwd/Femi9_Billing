<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
.topbar-right {
    margin-left: auto;
    display: flex; align-items: center; gap: 12px;
}
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
    cursor: pointer; font-size: 16px; font-weight: 400;
    background: var(--blue); color: #fff;
}
.lp-btn.sec { background: #e8f0fe; color: var(--blue); font-size: 12px; }
.lp-btn.dots { background: none; color: #888; font-size: 18px; }
.lp-search {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f2f4;
    flex-shrink: 0;
}
.lp-search input {
    width: 100%; height: 30px;
    border: 1px solid #d0d5dd; border-radius: 5px;
    padding: 0 10px 0 28px; font-size: 12px;
    outline: none; color: #333;
    background: #f8f9fa url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%23aaa' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
}
.lp-scroll { flex: 1; overflow-y: auto; }

.list-item {
    padding: 11px 12px 11px 36px;
    border-bottom: 1px solid #f0f2f4;
    cursor: pointer; transition: background 0.1s;
    position: relative;
}
.list-item:hover { background: #fafbff; }
.list-item.active { background: #eef4ff; border-left: 3px solid var(--blue); padding-left: 33px; }
.li-check {
    position: absolute; left: 12px; top: 14px;
    width: 14px; height: 14px;
}
.li-top {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 2px;
}
.li-name { font-size: 13px; font-weight: 500; color: var(--text); }
.li-amount { font-size: 13px; font-weight: 600; color: var(--text); }
.li-meta { font-size: 11px; color: #888; display: flex; gap: 5px; }
.li-meta .dot { color: #ccc; }
.li-status { font-size: 10px; font-weight: 600; margin-top: 3px; letter-spacing: 0.3px; }
.s-sent    { color: #1e40af; }
.s-paid    { color: var(--green); }
.s-draft   { color: #888; }
.s-overdue { color: var(--red); }

/* ── DETAIL AREA ── */
.detail-area {
    flex: 1; min-width: 0;
    display: flex; flex-direction: column;
    height: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
}

/* ── DETAIL TOOLBAR ── */
.detail-toolbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 6px 12px;
    min-height: 50px;
    flex-shrink: 0;
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 6px;
}
.dt-left { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.dt-inv-num { font-size: 15px; font-weight: 700; color: var(--text); }
.dt-loc { font-size: 10px; color: var(--muted); margin-bottom: 1px; }
.dt-right { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }

/* ── Action Buttons ── */
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
.ab-group.primary .ab-caret {
    background: var(--blue); color: #fff; border-color: var(--blue);
}
.ab-group.primary .ab-caret:hover { background: var(--blue2); }
.ab-divider { width: 1px; height: 22px; background: var(--border); margin: 0 2px; }

/* ── DETAIL SCROLL AREA ── */
.detail-scroll {
    flex: 1;
    overflow-x: hidden;
    overflow-y: auto;
    padding: 14px 16px 40px;
    background: var(--bg);
    min-width: 0;
    width: 100%;
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
.upi-row svg { width: 15px; height: 15px; color: var(--blue); flex-shrink: 0; }
.upi-row a { color: var(--blue); text-decoration: none; }

/* ── INVOICE PAPER ── */
.invoice-paper {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 6px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
}

/* Overdue ribbon */
.ribbon-wrap {
    position: relative; height: 0; overflow: visible;
    pointer-events: none; z-index: 10;
}
.ribbon {
    position: absolute; top: 18px; left: -28px;
    background: #e8a800; color: #fff;
    font-size: 10px; font-weight: 800;
    padding: 5px 32px;
    transform: rotate(-45deg);
    letter-spacing: 0.5px; text-transform: uppercase;
    box-shadow: 0 2px 6px rgba(0,0,0,0.18);
}

/* Paper inner - NO negative margins, padding handled per section */
.paper-inner { padding: 0; }

/* ── Paper Header (company + TAX INVOICE) ── */
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
.addr-lbl {
    font-size: 10px; font-weight: 700; color: #777;
    text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px;
}
.addr-cust-name { font-size: 13px; font-weight: 600; color: var(--blue); margin-bottom: 4px; }
.addr-lines { font-size: 12px; color: #444; line-height: 1.85; }

/* ── Items table ── */
.items-tbl { width: 100%; border-collapse: collapse; font-size: 12px; table-layout: fixed; }
.items-tbl thead tr { background: #f5f7fa; }
.items-tbl th {
    padding: 10px 12px; text-align: left;
    font-size: 11px; font-weight: 600; color: #555;
    border-top: 1px solid #dde2e8;
    border-bottom: 1px solid #dde2e8;
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
.tot-row.balance {
    font-size: 14px; font-weight: 700; color: var(--text);
    border-top: 2px solid var(--navy); padding-top: 9px; margin-top: 3px;
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
.badge {
    display: inline-block; padding: 2px 10px;
    border-radius: 10px; font-size: 11px; font-weight: 600;
}

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
.cp-avatar.y { background: #ffe8c5; color: #92400e; }
.cp-avatar.b { background: #dbeafe; color: #1e40af; }
.cp-meta { display: flex; align-items: center; gap: 6px; margin-bottom: 3px; }
.cp-name { font-size: 12px; font-weight: 600; color: var(--text); }
.cp-time { font-size: 11px; color: #aaa; }
.cp-text { font-size: 12px; color: #555; line-height: 1.5; }

/* ── PRINT ── */
@media print {
    .topbar, .sidenav, .list-panel, .detail-toolbar,
    .next-banner, .upi-row, .comments-panel { display: none !important; }
    .root-layout { margin-top: 0; }
    .detail-area { height: auto; overflow: visible; }
    .detail-scroll { padding: 0; overflow: visible; }
    .invoice-paper { border: none; box-shadow: none; }
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
            <span>&#9783;</span>
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
                <button class="lp-btn">+</button>
                <button class="lp-btn sec">
                    <svg width="11" height="11" fill="#4a90d9" viewBox="0 0 20 20"><path d="M10 3l7 7H3l7-7z"/></svg>
                </button>
                <button class="lp-btn dots" style="font-size:16px">···</button>
            </div>
        </div>
        <div class="lp-search">
            <input type="text" placeholder="Search invoices...">
        </div>
        <div class="lp-scroll">
            {{-- Active invoice --}}
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
                <div class="li-status s-{{ strtolower($invoice->status) }}">{{ strtoupper($invoice->status) }}</div>
            </div>

            @php
                $otherInvoices = \App\Models\Invoice::with('customer')
                    ->where('id', '!=', $invoice->id)
                    ->latest()->take(12)->get();
            @endphp
            @foreach($otherInvoices as $other)
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
                <div class="li-status s-{{ strtolower($other->status) }}">{{ strtoupper($other->status) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── DETAIL AREA ── --}}
    <div class="detail-area">

        {{-- Toolbar --}}
        <div class="detail-toolbar">
            <div class="dt-left">
                <div>
                    <div class="dt-loc">Location: {{ $locationName ?? 'Head Office' }}</div>
                    <div class="dt-inv-num">{{ $invoice->invoice_number }}</div>
                </div>
                <span class="badge badge-{{ strtolower($invoice->status) }}">{{ $invoice->status }}</span>
            </div>
            <div class="dt-right">
                {{-- Attachment --}}
                <button class="ab icon" title="Attachments">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="14" height="14">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                    </svg>
                </button>
                {{-- Comments --}}
                <button class="ab icon" title="Comments" onclick="toggleComments()">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="14" height="14">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </button>
                <div class="ab-divider"></div>

                {{-- Edit --}}
                <a href="#" class="ab">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit
                </a>

<button class="ab-caret">▾</button>
                {{-- Share --}}
                <button class="ab">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                        <path d="M8.59 13.51l6.83 3.98M15.41 6.51l-6.82 3.98"/>
                    </svg>
                    Share
                </button>

                {{-- Remind --}}
                <div class="ab-group">
                    <button class="ab">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        Remind
                    </button>
                    <button class="ab-caret">▾</button>
                </div>

                {{-- PDF --}}
                <div class="ab-group">
                    <button class="ab" onclick="window.print()">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="13" height="13">
                            <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF/Print
                    </button>
                    <button class="ab-caret">▾</button>
                </div>

                {{-- Record Payment --}}
                <div class="ab-group primary">
                    <button class="ab primary">
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
            <div style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:10px 14px;border-radius:6px;margin-bottom:12px;font-size:13px;">
                {{ session('success') }}
            </div>
            @endif

            {{-- Next Steps Banner --}}
            @if(in_array($invoice->status, ['Overdue','Sent']))
            <div class="next-banner">
                <div class="nb-left">
                    <div class="nb-icon">✦</div>
                    <div>
                        <div class="nb-title">WHAT'S NEXT?</div>
                        <div class="nb-body">
                            Payment is {{ $invoice->status === 'Overdue' ? 'overdue' : 'pending' }}.
                            Send a payment reminder or record the payment.
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <button class="btn-record">Record Payment</button>
            </div>
            @endif

            {{-- UPI Row --}}
            <div class="upi-row">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                </svg>
                Get paid faster by <a href="#" style="margin:0 4px">setting up payment gateways</a> or <a href="#" style="margin:0 4px">display a UPI QR code</a>.
            </div>

            {{-- ── INVOICE PAPER ── --}}
            <div class="invoice-paper">

                {{-- Overdue Ribbon --}}
                @if($invoice->status === 'Overdue')
                <div style="position:relative;height:0;overflow:visible;pointer-events:none;z-index:10;">
                    <div style="position:absolute;top:0;left:0;width:80px;height:80px;overflow:hidden;">
                        <div class="ribbon">Overdue</div>
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
        <col style="width:52px">
        <col>
        <col style="width:60px">
        <col style="width:80px">
        <col style="width:90px">
        <col style="width:90px">
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
                @if($gstValue > 0)
                    {{ $gstValue }}{{ $gstType }}
                @else
                    —
                @endif
            </td>
            <td class="r" style="color:#27ae60;font-size:11px">
                @if($gstAmount > 0)
                    ₹{{ number_format($gstAmount, 2) }}
                @else
                    —
                @endif
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
<!-- 
        @if(($invoice->adjustment ?? 0) != 0)
        <div class="tot-row">
            <span class="tl">Adjustment</span>
            <span>{{ number_format($invoice->adjustment, 2) }}</span>
        </div>
        @endif -->

        {{-- ✅ COURIER CHARGES --}}
        @if(($invoice->courier_charges ?? 0) > 0)
        <div class="tot-row">
            <span class="tl">🚚 Courier Charges</span>
            <span>{{ number_format($invoice->courier_charges, 2) }}</span>
        </div>
        @endif

            {{-- ✅ EXTRA CHARGES - DB format: {"vocher": 100, "Brochure": 100} --}}
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
                <span style="{{ $amount < 0 ? 'color:#e05050' : '' }}">
                    {{ $amount < 0 ? '− ' . number_format(abs($amount), 2) : number_format($amount, 2) }}
                </span>
            </div>
            @endif
        @endforeach
                        @endif
            @endif

        <div class="tot-row grand">
            <span>Total</span>
            <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
        </div>
        <div class="tot-row balance">
            <span>Balance Due</span>
            <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
        </div>
    </div>
</div>

                {{-- ⑥ Notes + Signature --}}
                <div class="notes-sig">
                    <div>
                        @if($invoice->customer_notes)
                        <div class="notes-lbl">Notes</div>
                        <div class="notes-text">{{ $invoice->customer_notes }}</div>
                        @endif
                    </div>
                    <div class="sig-block">
                        <div class="sig-line">Authorized Signature</div>
                    </div>
                </div>

            </div>{{-- end invoice-paper --}}
        </div>{{-- end detail-scroll --}}
    </div>{{-- end detail-area --}}

    {{-- ── COMMENTS PANEL ── --}}
<div class="comments-panel" id="comments-panel">
    <div class="cp-header">
        <div class="cp-title">Comments &amp; History</div>
        <button class="cp-close" onclick="toggleComments()">✕</button>
    </div>
 
    {{-- Comment input --}}
    <div class="cp-editor">
        <div class="cp-toolbar">
            <button class="cp-fmt"><strong>B</strong></button>
            <button class="cp-fmt"><em>I</em></button>
            <button class="cp-fmt"><u>U</u></button>
        </div>
        <textarea class="cp-textarea" id="comment-input" placeholder="Write a comment..."></textarea>
        <button class="cp-add" onclick="addComment()">Add Comment</button>
    </div>
 
   {{-- Section label --}}
<div class="cp-section-lbl">
    ALL COMMENTS &amp; HISTORY
    <span class="cp-badge" id="history-count">{{ $histories->count() }}</span>
</div>

<div class="cp-scroll" id="history-scroll">
    @forelse($histories as $h)
<div class="cp-item">
    <div class="cp-avatar ..." style="...">
        {{ $h['user_initials'] }}
    </div>
    <div>
        <div class="cp-meta">
            <span class="cp-name">{{ $h['user'] }}</span>
            <span class="cp-time">• {{ $h['time_human'] }}</span>
        </div>

        {{-- Invoice number badge — எந்த invoice என்று தெரியும் --}}
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

<script>
function toggleComments() {
    document.getElementById('comments-panel').classList.toggle('open');
}
const INVOICE_ID = {{ $invoice->id }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
 
// ── Toggle Comments Panel ──
function toggleComments() {
    document.getElementById('comments-panel').classList.toggle('open');
}
 
// ── Add Comment ──
function addComment() {
    const textarea = document.getElementById('comment-input');
    const text     = textarea.value.trim();
 
    if (!text) {
        textarea.focus();
        textarea.style.borderColor = '#e05050';
        return;
    }
    textarea.style.borderColor = '';
 
    fetch(`/invoices/${INVOICE_ID}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ comment: text }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            prependHistoryItem({
                action:        'comment',
                user:          'You',
                user_initials: 'Y',
                time_human:    'Just now',
                time:          new Date().toLocaleString('en-IN'),
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
 
// ── Prepend new history item to panel ──
function prependHistoryItem(h) {
    const scroll    = document.getElementById('history-scroll');
    const emptyEl   = document.getElementById('empty-history');
    const countEl   = document.getElementById('history-count');
 
    if (emptyEl) emptyEl.remove();
 
    const colorBg = {
        green: '#dcfce7', blue: '#dbeafe', orange: '#fef3c7',
        red: '#fef2f2', purple: '#f3e8ff', teal: '#ccfbf1', gray: '#f1f5f9',
    };
    const colorFg = {
        green: '#166534', blue: '#1e40af', orange: '#92400e',
        red: '#991b1b', purple: '#6b21a8', teal: '#0f766e', gray: '#475569',
    };
 
    const bg = colorBg[h.color_class] ?? colorBg.gray;
    const fg = colorFg[h.color_class] ?? colorFg.gray;
 
    const div = document.createElement('div');
    div.className = 'cp-item';
    div.dataset.action = h.action;
    div.innerHTML = `
        <div class="cp-avatar"
             style="background:${bg};color:${fg};width:28px;height:28px;
                    border-radius:6px;display:flex;align-items:center;
                    justify-content:center;font-size:12px;font-weight:600;flex-shrink:0;">
            ${h.user_initials}
        </div>
        <div style="flex:1;min-width:0;">
            <div class="cp-meta">
                <span class="cp-name">${escapeHtml(h.user)}</span>
                <span class="cp-time" title="${h.time}">• ${h.time_human}</span>
            </div>
            <div class="cp-text">${h.description}</div>
            <div style="font-size:10px;color:#bbb;margin-top:2px;">${h.time}</div>
        </div>
    `;
 
    // Animate in
    div.style.opacity   = '0';
    div.style.transform = 'translateY(-8px)';
    scroll.prepend(div);
    requestAnimationFrame(() => {
        div.style.transition = 'opacity 0.25s, transform 0.25s';
        div.style.opacity    = '1';
        div.style.transform  = 'translateY(0)';
    });
 
    // Count update
    if (countEl) countEl.textContent = parseInt(countEl.textContent || '0') + 1;
}
 
function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
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
        $crore  = (int)($rupees / 10000000); $rupees %= 10000000;
        $lakh   = (int)($rupees / 100000);   $rupees %= 100000;
        $thou   = (int)($rupees / 1000);     $rupees %= 1000;
        $result = '';
        if ($crore)  $result .= trim(convertGroup($crore,  $ones, $tens)) . ' Crore ';
        if ($lakh)   $result .= trim(convertGroup($lakh,   $ones, $tens)) . ' Lakh ';
        if ($thou)   $result .= trim(convertGroup($thou,   $ones, $tens)) . ' Thousand ';
        if ($rupees) $result .= trim(convertGroup($rupees, $ones, $tens));
        $result = trim($result);
    }

    $words = 'Indian Rupee ' . $result . ' Only';
    if ($paise > 0) {
        $words = 'Indian Rupee ' . $result . ' and ' . trim(convertGroup($paise, $ones, $tens)) . 'Paise Only';
    }
    return $words;
}
@endphp

</script>
 </body>
</html>