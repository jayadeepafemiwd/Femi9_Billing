<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts | Preferences | Settings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Zoho Puvi', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 13px;
            color: #333;
            background: #f5f5f5;
        }

        /* ─── TOP BROWSER BAR SIMULATION ─── */
        .browser-bar {
            background: #2d2d2d;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ccc;
            font-size: 12px;
        }
        .browser-bar .url-bar {
            background: #1a1a1a;
            border-radius: 4px;
            padding: 4px 12px;
            color: #aaa;
            flex: 1;
            font-size: 12px;
        }

        /* ─── MAIN LAYOUT ─── */
        .app-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #fff;
        }

        /* ─── TOP HEADER ─── */
        .top-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-icon {
            width: 32px;
            height: 32px;
            background: #e8f0fe;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-icon svg {
            width: 20px;
            height: 20px;
        }
        .back-arrow {
            cursor: pointer;
            color: #666;
            font-size: 16px;
            padding: 4px;
        }
        .header-title h1 {
            font-size: 15px;
            font-weight: 600;
            color: #222;
        }
        .header-title p {
            font-size: 11px;
            color: #888;
        }
        .search-settings {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 6px 16px;
            min-width: 260px;
            cursor: pointer;
        }
        .search-settings svg {
            color: #999;
        }
        .search-settings span {
            color: #999;
            font-size: 13px;
        }
        .close-settings-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }
        .close-settings-btn .x-icon {
            color: #e53935;
            font-weight: bold;
            font-size: 16px;
        }

        /* ─── BODY LAYOUT ─── */
        .settings-body {
            display: flex;
            flex: 1;
        }

        /* ─── LEFT SIDEBAR ─── */
        .sidebar {
            width: 220px;
            min-width: 220px;
            background: #fff;
            border-right: 1px solid #e8e8e8;
            padding: 16px 0;
            overflow-y: auto;
        }
        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            color: #999;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 8px 16px 4px;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 13px;
            color: #444;
            border-left: 3px solid transparent;
            transition: background 0.15s;
        }
        .sidebar-item:hover {
            background: #f5f7ff;
        }
        .sidebar-item.has-children {
            font-weight: 500;
        }
        .sidebar-item .chevron {
            font-size: 10px;
            color: #aaa;
        }
        .sidebar-item.expanded .chevron {
            transform: rotate(90deg);
        }
        .sidebar-sub-item {
            padding: 7px 16px 7px 28px;
            cursor: pointer;
            font-size: 13px;
            color: #555;
            border-left: 3px solid transparent;
            transition: background 0.15s;
        }
        .sidebar-sub-item:hover {
            background: #f5f7ff;
        }
        .sidebar-sub-item.active {
            background: #e8f0fe;
            border-left-color: #1a73e8;
            color: #1a73e8;
            font-weight: 600;
        }

        /* ─── MAIN CONTENT ─── */
        .main-content {
            flex: 1;
            padding: 0;
            overflow-y: auto;
        }

        /* ─── PAGE HEADER ─── */
        .page-header {
            padding: 20px 32px 0;
        }
        .page-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #222;
            margin-bottom: 16px;
        }

        /* ─── TABS ─── */
        .tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e8e8e8;
            margin-bottom: 0;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            font-size: 13px;
            color: #666;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: color 0.15s;
        }
        .tab:hover {
            color: #1a73e8;
        }
        .tab.active {
            color: #1a73e8;
            border-bottom-color: #1a73e8;
            font-weight: 600;
        }

        /* ─── FORM CONTENT ─── */
        .form-content {
            padding: 24px 32px 40px;
        }

        /* ─── FORM SECTIONS ─── */
        .form-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid #f0f0f0;
        }
        .form-section:last-of-type {
            border-bottom: none;
        }

        /* ─── CHECKBOX ROW ─── */
        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 0;
        }
        .checkbox-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #1a73e8;
            cursor: pointer;
        }
        .checkbox-row label {
            font-size: 13px;
            color: #333;
            cursor: pointer;
        }

        /* ─── SECTION TITLE ─── */
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            margin-bottom: 6px;
        }
        .section-desc {
            font-size: 12px;
            color: #888;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        /* ─── WARNING BOX ─── */
        .warning-box {
            background: #fff8f0;
            border: 1px solid #ffe0b2;
            border-radius: 6px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
            font-size: 12px;
            color: #555;
        }
        .warning-box .warn-icon {
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ─── RADIO GROUP ─── */
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .radio-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .radio-row input[type="radio"] {
            width: 15px;
            height: 15px;
            accent-color: #1a73e8;
            cursor: pointer;
        }
        .radio-row label {
            font-size: 13px;
            color: #333;
            cursor: pointer;
        }

        /* ─── TOGGLE SWITCH ─── */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .toggle-label-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .toggle-label {
            font-size: 14px;
            font-weight: 600;
            color: #222;
        }
        .toggle-desc {
            font-size: 12px;
            color: #888;
        }
        .toggle-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .toggle-status {
            font-size: 12px;
            color: #888;
        }
        .toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #ccc;
            border-radius: 22px;
            transition: 0.3s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }
        .toggle-switch input:checked + .slider {
            background: #1a73e8;
        }
        .toggle-switch input:checked + .slider:before {
            transform: translateX(18px);
        }

        /* ─── ADDRESS FORMAT BOX ─── */
        .address-format-label {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            margin-bottom: 4px;
        }
        .address-format-sublabel {
            font-size: 12px;
            color: #999;
            margin-left: 6px;
            font-weight: normal;
        }
        .placeholder-dropdown {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e0e0e0;
            background: #fafafa;
            border-radius: 5px 5px 0 0;
            padding: 7px 14px;
            font-size: 12px;
            color: #555;
            cursor: pointer;
            margin-bottom: 0;
        }
        .placeholder-dropdown:hover {
            background: #f0f0f0;
        }
        .address-textarea {
            width: 100%;
            max-width: 820px;
            min-height: 130px;
            padding: 12px 14px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
            color: #333;
            resize: vertical;
            background: #fff;
            line-height: 1.7;
            outline: none;
            transition: border-color 0.2s;
        }
        .address-textarea:focus {
            border-color: #1a73e8;
        }
        .address-format-wrapper {
            max-width: 820px;
            margin-bottom: 28px;
        }
        .address-format-wrapper .placeholder-dropdown {
            border-radius: 5px 5px 0 0;
        }

        /* ─── SAVE BUTTON ─── */
        .save-btn {
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 9px 28px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }
        .save-btn:hover {
            background: #1558b0;
        }
        .save-btn:active {
            background: #104d9d;
        }

        /* ─── RIGHT SIDEBAR ICONS ─── */
        .right-icons {
            width: 40px;
            background: #fff;
            border-left: 1px solid #e8e8e8;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 0;
            gap: 16px;
        }
        .right-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #666;
            font-size: 14px;
        }
        .right-icon.orange {
            background: #ff6d00;
            color: #fff;
        }

        /* ─── SCROLL INDICATOR ─── */
        .scroll-indicator {
            position: absolute;
            right: 56px;
            top: 320px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .scroll-up, .scroll-down {
            width: 18px;
            height: 18px;
            background: #e8e8e8;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 10px;
        }

        /* ─── SEPARATOR ─── */
        hr.section-sep {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 0 0 28px 0;
        }
    </style>
</head>
<body>

<div class="app-wrapper">

    <!-- TOP HEADER -->
    <div class="top-header">
        <div class="top-header-left">
            <!-- Logo -->
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="8" height="8" rx="1.5" fill="#e53935"/>
                    <rect x="13" y="3" width="8" height="8" rx="1.5" fill="#e53935" opacity="0.5"/>
                    <rect x="3" y="13" width="8" height="8" rx="1.5" fill="#e53935" opacity="0.5"/>
                    <rect x="13" y="13" width="8" height="8" rx="1.5" fill="#e53935" opacity="0.3"/>
                </svg>
            </div>
            <span class="back-arrow">&#8249;</span>
            <div class="header-title">
                <h1>All Settings</h1>
                <p>Techvolt</p>
            </div>
        </div>

        <!-- Search -->
        <div class="search-settings">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <span>Search settings &nbsp;( / )</span>
        </div>

        <!-- Close -->
        <button class="close-settings-btn">
            Close Settings <span class="x-icon">&#10005;</span>
        </button>
    </div>

    <!-- SETTINGS BODY -->
    <div class="settings-body">

        <!-- LEFT SIDEBAR -->
        <nav class="sidebar">
            <div class="sidebar-section-label">ORGANIZATION SETTINGS</div>
            <div class="sidebar-item has-children">Organization <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item has-children">Users &amp; Roles <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item has-children">Taxes &amp; Compliance <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item has-children">Setup &amp; Configurations <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item has-children">Customization <span class="chevron">&#8250;</span></div>
            <div class="sidebar-item has-children">Automation <span class="chevron">&#8250;</span></div>

            <div class="sidebar-section-label" style="margin-top:12px;">MODULE SETTINGS</div>
            <div class="sidebar-item has-children expanded">General <span class="chevron" style="transform:rotate(90deg);">&#8250;</span></div>
            <div class="sidebar-sub-item active">Customers and Vendors</div>
            <div class="sidebar-sub-item">Items</div>
            <div class="sidebar-item has-children">Inventory <span class="chevron">&#8250;</span></div>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="page-header">
                <h2>Customers and Vendors</h2>

                <!-- TABS -->
                <div class="tabs">
                    <div class="tab active">General</div>
                    <div class="tab">Field Customization</div>
                    <div class="tab">Custom Buttons</div>
                    <div class="tab">Related Lists</div>
                </div>
            </div>

            <!-- FORM -->
            <form class="form-content" action="<?php echo e(route('customers-vendors.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <!-- ── SECTION 1: Duplicates ── -->
                <div class="form-section">
                    <div class="checkbox-row">
                        <input type="checkbox" id="allow_duplicates" name="allow_duplicates" value="1"
    <?php echo e(old('allow_duplicates', $config['allow_duplicates'] ?? false) ? 'checked' : ''); ?>>
                        <label for="allow_duplicates">Allow duplicates for customer and vendor display name.</label>
                    </div>
                </div>

                <!-- ── SECTION 2: Customer & Vendor Numbers ── -->
                <div class="form-section">
                    <div class="section-title">Customer &amp; Vendor Numbers</div>
                    <div class="section-desc">
                        Generate customer and vendor numbers automatically. You can configure the series in which
                        numbers are generated while creating new records.
                    </div>

                    <div class="checkbox-row">
                        <input type="checkbox" id="enable_customer_numbers" name="enable_customer_numbers" value="1"
    <?php echo e(old('enable_customer_numbers', $config['enable_customer_numbers'] ?? false) ? 'checked' : ''); ?>>
                        <label for="enable_customer_numbers">Enable Customer Numbers</label>
                    </div>

                    <div class="checkbox-row" style="margin-top:8px;">
                       <input type="checkbox" id="enable_vendor_numbers" name="enable_vendor_numbers" value="1"
    <?php echo e(old('enable_vendor_numbers', $config['enable_vendor_numbers'] ?? false) ? 'checked' : ''); ?>>
                        <label for="enable_vendor_numbers">Enable Vendor Numbers</label>
                    </div>

                    <div class="warning-box">
                        <span class="warn-icon">⚠️</span>
                        <span>Once you've enabled this feature, you cannot disable it.</span>
                    </div>
                </div>

                <!-- ── SECTION 3: Default Customer Type ── -->
                <div class="form-section">
                    <div class="section-title">Default Customer Type</div>
                    <div class="section-desc">
                        Select the default customer type based on the kind of customers you usually sell your products
                        or services to. The default customer type will be pre-selected in the customer creation form.
                    </div>

                    <div class="radio-group">
                        <div class="radio-row">
                            <input type="radio" id="type_business" name="default_customer_type" value="business"
    <?php echo e(old('default_customer_type', $config['default_customer_type'] ?? 'business') === 'business' ? 'checked' : ''); ?>>
                            <label for="type_business">Business</label>
                        </div>
                        <div class="radio-row">
                            <input type="radio" id="type_individual" name="default_customer_type" value="individual"
    <?php echo e(old('default_customer_type', $config['default_customer_type'] ?? 'business') === 'individual' ? 'checked' : ''); ?>>
                            <label for="type_individual">Individual</label>
                        </div>
                    </div>
                </div>

                <!-- ── SECTION 4: Customer Credit Limit ── -->
                <div class="form-section">
                    <div class="toggle-row">
                        <div class="toggle-label-group">
                            <span class="toggle-label">Customer Credit Limit</span>
                            <span class="toggle-desc">
                                Credit Limit enables you to set limit on the outstanding receivable amount of the customers.
                            </span>
                        </div>
                        <div class="toggle-right">
                            <span class="toggle-status" id="credit-limit-status">Disabled</span>
                            <label class="toggle-switch">
                               <input type="checkbox" id="customer_credit_limit" name="customer_credit_limit" value="1"
    <?php echo e(old('customer_credit_limit', $config['customer_credit_limit'] ?? false) ? 'checked' : ''); ?>

    onchange="updateToggleStatus(this, 'credit-limit-status')">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ── SECTION 5: Billing Address Format ── -->
                <div class="form-section">
                    <div class="address-format-label">
                        Customer and Vendor Billing Address Format
                        <span class="address-format-sublabel">(Displayed in PDF only) &#9432;</span>
                    </div>
                    <br>
                    <div class="address-format-wrapper">
                        <div class="placeholder-dropdown">Insert Placeholders &#9660;</div>
                        <textarea name="billing_address_format" id="billing_address_format" rows="6" class="address-textarea"><?php echo e(old('billing_address_format', $config['billing_address_format'] ?? '${CONTACT.CONTACT_DISPLAYNAME}
${CONTACT.CONTACT_ADDRESS}
${CONTACT.CONTACT_CITY}
${CONTACT.CONTACT_CODE} ${CONTACT.CONTACT_STATE}
${CONTACT.CONTACT_COUNTRY}')); ?></textarea>
                    </div>
                </div>

                <!-- ── SECTION 6: Shipping Address Format ── -->
                <div class="form-section">
                    <div class="address-format-label">
                        Customer and Vendor Shipping Address Format
                        <span class="address-format-sublabel">(Displayed in PDF only) &#9432;</span>
                    </div>
                    <br>
                    <div class="address-format-wrapper">
                        <div class="placeholder-dropdown">Insert Placeholders &#9660;</div>
                        <textarea name="shipping_address_format" id="shipping_address_format" rows="6" class="address-textarea"><?php echo e(old('shipping_address_format', $config['shipping_address_format'] ?? '${CONTACT.CONTACT_ADDRESS}
${CONTACT.CONTACT_CITY}
${CONTACT.CONTACT_CODE} ${CONTACT.CONTACT_STATE}
${CONTACT.CONTACT_COUNTRY}')); ?></textarea>
                    </div>
                </div>

                <!-- ── SAVE BUTTON ── -->
                <button type="submit" class="save-btn">Save</button>

            </form>
        </div>

        <!-- RIGHT ICON PANEL -->
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
            <div class="right-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                </svg>
            </div>
        </div>

    </div><!-- end settings-body -->
</div><!-- end app-wrapper -->

<script>
    // Toggle status label
    function updateToggleStatus(checkbox, statusId) {
        const statusEl = document.getElementById(statusId);
        statusEl.textContent = checkbox.checked ? 'Enabled' : 'Disabled';
    }

    // Tab switching
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Sidebar item expand/collapse
    document.querySelectorAll('.sidebar-item.has-children').forEach(item => {
        item.addEventListener('click', function () {
            this.classList.toggle('expanded');
        });
    });

    // Sidebar sub-item active
    document.querySelectorAll('.sidebar-sub-item').forEach(item => {
        item.addEventListener('click', function () {
            document.querySelectorAll('.sidebar-sub-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>

</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/customer_setting_handle/create.blade.php ENDPATH**/ ?>