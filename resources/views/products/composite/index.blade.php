@extends('layouts.app')

@section('title', 'Composite Items')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div style="padding:20px;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h1 style="font-size:20px;font-weight:600;color:#1a1a2e;">Composite Items</h1>
        <a href="{{ route('composite-items.create') }}" class="btn-save">+ New Composite Item</a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('composite-items.index') }}"
          style="display:flex;gap:10px;margin-bottom:16px;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by name or SKU..."
               class="form-input" style="max-width:280px;">
        <select name="type" class="form-select" style="max-width:180px;">
            <option value="">All Types</option>
            <option value="assembly_item" {{ request('type') === 'assembly_item' ? 'selected' : '' }}>Assembly Item</option>
            <option value="kit_item"      {{ request('type') === 'kit_item'      ? 'selected' : '' }}>Kit Item</option>
        </select>
        <button type="submit" class="btn-save">Search</button>
        @if(request('search') || request('type'))
            <a href="{{ route('composite-items.index') }}" class="btn-cancel">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div style="background:#fff;border-radius:6px;border:1px solid #e0e3ea;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f8f9fb;">
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">#</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Name</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Type</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">SKU</th>
                    <th style="padding:10px 14px;text-align:left;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Unit</th>
                    <th style="padding:10px 14px;text-align:right;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Selling Price</th>
                    <th style="padding:10px 14px;text-align:right;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Cost Price</th>
                    <th style="padding:10px 14px;text-align:center;border-bottom:1px solid #e8eaed;color:#555;font-weight:500;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr style="border-bottom:1px solid #f0f1f3;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background=''">
                        <td style="padding:10px 14px;color:#888;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 14px;">
                            <div style="font-weight:500;color:#222;">{{ $product->name }}</div>
                            @if($product->brand)
                                <div style="font-size:11px;color:#888;">{{ $product->brand }}</div>
                            @endif
                        </td>
                        <td style="padding:10px 14px;">
                            @if($product->type === 'assembly_item')
                                <span style="background:#e8f0fe;color:#2563eb;padding:3px 8px;border-radius:12px;font-size:11px;font-weight:500;">Assembly</span>
                            @else
                                <span style="background:#fef3e2;color:#e08c00;padding:3px 8px;border-radius:12px;font-size:11px;font-weight:500;">Kit</span>
                            @endif
                        </td>
                        <td style="padding:10px 14px;color:#555;">{{ $product->sku ?? '—' }}</td>
                        <td style="padding:10px 14px;color:#555;">{{ $product->unit }}</td>
                        <td style="padding:10px 14px;text-align:right;color:#333;">
                            ₹{{ number_format($product->selling_price ?? 0, 2) }}
                        </td>
                        <td style="padding:10px 14px;text-align:right;color:#333;">
                            ₹{{ number_format($product->cost_price ?? 0, 2) }}
                        </td>
                        <td style="padding:10px 14px;text-align:center;">
                            <div style="display:flex;gap:8px;justify-content:center;">
                                {{-- ✅ View → products.show route ku redirect --}}
                                <a href="{{ route('products.show', $product->id) }}"
                                   style="color:#2563eb;font-size:12px;text-decoration:none;">View</a>
                                <a href="{{ route('composite-items.edit', $product->id) }}"
                                   style="color:#555;font-size:12px;text-decoration:none;">Edit</a>
                                <form action="{{ route('composite-items.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this item?')"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background:none;border:none;color:#e74c3c;font-size:12px;cursor:pointer;padding:0;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:40px;text-align:center;color:#aaa;">
                            <div style="font-size:15px;margin-bottom:8px;">No composite items found</div>
                            <a href="{{ route('composite-items.create') }}" style="color:#2563eb;font-size:13px;">
                                + Create your first composite item
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div style="margin-top:16px;">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif

</div>

@endsection