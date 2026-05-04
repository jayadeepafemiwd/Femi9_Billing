{{-- resources/views/products/history.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product History | Inventory</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }

    /* ── SIDEBAR ── */
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

    /* ── TOPBAR ── */
    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }

    /* ── MAIN ── */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; padding: 28px 32px; }

    /* ── PAGE HEADER ── */
    .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 24px; }
    .page-header a.btn-back {
      display: inline-flex; align-items: center; gap: 6px;
      background: #f0f4ff; color: #2d5be3; border: 1px solid #c7d6fb;
      border-radius: 6px; padding: 7px 14px; font-size: 13px; font-weight: 600;
      text-decoration: none; transition: background 0.15s;
    }
    .page-header a.btn-back:hover { background: #dce8ff; }
    .page-header h2 { font-size: 20px; font-weight: 700; color: #1a2340; }
    .page-header .product-badge {
      background: #e6f0ff; color: #2d5be3; border-radius: 20px;
      padding: 3px 12px; font-size: 12px; font-weight: 600;
    }

    /* ── CARD ── */
    .table-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); overflow: hidden; }

    /* ── TIMELINE TABLE ── */
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8fafd; }
    th {
      text-align: left; padding: 12px 16px;
      color: #4a5568; font-weight: 600; font-size: 12px;
      text-transform: uppercase; letter-spacing: 0.5px;
      border-bottom: 2px solid #e2e8f0;
    }
    td { padding: 14px 16px; border-bottom: 1px solid #f0f2f7; vertical-align: top; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #fafbff; }

    /* ── DATE CELL ── */
    .date-cell { white-space: nowrap; }
    .date-main { font-weight: 600; color: #2d3748; font-size: 13px; }
    .date-time { color: #9aa5b8; font-size: 11px; margin-top: 2px; }

    /* ── ACTION BADGE ── */
    .action-badge {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
      white-space: nowrap;
    }
    .action-create   { background: #e6f7e6; color: #10b981; }
    .action-update   { background: #fff3e6; color: #d97706; }
    .action-stock    { background: #e6f0ff; color: #2d5be3; }
    .action-delete   { background: #fee9e9; color: #ef4444; }
    .action-default  { background: #f0f0f0; color: #666; }

    /* ── USER CELL ── */
    .user-chip {
      display: inline-flex; align-items: center; gap: 6px;
      background: #f5f6fa; border-radius: 20px; padding: 3px 10px 3px 4px;
      font-size: 12px; font-weight: 600; color: #4a5568;
    }
    .user-avatar {
      width: 22px; height: 22px; border-radius: 50%;
      background: #2d5be3; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 10px; font-weight: 700;
    }
    .user-avatar.system { background: #94a3b8; }

    /* ── DATA DIFF CELLS ── */
    .diff-box {
      background: #f8fafd; border: 1px solid #e8ecf4;
      border-radius: 8px; padding: 10px 12px;
      font-size: 12px; font-family: 'Consolas', 'Courier New', monospace;
      color: #4a5568; min-width: 200px; max-width: 320px;
      white-space: pre-wrap; word-break: break-word; line-height: 1.6;
    }
    .diff-box.old { border-left: 3px solid #f87171; background: #fff8f8; }
    .diff-box.new { border-left: 3px solid #34d399; background: #f7fff9; }
    .diff-null { color: #c0c4cd; font-style: italic; font-size: 12px; }

    /* ── DIFF KEY-VALUE RENDERER ── */
    .diff-kv { display: flex; flex-direction: column; gap: 4px; }
    .diff-row { display: flex; gap: 6px; font-size: 12px; font-family: 'Consolas', monospace; }
    .diff-key { color: #7c8db0; font-weight: 600; flex-shrink: 0; }
    .diff-val { color: #2d3748; }
    .diff-val.changed-old { color: #ef4444; text-decoration: line-through; }
    .diff-val.changed-new { color: #10b981; font-weight: 600; }

    /* ── EMPTY STATE ── */
    .empty-row td { text-align: center; padding: 60px; color: #9aa5b8; font-size: 14px; }

    /* ── PAGINATION ── */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f0f2f7; display: flex; justify-content: flex-end; }
    .pagination a, .pagination span { padding: 6px 11px; margin: 0 2px; border: 1px solid #e2e8f0; border-radius: 4px; text-decoration: none; color: #4a5568; font-size: 13px; }
    .pagination a:hover { background: #f8fafd; }
    .pagination .active { background: #2d5be3; color: #fff; border-color: #2d5be3; }
  </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<div class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">I</div>
    <span>Inventory</span>
  </div>
  <div class="sidebar-menu">
    <div class="sidebar-item"><span>🏠</span><span>Home</span></div>
    <div class="sidebar-item active"><span>📦</span><span>Items</span><span class="arrow">▼</span></div>
    <div class="sidebar-sub">
      <div class="sidebar-sub-active" onclick="window.location='{{ route('products.index') }}'">Items +</div>
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

{{-- ── MAIN ── --}}
<div class="main">

  {{-- TOPBAR --}}
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

  {{-- CONTENT --}}
  <div class="content">

    {{-- PAGE HEADER --}}
    <div class="page-header">
      <a href="{{ route('products.show', $product->id) }}" class="btn-back">← Back</a>
      <h2>History</h2>
      <span class="product-badge">📦 {{ $product->name }}</span>
    </div>

    {{-- TABLE CARD --}}
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width:150px;">Date</th>
            <th style="width:150px;">Action</th>
            <th style="width:120px;">User</th>
            <th>Old Data</th>
            <th>New Data</th>
          </tr>
        </thead>
        <tbody>
          @forelse($histories as $history)
          @php
            $action = strtolower($history->action ?? '');
            $badgeClass = match(true) {
              str_contains($action, 'create') => 'action-create',
              str_contains($action, 'stock')  => 'action-stock',
              str_contains($action, 'update') => 'action-update',
              str_contains($action, 'delete') => 'action-delete',
              default                         => 'action-default',
            };
            $actionIcon = match(true) {
              str_contains($action, 'create') => '✦',
              str_contains($action, 'stock')  => '📦',
              str_contains($action, 'update') => '✎',
              str_contains($action, 'delete') => '✕',
              default                         => '•',
            };
            $userName   = $history->user->name ?? 'System';
            $userInitial = strtoupper(substr($userName, 0, 1));
            $isSystem   = ($userName === 'System');

            $oldData = is_string($history->old_data)
              ? json_decode($history->old_data, true)
              : (is_array($history->old_data) ? $history->old_data : null);
            $newData = is_string($history->new_data)
              ? json_decode($history->new_data, true)
              : (is_array($history->new_data) ? $history->new_data : null);
          @endphp
          <tr>
            {{-- DATE --}}
            <td class="date-cell">
              <div class="date-main">{{ $history->created_at->format('d M Y') }}</div>
              <div class="date-time">{{ $history->created_at->format('h:i A') }}</div>
            </td>

            {{-- ACTION --}}
            <td>
              <span class="action-badge {{ $badgeClass }}">
                {{ $actionIcon }} {{ ucfirst(str_replace('_', ' ', $history->action)) }}
              </span>
            </td>

            {{-- USER --}}
            <td>
              <span class="user-chip">
                <span class="user-avatar {{ $isSystem ? 'system' : '' }}">{{ $userInitial }}</span>
                {{ $userName }}
              </span>
            </td>

            {{-- OLD DATA --}}
            <td>
              @if($oldData)
                <div class="diff-box old">@include('products._history_data', ['data' => $oldData, 'compare' => $newData, 'side' => 'old'])</div>
              @else
                <span class="diff-null">— null —</span>
              @endif
            </td>

            {{-- NEW DATA --}}
            <td>
              @if($newData)
                <div class="diff-box new">@include('products._history_data', ['data' => $newData, 'compare' => $oldData, 'side' => 'new'])</div>
              @else
                <span class="diff-null">— null —</span>
              @endif
            </td>
          </tr>
          @empty
          <tr class="empty-row">
            <td colspan="5">📭 No history records found for this product.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      @if(method_exists($histories, 'links') && $histories->lastPage() > 1)
      <div class="pagination-wrap">
        <div class="pagination">{{ $histories->links() }}</div>
      </div>
      @endif
    </div>

  </div>{{-- /content --}}
</div>{{-- /main --}}

</body>
</html>