<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingHandleController;
use App\Http\Controllers\FieldCustomizationController;
use App\Http\Controllers\LockConfigurationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompositeItemController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserCategoryController;
use App\Http\Controllers\CustomerVendorController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\LcLayerController;
use App\Http\Controllers\AssemblyController;
use App\Http\Controllers\TransactionSeriesController;
use App\Http\Controllers\UserSubCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\PaymentsRecordController;
use App\Http\Controllers\ItemStockLedgerController;
use App\Http\Controllers\InvoiceSettingController;
use App\Http\Controllers\CommentsController;

// ===== HOME REDIRECT =====
Route::get('/', function () {
    return redirect()->route('products.index');
});

// ===== SETTINGS HANDLE ROUTES =====
Route::prefix('setting_handle')->name('setting_handle.')->group(function () {
    Route::get('/', [SettingHandleController::class, 'create'])->name('create');
    Route::post('/', [SettingHandleController::class, 'store'])->name('store');
    Route::delete('/', [SettingHandleController::class, 'destroy'])->name('destroy');
});

// ===== SIDEBAR SETTINGS ROUTES =====
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/organization', fn() => view('organization.index'))->name('organization.index');
    Route::get('/users-roles', fn() => view('users-roles.index'))->name('users.roles');
    Route::get('/taxes-compliance', fn() => view('taxes-compliance.index'))->name('taxes.compliance');
    Route::get('/setup-config', fn() => view('setup-config.index'))->name('setup.config');
    Route::get('/customization', fn() => view('customization.index'))->name('customization.index');
    Route::get('/automation', fn() => view('automation.index'))->name('automation.index');
    Route::get('/general', fn() => view('general-settings.index'))->name('general.settings');
    Route::get('/customers-vendors', fn() => view('customers-vendors.index'))->name('customers.vendors');
});

// ===== GENERAL REDIRECTS =====
Route::get('/general', fn() => redirect()->route('setting_handle.create'))->name('general');
Route::get('/record-locking', fn() => view('record-locking'))->name('record.locking');
Route::get('/custom-buttons', fn() => view('custom-buttons'))->name('custom.buttons');
Route::get('/related-lists', fn() => view('related-lists'))->name('related.lists');

