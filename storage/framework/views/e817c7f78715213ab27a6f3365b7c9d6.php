<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e($customer->display_name); ?> | Inventory</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #333; background: #f5f5f5; }

.app-shell { display: flex; height: 100vh; overflow: hidden; flex-direction: column; }
.topbar-global { display: flex; align-items: center; gap: 12px; padding: 0 16px; height: 48px; background: #fff; border-bottom: 1px solid #e0e0e0; flex-shrink: 0; }
.topbar-global .logo-refresh { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666; font-size: 16px; }
.topbar-global .search-box { display: flex; align-items: center; gap: 8px; border: 1px solid #d0d0d0; border-radius: 6px; padding: 5px 12px; width: 280px; background: #fff; }
.topbar-global .search-box input { border: none; outline: none; font-size: 13px; color: #555; width: 200px; background: transparent; }
.topbar-global .search-box .search-icon { color: #888; font-size: 13px; }
.topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
.topbar-trial { font-size: 12px; color: #888; }
.topbar-trial a { color: #1a73e8; text-decoration: none; font-weight: 500; }
.org-selector { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; color: #333; cursor: pointer; padding: 4px 8px; border-radius: 4px; }
.org-selector:hover { background: #f5f5f5; }
.btn-plus { width: 32px; height: 32px; background: #1a73e8; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; cursor: pointer; }
.topbar-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; font-size: 14px; position: relative; }
.topbar-icon:hover { background: #f5f5f5; }
.badge { position: absolute; top: 1px; right: 1px; background: #e53935; color: #fff; border-radius: 50%; width: 14px; height: 14px; font-size: 9px; display: flex; align-items: center; justify-content: center; }
.avatar-btn { width: 32px; height: 32px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #666; cursor: pointer; overflow: hidden; }
.apps-icon { font-size: 18px; cursor: pointer; color: #555; }
.body-layout { display: flex; flex: 1; overflow: hidden; }

.left-panel { width: 300px; flex-shrink: 0; background: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; overflow: hidden; }
.left-panel-header { display: flex; align-items: center; padding: 10px 14px; border-bottom: 1px solid #e0e0e0; gap: 8px; }
.left-panel-title { font-size: 14px; font-weight: 600; color: #222; display: flex; align-items: center; gap: 5px; flex: 1; }
.left-panel-title .caret { color: #555; font-size: 11px; }
.left-panel-actions { display: flex; align-items: center; gap: 6px; }
.lp-btn { width: 30px; height: 30px; border-radius: 6px; border: 1px solid #d0d0d0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; font-size: 15px; font-weight: 600; }
.lp-btn:hover { background: #f5f5f5; }
.lp-btn.blue { background: #1a73e8; border-color: #1a73e8; color: #fff; }
.customer-list { flex: 1; overflow-y: auto; }
.customer-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f0f0f0; }
.customer-item:hover { background: #f8f9ff; }
.customer-item.active { background: #e8f0fe; }
.customer-item .cb { width: 16px; height: 16px; border: 1px solid #ccc; border-radius: 3px; flex-shrink: 0; }
.customer-item-info { flex: 1; }
.customer-item-name { font-size: 13px; font-weight: 500; color: #222; }
.customer-item-amount { font-size: 12px; color: #666; margin-top: 2px; }

.main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: #fff; }
.customer-header { display: flex; align-items: center; padding: 10px 18px; border-bottom: 1px solid #e0e0e0; gap: 10px; background: #fff; flex-shrink: 0; }
.customer-header-name { font-size: 20px; font-weight: 600; color: #222; flex: 1; }
.header-actions { display: flex; align-items: center; gap: 8px; }
.btn-edit { padding: 6px 18px; border: 1px solid #d0d0d0; border-radius: 4px; background: #fff; font-size: 13px; color: #333; cursor: pointer; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; }
.btn-edit:hover { background: #f5f5f5; }
.btn-count { width: 28px; height: 28px; border: 1px solid #d0d0d0; border-radius: 4px; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #555; cursor: pointer; }
.btn-new-txn { padding: 6px 16px; background: #1a73e8; color: #fff; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; }
.btn-new-txn:hover { background: #1557b0; }
.btn-more { padding: 6px 12px; border: 1px solid #d0d0d0; border-radius: 4px; background: #fff; font-size: 13px; color: #333; cursor: pointer; display: flex; align-items: center; gap: 5px; }
.btn-more:hover { background: #f5f5f5; }
.btn-close-x { width: 28px; height: 28px; border-radius: 4px; border: none; background: none; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #666; cursor: pointer; text-decoration: none; }
.btn-close-x:hover { background: #f5f5f5; }

.tabs-bar { display: flex; border-bottom: 1px solid #e0e0e0; padding: 0 18px; flex-shrink: 0; }
.tab { padding: 9px 14px; font-size: 13px; color: #666; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; white-space: nowrap; }
.tab:hover { color: #333; }
.tab.active { color: #1a73e8; border-bottom-color: #1a73e8; font-weight: 500; }

.split-view { display: flex; flex: 1; overflow: hidden; }
.detail-left { width: 52%; border-right: 1px solid #e0e0e0; overflow-y: auto; flex-shrink: 0; }

.contact-card { display: flex; align-items: flex-start; gap: 12px; padding: 16px 18px; border-bottom: 1px solid #f0f0f0; }
.contact-avatar { width: 42px; height: 42px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #999; flex-shrink: 0; }
.contact-details { flex: 1; }
.contact-name { font-size: 14px; font-weight: 600; color: #222; }
.contact-email { font-size: 12.5px; color: #555; margin-top: 3px; word-break: break-all; }
.contact-portal { font-size: 12.5px; color: #1a73e8; cursor: pointer; margin-top: 5px; text-decoration: none; display: inline-block; }
.contact-portal:hover { text-decoration: underline; }
.contact-gear { width: 28px; height: 28px; border-radius: 50%; border: 1px solid #e0e0e0; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666; font-size: 13px; flex-shrink: 0; }
.contact-gear:hover { background: #f5f5f5; }

.section { border-bottom: 1px solid #f0f0f0; }
.section-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 18px; cursor: pointer; user-select: none; }
.section-header:hover { background: #fafafa; }
.section-title { font-size: 12px; font-weight: 600; color: #555; letter-spacing: 0.5px; text-transform: uppercase; }
.section-actions { display: flex; align-items: center; gap: 8px; }
.section-add-btn { width: 24px; height: 24px; border-radius: 50%; background: #1a73e8; color: #fff; border: none; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; line-height: 1; transition: background 0.15s; }
.section-add-btn:hover { background: #1557b0; }
.section-chevron { color: #888; font-size: 13px; }
.section-body { padding: 8px 18px 14px; }

.address-block { margin-bottom: 10px; }
.address-label { font-size: 13px; font-weight: 500; color: #333; margin-bottom: 3px; }
.address-value { font-size: 12.5px; color: #888; }
.address-value a { color: #1a73e8; text-decoration: none; }
.address-value a:hover { text-decoration: underline; }

.detail-row { display: flex; padding: 5px 0; gap: 12px; }
.detail-key { width: 130px; min-width: 130px; font-size: 13px; color: #888; }
.detail-val { font-size: 13px; color: #333; }
.detail-val.disabled { color: #e53935; font-weight: 500; }
.detail-val.disabled::before { content: '● '; }

.empty-msg { font-size: 13px; color: #888; padding: 8px 0; text-align: center; }
.portal-promo { background: #f0fff4; border: 1px solid #a7f3d0; border-radius: 6px; padding: 14px; margin: 10px 0; display: flex; gap: 12px; }
.portal-promo-icon { font-size: 24px; flex-shrink: 0; }
.portal-promo p { font-size: 12.5px; color: #444; line-height: 1.5; }
.portal-promo a { color: #1a73e8; }

.cp-list-item { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f5; }
.cp-list-item:last-child { border-bottom: none; }
.cp-list-name { font-size: 13px; font-weight: 500; color: #222; }
.cp-list-meta { font-size: 12px; color: #666; margin-top: 2px; }
.primary-badge { display: inline-flex; align-items: center; gap: 3px; background: #e8f0fe; color: #1a73e8; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; margin-left: 6px; vertical-align: middle; }
.cp-gear-wrap { position: relative; flex-shrink: 0; }
.cp-gear-btn { width: 26px; height: 26px; border-radius: 50%; border: 1px solid #e0e0e0; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #888; font-size: 12px; opacity: 0; transition: opacity 0.15s; }
.cp-list-item:hover .cp-gear-btn { opacity: 1; }
.cp-gear-btn:hover { background: #f5f5f5; opacity: 1; }
.cp-gear-menu { display: none; position: absolute; top: 30px; right: 0; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); z-index: 300; min-width: 165px; overflow: hidden; }
.cp-gear-menu.open { display: block; }
.cp-gear-menu-item { display: flex; align-items: center; gap: 8px; padding: 9px 14px; font-size: 13px; color: #333; cursor: pointer; }
.cp-gear-menu-item:hover { background: #f5f5f5; }
.cp-gear-menu-item.primary-action { color: #1a73e8; }
.cp-gear-menu-item.primary-action:hover { background: #f0f4ff; }
.cp-gear-menu-item.danger { color: #e53935; }
.cp-gear-menu-item.danger:hover { background: #fff5f5; }
.cp-gear-menu-sep { height: 1px; background: #f0f0f0; margin: 3px 0; }
.card-gear-wrap { position: relative; flex-shrink: 0; }
.card-gear-menu { display: none; position: absolute; top: 32px; right: 0; background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); z-index: 300; min-width: 165px; overflow: hidden; }
.card-gear-menu.open { display: block; }

.del-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 700; align-items: center; justify-content: center; }
.del-overlay.open { display: flex; }
.del-modal { background: #fff; border-radius: 8px; width: 380px; max-width: 95vw; padding: 28px; box-shadow: 0 8px 32px rgba(0,0,0,0.22); text-align: center; }
.del-modal-icon { font-size: 36px; margin-bottom: 12px; }
.del-modal-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 8px; }
.del-modal-msg { font-size: 13px; color: #666; margin-bottom: 22px; line-height: 1.5; }
.del-modal-actions { display: flex; gap: 10px; justify-content: center; }
.del-confirm-btn { padding: 8px 24px; background: #e53935; color: #fff; border: none; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer; }
.del-confirm-btn:hover { background: #c62828; }
.del-cancel-btn { padding: 8px 18px; background: #fff; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; cursor: pointer; }
.del-cancel-btn:hover { background: #f5f5f5; }

.detail-right { flex: 1; overflow-y: auto; padding: 18px 20px; }
.whats-next-card { border: 1px solid #e0e0e0; border-radius: 6px; padding: 14px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px; }
.whats-next-icon { font-size: 18px; }
.whats-next-text { flex: 1; }
.whats-next-text strong { font-size: 13px; font-weight: 600; color: #222; display: block; margin-bottom: 4px; }
.whats-next-text p { font-size: 12.5px; color: #555; }
.whats-next-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.btn-new-invoice { padding: 6px 14px; background: #1a73e8; color: #fff; border: none; border-radius: 4px; font-size: 12.5px; font-weight: 500; cursor: pointer; }
.btn-new-invoice:hover { background: #1557b0; }
.three-dots { color: #888; cursor: pointer; font-size: 18px; }
.info-label { font-size: 12px; color: #888; margin-bottom: 4px; }
.info-value { font-size: 13px; color: #333; font-weight: 500; margin-bottom: 14px; }
.receivables-title { font-size: 15px; font-weight: 600; color: #222; margin-bottom: 12px; }
.receivables-table { width: 100%; border-collapse: collapse; }
.receivables-table th { font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 0.4px; padding: 6px 10px; text-align: right; border-bottom: 1px solid #e0e0e0; }
.receivables-table th:first-child { text-align: left; }
.receivables-table td { font-size: 13px; color: #333; padding: 10px; text-align: right; border-bottom: 1px solid #f0f0f0; }
.receivables-table td:first-child { text-align: left; }
.ship-info { font-size: 12.5px; color: #555; margin-top: 14px; }
.ship-info .orange { color: #f97316; font-weight: 600; }

.right-sidebar { width: 36px; background: #fff; border-left: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 8px 0; gap: 4px; flex-shrink: 0; }
.rs-icon { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #777; font-size: 14px; border-radius: 4px; }
.rs-icon:hover { background: #f0f0f0; }
.rs-icon.orange { background: #f97316; color: #fff; }
.rs-icon.active { color: #1a73e8; }
.rs-separator { height: 1px; background: #e0e0e0; margin: 4px 6px; }

.overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 500; }
.overlay.open { display: block; }
.address-panel { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95); width: 500px; max-width: 95vw; max-height: 90vh; background: #fff; box-shadow: 0 8px 32px rgba(0,0,0,0.22); z-index: 501; display: flex; flex-direction: column; border-radius: 8px; opacity: 0; pointer-events: none; transition: opacity 0.2s ease, transform 0.2s ease; }
.address-panel.open { opacity: 1; pointer-events: all; transform: translate(-50%, -50%) scale(1); }
.ap-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid #e0e0e0; background: #fafafa; flex-shrink: 0; border-radius: 8px 8px 0 0; }
.ap-title { font-size: 15px; font-weight: 600; color: #222; }
.ap-close { width: 26px; height: 26px; border-radius: 50%; border: none; background: #eee; color: #555; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.ap-close:hover { background: #e53935; color: #fff; }
.ap-body { flex: 1; overflow-y: auto; padding: 18px; }
.ap-footer { padding: 12px 18px; border-top: 1px solid #e0e0e0; display: flex; gap: 8px; flex-shrink: 0; }
.ap-form-row { margin-bottom: 14px; }
.ap-form-row label { display: block; font-size: 12px; color: #555; margin-bottom: 5px; font-weight: 500; }
.ap-form-row input, .ap-form-row select, .ap-form-row textarea { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; background: #fff; outline: none; transition: border 0.15s; -webkit-appearance: none; appearance: none; }
.ap-form-row input:focus, .ap-form-row select:focus, .ap-form-row textarea:focus { border-color: #1a73e8; box-shadow: 0 0 0 2px rgba(26,115,232,0.1); }
.ap-form-row select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px; }
.ap-form-row textarea { resize: vertical; min-height: 70px; }
.ap-form-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.ap-phone-row { display: flex; gap: 8px; }
.ap-phone-code { width: 90px; flex-shrink: 0; padding: 7px 8px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; -webkit-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; padding-right: 22px; background-color: #fff; outline: none; }
.ap-phone-num { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; }
.ap-phone-num:focus { border-color: #1a73e8; }
.btn-save-ap { padding: 7px 22px; background: #1a73e8; color: #fff; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-save-ap:hover { background: #1557b0; }
.btn-cancel-ap { padding: 7px 16px; background: #fff; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; cursor: pointer; }
.btn-cancel-ap:hover { background: #f5f5f5; }

.cp-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 600; align-items: center; justify-content: center; }
.cp-modal-overlay.open { display: flex; }
.cp-modal { background: #fff; border-radius: 8px; width: 860px; max-width: 95vw; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 8px 32px rgba(0,0,0,0.22); animation: cpFadeIn 0.2s ease; }
@keyframes cpFadeIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
.cp-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #e0e0e0; flex-shrink: 0; background: #fafafa; border-radius: 8px 8px 0 0; }
.cp-modal-title { font-size: 16px; font-weight: 600; color: #222; }
.cp-modal-close { background: none; border: none; font-size: 22px; color: #e53935; cursor: pointer; line-height: 1; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.cp-modal-close:hover { background: #fee2e2; }
.cp-modal-body { flex: 1; overflow-y: auto; padding: 24px; display: flex; gap: 24px; }
.cp-form-main { flex: 1; }
.cp-form-row { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 18px; }
.cp-form-label { width: 140px; min-width: 140px; font-size: 13px; color: #555; padding-top: 8px; }
.cp-input-full { width: 100%; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; outline: none; }
.cp-input-full:focus { border-color: #1a73e8; box-shadow: 0 0 0 2px rgba(26,115,232,0.1); }
.cp-phone-wrap { display: flex; gap: 8px; width: 100%; margin-bottom: 8px; }
.cp-phone-wrap:last-child { margin-bottom: 0; }
.cp-phone-code { width: 85px; padding: 7px 8px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; -webkit-appearance: none; appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%23666'/%3E%3C/svg%3E") no-repeat right 8px center; padding-right: 22px; }
.cp-phone-num { flex: 1; padding: 7px 10px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; outline: none; }
.cp-phone-num:focus { border-color: #1a73e8; }
.cp-skype-wrap { display: flex; align-items: center; gap: 8px; width: 100%; }
.cp-skype-icon { width: 32px; height: 32px; background: #00aff0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 700; flex-shrink: 0; }
.cp-portal-wrap { display: flex; align-items: flex-start; gap: 10px; padding: 14px 24px; border-top: 1px solid #f0f0f0; }
.cp-portal-wrap input[type=checkbox] { width: 16px; height: 16px; margin-top: 2px; cursor: pointer; flex-shrink: 0; accent-color: #1a73e8; }
.cp-portal-text { font-size: 13px; color: #333; line-height: 1.6; }
.cp-portal-text a { color: #1a73e8; }
.cp-modal-footer { padding: 14px 24px; border-top: 1px solid #e0e0e0; display: flex; gap: 10px; flex-shrink: 0; border-radius: 0 0 8px 8px; }
.cp-img-panel { width: 220px; flex-shrink: 0; border: 1px solid #e0e0e0; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; padding: 20px; min-height: 200px; cursor: pointer; transition: background 0.15s; }
.cp-img-panel:hover { background: #f8f9ff; }
.cp-img-upload-icon { width: 40px; height: 40px; background: #1a73e8; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; }
.cp-img-panel p { font-size: 12px; color: #555; text-align: center; line-height: 1.6; }
.cp-img-panel a { color: #1a73e8; font-size: 12px; text-decoration: underline; cursor: pointer; }
.cp-alert { display: none; margin: 0 24px 12px; padding: 10px 14px; border-radius: 4px; font-size: 13px; }
.cp-save-btn { padding: 7px 22px; background: #1a73e8; color: #fff; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; }
.cp-save-btn:hover { background: #1557b0; }
.cp-save-btn:disabled { background: #9fc3f5; cursor: not-allowed; }
.cp-cancel-btn { padding: 7px 16px; background: #fff; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; color: #333; cursor: pointer; }
.cp-cancel-btn:hover { background: #f5f5f5; }

/* ══ COMMENTS TAB ══ */
.comments-wrap { flex: 1; overflow-y: auto; padding: 24px 28px; max-width: 800px; }

/* Rich text editor box */
.comment-editor-box { border: 1px solid #d0d0d0; border-radius: 6px; overflow: hidden; margin-bottom: 24px; background: #fff; }
.comment-toolbar { display: flex; align-items: center; gap: 2px; padding: 6px 10px; border-bottom: 1px solid #e8e8e8; background: #fff; }
.toolbar-btn { width: 28px; height: 28px; border: none; background: none; border-radius: 4px; cursor: pointer; font-size: 13px; color: #444; display: flex; align-items: center; justify-content: center; font-weight: 600; }
.toolbar-btn:hover { background: #f0f0f0; }
.toolbar-btn.active { background: #e8f0fe; color: #1a73e8; }
.toolbar-sep { width: 1px; height: 18px; background: #e0e0e0; margin: 0 4px; }
.comment-editor { min-height: 80px; padding: 10px 14px; outline: none; font-size: 13px; color: #333; line-height: 1.6; }
.comment-editor:empty:before { content: attr(data-placeholder); color: #aaa; pointer-events: none; }
.comment-editor-footer { display: flex; align-items: center; justify-content: flex-end; padding: 8px 10px; border-top: 1px solid #f0f0f0; background: #fafafa; }
.btn-add-comment { padding: 6px 20px; background: #1a73e8; color: #fff; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-add-comment:hover { background: #1557b0; }
.btn-add-comment:disabled { background: #9fc3f5; cursor: not-allowed; }

/* ALL COMMENTS section */
.comments-section-title { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.comments-section-label { font-size: 11px; font-weight: 700; color: #555; letter-spacing: 0.8px; text-transform: uppercase; }
.comments-count-badge { background: #1a73e8; color: #fff; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 10px; }

/* Individual comment */
.comment-item { display: flex; gap: 12px; margin-bottom: 20px; }
.comment-avatar { width: 32px; height: 32px; border-radius: 50%; background: #e8f0fe; color: #1a73e8; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; margin-top: 2px; }
.comment-body { flex: 1; }
.comment-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.comment-author { font-size: 13px; font-weight: 600; color: #222; }
.comment-dot { color: #ccc; font-size: 10px; }
.comment-time { font-size: 12px; color: #888; }
.comment-content-box { background: #f8f9fc; border: 1px solid #e8eaf0; border-radius: 6px; padding: 10px 14px; font-size: 13px; color: #333; line-height: 1.6; position: relative; }
.comment-content-box:hover .comment-del-btn { opacity: 1; }
.comment-del-btn { position: absolute; top: 8px; right: 8px; width: 26px; height: 26px; border: none; background: none; cursor: pointer; color: #bbb; font-size: 14px; display: flex; align-items: center; justify-content: center; border-radius: 4px; opacity: 0; transition: opacity 0.15s, color 0.15s; }
.comment-del-btn:hover { color: #e53935; background: #fee2e2; }

.comments-empty { text-align: center; padding: 40px 20px; color: #aaa; font-size: 13px; }
.comments-empty-icon { font-size: 36px; margin-bottom: 10px; }

/* TOAST */
.toast-msg { position: fixed; top: 20px; right: 20px; padding: 12px 22px; border-radius: 6px; font-size: 13px; font-weight: 600; z-index: 9999; box-shadow: 0 4px 16px rgba(0,0,0,.15); animation: toastIn 0.3s ease; }
.toast-msg.ok  { background: #d4edda; color: #155724; }
.toast-msg.err { background: #fee2e2; color: #991b1b; }
@keyframes toastIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
::-webkit-scrollbar-track { background: transparent; }
</style>
</head>
<body>

<?php
  $ad         = $customer->additional_datas ?? [];
  $contacts   = $ad['contact_persons'] ?? [];
  $primaryIdx = -1;
  foreach ($contacts as $i => $cp) {
    if (!empty($cp['is_primary'])) { $primaryIdx = $i; break; }
  }
  if ($primaryIdx === -1 && count($contacts) > 0) $primaryIdx = 0;
  $primaryContact = $contacts[$primaryIdx] ?? null;

  // Comments from DB
$comments = collect($customer->comments ?? [])->sortByDesc('created_at')->values();
?>

<div class="app-shell">

  <!-- TOPBAR -->
  <div class="topbar-global">
    <div class="logo-refresh">↺</div>
    <div class="search-box">
      <span class="search-icon">⌕</span>
      <input placeholder="Search in Customers ( / )" />
      <span style="color:#bbb;font-size:11px;">▾</span>
    </div>
    <div class="topbar-right">
      <span class="topbar-trial">Your premi... <a href="#">Subscribe</a></span>
      <div class="org-selector"><?php echo e($customer->company_name ?? 'Techvolt'); ?> <span style="font-size:10px;">▾</span></div>
      <div class="btn-plus">+</div>
      <div class="topbar-icon">👤</div>
      <div class="topbar-icon">🔔<span class="badge">1</span></div>
      <div class="topbar-icon">⚙️</div>
      <div class="avatar-btn">👤</div>
      <div class="apps-icon">⋮⋮⋮</div>
    </div>
  </div>

  <div class="body-layout">

    <!-- LEFT PANEL -->
    <div class="left-panel">
      <div class="left-panel-header">
        <div class="left-panel-title">Active Customers <span class="caret">▾</span></div>
        <div class="left-panel-actions">
          <div class="lp-btn blue">+</div>
          <div class="lp-btn">···</div>
        </div>
      </div>
      <div class="customer-list">
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="customer-item <?php echo e($c->id == $customer->id ? 'active' : ''); ?>"
             onclick="window.location='<?php echo e(route('customers.show', $c->id)); ?>'">
          <div class="cb <?php echo e($c->id == $customer->id ? 'style=background:#1a73e8;border-color:#1a73e8;' : ''); ?>"></div>
          <div class="customer-item-info">
            <div class="customer-item-name"><?php echo e($c->display_name); ?></div>
            <div class="customer-item-amount">₹<?php echo e(number_format($c->outstanding ?? 0, 2)); ?></div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if(!isset($customers) || $customers->isEmpty()): ?>
        <div class="customer-item active">
          <div class="cb" style="background:#1a73e8;border-color:#1a73e8;"></div>
          <div class="customer-item-info">
            <div class="customer-item-name"><?php echo e($customer->display_name); ?></div>
            <div class="customer-item-amount">₹0.00</div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
      <div class="customer-header">
        <div class="customer-header-name"><?php echo e($customer->display_name); ?></div>
        <div class="header-actions">
          <a href="<?php echo e(route('customers.edit', $customer->id)); ?>" class="btn-edit">Edit</a>
          <div class="btn-count">0</div>
          <button class="btn-new-txn">New Transaction <span style="font-size:10px;">▾</span></button>
          <button class="btn-more">More <span style="font-size:10px;">▾</span></button>
          <a href="<?php echo e(route('customers.index')); ?>" class="btn-close-x">×</a>
        </div>
      </div>

      <div class="tabs-bar">
        <div class="tab active" onclick="showTab('overview',this)">Overview</div>
        <div class="tab" onclick="showTab('comments',this)">Comments</div>
        <div class="tab" onclick="showTab('transactions',this)">Transactions</div>
        <div class="tab" onclick="showTab('statement',this)">Statement</div>
        <div class="tab" onclick="showTab('history',this)">History</div>
      </div>

      <!-- OVERVIEW TAB -->
      <div class="split-view" id="tab-overview">
        <div class="detail-left">

          <!-- Contact Card -->
          <div class="contact-card">
            <div class="contact-avatar">👤</div>
            <div class="contact-details">
              <div class="contact-name" id="cardName">
                <?php echo e($customer->display_name); ?>

                <?php if($primaryContact): ?><span class="primary-badge">★ Primary</span><?php endif; ?>
              </div>
              <div class="contact-email" id="cardEmail">
                <?php if($primaryContact): ?>
                  <?php if(!empty($primaryContact['email'])): ?><?php echo e($primaryContact['email']); ?><?php endif; ?>
                  <?php if(!empty($primaryContact['work_phone'])): ?> &nbsp;·&nbsp; <?php echo e($primaryContact['work_phone']); ?><?php endif; ?>
                  <?php if(!empty($primaryContact['mobile'])): ?> &nbsp;·&nbsp; <?php echo e($primaryContact['mobile']); ?><?php endif; ?>
                <?php elseif($customer->email): ?>
                  <?php echo e($customer->email); ?>

                <?php endif; ?>
              </div>
              <a href="#" class="contact-portal">Invite to Portal</a>
            </div>
            <div class="card-gear-wrap">
              <div class="contact-gear" onclick="toggleCardGear(event)">⚙</div>
              <div class="card-gear-menu cp-gear-menu" id="cardGearMenu">
                <?php if($primaryContact !== null): ?>
                <div class="cp-gear-menu-item" onclick="openEditCp(<?php echo e($primaryIdx); ?>); closeAllGears();">
                  <span>✏️</span> Edit
                </div>
                <div class="cp-gear-menu-sep"></div>
                <div class="cp-gear-menu-item danger" onclick="openDelConfirm(<?php echo e($primaryIdx); ?>); closeAllGears();">
                  <span>🗑️</span> Delete
                </div>
                <?php else: ?>
                <div class="cp-gear-menu-item" style="color:#aaa;cursor:default;">No contacts yet</div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- ADDRESS -->
          <div class="section" id="section-address">
            <div class="section-header" onclick="toggleSection('address')">
              <span class="section-title">Address</span>
              <span class="section-chevron" id="chev-address">▲</span>
            </div>
            <div class="section-body" id="body-address">
              <?php
                $billing  = $customer->common_address['billing']  ?? [];
                $shipping = $customer->common_address['shipping'] ?? [];
                $hasBilling  = !empty(array_filter($billing));
                $hasShipping = !empty(array_filter($shipping));
              ?>
              <div class="address-block">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <div class="address-label">Billing Address</div>
                  <?php if($hasBilling): ?><button onclick="openAddressPanel('billing');return false;" style="background:none;border:none;cursor:pointer;color:#888;font-size:13px;padding:2px 6px;">✏️</button><?php endif; ?>
                </div>
                <div class="address-value">
                  <?php if($hasBilling): ?>
                    <?php echo e($billing['attention'] ?? ''); ?><br><?php echo e($billing['street1'] ?? ''); ?> <?php echo e($billing['street2'] ?? ''); ?><br>
                    <?php echo e($billing['city'] ?? ''); ?><?php if(!empty($billing['state'])): ?>, <?php echo e($billing['state']); ?><?php endif; ?>
                    <?php if(!empty($billing['pincode'])): ?> – <?php echo e($billing['pincode']); ?><?php endif; ?><br><?php echo e($billing['country'] ?? ''); ?>

                  <?php else: ?>
                    No Billing Address – <a href="#" onclick="openAddressPanel('billing');return false;">New Address</a>
                  <?php endif; ?>
                </div>
              </div>
              <div class="address-block">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <div class="address-label">Shipping Address</div>
                  <?php if($hasShipping): ?><button onclick="openAddressPanel('shipping');return false;" style="background:none;border:none;cursor:pointer;color:#888;font-size:13px;padding:2px 6px;">✏️</button><?php endif; ?>
                </div>
                <div class="address-value">
                  <?php if($hasShipping): ?>
                    <?php echo e($shipping['attention'] ?? ''); ?><br><?php echo e($shipping['street1'] ?? ''); ?> <?php echo e($shipping['street2'] ?? ''); ?><br>
                    <?php echo e($shipping['city'] ?? ''); ?><?php if(!empty($shipping['state'])): ?>, <?php echo e($shipping['state']); ?><?php endif; ?>
                    <?php if(!empty($shipping['pincode'])): ?> – <?php echo e($shipping['pincode']); ?><?php endif; ?><br><?php echo e($shipping['country'] ?? ''); ?>

                  <?php else: ?>
                    No Shipping Address – <a href="#" onclick="openAddressPanel('shipping');return false;">New Address</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          

          <!-- OTHER DETAILS -->
          <div class="section" id="section-other">
            <div class="section-header" onclick="toggleSection('other')">
              <span class="section-title">Other Details</span>
              <span class="section-chevron" id="chev-other">▲</span>
            </div>
            <div class="section-body" id="body-other">
              <?php $ad = $customer->additional_datas ?? []; ?>
              <div class="detail-row"><div class="detail-key">Customer Type</div><div class="detail-val"><?php echo e(ucfirst($customer->customer_type ?? 'Business')); ?></div></div>
              <div class="detail-row"><div class="detail-key">Default Currency</div><div class="detail-val"><?php echo e($ad['currency'] ?? 'INR'); ?></div></div>
              <div class="detail-row">
                <div class="detail-key">Portal Status</div>
                <div class="detail-val <?php echo e(empty($ad['enable_portal']) ? 'disabled' : ''); ?>"><?php echo e(!empty($ad['enable_portal']) ? 'Enabled' : 'Disabled'); ?></div>
              </div>
              <div class="detail-row"><div class="detail-key">Customer Language</div><div class="detail-val"><?php echo e($ad['language'] ?? 'English'); ?></div></div>
              <?php if(!empty($customer->pan)): ?><div class="detail-row"><div class="detail-key">PAN</div><div class="detail-val"><?php echo e($customer->pan); ?></div></div><?php endif; ?>
              <?php if(!empty($ad['payment_terms'])): ?><div class="detail-row"><div class="detail-key">Payment Terms</div><div class="detail-val"><?php echo e($ad['payment_terms']); ?></div></div><?php endif; ?>
            </div>
          </div>

          <!-- CONTACT PERSONS -->
          <div class="section" id="section-contacts">
            <div class="section-header" onclick="toggleSection('contacts')">
              <span class="section-title">Contact Persons</span>
              <div class="section-actions" onclick="event.stopPropagation();">
                <button class="section-add-btn" onclick="openCpModal()" title="Add Contact Person">+</button>
                <span class="section-chevron" id="chev-contacts">▲</span>
              </div>
            </div>
            <div class="section-body" id="body-contacts">
              <?php $contacts = $ad['contact_persons'] ?? []; ?>
              <div id="cp-list">
                <?php if(count($contacts) > 0): ?>
                  <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $cp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php $isPrimary = ($idx === $primaryIdx); ?>
                  <div class="cp-list-item">
                    <div>
                      <div class="cp-list-name">
                        <?php echo e(trim(($cp['salutation'] ?? '').' '.($cp['first_name'] ?? '').' '.($cp['last_name'] ?? ''))); ?>

                        <?php if($isPrimary): ?><span class="primary-badge">★ Primary</span><?php endif; ?>
                      </div>
                      <div class="cp-list-meta">
                        <?php echo e($cp['email'] ?? ''); ?>

                        <?php if(!empty($cp['work_phone'])): ?> &nbsp;·&nbsp; <?php echo e($cp['work_phone']); ?> <?php endif; ?>
                        <?php if(!empty($cp['mobile'])): ?> &nbsp;·&nbsp; <?php echo e($cp['mobile']); ?> <?php endif; ?>
                      </div>
                    </div>
                    <div class="cp-gear-wrap">
                      <button class="cp-gear-btn" onclick="toggleRowGear(event, <?php echo e($idx); ?>)">⚙</button>
                      <div class="cp-gear-menu" id="rowGear<?php echo e($idx); ?>">
                        <div class="cp-gear-menu-item" onclick="openEditCp(<?php echo e($idx); ?>); closeAllGears();"><span>✏️</span> Edit</div>
                        <?php if(!$isPrimary): ?>
                        <div class="cp-gear-menu-item primary-action" onclick="markPrimary(<?php echo e($idx); ?>); closeAllGears();"><span>★</span> Mark as Primary</div>
                        <?php endif; ?>
                        <div class="cp-gear-menu-sep"></div>
                        <div class="cp-gear-menu-item danger" onclick="openDelConfirm(<?php echo e($idx); ?>); closeAllGears();"><span>🗑️</span> Delete</div>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                  <div class="empty-msg" id="cp-empty">No contact persons found.</div>
                <?php endif; ?>
              </div>
              <div class="portal-promo" style="margin-top:12px;">
                <div class="portal-promo-icon">🖼️</div>
                <p>Customer Portal allows your customers to keep track of all the transactions between them and your business. <a href="#">Learn More</a></p>
              </div>
            </div>
          </div>

        </div><!-- end detail-left -->

        <div class="detail-right">
          <div class="whats-next-card">
            <div class="whats-next-icon">✦</div>
            <div class="whats-next-text">
              <strong>WHAT'S NEXT?</strong>
              <p>Create an invoice and send it to your customer.</p>
            </div>
            <div class="whats-next-actions">
              <button class="btn-new-invoice">New Invoice</button>
              <span class="three-dots">⋮</span>
            </div>
          </div>
          <div class="info-label">Payment due period</div>
          <div class="info-value"><?php echo e($ad['payment_terms'] ?? 'Due on Receipt'); ?></div>
          <div class="receivables-title">Receivables</div>
          <table class="receivables-table">
            <thead><tr><th>Currency</th><th>Outstanding<br>Receivables</th><th>Unused Credits</th></tr></thead>
            <tbody>
              <tr>
                <td><?php echo e(($ad['currency'] ?? 'INR') === 'INR' ? 'INR- Indian Rupee' : ($ad['currency'] ?? 'INR')); ?></td>
                <td>₹0.00</td><td>₹0.00</td>
              </tr>
            </tbody>
          </table>
          <div class="ship-info" style="margin-top:14px;">
            Items to be packed: <span class="orange">0</span> &nbsp; Items to be shipped: <span class="orange">0</span>
          </div>
        </div>
      </div><!-- end split-view overview -->

      <!-- ══ COMMENTS TAB ══ -->
      <div id="tab-comments" style="display:none;flex:1;overflow:hidden;">
        <div class="comments-wrap">

          <!-- Editor box -->
          <div class="comment-editor-box">
            <div class="comment-toolbar">
              <button class="toolbar-btn" id="btn-bold"      onclick="execCmd('bold')"      title="Bold"><b>B</b></button>
              <button class="toolbar-btn" id="btn-italic"    onclick="execCmd('italic')"    title="Italic"><i>I</i></button>
              <button class="toolbar-btn" id="btn-underline" onclick="execCmd('underline')" title="Underline"><u>U</u></button>
            </div>
            <div class="comment-editor"
                 id="commentEditor"
                 contenteditable="true"
                 data-placeholder="Write a comment..."></div>
            <div class="comment-editor-footer">
              <button class="btn-add-comment" id="btnAddComment" onclick="submitComment()">Add Comment</button>
            </div>
          </div>

          <!-- All Comments -->
          <div class="comments-section-title">
            <span class="comments-section-label">ALL COMMENTS</span>
            <span class="comments-count-badge" id="commentsCount"><?php echo e(count($comments)); ?></span>
          </div>

          <div id="commentsList">
            <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
             $initial = strtoupper(substr($cm['user_name'] ?? 'U', 0, 1));
             $timeStr = $cm['created_at'] ?? '';
            ?>
            <div class="comment-item" id="comment-<?php echo e($cm['id']); ?>">
              <div class="comment-avatar"><?php echo e($initial); ?></div>
              <div class="comment-body">
                <div class="comment-meta">
                  <span class="comment-author"><?php echo e($cm['user_name'] ?? 'User'); ?></span>
                  <span class="comment-time"><?php echo e($timeStr); ?></span>
                </div>
                <div class="comment-content-box">
                <?php echo $cm['content']; ?>

                  <button class="comment-del-btn" onclick="deleteComment('<?php echo e($cm['id']); ?>')"title="Delete">🗑</button>
                </div>
              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="comments-empty" id="commentsEmpty">
              <div class="comments-empty-icon">💬</div>
              <div>No comments yet. Be the first to add one!</div>
            </div>
            <?php endif; ?>
          </div>

        </div>
      </div>

      <div id="tab-transactions" style="display:none;padding:24px 18px;color:#888;font-size:13px;">No transactions found.</div>
      <div id="tab-statement"    style="display:none;padding:24px 18px;color:#888;font-size:13px;">Statement will appear here.</div>


      <!-- ══ HISTORY TAB ══ -->
<div id="tab-history" style="display:none;flex:1;overflow-y:auto;">
  <div style="padding:24px 28px;max-width:860px;">

    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
      <span style="font-size:11px;font-weight:700;color:#555;letter-spacing:0.8px;text-transform:uppercase;">ACTIVITY HISTORY</span>
      <span style="background:#1a73e8;color:#fff;font-size:11px;font-weight:700;padding:1px 7px;border-radius:10px;"><?php echo e($histories->count()); ?></span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
   <?php
  $actionColors = [
    'create'          => '#22c55e',
    'update'          => '#f97316',
    'delete'          => '#e53935',
    'comment_added'   => '#8b5cf6',
    'comment_deleted' => '#ec4899',
  ];
  $actionLabels = [
    'create'          => 'Created',
    'update'          => 'Updated',
    'delete'          => 'Deleted',
    'comment_added'   => 'Comment Added',
    'comment_deleted' => 'Comment Deleted',
  ];
      $color = $actionColors[$h->action] ?? '#888';
      $label = $actionLabels[$h->action] ?? ucfirst($h->action);
      $initial = strtoupper(substr($h->user->name ?? 'U', 0, 1));
    ?>

    <div style="display:flex;gap:12px;margin-bottom:18px;">
      <!-- Avatar -->
      <div style="width:32px;height:32px;border-radius:50%;background:#e8f0fe;color:#1a73e8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;margin-top:2px;">
        <?php echo e($initial); ?>

      </div>

      <div style="flex:1;">
        <!-- Meta -->
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
          <span style="font-size:13px;font-weight:600;color:#222;"><?php echo e($h->user->name ?? 'System'); ?></span>
          <span style="background:<?php echo e($color); ?>22;color:<?php echo e($color); ?>;font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px;"><?php echo e($label); ?></span>
          <span style="font-size:12px;color:#888;"><?php echo e($h->created_at->setTimezone('Asia/Kolkata')->format('d/m/Y h:i A')); ?></span>        </div>

        <!-- Changes box -->
        <div style="background:#f8f9fc;border:1px solid #e8eaf0;border-radius:6px;padding:10px 14px;font-size:12.5px;">
          <?php if($h->action === 'create'): ?>
            <span style="color:#22c55e;">✓ Customer created</span>
            <?php if(!empty($h->new_data['display_name'])): ?>
              — <strong><?php echo e($h->new_data['display_name']); ?></strong>
            <?php endif; ?>

          <?php elseif($h->action === 'delete'): ?>
            <span style="color:#e53935;">✗ Customer deleted</span>

          <?php else: ?>
            
            <?php
              $fieldLabels = [
                'display_name'                        => 'Display Name',
                'email'                               => 'Email',
                'phone_number'                        => 'Phone',
                'company_name'                        => 'Company Name',
                'customer_type'                       => 'Customer Type',
                'pan'                                 => 'PAN',
                'remarks'                             => 'Remarks',
                'additional_datas.language'           => 'Language',
                'additional_datas.currency'           => 'Currency',
                'additional_datas.payment_terms'      => 'Payment Terms',
                'additional_datas.enable_portal'      => 'Portal Status',
                'additional_datas.website'            => 'Website',
                'additional_datas.department'         => 'Department',
                'additional_datas.designation'        => 'Designation',
                'additional_datas.twitter'            => 'Twitter',
                'additional_datas.skype'              => 'Skype',
                'additional_datas.mobile'             => 'Mobile',
                'common_address.billing.street1'      => 'Billing Street 1',
                'common_address.billing.city'         => 'Billing City',
                'common_address.billing.state'        => 'Billing State',
                'common_address.billing.pincode'      => 'Billing Pincode',
                'common_address.billing.country'      => 'Billing Country',
                'common_address.shipping.street1'     => 'Shipping Street 1',
                'common_address.shipping.city'        => 'Shipping City',
                'common_address.shipping.state'       => 'Shipping State',
                'common_address.shipping.pincode'     => 'Shipping Pincode',
                'common_address.shipping.country'     => 'Shipping Country',
              ];
            ?>

            <?php $__currentLoopData = $h->new_data ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $newVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $oldVal = $h->old_data[$key] ?? null;
              $label2 = $fieldLabels[$key] ?? str_replace(['additional_datas.','common_address.','.'], ['','','→'], $key);
            ?>
            <div style="margin-bottom:6px;padding-bottom:6px;border-bottom:1px solid #f0f0f0;">
              <span style="color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.4px;"><?php echo e($label2); ?></span><br>
              <?php if($oldVal !== null): ?>
                <span style="color:#e53935;font-size:12px;"><?php echo e(is_bool($oldVal) ? ($oldVal?'Yes':'No') : $oldVal); ?></span>
                <span style="color:#888;margin:0 4px;">→</span>
              <?php endif; ?>
              <span style="color:#22c55e;font-size:12px;"><?php echo e(is_bool($newVal) ? ($newVal?'Yes':'No') : $newVal); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div style="text-align:center;padding:40px 20px;color:#aaa;font-size:13px;">
      <div style="font-size:36px;margin-bottom:10px;">📋</div>
      <div>No history found for this customer.</div>
    </div>
    <?php endif; ?>

  </div>
</div>
    </div><!-- end main-content -->

    <div class="right-sidebar">
      <div class="rs-icon orange">?</div><div class="rs-separator"></div>
      <div class="rs-icon">📝</div><div class="rs-icon active">📺</div>
      <div class="rs-icon">💬</div><div class="rs-icon">📋</div><div class="rs-icon">⚙️</div>
    </div>
  </div>
</div>

<!-- ADDRESS PANEL -->
<div class="overlay" id="overlay" onclick="closeAddressPanel()"></div>
<div class="address-panel" id="addressPanel">
  <div class="ap-header">
    <span class="ap-title" id="apTitle">Billing Address</span>
    <button class="ap-close" onclick="closeAddressPanel()">×</button>
  </div>
  <div class="ap-body">
    <input type="hidden" id="addressType" value="billing">
    <div class="ap-form-row"><label>Attention</label><input type="text" id="ap_attention"></div>
    <div class="ap-form-row"><label>Country/Region</label>
      <select id="ap_country"><option value="">Select</option><option>India</option><option>USA</option><option>UK</option><option>Singapore</option><option>UAE</option></select>
    </div>
    <div class="ap-form-row"><label>Address</label>
      <textarea id="ap_street1" placeholder="Street 1"></textarea>
      <textarea id="ap_street2" placeholder="Street 2" style="margin-top:8px;"></textarea>
    </div>
    <div class="ap-form-row"><label>City</label><input type="text" id="ap_city"></div>
    <div class="ap-form-2col">
      <div class="ap-form-row" style="margin-bottom:0;"><label>State</label>
        <select id="ap_state"><option value="">Select</option><option>Tamil Nadu</option><option>Karnataka</option><option>Maharashtra</option><option>Delhi</option><option>Gujarat</option><option>Rajasthan</option></select>
      </div>
      <div class="ap-form-row" style="margin-bottom:0;"><label>Pin Code</label><input type="text" id="ap_pincode"></div>
    </div>
    <div class="ap-form-row" style="margin-top:14px;"><label>Phone</label>
      <div class="ap-phone-row">
        <select class="ap-phone-code"><option>+91</option><option>+1</option><option>+44</option></select>
        <input type="text" id="ap_phone" class="ap-phone-num">
      </div>
    </div>
    <div class="ap-form-row"><label>Fax Number</label><input type="text" id="ap_fax"></div>
  </div>
  <div class="ap-footer">
    <button class="btn-save-ap" onclick="saveAddress()">Save</button>
    <button class="btn-cancel-ap" onclick="closeAddressPanel()">Cancel</button>
  </div>
</div>

<!-- CONTACT PERSON MODAL -->
<div class="cp-modal-overlay" id="cpModalOverlay">
  <div class="cp-modal">
    <div class="cp-modal-header">
      <span class="cp-modal-title" id="cpModalTitle">Add Contact Person</span>
      <button class="cp-modal-close" onclick="closeCpModal()">×</button>
    </div>
    <div id="cpAlert" class="cp-alert"></div>
    <div class="cp-modal-body">
      <div class="cp-form-main">
        <input type="hidden" id="cp_edit_idx" value="-1">
        <div class="cp-form-row">
          <div class="cp-form-label">Name</div>
          <div style="flex:1;display:flex;gap:8px;flex-wrap:nowrap;">
            <select id="cp_salutation" style="width:115px;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;-webkit-appearance:none;appearance:none;background:#fff url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%2712%27 viewBox=%270 0 12 12%27%3E%3Cpath d=%27M6 8L1 3h10z%27 fill=%27%23666%27/%3E%3C/svg%3E') no-repeat right 8px center;padding-right:22px;">
              <option value="">Salutation</option><option>Mr.</option><option>Mrs.</option><option>Ms.</option><option>Miss.</option><option>Dr.</option>
            </select>
            <input type="text" id="cp_first_name" placeholder="First Name" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
            <input type="text" id="cp_last_name" placeholder="Last Name" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
          </div>
        </div>
        <div class="cp-form-row">
          <div class="cp-form-label">Email Address</div>
          <div style="flex:1;"><input type="email" id="cp_email" class="cp-input-full"></div>
        </div>
        <div class="cp-form-row">
          <div class="cp-form-label">Phone</div>
          <div style="flex:1;">
            <div class="cp-phone-wrap">
              <select class="cp-phone-code" id="cp_work_code"><option>+91</option><option>+1</option><option>+44</option><option>+971</option><option>+65</option></select>
              <input type="text" id="cp_work_phone" class="cp-phone-num" placeholder="Work Phone">
            </div>
            <div class="cp-phone-wrap">
              <select class="cp-phone-code" id="cp_mobile_code"><option>+91</option><option>+1</option><option>+44</option><option>+971</option><option>+65</option></select>
              <input type="text" id="cp_mobile" class="cp-phone-num" placeholder="Mobile">
            </div>
          </div>
        </div>
        <div class="cp-form-row">
          <div class="cp-form-label">Skype Name/Number</div>
          <div style="flex:1;" class="cp-skype-wrap">
            <div class="cp-skype-icon">S</div>
            <input type="text" id="cp_skype" class="cp-input-full" placeholder="Skype Name/Number">
          </div>
        </div>
        <div class="cp-form-row">
          <div class="cp-form-label">Other Details</div>
          <div style="flex:1;display:flex;gap:8px;">
            <input type="text" id="cp_designation" placeholder="Designation" style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
            <input type="text" id="cp_department"  placeholder="Department"  style="flex:1;padding:7px 10px;border:1px solid #d0d0d0;border-radius:4px;font-size:13px;outline:none;">
          </div>
        </div>
      </div>
      <div class="cp-img-panel" onclick="document.getElementById('cp_img_input').click()">
        <input type="file" id="cp_img_input" accept="image/*" style="display:none;" onchange="previewCpImage(this)">
        <div id="cp_img_preview" style="display:none;width:100%;text-align:center;">
          <img id="cp_img_tag" style="max-width:100%;max-height:130px;border-radius:6px;object-fit:contain;">
        </div>
        <div id="cp_img_placeholder" style="display:flex;flex-direction:column;align-items:center;gap:10px;">
          <div class="cp-img-upload-icon">⬆</div>
          <p><strong>Drag &amp; Drop Profile Image</strong><br>Supported Files: jpg, jpeg, png, gif, bmp<br>Maximum File Size: 5MB</p>
          <a>Upload File</a>
        </div>
      </div>
    </div>
    <div class="cp-portal-wrap">
      <input type="checkbox" id="cp_portal_access">
      <div class="cp-portal-text">
        <strong>Enable portal access</strong><br>
        This customer will be able to see all their transactions with your organization by logging in to the portal using their email address. <a href="#">Learn More</a>
      </div>
    </div>
    <div class="cp-modal-footer">
      <button class="cp-save-btn" id="cpSaveBtn" onclick="saveCpModal()">Save</button>
      <button class="cp-cancel-btn" onclick="closeCpModal()">Cancel</button>
    </div>
  </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="del-overlay" id="delOverlay">
  <div class="del-modal">
    <div class="del-modal-icon">🗑️</div>
    <div class="del-modal-title">Delete Contact Person</div>
    <div class="del-modal-msg">Are you sure you want to delete this contact person?<br>This action cannot be undone.</div>
    <div class="del-modal-actions">
      <button class="del-confirm-btn" onclick="confirmDel()">Delete</button>
      <button class="del-cancel-btn" onclick="closeDelConfirm()">Cancel</button>
    </div>
  </div>
</div>

<script>
var CSRF        = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var billingData  = <?php echo json_encode($customer->common_address['billing']  ?? [], 15, 512) ?>;
var shippingData = <?php echo json_encode($customer->common_address['shipping'] ?? [], 15, 512) ?>;
var customerId   = <?php echo e($customer->id); ?>;
var cpData       = <?php echo json_encode($contacts, 15, 512) ?>;
var delIdx       = -1;

/* ── TABS ── */
function showTab(id, el) {
  ['overview','comments','transactions','statement','history'].forEach(function(t) {
    var e = document.getElementById('tab-'+t);
    if (!e) return;
    e.style.display = 'none';
  });
  var target = document.getElementById('tab-'+id);
  if (target) target.style.display = (id === 'overview' || id === 'comments') ? 'flex' : 'block';
  document.querySelectorAll('.tab').forEach(function(t){ t.classList.remove('active'); });
  el.classList.add('active');
}

/* ── SECTIONS ── */
function toggleSection(id) {
  var body = document.getElementById('body-'+id);
  var chev = document.getElementById('chev-'+id);
  if (!body) return;
  var shown = body.style.display !== 'none';
  body.style.display = shown ? 'none' : 'block';
  chev.textContent   = shown ? '▼' : '▲';
}

/* ── GEAR MENUS ── */
function toggleCardGear(e) {
  e.stopPropagation();
  var m = document.getElementById('cardGearMenu');
  var isOpen = m.classList.contains('open');
  closeAllGears();
  if (!isOpen) m.classList.add('open');
}
function toggleRowGear(e, idx) {
  e.stopPropagation();
  closeAllGears();
  var m = document.getElementById('rowGear' + idx);
  if (m) m.classList.add('open');
}
function closeAllGears() {
  document.querySelectorAll('.cp-gear-menu.open').forEach(function(m){ m.classList.remove('open'); });
}
document.addEventListener('click', closeAllGears);

/* ── ADDRESS ── */
function openAddressPanel(type) {
  var isB = type === 'billing';
  document.getElementById('apTitle').textContent = isB ? 'Billing Address' : 'Shipping Address';
  document.getElementById('addressType').value   = type;
  var data = isB ? billingData : shippingData;
  ['attention','street1','street2','city','pincode','phone','fax'].forEach(function(f) {
    var el = document.getElementById('ap_'+f); if (el) el.value = data[f] || '';
  });
  var s = document.getElementById('ap_state');   if (s) s.value = data.state   || '';
  var c = document.getElementById('ap_country'); if (c) c.value = data.country || '';
  document.getElementById('overlay').classList.add('open');
  document.getElementById('addressPanel').classList.add('open');
}
function closeAddressPanel() {
  document.getElementById('overlay').classList.remove('open');
  document.getElementById('addressPanel').classList.remove('open');
}
async function saveAddress() {
  var type = document.getElementById('addressType').value;
  var data = { address_type:type, attention:document.getElementById('ap_attention').value, country:document.getElementById('ap_country').value, street1:document.getElementById('ap_street1').value, street2:document.getElementById('ap_street2').value, city:document.getElementById('ap_city').value, state:document.getElementById('ap_state').value, pincode:document.getElementById('ap_pincode').value, phone:document.getElementById('ap_phone').value, fax:document.getElementById('ap_fax').value };
  try {
    var res = await fetch('/customers/'+customerId+'/address', { method:'PUT', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    var j   = await res.json();
    if (j.success) { type==='billing'?billingData=data:shippingData=data; updateAddressDisplay(type,data); closeAddressPanel(); showToast('Address saved!'); }
    else alert(j.message||'Failed.');
  } catch(e) { alert('Network error.'); }
}
function updateAddressDisplay(type, data) {
  document.querySelectorAll('.address-block').forEach(function(block) {
    var label = block.querySelector('.address-label'); if (!label) return;
    var match = (label.textContent.trim()==='Billing Address'&&type==='billing')||(label.textContent.trim()==='Shipping Address'&&type==='shipping');
    if (!match) return;
    var parts=[]; if(data.attention)parts.push(data.attention);
    var st=[data.street1,data.street2].filter(Boolean).join(', '); if(st)parts.push(st);
    var cs=[data.city,data.state].filter(Boolean).join(', '); if(data.pincode)cs+=(cs?' – ':'')+data.pincode; if(cs)parts.push(cs);
    if(data.country)parts.push(data.country);
    block.querySelector('.address-value').innerHTML = parts.join('<br>') || 'No Address';
  });
}

/* ══ CONTACT PERSONS ══ */
function openCpModal() {
  document.getElementById('cpModalTitle').textContent = 'Add Contact Person';
  document.getElementById('cp_edit_idx').value = '-1';
  resetCpForm();
  document.getElementById('cpModalOverlay').classList.add('open');
  document.getElementById('cp_first_name').focus();
}
function openEditCp(idx) {
  var cp = cpData[idx]; if (!cp) return;
  document.getElementById('cpModalTitle').textContent = 'Edit Contact Person';
  document.getElementById('cp_edit_idx').value = idx;
  resetCpForm();
  document.getElementById('cp_salutation').value  = cp.salutation  || '';
  document.getElementById('cp_first_name').value  = cp.first_name  || '';
  document.getElementById('cp_last_name').value   = cp.last_name   || '';
  document.getElementById('cp_email').value        = cp.email       || '';
  document.getElementById('cp_skype').value        = cp.skype       || '';
  document.getElementById('cp_designation').value = cp.designation || '';
  document.getElementById('cp_department').value  = cp.department  || '';
  document.getElementById('cp_portal_access').checked = cp.portal_access || false;
  function parsePhone(str, codeId, numId) {
    if (!str) return;
    var p = str.trim().split(' ');
    if (p.length >= 2) { document.getElementById(codeId).value = p[0]; document.getElementById(numId).value = p.slice(1).join(' '); }
    else document.getElementById(numId).value = str;
  }
  parsePhone(cp.work_phone, 'cp_work_code', 'cp_work_phone');
  parsePhone(cp.mobile,     'cp_mobile_code', 'cp_mobile');
  document.getElementById('cpModalOverlay').classList.add('open');
}
function closeCpModal() {
  document.getElementById('cpModalOverlay').classList.remove('open');
  resetCpForm();
}
function resetCpForm() {
  ['cp_first_name','cp_last_name','cp_email','cp_work_phone','cp_mobile','cp_skype','cp_designation','cp_department'].forEach(function(id){
    var el=document.getElementById(id); if(el)el.value='';
  });
  document.getElementById('cp_salutation').value='';
  document.getElementById('cp_work_code').value='+91';
  document.getElementById('cp_mobile_code').value='+91';
  document.getElementById('cp_portal_access').checked=false;
  document.getElementById('cp_img_preview').style.display='none';
  document.getElementById('cp_img_placeholder').style.display='flex';
  document.getElementById('cp_img_tag').src='';
  var a=document.getElementById('cpAlert'); a.style.display='none'; a.textContent='';
  var b=document.getElementById('cpSaveBtn'); b.textContent='Save'; b.disabled=false;
}
function cpShowAlert(msg, type) {
  var el=document.getElementById('cpAlert');
  el.style.display='block';
  el.style.background=type==='error'?'#fee2e2':'#d4edda';
  el.style.color=type==='error'?'#991b1b':'#155724';
  el.style.border='1px solid '+(type==='error'?'#fca5a5':'#c3e6cb');
  el.textContent=msg;
  el.scrollIntoView({behavior:'smooth',block:'nearest'});
}
function saveCpModal() {
  var fn=document.getElementById('cp_first_name').value.trim();
  if(!fn){cpShowAlert('First name is required.','error');document.getElementById('cp_first_name').focus();return;}
  var editIdx=parseInt(document.getElementById('cp_edit_idx').value);
  var isEdit=editIdx>=0;
  var data={salutation:document.getElementById('cp_salutation').value,first_name:fn,last_name:document.getElementById('cp_last_name').value.trim(),email:document.getElementById('cp_email').value.trim(),work_code:document.getElementById('cp_work_code').value,work_phone:document.getElementById('cp_work_phone').value.trim(),mobile_code:document.getElementById('cp_mobile_code').value,mobile:document.getElementById('cp_mobile').value.trim(),skype:document.getElementById('cp_skype').value.trim(),designation:document.getElementById('cp_designation').value.trim(),department:document.getElementById('cp_department').value.trim(),portal_access:document.getElementById('cp_portal_access').checked};
  var btn=document.getElementById('cpSaveBtn'); btn.textContent='Saving...'; btn.disabled=true;
  var url=isEdit?'/customers/'+customerId+'/contact-persons/'+editIdx:'/customers/'+customerId+'/contact-persons';
  fetch(url,{method:isEdit?'PUT':'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify(data)})
  .then(function(r){return r.json();})
  .then(function(res){
    btn.textContent='Save';btn.disabled=false;
    if(res.success){if(isEdit){cpData[editIdx]=res.contact;showToast('Contact updated!');}else{cpData.push(res.contact);showToast('Contact added!');}closeCpModal();renderCpList();}
    else cpShowAlert(res.message||'Save failed.','error');
  }).catch(function(){btn.textContent='Save';btn.disabled=false;cpShowAlert('Server error.','error');});
}
function markPrimary(idx) {
  fetch('/customers/'+customerId+'/contact-persons/'+idx+'/primary',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({})})
  .then(function(r){return r.json();})
  .then(function(res){
    if(res.success){cpData.forEach(function(cp,i){cp.is_primary=(i===idx);});renderCpList();showToast(res.contact_name+' is now Primary!');}
    else showToast(res.message||'Failed.',true);
  }).catch(function(){showToast('Server error.',true);});
}
function openDelConfirm(idx){delIdx=idx;document.getElementById('delOverlay').classList.add('open');}
function closeDelConfirm(){delIdx=-1;document.getElementById('delOverlay').classList.remove('open');}
function confirmDel(){
  if(delIdx<0){closeDelConfirm();return;}
  var idx=delIdx;closeDelConfirm();
  fetch('/customers/'+customerId+'/contact-persons/'+idx,{method:'DELETE',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}})
  .then(function(r){return r.json();})
  .then(function(res){if(res.success){cpData.splice(idx,1);renderCpList();showToast('Contact deleted!');}else showToast(res.message||'Failed.',true);})
  .catch(function(){showToast('Server error.',true);});
}
function renderCpList() {
  var list=document.getElementById('cp-list');if(!list)return;
  if(cpData.length===0){list.innerHTML='<div class="empty-msg">No contact persons found.</div>';return;}
  var pIdx=-1;cpData.forEach(function(cp,i){if(cp.is_primary)pIdx=i;});if(pIdx===-1)pIdx=0;
  var html='';
  cpData.forEach(function(cp,idx){
    var name=[cp.salutation,cp.first_name,cp.last_name].filter(Boolean).join(' ').trim();
    var isPri=(idx===pIdx);
    var meta=[];if(cp.email)meta.push(cp.email);if(cp.work_phone)meta.push(cp.work_phone);if(cp.mobile)meta.push(cp.mobile);
    html+='<div class="cp-list-item"><div><div class="cp-list-name">'+name+(isPri?'<span class="primary-badge">★ Primary</span>':'')+'</div><div class="cp-list-meta">'+meta.join(' · ')+'</div></div>'+
      '<div class="cp-gear-wrap"><button class="cp-gear-btn" onclick="toggleRowGear(event,'+idx+')">⚙</button>'+
      '<div class="cp-gear-menu" id="rowGear'+idx+'">'+
        '<div class="cp-gear-menu-item" onclick="openEditCp('+idx+');closeAllGears();">✏️ Edit</div>'+
        (!isPri?'<div class="cp-gear-menu-item primary-action" onclick="markPrimary('+idx+');closeAllGears();">★ Mark as Primary</div>':'')+
        '<div class="cp-gear-menu-sep"></div>'+
        '<div class="cp-gear-menu-item danger" onclick="openDelConfirm('+idx+');closeAllGears();">🗑️ Delete</div>'+
      '</div></div></div>';
  });
  list.innerHTML=html;
  updateContactCard(pIdx);
}
function updateContactCard(pIdx) {
  var nameEl=document.getElementById('cardName');
  var emailEl=document.getElementById('cardEmail');
  if(!nameEl||!emailEl)return;
  if(cpData.length===0||pIdx<0){nameEl.innerHTML='<?php echo e($customer->display_name); ?>';emailEl.textContent='<?php echo e($customer->email ?? ""); ?>';return;}
  var cp=cpData[pIdx];
  var name=[cp.salutation,cp.first_name,cp.last_name].filter(Boolean).join(' ').trim();
  nameEl.innerHTML=name+'<span class="primary-badge">★ Primary</span>';
  var meta=[];if(cp.email)meta.push(cp.email);if(cp.work_phone)meta.push(cp.work_phone);if(cp.mobile)meta.push(cp.mobile);
  emailEl.textContent=meta.join(' · ');
  var gearMenu=document.getElementById('cardGearMenu');
  if(gearMenu){gearMenu.innerHTML='<div class="cp-gear-menu-item" onclick="openEditCp('+pIdx+');closeAllGears();">✏️ Edit</div><div class="cp-gear-menu-sep"></div><div class="cp-gear-menu-item danger" onclick="openDelConfirm('+pIdx+');closeAllGears();">🗑️ Delete</div>';}
}

/* ══ IMAGE PREVIEW ══ */
function previewCpImage(input) {
  if(input.files&&input.files[0]){
    var r=new FileReader();
    r.onload=function(e){document.getElementById('cp_img_tag').src=e.target.result;document.getElementById('cp_img_preview').style.display='block';document.getElementById('cp_img_placeholder').style.display='none';};
    r.readAsDataURL(input.files[0]);
  }
}

/* ══════════════════════════════════════
   COMMENTS
══════════════════════════════════════ */
/* Bold / Italic / Underline toolbar */
function execCmd(cmd) {
  document.getElementById('commentEditor').focus();
  document.execCommand(cmd, false, null);
  // Toggle active state on toolbar button
  var btn = document.getElementById('btn-'+cmd);
  if (btn) btn.classList.toggle('active');
}

/* Submit comment */
function submitComment() {
  var editor  = document.getElementById('commentEditor');
  var content = editor.innerHTML.trim();
  // Strip empty paragraphs / just <br>
  if (!content || content === '<br>' || content === '<div><br></div>') {
    showToast('Comment cannot be empty.', true);
    return;
  }

  var btn = document.getElementById('btnAddComment');
  btn.textContent = 'Saving...';
  btn.disabled    = true;

  fetch('/customers/' + customerId + '/comments', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ content: content })
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    btn.textContent = 'Add Comment';
    btn.disabled    = false;
    if (res.success) {
      editor.innerHTML = '';
      appendComment(res.comment);
      showToast('Comment added!');
    } else {
      showToast(res.message || 'Failed to save.', true);
    }
  })
  .catch(function() {
    btn.textContent = 'Add Comment';
    btn.disabled    = false;
    showToast('Server error.', true);
  });
}

/* Append new comment to DOM */
function appendComment(cm) {
  var empty = document.getElementById('commentsEmpty');
  if (empty) empty.remove();

  var list  = document.getElementById('commentsList');
  var initial = (cm.user_name || 'U').charAt(0).toUpperCase();

  var div = document.createElement('div');
  div.className = 'comment-item';
  div.id        = 'comment-' + cm.id;
  div.innerHTML =
    '<div class="comment-avatar">' + initial + '</div>' +
    '<div class="comment-body">' +
      '<div class="comment-meta">' +
        '<span class="comment-author">' + (cm.user_name || 'User') + '</span>' +
        '<span class="comment-dot">●</span>' +
        '<span class="comment-time">' + cm.created_at + '</span>' +
      '</div>' +
      '<div class="comment-content-box">' +
        cm.content +
        '<button class="comment-del-btn" onclick="deleteComment(' + cm.id + ')" title="Delete">🗑</button>' +
      '</div>' +
    '</div>';
  list.appendChild(div);

  // Update count badge
  var badge = document.getElementById('commentsCount');
  if (badge) badge.textContent = parseInt(badge.textContent || 0) + 1;
}

/* Delete comment */
function deleteComment(id) {
  if (!confirm('Delete this comment?')) return;
  fetch('/customers/' + customerId + '/comments/' + id, {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (res.success) {
      var el = document.getElementById('comment-' + id);
      if (el) el.remove();
      var badge = document.getElementById('commentsCount');
      if (badge) badge.textContent = Math.max(0, parseInt(badge.textContent || 0) - 1);
      var list = document.getElementById('commentsList');
      if (list && list.children.length === 0) {
        list.innerHTML = '<div class="comments-empty" id="commentsEmpty"><div class="comments-empty-icon">💬</div><div>No comments yet.</div></div>';
      }
      showToast('Comment deleted!');
    } else {
      showToast(res.message || 'Delete failed.', true);
    }
  })
  .catch(function() { showToast('Server error.', true); });
}

/* Close modals on overlay click */
document.getElementById('cpModalOverlay').addEventListener('click', function(e) { if(e.target===this)closeCpModal(); });
document.getElementById('delOverlay').addEventListener('click', function(e) { if(e.target===this)closeDelConfirm(); });

/* Toast */
function showToast(msg, isErr) {
  var d = document.createElement('div');
  d.className   = 'toast-msg ' + (isErr ? 'err' : 'ok');
  d.textContent = (isErr ? '✗ ' : '✓ ') + msg;
  document.body.appendChild(d);
  setTimeout(function() { d.remove(); }, 3000);
}
</script>
</body>
</html><?php /**PATH D:\MAMP\htdocs\femi_billing_11\resources\views/customers/show.blade.php ENDPATH**/ ?>