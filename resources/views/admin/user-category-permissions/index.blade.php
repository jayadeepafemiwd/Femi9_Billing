{{-- resources/views/admin/user-category-permissions/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Category Permissions')

@section('content')
<div class="pm-page">

    <div class="pm-header">
        <div>
            <h1 class="pm-title">Category Permissions</h1>
            <p class="pm-sub">Manage module access control for each user category</p>
        </div>
        <a href="{{ route('admin.ucp.create') }}" class="pm-btn pm-btn--primary">+ Create Role</a>
    </div>

    <div class="pm-card">
        <table class="pm-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Category</th>
                    <th>Level</th>
                    <th class="pm-center">Create</th>
                    <th class="pm-center">Read</th>
                    <th class="pm-center">Edit</th>
                    <th class="pm-center">Delete</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $i => $role)
                <tr>
                    <td style="color:var(--pm-muted)">{{ $i + 1 }}</td>
                    <td style="font-weight:500">{{ $role->name }}</td>
                    <td>
                        <span class="pm-cat-badge">{{ $role->category->name ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="pm-level-badge">Level {{ $role->category->level ?? '—' }}</span>
                    </td>

                    @php
                        $perms  = $role->permissions;
                        $total  = count(\App\Http\Controllers\UserCategoryPermissionController::getModules());
                        $create = $perms->where('can_create', true)->count();
                        $read   = $perms->where('can_read',   true)->count();
                        $edit   = $perms->where('can_edit',   true)->count();
                        $delete = $perms->where('can_delete', true)->count();
                    @endphp

                    <td class="pm-center">
                        <span class="pm-count {{ $create > 0 ? 'pm-count--on' : 'pm-count--off' }}">
                            {{ $create }}/{{ $total }}
                        </span>
                    </td>
                    <td class="pm-center">
                        <span class="pm-count {{ $read > 0 ? 'pm-count--on' : 'pm-count--off' }}">
                            {{ $read }}/{{ $total }}
                        </span>
                    </td>
                    <td class="pm-center">
                        <span class="pm-count {{ $edit > 0 ? 'pm-count--on' : 'pm-count--off' }}">
                            {{ $edit }}/{{ $total }}
                        </span>
                    </td>
                    <td class="pm-center">
                        <span class="pm-count {{ $delete > 0 ? 'pm-count--on' : 'pm-count--off' }}">
                            {{ $delete }}/{{ $total }}
                        </span>
                    </td>

                    <td style="text-align:right;white-space:nowrap">
                        <a href="{{ route('admin.ucp.edit', $role->id) }}" class="pm-action-btn">Edit</a>
                        <button class="pm-action-btn pm-action-btn--danger"
                                onclick="deleteRole({{ $role->id }}, this)">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--pm-muted)">
                        No roles created yet.
                        <a href="{{ route('admin.ucp.create') }}" style="color:var(--pm-blue)">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pm-toast" id="pm-toast"></div>
</div>

<style>
:root {
    --pm-blue:    #185FA5;
    --pm-blue-lt: #E6F1FB;
    --pm-blue-dk: #0C447C;
    --pm-green:   #0F6E56;
    --pm-green-lt:#E1F5EE;
    --pm-red:     #993C1D;
    --pm-red-lt:  #FAECE7;
    --pm-border:  rgba(0,0,0,0.08);
    --pm-text:    #1a1a1a;
    --pm-muted:   #888;
    --pm-bg:      #fff;
    --pm-bg2:     #f8f8f6;
    --pm-radius:  10px;
}

.pm-page    { padding: 24px; max-width: 1100px; margin: 0 auto; }
.pm-header  { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
.pm-title   { font-size: 20px; font-weight: 500; color: var(--pm-text); margin: 0; }
.pm-sub     { font-size: 13px; color: var(--pm-muted); margin: 4px 0 0; }

.pm-card    { background: var(--pm-bg); border: 0.5px solid var(--pm-border); border-radius: var(--pm-radius); overflow: hidden; }

.pm-table   { width: 100%; border-collapse: collapse; font-size: 13px; }
.pm-table thead tr { background: var(--pm-bg2); }
.pm-table th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 500; color: var(--pm-muted); border-bottom: 0.5px solid var(--pm-border); text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; }
.pm-table td { padding: 12px 14px; border-bottom: 0.5px solid var(--pm-border); vertical-align: middle; color: var(--pm-text); }
.pm-table tr:last-child td { border-bottom: none; }
.pm-table tr:hover td { background: #fafaf9; }
.pm-center  { text-align: center; }

.pm-level-badge { font-size: 11px; padding: 2px 8px; border-radius: 20px; background: var(--pm-blue-lt); color: var(--pm-blue); font-weight: 500; white-space: nowrap; }
.pm-cat-badge   { font-size: 12px; padding: 3px 10px; border-radius: 20px; background: var(--pm-bg2); color: var(--pm-text); font-weight: 500; border: 0.5px solid var(--pm-border); white-space: nowrap; }

.pm-count       { font-size: 12px; font-weight: 500; padding: 2px 8px; border-radius: 20px; }
.pm-count--on   { background: var(--pm-green-lt); color: var(--pm-green); }
.pm-count--off  { background: var(--pm-bg2); color: var(--pm-muted); }

.pm-btn         { height: 36px; padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; border: 0.5px solid var(--pm-border); background: var(--pm-bg); color: var(--pm-muted); display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.pm-btn:hover   { background: var(--pm-bg2); }
.pm-btn--primary { background: var(--pm-blue); color: #fff; border-color: var(--pm-blue); }
.pm-btn--primary:hover { background: var(--pm-blue-dk); }

.pm-action-btn  { padding: 4px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; border: 0.5px solid var(--pm-border); background: var(--pm-bg); color: var(--pm-muted); margin-left: 4px; }
.pm-action-btn:hover { background: var(--pm-bg2); }
.pm-action-btn--danger { color: var(--pm-red); border-color: #F0997B; }
.pm-action-btn--danger:hover { background: var(--pm-red-lt); }

.pm-toast       { position: fixed; bottom: 24px; right: 24px; background: #1a1a1a; color: #fff; padding: 10px 18px; border-radius: 8px; font-size: 13px; opacity: 0; pointer-events: none; transition: opacity .3s; z-index: 9999; }
.pm-toast.show  { opacity: 1; }
.pm-toast.error { background: #991b1b; }
</style>

<script>
const CSRF = '{{ csrf_token() }}';

async function deleteRole(id, btn) {
    if (!confirm('Delete this role? This cannot be undone.')) return;
    btn.disabled = true;
    try {
        const res  = await fetch(`/admin/user-category-permissions/${id}`, {
            method:  'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const json = await res.json();
        if (json.success) {
            btn.closest('tr').remove();
            showToast('Role deleted');
        } else {
            showToast(json.message || 'Delete failed', true);
            btn.disabled = false;
        }
    } catch (e) {
        showToast('Network error', true);
        btn.disabled = false;
    }
}

function showToast(msg, isError = false) {
    const t = document.getElementById('pm-toast');
    t.textContent = msg;
    t.className   = 'pm-toast show' + (isError ? ' error' : '');
    setTimeout(() => t.classList.remove('show'), 2200);
}
</script>

@endsection