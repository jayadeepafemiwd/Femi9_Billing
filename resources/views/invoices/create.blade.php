<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>New Invoice | Invoices</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
body { background: #f5f6f8; font-size: 13px; color: #333; }

/* ── Topbar ── */
.topbar { background: #1a2332; height: 48px; display: flex; align-items: center; padding: 0 16px; gap: 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
.topbar-logo { color: #fff; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.topbar-logo svg { width: 20px; height: 20px; fill: #fff; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; }
.topbar-right span { color: #9ba8b8; font-size: 12px; }
.topbar-right a { color: #4a90d9; font-size: 12px; text-decoration: none; }
.topbar-avatar { width: 30px; height: 30px; border-radius: 50%; background: #4a90d9; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 600; }

/* ── Layout ── */
.layout { display: flex; margin-top: 48px; }

/* ── Sidenav ── */
.sidenav { width: 200px; background: #1a2332; min-height: calc(100vh - 48px); position: fixed; top: 48px; left: 0; bottom: 0; overflow-y: auto; }
.snav-label { font-size: 10px; color: #5a6a7e; padding: 12px 14px 4px; text-transform: uppercase; letter-spacing: 0.6px; }
.snav-item { font-size: 13px; color: #9ba8b8; padding: 7px 14px 7px 16px; cursor: pointer; border-left: 3px solid transparent; display: block; text-decoration: none; transition: background 0.15s; }
.snav-item:hover { background: #243447; color: #cdd5df; }
.snav-item.active { color: #fff; background: #2d3f55; border-left: 3px solid #4a90d9; }
.snav-item.flex { display: flex; align-items: center; justify-content: space-between; }
.snav-plus { width: 20px; height: 20px; background: rgba(255,255,255,0.12); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; line-height: 1; }

/* ── Main ── */
.main { margin-left: 200px; flex: 1; padding: 24px 28px 90px; }

/* ── Alerts ── */
.alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── Page title ── */
.page-title { display: flex; align-items: center; gap: 8px; font-size: 18px; font-weight: 500; color: #1a1a2e; margin-bottom: 20px; }
.page-title svg { width: 20px; height: 20px; stroke: #555; }

/* ── Card ── */
.card { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 20px 24px; margin-bottom: 16px; }

/* ── Form rows ── */
.frow { display: grid; grid-template-columns: 160px 1fr; align-items: start; gap: 8px; margin-bottom: 14px; }
.frow label { padding-top: 8px; font-size: 13px; color: #555; }
.frow label.req::after { content: " *"; color: #e05050; }

/* ── Inputs ── */
input[type=text], input[type=date], input[type=number], select, textarea {
    width: 100%; height: 34px;
    border: 1px solid #d0d5dd; border-radius: 6px;
    padding: 0 10px; font-size: 13px; color: #333;
    background: #fff; outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
input:focus, select:focus, textarea:focus {
    border-color: #4a90d9;
    box-shadow: 0 0 0 3px rgba(74,144,217,0.12);
}
textarea { height: 72px; padding: 8px 10px; resize: vertical; }
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 28px;
}
.inp-btn { display: flex; gap: 8px; }
.btn-search { background: #4a90d9; border: none; border-radius: 6px; width: 34px; height: 34px; color: #fff; font-size: 18px; cursor: pointer; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.gear { color: #888; font-size: 17px; cursor: pointer; margin-left: 6px; }

/* ── Divider ── */
.divider { height: 1px; background: #f0f2f4; margin: 14px 0; }

/* ── Customer Address Box ── */
.customer-addr-box { display: none; margin-bottom: 14px; margin-left: 168px; }
.customer-addr-inner {
    display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    padding: 14px 16px; background: #f8fafc;
    border: 1px solid #e2e8f0; border-radius: 6px;
}
.addr-block-title { font-size: 11px; font-weight: 600; color: #4a90d9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.addr-block-body { font-size: 12px; color: #555; line-height: 1.8; }

/* ── Referral field ── */
.referral-wrap { display: flex; gap: 8px; align-items: center; max-width: 340px; }
.referral-display {
    flex: 1; height: 34px; border: 1px solid #d0d5dd; border-radius: 6px;
    padding: 0 10px; font-size: 13px; color: #333; background: #fff;
    display: flex; align-items: center; cursor: pointer; user-select: none;
}
.referral-display .placeholder { color: #aaa; }
.btn-referral-manage {
    background: none; border: 1px solid #d0d5dd; border-radius: 6px;
    padding: 0 12px; height: 34px; font-size: 12px; color: #555; cursor: pointer; white-space: nowrap;
}
.btn-referral-manage:hover { background: #f0f4ff; border-color: #4a90d9; color: #4a90d9; }
.referral-clear { background: none; border: none; color: #e05050; cursor: pointer; font-size: 16px; padding: 0 4px; display: none; }

/* ── Table header ── */
.tbl-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.tbl-title { font-size: 13px; font-weight: 500; }
.tbl-actions { display: flex; gap: 10px; }
.btn-scan { background: none; border: 1px solid #d0d5dd; border-radius: 6px; padding: 4px 10px; font-size: 12px; color: #555; cursor: pointer; }
.btn-bulk-lnk { background: none; border: none; color: #4a90d9; font-size: 12px; cursor: pointer; }

/* ── Item Table ── */
.item-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.item-table thead tr { background: #f8f9fa; }
.item-table th { padding: 8px 10px; text-align: left; font-weight: 500; font-size: 12px; color: #666; border-top: 1px solid #e8eaed; border-bottom: 1px solid #e8eaed; }
.item-table th.r, .item-table td.r { text-align: right; }
.item-table td { padding: 5px 8px; border-bottom: 1px solid #f2f3f5; vertical-align: middle; }
.item-table td input, .item-table td select { height: 30px; font-size: 13px; border-radius: 4px; }
.drag-col { color: #ccc; font-size: 14px; cursor: grab; width: 24px; }
.img-col { width: 36px; }

/* ── Product image ── */
.item-img-box {
    width: 28px; height: 28px; background: #f5f5f5; border: 1px solid #e0e0e0;
    border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.item-img-box img { width: 100%; height: 100%; object-fit: cover; }
.item-img-box .noimg { color: #bbb; font-size: 10px; }

/* ── Product meta ── */
.product-meta { display: none; font-size: 11px; color: #888; margin-top: 3px; line-height: 1.5; }
.product-meta.show { display: block; }
.stock-ok { color: #27ae60; }
.stock-low { color: #e05050; }

.btn-del { background: none; border: none; color: #e05050; cursor: pointer; font-size: 17px; padding: 2px 6px; line-height: 1; }
.tbl-footer { display: flex; gap: 12px; margin-top: 10px; }
.btn-addrow { background: none; border: none; color: #4a90d9; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 0; }
.tbl-acc-row { display: flex; gap: 10px; align-items: center; margin-top: 10px; }
.btn-acc { background: none; border: 1px solid #d0d5dd; border-radius: 6px; padding: 4px 10px; font-size: 12px; color: #555; cursor: pointer; }

/* ── Totals ── */
.totals-wrap { display: flex; justify-content: flex-end; margin-top: 16px; }
.totals-box { width: 370px; }
.tot-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; font-size: 13px; color: #555; border-bottom: 1px solid #f0f2f4; }
.tot-row.grand { font-size: 15px; font-weight: 600; color: #1a1a2e; border-bottom: none; padding-top: 10px; }
.disc-wrap { display: flex; align-items: center; gap: 6px; }
.disc-wrap input { width: 65px; height: 28px; text-align: right; }
.tax-row { display: flex; align-items: center; padding: 7px 0; border-bottom: 1px solid #f0f2f4; gap: 8px; }
.radio-grp { display: flex; gap: 12px; }
.radio-grp label { display: flex; align-items: center; gap: 4px; cursor: pointer; font-size: 13px; color: #555; }
.tax-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
.tax-right select { width: 155px; height: 28px; font-size: 12px; }
#tax-display { min-width: 65px; text-align: right; font-size: 13px; color: #555; }
.adj-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid #f0f2f4; }
.adj-row input[type=text] { width: 110px; height: 28px; font-size: 13px; background: #f8f9fa; }
.adj-row input[type=number] { width: 80px; height: 28px; text-align: right; }

/* ── Bottom grid ── */
.bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.section-lbl { font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }

/* ── Footer bar ── */
.footer-bar { position: fixed; bottom: 0; left: 200px; right: 0; background: #fff; border-top: 1px solid #e0e3e8; padding: 12px 24px; display: flex; align-items: center; gap: 10px; z-index: 200; }
.btn-primary { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 9px 22px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-primary:hover { background: #3a7bc8; }
.btn-draft { background: #fff; color: #333; border: 1px solid #d0d5dd; border-radius: 6px; padding: 9px 22px; font-size: 13px; cursor: pointer; }
.btn-draft:hover { background: #f5f6f8; }
.btn-cancel { background: none; border: none; color: #888; font-size: 13px; cursor: pointer; padding: 9px 10px; }
.footer-totals { margin-left: auto; font-size: 13px; color: #666; display: flex; gap: 24px; }
.footer-totals strong { color: #1a1a2e; }

/* ── Modals ── */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center; }
.modal-overlay.open { display: flex; }
.modal-box { background: #fff; border-radius: 10px; width: 600px; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 12px 40px rgba(0,0,0,0.2); overflow: hidden; animation: modalIn 0.2s ease; }
@keyframes modalIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e3e6ea; }
.modal-header h3 { font-size: 15px; font-weight: 600; color: #1a1a2e; }
.modal-close { background: none; border: none; font-size: 20px; color: #888; cursor: pointer; line-height: 1; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 4px; }
.modal-close:hover { background: #f0f2f4; }
.modal-toolbar { display: flex; gap: 10px; align-items: center; padding: 12px 20px; border-bottom: 1px solid #f0f2f4; }
.modal-search { flex: 1; height: 32px; border: 1px solid #d0d5dd; border-radius: 6px; padding: 0 10px; font-size: 13px; }
.modal-search:focus { border-color: #4a90d9; outline: none; }
.btn-add-ref { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 0 14px; height: 32px; font-size: 12px; font-weight: 500; cursor: pointer; white-space: nowrap; }
.btn-add-ref:hover { background: #3a7bc8; }
.ref-form { display: none; padding: 14px 20px; background: #f8fafc; border-bottom: 1px solid #e3e6ea; }
.ref-form.open { display: block; }
.ref-form-grid { display: grid; grid-template-columns: 1fr 1fr 1fr 100px; gap: 10px; align-items: end; }
.ref-form-field label { display: block; font-size: 11px; color: #777; margin-bottom: 4px; }
.ref-form-field label span { color: #e05050; }
.ref-form-field input, .ref-form-field select { height: 32px; width: 100%; border: 1px solid #d0d5dd; border-radius: 5px; padding: 0 8px; font-size: 13px; }
.ref-form-actions { display: flex; gap: 8px; padding-top: 2px; }
.btn-ref-save { background: #4a90d9; color: #fff; border: none; border-radius: 5px; padding: 0 14px; height: 32px; font-size: 12px; cursor: pointer; }
.btn-ref-cancel { background: none; border: 1px solid #d0d5dd; border-radius: 5px; padding: 0 10px; height: 32px; font-size: 12px; cursor: pointer; }
.ref-table-wrap { flex: 1; overflow-y: auto; }
.ref-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.ref-table thead tr { background: #f8f9fa; position: sticky; top: 0; z-index: 1; }
.ref-table th { padding: 9px 14px; text-align: left; font-weight: 600; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 1px solid #e8eaed; }
.ref-table td { padding: 10px 14px; border-bottom: 1px solid #f2f3f5; vertical-align: middle; }
.ref-table tr:hover td { background: #f8f9fa; }
.ref-table tr.selected-row td { background: #eef4ff; }
.type-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 500; }
.type-referral { background: #fef3c7; color: #92400e; }
.type-agent    { background: #dbeafe; color: #1e40af; }
.type-staff    { background: #dcfce7; color: #166534; }
.ref-action-btns { display: flex; gap: 6px; }
.btn-ref-select { background: #4a90d9; color: #fff; border: none; border-radius: 4px; padding: 4px 10px; font-size: 12px; cursor: pointer; }
.btn-ref-select:hover { background: #3a7bc8; }
.btn-ref-edit { background: none; border: 1px solid #d0d5dd; border-radius: 4px; padding: 4px 8px; font-size: 12px; cursor: pointer; color: #555; }
.btn-ref-edit:hover { border-color: #4a90d9; color: #4a90d9; }
.btn-ref-del { background: none; border: 1px solid #fecaca; border-radius: 4px; padding: 4px 8px; font-size: 12px; cursor: pointer; color: #e05050; }
.btn-ref-del:hover { background: #fef2f2; }
.ref-empty { text-align: center; padding: 40px 20px; color: #aaa; font-size: 13px; }
.ref-empty .icon { font-size: 32px; margin-bottom: 8px; }
.modal-footer { padding: 12px 20px; border-top: 1px solid #e3e6ea; display: flex; justify-content: flex-end; gap: 10px; }

/* ── Customer Detail Panel ── */
.cust-panel {
    position: fixed; top: 48px; right: 0; bottom: 0;
    width: 340px; background: #fff;
    border-left: 1px solid #e3e6ea;
    box-shadow: -4px 0 20px rgba(0,0,0,0.08);
    z-index: 150; display: flex; flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.25s ease;
    overflow: hidden;
}
.cust-panel.open { transform: translateX(0); }
.cust-panel-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; border-bottom: 1px solid #e3e6ea; flex-shrink: 0; }
.cust-panel-avatar { width: 36px; height: 36px; border-radius: 50%; background: #e8f0fe; color: #4a90d9; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; flex-shrink: 0; }
.cust-panel-name { font-size: 14px; font-weight: 600; color: #1a1a2e; }
.cust-panel-name a { color: #4a90d9; font-size: 11px; margin-left: 6px; text-decoration: none; }
.cust-panel-close { background: none; border: none; font-size: 18px; color: #e05050; cursor: pointer; padding: 4px; line-height: 1; border-radius: 4px; }
.cust-panel-close:hover { background: #fef2f2; }
.cust-panel-tabs { display: flex; border-bottom: 1px solid #e3e6ea; flex-shrink: 0; }
.cust-tab { flex: 1; padding: 10px 0; text-align: center; font-size: 13px; color: #888; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.15s; }
.cust-tab.active { color: #4a90d9; border-bottom-color: #4a90d9; font-weight: 500; }
.cust-panel-body { flex: 1; overflow-y: auto; padding: 16px; }
.cust-tab-pane { display: none; }
.cust-tab-pane.active { display: block; }
.cust-summary-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.cust-summary-card { padding: 12px; border-radius: 8px; border: 1px solid #e3e6ea; text-align: center; }
.cust-summary-card .icon { font-size: 18px; margin-bottom: 4px; }
.cust-summary-card .label { font-size: 10px; color: #999; text-transform: uppercase; }
.cust-summary-card .value { font-size: 14px; font-weight: 600; color: #1a1a2e; }
.cust-detail-section { margin-bottom: 14px; }
.cust-detail-title { font-size: 11px; font-weight: 600; color: #4a90d9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #f0f2f4; }
.cust-detail-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px; border-bottom: 1px solid #f8f9fa; }
.cust-detail-row .key { color: #888; }
.cust-detail-row .val { color: #333; font-weight: 500; text-align: right; max-width: 200px; }
.contact-person-card { padding: 10px 12px; border: 1px solid #e3e6ea; border-radius: 6px; margin-bottom: 8px; position: relative; }
.contact-person-card .cp-name { font-size: 13px; font-weight: 600; color: #1a1a2e; }
.contact-person-card .cp-info { font-size: 11px; color: #888; margin-top: 3px; line-height: 1.6; }
.cp-primary-badge { position: absolute; top: 8px; right: 8px; background: #dcfce7; color: #166534; font-size: 10px; padding: 2px 6px; border-radius: 8px; }
.cp-portal-ok { color: #27ae60; font-size: 11px; }
.cp-portal-no { color: #e05050; font-size: 11px; }
.addr-card { padding: 10px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 8px; }
.addr-card-title { font-size: 11px; font-weight: 600; color: #4a90d9; margin-bottom: 6px; }
.addr-card-body { font-size: 12px; color: #555; line-height: 1.7; }
.activity-item { display: flex; gap: 10px; margin-bottom: 14px; }
.activity-dot { width: 28px; height: 28px; border-radius: 50%; background: #e8f0fe; color: #4a90d9; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; font-weight: 600; }
.activity-content { flex: 1; }
.activity-content .act-user { font-size: 12px; font-weight: 600; color: #333; }
.activity-content .act-time { font-size: 11px; color: #aaa; margin-left: 6px; }
.activity-content .act-text { font-size: 12px; color: #555; margin-top: 2px; }
.act-divider { height: 1px; background: #f0f2f4; margin: 8px 0; }
</style>
</head>
<body>

{{-- ── Topbar ── --}}
<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 20 20"><rect x="1" y="1" width="8" height="8" rx="1.5"/><rect x="11" y="1" width="8" height="8" rx="1.5"/><rect x="1" y="11" width="8" height="8" rx="1.5"/><rect x="11" y="11" width="8" height="8" rx="1.5"/></svg>
        Inventory
    </div>
    <div class="topbar-right">
        <span>Your premi... &nbsp;<a href="#">Subscribe</a></span>
        <div class="topbar-avatar">J</div>
    </div>
</div>

<div class="layout">

    <nav class="sidenav">
        <div class="snav-item">Sales Orders</div>
        <a href="{{ route('invoices.create') }}" class="snav-item active flex">
            Invoices <span class="snav-plus">+</span>
        </a>
        <div class="snav-item">Delivery Challans</div>
        <div class="snav-item">Payments Received</div>
        <div class="snav-item">Sales Returns</div>
        <div class="snav-item">Credit Notes</div>
        <div class="snav-label">Purchases</div>
        <div class="snav-label">Reports</div>
        <div class="snav-label">Documents</div>
        <div class="snav-label">Apps</div>
        <div class="snav-item">Zoho Payments</div>
    </nav>

    <main class="main">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err){{ $err }}<br>@endforeach
        </div>
        @endif

        <div class="page-title">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            New Invoice
        </div>

        <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
        @csrf

        {{-- CARD 1 — Invoice Header --}}
        <div class="card">

            <div class="frow">
                <label class="req">Customer Name</label>
                <div class="inp-btn">
                    <select name="customer_id" id="customer-select" required style="flex:1"
                            onchange="onCustomerChange(this.value)">
                        <option value="">Select or add a customer</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}"
                                data-address="{{ json_encode($c->common_address) }}"
                                {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->display_name }}
                        </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn-search">&#128269;</button>
                    <button type="button" id="btn-cust-info" onclick="openCustPanelFromBtn()"
                            title="View Customer Details"
                            style="display:none;background:#e8f0fe;border:1px solid #4a90d9;
                                   border-radius:6px;width:34px;height:34px;color:#4a90d9;
                                   font-size:15px;cursor:pointer;flex-shrink:0;
                                   align-items:center;justify-content:center;">
                        &#128100;
                    </button>
                </div>
            </div>

            <div class="customer-addr-box" id="customer-addr-box">
                <div class="customer-addr-inner">
                    <div>
                        <div class="addr-block-title">📦 Billing Address</div>
                        <div class="addr-block-body" id="billing-addr-display">—</div>
                    </div>
                    <div>
                        <div class="addr-block-title">🚚 Shipping Address</div>
                        <div class="addr-block-body" id="shipping-addr-display">—</div>
                    </div>
                </div>
            </div>

            <div class="frow">
                <label>Location</label>
                <select name="location_id" style="max-width:260px" id="location-select"
                        onchange="onLocationChange(this.value)">
                    <option value="">— Select Location —</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->location_name }}
                        ({{ $loc->location_type === 'business' ? '🏢' : '🏭' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="series_wrapper" style="display:none; margin-bottom:14px;">
                <div class="frow">
                    <label>Invoice Series</label>
                    <select name="series_id" id="series_id" style="max-width:260px"
                            onchange="onSeriesChange(this.value)">
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <div class="frow">
                <label class="req">Invoice #</label>
                <div style="display:flex;align-items:center;max-width:260px">
                    <input type="text" name="invoice_number" id="invoice-number-input"
                           value="{{ $invoiceNumber }}" required>
                    <span class="gear">&#9881;</span>
                    <small id="invoice-format-hint" style="color:#888;font-size:11px;margin-top:3px;display:block;"></small>
                </div>
            </div>

            <div class="frow">
                <label>Order Number</label>
                <input type="text" name="order_number" value="{{ old('order_number') }}" style="max-width:260px">
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap">
                <label class="req" style="min-width:130px;font-size:13px;color:#555">
                    Invoice Date <span style="color:#e05050">*</span>
                </label>
                <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required style="width:170px">
                <label style="font-size:13px;color:#555;margin-left:10px">Terms</label>
                <select name="terms" id="terms-select" style="width:160px" onchange="calcDueDate()">
                    <option value="Due on Receipt">Due on Receipt</option>
                    <option value="Net 15">Net 15</option>
                    <option value="Net 30">Net 30</option>
                    <option value="Net 45">Net 45</option>
                    <option value="Net 60">Net 60</option>
                </select>
                <label style="font-size:13px;color:#555">Due Date</label>
                <input type="date" name="due_date" id="due-date" value="{{ old('due_date', date('Y-m-d')) }}" required style="width:170px">
            </div>

            <div class="frow">
                <label>Referral / Reference</label>
                <div class="referral-wrap">
                    <div class="referral-display" id="referral-display" onclick="openReferralModal()">
                        <span class="placeholder" id="referral-placeholder">Select or Add Referral</span>
                        <span id="referral-selected-name" style="display:none;color:#333;font-weight:500;"></span>
                    </div>
                    <input type="hidden" name="referral_id" id="referral-id-input">
                    <button type="button" class="referral-clear" id="referral-clear-btn" onclick="clearReferral()">✕</button>
                    <button type="button" class="btn-referral-manage" onclick="openReferralModal()">⊞ Manage</button>
                </div>
            </div>

            <div class="frow">
                <label>Subject</label>
                <textarea name="subject" placeholder="Let your customer know what this Invoice is for" style="max-width:500px">{{ old('subject') }}</textarea>
            </div>

        </div>{{-- end card 1 --}}

        {{-- CARD 2 — Item Table --}}
        <div class="card">

            <div class="tbl-top">
                <div class="tbl-title">Item Table</div>
                <div class="tbl-actions">
                    <button type="button" class="btn-scan">&#128247; Scan Item</button>
                    <button type="button" class="btn-bulk-lnk" onclick="openBulkModal()">&#10003; Bulk Actions</button>
                </div>
            </div>

            <table class="item-table" id="item-table">
                <thead>
                    <tr>
                        <th class="drag-col"></th>
                        <th class="img-col"></th>
                        <th>Item Details</th>
                        <th style="width:80px;text-align:center">SKU</th>
                        <th class="r" style="width:100px">Qty</th>
                        <th class="r" style="width:130px">GST</th>
                        <th class="r" style="width:120px">Rate (₹)</th>
                        <th class="r" style="width:120px">Amount (₹)</th>
                        <th style="width:36px"></th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr data-index="0">
                        <td class="drag-col">&#8942;&#8942;</td>
                        <td class="img-col">
                            <div class="item-img-box" id="iimg-0">
                                <span class="noimg">&#128247;</span>
                            </div>
                        </td>
                        <td>
                            <select name="items[0][product_id]" onchange="fillProduct(0, this)" style="width:100%">
                                <option value="">Type or click to select an item</option>
                                @foreach($products as $p)
                                @php
                                    $additionalData = is_string($p->additional_data)
                                        ? json_decode($p->additional_data, true)
                                        : ($p->additional_data ?? []);
                                    $pGst    = (float)($additionalData['gst'] ?? 0);
                                    $imgData = is_string($p->product_image)
                                        ? json_decode($p->product_image, true)
                                        : ($p->product_image ?? []);
                                    $imgPath = $imgData['front_image'] ?? null;
                                    $sku     = $p->sku ?? '';
                                    $stock   = $p->opening_stock ?? 0;
                                @endphp
                                <option value="{{ $p->id }}"
                                        data-name="{{ $p->name }}"
                                        data-rate="{{ $p->selling_price ?? 0 }}"
                                        data-sku="{{ $sku }}"
                                        data-stock="{{ $stock }}"
                                        data-unit="{{ $p->unit }}"
                                        data-gst="{{ $pGst }}"
                                        data-img="{{ $imgPath ? asset($imgPath) : '' }}">
                                    {{ $p->name }}
                                    @if($sku) [{{ $sku }}] @endif
                                    — ₹{{ number_format($p->selling_price ?? 0, 2) }}
                                    (Stock: {{ $stock }} {{ $p->unit }})
                                </option>
                                @endforeach
                            </select>
                            <div class="product-meta" id="imeta-0"></div>
                            <input type="hidden" name="items[0][item_name]" id="iname-0" value="">
                        </td>
                        <td style="text-align:center;font-size:12px;color:#888" id="isku-cell-0">—</td>
                        <td class="r">
                            <input type="number" name="items[0][quantity]" id="iqty-0"
                                   value="1" min="0.01" step="0.01"
                                   style="width:80px;text-align:right"
                                   oninput="calcRow(0)">
                        </td>
                        <td class="r">
                            <div style="display:flex;align-items:center;justify-content:flex-end">
                                <input type="number" name="items[0][gst_value]" id="igstval-0"
                                       value="0" min="0" step="0.01"
                                       style="width:55px;text-align:right;height:30px;
                                              border:1px solid #d0d5dd;border-right:none;
                                              border-radius:4px 0 0 4px;padding:0 6px;font-size:13px"
                                       oninput="calcRow(0)">
                                <select name="items[0][gst_type]" id="igsttype-0"
                                        onchange="calcRow(0)"
                                        style="width:40px;height:30px;border:1px solid #d0d5dd;
                                               border-radius:0 4px 4px 0;font-size:12px;
                                               background:#f8f9fa;padding:0 2px;appearance:none;text-align:center">
                                    <option value="%">%</option>
                                    <option value="₹">₹</option>
                                </select>
                            </div>
                            <div style="font-size:10px;color:#27ae60;text-align:right;margin-top:2px" id="igstamt-0">₹0.00</div>
                            <input type="hidden" name="items[0][gst_amount]" id="igstamthidden-0" value="0">
                        </td>
                        <td class="r">
                            <input type="number" name="items[0][rate]" id="irate-0"
                                   value="0.00" min="0" step="0.01"
                                   style="width:100px;text-align:right"
                                   oninput="calcRow(0)">
                        </td>
                        <td class="r" id="iamt-0" style="font-weight:600;color:#1a1a2e">0.00</td>
                        <td><button type="button" class="btn-del" onclick="delRow(this)">&#10005;</button></td>
                    </tr>
                </tbody>
            </table>

            <div class="tbl-acc-row">
                <button type="button" class="btn-acc">&#128441; Select an account</button>
                <button type="button" class="btn-acc">&#127991; Reporting Tags</button>
            </div>

            <div class="tbl-footer">
                <button type="button" class="btn-addrow" onclick="addRow()">&#43; Add New Row</button>
                <button type="button" class="btn-addrow" onclick="openBulkModal()">&#43; Add Items in Bulk</button>
            </div>

            {{-- Totals --}}
            <div class="totals-wrap">
                <div class="totals-box">
                    <div class="tot-row">
                        <span>Sub Total</span>
                        <span id="subtotal-display">0.00</span>
                        <input type="hidden" name="subtotal" id="subtotal-val">
                    </div>
                    <div class="tot-row">
                        <span>Discount</span>
                        <div class="disc-wrap">
                            <input type="number" name="discount_value" id="disc-pct"
                                   value="0" min="0" step="0.01" oninput="calcTotals()">
                            <select id="disc-type" onchange="calcTotals()"
                                    style="width:46px;height:28px;font-size:12px;border:1px solid #d0d5dd;
                                           border-radius:4px;background:#f8f9fa;padding:0 2px;
                                           appearance:none;text-align:center;cursor:pointer">
                                <option value="%">%</option>
                                <option value="₹">₹</option>
                            </select>
                            <span id="disc-display" style="min-width:55px;text-align:right">0.00</span>
                        </div>
                        <input type="hidden" name="discount_percent" id="disc-pct-val">
                        <input type="hidden" name="discount_amount"  id="disc-amt-val">
                    </div>
                    <div class="tax-row">
                        <div class="radio-grp">
                            <label><input type="radio" name="tax_type" value="TDS" checked onchange="calcTotals()"> TDS</label>
                            <label><input type="radio" name="tax_type" value="TCS" onchange="calcTotals()"> TCS</label>
                        </div>
                        <div class="tax-right">
                            <select name="tax_percent" id="tax-select" onchange="calcTotals()">
                                <option value="0">Select a Tax</option>
                                <option value="5">GST 5%</option>
                                <option value="12">GST 12%</option>
                                <option value="18">GST 18%</option>
                                <option value="28">GST 28%</option>
                            </select>
                            <span id="tax-display">0.00</span>
                        </div>
                        <input type="hidden" name="tax_amount" id="tax-amt-val">
                    </div>
                    <div class="adj-row">
                        <input type="text" value="Adjustment" style="width:120px;height:28px;background:#f8f9fa;font-size:13px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <input type="number" name="adjustment" id="adj-input"
                                   value="0" step="0.01"
                                   style="width:80px;height:28px;text-align:right"
                                   oninput="calcTotals()">
                        </div>
                    </div>
                    <div class="tot-row grand">
                        <span>Total (₹)</span>
                        <span id="grand-display">0.00</span>
                        <input type="hidden" name="grand_total" id="grand-val">
                    </div>
                </div>
            </div>

        </div>{{-- end card 2 --}}

        {{-- Bottom Section --}}
        <div class="bottom-grid">
            <div class="card">
                <div class="section-lbl">Customer Notes</div>
                <textarea name="customer_notes" placeholder="Thanks for your business.">{{ old('customer_notes') }}</textarea>
                <p style="font-size:11px;color:#aaa;margin-top:6px">Will be displayed on the invoice</p>
            </div>
            <div class="card">
                <div class="section-lbl">Terms &amp; Conditions</div>
                <textarea name="terms_conditions" placeholder="Enter the terms and conditions of your business to be displayed in your transaction">{{ old('terms_conditions') }}</textarea>
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="footer-bar">
            <button type="submit" name="action" value="draft" class="btn-draft">Save as Draft</button>
            <button type="submit" name="action" value="send"  class="btn-primary">Save and Send &#9660;</button>
            <a href="{{ route('invoices.index') }}" class="btn-cancel">Cancel</a>
            <div class="footer-totals">
                <span>Total Amount: <strong id="footer-amount">₹ 0.00</strong></span>
                <span>Total Quantity: <strong id="footer-qty">0</strong></span>
            </div>
        </div>

        </form>
    </main>
</div>

{{-- REFERRAL MODAL --}}
<div class="modal-overlay" id="referral-modal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>&#128101; Manage Referrals / References</h3>
            <button class="modal-close" onclick="closeReferralModal()">&#10005;</button>
        </div>
        <div class="modal-toolbar">
            <input class="modal-search" type="text" id="ref-search" placeholder="Search by name, email, phone..."
                   oninput="filterReferrals()">
            <button class="btn-add-ref" onclick="showRefForm()">&#43; New Referral</button>
        </div>
        <div class="ref-form" id="ref-form">
            <div class="ref-form-grid">
                <div class="ref-form-field">
                    <label>Name <span>*</span></label>
                    <input type="text" id="ref-name" placeholder="Full Name">
                </div>
                <div class="ref-form-field">
                    <label>Email</label>
                    <input type="text" id="ref-email" placeholder="email@example.com">
                </div>
                <div class="ref-form-field">
                    <label>Phone</label>
                    <input type="text" id="ref-phone" placeholder="+91 9876543210">
                </div>
                <div class="ref-form-field">
                    <label>Type <span>*</span></label>
                    <select id="ref-type">
                        <option value="referral">Referral</option>
                        <option value="agent">Agent</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
            <div class="ref-form-actions" style="margin-top:10px;">
                <button class="btn-ref-save" onclick="saveReferral()">Save</button>
                <button class="btn-ref-cancel" onclick="hideRefForm()">Cancel</button>
            </div>
            <input type="hidden" id="ref-edit-id" value="">
        </div>
        <div class="ref-table-wrap">
            <table class="ref-table">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ref-tbody">
                    <tr>
                        <td colspan="5">
                            <div class="ref-empty">
                                <div class="icon">👥</div>
                                <div>Loading referrals...</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn-draft" onclick="closeReferralModal()">Close</button>
        </div>
    </div>
</div>

{{-- CUSTOMER DETAIL PANEL --}}
<div class="cust-panel" id="cust-panel">
    <div class="cust-panel-header">
        <div style="display:flex;align-items:center;gap:10px">
            <div class="cust-panel-avatar" id="cp-avatar">?</div>
            <div>
                <div class="cust-panel-name">
                    <span id="cp-name">—</span>
                    <a href="#" id="cp-link" target="_blank">↗ View</a>
                </div>
                <div style="font-size:11px;color:#888" id="cp-type">Customer</div>
            </div>
        </div>
        <button class="cust-panel-close" onclick="closeCustPanel()">✕</button>
    </div>
    <div class="cust-panel-tabs">
        <div class="cust-tab active" onclick="switchCustTab('details',this)">Details</div>
        <div class="cust-tab" onclick="switchCustTab('contacts',this)">Contacts</div>
        <div class="cust-tab" onclick="switchCustTab('address',this)">Address</div>
        <div class="cust-tab" onclick="switchCustTab('activity',this)">Activity</div>
    </div>
    <div class="cust-panel-body">
        <div class="cust-tab-pane active" id="cptab-details">
            <div class="cust-summary-cards">
                <div class="cust-summary-card">
                    <div class="icon">⚠️</div>
                    <div class="label">Outstanding</div>
                    <div class="value" id="cp-outstanding">₹0.00</div>
                </div>
                <div class="cust-summary-card">
                    <div class="icon">✅</div>
                    <div class="label">Unused Credits</div>
                    <div class="value" id="cp-credits">₹0.00</div>
                </div>
            </div>
            <div class="cust-detail-section">
                <div class="cust-detail-title">Contact Details</div>
                <div id="cp-details-rows"></div>
            </div>
        </div>
        <div class="cust-tab-pane" id="cptab-contacts">
            <div id="cp-contacts-list">
                <div style="color:#aaa;font-size:13px;text-align:center;padding:20px">No contacts found</div>
            </div>
        </div>
        <div class="cust-tab-pane" id="cptab-address">
            <div id="cp-address-content">
                <div style="color:#aaa;font-size:13px;text-align:center;padding:20px">No address found</div>
            </div>
        </div>
        <div class="cust-tab-pane" id="cptab-activity">
            <div id="cp-activity-list">
                <div style="color:#aaa;font-size:13px;text-align:center;padding:20px">Loading...</div>
            </div>
        </div>
    </div>
</div>

{{-- BULK ADD ITEMS MODAL --}}
<div id="bulk-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
     z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:880px;max-height:88vh;
                display:flex;flex-direction:column;box-shadow:0 12px 40px rgba(0,0,0,0.2);
                overflow:hidden;animation:modalIn 0.2s ease;">
        {{-- Bulk Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid #e3e6ea;flex-shrink:0;">
            <h3 style="font-size:15px;font-weight:600;color:#1a1a2e;">&#128230; Add Items in Bulk</h3>
            <button onclick="closeBulkModal()" style="background:none;border:none;font-size:20px;
                    color:#888;cursor:pointer;width:28px;height:28px;display:flex;align-items:center;justify-content:center;">&#10005;</button>
        </div>
        {{-- Bulk Toolbar --}}
        <div style="display:flex;gap:12px;align-items:center;padding:12px 20px;
                    border-bottom:1px solid #f0f2f4;flex-wrap:wrap;flex-shrink:0;">
            <input id="bulk-search" type="text" placeholder="Type to search or scan the barcode of the item"
                   style="flex:1;min-width:240px;height:34px;border:1px solid #d0d5dd;border-radius:6px;
                          padding:0 10px;font-size:13px;"
                   oninput="filterBulkProducts()">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#555;cursor:pointer;">
                <input type="checkbox" id="bulk-subcategory"> Include sub-categories
            </label>
        </div>
        {{-- Bulk Body --}}
        <div style="display:flex;flex:1;overflow:hidden;min-height:0;">
            <div style="width:52%;border-right:1px solid #e3e6ea;overflow-y:auto;padding:10px 0;"
                 id="bulk-product-list">
                <div style="text-align:center;padding:40px;color:#aaa;font-size:13px;">
                    Select a location to load products
                </div>
            </div>
            <div style="flex:1;overflow-y:auto;padding:16px 20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">
                        Selected Items
                        <span id="bulk-selected-count"
                              style="display:inline-flex;align-items:center;justify-content:center;
                                     width:22px;height:22px;background:#e8f0fe;color:#4a90d9;
                                     border-radius:50%;font-size:12px;margin-left:4px;">0</span>
                    </span>
                    <span style="font-size:12px;color:#888;">
                        Total Quantity: <strong id="bulk-total-qty">0</strong>
                    </span>
                </div>
                <div id="bulk-selected-list">
                    <div style="color:#aaa;font-size:13px;text-align:center;padding:40px;">
                        Click the item names from the left pane to select them
                    </div>
                </div>
            </div>
        </div>
        {{-- Bulk Footer --}}
        <div id="bulk-footer" style="display:none;padding:12px 20px;border-top:1px solid #e3e6ea;
                                     justify-content:flex-end;gap:10px;flex-shrink:0;">
            <button onclick="confirmBulkAdd()"
                    style="background:#4a90d9;color:#fff;border:none;border-radius:6px;
                           padding:9px 22px;font-size:13px;font-weight:500;cursor:pointer;">
                Add Items
            </button>
            <button onclick="closeBulkModal()"
                    style="background:#fff;color:#333;border:1px solid #d0d5dd;border-radius:6px;
                           padding:9px 22px;font-size:13px;cursor:pointer;">
                Cancel
            </button>
        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ══════════════════════════════════════════
// PRODUCTS MAP
// ══════════════════════════════════════════
const PRODUCTS = {
    @foreach($products as $p)
    @php
        $additionalData = is_string($p->additional_data)
            ? json_decode($p->additional_data, true)
            : ($p->additional_data ?? []);
        $pGst    = (float)($additionalData['gst'] ?? 0);
        $imgData = is_string($p->product_image)
            ? json_decode($p->product_image, true)
            : ($p->product_image ?? []);
        $imgPath = $imgData['front_image'] ?? null;
        $pStock  = $p->opening_stock ?? 0;
    @endphp
    {{ $p->id }}: {
        name:  "{{ addslashes($p->name) }}",
        rate:  {{ (float)($p->selling_price ?? 0) }},
        sku:   "{{ addslashes($p->sku ?? '') }}",
        stock: {{ $pStock }},
        unit:  "{{ addslashes($p->unit ?? '') }}",
        img:   "{{ $imgPath ? asset($imgPath) : '' }}",
        gst:   {{ $pGst }}
    },
    @endforeach
};

let rowCount = 1;

// ══════════════════════════════════════════
// ADDRESS FORMAT
// ══════════════════════════════════════════
function formatAddress(addr) {
    if (!addr) return '<span style="color:#ccc">No address found</span>';
    const lines = [
        addr.attention, addr.street1, addr.street2,
        [addr.city, addr.pincode].filter(Boolean).join(' - '),
        addr.state, addr.country,
        addr.phone ? '📞 ' + addr.phone : null,
    ].filter(Boolean);
    return lines.length
        ? lines.map(l => `<div>${l}</div>`).join('')
        : '<span style="color:#ccc">No address</span>';
}

function formatAddressLines(addr) {
    if (!addr) return '—';
    return [addr.attention, addr.street1, addr.street2,
        [addr.city, addr.pincode].filter(Boolean).join(' - '),
        addr.state, addr.country,
        addr.phone ? '📞 ' + addr.phone : null,
        addr.fax   ? 'Fax: ' + addr.fax : null,
    ].filter(Boolean).map(l => `<div>${l}</div>`).join('');
}

// ══════════════════════════════════════════
// CUSTOMER CHANGE — single authoritative function
// ══════════════════════════════════════════
function onCustomerChange(customerId) {
    const sel      = document.getElementById('customer-select');
    const opt      = sel.options[sel.selectedIndex];
    const box      = document.getElementById('customer-addr-box');
    const billing  = document.getElementById('billing-addr-display');
    const shipping = document.getElementById('shipping-addr-display');
    const infoBtn  = document.getElementById('btn-cust-info');

    if (!customerId) {
        box.style.display     = 'none';
        infoBtn.style.display = 'none';
        closeCustPanel();
        return;
    }

    infoBtn.style.display = 'flex';

    let addrData = null;
    try { addrData = JSON.parse(opt.dataset.address || 'null'); } catch(e) {}

    if (addrData) {
        billing.innerHTML  = formatAddress(addrData.billing);
        shipping.innerHTML = formatAddress(addrData.shipping);
        box.style.display  = 'block';
    } else {
        box.style.display = 'none';
    }

    window._selectedCustomerId   = customerId;
    window._selectedCustomerName = opt.textContent.trim();
}

function openCustPanelFromBtn() {
    if (!window._selectedCustomerId) return;
    openCustPanel(window._selectedCustomerId, window._selectedCustomerName);
}

// ══════════════════════════════════════════
// LOCATION-SPECIFIC STOCK FETCH (for single row)
// ══════════════════════════════════════════
function fetchLocationStock(idx, productId, locationId, unit) {
    fetch(`/invoices/location-stock?location_id=${locationId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const prod = (data.products || []).find(p => p.id == productId);
        if (!prod) return; // ✅ not found-ஆ இருந்தா existing value மாத்தாதே
        const stock = prod.stock_on_hand;
        const el = document.getElementById('stock-display-' + idx);
        if (el) {
            el.className = stock > 0 ? 'stock-ok' : 'stock-low';
            el.innerHTML = `<strong>${stock}</strong> ${unit}`;
        }
    })
    .catch(() => {});
}

// ══════════════════════════════════════════
// PRODUCT FILL
// ══════════════════════════════════════════
function fillProduct(idx, sel) {
    const pid       = parseInt(sel.value);
    const nameInput = document.getElementById('iname-'     + idx);
    const metaEl    = document.getElementById('imeta-'     + idx);
    const skuCell   = document.getElementById('isku-cell-' + idx);
    const imgBox    = document.getElementById('iimg-'      + idx);

    if (pid && PRODUCTS[pid]) {
        const p = PRODUCTS[pid];

        nameInput.value = p.name;
        document.getElementById('igstval-'  + idx).value = p.gst || 0;
        document.getElementById('igsttype-' + idx).value = '%';
        document.getElementById('irate-'    + idx).value = parseFloat(p.rate).toFixed(2);
        skuCell.textContent = p.sku || '—';

        imgBox.innerHTML = p.img
            ? `<img src="${p.img}" alt="${p.name}">`
            : '<span class="noimg">&#128247;</span>';

        metaEl.innerHTML = `
            <span style="color:#666">SKU: <strong>${p.sku || '—'}</strong></span>
            &nbsp;|&nbsp;
            Stock: <span id="stock-display-${idx}" class="${p.stock > 0 ? 'stock-ok' : 'stock-low'}">
                <strong>${p.stock}</strong> ${p.unit}
            </span>
            <span id="stock-warn-${idx}" style="display:none;color:#e05050;font-weight:600;
                  margin-left:6px;font-size:11px"></span>
        `;
        metaEl.classList.add('show');

        // ✅ FIX: optional chaining — crash ஆகாது
        const warehouseId = document.querySelector('select[name="warehouse_location_id"]')?.value
                         || document.getElementById('location-select')?.value;
        if (warehouseId) {
            fetchLocationStock(idx, pid, warehouseId, p.unit);
        }

    } else {
        nameInput.value = '';
        skuCell.textContent = '—';
        metaEl.classList.remove('show');
        imgBox.innerHTML = '<span class="noimg">&#128247;</span>';
        document.getElementById('irate-'   + idx).value = '0.00';
        document.getElementById('igstval-' + idx).value = '0';
    }

    calcRow(idx);
}

// ══════════════════════════════════════════
// VALIDATE QTY — ✅ NEW (was missing, caused crash)
// ══════════════════════════════════════════
function validateQty(idx) {
    const qtyInput = document.getElementById('iqty-' + idx);
    const warnEl   = document.getElementById('stock-warn-' + idx);
    if (!qtyInput || !warnEl) return;

    const qty     = parseFloat(qtyInput.value) || 0;
    const metaEl  = document.getElementById('imeta-' + idx);
    if (!metaEl || !metaEl.classList.contains('show')) return;

    const stockEl = document.getElementById('stock-display-' + idx);
    if (!stockEl) return;

    // stock value — strong tag-ல் இருக்கு
    const strongEl = stockEl.querySelector('strong');
    const stock    = parseFloat(strongEl ? strongEl.textContent : '0') || 0;

    if (stock > 0 && qty > stock) {
        warnEl.textContent   = `⚠ Only ${stock} available`;
        warnEl.style.display = 'inline';
        qtyInput.style.borderColor = '#e05050';
    } else {
        warnEl.style.display = 'none';
        qtyInput.style.borderColor = '';
    }
}

// ══════════════════════════════════════════
// CALC ROW
// ══════════════════════════════════════════
function calcRow(idx) {
    const qty     = parseFloat(document.getElementById('iqty-'     + idx)?.value) || 0;
    const rate    = parseFloat(document.getElementById('irate-'    + idx)?.value) || 0;
    const gstVal  = parseFloat(document.getElementById('igstval-'  + idx)?.value) || 0;
    const gstType = document.getElementById('igsttype-' + idx)?.value || '%';

    const baseAmt = qty * rate;
    const gstAmt  = gstType === '%' ? (baseAmt * gstVal / 100) : (gstVal * qty);
    const total   = baseAmt + gstAmt;

    const gstEl = document.getElementById('igstamt-' + idx);
    if (gstEl) gstEl.textContent = '₹' + gstAmt.toFixed(2);

    const gstHidden = document.getElementById('igstamthidden-' + idx);
    if (gstHidden) gstHidden.value = gstAmt.toFixed(2);

    const amtEl = document.getElementById('iamt-' + idx);
    if (amtEl) amtEl.textContent = total.toFixed(2);

    validateQty(idx);
    calcTotals();
}

// ══════════════════════════════════════════
// CALC TOTALS
// ══════════════════════════════════════════
function calcTotals() {
    let subtotal = 0, totalQty = 0;

    document.querySelectorAll('#items-body tr').forEach(tr => {
        const idx     = tr.dataset.index;
        const qty     = parseFloat(document.getElementById('iqty-'    + idx)?.value) || 0;
        const rate    = parseFloat(document.getElementById('irate-'   + idx)?.value) || 0;
        const gstVal  = parseFloat(document.getElementById('igstval-' + idx)?.value) || 0;
        const gstType = document.getElementById('igsttype-' + idx)?.value || '%';

        const baseAmt = qty * rate;
        const gstAmt  = gstType === '%' ? (baseAmt * gstVal / 100) : (gstVal * qty);

        subtotal += baseAmt + gstAmt;
        totalQty += qty;
    });

    const discVal  = parseFloat(document.getElementById('disc-pct').value) || 0;
    const discType = document.getElementById('disc-type').value;
    let discAmt, discPct;

    if (discType === '%') {
        discPct = Math.min(discVal, 100);
        discAmt = subtotal * discPct / 100;
    } else {
        discAmt = Math.min(discVal, subtotal);
        discPct = subtotal > 0 ? (discAmt / subtotal * 100) : 0;
    }

    const after  = subtotal - discAmt;
    const taxPct = parseFloat(document.getElementById('tax-select').value) || 0;
    const taxAmt = after * taxPct / 100;
    const adj    = parseFloat(document.getElementById('adj-input').value)  || 0;
    const grand  = after + taxAmt + adj;

    document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
    document.getElementById('disc-display').textContent     = discAmt.toFixed(2);
    document.getElementById('tax-display').textContent      = Math.abs(taxAmt).toFixed(2);
    document.getElementById('grand-display').textContent    = grand.toFixed(2);

    document.getElementById('disc-pct-val').value  = discPct.toFixed(4);
    document.getElementById('disc-amt-val').value  = discAmt.toFixed(2);
    document.getElementById('tax-amt-val').value   = taxAmt.toFixed(2);
    document.getElementById('grand-val').value     = grand.toFixed(2);
    document.getElementById('subtotal-val').value  = subtotal.toFixed(2);

    document.getElementById('footer-amount').textContent = '₹ ' + grand.toFixed(2);
    document.getElementById('footer-qty').textContent    = totalQty.toFixed(2);
}

// ══════════════════════════════════════════
// ADD NEW ROW
// ══════════════════════════════════════════
function addRow() {
    const idx   = rowCount++;
    const tbody = document.getElementById('items-body');
    const tr    = document.createElement('tr');
    tr.dataset.index = idx;

    let options = '<option value="">Type or click to select an item</option>';
    for (const [pid, pd] of Object.entries(PRODUCTS)) {
        options += `<option value="${pid}"
            data-name="${pd.name}" data-rate="${pd.rate}"
            data-sku="${pd.sku}" data-stock="${pd.stock}"
            data-unit="${pd.unit}" data-img="${pd.img}"
            data-gst="${pd.gst || 0}">
            ${pd.name} ${pd.sku ? '['+pd.sku+']' : ''} — ₹${parseFloat(pd.rate).toFixed(2)}
            (Stock: ${pd.stock} ${pd.unit})
        </option>`;
    }

    tr.innerHTML = `
        <td class="drag-col">&#8942;&#8942;</td>
        <td class="img-col"><div class="item-img-box" id="iimg-${idx}"><span class="noimg">&#128247;</span></div></td>
        <td>
            <select name="items[${idx}][product_id]" onchange="fillProduct(${idx}, this)" style="width:100%">
                ${options}
            </select>
            <div class="product-meta" id="imeta-${idx}"></div>
            <input type="hidden" name="items[${idx}][item_name]" id="iname-${idx}" value="">
        </td>
        <td style="text-align:center;font-size:12px;color:#888" id="isku-cell-${idx}">—</td>
        <td class="r">
            <input type="number" name="items[${idx}][quantity]" id="iqty-${idx}"
                   value="1" min="0.01" step="0.01" style="width:80px;text-align:right"
                   oninput="calcRow(${idx})">
        </td>
        <td class="r">
            <div style="display:flex;align-items:center;justify-content:flex-end">
                <input type="number" name="items[${idx}][gst_value]" id="igstval-${idx}"
                       value="0" min="0" step="0.01"
                       style="width:55px;text-align:right;height:30px;border:1px solid #d0d5dd;
                              border-right:none;border-radius:4px 0 0 4px;padding:0 6px;font-size:13px"
                       oninput="calcRow(${idx})">
                <select name="items[${idx}][gst_type]" id="igsttype-${idx}"
                        onchange="calcRow(${idx})"
                        style="width:40px;height:30px;border:1px solid #d0d5dd;
                               border-radius:0 4px 4px 0;font-size:12px;
                               background:#f8f9fa;padding:0 2px;appearance:none;text-align:center">
                    <option value="%">%</option>
                    <option value="₹">₹</option>
                </select>
            </div>
            <div style="font-size:10px;color:#27ae60;text-align:right;margin-top:2px" id="igstamt-${idx}">₹0.00</div>
            <input type="hidden" name="items[${idx}][gst_amount]" id="igstamthidden-${idx}" value="0">
        </td>
        <td class="r">
            <input type="number" name="items[${idx}][rate]" id="irate-${idx}"
                   value="0.00" min="0" step="0.01" style="width:100px;text-align:right"
                   oninput="calcRow(${idx})">
        </td>
        <td class="r" id="iamt-${idx}" style="font-weight:600;color:#1a1a2e">0.00</td>
        <td><button type="button" class="btn-del" onclick="delRow(this)">&#10005;</button></td>
    `;
    tbody.appendChild(tr);
    calcTotals();
}

function delRow(btn) {
    if (document.querySelectorAll('#items-body tr').length <= 1) return;
    btn.closest('tr').remove();
    calcTotals();
}

// ══════════════════════════════════════════
// DUE DATE CALC
// ══════════════════════════════════════════
function calcDueDate() {
    const terms   = document.getElementById('terms-select').value;
    const invDate = document.querySelector('input[name=invoice_date]').value;
    if (!invDate) return;
    const d    = new Date(invDate);
    const days = { 'Net 15': 15, 'Net 30': 30, 'Net 45': 45, 'Net 60': 60 };
    if (days[terms]) {
        d.setDate(d.getDate() + days[terms]);
        document.getElementById('due-date').value = d.toISOString().split('T')[0];
    } else {
        document.getElementById('due-date').value = invDate;
    }
}

// ══════════════════════════════════════════
// LOCATION → SERIES → INVOICE NUMBER
// ══════════════════════════════════════════
function onLocationChange(locationId) {
    bulkProducts = [];
    bulkSelected = {};
    
    if (!locationId) {
        document.getElementById('series_wrapper').style.display = 'none';
        return;
    }

    // ✅ Bulk modal open-ஆ இருந்தா products reload பண்ணு
    if (document.getElementById('bulk-modal').style.display === 'flex') {
        loadBulkProducts(locationId);
    }

    fetch(`/invoices/invoice-number?location_id=${locationId}`, {

    headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('invoice-number-input').value = data.invoice_number ?? '';

        const hint = document.getElementById('invoice-format-hint');
        if (hint && data.format_preview) hint.textContent = `Format: ${data.format_preview}`;

        const wrapper      = document.getElementById('series_wrapper');
        const seriesSelect = document.getElementById('series_id');
        seriesSelect.innerHTML = '';

        if (data.series_list && data.series_list.length > 0) {
            data.series_list.forEach(series => {
                const opt     = document.createElement('option');
                opt.value     = series.id;
                const preview = series.prefix + String(
                    series.last_number
                        ? (parseInt(series.last_number) + 1)
                        : series.start
                ).padStart(String(series.start).length, '0');
                opt.textContent = series.name + '  (' + preview + ')';
                seriesSelect.appendChild(opt);
            });
            wrapper.style.display = 'block';
            updateInvoiceNumberBySeries(locationId, data.series_list[0].id);
        } else {
            wrapper.style.display = 'none';
        }
    })
    .catch(() => console.warn('Invoice number fetch failed'));
}

function onSeriesChange(seriesId) {
    const locationId = document.getElementById('location-select').value;
    if (!locationId || !seriesId) return;
    updateInvoiceNumberBySeries(locationId, seriesId);
}

function updateInvoiceNumberBySeries(locationId, seriesId) {
    fetch(`/invoices/invoice-number?location_id=${locationId}&series_id=${seriesId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('invoice-number-input').value = data.invoice_number ?? '';
    })
    .catch(() => console.warn('Series invoice number fetch failed'));
}

// ══════════════════════════════════════════
// BULK ADD ITEMS MODAL
// ══════════════════════════════════════════
let bulkProducts = [];
let bulkSelected = {};

function openBulkModal() {
    document.getElementById('bulk-modal').style.display = 'flex';
    bulkSelected = {};
    renderBulkSelected();

    // ✅ FIX: optional chaining — warehouse_location_id இல்லன்னா location-select use பண்ணு
    const warehouseId = document.querySelector('select[name="warehouse_location_id"]')?.value
                     || document.getElementById('location-select')?.value;

    if (warehouseId) {
        loadBulkProducts(warehouseId);
    } else {
        document.getElementById('bulk-product-list').innerHTML =
            '<div style="text-align:center;padding:40px;color:#aaa;font-size:13px;">Select a location first</div>';
    }
}

function closeBulkModal() {
    document.getElementById('bulk-modal').style.display = 'none';
}

// ✅ FIX: backdrop click close
document.getElementById('bulk-modal').addEventListener('click', function(e) {
    if (e.target === this) closeBulkModal();
});

function loadBulkProducts(locationId) {
    document.getElementById('bulk-product-list').innerHTML =
        '<div style="text-align:center;padding:40px;color:#aaa;font-size:13px;">Loading products...</div>';

    fetch(`/invoices/location-stock?location_id=${locationId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        bulkProducts = data.products || [];
        renderBulkProducts(bulkProducts);
    })
    .catch(() => {
        document.getElementById('bulk-product-list').innerHTML =
            '<div style="text-align:center;padding:40px;color:#e05050;font-size:13px;">Failed to load products</div>';
    });
}

function filterBulkProducts() {
    const q = document.getElementById('bulk-search').value.toLowerCase();
    const filtered = q
        ? bulkProducts.filter(p =>
            p.name.toLowerCase().includes(q) ||
            (p.sku || '').toLowerCase().includes(q))
        : bulkProducts;
    renderBulkProducts(filtered);
}

function renderBulkProducts(list) {
    const container = document.getElementById('bulk-product-list');
    if (!list.length) {
        container.innerHTML =
            '<div style="text-align:center;padding:40px;color:#aaa;font-size:13px;">No products found in this location</div>';
        return;
    }
    container.innerHTML = list.map(p => {
        const isSelected = bulkSelected[p.id] !== undefined;
        const stockColor = p.stock_on_hand > 0 ? '#27ae60' : '#e05050';
        const stockNum   = parseFloat(p.stock_on_hand).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
        return `
        <div id="bulk-prow-${p.id}" onclick="toggleBulkProduct(${p.id})"
             style="display:flex;align-items:center;justify-content:space-between;
                    padding:10px 16px;cursor:pointer;
                    background:${isSelected ? '#eef4ff' : '#fff'};
                    border-left:3px solid ${isSelected ? '#4a90d9' : 'transparent'};
                    transition:background 0.1s;">
          <div>
            <div style="font-size:13px;font-weight:500;color:${isSelected ? '#4a90d9' : '#1a1a2e'}">${p.name}</div>
            <div style="font-size:11px;color:#888;margin-top:2px">Rate: ₹${parseFloat(p.rate).toFixed(2)}</div>
          </div>
          <div style="text-align:right;display:flex;align-items:center;gap:10px;">
            <div>
              <div style="font-size:11px;color:#888;">Stock on Hand</div>
              <div style="font-size:12px;font-weight:600;color:${stockColor}">${stockNum} ${p.unit}</div>
            </div>
            <div style="width:22px;height:22px;border-radius:50%;
                        background:${isSelected ? '#4a90d9' : '#e8eaed'};
                        display:flex;align-items:center;justify-content:center;">
              ${isSelected ? '<span style="color:#fff;font-size:14px">✓</span>' : ''}
            </div>
          </div>
        </div>`;
    }).join('');
}

function toggleBulkProduct(productId) {
    const p = bulkProducts.find(x => x.id == productId);
    if (!p) return;
    if (bulkSelected[productId] !== undefined) {
        delete bulkSelected[productId];
    } else {
        bulkSelected[productId] = 1;
    }
    renderBulkSelected();
    filterBulkProducts(); // re-render with current search
}

function renderBulkSelected() {
    const ids     = Object.keys(bulkSelected);
    const countEl = document.getElementById('bulk-selected-count');
    const footer  = document.getElementById('bulk-footer');

    countEl.textContent = ids.length;

    let totalQty = 0;
    ids.forEach(id => { totalQty += parseFloat(bulkSelected[id] || 0); });
    document.getElementById('bulk-total-qty').textContent = totalQty;

    if (!ids.length) {
        document.getElementById('bulk-selected-list').innerHTML =
            '<div style="color:#aaa;font-size:13px;text-align:center;padding:40px;">Click the item names from the left pane to select them</div>';
        footer.style.display = 'none';
        return;
    }
    footer.style.display = 'flex';

    document.getElementById('bulk-selected-list').innerHTML = ids.map(id => {
        const p   = bulkProducts.find(x => x.id == id);
        if (!p) return '';
        const qty       = bulkSelected[id];
        const overStock = parseFloat(qty) > parseFloat(p.stock_on_hand);
        return `
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:10px 0;border-bottom:1px solid #f0f2f4;">
          <div>
            <div style="font-size:13px;font-weight:500;color:#1a1a2e">${p.name}</div>
            ${overStock ? `<div style="font-size:11px;color:#e05050;margin-top:2px;">
              ⚠ Insufficient stock — only ${parseFloat(p.stock_on_hand).toFixed(2)} ${p.unit} available
            </div>` : ''}
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <button type="button" onclick="changeBulkQty(${id}, -1)"
                    style="width:26px;height:26px;border-radius:50%;border:1px solid #d0d5dd;
                           background:${qty <= 1 ? '#f5f5f5':'#fff'};font-size:16px;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;color:#555">−</button>
            <input type="number" value="${qty}" min="1" id="bulk-qty-input-${id}"
                   style="width:52px;height:30px;text-align:center;
                          border:2px solid ${overStock?'#e05050':'#4a90d9'};
                          border-radius:6px;font-size:13px;font-weight:600;"
                   oninput="setBulkQty(${id}, this.value)">
            <button type="button" onclick="changeBulkQty(${id}, 1)"
                    style="width:26px;height:26px;border-radius:50%;border:none;
                           background:#4a90d9;font-size:16px;cursor:pointer;
                           display:flex;align-items:center;justify-content:center;color:#fff">+</button>
            <button type="button" onclick="removeBulkSelected(${id})"
                    style="width:26px;height:26px;border-radius:50%;border:1px solid #fecaca;
                           background:#fff;cursor:pointer;display:flex;align-items:center;
                           justify-content:center;color:#e05050;font-size:14px;">✕</button>
          </div>
        </div>`;
    }).join('');
}

function changeBulkQty(productId, delta) {
    const current = parseFloat(bulkSelected[productId] || 1);
    bulkSelected[productId] = Math.max(1, current + delta);
    renderBulkSelected();
}

function setBulkQty(productId, val) {
    const n = parseFloat(val);
    if (!isNaN(n) && n > 0) {
        bulkSelected[productId] = n;
        renderBulkSelected();
    }
}

function removeBulkSelected(productId) {
    delete bulkSelected[productId];
    renderBulkSelected();
    filterBulkProducts();
}

function confirmBulkAdd() {
    // Stock validation
    let hasError = false;
    for (const [id, qty] of Object.entries(bulkSelected)) {
        const p = bulkProducts.find(x => x.id == id);
        if (p && parseFloat(qty) > parseFloat(p.stock_on_hand)) {
            hasError = true;
            break;
        }
    }
    if (hasError) {
        alert('⚠ Some items exceed available stock. Please reduce the quantities highlighted in red.');
        return;
    }

    // Remove empty rows
    document.querySelectorAll('#items-body tr').forEach(tr => {
        const sel = tr.querySelector('select[name*="product_id"]');
        if (sel && !sel.value) tr.remove();
    });

    // Add rows for selected items
    for (const [id, qty] of Object.entries(bulkSelected)) {
        const p = bulkProducts.find(x => x.id == id);
        if (!p) continue;

        const idx   = rowCount++;
        const tbody = document.getElementById('items-body');
        const tr    = document.createElement('tr');
        tr.dataset.index = idx;

        let options = '<option value="">Type or click to select an item</option>';
        for (const [pid, pd] of Object.entries(PRODUCTS)) {
            const sel = parseInt(pid) === parseInt(id) ? 'selected' : '';
            options += `<option value="${pid}" ${sel}
                data-name="${pd.name}" data-rate="${pd.rate}"
                data-sku="${pd.sku}" data-stock="${pd.stock}"
                data-unit="${pd.unit}" data-img="${pd.img}"
                data-gst="${pd.gst || 0}">
                ${pd.name} ${pd.sku ? '['+pd.sku+']' : ''} — ₹${parseFloat(pd.rate).toFixed(2)}
                (Stock: ${pd.stock} ${pd.unit})
            </option>`;
        }

        tr.innerHTML = `
            <td class="drag-col">&#8942;&#8942;</td>
            <td class="img-col">
                <div class="item-img-box" id="iimg-${idx}">
                    ${p.img ? `<img src="${p.img}" alt="${p.name}">` : '<span class="noimg">&#128247;</span>'}
                </div>
            </td>
            <td>
                <select name="items[${idx}][product_id]" onchange="fillProduct(${idx}, this)" style="width:100%">
                    ${options}
                </select>
                <div class="product-meta show" id="imeta-${idx}">
                    <span style="color:#666">SKU: <strong>${p.sku || '—'}</strong></span>
                    &nbsp;|&nbsp;
                    Stock: <span id="stock-display-${idx}" class="${p.stock_on_hand > 0 ? 'stock-ok' : 'stock-low'}">
                        <strong>${parseFloat(p.stock_on_hand)}</strong> ${p.unit}
                    </span>
                    <span id="stock-warn-${idx}" style="display:none;color:#e05050;font-weight:600;
                          margin-left:6px;font-size:11px"></span>
                </div>
                <input type="hidden" name="items[${idx}][item_name]" id="iname-${idx}" value="${p.name}">
            </td>
            <td style="text-align:center;font-size:12px;color:#888" id="isku-cell-${idx}">${p.sku || '—'}</td>
            <td class="r">
                <input type="number" name="items[${idx}][quantity]" id="iqty-${idx}"
                       value="${qty}" min="0.01" step="0.01" style="width:80px;text-align:right"
                       oninput="calcRow(${idx})">
            </td>
            <td class="r">
                <div style="display:flex;align-items:center;justify-content:flex-end">
                    <input type="number" name="items[${idx}][gst_value]" id="igstval-${idx}"
                           value="${p.gst || 0}" min="0" step="0.01"
                           style="width:55px;text-align:right;height:30px;border:1px solid #d0d5dd;
                                  border-right:none;border-radius:4px 0 0 4px;padding:0 6px;font-size:13px"
                           oninput="calcRow(${idx})">
                    <select name="items[${idx}][gst_type]" id="igsttype-${idx}"
                            onchange="calcRow(${idx})"
                            style="width:40px;height:30px;border:1px solid #d0d5dd;
                                   border-radius:0 4px 4px 0;font-size:12px;
                                   background:#f8f9fa;padding:0 2px;appearance:none;text-align:center">
                        <option value="%">%</option>
                        <option value="₹">₹</option>
                    </select>
                </div>
                <div style="font-size:10px;color:#27ae60;text-align:right;margin-top:2px" id="igstamt-${idx}">₹0.00</div>
                <input type="hidden" name="items[${idx}][gst_amount]" id="igstamthidden-${idx}" value="0">
            </td>
            <td class="r">
                <input type="number" name="items[${idx}][rate]" id="irate-${idx}"
                       value="${parseFloat(p.rate).toFixed(2)}" min="0" step="0.01"
                       style="width:100px;text-align:right"
                       oninput="calcRow(${idx})">
            </td>
            <td class="r" id="iamt-${idx}" style="font-weight:600;color:#1a1a2e">0.00</td>
            <td><button type="button" class="btn-del" onclick="delRow(this)">&#10005;</button></td>
        `;
        tbody.appendChild(tr);
        calcRow(idx);
    }

    closeBulkModal();
    calcTotals();
}

// ══════════════════════════════════════════
// REFERRAL MODAL
// ══════════════════════════════════════════
let allReferrals     = [];
let selectedReferral = null;

function openReferralModal() {
    document.getElementById('referral-modal').classList.add('open');
    loadReferrals();
}
function closeReferralModal() {
    document.getElementById('referral-modal').classList.remove('open');
    hideRefForm();
}
document.getElementById('referral-modal').addEventListener('click', function(e) {
    if (e.target === this) closeReferralModal();
});

function loadReferrals() {
    fetch('/referrals', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => {
        allReferrals = data.data || [];
        renderReferrals(allReferrals);
    })
    .catch(() => {
        document.getElementById('ref-tbody').innerHTML =
            `<tr><td colspan="5"><div class="ref-empty"><div class="icon">⚠️</div><div>Failed to load referrals.</div></div></td></tr>`;
    });
}

function renderReferrals(list) {
    const tbody = document.getElementById('ref-tbody');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="5"><div class="ref-empty"><div class="icon">👥</div><div>No referrals found. Click "+ New Referral" to add one.</div></div></td></tr>`;
        return;
    }
    const typeBadge = t => {
        const map = { referral: 'type-referral', agent: 'type-agent', staff: 'type-staff' };
        return `<span class="type-badge ${map[t]||''}">${t.charAt(0).toUpperCase()+t.slice(1)}</span>`;
    };
    tbody.innerHTML = list.map(r => `
        <tr id="ref-row-${r.id}" class="${selectedReferral?.id == r.id ? 'selected-row' : ''}">
            <td><strong>${r.name}</strong></td>
            <td>${r.email || '—'}</td>
            <td>${r.phone || '—'}</td>
            <td>${typeBadge(r.type)}</td>
            <td>
                <div class="ref-action-btns">
                    <button class="btn-ref-select" onclick="selectReferral(${r.id},'${r.name.replace(/'/g,"\\'")}')">Select</button>
                    <button class="btn-ref-edit"   onclick="editReferral(${r.id})">✎ Edit</button>
                    <button class="btn-ref-del"    onclick="deleteReferral(${r.id})">🗑</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function filterReferrals() {
    const q = document.getElementById('ref-search').value.toLowerCase();
    renderReferrals(allReferrals.filter(r =>
        r.name.toLowerCase().includes(q) ||
        (r.email||'').toLowerCase().includes(q) ||
        (r.phone||'').toLowerCase().includes(q)
    ));
}

function selectReferral(id, name) {
    selectedReferral = { id, name };
    document.getElementById('referral-id-input').value             = id;
    document.getElementById('referral-placeholder').style.display  = 'none';
    const nameEl = document.getElementById('referral-selected-name');
    nameEl.textContent   = name;
    nameEl.style.display = 'inline';
    document.getElementById('referral-clear-btn').style.display    = 'inline';
    closeReferralModal();
}

function clearReferral() {
    selectedReferral = null;
    document.getElementById('referral-id-input').value                    = '';
    document.getElementById('referral-placeholder').style.display         = 'inline';
    document.getElementById('referral-selected-name').style.display       = 'none';
    document.getElementById('referral-clear-btn').style.display           = 'none';
}

function showRefForm(clearFields = true) {
    if (clearFields) {
        document.getElementById('ref-edit-id').value = '';
        document.getElementById('ref-name').value    = '';
        document.getElementById('ref-email').value   = '';
        document.getElementById('ref-phone').value   = '';
        document.getElementById('ref-type').value    = 'referral';
    }
    document.getElementById('ref-form').classList.add('open');
    document.getElementById('ref-name').focus();
}
function hideRefForm() {
    document.getElementById('ref-form').classList.remove('open');
}

function saveReferral() {
    const name   = document.getElementById('ref-name').value.trim();
    const email  = document.getElementById('ref-email').value.trim();
    const phone  = document.getElementById('ref-phone').value.trim();
    const type   = document.getElementById('ref-type').value;
    const editId = document.getElementById('ref-edit-id').value;

    if (!name) { alert('Name is required.'); document.getElementById('ref-name').focus(); return; }

    fetch(editId ? `/referrals/${editId}` : '/referrals', {
        method: editId ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ name, email, phone, type })
    })
    .then(r => r.json())
    .then(data => { if (data.success) { hideRefForm(); loadReferrals(); } else alert(data.message || 'Failed to save.'); })
    .catch(() => alert('Server error. Please try again.'));
}

function editReferral(id) {
    const r = allReferrals.find(x => x.id == id);
    if (!r) return;
    document.getElementById('ref-edit-id').value = r.id;
    document.getElementById('ref-name').value    = r.name;
    document.getElementById('ref-email').value   = r.email  || '';
    document.getElementById('ref-phone').value   = r.phone  || '';
    document.getElementById('ref-type').value    = r.type;
    showRefForm(false);
}

function deleteReferral(id) {
    if (!confirm('Delete this referral?')) return;
    fetch(`/referrals/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { if (selectedReferral?.id == id) clearReferral(); loadReferrals(); }
        else alert(data.message || 'Failed to delete.');
    })
    .catch(() => alert('Server error.'));
}

// ══════════════════════════════════════════
// FORM SUBMIT VALIDATION
// ══════════════════════════════════════════
document.getElementById('invoice-form').addEventListener('submit', function(e) {
    let valid = false;
    document.querySelectorAll('#items-body tr').forEach(tr => {
        const idx       = tr.dataset.index;
        const nameInput = document.getElementById('iname-' + idx);
        const sel       = tr.querySelector('select[name*="product_id"]');
        if (nameInput && nameInput.value.trim()) {
            valid = true;
        } else if (sel && sel.value && nameInput && !nameInput.value) {
            nameInput.value = sel.options[sel.selectedIndex].text.split('—')[0].trim();
            if (nameInput.value.trim()) valid = true;
        }
    });
    if (!valid) { e.preventDefault(); alert('Please add at least one item to the invoice.'); }
});

// ══════════════════════════════════════════
// CUSTOMER DETAIL PANEL
// ══════════════════════════════════════════
function openCustPanel(customerId, displayName) {
    const panel = document.getElementById('cust-panel');
    panel.classList.add('open');

    const initial = displayName.replace(/^(Mr\.|Mrs\.|Ms\.|Dr\.)\s*/i,'').trim()[0]?.toUpperCase() || '?';
    document.getElementById('cp-avatar').textContent = initial;
    document.getElementById('cp-name').textContent   = displayName;
    document.getElementById('cp-link').href          = `/customers/${customerId}`;

    switchCustTab('details', document.querySelector('.cust-tab'));

    fetch(`/customers/${customerId}/panel-data`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => renderCustPanel(data))
    .catch(() => {
        document.getElementById('cp-details-rows').innerHTML =
            '<div style="color:#e05050;font-size:12px">Failed to load customer data.</div>';
    });
}

function closeCustPanel() {
    document.getElementById('cust-panel').classList.remove('open');
}

function switchCustTab(tab, el) {
    document.querySelectorAll('.cust-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.cust-tab-pane').forEach(p => p.classList.remove('active'));
    if (el) el.classList.add('active');
    else document.querySelectorAll('.cust-tab')[['details','contacts','address','activity'].indexOf(tab)]?.classList.add('active');
    document.getElementById('cptab-' + tab)?.classList.add('active');
}

function renderCustPanel(data) {
    document.getElementById('cp-outstanding').textContent = '₹' + parseFloat(data.outstanding ?? 0).toFixed(2);
    document.getElementById('cp-credits').textContent     = '₹' + parseFloat(data.credits ?? 0).toFixed(2);
    document.getElementById('cp-type').textContent        = data.customer_type ?? 'Customer';

    const detailsMap = [
        ['Customer Type', data.customer_type],
        ['Currency',      data.currency ?? 'INR'],
        ['Payment Terms', data.payment_terms ?? '—'],
        ['PAN',           data.pan ?? '—'],
        ['GSTIN',         data.gstin ?? '—'],
        ['Portal Status', data.portal_status ?? '—'],
    ];
    document.getElementById('cp-details-rows').innerHTML = detailsMap
        .map(([k,v]) => `
            <div class="cust-detail-row">
                <span class="key">${k}</span>
                <span class="val">${v || '—'}</span>
            </div>`).join('');

    const contacts = data.contacts ?? [];
    document.getElementById('cp-contacts-list').innerHTML = contacts.length
        ? contacts.map(c => `
            <div class="contact-person-card">
                ${c.is_primary ? '<span class="cp-primary-badge">⭐ Primary</span>' : ''}
                <div class="cp-name">${c.name}</div>
                <div class="cp-info">
                    ${c.designation ? `<span style="color:#4a90d9;font-size:11px">${c.designation}</span><br>` : ''}
                    ${c.email  ? `📧 ${c.email}<br>`  : ''}
                    ${c.phone  ? `📞 ${c.phone}<br>`  : ''}
                    ${c.mobile ? `📱 ${c.mobile}<br>` : ''}
                    <span class="${c.portal_enabled ? 'cp-portal-ok' : 'cp-portal-no'}">
                        ${c.portal_enabled ? '✓ Portal enabled' : 'Portal not enabled'}
                    </span>
                </div>
            </div>`).join('')
        : '<div style="color:#aaa;font-size:13px;text-align:center;padding:20px">No contact persons</div>';

    const billing  = data.billing_address;
    const shipping = data.shipping_address;
    document.getElementById('cp-address-content').innerHTML = `
        ${billing  ? `<div class="addr-card"><div class="addr-card-title">📦 Billing Address</div><div class="addr-card-body">${formatAddressLines(billing)}</div></div>`  : ''}
        ${shipping ? `<div class="addr-card"><div class="addr-card-title">🚚 Shipping Address</div><div class="addr-card-body">${formatAddressLines(shipping)}</div></div>` : ''}
    `;

    const activities = data.activities ?? [];
    document.getElementById('cp-activity-list').innerHTML = activities.length
        ? activities.map(a => `
            <div class="activity-item">
                <div class="activity-dot">${(a.user||'U')[0].toUpperCase()}</div>
                <div class="activity-content">
                    <span class="act-user">${a.user ?? 'System'}</span>
                    <span class="act-time">• ${a.time ?? ''}</span>
                    <div class="act-text">${a.description ?? ''}</div>
                </div>
            </div>
            <div class="act-divider"></div>`).join('')
        : '<div style="color:#aaa;font-size:13px;text-align:center;padding:20px">No activity yet</div>';
}

// ══════════════════════════════════════════
// INIT
// ══════════════════════════════════════════
calcTotals();
</script>

</body>
</html>