<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Add New | Payments Received</title>
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

  .content { flex: 1; padding: 20px 24px 80px; }
  .page-title { font-size: 18px; font-weight: 500; color: #1a1a2e; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
  .card { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 20px 24px; margin-bottom: 16px; }

  .frow { display: grid; grid-template-columns: 160px 1fr; align-items: start; gap: 8px; margin-bottom: 14px; }
  .frow label { padding-top: 8px; font-size: 13px; color: #555; }
  .frow label.req::after { content: " *"; color: #e05050; }

  input[type=text], input[type=date], input[type=number], select, textarea {
    width: 100%; height: 34px; border: 1px solid #d0d5dd; border-radius: 6px;
    padding: 0 10px; font-size: 13px; color: #333; background: #fff; outline: none;
    transition: border-color .15s, box-shadow .15s;
  }
  input:focus, select:focus, textarea:focus { border-color: #4a90d9; box-shadow: 0 0 0 3px rgba(74,144,217,.12); }
  textarea { height: 72px; padding: 8px 10px; resize: vertical; }
  select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }

  .customer-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #d0d5dd; border-top: none; border-radius: 0 0 6px 6px; max-height: 240px; overflow-y: auto; z-index: 200; box-shadow: 0 4px 12px rgba(0,0,0,.1); display: none; }
  .customer-dropdown.show { display: block; }
  .cust-option { padding: 9px 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background .1s; }
  .cust-option:hover { background: #f0f7ff; }
  .cust-option .cn { font-weight: 500; color: #1a1a2e; font-size: 13px; }
  .cust-option .cm { font-size: 11px; color: #888; margin-top: 2px; }
  .cust-option.no-res { color: #aaa; font-size: 12px; cursor: default; }
  .cust-option.no-res:hover { background: #fff; }

  .cat-label-box { display: none; margin-bottom: 14px; margin-left: 168px; }
  .cat-label-inner { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #eef4ff; border: 0.5px solid #b5d4f4; border-radius: 6px; }
  .cat-avatar { width: 32px; height: 32px; border-radius: 50%; background: #4a90d9; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 600; flex-shrink: 0; }
  .cat-name { font-size: 13px; font-weight: 500; color: #0c447c; }
  .cat-sub { font-size: 11px; color: #185fa5; margin-top: 1px; }
  .cat-badge { margin-left: auto; font-size: 11px; border-radius: 10px; padding: 3px 10px; white-space: nowrap; }
  .cat-badge.ok   { color: #27ae60; background: #ecfdf5; border: 0.5px solid #a7f3d0; }
  .cat-badge.warn { color: #e05050; background: #fef2f2; border: 0.5px solid #fecaca; }

  .amt-wrap { position: relative; }
  .amt-prefix { position: absolute; left: 0; top: 0; bottom: 0; width: 40px; display: flex; align-items: center; justify-content: center; background: #f5f6fa; border: 1px solid #d0d5dd; border-right: none; border-radius: 6px 0 0 6px; font-size: 12px; color: #555; font-weight: 600; }
  .amt-wrap input { padding-left: 48px; }

  .full-amt-check { display: flex; align-items: center; gap: 6px; margin-top: 5px; font-size: 12px; color: #555; cursor: pointer; }
  .full-amt-check input { cursor: pointer; width: 13px; height: 13px; }

  .radio-grp { display: flex; gap: 20px; align-items: center; padding-top: 6px; }
  .radio-grp label { display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12.5px; }
  .radio-grp input[type=radio] { cursor: pointer; accent-color: #4a90d9; }

  .inv-section { border: 1px solid #e3e6ea; border-radius: 8px; margin-bottom: 16px; overflow: hidden; background: #fff; }
  .inv-header { background: #f8f9fa; padding: 10px 16px; display: flex; align-items: center; gap: 14px; border-bottom: 1px solid #e3e6ea; }
  .inv-header h3 { font-size: 13px; font-weight: 600; color: #333; }
  .clear-btn { margin-left: auto; font-size: 11.5px; color: #4a90d9; cursor: pointer; background: none; border: none; }
  .clear-btn:hover { text-decoration: underline; }
  .inv-table { width: 100%; border-collapse: collapse; }
  .inv-table thead th { background: #f5f6fa; padding: 8px 12px; font-size: 11px; font-weight: 600; color: #777; text-transform: uppercase; letter-spacing: .3px; text-align: left; border-bottom: 1px solid #e5e5e5; }
  .inv-table tbody td { padding: 10px 12px; font-size: 12.5px; color: #333; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  .inv-table tbody tr:last-child td { border-bottom: none; }
  .inv-table tbody tr:hover td { background: #fafbff; }
  .inv-num { color: #4a90d9; font-weight: 500; }
  .due-date-sm { font-size: 11px; color: #888; }
  .pay-full-link { font-size: 11px; color: #4a90d9; cursor: pointer; display: block; margin-top: 3px; background: none; border: none; padding: 0; }
  .pay-full-link:hover { text-decoration: underline; }
  input.pay-amt { width: 100px; padding: 5px 8px; border: 1px solid #d0d5dd; border-radius: 4px; font-size: 12px; height: 30px; outline: none; }
  input.pay-amt:focus { border-color: #4a90d9; }
  .inv-footer { background: #fafafa; padding: 8px 12px; border-top: 1px solid #e3e6ea; }
  .inv-footer .tot-row { display: flex; justify-content: flex-end; gap: 20px; font-size: 12px; color: #555; font-weight: 500; }
  .empty-inv { padding: 30px; text-align: center; color: #aaa; font-size: 12.5px; }

  .summary-box { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; margin-bottom: 16px; }
  .sum-row { display: flex; width: 340px; justify-content: space-between; font-size: 12.5px; color: #555; }
  .sum-row.excess { color: #e05050; font-weight: 500; }
  .sum-row .sv { font-weight: 500; min-width: 80px; text-align: right; }

  .footer-bar { position: fixed; bottom: 0; left: 200px; right: 0; background: #fff; border-top: 1px solid #e0e3e8; padding: 12px 24px; display: flex; align-items: center; gap: 10px; z-index: 200; }
  .btn-primary { background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 9px 22px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-primary:hover { background: #3a7bc8; }
  .btn-primary:disabled { background: #93c5fd; cursor: not-allowed; }
  .btn-draft { background: #fff; color: #333; border: 1px solid #d0d5dd; border-radius: 6px; padding: 9px 22px; font-size: 13px; cursor: pointer; }
  .btn-draft:hover { background: #f5f6f8; }
  .btn-cancel { background: none; border: none; color: #888; font-size: 13px; cursor: pointer; padding: 9px 10px; }

  .toast { position: fixed; bottom: 24px; right: 24px; background: #2d3748; color: #fff; padding: 12px 20px; border-radius: 6px; font-size: 13px; opacity: 0; transform: translateY(10px); transition: all .3s; z-index: 9999; pointer-events: none; }
  .toast.show { opacity: 1; transform: translateY(0); }
  .toast.success { background: #276749; }
  .toast.error { background: #9b2c2c; }

  .spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid #ccc; border-top-color: #4a90d9; border-radius: 50%; animation: spin .6s linear infinite; margin-right: 6px; vertical-align: middle; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Modals ── */
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1000; display: flex; align-items: center; justify-content: center; }
  .modal-box { background: #fff; border-radius: 10px; padding: 28px 32px; width: 420px; box-shadow: 0 8px 32px rgba(0,0,0,.18); }
  .modal-box h2 { font-size: 16px; font-weight: 600; color: #1a1a2e; margin-bottom: 6px; }
  .modal-box p { font-size: 12.5px; color: #666; margin-bottom: 20px; }
  .modal-amt-wrap { position: relative; margin-bottom: 16px; }
  .modal-amt-prefix { position: absolute; left: 0; top: 0; bottom: 0; width: 44px; display: flex; align-items: center; justify-content: center; background: #f5f6fa; border: 1px solid #d0d5dd; border-right: none; border-radius: 6px 0 0 6px; font-weight: 600; color: #555; }
  .modal-amt-wrap input { width: 100%; height: 40px; border: 1px solid #d0d5dd; border-radius: 6px; padding: 0 12px 0 52px; font-size: 15px; font-weight: 600; outline: none; }
  .modal-amt-wrap input:focus { border-color: #4a90d9; box-shadow: 0 0 0 3px rgba(74,144,217,.12); }
  .modal-note { font-size: 11.5px; color: #888; background: #f8f9fa; border-radius: 6px; padding: 8px 12px; margin-bottom: 20px; }
  .modal-btns { display: flex; gap: 10px; justify-content: flex-end; }
  .modal-btn-confirm { background: #27ae60; color: #fff; border: none; border-radius: 6px; padding: 9px 22px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .modal-btn-confirm:hover { background: #219150; }
  .modal-btn-cancel { background: #fff; color: #555; border: 1px solid #d0d5dd; border-radius: 6px; padding: 9px 18px; font-size: 13px; cursor: pointer; }
  .modal-btn-cancel:hover { background: #f5f6f8; }
  .custom-mode-item { display: flex; align-items: center; justify-content: space-between; padding: 7px 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 5px; font-size: 12.5px; }
  .custom-mode-del { background: none; border: none; color: #e05050; cursor: pointer; font-size: 14px; padding: 0 4px; }

  /* ── AI Model Panel ── */
  .ai-panel { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 16px 20px; margin-bottom: 16px; }
  .ai-panel-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
  .ai-panel-header h3 { font-size: 13px; font-weight: 600; color: #333; }
  .ai-panel-header .ai-badge { background: #eef4ff; color: #4a90d9; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 10px; border: 0.5px solid #b5d4f4; text-transform: uppercase; letter-spacing: .4px; }
  .ai-suggestion-box { background: #f8fbff; border: 1px solid #d0e8fb; border-radius: 6px; padding: 12px 14px; min-height: 60px; font-size: 12.5px; color: #333; line-height: 1.6; }
  .ai-suggestion-box.loading { color: #aaa; font-style: italic; }
  .ai-suggestion-box.empty { color: #bbb; font-style: italic; }
  .ai-actions { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
  .ai-chip { padding: 5px 12px; border-radius: 14px; font-size: 11.5px; cursor: pointer; border: 1px solid #d0d5dd; background: #fff; color: #555; transition: all .15s; white-space: nowrap; }
  .ai-chip:hover { background: #f0f7ff; border-color: #4a90d9; color: #4a90d9; }
  .ai-chip.active { background: #4a90d9; border-color: #4a90d9; color: #fff; }
  .ai-apply-btn { margin-left: auto; background: #4a90d9; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-size: 12px; cursor: pointer; display: none; }
  .ai-apply-btn:hover { background: #3a7bc8; }
  .ai-apply-btn.visible { display: block; }
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
    <div class="page-title">💰 Record Payment</div>

    <div class="card">

      <!-- Customer Name -->
      <div class="frow">
        <label class="req">Customer Name</label>
        <div style="position:relative;flex:1;max-width:460px">
          <input type="text" id="customerInput" placeholder="Select Customer"
            autocomplete="off"
            oninput="onCustomerInput(this.value)"
            onfocus="onCustomerFocus()">
          <input type="hidden" id="customerId">
          <div class="customer-dropdown" id="customerDropdown"></div>
        </div>
      </div>

      <!-- Category Label -->
      <div class="cat-label-box" id="cat-label-box">
        <div class="cat-label-inner">
          <div class="cat-avatar" id="cat-avatar">?</div>
          <div>
            <div class="cat-name" id="cat-display-name">—</div>
            <div class="cat-sub">User Category</div>
          </div>
          <span class="cat-badge" id="cat-badge"></span>
        </div>
      </div>
      <input type="hidden" id="user-category-id">

      <!-- Location -->
      <div class="frow">
        <label>Location</label>
        <select id="locationSelect" style="max-width:260px" onchange="onLocationChange(this.value)">
          <option value="">— Select Location —</option>
          @foreach($locations as $loc)
          <option value="{{ $loc->id }}">
            {{ $loc->location_name }}
            ({{ $loc->location_type === 'business' ? '🏢' : '🏭' }})
          </option>
          @endforeach
        </select>
      </div>

      <div style="height:1px;background:#f0f2f4;margin:6px 0 14px"></div>

      <!-- Amount Received -->
      <div class="frow">
        <label class="req">Amount Received</label>
        <div style="max-width:320px">
          <div class="amt-wrap">
            <span class="amt-prefix">₹</span>
            <input type="number" id="amountReceived" placeholder="0.00" min="0" step="0.01"
                   oninput="onAmountInput(this.value)">
          </div>

          <!-- Outstanding balance display -->
          <div id="balanceInfoBox" style="display:none;margin-top:8px;background:#f8f9fa;border:1px solid #e3e6ea;border-radius:6px;padding:10px 12px">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;padding:3px 0">
              <span>Total Outstanding (Balance Due):</span>
              <span style="font-weight:600;color:#e05050" id="totalOutstandingDisplay">₹0.00</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;padding:3px 0">
              <span>Amount Entering:</span>
              <span style="font-weight:600;color:#1a1a2e" id="enteringAmtDisplay">₹0.00</span>
            </div>
            <div style="height:1px;background:#e3e6ea;margin:5px 0"></div>
            <div id="remainingRow" style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0">
              <span id="remainingLabel">Remaining Balance:</span>
              <span style="font-weight:600" id="remainingDisplay">₹0.00</span>
            </div>
            <div id="excessCreditBox" style="display:none;margin-top:8px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:6px;padding:8px 12px">
              <div style="font-size:12px;color:#166534;font-weight:600">✅ ₹<span id="excessCreditAmt">0.00</span> will be stored as Customer Credit</div>
              <div style="font-size:11px;color:#15803d;margin-top:2px">This credit can be applied to future invoices</div>
            </div>
          </div>

          <label class="full-amt-check" id="fullAmtLabel" style="display:none;margin-top:8px">
            <input type="checkbox" id="fullAmtCheck" onchange="applyFullAmount(this)">
            <span id="fullAmtText">Received full amount (₹0.00)</span>
          </label>
        </div>
      </div>

      <!-- Bank Charges -->
      <div class="frow">
        <label>Bank Charges</label>
        <input type="number" id="bankCharges" placeholder="0.00" min="0" step="0.01" style="max-width:260px" oninput="updateSummary()">
      </div>

      <!-- Payment Date -->
      <div class="frow">
        <label class="req">Payment Date</label>
        <input type="date" id="paymentDate" style="max-width:200px">
      </div>

      <!-- Payment # -->
      <div class="frow">
        <label class="req">Payment #</label>
        <input type="text" id="paymentNo" style="max-width:260px">
      </div>

      <!-- Payment Mode -->
      <div class="frow">
        <label>Payment Mode</label>
        <div style="display:flex;gap:8px;align-items:center;max-width:400px">
          <select id="paymentMode" style="flex:1" onchange="onPaymentModeChange(this.value)">
            <option value="">— Select —</option>
            <option value="cash" selected>Cash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="upi">UPI</option>
            <option value="card">Card</option>
            <option value="advance_payment" style="color:#27ae60;font-weight:600">⭐ Advance Payment</option>
          </select>
          <button type="button" onclick="openAddPaymentModeModal()"
                  style="height:34px;padding:0 12px;border:1px dashed #4a90d9;border-radius:6px;background:#fff;color:#4a90d9;font-size:12px;cursor:pointer;white-space:nowrap;flex-shrink:0">
            + Add New
          </button>
        </div>
      </div>

      <!-- Advance Payment Info Box -->
      <div class="frow" id="advanceInfoRow" style="display:none">
        <label></label>
        <div style="max-width:460px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;padding:12px 16px">
          <div style="font-size:13px;font-weight:600;color:#166534;margin-bottom:6px">⭐ Advance Payment Mode</div>
          <div style="font-size:12px;color:#15803d;line-height:1.6">
            This payment will be stored as <strong>customer credit</strong>.<br>
            The credit will be used when applied to an invoice.<br>
            <span id="advanceCreditPreview" style="font-weight:600"></span>
          </div>
        </div>
      </div>
        <!-- Credit Apply to Invoices Section (shown after advance payment confirmed) -->
<div class="frow" id="creditApplyInvoicesRow" style="display:none">
  <label></label>
  <div style="max-width:600px;border:1px solid #a7f3d0;border-radius:8px;overflow:hidden">
    
    <!-- Header with checkbox -->
    <div style="background:#ecfdf5;padding:12px 16px;display:flex;align-items:center;gap:10px;
                border-bottom:1px solid #a7f3d0">
      <input type="checkbox" id="applyToInvoicesCheck" 
             style="width:15px;height:15px;accent-color:#27ae60;cursor:pointer"
             onchange="onApplyToInvoicesToggle(this)">
      <label for="applyToInvoicesCheck" 
             style="font-size:13px;font-weight:600;color:#166534;cursor:pointer;margin:0">
        💡 Apply this credit to outstanding invoices immediately
      </label>
    </div>

    <!-- Invoice list (shown when checked) -->
    <div id="creditInvoiceApplyList" style="display:none;padding:12px 16px;background:#fff">
      <div style="font-size:12px;color:#555;margin-bottom:10px;">
        Select how much to apply to each invoice:
      </div>
      <div id="creditInvoiceRows"></div>
      <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f2f4;
                  display:flex;justify-content:space-between;font-size:12px;font-weight:600">
        <span>Total to Apply:</span>
        <span id="creditApplyTotal" style="color:#166534">₹0.00</span>
      </div>
      <div style="margin-top:6px;font-size:11px;color:#888" id="creditRemainingNote"></div>
    </div>

  </div>
</div>

      <!-- Deposit To -->
      <div class="frow">
        <label class="req">Deposit To</label>
        <select id="depositTo" style="max-width:260px">
          <option value="petty_cash" selected>Petty Cash</option>
          <option value="bank">Bank</option>
        </select>
      </div>

      <!-- Reference # -->
      <div class="frow">
        <label>Reference #</label>
        <input type="text" id="referenceNo" style="max-width:260px">
      </div>

      <!-- Tax Deducted -->
      <div class="frow">
        <label>Tax Deducted?</label>
        <div class="radio-grp">
          <label><input type="radio" name="tax" value="no" checked> No Tax Deducted</label>
          <label><input type="radio" name="tax" value="yes"> Yes, TDS (Income Tax)</label>
        </div>
      </div>

    </div><!-- /card -->
    <!-- Unpaid Invoices -->
    <div class="inv-section" id="invSection">
      <div class="inv-header">
        <h3>📄 Unpaid Invoices</h3>
        <button class="clear-btn" onclick="clearApplied()">Clear Applied Amount</button>
      </div>
      <div id="invoicesWrapper">
        <div class="empty-inv">Select a customer to view unpaid invoices</div>
      </div>
      <div class="inv-footer">
        <div class="tot-row">
          <span>Total Applied</span>
          <span id="invoiceTotal">0.00</span>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="summary-box">
      <div class="sum-row"><span>Amount Received :</span><span class="sv" id="sumReceived">0.00</span></div>
      <div class="sum-row"><span>Amount Used for Payments :</span><span class="sv" id="sumUsed">0.00</span></div>
      <div class="sum-row"><span>Amount Refunded :</span><span class="sv">0.00</span></div>
      <div class="sum-row excess"><span>⚠ Amount in Excess :</span><span class="sv" id="sumExcess">₹ 0.00</span></div>
    </div>

    <!-- Notes -->
    <div class="card">
      <div class="frow">
        <label>Notes</label>
        <textarea id="notes" placeholder="Internal use. Not visible to customer." style="max-width:500px"></textarea>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer-bar">
      <button class="btn-draft" onclick="savePayment('draft')">Save as Draft</button>
      <button class="btn-primary" id="saveBtn" onclick="savePayment('paid')">Save as Paid</button>
      <button class="btn-cancel" onclick="history.back()">Cancel</button>
    </div>
  </main>
</div>

<div class="toast" id="toast"></div>

<!-- Advance Payment Modal -->
<div class="modal-overlay" id="advanceModal" style="display:none" onclick="closeAdvanceModal(event)">
  <div class="modal-box" onclick="event.stopPropagation()">
    <h2>⭐ Advance Payment</h2>
    <p>How much advance is the customer paying? Any amount exceeding the balance due will be stored as credit.</p>
    <div class="modal-amt-wrap">
      <span class="modal-amt-prefix">₹</span>
      <input type="number" id="advanceAmountInput" placeholder="0.00" min="0.01" step="0.01"
             oninput="onAdvanceAmountInput(this.value)">
    </div>
    <div class="modal-note">
      ₹<span id="advancePreviewAmt">0.00</span> will be stored as
      <strong>Unused Credit</strong> for this customer.
    </div>
    <div class="modal-btns">
      <button class="modal-btn-cancel" onclick="cancelAdvancePayment()">Cancel</button>
      <button class="modal-btn-confirm" onclick="confirmAdvanceAmount()">Confirm & Apply</button>
    </div>
  </div>
</div>

<!-- Add New Payment Mode Modal -->
<div class="modal-overlay" id="addModeModal" style="display:none" onclick="closeAddModeModal(event)">
  <div class="modal-box" onclick="event.stopPropagation()" style="width:380px">
    <h2>+ Add Payment Mode</h2>
    <p style="margin-bottom:16px">Enter a name for the new payment mode</p>
    <div style="margin-bottom:16px">
      <input type="text" id="newModeInput" placeholder="e.g. NEFT, RTGS, Demand Draft..."
             style="width:100%;height:38px;border:1px solid #d0d5dd;border-radius:6px;padding:0 12px;font-size:13px;outline:none"
             onkeyup="if(event.key==='Enter') addNewPaymentMode()">
    </div>
    <div id="customModesList" style="margin-bottom:16px;max-height:160px;overflow-y:auto"></div>
    <div class="modal-btns">
      <button class="modal-btn-cancel" onclick="closeAddModeModal()">Cancel</button>
      <button class="modal-btn-confirm" onclick="addNewPaymentMode()">Add Mode</button>
    </div>
  </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ══════════════════════════════════════════════════════
// ALL_LOCATIONS — Blade → JS (location dropdown reset)
// ══════════════════════════════════════════════════════
const ALL_LOCATIONS = [
  @foreach($locations as $loc)
  {
    id: {{ $loc->id }},
    location_name: "{{ addslashes($loc->location_name) }}",
    location_type: "{{ $loc->location_type }}"
  },
  @endforeach
];

// ══════════════════════════════════════════════════════
// STATE
// ══════════════════════════════════════════════════════
let allCustomers     = [];
let unpaidInvoices   = [];
let invoicePayments  = {};
let isAdvancePayment = false;
let totalOutstanding = 0;
let customPayModes   = JSON.parse(localStorage.getItem('customPayModes') || '[]');
let aiLastSuggestion = null;
let aiLastAction     = null;

// ══════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('paymentDate').value = new Date().toISOString().split('T')[0];
  fetchCustomers('');
  renderCustomModesInSelect();

  document.addEventListener('click', e => {
    if (!e.target.closest('#customerInput') && !e.target.closest('#customerDropdown'))
      document.getElementById('customerDropdown').classList.remove('show');
  });
});

// ══════════════════════════════════════════════════════
// CUSTOMER FETCH & DROPDOWN
// ══════════════════════════════════════════════════════
async function fetchCustomers(search) {
  try {
    const res  = await fetch(`/payments-records/customers?search=${encodeURIComponent(search)}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    if (data.success) allCustomers = data.data;
  } catch(e) { console.error('Customer fetch failed', e); }
}

function onCustomerFocus() {
  onCustomerInput(document.getElementById('customerInput').value);
}

function onCustomerInput(val) {
  if (!allCustomers.length) fetchCustomers(val);

  const dd = document.getElementById('customerDropdown');
  const filtered = val
    ? allCustomers.filter(c =>
        (c.name         || '').toLowerCase().includes(val.toLowerCase()) ||
        (c.display_name || '').toLowerCase().includes(val.toLowerCase()) ||
        (c.user_code    || '').toLowerCase().includes(val.toLowerCase()))
    : allCustomers;

  dd.innerHTML = '';
  if (!filtered.length) {
    dd.innerHTML = '<div class="cust-option no-res">No customers found</div>';
  } else {
    filtered.forEach(c => {
      const div = document.createElement('div');
      div.className = 'cust-option';
      const meta = [c.company_name, c.phone_number, c.user_code].filter(Boolean).join(' · ');
      div.innerHTML = `<div class="cn">${c.display_name || c.name}</div>
                       ${meta ? `<div class="cm">${meta}</div>` : ''}`;
      div.onclick = () => selectCustomer(c);
      dd.appendChild(div);
    });
  }
  dd.classList.add('show');
}

function selectCustomer(c) {
  document.getElementById('customerInput').value = c.display_name || c.name;
  document.getElementById('customerId').value    = c.id;
  document.getElementById('customerDropdown').classList.remove('show');

  // Reset
  invoicePayments  = {};
  isAdvancePayment = false;
  totalOutstanding = 0;
  document.getElementById('amountReceived').value           = '';
  document.getElementById('paymentNo').value                = '';
  document.getElementById('balanceInfoBox').style.display   = 'none';
  document.getElementById('advanceInfoRow').style.display   = 'none';
  document.getElementById('invSection').style.display       = '';
  document.getElementById('paymentMode').value              = 'cash';
  resetCategoryLabel();
  updateSummary();
  resetAIPanel();

  fetchInvoices(c.id);
  fetchCustomerDefaults(c.id);
  fetchCustomerOutstanding(c.id);
}

// ══════════════════════════════════════════════════════
// OUTSTANDING BALANCE FETCH
// ══════════════════════════════════════════════════════
async function fetchCustomerOutstanding(customerId) {
  try {
    const res  = await fetch(`/payments-records/customer-credit?customer_id=${customerId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    if (data.success) {
      totalOutstanding = parseFloat(data.total_outstanding ?? 0);
      const box = document.getElementById('balanceInfoBox');
      if (totalOutstanding > 0) {
        box.style.display = 'block';
        document.getElementById('totalOutstandingDisplay').textContent = '₹' + totalOutstanding.toFixed(2);
        document.getElementById('enteringAmtDisplay').textContent      = '₹0.00';
        document.getElementById('remainingLabel').textContent          = 'Remaining Balance:';
        document.getElementById('remainingDisplay').textContent        = '₹' + totalOutstanding.toFixed(2);
        document.getElementById('remainingDisplay').style.color        = '#e05050';
        document.getElementById('excessCreditBox').style.display       = 'none';
      }
    }
  } catch(e) { console.error('Outstanding fetch failed', e); }
}

// ══════════════════════════════════════════════════════
// AMOUNT INPUT — real-time balance calculation
// ══════════════════════════════════════════════════════
function onAmountInput(val) {
  const amt = parseFloat(val) || 0;

  if (totalOutstanding > 0) {
    document.getElementById('balanceInfoBox').style.display   = 'block';
    document.getElementById('enteringAmtDisplay').textContent = '₹' + amt.toFixed(2);

    const remaining = totalOutstanding - amt;

    if (remaining > 0.005) {
      document.getElementById('remainingLabel').textContent           = 'Remaining Balance:';
      document.getElementById('remainingDisplay').textContent         = '₹' + remaining.toFixed(2);
      document.getElementById('remainingDisplay').style.color         = '#e05050';
      document.getElementById('excessCreditBox').style.display        = 'none';
    } else if (remaining < -0.005) {
      const excess = Math.abs(remaining);
      document.getElementById('remainingLabel').textContent           = 'Balance Cleared:';
      document.getElementById('remainingDisplay').textContent         = '₹0.00 (Fully Paid)';
      document.getElementById('remainingDisplay').style.color         = '#27ae60';
      document.getElementById('excessCreditBox').style.display        = 'block';
      document.getElementById('excessCreditAmt').textContent          = excess.toFixed(2);
    } else {
      document.getElementById('remainingLabel').textContent           = 'Balance Cleared:';
      document.getElementById('remainingDisplay').textContent         = '₹0.00 (Fully Paid ✓)';
      document.getElementById('remainingDisplay').style.color         = '#27ae60';
      document.getElementById('excessCreditBox').style.display        = 'none';
    }
  }

  updateSummary();
}

// ══════════════════════════════════════════════════════
// CUSTOMER DEFAULTS (category + location + series)
// ══════════════════════════════════════════════════════
async function fetchCustomerDefaults(customerId) {
  try {
    const res  = await fetch(`/payments-records/customer-defaults?customer_id=${customerId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) { resetCategoryLabel(); return; }

    const data = await res.json();
    if (!data.category) { resetCategoryLabel(); return; }

    document.getElementById('cat-label-box').style.display  = 'block';
    document.getElementById('cat-avatar').textContent        = (data.category.name[0] || '?').toUpperCase();
    document.getElementById('cat-display-name').textContent  = data.category.name;
    document.getElementById('user-category-id').value        = data.category.id;

    const badge = document.getElementById('cat-badge');
    if (data.locations && data.locations.length > 0) {
      badge.textContent = data.locations.length + ' location' + (data.locations.length > 1 ? 's' : '');
      badge.className   = 'cat-badge ok';
    } else {
      badge.textContent = 'No locations assigned';
      badge.className   = 'cat-badge warn';
    }

    filterLocationDropdown(data.locations || []);
    if (data.locations && data.locations.length > 0) {
      document.getElementById('locationSelect').value = data.locations[0].id;
    }
     // ── Advance Payment option show/hide ──
toggleAdvancePaymentOption(data.show_advance_payment === true);
    if (data.series && data.series.length > 0) {
      const s      = data.series[0];
      const padLen = String(s.start || '000001').length;
      const next   = s.last_number !== null && s.last_number !== undefined
        ? parseInt(s.last_number) + 1
        : parseInt(s.start || 1);
      document.getElementById('paymentNo').value = s.prefix + String(next).padStart(padLen, '0');
    }
  } catch(e) {
    console.error('[PAY] fetchCustomerDefaults error:', e);
    resetCategoryLabel();
  }
}

function filterLocationDropdown(filteredLocs) {
  const sel     = document.getElementById('locationSelect');
  const currVal = sel.value;
  sel.innerHTML = '<option value="">— Select Location —</option>';
  const list    = (filteredLocs && filteredLocs.length) ? filteredLocs : ALL_LOCATIONS;
  list.forEach(loc => {
    const icon = loc.location_type === 'business' ? '🏢' : '🏭';
    const opt  = document.createElement('option');
    opt.value  = loc.id;
    opt.textContent = `${loc.location_name} (${icon})`;
    if (String(loc.id) === String(currVal)) opt.selected = true;
    sel.appendChild(opt);
  });
}

function resetCategoryLabel() {
    document.getElementById('cat-label-box').style.display = 'none';
    document.getElementById('user-category-id').value      = '';
    filterLocationDropdown(ALL_LOCATIONS);
    toggleAdvancePaymentOption(false); 
}

function onLocationChange(locationId) {
  // Future: re-fetch series by location
}


// ══════════════════════════════════════════════════════
// ADVANCE CREDIT → INVOICE APPLY SECTION
// ══════════════════════════════════════════════════════
let creditApplyAmounts = {}; // { invoice_id: amount }

function renderAdvanceCreditApplySection(creditAmt) {
  const row = document.getElementById('creditApplyInvoicesRow');
  row.style.display = 'grid';
  document.getElementById('applyToInvoicesCheck').checked = false;
  document.getElementById('creditInvoiceApplyList').style.display = 'none';
  creditApplyAmounts = {};
}

function onApplyToInvoicesToggle(chk) {
  const listDiv = document.getElementById('creditInvoiceApplyList');
  listDiv.style.display = chk.checked ? 'block' : 'none';
  if (chk.checked) {
    renderCreditInvoiceRows();
  }
}

function renderCreditInvoiceRows() {
  const creditAmt = parseFloat(document.getElementById('amountReceived').value) || 0;
  const container = document.getElementById('creditInvoiceRows');
  
  if (!unpaidInvoices.length) {
    container.innerHTML = '<div style="color:#aaa;text-align:center;padding:10px;font-size:12px">No unpaid invoices</div>';
    return;
  }

  // Auto-distribute: fill invoices one by one up to credit amount
  let remaining = creditAmt;
  creditApplyAmounts = {};
  unpaidInvoices.forEach(inv => {
    const due = parseFloat(inv.amount_due || 0);
    const apply = Math.min(due, remaining);
    creditApplyAmounts[inv.id] = apply;
    remaining = Math.max(0, remaining - apply);
  });

  container.innerHTML = unpaidInvoices.map(inv => {
    const due   = parseFloat(inv.amount_due || 0);
    const apply = creditApplyAmounts[inv.id] || 0;
    const willBePaid = apply >= due - 0.005;
    return `
      <div style="display:flex;align-items:center;gap:10px;padding:8px 0;
                  border-bottom:1px solid #f5f5f5;font-size:12.5px">
        <div style="flex:1">
          <span style="color:#4a90d9;font-weight:500">${inv.invoice_number}</span>
          <span style="color:#888;margin-left:8px">Due: ₹${due.toFixed(2)}</span>
          ${willBePaid ? '<span style="color:#27ae60;margin-left:6px;font-size:11px">✓ Will be fully paid</span>' : ''}
        </div>
        <div style="display:flex;align-items:center;gap:6px">
          <span style="color:#555;font-size:11px">Apply:</span>
          <input type="number" class="credit-inv-input" 
                 id="cred-inv-${inv.id}"
                 data-inv-id="${inv.id}" data-max="${due}"
                 value="${apply.toFixed(2)}" min="0" step="0.01"
                 style="width:90px;height:28px;border:1px solid #d0d5dd;border-radius:4px;
                        padding:0 8px;font-size:12px;text-align:right"
                 oninput="onCreditInvInput(this)">
          <button type="button" onclick="setCreditInvFull(${inv.id}, ${due})"
                  style="font-size:11px;color:#4a90d9;background:none;border:none;
                         cursor:pointer;padding:0 4px">Full</button>
          <button type="button" onclick="setCreditInvZero(${inv.id})"
                  style="font-size:11px;color:#e05050;background:none;border:none;
                         cursor:pointer;padding:0 4px">Skip</button>
        </div>
      </div>`;
  }).join('');

  updateCreditApplyTotal();
}

function onCreditInvInput(input) {
  const invId = parseInt(input.dataset.invId);
  const max   = parseFloat(input.dataset.max || 0);
  let val     = parseFloat(input.value) || 0;
  if (val > max) { val = max; input.value = val.toFixed(2); }
  if (val < 0)   { val = 0;   input.value = 0; }
  creditApplyAmounts[invId] = val;
  updateCreditApplyTotal();
}

function setCreditInvFull(invId, amount) {
  const input = document.getElementById('cred-inv-' + invId);
  if (input) { input.value = parseFloat(amount).toFixed(2); }
  creditApplyAmounts[invId] = parseFloat(amount);
  updateCreditApplyTotal();
}

function setCreditInvZero(invId) {
  const input = document.getElementById('cred-inv-' + invId);
  if (input) { input.value = '0.00'; }
  creditApplyAmounts[invId] = 0;
  updateCreditApplyTotal();
}

function updateCreditApplyTotal() {
  const creditAmt = parseFloat(document.getElementById('amountReceived').value) || 0;
  const total = Object.values(creditApplyAmounts).reduce((s, v) => s + (parseFloat(v) || 0), 0);
  const remaining = creditAmt - total;

  document.getElementById('creditApplyTotal').textContent = '₹' + total.toFixed(2);
  
  const noteEl = document.getElementById('creditRemainingNote');
  if (remaining > 0.005) {
    noteEl.textContent = `₹${remaining.toFixed(2)} will remain as unused credit`;
    noteEl.style.color = '#888';
  } else if (remaining < -0.005) {
    noteEl.textContent = `⚠ Total exceeds credit amount by ₹${Math.abs(remaining).toFixed(2)}`;
    noteEl.style.color = '#e05050';
  } else {
    noteEl.textContent = '✓ Full credit will be utilized';
    noteEl.style.color = '#27ae60';
  }

  // Re-render rows to update "Will be fully paid" badges
  document.querySelectorAll('.credit-inv-input').forEach(inp => {
    const invId = parseInt(inp.dataset.invId);
    const due   = parseFloat(inp.dataset.max || 0);
    const apply = creditApplyAmounts[invId] || 0;
    const parentRow = inp.closest('div[style*="border-bottom"]');
    if (!parentRow) return;
    const badgeEl = parentRow.querySelector('span[style*="27ae60"]');
    if (apply >= due - 0.005) {
      if (!badgeEl) {
        const nameSpan = parentRow.querySelector('span[style*="4a90d9"]');
        if (nameSpan) {
          const badge = document.createElement('span');
          badge.style.cssText = 'color:#27ae60;margin-left:6px;font-size:11px';
          badge.textContent = '✓ Will be fully paid';
          nameSpan.parentNode.insertBefore(badge, nameSpan.nextSibling?.nextSibling);
        }
      }
    } else if (badgeEl) {
      badgeEl.remove();
    }
  });
}

// ══════════════════════════════════════════════════════
// INVOICES FETCH & RENDER
// ══════════════════════════════════════════════════════
async function fetchInvoices(customerId) {
  const wrapper = document.getElementById('invoicesWrapper');
  wrapper.innerHTML = '<div class="empty-inv"><span class="spinner"></span> Loading invoices...</div>';

  try {
    const res  = await fetch(`/payments-records/invoices?customer_id=${customerId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    unpaidInvoices = data.success ? (data.invoices || []) : [];

    const payNoEl = document.getElementById('paymentNo');
    if (!payNoEl.value && data.next_payment_no) {
      payNoEl.value = data.next_payment_no;
    }
  } catch(e) {
    console.error('Invoices fetch failed', e);
    unpaidInvoices = [];
  }
  renderInvoices();
}

function renderInvoices() {
  const wrapper  = document.getElementById('invoicesWrapper');
  const totalDue = unpaidInvoices.reduce((s, i) => s + parseFloat(i.amount_due || 0), 0);

  const fullLabel = document.getElementById('fullAmtLabel');
  if (totalDue > 0) {
    fullLabel.style.display = 'flex';
    document.getElementById('fullAmtText').textContent = `Received full amount (₹${totalDue.toFixed(2)})`;
  } else {
    fullLabel.style.display = 'none';
  }

  if (!unpaidInvoices.length) {
    wrapper.innerHTML = '<div class="empty-inv">No unpaid invoices found for this customer</div>';
    updateInvoiceTotal();
    return;
  }

  const today = new Date().toISOString().split('T')[0];
  const rows  = unpaidInvoices.map(inv => {
    const existing = invoicePayments[inv.id] !== undefined ? invoicePayments[inv.id] : '';
    return `
      <tr>
        <td>
          <div>${fmtDate(inv.invoice_date)}</div>
          <div class="due-date-sm">Due: ${fmtDate(inv.due_date)}</div>
        </td>
        <td><span class="inv-num">${inv.invoice_number}</span></td>
        <td>${inv.location || '—'}</td>
        <td>${parseFloat(inv.total_amount || 0).toFixed(2)}</td>
        <td>${parseFloat(inv.amount_due   || 0).toFixed(2)}</td>
        <td>${today}</td>
        <td>
          <input class="pay-amt" type="number" min="0" step="0.01"
            placeholder="0" value="${existing}"
            data-inv-id="${inv.id}" data-max="${inv.amount_due}"
            oninput="onPayChange(this)">
          <button class="pay-full-link" onclick="payInFull(${inv.id}, ${inv.amount_due})">Pay in Full</button>
        </td>
      </tr>`;
  }).join('');

  wrapper.innerHTML = `
    <table class="inv-table">
      <thead><tr>
        <th>Date</th><th>Invoice #</th><th>Location</th>
        <th>Invoice Amount</th><th>Amount Due</th>
        <th>Payment Date</th><th>Payment</th>
      </tr></thead>
      <tbody>${rows}</tbody>
    </table>
    <div style="font-size:11px;color:#aaa;padding:6px 12px">* Shows only Unpaid / Partial invoices</div>`;

  updateInvoiceTotal();
}

function onPayChange(input) {
  const invId = parseInt(input.dataset.invId);
  const max   = parseFloat(input.dataset.max || 0);
  let val     = parseFloat(input.value) || 0;
  if (val > max) { val = max; input.value = val; }
  if (val < 0)   { val = 0;   input.value = 0; }
  invoicePayments[invId] = val;
  updateInvoiceTotal();
  updateSummary();
}

function payInFull(invId, amount) {
  const input = document.querySelector(`.pay-amt[data-inv-id="${invId}"]`);
  if (input) {
    input.value = parseFloat(amount).toFixed(2);
    invoicePayments[invId] = parseFloat(amount);
    updateInvoiceTotal();
    updateSummary();
  }
}

function clearApplied() {
  invoicePayments = {};
  document.querySelectorAll('.pay-amt').forEach(i => i.value = '');
  updateInvoiceTotal();
  updateSummary();
}

function applyFullAmount(chk) {
  const totalDue = unpaidInvoices.reduce((s, i) => s + parseFloat(i.amount_due || 0), 0);
  if (chk.checked) {
    document.getElementById('amountReceived').value = totalDue.toFixed(2);
    unpaidInvoices.forEach(inv => {
      invoicePayments[inv.id] = parseFloat(inv.amount_due);
      const inp = document.querySelector(`.pay-amt[data-inv-id="${inv.id}"]`);
      if (inp) inp.value = parseFloat(inv.amount_due).toFixed(2);
    });
  } else {
    document.getElementById('amountReceived').value = '';
    clearApplied();
  }
  updateInvoiceTotal();
  updateSummary();
}

function updateInvoiceTotal() {
  const total = Object.values(invoicePayments).reduce((s, v) => s + (parseFloat(v) || 0), 0);
  document.getElementById('invoiceTotal').textContent = total.toFixed(2);
  return total;
}

function updateSummary() {
  const received = parseFloat(document.getElementById('amountReceived').value) || 0;
  const used     = updateInvoiceTotal();
  const excess   = Math.max(0, received - used);
  document.getElementById('sumReceived').textContent = received.toFixed(2);
  document.getElementById('sumUsed').textContent     = used.toFixed(2);
  document.getElementById('sumExcess').textContent   = `₹ ${excess.toFixed(2)}`;
}

// ══════════════════════════════════════════════════════
// PAYMENT MODE — advance payment
// ══════════════════════════════════════════════════════
function onPaymentModeChange(val) {
  if (val === 'advance_payment') {
    document.getElementById('advanceModal').style.display    = 'flex';
    document.getElementById('advanceAmountInput').value      = document.getElementById('amountReceived').value || '';
    onAdvanceAmountInput(document.getElementById('advanceAmountInput').value);
    setTimeout(() => document.getElementById('advanceAmountInput').focus(), 100);
  } else {
    isAdvancePayment = false;
    document.getElementById('advanceInfoRow').style.display  = 'none';
    document.getElementById('invSection').style.display      = '';
  }
}

function onAdvanceAmountInput(val) {
  document.getElementById('advancePreviewAmt').textContent = (parseFloat(val) || 0).toFixed(2);
}

function confirmAdvanceAmount() {
  const amt = parseFloat(document.getElementById('advanceAmountInput').value) || 0;
  if (amt <= 0) { alert('Please enter a valid amount'); return; }

  isAdvancePayment = true;
  document.getElementById('amountReceived').value = amt.toFixed(2);
  document.getElementById('advanceCreditPreview').textContent = `₹${amt.toFixed(2)} will be stored as credit`;
  document.getElementById('advanceInfoRow').style.display = 'grid';
  document.getElementById('invSection').style.display = 'none';
  document.getElementById('advanceModal').style.display = 'none';

  // ── NEW: Outstanding invoices இருந்தா apply checkbox காட்டு ──
  if (totalOutstanding > 0 && unpaidInvoices.length > 0) {
    renderAdvanceCreditApplySection(amt);
  }

  updateSummary();
}

function cancelAdvancePayment() {
  document.getElementById('paymentMode').value             = 'cash';
  isAdvancePayment = false;
  document.getElementById('advanceInfoRow').style.display  = 'none';
  document.getElementById('invSection').style.display      = '';
  document.getElementById('advanceModal').style.display    = 'none';
}

function closeAdvanceModal(e) {
  if (!e || e.target === document.getElementById('advanceModal'))
    document.getElementById('advanceModal').style.display = 'none';
}

// ══════════════════════════════════════════════════════
// ADD NEW PAYMENT MODE
// ══════════════════════════════════════════════════════
function openAddPaymentModeModal() {
  document.getElementById('addModeModal').style.display = 'flex';
  document.getElementById('newModeInput').value         = '';
  renderCustomModesList();
  setTimeout(() => document.getElementById('newModeInput').focus(), 100);
}

function closeAddModeModal(e) {
  if (!e || e.target === document.getElementById('addModeModal'))
    document.getElementById('addModeModal').style.display = 'none';
}

function renderCustomModesList() {
  const list = document.getElementById('customModesList');
  if (!customPayModes.length) {
    list.innerHTML = '<div style="font-size:12px;color:#aaa;text-align:center;padding:8px">No custom modes added yet</div>';
    return;
  }
  list.innerHTML = customPayModes.map((m, i) => `
    <div class="custom-mode-item">
      <span>${m}</span>
      <button class="custom-mode-del" onclick="deleteCustomMode(${i})">✕</button>
    </div>`).join('');
}

function addNewPaymentMode() {
  const name = document.getElementById('newModeInput').value.trim();
  if (!name) { alert('Please enter a mode name'); return; }
  if (customPayModes.includes(name)) { alert('This mode already exists!'); return; }

  customPayModes.push(name);
  localStorage.setItem('customPayModes', JSON.stringify(customPayModes));
  renderCustomModesList();
  renderCustomModesInSelect();
  document.getElementById('paymentMode').value          = name;
  document.getElementById('addModeModal').style.display = 'none';
}

function deleteCustomMode(index) {
  customPayModes.splice(index, 1);
  localStorage.setItem('customPayModes', JSON.stringify(customPayModes));
  renderCustomModesList();
  renderCustomModesInSelect();
}

function renderCustomModesInSelect() {
  const sel = document.getElementById('paymentMode');
  sel.querySelectorAll('.custom-mode-opt').forEach(o => o.remove());
  const advOpt = sel.querySelector('option[value="advance_payment"]');
  customPayModes.forEach(m => {
    const opt       = document.createElement('option');
    opt.value       = m;
    opt.textContent = m;
    opt.className   = 'custom-mode-opt';
    sel.insertBefore(opt, advOpt);
  });
}

// ══════════════════════════════════════════════════════
// AI MODEL — Claude-powered payment assistant
// ══════════════════════════════════════════════════════
function resetAIPanel() {
  aiLastSuggestion = null;
  aiLastAction     = null;
  
  const box = document.getElementById('aiSuggestionBox');
  if (!box) return; 
  
  box.className   = 'ai-suggestion-box empty';
  box.textContent = 'Select a customer and enter an amount to get AI-powered payment insights and suggestions.';
  
  const applyBtn = document.getElementById('aiApplyBtn');
  if (applyBtn) applyBtn.classList.remove('visible');
  
  document.querySelectorAll('.ai-chip').forEach(c => c.classList.remove('active'));
}

async function askAI(action) {
  const customerName = document.getElementById('customerInput').value.trim();
  const customerId   = document.getElementById('customerId').value;
  if (!customerId) { showToast('Please select a customer first', 'error'); return; }

  const amount      = parseFloat(document.getElementById('amountReceived').value) || 0;
  const outstanding = totalOutstanding;
  const invoiceCount = unpaidInvoices.length;
  const totalDue    = unpaidInvoices.reduce((s, i) => s + parseFloat(i.amount_due || 0), 0);
  const paymentMode = document.getElementById('paymentMode').value;

  // Build context for AI
  const context = {
    customer_name: customerName,
    amount_received: amount,
    total_outstanding: outstanding,
    unpaid_invoice_count: invoiceCount,
    total_due_on_invoices: totalDue,
    payment_mode: paymentMode,
    excess_amount: Math.max(0, amount - totalDue),
  };

  let prompt = '';
  if (action === 'summarize') {
    prompt = `You are a helpful accounts receivable assistant. Given the following payment context, provide a clear, concise 2-3 sentence summary of the payment situation for the user recording this payment. Be specific about amounts and what action is recommended.\n\nContext: ${JSON.stringify(context, null, 2)}`;
  } else if (action === 'suggest_mode') {
    prompt = `You are a helpful accounts assistant. Based on the payment amount and context below, briefly suggest the most appropriate payment mode and explain why in 1-2 sentences. Keep it practical and concise.\n\nContext: ${JSON.stringify(context, null, 2)}`;
  } else if (action === 'overdue_check') {
    prompt = `You are an accounts receivable risk analyst. Review the following payment context and assess the overdue risk in 2-3 sentences. Mention if the customer is clearing dues, partially paying, or if there are concerns. Be direct and helpful.\n\nContext: ${JSON.stringify(context, null, 2)}`;
  } else if (action === 'notes') {
    prompt = `You are a professional bookkeeper. Write a concise internal payment note (1-2 sentences, no more than 30 words) suitable for the Notes field based on the following context. Just output the note text, nothing else.\n\nContext: ${JSON.stringify(context, null, 2)}`;
  }

  // Update UI
  aiLastAction = action;
  document.querySelectorAll('.ai-chip').forEach(c => c.classList.remove('active'));
  const activeChip = document.querySelector(`.ai-chip[onclick="askAI('${action}')"]`);
  if (activeChip) activeChip.classList.add('active');

  const box = document.getElementById('aiSuggestionBox');
  box.className   = 'ai-suggestion-box loading';
  box.innerHTML   = '<span class="spinner"></span> Thinking...';
  document.getElementById('aiApplyBtn').classList.remove('visible');

  try {
    const res = await fetch('https://api.anthropic.com/v1/messages', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        model: 'claude-sonnet-4-20250514',
        max_tokens: 1000,
        messages: [{ role: 'user', content: prompt }]
      })
    });

    const data = await res.json();
    const text = (data.content || []).map(b => b.text || '').join('').trim();

    aiLastSuggestion = text;
    box.className    = 'ai-suggestion-box';
    box.textContent  = text;

    if (action === 'notes') {
      document.getElementById('aiApplyBtn').classList.add('visible');
      document.getElementById('aiApplyBtn').textContent = 'Apply to Notes';
    }

  } catch(e) {
    box.className   = 'ai-suggestion-box';
    box.textContent = 'Unable to get AI suggestion at this time. Please try again.';
    console.error('AI fetch error:', e);
  }
}

function applyAISuggestion() {
  if (!aiLastSuggestion) return;
  if (aiLastAction === 'notes') {
    document.getElementById('notes').value = aiLastSuggestion;
    showToast('Note applied from AI suggestion', 'success');
    document.getElementById('aiApplyBtn').classList.remove('visible');
  }
}

// ══════════════════════════════════════════════════════
// SAVE PAYMENT
// ══════════════════════════════════════════════════════
async function savePayment(status) {
  const customerId  = document.getElementById('customerId').value;
  const amount      = parseFloat(document.getElementById('amountReceived').value) || 0;
  const paymentDate = document.getElementById('paymentDate').value;
  const paymentNo   = document.getElementById('paymentNo').value;

  if (!customerId)  { showToast('Please select a customer', 'error');       return; }
  if (amount <= 0)  { showToast('Please enter amount received', 'error');   return; }
  if (!paymentDate) { showToast('Please select payment date', 'error');     return; }
  if (!paymentNo)   { showToast('Please enter payment number', 'error');    return; }

  const btn = document.getElementById('saveBtn');
  btn.disabled = true;
  btn.textContent = 'Saving...';

  const invoicePaymentsArr = Object.entries(invoicePayments)
    .filter(([, amt]) => amt > 0)
    .map(([id, amt]) => ({ invoice_id: parseInt(id), amount: amt }));

  try {
    // Credit apply to invoices — advance payment-க்கு மட்டும்
const applyToInvoices = document.getElementById('applyToInvoicesCheck')?.checked || false;
const creditInvoicePayments = applyToInvoices
  ? Object.entries(creditApplyAmounts)
      .filter(([, amt]) => amt > 0.005)
      .map(([id, amt]) => ({ invoice_id: parseInt(id), amount: parseFloat(amt) }))
  : [];
    const res = await fetch('/payments-records', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        customer_id:        parseInt(customerId),
        amount_received:    amount,
        payment_date:       paymentDate,
        payment_no:         paymentNo,
        payment_mode:       document.getElementById('paymentMode').value,
        deposit_to:         document.getElementById('depositTo').value,
        reference:          document.getElementById('referenceNo').value,
        bank_charges:       parseFloat(document.getElementById('bankCharges').value) || 0,
        notes:              document.getElementById('notes').value,
        status,
        invoice_payments:   invoicePaymentsArr,
        is_advance_payment: isAdvancePayment,
        credit_invoice_payments: creditInvoicePayments,
apply_credit_to_invoices: applyToInvoices,
      }),
    });
    const data = await res.json();
    if (data.success) {
      showToast(`Payment #${paymentNo} recorded successfully!`, 'success');
    setTimeout(() => window.location.href = '{{ route("payments_records.index") }}', 2000);
    } else {
      showToast(data.message || 'Failed to save payment', 'error');
    }
  } catch(e) {
    showToast('Server error. Please try again.', 'error');
  } finally {
    btn.disabled    = false;
    btn.textContent = 'Save as Paid';
  }
}



function toggleAdvancePaymentOption(show) {
    const sel = document.getElementById('paymentMode');
    const existingOpt = sel.querySelector('option[value="advance_payment"]');
    
    if (show) {
        // காட்டு — இல்லன்னா add பண்ணு
        if (!existingOpt) {
            const opt = document.createElement('option');
            opt.value       = 'advance_payment';
            opt.textContent = '⭐ Advance Payment';
            opt.style.color      = '#27ae60';
            opt.style.fontWeight = '600';
            sel.appendChild(opt);
        } else {
            existingOpt.style.display = '';
        }
    } else {
        // மறை
        if (existingOpt) existingOpt.style.display = 'none';
        // Advance mode select ஆகியிருந்தா reset பண்ணு
        if (sel.value === 'advance_payment') {
            sel.value = 'cash';
            isAdvancePayment = false;
            document.getElementById('advanceInfoRow').style.display = 'none';
            document.getElementById('invSection').style.display = '';
        }
    }
}


// ══════════════════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════════════════
function fmtDate(d) {
  if (!d) return '—';
  return new Date(d).toLocaleDateString('en-IN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className   = `toast show ${type}`;
  setTimeout(() => t.className = 'toast', 3500);
}
</script>
</body>
</html>