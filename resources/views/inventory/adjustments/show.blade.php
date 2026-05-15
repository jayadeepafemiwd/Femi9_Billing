{{-- resources/views/inventory/adjustments/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Adjustment Details')

@push('styles')
<style>
    /* ── Wrapper ─────────────────────────────────────────── */
    .show-wrapper {
        background: #fff;
        border: 1px solid #dde3ee;
        border-radius: 6px;
        max-width: 1100px;
        margin: 20px auto;
        overflow: hidden;
    }

    /* ── Top Action Bar ──────────────────────────────────── */
    .action-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-bottom: 1px solid #e4e8f0;
        background: #fafbff;
    }
    .action-bar .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border: 1px solid #c8d0e0;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        background: #fff;
        color: #333;
        text-decoration: none;
        transition: background .15s;
    }
    .action-bar .btn-action:hover { background: #f0f3fa; }
    .action-bar .btn-action.btn-convert {
        background: #3d8ef8;
        color: #fff;
        border-color: #3d8ef8;
        font-weight: 500;
    }
    .action-bar .btn-action.btn-convert:hover { background: #2d7de0; }
    .action-bar .btn-action.btn-danger {
        color: #e05252;
        border-color: #e8c0c0;
    }
    .action-bar .btn-action.btn-danger:hover { background: #fff5f5; }
    .action-bar .spacer { flex: 1; }
    .action-bar .btn-icon {
        background: none;
        border: none;
        cursor: pointer;
        color: #888;
        font-size: 18px;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
    }
    .action-bar .btn-icon:hover { background: #f0f3fa; color: #333; }

    /* ── PDF View Toggle ─────────────────────────────────── */
    .pdf-toggle-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 10px 24px 0;
        font-size: 13px;
        color: #555;
    }
    .toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        inset: 0;
        background: #ccc;
        border-radius: 20px;
        cursor: pointer;
        transition: .2s;
    }
    .toggle-slider:before {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        left: 3px;
        bottom: 3px;
        background: #fff;
        border-radius: 50%;
        transition: .2s;
    }
    .toggle-switch input:checked + .toggle-slider { background: #3d8ef8; }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(16px); }

    /* ── PDF Document Area ───────────────────────────────── */
    .pdf-doc {
        margin: 16px 24px 24px;
        border: 1px solid #dde3ee;
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    /* ── Ribbon ──────────────────────────────────────────── */
    .ribbon-wrap {
        position: absolute;
        top: 0; left: 0;
        width: 110px;
        height: 110px;
        overflow: hidden;
        pointer-events: none;
        z-index: 10;
    }
    .ribbon {
        position: absolute;
        top: 28px;
        left: -22px;
        width: 110px;
        padding: 6px 0;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        transform: rotate(-45deg);
        transform-origin: center;
        text-transform: uppercase;
    }
    .ribbon.adjusted {
        background: #3d8ef8;
        color: #fff;
    }
    .ribbon.draft {
        background: #adb5c8;
        color: #fff;
    }

    /* ── Customize Button ────────────────────────────────── */
    .customize-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border: 1px solid #3d8ef8;
        border-radius: 4px;
        font-size: 13px;
        color: #3d8ef8;
        background: #fff;
        cursor: pointer;
        z-index: 5;
    }
    .customize-btn:hover { background: #eef4ff; }

    /* ── Document Content ────────────────────────────────── */
    .doc-content {
        padding: 60px 48px 40px;
        background: #fff;
    }
    .doc-title {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #1a1a2e;
        margin-bottom: 28px;
        text-transform: uppercase;
    }

    /* ── Meta Grid ───────────────────────────────────────── */
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px 32px;
        max-width: 520px;
        margin: 0 auto 32px;
        font-size: 13px;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        border-bottom: 1px solid #f0f2f8;
        padding: 6px 0;
    }
    .meta-label { color: #888; }
    .meta-value { color: #222; font-weight: 500; text-align: right; }

    /* ── Item Table ──────────────────────────────────────── */
    .doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 8px;
    }
    .doc-table thead tr {
        background: #2c3e6b;
        color: #fff;
    }
    .doc-table thead th {
        padding: 10px 16px;
        text-align: left;
        font-weight: 500;
        font-size: 12px;
    }
    .doc-table thead th:first-child { width: 48px; }
    .doc-table thead th.text-right { text-align: right; }
    .doc-table tbody td {
        padding: 10px 16px;
        border-bottom: 1px solid #eef0f8;
        color: #333;
        vertical-align: top;
    }
    .doc-table tbody tr:last-child td { border-bottom: none; }
    .doc-table tbody tr:hover { background: #fafbff; }
    .doc-table td.text-right { text-align: right; }
    .item-name { font-weight: 500; color: #222; }
    .item-desc { font-size: 11px; color: #888; margin-top: 2px; }

    /* ── Total Row ───────────────────────────────────────── */
    .total-row {
        display: flex;
        justify-content: flex-end;
        padding: 12px 16px;
        border-top: 2px solid #dde3ee;
        margin-top: 4px;
        font-size: 13px;
        gap: 32px;
    }
    .total-label { color: #555; font-weight: 500; }
    .total-value { color: #222; font-weight: 700; min-width: 100px; text-align: right; }

    /* ── Status Badge ────────────────────────────────────── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .status-badge.adjusted { background: #e3f0ff; color: #3d8ef8; }
    .status-badge.draft    { background: #f0f2f8; color: #888; }

    /* ── More Dropdown ───────────────────────────────────── */
    .dropdown-wrap { position: relative; display: inline-block; }
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 110%;
        background: #fff;
        border: 1px solid #dde3ee;
        border-radius: 4px;
        min-width: 160px;
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        z-index: 100;
        overflow: hidden;
    }
    .dropdown-menu.open { display: block; }
    .dropdown-item {
        display: block;
        padding: 9px 16px;
        font-size: 13px;
        color: #333;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        text-decoration: none;
    }
    .dropdown-item:hover { background: #f5f7fc; }
    .dropdown-item.danger { color: #e05252; }

    /* ── Alert ───────────────────────────────────────────── */
    .alert-success {
        background: #e8f7ee;
        border: 1px solid #b0dfc0;
        color: #2e7d50;
        border-radius: 4px;
        padding: 10px 16px;
        font-size: 13px;
        margin: 12px 20px 0;
    }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 600px) {
        .doc-content { padding: 48px 20px 24px; }
        .meta-grid { grid-template-columns: 1fr; }
        .action-bar { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert-success">✓ {{ session('success') }}</div>
@endif

<div class="show-wrapper">

    {{-- ── Action Bar ────────────────────────────────────── --}}
    <div class="action-bar">

        {{-- Edit --}}
        @if($adjustment->status === 'draft')
        <a href="{{ route('inventory.adjustments.edit', $adjustment) }}" class="btn-action">
            ✏️ Edit
        </a>
        @endif

        {{-- PDF / Print --}}
        <button type="button" class="btn-action" onclick="window.print()">
            🖨️ PDF/Print ▾
        </button>

        {{-- Convert to Adjusted (Draft only) --}}
        @if($adjustment->status === 'draft')
        <form method="POST"
              action="{{ route('inventory.adjustments.convert', $adjustment) }}"
              onsubmit="return confirm('Convert this draft to Adjusted? Stock will be updated.')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-action btn-convert">
                Convert to Adjusted
            </button>
        </form>
        @endif

        <div class="spacer"></div>

        {{-- Attachment icon --}}
        <button class="btn-icon" title="Attachments">📎</button>
        {{-- Comment icon --}}
        <button class="btn-icon" title="Comments">💬</button>

        {{-- More (⋯) dropdown --}}
        <div class="dropdown-wrap">
            <button class="btn-icon" onclick="toggleDropdown()" title="More options">⋯</button>
            <div class="dropdown-menu" id="moreDropdown">
                @if($adjustment->status === 'draft')
                <form method="POST"
                      action="{{ route('inventory.adjustments.destroy', $adjustment) }}"
                      onsubmit="return confirm('Delete this adjustment?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item danger">🗑 Delete</button>
                </form>
                @endif
                <a href="{{ route('inventory.adjustments.index') }}" class="dropdown-item">
                    ← Back to List
                </a>
            </div>
        </div>

        {{-- Close --}}
        <a href="{{ route('inventory.adjustments.index') }}" class="btn-icon" title="Close">✕</a>
    </div>

    {{-- ── PDF Toggle ────────────────────────────────────── --}}
    <div class="pdf-toggle-bar">
        <span>Show PDF View</span>
        <label class="toggle-switch">
            <input type="checkbox" checked onchange="togglePdfView(this)">
            <span class="toggle-slider"></span>
        </label>
    </div>

    {{-- ── PDF Document ───────────────────────────────────── --}}
    <div class="pdf-doc" id="pdfDoc">

        {{-- Ribbon --}}
        <div class="ribbon-wrap">
            <div class="ribbon {{ $adjustment->status === 'adjusted' ? 'adjusted' : 'draft' }}">
                {{ $adjustment->status === 'adjusted' ? 'Adjusted' : 'Draft' }}
            </div>
        </div>

        {{-- Customize button --}}
        <button class="customize-btn">⚙ Customize</button>

        <div class="doc-content">

            {{-- Title --}}
            <div class="doc-title">Inventory Adjustment</div>

            {{-- Meta info --}}
            <div class="meta-grid">
                <div class="meta-row">
                    <span class="meta-label">Date</span>
                    <span class="meta-value">
                        {{ \Carbon\Carbon::parse($adjustment->date)->format('d/m/Y') }}
                    </span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Reason</span>
                    <span class="meta-value">{{ $adjustment->reason }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Account</span>
                    <span class="meta-value">{{ $adjustment->account }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Adjustment Type</span>
                    <span class="meta-value">{{ ucfirst($adjustment->mode) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Location Name</span>
                    <span class="meta-value">{{ $adjustment->location?->location_name ?? '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Created By</span>
                    <span class="meta-value">{{ auth()->user()?->name ?? '—' }}</span>
                </div>
                @if($adjustment->reference_number)
                <div class="meta-row">
                    <span class="meta-label">Reference #</span>
                    <span class="meta-value">{{ $adjustment->reference_number }}</span>
                </div>
                @endif
                @if($adjustment->description)
                <div class="meta-row" style="grid-column: 1/-1;">
                    <span class="meta-label">Description</span>
                    <span class="meta-value">{{ $adjustment->description }}</span>
                </div>
                @endif
            </div>

            {{-- Item Table --}}
            <table class="doc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item & Description</th>
                        @if($adjustment->mode === 'quantity')
                            <th class="text-right">Quantity Adjusted</th>
                            <th class="text-right">Cost Price</th>
                        @else
                            <th class="text-right">Current Value</th>
                            <th class="text-right">Adjusted Value</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustment->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="item-name">
                                {{ $item->product?->name ?? 'Unknown Item' }}
                                @if($item->variant_name)
                                    <span style="color:#888;font-size:11px;"> - {{ $item->variant_name }}</span>
                                @endif
                            </div>
                            @if($item->product?->sku)
                            <div class="item-desc">{{ $item->product->sku }}</div>
                            @endif
                        </td>
                        @if($adjustment->mode === 'quantity')
                            <td class="text-right">
                                {{ number_format($item->quantity_adjusted, 4) }}
                                <div style="font-size:11px;color:#999;">{{ $item->product?->unit ?? '' }}</div>
                            </td>
                            <td class="text-right">
                                ₹{{ number_format($item->product?->cost_price ?? 0, 2) }}
                            </td>
                        @else
                            <td class="text-right">
                                ₹{{ number_format($item->current_value ?? 0, 2) }}
                            </td>
                            <td class="text-right">
                                ₹{{ number_format($item->adjusted_value ?? 0, 2) }}
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:#aaa;padding:24px;">
                            No items found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Total --}}
            @php
                $total = $adjustment->items->sum(function($item) use ($adjustment) {
                    if ($adjustment->mode === 'quantity') {
                        return abs($item->quantity_adjusted ?? 0) * ($item->product?->cost_price ?? 0);
                    }
                    return abs($item->adjusted_value ?? 0);
                });
            @endphp
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-value">₹{{ number_format($total, 2) }}</span>
            </div>

        </div>{{-- /doc-content --}}
    </div>{{-- /pdf-doc --}}

</div>{{-- /show-wrapper --}}
@endsection

@push('scripts')
<script>
// ── More dropdown toggle ───────────────────────────────────
function toggleDropdown() {
    document.getElementById('moreDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-wrap')) {
        document.getElementById('moreDropdown')?.classList.remove('open');
    }
});

// ── PDF View toggle ────────────────────────────────────────
function togglePdfView(checkbox) {
    const doc = document.getElementById('pdfDoc');
    doc.style.display = checkbox.checked ? '' : 'none';
}
</script>
@endpush