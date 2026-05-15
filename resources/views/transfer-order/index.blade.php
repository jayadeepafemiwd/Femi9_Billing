@extends('layouts.app')

@section('title', 'Transfer Orders')

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f6fa; color: #333; font-size: 13px; }

.page-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 24px; border-bottom: 1px solid #e8e8e8; background: #fff;
}
.page-header h2 { font-size: 16px; font-weight: 600; color: #1a1a2e; }
.btn-new {
    display: inline-flex; align-items: center; gap: 6px;
    background: #2d7dd2; color: #fff; border: none;
    padding: 7px 16px; border-radius: 4px; font-size: 13px;
    font-weight: 500; cursor: pointer; text-decoration: none;
}
.btn-new:hover { background: #246bb8; color: #fff; }

.content-wrap { padding: 24px; }

.alert-success {
    background: #eafaf1; border: 1px solid #b7e4c7; border-radius: 4px;
    padding: 10px 14px; margin-bottom: 16px; color: #155724; font-size: 13px;
}
.alert-danger {
    background: #fdf0ee; border: 1px solid #f5c6cb; border-radius: 4px;
    padding: 10px 14px; margin-bottom: 16px; color: #721c24; font-size: 13px;
}

.table-card {
    background: #fff; border: 1px solid #e0e0e0; border-radius: 5px; overflow: hidden;
}

.table-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; border-bottom: 1px solid #e8e8e8; background: #fafafa;
}
.table-toolbar .count { font-size: 12px; color: #888; }
.search-input {
    padding: 5px 10px; border: 1px solid #d0d0d0; border-radius: 4px;
    font-size: 12px; outline: none; width: 220px;
}
.search-input:focus { border-color: #4a90d9; }

table { width: 100%; border-collapse: collapse; }
thead tr { background: #f5f6fa; }
th {
    padding: 9px 14px; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.4px; color: #777;
    text-align: left; border-bottom: 1px solid #e8e8e8;
    white-space: nowrap;
}
td {
    padding: 10px 14px; border-bottom: 1px solid #f0f0f0;
    font-size: 13px; vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: #fafcff; }

.to-number { font-weight: 600; color: #2d7dd2; }
.to-number a { color: inherit; text-decoration: none; }
.to-number a:hover { text-decoration: underline; }

.location-arrow { color: #aaa; font-size: 11px; margin: 0 4px; }

.badge {
    display: inline-block; padding: 2px 8px; border-radius: 10px;
    font-size: 11px; font-weight: 600; white-space: nowrap;
}
.badge-draft      { background: #f0f0f0; color: #666; }
.badge-initiated  { background: #ddeeff; color: #1a5fa0; }
.badge-completed  { background: #eafaf1; color: #1a7a40; }
.badge-cancelled  { background: #fdf0ee; color: #a94442; }

.row-actions { display: flex; gap: 6px; align-items: center; }
.btn-icon {
    background: none; border: none; cursor: pointer; padding: 4px 6px;
    border-radius: 3px; font-size: 13px; color: #888; transition: all 0.15s;
    text-decoration: none; display: inline-flex; align-items: center;
}
.btn-icon:hover { background: #eaf2fb; color: #2d7dd2; }
.btn-icon.del:hover { background: #fdf0ee; color: #e74c3c; }

.empty-state {
    text-align: center; padding: 48px 24px; color: #aaa;
}
.empty-state .icon { font-size: 36px; margin-bottom: 10px; }
.empty-state p { font-size: 13px; }

.pagination-wrap {
    display: flex; justify-content: flex-end; align-items: center;
    padding: 12px 16px; border-top: 1px solid #f0f0f0;
    background: #fafafa;
}
.pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; }
.pagination-wrap .page-item .page-link {
    display: inline-block; padding: 4px 10px; border: 1px solid #d0d0d0;
    border-radius: 3px; font-size: 12px; color: #555; text-decoration: none;
    background: #fff;
}
.pagination-wrap .page-item.active .page-link {
    background: #2d7dd2; border-color: #2d7dd2; color: #fff;
}
.pagination-wrap .page-item.disabled .page-link { color: #ccc; cursor: default; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h2>&#x21C4; Transfer Orders</h2>
    <a href="{{ route('transfer-orders.create') }}" class="btn-new">&#43; New Transfer Order</a>
</div>

<div class="content-wrap">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-card">
        <div class="table-toolbar">
            <span class="count">{{ $transferOrders->count() }} transfer order(s)</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Transfer Order #</th>
                    <th>Date</th>
                    <th>Source → Destination</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transferOrders as $order)
                <tr>
                    <td class="to-number">
                    <a href="{{ route('transfer-orders.show', $order->id) }}">
                            {{ $order->transfer_order_number }}
                        </a>
                    </td>
                    <td>{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d M Y') : '-' }}</td>
                    <td>
                        {{ $order->sourceLocation->location_name ?? '-' }}
                        <span class="location-arrow">&#8594;</span>
                        {{ $order->destinationLocation->location_name ?? '-' }}
                    </td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $order->reason ?: '-' }}
                    </td>
                    <td>
                @php
    $badgeClass = match($order->status) {
        'draft'       => 'badge-draft',
        'initiated'   => 'badge-initiated',
        'transferred' => 'badge-completed',
        'completed'   => 'badge-completed',
        'cancelled'   => 'badge-cancelled',
        default       => 'badge-draft',
    };
@endphp
<span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td>
                        <div class="row-actions">
    {{-- ✅ ADD THIS --}}
    <a href="{{ route('transfer-orders.show', $order->id) }}"
       class="btn-icon" title="View">&#128065;</a>

    <a href="{{ route('transfer-orders.edit', $order->id) }}"
       class="btn-icon" title="Edit">&#9998;</a>

    @if($order->status === 'draft')
    <form method="POST" action="{{ route('transfer-orders.destroy', $order->id) }}"
          onsubmit="return confirm('Delete this draft order?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-icon del" title="Delete">&#128465;</button>
    </form>
    @endif
</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="icon">&#x21C4;</div>
                            <p>No transfer orders yet. <a href="{{ route('transfer-orders.create') }}">Create one</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($transferOrders->hasPages())
        <div class="pagination-wrap">
            {{ $transferOrders->links() }}
        </div>
        @endif
    </div>

</div>

@endsection