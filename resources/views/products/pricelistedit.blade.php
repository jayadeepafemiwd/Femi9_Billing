@extends('layouts.app')

@section('title', 'Edit Price List')

@section('breadcrumb')
    <a href="{{ route('products.index') }}">Products</a>
    <span class="sep">›</span>
    <a href="{{ route('price-lists.index') }}">Price Lists</a>
    <span class="sep">›</span>
    <span class="current">Edit Price List</span>
@endsection

@push('styles')
<style>
.pl-wrap { padding: 24px; }

.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
.page-header h2 { font-size:20px; font-weight:700; color:var(--text); }

.btn-new {
    background:var(--pink); color:#fff; border:none; border-radius:6px;
    padding:8px 18px; font-size:13px; cursor:pointer; font-weight:600;
    text-decoration:none; display:inline-flex; align-items:center; gap:6px;
}
.btn-new:hover { background:var(--pink-dk); color:#fff; }

/* ── Card / Form ── */
.card { background:#fff; border-radius:10px; border:1px solid var(--border); padding:24px; }

.form-row { display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.2rem; }
.form-label { min-width:160px; font-size:14px; color:var(--muted); padding-top:8px; }
.form-label.req { color:#d44; }
.form-control { flex:1; }

input[type=text],
textarea,
select {
    width:100%; font-size:14px; padding:7px 10px;
    border:1px solid var(--border); border-radius:6px;
    background:#fff; color:var(--text);
}
input[type=text]:focus,
textarea:focus,
select:focus {
    outline:none; border-color:var(--pink); box-shadow:0 0 0 3px var(--pink-lt);
}
textarea { resize:vertical; min-height:70px; }

/* ── Radio ── */
.radio-group { display:flex; gap:1.5rem; padding-top:6px; }
.radio-group label { display:flex; align-items:center; gap:6px; font-size:14px; cursor:pointer; }

/* ── Type Cards ── */
.card-group { display:flex; gap:12px; padding-top:4px; flex-wrap:wrap; }
.type-card {
    border:1.5px solid var(--border); border-radius:10px;
    padding:12px 16px; cursor:pointer; width:240px;
    transition:border-color 0.15s, background 0.15s;
}
.type-card.active { border-color:var(--pink); background:var(--pink-xlt); }
.tc-title { font-size:14px; font-weight:600; color:var(--text); display:flex; align-items:center; gap:8px; }
.tc-sub { font-size:12px; color:var(--muted); margin-top:3px; }

.check-circle {
    width:18px; height:18px; border-radius:50%;
    border:2px solid #aaa; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
}
.check-circle.on { border-color:var(--pink); background:var(--pink); }
.check-circle.on::after {
    content:''; width:6px; height:6px;
    border-radius:50%; background:#fff;
}

/* ── Percentage Row ── */
.pct-row { display:flex; align-items:center; gap:8px; padding-top:4px; }
.pct-row select { width:130px; flex:none; }
.pct-row input  { width:100px; flex:none; }
.pct-row span   { font-size:14px; color:var(--muted); }

/* ── Divider ── */
.divider { height:1px; background:var(--border); margin:1.5rem 0; }
.section-title { font-size:16px; font-weight:600; color:var(--text); margin-bottom:1rem; }

/* ── Items Table ── */
.table-wrap { overflow-x:auto; border:1px solid var(--border); border-radius:8px; }
table { width:100%; border-collapse:collapse; font-size:13px; }
th {
    background:var(--bg); padding:8px 12px; text-align:left;
    font-weight:600; font-size:12px; color:var(--muted);
    border-bottom:1px solid var(--border);
    text-transform:uppercase; letter-spacing:0.3px;
}
td {
    padding:8px 12px; border-bottom:1px solid var(--border);
    color:var(--text); vertical-align:top;
}
tr:last-child td { border-bottom:none; }
tr:hover td { background:var(--pink-xlt); }

.item-name { font-weight:600; font-size:13px; }
.item-sku  { font-size:11px; color:var(--muted); }
td input[type=number] {
    width:90px; font-size:13px; padding:4px 8px;
    border:1px solid var(--border); border-radius:6px;
}
td input[type=number]:focus { outline:none; border-color:var(--pink); }

.add-range {
    font-size:12px; color:var(--pink); cursor:pointer;
    margin-top:4px; display:inline-flex; align-items:center; gap:4px;
}
.add-range:hover { text-decoration:underline; }

.btn-del { background:none; border:none; cursor:pointer; color:#d44; font-size:16px; padding:2px 6px; border-radius:4px; }
.btn-del:hover { background:#fdecea; }

/* ── Rounding popup ── */
.rel { position:relative; }
.rounding-popup {
    position:absolute; z-index:10; background:#fff;
    border:1px solid var(--border); border-radius:10px;
    padding:1rem; min-width:320px; top:36px; left:0;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.view-eg { font-size:12px; color:var(--pink); cursor:pointer; margin-top:4px; display:inline-block; }
.view-eg:hover { text-decoration:underline; }

/* ── Discount note ── */
.discount-note {
    font-size:12px; color:var(--muted); margin-top:4px;
    padding:6px 10px; background:var(--bg);
    border-radius:6px; border-left:2px solid var(--pink);
}

/* ── Volume badge ── */
.badge-vol {
    display:inline-block; background:var(--pink-lt);
    color:var(--pink-dk); font-size:11px;
    border-radius:4px; padding:2px 7px; margin-left:6px;
}

/* ── Import toggle ── */
.import-toggle { display:flex; align-items:center; gap:10px; font-size:13px; color:var(--muted); }
.toggle-sw {
    width:36px; height:20px; border-radius:10px;
    background:#ccc; cursor:pointer; position:relative;
    transition:background 0.2s; flex-shrink:0;
}
.toggle-sw.on { background:var(--pink); }
.toggle-sw::after {
    content:''; position:absolute; top:3px; left:3px;
    width:14px; height:14px; border-radius:50%;
    background:#fff; transition:left 0.2s;
}
.toggle-sw.on::after { left:19px; }

.import-section {
    margin-top:1rem; padding:1rem;
    border:1px solid var(--border); border-radius:8px; background:var(--bg);
}
.import-section p { font-size:13px; color:var(--muted); margin-bottom:8px; }

.btn-export {
    background:#fff; border:1px solid var(--border); border-radius:6px;
    padding:6px 14px; font-size:13px; cursor:pointer; color:var(--text);
    display:inline-flex; align-items:center; gap:6px;
    margin-right:8px; margin-top:4px;
}
.btn-export:hover { border-color:var(--pink); color:var(--pink); }

/* ── Action buttons ── */
.btn-row { display:flex; gap:10px; margin-top:1.5rem; align-items:center; flex-wrap:wrap; }
.btn-save {
    background:var(--pink); color:#fff; border:none; border-radius:6px;
    padding:8px 22px; font-size:14px; cursor:pointer; font-weight:600;
}
.btn-save:hover { background:var(--pink-dk); }
.btn-cancel {
    background:none; border:1px solid var(--border); border-radius:6px;
    padding:8px 22px; font-size:14px; cursor:pointer; color:var(--text);
    text-decoration:none; display:inline-flex; align-items:center;
}
.btn-cancel:hover { border-color:var(--pink); color:var(--pink); }
.btn-history {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 18px; border:1px solid var(--border);
    border-radius:6px; font-size:14px; color:var(--muted);
    background:#fff; text-decoration:none;
}
.btn-history:hover { border-color:#7c3aed; color:#7c3aed; background:#f3f0ff; }

/* ── Alert ── */
.alert-error {
    background:#fdecea; color:#c0392b; padding:10px 16px;
    border-radius:6px; margin-bottom:1rem; font-size:13px;
}

/* ── Category Dropdown ── */
.pl-cat-dropdown { position:relative; width:100%; }
.pl-cat-selected {
    display:flex; justify-content:space-between; align-items:center;
    padding:7px 10px; border:1px solid var(--border); border-radius:6px;
    background:#fff; cursor:pointer; font-size:14px; min-height:36px;
}
.pl-cat-selected:hover { border-color:var(--pink); }
.pl-cat-list {
    display:none; position:absolute; top:calc(100% + 3px); left:0; right:0;
    background:#fff; border:1px solid var(--border); border-radius:6px;
    box-shadow:0 6px 20px rgba(0,0,0,0.12); z-index:200;
    max-height:220px; overflow-y:auto;
}
.pl-cat-list.open { display:block; }
.pl-cat-item {
    display:flex; justify-content:space-between; align-items:center;
    padding:9px 12px; cursor:pointer; font-size:13px;
    border-bottom:1px solid var(--border);
}
.pl-cat-item:last-child { border-bottom:none; }
.pl-cat-item:hover { background:var(--pink-xlt); }
.pl-cat-item.selected { background:var(--pink-lt); }
.pl-cat-name { font-weight:500; color:var(--text); }
.pl-cat-loc {
    font-size:11px; color:#fff; background:var(--pink);
    padding:2px 8px; border-radius:10px; white-space:nowrap;
}
.pl-cat-arrow { font-size:11px; color:var(--muted); margin-left:6px; }

.empty-row td { text-align:center; padding:24px; color:var(--muted); font-size:13px; }
</style>
@endpush

@section('content')
<div class="pl-wrap">

    <div class="page-header">
        <h2>Edit Price List</h2>
        <a href="{{ route('price-lists.index') }}" class="btn-new">← Back to Price Lists</a>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin:0;padding-left:1rem">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('price-lists.update', $priceList->id) }}" id="pl-form">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div class="form-row">
                <div class="form-label req">Name*</div>
                <div class="form-control">
                    <input type="text" name="name" id="pl-name" placeholder="Price list name"
                           value="{{ old('name', $priceList->name) }}">
                </div>
            </div>

            {{-- TRANSACTION TYPE --}}
            <div class="form-row">
                <div class="form-label">Transaction Type</div>
                <div class="form-control">
                    <div class="radio-group">
                        @foreach(['sales' => 'Sales', 'purchase' => 'Purchase', 'both' => 'Both'] as $val => $label)
                        <label>
                            <input type="radio" name="transaction_type" value="{{ $val }}"
                                {{ old('transaction_type', $priceList->transaction_type) === $val ? 'checked' : '' }}
                                onchange="renderItems()"> {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- PRICE LIST CATEGORY --}}
            <div class="form-row">
                <div class="form-label">Category <span title="Group your price lists" style="cursor:help;color:var(--muted)">ℹ</span></div>
                <div class="form-control" style="max-width:420px;">
                    <input type="hidden" name="category_id"   id="pl_category_id"
                           value="{{ old('category_id', $priceList->category_id) }}">
                    <input type="hidden" name="category_name" id="pl_category_name"
                           value="{{ old('category_name', $priceList->category_name) }}">
                    <div class="pl-cat-dropdown" id="plCatDropdown">
                        <div class="pl-cat-selected" id="plCatSelected" onclick="togglePlCatList(event)">
                            <span id="plCatSelectedText" style="color:var(--muted);">-- Select Category --</span>
                            <span class="pl-cat-arrow">▾</span>
                        </div>
                        <div class="pl-cat-list" id="plCatList">
                            <div class="pl-cat-item" data-id="" data-name="" data-loc="" onclick="selectPlCat(this)">
                                <span class="pl-cat-name" style="color:var(--muted);">-- Select Category --</span>
                            </div>
                            @foreach($categories as $cat)
                            <div class="pl-cat-item"
                                 data-id="{{ $cat->id }}"
                                 data-name="{{ $cat->name }}"
                                 data-loc="{{ $cat->location_label }}"
                                 onclick="selectPlCat(this)">
                                <span class="pl-cat-name">{{ $cat->name }}</span>
                                @if($cat->location_label)
                                    <span class="pl-cat-loc">{{ $cat->location_label }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADMIN PURPOSE ONLY --}}
            <div class="form-row">
                <div class="form-label"></div>
                <div class="form-control">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
                        <input type="checkbox" name="access_permission" id="access-permission" value="1"
                               {{ old('access_permission', $priceList->access_permission) ? 'checked' : '' }}>
                        This category use admin purpose only
                    </label>
                </div>
            </div>

            {{-- PRICE LIST TYPE --}}
            @php $plType = old('price_list_type', $priceList->price_list_type ?? 'all_items'); @endphp
            <div class="form-row">
                <div class="form-label">Price List Type</div>
                <div class="form-control">
                    <input type="hidden" name="price_list_type" id="price_list_type" value="{{ $plType }}">
                    <div class="card-group">
                        <div class="type-card {{ $plType === 'all_items' ? 'active' : '' }}"
                             id="card-all" onclick="selectType('all')">
                            <div class="tc-title">
                                <span class="check-circle {{ $plType === 'all_items' ? 'on' : '' }}" id="cc-all"></span>
                                All Items
                            </div>
                            <div class="tc-sub">Mark up or mark down the rates of all items</div>
                        </div>
                        <div class="type-card {{ $plType === 'individual_items' ? 'active' : '' }}"
                             id="card-ind" onclick="selectType('ind')">
                            <div class="tc-title">
                                <span class="check-circle {{ $plType === 'individual_items' ? 'on' : '' }}" id="cc-ind"></span>
                                Individual Items
                            </div>
                            <div class="tc-sub">Customize the rate of each item</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-row">
                <div class="form-label">Description</div>
                <div class="form-control">
                    <textarea name="description" placeholder="Enter the description">{{ old('description', $priceList->description) }}</textarea>
                </div>
            </div>

            {{-- ===== ALL ITEMS FIELDS ===== --}}
            <div id="all-fields" style="{{ $plType === 'individual_items' ? 'display:none' : '' }}">
                <div class="form-row">
                    <div class="form-label req">Percentage*</div>
                    <div class="form-control">
                        <div class="pct-row">
                            <select name="markup_type" id="markup-type">
                                <option value="markup"   {{ old('markup_type', $priceList->markup_type) === 'markup'   ? 'selected':'' }}>Markup</option>
                                <option value="markdown" {{ old('markup_type', $priceList->markup_type) === 'markdown' ? 'selected':'' }}>Markdown</option>
                            </select>
                            <input type="number" name="percentage" placeholder="0" min="0" max="100"
                                   value="{{ old('percentage', $priceList->percentage) }}">
                            <span>%</span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label req">Round Off To*</div>
                    <div class="form-control rel">
                        <select name="round_off" style="width:200px">
                            @foreach(['Never mind','Nearest whole number','0.99','0.50','0.49','Decimal Places'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ old('round_off', $priceList->round_off) === $opt ? 'selected':'' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        <div class="view-eg" onclick="toggleRounding()">View Examples</div>
                        <div class="rounding-popup" id="round-popup" style="display:none">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                                <span style="font-weight:600;font-size:14px">Rounding Examples</span>
                                <span style="cursor:pointer;color:var(--muted);font-size:16px" onclick="toggleRounding()">&#10005;</span>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Round off to</th>
                                        <th>Input value</th>
                                        <th>Rounded value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Never mind</td><td>1000.678</td><td>1000.678</td></tr>
                                    <tr><td>Nearest whole number</td><td>1000.678</td><td>1001</td></tr>
                                    <tr><td>0.99</td><td>1000.678</td><td>1000.99</td></tr>
                                    <tr><td>0.50</td><td>1000.678</td><td>1000.50</td></tr>
                                    <tr><td>0.49</td><td>1000.678</td><td>1000.49</td></tr>
                                    <tr><td>Decimal Places</td><td>1000.678</td><td>1000.68</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== INDIVIDUAL ITEMS FIELDS ===== --}}
            @php
                $pricingScheme   = old('pricing_scheme',  $priceList->pricing_scheme  ?? 'volume');
                $currency        = old('currency',         $priceList->currency        ?? 'INR');
                $includeDiscount = old('include_discount', $priceList->include_discount ?? false);
            @endphp
            <div id="ind-fields" style="{{ $plType === 'individual_items' ? '' : 'display:none' }}">
                <div class="form-row">
                    <div class="form-label">Pricing Scheme</div>
                    <div class="form-control">
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="pricing_scheme" value="unit"
                                    {{ $pricingScheme === 'unit' ? 'checked':'' }}
                                    onchange="renderItems()"> Unit Pricing
                            </label>
                            <label>
                                <input type="radio" name="pricing_scheme" value="volume"
                                    {{ $pricingScheme === 'volume' ? 'checked':'' }}
                                    onchange="renderItems()"> Volume Pricing
                                <span class="badge-vol">Volume</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">Currency</div>
                    <div class="form-control">
                        <select name="currency" style="width:220px">
                            <option value="INR" {{ $currency === 'INR' ? 'selected':'' }}>INR - Indian Rupee</option>
                            <option value="USD" {{ $currency === 'USD' ? 'selected':'' }}>USD - US Dollar</option>
                            <option value="EUR" {{ $currency === 'EUR' ? 'selected':'' }}>EUR - Euro</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">Discount</div>
                    <div class="form-control">
                        <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
                            <input type="checkbox" name="include_discount" id="disc-chk" value="1"
                                   onchange="toggleDiscount()"
                                   {{ $includeDiscount ? 'checked':'' }}>
                            I want to include discount percentage for the items
                        </label>
                        <div id="disc-note" class="discount-note" style="{{ $includeDiscount ? '':'display:none' }}">
                            When a price list is applied, the discount percentage will be applied only if discount is enabled at the line-item level.
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                    <div class="section-title" style="margin-bottom:0">Customise Rates in Bulk</div>
                    <div class="import-toggle">
                        <span>Import Price List for Items</span>
                        <div class="toggle-sw" id="import-toggle" onclick="toggleImport()"></div>
                    </div>
                </div>

                <div id="items-table-section">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:35%">Item Details</th>
                                    <th>Sales Rate</th>
                                    <th>Start Qty</th>
                                    <th>End Qty</th>
                                    <th>Custom Rate</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody"></tbody>
                        </table>
                    </div>
                </div>

                <div id="import-section" style="display:none">
                    <div class="import-section">
                        <p><strong>1. Export items as XLS file</strong><br>
                        Export all items or filter specific items, export them to an XLS file, update the rates, and import the file back.</p>
                        <button type="button" class="btn-export">&#8593; Export All Items</button>
                        <button type="button" class="btn-export">&#8593; Export Filtered Items</button>
                    </div>
                    <div class="import-section" style="margin-top:12px">
                        <p><strong>2. Import items as XLS file</strong><br>
                        Import the CSV or XLS file that you've exported and updated with the customised rates.</p>
                        <p style="margin-top:8px;font-size:12px">
                            <strong>NOTE:</strong> Column names must be in English: Item Name, SKU, Start Quantity, End Quantity, PriceList Rate
                        </p>
                        <p style="font-size:12px;margin-top:4px">
                            Once imported, existing items and rates will be replaced with the data in the import file.
                        </p>
                        <button type="button" class="btn-export" style="margin-top:10px">&#8595; Import Items</button>
                    </div>
                </div>
            </div>{{-- end #ind-fields --}}

            <div class="btn-row">
                <button type="submit" class="btn-save">Update</button>
                <a href="{{ route('price-lists.index') }}" class="btn-cancel">Cancel</a>
                <a href="{{ route('price-lists.history', $priceList->id) }}" class="btn-history">
                    🕐 View History
                </a>
            </div>

        </form>
    </div>{{-- end .card --}}

</div>{{-- end .pl-wrap --}}
@endsection

@push('scripts')
<script>
// ── Saved Data ─────────────────────────────────────────────────────────────────
const SAVED_UNIT_ITEMS   = {!! json_encode($priceList->individual_items_unit   ?? []) !!};
const SAVED_VOLUME_ITEMS = {!! json_encode($priceList->individual_items_volume ?? []) !!};

@php
$productJson = collect($items)->map(function($p) {
    return [
        'id'            => $p['id'],
        'name'          => $p['name'],
        'sku'           => $p['sku'] ?? '',
        'selling_price' => (float)($p['selling_price'] ?? 0),
        'cost_price'    => (float)($p['cost_price']    ?? 0),
    ];
})->values()->toJson();
@endphp
const ALL_PRODUCTS = {!! $productJson !!};

let extraIdx = 10000;

function escHtml(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

// ── Category Dropdown ──────────────────────────────────────────────────────────
function togglePlCatList(e) {
    e.stopPropagation();
    document.getElementById('plCatList').classList.toggle('open');
}

function selectPlCat(el) {
    const id   = el.dataset.id;
    const name = el.dataset.name;
    const loc  = el.dataset.loc;

    document.getElementById('pl_category_id').value   = id;
    document.getElementById('pl_category_name').value = name;

    const txt = document.getElementById('plCatSelectedText');
    if (name) {
        txt.style.color = 'var(--text)';
        txt.innerHTML   = escHtml(name) + (loc
            ? ' <span style="font-size:11px;color:#fff;background:var(--pink);padding:2px 8px;border-radius:10px;margin-left:6px;">' + escHtml(loc) + '</span>'
            : '');
    } else {
        txt.style.color = 'var(--muted)';
        txt.textContent = '-- Select Category --';
    }

    document.querySelectorAll('.pl-cat-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('plCatList').classList.remove('open');
}

// Outside click → close dropdown
document.addEventListener('click', function (e) {
    const dd = document.getElementById('plCatDropdown');
    if (dd && !dd.contains(e.target)) {
        document.getElementById('plCatList').classList.remove('open');
    }
});

// ── Page Load ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const savedId = "{{ $priceList->category_id ?? '' }}";
    if (savedId) {
        const match = document.querySelector(`.pl-cat-item[data-id="${savedId}"]`);
        if (match) selectPlCat(match);
    }

    if (document.getElementById('price_list_type').value === 'individual_items') {
        renderItems();
    }
});

// ── Items Table ────────────────────────────────────────────────────────────────
function renderItems() {
    const tbody    = document.getElementById('items-tbody');
    const isVolume = (document.querySelector('input[name="pricing_scheme"]:checked')?.value ?? 'volume') === 'volume';
    const saved    = isVolume ? SAVED_VOLUME_ITEMS : SAVED_UNIT_ITEMS;
    const txn      = document.querySelector('input[name="transaction_type"]:checked')?.value ?? 'sales';

    if (!ALL_PRODUCTS.length) {
        tbody.innerHTML = `<tr class="empty-row"><td colspan="6">No products found.</td></tr>`;
        return;
    }

    tbody.innerHTML = '';

    ALL_PRODUCTS.forEach((item, idx) => {
        const savedItem  = saved?.[item.id];
        const firstRange = savedItem?.ranges?.[0];
        const rate       = txn === 'purchase' ? item.cost_price : item.selling_price;
        const rateDisplay = rate > 0
            ? `&#8377;${rate.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`
            : `<span style="color:var(--muted)">—</span>`;

        const startVal  = firstRange?.start_qty   ?? '';
        const endVal    = firstRange?.end_qty     ?? '';
        const customVal = firstRange?.custom_rate ?? '';

        const row = document.createElement('tr');
        row.dataset.itemId = item.id;
        row.innerHTML = `
            <td>
                <input type="hidden" name="items[${idx}][item_id]" value="${item.id}">
                <div class="item-name">${escHtml(item.name)}</div>
                ${item.sku ? `<div class="item-sku">SKU: ${escHtml(item.sku)}</div>` : ''}
            </td>
            <td>${rateDisplay}</td>
            <td>${isVolume
                ? `<input type="number" name="items[${idx}][start_quantity]" min="1" style="width:70px" value="${escHtml(String(startVal))}">`
                : '&mdash;'}</td>
            <td>${isVolume
                ? `<input type="number" name="items[${idx}][end_quantity]" min="1" style="width:70px" value="${escHtml(String(endVal))}">`
                : '&mdash;'}</td>
            <td><input type="number" name="items[${idx}][custom_rate]" placeholder="0" min="0" style="width:90px" value="${escHtml(String(customVal))}"></td>
            <td><button type="button" class="btn-del" onclick="removeRow(this)">&#10005;</button></td>`;
        tbody.appendChild(row);

        if (isVolume) {
            const allRanges = savedItem?.ranges ?? [];
            allRanges.slice(1).forEach(extra => {
                const ei = extraIdx++;
                const er = document.createElement('tr');
                er.innerHTML = `
                    <td><input type="hidden" name="items[${ei}][item_id]" value="${item.id}"></td>
                    <td></td>
                    <td><input type="number" name="items[${ei}][start_quantity]" min="1" style="width:70px" value="${escHtml(String(extra.start_qty ?? ''))}"></td>
                    <td><input type="number" name="items[${ei}][end_quantity]"   min="1" style="width:70px" value="${escHtml(String(extra.end_qty   ?? ''))}"></td>
                    <td><input type="number" name="items[${ei}][custom_rate]" placeholder="0" min="0" style="width:90px" value="${escHtml(String(extra.custom_rate ?? ''))}"></td>
                    <td><button type="button" class="btn-del" onclick="this.closest('tr').remove()">&#10005;</button></td>`;
                tbody.appendChild(er);
            });

            const rr = document.createElement('tr');
            rr.className = 'range-hint-row';
            rr.dataset.parentId = item.id;
            rr.innerHTML = `<td></td><td></td><td colspan="4">
                <span class="add-range" onclick="addRange(this,'${item.id}')">+ Add New Range</span>
            </td>`;
            tbody.appendChild(rr);
        }
    });
}

function addRange(el, itemId) {
    const i = extraIdx++;
    const hintRow = el.closest('tr');
    const tr = document.createElement('tr');
    tr.dataset.rangeFor = itemId;
    tr.innerHTML = `
        <td><input type="hidden" name="items[${i}][item_id]" value="${itemId}"></td>
        <td></td>
        <td><input type="number" name="items[${i}][start_quantity]" min="1" style="width:70px"></td>
        <td><input type="number" name="items[${i}][end_quantity]"   min="1" style="width:70px"></td>
        <td><input type="number" name="items[${i}][custom_rate]" placeholder="0" min="0" style="width:90px"></td>
        <td><button type="button" class="btn-del" onclick="this.closest('tr').remove()">&#10005;</button></td>`;
    hintRow.parentNode.insertBefore(tr, hintRow);
}

function removeRow(btn) {
    const tr     = btn.closest('tr');
    const tbody  = document.getElementById('items-tbody');
    const itemId = tr.querySelector('input[name*="[item_id]"]')?.value;

    tbody.querySelectorAll(`tr[data-range-for="${itemId}"]`).forEach(r => r.remove());
    tbody.querySelectorAll('.range-hint-row').forEach(r => {
        if (r.dataset.parentId == itemId) r.remove();
    });
    tr.remove();
}

function selectType(t) {
    const isInd = t === 'ind';
    document.getElementById('card-all').classList.toggle('active', !isInd);
    document.getElementById('card-ind').classList.toggle('active',  isInd);
    document.getElementById('cc-all').className = 'check-circle' + (!isInd ? ' on' : '');
    document.getElementById('cc-ind').className = 'check-circle' + ( isInd ? ' on' : '');
    document.getElementById('price_list_type').value    = isInd ? 'individual_items' : 'all_items';
    document.getElementById('all-fields').style.display = isInd ? 'none' : '';
    document.getElementById('ind-fields').style.display = isInd ? ''     : 'none';
    if (isInd) renderItems();
}

function toggleRounding() {
    const p = document.getElementById('round-popup');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}

function toggleDiscount() {
    document.getElementById('disc-note').style.display =
        document.getElementById('disc-chk').checked ? 'block' : 'none';
}

function toggleImport() {
    const tog = document.getElementById('import-toggle');
    tog.classList.toggle('on');
    const on = tog.classList.contains('on');
    document.getElementById('items-table-section').style.display = on ? 'none'  : 'block';
    document.getElementById('import-section').style.display      = on ? 'block' : 'none';
}
</script>
@endpush