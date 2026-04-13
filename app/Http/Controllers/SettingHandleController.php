<?php

namespace App\Http\Controllers;

use App\Models\SettingHandle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;

class SettingHandleController extends Controller
{
    const SCHEMA = [
        'decimal_rate'              => ['type' => 'integer', 'default' => 2,              'min' => 0, 'max' => 6],
        'dimension_unit'            => ['type' => 'string',  'default' => 'cm',           'allowed' => ['cm', 'm', 'in', 'ft', 'mm']],
        'weight'                    => ['type' => 'float',   'default' => 0,              'nullable' => true],
        'weight_unit'               => ['type' => 'string',  'default' => 'kg',           'allowed' => ['kg', 'g', 'lb', 'oz', 'mg']],
        'barcode_field'             => ['type' => 'string',  'default' => 'sku',          'allowed' => ['sku', 'upc', 'ean', 'isbn']],
        'inventory_start_date'      => ['type' => 'date',    'default' => null,           'nullable' => true],
        'enable_serial_number'      => ['type' => 'boolean', 'default' => false],
        'enable_batch_tracking'     => ['type' => 'boolean', 'default' => false],
        'stock_prevention_level'    => ['type' => 'string',  'default' => 'organization', 'allowed' => ['organization', 'location']],
        'show_out_of_stock_warning' => ['type' => 'boolean', 'default' => false],
        'notify_reorder_point'      => ['type' => 'boolean', 'default' => false],
        'notify_email'              => ['type' => 'email',   'default' => null,           'nullable' => true],
        'track_landed_cost'         => ['type' => 'boolean', 'default' => false],
        'duplicate_item_name'       => ['type' => 'boolean', 'default' => false],
        'enhanced_item_search'      => ['type' => 'boolean', 'default' => false],
        'enable_price_lists'        => ['type' => 'boolean', 'default' => false],
        'enable_composite_items'    => ['type' => 'boolean', 'default' => false],
    ];

    const ALLOWED_MODULES = ['products', 'sales', 'purchases'];

   
    public function create()
    {
        try {
            $fromUrl = request('from');
            if ($fromUrl && in_array($fromUrl, self::ALLOWED_MODULES)) {
                session(['setting_handle_module' => $fromUrl]);
            }

            $module = session('setting_handle_module', 'products');

            Cache::forget("setting_handle_{$module}");

           
            $setting = SettingHandle::where('category_name', $module)->first();

            
            $config = $setting ? ($setting->config ?? []) : [];

            Log::info('SettingHandleController@create', [
                'module'  => $module,
                'has_row' => $setting ? 'yes' : 'no',
            ]);

            return view('setting_handle.create', compact('setting', 'module', 'config'));

        } catch (Exception $e) {
            Log::error('SettingHandleController@create - Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load settings page.');
        }
    }

