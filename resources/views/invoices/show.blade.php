<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice {{ $invoice->invoice_number }}</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
body { background: #f5f6f8; font-size: 13px; color: #333; }

.topbar { background: #1a2332; height: 48px; display: flex; align-items: center; padding: 0 16px; gap: 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
.topbar-logo { color: #fff; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; }
.topbar-right a { color: #4a90d9; font-size: 12px; text-decoration: none; }
.topbar-avatar { width: 30px; height: 30px; border-radius: 50%; background: #4a90d9; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: 600; }

.layout { display: flex; margin-top: 48px; }

.sidenav { width: 200px; background: #1a2332; min-height: calc(100vh - 48px); position: fixed; top: 48px; left: 0; bottom: 0; overflow-y: auto; }
.snav-label { font-size: 10px; color: #5a6a7e; padding: 12px 14px 4px; text-transform: uppercase; letter-spacing: 0.6px; }
.snav-item { font-size: 13px; color: #9ba8b8; padding: 7px 14px 7px 16px; cursor: pointer; border-left: 3px solid transparent; display: block; text-decoration: none; transition: background 0.15s; }
.snav-item:hover { background: #243447; color: #cdd5df; }
.snav-item.active { color: #fff; background: #2d3f55; border-left: 3px solid #4a90d9; }

.main { margin-left: 200px; flex: 1; padding: 24px 28px 60px; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
.page-header-left { display: flex; align-items: center; gap: 12px; }
.page-title { font-size: 18px; font-weight: 500; color: #1a1a2e; }
.page-header-actions { display: flex; gap: 10px; }

.btn-primary { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-primary:hover { background: #3a7bc8; }
.btn-outline { background: #fff; color: #333; border: 1px solid #d0d5dd; border-radius: 6px; padding: 8px 16px; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-outline:hover { background: #f5f6f8; }
.btn-back { background: none; border: none; color: #4a90d9; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; padding: 0; }

.badge { display: inline-block; padding: 4px 12px; border-radius: 10px; font-size: 12px; font-weight: 500; }
.badge-draft   { background: #f1f5f9; color: #64748b; }
.badge-sent    { background: #dbeafe; color: #1e40af; }
.badge-paid    { background: #dcfce7; color: #166534; }
.badge-overdue { background: #fef2f2; color: #991b1b; }

.alert { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }

/* Invoice Paper */
.invoice-paper { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 40px 48px; max-width: 860px; margin: 0 auto; }

.inv-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
.inv-brand { font-size: 22px; font-weight: 700; color: #1a2332; }
.inv-brand-sub { font-size: 12px; color: #999; margin-top: 4px; }
.inv-title-block { text-align: right; }
.inv-title-block h1 { font-size: 26px; font-weight: 700; color: #1a2332; letter-spacing: 1px; }
.inv-number { font-size: 13px; color: #666; margin-top: 4px; }

.inv-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; padding: 20px 0; border-top: 1px solid #f0f2f4; border-bottom: 1px solid #f0f2f4; }
.meta-block-title { font-size: 10px; font-weight: 700; color: #4a90d9; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
.meta-row { display: flex; gap: 8px; margin-bottom: 5px; font-size: 13px; }
.meta-label { color: #999; min-width: 90px; }
.meta-value { color: #333; font-weight: 500; }

.addr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
.addr-block { padding: 14px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; }
.addr-title { font-size: 10px; font-weight: 700; color: #4a90d9; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
.addr-body { font-size: 12px; color: #555; line-height: 1.9; }

/* Items Table */
.items-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 24px; }
.items-table thead tr { background: #1a2332; }
.items-table th { padding: 10px 14px; text-align: left; font-weight: 500; font-size: 12px; color: #fff; }
.items-table th.r { text-align: right; }
.items-table td { padding: 10px 14px; border-bottom: 1px solid #f2f3f5; vertical-align: middle; }
.items-table td.r { text-align: right; }
.items-table tbody tr:last-child td { border-bottom: none; }
.items-table tfoot tr { background: #f8f9fa; }
.items-table tfoot td { padding: 8px 14px; font-size: 13px; color: #555; }
.items-table tfoot td.r { text-align: right; }

/* Totals */
.totals-section { display: flex; justify-content: flex-end; margin-bottom: 28px; }
.totals-box { width: 320px; }
.tot-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 13px; color: #555; border-bottom: 1px solid #f0f2f4; }
.tot-row.grand { font-size: 15px; font-weight: 700; color: #1a1a2e; border-bottom: none; padding-top: 10px; }

/* Notes */
.notes-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f0f2f4; }
.notes-block-title { font-size: 10px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
.notes-body { font-size: 12px; color: #666; line-height: 1.7; }

@media print {
    .topbar, .sidenav, .page-header, .no-print { display: none !important; }
    .main { margin-left: 0; padding: 0; }
    .invoice-paper { border: none; box-shadow: none; padding: 20px; }
    body { background: #fff; }
}
</style>
</head>
<body>

<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 20 20" width="20" height="20" fill="#fff"><rect x="1" y="1" width="8" height="8" rx="1.5"/><rect x="11" y="1" width="8" height="8" rx="1.5"/><rect x="1" y="11" width="8" height="8" rx="1.5"/><rect x="11" y="11" width="8" height="8" rx="1.5"/></svg>
        Inventory
    </div>
    <div class="topbar-right">
        <a href="#">Subscribe</a>
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

        <div class="page-header no-print">
            <div class="page-header-left">
                <a href="{{ route('invoices.index') }}" class="btn-back">&#8592; Back</a>
                <div class="page-title">{{ $invoice->invoice_number }}</div>
                @php
                    $badgeClass = match($invoice->status) {
                        'Draft'   => 'badge-draft',
                        'Sent'    => 'badge-sent',
                        'Paid'    => 'badge-paid',
                        'Overdue' => 'badge-overdue',
                        default   => 'badge-draft',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $invoice->status }}</span>
            </div>
            <div class="page-header-actions">
                <button class="btn-outline" onclick="window.print()">🖨 Print</button>
                <a href="{{ route('invoices.create') }}" class="btn-primary">&#43; New Invoice</a>
            </div>
        </div>

        <div class="invoice-paper">

            {{-- Top Header --}}
            <div class="inv-header">
                <div>
                    <div class="inv-brand">{{ config('app.name', 'Your Company') }}</div>
                    <div class="inv-brand-sub">{{ $locationName ?? '' }}</div>
                </div>
                <div class="inv-title-block">
                    <h1>INVOICE</h1>
                    <div class="inv-number"># {{ $invoice->invoice_number }}</div>
                    @php
                        $badgeClass2 = match($invoice->status) {
                            'Draft'   => 'badge-draft',
                            'Sent'    => 'badge-sent',
                            'Paid'    => 'badge-paid',
                            'Overdue' => 'badge-overdue',
                            default   => 'badge-draft',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass2 }}" style="margin-top:6px;display:inline-block">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="inv-meta">
                <div>
                    <div class="meta-block-title">Invoice Details</div>
                    <div class="meta-row">
                        <span class="meta-label">Invoice Date</span>
                        <span class="meta-value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Due Date</span>
                        <span class="meta-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Terms</span>
                        <span class="meta-value">{{ $invoice->terms }}</span>
                    </div>
                    @if($invoice->order_number)
                    <div class="meta-row">
                        <span class="meta-label">Order #</span>
                        <span class="meta-value">{{ $invoice->order_number }}</span>
                    </div>
                    @endif
                </div>
                <div>
                    <div class="meta-block-title">Bill To</div>
                    <div style="font-size:14px;font-weight:600;color:#1a1a2e;margin-bottom:4px">
                        {{ $invoice->customer->display_name ?? '—' }}
                    </div>
                    @if($invoice->customer->email ?? false)
                    <div style="font-size:12px;color:#666">{{ $invoice->customer->email }}</div>
                    @endif
                    @if($invoice->customer->phone ?? false)
                    <div style="font-size:12px;color:#666">{{ $invoice->customer->phone }}</div>
                    @endif
                </div>
            </div>

            {{-- Subject --}}
            @if($invoice->subject)
            <div style="margin-bottom:20px;font-size:13px;color:#555;font-style:italic">
                {{ $invoice->subject }}
            </div>
            @endif

            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Item & Description</th>
                        <th style="width:80px">SKU</th>
                        <th class="r" style="width:80px">Qty</th>
                        <th class="r" style="width:110px">Rate (₹)</th>
                        <th class="r" style="width:120px">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $i => $item)
                    <tr>
                        <td style="color:#999">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight:500">{{ $item->item_name }}</div>
                            @if($item->product)
                            <div style="font-size:11px;color:#aaa">{{ $item->product->name }}</div>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#888">{{ $item->product->sku ?? '—' }}</td>
                        <td class="r">{{ number_format($item->quantity, 2) }}</td>
                        <td class="r">{{ number_format($item->rate, 2) }}</td>
                        <td class="r" style="font-weight:600">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-section">
                <div class="totals-box">
                    <div class="tot-row">
                        <span>Sub Total</span>
                        <span>₹{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount_amount > 0)
                    <div class="tot-row">
                        <span>Discount ({{ $invoice->discount_percent }}%)</span>
                        <span>- ₹{{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <div class="tot-row">
                        <span>{{ $invoice->tax_type }} ({{ $invoice->tax_percent }}%)</span>
                        <span>₹{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->adjustment != 0)
                    <div class="tot-row">
                        <span>Adjustment</span>
                        <span>₹{{ number_format($invoice->adjustment, 2) }}</span>
                    </div>
                    @endif
                    <div class="tot-row grand">
                        <span>Total (₹)</span>
                        <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes & Terms --}}
            @if($invoice->customer_notes || $invoice->terms_conditions)
            <div class="notes-grid">
                @if($invoice->customer_notes)
                <div>
                    <div class="notes-block-title">Customer Notes</div>
                    <div class="notes-body">{{ $invoice->customer_notes }}</div>
                </div>
                @endif
                @if($invoice->terms_conditions)
                <div>
                    <div class="notes-block-title">Terms & Conditions</div>
                    <div class="notes-body">{{ $invoice->terms_conditions }}</div>
                </div>
                @endif
            </div>
            @endif

        </div>{{-- end invoice-paper --}}

    </main>
</div>

</body>
</html>