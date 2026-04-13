<?php

namespace App\Http\Controllers;

use App\Models\SettingHandle;
use Illuminate\Http\Request;

class CustomerVendorController extends Controller
{
    public function create()
    {
        $setting = SettingHandle::where('process', 'customer_vendor_settings')
                                ->where('category_name', 'general')
                                ->first();

        $config = $setting?->config ?? [];

        return view('customer_setting_handle.create', compact('config'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'allow_duplicates'          => 'nullable|boolean',
            'default_customer_type'     => 'required|in:business,individual',
            'customer_credit_limit'     => 'nullable|boolean',
            'credit_limit_action'       => 'nullable|in:restrict,warn',
            'include_sales_orders'      => 'nullable|boolean',
            'billing_address_format'    => 'nullable|string',
            'shipping_address_format'   => 'nullable|string',
        ]);

        $config = [
            'allow_duplicates'          => $request->boolean('allow_duplicates'),
            'default_customer_type'     => $validated['default_customer_type'],
            'customer_credit_limit'     => $request->boolean('customer_credit_limit'),
            'credit_limit_action'       => $validated['credit_limit_action'] ?? 'warn',
            'include_sales_orders'      => $request->boolean('include_sales_orders'),
            'billing_address_format'    => $validated['billing_address_format'] ?? null,
            'shipping_address_format'   => $validated['shipping_address_format'] ?? null,
        ];

        SettingHandle::updateOrCreate(
            [
                'process'       => 'customer_vendor_settings',
                'category_name' => 'general',
            ],
            [
                'config' => $config,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}