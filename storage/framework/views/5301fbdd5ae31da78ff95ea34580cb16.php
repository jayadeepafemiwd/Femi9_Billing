
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Item | Zoho Inventory</title>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; font-size: 14px; color: #333; background: #f5f6fa; display: flex; height: 100vh; overflow: hidden; }
    .sidebar { width: 220px; background: #1a2340; color: #b0b8cc; display: flex; flex-direction: column; flex-shrink: 0; transition: width 0.3s ease; }
    .sidebar-logo { padding: 18px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #2a3556; }
    .sidebar-logo-icon { width: 32px; height: 32px; background: #2d5be3; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; }
    .sidebar-logo span { color: #fff; font-weight: 600; font-size: 16px; }
    .sidebar-menu { flex: 1; overflow-y: auto; padding: 10px 0; }
    .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; cursor: pointer; color: #b0b8cc; transition: background 0.15s; }
    .sidebar-item:hover { background: #243060; }
    .sidebar-item.active { background: #2d5be3; color: #fff; font-weight: 600; }
    .sidebar-item .arrow { margin-left: auto; font-size: 11px; transition: transform 0.2s; }
    .sidebar-sub { padding-left: 48px; padding-bottom: 4px; display: block; }
    .sidebar-sub-active { color: #2d5be3; font-size: 13px; padding: 5px 10px; font-weight: 600; background: #1e2d52; border-radius: 4px; cursor: pointer; }
    .sidebar-sub-item { color: #b0b8cc; font-size: 13px; padding: 5px 10px; cursor: pointer; border-radius: 4px; transition: all 0.2s; }
    .sidebar-sub-item:hover { background: #2a3556; color: #fff; padding-left: 15px; }
    .sidebar-apps-label { padding: 14px 20px 4px; color: #6b7a99; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; }
    .sidebar-collapse { padding: 10px 20px; border-top: 1px solid #2a3556; color: #6b7a99; font-size: 12px; cursor: pointer; }
    .topbar { background: #fff; border-bottom: 1px solid #e0e3ea; padding: 0 24px; display: flex; align-items: center; height: 52px; gap: 12px; flex-shrink: 0; }
    .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 14px; width: 260px; color: #aaa; background: #f8f9fc; font-size: 13px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 14px; color: #666; font-size: 13px; }
    .btn-subscribe { background: #2d5be3; color: #fff; border: none; border-radius: 5px; padding: 5px 14px; font-weight: 600; cursor: pointer; font-size: 13px; }
    .topbar-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
    .notif-wrap { position: relative; cursor: pointer; }
    .notif-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: #fff; border-radius: 50%; font-size: 9px; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 28px 32px; }
    .form-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,0.07); padding: 28px 32px; width: 100%; box-sizing: border-box; max-width: 1100px; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .form-header h2 { font-size: 22px; font-weight: 700; }
    .form-close { font-size: 20px; color: #aaa; cursor: pointer; text-decoration: none; }
    hr.divider { border: none; border-top: 1px solid #e8eaf0; margin: 20px 0; }
    .form-row { display: flex; align-items: center; margin-bottom: 16px; gap: 16px; }
    .form-row label.field-label { width: 140px; font-weight: 500; color: #444; text-align: right; flex-shrink: 0; }
    .form-row label.field-label.required { color: #c0392b; }
    .form-row input[type="text"], .form-row input[type="number"], .form-row textarea { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; font-family: inherit; outline: none; }
    .form-row input:focus, .form-row textarea:focus { border-color: #2d5be3; }
    .name-input { border: 2px solid #2d5be3 !important; }
    .select-wrap { flex: 1; position: relative; }
    .select-wrap select { width: 100%; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; background: #fff; appearance: none; color: #333; font-family: inherit; outline: none; }
    .select-wrap select:focus { border-color: #2d5be3; }
    .select-wrap .arrow { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #888; }
    .brand-row-wrap { display: flex; align-items: center; gap: 8px; flex: 1; }
    .brand-row-wrap .select-wrap { flex: 1; }
    .btn-gear { width: 34px; height: 34px; border: 1px solid #d0d4de; border-radius: 6px; background: #f5f6fa; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; color: #555; transition: background 0.15s; }
    .btn-gear:hover { background: #e8eaf0; }
    .radio-group { display: flex; gap: 20px; }
    .radio-option { display: flex; align-items: center; gap: 6px; cursor: pointer; color: #555; font-weight: 500; user-select: none; }
    .radio-option input[type="radio"] { accent-color: #2d5be3; width: 16px; height: 16px; cursor: pointer; }
    .radio-option.checked { color: #2d5be3; }
    .item-type-group { display: flex; gap: 14px; }
    .item-type-btn { border: 2px solid #d0d4de; border-radius: 8px; padding: 10px 22px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500; color: #555; background: #fff; min-width: 150px; justify-content: center; transition: all 0.15s; user-select: none; }
    .item-type-btn.active { border-color: #2d5be3; color: #2d5be3; background: #f0f4ff; }
    .radio-dot { width: 16px; height: 16px; border-radius: 50%; border: 2px solid #aaa; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .radio-dot.active { border-color: #2d5be3; background: #2d5be3; }
    .radio-dot-inner { width: 6px; height: 6px; border-radius: 50%; background: #fff; }
    .section-title { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }
    .section-title h3 { font-weight: 700; font-size: 15px; color: #222; }
    .section-title input[type="checkbox"] { width: 16px; height: 16px; accent-color: #2d5be3; cursor: pointer; }
    h3.plain-title { font-weight: 700; font-size: 15px; color: #222; margin-bottom: 16px; }
    .two-col { display: flex; gap: 32px; flex-wrap: wrap; }
    .two-col > * { flex: 1; }
    .inr-wrap { display: flex; flex: 1; border: 1px solid #d0d4de; border-radius: 6px; overflow: hidden; }
    .inr-prefix { background: #f0f1f5; padding: 8px 12px; border-right: 1px solid #d0d4de; color: #555; font-weight: 600; }
    .inr-wrap input { flex: 1; border: none; padding: 8px 12px; font-size: 14px; outline: none; font-family: inherit; }
    .image-panel { width: 280px; border: 1px solid #e0e3ea; border-radius: 10px; padding: 16px; background: #fafbfc; flex-shrink: 0; }
    .upload-label { font-weight: 600; margin-bottom: 10px; color: #444; font-size: 13px; }
    .drop-zone { border: 2px dashed #d0d4de; border-radius: 10px; min-height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: border-color 0.2s, background 0.2s; padding: 20px 12px; text-align: center; gap: 8px; }
    .drop-zone:hover, .drop-zone.dragover { border-color: #2d5be3; background: #f0f4ff; }
    .drop-zone .upload-icon { width: 40px; height: 40px; background: #2d5be3; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; margin-bottom: 4px; }
    .drop-zone .drop-title { font-weight: 700; color: #333; font-size: 13px; }
    .drop-zone .drop-sub { color: #999; font-size: 11px; line-height: 1.5; }
    .drop-zone .browse-link { color: #2d5be3; text-decoration: underline; cursor: pointer; font-size: 12px; }
    #front-preview-wrap { margin-top: 10px; display: none; position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #e0e3ea; }
    #front-preview-wrap img { width: 100%; max-height: 160px; object-fit: cover; display: block; }
    #front-preview-wrap .preview-actions { position: absolute; top: 6px; right: 6px; display: flex; gap: 4px; }
    #front-preview-wrap .preview-actions button { background: rgba(0,0,0,0.55); color: #fff; border: none; border-radius: 4px; padding: 3px 8px; font-size: 11px; cursor: pointer; }
    #front-preview-wrap .preview-actions button:hover { background: rgba(220,30,30,0.8); }
    #front-file-name { font-size: 11px; color: #888; margin-top: 6px; text-align: center; }
    .track-note { color: #888; font-size: 12px; margin-left: 26px; margin-bottom: 16px; }
    .btn-save { background: #2d5be3; color: #fff; border: none; border-radius: 7px; padding: 10px 28px; font-weight: 700; font-size: 14px; cursor: pointer; }
    .btn-save:hover { background: #1e4acf; }
    .btn-cancel { background: #fff; color: #555; border: 1px solid #d0d4de; border-radius: 7px; padding: 10px 28px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-cancel:hover { background: #f5f6fa; }
    .btn-group { display: flex; gap: 12px; margin-top: 8px; }
    .dim-hint { color: #aaa; font-size: 12px; margin-top: 4px; }
    .section-body { display: block; }
    .section-body.hidden { display: none; }
    textarea { resize: vertical; min-height: 60px; }
    .text-danger { color: #dc3545; font-size: 12px; margin-top: 4px; display: block; }
    .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
    .alert-danger { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; }

    /* Brand Modal */
    .brand-success { color: #28a745; font-size: 12px; margin-bottom: 10px; display: none; padding: 8px; background: #d4edda; border-radius: 4px; }
    .btn-edit-brand { background: none; border: none; color: #2d5be3; cursor: pointer; font-size: 14px; padding: 2px 6px; border-radius: 4px; margin-right: 4px; }
    .btn-edit-brand:hover { background: #e8f0fe; }
    .brand-list-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 6px; font-size: 13px; }
    .brand-list-item:hover { background: #f5f6fa; }
    .brand-actions { display: flex; gap: 4px; }
    .btn-del-brand, .btn-edit-brand { background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 14px; }
    .btn-edit-brand { color: #2d5be3; }
    .btn-edit-brand:hover { background: #e8f0fe; }
    .btn-del-brand { color: #e74c3c; }
    .btn-del-brand:hover { background: #fde8e8; }
    #btn-update-brand { background: #28a745; }
    #btn-update-brand:hover { background: #218838; }
    #btn-cancel-edit { background: #6c757d; }
    #btn-cancel-edit:hover { background: #5a6268; }
    .brand-list-item.editing { background: #fff3cd; border-left: 3px solid #ffc107; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; width: 480px; max-width: 95vw; box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #e8eaf0; font-weight: 700; font-size: 15px; }
    .modal-close { cursor: pointer; font-size: 18px; color: #aaa; background: none; border: none; }
    .modal-body { padding: 20px; }
    .add-brand-row { display: flex; gap: 8px; margin-bottom: 8px; }
    .add-brand-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; outline: none; font-family: inherit; }
    .add-brand-row input:focus { border-color: #2d5be3; }
    .btn-add-brand { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 8px 18px; font-weight: 600; cursor: pointer; font-size: 13px; white-space: nowrap; }
    .btn-add-brand:hover { background: #1e4acf; }
    .btn-add-brand:disabled { background: #a0b4f0; cursor: not-allowed; }
    .brand-error { color: #dc3545; font-size: 12px; margin-bottom: 10px; display: none; }
    .brand-list { max-height: 260px; overflow-y: auto; margin-top: 12px; }
    .brand-empty { color: #aaa; font-size: 13px; text-align: center; padding: 20px 0; }

    /* Unit Custom Dropdown */
    .unit-dropdown-wrap { position: relative; flex: 1; }
    .unit-input-box { display: flex; align-items: center; border: 1px solid #d0d4de; border-radius: 6px; background: #fff; cursor: pointer; transition: border-color 0.15s; }
    .unit-input-box.focused { border-color: #2d5be3; }
    .unit-input-box input { flex: 1; border: none; outline: none; padding: 8px 12px; font-size: 14px; font-family: inherit; background: transparent; cursor: text; color: #333; min-width: 0; }
    .unit-input-box input::placeholder { color: #aaa; }
    .unit-chevron { padding: 8px 10px; color: #888; font-size: 11px; pointer-events: none; user-select: none; flex-shrink: 0; }
    .unit-dropdown-menu { display: none; position: absolute; top: calc(100% + 3px); left: 0; width: 100%; background: #fff; border: 1px solid #d0d4de; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); z-index: 500; max-height: 260px; overflow-y: auto; }
    .unit-dropdown-menu.open { display: block; }
    .unit-option { display: flex; align-items: center; justify-content: space-between; padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer; transition: background 0.1s; user-select: none; }
    .unit-option:hover { background: #f0f4ff; }
    .unit-option.selected { background: #e8efff; color: #2d5be3; font-weight: 600; }
    .unit-option .unit-del { display: none; background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 12px; padding: 2px 7px; border-radius: 4px; line-height: 1; }
    .unit-option:hover .unit-del { display: inline-flex; align-items: center; }
    .unit-option .unit-del:hover { background: #fde8e8; }
    .unit-no-result { padding: 10px 14px; color: #aaa; font-size: 13px; }
    .unit-add-new { padding: 9px 14px; font-size: 13px; color: #2d5be3; cursor: pointer; border-top: 1px solid #e8eaf0; display: flex; align-items: center; gap: 6px; font-weight: 500; }
    .unit-add-new:hover { background: #f0f4ff; }

    /* Add Identifier */
    .add-identifier-wrap { margin-top: 10px; margin-left: 156px; }
    .add-link { color: #2d5be3; cursor: pointer; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; user-select: none; }
    .add-link:hover { text-decoration: underline; }
    .identifier-fields { display: none; margin-top: 14px; }
    .identifier-fields.show { display: block; }
    .identifier-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 32px; }
    .identifier-row { display: flex; align-items: center; gap: 12px; }
    .identifier-row label { width: 55px; font-size: 13px; color: #555; font-weight: 500; flex-shrink: 0; text-align: right; }
    .identifier-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; }
    .identifier-row input:focus { border-color: #2d5be3; }

    /* CATEGORY DROPDOWN */
    .cat-dd-wrap { position: relative; flex: 1; }
    .cat-dd-box { display: flex; align-items: center; justify-content: space-between; border: 1px solid #d0d4de; border-radius: 6px; background: #fff; padding: 8px 12px; cursor: pointer; user-select: none; transition: border-color 0.15s; min-height: 38px; }
    .cat-dd-box:hover, .cat-dd-box.open { border-color: #2d5be3; }
    .cat-dd-label { font-size: 14px; color: #aaa; }
    .cat-dd-label.has-val { color: #333; }
    .cat-dd-chev { font-size: 11px; color: #888; flex-shrink: 0; }
    .cat-dd-panel { display: none; position: absolute; top: calc(100% + 3px); left: 0; width: 100%; background: #fff; border: 1px solid #d0d4de; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.13); z-index: 600; }
    .cat-dd-panel.open { display: block; }
    .cat-dd-sr { display: flex; align-items: center; gap: 6px; padding: 7px 10px; border-bottom: 1px solid #eee; }
    .cat-dd-sr input { flex: 1; border: none; outline: none; font-size: 13px; font-family: inherit; background: transparent; }
    .cat-dd-sr input::placeholder { color: #bbb; }
    .cat-dd-list { max-height: 200px; overflow-y: auto; }
    .cat-dd-item { display: flex; align-items: center; gap: 8px; padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer; }
    .cat-dd-item:hover { background: #f0f4ff; color: #2d5be3; }
    .cat-dd-item.sel { background: #2d5be3; color: #fff; }
    .cat-parent-hint { font-size: 11px; color: #aaa; margin-left: 2px; }
    .cat-dd-item.sel .cat-parent-hint { color: #c5d3f7; }
    .cat-dd-empty { padding: 12px 14px; font-size: 13px; color: #aaa; text-align: center; }
    .cat-dd-manage { display: flex; align-items: center; gap: 7px; padding: 10px 14px; font-size: 13px; color: #2d5be3; font-weight: 500; cursor: pointer; border-top: 1px solid #eee; }
    .cat-dd-manage:hover { background: #f0f4ff; }

    /* MANAGE CATEGORIES MODAL */
    .cat-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 2000; align-items: flex-start; justify-content: center; padding-top: 60px; }
    .cat-modal-overlay.open { display: flex; }
    .cat-modal-box { background: #fff; border-radius: 8px; width: 700px; max-width: 96vw; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden; }
    .cat-modal-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #eee; flex-shrink: 0; }
    .cat-modal-head span { font-size: 16px; font-weight: 600; color: #222; }
    .cat-modal-x { background: none; border: none; font-size: 22px; color: #e74c3c; cursor: pointer; font-weight: 700; line-height: 1; }
    .cat-modal-body { flex: 1; overflow-y: auto; padding: 24px; }
    .cat-new-form { display: none; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #e8eaf0; }
    .cat-new-form.show { display: block; }
    .cat-form-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .cat-form-row label { width: 140px; font-size: 13px; font-weight: 500; text-align: right; flex-shrink: 0; color: #444; }
    .cat-form-row label.req { color: #c0392b; }
    .cat-form-row input[type="text"] { flex: 1; border: 2px solid #2d5be3; border-radius: 6px; padding: 7px 12px; font-size: 14px; font-family: inherit; outline: none; }
    .cat-psw { flex: 1; position: relative; }
    .cat-psw select { width: 100%; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 12px; font-size: 14px; background: #fff; appearance: none; font-family: inherit; outline: none; color: #333; }
    .cat-psw .arr { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #888; font-size: 11px; }
    .cat-form-btns { display: flex; gap: 8px; margin-left: 152px; }
    .btn-cs { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 8px 22px; font-weight: 600; font-size: 13px; cursor: pointer; }
    .btn-cs:hover { background: #1e4acf; }
    .btn-cs:disabled { background: #a0b4f0; cursor: not-allowed; }
    .btn-cc { background: #fff; color: #555; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 16px; font-size: 13px; cursor: pointer; }
    .cat-fe { color: #dc3545; font-size: 12px; margin-left: 152px; margin-top: -8px; margin-bottom: 8px; display: none; }
    .cat-list-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .cat-list-title { font-size: 12px; font-weight: 700; color: #4a5568; letter-spacing: 0.6px; text-transform: uppercase; }
    .btn-anc { display: flex; align-items: center; gap: 5px; background: none; border: none; color: #2d5be3; font-size: 13px; font-weight: 600; cursor: pointer; padding: 4px 8px; border-radius: 4px; }
    .btn-anc:hover { background: #e8f0fe; }
    .anc-circle { width: 18px; height: 18px; border-radius: 50%; background: #2d5be3; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; line-height: 1; }
    .cat-li { display: flex; align-items: center; gap: 10px; padding: 10px 4px; border-bottom: 1px solid #f0f2f7; }
    .cat-li:last-child { border-bottom: none; }
    .cat-li .li-icon { font-size: 16px; color: #5b8dee; }
    .cat-li .li-name { flex: 1; font-size: 13px; font-weight: 500; color: #333; }
    .cat-li .li-parent { font-size: 11px; color: #aaa; margin-left: 4px; }
    .cat-li .li-acts { display: flex; gap: 2px; }
    .cat-li .li-acts button { background: none; border: none; cursor: pointer; padding: 4px 7px; border-radius: 4px; font-size: 13px; }
    .li-edit { color: #2d5be3; }
    .li-edit:hover { background: #e8f0fe; }
    .li-del { color: #e74c3c; }
    .li-del:hover { background: #fde8e8; }
    .cat-li-empty { text-align: center; padding: 24px; color: #aaa; font-size: 13px; }
    .cat-ms { background: #d4edda; color: #155724; font-size: 12px; padding: 8px 12px; border-radius: 4px; margin-bottom: 12px; display: none; }
    .cat-modal-foot { padding: 12px 24px; border-top: 1px solid #eee; flex-shrink: 0; }
    .btn-cf { background: #fff; color: #555; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 20px; font-size: 13px; cursor: pointer; }

    /* ══════════════════════════════════════════
       VARIATIONS SECTION — NEW STYLES
    ══════════════════════════════════════════ */
    #variations-section { display: none; }
    #variations-section.show { display: block; }

    /* Attribute rows */
    .variation-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; padding: 14px 16px; background: #f8f9fc; border: 1px solid #e8eaf0; border-radius: 8px; }
    .var-attr-wrap { width: 200px; flex-shrink: 0; position: relative; }
    .var-attr-wrap select { width: 100%; border: 1px solid #d0d4de; border-radius: 6px; padding: 8px 12px; font-size: 14px; background: #fff; appearance: none; font-family: inherit; outline: none; color: #333; }
    .var-attr-wrap select:focus { border-color: #2d5be3; }
    .var-attr-wrap .arr { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #888; font-size: 11px; }
    .var-options-wrap { flex: 1; }
    .tag-input-box { display: flex; flex-wrap: wrap; gap: 6px; border: 1px solid #d0d4de; border-radius: 6px; padding: 6px 10px; background: #fff; min-height: 40px; cursor: text; }
    .tag-input-box:focus-within { border-color: #2d5be3; }
    .tag-chip { display: inline-flex; align-items: center; gap: 4px; background: #e8efff; color: #2d5be3; border-radius: 4px; padding: 3px 8px; font-size: 12px; font-weight: 500; }
    .tag-chip button { background: none; border: none; cursor: pointer; color: #2d5be3; font-size: 14px; line-height: 1; padding: 0 1px; }
    .tag-chip button:hover { color: #e74c3c; }
    .tag-real-input { border: none; outline: none; font-size: 13px; font-family: inherit; min-width: 80px; flex: 1; padding: 2px 4px; }
    .var-del-btn { background: none; border: none; cursor: pointer; color: #ccc; font-size: 18px; padding: 6px; flex-shrink: 0; border-radius: 4px; transition: color 0.15s; }
    .var-del-btn:hover { color: #e74c3c; background: #fde8e8; }
    .add-variation-btn { display: inline-flex; align-items: center; gap: 6px; color: #2d5be3; font-size: 13px; font-weight: 500; cursor: pointer; border: none; background: none; padding: 6px 0; margin-top: 4px; }
    .add-variation-btn:hover { text-decoration: underline; }

    /* Variants generated table */
    #variants-table-wrap { display: none; margin-top: 20px; }
    #variants-table-wrap.show { display: block; }
    .variants-tbl { width: 100%; border-collapse: collapse; }
    .variants-tbl thead tr { background: #f5f6fa; border-bottom: 2px solid #e0e3ea; }
    .variants-tbl thead th { padding: 10px 12px; font-size: 12px; font-weight: 700; color: #e74c3c; text-align: left; text-transform: uppercase; letter-spacing: 0.4px; }
    .variants-tbl thead th.th-sku { color: #555; }
    .variants-tbl thead th .copy-all { color: #2d5be3; font-size: 11px; font-weight: 500; cursor: pointer; display: block; text-decoration: underline; margin-top: 2px; }
    .variants-tbl thead th .gen-sku-btn { color: #2d5be3; font-size: 11px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 3px; text-decoration: underline; margin-top: 2px; }
    .variants-tbl tbody tr { border-bottom: 1px solid #f0f2f7; }
    .variants-tbl tbody tr:hover { background: #fafbff; }
    .var-row-name { padding: 10px 12px; font-size: 13px; font-weight: 500; color: #333; }
    .var-row-input { padding: 6px 8px; }
    .var-row-input input { width: 100%; border: 1px solid #e0e3ea; border-radius: 5px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; background: #fff; }
    .var-row-input input:focus { border-color: #2d5be3; }
    .var-row-actions { padding: 6px 8px; white-space: nowrap; }
    .btn-var-info { background: none; border: none; cursor: pointer; color: #2d5be3; font-size: 16px; padding: 4px 6px; border-radius: 4px; }
    .btn-var-info:hover { background: #e8f0fe; }
    .btn-var-del { background: none; border: none; cursor: pointer; color: #e74c3c; font-size: 16px; padding: 4px 6px; border-radius: 4px; }
    .btn-var-del:hover { background: #fde8e8; }
    .reporting-tags-row td { padding: 2px 12px 8px; }
    .reporting-tags-toggle { display: inline-flex; align-items: center; gap: 5px; color: #888; font-size: 12px; cursor: pointer; user-select: none; }
    .reporting-tags-toggle:hover { color: #2d5be3; }
    /* ══ VARIANT IMAGE UPLOAD ══ */
.var-img-cell { padding: 6px 8px; vertical-align: middle; }
.var-img-wrap { position: relative; display: inline-block; }
.var-img-trigger {
  width: 54px; height: 54px;
  border: 2px dashed #c8cfe0;
  border-radius: 8px;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  cursor: pointer;
  background: #f8f9fc;
  transition: border-color 0.15s, background 0.15s;
  overflow: hidden;
}
.var-img-trigger:hover { border-color: #2d5be3; background: #f0f4ff; }
.var-img-trigger.has-img { border-style: solid; border-color: #2d5be3; padding: 0; background: transparent; }
.vit-icon  { font-size: 18px; color: #b0b8cc; pointer-events: none; }
.vit-label { font-size: 9px; color: #b0b8cc; line-height: 1.1; margin-top: 2px; pointer-events: none; }
img.vit-preview { width: 100%; height: 100%; object-fit: cover; display: none; border-radius: 6px; }
.has-img img.vit-preview { display: block; }
.has-img .vit-icon, .has-img .vit-label { display: none; }
.var-img-remove {
  display: none;
  position: absolute; top: -5px; right: -5px;
  width: 16px; height: 16px;
  background: #e74c3c; color: #fff;
  border: none; border-radius: 50%;
  cursor: pointer; font-size: 10px;
  align-items: center; justify-content: center;
  line-height: 1; z-index: 10; padding: 0;
}
.var-img-wrap:hover .var-img-remove { display: flex; }
.var-img-file { display: none; }

    /* Additional Info Modal (per variant) */
    .addl-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 3000; align-items: center; justify-content: center; }
    .addl-modal-overlay.open { display: flex; }
    .addl-modal-box { background: #fff; border-radius: 10px; width: 680px; max-width: 96vw; max-height: 88vh; display: flex; flex-direction: column; box-shadow: 0 8px 32px rgba(0,0,0,0.18); overflow: hidden; }
    .addl-modal-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #eee; flex-shrink: 0; }
    .addl-modal-head span { font-size: 15px; font-weight: 700; color: #222; }
    .addl-modal-x { background: none; border: none; font-size: 22px; color: #e74c3c; cursor: pointer; font-weight: 700; }
    .addl-modal-body { flex: 1; overflow-y: auto; padding: 24px; }
    .addl-section-title { font-size: 14px; font-weight: 700; color: #222; margin-bottom: 16px; }
    .addl-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 32px; margin-bottom: 24px; }
    .addl-field-row { display: flex; align-items: center; gap: 12px; }
    .addl-field-row label { width: 60px; font-size: 13px; color: #555; font-weight: 500; flex-shrink: 0; }
    .addl-field-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; }
    .addl-field-row input:focus { border-color: #2d5be3; }
    .addl-divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    .addl-custom-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .addl-custom-row label { width: 120px; font-size: 13px; color: #555; font-weight: 500; flex-shrink: 0; }
    .addl-custom-row input { flex: 1; border: 1px solid #d0d4de; border-radius: 6px; padding: 7px 10px; font-size: 13px; font-family: inherit; outline: none; }
    .addl-custom-row input:focus { border-color: #2d5be3; }
    .addl-modal-foot { padding: 14px 24px; border-top: 1px solid #eee; display: flex; gap: 10px; flex-shrink: 0; }
    .btn-addl-save { background: #2d5be3; color: #fff; border: none; border-radius: 6px; padding: 9px 24px; font-weight: 700; font-size: 13px; cursor: pointer; }
    .btn-addl-save:hover { background: #1e4acf; }
    .btn-addl-cancel { background: #fff; color: #555; border: 1px solid #d0d4de; border-radius: 6px; padding: 9px 18px; font-size: 13px; cursor: pointer; }

    /* Single Item — hidden fields when Variants mode */
    .single-only { }
    .single-only.hide { display: none !important; }
    .variant-only { display: none; }
    .variant-only.show { display: block; }
    .var-attr-input {
  width: 100%;
  border: 1px solid #d0d4de;
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 14px;
  font-family: inherit;
  outline: none;
  transition: border-color 0.15s;
}

.var-attr-input:focus {
  border-color: #2d5be3;
}
/* GENERATE SKU MODAL */
.sku-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:5000; align-items:flex-start; justify-content:center; padding-top:60px; }
.sku-modal-overlay.open { display:flex; }
.sku-modal-box { background:#fff; border-radius:8px; width:900px; max-width:96vw; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 8px 32px rgba(0,0,0,0.2); overflow:hidden; }
.sku-modal-head { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-bottom:1px solid #eee; flex-shrink:0; }
.sku-modal-head span { font-size:16px; font-weight:700; color:#222; }
.sku-modal-x { background:none; border:none; font-size:22px; color:#888; cursor:pointer; font-weight:700; }
.sku-modal-body { flex:1; overflow-y:auto; padding:24px; }
.sku-sub { font-size:13px; color:#666; margin-bottom:20px; display:flex; align-items:center; gap:6px; }
.sku-tbl { width:100%; border-collapse:collapse; }
.sku-tbl thead tr { background:#f8fafd; }
.sku-tbl thead th { padding:10px 12px; font-size:11px; font-weight:700; color:#4a5568; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; text-align:left; }
.sku-tbl tbody tr { border-bottom:1px solid #f0f2f7; }
.sku-tbl td { padding:8px 12px; vertical-align:middle; }
.sku-sel { width:100%; border:1px solid #d0d4de; border-radius:6px; padding:7px 10px; font-size:13px; font-family:inherit; outline:none; background:#fff; }
.sku-sel:focus { border-color:#2d5be3; }
.sku-show-wrap { display:flex; align-items:center; gap:6px; }
.sku-show-wrap select { width:70px; border:1px solid #d0d4de; border-radius:6px; padding:7px 8px; font-size:13px; font-family:inherit; outline:none; }
.sku-show-wrap input[type=number] { width:56px; border:1px solid #d0d4de; border-radius:6px; padding:7px 8px; font-size:13px; text-align:center; outline:none; }
.sku-case-wrap { display:flex; align-items:center; gap:4px; }
.sku-case-sel { flex:1; border:1px solid #2d5be3; border-radius:6px; padding:7px 10px; font-size:13px; font-family:inherit; outline:none; color:#2d5be3; }
.sku-case-x { background:none; border:none; color:#e74c3c; font-size:16px; cursor:pointer; padding:0 4px; }
.sku-sep-wrap { display:flex; align-items:center; gap:4px; }
.sku-sep-sel { flex:1; border:1px solid #2d5be3; border-radius:6px; padding:7px 10px; font-size:13px; font-family:inherit; outline:none; color:#2d5be3; }
.sku-sep-x { background:none; border:none; color:#e74c3c; font-size:16px; cursor:pointer; padding:0 4px; }
.sku-row-del { background:none; border:none; cursor:pointer; color:#e74c3c; font-size:18px; padding:4px 6px; border-radius:50%; }
.sku-row-del:hover { background:#fde8e8; }
.sku-add-attr { display:inline-flex; align-items:center; gap:5px; color:#2d5be3; font-size:13px; font-weight:500; cursor:pointer; border:none; background:none; padding:6px 0; margin-top:8px; }
.sku-add-attr:hover { text-decoration:underline; }
.sku-preview-wrap { margin-top:20px; }
.sku-preview-label { font-size:12px; font-weight:700; color:#555; margin-bottom:8px; }
.sku-preview-box { background:#fffbea; border:2px dashed #f0c040; border-radius:6px; padding:20px; text-align:center; font-size:18px; font-weight:700; color:#333; letter-spacing:2px; min-height:60px; display:flex; align-items:center; justify-content:center; }
.sku-modal-foot { padding:14px 24px; border-top:1px solid #eee; display:flex; gap:10px; flex-shrink:0; }
.btn-sku-gen { background:#2d5be3; color:#fff; border:none; border-radius:6px; padding:9px 24px; font-weight:700; font-size:13px; cursor:pointer; }
.btn-sku-gen:hover { background:#1e4acf; }
.btn-sku-cancel { background:#fff; color:#555; border:1px solid #d0d4de; border-radius:6px; padding:9px 18px; font-size:13px; cursor:pointer; }
 
 </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-logo"><div class="sidebar-logo-icon">I</div><span>Inventory</span></div>
  <div class="sidebar-menu">
    <div class="sidebar-item"><span>🏠</span><span>Home</span></div>
    <div class="sidebar-item active"><span>📦</span><span>Items</span><span class="arrow">▼</span></div>
    <div class="sidebar-sub">
  <div class="sidebar-sub-active">Items +</div>
  <?php if(!empty($settings['enable_price_lists'])): ?>
    <div class="sidebar-sub-item"
         onclick="window.location='<?php echo e(route('price-lists.index')); ?>'">
      Price Lists
    </div>
  <?php endif; ?>
  <?php if(!empty($settings['enable_composite_items'])): ?>
    <div class="sidebar-sub-item"
         onclick="window.location='<?php echo e(route('composite-items.index')); ?>'">
      Composite Items
    </div>
  <?php endif; ?>
</div>
    <div class="sidebar-item"><span>🏪</span><span>Inventory</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>💼</span><span>Sales</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>🛒</span><span>Purchases</span><span class="arrow">▶</span></div>
    <div class="sidebar-item"><span>📊</span><span>Reports</span></div>
    <div class="sidebar-item"><span>📄</span><span>Documents</span></div>
    <div class="sidebar-apps-label">APPS</div>
    <div class="sidebar-item"><span>💳</span><span>Zoho Payments</span></div>
  </div>
  <div class="sidebar-collapse">◀ Collapse</div>
</div>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <div class="search-box">🔍 <span>Search in Items ( / )</span></div>
    <div class="topbar-right">
      <span style="color:#e67e00;font-size:12px;">Your premi...</span>
      <button class="btn-subscribe">Subscribe</button>
      <span style="font-weight:600;">Jayadeepa ▼</span>
      <div class="topbar-avatar" style="background:#2d5be3;">+</div>
      <div class="notif-wrap">🔔<span class="notif-badge">1</span></div>
      <span style="cursor:pointer;">⚙️</span>
      <div class="topbar-avatar" style="background:#e74c3c;">J</div>
    </div>
  </div>

  <div class="content">
    <?php if(session('success')): ?><div class="alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

    <div class="form-card">
      <div class="form-header">
        <h2>New Item</h2>
        <a href="<?php echo e(url('/products')); ?>" class="form-close">✕</a>
      </div>

      <?php
        $weightUnit = $settings['weight_unit'] ?? 'kg';
        $dimUnit = $settings['dimension_unit'] ?? 'cm';
        $decRate = (int)($settings['decimal_rate'] ?? 2);
        $wStep = $decRate > 0 ? '0.' . str_repeat('0', $decRate - 1) . '1' : '1';
      ?>

      <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data" id="main-form">
        <?php echo csrf_field(); ?>

        <div style="display:flex;gap:32px;margin-bottom:28px;">
          <div style="flex:1;">
            <!-- Name -->
            <div class="form-row">
              <label class="field-label required">Name*</label>
              <input type="text" name="name" class="name-input" value="<?php echo e(old('name')); ?>" required />
              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Type -->
            <div class="form-row">
              <label class="field-label">Type</label>
              <div class="radio-group">
                <label class="radio-option <?php echo e(old('type','goods')=='goods'?'checked':''); ?>">
                  <input type="radio" name="type" value="goods" <?php echo e(old('type','goods')=='goods'?'checked':''); ?> onchange="updateRadio(this,'type')" /> Goods
                </label>
                <label class="radio-option <?php echo e(old('type')=='service'?'checked':''); ?>">
                  <input type="radio" name="type" value="service" <?php echo e(old('type')=='service'?'checked':''); ?> onchange="updateRadio(this,'type')" /> Service
                </label>
              </div>
            </div>

            <!-- Category -->
            <div class="form-row">
              <label class="field-label">Category</label>
              <input type="hidden" name="category_id"   id="cat-hid-id"   value="<?php echo e(old('category_id')); ?>" />
              <input type="hidden" name="category_name" id="cat-hid-name" value="<?php echo e(old('category_name')); ?>" />
              <div class="cat-dd-wrap">
                <div class="cat-dd-box" id="cat-dd-box" onclick="toggleCatDd()">
                  <span class="cat-dd-label" id="cat-dd-lbl">Select a category</span>
                  <span class="cat-dd-chev" id="cat-dd-chev">▼</span>
                </div>
                <div class="cat-dd-panel" id="cat-dd-panel">
                  <div class="cat-dd-sr">
                    <span style="color:#bbb;font-size:13px;">🔍</span>
                    <input type="text" id="cat-dd-q" placeholder="Search" oninput="filterCatDd(this.value)" autocomplete="off" />
                  </div>
                  <div class="cat-dd-list" id="cat-dd-list"><div class="cat-dd-empty">Loading...</div></div>
                  <div class="cat-dd-manage" onclick="openCatModal();closeCatDd();">⚙️ Manage Categories</div>
                </div>
              </div>
            </div>

            <!-- Brand -->
            <div class="form-row">
              <label class="field-label">Brand</label>
              <div class="brand-row-wrap">
                <div class="select-wrap">
                  <select name="brand_id" id="brand-select">
                    <option value="">— Select Brand —</option>
                    <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id')==$brand->id?'selected':''); ?>><?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <span class="arrow">▼</span>
                </div>
                <button type="button" class="btn-gear" onclick="openBrandModal()" title="Manage Brands">⚙️</button>
              </div>
            </div>
          </div>

          <!-- Image Panel -->
          <div class="image-panel">
            <div class="upload-label">Product Image</div>
            <input type="file" id="front_image" name="front_image" accept="image/jpeg,image/png,image/jpg,image/gif" style="display:none;" onchange="handleFileSelect(this)">
            <div class="drop-zone" id="drop-zone"
                 ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)"
                 onclick="document.getElementById('front_image').click()">
              <div class="upload-icon">↑</div>
              <div class="drop-title">Drag & Drop Image</div>
              <div class="drop-sub">or <span class="browse-link">browse</span><br>JPG, PNG, GIF · max 5MB</div>
            </div>
            <div id="front-preview-wrap">
              <img id="front-preview-img" src="" alt="Preview" />
              <div class="preview-actions"><button type="button" onclick="clearFrontImage()">✕ Remove</button></div>
            </div>
            <div id="front-file-name"></div>
            <?php $__errorArgs = ['front_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>

        
        <?php if(isset($customFields) && $customFields->count() > 0): ?>
          <hr class="divider" />
          <h3 class="plain-title">Additional Information</h3>
          <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-row">
              <label class="field-label <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>">
                <?php echo e($field->name); ?><?php if($field->mandatory == 'yes'): ?> *<?php endif; ?>
              </label>
              <?php
                $config = $field->additional_config ?? [];
                $fieldName = 'additional_fields[' . $field->id . ']';
                $oldValue = old('additional_fields.' . $field->id);
              ?>
              <?php switch($field->data_type):
                case ('integer'): ?> <?php case ('decimal'): ?> <?php case ('float'): ?> <?php case ('currency'): ?> <?php case ('percentage'): ?>
                  <input type="number" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldValue ?? $config['default_value'] ?? ''); ?>" step="<?php echo e(in_array($field->data_type, ['decimal','float','currency','percentage']) ? '0.01' : '1'); ?>" placeholder="<?php echo e($config['help_text'] ?? ''); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?> style="flex:1;" />
                <?php break; ?>
                <?php case ('date'): ?>
                  <input type="date" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldValue ?? ''); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?> style="flex:1;" />
                <?php break; ?>
                <?php case ('datetime'): ?>
                  <input type="datetime-local" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldValue ?? ''); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?> style="flex:1;" />
                <?php break; ?>
                <?php case ('boolean'): ?>
                  <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="<?php echo e($fieldName); ?>" value="1" <?php echo e(($oldValue ?? $config['default_value'] ?? '') == '1' ? 'checked' : ''); ?> /> Yes</label>
                    <label class="radio-option"><input type="radio" name="<?php echo e($fieldName); ?>" value="0" <?php echo e(($oldValue ?? $config['default_value'] ?? '0') == '0' ? 'checked' : ''); ?> /> No</label>
                  </div>
                <?php break; ?>
                <?php case ('array'): ?>
                  <div class="select-wrap">
                    <select name="<?php echo e($fieldName); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?>>
                      <option value="">-- Select --</option>
                      <?php if(!empty($config['options'])): ?>
                        <?php $__currentLoopData = explode("\n", $config['options']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if(trim($opt)): ?>
                            <option value="<?php echo e(trim($opt)); ?>" <?php echo e(($oldValue ?? $config['default_value'] ?? '') == trim($opt) ? 'selected' : ''); ?>><?php echo e(trim($opt)); ?></option>
                          <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                    <span class="arrow">▼</span>
                  </div>
                <?php break; ?>
                <?php default: ?>
                  <input type="<?php echo e(in_array($field->data_type, ['email']) ? 'email' : (in_array($field->data_type, ['phone']) ? 'tel' : 'text')); ?>" name="<?php echo e($fieldName); ?>" value="<?php echo e($oldValue ?? $config['default_value'] ?? ''); ?>" placeholder="<?php echo e($config['help_text'] ?? ''); ?>" <?php echo e($field->mandatory == 'yes' ? 'required' : ''); ?> <?php if(!empty($config['char_limit'])): ?> maxlength="<?php echo e($config['char_limit']); ?>" <?php endif; ?> style="flex:1;" />
              <?php endswitch; ?>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <hr class="divider" />

        <!-- Item Details -->
        <h3 class="plain-title">Item Details</h3>
        <div class="form-row" style="margin-bottom:18px;">
          <label class="field-label">Item Type</label>
          <div class="item-type-group">
            <div class="item-type-btn <?php echo e(old('item_variant_type','single')=='single'?'active':''); ?>" onclick="selectItemType('single',this)">
              <div class="radio-dot <?php echo e(old('item_variant_type','single')=='single'?'active':''); ?>" id="dot-single"><div class="radio-dot-inner"></div></div>Single Item
            </div>
            <div class="item-type-btn <?php echo e(old('item_variant_type')=='contains_variants'?'active':''); ?>" onclick="selectItemType('variants',this)">
              <div class="radio-dot <?php echo e(old('item_variant_type')=='contains_variants'?'active':''); ?>" id="dot-variants"></div>Contains Variants
            </div>
          </div>
          <input type="hidden" name="item_variant_type" id="item_variant_type" value="<?php echo e(old('item_variant_type','single')); ?>">
        </div>

        <!-- UNIT FIELD -->
        <div class="two-col" style="margin-bottom:0;">
          <div class="form-row" style="margin-bottom:0;">
            <label class="field-label required" style="color:#c0392b;">Unit*</label>
            <input type="hidden" name="unit" id="unit-hidden" value="<?php echo e(old('unit')); ?>" />
            <div class="unit-dropdown-wrap">
              <div class="unit-input-box" id="unit-input-box">
                <input type="text" id="unit-search" placeholder="Select or type to add" autocomplete="off" oninput="filterUnits(this.value)" onfocus="openUnitDropdown()" />
                <span class="unit-chevron" id="unit-chevron">▼</span>
              </div>
              <div class="unit-dropdown-menu" id="unit-dropdown"></div>
            </div>
          </div>
          
          <div class="form-row single-only" id="sku-row" style="margin-bottom:0;">
            <label class="field-label" style="width:80px;">SKU</label>
            <input type="text" name="sku" style="flex:1;" value="<?php echo e(old('sku')); ?>" />
          </div>
        </div>

        
        <div class="add-identifier-wrap single-only" id="identifier-wrap">
          <span class="add-link" id="add-identifier-btn" onclick="toggleIdentifiers(this)">➕ Add Identifier</span>
        </div>
        <div class="identifier-fields single-only" id="identifier-fields">
          <div class="identifier-grid">
            <div class="identifier-row"><label>UPC</label><input type="text" name="upc" value="<?php echo e(old('upc')); ?>" /></div>
            <div class="identifier-row"><label>MPN</label><input type="text" name="mpn" value="<?php echo e(old('mpn')); ?>" /></div>
            <div class="identifier-row"><label>EAN</label><input type="text" name="ean" value="<?php echo e(old('ean')); ?>" /></div>
            <div class="identifier-row"><label>ISBN</label><input type="text" name="isbn" value="<?php echo e(old('isbn')); ?>" /></div>
          </div>
        </div>

        <!-- Item Description -->
        <div class="form-row" style="margin-top:16px;">
          <label class="field-label">Item Description</label>
          <textarea name="items_description" style="flex:1;" placeholder="Enter item description..."><?php echo e(old('items_description')); ?></textarea>
        </div>

        <!-- ══════════════════════════════════════
             VARIATIONS SECTION (Variants mode only)
        ══════════════════════════════════════ -->
        <div id="variations-section">
          <hr class="divider" />
          <h3 class="plain-title">Variations</h3>
          <div id="variation-rows-wrap">
            <!-- Rows added dynamically -->
          </div>
          <button type="button" class="add-variation-btn" onclick="addVariationRow()">
            ➕ Add another attribute
          </button>

          <!-- Generated Variants Table -->
          <div id="variants-table-wrap">
            <h3 class="plain-title" style="margin-top:20px;">Variants</h3>
            <table class="variants-tbl">
             <thead>
  <tr>
    <th style="width:26%;">ITEM NAME*</th>
    <th class="th-sku">
      SKU
      <span class="gen-sku-btn" onclick="generateAllSKU()">🔗 Generate SKU</span>
    </th>
    <th>
      COST PRICE (₹)*
      <span class="copy-all" onclick="copyToAll('cost')">COPY TO ALL</span>
    </th>
    <th>
      SELLING PRICE (₹)*
      <span class="copy-all" onclick="copyToAll('sell')">COPY TO ALL</span>
    </th>
    <th style="width:70px; color:#555; font-size:12px;">IMAGE</th>
    <th style="width:80px;"></th>
  </tr>
</thead>
              <tbody id="variants-tbody"></tbody>
            </table>
          </div>

          
          <input type="hidden" name="variants_json" id="variants-json" value="" />
        </div>
        <!-- end variations-section -->

        <hr class="divider" />

        <!-- Sales Information -->
        <div class="section-title">
          <input type="checkbox" id="chk-sales" name="has_sales" value="1" <?php echo e(old('has_sales',true)?'checked':''); ?> onchange="toggleSection('chk-sales','sales-body')" />
          <h3>Sales Information</h3>
        </div>
        <div class="section-body <?php echo e(old('has_sales',true)?'':'hidden'); ?>" id="sales-body">
          
          <div class="two-col single-only" id="selling-price-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required">Selling Price*</label>
              <div class="inr-wrap"><span class="inr-prefix">INR</span><input type="number" name="selling_price" step="0.01" value="<?php echo e(old('selling_price')); ?>" /></div>
            </div>
          </div>
          
          <!-- <div class="two-col variant-only" id="sales-account-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="color:#c0392b;">Account*</label>
              <div class="select-wrap">
                <select name="sales_account">
                  <option value="">Select an account</option>
                  <option value="Sales" selected>Sales</option>
                  <option value="Service Revenue">Service Revenue</option>
                  <option value="Other Income">Other Income</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div> -->
          <div class="form-row">
            <label class="field-label">Sales Description</label>
            <textarea name="sales_description" style="flex:1;" placeholder="Description shown on sales documents..."><?php echo e(old('sales_description')); ?></textarea>
          </div>
        </div>

        <hr class="divider" />

        <!-- Purchase Information -->
        <div class="section-title">
          <input type="checkbox" id="chk-purchase" name="has_purchase" value="1" <?php echo e(old('has_purchase',true)?'checked':''); ?> onchange="toggleSection('chk-purchase','purchase-body')" />
          <h3>Purchase Information</h3>
        </div>
        <div class="section-body <?php echo e(old('has_purchase',true)?'':'hidden'); ?>" id="purchase-body">
          
          <div class="two-col single-only" id="cost-price-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required">Cost Price*</label>
              <div class="inr-wrap"><span class="inr-prefix">INR</span><input type="number" name="cost_price" step="0.01" value="<?php echo e(old('cost_price')); ?>" /></div>
            </div>
          </div>
          
          <!-- <div class="two-col variant-only" id="purchase-account-row" style="margin-bottom:14px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="color:#c0392b;">Account*</label>
              <div class="select-wrap">
                <select name="purchase_account">
                  <option value="">Select an account</option>
                  <option value="Cost of Goods Sold" selected>Cost of Goods Sold</option>
                  <option value="Purchase Expense">Purchase Expense</option>
                  <option value="Other Expense">Other Expense</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div> -->
          <div class="two-col">
            <div class="form-row">
              <label class="field-label">Purchase Description</label>
              <textarea name="purchase_description" style="flex:1;" placeholder="Description shown on purchase documents..."><?php echo e(old('purchase_description')); ?></textarea>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label">Preferred Vendor</label>
              <div class="select-wrap">
                <select name="preferred_vendor_id" style="width:220px;">
                  <option value="">Select Vendor</option>
                  <optgroup label="Suppliers">
                    <option value="vendor_1">ABC Suppliers</option>
                    <option value="vendor_2">XYZ Traders</option>
                    <option value="vendor_3">Global Imports</option>
                    <option value="vendor_4">Local Wholesale</option>
                  </optgroup>
                  <optgroup label="Manufacturers">
                    <option value="vendor_5">Direct Manufacturer A</option>
                    <option value="vendor_6">Direct Manufacturer B</option>
                  </optgroup>
                  <optgroup label="Distributors">
                    <option value="vendor_7">National Distributor</option>
                    <option value="vendor_8">Regional Distributor</option>
                  </optgroup>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
        </div>

        <hr class="divider" />

        <!-- Track Inventory -->
        <div class="section-title">
          <input type="checkbox" id="chk-track" name="track_inventory" value="1" <?php echo e(old('track_inventory',true)?'checked':''); ?> onchange="toggleSection('chk-track','track-body')" />
          <h3>Track Inventory for this item</h3>
        </div>
        <p class="track-note">You cannot enable/disable inventory tracking once you've created transactions for this item</p>
        <div class="section-body <?php echo e(old('track_inventory',true)?'':'hidden'); ?>" id="track-body">
          <div class="form-row" style="margin-bottom:16px;">
            <label class="field-label" style="width:200px;text-align:left;">Bin Location Tracking</label>
            <div class="radio-group">
              <label class="radio-option"><input type="radio" name="bin_location_tracking" value="1" <?php echo e(old('bin_location_tracking')=='1'?'checked':''); ?> /> Yes</label>
              <label class="radio-option <?php echo e(old('bin_location_tracking')!='1'?'checked':''); ?>"><input type="radio" name="bin_location_tracking" value="0" <?php echo e(old('bin_location_tracking')!='1'?'checked':''); ?> /> No</label>
            </div>
          </div>
          <div class="two-col" style="margin-bottom:16px;">
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="width:180px;">Inventory Account*</label>
              <div class="select-wrap">
                <select name="inventory_account_id">
                  <option value="">Select an account</option>
                  <optgroup label="Assets">
                    <option value="inventory_asset">Inventory Asset</option>
                    <option value="raw_material">Raw Material</option>
                    <option value="work_in_progress">Work In Progress</option>
                    <option value="finished_goods">Finished Goods</option>
                  </optgroup>
                  <optgroup label="Cost of Goods Sold">
                    <option value="cogs">Cost of Goods Sold</option>
                    <option value="purchase_discount">Purchase Discount</option>
                    <option value="purchase_returns">Purchase Returns</option>
                  </optgroup>
                  <optgroup label="Income">
                    <option value="sales">Sales</option>
                    <option value="sales_discount">Sales Discount</option>
                    <option value="sales_returns">Sales Returns</option>
                  </optgroup>
                  <optgroup label="Expenses">
                    <option value="freight_expense">Freight Expense</option>
                    <option value="purchase_expense">Purchase Expense</option>
                  </optgroup>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label required" style="width:220px;">Inventory Valuation Method*</label>
              <div class="select-wrap">
                <select name="inventory_valuation_method">
                  <option value="">Select the valuation method</option>
                  <option value="FIFO" <?php echo e(old('inventory_valuation_method')=='FIFO'?'selected':''); ?>>FIFO</option>
                  <option value="Weighted Average" <?php echo e(old('inventory_valuation_method')=='Weighted Average'?'selected':''); ?>>Weighted Average</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
          <div class="form-row">
            <label class="field-label" style="width:160px;">Reorder Point</label>
            <input type="text" name="reorder_point" step="0.01" style="width:300px;" value="<?php echo e(old('reorder_point')); ?>" />
          </div>
        </div>

        <hr class="divider" />

        <!-- Cancellation -->
        <h3 class="plain-title">Cancellation and Returns</h3>
        <div class="form-row">
          <label class="field-label" style="width:160px;text-align:left;">Returnable Item</label>
          <div class="radio-group">
            <label class="radio-option <?php echo e(old('is_returnable','1')=='1'?'checked':''); ?>"><input type="radio" name="is_returnable" value="1" <?php echo e(old('is_returnable','1')=='1'?'checked':''); ?> /> Yes</label>
            <label class="radio-option <?php echo e(old('is_returnable')=='0'?'checked':''); ?>"><input type="radio" name="is_returnable" value="0" <?php echo e(old('is_returnable')=='0'?'checked':''); ?> /> No</label>
          </div>
        </div>

        <hr class="divider" />

        <!-- Fulfilment Details — Single Item only -->
        <div class="single-only" id="fulfilment-wrap">
          <h3 class="plain-title">Fulfilment Details</h3>
          <div class="two-col">
            <div>
              <div class="form-row" style="margin-bottom:4px;">
                <label class="field-label">Dimensions</label>
                <div style="display:flex;gap:6px;align-items:center;">
                  <input type="number" name="custom_field[length]" step="0.01" style="width:80px;" value="<?php echo e(old('custom_field.length')); ?>" placeholder="L" />
                  <span style="color:#aaa;">x</span>
                  <input type="number" name="custom_field[width]" step="0.01" style="width:80px;" value="<?php echo e(old('custom_field.width')); ?>" placeholder="W" />
                  <span style="color:#aaa;">x</span>
                  <input type="number" name="custom_field[height]" step="0.01" style="width:80px;" value="<?php echo e(old('custom_field.height')); ?>" placeholder="H" />
                  <div class="select-wrap" style="flex:none;width:70px;">
                    <select name="custom_field[dimension_unit]">
                      <option value="cm" <?php echo e(old('custom_field.dimension_unit', $dimUnit)=='cm'?'selected':''); ?>>cm</option>
                      <option value="in" <?php echo e(old('custom_field.dimension_unit', $dimUnit)=='in'?'selected':''); ?>>in</option>
                      <option value="m"  <?php echo e(old('custom_field.dimension_unit', $dimUnit)=='m' ?'selected':''); ?>>m</option>
                      <option value="ft" <?php echo e(old('custom_field.dimension_unit', $dimUnit)=='ft'?'selected':''); ?>>ft</option>
                      <option value="mm" <?php echo e(old('custom_field.dimension_unit', $dimUnit)=='mm'?'selected':''); ?>>mm</option>
                    </select>
                    <span class="arrow">▼</span>
                  </div>
                </div>
              </div>
              <p class="dim-hint" style="margin-left:156px;">(Length × Width × Height)</p>
            </div>
            <div class="form-row" style="margin-bottom:0;">
              <label class="field-label" style="width:80px;">Weight</label>
              <input type="number" name="custom_field[weight]" step="<?php echo e($wStep); ?>" style="width:180px;" value="<?php echo e(old('custom_field.weight')); ?>" />
              <div class="select-wrap" style="flex:none;width:70px;margin-left:6px;">
                <select name="custom_field[weight_unit]">
                  <option value="kg" <?php echo e(old('custom_field.weight_unit', $weightUnit)=='kg'?'selected':''); ?>>kg</option>
                  <option value="g"  <?php echo e(old('custom_field.weight_unit', $weightUnit)=='g' ?'selected':''); ?>>g</option>
                  <option value="lb" <?php echo e(old('custom_field.weight_unit', $weightUnit)=='lb'?'selected':''); ?>>lb</option>
                  <option value="oz" <?php echo e(old('custom_field.weight_unit', $weightUnit)=='oz'?'selected':''); ?>>oz</option>
                  <option value="mg" <?php echo e(old('custom_field.weight_unit', $weightUnit)=='mg'?'selected':''); ?>>mg</option>
                </select>
                <span class="arrow">▼</span>
              </div>
            </div>
          </div>
          <hr class="divider" />
        </div>

        <div class="btn-group">
          <button type="button" class="btn-save" onclick="handleFormSubmit()">Save</button>
          <a href="<?php echo e(url('/products')); ?>" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MANAGE CATEGORIES MODAL -->
<div class="cat-modal-overlay" id="cat-modal">
  <div class="cat-modal-box">
    <div class="cat-modal-head">
      <span>Manage Categories</span>
      <button class="cat-modal-x" onclick="closeCatModal()">✕</button>
    </div>
    <div class="cat-modal-body">
      <div class="cat-new-form" id="cat-new-form">
        <div class="cat-form-row">
          <label class="req">Category Name*</label>
          <input type="text" id="cat-name-inp" onkeydown="if(event.key==='Enter'){event.preventDefault();saveCat();}" />
        </div>
        <div class="cat-form-row">
          <label>Parent Category</label>
          <div class="cat-psw">
            <select id="cat-par-sel"><option value="">— None —</option></select>
            <span class="arr">▼</span>
          </div>
        </div>
        <div class="cat-fe" id="cat-fe"></div>
        <div class="cat-form-btns">
          <button class="btn-cs" id="btn-cs" onclick="saveCat()">Save</button>
          <button class="btn-cc" onclick="hideCatForm()">Cancel</button>
        </div>
      </div>
      <div class="cat-list-head">
        <span class="cat-list-title">CATEGORIES</span>
        <button class="btn-anc" id="btn-anc" onclick="showCatForm()"><span class="anc-circle">+</span> Add New Category</button>
      </div>
      <div class="cat-ms" id="cat-ms"></div>
      <div id="cat-li-wrap"><div class="cat-li-empty">Loading...</div></div>
    </div>
    <div class="cat-modal-foot">
      <button class="btn-cf" onclick="closeCatModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- BRAND MODAL -->
<div class="modal-overlay" id="brand-modal">
  <div class="modal-box">
    <div class="modal-header">
      <span>⚙️ Manage Brands</span>
      <button class="modal-close" onclick="closeBrandModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="add-brand-row" id="add-brand-form">
        <input type="text" id="new-brand-input" placeholder="Enter brand name..." onkeydown="if(event.key==='Enter'){event.preventDefault();addBrand();}" />
        <button class="btn-add-brand" id="btn-add-brand" onclick="addBrand()">+ Add New</button>
      </div>
      <div class="add-brand-row" id="edit-brand-form" style="display:none;">
        <input type="text" id="edit-brand-input" placeholder="Edit brand name..." />
        <input type="hidden" id="edit-brand-id" value="" />
        <button class="btn-add-brand" id="btn-update-brand" onclick="updateBrand()" style="background:#28a745;">✓ Update</button>
        <button class="btn-add-brand" id="btn-cancel-edit" onclick="cancelEdit()" style="background:#6c757d;">✕ Cancel</button>
      </div>
      <div class="brand-error" id="brand-error"></div>
      <div class="brand-success" id="brand-success"></div>
      <div class="brand-list" id="brand-list"><div class="brand-empty">Loading...</div></div>
    </div>
  </div>
</div>

<!-- ADDITIONAL INFO MODAL (per variant) -->
<div class="addl-modal-overlay" id="addl-modal">
  <div class="addl-modal-box">
    <div class="addl-modal-head">
      <span>Additional Information</span>
      <button class="addl-modal-x" onclick="closeAddlModal()">✕</button>
    </div>
    <div class="addl-modal-body">
      <div class="addl-section-title">Identifiers</div>
      <div class="addl-grid">
        <div class="addl-field-row"><label>UPC</label><input type="text" id="addl-upc" /></div>
        <div class="addl-field-row"><label>MPN</label><input type="text" id="addl-mpn" /></div>
        <div class="addl-field-row"><label>EAN</label><input type="text" id="addl-ean" /></div>
        <div class="addl-field-row"><label>ISBN</label><input type="text" id="addl-isbn" /></div>
      </div>
      <?php if(isset($customFields) && $customFields->count() > 0): ?>
        <hr class="addl-divider" />
        <div class="addl-section-title">Custom Fields</div>
        <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="addl-custom-row">
            <label><?php echo e($field->name); ?></label>
            <input type="text" id="addl-cf-<?php echo e($field->id); ?>" data-field-id="<?php echo e($field->id); ?>" />
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
    <div class="addl-modal-foot">
      <button class="btn-addl-save" onclick="saveAddlModal()">Save</button>
      <button class="btn-addl-cancel" onclick="closeAddlModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- GENERATE SKU MODAL -->
<div class="sku-modal-overlay" id="sku-modal">
  <div class="sku-modal-box">
    <div class="sku-modal-head">
      <span>Generate SKU - <span id="sku-modal-title"></span></span>
      <button class="sku-modal-x" onclick="closeSkuModal()">✕</button>
    </div>
    <div class="sku-modal-body">
      <div class="sku-sub">
        Select attributes that you would like to generate the SKU from <span style="color:#aaa;font-size:14px;">ⓘ</span>
      </div>
      <table class="sku-tbl">
        <thead>
          <tr>
            <th style="width:28%;">SELECT ATTRIBUTE</th>
            <th style="width:22%;">SHOW</th>
            <th style="width:24%;">LETTER CASE</th>
            <th style="width:18%;">SEPARATOR</th>
            <th style="width:8%;"></th>
          </tr>
        </thead>
        <tbody id="sku-rows-tbody"></tbody>
      </table>
      <button type="button" class="sku-add-attr" onclick="skuAddRow()">+ Add Attribute</button>
      <div class="sku-preview-wrap">
        <div class="sku-preview-label">SKU Preview</div>
        <div class="sku-preview-box" id="sku-preview-box">—</div>
      </div>
    </div>
    <div class="sku-modal-foot">
      <button class="btn-sku-gen" onclick="applyGeneratedSKU()">Generate SKU</button>
      <button class="btn-sku-cancel" onclick="closeSkuModal()">Cancel</button>
    </div>
  </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ══════════════════════════════════════════════
// CATEGORY
// ══════════════════════════════════════════════
let _cats=[], _catOpen=false, _selCatId=null, _selCatNm='', _editCatId=null;
(async function initCats(){
  await _fetchCats();
  const oid='<?php echo e(old("category_id")); ?>', onm='<?php echo e(old("category_name")); ?>';
  if(oid&&onm){_selCatId=parseInt(oid);_selCatNm=onm;const l=document.getElementById('cat-dd-lbl');l.textContent=onm;l.classList.add('has-val');}
})();
async function _fetchCats(){try{const r=await fetch('/categories/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await r.json();if(j.success){_cats=j.data;_renderDdList('');}}catch{}}
function toggleCatDd(){_catOpen?closeCatDd():openCatDd();}
function openCatDd(){_catOpen=true;document.getElementById('cat-dd-panel').classList.add('open');document.getElementById('cat-dd-box').classList.add('open');document.getElementById('cat-dd-chev').textContent='▲';document.getElementById('cat-dd-q').value='';_renderDdList('');setTimeout(()=>document.getElementById('cat-dd-q').focus(),20);}
function closeCatDd(){_catOpen=false;document.getElementById('cat-dd-panel').classList.remove('open');document.getElementById('cat-dd-box').classList.remove('open');document.getElementById('cat-dd-chev').textContent='▼';}
function filterCatDd(q){_renderDdList(q);}
function _renderDdList(q){const el=document.getElementById('cat-dd-list');q=(q||'').trim().toLowerCase();const list=q?_cats.filter(c=>c.name.toLowerCase().includes(q)||(c.parent_name||'').toLowerCase().includes(q)):_cats;if(!list.length){el.innerHTML='<div class="cat-dd-empty">No categories found</div>';return;}el.innerHTML=list.map(c=>`<div class="cat-dd-item${_selCatId===c.id?' sel':''}" onclick="selCat(${c.id},'${c.name.replace(/'/g,"\\'")}')"><span>📁</span><span>${c.name}</span>${c.parent_name?`<span class="cat-parent-hint">(${c.parent_name})</span>`:''}</div>`).join('');}
function selCat(id,name){_selCatId=id;_selCatNm=name;document.getElementById('cat-hid-id').value=id;document.getElementById('cat-hid-name').value=name;const l=document.getElementById('cat-dd-lbl');l.textContent=name;l.classList.add('has-val');closeCatDd();}
document.addEventListener('click',function(e){if(!document.querySelector('.cat-dd-wrap')?.contains(e.target))closeCatDd();});
function openCatModal(){document.getElementById('cat-modal').classList.add('open');hideCatForm();_renderModalList();}
function closeCatModal(){document.getElementById('cat-modal').classList.remove('open');hideCatForm();}
document.getElementById('cat-modal').addEventListener('click',e=>{if(e.target.id==='cat-modal')closeCatModal();});
function showCatForm(eId,eNm,ePar){_editCatId=eId||null;document.getElementById('cat-new-form').classList.add('show');document.getElementById('cat-name-inp').value=eNm||'';document.getElementById('cat-fe').style.display='none';document.getElementById('btn-cs').textContent=eId?'Update':'Save';document.getElementById('btn-anc').style.display='none';const sel=document.getElementById('cat-par-sel');sel.innerHTML='<option value="">— None —</option>';_cats.forEach(c=>{if(c.id!==eId){const o=document.createElement('option');o.value=c.id;o.textContent=c.full_name||c.name;if(c.id===ePar)o.selected=true;sel.appendChild(o);}});setTimeout(()=>document.getElementById('cat-name-inp').focus(),30);}
function hideCatForm(){document.getElementById('cat-new-form').classList.remove('show');document.getElementById('btn-anc').style.display='flex';document.getElementById('cat-name-inp').value='';document.getElementById('cat-fe').style.display='none';_editCatId=null;}
async function saveCat(){const name=document.getElementById('cat-name-inp').value.trim();const par=document.getElementById('cat-par-sel').value||null;const fe=document.getElementById('cat-fe');const btn=document.getElementById('btn-cs');fe.style.display='none';if(!name){fe.textContent='Category name cannot be empty.';fe.style.display='block';return;}btn.disabled=true;btn.textContent=_editCatId?'Updating...':'Saving...';try{const res=await fetch(_editCatId?`/categories/${_editCatId}`:'/categories',{method:_editCatId?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name,parent_id:par})});const j=await res.json();if(!res.ok){fe.textContent=j.message||'Failed.';fe.style.display='block';}else{hideCatForm();_showCatMsg(_editCatId?'Category updated!':'Category added!');await _fetchCats();_renderModalList();}}catch{fe.textContent='Network error.';fe.style.display='block';}finally{btn.disabled=false;btn.textContent=_editCatId?'Update':'Save';}}
function _renderModalList(){const el=document.getElementById('cat-li-wrap');if(!_cats.length){el.innerHTML='<div class="cat-li-empty">No categories added yet</div>';return;}el.innerHTML=_cats.map(c=>`<div class="cat-li" id="cli-${c.id}"><span class="li-icon">📁</span><span class="li-name">${c.name}${c.parent_name?`<span class="li-parent">(${c.parent_name})</span>`:''}</span><div class="li-acts"><button class="li-edit" onclick="showCatForm(${c.id},'${c.name.replace(/'/g,"\\'")}',${c.parent_id||'null'})">✏️ Edit</button><button class="li-del" onclick="delCat(${c.id},'${c.name.replace(/'/g,"\\'")}')">🗑️ Delete</button></div></div>`).join('');}
async function delCat(id,name){if(!confirm(`Delete category "${name}"?`))return;try{const res=await fetch(`/categories/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const j=await res.json();if(res.ok&&j.success){if(_selCatId===id){_selCatId=null;_selCatNm='';document.getElementById('cat-hid-id').value='';document.getElementById('cat-hid-name').value='';const l=document.getElementById('cat-dd-lbl');l.textContent='Select a category';l.classList.remove('has-val');}_showCatMsg('Category deleted!');await _fetchCats();_renderModalList();}else{alert(j.message||'Failed to delete');}}catch{alert('Network error');}}
function _showCatMsg(m){const el=document.getElementById('cat-ms');el.textContent=m;el.style.display='block';setTimeout(()=>el.style.display='none',3000);}

// ══════════════════════════════════════════════
// SIDEBAR
// ══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
  const sidebarItems = document.querySelectorAll('.sidebar-item');
  sidebarItems.forEach(item => {
    item.addEventListener('click', function(e) {
      const hasArrow = this.querySelector('.arrow');
      if (hasArrow) {
        e.preventDefault(); e.stopPropagation();
        const arrow = this.querySelector('.arrow');
        const isExpanded = arrow.textContent === '▼';
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('sidebar-sub')) {
          if (isExpanded) { arrow.textContent = '▶'; submenu.style.display = 'none'; }
          else { arrow.textContent = '▼'; submenu.style.display = 'block'; }
        }
      } else {
        sidebarItems.forEach(si => si.classList.remove('active'));
        this.classList.add('active');
      }
    });
  });
  document.querySelectorAll('.sidebar-sub-active, .sidebar-sub-item').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault(); e.stopPropagation();
      document.querySelectorAll('.sidebar-sub-active, .sidebar-sub-item').forEach(si => { si.style.background=''; si.style.color=''; });
      this.style.background = '#2d5be3'; this.style.color = '#fff';
      const parentItem = this.closest('.sidebar-sub')?.previousElementSibling;
      if (parentItem && parentItem.classList.contains('sidebar-item')) { sidebarItems.forEach(si => si.classList.remove('active')); parentItem.classList.add('active'); }
    });
  });
  const collapseBtn = document.querySelector('.sidebar-collapse');
  const sidebar = document.querySelector('.sidebar');
  collapseBtn?.addEventListener('click', function() {
    const isCollapsed = sidebar.style.width === '60px';
    if (!isCollapsed) {
      sidebar.style.width = '60px'; this.innerHTML = '▶ Expand';
      document.querySelectorAll('.sidebar-item span:not(:first-child), .sidebar-logo span, .sidebar-apps-label, .sidebar-sub').forEach(el => el.style.display = 'none');
      document.querySelector('.sidebar-logo').style.justifyContent = 'center';
    } else {
      sidebar.style.width = '220px'; this.innerHTML = '◀ Collapse';
      document.querySelectorAll('.sidebar-item span:not(:first-child), .sidebar-logo span, .sidebar-apps-label, .sidebar-sub').forEach(el => el.style.display = '');
      document.querySelector('.sidebar-logo').style.justifyContent = '';
    }
  });
  document.querySelectorAll('.sidebar-sub').forEach(sub => sub.style.display = 'block');
  document.querySelectorAll('.sidebar-item .arrow').forEach(arrow => arrow.textContent = '▼');
});

// ══════════════════════════════════════════════
// GENERAL HELPERS
// ══════════════════════════════════════════════
function toggleSection(id, bodyId) {
  document.getElementById(bodyId).classList.toggle('hidden', !document.getElementById(id).checked);
}
function updateRadio(input, name) {
  document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.closest('.radio-option').classList.remove('checked'));
  input.closest('.radio-option').classList.add('checked');
}

// ══════════════════════════════════════════════
// SELECT ITEM TYPE — KEY LOGIC
// ══════════════════════════════════════════════
function selectItemType(type, el) {
  document.querySelectorAll('.item-type-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.radio-dot').forEach(d => { d.classList.remove('active'); d.innerHTML = ''; });
  el.classList.add('active');
  const dot = document.getElementById('dot-' + type);
  dot.classList.add('active');
  dot.innerHTML = '<div class="radio-dot-inner"></div>';
  document.getElementById('item_variant_type').value = type === 'single' ? 'single' : 'contains_variants';

  const isVariant = type === 'variants';

  // Single-only elements hide/show
  document.querySelectorAll('.single-only').forEach(el => {
    if (isVariant) el.classList.add('hide');
    else el.classList.remove('hide');
  });

  // Variant-only elements hide/show
  document.querySelectorAll('.variant-only').forEach(el => {
    if (isVariant) el.classList.add('show');
    else el.classList.remove('show');
  });

  // Variations section
  const varSec = document.getElementById('variations-section');
  if (isVariant) {
    varSec.classList.add('show');
    if (document.querySelectorAll('.variation-row').length === 0) {
      addVariationRow(); // auto add first row
    }
  } else {
    varSec.classList.remove('show');
  }
}

// ══════════════════════════════════════════════
// DRAG & DROP IMAGE
// ══════════════════════════════════════════════
function onDragOver(e) { e.preventDefault(); document.getElementById('drop-zone').classList.add('dragover'); }
function onDragLeave(e) { document.getElementById('drop-zone').classList.remove('dragover'); }
function onDrop(e) { e.preventDefault(); document.getElementById('drop-zone').classList.remove('dragover'); if (e.dataTransfer.files[0]) applyFile(e.dataTransfer.files[0]); }
function handleFileSelect(input) { if (input.files[0]) applyFile(input.files[0]); }
const MAX_SIZE_BYTES=5*1024*1024, MAX_WIDTH=1920, MAX_HEIGHT=1920, JPEG_QUALITY=0.92, TARGET_MAX_KB=800;
function applyFile(file) {
  if (!file.type.match(/image\/(jpeg|png|jpg|gif)/)) { alert('Please upload a JPG, PNG or GIF image.'); return; }
  if (file.size > MAX_SIZE_BYTES) { alert('Image must be under 5 MB.'); return; }
  document.getElementById('front-file-name').textContent = 'Compressing…';
  const reader = new FileReader();
  reader.onload = ev => {
    const img = new Image();
    img.onload = () => {
      let {width, height} = img;
      if (width > MAX_WIDTH || height > MAX_HEIGHT) { const r = Math.min(MAX_WIDTH/width, MAX_HEIGHT/height); width = Math.round(width*r); height = Math.round(height*r); }
      const canvas = document.createElement('canvas');
      canvas.width = width; canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#ffffff'; ctx.fillRect(0,0,width,height); ctx.drawImage(img,0,0,width,height);
      let quality = JPEG_QUALITY, dataURL = canvas.toDataURL('image/jpeg', quality);
      let sizeKB = Math.round((dataURL.length*3)/4/1024);
      while (sizeKB > TARGET_MAX_KB && quality > 0.5) { quality -= 0.05; dataURL = canvas.toDataURL('image/jpeg', quality); sizeKB = Math.round((dataURL.length*3)/4/1024); }
      const byteStr = atob(dataURL.split(',')[1]), ab = new ArrayBuffer(byteStr.length), ia = new Uint8Array(ab);
      for (let i=0; i<byteStr.length; i++) ia[i] = byteStr.charCodeAt(i);
      const blob = new Blob([ab], {type:'image/jpeg'});
      const origName = file.name.replace(/\.[^.]+$/,'')+'.jpg';
      const dt = new DataTransfer(); dt.items.add(new File([blob], origName, {type:'image/jpeg'}));
      document.getElementById('front_image').files = dt.files;
      document.getElementById('front-preview-img').src = dataURL;
      document.getElementById('front-preview-wrap').style.display = 'block';
      document.getElementById('drop-zone').style.display = 'none';
      document.getElementById('front-file-name').textContent = origName+' — '+sizeKB+' KB (original: '+Math.round(file.size/1024)+' KB, quality: '+Math.round(quality*100)+'%)';
    };
    img.src = ev.target.result;
  };
  reader.readAsDataURL(file);
}
function clearFrontImage() {
  document.getElementById('front_image').value='';
  document.getElementById('front-preview-img').src='';
  document.getElementById('front-preview-wrap').style.display='none';
  document.getElementById('drop-zone').style.display='flex';
  document.getElementById('front-file-name').textContent='';
}

// ══════════════════════════════════════════════
// UNIT DROPDOWN
// ══════════════════════════════════════════════
const DEFAULT_UNITS = [
  {code:'BOX',name:'box'},{code:'CMS',name:'cm'},{code:'DOZ',name:'dz'},{code:'FTS',name:'ft'},
  {code:'GMS',name:'g'},{code:'INC',name:'in'},{code:'KGS',name:'kg'},{code:'KME',name:'km'},
  {code:'LBS',name:'lb'},{code:'MGS',name:'mg'},{code:'PCS',name:'pcs'},{code:'LTR',name:'l'},
  {code:'MLT',name:'ml'},{code:'MTR',name:'m'},{code:'NOS',name:'nos'},{code:'OZS',name:'oz'},
  {code:'TNE',name:'ton'},{code:'YDS',name:'yd'},
];
let unitOpen=false, selectedUnit=null;
function getCustomUnits(){try{return JSON.parse(localStorage.getItem('custom_units')||'[]');}catch{return[];}}
function saveCustomUnits(units){localStorage.setItem('custom_units',JSON.stringify(units));}
function allUnits(){return [...DEFAULT_UNITS,...getCustomUnits()];}
function renderUnitDropdown(filter){
  filter=(filter||'').trim().toLowerCase();
  const menu=document.getElementById('unit-dropdown');
  const customCodes=getCustomUnits().map(u=>u.code);
  const matched=allUnits().filter(u=>!filter||u.code.toLowerCase().includes(filter)||u.name.toLowerCase().includes(filter));
  let html='';
  if(matched.length===0){html='<div class="unit-no-result">No match — type to add new unit</div>';}
  else{html=matched.map(u=>{const isSel=selectedUnit&&selectedUnit.code===u.code;const isCustom=customCodes.includes(u.code);const delBtn=isCustom?`<button class="unit-del" type="button" onclick="deleteCustomUnit(event,'${u.code}')" title="Delete">✕</button>`:'';return`<div class="unit-option${isSel?' selected':''}" onclick="selectUnit('${u.code}','${u.name}')"><span>${u.code} - ${u.name}</span>${delBtn}</div>`;}).join('');}
  const exists=allUnits().some(u=>u.code.toLowerCase()===filter||u.name.toLowerCase()===filter);
  if(filter&&!exists){html+=`<div class="unit-add-new" onclick="addCustomUnit('${filter.replace(/'/g,"\\'")}')">➕ Add "${filter}"</div>`;}
  menu.innerHTML=html;
}
function openUnitDropdown(){if(unitOpen)return;unitOpen=true;document.getElementById('unit-dropdown').classList.add('open');document.getElementById('unit-chevron').textContent='▲';document.getElementById('unit-input-box').classList.add('focused');renderUnitDropdown(document.getElementById('unit-search').value);}
function closeUnitDropdown(){if(!unitOpen)return;unitOpen=false;document.getElementById('unit-dropdown').classList.remove('open');document.getElementById('unit-chevron').textContent='▼';document.getElementById('unit-input-box').classList.remove('focused');const searchEl=document.getElementById('unit-search');searchEl.value=selectedUnit?`${selectedUnit.code} - ${selectedUnit.name}`:'';}
function filterUnits(val){if(!unitOpen)openUnitDropdown();renderUnitDropdown(val);}
function selectUnit(code,name){selectedUnit={code,name};document.getElementById('unit-hidden').value=name;document.getElementById('unit-search').value=`${code} - ${name}`;closeUnitDropdown();}
function addCustomUnit(input){const code=input.toUpperCase().replace(/\s+/g,'').slice(0,8);const name=input.toLowerCase().trim();const custom=getCustomUnits();if(!custom.find(u=>u.code===code)){custom.push({code,name});saveCustomUnits(custom);}selectUnit(code,name);}
function deleteCustomUnit(e,code){e.stopPropagation();if(!confirm(`"${code}" if you want delete the unit`))return;const updated=getCustomUnits().filter(u=>u.code!==code);saveCustomUnits(updated);if(selectedUnit&&selectedUnit.code===code){selectedUnit=null;document.getElementById('unit-hidden').value='';document.getElementById('unit-search').value='';}renderUnitDropdown(document.getElementById('unit-search').value);}
document.addEventListener('click',function(e){const wrap=document.querySelector('.unit-dropdown-wrap');if(wrap&&!wrap.contains(e.target))closeUnitDropdown();});
document.getElementById('unit-input-box').addEventListener('click',function(e){if(e.target.id==='unit-search')return;unitOpen?closeUnitDropdown():openUnitDropdown();});
(function initUnit(){const oldVal='<?php echo e(old("unit")); ?>';if(oldVal){const found=allUnits().find(u=>u.name===oldVal||u.code===oldVal.toUpperCase());if(found){selectedUnit=found;document.getElementById('unit-hidden').value=found.name;document.getElementById('unit-search').value=`${found.code} - ${found.name}`;}}})();

// ══════════════════════════════════════════════
// IDENTIFIER TOGGLE
// ══════════════════════════════════════════════
function toggleIdentifiers(btn) {
  const fields = document.getElementById('identifier-fields');
  const isOpen = fields.classList.contains('show');
  if (isOpen) { fields.classList.remove('show'); btn.innerHTML = '➕ Add Identifier'; }
  else { fields.classList.add('show'); btn.innerHTML = '➖ Hide Identifier'; fields.querySelector('input').focus(); }
}

// ══════════════════════════════════════════════
// VARIATIONS — ATTRIBUTE ROWS
// ══════════════════════════════════════════════
const ATTR_OPTIONS = [
  'eg: color','Size','Color','Material','Style','Flavor','Capacity',
  '330ml','180','Voltage','Weight','Pattern','Finish','Pack Size'
];

let varRowCount = 0;

function addVariationRow(attrVal='', optionTags=[]) {
  varRowCount++;
  const rowId = 'var-row-' + varRowCount;
  const wrap = document.getElementById('variation-rows-wrap');
  const div = document.createElement('div');
  div.className = 'variation-row';
  div.id = rowId;

  const tagsHtml = optionTags.map(t =>
    `<span class="tag-chip">${escHtml(t)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button></span>`
  ).join('');

  div.innerHTML = `
    <div class="var-attr-wrap">
      <input type="text" 
             class="var-attr-input" 
             placeholder="eg: Color, Size, Material..." 
             value="${escHtml(attrVal)}"
             oninput="regenerateVariants()"
             style="width:100%; border:1px solid #d0d4de; border-radius:6px; padding:8px 12px; font-size:14px; font-family:inherit; outline:none;" />
    </div>
    <div class="var-options-wrap">
      <div class="tag-input-box" id="tags-${rowId}" onclick="focusTagInput('${rowId}')">
        ${tagsHtml}
        <input class="tag-real-input" id="input-${rowId}" type="text"
               placeholder="Type and press Enter or comma"
               onkeydown="handleTagKey(event,'${rowId}')"
               oninput="handleTagInput(event,'${rowId}')" />
      </div>
    </div>
    <button type="button" class="var-del-btn" onclick="removeVariationRow('${rowId}')" title="Remove">🗑</button>
  `;
  wrap.appendChild(div);
}

function focusTagInput(rowId) {
  document.getElementById('input-' + rowId)?.focus();
}

function handleTagKey(e, rowId) {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    const input = e.target;
    const val = input.value.trim().replace(/,$/, '');
    if (val) addTag(rowId, val);
    input.value = '';
  } else if (e.key === 'Backspace' && e.target.value === '') {
    // Remove last tag
    const box = document.getElementById('tags-' + rowId);
    const chips = box.querySelectorAll('.tag-chip');
    if (chips.length) chips[chips.length - 1].remove();
    regenerateVariants();
  }
}

function handleTagInput(e, rowId) {
  const val = e.target.value;
  if (val.endsWith(',')) {
    const clean = val.slice(0, -1).trim();
    if (clean) addTag(rowId, clean);
    e.target.value = '';
  }
}

function addTag(rowId, val) {
  const box = document.getElementById('tags-' + rowId);
  const input = document.getElementById('input-' + rowId);
  // Avoid duplicates
  const existing = [...box.querySelectorAll('.tag-chip')].map(c => c.textContent.replace('×','').trim());
  if (existing.includes(val)) return;
  const chip = document.createElement('span');
  chip.className = 'tag-chip';
  chip.innerHTML = `${escHtml(val)}<button type="button" onclick="removeTag(this,'${rowId}')">×</button>`;
  box.insertBefore(chip, input);
  regenerateVariants();
}

function removeTag(btn, rowId) {
  btn.closest('.tag-chip').remove();
  regenerateVariants();
}

function removeVariationRow(rowId) {
  document.getElementById(rowId)?.remove();
  regenerateVariants();
}

function getVariationData() {
  const rows = document.querySelectorAll('.variation-row');
  const data = [];
  rows.forEach(row => {
    const attr = row.querySelector('.var-attr-input')?.value.trim() || '';
    const chips = [...row.querySelectorAll('.tag-chip')].map(c => c.textContent.replace('×','').trim());
    if (attr && chips.length) data.push({ attribute: attr, options: chips });
  });
  return data;
}

// ══════════════════════════════════════════════
// VARIANTS TABLE — AUTO GENERATE (Cartesian product)
// ══════════════════════════════════════════════

// Store additional info per variant: variantAdditionalData[variantName] = {upc,mpn,...}
let variantAdditionalData = {};
const variantImageData = {}; 
function handleVariantImageChange(input, variantName) {
  const file = input.files[0];
  if (!file) return;
  if (!file.type.match(/image\/(jpeg|png|jpg|gif)/)) { alert('JPG / PNG / GIF மட்டும் upload பண்ணலாம்.'); return; }
  if (file.size > 5 * 1024 * 1024) { alert('Image 5 MB-க்கு மேல இருக்க கூடாது.'); return; }

  const reader = new FileReader();
  reader.onload = ev => {
    const img = new Image();
    img.onload = () => {
      let {width, height} = img;
      const MAX = 1200;
      if (width > MAX || height > MAX) {
        const r = Math.min(MAX/width, MAX/height);
        width = Math.round(width * r);
        height = Math.round(height * r);
      }
      const canvas = document.createElement('canvas');
      canvas.width = width; canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#fff'; ctx.fillRect(0,0,width,height);
      ctx.drawImage(img, 0, 0, width, height);

      let quality = 0.88;
      let dataURL = canvas.toDataURL('image/jpeg', quality);
      let sizeKB = Math.round((dataURL.length * 3) / 4 / 1024);
      while (sizeKB > 600 && quality > 0.5) {
        quality -= 0.05;
        dataURL = canvas.toDataURL('image/jpeg', quality);
        sizeKB = Math.round((dataURL.length * 3) / 4 / 1024);
      }

      const safeFilename = variantName.replace(/[^a-zA-Z0-9_\-]/g, '_').toLowerCase() + '_' + Date.now() + '.jpg';
      variantImageData[variantName] = { base64: dataURL, filename: safeFilename };

      // Thumbnail update
      const triggerEl = document.querySelector(`[data-variant-img="${CSS.escape(variantName)}"]`);
      if (triggerEl) {
        triggerEl.classList.add('has-img');
        const previewImg = triggerEl.querySelector('img.vit-preview');
        if (previewImg) previewImg.src = dataURL;
      }
    };
    img.src = ev.target.result;
  };
  reader.readAsDataURL(file);
}

function removeVariantImage(variantName) {
  delete variantImageData[variantName];
  const triggerEl = document.querySelector(`[data-variant-img="${CSS.escape(variantName)}"]`);
  if (triggerEl) {
    triggerEl.classList.remove('has-img');
    const previewImg = triggerEl.querySelector('img.vit-preview');
    if (previewImg) previewImg.src = '';
  }
  const safeId = 'vif_' + variantName.replace(/[^a-zA-Z0-9]/g, '_');
  const fileInput = document.getElementById(safeId);
  if (fileInput) fileInput.value = '';
}
function cartesian(arrays) {
  if (!arrays.length) return [[]];
  return arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...x, y])), [[]]);
}

function regenerateVariants() {
  const data = getVariationData();
  const tbody = document.getElementById('variants-tbody');
  const tableWrap = document.getElementById('variants-table-wrap');

  if (!data.length) {
    tbody.innerHTML = '';
    tableWrap.classList.remove('show');
    return;
  }

  const combos = cartesian(data.map(d => d.options.map(o => ({ attr: d.attribute, val: o }))));

  if (!combos.length || !combos[0].length) {
    tbody.innerHTML = '';
    tableWrap.classList.remove('show');
    return;
  }

  tableWrap.classList.add('show');

  // Keep existing values to avoid losing user input
  const existingRows = {};
  tbody.querySelectorAll('tr.variant-data-row').forEach(tr => {
    const name = tr.dataset.variantName;
    existingRows[name] = {
      sku:  tr.querySelector('.var-sku')?.value  || '',
      cost: tr.querySelector('.var-cost')?.value || '',
      sell: tr.querySelector('.var-sell')?.value || '',
    };
  });

  tbody.innerHTML = '';

  combos.forEach((combo, idx) => {
    const name = combo.map(c => c.val).join(' - ');
    const prev = existingRows[name] || {};
const safeId = 'vif_' + name.replace(/[^a-zA-Z0-9]/g, '_');
const hasImg = !!variantImageData[name];
    // Data row
    const tr = document.createElement('tr');
    tr.className = 'variant-data-row';
    tr.dataset.variantName = name;
    tr.dataset.variantIdx  = idx;
    tr.innerHTML = `
      <td class="var-row-name">${escHtml(name)}</td>
      <td class="var-row-input"><input type="text" class="var-sku"  value="${escHtml(prev.sku  ||'')}" placeholder="" /></td>
      <td class="var-row-input"><input type="number" class="var-cost" value="${escHtml(prev.cost||'')}" placeholder="" step="0.01" min="0" /></td>
      <td class="var-row-input"><input type="number" class="var-sell" value="${escHtml(prev.sell||'0')}" placeholder="" step="0.01" min="0" /></td>
      <td class="var-row-actions">
        <button type="button" class="btn-var-info" onclick="openAddlModal('${escAttr(name)}')" title="Additional Info">✏️</button>
        <button type="button" class="btn-var-del"  onclick="deleteVariantRow(this,'${escAttr(name)}')" title="Delete">⊗</button>
      </td>
      <td class="var-img-cell">
  <input type="file"
         class="var-img-file"
         id="${safeId}"
         accept="image/jpeg,image/png,image/jpg,image/gif"
         onchange="handleVariantImageChange(this,'${escAttr(name)}')" />
  <div class="var-img-wrap">
    <div class="var-img-trigger ${hasImg ? 'has-img' : ''}"
         data-variant-img="${escHtml(name)}"
         onclick="document.getElementById('${safeId}').click()"
         title="Upload image">
      <img class="vit-preview" src="${hasImg ? escHtml(variantImageData[name].base64) : ''}" alt="" />
      <span class="vit-icon">🖼</span>
      <span class="vit-label">Add<br>Image</span>
    </div>
    <button class="var-img-remove" type="button"
            onclick="removeVariantImage('${escAttr(name)}')" title="Remove">✕</button>
  </div>
</td>
    `;
    tbody.appendChild(tr);

    // Reporting tags row (collapsed by default)
    const tr2 = document.createElement('tr');
    tr2.className = 'reporting-tags-row';
    tr2.innerHTML = `
      <td colspan="5">
        <span class="reporting-tags-toggle" onclick="this.parentElement.querySelector('.rt-content').style.display = this.parentElement.querySelector('.rt-content').style.display==='none'?'block':'none';">
          🏷️ Reporting Tags ▾
        </span>
        <div class="rt-content" style="display:none;padding:6px 0 2px;">
          <input type="text" style="width:220px;border:1px solid #d0d4de;border-radius:5px;padding:5px 8px;font-size:12px;" placeholder="Add tags..." />
        </div>
      </td>
    `;
    tbody.appendChild(tr2);
  });
}

function deleteVariantRow(btn, name) {
  const tr = btn.closest('tr.variant-data-row');
  const tr2 = tr.nextElementSibling;
  tr.remove();
  if (tr2 && tr2.classList.contains('reporting-tags-row')) tr2.remove();
  delete variantAdditionalData[name];
  delete variantImageData[name];
}

function generateAllSKU() {
  const nameEl = document.querySelector('input[name="name"]');
  const base = (nameEl?.value || 'ITEM').toUpperCase().replace(/\s+/g,'-').slice(0,8);
  let counter = 1;
  document.querySelectorAll('.var-sku').forEach(inp => {
    if (!inp.value) inp.value = base + '-' + String(counter++).padStart(3,'0');
  });
}

function copyToAll(field) {
  const selector = field === 'cost' ? '.var-cost' : '.var-sell';
  const inputs = document.querySelectorAll(selector);
  if (!inputs.length) return;
  const first = inputs[0].value;
  if (!first) { alert('Fill the value first row'); return; }
  inputs.forEach(inp => inp.value = first);
}

// ══════════════════════════════════════════════
// ADDITIONAL INFO MODAL (per variant)
// ══════════════════════════════════════════════
let _addlCurrentVariant = null;

function openAddlModal(variantName) {
  _addlCurrentVariant = variantName;
  const data = variantAdditionalData[variantName] || {};
  document.getElementById('addl-upc').value  = data.upc  || '';
  document.getElementById('addl-mpn').value  = data.mpn  || '';
  document.getElementById('addl-ean').value  = data.ean  || '';
  document.getElementById('addl-isbn').value = data.isbn || '';
  // Custom fields
  document.querySelectorAll('[id^="addl-cf-"]').forEach(inp => {
    const fid = inp.dataset.fieldId;
    inp.value = data['cf_' + fid] || '';
  });
  document.getElementById('addl-modal').classList.add('open');
}

function closeAddlModal() {
  document.getElementById('addl-modal').classList.remove('open');
  _addlCurrentVariant = null;
}

function saveAddlModal() {
  if (!_addlCurrentVariant) { closeAddlModal(); return; }
  const data = {
    upc:  document.getElementById('addl-upc').value.trim(),
    mpn:  document.getElementById('addl-mpn').value.trim(),
    ean:  document.getElementById('addl-ean').value.trim(),
    isbn: document.getElementById('addl-isbn').value.trim(),
  };
  document.querySelectorAll('[id^="addl-cf-"]').forEach(inp => {
    data['cf_' + inp.dataset.fieldId] = inp.value.trim();
  });
  variantAdditionalData[_addlCurrentVariant] = data;
  closeAddlModal();
}

document.getElementById('addl-modal').addEventListener('click', e => {
  if (e.target.id === 'addl-modal') closeAddlModal();
});

// ══════════════════════════════════════════════
// PACK VARIANTS INTO JSON before submit
// ══════════════════════════════════════════════

function packVariants() {
  const isVariant = document.getElementById('item_variant_type').value === 'contains_variants';
  if (!isVariant) return;

  const variants = [];
  document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr => {
    const name = tr.dataset.variantName;
    const imgData = variantImageData[name];
variants.push({
  name,
  sku:                   tr.querySelector('.var-sku')?.value  || '',
  cost_price:            tr.querySelector('.var-cost')?.value || '',
  selling_price:         tr.querySelector('.var-sell')?.value || '',
  additional:            variantAdditionalData[name] || {},
  product_image:         imgData ? 'product_img/' + imgData.filename : null,
  product_image_base64:  imgData ? imgData.base64 : null,
});
    
  });

  const variationData = getVariationData();
  document.getElementById('variants-json').value = JSON.stringify({
    attributes: variationData,
    variants:   variants,
  });
}

function handleFormSubmit() {
  // Pack variants JSON before submitting
  packVariants();
  // Small delay to ensure hidden field is set, then submit
  document.getElementById('main-form').submit();
}

// ══════════════════════════════════════════════
// BRAND MODAL
// ══════════════════════════════════════════════
function openBrandModal(){document.getElementById('brand-modal').classList.add('open');document.getElementById('new-brand-input').value='';document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';cancelEdit();loadBrands();}
function closeBrandModal(){document.getElementById('brand-modal').classList.remove('open');cancelEdit();}
document.getElementById('brand-modal').addEventListener('click',e=>{if(e.target.id==='brand-modal')closeBrandModal();});
async function loadBrands(){const list=document.getElementById('brand-list');list.innerHTML='<div class="brand-empty">Loading...</div>';try{const res=await fetch('/brands/list',{headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const json=await res.json();if(json.success)renderList(json.data);else list.innerHTML='<div class="brand-empty" style="color:#e74c3c;">Failed to load</div>';}catch{list.innerHTML='<div class="brand-empty" style="color:#e74c3c;">Failed to load</div>';}}
function renderList(brands){const list=document.getElementById('brand-list');if(!brands||!brands.length){list.innerHTML='<div class="brand-empty">No brands added yet</div>';return;}list.innerHTML=brands.map(b=>{const s=esc(b.name);return`<div class="brand-list-item" id="brand-row-${b.id}"><span>${s}</span><div class="brand-actions"><button class="btn-edit-brand" onclick="editBrand(${b.id},'${s.replace(/'/g,"\\'")}')">✏️</button><button class="btn-del-brand" onclick="deleteBrand(${b.id},'${s.replace(/'/g,"\\'")}')">🗑️</button></div></div>`;}).join('');}
function editBrand(id,name){document.getElementById('add-brand-form').style.display='none';document.getElementById('edit-brand-form').style.display='flex';document.getElementById('edit-brand-id').value=id;document.getElementById('edit-brand-input').value=name;document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));document.getElementById(`brand-row-${id}`)?.classList.add('editing');document.getElementById('edit-brand-input').focus();document.getElementById('brand-error').style.display='none';document.getElementById('brand-success').style.display='none';}
function cancelEdit(){document.getElementById('edit-brand-form').style.display='none';document.getElementById('add-brand-form').style.display='flex';document.getElementById('edit-brand-id').value='';document.getElementById('edit-brand-input').value='';document.querySelectorAll('.brand-list-item').forEach(i=>i.classList.remove('editing'));}
async function updateBrand(){const id=document.getElementById('edit-brand-id').value;const name=document.getElementById('edit-brand-input').value.trim();const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-update-brand');errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';return;}btn.disabled=true;btn.textContent='Updating...';try{const res=await fetch(`/brands/${id}`,{method:'PUT',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const json=await res.json();if(!res.ok){errEl.textContent=json.errors?.name?.[0]||json.message||'Failed';errEl.style.display='block';}else{sucEl.textContent='Brand updated successfully!';sucEl.style.display='block';const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt)opt.textContent=name;loadBrands();cancelEdit();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='✓ Update';}}
async function addBrand(){const input=document.getElementById('new-brand-input');const errEl=document.getElementById('brand-error');const sucEl=document.getElementById('brand-success');const btn=document.getElementById('btn-add-brand');const name=input.value.trim();errEl.style.display='none';sucEl.style.display='none';if(!name){errEl.textContent='Brand name cannot be empty.';errEl.style.display='block';input.focus();return;}btn.disabled=true;btn.textContent='Adding...';try{const res=await fetch('/brands',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({name})});const json=await res.json();if(!res.ok){errEl.textContent=json.message||'Failed';errEl.style.display='block';}else{input.value='';sucEl.textContent='Brand added successfully!';sucEl.style.display='block';const sel=document.getElementById('brand-select');const opt=document.createElement('option');opt.value=json.data.id;opt.textContent=json.data.name;opt.selected=true;sel.appendChild(opt);loadBrands();setTimeout(()=>sucEl.style.display='none',3000);}}catch{errEl.textContent='Network error.';errEl.style.display='block';}finally{btn.disabled=false;btn.textContent='+ Add New';}}
async function deleteBrand(id,name){if(!confirm(`Delete brand "${name}"? This action cannot be undone.`))return;try{const res=await fetch(`/brands/${id}`,{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}});const json=await res.json();if(res.ok&&json.success){document.getElementById(`brand-row-${id}`)?.remove();const opt=document.querySelector(`#brand-select option[value="${id}"]`);if(opt){if(opt.selected)document.getElementById('brand-select').value='';opt.remove();}if(!document.getElementById('brand-list').querySelector('.brand-list-item'))document.getElementById('brand-list').innerHTML='<div class="brand-empty">No brands added yet</div>';const sucEl=document.getElementById('brand-success');sucEl.textContent='Brand deleted successfully!';sucEl.style.display='block';setTimeout(()=>sucEl.style.display='none',3000);if(document.getElementById('edit-brand-id').value==id)cancelEdit();}else{alert(json.message||'Failed to delete brand');}}catch{alert('Failed to delete brand');}}

// ══════════════════════════════════════════════
// ESCAPE HELPERS
// ══════════════════════════════════════════════
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');}
function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function escAttr(s){return String(s).replace(/'/g,"\\'");}
// ══════════════════════════════════════════════
// GENERATE SKU MODAL
// ══════════════════════════════════════════════
let _skuRowCount = 0;

function getSkuAttrOptions() {
  // Item Name + current variation attributes
  const attrs = ['Item Name', 'Custom Text'];
  document.querySelectorAll('.variation-row .var-attr-input').forEach(inp => {
    const v = inp.value.trim();
    if (v && !attrs.includes(v)) attrs.push(v);
  });
  return attrs;
}

function openSkuModal() {
  const itemName = document.querySelector('input[name="name"]')?.value || 'ITEM';
  document.getElementById('sku-modal-title').textContent = itemName;
  document.getElementById('sku-rows-tbody').innerHTML = '';
  _skuRowCount = 0;

  // Auto-add default rows: Item Name + first attribute
  skuAddRow('Item Name', 'First', 3, 'Upper Case', '-');
  const firstAttr = document.querySelector('.variation-row .var-attr-input')?.value.trim();
  if (firstAttr) skuAddRow(firstAttr, 'First', 3, 'Upper Case', '');

  skuUpdatePreview();
  document.getElementById('sku-modal').classList.add('open');
}

function closeSkuModal() {
  document.getElementById('sku-modal').classList.remove('open');
}

function skuAddRow(attr = 'Item Name', showType = 'First', showCount = 3, letterCase = 'Upper Case', separator = '-') {
  _skuRowCount++;
  const n = _skuRowCount;
  const attrOpts = getSkuAttrOptions();
  const attrHtml = attrOpts.map(a => `<option value="${escHtml(a)}" ${a === attr ? 'selected' : ''}>${escHtml(a)}</option>`).join('');

  const showInput = attr === 'Custom Text'
    ? `<input type="text" class="sku-show-wrap" id="sku-custom-${n}" placeholder="Custom text" value="" style="border:1px solid #d0d4de;border-radius:6px;padding:7px 10px;font-size:13px;outline:none;width:100%;" oninput="skuUpdatePreview()" />`
    : `<div class="sku-show-wrap">
        <select id="sku-show-type-${n}" onchange="skuUpdatePreview()">
          <option ${showType === 'First' ? 'selected' : ''}>First</option>
          <option ${showType === 'Last' ? 'selected' : ''}>Last</option>
        </select>
        <input type="number" id="sku-show-count-${n}" value="${showCount}" min="1" max="20" oninput="skuUpdatePreview()" />
      </div>`;

  const sepOpts = ['-', '_', '/', '.', ''].map(s =>
    `<option value="${s}" ${s === separator ? 'selected' : ''}>${s === '' ? '(none)' : s}</option>`
  ).join('');

  const isLast = separator === '' || _skuRowCount === 1;

  const tr = document.createElement('tr');
  tr.id = 'sku-row-' + n;
  tr.innerHTML = `
    <td>
      <select class="sku-sel" id="sku-attr-${n}" onchange="skuAttrChanged(${n}); skuUpdatePreview()">
        ${attrHtml}
      </select>
    </td>
    <td id="sku-show-cell-${n}">${showInput}</td>
    <td>
      <div class="sku-case-wrap">
        <select class="sku-case-sel" id="sku-case-${n}" onchange="skuUpdatePreview()">
          <option ${letterCase === 'Upper Case' ? 'selected' : ''}>Upper Case</option>
          <option ${letterCase === 'Lower Case' ? 'selected' : ''}>Lower Case</option>
          <option ${letterCase === 'None' ? 'selected' : ''}>None</option>
        </select>
        <button class="sku-case-x" type="button" onclick="skuClearCase(${n})">✕</button>
      </div>
    </td>
    <td>
      <div class="sku-sep-wrap">
        <select class="sku-sep-sel" id="sku-sep-${n}" onchange="skuUpdatePreview()">
          ${sepOpts}
        </select>
        <button class="sku-sep-x" type="button" onclick="skuClearSep(${n})">✕</button>
      </div>
    </td>
    <td>
      <button class="sku-row-del" type="button" onclick="skuDelRow(${n})" title="Remove">⊗</button>
    </td>
  `;
  document.getElementById('sku-rows-tbody').appendChild(tr);
  skuUpdatePreview();
}

function skuAttrChanged(n) {
  const attr = document.getElementById('sku-attr-' + n)?.value;
  const cell = document.getElementById('sku-show-cell-' + n);
  if (!cell) return;

  if (attr === 'Custom Text') {
    cell.innerHTML = `<input type="text" id="sku-custom-${n}" placeholder="Custom text" value="" style="border:1px solid #d0d4de;border-radius:6px;padding:7px 10px;font-size:13px;outline:none;width:100%;" oninput="skuUpdatePreview()" />`;
  } else {
    cell.innerHTML = `<div class="sku-show-wrap">
      <select id="sku-show-type-${n}" onchange="skuUpdatePreview()">
        <option>First</option><option>Last</option>
      </select>
      <input type="number" id="sku-show-count-${n}" value="3" min="1" max="20" oninput="skuUpdatePreview()" />
    </div>`;
  }
  skuUpdatePreview();
}

function skuClearCase(n) {
  const sel = document.getElementById('sku-case-' + n);
  if (sel) sel.value = 'None';
  skuUpdatePreview();
}

function skuClearSep(n) {
  const sel = document.getElementById('sku-sep-' + n);
  if (sel) sel.value = '';
  skuUpdatePreview();
}

function skuDelRow(n) {
  document.getElementById('sku-row-' + n)?.remove();
  skuUpdatePreview();
}

function skuGetPartValue(n, variantName) {
  const attr = document.getElementById('sku-attr-' + n)?.value || '';
  const caseType = document.getElementById('sku-case-' + n)?.value || 'None';

  let raw = '';

  if (attr === 'Item Name') {
    raw = document.querySelector('input[name="name"]')?.value || '';
    const showType = document.getElementById('sku-show-type-' + n)?.value || 'First';
    const count = parseInt(document.getElementById('sku-show-count-' + n)?.value || 3);
    raw = showType === 'First' ? raw.slice(0, count) : raw.slice(-count);
  } else if (attr === 'Custom Text') {
    raw = document.getElementById('sku-custom-' + n)?.value || '';
  } else {
    // Variant attribute value — get from variantName
    // variantName is like "Red - Large", attr is "Color"
    // Find attribute index
    const varData = getVariationData();
    const attrIdx = varData.findIndex(d => d.attribute === attr);
    if (attrIdx >= 0 && variantName) {
      const parts = variantName.split(' - ');
      raw = parts[attrIdx] || attr;
    } else {
      raw = attr;
    }
    const showType = document.getElementById('sku-show-type-' + n)?.value || 'First';
    const count = parseInt(document.getElementById('sku-show-count-' + n)?.value || 3);
    raw = showType === 'First' ? raw.slice(0, count) : raw.slice(-count);
  }

  // Apply letter case
  if (caseType === 'Upper Case') raw = raw.toUpperCase();
  else if (caseType === 'Lower Case') raw = raw.toLowerCase();

  return raw;
}

function skuBuildForVariant(variantName) {
  const rows = document.querySelectorAll('#sku-rows-tbody tr');
  let result = '';
  rows.forEach((tr, idx) => {
    const n = tr.id.replace('sku-row-', '');
    const part = skuGetPartValue(n, variantName);
    const sep = document.getElementById('sku-sep-' + n)?.value || '';
    result += part;
    if (idx < rows.length - 1) result += sep;
  });
  return result;
}

function skuUpdatePreview() {
  // Preview using first variant or just item name
  const firstVariant = document.querySelector('#variants-tbody tr.variant-data-row')?.dataset.variantName || '';
  const preview = skuBuildForVariant(firstVariant);
  document.getElementById('sku-preview-box').textContent = preview || '—';
}

function applyGeneratedSKU() {
  // Apply to ALL variant rows
  document.querySelectorAll('#variants-tbody tr.variant-data-row').forEach(tr => {
    const variantName = tr.dataset.variantName || '';
    const sku = skuBuildForVariant(variantName);
    const skuInput = tr.querySelector('.var-sku');
    if (skuInput) skuInput.value = sku;
  });
  closeSkuModal();
}

// Override existing simple generateAllSKU with modal opener
function generateAllSKU() {
  openSkuModal();
}

document.getElementById('sku-modal').addEventListener('click', e => {
  if (e.target.id === 'sku-modal') closeSkuModal();
});
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/products/create.blade.php ENDPATH**/ ?>