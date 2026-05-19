@extends('layouts.app')

@section('title', 'Credit Note ' . $creditNote->credit_note_number)

@section('content')
<div class="container-fluid px-4 mt-3">

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Top bar --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <a href="{{ route('credit-notes.index') }}" class="text-muted text-decoration-none small">
                ← Credit Notes
            </a>
            <h4 class="mb-0 mt-1">{{ $creditNote->credit_note_number }}</h4>
        </div>
        <div class="d-flex gap-2">
            @if(!in_array($creditNote->status, ['void','closed']))
                <a href="{{ route('credit-notes.edit', $creditNote) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            @endif
            @if($creditNote->status === 'open')
                <form method="POST" action="{{ route('credit-notes.void', $creditNote) }}"
                      onsubmit="return confirm('Void this credit note?')">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">Void</button>
                </form>
            @endif
            @if($creditNote->status === 'draft')
                <form method="POST" action="{{ route('credit-notes.destroy', $creditNote) }}"
                      onsubmit="return confirm('Delete this credit note?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-3">

        {{-- Details card --}}
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Credit Note Details</span>
                    @php
                        $badge = match($creditNote->status) {
                            'open'   => 'success',
                            'draft'  => 'secondary',
                            'void'   => 'danger',
                            'closed' => 'dark',
                            default  => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($creditNote->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-2 small">
                        <div class="col-sm-4 text-muted">Customer</div>
                        <div class="col-sm-8 fw-semibold">{{ $creditNote->customer?->display_name ?? '—' }}</div>

                        <div class="col-sm-4 text-muted">Date</div>
                        <div class="col-sm-8">{{ $creditNote->credit_note_date->format('d M Y') }}</div>

                        @if($creditNote->reference_number)
                        <div class="col-sm-4 text-muted">Reference</div>
                        <div class="col-sm-8">{{ $creditNote->reference_number }}</div>
                        @endif

                        <div class="col-sm-4 text-muted">Location</div>
                        <div class="col-sm-8">{{ $creditNote->location }}</div>

                        @if($creditNote->salesperson)
                        <div class="col-sm-4 text-muted">Salesperson</div>
                        <div class="col-sm-8">{{ $creditNote->salesperson->name }}</div>
                        @endif

                        @if($creditNote->subject)
                        <div class="col-sm-4 text-muted">Subject</div>
                        <div class="col-sm-8">{{ $creditNote->subject }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Line items --}}
            <div class="card">
                <div class="card-header fw-semibold">Line Items</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>SKU</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Disc%</th>
                                <th class="text-end">Tax%</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($creditNote->items as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    {{ $item->item_name }}
                                    @if($item->description)
                                        <div class="text-muted small">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $item->item_sku }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                <td class="text-end">{{ $item->discount_percentage }}%</td>
                                <td class="text-end">{{ $item->tax_percentage }}%</td>
                                <td class="text-end fw-semibold">{{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>{{ number_format($creditNote->total, 2) }}</span>
                    </div>

                 {{-- ✅ Applied / Unused breakdown --}}
@if($creditNote->status === 'open')
<hr class="my-2">

@if($creditNote->invoice_id)
{{-- ✅ Applied amount மட்டும் காட்டு —}}
<div class="d-flex justify-content-between mb-1">
    <span class="text-muted" style="font-size:12px;">Applied to Invoice</span>
    <a href="{{ route('invoices.show', $creditNote->invoice_id) }}"
       style="font-size:12px;font-weight:600;color:#4a90d9;text-decoration:none;">
        {{ \App\Models\Invoice::find($creditNote->invoice_id)?->invoice_number }}
    </a>
</div>
<div class="d-flex justify-content-between mb-1">
    <span class="text-muted" style="font-size:12px;">Amount Applied</span>
    <span style="font-size:12px;font-weight:700;color:#166534;">
        ₹{{ number_format($creditNote->applied_amount ?? 0, 2) }}
    </span>
</div>

{{-- ✅ Total credit note amount தனியா காட்டு --}}
<div class="d-flex justify-content-between mb-1">
    <span class="text-muted" style="font-size:12px;">Credit Note Total</span>
    <span style="font-size:12px;color:#6b7280;">
        ₹{{ number_format($creditNote->total, 2) }}
    </span>
</div>
@endif

@if(($creditNote->unused_amount ?? 0) > 0)
<div class="d-flex justify-content-between align-items-center mt-2 px-2 py-2"
     style="background:#fef3c7;border-radius:6px;border:1px solid #fde047;">
    <div>
        <div style="font-size:12px;font-weight:600;color:#92400e;">Unused Credit</div>
        <div style="font-size:11px;color:#92400e;margin-top:2px;">
            Available for future invoices
        </div>
    </div>
    <span style="font-size:13px;font-weight:800;color:#92400e;">
        ₹{{ number_format($creditNote->unused_amount, 2) }}
    </span>
</div>
@else
<div class="d-flex justify-content-between align-items-center mt-2 px-2 py-2"
     style="background:#dcfce7;border-radius:6px;border:1px solid #22c55e;">
    <span style="font-size:12px;font-weight:600;color:#166534;">Fully Applied</span>
    <span style="font-size:12px;color:#166534;">✓ No unused amount</span>
</div>
@endif

@endif
                    {{-- ✅ END Applied / Unused --}}

                </div>
            </div>  {{-- /card-body + /card (Summary) --}}
            </div>
        </div>

        {{-- Totals + Notes --}}
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Summary</div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Sub Total</span>
                        <span>{{ number_format($creditNote->sub_total, 2) }}</span>
                    </div>
                    @if($creditNote->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Discount ({{ $creditNote->discount_percentage }}%)</span>
                        <span class="text-danger">− {{ number_format($creditNote->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($creditNote->tax_amount > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Tax ({{ $creditNote->tax_type }})</span>
                        <span>{{ number_format($creditNote->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($creditNote->adjustment != 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Adjustment</span>
                        <span>{{ number_format($creditNote->adjustment, 2) }}</span>
                    </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>{{ number_format($creditNote->total, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($creditNote->customer_notes)
            <div class="card mb-3">
                <div class="card-header fw-semibold">Customer Notes</div>
                <div class="card-body small">{{ $creditNote->customer_notes }}</div>
            </div>
            @endif

            @if($creditNote->terms_and_conditions)
            <div class="card">
                <div class="card-header fw-semibold">Terms & Conditions</div>
                <div class="card-body small">{{ $creditNote->terms_and_conditions }}</div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection