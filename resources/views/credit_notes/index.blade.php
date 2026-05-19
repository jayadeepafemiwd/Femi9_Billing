@extends('layouts.app')

@section('title', 'Credit Notes')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
        <h4 class="mb-0">Credit Notes</h4>
        <a href="{{ route('credit-notes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Credit Note
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search number or customer…" value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach(['draft','open','void','closed'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-secondary btn-sm">Filter</button>
                    <a href="{{ route('credit-notes.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>CN Number</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($creditNotes as $cn)
                        <tr>
                            <td>
                                <a href="{{ route('credit-notes.show', $cn) }}" class="fw-semibold text-decoration-none">
                                    {{ $cn->credit_note_number }}
                                </a>
                            </td>
                            <td>{{ $cn->credit_note_date->format('d M Y') }}</td>
                            <td>{{ $cn->customer?->display_name ?? '—' }}</td>
                            <td>
                                @php
                                    $badge = match($cn->status) {
                                        'open'   => 'success',
                                        'draft'  => 'secondary',
                                        'void'   => 'danger',
                                        'closed' => 'dark',
                                        default  => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($cn->status) }}</span>
                            </td>
                            <td class="text-end">{{ number_format($cn->total, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('credit-notes.show', $cn) }}"
                                   class="btn btn-outline-primary btn-xs py-0 px-2">View</a>
                                @if(!in_array($cn->status, ['void','closed']))
                                    <a href="{{ route('credit-notes.edit', $cn) }}"
                                       class="btn btn-outline-secondary btn-xs py-0 px-2">Edit</a>
                                @endif
                                @if($cn->status === 'draft')
                                    <form method="POST" action="{{ route('credit-notes.destroy', $cn) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this credit note?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-xs py-0 px-2">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No credit notes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($creditNotes->hasPages())
            <div class="card-footer">
                {{ $creditNotes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection