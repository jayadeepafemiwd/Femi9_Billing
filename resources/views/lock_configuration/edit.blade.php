@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm" style="max-width: 780px; margin: auto;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
            <h5 class="mb-0 fw-bold">Edit Lock Configuration</h5>
            <a href="{{ route('lock_configuration.index') }}" class="text-muted text-decoration-none">
                Close Settings ✕
            </a>
        </div>

        <div class="card-body py-4 px-4">

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lock_configuration.update', $config->id) }}" method="POST" id="lockForm">
                @csrf
                @method('PUT')

                {{-- ── Lock Configuration Name ─────────────────────────── --}}
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Lock Configuration Name*
                    </label>
                    <div class="col-sm-8">
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $config->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ── Description ─────────────────────────────────────── --}}
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label">Description</label>
                    <div class="col-sm-8">
                        <textarea name="description"
                                  class="form-control"
                                  rows="3">{{ old('description', $config->description) }}</textarea>
                    </div>
                </div>

                <hr class="my-3">

                {{-- ── Allow or Restrict Actions ────────────────────────── --}}
                @php $currentActionType = old('action_type', $config->action_type); @endphp
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Allow or Restrict Actions* ⓘ
                    </label>
                    <div class="col-sm-8">
                        <select name="action_type" id="actionTypeSelect" class="form-select mb-2">
                            <option value="restrict_all"      {{ $currentActionType == 'restrict_all'      ? 'selected' : '' }}>Restrict All Actions</option>
                            <option value="restrict_selected" {{ $currentActionType == 'restrict_selected' ? 'selected' : '' }}>Restrict Selected Actions</option>
                            <option value="allow_selected"    {{ $currentActionType == 'allow_selected'    ? 'selected' : '' }}>Allow Selected Actions</option>
                        </select>

                        <div id="actionsSubSection">
                            <div id="actionsCheckboxList" class="border rounded p-2 bg-white">
                                <div class="text-muted small px-1 pb-1" id="actionsLabel">
                                    {{ $currentActionType == 'allow_selected' ? 'Select allowed actions' : 'Select restricted actions' }}
                                </div>
                                @php $currentActions = old('selected_actions', $config->selected_actions ?? []); @endphp
                                <div class="form-check py-1 border-bottom">
                                    <input class="form-check-input" type="checkbox"
                                           name="selected_actions[]" value="Edit"
                                           id="actionEdit"
                                           {{ in_array('Edit', $currentActions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actionEdit">Edit</label>
                                </div>
                                <div class="form-check py-1">
                                    <input class="form-check-input" type="checkbox"
                                           name="selected_actions[]" value="Delete"
                                           id="actionDelete"
                                           {{ in_array('Delete', $currentActions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actionDelete">Delete</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Allow or Restrict Fields ─────────────────────────── --}}
                @php $currentFieldType = old('field_type', $config->field_type); @endphp
                <div class="row mb-3 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">
                        Allow or Restrict Fields* ⓘ
                    </label>
                    <div class="col-sm-8">
                        <select name="field_type" id="fieldTypeSelect" class="form-select mb-2">
                            <option value="restrict_all"      {{ $currentFieldType == 'restrict_all'      ? 'selected' : '' }}>Restrict All Fields</option>
                            <option value="restrict_selected" {{ $currentFieldType == 'restrict_selected' ? 'selected' : '' }}>Restrict Selected Fields</option>
                            <option value="allow_selected"    {{ $currentFieldType == 'allow_selected'    ? 'selected' : '' }}>Allow Selected Fields</option>
                        </select>

                        <div id="fieldsSubSection">
                            <div class="border rounded bg-white">
                                <div class="p-2 border-bottom">
                                    <input type="text" id="fieldSearch"
                                           class="form-control form-control-sm"
                                           placeholder="🔍 Search fields...">
                                </div>
                                <div style="max-height: 220px; overflow-y: auto;" id="fieldsList">
                                    <div class="text-muted small px-2 py-1" id="fieldsLabel">
                                        {{ $currentFieldType == 'allow_selected' ? 'Select allowed fields' : 'Select restricted fields' }}
                                    </div>
                                    @php $currentFields = old('selected_fields', $config->selected_fields ?? []); @endphp
                                    @foreach($availableFields as $key => $label)
                                        <div class="form-check px-3 py-1 field-item" data-label="{{ strtolower($label) }}">
                                            <input class="form-check-input" type="checkbox"
                                                   name="selected_fields[]"
                                                   value="{{ $key }}"
                                                   id="field_{{ $key }}"
                                                   {{ in_array($key, $currentFields) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="field_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Lock Records For ─────────────────────────────────── --}}
                @php $currentLockFor = old('lock_for_type', $config->lock_for_type); @endphp
                <div class="row mb-4 align-items-start">
                    <label class="col-sm-4 col-form-label text-danger fw-semibold">Lock Records For*</label>
                    <div class="col-sm-8">
                        <select name="lock_for_type" id="lockForTypeSelect" class="form-select mb-2">
                            <option value="all_roles"        {{ $currentLockFor == 'all_roles'        ? 'selected' : '' }}>All Roles</option>
                            <option value="all_roles_except" {{ $currentLockFor == 'all_roles_except' ? 'selected' : '' }}>All Roles Except</option>
                            <option value="selected_roles"   {{ $currentLockFor == 'selected_roles'   ? 'selected' : '' }}>Selected Roles</option>
                        </select>

                        <div id="rolesSubSection" style="{{ $currentLockFor !== 'all_roles' ? 'display:block' : 'display:none' }}">
                            <div class="border rounded bg-white">
                                <div class="p-2 border-bottom">
                                    <small class="text-muted" id="rolesLabel">
                                        {{ $currentLockFor == 'all_roles_except' ? 'Except these roles' : 'Select Roles' }}
                                    </small>
                                </div>
                                @php $currentRoles = old('roles', $config->roles ?? []); @endphp
                                @foreach($availableRoles as $roleKey => $roleLabel)
                                    <div class="form-check px-3 py-2 border-bottom">
                                        <input class="form-check-input" type="checkbox"
                                               name="roles[]"
                                               value="{{ $roleKey }}"
                                               id="role_{{ $roleKey }}"
                                               {{ in_array($roleKey, $currentRoles) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $roleKey }}">
                                            {{ $roleLabel }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Buttons ─────────────────────────────────────────── --}}
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                    <a href="{{ route('lock_configuration.index') }}"
                       class="btn btn-outline-secondary px-4">Cancel</a>
                    <form action="{{ route('lock_configuration.destroy', $config->id) }}"
                          method="POST" class="ms-auto"
                          onsubmit="return confirm('Delete this configuration?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4">Delete</button>
                    </form>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── ACTION TYPE ────────────────────────────────────────────────────────
    const actionTypeSelect  = document.getElementById('actionTypeSelect');
    const actionsSubSection = document.getElementById('actionsSubSection');
    const actionsLabel      = document.getElementById('actionsLabel');
    const actionCheckboxes  = document.querySelectorAll('#actionsCheckboxList input[type="checkbox"]');

    function updateActionsUI(value) {
        if (value === 'restrict_all') {
            actionsSubSection.style.display = 'none';
            actionCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
        } else {
            actionsSubSection.style.display = 'block';
            actionCheckboxes.forEach(cb => { cb.disabled = false; });
            actionsLabel.textContent = value === 'allow_selected'
                ? 'Select allowed actions'
                : 'Select restricted actions';
        }
    }
    actionTypeSelect.addEventListener('change', function () { updateActionsUI(this.value); });
    updateActionsUI(actionTypeSelect.value);

    // ── FIELD TYPE ─────────────────────────────────────────────────────────
    const fieldTypeSelect  = document.getElementById('fieldTypeSelect');
    const fieldsSubSection = document.getElementById('fieldsSubSection');
    const fieldsLabel      = document.getElementById('fieldsLabel');
    const fieldCheckboxes  = document.querySelectorAll('#fieldsList input[type="checkbox"]');

    function updateFieldsUI(value) {
        if (value === 'restrict_all') {
            fieldsSubSection.style.display = 'none';
            fieldCheckboxes.forEach(cb => { cb.checked = false; cb.disabled = true; });
        } else {
            fieldsSubSection.style.display = 'block';
            fieldCheckboxes.forEach(cb => { cb.disabled = false; });
            fieldsLabel.textContent = value === 'allow_selected'
                ? 'Select allowed fields'
                : 'Select restricted fields';
        }
    }
    fieldTypeSelect.addEventListener('change', function () { updateFieldsUI(this.value); });
    updateFieldsUI(fieldTypeSelect.value);

    // ── LOCK FOR TYPE ──────────────────────────────────────────────────────
    const lockForTypeSelect = document.getElementById('lockForTypeSelect');
    const rolesSubSection   = document.getElementById('rolesSubSection');
    const rolesLabel        = document.getElementById('rolesLabel');
    const roleCheckboxes    = document.querySelectorAll('#rolesSubSection input[type="checkbox"]');

    function updateRolesUI(value) {
        if (value === 'all_roles') {
            rolesSubSection.style.display = 'none';
            roleCheckboxes.forEach(cb => { cb.checked = false; });
        } else {
            rolesSubSection.style.display = 'block';
            rolesLabel.textContent = value === 'all_roles_except' ? 'Except these roles' : 'Select Roles';
        }
    }
    lockForTypeSelect.addEventListener('change', function () { updateRolesUI(this.value); });
    updateRolesUI(lockForTypeSelect.value);

    // ── FIELD SEARCH ───────────────────────────────────────────────────────
    document.getElementById('fieldSearch').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.field-item').forEach(function (item) {
            item.style.display = item.getAttribute('data-label').includes(term) ? 'block' : 'none';
        });
    });

});
</script>
@endsection