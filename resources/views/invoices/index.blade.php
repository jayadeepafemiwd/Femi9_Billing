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

.action-links { display: flex; gap: 10px; }
.action-links a { color: #4a90d9; text-decoration: none; font-size: 12px; }
.action-links a:hover { text-decoration: underline; }
.action-links a.del { color: #e05050; }

.empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
.empty-state .icon { font-size: 40px; margin-bottom: 12px; }
.empty-state p { margin-bottom: 16px; }

.pagination { display: flex; gap: 6px; padding: 14px 16px; justify-content: flex-end; border-top: 1px solid #f0f2f4; }
.pagination a, .pagination span { padding: 5px 10px; border: 1px solid #d0d5dd; border-radius: 5px; font-size: 12px; color: #555; text-decoration: none; }
.pagination a:hover { background: #f0f4ff; border-color: #4a90d9; color: #4a90d9; }
.pagination span.active { background: #4a90d9; color: #fff; border-color: #4a90d9; }

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
            <a href="{{ route('invoices.create') }}" class="btn-primary">&#43; New Invoice</a>
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
                                $badgeClass = match($invoice->status) {
                                    'Draft'  => 'badge-draft',
                                    'Sent'   => 'badge-sent',
                                    'Paid'   => 'badge-paid',
                                    'Overdue'=> 'badge-overdue',
                                    default  => 'badge-draft',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $invoice->status }}</span>
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('invoices.show', $invoice->id) }}">View</a>
                                {{-- <a href="{{ route('invoices.edit', $invoice->id) }}">Edit</a> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($invoices->hasPages())
            <div class="pagination">
                {{ $invoices->links() }}
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

</body>
</html>