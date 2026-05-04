<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoices</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
body { background: #f5f6f8; font-size: 13px; color: #333; }

.topbar { background: #1a2332; height: 48px; display: flex; align-items: center; padding: 0 16px; gap: 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
.topbar-logo { color: #fff; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; }
.topbar-right span { color: #9ba8b8; font-size: 12px; }
.topbar-right a { color: #4a90d9; font-size: 12px; text-decoration: none; }
.topbar-avatar { width: 30px; height: 30px; border-radius: 50%; background: #4a90d9; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 600; }

.layout { display: flex; margin-top: 48px; }

.sidenav { width: 200px; background: #1a2332; min-height: calc(100vh - 48px); position: fixed; top: 48px; left: 0; bottom: 0; overflow-y: auto; }
.snav-label { font-size: 10px; color: #5a6a7e; padding: 12px 14px 4px; text-transform: uppercase; letter-spacing: 0.6px; }
.snav-item { font-size: 13px; color: #9ba8b8; padding: 7px 14px 7px 16px; cursor: pointer; border-left: 3px solid transparent; display: block; text-decoration: none; transition: background 0.15s; }
.snav-item:hover { background: #243447; color: #cdd5df; }
.snav-item.active { color: #fff; background: #2d3f55; border-left: 3px solid #4a90d9; }

.main { margin-left: 200px; flex: 1; padding: 24px 28px; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.page-title { font-size: 18px; font-weight: 500; color: #1a1a2e; }
.btn-primary { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 9px 18px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-primary:hover { background: #3a7bc8; }

.alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

.card { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; overflow: hidden; }

.filters { display: flex; gap: 10px; padding: 14px 16px; border-bottom: 1px solid #f0f2f4; flex-wrap: wrap; }
.filters input, .filters select { height: 32px; border: 1px solid #d0d5dd; border-radius: 6px; padding: 0 10px; font-size: 13px; color: #333; outline: none; }
.filters input { width: 220px; }
.filters input:focus, .filters select:focus { border-color: #4a90d9; }

table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead tr { background: #f8f9fa; }
th { padding: 10px 14px; text-align: left; font-weight: 600; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 1px solid #e8eaed; }
td { padding: 11px 14px; border-bottom: 1px solid #f2f3f5; vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: #fafbfc; }

.badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: 500; }
.badge-draft    { background: #f1f5f9; color: #64748b; }
.badge-sent     { background: #dbeafe; color: #1e40af; }
.badge-paid     { background: #dcfce7; color: #166534; }
.badge-overdue  { background: #fef2f2; color: #991b1b; }

.action-links { display: flex; gap: 8px; align-items: center; }
.action-links a { color: #4a90d9; text-decoration: none; font-size: 12px; padding: 3px 8px; border-radius: 4px; border: 1px solid transparent; transition: all 0.15s; }
.action-links a:hover { background: #eef4ff; border-color: #4a90d9; }
.action-links a.edit { color: #f59e0b; }
.action-links a.edit:hover { background: #fffbeb; border-color: #f59e0b; }
.action-links a.del { color: #e05050; }
.action-links a.del:hover { background: #fef2f2; border-color: #fecaca; }

.empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
.empty-state .icon { font-size: 40px; margin-bottom: 12px; }
.empty-state p { margin-bottom: 16px; }

.pagination { display: flex; gap: 6px; padding: 14px 16px; justify-content: flex-end; border-top: 1px solid #f0f2f4; align-items: center; flex-wrap: wrap; }
.pagination a, .pagination span { padding: 5px 10px; border: 1px solid #d0d5dd; border-radius: 5px; font-size: 12px; color: #555; text-decoration: none; display: inline-block; line-height: 1.4; }
.pagination a:hover { background: #f0f4ff; border-color: #4a90d9; color: #4a90d9; }
.pagination span.active { background: #4a90d9; color: #fff; border-color: #4a90d9; }
.pagination span.disabled { color: #ccc; cursor: default; background: #fafafa; }

.summary-bar { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
.summary-card { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 14px 20px; flex: 1; min-width: 140px; }
.summary-card .label { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.summary-card .value { font-size: 20px; font-weight: 600; color: #1a1a2e; }
.summary-card .sub { font-size: 11px; color: #aaa; margin-top: 2px; }
</style>
</head>
<body>

<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 20 20" width="20" height="20" fill="#fff"><rect x="1" y="1" width="8" height="8" rx="1.5"/><rect x="11" y="1" width="8" height="8" rx="1.5"/><rect x="1" y="11" width="8" height="8" rx="1.5"/><rect x="11" y="11" width="8" height="8" rx="1.5"/></svg>
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
        <a href="{{ route('invoices.index') }}" class="snav-item active">Invoices</a>
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

        <div class="page-header">
            <div class="page-title">Invoices</div>
            <div style="display:flex;align-items:center;gap:10px;">

                {{-- Preferences Dropdown --}}
                <div style="position:relative;" id="prefDropdownWrap">
                    <button onclick="togglePrefDropdown()"
                            style="background:#fff;border:1px solid #d0d5dd;border-radius:6px;padding:8px 14px;font-size:13px;color:#444;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                        ⚙ Preferences
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div id="prefDropdown"
                         style="display:none;position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid #e3e6ea;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,0.1);min-width:200px;z-index:300;overflow:hidden;">
                        <div style="padding:8px 14px;font-size:10px;color:#999;text-transform:uppercase;letter-spacing:0.6px;border-bottom:1px solid #f0f2f4;">
                            Invoice Preferences
                        </div>
                        <a href="{{ route('invoice_setting.create') }}"
                           style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#333;text-decoration:none;font-size:13px;"
                           onmouseover="this.style.background='#f5f7ff'" onmouseout="this.style.background=''">
                            ⚙ General
                        </a>
                        <a href="{{ route('field_customization.create', ['from' => 'invoice']) }}"
                           style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:#333;text-decoration:none;font-size:13px;"
                           onmouseover="this.style.background='#f5f7ff'" onmouseout="this.style.background=''">
                            ✏ Field Customization
                        </a>
                    </div>
                </div>

                <a href="{{ route('invoices.create') }}" class="btn-primary">&#43; New Invoice</a>
            </div>
        </div>

        {{-- Summary Bar --}}
        <div class="summary-bar">
            <div class="summary-card">
                <div class="label">Total Invoices</div>
                <div class="value">{{ $invoices->total() }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Draft</div>
                <div class="value">{{ $invoices->getCollection()->where('status','Draft')->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Sent</div>
                <div class="value">{{ $invoices->getCollection()->where('status','Sent')->count() }}</div>
            </div>
            <div class="summary-card">
                <div class="label">Total Value</div>
                <div class="value">₹{{ number_format($invoices->getCollection()->sum('grand_total'), 2) }}</div>
                <div class="sub">This page</div>
            </div>
        </div>

        <div class="card">
            <div class="filters">
                <input type="text" placeholder="&#128269; Search invoice # or customer...">
                <select>
                    <option value="">All Status</option>
                    <option>Draft</option>
                    <option>Sent</option>
                    <option>Paid</option>
                    <option>Overdue</option>
                </select>
                <select>
                    <option value="">All Time</option>
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Year</option>
                </select>
            </div>

            @if($invoices->count())
            <table>
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th style="text-align:right">Amount (₹)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}"
                               style="color:#4a90d9;text-decoration:none;font-weight:500">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td>{{ $invoice->customer->display_name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                        <td style="text-align:right;font-weight:600">
                            ₹{{ number_format($invoice->grand_total, 2) }}
                        </td>
                        <td>
                            @php
                                $payStatus = $invoice->payment_status ?? 'unpaid';
                                if ($payStatus === 'paid') {
                                    $displayStatus = 'PAID';
                                    $statusColor   = '#166534';
                                    $statusBg      = '#dcfce7';
                                } elseif ($payStatus === 'partial') {
                                    $displayStatus = 'PARTIALLY PAID';
                                    $statusColor   = '#92400e';
                                    $statusBg      = '#fef3c7';
                                } else {
                                    $displayStatus = $invoice->status;
                                    $statusColor   = match($invoice->status) {
                                        'Draft'   => '#64748b',
                                        'Sent'    => '#1e40af',
                                        'Overdue' => '#991b1b',
                                        default   => '#64748b',
                                    };
                                    $statusBg = match($invoice->status) {
                                        'Draft'   => '#f1f5f9',
                                        'Sent'    => '#dbeafe',
                                        'Overdue' => '#fef2f2',
                                        default   => '#f1f5f9',
                                    };
                                }
                            @endphp
                            <span style="display:inline-block;padding:3px 10px;border-radius:10px;
                                         font-size:11px;font-weight:600;
                                         background:{{ $statusBg }};color:{{ $statusColor }};">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('invoices.show', $invoice->id) }}">View</a>
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="edit">✏ Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- ✅ FIXED PAGINATION --}}
            @if($invoices->hasPages())
            <div class="pagination">
                {{-- Previous Button --}}
                @if($invoices->onFirstPage())
                    <span class="disabled">‹ Prev</span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}">‹ Prev</a>
                @endif

                {{-- Page Numbers --}}
                @foreach($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                    @if($page == $invoices->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}">Next ›</a>
                @else
                    <span class="disabled">Next ›</span>
                @endif
            </div>
            @endif

            @else
            <div class="empty-state">
                <div class="icon">🧾</div>
                <p>No invoices yet.</p>
                <a href="{{ route('invoices.create') }}" class="btn-primary">&#43; Create First Invoice</a>
            </div>
            @endif
        </div>

    </main>
</div>

<script>
function togglePrefDropdown() {
    const d = document.getElementById('prefDropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('prefDropdownWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('prefDropdown').style.display = 'none';
    }
});
</script>
</body>
</html>