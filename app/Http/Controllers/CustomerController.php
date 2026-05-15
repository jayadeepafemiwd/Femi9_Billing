<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // ================================================================
    //  VALIDATION RULES
    // ================================================================
    private function validationRules(): array
    {
        return [
            // ── Basic Info ────────────────────────────────────
            'customer_type'     => 'required|in:business,individual',
            'customer_category' => 'nullable|string|max:100',
           'customer_sub_category_id' => 'nullable|exists:user_sub_categories,id',
            'assign_location.layer_id'  => 'nullable|string',
            'assign_location.value_id'  => 'nullable|string',
            'assign_location.path'      => 'nullable|string|max:500',
            'assign_location.value_ids' => 'nullable|string',
            'salutation'        => 'nullable|string|max:10',
            'first_name'        => 'nullable|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'company_name'      => 'nullable|string|max:255',
            'display_name'      => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone_number'      => 'nullable|string|max:20',
            'mobile'            => 'nullable|string|max:20',

            // ── Other Details ─────────────────────────────────
            'pan'               => 'nullable|string|max:10',
            'language'          => 'nullable|string|max:50',
            'currency'          => 'nullable|string|max:10',
            'payment_terms'     => 'nullable|string|max:50',
            'enable_portal'     => 'nullable|boolean',
            'website'           => 'nullable|url|max:255',
            'department'        => 'nullable|string|max:100',
            'designation'       => 'nullable|string|max:100',
            'twitter'           => 'nullable|string|max:255',
            'skype'             => 'nullable|string|max:255',
            'facebook'          => 'nullable|string|max:255',

            // ── Billing Address ───────────────────────────────
            'billing.attention' => 'nullable|string|max:255',
            'billing.country'   => 'nullable|string|max:100',
            'billing.street1'   => 'nullable|string|max:255',
            'billing.street2'   => 'nullable|string|max:255',
            'billing.city'      => 'nullable|string|max:100',
            'billing.state'     => 'nullable|string|max:100',
            'billing.pincode'   => 'nullable|string|max:20',
            'billing.phone'     => 'nullable|string|max:20',
            'billing.fax'       => 'nullable|string|max:50',

            // ── Shipping Address ──────────────────────────────
            'shipping.attention' => 'nullable|string|max:255',
            'shipping.country'   => 'nullable|string|max:100',
            'shipping.street1'   => 'nullable|string|max:255',
            'shipping.street2'   => 'nullable|string|max:255',
            'shipping.city'      => 'nullable|string|max:100',
            'shipping.state'     => 'nullable|string|max:100',
            'shipping.pincode'   => 'nullable|string|max:20',
            'shipping.phone'     => 'nullable|string|max:20',
            'shipping.fax'       => 'nullable|string|max:50',

            // ── Contact Persons ───────────────────────────────
            'contact_persons'              => 'nullable|array',
            'contact_persons.*.salutation' => 'nullable|string|max:10',
            'contact_persons.*.first_name' => 'nullable|string|max:100',
            'contact_persons.*.last_name'  => 'nullable|string|max:100',
            'contact_persons.*.email'      => 'nullable|email|max:255',
            'contact_persons.*.work_phone' => 'nullable|string|max:20',
            'contact_persons.*.mobile'     => 'nullable|string|max:20',

            // ── Remarks ───────────────────────────────────────
            'remarks' => 'nullable|string',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'display_name.required'  => 'Display Name is required.',
            'customer_type.required' => 'Customer Type is required.',
            'customer_type.in'       => 'Customer Type must be business or individual.',
            'email.email'            => 'Please enter a valid email address.',
        ];
    }

    // ================================================================
    //  INDEX
    // ================================================================
    public function index(Request $request)
    {
        try {
            $customers = Customer::latest()->paginate(15);
            return view('customers.index', compact('customers'));
        } catch (\Exception $e) {
            Log::error('[CustomerController:INDEX] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load customers.');
        }
    }

    // ================================================================
    //  CREATE
    // ================================================================
 public function create()
{
    // ✅ category_name = 'general' use பண்ணு
    $settingRow = \App\Models\SettingHandle::where('category_name', 'general')->first();
    $config = $settingRow ? ($settingRow->config ?? []) : [];

    $customFields = \App\Models\AdditionalSetting::where('category_name', 'customers')
                    ->where('status', 'active')
                    ->get();

    $categories = \App\Models\UserCategory::orderBy('name')
                    ->get(['id', 'name', 'assign_fix_location', 'country_id', 'location_label']);

    return view('customers.create', compact('config', 'customFields', 'categories'));
}
    // ================================================================
    //  STORE
    // ================================================================
    public function store(Request $request)
    {
        $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        try {
            DB::beginTransaction();

            // ── 1. Full name ──────────────────────────────────────────
            $fullName = trim(
                ($request->first_name ?? '') . ' ' . ($request->last_name ?? '')
            ) ?: null;

            // ── 2. assign_location build (ONCE, here) ─────────────────
            $assignLocation = null;
            if ($request->filled('assign_location.value_id')) {
                $assignLocation = [
                    'layer_id'  => $request->input('assign_location.layer_id'),
                    'value_id'  => $request->input('assign_location.value_id'),
                    'path'      => $request->input('assign_location.path'),
                    'value_ids' => json_decode(
                                       $request->input('assign_location.value_ids', '[]'),
                                       true
                                   ) ?? [],
                ];
            }

            // ── 3. additional_datas JSON ──────────────────────────────
            $additionalDatas = [
                'salutation'      => $request->salutation,
                'first_name'      => $request->first_name,
                'last_name'       => $request->last_name,
                'mobile'          => $request->mobile,
                'language'        => $request->language,
                'currency'        => $request->currency,
                'payment_terms'   => $request->payment_terms,
                'enable_portal'   => (bool) $request->enable_portal,
                'website'         => $request->website,
                'department'      => $request->department,
                'designation'     => $request->designation,
                'twitter'         => $request->twitter,
                'skype'           => $request->skype,
                'facebook'        => $request->facebook,
                'contact_persons' => $this->sanitizeContactPersons(
                                         $request->contact_persons ?? []
                                     ),
                'custom_fields'   => $request->custom_fields ?? [],
                'remarks'         => ['note' => $request->remarks ?? null],
            ];

            // ── 4. common_address JSON ────────────────────────────────
            $commonAddress = [
                'billing'  => $this->sanitizeAddress($request->input('billing',  [])),
                'shipping' => $this->sanitizeAddress($request->input('shipping', [])),
            ];
             
            // ── 6. user_code generate ─────────────────────────────────
$userCode = null;
if ($request->filled('customer_category')) {
    $userCode = $this->generateUserCode($request->customer_category);
}
            // ── 5. Create Customer ────────────────────────────────────
            $customer = Customer::create([
                'customer_type'     => $request->customer_type,
                'customer_category' => $request->customer_category,
               'customer_sub_category_id' => $request->customer_sub_category_id ?: null,
                'name'              => $fullName,
                'user_code' => $userCode,
                'company_name'      => $request->company_name,
                'display_name'      => $request->display_name,
                'email'             => $request->email,
                'phone_number'      => $request->phone_number,
                'pan'               => $request->pan
                                           ? strtoupper(trim($request->pan))
                                           : null,
                'assign_location'   => $assignLocation,
                'additional_datas'  => $additionalDatas,
                'common_address'    => $commonAddress,
                'remarks'           => $request->remarks,
            ]);

            DB::commit();

            Log::info('[CustomerController:STORE] Customer created', [
                'id'           => $customer->id,
                'display_name' => $customer->display_name,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully.',
                    'data'    => $customer,
                ], 201);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[CustomerController:STORE] ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create customer.',
                    'error'   => config('app.debug') ? $e->getMessage() : 'Server error',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to create customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ================================================================
    //  SHOW
    // ================================================================
    public function show(Customer $customer)
{
    $customers = Customer::whereNull('deleted_at')
                    ->orderBy('display_name')->get();

    $comments = \App\Models\Comment::forModule('customer', $customer->id)
                    ->with('user:id,name')
                    ->latest()
                    ->get()
                    ->map(fn($c) => $c->toApiArray());

    $histories = \App\Models\History::where('module', 'customer')
                    ->where('record_id', $customer->id)
                    ->with('user')
                    ->latest()
                    ->get();

    return view('customers.show', compact('customer', 'customers', 'comments', 'histories'));
}


    // CustomerController
 public function panelData($id)
{
    $customer = Customer::findOrFail($id);

    // Parse JSON columns
    $additionalDatas = is_string($customer->additional_datas)
        ? json_decode($customer->additional_datas, true)
        : ($customer->additional_datas ?? []);

    // ✅ common_address parse
    $commonAddress = $customer->common_address ?? [];
    if (is_string($commonAddress)) {
        $commonAddress = json_decode($commonAddress, true) ?? [];
    }

    // ✅ Contacts — first_name + last_name join பண்ணு
   $contacts = collect($additionalDatas['contact_persons'] ?? [])
    ->map(fn($c) => [
        'name'           => trim(
                                ($c['salutation'] ?? '') . ' ' .
                                ($c['first_name'] ?? '') . ' ' .
                                ($c['last_name']  ?? '')
                            ) ?: '—',
        'email'          => $c['email']       ?? null,
        'phone'          => $c['work_phone']  ?? null,
        'mobile'         => $c['mobile']      ?? null,
        'designation'    => $c['designation'] ?? null,  // ✅ extra info
        'department'     => $c['department']  ?? null,
        'is_primary'     => (bool)($c['is_primary']    ?? false),
        'portal_enabled' => (bool)($c['portal_access'] ?? false),
    ])->values()->toArray();
    // ✅ Activity
    $activities = \App\Models\History::where('module', 'customer')
        ->where('record_id', $id)
        ->with('user')
        ->latest()
        ->take(20)
        ->get()
        ->map(function ($h) {
            return [
                'user'        => $h->user?->name ?? 'System',
                'time'        => $h->created_at->diffForHumans(),
                'description' => match($h->action) {
                    'create'          => 'Customer created',
                    'update'          => self::buildUpdateDescription($h->old_data, $h->new_data),
                    'delete'          => 'Customer deleted',
                    'comment_added'   => 'Comment added: "' . ($h->new_data['comment'] ?? '') . '"',
                    'comment_deleted' => 'Comment deleted',
                    default           => ucfirst($h->action),
                },
                'action' => $h->action,
            ];
        })->toArray();

        // ✅ Activity — customer's invoice history
$invoiceIds = \App\Models\Invoice::where('customer_id', $id)->pluck('id');

$activities = \App\Models\History::where('module', 'invoice')
    ->whereIn('record_id', $invoiceIds)
    ->with('user')
    ->latest()
    ->take(20)
    ->get()
    ->map(function ($h) {
        $invoiceNumber = \App\Models\Invoice::find($h->record_id)?->invoice_number ?? '';
        $new = is_array($h->new_data) ? $h->new_data : (json_decode($h->new_data ?? '{}', true) ?? []);

        return [
            'user'        => $h->user?->name ?? 'System',
            'time'        => $h->created_at->diffForHumans(),
            'description' => match($h->action) {
                'create'         => '🧾 Invoice <strong>' . $invoiceNumber . '</strong> created for ₹' . number_format($new['grand_total'] ?? 0, 2),
                'status_changed' => '🔄 ' . $invoiceNumber . ': Status → <strong>' . ($new['status'] ?? '?') . '</strong>',
                'comment'        => '💬 ' . $invoiceNumber . ': ' . ($new['comment'] ?? ''),
                'update'         => '✏️ Invoice <strong>' . $invoiceNumber . '</strong> updated',
                'delete'         => '🗑️ Invoice <strong>' . $invoiceNumber . '</strong> deleted',
                'payment'        => '💰 Payment for <strong>' . $invoiceNumber . '</strong>',
                default          => ucfirst(str_replace('_', ' ', $h->action)),
            },
            'action' => $h->action,
        ];
    })->toArray();
    
    // ✅ Address — empty check
    $hasData      = fn($addr) => $addr && array_filter($addr, fn($v) => !empty($v));
    $billingAddr  = $commonAddress['billing']  ?? null;
    $shippingAddr = $commonAddress['shipping'] ?? null;

   return response()->json([
    'outstanding'      => round(
                            \App\Models\Invoice::where('customer_id', $id)
                                ->whereIn('payment_status', ['unpaid', 'partial'])
                                ->sum('balance_due'),
                            2
                          ),
    'credits'          => floatval($customer->unused_credits ?? 0),
    'customer_type'    => ucfirst($customer->customer_type ?? ''),
    'currency'         => $additionalDatas['currency']      ?? 'INR',
    'payment_terms'    => $additionalDatas['payment_terms'] ?? '—',
    'pan'              => $customer->pan                    ?? '—',
    'gstin'            => $additionalDatas['gstin']         ?? '—',
    'portal_status'    => '—',
    'contacts'         => $contacts,
    'billing_address'  => $hasData($billingAddr)  ? $billingAddr  : null,
    'shipping_address' => $hasData($shippingAddr) ? $shippingAddr : null,
    'activities'       => $activities,
]);
}

private static function buildUpdateDescription(?array $oldData, ?array $newData): string
{
    if (empty($newData)) return 'Record updated';

    $parts = [];
    foreach ($newData as $key => $newVal) {
        $oldVal = $oldData[$key] ?? '—';
        $label  = ucwords(str_replace(['.', '_'], ' ', $key));
        if ($oldVal !== $newVal) {
            $parts[] = "{$label}: \"{$oldVal}\" → \"{$newVal}\"";
        }
    }

    return empty($parts)
        ? 'Record updated'
        : implode(', ', array_slice($parts, 0, 3)) . (count($parts) > 3 ? ' ...' : '');
}
    // ================================================================
    //  EDIT  ✅ FIX: $categories added (was missing before)
    // ================================================================
    public function edit($id)
    {
        $customer     = Customer::findOrFail($id);
        $customFields = \App\Models\AdditionalSetting::where('category_name', 'customers')
                        ->where('status', 'active')
                        ->get();
        $categories   = \App\Models\UserCategory::orderBy('name')
                        ->get(['id', 'name', 'assign_fix_location', 'country_id', 'location_label']);

        return view('customers.edit', compact('customer', 'customFields', 'categories'));
    }

    // ================================================================
    //  UPDATE  ✅ FIX: removed duplicate update call + $commonAddress
    //                   now defined BEFORE it is used
    // ================================================================
    public function update(Request $request, $id)
    {
        $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        try {
            $customer = Customer::findOrFail($id);

            DB::beginTransaction();

            $fullName = trim(
                ($request->first_name ?? '') . ' ' . ($request->last_name ?? '')
            ) ?: null;

            $existingAdditional = $customer->additional_datas ?? [];
            $existingAddress    = $customer->common_address   ?? [];

            $additionalDatas = array_merge($existingAdditional, [
                'salutation'      => $request->first_name !== null
                                         ? $request->salutation
                                         : ($existingAdditional['salutation'] ?? null),
                'first_name'      => $request->first_name    ?? $existingAdditional['first_name']    ?? null,
                'last_name'       => $request->last_name     ?? $existingAdditional['last_name']     ?? null,
                'mobile'          => $request->mobile        ?? $existingAdditional['mobile']        ?? null,
                'language'        => $request->language      ?? $existingAdditional['language']      ?? null,
                'currency'        => $request->currency      ?? $existingAdditional['currency']      ?? null,
                'payment_terms'   => $request->payment_terms ?? $existingAdditional['payment_terms'] ?? null,
                'enable_portal'   => $request->has('enable_portal')
                                         ? (bool) $request->enable_portal
                                         : ($existingAdditional['enable_portal'] ?? false),
                'website'         => $request->website       ?? $existingAdditional['website']       ?? null,
                'department'      => $request->department    ?? $existingAdditional['department']    ?? null,
                'designation'     => $request->designation   ?? $existingAdditional['designation']   ?? null,
                'twitter'         => $request->twitter       ?? $existingAdditional['twitter']       ?? null,
                'skype'           => $request->skype         ?? $existingAdditional['skype']         ?? null,
                'facebook'        => $request->facebook      ?? $existingAdditional['facebook']      ?? null,
                'contact_persons' => $request->has('contact_persons')
                                         ? $this->sanitizeContactPersons($request->contact_persons ?? [])
                                         : ($existingAdditional['contact_persons'] ?? []),
                'custom_fields'   => $request->has('custom_fields')
                                         ? $request->custom_fields
                                         : ($existingAdditional['custom_fields'] ?? []),
            ]);

            $assignLocation = $customer->assign_location;
            if ($request->filled('assign_location.value_id')) {
                $assignLocation = [
                    'layer_id'  => $request->input('assign_location.layer_id'),
                    'value_id'  => $request->input('assign_location.value_id'),
                    'path'      => $request->input('assign_location.path'),
                    'value_ids' => json_decode(
                                       $request->input('assign_location.value_ids', '[]'),
                                       true
                                   ) ?? [],
                ];
            }

            $commonAddress = [
                'billing'  => $request->has('billing')
                    ? $this->sanitizeAddress($request->input('billing', []))
                    : ($existingAddress['billing']  ?? []),
                'shipping' => $request->has('shipping')
                    ? $this->sanitizeAddress($request->input('shipping', []))
                    : ($existingAddress['shipping'] ?? []),
            ];

            $customer->update([
                'customer_type'     => $request->customer_type     ?? $customer->customer_type,
                'customer_category' => $request->customer_category ?? $customer->customer_category,
                'customer_sub_category_id' => $request->customer_sub_category_id ?? $customer->customer_sub_category_id,
                'name'              => $fullName                   ?? $customer->name,
                'company_name'      => $request->company_name      ?? $customer->company_name,
                'display_name'      => $request->display_name      ?? $customer->display_name,
                'email'             => $request->email             ?? $customer->email,
                'phone_number'      => $request->phone_number      ?? $customer->phone_number,
                'pan'               => $request->pan
                                           ? strtoupper(trim($request->pan))
                                           : $customer->pan,
                'assign_location'   => $assignLocation,
                'additional_datas'  => $additionalDatas,
                'common_address'    => $commonAddress,
                'remarks'           => $request->remarks ?? $customer->remarks,
            ]);

            DB::commit();

            Log::info('[CustomerController:UPDATE] Customer updated', ['id' => $id]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully.',
                    'data'    => $customer->fresh(),
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[CustomerController:UPDATE] ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update customer.',
                    'error'   => config('app.debug') ? $e->getMessage() : 'Server error',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ================================================================
    //  USED LOCATIONS  ✅ NEW METHOD (added for edit page support)
    //  exclude_id — current customer's own value not marked as "used"
    // ================================================================
    public function getUsedLocations(Request $request)
    {
        $category  = $request->query('category');
        $excludeId = $request->query('exclude_id');

        if (!$category) {
            return response()->json(['used_value_ids' => []]);
        }

        $query = Customer::whereNull('deleted_at')
            ->where('customer_category', $category)
            ->whereNotNull('assign_location');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $usedValueIds = $query->get()
            ->pluck('assign_location')
            ->filter()
            ->map(function ($loc) {
                return is_array($loc) ? ($loc['value_id'] ?? null) : null;
            })
            ->filter()
            ->values()
            ->toArray();

        return response()->json([
            'success'        => true,
            'used_value_ids' => $usedValueIds,
            'category'       => $category,
        ]);
    }

    // ================================================================
    //  UPDATE ADDRESS
    // ================================================================
    public function updateAddress(Request $request, Customer $customer)
    {
        $request->validate([
            'address_type' => 'required|in:billing,shipping',
            'attention'    => 'nullable|string|max:255',
            'country'      => 'nullable|string|max:100',
            'street1'      => 'nullable|string|max:255',
            'street2'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'state'        => 'nullable|string|max:100',
            'pincode'      => 'nullable|string|max:20',
            'phone'        => 'nullable|string|max:20',
            'fax'          => 'nullable|string|max:50',
        ]);

        try {
            $type = $request->address_type;

            $existingAddress = $customer->common_address ?? [];

            $newAddress = [
                'attention' => $this->clean($request->attention),
                'country'   => $this->clean($request->country),
                'street1'   => $this->clean($request->street1),
                'street2'   => $this->clean($request->street2),
                'city'      => $this->clean($request->city),
                'state'     => $this->clean($request->state),
                'pincode'   => $this->clean($request->pincode),
                'phone'     => $this->clean($request->phone),
                'fax'       => $this->clean($request->fax),
            ];

            $existingAddress[$type] = $newAddress;

            $customer->update(['common_address' => $existingAddress]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' address updated successfully.',
                'address' => $newAddress,
            ]);

        } catch (\Exception $e) {
            Log::error('[CustomerController:updateAddress] ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address.',
            ], 500);
        }
    }

    // ── Add Contact Person ──
    public function storeContactPerson(Request $request, Customer $customer)
    {
        $request->validate(['first_name' => 'required|string|max:100']);

        $ad = is_array($customer->additional_datas)
            ? $customer->additional_datas
            : (json_decode($customer->additional_datas ?? '{}', true) ?? []);

        $newContact = [
            'salutation'    => $request->salutation   ?? '',
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name    ?? '',
            'email'         => $request->email        ?? '',
            'work_phone'    => trim(($request->work_code   ?? '+91').' '.($request->work_phone  ?? '')),
            'mobile'        => trim(($request->mobile_code ?? '+91').' '.($request->mobile      ?? '')),
            'skype'         => $request->skype        ?? '',
            'designation'   => $request->designation  ?? '',
            'department'    => $request->department   ?? '',
            'portal_access' => $request->portal_access ?? false,
            'is_primary'    => empty($ad['contact_persons']),
        ];

        $ad['contact_persons'][] = $newContact;
        $customer->additional_datas = $ad;
        $customer->save();

        return response()->json(['success' => true, 'contact' => $newContact]);
    }

    // ── Edit Contact Person ──
    public function updateContactPerson(Request $request, Customer $customer, $index)
    {
        $request->validate(['first_name' => 'required|string|max:100']);

        $ad = is_array($customer->additional_datas)
            ? $customer->additional_datas
            : (json_decode($customer->additional_datas ?? '{}', true) ?? []);

        if (!isset($ad['contact_persons'][$index])) {
            return response()->json(['success' => false, 'message' => 'Contact not found.'], 404);
        }

        $existing = $ad['contact_persons'][$index];

        $ad['contact_persons'][$index] = array_merge($existing, [
            'salutation'    => $request->salutation   ?? '',
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name    ?? '',
            'email'         => $request->email        ?? '',
            'work_phone'    => trim(($request->work_code   ?? '+91').' '.($request->work_phone  ?? '')),
            'mobile'        => trim(($request->mobile_code ?? '+91').' '.($request->mobile      ?? '')),
            'skype'         => $request->skype        ?? '',
            'designation'   => $request->designation  ?? '',
            'department'    => $request->department   ?? '',
            'portal_access' => $request->portal_access ?? false,
        ]);

        $customer->additional_datas = $ad;
        $customer->save();

        return response()->json(['success' => true, 'contact' => $ad['contact_persons'][$index]]);
    }

    // ── Mark as Primary ──
    public function markContactPersonPrimary(Customer $customer, $index)
    {
        $ad = is_array($customer->additional_datas)
            ? $customer->additional_datas
            : (json_decode($customer->additional_datas ?? '{}', true) ?? []);

        if (!isset($ad['contact_persons'][$index])) {
            return response()->json(['success' => false, 'message' => 'Contact not found.'], 404);
        }

        foreach ($ad['contact_persons'] as $i => $cp) {
            $ad['contact_persons'][$i]['is_primary'] = ($i == $index);
        }

        $customer->additional_datas = $ad;
        $customer->save();

        $name = trim(
            ($ad['contact_persons'][$index]['salutation'] ?? '') . ' ' .
            ($ad['contact_persons'][$index]['first_name'] ?? '') . ' ' .
            ($ad['contact_persons'][$index]['last_name']  ?? '')
        );

        return response()->json(['success' => true, 'contact_name' => $name]);
    }

    // ── Delete Contact Person ──
    public function destroyContactPerson(Customer $customer, $index)
    {
        $ad = is_array($customer->additional_datas)
            ? $customer->additional_datas
            : (json_decode($customer->additional_datas ?? '{}', true) ?? []);

        if (!isset($ad['contact_persons'][$index])) {
            return response()->json(['success' => false, 'message' => 'Contact not found.'], 404);
        }

        array_splice($ad['contact_persons'], $index, 1);

        if (!empty($ad['contact_persons'])) {
            $hasPrimary = collect($ad['contact_persons'])->contains('is_primary', true);
            if (!$hasPrimary) {
                $ad['contact_persons'][0]['is_primary'] = true;
            }
        }

        $customer->additional_datas = $ad;
        $customer->save();

        return response()->json(['success' => true]);
    }
   

 private function generateUserCode(string $categoryName): string
{
    $category = \App\Models\UserCategory::where('name', $categoryName)
                    ->whereNull('deleted_at')
                    ->first();

    if (!$category) {
        return strtoupper(substr($categoryName, 0, 3)) . '001';
    }

    $baseCode = strtoupper($category->code); // SS001, MV0001, SS01

    // letters + numbers split
    preg_match('/^([A-Z]+)(\d+)$/', $baseCode, $m);

    if (!$m) {
        return $baseCode;
    }

    $letters   = $m[1];            // "SS", "MV", "CF"
    $baseNum   = $m[2];            // "001", "0001", "01"
    $padLength = strlen($baseNum); // 3, 4, 2

    // இந்த category-ல் last customer user_code எடு
    $last = Customer::where('customer_category', $categoryName)
                ->whereNotNull('user_code')
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('user_code');

    if ($last) {
        // SS002 → numbers part → 002 → int → 2 → +1 → 3
        preg_match('/^[A-Z]+(\d+)$/', strtoupper($last), $lm);
        $next = isset($lm[1]) ? ((int)$lm[1] + 1) : ((int)$baseNum + 1);
    } else {
        // First customer → base code அப்படியே
        $next = (int)$baseNum;
    }

    return $letters . str_pad($next, $padLength, '0', STR_PAD_LEFT);
}
    // ================================================================
    //  DESTROY
    // ================================================================
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            Log::info('[CustomerController:DESTROY] Customer deleted', ['id' => $id]);

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Customer deleted.']);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer deleted successfully!');

        } catch (\Exception $e) {
            Log::error('[CustomerController:DESTROY] ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete.'], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete customer.');
        }
    }

    // ================================================================
    //  PRIVATE HELPERS
    // ================================================================
    private function sanitizeAddress(array $address): array
    {
        return [
            'attention' => $this->clean($address['attention'] ?? null),
            'country'   => $this->clean($address['country']   ?? null),
            'street1'   => $this->clean($address['street1']   ?? null),
            'street2'   => $this->clean($address['street2']   ?? null),
            'city'      => $this->clean($address['city']      ?? null),
            'state'     => $this->clean($address['state']     ?? null),
            'pincode'   => $this->clean($address['pincode']   ?? null),
            'phone'     => $this->clean($address['phone']     ?? null),
            'fax'       => $this->clean($address['fax']       ?? null),
        ];
    }

   private function sanitizeContactPersons(array $persons): array
{
    $clean = [];
    foreach ($persons as $p) {
        if (empty($p['first_name']) && empty($p['email'])) {
            continue;
        }
        $clean[] = [
            'salutation'    => $this->clean($p['salutation'] ?? null),
            'first_name'    => $this->clean($p['first_name'] ?? null),
            'last_name'     => $this->clean($p['last_name']  ?? null),
            'email'         => $this->clean($p['email']      ?? null),
            'work_phone'    => $this->clean($p['work_phone'] ?? null),
            'mobile'        => $this->clean($p['mobile']     ?? null),
            'location'      => $p['location']     ?? [],
            // ✅ இந்த இரண்டு fields add பண்ணு
            'is_primary'    => (bool)($p['is_primary']    ?? false),
            'portal_access' => (bool)($p['portal_access'] ?? false),
        ];
    }
    return $clean;
}
    private function clean(?string $value): ?string
    {
        if ($value === null) return null;
        $v = trim(strip_tags($value));
        return $v === '' ? null : htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}   