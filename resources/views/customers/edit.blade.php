<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Edit Customer | Inventory</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #333; background: #f5f5f5; }
  .app-shell { display: flex; height: 100vh; overflow: hidden; }
  .sidebar { width: 220px; background: #1a1f2e; color: #ccc; flex-shrink: 0; display: flex; flex-direction: column; }
  .sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 16px 18px; border-bottom: 1px solid #2e3448; }
  .sidebar-logo .logo-icon { width: 28px; height: 28px; background: #e8f0fe; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
  .sidebar-logo span { font-size: 15px; font-weight: 600; color: #fff; }
  .sidebar-nav { flex: 1; overflow-y: auto; padding: 8px 0; }
  .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 18px; cursor: pointer; font-size: 13px; color: #aab; transition: background 0.15s; }
  .nav-item:hover { background: #252b3d; color: #fff; }
  .nav-item .arrow { margin-left: auto; font-size: 10px; }
  .sidebar-bottom { padding: 12px; border-top: 1px solid #2e3448; }
  .collapse-btn { display: flex; align-items: center; justify-content: center; color: #666; cursor: pointer; padding: 4px; }
  .nav-sub { padding-left: 42px; font-size: 12.5px; color: #aab; padding-top: 7px; padding-bottom: 7px; cursor: pointer; }
  .nav-sub:hover { color: #fff; background: #252b3d; }
  .nav-sub.active { color: #fff; background: #3b6cf8; }
  .topbar { display: flex; align-items: center; gap: 12px; padding: 0 20px; height: 52px; background: #fff; border-bottom: 1px solid #e8e8e8; }
  .topbar-search { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e0e0e0; border-radius: 6px; padding: 6px 12px; min-width: 280px; }
  .topbar-search input { border: none; background: transparent; outline: none; font-size: 13px; color: #333; width: 200px; }
  .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
  .topbar-btn { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; position: relative; }
  .topbar-btn:hover { background: #f5f5f5; }
  .topbar-btn .badge { position: absolute; top: 2px; right: 2px; background: #e53935; color: #fff; border-radius: 50%; width: 14px; height: 14px; font-size: 9px; display: flex; align-items: center; justify-content: center; }
  .avatar { width: 32px; height: 32px; border-radius: 50%; background: #3b6cf8; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #fff; font-size: 13px; cursor: pointer; }
  .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
  .content { flex: 1; overflow-y: auto; }
  .page { background: #fff; min-height: 100%; }
  .page-header { padding: 20px 24px 0; border-bottom: 1px solid #e8e8e8; }
  .page-title { font-size: 20px; font-weight: 600; color: #222; margin-bottom: 14px; }
  .tabs { display: flex; gap: 0; border-bottom: 2px solid #e8e8e8; }
  .tab { padding: 10px 18px; font-size: 13px; color: #666; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; }
  .tab:hover { color: #333; }
  .tab.active { color: #3b6cf8; border-bottom-color: #3b6cf8; font-weight: 500; }
  .form-body { padding: 20px 24px; }
  .form-row { display: flex; align-items: flex-start; margin-bottom: 16px; gap: 16px; }
  .form-label { width: 180px; min-width: 180px; font-size: 13px; color: #555; padding-top: 8px; display: flex; align-items: center; gap: 5px; }
  .form-label.required { color: #e53935; }
  .info-icon { color: #aaa; font-size: 12px; cursor: pointer; }
  .form-control { flex: 1; max-width: 520px; }
  .form-control input, .form-control select, .form-control textarea { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; background: #fff; outline: none; transition: border 0.15s; appearance: none; -webkit-appearance: none; }
  .form-control input:focus, .form-control select:focus, .form-control textarea:focus { border-color: #3b6cf8; box-shadow: 0 0 0 2px rgba(59,108,248,0.1); }
  .form-control select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }
  .form-control textarea { resize: vertical; min-height: 80px; }
  .phone-group { display: flex; gap: 8px; }
  .phone-country { display: flex; align-items: center; gap: 4px; border: 1px solid #d0d0d0; border-radius: 4px; padding: 7px 8px; background: #fff; }
  .phone-country select { border: none; outline: none; background: transparent; font-size: 13px; padding: 0; cursor: pointer; }
  .phone-input { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; }
  .phone-input:focus { border-color: #3b6cf8; }
  .inline-group { display: flex; gap: 8px; }
  .inline-group select { flex: 0 0 140px; }
  .inline-group input { flex: 1; }
  .radio-group { display: flex; gap: 12px; padding-top: 4px; }
  .radio-option { display: flex; align-items: center; gap: 8px; padding: 7px 16px; border: 2px solid #d0d0d0; border-radius: 6px; cursor: pointer; user-select: none; transition: all 0.15s; background: #fff; font-size: 13px; color: #555; font-weight: 500; }
  .radio-option:hover { border-color: #3b6cf8; color: #3b6cf8; background: #f0f4ff; }
  .radio-option.selected { border-color: #3b6cf8; background: #3b6cf8; color: #fff; }
  .radio-option input[type=radio] { display: none; }
  input[type=checkbox] { display: inline-block !important; appearance: auto !important; -webkit-appearance: checkbox !important; pointer-events: auto !important; cursor: pointer; }
  .radio-dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid currentColor; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .radio-option.selected .radio-dot { background: #fff; border-color: #fff; }
  .email-wrapper { position: relative; }
  .email-wrapper input { padding-left: 34px !important; }
  .email-wrapper .email-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 14px; }
  .address-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
  .address-section h4 { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
  .address-section h4 a { font-size: 12px; font-weight: 400; color: #3b6cf8; text-decoration: none; }
  .address-form-row { margin-bottom: 12px; }
  .address-form-row label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; }
  .address-form-row input, .address-form-row select, .address-form-row textarea { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; background: #fff; outline: none; appearance: none; -webkit-appearance: none; }
  .address-form-row select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }
  .address-form-row textarea { min-height: 60px; resize: vertical; }
  .address-note { background: #fffbf0; border-left: 3px solid #f5a623; padding: 10px 14px; margin-top: 16px; border-radius: 0 4px 4px 0; font-size: 12px; color: #444; }
  .address-note ul { margin-left: 14px; margin-top: 4px; }
  .contact-table { width: 100%; border-collapse: collapse; }
  .contact-table th { background: #f5f5f5; font-size: 11px; font-weight: 600; color: #666; padding: 8px 10px; text-align: left; border-bottom: 1px solid #e0e0e0; text-transform: uppercase; letter-spacing: 0.4px; }
  .contact-table td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  .contact-table td input, .contact-table td select { width: 100%; padding: 5px 8px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 12.5px; background: #fff; outline: none; appearance: none; -webkit-appearance: checkbox; }
  .contact-table td.action-col { width: 36px; text-align: center; }
  .remove-row-btn { background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px; line-height: 1; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; }
  .remove-row-btn:hover { background: #fff0f0; color: #e53935; }
  .add-person-btn { display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; color: #3b6cf8; font-size: 13px; cursor: pointer; border: none; background: none; padding: 0; }
  .form-footer { padding: 16px 24px; border-top: 1px solid #e8e8e8; display: flex; gap: 10px; background: #fff; position: sticky; bottom: 0; }
  .btn-save { background: #3b6cf8; color: #fff; border: none; border-radius: 4px; padding: 8px 24px; font-size: 13px; font-weight: 500; cursor: pointer; }
  .btn-save:hover { background: #2b5ce0; }
  .btn-cancel { background: #fff; color: #333; border: 1px solid #d0d0d0; border-radius: 4px; padding: 8px 20px; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
  .btn-cancel:hover { background: #f5f5f5; }
  .portal-check { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #444; cursor: pointer; }
  .portal-check input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; accent-color: #3b6cf8; flex-shrink: 0; }
  .website-wrapper { position: relative; }
  .website-wrapper input { padding-left: 34px !important; }
  .website-wrapper .globe-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #aaa; font-size: 13px; }
  .social-icon-box { width: 32px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border: 1px solid #d0d0d0; border-right: none; border-radius: 4px 0 0 4px; font-size: 13px; }
  .social-input { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 0 4px 4px 0; font-size: 13px; color: #333; background: #fff; outline: none; }
  .social-row { display: flex; }
  .add-more-btn { display: inline-flex; align-items: center; gap: 6px; color: #3b6cf8; font-size: 13px; cursor: pointer; border: none; background: none; padding: 4px 0; margin-bottom: 8px; }
  .tab-content { display: none; }
  .alert-danger { background: #fde8e8; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 13px; }
  .alert-danger ul { margin-left: 16px; margin-top: 4px; }
  .display-name-wrapper { position: relative; width: 100%; }
  .display-name-wrapper input { width: 100%; }
  .dn-dropdown { position: absolute; top: calc(100% + 3px); left: 0; right: 0; background: #fff; border: 1px solid #c8d6ff; border-radius: 6px; box-shadow: 0 6px 20px rgba(59,108,248,0.13); z-index: 500; max-height: 210px; overflow-y: auto; display: none; }
  .dn-dropdown.open { display: block; }
  .dn-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; cursor: pointer; font-size: 13px; color: #333; transition: background 0.12s; border-bottom: 1px solid #f4f4f4; }
  .dn-item:last-child { border-bottom: none; }
  .dn-item:hover, .dn-item.active { background: #f0f4ff; color: #3b6cf8; }
  .dn-avatar { width: 24px; height: 24px; border-radius: 50%; background: #dce8ff; color: #3b6cf8; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .dn-bold { font-weight: 700; color: #3b6cf8; }
  .dn-empty { padding: 14px 12px; font-size: 12px; color: #aaa; text-align: center; }
  .pl-cat-dropdown { position: relative; }
</style>
</head>
<body>

@php
  $ad       = $customer->additional_datas ?? [];
  $billing  = $customer->common_address['billing']  ?? [];
  $shipping = $customer->common_address['shipping'] ?? [];
  $contacts = $ad['contact_persons'] ?? [];
  $cfSaved  = $ad['custom_fields']   ?? [];
  $ctype    = old('customer_type', $customer->customer_type ?? 'business');
  $savedCat = old('customer_category', $customer->customer_category ?? '');
  $savedAssignLoc = $customer->assign_location ?? [];
@endphp

<div class="app-shell">

  {{-- SIDEBAR --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">📦</div>
      <span>Inventory</span>
    </div>
    <div class="sidebar-nav">
      <div class="nav-item">🏠 Home</div>
      <div class="nav-item">📦 Items <span class="arrow">›</span></div>
      <div class="nav-item">🏪 Inventory <span class="arrow">›</span></div>
      <div class="nav-item" style="color:#fff;background:#252b3d;">🛒 Sales <span class="arrow">›</span></div>
      <div class="nav-sub active">Customers</div>
      <div class="nav-sub">Sales Orders</div>
      <div class="nav-sub">Invoices</div>
      <div class="nav-sub">Payments Received</div>
      <div class="nav-sub">Sales Returns</div>
      <div class="nav-item">🛍️ Purchases <span class="arrow">›</span></div>
      <div class="nav-item">📊 Reports</div>
      <div class="nav-item">📄 Documents</div>
    </div>
    <div class="sidebar-bottom"><div class="collapse-btn">⟨ Collapse</div></div>
  </div>

  <div class="main">
    {{-- TOPBAR --}}
    <div class="topbar">
      <div class="topbar-search">
        <span>🔍</span>
        <input placeholder="Search in Customers ( / )" />
      </div>
      <div class="topbar-right">
        <div class="topbar-btn">🔔<span class="badge">1</span></div>
        <div class="topbar-btn">⚙️</div>
        <div class="avatar">V</div>
      </div>
    </div>

    <div class="content">
      <div class="page">

        <form id="customer-form"
              action="{{ route('customers.update', $customer->id) }}"
              method="POST"
              enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="page-header">
            <div class="page-title">Edit Customer</div>

            @if($errors->any())
              <div class="alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
              </div>
            @endif

            <div class="form-body" style="padding:16px 0 0;">

              {{-- Customer Type --}}
              <div class="form-row">
                <div class="form-label">Customer Type <span class="info-icon">ℹ</span></div>
                <div class="form-control">
                  <div class="radio-group">
                    <label class="radio-option {{ $ctype=='business'?'selected':'' }}"
                           id="opt-business" onclick="selectCustomerType('business')">
                      <input type="radio" name="customer_type" value="business"
                             {{ $ctype=='business'?'checked':'' }}>
                      <span class="radio-dot"></span> Business
                    </label>
                    <label class="radio-option {{ $ctype=='individual'?'selected':'' }}"
                           id="opt-individual" onclick="selectCustomerType('individual')">
                      <input type="radio" name="customer_type" value="individual"
                             {{ $ctype=='individual'?'checked':'' }}>
                      <span class="radio-dot"></span> Individual
                    </label>
                  </div>
                </div>
              </div>

              {{-- Customer Category --}}
              <div class="form-row" id="row-customerCategory"
                   style="{{ $ctype=='individual'?'display:none':'' }}">
                <div class="form-label">Customer Category <span class="info-icon">ℹ</span></div>
                <div class="form-control" style="max-width:420px;">

                  <input type="hidden" name="customer_category"
                         id="customerCategoryValue"
                         value="{{ $savedCat }}">

                  <div class="pl-cat-dropdown" id="custCatDropdown">
                    <div class="pl-cat-selected" id="custCatSelected"
                         onclick="toggleCustCatList()"
                         style="display:flex;justify-content:space-between;align-items:center;
                                padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;
                                background:#fff;cursor:pointer;font-size:13px;min-height:36px;">
                      <span id="custCatSelectedText" style="color:#999;">Loading...</span>
                      <span style="font-size:11px;color:#999;">▾</span>
                    </div>
                    <div id="custCatList"
                         style="display:none;position:absolute;background:#fff;
                                border:1px solid #d0d0d0;border-radius:6px;
                                box-shadow:0 6px 20px rgba(0,0,0,0.12);z-index:200;
                                max-height:220px;overflow-y:auto;width:420px;">
                    </div>
                  </div>

                </div>
              </div>

              {{-- Primary Contact --}}
              <div class="form-row">
                <div class="form-label">Primary Contact <span class="info-icon">ℹ</span></div>
                <div class="form-control">
                  <div class="inline-group">
                    <select name="salutation" style="flex:0 0 130px;">
                      <option value="">Salutation</option>
                      @foreach(['Mr.','Mrs.','Ms.','Dr.','Prof.'] as $sal)
                        <option value="{{ $sal }}"
                          {{ old('salutation', $ad['salutation'] ?? '') == $sal ? 'selected':'' }}>
                          {{ $sal }}
                        </option>
                      @endforeach
                    </select>
                    <input type="text" name="first_name" id="firstName"
                           placeholder="First Name"
                           value="{{ old('first_name', $ad['first_name'] ?? '') }}"
                           oninput="refreshDisplaySuggestions()" style="flex:1;">
                    <input type="text" name="last_name" id="lastName"
                           placeholder="Last Name"
                           value="{{ old('last_name', $ad['last_name'] ?? '') }}"
                           oninput="refreshDisplaySuggestions()" style="flex:1;">
                  </div>
                </div>
              </div>

              {{-- Company Name --}}
              <div class="form-row" id="row-companyName"
                   style="{{ $ctype=='individual'?'display:none':'' }}">
                <div class="form-label">Company Name</div>
                <div class="form-control">
                  <input type="text" name="company_name" id="companyName"
                         value="{{ old('company_name', $customer->company_name) }}"
                         oninput="refreshDisplaySuggestions()" autocomplete="off">
                </div>
              </div>

              {{-- Display Name --}}
              <div class="form-row">
                <div class="form-label required">Display Name* <span class="info-icon">ℹ</span></div>
                <div class="form-control">
                  <div class="display-name-wrapper" id="dnWrapper">
                    <input type="text" name="display_name" id="displayName"
                           value="{{ old('display_name', $customer->display_name) }}"
                           placeholder="Select or type display name"
                           autocomplete="off" required
                           oninput="onDisplayNameType()"
                           onfocus="refreshDisplaySuggestions()">
                    <div class="dn-dropdown" id="dnDropdown"></div>
                  </div>
                </div>
              </div>

              {{-- Email --}}
              <div class="form-row">
                <div class="form-label">Email Address</div>
                <div class="form-control">
                  <div class="email-wrapper">
                    <span class="email-icon">✉</span>
                    <input type="email" name="email"
                           value="{{ old('email', $customer->email) }}">
                  </div>
                </div>
              </div>

              {{-- Phone --}}
              <div class="form-row">
                <div class="form-label">Phone</div>
                <div class="form-control">
                  <div class="phone-group">
                    <div class="phone-country"><select><option>+91</option><option>+1</option><option>+44</option></select></div>
                    <input type="text" name="phone_number" placeholder="Work Phone"
                           class="phone-input"
                           value="{{ old('phone_number', $customer->phone_number) }}">
                    <div class="phone-country"><select><option>+91</option><option>+1</option><option>+44</option></select></div>
                    <input type="text" name="mobile" placeholder="Mobile"
                           class="phone-input"
                           value="{{ old('mobile', $ad['mobile'] ?? '') }}">
                  </div>
                </div>
              </div>

              {{-- Language --}}
              <div class="form-row">
                <div class="form-label">Customer Language</div>
                <div class="form-control" style="max-width:320px;">
                  <select name="language">
                    @foreach(['English','Tamil','Hindi','Telugu','Kannada'] as $lang)
                      <option value="{{ $lang }}"
                        {{ old('language', $ad['language'] ?? 'English') == $lang ? 'selected':'' }}>
                        {{ $lang }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

            </div>{{-- end form-body --}}

            {{-- TABS --}}
            <div class="tabs">
              <div class="tab active" onclick="showTab('otherDetails',this)">Other Details</div>
              <div class="tab" onclick="showTab('address',this)">Address</div>
              <div class="tab" onclick="showTab('contactPersons',this)">Contact Persons</div>
              <div class="tab" onclick="showTab('assignLocation',this)">Assign Location</div>
              <div class="tab" onclick="showTab('remarks',this)">Remarks</div>
              <div class="tab" onclick="showTab('customFields',this)">Custom Fields</div>
              <div class="tab" onclick="showTab('reportingTags',this)">Reporting Tags</div>
            </div>
          </div>{{-- end page-header --}}

          {{-- OTHER DETAILS --}}
          <div id="tab-otherDetails" class="form-body tab-content" style="display:block;">
            <div class="form-row">
              <div class="form-label">PAN</div>
              <div class="form-control">
                <input type="text" name="pan" maxlength="10"
                       style="max-width:200px;text-transform:uppercase;"
                       value="{{ old('pan', $customer->pan) }}">
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Currency</div>
              <div class="form-control" style="max-width:320px;">
                <select name="currency">
                  @foreach(['INR'=>'INR - Indian Rupee','USD'=>'USD - US Dollar','EUR'=>'EUR - Euro','GBP'=>'GBP - British Pound'] as $val=>$label)
                    <option value="{{ $val }}"
                      {{ old('currency', $ad['currency'] ?? 'INR') == $val ? 'selected':'' }}>
                      {{ $label }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Payment Terms</div>
              <div class="form-control" style="max-width:320px;">
                <select name="payment_terms">
                  @foreach(['Due on Receipt','Net 15','Net 30','Net 45','Net 60'] as $pt)
                    <option value="{{ $pt }}"
                      {{ old('payment_terms', $ad['payment_terms'] ?? 'Due on Receipt') == $pt ? 'selected':'' }}>
                      {{ $pt }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-label">Enable Portal?</div>
              <div class="form-control">
                <label class="portal-check">
                  <input type="checkbox" name="enable_portal" value="1"
                    {{ old('enable_portal', $ad['enable_portal'] ?? false) ? 'checked':'' }}>
                  Allow portal access for this customer
                </label>
              </div>
            </div>

            <button type="button" class="add-more-btn" id="addMoreBtn"
                    onclick="toggleMoreDetails()">＋ Add More Details</button>

            <div id="moreDetailsSection" style="display:none;">
              <div class="form-row">
                <div class="form-label">Website URL</div>
                <div class="form-control">
                  <div class="website-wrapper">
                    <span class="globe-icon">🌐</span>
                    <input type="url" name="website"
                           placeholder="ex: https://www.example.com"
                           value="{{ old('website', $ad['website'] ?? '') }}">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-label">Department</div>
                <div class="form-control">
                  <input type="text" name="department"
                         value="{{ old('department', $ad['department'] ?? '') }}">
                </div>
              </div>
              <div class="form-row">
                <div class="form-label">Designation</div>
                <div class="form-control">
                  <input type="text" name="designation"
                         value="{{ old('designation', $ad['designation'] ?? '') }}">
                </div>
              </div>
              <div class="form-row">
                <div class="form-label">X (Twitter)</div>
                <div class="form-control">
                  <div class="social-row">
                    <div class="social-icon-box" style="background:#000;border-color:#000;color:#fff;font-weight:700;font-size:12px;">𝕏</div>
                    <input type="text" name="twitter" class="social-input"
                           value="{{ old('twitter', $ad['twitter'] ?? '') }}">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-label">Skype</div>
                <div class="form-control">
                  <div class="social-row">
                    <div class="social-icon-box" style="color:#00aff0;">🅢</div>
                    <input type="text" name="skype" class="social-input"
                           value="{{ old('skype', $ad['skype'] ?? '') }}">
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-label">Facebook</div>
                <div class="form-control">
                  <div class="social-row">
                    <div class="social-icon-box" style="color:#1877f2;font-weight:700;">f</div>
                    <input type="text" name="facebook" class="social-input"
                           value="{{ old('facebook', $ad['facebook'] ?? '') }}">
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ADDRESS --}}
          <div id="tab-address" class="form-body tab-content">
            <div class="address-grid">
              <div class="address-section">
                <h4>Billing Address</h4>
                @foreach(['attention','street1','street2','city','pincode','phone','fax'] as $f)
                  <div class="address-form-row">
                    <label>{{ ucfirst($f) }}</label>
                    @if(in_array($f,['street1','street2']))
                      <textarea name="billing[{{ $f }}]">{{ old('billing.'.$f, $billing[$f] ?? '') }}</textarea>
                    @else
                      <input type="text" name="billing[{{ $f }}]"
                             value="{{ old('billing.'.$f, $billing[$f] ?? '') }}">
                    @endif
                  </div>
                @endforeach
                <div class="address-form-row">
                  <label>Country/Region</label>
                  <select name="billing[country]">
                    <option value="">Select</option>
                    @foreach(['India','USA','UK'] as $c)
                      <option value="{{ $c }}"
                        {{ old('billing.country', $billing['country'] ?? '') == $c ? 'selected':'' }}>{{ $c }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="address-form-row">
                  <label>State</label>
                  <select name="billing[state]">
                    <option value="">Select</option>
                    @foreach(['Tamil Nadu','Karnataka','Maharashtra','Delhi'] as $s)
                      <option value="{{ $s }}"
                        {{ old('billing.state', $billing['state'] ?? '') == $s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="address-section">
                <h4>Shipping Address
                  <a href="#" onclick="copyBilling();return false;">↓ Copy billing address</a>
                </h4>
                @foreach(['attention','street1','street2','city','pincode','phone','fax'] as $f)
                  <div class="address-form-row">
                    <label>{{ ucfirst($f) }}</label>
                    @if(in_array($f,['street1','street2']))
                      <textarea name="shipping[{{ $f }}]">{{ old('shipping.'.$f, $shipping[$f] ?? '') }}</textarea>
                    @else
                      <input type="text" name="shipping[{{ $f }}]"
                             value="{{ old('shipping.'.$f, $shipping[$f] ?? '') }}">
                    @endif
                  </div>
                @endforeach
                <div class="address-form-row">
                  <label>Country/Region</label>
                  <select name="shipping[country]">
                    <option value="">Select</option>
                    @foreach(['India','USA','UK'] as $c)
                      <option value="{{ $c }}"
                        {{ old('shipping.country', $shipping['country'] ?? '') == $c ? 'selected':'' }}>{{ $c }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="address-form-row">
                  <label>State</label>
                  <select name="shipping[state]">
                    <option value="">Select</option>
                    @foreach(['Tamil Nadu','Karnataka','Maharashtra','Delhi'] as $s)
                      <option value="{{ $s }}"
                        {{ old('shipping.state', $shipping['state'] ?? '') == $s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="address-note"><strong>Note:</strong><ul><li>Enter your Billing and Shipping address here.</li></ul></div>
          </div>

          {{-- CONTACT PERSONS --}}
          <div id="tab-contactPersons" class="form-body tab-content">
            <table class="contact-table">
              <thead>
                <tr>
                  <th>Salutation</th><th>First Name</th><th>Last Name</th>
                  <th>Email Address</th><th>Work Phone</th><th>Mobile</th><th></th>
                </tr>
              </thead>
              <tbody id="contactPersonsBody"></tbody>
            </table>
            <button type="button" class="add-person-btn" onclick="addContactRow()">
              ⊕ Add Contact Person
            </button>
          </div>

          {{-- ASSIGN LOCATION --}}
          <div id="tab-assignLocation" class="form-body tab-content">
            <div id="assign-loc-wrap">
              <span style="font-size:12px;color:#aaa;">Loading...</span>
            </div>
          </div>

          {{-- REMARKS --}}
          <div id="tab-remarks" class="form-body tab-content">
            <div class="form-row">
              <div class="form-label">Remarks
                <span style="color:#999;font-size:12px;">(Internal Use)</span>
              </div>
              <div class="form-control" style="max-width:600px;">
                <textarea name="remarks" rows="6">{{ old('remarks', $customer->remarks ?? ($ad['remarks']['note'] ?? '')) }}</textarea>
              </div>
            </div>
          </div>

          {{-- CUSTOM FIELDS --}}
          <div id="tab-customFields" class="form-body tab-content">
            @if($customFields->isEmpty())
              <p style="color:#888;font-size:13px;">
                No custom fields yet.
                <a href="{{ route('field_customization.create') }}?from=customers"
                   style="color:#3b6cf8;">+ Add Custom Fields</a>
              </p>
            @else
              @foreach($customFields as $field)
                <div class="form-row">
                  <div class="form-label {{ $field->mandatory=='yes'?'required':'' }}">
                    {{ $field->name }}
                    @if($field->mandatory=='yes')<span style="color:#e53935;">*</span>@endif
                  </div>
                  <div class="form-control">
                    @php
                      $config    = $field->additional_config ?? [];
                      $fieldName = 'custom_fields[' . $field->id . ']';
                      $savedVal  = $cfSaved[$field->id] ?? null;
                      $oldVal    = old('custom_fields.' . $field->id, $savedVal);
                    @endphp
                    @switch($field->data_type)
                      @case('string') @case('char') @case('email')
                      @case('phone')  @case('url')  @case('ip_address')
                        <input type="{{ $field->data_type=='email'?'email':($field->data_type=='url'?'url':'text') }}"
                               name="{{ $fieldName }}"
                               value="{{ $oldVal ?? ($config['default_value'] ?? '') }}"
                               {{ $field->mandatory=='yes'?'required':'' }}
                               @if(!empty($config['char_limit'])) maxlength="{{ $config['char_limit'] }}" @endif
                               placeholder="{{ $config['help_text'] ?? '' }}">
                      @break
                      @case('text') @case('longtext')
                        <textarea name="{{ $fieldName }}"
                          {{ $field->mandatory=='yes'?'required':'' }}
                          placeholder="{{ $config['help_text'] ?? '' }}"
                        >{{ $oldVal ?? ($config['default_value'] ?? '') }}</textarea>
                      @break
                      @case('integer') @case('biginteger') @case('smallinteger')
                      @case('tinyinteger') @case('decimal') @case('float')
                      @case('double') @case('currency') @case('percentage')
                        <input type="number" name="{{ $fieldName }}"
                               value="{{ $oldVal ?? ($config['default_value'] ?? '') }}"
                               {{ $field->mandatory=='yes'?'required':'' }}
                               placeholder="{{ $config['help_text'] ?? '' }}"
                               step="{{ in_array($field->data_type,['decimal','float','double','currency','percentage'])?'any':'1' }}">
                      @break
                      @case('boolean')
                        <label class="portal-check">
                          <input type="checkbox" name="{{ $fieldName }}" value="1"
                            {{ ($oldVal ?? ($config['default_value'] ?? ''))=='1'?'checked':'' }}>
                          {{ $config['help_text'] ?? $field->name }}
                        </label>
                      @break
                      @case('date')
                        <input type="date" name="{{ $fieldName }}"
                               value="{{ $oldVal ?? ($config['default_date'] ?? '') }}"
                               {{ $field->mandatory=='yes'?'required':'' }}
                               style="max-width:200px;">
                      @break
                      @case('datetime') @case('timestamp')
                        <input type="datetime-local" name="{{ $fieldName }}"
                               value="{{ $oldVal ?? '' }}"
                               {{ $field->mandatory=='yes'?'required':'' }}
                               style="max-width:260px;">
                      @break
                      @case('time')
                        <input type="time" name="{{ $fieldName }}"
                               value="{{ $oldVal ?? ($config['default_time'] ?? '') }}"
                               {{ $field->mandatory=='yes'?'required':'' }}
                               style="max-width:160px;">
                      @break
                      @case('array')
                        @php
                          $options = array_filter(array_map('trim',
                            explode("\n", $config['options'] ?? '')));
                        @endphp
                        @if(count($options))
                          <select name="{{ $fieldName }}" {{ $field->mandatory=='yes'?'required':'' }}>
                            <option value="">-- Select --</option>
                            @foreach($options as $opt)
                              <option value="{{ $opt }}"
                                {{ ($oldVal ?? ($config['default_value'] ?? ''))==$opt?'selected':'' }}>{{ $opt }}</option>
                            @endforeach
                          </select>
                        @else
                          <input type="text" name="{{ $fieldName }}" value="{{ $oldVal ?? '' }}">
                        @endif
                      @break
                      @default
                        <input type="text" name="{{ $fieldName }}" value="{{ $oldVal ?? '' }}">
                    @endswitch
                    @if(!empty($config['help_text']))
                      <div style="font-size:11px;color:#999;margin-top:4px;">{{ $config['help_text'] }}</div>
                    @endif
                  </div>
                </div>
              @endforeach
            @endif
          </div>

          {{-- REPORTING TAGS --}}
          <div id="tab-reportingTags" class="form-body tab-content">
            <p style="color:#888;font-size:13px;">You've not created any Reporting Tags.</p>
          </div>

          <div class="form-footer">
            <button type="submit" class="btn-save">Update</button>
            <a href="{{ route('customers.index') }}" class="btn-cancel">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ── Saved values from DB ── */
var SAVED_CATEGORY   = @json($savedCat);
var SAVED_ASSIGN_LOC = @json($savedAssignLoc);
var existingContacts = @json($contacts);

/* ════════════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════════════ */
var _currentCatLayerId   = '';
var _currentCatCountryId = '';
var _usedValueIds        = [];
var _allCategories       = [];
var _lcLayers            = [];
var _lcValues            = [];

/* ════════════════════════════════════════════════════
   UTILS
═══════════════════════════════════════════════════════ */
function capFirst(s) { return String(s).charAt(0).toUpperCase() + String(s).slice(1); }
function escHtml(s)  { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function escAttr(s)  { return String(s).replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

/* ════════════════════════════════════════════════════
   LOAD LC-TREE
═══════════════════════════════════════════════════════ */
async function loadLcLayers() {
    try {
        var res  = await fetch('/lc-tree', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        var json = await res.json();
        if (!json.success) { console.error('lc-tree: success=false'); return; }

        _lcLayers = (json.layers || []).map(function(l) {
            return {
                id:              String(l.id),
                name:            l.name,
                depth:           parseInt(l.depth),
                is_global:       !!l.is_global,
                parent_value_id: l.parent_value_id != null ? String(l.parent_value_id) : null,
            };
        });
        _lcValues = (json.values || []).map(function(v) {
            return {
                id:              String(v.id),
                layer_id:        String(v.layer_id),
                parent_value_id: v.parent_value_id != null ? String(v.parent_value_id) : null,
                value:           v.value,
            };
        });
    } catch(e) { console.error('loadLcLayers failed:', e); }
}

/* ════════════════════════════════════════════════════
   LOAD CATEGORY DROPDOWN
   FIX: no longer calls renderAssignLocation internally.
        Caller (DOMContentLoaded) handles that after
        both promises resolve.
═══════════════════════════════════════════════════════ */
async function loadCategoryDropdown() {
    try {
        var res  = await fetch('/user-categories', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        var json = await res.json();

        if (!json.success) {
            console.error('user-categories: success=false', json);
            document.getElementById('custCatSelectedText').textContent = '-- Select Category --';
            document.getElementById('custCatSelectedText').style.color = '#999';
            return;
        }

        _allCategories = json.data || [];
        buildCustCatListHTML(_allCategories);

        /* ── Pre-select saved category ──
           FIX: match by iterating _allCategories (plain JS objects),
                NOT by DOM querySelector with data-name (escaping mismatch). */
        if (SAVED_CATEGORY) {
            var savedCat = _allCategories.find(function(c) { return c.name === SAVED_CATEGORY; });

            if (savedCat) {
                _currentCatLayerId   = String(savedCat.assign_fix_location || '');
                _currentCatCountryId = String(savedCat.country_id || '');

                var loc = savedCat.location_label || '';
                var txt = document.getElementById('custCatSelectedText');
                txt.style.color = '#333';
                txt.innerHTML   = loc
                    ? '<span style="font-weight:500;">' + escHtml(SAVED_CATEGORY) + '</span>'
                      + ' <span style="font-size:11px;color:#fff;background:#3b6cf8;'
                      + 'padding:2px 8px;border-radius:10px;margin-left:6px;">' + escHtml(loc) + '</span>'
                    : escHtml(SAVED_CATEGORY);

                /* Fetch used locations (exclude self) — await so _usedValueIds is ready */
                await fetchUsedLocations(SAVED_CATEGORY);

            } else {
                /* Category was saved but no longer exists in the list */
                document.getElementById('custCatSelectedText').textContent = SAVED_CATEGORY;
                document.getElementById('custCatSelectedText').style.color = '#333';
            }
        } else {
            document.getElementById('custCatSelectedText').textContent = '-- Select Category --';
            document.getElementById('custCatSelectedText').style.color = '#999';
        }

        /* NOTE: renderAssignLocation is NOT called here.
           DOMContentLoaded calls it after BOTH promises resolve. */

    } catch(e) {
        console.error('loadCategoryDropdown failed:', e);
        document.getElementById('custCatSelectedText').textContent = 'Error loading categories';
        document.getElementById('custCatSelectedText').style.color = '#e53935';
    }
}

/* ════════════════════════════════════════════════════
   BUILD CATEGORY LIST HTML
═══════════════════════════════════════════════════════ */
function buildCustCatListHTML(categories) {
    var list = document.getElementById('custCatList');
    var html = '<div style="display:flex;justify-content:space-between;align-items:center;'
             + 'padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f0f0f0;color:#999;"'
             + ' data-name="" data-layer-id="" data-country-id="" data-loc=""'
             + ' onclick="selectCustCat(this)">'
             + '<span>-- Select Category --</span></div>';

    categories.forEach(function(cat) {
        var loc = cat.location_label || '';
        html += '<div style="display:flex;justify-content:space-between;align-items:center;'
              + 'padding:9px 12px;cursor:pointer;font-size:13px;border-bottom:1px solid #f0f0f0;"'
              + ' data-name="'       + escAttr(cat.name) + '"'
              + ' data-layer-id="'  + escAttr(String(cat.assign_fix_location || '')) + '"'
              + ' data-country-id="' + escAttr(String(cat.country_id || '')) + '"'
              + ' data-loc="'       + escAttr(loc) + '"'
              + ' onclick="selectCustCat(this)"'
              + ' onmouseover="this.style.background=\'#f0f4ff\'"'
              + ' onmouseout="this.style.background=\'#fff\'">'
              + '<span style="font-weight:500;color:#333;">' + escHtml(cat.name) + '</span>';
        if (loc) {
            html += '<span style="font-size:11px;color:#fff;background:#3b6cf8;'
                  + 'padding:2px 8px;border-radius:10px;white-space:nowrap;">' + escHtml(loc) + '</span>';
        }
        html += '</div>';
    });
    list.innerHTML = html;
}

function toggleCustCatList() {
    var list = document.getElementById('custCatList');
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

/* Called when user manually picks from the dropdown list */
function selectCustCat(el) {
    var name      = el.dataset.name;
    var layerId   = el.dataset.layerId;
    var countryId = el.dataset.countryId;
    var loc       = el.dataset.loc;

    _currentCatLayerId   = layerId   || '';
    _currentCatCountryId = countryId || '';

    document.getElementById('customerCategoryValue').value = name;
    document.getElementById('custCatList').style.display   = 'none';

    var txt = document.getElementById('custCatSelectedText');
    if (name) {
        txt.style.color = '#333';
        txt.innerHTML   = loc
            ? '<span style="font-weight:500;">' + escHtml(name) + '</span>'
              + ' <span style="font-size:11px;color:#fff;background:#3b6cf8;'
              + 'padding:2px 8px;border-radius:10px;margin-left:6px;">' + escHtml(loc) + '</span>'
            : escHtml(name);
    } else {
        txt.style.color        = '#999';
        txt.textContent        = '-- Select Category --';
        _currentCatLayerId     = '';
        _currentCatCountryId   = '';
    }

    if (name && layerId) {
        fetchUsedLocations(name).then(function() {
            renderAssignLocation(layerId || null, countryId || null);
        });
    } else {
        _usedValueIds = [];
        renderAssignLocation(null, null);
    }
}

/* Close category list on outside click */
document.addEventListener('mousedown', function(e) {
    var dd = document.getElementById('custCatDropdown');
    if (dd && !dd.contains(e.target)) {
        document.getElementById('custCatList').style.display = 'none';
    }
});

/* ════════════════════════════════════════════════════
   FETCH USED LOCATIONS  (exclude current customer)
═══════════════════════════════════════════════════════ */
async function fetchUsedLocations(categoryName) {
    try {
        var res  = await fetch(
            '/customers/used-locations?category=' + encodeURIComponent(categoryName)
            + '&exclude_id={{ $customer->id }}',
            { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } }
        );
        var json = await res.json();
        _usedValueIds = (json.used_value_ids || []).map(String);
    } catch(e) {
        console.error('fetchUsedLocations failed:', e);
        _usedValueIds = [];
    }
}

/* ════════════════════════════════════════════════════
   RENDER ASSIGN LOCATION
═══════════════════════════════════════════════════════ */
function renderAssignLocation(categoryLayerId, categoryCountryId) {
    var wrap = document.getElementById('assign-loc-wrap');
    if (!wrap) return;

    if (!categoryLayerId) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">Please select a Customer Category first.</p>';
        return;
    }

    if (!_lcLayers.length) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">Location data not loaded yet. Please wait...</p>';
        return;
    }

    var targetLayer = _lcLayers.find(function(l) { return String(l.id) === String(categoryLayerId); });
    if (!targetLayer) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">Layer not found (id=' + escHtml(categoryLayerId) + ').</p>';
        return;
    }

    var layerValues = [];
    if (categoryCountryId) {
        layerValues = _lcValues.filter(function(v) {
            return String(v.layer_id) === String(categoryLayerId)
                && String(v.parent_value_id) === String(categoryCountryId);
        });
    }
    if (!layerValues.length) {
        layerValues = _lcValues.filter(function(v) {
            return String(v.layer_id) === String(categoryLayerId);
        });
    }
    if (!layerValues.length) {
        wrap.innerHTML = '<p style="color:#aaa;font-size:13px;">No location values found for this layer.</p>';
        return;
    }

    var savedValueId = (SAVED_ASSIGN_LOC && SAVED_ASSIGN_LOC.value_id)
                     ? String(SAVED_ASSIGN_LOC.value_id)
                     : '';

    var allUsed = layerValues.every(function(v) {
        return String(v.id) !== savedValueId && _usedValueIds.includes(String(v.id));
    });

    var opts = '<option value="">-- Select ' + escHtml(capFirst(targetLayer.name)) + ' --</option>';
    layerValues.forEach(function(v) {
        var usedByOther = _usedValueIds.includes(String(v.id)) && String(v.id) !== savedValueId;
        var selected    = String(v.id) === savedValueId ? ' selected' : '';
        opts += '<option value="' + escAttr(v.id) + '"'
             + (usedByOther ? ' disabled style="color:#ccc;"' : '')
             + selected + '>'
             + escHtml(v.value)
             + (usedByOther ? ' (Already Assigned)' : '')
             + '</option>';
    });

    var warningHtml = allUsed
        ? '<div style="margin-top:8px;padding:8px 12px;background:#fff3cd;border:1px solid #ffc107;'
        + 'border-radius:4px;font-size:12px;color:#856404;">'
        + '⚠️ All locations for this category are already assigned to other customers.'
        + '</div>'
        : '';

    wrap.innerHTML =
        '<div class="form-row">'
      + '<div class="form-label" style="text-transform:capitalize;">' + escHtml(targetLayer.name) + '</div>'
      + '<div class="form-control" style="max-width:320px;">'
      + '<select id="assign-loc-select"'
      + '  name="assign_location_value_id"'
      + '  data-layer-id="'   + escAttr(categoryLayerId) + '"'
      + '  data-country-id="' + escAttr(categoryCountryId || '') + '"'
      + '  onchange="onAssignLocChange(this)"'
      + (allUsed ? ' disabled' : '') + '>'
      + opts
      + '</select>'
      + warningHtml
      + '</div></div>'
      + '<input type="hidden" name="assign_location[layer_id]"  id="al_layer_id"  value="' + escAttr(SAVED_ASSIGN_LOC.layer_id  || '') + '">'
      + '<input type="hidden" name="assign_location[value_id]"  id="al_value_id"  value="' + escAttr(savedValueId) + '">'
      + '<input type="hidden" name="assign_location[path]"      id="al_path"      value="' + escAttr(SAVED_ASSIGN_LOC.path      || '') + '">'
      + '<input type="hidden" name="assign_location[value_ids]" id="al_value_ids" value="' + escAttr(JSON.stringify(SAVED_ASSIGN_LOC.value_ids || [])) + '">';
}

function onAssignLocChange(sel) {
    var selectedValueId = sel.value;
    if (!selectedValueId) {
        document.getElementById('al_layer_id').value  = '';
        document.getElementById('al_value_id').value  = '';
        document.getElementById('al_path').value      = '';
        document.getElementById('al_value_ids').value = '';
        return;
    }
    var chain    = buildValueChain(selectedValueId);
    var path     = chain.map(function(v) { return v.value; }).join(' → ');
    var valueIds = chain.map(function(v) { return v.id; });

    document.getElementById('al_layer_id').value  = sel.dataset.layerId;
    document.getElementById('al_value_id').value  = selectedValueId;
    document.getElementById('al_path').value      = path;
    document.getElementById('al_value_ids').value = JSON.stringify(valueIds);
}

function buildValueChain(valueId) {
    var chain   = [];
    var current = _lcValues.find(function(v) { return String(v.id) === String(valueId); });
    while (current) {
        chain.unshift(current);
        if (!current.parent_value_id) break;
        current = _lcValues.find(function(v) { return String(v.id) === String(current.parent_value_id); });
    }
    return chain;
}

/* ════════════════════════════════════════════════════
   showTab
═══════════════════════════════════════════════════════ */
function showTab(tabId, el) {
    document.querySelectorAll('.tab-content').forEach(function(t) { t.style.display = 'none'; });
    document.querySelectorAll('.tab').forEach(function(t) { t.classList.remove('active'); });
    document.getElementById('tab-' + tabId).style.display = 'block';
    el.classList.add('active');

    if (tabId === 'assignLocation') {
        /* If layers haven't loaded yet (edge case), load then render */
        if (!_lcLayers.length) {
            loadLcLayers().then(function() {
                renderAssignLocation(_currentCatLayerId || null, _currentCatCountryId || null);
            });
        } else {
            renderAssignLocation(_currentCatLayerId || null, _currentCatCountryId || null);
        }
    }
}

/* ════════════════════════════════════════════════════
   DISPLAY NAME helpers
═══════════════════════════════════════════════════════ */
var dnActiveIdx = -1;

function generateVariants() {
    var company  = (document.getElementById('companyName')?.value || '').trim();
    var first    = (document.getElementById('firstName')?.value   || '').trim();
    var last     = (document.getElementById('lastName')?.value    || '').trim();
    var variants = [];
    if (company) {
        variants.push(company);
        var words = company.split(/\s+/);
        if (words.length > 1) variants.push(words[0]);
        if (words.length > 2) variants.push(words[0] + ' ' + words[words.length - 1]);
        var suffixes = ['Private Limited','Pvt Ltd','Pvt. Ltd.','Pvt. Ltd','Ltd','LLP','Inc','Corp','Co'];
        suffixes.forEach(function(s) {
            var reg      = new RegExp('\\s*' + s.replace(/\./g, '\\.') + '\\.?$', 'i');
            var stripped = company.replace(reg, '').trim();
            if (stripped && stripped !== company) variants.push(stripped);
        });
        var acronym = words.map(function(w) { return w[0]; }).join('').toUpperCase();
        if (acronym.length >= 2 && acronym.length <= 5) variants.push(acronym);
    }
    if (first || last) {
        var full = (first + ' ' + last).trim();
        if (full)  variants.push(full);
        if (first) variants.push(first);
        if (last)  variants.push(last);
    }
    return variants.filter(function(v, i, a) { return v && a.indexOf(v) === i; });
}

function boldMatch(text, query) {
    if (!query) return escHtml(text);
    var idx = text.toLowerCase().indexOf(query.toLowerCase());
    if (idx === -1) return escHtml(text);
    return escHtml(text.slice(0, idx))
         + '<span class="dn-bold">' + escHtml(text.slice(idx, idx + query.length)) + '</span>'
         + escHtml(text.slice(idx + query.length));
}

function renderDnDropdown(variants, query) {
    var dd = document.getElementById('dnDropdown');
    dnActiveIdx = -1;
    if (!variants.length) {
        dd.innerHTML = '<div class="dn-empty">No suggestions</div>';
        dd.classList.add('open');
        return;
    }
    dd.innerHTML = variants.map(function(name) {
        return '<div class="dn-item" data-value="' + escAttr(name) + '">'
             + '<div class="dn-avatar">' + escHtml(name[0].toUpperCase()) + '</div>'
             + '<span>' + boldMatch(name, query) + '</span></div>';
    }).join('');
    dd.querySelectorAll('.dn-item').forEach(function(item) {
        item.addEventListener('mousedown', function(e) {
            e.preventDefault();
            selectDisplayName(this.dataset.value);
        });
    });
    dd.classList.add('open');
}

function closeDnDropdown() {
    var dd = document.getElementById('dnDropdown');
    dd.classList.remove('open');
    dd.innerHTML = '';
    dnActiveIdx  = -1;
}

function selectDisplayName(value) {
    document.getElementById('displayName').value = value;
    closeDnDropdown();
}

function refreshDisplaySuggestions() {
    var dn       = document.getElementById('displayName');
    var variants = generateVariants();
    if (!dn.value && variants.length) dn.value = variants[0];
    var query    = dn.value.trim();
    var filtered = variants.filter(function(v) { return v.toLowerCase().includes(query.toLowerCase()); });
    if (filtered.length) renderDnDropdown(filtered, query); else closeDnDropdown();
}

function onDisplayNameType() {
    var query    = document.getElementById('displayName').value.trim();
    var variants = generateVariants();
    if (!query) {
        if (variants.length) renderDnDropdown(variants, ''); else closeDnDropdown();
        return;
    }
    var filtered = variants.filter(function(v) { return v.toLowerCase().includes(query.toLowerCase()); });
    if (filtered.length) renderDnDropdown(filtered, query); else closeDnDropdown();
}

document.getElementById('displayName').addEventListener('keydown', function(e) {
    var items = document.querySelectorAll('#dnDropdown .dn-item');
    if (!items.length) return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        dnActiveIdx = Math.min(dnActiveIdx + 1, items.length - 1);
        items.forEach(function(el, i) { el.classList.toggle('active', i === dnActiveIdx); });
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        dnActiveIdx = Math.max(dnActiveIdx - 1, 0);
        items.forEach(function(el, i) { el.classList.toggle('active', i === dnActiveIdx); });
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (dnActiveIdx >= 0 && items[dnActiveIdx]) selectDisplayName(items[dnActiveIdx].dataset.value);
    } else if (e.key === 'Escape') {
        closeDnDropdown();
    }
});

document.addEventListener('mousedown', function(e) {
    var wrapper = document.getElementById('dnWrapper');
    if (wrapper && !wrapper.contains(e.target)) closeDnDropdown();
});

/* ════════════════════════════════════════════════════
   OTHER HELPERS
═══════════════════════════════════════════════════════ */
function selectCustomerType(type) {
    document.getElementById('opt-business').classList.toggle('selected',   type === 'business');
    document.getElementById('opt-individual').classList.toggle('selected', type === 'individual');
    document.querySelector('input[value="business"]').checked   = (type === 'business');
    document.querySelector('input[value="individual"]').checked = (type === 'individual');
    var b = (type === 'business');
    document.getElementById('row-customerCategory').style.display = b ? 'flex' : 'none';
    document.getElementById('row-companyName').style.display       = b ? 'flex' : 'none';
}

function toggleMoreDetails() {
    var s = document.getElementById('moreDetailsSection');
    var b = document.getElementById('addMoreBtn');
    var h = s.style.display === 'none';
    s.style.display = h ? 'block' : 'none';
    b.textContent   = h ? '－ Show Less' : '＋ Add More Details';
}

function copyBilling() {
    ['attention','street1','street2','city','pincode','phone','fax'].forEach(function(f) {
        var s = document.querySelector('[name="billing[' + f + ']"]');
        var d = document.querySelector('[name="shipping[' + f + ']"]');
        if (s && d) d.value = s.value;
    });
    var sc = document.querySelector('[name="billing[country]"]');
    var dc = document.querySelector('[name="shipping[country]"]');
    if (sc && dc) dc.value = sc.value;
    var ss = document.querySelector('[name="billing[state]"]');
    var ds = document.querySelector('[name="shipping[state]"]');
    if (ss && ds) ds.value = ss.value;
}

/* ════════════════════════════════════════════════════
   CONTACT PERSONS
═══════════════════════════════════════════════════════ */
var contactRowIndex = 0;

function makeContactRow(data) {
    data  = data || {};
    var i = contactRowIndex++;
    var tr = document.createElement('tr');
    var sals    = ['','Mr.','Mrs.','Ms.','Dr.'];
    var salOpts = sals.map(function(s) {
        return '<option value="' + s + '"' + (data.salutation === s ? ' selected' : '') + '>' + s + '</option>';
    }).join('');
    tr.innerHTML =
        '<td><select name="contact_persons[' + i + '][salutation]">' + salOpts + '</select></td>'
      + '<td><input type="text"  name="contact_persons[' + i + '][first_name]"  value="' + escHtml(data.first_name  || '') + '"></td>'
      + '<td><input type="text"  name="contact_persons[' + i + '][last_name]"   value="' + escHtml(data.last_name   || '') + '"></td>'
      + '<td><input type="email" name="contact_persons[' + i + '][email]"       value="' + escHtml(data.email       || '') + '"></td>'
      + '<td><input type="text"  name="contact_persons[' + i + '][work_phone]"  value="' + escHtml(data.work_phone  || '') + '"></td>'
      + '<td><input type="text"  name="contact_persons[' + i + '][mobile]"      value="' + escHtml(data.mobile      || '') + '"></td>'
      + '<td class="action-col"><button type="button" class="remove-row-btn" onclick="removeContactRow(this)">×</button></td>';
    return tr;
}

function addContactRow(data) {
    document.getElementById('contactPersonsBody').appendChild(makeContactRow(data));
}

function removeContactRow(btn) {
    var row   = btn.closest('tr');
    var tbody = document.getElementById('contactPersonsBody');
    if (tbody.rows.length > 1) {
        row.remove();
    } else {
        row.querySelectorAll('input').forEach(function(i) { i.value = ''; });
        row.querySelectorAll('select').forEach(function(s) { s.selectedIndex = 0; });
    }
}

/* ════════════════════════════════════════════════════
   INIT
   FIX: loadLcLayers() and loadCategoryDropdown() run
        sequentially (not in parallel) so that when
        loadCategoryDropdown resolves, _lcLayers is
        already populated and renderAssignLocation works
        correctly on first paint.
═══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', async function() {

    /* 1. Render existing contact persons */
    if (existingContacts && existingContacts.length) {
        existingContacts.forEach(function(c) { addContactRow(c); });
    } else {
        addContactRow(); addContactRow();
    }

    /* 2. Load lc-tree FIRST, then categories.
          Sequential order guarantees _lcLayers is ready
          before we try to pre-render the assign location. */
    await loadLcLayers();
    await loadCategoryDropdown();

    /* 3. Both loaded — render assign location with saved values */
    if (_currentCatLayerId) {
        renderAssignLocation(_currentCatLayerId, _currentCatCountryId || null);
    }

    /* 4. Show "More Details" section if any extra field has a saved value */
    @php $ad2 = $customer->additional_datas ?? []; @endphp
    @if(!empty($ad2['website']) || !empty($ad2['department']) || !empty($ad2['designation']) || !empty($ad2['twitter']) || !empty($ad2['skype']) || !empty($ad2['facebook']))
        document.getElementById('moreDetailsSection').style.display = 'block';
        document.getElementById('addMoreBtn').textContent = '－ Show Less';
    @endif
});
</script>

</body>
</html>