<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoices | Preferences | Settings</title>
    <style>
        /* ── RESET ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 13px;
            color: #333;
            background: #f5f5f5;
        }

        .app-wrapper { display: flex; flex-direction: column; min-height: 100vh; background: #fff; }

        /* ── TOP HEADER ── */
        .top-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 20px; background: #fff; border-bottom: 1px solid #e8e8e8;
            position: sticky; top: 0; z-index: 100;
        }
        .top-header-left { display: flex; align-items: center; gap: 12px; }
        .logo-icon {
            width: 32px; height: 32px; background: #e8f0fe;
            border-radius: 4px; display: flex; align-items: center; justify-content: center;
        }
        .back-arrow { cursor: pointer; color: #666; font-size: 18px; padding: 4px; }
        .header-title h1 { font-size: 15px; font-weight: 600; color: #222; }
        .header-title p  { font-size: 11px; color: #888; }
        .search-settings {
            display: flex; align-items: center; gap: 8px;
            background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 6px;
            padding: 6px 16px; min-width: 260px; cursor: pointer;
        }
        .search-settings span { color: #999; font-size: 13px; }
        .close-settings-btn {
            display: flex; align-items: center; gap: 6px;
            background: none; border: none; cursor: pointer;
            font-size: 13px; color: #333; font-weight: 500;
        }
        .close-settings-btn .x-icon { color: #e53935; font-weight: bold; font-size: 16px; }

        /* ── BODY ── */
        .settings-body { display: flex; flex: 1; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 220px; min-width: 220px; background: #fff;
            border-right: 1px solid #e8e8e8; padding: 16px 0; overflow-y: auto;
        }
        .sidebar-section-label {
            font-size: 10px; font-weight: 700; color: #999;
            letter-spacing: 0.8px; text-transform: uppercase; padding: 8px 16px 4px;
        }
        .sidebar-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 16px; cursor: pointer; font-size: 13px; color: #444;
            border-left: 3px solid transparent;
        }
        .sidebar-item:hover { background: #f5f7ff; }
        .sidebar-item .chevron { font-size: 10px; color: #aaa; }
        .sidebar-sub-item {
            padding: 7px 16px 7px 28px; cursor: pointer; font-size: 13px;
            color: #555; border-left: 3px solid transparent;
        }
        .sidebar-sub-item:hover { background: #f5f7ff; }
        .sidebar-sub-item.active {
            background: #e8f0fe; border-left-color: #1a73e8;
            color: #1a73e8; font-weight: 600;
        }

        /* ── MAIN CONTENT ── */
        .main-content { flex: 1; overflow-y: auto; }
        .page-header { padding: 20px 32px 0; }
        .page-header h2 { font-size: 18px; font-weight: 600; color: #222; margin-bottom: 16px; }

        /* ── TABS ── */
        .tabs { display: flex; border-bottom: 2px solid #e8e8e8; }
        .tab {
            padding: 10px 20px; cursor: pointer; font-size: 13px;
            color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px;
        }
        .tab:hover { color: #1a73e8; }
        .tab.active { color: #1a73e8; border-bottom-color: #1a73e8; font-weight: 600; }

        /* ── FORM CONTENT ── */
        .form-content { padding: 24px 32px 40px; max-width: 860px; }

        .form-section {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f0f0f0;
        }
        .form-section:last-of-type { border-bottom: none; }

        .section-title { font-size: 14px; font-weight: 600; color: #222; margin-bottom: 12px; }

        /* ── CUSTOM CHECKBOX ── */
        .checkbox-row { display: flex; align-items: flex-start; gap: 10px; padding: 5px 0; }

        .checkbox-label {
            display: flex; align-items: flex-start; gap: 10px;
            cursor: pointer; font-size: 13px; color: #333; user-select: none;
        }
        .checkbox-label input[type="checkbox"] { display: none; }

        .checkmark {
            width: 15px; height: 15px; min-width: 15px;
            border: 1.5px solid #bbb; border-radius: 3px;
            background: #fff; margin-top: 1px;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s, border-color 0.15s;
        }
        .checkbox-label input[type="checkbox"]:checked + .checkmark {
            background: #1a73e8; border-color: #1a73e8;
        }
        .checkbox-label input[type="checkbox"]:checked + .checkmark::after {
            content: '';
            display: block;
            width: 4px; height: 7px;
            border: 2px solid #fff;
            border-top: none; border-left: none;
            transform: rotate(45deg) translate(-1px,-1px);
        }

        /* ── CUSTOM RADIO ── */
        .radio-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-label {
            display: flex; align-items: center; gap: 10px;
            cursor: pointer; font-size: 13px; color: #333; user-select: none;
        }
        .radio-label input[type="radio"] { display: none; }
        .radiomark {
            width: 15px; height: 15px; min-width: 15px;
            border: 1.5px solid #bbb; border-radius: 50%; background: #fff;
            display: flex; align-items: center; justify-content: center;
            transition: border-color 0.15s;
        }
        .radio-label input[type="radio"]:checked + .radiomark { border-color: #1a73e8; }
        .radio-label input[type="radio"]:checked + .radiomark::after {
            content: ''; display: block;
            width: 7px; height: 7px;
            border-radius: 50%; background: #1a73e8;
        }

        /* ── TOGGLE SWITCH ── */
        .toggle-row {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 20px;
        }
        .toggle-label-group { flex: 1; }
        .toggle-title { font-size: 14px; font-weight: 600; color: #222; margin-bottom: 4px; }
        .toggle-desc  { font-size: 12px; color: #888; line-height: 1.6; }
        .toggle-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .toggle-status-text { font-size: 12px; color: #888; white-space: nowrap; }

        .toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
        .toggle-switch input { display: none; }
        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #ccc; border-radius: 22px; transition: 0.3s;
        }
        .slider::before {
            position: absolute; content: "";
            height: 16px; width: 16px; left: 3px; bottom: 3px;
            background: white; border-radius: 50%; transition: 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .toggle-switch input:checked + .slider { background: #1a73e8; }
        .toggle-switch input:checked + .slider::before { transform: translateX(18px); }

        /* ── TEXTAREA ── */
        .setting-textarea {
            width: 100%; min-height: 110px;
            border: 1px solid #ddd; border-radius: 6px;
            padding: 10px 12px; font-size: 13px;
            font-family: inherit; color: #333;
            background: #fff; resize: vertical; outline: none;
            transition: border-color 0.15s;
        }
        .setting-textarea:focus { border-color: #1a73e8; }

        /* ── SAVE BUTTON ── */
        .btn-save {
            background: #1a73e8; color: #fff;
            border: none; border-radius: 5px;
            padding: 9px 28px; font-size: 13px;
            font-weight: 600; cursor: pointer;
            transition: background 0.2s;
        }
        .btn-save:hover    { background: #1558b0; }
        .btn-save:disabled { background: #90baf9; cursor: not-allowed; }

        /* ── ALERTS ── */
        .alert {
            padding: 10px 16px; border-radius: 6px;
            margin-bottom: 16px; font-size: 13px;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .alert-success { background: #e6f4ea; border: 1px solid #a8d5b5; color: #1e7e34; }
        .alert-danger  { background: #fdecea; border: 1px solid #f5c6cb; color: #c0392b; }
        .alert-info    { background: #e8f0fe; border: 1px solid #c5d8fb; color: #1a55a0; }
        .btn-close-alert {
            margin-left: auto; background: none; border: none;
            cursor: pointer; font-size: 16px; color: inherit; line-height: 1;
        }

        /* ── RIGHT ICONS ── */
        .right-icons {
            width: 44px; background: #fff; border-left: 1px solid #e8e8e8;
            display: flex; flex-direction: column; align-items: center;
            padding: 12px 0; gap: 16px;
        }
        .right-icon {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: #666;
        }
        .right-icon.orange { background: #ff6d00; color: #fff; font-weight: bold; }
    </style>
</head>
<body>
<div class="app-wrapper">

    {{-- ── TOP HEADER ── --}}
    <div class="top-header">
        <div class="top-header-left">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none">
                    <rect x="3"  y="3"  width="8" height="8" rx="1.5" fill="#e53935"/>
                    <rect x="13" y="3"  width="8" height="8" rx="1.5" fill="#e53935" opacity="0.5"/>
                    <rect x="3"  y="13" width="8" height="8" rx="1.5" fill="#e53935" opacity="0.5"/>
                    <rect x="13" y="13" width="8" height="8" rx="1.5" fill="#e53935" opacity="0.3"/>
                </svg>
            </div>
            <span class="back-arrow">&#8249;</span>
            <div class="header-title">
                <h1>All Settings</h1>
                <p>{{ config('app.name', 'My App') }}</p>
            </div>
        </div>
        <div class="search-settings">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <span>Search settings &nbsp;( / )</span>
        </div>
        <button type="button" class="close-settings-btn">
            Close Settings <span class="x-icon">&#10005;</span>
        </button>
    </div>

    {{-- ── BODY ── --}}
    <div class="settings-body">

        {{-- ── SIDEBAR ── --}}
        <nav class="sidebar">
            <div class="sidebar-section-label">ORGANIZATION SETTINGS</div>
            <div class="sidebar-item">Organization <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item">Users &amp; Roles <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item">Taxes &amp; Compliance <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item">Setup &amp; Configurations <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item">Customization <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item">Automation <span class="chevron">&#8250;</span></div>

            <div class="sidebar-section-label" style="margin-top:12px;">MODULE SETTINGS</div>
            <div class="sidebar-item">
                General <span class="chevron" style="transform:rotate(90deg)">&#8250;</span>
            </div>
            <div class="sidebar-sub-item">
                <a href="{{ route('setting_handle.create', ['from' => 'products']) }}"
                   style="text-decoration:none;color:inherit;">Items</a>
            </div>
            <div class="sidebar-sub-item">
                <a href="{{ route('customers-vendors.create') }}"
                   style="text-decoration:none;color:inherit;">Customers and Vendors</a>
            </div>
            <div class="sidebar-item">Inventory <span class="chevron">&#8250;</span></div>
            <div class="sidebar-sub-item active">Invoices</div>
        </nav>

        {{-- ── MAIN CONTENT ── --}}
        <div class="main-content">
            <div class="page-header">
                <h2>Invoices</h2>
                <div class="tabs">
                    <div class="tab active" data-tab="general">General</div>
                    <div class="tab" data-tab="approvals">Approvals</div>
                    <div class="tab" data-tab="field-customization">Field Customization</div>
                    <div class="tab" data-tab="validation-rules">Validation Rules</div>
                    <div class="tab" data-tab="record-locking">Record Locking</div>
                    <div class="tab" data-tab="custom-buttons">Custom Buttons</div>
                    <div class="tab" data-tab="related-lists">Related Lists</div>
                </div>
            </div>

            <div class="form-content">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        ✓ {{ session('success') }}
                        <button class="btn-close-alert" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif
                @if(session('last_saved'))
                    <div class="alert alert-info">
                        🕐 Last saved: {{ session('last_saved') }}
                        <button class="btn-close-alert" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        ✕ {{ session('error') }}
                        <button class="btn-close-alert" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <div>
                            @foreach($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                        <button class="btn-close-alert" onclick="this.parentElement.remove()">×</button>
                    </div>
                @endif

                <form action="{{ route('invoice_setting.store') }}" method="POST">
                    @csrf

                    {{-- ── SECTION 1: General ── --}}
                    <div class="form-section">
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="allow_editing_sent_invoice"
                                       value="1"
                                       {{ old('allow_editing_sent_invoice', $config['allow_editing_sent_invoice'] ?? true) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Allow editing of Sent Invoice?
                            </label>
                        </div>
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="associate_expense_receipts"
                                       value="1"
                                       {{ old('associate_expense_receipts', $config['associate_expense_receipts'] ?? false) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Associate and display expense receipts in Invoice PDF
                            </label>
                        </div>
                    </div>

                    {{-- ── SECTION 2: Invoice Order Number ── --}}
                    <div class="form-section">
                        <div class="section-title">Invoice Order Number</div>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio"
                                       name="invoice_order_number"
                                       value="sales_order_number"
                                       {{ old('invoice_order_number', $config['invoice_order_number'] ?? 'sales_order_number') === 'sales_order_number' ? 'checked' : '' }}>
                                <span class="radiomark"></span>
                                Use Sales Order Number
                            </label>
                            <label class="radio-label">
                                <input type="radio"
                                       name="invoice_order_number"
                                       value="sales_order_reference_number"
                                       {{ old('invoice_order_number', $config['invoice_order_number'] ?? '') === 'sales_order_reference_number' ? 'checked' : '' }}>
                                <span class="radiomark"></span>
                                Use Sales Order Reference Number
                            </label>
                        </div>
                    </div>

                    {{-- ── SECTION 3: Payments ── --}}
                    <div class="form-section">
                        <div class="section-title">Payments</div>
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="notify_online_payment"
                                       value="1"
                                       {{ old('notify_online_payment', $config['notify_online_payment'] ?? true) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Get notified when customers pay online
                            </label>
                        </div>
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="include_payment_receipt_thank_you"
                                       value="1"
                                       {{ old('include_payment_receipt_thank_you', $config['include_payment_receipt_thank_you'] ?? true) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Do you want to include the payment receipt along with the Thank You note?
                            </label>
                        </div>
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="automate_thank_you_note"
                                       value="1"
                                       {{ old('automate_thank_you_note', $config['automate_thank_you_note'] ?? true) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Automate thank you note to customer on receipt of online payment
                            </label>
                        </div>
                    </div>
                    
                        {{-- ── SECTION 3.5: Advance Payment ── --}}
<div class="form-section">
    <div class="checkbox-row">
        <label class="checkbox-label">
            <input type="checkbox"
                   name="advance_payment_enabled"
                   id="advance_payment_enabled"
                   value="1"
                   {{ old('advance_payment_enabled', $config['advance_payment_enabled'] ?? false) ? 'checked' : '' }}
                   onchange="toggleAdvancePayment(this)">
            <span class="checkmark"></span>
            <span style="font-size:14px; font-weight:600; color:#222;">Advance Payment</span>
        </label>
    </div>

    <div id="advance_payment_categories"
         style="margin-top:12px; margin-left:25px; display:{{ old('advance_payment_enabled', $config['advance_payment_enabled'] ?? false) ? 'block' : 'none' }};">
       <div style="
    border:1px solid #e0e0e0;
    border-radius:6px;
    background:#fafafa;
    padding:16px;
    height:220px;
    overflow-y:scroll;
">
            <p style="font-size:12px; color:#888; margin-bottom:12px; line-height:1.6;">
                Select the customer categories for which advance payment is applicable.
            </p>
            @forelse($userCategories ?? [] as $category)
                <div class="checkbox-row" style="padding:4px 0;">
                    <label class="checkbox-label">
                        <input type="checkbox"
                               name="advance_payment_categories[]"
                               value="{{ $category->id }}"
                               {{ in_array($category->id, old('advance_payment_categories', $config['advance_payment_categories'] ?? [])) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ $category->name }}
                        <span style="font-size:11px; color:#888; margin-left:4px;">({{ $category->code }})</span>
                    </label>
                </div>
            @empty
                <p style="font-size:12px; color:#aaa;">No categories found.</p>
            @endforelse
        </div>
    </div>
</div>
                    {{-- ── SECTION 4: Invoice QR Code ── --}}
                    <div class="form-section">
                        <div class="toggle-row">
                            <div class="toggle-label-group">
                                <div class="toggle-title">Invoice QR Code</div>
                                <div class="toggle-desc">
                                    Enable and configure the QR code you want to display on the PDF copy of an Invoice.
                                    Your customers can scan the QR code using their device to access the URL or other
                                    information that you configure.
                                </div>
                            </div>
                            <div class="toggle-right">
                                <span class="toggle-status-text" id="qrStatusText">
                                    {{ old('invoice_qr_code_enabled', $config['invoice_qr_code_enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                                </span>
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           name="invoice_qr_code_enabled"
                                           id="invoice_qr_code_enabled"
                                           value="1"
                                           {{ old('invoice_qr_code_enabled', $config['invoice_qr_code_enabled'] ?? false) ? 'checked' : '' }}
                                           onchange="updateToggleStatus(this, 'qrStatusText')">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                

                    {{-- ── SECTION 5: Zero-Value Line Items ── --}}
                    <div class="form-section">
                        <div class="section-title">Zero-Value Line Items</div>
                        <div class="checkbox-row">
                            <label class="checkbox-label">
                                <input type="checkbox"
                                       name="hide_zero_value_line_items"
                                       value="1"
                                       {{ old('hide_zero_value_line_items', $config['hide_zero_value_line_items'] ?? false) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Hide zero-value line items
                            </label>
                        </div>
                        <p style="font-size:12px;color:#888;margin-top:6px;margin-left:25px;line-height:1.6;">
                            Choose whether you want to hide zero-value line items in an invoice's PDF and the Customer Portal.
                            They will still be visible while editing an invoice.
                            This setting will not apply to invoices whose total is zero.
                        </p>
                    </div>

                    {{-- ── SECTION 6: Terms & Conditions ── --}}
                    <div class="form-section">
                        <div class="section-title">Terms &amp; Conditions</div>
                        <textarea name="terms_and_conditions"
                                  class="setting-textarea"
                                  placeholder="Enter your terms and conditions here...">{{ old('terms_and_conditions', $config['terms_and_conditions'] ?? '') }}</textarea>
                    </div>

                    {{-- ── SECTION 7: Customer Notes ── --}}
                    <div class="form-section" style="border-bottom:none;">
                        <div class="section-title">Customer Notes</div>
                        <textarea name="customer_notes"
                                  class="setting-textarea">{{ old('customer_notes', $config['customer_notes'] ?? 'Thanks for your business.') }}</textarea>
                    </div>

                    {{-- ── SAVE BUTTON ── --}}
                    <div style="margin-top:24px;">
                        <button type="submit" class="btn-save" id="saveBtn">Save</button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ── RIGHT ICONS ── --}}
        <div class="right-icons">
            <div class="right-icon orange">?</div>
            <div class="right-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <div class="right-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
            <div class="right-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        </div>

    </div>
</div>

<script>
    // ── Toggle label update ──
    function updateToggleStatus(checkbox, statusId) {
        document.getElementById(statusId).textContent = checkbox.checked ? 'Enabled' : 'Disabled';
    }

    // ── Tab switching ──
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ── Sidebar active state ──
    document.querySelectorAll('.sidebar-item, .sidebar-sub-item').forEach(item => {
        item.addEventListener('click', function () {
            if (this.querySelector('a')) return; // has link, let it navigate
            document.querySelectorAll('.sidebar-sub-item').forEach(i => i.classList.remove('active'));
            if (this.classList.contains('sidebar-sub-item')) {
                this.classList.add('active');
            }
        });
    });

    // ── Save button loading state ──
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.textContent = 'Saving…';
    });

    // ── Advance Payment toggle ──
function toggleAdvancePayment(checkbox) {
    const panel = document.getElementById('advance_payment_categories');
    panel.style.display = checkbox.checked ? 'block' : 'none';
}
</script>
</body>
</html>
