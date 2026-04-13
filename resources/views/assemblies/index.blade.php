@extends('layouts.app')
@section('title', 'Assemblies')

@section('content')
<div style="padding:28px 32px;">
  @if(session('success'))
    <div style="background:#d4edda;color:#155724;padding:10px 16px;border-radius:5px;margin-bottom:16px;font-size:13px;">
      {{ session('success') }}
    </div>
  @endif

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h2 style="font-size:20px;font-weight:700;">Assemblies</h2>
    <a href="{{ route('assemblies.create') }}"
       style="background:#2d5be3;color:#fff;border:none;border-radius:5px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">
      + New Assembly
    </a>
  </div>

  <div style="background:#fff;border-radius:8px;box-shadow:0 1px 6px rgba(0,0,0,.07);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
      <thead>
        <tr style="background:#f8fafd;">
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Assembly#</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Composite Item</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Date</th>
          <th style="padding:10px 14px;text-align:right;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Qty</th>
          <th style="padding:10px 14px;text-align:left;color:#4a5568;font-weight:600;border-bottom:2px solid #e2e8f0;">Status</th>
          <th style="padding:10px 14px;border-bottom:2px solid #e2e8f0;"></th>
          <th style="padding:10px 14px;border-bottom:2px solid #e2e8f0;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($assemblies as $a)
        <tr style="border-bottom:1px solid #f0f2f7;">
          <td style="padding:10px 14px;font-weight:600;color:#2d5be3;">{{ $a->assembly_number }}</td>
          <td style="padding:10px 14px;">{{ $a->composite_item_name }}</td>
          <td style="padding:10px 14px;color:#555;">{{ $a->assembled_date->format('d M Y') }}</td>
          <td style="padding:10px 14px;text-align:right;">{{ $a->quantity_to_assemble }}</td>
          <td style="padding:10px 14px;">
            <span style="padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;
              background:{{ $a->status === 'assembled' ? '#d1fae5' : '#fff3cd' }};
              color:{{ $a->status === 'assembled' ? '#065f46' : '#856404' }};">
              {{ ucfirst($a->status) }}
            </span>
          </td>
          <td style="padding:10px 14px;">
  <a href="{{ route('assemblies.edit', $a->id) }}"
     style="background:#e2e8f0;color:#2d5be3;border:none;border-radius:4px;padding:4px 10px;text-decoration:none;font-size:12px;display:inline-block;margin-right:6px;">
    Edit
  </a>
  <form method="POST" action="{{ route('assemblies.destroy', $a->id) }}" style="display:inline;">
    @csrf @method('DELETE')
    <button type="submit" 
            style="background:#fee2e2;color:#ef4444;border:none;border-radius:4px;padding:4px 10px;cursor:pointer;font-size:12px;"
            onclick="return confirm('Delete this assembly?')">Delete</button>
  </form>
</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="padding:40px;text-align:center;color:#888;">
            No assemblies yet. <a href="{{ route('assemblies.create') }}" style="color:#2d5be3;">Create one</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    @if(method_exists($assemblies, 'links'))
    <div style="padding:12px 14px;">{{ $assemblies->links() }}</div>
    @endif
  </div>
</div>
@endsection