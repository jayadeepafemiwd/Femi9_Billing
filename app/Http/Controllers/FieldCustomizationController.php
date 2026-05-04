<?php

namespace App\Http\Controllers;

use App\Models\AdditionalSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class FieldCustomizationController extends Controller
{
    const SCHEMA = [
        'data_type' => [
            'type'    => 'string',
            'default' => 'string',
            'allowed' => [
                'string', 'text', 'longtext', 'char',
                'integer', 'biginteger', 'smallinteger', 'tinyinteger',
                'decimal', 'float', 'double',
                'boolean',
                'date', 'datetime', 'timestamp', 'time', 'year',
                'email', 'phone', 'url', 'ip_address', 'mac_address',
                'file', 'image', 'video', 'audio',
                'json', 'array', 'uuid', 'password', 'color',
                'currency', 'percentage', 'coordinates',
            ],
        ],

        'mandatory' => [
            'type'    => 'string',
            'default' => 'no',
            'allowed' => ['yes', 'no'],
        ],

        // ─── additional_config JSON FIELDS ────────────────────────────────
        'help_text' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'sanitize'  => true,
        ],

        'privacy_pii' => [
            'type'    => 'boolean',
            'default' => false,
        ],

        'show_in_pdfs' => [
            'type'    => 'boolean',
            'default' => false,
        ],

        'input_format' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'applies_to' => [
                'integer', 'biginteger', 'smallinteger', 'tinyinteger',
                'decimal', 'float', 'double', 'currency', 'percentage',
            ],
        ],

        'default_value' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'sanitize'  => true,
            'applies_to' => [
                'integer', 'biginteger', 'smallinteger', 'tinyinteger',
                'decimal', 'float', 'double', 'currency', 'percentage',
                'string', 'text', 'longtext', 'char',
                'email', 'phone', 'url', 'ip_address', 'mac_address',
                'password', 'color',
                'boolean',
                'array',
            ],
        ],

        'default_date' => [
            'type'      => 'date',
            'default'   => null,
            'nullable'  => true,
            'applies_to' => ['date', 'datetime', 'timestamp', 'time', 'year'],
        ],

        'default_time' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'applies_to' => ['date', 'datetime', 'timestamp', 'time', 'year'],
        ],

        'prefix' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'sanitize'  => true,
            'applies_to' => ['string'],
        ],

        'starting_number' => [
            'type'      => 'integer',
            'default'   => 1,
            'min'       => 0,
            'nullable'  => true,
            'applies_to' => ['string'],
        ],

        'suffix' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'sanitize'  => true,
            'applies_to' => ['string'],
        ],

        'add_to_existing' => [
            'type'    => 'boolean',
            'default' => false,
            'applies_to' => ['string'],
        ],

        'char_limit' => [
            'type'      => 'integer',
            'default'   => null,
            'nullable'  => true,
            'min'       => 0,
            'applies_to' => [
                'string', 'text', 'longtext', 'char',
                'email', 'phone', 'url', 'ip_address', 'mac_address',
                'password', 'color',
            ],
        ],

        'options' => [
            'type'      => 'string',
            'default'   => null,
            'nullable'  => true,
            'applies_to' => ['array'],
        ],
    ];

    private array $dataTypeMap = [
        'Number'                  => 'integer',
        'Integer'                 => 'integer',
        'Big Integer'             => 'biginteger',
        'Small Integer'           => 'smallinteger',
        'Tiny Integer'            => 'tinyinteger',
        'Decimal'                 => 'decimal',
        'Float'                   => 'float',
        'Double'                  => 'double',
        'Text'                    => 'string',
        'Text Box (Single Line)'  => 'string',
        'Text Area (Multi Line)'  => 'text',
        'Long Text'               => 'longtext',
        'Character'               => 'char',
        'Date'                    => 'date',
        'DateTime'                => 'datetime',
        'Date and Time'           => 'datetime',
        'Time'                    => 'time',
        'Timestamp'               => 'timestamp',
        'Year'                    => 'year',
        'Boolean'                 => 'boolean',
        'Yes/No'                  => 'boolean',
        'Email'                   => 'email',
        'Phone'                   => 'phone',
        'Phone Number'            => 'phone',
        'URL'                     => 'url',
        'IP Address'              => 'ip_address',
        'MAC Address'             => 'mac_address',
        'File'                    => 'file',
        'Image'                   => 'image',
        'Video'                   => 'video',
        'Audio'                   => 'audio',
        'Currency'                => 'currency',
        'Percentage'              => 'percentage',
        'Color'                   => 'color',
        'JSON'                    => 'json',
        'Array'                   => 'array',
        'UUID'                    => 'uuid',
        'Password'                => 'password',
        'Coordinates'             => 'coordinates',
        'Dropdown'                => 'array',
        'Multi Select'            => 'array',
        'MultiSelect'             => 'array',
        'Auto Number'             => 'string',
        'AutoNumber'              => 'string',
    ];

    private array $reverseMap = [
        'integer'      => 'Number',
        'biginteger'   => 'Big Integer',
        'smallinteger' => 'Small Integer',
        'tinyinteger'  => 'Tiny Integer',
        'decimal'      => 'Decimal',
        'float'        => 'Float',
        'double'       => 'Double',
        'string'       => 'Text',
        'text'         => 'Text Area (Multi Line)',
        'longtext'     => 'Long Text',
        'char'         => 'Character',
        'date'         => 'Date',
        'datetime'     => 'DateTime',
        'time'         => 'Time',
        'timestamp'    => 'Timestamp',
        'year'         => 'Year',
        'boolean'      => 'Boolean',
        'email'        => 'Email',
        'phone'        => 'Phone',
        'url'          => 'URL',
        'ip_address'   => 'IP Address',
        'mac_address'  => 'MAC Address',
        'file'         => 'File',
        'image'        => 'Image',
        'video'        => 'Video',
        'audio'        => 'Audio',
        'currency'     => 'Currency',
        'percentage'   => 'Percentage',
        'color'        => 'Color',
        'json'         => 'JSON',
        'array'        => 'Array',
        'uuid'         => 'UUID',
        'password'     => 'Password',
        'coordinates'  => 'Coordinates',
    ];

    // ═══════════════════════════════════════════════════════════════════════
    //  CRUD METHODS
    // ═══════════════════════════════════════════════════════════════════════

        public function index(Request $request): View
    {
        try {
            $category = $request->query('from', 'products');
            $fields = AdditionalSetting::where('category_name', $category)->latest()->paginate(10);
            return view('field_customization.index', compact('fields', 'category'));
        } catch (Exception $e) {
            Log::error('FieldCustomizationController@index - ' . $e->getMessage());
            return view('field_customization.index', ['fields' => collect([]), 'category' => 'products']);
        }
    }

    public function create(Request $request): View
{
    $category = $request->query('from', $request->query('category', 'products'));
    return view('field_customization.create', compact('category'));
}

    public function store(Request $request): RedirectResponse
    {
        
        try {
            $request->validate([
                'label_name'      => 'required|string|max:255',
                'data_type'       => 'required|string',
                'mandatory'       => 'required|in:yes,no',
                'help_text'       => 'nullable|string',
                'privacy_pii'     => 'nullable',
                'input_format'    => 'nullable|string',
                'default_value'   => 'nullable|string',
                'default_date'    => 'nullable|string',
                'default_time'    => 'nullable|string',
                'prefix'          => 'nullable|string',
                'starting_number' => 'nullable|numeric',
                'suffix'          => 'nullable|string',
                'char_limit'      => 'nullable|numeric',
                'options'         => 'nullable|string',
                'access'          => 'nullable|array',
            ]);

            DB::beginTransaction();

            $dbDataType        = $this->dataTypeMap[$request->data_type] ?? 'string';
            $additional_config = $this->sanitizeConfig($request, $dbDataType, isNew: true);

           $field = AdditionalSetting::create([
    'name'              => $this->sanitizeString($request->label_name),
    'category_name'     => $request->input('category_name') ?? 'products', // ADD
    'data_type'         => $dbDataType,
    'mandatory'         => $request->mandatory,
    'status'            => 'active',
    'additional_config' => $additional_config,
]);

            DB::commit();

            Log::info('FieldCustomizationController@store - Created ID: ' . $field->id, [
                'user_id' => auth()->id()
            ]);

           $category = $request->input('category_name', 'products');

if ($category === 'invoice') {
    return redirect()->route('invoices.index')
        ->with('success', 'Invoice custom field created successfully!');
}

return redirect()->route('field_customization.index', ['from' => $category])
    ->with('success', 'Field created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FieldCustomizationController@store - ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(), 'user_id' => auth()->id()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to create field. Please try again.')
                ->withInput();
        }
    }

    public function edit($id): View|RedirectResponse
    {
        try {
            $field       = AdditionalSetting::findOrFail($id);
            $displayType = $this->reverseMap[$field->data_type] ?? 'Text';
            return view('field_customization.edit', compact('field', 'displayType'));
        } catch (Exception $e) {
            Log::error('FieldCustomizationController@edit - ' . $e->getMessage());
            return redirect()->route('field_customization.index')
                ->with('error', 'Failed to load edit form.');
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'label_name'      => 'required|string|max:255',
                'data_type'       => 'required|string',
                'mandatory'       => 'required|in:yes,no',
                'help_text'       => 'nullable|string',
                'privacy_pii'     => 'nullable',
                'input_format'    => 'nullable|string',
                'default_value'   => 'nullable|string',
                'default_date'    => 'nullable|string',
                'default_time'    => 'nullable|string',
                'prefix'          => 'nullable|string',
                'starting_number' => 'nullable|numeric',
                'suffix'          => 'nullable|string',
                'char_limit'      => 'nullable|numeric',
                'options'         => 'nullable|string',
                'add_to_existing' => 'nullable',
                'access'          => 'nullable|array',
            ]);

            DB::beginTransaction();

            $field             = AdditionalSetting::findOrFail($id);
            $dbDataType        = $this->dataTypeMap[$request->data_type] ?? 'string';
            $existing          = $field->additional_config ?? [];
            $additional_config = $this->sanitizeConfig($request, $dbDataType, isNew: false, existing: $existing);

            $field->update([
                'Name'              => $this->sanitizeString($request->label_name),
                'data_type'         => $dbDataType,
                'mandatory'         => $request->mandatory,
                'additional_config' => $additional_config,
            ]);

            DB::commit();

            Log::info('FieldCustomizationController@update - Updated ID: ' . $field->id, [
                'user_id' => auth()->id()
            ]);

            return redirect()->route('field_customization.index')
                ->with('success', 'Field updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FieldCustomizationController@update - ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(), 'user_id' => auth()->id()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to update field. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $field = AdditionalSetting::findOrFail($id);
            $field->delete();
            DB::commit();
            Log::info('FieldCustomizationController@destroy - Deleted ID: ' . $id, ['user_id' => auth()->id()]);
            return redirect()->route('field_customization.index')
                ->with('success', 'Field deleted successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FieldCustomizationController@destroy - ' . $e->getMessage());
            return redirect()->route('field_customization.index')
                ->with('error', 'Failed to delete field.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  ACCESS & TOGGLE HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    public function access($id): View|RedirectResponse
    {
        try {
            $field  = AdditionalSetting::findOrFail($id);
            $roles  = [
                ['id' => 1, 'name' => 'Admin'],
                ['id' => 2, 'name' => 'Manager'],
                ['id' => 3, 'name' => 'Staff'],
                ['id' => 4, 'name' => 'Viewer'],
            ];
            $accessSettings = $field->additional_config['access'] ?? [];
            return view('field_customization.access', compact('field', 'roles', 'accessSettings'));
        } catch (Exception $e) {
            Log::error('FieldCustomizationController@access - ' . $e->getMessage());
            return redirect()->route('field_customization.index')
                ->with('error', 'Failed to load access settings.');
        }
    }

    public function updateAccess(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate([
                'access'              => 'required|array',
                'access.*.permission' => 'required|in:read_write,read_only,hide',
            ]);

            DB::beginTransaction();
            $field  = AdditionalSetting::findOrFail($id);
            $config = $field->additional_config ?? [];

            $config['access']            = $this->sanitizeAccessData($request->access);
            $config['access_updated_at'] = now()->toDateTimeString();

            $field->update(['additional_config' => $config]);
            DB::commit();

            return redirect()->route('field_customization.index')
                ->with('success', 'Access updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FieldCustomizationController@updateAccess - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update access permissions.');
        }
    }

    public function toggleStatus($id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $field     = AdditionalSetting::findOrFail($id);
            $newStatus = $field->status === 'active' ? 'inactive' : 'active';

            $field->update(['status' => $newStatus]);

            DB::commit();

            Log::info("FieldCustomizationController@toggleStatus - field ID: {$id} → {$newStatus}", [
                'user_id' => auth()->id()
            ]);

            return redirect()->route('field_customization.index')
                ->with('success', 'Field status updated to ' . $newStatus . ' successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('FieldCustomizationController@toggleStatus - ' . $e->getMessage());
            return redirect()->route('field_customization.index')
                ->with('error', 'Failed to update field status.');
        }
    }

    public function togglePdf($id): RedirectResponse
    {
        return $this->toggleConfigKey($id, 'show_in_pdfs', false, true, 'PDF visibility updated successfully!');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  CORE: SCHEMA-BASED sanitizeConfig
    // ═══════════════════════════════════════════════════════════════════════

    private function sanitizeConfig(
        Request $request,
        string  $dbDataType,
        bool    $isNew    = true,
        array   $existing = []
    ): array {
        $config = $existing;

        // Timestamp
        $config[$isNew ? 'created_at' : 'updated_at'] = now()->toDateTimeString();

        
        $raw = [
            'help_text'       => $request->help_text,
            'privacy_pii'     => $request->has('privacy_pii'),
            'input_format'    => $request->input_format,
            'default_value'   => $request->default_value,
            'default_date'    => $request->default_date,
            'default_time'    => $request->default_time,
            'prefix'          => $request->prefix,
            'starting_number' => $request->starting_number,
            'suffix'          => $request->suffix,
            'add_to_existing' => $request->has('add_to_existing'),
            'char_limit'      => $request->char_limit,
            'options'         => $request->options,
        ];

        foreach ($raw as $key => $value) {
            $schema = self::SCHEMA[$key] ?? null;

            if (!$schema) {
                continue;
            }

            if (isset($schema['applies_to']) && !in_array($dbDataType, $schema['applies_to'], true)) {
                unset($config[$key]);
                continue;
            }

            if (($schema['nullable'] ?? false) && is_null($value)) {
                $config[$key] = null;
                continue;
            }

            if (is_string($value) && ($schema['sanitize'] ?? false)) {
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

            $config[$key] = $value;
        }

        if ($request->has('access')) {
            $config['access'] = $this->sanitizeAccessData($request->access);
        }

        return array_filter($config, fn($v) => !is_null($v) && $v !== '');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    private function sanitizeAccessData(array $accessInput): array
    {
        $result = [];
        foreach ($accessInput as $roleId => $access) {
            if (!isset($access['permission'])) {
                continue;
            }
            $result[] = [
                'role_id'    => (int) $roleId,
                'role_name'  => $this->sanitizeString($access['role_name'] ?? "Role $roleId"),
                'permission' => $access['permission'],
                'is_admin'   => isset($access['is_admin']),
            ];
        }
        return $result;
    }

    private function toggleConfigKey($id, string $key, mixed $on, mixed $off, string $successMsg): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $field  = AdditionalSetting::findOrFail($id);
            $config = $field->additional_config ?? [];

            $config[$key] = ($config[$key] ?? $on) === $on ? $off : $on;
            $field->update(['additional_config' => $config]);
            DB::commit();

            Log::info("FieldCustomizationController@toggleConfigKey [{$key}] - field ID: {$id}", [
                'user_id' => auth()->id(), 'new_value' => $config[$key]
            ]);

            return redirect()->route('field_customization.index')->with('success', $successMsg);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("FieldCustomizationController@toggleConfigKey [{$key}] - " . $e->getMessage());
            return redirect()->route('field_customization.index')->with('error', 'Failed to update field.');
        }
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

    private function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(trim(strip_tags($value)), ENT_QUOTES, 'UTF-8');
    }
}