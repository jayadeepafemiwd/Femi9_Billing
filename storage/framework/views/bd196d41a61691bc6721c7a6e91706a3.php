
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo e($displayProductName ?? $product->name); ?> | Inventory</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }

    .sidebar { width: 220px; background: #1a2340; color: #b0b8cc; display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-logo { padding: 18px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #2a3556; }
    .sidebar-logo-icon { width: 32px; height: 32px; background: #2d5be3; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .sidebar-logo span { color: #fff; font-weight: 600; font-size: 16px; }
    .sidebar-menu { flex: 1; overflow-y: auto; padding: 10px 0; }
    .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; cursor: pointer; color: #b0b8cc; transition: background 0.15s; }
    .sidebar-item:hover { background: #243060; }
    .sidebar-item.active { background: #2d5be3; color: #fff; font-weight: 600; }
    .sidebar-item .arrow { margin-left: auto; font-size: 11px; }
    .sidebar-sub { padding-left: 48px; padding-bottom: 4px; }
    .sidebar-sub-active { color: #2d5be3; font-size: 13px; padding: 5px 10px; font-weight: 600; background: #1e2d52; border-radius: 4px; cursor: pointer; }
    .sidebar-sub-item { color: #b0b8cc; font-size: 13px; padding: 5px 10px; cursor: pointer; }
    .sidebar-apps-label { padding: 14px 20px 4px; color: #6b7a99; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
    .sidebar-collapse { padding: 10px 20px; border-top: 1px solid #2a3556; color: #6b7a99; font-size: 12px; cursor: pointer; }

    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }

    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; padding: 0; display: flex; }

    .product-list-panel { width: 280px; background: #fff; border-right: 1px solid #e0e3ea; display: flex; flex-direction: column; flex-shrink: 0; overflow: hidden; }
    .panel-header { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e0e3ea; }
    .panel-header h3 { font-size: 14px; font-weight: 600; color: #333; }
    .panel-actions { display: flex; gap: 8px; }
    .panel-btn { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 10px; font-size: 12px; cursor: pointer; }
    .panel-btn-icon { background: #f0f0f0; border: none; border-radius: 5px; padding: 5px 8px; cursor: pointer; font-size: 13px; }
    .product-list { flex: 1; overflow-y: auto; }
    .product-list-item { padding: 12px 16px; border-bottom: 1px solid #f0f2f7; cursor: pointer; transition: all 0.2s ease; }
    .product-list-item:hover { background: #f8fafd; transform: translateX(2px); }
    .product-list-item.active { background: #eef2ff; border-left: 3px solid #2d5be3; }
    .product-list-item .pname { font-weight: 600; font-size: 13px; color: #333; }
    .product-list-item .psku { font-size: 12px; color: #888; margin-top: 2px; }
    .product-list-item .pprice { font-size: 13px; color: #333; float: right; font-weight: 500; }

    .detail-panel { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: #fff; }
    .detail-header { padding: 16px 24px; border-bottom: 1px solid #e0e3ea; display: flex; align-items: center; justify-content: space-between; }
    .detail-header h1 { font-size: 22px; font-weight: 700; }
    .detail-header-actions { display: flex; gap: 10px; align-items: center; }
    .btn-adjust { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .btn-more { background: #fff; color: #333; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .btn-close { background: none; border: none; font-size: 20px; color: #888; cursor: pointer; }
    .btn-edit-icon { background: none; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 10px; cursor: pointer; font-size: 14px; color: #555; }
    .returnable-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: #555; margin-top: 4px; }
    .returnable-badge span { color: #2d5be3; }

    .tabs { display: flex; padding: 0 24px; border-bottom: 1px solid #e0e3ea; }
    .tab { padding: 12px 20px; cursor: pointer; font-size: 14px; color: #666; border-bottom: 2px solid transparent; margin-bottom: -1px; transition: color 0.15s; }
    .tab:hover { color: #2d5be3; }
    .tab.active { color: #2d5be3; border-bottom-color: #2d5be3; font-weight: 600; }

    .tab-content { flex: 1; overflow-y: auto; padding: 24px; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    .section-title { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #222; }
    .detail-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f0f2f7; }
    .detail-label { width: 180px; color: #888; font-size: 13px; flex-shrink: 0; }
    .detail-value { color: #333; font-size: 13px; font-weight: 500; }

    .images-section { margin-top: 20px; }
    .images-grid { display: flex; gap: 16px; flex-wrap: wrap; }
    .image-card { border: 1px solid #e0e3ea; border-radius: 8px; padding: 10px; width: 140px; text-align: center; position: relative; }
    .image-card img { width: 100%; height: 80px; object-fit: contain; border-radius: 4px; }
    .image-card .img-label { font-size: 11px; color: #888; margin-top: 6px; }
    .image-card .img-del { position: absolute; top: 6px; right: 6px; background: #fee9e9; color: #ef4444; border: none; border-radius: 4px; width: 22px; height: 22px; cursor: pointer; font-size: 12px; }
    .image-upload-card { border: 2px dashed #d0d4de; border-radius: 8px; width: 140px; height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; color: #2d5be3; }
    .image-upload-card .up-arrow { font-size: 22px; }
    .image-upload-card p { font-size: 11px; color: #555; text-align: center; margin-top: 6px; }

    .opening-stock-box { background: #f0f4ff; border: 1px solid #c5d3f7; border-radius: 8px; padding: 14px 20px; margin-top: 20px; display: inline-flex; align-items: center; gap: 10px; }
    .opening-stock-box .os-label { font-size: 13px; color: #2d5be3; font-weight: 600; }
    .opening-stock-box .os-value { font-size: 18px; font-weight: 700; color: #1a2340; }

    .locations-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .locations-table th { text-align: left; padding: 10px 14px; background: #f8fafd; color: #4a5568; font-weight: 600; border-bottom: 2px solid #e2e8f0; font-size: 13px; }
    .locations-table td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
    .locations-table tr:hover td { background: #f8fafd; }

    .transactions-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .transactions-table th { text-align: left; padding: 10px 14px; background: #f8fafd; color: #4a5568; font-weight: 600; border-bottom: 2px solid #e2e8f0; font-size: 13px; }
    .transactions-table td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
    .txn-in { color: #10b981; font-weight: 600; }
    .txn-out { color: #ef4444; font-weight: 600; }

    .history-list { margin-top: 10px; }
    .history-item { display: flex; gap: 14px; padding: 14px 0; border-bottom: 1px solid #f0f2f7; }
    .history-dot { width: 10px; height: 10px; border-radius: 50%; background: #2d5be3; margin-top: 4px; flex-shrink: 0; }
    .history-text { font-size: 13px; color: #333; }
    .history-time { font-size: 11px; color: #aaa; margin-top: 3px; }

    .no-bin-msg { text-align: center; padding: 50px 20px; color: #888; }
    .no-bin-msg .icon { font-size: 40px; margin-bottom: 12px; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px 16px; border-radius: 6px; margin-bottom: 14px; border: 1px solid #c3e6cb; font-size: 13px; }

    .os-dd-wrap { position: relative; }
    .os-dd-btn {
      width: 100%; padding: 7px 28px 7px 10px;
      border: 1px solid #d0d4de; border-radius: 6px;
      font-size: 13px; cursor: pointer; text-align: left; color: #333; outline: none;
      background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2.5'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 9px center;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .os-dd-btn.ph { color: #aaa; }
    .os-dd-panel {
      display: none; position: absolute; top: calc(100% + 3px); left: 0;
      width: 100%; min-width: 240px; background: #fff;
      border: 1px solid #d0d4de; border-radius: 6px;
      box-shadow: 0 6px 20px rgba(0,0,0,.12); z-index: 99999;
    }
    .os-dd-panel.show { display: block; }
    .os-dd-search {
      width: 100%; padding: 7px 10px 7px 28px;
      border: none; border-bottom: 1px solid #e0e3ea;
      font-size: 12px; outline: none;
      background: #f8fafd url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 8px center;
    }
    .os-dd-list { max-height: 160px; overflow-y: auto; }
    .os-dd-opt { padding: 9px 12px; font-size: 13px; cursor: pointer; color: #333; }
    .os-dd-opt:hover { background: #eef2ff; color: #2d5be3; }
    .os-dd-opt.sel { background: #2d5be3; color: #fff; }
    .os-dd-empty { padding: 12px; font-size: 12px; color: #aaa; text-align: center; }
    .os-num {
      width: 100%; padding: 7px 10px;
      border: 1px solid #d0d4de; border-radius: 6px;
      font-size: 13px; text-align: right; outline: none;
    }
    .os-num:focus { border-color: #2d5be3; }
    /* Contact Person Modal */
.cp-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:600; }
.cp-modal-overlay.open { display:flex; align-items:center; justify-content:center; }
.cp-modal { background:#fff; border-radius:8px; width:860px; max-width:95vw; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.22); }
.cp-modal-header { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid #e0e0e0; flex-shrink:0; }
.cp-modal-title { font-size:16px; font-weight:600; color:#222; }
.cp-modal-close { background:none; border:none; font-size:22px; color:#e53935; cursor:pointer; line-height:1; }
.cp-modal-body { flex:1; overflow-y:auto; padding:24px; display:flex; gap:24px; }
.cp-form-main { flex:1; }
.cp-form-row { display:flex; align-items:flex-start; gap:16px; margin-bottom:18px; }
.cp-form-label { width:140px; min-width:140px; font-size:13px; color:#555; padding-top:8px; }
.cp-form-fields { flex:1; display:flex; gap:8px; flex-wrap:wrap; }
.cp-form-fields input, .cp-form-fields select { padding:7px 10px; border:1px solid #d0d0d0; border-radius:4px; font-size:13px; color:#333; outline:none; }
.cp-form-fields input:focus, .cp-form-fields select:focus { border-color:#1a73e8; box-shadow:0 0 0 2px rgba(26,115,232,0.1); }
.cp-input-full { width:100%; padding:7px 10px; border:1px solid #d0d0d0; border-radius:4px; font-size:13px; color:#333; outline:none; }
.cp-input-full:focus { border-color:#1a73e8; box-shadow:0 0 0 2px rgba(26,115,232,0.1); }
.cp-phone-wrap { display:flex; gap:8px; width:100%; margin-bottom:8px; }
.cp-phone-code { width:85px; padding:7px 8px; border:1px solid #d0d0d0; border-radius:4px; font-size:13px; -webkit-appearance:none; appearance:none; background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E") no-repeat right 8px center; padding-right:22px; outline:none; }
.cp-phone-num { flex:1; padding:7px 10px; border:1px solid #d0d0d0; border-radius:4px; font-size:13px; outline:none; }
.cp-phone-num:focus { border-color:#1a73e8; }
.cp-skype-wrap { display:flex; align-items:center; gap:8px; width:100%; }
.cp-skype-icon { width:32px; height:32px; background:#00aff0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:14px; flex-shrink:0; }
.cp-portal-wrap { display:flex; align-items:flex-start; gap:10px; padding:16px 24px; border-top:1px solid #f0f0f0; }
.cp-portal-wrap input[type=checkbox] { width:16px; height:16px; margin-top:2px; cursor:pointer; flex-shrink:0; }
.cp-portal-text { font-size:13px; color:#333; line-height:1.5; }
.cp-portal-text a { color:#1a73e8; }
.cp-modal-footer { padding:14px 24px; border-top:1px solid #e0e0e0; display:flex; gap:10px; flex-shrink:0; }
.cp-img-panel { width:220px; flex-shrink:0; border:1px solid #e0e0e0; border-radius:6px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; padding:20px; min-height:180px; cursor:pointer; }
.cp-img-panel:hover { background:#f8f9ff; }
.cp-img-upload-icon { width:40px; height:40px; background:#1a73e8; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; }
.cp-img-panel p { font-size:12px; color:#555; text-align:center; line-height:1.6; }
.cp-img-panel a { color:#1a73e8; font-size:12px; text-decoration:underline; cursor:pointer; }
.cp-alert { display:none; margin:0 24px 0; padding:10px 14px; border-radius:4px; font-size:13px; }
  </style>
</head>
<body>

<?php
    $decRate = (int)($settings['decimal_rate'] ?? 2);
    $wStep   = $decRate > 0 ? '0.' . str_repeat('0', $decRate - 1) . '1' : '1';

    // Get current variant from session/URL
   $selectedVariantName = request()->query('variant'); // ?variant=3piece
$selectedVariant     = null;
$displayProductName  = $product->name;
$displaySku          = $product->sku;
$displaySellingPrice = $product->selling_price;
$displayCostPrice    = $product->cost_price;

if ($selectedVariantName && $product->variants_data) {
    $variantsData = is_string($product->variants_data)
        ? json_decode($product->variants_data, true)
        : $product->variants_data;

    foreach (($variantsData['variants'] ?? []) as $variant) {
        if ($variant['name'] === $selectedVariantName) {
            $selectedVariant     = $variant;
            $displayProductName  = $product->name . ' - ' . $variant['name'];
            $displaySku          = $variant['sku']           ?? $product->sku;
            $displaySellingPrice = $variant['selling_price'] ?? $product->selling_price;
            $displayCostPrice    = $variant['cost_price']    ?? $product->cost_price;
            break;
        }
    }
}
    
    // ⭐ additional_data JSON decode
    $addData  = is_array($product->additional_data)
        ? $product->additional_data
        : (json_decode($product->additional_data ?? '{}', true) ?? []);

    // ⭐ description JSON-
    $descData  = $addData['description'] ?? [];
    $itemsDesc = $descData['items_description']    ?? null;
    $salesDesc = $descData['sales_description']    ?? null;
    $purchDesc = $descData['purchase_description'] ?? null;

    // ⭐ account_details JSON-
    $accountDetails   = $addData['account_details'] ?? [];
    $inventoryAccount = $accountDetails['inventory_account'] ?? null;

    // ⭐ product_image JSON decode → URL
    $imgData = json_decode($product->product_image ?? '{}', true) ?? [];
    $imgPath = $imgData['front_image'] ?? null;
    $imgUrl  = null;
    if ($imgPath) {
        $imgUrl = str_starts_with($imgPath, 'storage:')
            ? asset('storage/' . str_replace('storage:', '', $imgPath))
            : asset($imgPath);
    }
?>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">I</div>
    <span>Inventory</span>
  </div>
  <div class="sidebar-menu">
    <div class="sidebar-item"><span>🏠</span><span>Home</span></div>
    <div class="sidebar-item active"><span>📦</span><span>Items</span><span class="arrow">▼</span></div>
    <div class="sidebar-sub">
      <div class="sidebar-sub-active">Items +</div>
      <div class="sidebar-sub-item">Price Lists</div>
    </div>
    <div class="sidebar-item"><span>🏪</span><span>Inventory</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>💼</span><span>Sales</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>🛒</span><span>Purchases</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>📊</span><span>Reports</span></div>
    <div class="sidebar-item"><span>📄</span><span>Documents</span></div>
    <div class="sidebar-item"><span>⚙️</span><span>Custom Modules</span><span class="arrow">▶</span></div>
    <div class="sidebar-apps-label">APPS</div>
    <div class="sidebar-item"><span>💳</span><span>Zoho Payments</span></div>
  </div>
  <div class="sidebar-collapse">◀ Collapse</div>
</div>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <div class="search-box">🔍 <span>Search in Items ( / )</span></div>
    <div class="topbar-right">
      <span style="color:#e67e00;font-size:12px;">Your premi...</span>
      <button class="btn-subscribe">Subscribe</button>
      <span style="font-weight:600;">Jayadeepa ▼</span>
      <div class="topbar-avatar" style="background:#2d5be3;">+</div>
      <span style="cursor:pointer;">👥</span>
      <div class="notif-wrap">🔔<span class="notif-badge">1</span></div>
      <span style="cursor:pointer;">⚙️</span>
      <div class="topbar-avatar" style="background:#e74c3c;">J</div>
    </div>
  </div>

  <div class="content">

   <!-- LEFT: Product List -->
<div class="product-list-panel">
  <div class="panel-header">
    <h3>Active Items ▾</h3>
    <div class="panel-actions">
      <button class="panel-btn" onclick="window.location='<?php echo e(url('/products/create')); ?>'">+ New</button>
      <button class="panel-btn-icon">⋯</button>
    </div>
  </div>
  <div class="product-list">
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $isVariantParent = ($p->item_variant_type === 'contains_variants');
        $variants = [];
        
        if ($isVariantParent && $p->variants_data) {
            $variantsData = is_string($p->variants_data) 
                ? json_decode($p->variants_data, true) 
                : $p->variants_data;
            
            if (isset($variantsData['variants']) && is_array($variantsData['variants'])) {
                $variants = $variantsData['variants'];
            }
        }
      ?>
      
      <?php if(!$isVariantParent): ?>
        <!-- Regular Product (Single Item) -->
        <div class="product-list-item <?php echo e(($p->id == $product->id && !$selectedVariant) ? 'active' : ''); ?>" 
             onclick="window.location='<?php echo e(url('/products/' . $p->id)); ?>'">
          <span class="pprice">₹<?php echo e(number_format($p->selling_price ?? 0, 2)); ?></span>
          <div class="pname"><?php echo e($p->name); ?></div>
          <?php if($p->sku): ?>
          <div class="psku">SKU: <?php echo e($p->sku); ?></div>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <!-- Show each variant as separate item -->
        <?php if(count($variants) > 0): ?>
          <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $variantSku = $variant['sku'] ?? '';
              $variantSellingPrice = $variant['selling_price'] ?? $p->selling_price;
              $variantName = $variant['name'] ?? 'Variant';
              $isActive = (
    $p->id == $product->id &&
    $selectedVariantName !== null &&
    $variantName === $selectedVariantName
);
                ?>
          <div class="product-list-item <?php echo e($isActive ? 'active' : ''); ?>" 
     style="border-left: 3px solid #e8edff;"
     onclick="window.location='<?php echo e(url('/products/' . $p->id)); ?>?variant=<?php echo e(urlencode($variantName)); ?>'">
              <span class="pprice">₹<?php echo e(number_format($variantSellingPrice, 2)); ?></span>
              <div class="pname" style="font-weight: 500;">
                <?php echo e($p->name); ?> - <?php echo e($variantName); ?>

              </div>
              <?php if($variantSku): ?>
              <div class="psku" style="font-size: 11px;">SKU: <?php echo e($variantSku); ?></div>
              <?php endif; ?>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>

    <!-- RIGHT: Detail Panel -->
    <div class="detail-panel">
      <div class="detail-header">
        <div>
          <h1><?php echo e($displayProductName); ?></h1>
          <?php if($product->is_returnable): ?>
          <div class="returnable-badge"><span>↩</span> Returnable Item</div>
          <?php endif; ?>
        </div>
       <div class="detail-header-actions">
  <a href="<?php echo e(url('/products/' . $product->id . '/edit') . ($selectedVariant ? '?variant=' . urlencode($selectedVariant['name']) : '')); ?>" 
     class="btn-edit-icon" title="Edit">✏️</a>

<?php if($product->item_type === 'item'): ?>
    <button class="btn-adjust" onclick="openOpeningStock()">Adjust Stock</button>
<?php elseif($product->item_type === 'composite_item'): ?>
  <button class="btn-adjust" 
            style="background:#7c3aed;"
            onclick="window.location='<?php echo e(route('assemblies.create')); ?>?composite_item_id=<?php echo e($product->id); ?>'">
      🔩 Create Assemblies
    </button>
<?php endif; ?>

  <button class="btn-more">More ▾</button>
  <a href="<?php echo e(url('/products')); ?>" class="btn-close" title="Back">✕</a>
</div>
      </div>

      <!-- TABS -->
      <div class="tabs">
        <div class="tab active" onclick="switchTab('overview', this)">Overview</div>
        <?php if($product->track_inventory): ?>
        <div class="tab" onclick="switchTab('locations', this)">Locations</div>
        <?php endif; ?>
        <div class="tab" onclick="switchTab('transactions', this)">Transactions</div>
        <div class="tab" onclick="switchTab('history', this)">History</div>
      </div>

      <!-- TAB CONTENT -->
      <div class="tab-content">

        <?php if(session('success')): ?>
        <div class="alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <!-- OVERVIEW TAB -->
<div class="tab-pane active" id="tab-overview">
  <div style="display:flex;gap:24px;align-items:flex-start;">

    
    <div style="flex:1;min-width:0;">

      
      <div style="margin-bottom:24px;">
        <div class="section-title">Purchase Information</div>
        <div class="detail-row">
          <span class="detail-label">Cost Price</span>
          <span class="detail-value">₹<?php echo e(number_format($displayCostPrice ?? 0, 2)); ?></span>
        </div>
        <?php if($inventoryAccount): ?>
        <div class="detail-row">
          <span class="detail-label">Purchase Account</span>
          <span class="detail-value"><?php echo e($inventoryAccount); ?></span>
        </div>
        <?php endif; ?>
        <?php if($purchDesc): ?>
        <div class="detail-row">
          <span class="detail-label">Purchase Description</span>
          <span class="detail-value"><?php echo e($purchDesc); ?></span>
        </div>
        <?php endif; ?>
      </div>

      
      <div style="margin-bottom:24px;">
        <div class="section-title">Sales Information</div>
        <div class="detail-row">
          <span class="detail-label">Selling Price</span>
          <span class="detail-value">₹<?php echo e(number_format($displaySellingPrice ?? 0, 2)); ?></span>
        </div>
        <?php if($salesDesc): ?>
        <div class="detail-row">
          <span class="detail-label">Sales Description</span>
          <span class="detail-value"><?php echo e($salesDesc); ?></span>
        </div>
        <?php endif; ?>
      </div>

      
      <div style="margin-bottom:24px;">
        <div class="section-title">Item Details</div>
        <div class="detail-row">
          <span class="detail-label">Item Type</span>
          <span class="detail-value"><?php echo e($product->type == 'goods' ? 'Inventory Items' : 'Service'); ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">SKU</span>
          <span class="detail-value"><?php echo e($displaySku ?? '—'); ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Unit</span>
          <span class="detail-value"><?php echo e($product->unit); ?></span>
        </div>
        <?php if($product->brand): ?>
        <div class="detail-row">
          <span class="detail-label">Brand</span>
          <span class="detail-value"><?php echo e($product->brand); ?></span>
        </div>
        <?php endif; ?>
        <?php if($product->inventory_valuation_method): ?>
        <div class="detail-row">
          <span class="detail-label">Valuation Method</span>
          <span class="detail-value"><?php echo e($product->inventory_valuation_method); ?></span>
        </div>
        <?php endif; ?>
        <?php if($itemsDesc): ?>
        <div class="detail-row">
          <span class="detail-label">Description</span>
          <span class="detail-value"><?php echo e($itemsDesc); ?></span>
        </div>
        <?php endif; ?>
      </div>

      
      <div class="images-section">
        <div class="section-title">Images</div>
        <div class="images-grid">
          <?php if($imgUrl): ?>
          <div class="image-card">
            <img src="<?php echo e($imgUrl); ?>" alt="Front View">
            <div class="img-label">Front View</div>
          </div>
          <?php else: ?>
          <div class="image-card" style="background:#f8fafd;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:4px;">
            <span style="font-size:28px;color:#ccc;">🖼️</span>
            <div class="img-label">No Image</div>
          </div>
          <?php endif; ?>
          <div class="image-upload-card">
            <div class="up-arrow">⬆</div>
            <p>Drag & Drop Images<br><span style="color:#888;font-size:10px;">Up to 15 images, max 5 MB each</span></p>
          </div>
        </div>
      </div>
      
<?php if(in_array($product->type, ['assembly_item', 'kit_item']) && !empty($associateItemDetails)): ?>
<hr style="border:none;border-top:1px solid #e0e3ea;margin:20px 0;">
<div class="section-title">
<?php echo e($product->type === 'assembly_item' ? 'Assembly Components' : 'Kit Items'); ?>

</div>

<?php if(!empty($associateItemDetails['items'])): ?>
<div style="margin-bottom:16px;">
<div style="font-size:13px;font-weight:600;color:#333;margin-bottom:8px;">Items</div>
<table class="locations-table">
<thead>
<tr>
<th>Item Name</th>
<th>SKU</th>
<th style="text-align:right;">Qty</th>
<th style="text-align:right;">Selling Price</th>
<th style="text-align:right;">Cost Price</th>
<th style="text-align:right;">Line Total (SP)</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $associateItemDetails['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
<td><?php echo e($item['name'] ?? '—'); ?></td>
<td style="color:#888;font-size:12px;"><?php echo e($item['sku'] ?? '—'); ?></td>
<td style="text-align:right;"><?php echo e($item['quantity'] ?? 0); ?> <?php echo e($item['unit'] ?? ''); ?></td>
<td style="text-align:right;">₹<?php echo e(number_format($item['selling_price'] ?? 0, 2)); ?></td>
<td style="text-align:right;">₹<?php echo e(number_format($item['cost_price'] ?? 0, 2)); ?></td>
<td style="text-align:right;font-weight:600;">
₹<?php echo e(number_format($item['line_total_selling'] ?? 0, 2)); ?>

</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
</div>
<?php endif; ?>

<?php if(!empty($associateItemDetails['services'])): ?>
<div style="margin-bottom:16px;">
<div style="font-size:13px;font-weight:600;color:#333;margin-bottom:8px;">Services</div>
<table class="locations-table">
<thead>
<tr>
<th>Service Name</th>
<th>SKU</th>
<th style="text-align:right;">Qty</th>
<th style="text-align:right;">Selling Price</th>
<th style="text-align:right;">Cost Price</th>
<th style="text-align:right;">Line Total (SP)</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $associateItemDetails['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
<td><?php echo e($svc['name'] ?? '—'); ?></td>
<td style="color:#888;font-size:12px;"><?php echo e($svc['sku'] ?? '—'); ?></td>
<td style="text-align:right;"><?php echo e($svc['quantity'] ?? 0); ?> <?php echo e($svc['unit'] ?? ''); ?></td>
<td style="text-align:right;">₹<?php echo e(number_format($svc['selling_price'] ?? 0, 2)); ?></td>
<td style="text-align:right;">₹<?php echo e(number_format($svc['cost_price'] ?? 0, 2)); ?></td>
<td style="text-align:right;font-weight:600;">
₹<?php echo e(number_format($svc['line_total_selling'] ?? 0, 2)); ?>

</td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
</div>
<?php endif; ?>

<?php if(!empty($associateItemDetails['totals'])): ?>
<div style="display:flex;gap:16px;justify-content:flex-end;margin-top:8px;">
<div style="background:#e6f1fb;border-radius:6px;padding:8px 16px;font-size:13px;">
<span style="color:#888;">Total Selling: </span>
<strong style="color:#0c447c;">
₹<?php echo e(number_format($associateItemDetails['totals']['selling_price'] ?? 0, 2)); ?>

</strong>
</div>
<div style="background:#e1f5ee;border-radius:6px;padding:8px 16px;font-size:13px;">
<span style="color:#888;">Total Cost: </span>
<strong style="color:#085041;">
₹<?php echo e(number_format($associateItemDetails['totals']['cost_price'] ?? 0, 2)); ?>

</strong>
</div>
</div>
<?php endif; ?>
<?php endif; ?> 

    </div>

    
    <div style="width:220px;flex-shrink:0;">

      
      <div style="background:#fff;border:1px solid #e0e3ea;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;margin-bottom:6px;">
          <span>🏷️</span> Opening Stock
          <span style="color:#aaa;font-size:11px;">ⓘ</span>
        </div>
        <div class="os-value" style="font-size:22px;font-weight:700;color:#1a2340;">
          <?php echo e(number_format($product->opening_stock ?? 0, $decRate)); ?>

        </div>
      </div>

      
      <div style="background:#fff;border:1px solid #e0e3ea;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
        <div style="font-size:12px;font-weight:700;color:#333;margin-bottom:12px;display:flex;align-items:center;gap:4px;">
          Accounting Stock <span style="color:#aaa;font-size:11px;">ⓘ</span>
        </div>
        <?php
          $totalStockOnHand  = collect($stockLocations)->sum('stock_on_hand');
          $totalCommitted    = collect($stockLocations)->sum('committed');
          $totalAvailable    = collect($stockLocations)->sum('available');
        ?>
        <div style="margin-bottom:10px;">
          <div style="font-size:11px;color:#888;">Stock on Hand</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalStockOnHand, 2)); ?></div>
        </div>
        <div style="margin-bottom:10px;">
          <div style="font-size:11px;color:#888;">Committed Stock</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalCommitted, 2)); ?></div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;">Available for Sale</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalAvailable, 2)); ?></div>
        </div>
      </div>

      
      <div style="background:#fff;border:1px solid #e0e3ea;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
        <div style="font-size:12px;font-weight:700;color:#333;margin-bottom:12px;display:flex;align-items:center;gap:4px;">
          Physical Stock <span style="color:#aaa;font-size:11px;">ⓘ</span>
        </div>
        <div style="margin-bottom:10px;">
          <div style="font-size:11px;color:#888;">Stock on Hand</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalStockOnHand, 2)); ?></div>
        </div>
        <div style="margin-bottom:10px;">
          <div style="font-size:11px;color:#888;">Committed Stock</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalCommitted, 2)); ?></div>
        </div>
        <div>
          <div style="font-size:11px;color:#888;">Available for Sale</div>
          <div style="font-size:15px;font-weight:700;color:#1a2340;">: <?php echo e(number_format($totalAvailable, 2)); ?></div>
        </div>
      </div>

      
      <?php if($product->reorder_point): ?>
      <div style="background:#fff;border:1px solid #e0e3ea;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
        <div style="font-size:12px;color:#888;margin-bottom:4px;">Reorder Point</div>
        <div style="font-size:16px;font-weight:700;color:#1a2340;"><?php echo e(number_format($product->reorder_point, $decRate)); ?></div>
      </div>
      <?php else: ?>
      <div style="background:#fff9ee;border:1px solid #ffe9b0;border-radius:8px;padding:14px 16px;margin-bottom:16px;font-size:12px;color:#7a5c00;">
        <div style="font-weight:700;margin-bottom:4px;">Reorder Point</div>
        You have to enable reorder notification before setting reorder point for items.
        <a href="#" style="color:#2d5be3;text-decoration:none;">Click here</a>
      </div>
      <?php endif; ?>

    </div>
  </div>

  
  <div style="border:1px solid #e0e3ea;border-radius:8px;padding:16px 20px;margin-top:8px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <div style="font-size:14px;font-weight:700;color:#333;">Sales Order Summary (In INR)</div>
      <select style="border:1px solid #d0d4de;border-radius:5px;padding:4px 10px;font-size:12px;color:#555;background:#fff;">
        <option>This Month</option>
        <option>Last Month</option>
        <option>This Year</option>
      </select>
    </div>
    <div style="height:80px;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:13px;border-bottom:1px solid #f0f2f7;margin-bottom:12px;">
      No data found.
    </div>
    <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#555;font-weight:600;">
      <span style="width:10px;height:10px;border-radius:50%;background:#00bcd4;display:inline-block;"></span>
      Total Sales<br>
      <span style="color:#333;font-weight:700;">DIRECT SALES ₹0.00</span>
    </div>
  </div>

</div>

        <!-- LOCATIONS TAB -->
        <?php if($product->track_inventory): ?>
        <div class="tab-pane" id="tab-locations">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div class="section-title" style="margin:0;">Stock Locations</div>
            <div style="display:flex;gap:8px;align-items:center;">
              <div style="position:relative;">
                <button onclick="toggleGear()" style="border:1px solid #d0d4de;background:#fff;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:13px;">⚙ ▾</button>
                <div id="gear-menu" style="display:none;position:absolute;right:0;top:34px;background:#fff;border:1px solid #e0e3ea;border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,0.1);z-index:100;min-width:160px;">
                  <button onclick="openOpeningStock()" style="display:block;width:100%;padding:10px 16px;text-align:left;background:none;border:none;cursor:pointer;font-size:13px;color:#333;">Add Opening Stock</button>
                </div>
              </div>
              <div style="display:flex;border:1px solid #d0d4de;border-radius:6px;overflow:hidden;">
                <button id="btn-accounting" onclick="switchStock('accounting')" style="padding:6px 14px;border:none;background:#2d5be3;color:#fff;font-size:12px;font-weight:600;cursor:pointer;">Accounting Stock</button>
                <button id="btn-physical" onclick="switchStock('physical')" style="padding:6px 14px;border:none;background:#fff;color:#333;font-size:12px;font-weight:600;cursor:pointer;">Physical Stock</button>
                              </div>
            </div>
          </div>

          <table class="locations-table" id="stock-locations-table">
            <thead>
               <tr>
                <th>LOCATION NAME</th>
                <th style="text-align:right;">STOCK ON HAND</th>
                <th style="text-align:right;">COMMITTED STOCK</th>
                <th style="text-align:right;">AVAILABLE FOR SALE</th>
               </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $stockLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr data-loc-id="<?php echo e($loc['id']); ?>">
                <td>
                  <?php echo e($loc['location_name']); ?>

                  <?php if($loc['location_type'] === 'business'): ?>
                    <span style="color:#f59e0b;" title="Primary">★</span>
                  <?php endif; ?>
                </td>
                <td style="text-align:right;" class="td-stock"><?php echo e(number_format($loc['stock_on_hand'], $decRate)); ?></td>
                <td style="text-align:right;" class="td-committed"><?php echo e(number_format($loc['committed'], $decRate)); ?></td>
                <td style="text-align:right;" class="td-available"><?php echo e(number_format($loc['available'], $decRate)); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr id="empty-row">
                <td colspan="4" style="text-align:center;padding:40px;color:#888;">No locations found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
            <?php if(count($stockLocations) > 0): ?>
            <tfoot>
              <tr style="background:#f8fafd;font-weight:600;" id="total-row">
                <td>Total</td>
                <td style="text-align:right;" id="total-stock"><?php echo e(number_format(collect($stockLocations)->sum('stock_on_hand'), $decRate)); ?></td>
                <td style="text-align:right;" id="total-committed"><?php echo e(number_format(collect($stockLocations)->sum('committed'), $decRate)); ?></td>
                <td style="text-align:right;" id="total-available"><?php echo e(number_format(collect($stockLocations)->sum('available'), $decRate)); ?></td>
              </tr>
            </tfoot>
            <?php endif; ?>
          </table>
        </div>
        <?php endif; ?>

        <!-- TRANSACTIONS TAB -->
        <div class="tab-pane" id="tab-transactions">
          <div class="section-title">Transactions</div>
          <?php if(isset($transactions) && $transactions->count() > 0): ?>
          <table class="transactions-table">
            <thead>
               <tr>
                <th>Date</th><th>Type</th><th>Reference</th><th>Quantity</th><th>Balance</th>
               </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($txn->created_at->format('d M Y')); ?></td>
                <td><?php echo e($txn->type); ?></td>
                <td><?php echo e($txn->reference ?? '—'); ?></td>
                <td class="<?php echo e($txn->quantity > 0 ? 'txn-in' : 'txn-out'); ?>"><?php echo e($txn->quantity > 0 ? '+' : ''); ?><?php echo e(number_format($txn->quantity, 2)); ?></td>
                <td><?php echo e(number_format($txn->balance ?? 0, 2)); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          <?php else: ?>
          <div class="no-bin-msg">
            <div class="icon">📋</div>
            <p style="font-weight:600;color:#555;margin-bottom:6px;">No transactions yet</p>
            <p style="font-size:12px;">Transactions will appear here once stock movements happen.</p>
          </div>
          <?php endif; ?>
        </div>

       <!-- HISTORY TAB -->
<div class="tab-pane" id="tab-history">
  <div class="section-title">History</div>
  <?php if(isset($histories) && $histories->count() > 0): ?>
  <div class="history-list">
    <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="history-item">
      <div class="history-dot" style="background: <?php echo e($h->action == 'create' ? '#10b981' : ($h->action == 'delete' ? '#ef4444' : '#2d5be3')); ?>"></div>
      <div style="flex:1;">

        
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
          <span style="
            background: <?php echo e($h->action == 'create' ? '#d1fae5' : ($h->action == 'delete' ? '#fee2e2' : '#eef2ff')); ?>;
            color: <?php echo e($h->action == 'create' ? '#065f46' : ($h->action == 'delete' ? '#991b1b' : '#3730a3')); ?>;
            font-size:11px; font-weight:700; padding:2px 8px; border-radius:4px; text-transform:uppercase;
          "><?php echo e($h->action); ?></span>
          <span style="font-size:12px;color:#888;"><?php echo e($h->created_at->format('d M Y, h:i A')); ?></span>
          <span style="font-size:12px;color:#555;font-weight:600;">by <?php echo e($h->user->name ?? 'System'); ?></span>
        </div>

        
        <?php if($h->action == 'create'): ?>
          <div style="font-size:13px;color:#555;">Product created</div>

        
        <?php elseif($h->action == 'update' && $h->old_data && $h->new_data): ?>
          <table style="font-size:12px;border-collapse:collapse;width:100%;max-width:500px;">
            <thead>
              <tr style="background:#f8fafd;">
                <th style="padding:6px 10px;text-align:left;color:#888;font-weight:600;border-bottom:1px solid #e0e3ea;">Field</th>
                <th style="padding:6px 10px;text-align:left;color:#888;font-weight:600;border-bottom:1px solid #e0e3ea;">Old Value</th>
                <th style="padding:6px 10px;text-align:left;color:#888;font-weight:600;border-bottom:1px solid #e0e3ea;">New Value</th>
              </tr>
            </thead>
            <tbody>
             <?php $__currentLoopData = $h->new_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $newVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(in_array($field, ['updated_at', 'created_at', 'deleted_at'])): ?> <?php continue; ?> <?php endif; ?>
              <tr style="border-bottom:1px solid #f0f2f7;">
                <td style="padding:5px 10px;color:#555;font-weight:500;"><?php echo e($field); ?></td>
                <td style="padding:5px 10px;color:#ef4444;"><?php echo e($h->old_data[$field] ?? '—'); ?></td>
                <td style="padding:5px 10px;color:#10b981;"><?php echo e($newVal); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>

        
        <?php elseif($h->action == 'delete'): ?>
          <div style="font-size:13px;color:#ef4444;">Product deleted</div>
          <?php elseif($h->action === 'stock_updated'): ?>
<div style="font-size:13px;color:#555;margin-bottom:8px;">Opening stock updated</div>
<?php if(isset($h->new_data['locations'])): ?>
<table style="font-size:12px;border-collapse:collapse;width:100%;max-width:500px;">
  <thead>
    <tr style="background:#f8fafd;">
      <th style="padding:6px 10px;text-align:left;color:#888;border-bottom:1px solid #e0e3ea;">Location</th>
      <th style="padding:6px 10px;text-align:right;color:#ef4444;border-bottom:1px solid #e0e3ea;">Old Stock</th>
      <th style="padding:6px 10px;text-align:right;color:#10b981;border-bottom:1px solid #e0e3ea;">New Stock</th>
    </tr>
  </thead>
  <tbody>
    <?php $__currentLoopData = $h->new_data['locations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $oldLoc = $h->old_data['locations'][$i] ?? null; ?>
    <tr style="border-bottom:1px solid #f0f2f7;">
      <td style="padding:5px 10px;color:#555;"><?php echo e($loc['location']); ?></td>
      <td style="padding:5px 10px;color:#ef4444;text-align:right;">
        <?php echo e($oldLoc ? number_format($oldLoc['stock'], 2) : '0.00'); ?>

      </td>
      <td style="padding:5px 10px;color:#10b981;text-align:right;font-weight:600;">
        <?php echo e(number_format($loc['stock'], 2)); ?>

      </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tbody>
  <tfoot>
    <tr style="background:#f8fafd;">
      <td style="padding:5px 10px;font-weight:700;color:#333;">Total</td>
      <td style="padding:5px 10px;color:#ef4444;font-weight:700;text-align:right;">
        <?php echo e(number_format($h->old_data['total_stock'] ?? 0, 2)); ?>

      </td>
      <td style="padding:5px 10px;color:#2d5be3;font-weight:700;text-align:right;">
        <?php echo e(number_format($h->new_data['total_stock'], 2)); ?>

      </td>
    </tr>
  </tfoot>
</table>
<?php endif; ?>
        <?php endif; ?>

      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <?php else: ?>
  <div class="no-bin-msg">
    <div class="icon">🕐</div>
    <p style="font-weight:600;color:#555;margin-bottom:6px;">No history available</p>
    <p style="font-size:12px;">Changes to this item will be logged here.</p>
  </div>
  <?php endif; ?>
</div>

<!-- OPENING STOCK MODAL -->
<div id="osModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;justify-content:center;align-items:flex-start;padding-top:60px;">
  <div style="background:#fff;border-radius:8px;width:820px;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 8px 32px rgba(0,0,0,0.2);">
    <div style="padding:14px 24px;border-bottom:1px solid #e0e3ea;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
      <span style="font-size:16px;font-weight:700;color:#1a2340;"><?php echo e($product->name); ?></span>
      <button onclick="osClose()" style="background:none;border:none;font-size:22px;color:#888;cursor:pointer;line-height:1;">×</button>
    </div>
    <div id="osAlert" style="display:none;margin:12px 24px 0;padding:10px 14px;border-radius:4px;font-size:13px;"></div>
    <div style="padding:16px 24px;flex:1;overflow-y:auto;">
      <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
        <colgroup>
          <col style="width:40%"><col style="width:24%"><col style="width:28%"><col style="width:8%">
        </colgroup>
        <thead>
          <tr style="background:#f8fafd;border-bottom:2px solid #e2e8f0;">
            <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:700;color:#4a5568;letter-spacing:.5px;">LOCATION</th>
            <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:700;color:#4a5568;letter-spacing:.5px;">
              OPENING STOCK<br>
              <button onclick="osCopyAll('qty')" style="background:none;border:none;color:#2d5be3;font-size:10px;font-weight:700;cursor:pointer;padding:2px 0;">COPY TO ALL</button>
            </th>
            <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:700;color:#4a5568;letter-spacing:.5px;">
              VALUE PER UNIT<br>
              <button onclick="osCopyAll('val')" style="background:none;border:none;color:#2d5be3;font-size:10px;font-weight:700;cursor:pointer;padding:2px 0;">COPY TO ALL</button>
            </th>
            <th style="width:36px;"></th>
           </tr>
        </thead>
        <tbody id="osTbody"></tbody>
      </table>
      <button onclick="osAddRow()" style="margin-top:12px;background:none;border:none;color:#2d5be3;font-size:13px;font-weight:600;cursor:pointer;">+ New Row</button>
    </div>
    <div style="padding:12px 24px;border-top:1px solid #e0e3ea;display:flex;gap:10px;flex-shrink:0;">
      <button onclick="osSave()" id="osSaveBtn" style="background:#2d5be3;color:#fff;border:none;padding:8px 22px;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Save</button>
      <button onclick="osClose()" style="background:#fff;color:#333;border:1px solid #d0d4de;padding:8px 18px;border-radius:6px;font-size:13px;cursor:pointer;">Cancel</button>
    </div>
  </div>
</div>

<!-- ADD CONTACT PERSON MODAL -->
<div class="cp-modal-overlay" id="cpModalOverlay">
  <div class="cp-modal">
    <div class="cp-modal-header">
      <span class="cp-modal-title">Add Contact Person</span>
      <button class="cp-modal-close" onclick="closeCpModal()">×</button>
    </div>
    <div id="cpAlert" class="cp-alert"></div>
    <div class="cp-modal-body">
      <!-- Left: Form -->
      <div class="cp-form-main">
        <!-- Name -->
        <div class="cp-form-row">
          <div class="cp-form-label">Name</div>
          <div class="cp-form-fields" style="flex-wrap:nowrap;">
            <select id="cp_salutation" style="width:110px;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;-webkit-appearance:none;appearance:none;background:#fff url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E\") no-repeat right 8px center;padding-right:22px;outline:none;">
              <option value="">Salutation</option>
              <option>Mr.</option><option>Mrs.</option><option>Ms.</option><option>Miss.</option><option>Dr.</option>
            </select>
            <input type="text" id="cp_first_name" placeholder="First Name" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
            <input type="text" id="cp_last_name" placeholder="Last Name" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
          </div>
        </div>
        <!-- Email -->
        <div class="cp-form-row">
          <div class="cp-form-label">Email Address</div>
          <div style="flex:1;">
            <input type="email" id="cp_email" class="cp-input-full" placeholder="">
          </div>
        </div>
        <!-- Phone -->
        <div class="cp-form-row">
          <div class="cp-form-label">Phone</div>
          <div style="flex:1;">
            <div class="cp-phone-wrap">
              <select class="cp-phone-code" id="cp_work_code"><option>+91</option><option>+1</option><option>+44</option></select>
              <input type="text" id="cp_work_phone" class="cp-phone-num" placeholder="Work Phone">
            </div>
            
          </div>
        </div>
        <!-- Skype -->
        <div class="cp-form-row">
          <div class="cp-form-label">Skype Name/Number</div>
          <div style="flex:1;" class="cp-skype-wrap">
            <div class="cp-skype-icon">S</div>
            <input type="text" id="cp_skype" class="cp-input-full" placeholder="Skype Name/Number">
          </div>
        </div>
        <!-- Other Details -->
        <div class="cp-form-row">
          <div class="cp-form-label">Other Details</div>
          <div style="flex:1;display:flex;gap:8px;">
            <input type="text" id="cp_designation" placeholder="Designation" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
            <input type="text" id="cp_department" placeholder="Department" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
          </div>
        </div>
      </div>
      <!-- Right: Image Upload -->
      <div class="cp-img-panel" onclick="document.getElementById('cp_img_input').click()">
        <input type="file" id="cp_img_input" accept="image/*" style="display:none;" onchange="previewCpImage(this)">
        <div id="cp_img_preview" style="display:none;width:100%;text-align:center;">
          <img id="cp_img_tag" style="max-width:100%;max-height:120px;border-radius:6px;object-fit:contain;">
        </div>
        <div id="cp_img_placeholder">
          <div class="cp-img-upload-icon">⬆</div>
          <p><strong>Drag &amp; Drop Profile Image</strong><br>Supported Files: jpg, jpeg, png, gif, bmp<br>Maximum File Size: 5MB</p>
          <a>Upload File</a>
        </div>
      </div>
    </div>
    <!-- Enable Portal Access -->
    <div class="cp-portal-wrap">
      <input type="checkbox" id="cp_portal_access">
      <div class="cp-portal-text">
        <strong>Enable portal access</strong><br>
        This customer will be able to see all their transactions with your organization by logging in to the portal using their email address. <a href="#">Learn More</a>
      </div>
    </div>
    <div class="cp-modal-footer">
      <button class="btn-save-ap" onclick="saveCpModal()">Save</button>
      <button class="btn-cancel-ap" onclick="closeCpModal()">Cancel</button>
    </div>
  </div>
</div>


<script>
const _locs    = <?php echo json_encode($stockLocations ?? [], 15, 512) ?>;
const _pid     = <?php echo e($product->id); ?>;
const _decRate = <?php echo e($decRate); ?>;
const _wStep   = "<?php echo e($wStep); ?>";
let   _n       = 0;

function switchTab(name, el) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  const pane = document.getElementById('tab-' + name);
  if (pane) pane.classList.add('active');
}

function toggleGear() {
  const m = document.getElementById('gear-menu');
  if (m) m.style.display = m.style.display === 'none' ? 'block' : 'none';
}

function switchStock(type) {
  const isAcc = type === 'accounting';
  document.getElementById('btn-accounting').style.background = isAcc ? '#2d5be3' : '#fff';
  document.getElementById('btn-accounting').style.color      = isAcc ? '#fff'    : '#333';
  document.getElementById('btn-physical').style.background   = isAcc ? '#fff'    : '#2d5be3';
  document.getElementById('btn-physical').style.color        = isAcc ? '#333'    : '#fff';
}

function openOpeningStock() {
  const gm = document.getElementById('gear-menu');
  if (gm) gm.style.display = 'none';
  document.getElementById('osTbody').innerHTML = '';
  document.getElementById('osAlert').style.display = 'none';
  _n = 0;

  const withStock = _locs.filter(l => parseFloat(l.stock_on_hand || 0) > 0);
  
  if (withStock.length) {
    withStock.forEach(l => osAddRow(l.id, l.stock_on_hand, l.value_per_unit ?? 0));
  } else {
    // Stock — empty row
    osAddRow();
  }

  document.getElementById('osModal').style.display = 'flex';
}

function osClose() {
  document.getElementById('osModal').style.display = 'none';
  osCloseAllDD();
}

function osAddRow(locId='', qty='', val='') {
  _n++;
  const n = _n, rid = 'osR' + n;
  const sel = _locs.find(l => l.id == locId);
  const btnTxt = sel ? sel.location_name : '';
  const opts = _locs.map(l =>
    `<div class="os-dd-opt${l.id == locId ? ' sel' : ''}" onclick="osSelect(${n},${l.id},'${l.location_name.replace(/'/g,"\\'")}',this)">${l.location_name}</div>`
  ).join('') || '<div class="os-dd-empty">No locations</div>';
  const tr = document.createElement('tr');
  tr.id = rid;
  tr.style.borderBottom = '1px solid #f0f2f7';
  tr.innerHTML = `
    <td style="padding:8px 12px;">
      <div class="os-dd-wrap">
        <input type="hidden" class="osLoc" data-n="${n}" value="${locId}">
        <button type="button" class="os-dd-btn${btnTxt ? '' : ' ph'}" id="osB${n}" onclick="osToggleDD(event,${n})">${btnTxt || 'Select Location'}</button>
        <div class="os-dd-panel" id="osP${n}">
          <input class="os-dd-search" placeholder="Search..." oninput="osSearch(${n},this.value)">
          <div class="os-dd-list" id="osL${n}">${opts}</div>
        </div>
      </div>
    </td>
    <td style="padding:8px 12px;">
      <input type="number" class="os-num osQty" data-n="${n}" value="${qty}" placeholder="0" min="0" step="${_wStep}">
    </td>
    <td style="padding:8px 12px;">
      <input type="number" class="os-num osVal" data-n="${n}" value="${val}" placeholder="0.00" min="0" step="0.01">
    </td>
    <td style="padding:8px 12px;text-align:center;">
      <button onclick="document.getElementById('${rid}').remove()" style="background:#fee2e2;color:#ef4444;border:none;border-radius:4px;width:28px;height:28px;cursor:pointer;font-size:16px;line-height:1;">×</button>
    </td>`;
  document.getElementById('osTbody').appendChild(tr);
}

function osToggleDD(e, n) {
  e.stopPropagation();
  const p = document.getElementById('osP' + n);
  const isOpen = p.classList.contains('show');
  osCloseAllDD();
  if (!isOpen) { p.classList.add('show'); setTimeout(() => p.querySelector('.os-dd-search')?.focus(), 30); }
}

function osSelect(n, id, name, el) {
  document.querySelector(`.osLoc[data-n="${n}"]`).value = id;
  const btn = document.getElementById('osB' + n);
  btn.textContent = name; btn.classList.remove('ph');
  document.querySelectorAll(`#osL${n} .os-dd-opt`).forEach(o => o.classList.remove('sel'));
  el.classList.add('sel');
  osCloseAllDD();
}

function osSearch(n, q) {
  const list = document.getElementById('osL' + n);
  const selId = document.querySelector(`.osLoc[data-n="${n}"]`).value;
  const filtered = _locs.filter(l => l.location_name.toLowerCase().includes(q.toLowerCase()));
  list.innerHTML = filtered.length
    ? filtered.map(l => `<div class="os-dd-opt${l.id == selId ? ' sel' : ''}" onclick="osSelect(${n},${l.id},'${l.location_name.replace(/'/g,"\\'")}',this)">${l.location_name}</div>`).join('')
    : '<div class="os-dd-empty">No results</div>';
}

function osCloseAllDD() {
  document.querySelectorAll('.os-dd-panel.show').forEach(p => p.classList.remove('show'));
}

function osCopyAll(type) {
  const all = document.querySelectorAll(type === 'qty' ? '.osQty' : '.osVal');
  if (!all.length) return;
  const v = all[0].value;
  all.forEach(i => i.value = v);
}

function osSave() {
  const rows = [], used = [];
  let err = '';
  document.querySelectorAll('.osLoc').forEach(inp => {
    const n = inp.dataset.n;
    const locId = inp.value;
    const qty = document.querySelector(`.osQty[data-n="${n}"]`)?.value || 0;
    const val = document.querySelector(`.osVal[data-n="${n}"]`)?.value || 0;
    if (!locId) { err = 'Please select location for all rows'; return; }
    if (used.includes(locId)) { err = 'Duplicate location found'; return; }
    used.push(locId);
    rows.push({ bin_location_id: locId, quantity: qty, value_per_unit: val });
  });
  if (err) { osShowAlert(err, 'error'); return; }
  if (!rows.length) { osShowAlert('Please add at least one row', 'error'); return; }

  const btn = document.getElementById('osSaveBtn');
  btn.textContent = 'Saving...'; btn.disabled = true;

  fetch(`/products/${_pid}/opening-stock`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ rows })
  })
  .then(r => r.json())
  .then(data => {
    btn.textContent = 'Save'; btn.disabled = false;
    if (data.success) {
      osClose();
      if (data.stockLocations) osUpdateTable(data.stockLocations);
      if (data.total_stock !== undefined) updateOverviewStock(data.total_stock);
      osPageAlert('Opening stock saved!');
    } else {
      osShowAlert(data.message || 'Save failed', 'error');
    }
  })
  .catch(() => { btn.textContent = 'Save'; btn.disabled = false; osShowAlert('Server error', 'error'); });
}

function osUpdateTable(locs) {
  locs.forEach(l => {
    const row = document.querySelector(`#stock-locations-table tr[data-loc-id="${l.id}"]`);
    if (row) {
      row.querySelector('.td-stock').textContent     = parseFloat(l.stock_on_hand).toFixed(_decRate);
      row.querySelector('.td-committed').textContent = parseFloat(l.committed || 0).toFixed(_decRate);
      row.querySelector('.td-available').textContent = parseFloat(l.available).toFixed(_decRate);
    }
    const ex = _locs.find(x => x.id == l.id);
    if (ex) { ex.stock_on_hand = l.stock_on_hand; ex.available = l.available; }
  });
  const totalStock = _locs.reduce((s, l) => s + parseFloat(l.stock_on_hand || 0), 0);
  const totalAvail  = _locs.reduce((s, l) => s + parseFloat(l.available    || 0), 0);
  const ts = document.getElementById('total-stock');
  const ta = document.getElementById('total-available');
  if (ts) ts.textContent = totalStock.toFixed(_decRate);
  if (ta) ta.textContent = totalAvail.toFixed(_decRate);
  updateOverviewStock(totalStock);
}
function updateOverviewStock(totalStock) {
  const openingStockBox = document.querySelector('.opening-stock-box .os-value');
  if (openingStockBox) {
    openingStockBox.textContent = parseFloat(totalStock).toFixed(_decRate);
  }
   document.querySelectorAll('.os-value').forEach(el => {
    el.textContent = parseFloat(totalStock).toFixed(_decRate);
  });
}

function osShowAlert(msg, type) {
  const el = document.getElementById('osAlert');
  el.style.display    = 'block';
  el.style.background = type === 'error' ? '#fee2e2' : '#d4edda';
  el.style.color      = type === 'error' ? '#991b1b' : '#155724';
  el.style.border     = `1px solid ${type === 'error' ? '#fca5a5' : '#c3e6cb'}`;
  el.textContent = msg;
}

function osPageAlert(msg) {
  const d = document.createElement('div');
  d.style.cssText = 'position:fixed;top:20px;right:20px;background:#d4edda;color:#155724;padding:12px 22px;border-radius:6px;font-size:13px;font-weight:600;z-index:99999;box-shadow:0 4px 16px rgba(0,0,0,.15);';
  d.textContent = '✓ ' + msg;
  document.body.appendChild(d);
  setTimeout(() => d.remove(), 3000);
}

// Function to handle variant selection
function selectVariant(productId, variantName, variantSku, variantPrice) {
    const variantData = {
        product_id: productId,
        variant_name: variantName,
        sku: variantSku,
        price: variantPrice
    };
    
    localStorage.setItem('current_variant', JSON.stringify(variantData));
    
    fetch('<?php echo e(url("/products/set-variant")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(variantData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '<?php echo e(url("/products/" . $product->id)); ?>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.reload();
    });
}

// Function to clear variant selection
function clearVariantSelection() {
    localStorage.removeItem('current_variant');
    fetch('<?php echo e(url("/products/clear-variant")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(() => {
        window.location.reload();
    });
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('.os-dd-wrap')) osCloseAllDD();
  if (!e.target.closest('#gear-menu') && !e.target.closest('[onclick="toggleGear()"]')) {
    const gm = document.getElementById('gear-menu');
    if (gm) gm.style.display = 'none';
  }
});

document.getElementById('osModal').addEventListener('click', function(e) {
  if (e.target === this) osClose();
});

document.addEventListener('DOMContentLoaded', function() {
    // Highlight active variant in sidebar
    const storedVariant = localStorage.getItem('current_variant');
    if (storedVariant) {
        const variant = JSON.parse(storedVariant);
        document.querySelectorAll('.product-list-item').forEach(item => {
            if (item.textContent.includes(variant.variant_name)) {
                item.classList.add('active');
            }
        });
    }
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/show.blade.php ENDPATH**/ ?>