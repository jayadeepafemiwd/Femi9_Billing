<?php

namespace App\Http\Controllers;

use App\Models\SettingHandle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\UserCategory;
use Exception;

class InvoiceSettingController extends Controller
{
    // ================================================================
    // SCHEMA — all invoice setting keys with type & defaults
    // ================================================================
    const CATEGORY = 'invoice';

    const SCHEMA = [
        // General
        'allow_editing_sent_invoice'        => ['type' => 'boolean', 'default' => true],
        'associate_expense_receipts'        => ['type' => 'boolean', 'default' => false],

        // Invoice Order Number
        'invoice_order_number'              => [
            'type'    => 'string',
            'default' => 'sales_order_number',
            'allowed' => ['sales_order_number', 'sales_order_reference_number'],
        ],

        // Payments
        'notify_online_payment'             => ['type' => 'boolean', 'default' => true],
        'include_payment_receipt_thank_you' => ['type' => 'boolean', 'default' => true],
        'automate_thank_you_note'           => ['type' => 'boolean', 'default' => true],

        // Invoice QR Code
        'invoice_qr_code_enabled'           => ['type' => 'boolean', 'default' => false],

        // Zero-Value Line Items
        'hide_zero_value_line_items'        => ['type' => 'boolean', 'default' => false],

        // Terms & Customer Notes
        'terms_and_conditions'              => ['type' => 'string',  'default' => null, 'nullable' => true],
        'customer_notes'                    => ['type' => 'string',  'default' => 'Thanks for your business.', 'nullable' => true],
           'advance_payment_enabled'    => ['type' => 'boolean', 'default' => false],
    'advance_payment_categories' => ['type' => 'array',   'default' => []],
        ];

    // ================================================================
    // create() — load & show the Invoice Settings page
    // ================================================================
 public function create()
{
    try {
        Cache::forget('setting_handle_' . self::CATEGORY);

        $setting = SettingHandle::where('category_name', self::CATEGORY)->first();
        $config  = $setting ? ($setting->config ?? []) : [];

        foreach (self::SCHEMA as $key => $schema) {
            if (!array_key_exists($key, $config)) {
                $config[$key] = $schema['default'];
            }
        }

        // ✅ ADD THIS LINE
        $userCategories = UserCategory::orderBy('level')->get();

        Log::info('InvoiceSettingController@create', [
            'category' => self::CATEGORY,
            'has_row'  => $setting ? 'yes' : 'no',
        ]);

        // ✅ CHANGE compact() to include userCategories
        return view('invoice_setting.create', compact('setting', 'config', 'userCategories'));

    } catch (Exception $e) {
        Log::error('InvoiceSettingController@create - Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to load invoice settings.');
    }
}

    // ================================================================
    // store() — validate → sanitize → upsert into setting_handle
    // ================================================================
    public function store(Request $request)
    {
        $request->validate([
            'invoice_order_number' => 'required|in:sales_order_number,sales_order_reference_number',
            'terms_and_conditions' => 'nullable|string|max:5000',
            'customer_notes'       => 'nullable|string|max:2000',
        ]);

        $configData = $this->sanitizeConfig([
            'allow_editing_sent_invoice'        => $request->boolean('allow_editing_sent_invoice'),
            'associate_expense_receipts'        => $request->boolean('associate_expense_receipts'),
            'invoice_order_number'              => $request->invoice_order_number,
            'notify_online_payment'             => $request->boolean('notify_online_payment'),
            'include_payment_receipt_thank_you' => $request->boolean('include_payment_receipt_thank_you'),
            'automate_thank_you_note'           => $request->boolean('automate_thank_you_note'),
            'invoice_qr_code_enabled'           => $request->boolean('invoice_qr_code_enabled'),
            'hide_zero_value_line_items'        => $request->boolean('hide_zero_value_line_items'),
            'terms_and_conditions'              => $request->terms_and_conditions,
            'customer_notes'                    => $request->customer_notes,
             'advance_payment_enabled'           => $request->boolean('advance_payment_enabled'),
    'advance_payment_categories'        => $request->input('advance_payment_categories', []),
            ]);

        DB::beginTransaction();

        try {
            $existing = SettingHandle::where('category_name', self::CATEGORY)->first();

            if ($existing) {
                $existing->update([
                    'process' => 'Invoice Settings',
                    'config'  => $configData,
                ]);
                $message = 'Invoice settings updated successfully!';
            } else {
                SettingHandle::create([
                    'process'       => 'Invoice Settings',
                    'category_name' => self::CATEGORY,
                    'config'        => $configData,
                ]);
                $message = 'Invoice settings saved successfully!';
            }

            Cache::forget('setting_handle_' . self::CATEGORY);
            DB::commit();

            return redirect()->route('invoice_setting.create')
                ->with('success', $message)
                ->with('last_saved', now()->format('d/m/Y h:i:s A'));

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('InvoiceSettingController@store - Error: ' . $e->getMessage());

            return redirect()->route('invoice_setting.create')
                ->with('error', 'Failed to save settings. Please try again.')
                ->withInput();
        }
    }

    // ================================================================
    // getSettingsForInvoice() — use this in other controllers
    // ================================================================
    public static function getSettingsForInvoice(): array
    {
        return Cache::remember('setting_handle_' . self::CATEGORY, 3600, function () {
            $setting = SettingHandle::where('category_name', self::CATEGORY)->first();
            return $setting ? ($setting->config ?? self::getDefaultConfig()) : self::getDefaultConfig();
        });
    }

    // ================================================================
    // HELPERS
    // ================================================================
    private function sanitizeConfig(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            $schema = self::SCHEMA[$key] ?? null;
            if (!$schema) continue;

            if (($schema['nullable'] ?? false) && is_null($value)) {
                $sanitized[$key] = null;
                continue;
            }

            if (is_string($value)) {
                $value = $this->sanitizeString($value);
            }

            $value = $this->castValue($value, $schema['type']);

            if (isset($schema['allowed']) && !in_array($value, $schema['allowed'], true)) {
                $value = $schema['default'];
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    private function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'string'  => (string) $value,
            'boolean' => (bool) $value,
             'array'   => (array) $value,
            default   => $value,
        };
    }

    private function sanitizeString(string $value): string
    {
        return htmlspecialchars(trim(strip_tags($value)), ENT_QUOTES, 'UTF-8');
    }

    private static function getDefaultConfig(): array
    {
        $defaults = [];
        foreach (self::SCHEMA as $key => $schema) {
            $defaults[$key] = $schema['default'];
        }
        return $defaults;
    }

    // Redirect unused resource methods
    public function index()  { return redirect()->route('invoice_setting.create'); }
    public function show($id)  { return redirect()->route('invoice_setting.create'); }
    public function edit($id)  { return redirect()->route('invoice_setting.create'); }
    public function update(Request $request, $id) { return redirect()->route('invoice_setting.create'); }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            $setting = SettingHandle::where('category_name', self::CATEGORY)->first();
            if ($setting) {
                $setting->delete();
                Cache::forget('setting_handle_' . self::CATEGORY);
                DB::commit();
                return redirect()->route('invoice_setting.create')->with('success', 'Invoice settings deleted.');
            }
            return redirect()->route('invoice_setting.create')->with('error', 'No settings found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('InvoiceSettingController@destroy - Error: ' . $e->getMessage());
            return redirect()->route('invoice_setting.create')->with('error', 'Failed to delete settings.');
        }
    }
}
