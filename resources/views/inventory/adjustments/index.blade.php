{{-- resources/views/inventory/adjustments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Adjustments')

@section('content')
<div style="max-width:1100px;margin:20px auto;">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;margin:0;">Inventory Adjustments</h2>
        <a href="{{ route('inventory.adjustments.create') }}"
            style="background:#3d8ef8;color:#fff;border-radius:4px;padding:8px 18px;font-size:13px;text-decoration:none;">
            + New Adjustment
        </a>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div style="background:#e6f4ea;border:1px solid #b7dfb9;border-radius:4px;padding:10px 16px;margin-bottom:16px;font-size:13px;color:#2d6a4f;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div style="background:#fff;border:1px solid #dde3ee;border-radius:6px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:#f5f7fc;">
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">#</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Reference</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Date</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Mode</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Location</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Reason</th>
                    <th style="padding:10px 14px;text-align:left;color:#555;font-weight:500;border-bottom:1px solid #e4e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $adj)
                <tr style="border-bottom:1px solid #f0f2f8;">
                    <td style="padding:10px 14px;color:#888;">{{ $adj->id }}</td>
                    <td style="padding:10px 14px;">
                        <a href="{{ route('inventory.adjustments.show', $adj) }}"
                            style="color:#3d8ef8;text-decoration:none;">
                            {{ $adj->reference_number ?? '—' }}
                        </a>
                    </td>
                    <td style="padding:10px 14px;color:#555;">{{ \Carbon\Carbon::parse($adj->date)->format('d M Y') }}</td>
                    <td style="padding:10px 14px;color:#555;text-transform:capitalize;">{{ $adj->mode }}</td>
                    <td style="padding:10px 14px;color:#555;">{{ $adj->location->location_name ?? '—' }}</td>
                    <td style="padding:10px 14px;color:#555;">{{ $adj->reason }}</td>
                    <td style="padding:10px 14px;">
                        @if($adj->status === 'adjusted')
                            <span style="background:#e6f4ea;color:#2d6a4f;padding:2px 10px;border-radius:12px;font-size:12px;">Adjusted</span>
                        @else
                            <span style="background:#fff8e1;color:#b7791f;padding:2px 10px;border-radius:12px;font-size:12px;">Draft</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:32px;text-align:center;color:#aaa;font-size:13px;">
                        No adjustments found. <a href="{{ route('inventory.adjustments.create') }}" style="color:#3d8ef8;">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:16px;">
        {{ $adjustments->links() }}
    </div>

</div>
@endsection