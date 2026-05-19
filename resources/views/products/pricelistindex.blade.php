@extends('layouts.app')

@section('title', 'Price Lists')

@section('breadcrumb')
    <a href="{{ route('products.index') }}">Products</a>
    <span class="sep">›</span>
    <span class="current">Price Lists</span>
@endsection

@push('styles')
<style>
.pl-wrap { padding: 24px; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
.page-header h2 { font-size:20px; font-weight:700; color:var(--text); }
.btn-new { background:var(--pink); color:#fff; border:none; border-radius:6px; padding:8px 18px; font-size:13px; cursor:pointer; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-new:hover { background:var(--pink-dk); color:#fff; }
.card { background:#fff; border-radius:10px; border:1px solid var(--border); overflow:hidden; }
table { width:100%; border-collapse:collapse; font-size:13px; }
th { background:var(--bg); padding:10px 14px; text-align:left; font-weight:600; font-size:12px; color:var(--muted); border-bottom:2px solid var(--border); text-transform:uppercase; letter-spacing:0.3px; }
td { padding:10px 14px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:var(--pink-xlt); }
.badge { display:inline-block; font-size:11px; border-radius:4px; padding:2px 8px; font-weight:600; }
.badge-sales    { background:#e6f7ee; color:#1a7a3f; }
.badge-purchase { background:#fff3e0; color:#c76b00; }
.badge-both     { background:#f3f0ff; color:#6c47d4; }
.badge-all      { background:var(--pink-lt); color:var(--pink-dk); }
.badge-ind      { background:#f3f0ff; color:#6c47d4; }
.actions { display:flex; align-items:center; gap:4px; }
.action-btn { background:none; border:none; cursor:pointer; font-size:12px; padding:5px 10px; border-radius:5px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
.btn-edit    { color:var(--pink); }
.btn-edit:hover { background:var(--pink-lt); }
.btn-history { color:#7c3aed; }
.btn-history:hover { background:#f3f0ff; }
.btn-del     { color:#d44; }
.btn-del:hover { background:#fdecea; }
.divider-v { width:1px; height:16px; background:var(--border); margin:0 2px; }
.empty-state { text-align:center; padding:48px 20px; color:var(--muted); }
.empty-state p { font-size:14px; margin-top:8px; }
</style>
@endpush

@section('content')
<div class="pl-wrap">

    <div class="page-header">
        <h2>Price Lists</h2>
        <a href="{{ route('price-lists.create') }}" class="btn-new">+ New Price List</a>
    </div>

    <div class="card">
        @if($priceLists->isEmpty())
            <div class="empty-state">
                <div style="font-size:36px">🏷️</div>
                <p>No price lists yet. Create your first one!</p>
                <a href="{{ route('price-lists.create') }}" class="btn-new" style="margin-top:12px;">+ New Price List</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Transaction</th>
                        <th>Type</th>
                        <th>Scheme</th>
                        <th>Currency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($priceLists as $pl)
                    <tr>
                        <td style="color:var(--muted);font-size:12px">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight:600">{{ $pl->name }}</div>
                            @if($pl->description)
                                <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ Str::limit($pl->description, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $txBadge = match($pl->transaction_type) {
                                    'sales'    => 'badge-sales',
                                    'purchase' => 'badge-purchase',
                                    default    => 'badge-both',
                                };
                            @endphp
                            <span class="badge {{ $txBadge }}">{{ ucfirst($pl->transaction_type) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $pl->price_list_type === 'all_items' ? 'badge-all' : 'badge-ind' }}">
                                {{ $pl->price_list_type === 'all_items' ? 'All Items' : 'Individual' }}
                            </span>
                        </td>
                        <td style="color:var(--muted)">
                            @if($pl->price_list_type === 'all_items')
                                {{ ucfirst($pl->markup_type ?? '—') }}
                                @if($pl->percentage) {{ $pl->percentage }}% @endif
                            @else
                                {{ ucfirst($pl->pricing_scheme ?? '—') }}
                            @endif
                        </td>
                        <td style="color:var(--muted)">{{ $pl->currency ?? 'INR' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('price-lists.edit', $pl->id) }}" class="action-btn btn-edit">✏️ Edit</a>
                                <div class="divider-v"></div>
                                <a href="{{ route('price-lists.history', $pl->id) }}" class="action-btn btn-history">🕐 History</a>
                                <div class="divider-v"></div>
                                <form method="POST" action="{{ route('price-lists.destroy', $pl->id) }}" style="display:inline" onsubmit="return confirm('Delete this price list?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn btn-del">🗑 Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection