@extends('layouts.app')

@section('title', 'Items List')

@section('breadcrumb')
    <span class="current">Items</span>
@endsection

@push('styles')
<style>
    .table-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); padding: 20px; overflow-x: auto; }
    .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
    .table-header h2 { font-size: 20px; font-weight: 700; }
    .btn-add { background: var(--pink); color: #fff; border: none; border-radius: 6px; padding: 8px 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 13px; }
    .btn-add:hover { background: var(--pink-dk); color: #fff; }
    .btn-add.grey { background: #6c757d; }
    .btn-add.grey:hover { background: #5a6268; }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 10px; background: var(--bg); color: var(--muted); font-weight: 600; border-bottom: 2px solid var(--border); font-size: 12px; text-transform: uppercase; letter-spacing: 0.3px; }
    td { padding: 11px 10px; border-bottom: 1px solid var(--border); font-size: 13px; }
    tr:hover td { background: var(--pink-xlt); }

    .badge { padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
    .badge-goods     { background: #e6f0ff; color: #2d5be3; }
    .badge-service   { background: #e6f7e6; color: #10b981; }
    .badge-assembly  { background: #fef3e2; color: #d97706; }
    .badge-kit       { background: #f3e8ff; color: #7c3aed; }
    .badge-composite { background: #fff0f0; color: #dc2626; }
    .badge-variant   { background: #f0f0f0; color: #666; }

    .btn-action { padding: 4px 10px; border-radius: 4px; text-decoration: none; margin: 0 2px; display: inline-block; font-size: 12px; font-weight: 500; }
    .btn-view   { background: #e6f0ff; color: #2d5be3; }
    .btn-edit   { background: #fff3e6; color: #f59e0b; }
    .btn-delete { background: #fee9e9; color: #ef4444; border: none; cursor: pointer; }
    .btn-history { background: #e6f7e6; color: #10b981; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state h3 { color: #4a5568; margin-bottom: 10px; }
    .empty-state p { color: #718096; margin-bottom: 20px; }

    .variant-row { background: #f9fafb; font-size: 13px; }
    .variant-row:hover td { background: #f3f4f6; }
    .toggle-icon { display: inline-block; cursor: pointer; font-size: 12px; color: var(--pink); transition: transform 0.2s; width: 20px; text-align: center; }
    .toggle-icon.expanded { transform: rotate(90deg); }
    .parent-row { cursor: pointer; }
    .variant-indent { padding-left: 25px; color: #666; font-size: 13px; }
    .variant-sku { color: #888; font-size: 11px; margin-left: 8px; }
    .variant-badge { background: #f0f0f0; color: #666; font-size: 11px; padding: 2px 6px; border-radius: 12px; margin-left: 8px; }
</style>
@endpush

@section('content')
<div style="padding: 24px;">

    <div class="table-card">
        <div class="table-header">
            <h2>Items List</h2>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('field_customization.index') }}?from=products" class="btn-add grey">
                    ⚙️ Preferences
                </a>
                <a href="{{ route('products.create') }}" class="btn-add">
                    + New Item
                </a>
            </div>
        </div>

        @if($products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:30px;"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>SKU</th>
                    <th>Unit</th>
                    <th>Selling Price</th>
                    <th>Cost Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php
                    $isVariantParent = ($product->item_variant_type === 'contains_variants');
                    $isComposite     = ($product->item_type === 'composite_item');
                    $variants        = [];

                    if ($isVariantParent && $product->variants_data) {
                        $variantsData = is_string($product->variants_data)
                            ? json_decode($product->variants_data, true)
                            : $product->variants_data;
                        if (isset($variantsData['variants']) && is_array($variantsData['variants'])) {
                            $variants = $variantsData['variants'];
                        }
                    }

                    if ($isComposite) {
                        $badgeClass = match($product->type) {
                            'assembly_item' => 'badge-assembly',
                            'kit_item'      => 'badge-kit',
                            default         => 'badge-composite',
                        };
                        $badgeLabel = match($product->type) {
                            'assembly_item' => '🔩 Assembly',
                            'kit_item'      => '📦 Kit',
                            default         => 'Composite',
                        };
                    } else {
                        $badgeClass = match($product->type) {
                            'goods'   => 'badge-goods',
                            'service' => 'badge-service',
                            default   => 'badge-variant',
                        };
                        $badgeLabel = match($product->type) {
                            'goods'   => 'Goods',
                            'service' => 'Service',
                            default   => ucfirst($product->type ?? '—'),
                        };
                    }
                @endphp

                {{-- Parent Row --}}
                <tr class="parent-row" data-parent-id="{{ $product->id }}">
                    <td style="text-align:center;">
                        @if($isVariantParent && count($variants) > 0)
                            <span class="toggle-icon" data-parent="{{ $product->id }}">▶</span>
                        @endif
                    </td>
                    <td>#{{ $product->id }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        @if($isVariantParent && count($variants) > 0)
                            <span class="variant-badge">{{ count($variants) }} variants</span>
                        @endif
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                    <td>{{ $product->sku ?? '—' }}</td>
                    <td>{{ $product->unit }}</td>
                    <td>₹{{ number_format($product->selling_price, 2) }}</td>
                    <td>₹{{ number_format($product->cost_price, 2) }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn-action btn-view">View</a>
                        <a href="{{ in_array($product->type, ['assembly_item','kit_item']) ? route('composite-items.edit', $product->id) : route('products.edit', $product->id) }}" class="btn-action btn-edit">Edit</a>
                        @if($isComposite)
                            <a href="{{ route('composite-items.show', $product->id) }}" class="btn-action" style="background:#f3e8ff;color:#7c3aed;">🔩 Assemblies</a>
                        @endif
                        <a href="{{ route('products.history', $product->id) }}" class="btn-action btn-history">History</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this item?')">Delete</button>
                        </form>
                    </td>
                </tr>

                {{-- Variant Rows --}}
                @if($isVariantParent && count($variants) > 0)
                    @foreach($variants as $variantIndex => $variant)
                    <tr class="variant-row variant-parent-{{ $product->id }}" style="display:none;">
                        <td style="border-left:2px solid var(--pink);"></td>
                        <td>↳</td>
                        <td class="variant-indent">
                            {{ $variant['name'] ?? 'Variant '.($variantIndex+1) }}
                            @if(isset($variant['sku']))
                                <span class="variant-sku">SKU: {{ $variant['sku'] }}</span>
                            @endif
                        </td>
                        <td><span class="badge badge-variant">Variant</span></td>
                        <td>{{ $variant['sku'] ?? '—' }}</td>
                        <td>{{ $product->unit }}</td>
                        <td>₹{{ number_format((float)($variant['selling_price'] ?? $product->selling_price ?? 0), 2) }}</td>
                        <td>₹{{ number_format((float)($variant['cost_price'] ?? $product->cost_price ?? 0), 2) }}</td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="btn-action btn-view">View</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-action btn-edit">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                @endif

                @endforeach
            </tbody>
        </table>

        @if(method_exists($products, 'links'))
        <div style="margin-top:20px;">{{ $products->links() }}</div>
        @endif

        @else
        <div class="empty-state">
            <h3>No Items Found</h3>
            <p>Get started by creating your first inventory item</p>
            <a href="{{ route('products.create') }}" class="btn-add">+ Create New Item</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.parent-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' ||
                e.target.closest('.btn-action') || e.target.closest('form')) return;

            const parentId    = this.getAttribute('data-parent-id');
            const variantRows = document.querySelectorAll(`.variant-parent-${parentId}`);
            const toggleIcon  = this.querySelector('.toggle-icon');

            if (variantRows.length > 0) {
                const isHidden = variantRows[0].style.display === 'none';
                variantRows.forEach(r => r.style.display = isHidden ? 'table-row' : 'none');
                if (toggleIcon) {
                    toggleIcon.classList.toggle('expanded', isHidden);
                    toggleIcon.textContent = isHidden ? '▼' : '▶';
                }
            }
        });
    });

    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            this.closest('.parent-row')?.click();
        });
    });
});
</script>
@endpush