    // ================================================================
    // store() — validate → sanitize → upsert by category_name
    // ================================================================
    public function store(Request $request)
{
    $module = session('setting_handle_module', 'products');

    $request->validate([
        'name'                           => 'required|string|max:200',
        'decimal_rate'                   => 'required|integer|min:0|max:6',
        'custom_field.dimension_unit'    => 'required|in:cm,m,in,ft,mm',  // ⭐ fixed
        'custom_field.weight_unit'       => 'required|in:kg,g,lb,oz,mg',  // ⭐ fixed
        'barcode_field'                  => 'required|in:sku,upc,ean,isbn',
        'inventory_start_date'           => 'required|date',
        'enable_serial_number'           => 'nullable|boolean',
        'enable_batch_tracking'          => 'nullable|boolean',
        'stock_prevention_level'         => 'required|in:organization,location',
        'show_out_of_stock_warning'      => 'nullable|boolean',
        'notify_reorder_point'           => 'nullable|boolean',
        'notify_email'                   => 'nullable|email',
        'track_landed_cost'              => 'nullable|boolean',
        'duplicate_item_name'            => 'nullable|boolean',
        'enhanced_item_search'           => 'nullable|boolean',
        'enable_price_lists'             => 'nullable|boolean',
        'enable_composite_items'         => 'nullable|boolean',
    ]);

    $configData = $this->sanitizeConfig([
        'decimal_rate'              => $request->decimal_rate,
        'dimension_unit'            => $request->input('custom_field.dimension_unit'), // ⭐ fixed
        'weight_unit'               => $request->input('custom_field.weight_unit'),    // ⭐ fixed
        'barcode_field'             => $request->barcode_field,
        'inventory_start_date'      => $request->inventory_start_date,
        'enable_serial_number'      => $request->boolean('enable_serial_number'),
        'enable_batch_tracking'     => $request->boolean('enable_batch_tracking'),
        'stock_prevention_level'    => $request->stock_prevention_level,
        'show_out_of_stock_warning' => $request->boolean('show_out_of_stock_warning'),
        'notify_reorder_point'      => $request->boolean('notify_reorder_point'),
        'notify_email'              => $request->notify_email,
        'track_landed_cost'         => $request->boolean('track_landed_cost'),
        'duplicate_item_name'       => $request->boolean('duplicate_item_name'),
        'enhanced_item_search'      => $request->boolean('enhanced_item_search'),
        'enable_price_lists'        => $request->boolean('enable_price_lists'),
        'enable_composite_items'    => $request->boolean('enable_composite_items'),
    ]);

    DB::beginTransaction();

    try {
        $existingSetting = SettingHandle::where('category_name', $module)->first();

        if ($existingSetting) {
            $existingSetting->update([
                'process' => $this->sanitizeString($request->name),
                'config'  => $configData,
            ]);
            $message = 'Settings updated successfully!';
        } else {
            SettingHandle::create([
                'process'       => $this->sanitizeString($request->name),
                'category_name' => $module,
                'config'        => $configData,
            ]);
            $message = 'Settings saved successfully!';
        }

        Cache::forget("setting_handle_{$module}");
        DB::commit();

        return redirect()->route('setting_handle.create')
            ->with('success', $message)
            ->with('last_saved', now()->format('d/m/Y h:i:s A'));

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('SettingHandleController@store - Error: ' . $e->getMessage());

        return redirect()->route('setting_handle.create')
            ->with('error', 'Failed to save settings. Please try again.')
            ->withInput();
    }
}

    // ================================================================
    // getSettingsForModule() — other controllers use this
    // ================================================================
    public static function getSettingsForModule(string $module): array
    {
        return Cache::remember("setting_handle_{$module}", 3600, function () use ($module) {
            $setting = SettingHandle::where('category_name', $module)->first();
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

            if (isset($schema['min']) && is_numeric($value)) {
                $value = max($schema['min'], $value);
            }
            if (isset($schema['max']) && is_numeric($value)) {
                $value = min($schema['max'], $value);
            }
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
            'integer' => (int) $value,
            'float'   => (float) $value,
            'boolean' => (bool) $value,
            'email'   => strtolower(trim((string) $value)),
            'date'    => $value,
            'array'   => is_array($value) ? $value : [],
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

    public static function getSettings()
    {
        return ['process' => 'Default Settings', 'config' => self::getSettingsForModule('products')];
    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            $module  = session('setting_handle_module', 'products');
            $setting = SettingHandle::where('category_name', $module)->first();

            if ($setting) {
                $setting->delete();
                Cache::forget("setting_handle_{$module}");
                DB::commit();
                return redirect()->route('setting_handle.create')->with('success', 'Settings deleted successfully!');
            }
            return redirect()->route('setting_handle.create')->with('error', 'No settings found to delete.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('SettingHandleController@destroy - Error: ' . $e->getMessage());
            return redirect()->route('setting_handle.create')->with('error', 'Failed to delete settings.');
        }
    }

    public function index()  { return redirect()->route('setting_handle.create'); }
    public function show($id)  { return redirect()->route('setting_handle.create'); }
    public function edit($id)  { return redirect()->route('setting_handle.create'); }
    public function update(Request $request, $id) { return redirect()->route('setting_handle.create'); }
}