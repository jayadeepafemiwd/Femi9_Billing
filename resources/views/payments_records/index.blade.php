{{-- payments_records/index.blade.php --}}
{{-- Customer name click → Credit Modal (credit balance + pending invoices + apply from credit) --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Payments Received</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; background: #f5f6fa; color: #333; }

.topbar { height: 48px; background: #1a2332; border-bottom: 1px solid #243447; display: flex; align-items: center; padding: 0 16px; gap: 12px; position: sticky; top: 0; z-index: 100; }
.topbar .app-logo { font-weight: 700; font-size: 15px; color: #fff; display: flex; align-items: center; gap: 6px; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; font-size: 12px; color: #9ba8b8; }
.topbar-right .avatar { width: 30px; height: 30px; background: #4a90d9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 12px; }

.layout { display: flex; min-height: calc(100vh - 48px); }
.sidebar { width: 200px; background: #1a2332; color: #9ba8b8; flex-shrink: 0; padding: 8px 0; position: sticky; top: 48px; height: calc(100vh - 48px); overflow-y: auto; }
.sidebar .nav-item { padding: 7px 14px 7px 16px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; cursor: pointer; transition: background .15s; border-left: 3px solid transparent; }
.sidebar .nav-item:hover { background: #243447; color: #cdd5df; }
.sidebar .nav-item.active { background: #2d3f55; color: #fff; border-left-color: #4a90d9; }
.sidebar .section-title { padding: 12px 14px 4px; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #5a6a7e; }

.content { flex: 1; padding: 20px 24px 40px; }

/* ── Top bar ── */
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.page-title { font-size: 18px; font-weight: 500; color: #1a1a2e; }
.btn-primary { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.btn-primary:hover { background: #3a7bc8; }

/* ── Stats ── */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
.stat-card { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 14px 18px; }
.stat-card .sv { font-size: 22px; font-weight: 600; color: #1a2332; }
.stat-card .sl { font-size: 11px; color: #888; margin-top: 2px; }
.stat-card.green .sv { color: #27ae60; }
.stat-card.blue  .sv { color: #4a90d9; }
.stat-card.amber .sv { color: #d97706; }

/* ── Filters ── */
.filters { display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
.filters input, .filters select { height: 34px; border: 1px solid #d0d5dd; border-radius: 6px; padding: 0 10px; font-size: 13px; color: #333; background: #fff; outline: none; }
.filters input { min-width: 220px; }
.filters input:focus, .filters select:focus { border-color: #4a90d9; box-shadow: 0 0 0 3px rgba(74,144,217,.12); }
.btn-filter { background: #fff; border: 1px solid #d0d5dd; border-radius: 6px; padding: 0 14px; height: 34px; font-size: 13px; cursor: pointer; }
.btn-filter:hover { background: #f5f6f8; }

/* ── Table ── */
.table-wrap { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
thead th { background: #f5f6fa; padding: 9px 14px; font-size: 11px; font-weight: 600; color: #777; text-transform: uppercase; letter-spacing: .3px; text-align: left; border-bottom: 1px solid #e5e5e5; white-space: nowrap; }
tbody td { padding: 11px 14px; font-size: 12.5px; color: #333; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafbff; }

/* Customer name — clickable link */
.cust-link { color: #4a90d9; font-weight: 500; cursor: pointer; background: none; border: none; padding: 0; font-size: 12.5px; text-align: left; }
.cust-link:hover { text-decoration: underline; }

/* Credit badge inline */
.credit-chip { display: inline-flex; align-items: center; gap: 4px; background: #ecfdf5; border: 0.5px solid #a7f3d0; color: #166534; border-radius: 20px; padding: 2px 8px; font-size: 10.5px; font-weight: 500; margin-left: 6px; cursor: pointer; }
.credit-chip:hover { background: #d1fae5; }

.pay-num { color: #4a90d9; font-weight: 500; }
.badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: 500; }
.badge.paid  { background: #ecfdf5; color: #27ae60; border: 0.5px solid #a7f3d0; }
.badge.draft { background: #fef3c7; color: #d97706; border: 0.5px solid #fcd34d; }
.badge.advance { background: #eff6ff; color: #2563eb; border: 0.5px solid #bfdbfe; }

/* ── Pagination ── */
.pagination { padding: 12px 16px; display: flex; align-items: center; gap: 6px; border-top: 1px solid #f0f0f0; }
.pagination a, .pagination span { padding: 4px 10px; border: 1px solid #e3e6ea; border-radius: 4px; font-size: 12px; color: #555; text-decoration: none; }
.pagination span.active { background: #4a90d9; color: #fff; border-color: #4a90d9; }

/* ══════════════════════════════════════════
   CUSTOMER CREDIT MODAL
   ══════════════════════════════════════════ */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(15,23,42,0.55); backdrop-filter: blur(3px);
  z-index: 1000; align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }

.cred-modal {
  background: #fff; border-radius: 12px; width: 680px; max-width: 95vw;
  max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,.2);
  animation: slideUp .25s ease;
}
@keyframes slideUp { from { transform: translateY(20px); opacity:0; } to { transform: translateY(0); opacity:1; } }

/* Modal header */
.cred-modal-header {
  background: linear-gradient(135deg, #1a2332 0%, #2d3f55 100%);
  padding: 18px 22px; display: flex; align-items: flex-start; gap: 14px;
}
.cred-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  background: #4a90d9; display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 18px; font-weight: 700; flex-shrink: 0;
}
.cred-header-info { flex: 1; }
.cred-cust-name { font-size: 16px; font-weight: 600; color: #fff; }
.cred-cust-meta { font-size: 11px; color: #9ba8b8; margin-top: 3px; }
.cred-close {
  background: rgba(255,255,255,.1); border: none; color: #fff;
  width: 28px; height: 28px; border-radius: 6px; cursor: pointer;
  font-size: 16px; display: flex; align-items: center; justify-content: center;
}
.cred-close:hover { background: rgba(255,255,255,.2); }

/* Credit summary strip */
.cred-summary-strip {
  display: grid; grid-template-columns: repeat(3,1fr);
  border-bottom: 1px solid #e3e6ea; background: #f8faff;
}
.cred-stat { padding: 14px 18px; text-align: center; border-right: 1px solid #e3e6ea; }
.cred-stat:last-child { border-right: none; }
.cred-stat .csv { font-size: 20px; font-weight: 700; color: #1a2332; }
.cred-stat .csv.green { color: #27ae60; }
.cred-stat .csv.red   { color: #e05050; }
.cred-stat .csl { font-size: 11px; color: #888; margin-top: 2px; }

/* Modal body scroll */
.cred-modal-body { flex: 1; overflow-y: auto; padding: 18px 22px; }

/* Section label */
.sec-label { font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 10px; }

/* Pending invoices table inside modal */
.mini-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
.mini-table thead th { background: #f5f6fa; padding: 7px 10px; font-size: 11px; font-weight: 600; color: #777; text-transform: uppercase; letter-spacing: .3px; text-align: left; border-bottom: 1px solid #e5e5e5; }
.mini-table tbody td { padding: 9px 10px; font-size: 12px; color: #333; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.mini-table tbody tr:last-child td { border-bottom: none; }
.mini-table tbody tr:hover td { background: #fafbff; }
.inv-link { color: #4a90d9; font-weight: 500; }
.amt-due { font-weight: 600; color: #e05050; }
.use-credit-btn {
  background: #ecfdf5; border: 1px solid #a7f3d0; color: #166534;
  border-radius: 5px; padding: 4px 10px; font-size: 11.5px; cursor: pointer; white-space: nowrap;
}
.use-credit-btn:hover { background: #d1fae5; }
.use-credit-btn:disabled { background: #f0f0f0; color: #aaa; border-color: #ddd; cursor: not-allowed; }

/* Apply credit inline section */
.apply-credit-box {
  background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 8px;
  padding: 14px 16px; margin-top: 4px; display: none;
}
.apply-credit-box.open { display: block; }
.apply-credit-box .acb-title { font-size: 13px; font-weight: 600; color: #166534; margin-bottom: 8px; }
.apply-credit-box .acb-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.apply-credit-box input {
  height: 32px; border: 1px solid #86efac; border-radius: 5px;
  padding: 0 10px; font-size: 13px; outline: none; width: 140px;
}
.apply-credit-box input:focus { border-color: #27ae60; box-shadow: 0 0 0 3px rgba(39,174,96,.12); }
.acb-max { font-size: 11px; color: #15803d; }
.btn-apply { background: #27ae60; color: #fff; border: none; border-radius: 5px; padding: 6px 16px; font-size: 12.5px; cursor: pointer; }
.btn-apply:hover { background: #219d55; }
.btn-cancel-apply { background: none; border: none; color: #888; font-size: 12px; cursor: pointer; }

/* Empty state */
.empty-mini { text-align: center; padding: 24px; color: #aaa; font-size: 12px; }

/* Modal footer */
.cred-modal-footer { border-top: 1px solid #e3e6ea; padding: 12px 22px; display: flex; justify-content: flex-end; gap: 10px; }
.btn-secondary { background: #fff; border: 1px solid #d0d5dd; border-radius: 6px; padding: 7px 18px; font-size: 13px; cursor: pointer; color: #333; }
.btn-secondary:hover { background: #f5f6f8; }

/* Toast */
.toast { position: fixed; bottom: 24px; right: 24px; background: #2d3748; color: #fff; padding: 12px 20px; border-radius: 6px; font-size: 13px; opacity: 0; transform: translateY(10px); transition: all .3s; z-index: 9999; pointer-events: none; }
.toast.show { opacity: 1; transform: translateY(0); }
.toast.success { background: #276749; }
.toast.error   { background: #9b2c2c; }

.spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid #ccc; border-top-color: #4a90d9; border-radius: 50%; animation: spin .6s linear infinite; margin-right: 4px; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>

<div class="topbar">
  <div class="app-logo">
    <svg width="20" height="20" viewBox="0 0 20 20"><rect x="1" y="1" width="8" height="8" rx="1.5" fill="#fff"/><rect x="11" y="1" width="8" height="8" rx="1.5" fill="#fff"/><rect x="1" y="11" width="8" height="8" rx="1.5" fill="#fff"/><rect x="11" y="11" width="8" height="8" rx="1.5" fill="#fff"/></svg>
    Inventory
  </div>
  <div class="topbar-right">
    <span>Jayadeepa</span>
    <div class="avatar">J</div>
  </div>
</div>

<div class="layout">

  <nav class="sidebar">
    <div class="nav-item">Sales Orders</div>
    <div class="nav-item">Invoices</div>
    <div class="nav-item">Delivery Challans</div>
    <div class="nav-item active">Payments Received</div>
    <div class="nav-item">Sales Returns</div>
    <div class="nav-item">Credit Notes</div>
    <div class="section-title">Purchases</div>
    <div class="section-title">Reports</div>
  </nav>

  <main class="content">

    <div class="page-header">
      <div class="page-title">💰 Payments Received</div>
      <a href="{{ route('payments_records.create') }}" class="btn-primary">+ New Payment</a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="sv">{{ $stats['total_count'] }}</div>
        <div class="sl">Total Payments</div>
      </div>
      <div class="stat-card green">
        <div class="sv">₹{{ number_format($stats['total_amount'], 2) }}</div>
        <div class="sl">Total Received</div>
      </div>
      <div class="stat-card blue">
        <div class="sv">₹{{ number_format($stats['this_month'], 2) }}</div>
        <div class="sl">This Month</div>
      </div>
      <div class="stat-card amber">
        <div class="sv">{{ $stats['draft_count'] }}</div>
        <div class="sl">Drafts</div>
      </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('payments_records.index') }}">
      <div class="filters">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search customer, payment #...">
        <select name="status">
          <option value="">All Status</option>
          <option value="paid"  {{ request('status')=='paid'  ? 'selected' : '' }}>Paid</option>
          <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
        </select>
        <select name="mode">
          <option value="">All Modes</option>
          <option value="cash"            {{ request('mode')=='cash'            ? 'selected' : '' }}>Cash</option>
          <option value="bank_transfer"   {{ request('mode')=='bank_transfer'   ? 'selected' : '' }}>Bank Transfer</option>
          <option value="cheque"          {{ request('mode')=='cheque'          ? 'selected' : '' }}>Cheque</option>
          <option value="upi"             {{ request('mode')=='upi'             ? 'selected' : '' }}>UPI</option>
          <option value="advance_payment" {{ request('mode')=='advance_payment' ? 'selected' : '' }}>Advance</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" title="From date">
        <input type="date" name="date_to"   value="{{ request('date_to') }}"   title="To date">
        <button type="submit" class="btn-filter">Filter</button>
        <a href="{{ route('payments_records.index') }}" class="btn-filter" style="text-decoration:none;display:flex;align-items:center">Reset</a>
      </div>
    </form>

    <!-- Table -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Payment #</th>
            <th>Customer</th>
            <th>Mode</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $p)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
            <td><span class="pay-num">{{ $p->payment_no }}</span></td>
            <td>
              {{-- Customer name → click → open credit modal --}}
              <button class="cust-link"
                onclick="openCreditModal({{ $p->customer_id }}, '{{ addslashes($p->customer_name) }}', '{{ $p->customer_code }}')">
                {{ $p->customer_name }}
              </button>
              {{-- Show credit chip only if customer has unused credit --}}
              {{-- We'll fetch live via JS; placeholder chip shown always, JS will hide if 0 --}}
              <span class="credit-chip"
                id="chip-{{ $p->customer_id }}"
                onclick="openCreditModal({{ $p->customer_id }}, '{{ addslashes($p->customer_name) }}', '{{ $p->customer_code }}')"
                style="display:none">
                ⭐ Credit
              </span>
            </td>
            <td>
              @php
                $modeLabels = [
                  'cash'=>'Cash','bank_transfer'=>'Bank Transfer',
                  'cheque'=>'Cheque','upi'=>'UPI','card'=>'Card',
                  'advance_payment'=>'Advance'
                ];
              @endphp
              {{ $modeLabels[$p->payment_mode] ?? ucfirst($p->payment_mode) }}
            </td>
            <td>₹{{ number_format($p->amount_received, 2) }}</td>
            <td>
              @if($p->payment_mode === 'advance_payment')
                <span class="badge advance">⭐ Advance</span>
              @elseif($p->status === 'paid')
                <span class="badge paid">Paid</span>
              @else
                <span class="badge draft">Draft</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:#aaa">No payments found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
@if($payments->hasPages())
<div class="pagination" style="padding:12px 16px;display:flex;align-items:center;gap:6px;border-top:1px solid #f0f0f0;flex-wrap:wrap">
  {{-- Previous --}}
  @if($payments->onFirstPage())
    <span style="padding:4px 10px;border:1px solid #e3e6ea;border-radius:4px;font-size:12px;color:#ccc">« Prev</span>
  @else
    <a href="{{ $payments->previousPageUrl() }}" style="padding:4px 10px;border:1px solid #e3e6ea;border-radius:4px;font-size:12px;color:#555;text-decoration:none">« Prev</a>
  @endif

  {{-- Page numbers --}}
  @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
    @if($page == $payments->currentPage())
      <span style="padding:4px 10px;border:1px solid #4a90d9;border-radius:4px;font-size:12px;background:#4a90d9;color:#fff">{{ $page }}</span>
    @else
      <a href="{{ $url }}" style="padding:4px 10px;border:1px solid #e3e6ea;border-radius:4px;font-size:12px;color:#555;text-decoration:none">{{ $page }}</a>
    @endif
  @endforeach

  {{-- Next --}}
  @if($payments->hasMorePages())
    <a href="{{ $payments->nextPageUrl() }}" style="padding:4px 10px;border:1px solid #e3e6ea;border-radius:4px;font-size:12px;color:#555;text-decoration:none">Next »</a>
  @else
    <span style="padding:4px 10px;border:1px solid #e3e6ea;border-radius:4px;font-size:12px;color:#ccc">Next »</span>
  @endif

  <span style="margin-left:8px;font-size:12px;color:#888">
    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
  </span>
</div>
@endif
    </div>

  </main>
</div>

<!-- ══════════════════════════════════════════════════
     CUSTOMER CREDIT MODAL
     ══════════════════════════════════════════════════ -->
<div class="modal-overlay" id="creditModal" onclick="closeCreditModal(event)">
  <div class="cred-modal" onclick="event.stopPropagation()">

    <!-- Header -->
    <div class="cred-modal-header">
      <div class="cred-avatar" id="credAvatar">?</div>
      <div class="cred-header-info">
        <div class="cred-cust-name" id="credCustName">—</div>
        <div class="cred-cust-meta" id="credCustMeta">—</div>
      </div>
      <button class="cred-close" onclick="document.getElementById('creditModal').classList.remove('open')">✕</button>
    </div>

    <!-- Credit summary strip -->
    <div class="cred-summary-strip">
      <div class="cred-stat">
        <div class="csv green" id="credBalance">—</div>
        <div class="csl">Available Credit</div>
      </div>
      <div class="cred-stat">
        <div class="csv red" id="credPendingAmt">—</div>
        <div class="csl">Total Amount Due</div>
      </div>
      <div class="cred-stat">
        <div class="csv" id="credInvCount">—</div>
        <div class="csl">Unpaid Invoices</div>
      </div>
    </div>

    <!-- Body -->
    <div class="cred-modal-body" id="credModalBody">
      <div style="text-align:center;padding:40px;color:#aaa">
        <span class="spinner"></span> Loading...
      </div>
    </div>

    <!-- Footer -->
    <div class="cred-modal-footer">
      <button class="btn-secondary" onclick="document.getElementById('creditModal').classList.remove('open')">Close</button>
    </div>

  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Open Credit Modal ──
async function openCreditModal(customerId, name, code) {
  // Reset
  document.getElementById('credAvatar').textContent = (name[0] || '?').toUpperCase();
  document.getElementById('credCustName').textContent = name;
  document.getElementById('credCustMeta').textContent = code || '';
  document.getElementById('credBalance').textContent    = '—';
  document.getElementById('credPendingAmt').textContent = '—';
  document.getElementById('credInvCount').textContent   = '—';
  document.getElementById('credModalBody').innerHTML =
    '<div style="text-align:center;padding:40px;color:#aaa"><span class="spinner"></span> Loading...</div>';

  document.getElementById('creditModal').classList.add('open');

  try {
    const res  = await fetch(`/payments_records/customer-credit?customer_id=${customerId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();

    const credit   = parseFloat(data.unused_credits || 0);
    const invoices = data.invoices || [];
    const totalDue = invoices.reduce((s, i) => s + parseFloat(i.amount_due || 0), 0);

    // Summary strip
    document.getElementById('credBalance').textContent    = '₹' + credit.toFixed(2);
    document.getElementById('credPendingAmt').textContent = '₹' + totalDue.toFixed(2);
    document.getElementById('credInvCount').textContent   = invoices.length;

    // Show credit chip in table row
    const chip = document.getElementById('chip-' + customerId);
    if (chip && credit > 0) chip.style.display = 'inline-flex';

    // Body
    renderCreditModalBody(customerId, credit, invoices);

  } catch(e) {
    console.error(e);
    document.getElementById('credModalBody').innerHTML =
      '<div class="empty-mini">Failed to load data. Please try again.</div>';
  }
}

function renderCreditModalBody(customerId, credit, invoices) {
  const body = document.getElementById('credModalBody');

  if (!invoices.length && credit <= 0) {
    body.innerHTML = '<div class="empty-mini">No pending invoices and no unused credit for this customer.</div>';
    return;
  }

  let html = '';

  // Pending invoices section
  html += `<div class="sec-label">📄 Pending Invoices</div>`;

  if (!invoices.length) {
    html += '<div class="empty-mini" style="padding:16px 0">No unpaid invoices ✅</div>';
  } else {
    html += `
      <table class="mini-table">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Date</th>
            <th>Total</th>
            <th>Amount Due</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>`;

    invoices.forEach(inv => {
      const canApply = credit > 0;
      html += `
        <tr id="inv-row-${inv.id}">
          <td><span class="inv-link">${inv.invoice_number}</span></td>
          <td>${fmtDate(inv.invoice_date)}</td>
          <td>₹${parseFloat(inv.total_amount||0).toFixed(2)}</td>
          <td><span class="amt-due">₹${parseFloat(inv.amount_due||0).toFixed(2)}</span></td>
          <td>
            ${canApply
              ? `<button class="use-credit-btn" onclick="toggleApplyBox(${inv.id}, ${inv.amount_due}, ${credit}, ${customerId})">
                   ⭐ Use Credit
                 </button>`
              : `<button class="use-credit-btn" disabled title="No credit available">No Credit</button>`
            }
          </td>
        </tr>
        <tr id="apply-row-${inv.id}" style="display:none">
          <td colspan="5" style="padding:0 10px 10px">
            <div class="apply-credit-box" id="apply-box-${inv.id}">
              <div class="acb-title">⭐ Apply Credit to Invoice ${inv.invoice_number}</div>
              <div class="acb-row">
                <div>
                  <input type="number" id="credit-input-${inv.id}"
                    min="0.01" step="0.01"
                    placeholder="Amount to apply"
                    value="${Math.min(credit, parseFloat(inv.amount_due)).toFixed(2)}">
                  <div class="acb-max" style="margin-top:4px">
                    Max applicable: ₹${Math.min(credit, parseFloat(inv.amount_due)).toFixed(2)}
                    &nbsp;|&nbsp; Available credit: ₹${credit.toFixed(2)}
                  </div>
                </div>
                <button class="btn-apply" onclick="applyCredit(${customerId}, ${inv.id}, '${inv.invoice_number}', ${credit}, ${inv.amount_due})">
                  Apply
                </button>
                <button class="btn-cancel-apply" onclick="cancelApplyBox(${inv.id})">Cancel</button>
              </div>
            </div>
          </td>
        </tr>`;
    });

    html += `</tbody></table>`;
  }

  // Credit history hint
  if (credit > 0) {
    html += `
      <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:12px 14px;margin-top:4px">
        <div style="font-size:13px;font-weight:600;color:#92400e;margin-bottom:4px">⭐ Available Credit: ₹${credit.toFixed(2)}</div>
        <div style="font-size:12px;color:#78350f;line-height:1.6">
          இந்த credit advance payment மூலம் சேர்க்கப்பட்டது.<br>
          Invoice-க்கு apply பண்ண மேலே "Use Credit" button click பண்ணுங்க.
        </div>
      </div>`;
  }

  body.innerHTML = html;
}

function toggleApplyBox(invId, amountDue, credit, customerId) {
  const row = document.getElementById('apply-row-' + invId);
  const box = document.getElementById('apply-box-' + invId);
  if (row.style.display === 'none') {
    row.style.display = '';
    box.classList.add('open');
  } else {
    row.style.display = 'none';
    box.classList.remove('open');
  }
}

function cancelApplyBox(invId) {
  document.getElementById('apply-row-' + invId).style.display = 'none';
  document.getElementById('apply-box-'  + invId).classList.remove('open');
}

// ── Apply Credit to Invoice ──
async function applyCredit(customerId, invoiceId, invoiceNo, availableCredit, amountDue) {
  const input = document.getElementById('credit-input-' + invoiceId);
  const amt   = parseFloat(input.value) || 0;

  if (amt <= 0) { showToast('Valid amount enter பண்ணுங்க', 'error'); return; }
  if (amt > availableCredit) { showToast(`Available credit ₹${availableCredit.toFixed(2)} மட்டுமே`, 'error'); return; }
  if (amt > parseFloat(amountDue)) { showToast(`Invoice due ₹${parseFloat(amountDue).toFixed(2)} மட்டுமே apply ஆகும்`, 'error'); return; }

  if (!confirm(`₹${amt.toFixed(2)} credit-ஐ Invoice ${invoiceNo}-க்கு apply பண்ணலாமா?`)) return;

  try {
    const res = await fetch('/payments_records/apply-credit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ customer_id: customerId, invoice_id: invoiceId, amount: amt }),
    });
    const data = await res.json();

    if (data.success) {
      showToast(`₹${amt.toFixed(2)} Invoice ${invoiceNo}-க்கு apply ஆனது! ✅`, 'success');
      // Reload modal with fresh data
      setTimeout(() => {
        const name = document.getElementById('credCustName').textContent;
        const code = document.getElementById('credCustMeta').textContent;
        openCreditModal(customerId, name, code);
      }, 800);
    } else {
      showToast(data.message || 'Error occurred', 'error');
    }
  } catch(e) {
    showToast('Server error. Try again.', 'error');
  }
}

function closeCreditModal(e) {
  if (e.target === document.getElementById('creditModal')) {
    document.getElementById('creditModal').classList.remove('open');
  }
}

function fmtDate(d) {
  if (!d) return '—';
  return new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = `toast show ${type}`;
  setTimeout(() => t.className = 'toast', 3500);
}
</script>
</body>
</html>