// ===== FIELD CUSTOMIZATION ROUTES =====
Route::prefix('field_customization')->name('field_customization.')->group(function () {
    Route::get('/', [FieldCustomizationController::class, 'index'])->name('index');
    Route::get('/create', [FieldCustomizationController::class, 'create'])->name('create');
    Route::post('/', [FieldCustomizationController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FieldCustomizationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FieldCustomizationController::class, 'update'])->name('update');
    Route::delete('/{id}', [FieldCustomizationController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-status', [FieldCustomizationController::class, 'toggleStatus'])->name('toggle-status');
    Route::patch('/{id}/toggle-pdf', [FieldCustomizationController::class, 'togglePdf'])->name('toggle-pdf');
    Route::get('/{id}/access', [FieldCustomizationController::class, 'access'])->name('access');
    Route::post('/{id}/access', [FieldCustomizationController::class, 'updateAccess'])->name('updateAccess');
});

// ===== LOCK CONFIGURATION ROUTES =====
Route::prefix('lock_configuration')->name('lock_configuration.')->group(function () {
    Route::get('/', [LockConfigurationController::class, 'index'])->name('index');
    Route::get('/create', [LockConfigurationController::class, 'create'])->name('create');
    Route::post('/', [LockConfigurationController::class, 'store'])->name('store');
    Route::get('/{lockConfiguration}/edit', [LockConfigurationController::class, 'edit'])->name('edit');
    Route::put('/{lockConfiguration}', [LockConfigurationController::class, 'update'])->name('update');
    Route::delete('/{lockConfiguration}', [LockConfigurationController::class, 'destroy'])->name('destroy');
    Route::patch('/{lockConfiguration}/toggle', [LockConfigurationController::class, 'toggleStatus'])->name('toggle');
});

// ===== PRODUCT ROUTES =====
Route::resource('products', ProductController::class);
Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
Route::post('/products/{product}/opening-stock', [ProductController::class, 'saveOpeningStock'])->name('products.opening-stock');
Route::post('/products/set-variant', function(\Illuminate\Http\Request $request) {
    session(['current_variant' => $request->all()]);
    return response()->json(['success' => true]);
})->name('products.set.variant');
Route::post('/products/clear-variant', function () {
    session()->forget('current_variant');
    return response()->json(['success' => true]);
})->name('products.clear.variant');
Route::get('/products/{id}/history', [ProductController::class, 'history'])->name('products.history');

// ===== BRAND ROUTES =====
Route::get('/brands/list', [BrandController::class, 'getList'])->name('brands.list');
Route::resource('brands', BrandController::class)->except(['show', 'edit', 'update']);
Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');

// ===== LOCATION ROUTES =====
// Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
// Route::get('/locations/create', fn() => redirect()->route('locations.index'));
// Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->where('id', '[0-9]+')->name('locations.edit');
// Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
// Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
// Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

// ===== CATEGORY ROUTES =====
Route::get('/categories/list', [CategoryController::class, 'list']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// ===== CUSTOMER ROUTES =====

Route::get('/customers/used-locations', 
    [CustomerController::class, 'getUsedLocations'])
    ->name('customers.used-locations');

// ✅ THEN resource
Route::resource('customers', CustomerController::class);

// ✅ THEN nested routes
Route::put('/customers/{customer}/address', 
    [CustomerController::class, 'updateAddress'])
    ->name('customers.update_address');
Route::post('/customers/{customer}/contact-persons', 
    [CustomerController::class, 'storeContactPerson']);
Route::put('/customers/{customer}/contact-persons/{index}', 
    [CustomerController::class, 'updateContactPerson']);
Route::post('/customers/{customer}/contact-persons/{index}/primary', 
    [CustomerController::class, 'markContactPersonPrimary']);
Route::delete('/customers/{customer}/contact-persons/{index}', 
    [CustomerController::class, 'destroyContactPerson']);

Route::get('customers/used-locations', [CustomerController::class, 'getUsedLocations']);
Route::get('/admin/user-categories', function () {
    return view('admin.user-categories.index');
})->name('admin.user-categories.index');
// routes/web.php
Route::get('/customers/{customer}/panel-data', [CustomerController::class, 'panelData']);
Route::get('/customers/{id}/panel-data', [CustomerController::class, 'panelData']);

Route::prefix('user-categories')->group(function () {
    Route::get('/',                     [UserCategoryController::class, 'index']);  // ← ADD THIS
    Route::get('flat',                  [UserCategoryController::class, 'flat']);
    Route::get('used-layers',           [UserCategoryController::class, 'usedLayers']);
    Route::post('/',                    [UserCategoryController::class, 'store']);
    Route::get('{userCategory}',        [UserCategoryController::class, 'show']);
    Route::put('{userCategory}',        [UserCategoryController::class, 'update']);
    Route::delete('{userCategory}',     [UserCategoryController::class, 'destroy']);
});


// ===== CUSTOMER VENDOR PREFERENCE ROUTES =====
Route::get('/settings/preferences/contacts', [CustomerVendorController::class, 'create'])->name('customers-vendors.create');
Route::post('/settings/preferences/contacts', [CustomerVendorController::class, 'store'])->name('customers-vendors.store');


// ===== PRICE LIST ROUTES =====
Route::prefix('price-lists')->name('price-lists.')->group(function () {
    Route::get('/',           [PriceListController::class, 'index'])->name('index');
    Route::get('/create',     [PriceListController::class, 'create'])->name('create');
    Route::post('/',          [PriceListController::class, 'store'])->name('store');
    Route::get('/{id}/edit',  [PriceListController::class, 'edit'])->name('edit');
    Route::put('/{id}',       [PriceListController::class, 'update'])->name('update');
    Route::delete('/{id}',    [PriceListController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/toggle-status', [PriceListController::class, 'toggleStatus'])->name('toggle-status');
});

Route::apiResource('price-list-categories', PriceListController::class)
     ->except(['show','create','edit']);

Route::prefix('assign-location')->group(function () {
    Route::get('/',           [LcLayerController::class, 'index']);
    Route::get('/tree',       [LcLayerController::class, 'getTree']);
 
    // Layer
    Route::post('/add-layer',    [LcLayerController::class, 'addLayer']);
    Route::post('/edit-layer',   [LcLayerController::class, 'editLayer']);   // NEW
    Route::post('/delete-layer', [LcLayerController::class, 'deleteLayer']);
 
    // Value
    Route::post('/add-value',    [LcLayerController::class, 'addValue']);
    Route::post('/edit-value',   [LcLayerController::class, 'editValue']);   // NEW
    Route::post('/delete-value', [LcLayerController::class, 'deleteValue']);
});
    // routes/api.php

Route::prefix('composite-items')->name('composite-items.')->group(function () {

    Route::get('search-products', [CompositeItemController::class, 'searchProducts'])
        ->name('search-products');

    Route::post('{id}/restore', [CompositeItemController::class, 'restore'])
        ->name('restore');

    // CRUD
    Route::get('/',         [CompositeItemController::class, 'index'])->name('index');
    Route::get('/create',   [CompositeItemController::class, 'create'])->name('create');
    Route::post('/',        [CompositeItemController::class, 'store'])->name('store');
    Route::get('{id}',      [CompositeItemController::class, 'show'])->name('show');
    Route::get('{id}/edit', [CompositeItemController::class, 'edit'])->name('edit');
    Route::put('{id}',      [CompositeItemController::class, 'update'])->name('update');
    Route::delete('{id}',   [CompositeItemController::class, 'destroy'])->name('destroy');
    Route::get('composite-items/{compositeItem}/edit', [CompositeItemController::class, 'edit'])->name('composite-items.edit');
Route::put('composite-items/{compositeItem}', [CompositeItemController::class, 'update'])->name('composite-items.update');

});

Route::resource('assemblies', AssemblyController::class);
// Assemblies
Route::get('/assemblies',                  [App\Http\Controllers\AssemblyController::class, 'index'])->name('assemblies.index');
Route::get('/assemblies/create',           [App\Http\Controllers\AssemblyController::class, 'create'])->name('assemblies.create');
Route::post('/assemblies',                 [App\Http\Controllers\AssemblyController::class, 'store'])->name('assemblies.store');
Route::get('/assemblies/{id}',             [App\Http\Controllers\AssemblyController::class, 'show'])->name('assemblies.show');
Route::delete('/assemblies/{id}',          [App\Http\Controllers\AssemblyController::class, 'destroy'])->name('assemblies.destroy');
Route::get('assemblies/{assembly}/edit', [AssemblyController::class, 'edit'])->name('assemblies.edit');
Route::put('assemblies/{assembly}',      [AssemblyController::class, 'update'])->name('assemblies.update');

// API — composite item details fetch (for form)
Route::get('/assemblies/composite-item/{id}', [App\Http\Controllers\AssemblyController::class, 'getCompositeItem'])->name('assemblies.composite-item');

Route::get('/lc-tree', [LcLayerController::class, 'getTree']);

Route::post('/locations/{location}/attach-series', [TransactionSeriesController::class, 'attachToLocation'])
    ->name('locations.attach-series');
 
// Locations CRUD (if not already defined)
Route::prefix('locations')->group(function () {
    Route::get('/',             [LocationController::class, 'index'])  ->name('locations.index');
    Route::post('/',            [LocationController::class, 'store'])  ->name('locations.store');
    Route::get('/{location}/edit',   [LocationController::class, 'edit'])   ->name('locations.edit');
    Route::put('/{location}',   [LocationController::class, 'update']) ->name('locations.update');
    Route::delete('/{location}',[LocationController::class, 'destroy'])->name('locations.destroy');
});
Route::prefix('transaction-series')->name('transaction-series.')->group(function () {
    // ⚠️ /list முதல்ல வரணும் — இல்லன்னா /{transactionSeries} அதை catch பண்ணிடும்
    Route::get('/list',                     [TransactionSeriesController::class, 'list'])   ->name('list');
    
    Route::get('/',                         [TransactionSeriesController::class, 'index'])  ->name('index');
    Route::get('/create',                   [TransactionSeriesController::class, 'create']) ->name('create');
    Route::post('/',                        [TransactionSeriesController::class, 'store'])  ->name('store');
    Route::get('/{transactionSeries}/edit', [TransactionSeriesController::class, 'edit'])  ->name('edit');
    Route::get('/{transactionSeries}',      [TransactionSeriesController::class, 'show'])  ->name('show');
    Route::put('/{transactionSeries}',      [TransactionSeriesController::class, 'update'])->name('update');
    Route::delete('/{transactionSeries}',   [TransactionSeriesController::class, 'destroy'])->name('destroy');
});
Route::get('composite-items/{id}/edit', [CompositeItemController::class, 'edit'])
     ->name('composite-items.edit');

Route::resource('price-lists', PriceListController::class);
Route::get('price-lists/{id}/history', [PriceListController::class, 'history'])
    ->name('price-lists.history');

    Route::prefix('user-sub-categories')->name('user_sub_categories.')->group(function () {
    Route::get('/',                     [UserSubCategoryController::class, 'index'])      ->name('index');
    Route::post('/',                    [UserSubCategoryController::class, 'store'])      ->name('store');
    Route::put('/{id}',                 [UserSubCategoryController::class, 'update'])     ->name('update');
    Route::delete('/{id}',              [UserSubCategoryController::class, 'destroy'])    ->name('destroy');
    Route::get('/by-category/{id}',     [UserSubCategoryController::class, 'byCategory'])->name('byCategory');
});
// ── Invoice Routes ── (specific routes FIRST, dynamic {id} LAST)
// ── Static routes FIRST (specific before dynamic) ──
Route::get('/invoices/location-stock',     [InvoiceController::class, 'getLocationStock']);
Route::get('/invoices/invoice-number',     [InvoiceController::class, 'getInvoiceNumber']);
Route::get('/invoices/price-list-rates',   [InvoiceController::class, 'getPriceListRates']);
Route::get('/invoices/category-locations', [InvoiceController::class, 'categoryLocations'])->name('invoices.category-locations');
Route::get('/invoices/customer-defaults',  [InvoiceController::class, 'getCustomerDefaults']);

// ── CRUD routes ──
Route::get('/invoices',        [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices',       [InvoiceController::class, 'store'])->name('invoices.store');

// ── Dynamic route LAST ──
Route::get('/invoices/{id}',           [InvoiceController::class, 'show'])->name('invoices.show');
Route::post('/invoices/{id}/comment',  [InvoiceController::class, 'addComment'])->name('invoices.comment');

Route::get('/invoices/{id}/payment', [InvoiceController::class, 'showPaymentForm'])
    ->name('invoices.payment.form');

Route::post('/invoices/{id}/payment', [InvoiceController::class, 'storePayment'])
    ->name('invoices.payment.store');
// ── Other ──
Route::get('/api/products/{id}', [InvoiceController::class, 'getProduct'])->name('products.get');

Route::get('/invoices/{id}/edit',   [InvoiceController::class, 'edit'])  ->name('invoices.edit');
Route::put('/invoices/{id}',        [InvoiceController::class, 'update'])->name('invoices.update');

//important one
Route::get('/stock-ledger', [ItemStockLedgerController::class, 'create']);

Route::get('/referrals',         [ReferralController::class, 'index']);
Route::post('/referrals',        [ReferralController::class, 'store']);
Route::put('/referrals/{id}',    [ReferralController::class, 'update']);
Route::delete('/referrals/{id}', [ReferralController::class, 'destroy']);

Route::get('/referrals',          [ReferralController::class, 'index']);
Route::post('/referrals',         [ReferralController::class, 'store']);
Route::put('/referrals/{id}',     [ReferralController::class, 'update']);
Route::delete('/referrals/{id}',  [ReferralController::class, 'destroy']);

Route::prefix('payments-records')->name('payments_records.')->group(function () {
    Route::get('/',                  [PaymentsRecordController::class, 'index'])               ->name('index');
    Route::get('/create',            [PaymentsRecordController::class, 'create'])              ->name('create');
    Route::post('/',                 [PaymentsRecordController::class, 'store'])               ->name('store');
    Route::get('/customers',         [PaymentsRecordController::class, 'getCustomers'])        ->name('get_customers');
    Route::get('/invoices',          [PaymentsRecordController::class, 'getInvoices'])         ->name('get_invoices');
    Route::get('/customer-defaults', [PaymentsRecordController::class, 'getCustomerDefaults']) ->name('get_customer_defaults');
    
    // ✅ இங்கே prefix already 'payments-records' — மீண்டும் போடாதீங்க
    Route::get('/customer-credit',   [PaymentsRecordController::class, 'getCustomerCredit'])   ->name('get_customer_credit');
    Route::post('/apply-credit',     [PaymentsRecordController::class, 'applyCredit'])         ->name('apply_credit');
    Route::post('/payments-records/apply-credit', [PaymentsRecordController::class, 'applyCredit']);
    });
    

Route::get('/settings/invoice',  [InvoiceSettingController::class, 'create'])->name('invoice_setting.create');
Route::post('/settings/invoice', [InvoiceSettingController::class, 'store'])->name('invoice_setting.store');
Route::delete('/settings/invoice/reset', [InvoiceSettingController::class, 'destroy'])->name('invoice_setting.destroy');

Route::get('/comments', [CommentsController::class, 'index'])
     ->name('comments.index');

Route::post('/{module}/{record_id}/comments', [CommentsController::class, 'store'])
     ->where('module', 'customers|products|invoices')
     ->where('record_id', '[0-9]+')
     ->name('comments.store');

Route::delete('/{module}/{record_id}/comments/{id}', [CommentsController::class, 'destroy'])
     ->where('module', 'customers|products|invoices')
     ->where('record_id', '[0-9]+')
     ->where('id', '[0-9]+')
     ->name('comments.destroy');

Route::get('/comment-settings/{module}',  [CommentsController::class, 'getSettings']);
Route::post('/comment-settings/{module}', [CommentsController::class, 'updateSettings']);
