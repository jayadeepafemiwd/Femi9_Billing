{{-- resources/views/invoices/payment.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Payment for {{ $invoice->invoice_number }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --navy:#1a2332; --navy2:#243447; --navy3:#2d3f55;
    --blue:#4a90d9; --blue2:#3a7bc8;
    --text:#1a1a2e; --muted:#6b7280;
    --border:#e3e6ea; --bg:#f0f2f5; --white:#ffffff;
    --red:#e05050; --green:#166534; --topbar-h:48px; --sidenav-w:220px;
}
body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; font-size:13px; color:var(--text); background:var(--bg); overflow:hidden; }

.topbar { position:fixed; top:0; left:0; right:0; z-index:400; height:var(--topbar-h); background:var(--navy); display:flex; align-items:center; padding:0 14px; gap:12px; }
.topbar-logo { color:#fff; font-size:14px; font-weight:600; display:flex; align-items:center; gap:8px; }
.topbar-search { flex:1; max-width:360px; margin:0 auto; background:var(--navy2); border-radius:5px; height:32px; display:flex; align-items:center; gap:8px; padding:0 12px; }
.topbar-search span { font-size:12px; color:#6b8097; }
.topbar-right { margin-left:auto; display:flex; align-items:center; gap:12px; }
.topbar-right .sub-text { color:#9ba8b8; font-size:12px; }
.topbar-right .sub-text a { color:var(--blue); text-decoration:none; }
.topbar-icons { display:flex; gap:10px; align-items:center; color:#9ba8b8; font-size:17px; }
.topbar-icons span { cursor:pointer; position:relative; }
.notif-dot { position:absolute; top:-4px; right:-4px; background:var(--red); color:#fff; font-size:9px; border-radius:50%; width:14px; height:14px; display:flex; align-items:center; justify-content:center; }
.topbar-avatar { width:30px; height:30px; border-radius:50%; background:var(--blue); color:#fff; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; }

.root-layout { display:flex; margin-top:var(--topbar-h); height:calc(100vh - var(--topbar-h)); }

.sidenav { width:var(--sidenav-w); background:var(--navy); height:100%; flex-shrink:0; overflow-y:auto; }
.snav-section { padding:12px 0 2px; }
.snav-label { font-size:10px; color:#5a6a7e; padding:4px 14px; text-transform:uppercase; letter-spacing:0.6px; }
.snav-item { font-size:13px; color:#9ba8b8; padding:7px 14px; cursor:pointer; border-left:3px solid transparent; display:flex; align-items:center; gap:8px; text-decoration:none; transition:background .15s; }
.snav-item:hover { background:var(--navy2); color:#cdd5df; }
.snav-item.active { color:#fff; background:var(--navy3); border-left-color:var(--blue); }

.main-content { flex:1; height:100%; overflow-y:auto; background:var(--bg); }

.pay-card { background:var(--white); border:1px solid var(--border); border-radius:8px; max-width:860px; margin:20px auto; overflow:hidden; }
.pay-header { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid var(--border); }
.pay-title { font-size:16px; font-weight:700; color:var(--text); }
.pay-inv-badge { background:#e8f0fe; color:var(--blue); padding:4px 12px; border-radius:4px; font-size:12px; font-weight:600; }

.balance-banner { display:flex; align-items:center; justify-content:space-between; padding:10px 24px; background:#fefce8; border-bottom:1px solid #fef08a; }
.bb-label { font-size:12px; color:#92400e; font-weight:500; }
.bb-amount { font-size:16px; font-weight:700; color:#92400e; }

.pay-body { padding:24px; }
.form-row { display:grid; gap:20px; margin-bottom:20px; }
.form-row.cols-2 { grid-template-columns:1fr 1fr; }
.form-row.cols-1 { grid-template-columns:1fr; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-label { font-size:12px; font-weight:600; color:var(--muted); }
.form-label .req { color:var(--red); margin-left:2px; }
.form-control { height:36px; border:1px solid var(--border); border-radius:5px; padding:0 10px; font-size:13px; color:var(--text); background:var(--white); outline:none; transition:border-color .15s; width:100%; }
.form-control:focus { border-color:var(--blue); box-shadow:0 0 0 2px rgba(74,144,217,.12); }
.form-control.readonly-field { background:#f8f9fa; color:#666; }
select.form-control { cursor:pointer; }
textarea.form-control { height:72px; padding:8px 10px; resize:vertical; }
.pan-hint { font-size:11px; color:var(--blue); margin-top:3px; }
.form-divider { border:none; border-top:1px solid var(--border); margin:4px 0 20px; }

.radio-group { display:flex; align-items:center; gap:20px; height:36px; }
.radio-item { display:flex; align-items:center; gap:6px; cursor:pointer; }
.radio-item input[type="radio"] { accent-color:var(--blue); width:15px; height:15px; }
.radio-item label { font-size:13px; color:var(--text); cursor:pointer; }

.section-label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:14px; }
.attach-box { border:1px solid var(--border); border-radius:6px; padding:14px 16px; }
.attach-btn { display:inline-flex; align-items:center; gap:6px; height:34px; padding:0 14px; border:1px solid var(--border); border-radius:5px; background:var(--white); color:#444; font-size:12px; cursor:pointer; }
.attach-btn:hover { background:#f5f6f8; }
.attach-hint { font-size:11px; color:var(--muted); margin-top:8px; }
.ty-row { display:flex; align-items:center; gap:8px; margin-top:16px; }
.ty-row input[type="checkbox"] { accent-color:var(--blue); width:15px; height:15px; }
.ty-row label { font-size:13px; color:var(--text); cursor:pointer; }

.pay-footer { display:flex; align-items:center; gap:10px; padding:16px 24px; border-top:1px solid var(--border); background:#fafbfc; }
.btn-save-paid { height:36px; padding:0 20px; background:var(--blue); color:#fff; border:none; border-radius:5px; font-size:13px; font-weight:600; cursor:pointer; }
.btn-save-paid:hover { background:var(--blue2); }
.btn-save-draft { height:36px; padding:0 20px; background:var(--white); color:#444; border:1px solid var(--border); border-radius:5px; font-size:13px; font-weight:500; cursor:pointer; }
.btn-save-draft:hover { background:#f5f6f8; }
.btn-cancel { height:36px; padding:0 16px; background:none; color:var(--red); border:none; font-size:13px; cursor:pointer; margin-left:auto; text-decoration:none; display:flex; align-items:center; }
.btn-cancel:hover { text-decoration:underline; }

.cust-shortcut { position:fixed; top:calc(var(--topbar-h) + 20px); right:0; background:var(--navy); color:#fff; padding:10px 14px 10px 16px; border-radius:8px 0 0 8px; font-size:12px; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:-2px 2px 8px rgba(0,0,0,.15); z-index:300; }
</style>
</head>
<body>

<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 20 20" width="18" height="18" fill="#fff"><rect x="1" y="1" width="8" height="8" rx="1.5"/><rect x="11" y="1" width="8" height="8" rx="1.5"/><rect x="1" y="11" width="8" height="8" rx="1.5"/><rect x="11" y="11" width="8" height="8" rx="1.5"/></svg>
        Inventory
    </div>
    <div class="topbar-search">
        <svg width="13" height="13" fill="none" stroke="#6b8097" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
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

    <nav class="sidenav">
        <div class="snav-section">
            <a href="#" class="snav-item">Home</a>
            <a href="#" class="snav-item">Items</a>
            <a href="#" class="snav-item">Inventory</a>
        </div>
        <div class="snav-section">
            <div class="snav-label">Sales</div>
            <a href="#" class="snav-item">Customers</a>
            <a href="#" class="snav-item">Sales Orders</a>
            <a href="{{ route('invoices.index') }}" class="snav-item active">Invoices</a>
            <a href="#" class="snav-item">Delivery Challans</a>
            <a href="{{ route('payments_records.index') }}" class="snav-item">Payments Received</a>
            <a href="#" class="snav-item">Sales Returns</a>
            <a href="#" class="snav-item">Credit Notes</a>
        </div>
        <div class="snav-section">
            <div class="snav-label">Purchases</div>
            <div class="snav-label">Reports</div>
        </div>
    </nav>

    <div class="main-content">

        <div class="cust-shortcut">
            {{ $invoice->customer->display_name ?? 'Customer' }}'s De…
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </div>

        <form method="POST" action="{{ route('invoices.payment.store', $invoice->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="pay-card">

            <div class="pay-header">
                <div class="pay-title">Payment for {{ $invoice->invoice_number }}</div>
                <span class="pay-inv-badge">{{ $invoice->invoice_number }}</span>
            </div>

            <div class="balance-banner">
                <span class="bb-label">Balance Due</span>
                <span class="bb-amount">₹{{ number_format($balanceDue, 2) }}</span>
            </div>

            <div class="pay-body">

                @if($errors->any())
                <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:13px;">
                    {{ $errors->first() }}
                </div>
                @endif

                @if(session('error'))
                <div style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:13px;">
                    {{ session('error') }}
                </div>
                @endif

                {{-- Row 1: Customer Name | Payment # --}}
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Customer Name<span class="req">*</span></label>
                        <input type="text" class="form-control readonly-field"
                               value="{{ $invoice->customer->display_name ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment #<span class="req">*</span></label>
                        <div style="display:flex;gap:6px;align-items:center;">
                            <input type="text" name="payment_number" class="form-control"
                                   value="{{ old('payment_number', $paymentNumber) }}" required style="flex:1;">
                            <button type="button" title="Refresh number"
                                    style="width:34px;height:36px;border:1px solid var(--border);border-radius:5px;background:#f8f9fa;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="14" height="14" fill="none" stroke="#666" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Location --}}
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <select name="location_id" class="form-control">
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

                <hr class="form-divider">

                {{-- Row 3: Amount | Bank Charges --}}
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label" style="color:var(--red);">Amount Received (INR)<span class="req">*</span></label>
                        <input type="number" name="amount_received" id="amountInput"
                               class="form-control" step="0.01" min="0.01"
                               max="{{ $balanceDue }}"
                               value="{{ old('amount_received', number_format($balanceDue, 2, '.', '')) }}"
                               required style="border-color:var(--blue);">
                        @if($invoice->customer->pan_number ?? null)
                        <div class="pan-hint">PAN: {{ $invoice->customer->pan_number }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bank Charges (if any)</label>
                        <input type="number" name="bank_charges" class="form-control"
                               step="0.01" min="0" value="0" placeholder="0.00">
                    </div>
                </div>

                {{-- Row 4: Tax deducted --}}
                <div class="form-row cols-1">
                    <div class="form-group">
                        <label class="form-label">Tax deducted?</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" name="tax_deducted" id="no_tax" value="no" checked>
                                <label for="no_tax">No Tax deducted</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="tax_deducted" id="yes_tds" value="tds">
                                <label for="yes_tds">Yes, TDS (Income Tax)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                {{-- Row 5: Payment Date | Payment Mode --}}
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label" style="color:var(--red);">Payment Date<span class="req">*</span></label>
                        <input type="date" name="payment_date" class="form-control"
                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" class="form-control" required>
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
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Payment Received On</label>
                        <input type="date" name="payment_received_on" class="form-control"
                               value="{{ old('payment_received_on') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color:var(--red);">Deposit To<span class="req">*</span></label>
                        <select name="deposit_to" class="form-control" required>
                            <option value="Petty Cash" selected>Petty Cash</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Savings Account">Savings Account</option>
                            <option value="Current Account">Current Account</option>
                        </select>
                    </div>
                </div>

                {{-- Row 7: Reference | Notes --}}
                <div class="form-row cols-2">
                    <div class="form-group">
                        <label class="form-label">Reference#</label>
                        <input type="text" name="reference" class="form-control"
                               value="{{ old('reference') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Attachments --}}
                <hr class="form-divider">
                <div class="section-label">Attachments</div>
                <div class="attach-box">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button type="button" class="attach-btn"
                                onclick="document.getElementById('file-upload').click()">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            Upload File
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <input type="file" id="file-upload" name="attachments[]"
                               multiple style="display:none" onchange="showFileNames(this)">
                        <span id="file-names" style="font-size:12px;color:#888;"></span>
                    </div>
                    <div class="attach-hint">You can upload a maximum of 5 files, 5MB each</div>
                </div>

                <div class="ty-row">
                    <input type="checkbox" id="thankyou" name="send_thankyou" value="1">
                    <label for="thankyou">Send a "Thank you" note for this payment</label>
                </div>

            </div>{{-- end pay-body --}}

            <div class="pay-footer">
                <button type="submit" name="action" value="draft" class="btn-save-draft">
                    Save as Draft
                </button>
                <button type="submit" name="action" value="paid" class="btn-save-paid">
                    Save as Paid
                </button>
                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn-cancel">
                    Cancel
                </a>
            </div>

        </div>{{-- end pay-card --}}
        </form>

    </div>
</div>

<script>
function showFileNames(input) {
    const names = Array.from(input.files).map(f => f.name).join(', ');
    document.getElementById('file-names').textContent = names || '';
}
</script>
</body>
</html>