{{-- resources/views/products/pricelisthistory.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $priceList->name }} — History | Inventory</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }

    /* ── Sidebar ── */
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

    /* ── Topbar ── */
    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }

    /* ── Layout ── */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; padding: 24px; }

    /* ── Page header ── */
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .page-header-left { display: flex; align-items: center; gap: 12px; }
    .back-btn { display: inline-flex; align-items: center; gap: 6px; color: #378ADD; text-decoration: none; font-size: 13px; font-weight: 600; padding: 6px 12px; border: 1px solid #c5d8f7; border-radius: 6px; background: #EAF4FD; transition: background 0.15s; }
    .back-btn:hover { background: #d0e9fb; }
    .page-title { font-size: 20px; font-weight: 700; color: #1a2340; }
    .page-subtitle { font-size: 13px; color: #888; margin-top: 2px; }
    .pl-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
    .pl-badge-sales     { background: #e6f7ee; color: #1a7a3f; }
    .pl-badge-purchase  { background: #fff3e0; color: #c76b00; }
    .pl-badge-both      { background: #f3f0ff; color: #6c47d4; }

    /* ── History list ── */
    .history-wrap { max-width: 860px; }
    .history-item { display: flex; gap: 16px; padding: 16px 0; border-bottom: 1px solid #f0f2f7; }
    .history-dot-col { display: flex; flex-direction: column; align-items: center; gap: 0; }
    .history-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; margin-top: 3px; }
    .history-line { flex: 1; width: 2px; background: #e8ecf5; margin-top: 4px; }
    .history-body { flex: 1; }

    /* action badge */
    .action-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 4px; text-transform: uppercase; letter-spacing: .4px; }
    .badge-create       { background: #d1fae5; color: #065f46; }
    .badge-update       { background: #eef2ff; color: #3730a3; }
    .badge-delete       { background: #fee2e2; color: #991b1b; }
    .badge-items_added  { background: #e0f2fe; color: #0369a1; }
    .badge-items_updated{ background: #fef9c3; color: #854d0e; }

    .history-meta { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap; }
    .history-time { font-size: 12px; color: #888; }
    .history-user { font-size: 12px; color: #555; font-weight: 600; }

    /* diff table */
    .diff-table { width: 100%; max-width: 600px; border-collapse: collapse; font-size: 12px; border-radius: 6px; overflow: hidden; border: 1px solid #e8ecf5; margin-top: 4px; }
    .diff-table th { padding: 7px 12px; text-align: left; background: #f8fafd; color: #666; font-weight: 600; border-bottom: 1px solid #e8ecf5; }
    .diff-table td { padding: 6px 12px; border-bottom: 1px solid #f0f2f7; color: #333; vertical-align: top; }
    .diff-table tr:last-child td { border-bottom: none; }
    .old-val { color: #ef4444; }
    .new-val { color: #10b981; font-weight: 600; }
    .field-name { color: #555; font-weight: 500; }

    /* items table */
    .items-table { width: 100%; max-width: 640px; border-collapse: collapse; font-size: 12px; border-radius: 6px; overflow: hidden; border: 1px solid #e8ecf5; margin-top: 4px; }
    .items-table th { padding: 7px 12px; background: #f8fafd; color: #666; font-weight: 600; border-bottom: 1px solid #e8ecf5; text-align: left; }
    .items-table td { padding: 6px 12px; border-bottom: 1px solid #f0f2f7; color: #333; }
    .items-table tr:last-child td { border-bottom: none; }

    /* empty */
    .empty-history { text-align: center; padding: 60px 20px; color: #aaa; }
    .empty-history .icon { font-size: 42px; margin-bottom: 14px; }
    .empty-history p { font-size: 14px; }

    /* label mapping — human-readable field names */
  </style>
</head>
<body>

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
      <div class="sidebar-sub-item">Items +</div>
      <div class="sidebar-sub-active">Price Lists</div>
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

  <!-- Topbar -->
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

  <!-- Content -->
  <div class="content">

    <!-- Page Header -->
    <div class="page-header">
      <div class="page-header-left">
        <a href="{{ route('price-lists.index') }}" class="back-btn">← Back to Price Lists</a>
        <div>
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="page-title">{{ $priceList->name }}</div>
            @php
              $txClass = match($priceList->transaction_type) {
                'sales'    => 'pl-badge-sales',
                'purchase' => 'pl-badge-purchase',
                default    => 'pl-badge-both',
              };
            @endphp
            <span class="pl-badge {{ $txClass }}">{{ ucfirst($priceList->transaction_type) }}</span>
            @if($priceList->trashed())
              <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;">DELETED</span>
            @endif
          </div>
          <div class="page-subtitle">Price List History — all changes tracked below</div>
        </div>
      </div>

      <div style="display:flex;gap:8px;">
        @if(!$priceList->trashed())
          <a href="{{ route('price-lists.edit', $priceList->id) }}"
             style="display:inline-flex;align-items:center;gap:6px;padding:7px 16px;background:#378ADD;color:#fff;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">
            ✏️ Edit
          </a>
        @endif
      </div>
    </div>

    <!-- History Timeline -->
    <div class="history-wrap">
      @if($histories->isEmpty())
        <div class="empty-history">
          <div class="icon">🕐</div>
          <p style="font-weight:600;color:#555;margin-bottom:6px;">No history available</p>
          <p style="font-size:12px;">Changes to this price list will be logged here.</p>
        </div>
      @else
        @foreach($histories as $h)
        <div class="history-item">
          <!-- Dot + line -->
          <div class="history-dot-col">
            @php
              $dotColor = match($h->action) {
                'create'         => '#10b981',
                'delete'         => '#ef4444',
                'items_added'    => '#0ea5e9',
                'items_updated'  => '#f59e0b',
                default          => '#2d5be3',
              };
            @endphp
            <div class="history-dot" style="background:{{ $dotColor }};"></div>
            @if(!$loop->last)
              <div class="history-line"></div>
            @endif
          </div>

          <!-- Body -->
          <div class="history-body">
            <!-- Meta row -->
            <div class="history-meta">
              @php
                $badgeClass = match($h->action) {
                  'create'         => 'badge-create',
                  'delete'         => 'badge-delete',
                  'items_added'    => 'badge-items_added',
                  'items_updated'  => 'badge-items_updated',
                  default          => 'badge-update',
                };
                $actionLabel = match($h->action) {
                  'create'         => '✅ Created',
                  'update'         => '✏️ Updated',
                  'delete'         => '🗑 Deleted',
                  'items_added'    => '📋 Items Added',
                  'items_updated'  => '📝 Items Updated',
                  default          => ucfirst($h->action),
                };
              @endphp
              <span class="action-badge {{ $badgeClass }}">{{ $actionLabel }}</span>
              <span class="history-time">{{ $h->created_at->format('d M Y, h:i A') }}</span>
              <span class="history-user">by {{ $h->user->name ?? 'System' }}</span>
            </div>

            {{-- ── CREATE ── --}}
            @if($h->action === 'create')
              <div style="font-size:13px;color:#555;">Price list <strong>"{{ $h->new_data['name'] ?? '' }}"</strong> created
                @if(isset($h->new_data['transaction_type']))
                  as <strong>{{ ucfirst($h->new_data['transaction_type']) }}</strong> /
                  <strong>{{ $h->new_data['price_list_type'] === 'all_items' ? 'All Items' : 'Individual Items' }}</strong>
                @endif
              </div>

            {{-- ── UPDATE ── --}}
            @elseif($h->action === 'update' && $h->old_data && $h->new_data)
              @php
                $fieldLabels = [
                  'name'             => 'Name',
                  'transaction_type' => 'Transaction Type',
                  'price_list_type'  => 'Price List Type',
                  'description'      => 'Description',
                  'category_id'      => 'Category ID',
                  'category_name'    => 'Category',
                  'markup_type'      => 'Markup Type',
                  'percentage'       => 'Percentage',
                  'round_off'        => 'Round Off',
                  'pricing_scheme'   => 'Pricing Scheme',
                  'currency'         => 'Currency',
                  'include_discount' => 'Include Discount',
                ];
              @endphp
              <table class="diff-table">
                <thead>
                  <tr>
                    <th style="width:30%;">Field</th>
                    <th style="width:35%;">Old Value</th>
                    <th style="width:35%;">New Value</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($h->new_data as $field => $newVal)
                    @if(in_array($field, ['updated_at','created_at','deleted_at'])) @continue @endif
                    <tr>
                      <td class="field-name">{{ $fieldLabels[$field] ?? ucwords(str_replace('_',' ',$field)) }}</td>
                      <td class="old-val">
                        @php $oldVal = $h->old_data[$field] ?? '—'; @endphp
                        @if(is_bool($oldVal))
                          {{ $oldVal ? 'Yes' : 'No' }}
                        @elseif(is_array($oldVal))
                          {{ json_encode($oldVal) }}
                        @else
                          {{ $oldVal !== null && $oldVal !== '' ? $oldVal : '—' }}
                        @endif
                      </td>
                      <td class="new-val">
                        @if(is_bool($newVal))
                          {{ $newVal ? 'Yes' : 'No' }}
                        @elseif(is_array($newVal))
                          {{ json_encode($newVal) }}
                        @else
                          {{ $newVal !== null && $newVal !== '' ? $newVal : '—' }}
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

            {{-- ── DELETE ── --}}
            @elseif($h->action === 'delete')
              <div style="font-size:13px;color:#ef4444;">
                Price list <strong>"{{ $h->old_data['name'] ?? '' }}"</strong> deleted.
              </div>

            {{-- ── ITEMS ADDED ── --}}
            @elseif($h->action === 'items_added' && !empty($h->new_data['items']))
              <div style="font-size:13px;color:#555;margin-bottom:8px;">
                {{ count($h->new_data['items']) }} item(s) added to price list.
              </div>
              <table class="items-table">
                <thead>
                  <tr>
                    <th>Item ID</th>
                    <th>Start Qty</th>
                    <th>End Qty</th>
                    <th>Custom Rate</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($h->new_data['items'] as $item)
                  <tr>
                    <td>{{ $item['item_id'] }}</td>
                    <td>{{ $item['start_quantity'] ?? '—' }}</td>
                    <td>{{ $item['end_quantity'] ?? '—' }}</td>
                    <td style="font-weight:600;color:#10b981;">
                      @if($item['custom_rate'] !== null)
                        ₹{{ number_format($item['custom_rate'], 2) }}
                      @else —
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

            {{-- ── ITEMS UPDATED ── --}}
            @elseif($h->action === 'items_updated')
              <div style="font-size:13px;color:#555;margin-bottom:8px;">Item rates updated.</div>
              <div style="display:flex;gap:16px;flex-wrap:wrap;">
                {{-- Old --}}
                @if(!empty($h->old_data['items']))
                <div style="flex:1;min-width:280px;">
                  <div style="font-size:11px;font-weight:700;color:#ef4444;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Before</div>
                  <table class="items-table">
                    <thead><tr><th>Item ID</th><th>Start Qty</th><th>End Qty</th><th>Rate</th></tr></thead>
                    <tbody>
                      @foreach($h->old_data['items'] as $item)
                      <tr>
                        <td>{{ $item['item_id'] }}</td>
                        <td>{{ $item['start_quantity'] ?? '—' }}</td>
                        <td>{{ $item['end_quantity'] ?? '—' }}</td>
                        <td class="old-val">{{ $item['custom_rate'] !== null ? '₹'.number_format($item['custom_rate'],2) : '—' }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @endif
                {{-- New --}}
                @if(!empty($h->new_data['items']))
                <div style="flex:1;min-width:280px;">
                  <div style="font-size:11px;font-weight:700;color:#10b981;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">After</div>
                  <table class="items-table">
                    <thead><tr><th>Item ID</th><th>Start Qty</th><th>End Qty</th><th>Rate</th></tr></thead>
                    <tbody>
                      @foreach($h->new_data['items'] as $item)
                      <tr>
                        <td>{{ $item['item_id'] }}</td>
                        <td>{{ $item['start_quantity'] ?? '—' }}</td>
                        <td>{{ $item['end_quantity'] ?? '—' }}</td>
                        <td class="new-val">{{ $item['custom_rate'] !== null ? '₹'.number_format($item['custom_rate'],2) : '—' }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @endif
              </div>

            @endif
          </div>
        </div>
        @endforeach
      @endif
    </div>

  </div>
</div>

</body>
</html>