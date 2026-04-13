@extends('layouts.app')
@section('title', 'Edit Assembly')

@section('content')
<div style="padding:28px 32px;">
  @if($errors->any())
    <div style="background:#f8d7da;color:#721c24;padding:10px 16px;border-radius:5px;margin-bottom:16px;font-size:13px;">
      <ul style="margin:0;padding-left:20px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h2 style="font-size:20px;font-weight:700;">Edit Assembly #{{ $assembly->assembly_number }}</h2>
    <a href="{{ route('assemblies.index') }}"
       style="background:#6c757d;color:#fff;border:none;border-radius:5px;padding:8px 18px;font-size:13px;font-weight:600;text-decoration:none;">
      ← Back to List
    </a>
  </div>

  <div style="background:#fff;border-radius:8px;box-shadow:0 1px 6px rgba(0,0,0,.07);padding:24px;">
    <form method="POST" action="{{ route('assemblies.update', $assembly->id) }}">
      @csrf
      @method('PUT')

      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;color:#4a5568;">Assembly Number *</label>
        <input type="text" name="assembly_number" value="{{ old('assembly_number', $assembly->assembly_number) }}"
               style="width:100%;max-width:400px;padding:8px 12px;border:1px solid #cbd5e0;border-radius:5px;font-size:13px;"
               required>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;color:#4a5568;">Composite Item Name *</label>
        <input type="text" name="composite_item_name" value="{{ old('composite_item_name', $assembly->composite_item_name) }}"
               style="width:100%;max-width:400px;padding:8px 12px;border:1px solid #cbd5e0;border-radius:5px;font-size:13px;"
               required>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;color:#4a5568;">Assembled Date *</label>
        <input type="date" name="assembled_date" value="{{ old('assembled_date', $assembly->assembled_date->format('Y-m-d')) }}"
               style="width:100%;max-width:300px;padding:8px 12px;border:1px solid #cbd5e0;border-radius:5px;font-size:13px;"
               required>
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;color:#4a5568;">Quantity to Assemble *</label>
        <input type="number" name="quantity_to_assemble" value="{{ old('quantity_to_assemble', $assembly->quantity_to_assemble) }}"
               style="width:100%;max-width:200px;padding:8px 12px;border:1px solid #cbd5e0;border-radius:5px;font-size:13px;"
               min="1" required>
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;font-size:13px;color:#4a5568;">Status *</label>
        <select name="status" style="width:100%;max-width:200px;padding:8px 12px;border:1px solid #cbd5e0;border-radius:5px;font-size:13px;" required>
          <option value="pending" {{ old('status', $assembly->status) == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="assembled" {{ old('status', $assembly->status) == 'assembled' ? 'selected' : '' }}>Assembled</option>
        </select>
      </div>

      <div style="display:flex;gap:12px;padding-top:8px;border-top:1px solid #e2e8f0;">
        <button type="submit" 
                style="background:#2d5be3;color:#fff;border:none;border-radius:5px;padding:8px 24px;font-size:13px;font-weight:600;cursor:pointer;">
          Update Assembly
        </button>
        <a href="{{ route('assemblies.index') }}"
           style="background:#e2e8f0;color:#4a5568;border:none;border-radius:5px;padding:8px 24px;font-size:13px;font-weight:600;text-decoration:none;text-align:center;">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
@endsection