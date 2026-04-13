<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts | Preferences | Settings</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 13px;
            color: #333;
            background: #f5f5f5;
        }

        .app-wrapper { display: flex; flex-direction: column; min-height: 100vh; background: #fff; }

        /* ─── TOP HEADER ─── */
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

        /* ─── BODY ─── */
        .settings-body { display: flex; flex: 1; }

        /* ─── SIDEBAR ─── */
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

        /* ─── MAIN CONTENT ─── */
        .main-content { flex: 1; overflow-y: auto; }
        .page-header { padding: 20px 32px 0; }
        .page-header h2 { font-size: 18px; font-weight: 600; color: #222; margin-bottom: 16px; }

        /* ─── TABS ─── */
        .tabs { display: flex; border-bottom: 2px solid #e8e8e8; }
        .tab {
            padding: 10px 20px; cursor: pointer; font-size: 13px;
            color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px;
        }
        .tab:hover { color: #1a73e8; }
        .tab.active { color: #1a73e8; border-bottom-color: #1a73e8; font-weight: 600; }

        /* ─── FORM ─── */
        .form-content { padding: 24px 32px 40px; }

        .form-section {
            margin-bottom: 28px; padding-bottom: 28px;
            border-bottom: 1px solid #f0f0f0;
        }
        .form-section:last-of-type { border-bottom: none; }

        /* ─── CHECKBOX ─── */
        .checkbox-row { display: flex; align-items: flex-start; gap: 10px; padding: 4px 0; }
        .checkbox-row input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: #1a73e8;
            cursor: pointer; margin-top: 1px; flex-shrink: 0;
        }
        .checkbox-row label { font-size: 13px; color: #333; cursor: pointer; }

        /* ─── SECTION TITLES ─── */
        .section-title { font-size: 14px; font-weight: 600; color: #222; margin-bottom: 6px; }
        .section-desc  { font-size: 12px; color: #888; margin-bottom: 14px; line-height: 1.5; }

        /* ─── RADIO ─── */
        .radio-group { display: flex; flex-direction: column; gap: 10px; }
        .radio-row { display: flex; align-items: center; gap: 10px; }
        .radio-row input[type="radio"] {
            width: 15px; height: 15px; accent-color: #1a73e8; cursor: pointer;
        }
        .radio-row label { font-size: 13px; color: #333; cursor: pointer; }

        /* ─── TOGGLE ─── */
        .toggle-row { display: flex; align-items: flex-start; justify-content: space-between; }
        .toggle-label-group { display: flex; flex-direction: column; gap: 4px; flex: 1; padding-right: 20px; }
        .toggle-label { font-size: 14px; font-weight: 600; color: #222; }
        .toggle-desc  { font-size: 12px; color: #888; line-height: 1.5; }
        .toggle-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .toggle-status { font-size: 12px; color: #888; }
        .toggle-switch { position: relative; width: 40px; height: 22px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #ccc; border-radius: 22px; transition: 0.3s;
        }
        .slider:before {
            position: absolute; content: ""; height: 16px; width: 16px;
            left: 3px; bottom: 3px; background: white;
            border-radius: 50%; transition: 0.3s;
        }
        .toggle-switch input:checked + .slider { background: #1a73e8; }
        .toggle-switch input:checked + .slider:before { transform: translateX(18px); }

        /* ─── CREDIT LIMIT EXPANDED ─── */
        .credit-limit-expanded {
            margin-top: 16px; padding-top: 16px;
            border-top: 1px solid #f0f0f0;
            display: none;
        }
        .credit-limit-expanded.visible { display: block; }
        .credit-question { font-size: 13px; font-weight: 500; color: #333; margin-bottom: 12px; }
        .credit-note { margin-top: 14px; font-size: 12px; color: #555; }
        .credit-note strong { color: #333; }
        .credit-note ul { padding-left: 18px; margin-top: 4px; }
        .credit-note ul li { margin-bottom: 2px; }
        .checkbox-sub-desc { font-size: 11px; color: #888; margin-top: 3px; margin-left: 25px; }

        /* ─── ADDRESS FORMAT ─── */
        .address-format-label { font-size: 14px; font-weight: 600; color: #222; margin-bottom: 4px; }
        .address-format-sublabel { font-size: 12px; color: #999; margin-left: 6px; font-weight: normal; }
        .address-format-wrapper { max-width: 820px; margin-bottom: 28px; }
        .address-format-box {
            border: 1px solid #e0e0e0; border-radius: 6px; overflow: hidden;
        }
        .placeholder-bar {
            display: flex; align-items: center; gap: 6px;
            background: #f5f5f5; border-bottom: 1px solid #e0e0e0;
            padding: 8px 14px; font-size: 12px; color: #555; cursor: pointer;
        }
        .placeholder-bar:hover { background: #ebebeb; }
        .placeholder-bar svg { width: 12px; height: 12px; }
        .address-textarea {
            width: 100%; min-height: 120px;
            padding: 12px 14px; border: none; outline: none;
            font-size: 12px; font-family: 'Courier New', Courier, monospace;
            color: #333; resize: vertical; background: #fff; line-height: 1.8;
        }

        /* ─── SAVE ─── */
        .save-btn {
            background: #1a73e8; color: #fff; border: none;
            border-radius: 5px; padding: 9px 28px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: background 0.2s; margin-top: 8px;
        }
        .save-btn:hover { background: #1558b0; }

        /* ─── ALERTS ─── */
        .alert { padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
        .alert-success { background: #e6f4ea; border: 1px solid #a8d5b5; color: #1e7e34; }
        .alert-error   { background: #fdecea; border: 1px solid #f5c6cb; color: #c0392b; }

        /* ─── RIGHT ICONS ─── */
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

    <!-- TOP HEADER -->
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
                <p>Techvolt</p>
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

    <!-- BODY -->
    <div class="settings-body">

        <!-- SIDEBAR -->
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
                General <span class="chevron" style="transform:rotate(90deg);">&#8250;</span>
            </div>
            <div class="sidebar-sub-item active">Customers and Vendors</div>
            <div class="sidebar-sub-item">Items</div>
            <div class="sidebar-item">Inventory <span class="chevron">&#8250;</span></div>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="page-header">
                <h2>Customers and Vendors</h2>
                <div class="tabs">
                    <div class="tab active">General</div>
                    <div class="tab">Field Customization</div>
                    <div class="tab">Custom Buttons</div>
                    <div class="tab">Related Lists</div>
                </div>
            </div>

            <div class="form-content">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-error"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-error">
                        <ul style="margin:0;padding-left:16px;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

               <form action="<?php echo e(route('customers-vendors.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- ── SECTION 1: Duplicates ── -->
                    <div class="form-section">
                        <div class="checkbox-row">
                            <input type="checkbox" id="allow_duplicates" name="allow_duplicates" value="1"
                                <?php echo e(old('allow_duplicates', $config['allow_duplicates'] ?? false) ? 'checked' : ''); ?>>
                            <label for="allow_duplicates">Allow duplicates for customer and vendor display name.</label>
                        </div>
                    </div>

                    <!-- ── SECTION 3: Default Customer Type ── -->
                    <div class="form-section">
                        <div class="section-title">Default Customer Type</div>
                        <div class="section-desc">
                            Select the default customer type based on the kind of customers you usually sell your
                            products or services to. The default customer type will be pre-selected in the customer
                            creation form.
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
                                    Credit Limit enables you to set a limit on the outstanding receivable amount of the customers.
                                </span>
                            </div>
                            <div class="toggle-right">
                                <span class="toggle-status" id="credit-limit-status">
                                    <?php echo e(old('customer_credit_limit', $config['customer_credit_limit'] ?? false) ? 'Enabled' : 'Disabled'); ?>

                                </span>
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           id="customer_credit_limit"
                                           name="customer_credit_limit"
                                           value="1"
                                           <?php echo e(old('customer_credit_limit', $config['customer_credit_limit'] ?? false) ? 'checked' : ''); ?>

                                           onchange="updateToggleStatus(this, 'credit-limit-status'); toggleBlock(this, 'credit-limit-expanded')">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Credit Limit Expanded Options -->
                        <div id="credit-limit-expanded"
                             class="credit-limit-expanded <?php echo e(old('customer_credit_limit', $config['customer_credit_limit'] ?? false) ? 'visible' : ''); ?>">
                            <div class="credit-question">What do you want to do when credit limit is exceeded?</div>
                            <div class="radio-group">
                                <div class="radio-row">
                                    <input type="radio" id="credit_restrict" name="credit_limit_action" value="restrict"
                                        <?php echo e(old('credit_limit_action', $config['credit_limit_action'] ?? 'warn') === 'restrict' ? 'checked' : ''); ?>>
                                    <label for="credit_restrict">Restrict creating or updating invoices</label>
                                </div>
                                <div class="radio-row">
                                    <input type="radio" id="credit_warn" name="credit_limit_action" value="warn"
                                        <?php echo e(old('credit_limit_action', $config['credit_limit_action'] ?? 'warn') === 'warn' ? 'checked' : ''); ?>>
                                    <label for="credit_warn">Show a warning and allow users to proceed</label>
                                </div>
                            </div>

                            <div class="checkbox-row" style="margin-top: 14px;">
                                <input type="checkbox" id="include_sales_orders" name="include_sales_orders" value="1"
                                    <?php echo e(old('include_sales_orders', $config['include_sales_orders'] ?? true) ? 'checked' : ''); ?>>
                                <div>
                                    <label for="include_sales_orders">
                                        Include sales orders' amount in limiting the credit given to customers
                                    </label>
                                    <div class="checkbox-sub-desc">
                                        Credit Limit will not affect the creation of sales orders from marketplace,
                                        Zoho POS Registers and Zoho Commerce.
                                    </div>
                                </div>
                            </div>

                            <div class="credit-note">
                                <strong>Note:</strong>
                                <ul>
                                    <li>Go to the respective customer's contact details to set the credit limit.</li>
                                </ul>
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
                            <div class="address-format-box">
                                <div class="placeholder-bar">
                                    Insert Placeholders
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                                <textarea name="billing_address_format"
                                          id="billing_address_format"
                                          class="address-textarea"><?php echo e(old('billing_address_format', $config['billing_address_format'] ?? '${CONTACT.CONTACT_DISPLAYNAME}
${CONTACT.CONTACT_ADDRESS}
${CONTACT.CONTACT_CITY}
${CONTACT.CONTACT_CODE} ${CONTACT.CONTACT_STATE}
${CONTACT.CONTACT_COUNTRY}')); ?></textarea>
                            </div>
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
                            <div class="address-format-box">
                                <div class="placeholder-bar">
                                    Insert Placeholders
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </div>
                                <textarea name="shipping_address_format"
                                          id="shipping_address_format"
                                          class="address-textarea"><?php echo e(old('shipping_address_format', $config['shipping_address_format'] ?? '${CONTACT.CONTACT_ADDRESS}
${CONTACT.CONTACT_CITY}
${CONTACT.CONTACT_CODE} ${CONTACT.CONTACT_STATE}
${CONTACT.CONTACT_COUNTRY}')); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="save-btn">Save</button>
                </form>
            </div>
        </div>

        <!-- RIGHT ICONS -->
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

    </div>
</div>

<script>
    // Toggle status label for toggle switches
    function updateToggleStatus(checkbox, statusId) {
        document.getElementById(statusId).textContent = checkbox.checked ? 'Enabled' : 'Disabled';
    }

    // Show/hide a conditional block based on checkbox state
    function toggleBlock(checkbox, blockId) {
        const block = document.getElementById(blockId);
        if (block) {
            block.classList.toggle('visible', checkbox.checked);
        }
    }

    // Tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/customer_setting_handle/create.blade.php ENDPATH**/ ?>