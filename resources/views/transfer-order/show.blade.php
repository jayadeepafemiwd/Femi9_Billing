@extends('layouts.app')

@section('title', 'Transfer Order #' . $order->transfer_order_number)

@push('styles')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; font-size: 13px; }

.to-layout { display: flex; height: calc(100vh - 56px); overflow: hidden; }

/* ── Left List Panel ── */
.to-list-panel {
    width: 280px; min-width: 220px; background: #fff;
    border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; flex-shrink: 0;
}
.to-list-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px; border-bottom: 1px solid #e8e8e8; background: #fff; flex-shrink: 0;
}
.to-list-header .title-wrap { display: flex; align-items: center; gap: 6px; }
.to-list-header h3 { font-size: 13px; font-weight: 600; color: #1a1a2e; }
.filter-arrow { color: #4a90d9; font-size: 11px; cursor: pointer; }
.new-btn {
    width: 26px; height: 26px; background: #2d7dd2; color: #fff; border: none;
    border-radius: 4px; font-size: 20px; cursor: pointer; display: flex;
    align-items: center; justify-content: center; line-height: 1; text-decoration: none;
}
.more-btn { background: none; border: none; color: #888; font-size: 20px; cursor: pointer; padding: 2px 5px; border-radius: 3px; }
.more-btn:hover { background: #f0f0f0; }
.to-list-scroll { flex: 1; overflow-y: auto; }

.to-list-item {
    padding: 11px 14px 10px; border-bottom: 1px solid #f2f2f2; cursor: pointer;
    transition: background 0.1s; border-left: 3px solid transparent;
}
.to-list-item:hover { background: #f5f8ff; }
.to-list-item.active { background: #eaf2fb; border-left-color: #2d7dd2; }
.li-row1 { display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px; }
.li-num { font-weight: 700; font-size: 13px; color: #1a1a2e; }
.li-amt { font-weight: 600; font-size: 13px; color: #1a1a2e; }
.li-row2 { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.badge-transferred { color: #27ae60; font-size: 11px; font-weight: 600; }
.badge-initiated   { color: #2d7dd2; font-size: 11px; font-weight: 600; }
.badge-draft       { color: #888;    font-size: 11px; font-weight: 600; }
.li-date { font-size: 11px; color: #888; }
.li-route { font-size: 11px; color: #666; display: flex; align-items: center; gap: 4px; }
.li-arrow { color: #bbb; }

/* ── Right Panel ── */
.to-detail-panel { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

/* Toolbar */
.to-toolbar {
    display: flex; align-items: center; padding: 10px 18px; gap: 8px;
    background: #fff; border-bottom: 1px solid #e0e0e0; flex-shrink: 0;
}
.to-toolbar .t-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-right: 8px; }
.tbtn {
    display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px;
    border: 1px solid #d0d0d0; border-radius: 4px; background: #fff;
    font-size: 12px; color: #444; cursor: pointer; text-decoration: none; transition: all .15s;
}
.tbtn:hover { background: #f5f5f5; border-color: #bbb; }
.tbtn.primary { background: #2d7dd2; color: #fff; border-color: #2d7dd2; }
.tbtn.primary:hover { background: #246bb8; }
.tbtn-more { background: none; border: none; font-size: 20px; color: #888; cursor: pointer; padding: 4px 6px; border-radius: 3px; }
.tbtn-more:hover { background: #f0f0f0; }
.tbtn-close { background: none; border: none; font-size: 22px; color: #aaa; cursor: pointer; line-height: 1; }
.tbtn-close:hover { color: #555; }
.t-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
.pdf-label { font-size: 12px; color: #555; display: flex; align-items: center; gap: 6px; }
.tog { position: relative; display: inline-block; width: 36px; height: 20px; }
.tog input { opacity:0; width:0; height:0; }
.tog-slider { position:absolute; top:0; left:0; right:0; bottom:0; background:#2d7dd2; border-radius:20px; cursor:pointer; transition:.2s; }
.tog-slider:before { position:absolute; content:""; width:14px; height:14px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; }
.tog input:not(:checked) + .tog-slider { background:#ccc; }

/* PDF dropdown */
.pdf-drop-wrap { position: relative; }
.pdf-dropdown {
    display: none; position: absolute; top: 110%; left: 0; z-index: 200;
    background: #fff; border: 1px solid #ddd; border-radius: 4px; min-width: 150px;
    box-shadow: 0 4px 14px rgba(0,0,0,.1);
}
.pdf-dropdown.show { display: block; }
.pdf-drop-item { display: block; padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer; text-decoration: none; border-bottom: 1px solid #f5f5f5; }
.pdf-drop-item:last-child { border-bottom: none; }
.pdf-drop-item:hover { background: #eaf2fb; }

/* PDF Area */
.to-pdf-area { flex: 1; overflow-y: auto; padding: 24px; display: flex; justify-content: center; align-items: flex-start; }

.pdf-card {
    background: #fff; width: 760px; min-height: 880px;
    box-shadow: 0 2px 18px rgba(0,0,0,.12); border-radius: 2px;
    position: relative; overflow: hidden;
}

/* Ribbon */
.ribbon-wrap { position:absolute; top:0; left:0; width:112px; height:112px; overflow:hidden; pointer-events:none; z-index:10; }
.ribbon {
    position:absolute; top:28px; left:-30px; width:136px;
    padding:7px 0; text-align:center; font-size:11px; font-weight:700;
    letter-spacing:.5px; color:#fff; transform:rotate(-45deg);
}
.r-transit     { background:#2d7dd2; }
.r-transferred { background:#27ae60; }
.r-draft       { background:#999; }

/* Customize btn */
.cust-btn {
    position:absolute; top:14px; right:14px; display:flex; align-items:center; gap:5px;
    padding:6px 14px; background:#2d7dd2; color:#fff; border:none; border-radius:4px;
    font-size:12px; font-weight:500; cursor:pointer; z-index:5;
}
.cust-btn:hover { background:#246bb8; }

/* PDF content */
.pdf-inner { padding: 44px 44px 0; }
.pdf-heading { text-align:center; font-size:28px; font-weight:700; letter-spacing:1px; color:#1a1a2e; margin-bottom:26px; }

.pdf-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:26px; }
.co-name { font-size:14px; font-weight:700; color:#1a1a2e; margin-bottom:5px; }
.co-addr { font-size:12px; color:#555; line-height:1.7; }
.meta-tbl { border-collapse:collapse; margin-left:auto; }
.meta-tbl td { padding:2px 0 2px 22px; font-size:12px; vertical-align:top; }
.meta-tbl .ml { color:#888; text-align:right; white-space:nowrap; }
.meta-tbl .mr { font-weight:600; color:#1a1a2e; }

.loc-grid { display:grid; grid-template-columns:1fr 1fr; border:1px solid #e0e0e0; border-radius:3px; margin-bottom:26px; }
.loc-cell { padding:13px 17px; }
.loc-cell:first-child { border-right:1px solid #e0e0e0; }
.loc-lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#888; margin-bottom:5px; }
.loc-nm  { font-size:13px; font-weight:600; color:#1a1a2e; margin-bottom:3px; }
.loc-ad  { font-size:12px; color:#555; line-height:1.65; }

/* Items */
table.pitems { width:100%; border-collapse:collapse; }
table.pitems thead tr { background:#1e2233; }
table.pitems th { padding:10px 14px; font-size:11px; color:#fff; font-weight:600; text-align:left; }
table.pitems th.r { text-align:right; }
table.pitems td { padding:12px 14px; border-bottom:1px solid #f0f0f0; font-size:13px; vertical-align:top; }
table.pitems td.r { text-align:right; font-weight:600; }
table.pitems .iname { font-weight:600; color:#1a1a2e; }
table.pitems .idesc { font-size:11px; color:#888; margin-top:2px; }
table.pitems .iunit { font-size:11px; color:#aaa; }
table.pitems tbody tr:last-child td { border-bottom:none; }

.total-bar { border-top:2px solid #e8e8e8; padding:12px 14px 0; display:flex; justify-content:flex-end; margin-bottom:0; }
.grand-line { font-size:14px; font-weight:700; color:#1a1a2e; }

/* Footer */
.pdf-foot { padding:16px 44px 34px; }
.tw-label { font-size:11px; color:#888; margin-bottom:3px; }
.tw-val   { font-size:13px; font-style:italic; font-weight:600; color:#1a1a2e; margin-bottom:28px; }
.sig-wrap { display:flex; justify-content:flex-end; }
.sig-box  { text-align:center; }
.sig-line { width:210px; border-bottom:1px solid #555; height:32px; }
.sig-lbl  { font-size:11px; color:#555; margin-top:4px; }
</style>
@endpush

@section('content')
<div class="to-layout">

    {{-- ════ LEFT: List Panel ════ --}}
    <div class="to-list-panel">
        <div class="to-list-header">
            <div class="title-wrap">
                <h3>All Transfer Ord...</h3>
                <span class="filter-arrow">&#9660;</span>
            </div>
            <div style="display:flex;gap:6px;align-items:center;">
                <a href="{{ route('transfer-orders.create') }}" class="new-btn" title="New">&#43;</a>
                <button class="more-btn">&#8943;</button>
            </div>
        </div>

        <div class="to-list-scroll">
            @foreach($allOrders as $to)
            @php
                $totalAmt   = $to->items->sum(fn($i) => ($i->quantity ?? 0) * ($i->product->selling_price ?? $i->product->purchase_price ?? 0));
                $badgeClass = match($to->status) {
                    'transferred' => 'badge-transferred',
                    'initiated'   => 'badge-initiated',
                    default       => 'badge-draft',
                };
                $badgeText  = match($to->status) {
                    'transferred' => 'TRANSFERRED',
                    'initiated'   => 'IN TRANSIT',
                    'draft'       => 'DRAFT',
                    default       => strtoupper($to->status),
                };
            @endphp
            <a href="{{ route('transfer-orders.show', $to->id) }}" style="text-decoration:none;">
                <div class="to-list-item {{ $to->id == $order->id ? 'active' : '' }}">
                    <div class="li-row1">
                        <span class="li-num">{{ $to->transfer_order_number }}</span>
                        <span class="li-amt">{{ number_format($totalAmt, 4) }}</span>
                    </div>
                    <div class="li-row2">
                        <span class="{{ $badgeClass }}">{{ $badgeText }}</span>
                        <span class="li-date">{{ \Carbon\Carbon::parse($to->date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="li-route">
                        {{ $to->sourceLocation->location_name ?? '—' }}
                        <span class="li-arrow">&#8594;</span>
                        {{ $to->destinationLocation->location_name ?? '—' }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ════ RIGHT: Detail Panel ════ --}}
    <div class="to-detail-panel">

        {{-- Toolbar --}}
        <div class="to-toolbar">
            <span class="t-title">{{ $order->transfer_order_number }}</span>

            <a href="{{ route('transfer-orders.edit', $order->id) }}" class="tbtn">
                &#9998; Edit
            </a>

            <div class="pdf-drop-wrap">
                <button class="tbtn" onclick="togglePdfDrop()">
                    &#128196; PDF/Print <span style="font-size:10px">&#9660;</span>
                </button>
                <div class="pdf-dropdown" id="pdfDropdown">
                    <a href="{{ route('transfer-orders.pdf', $order->id) }}" target="_blank" class="pdf-drop-item">&#128196; Download PDF</a>
                    <div class="pdf-drop-item" onclick="window.print()">&#128424; Print</div>
                </div>
            </div>

            @if($order->status !== 'transferred')
            <form method="POST" action="{{ route('transfer-orders.mark-transferred', $order->id) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="tbtn primary">&#8644; Mark as Transferred</button>
            </form>
            @endif

            <button class="tbtn-more">&#8943;</button>

            <div class="t-right">
                <label class="pdf-label">
                    Show PDF View
                    <label class="tog">
                        <input type="checkbox" id="pdfToggle" checked
                               onchange="document.getElementById('pdfView').style.display=this.checked?'flex':'none'">
                        <span class="tog-slider"></span>
                    </label>
                </label>
                <a href="{{ route('transfer-orders.index') }}">
                    <button class="tbtn-close">&times;</button>
                </a>
            </div>
        </div>

        {{-- PDF View --}}
        <div class="to-pdf-area" id="pdfView">
            <div class="pdf-card">

                {{-- Ribbon --}}
                @php
                    $rc = match($order->status) { 'transferred'=>'r-transferred', 'initiated'=>'r-transit', default=>'r-draft' };
                    $rt = match($order->status) { 'transferred'=>'Transferred', 'initiated'=>'In Transit', default=>ucfirst($order->status) };
                @endphp
                <div class="ribbon-wrap">
                    <div class="ribbon {{ $rc }}">{{ $rt }}</div>
                </div>

                <button class="cust-btn">&#9881; Customize</button>

                {{-- PDF Inner --}}
                <div class="pdf-inner">

                    <div class="pdf-heading">TRANSFER ORDER</div>

                    {{-- Top: Company + Meta --}}
                    <div class="pdf-top">
                        <div>
                            @php
                                $srcL = $order->sourceLocation;
                                $sA   = $srcL->address_details ?? null;
                                if (is_string($sA)) $sA = json_decode($sA, true);
                                $sAP  = is_array($sA) ? array_filter([$sA['address']??($sA[0]??''), $sA['city']??($sA[1]??''), $sA['state']??($sA[2]??''), $sA['zip']??($sA[3]??'')]) : [];
                            @endphp
                            <div class="co-name">{{ config('app.company_name', $srcL->location_name ?? 'Your Company') }}</div>
                            <div class="co-addr">
                                @foreach($sAP as $p)<div>{{ $p }}</div>@endforeach
                                <div>India</div>
                                @if($srcL->phone ?? null)<div>{{ $srcL->phone }}</div>@endif
                                @if($srcL->email ?? null)<div>{{ $srcL->email }}</div>@endif
                            </div>
                        </div>
                        <table class="meta-tbl">
                            <tr><td class="ml">TransferOrder#</td><td class="mr">{{ $order->transfer_order_number }}</td></tr>
                            <tr><td class="ml">Date</td><td class="mr">{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}</td></tr>
                            @if($order->status === 'transferred')
                            <tr><td class="ml">Date of Transfer</td><td class="mr">{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y') }}</td></tr>
                            @endif
                            <tr>
                                <td class="ml">Created By</td>
                                <td class="mr">Admin</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Locations --}}
                    <div class="loc-grid">
                        <div class="loc-cell">
                            <div class="loc-lbl">Source Location</div>
                            <div class="loc-nm">{{ $order->sourceLocation->location_name ?? '—' }}</div>
                            @php
                                $sLA = $order->sourceLocation->address_details ?? null;
                                if(is_string($sLA)) $sLA = json_decode($sLA,true);
                                $sLP = is_array($sLA) ? array_filter([$sLA['address']??($sLA[0]??''),$sLA['city']??($sLA[1]??''),$sLA['state']??($sLA[2]??''),$sLA['zip']??($sLA[3]??'')]) : [];
                            @endphp
                            <div class="loc-ad">@foreach($sLP as $p){{ $p }}<br>@endforeach India</div>
                        </div>
                        <div class="loc-cell">
                            <div class="loc-lbl">Destination Location</div>
                            <div class="loc-nm">{{ $order->destinationLocation->location_name ?? '—' }}</div>
                            @php
                                $dL  = $order->destinationLocation;
                                $dLA = $dL->address_details ?? null;
                                if(is_string($dLA)) $dLA = json_decode($dLA,true);
                                $dLP = is_array($dLA) ? array_filter([$dLA['address']??($dLA[0]??''),$dLA['city']??($dLA[1]??''),$dLA['state']??($dLA[2]??''),$dLA['zip']??($dLA[3]??'')]) : [];
                            @endphp
                            <div class="loc-ad">
                                @foreach($dLP as $p){{ $p }}<br>@endforeach
                                India<br>
                                @if($dL->phone ?? null){{ $dL->phone }}<br>@endif
                                @if($dL->email ?? null){{ $dL->email }}@endif
                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    @php $grandTotal = 0; @endphp
                    <table class="pitems">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:57%">Item &amp; Description</th>
                                <th class="r" style="width:15%">Qty</th>
                                <th class="r" style="width:23%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $idx => $item)
                            @php
                                $price   = $item->product->selling_price ?? $item->product->purchase_price ?? 0;
                                $lineAmt = $item->quantity * $price;
                                $grandTotal += $lineAmt;
                                $variant = $item->variant_id ? \DB::table('item_variants')->find($item->variant_id) : null;
                            @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <div class="iname">{{ $item->product->name ?? '—' }}</div>
                                    @if($variant)<div class="idesc">{{ $variant->name }}</div>@endif
                                    @if($item->product->sku ?? null)<div class="idesc">{{ $item->product->sku }}</div>@endif
                                </td>
                                <td class="r">
                                    <div>{{ number_format($item->quantity, 2) }}</div>
                                    <div class="iunit">{{ $item->product->unit ?? 'box' }}</div>
                                </td>
                                <td class="r">{{ number_format($lineAmt, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="total-bar">
                        <div class="grand-line">Total ₹{{ number_format($grandTotal, 2) }}</div>
                    </div>

                </div>{{-- /pdf-inner --}}

                {{-- Footer --}}
                <div class="pdf-foot">
                    <div class="tw-label">Total In Words:</div>
                    <div class="tw-val">{{ \App\Helpers\NumberHelper::toWords($grandTotal) }}</div>
                    <div class="sig-wrap">
                        <div class="sig-box">
                            <div class="sig-line"></div>
                            <div class="sig-lbl">Authorized Signature</div>
                        </div>
                    </div>
                </div>

            </div>{{-- /pdf-card --}}
        </div>{{-- /pdf-area --}}

    </div>{{-- /detail-panel --}}
</div>
@endsection

@push('scripts')
<script>
function togglePdfDrop() {
    document.getElementById('pdfDropdown').classList.toggle('show');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.pdf-drop-wrap')) {
        document.getElementById('pdfDropdown')?.classList.remove('show');
    }
});
</script>
@endpush