<?php

namespace App\Http\Controllers;

use App\Models\LockConfiguration;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class LockConfigurationController extends Controller
{
    /**
     * Log channel for lock configuration operations
     */
    protected $logChannel = 'lock_config';

    // ================================================================
    //  SCHEMA — Field definitions & validation rules
    // ================================================================

    const SCHEMA = [

        'name' => [
            'type'     => 'string',
            'default'  => null,
            'nullable' => false,
            'max'      => 255,
        ],

        'description' => [
            'type'     => 'string',
            'default'  => null,
            'nullable' => true,
        ],

        'module' => [
            'type'    => 'string',
            'default' => 'items',
            'allowed' => ['items', 'sales', 'purchases', 'inventory'],
        ],

        'action_type' => [
            'type'    => 'string',
            'default' => 'restrict_all',
            'allowed' => ['restrict_all', 'restrict_selected', 'allow_selected'],
        ],

        'selected_actions' => [
            'type'    => 'array',
            'default' => [],
            'allowed' => ['create', 'edit', 'delete', 'view', 'export', 'import'],
        ],

        'field_type' => [
            'type'    => 'string',
            'default' => 'restrict_all',
            'allowed' => ['restrict_all', 'restrict_selected', 'allow_selected'],
        ],

        'selected_fields' => [
            'type'    => 'array',
            'default' => [],
            'allowed' => [
                'item_name', 'sku', 'unit',
                'selling_price', 'purchase_price', 'cost_price',
                'sales_account', 'purchase_account', 'inventory_account',
                'opening_stock', 'opening_stock_value', 'reorder_point',
                'upc', 'mpn', 'manufacturer', 'ean',
                'weight_unit', 'description', 'tax',
                'vendor', 'brand', 'category',
            ],
        ],

        'lock_for_type' => [
            'type'    => 'string',
            'default' => 'all_roles',
            'allowed' => ['all_roles', 'all_roles_except', 'selected_roles'],
        ],

        'roles' => [
            'type'    => 'array',
            'default' => [],
            'allowed' => ['zoho_admin', 'zoho_staff', 'zoho_manager'],
        ],

        // ── Active / Inactive ──────────────────────────────────────
        'status' => [
            'type'    => 'string',
            'default' => 'active',
            'allowed' => ['active', 'inactive'],
        ],
    ];

    // ================================================================
    //  AVAILABLE FIELDS & ROLES
    // ================================================================

    private array $availableFields = [
        'item_name'           => 'Item Name',
        'sku'                 => 'SKU',
        'unit'                => 'Unit',
        'selling_price'       => 'Selling Price',
        'purchase_price'      => 'Purchase Price',
        'cost_price'          => 'Cost Price',
        'sales_account'       => 'Sales Account',
        'purchase_account'    => 'Purchase Account',
        'inventory_account'   => 'Inventory Account',
        'opening_stock'       => 'Opening Stock',
        'opening_stock_value' => 'Opening Stock Value',
        'reorder_point'       => 'Reorder Point',
        'upc'                 => 'Universal Product Code (UPC)',
        'mpn'                 => 'Manufacturer Part Number (MPN)',
        'manufacturer'        => 'Manufacturer',
        'ean'                 => 'European Article Number (EAN)',
        'weight_unit'         => 'Weight Unit',
        'description'         => 'Description',
        'tax'                 => 'Tax',
        'vendor'              => 'Vendor',
        'brand'               => 'Brand',
        'category'            => 'Category',
    ];

    // ── Role list matching Zoho UI ─────────────────────────────────────────────
    private array $availableRoles = [
        'zoho_admin'   => 'Zoho Inventory - Admin',
        'zoho_staff'   => 'Zoho Inventory - Staff',
        'zoho_manager' => 'Zoho Inventory - Warehouse Manager',
    ];

    private array $dataTypeMap = [
        'Number'   => 'integer',
        'Decimal'  => 'decimal',
        'Text'     => 'string',
        'Date'     => 'date',
        'DateTime' => 'datetime',
        'Boolean'  => 'boolean',
        'Email'    => 'email',
        'Phone'    => 'phone',
        'URL'      => 'url',
        'File'     => 'file',
        'Image'    => 'image',
    ];

    // ================================================================
    //  CONSTRUCTOR - Initialize logging
    // ================================================================
    
    public function __construct()
    {
        // Set up custom log channel for lock configurations
        try {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/lock_configurations.log'),
                'level' => env('LOG_LEVEL', 'debug'),
            ]);
        } catch (Exception $e) {
            // Fallback to default log if custom channel fails
            Log::warning('Failed to create lock_config log channel: ' . $e->getMessage());
        }
    }

    // ================================================================
    //  LOGGING HELPERS
    // ================================================================

    /**
     * Log lock configuration operation with context
     */
    private function logOperation($operation, $message, $context = [], $level = 'info')
    {
        try {
            $context = array_merge([
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email ?? 'system',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ], $context);

            // Try to log to custom channel, fallback to default
            try {
                Log::channel($this->logChannel)->$level("[LOCK_CONFIG] [{$operation}] {$message}", $context);
            } catch (Exception $e) {
                Log::$level("[LockConfigController:{$operation}] {$message}", $context);
            }
            
        } catch (Exception $e) {
            // Last resort - don't let logging break the application
            Log::error('Logging failed in LockConfigController: ' . $e->getMessage());
        }
    }

    /**
     * Log error with exception details
     */
    private function logError($operation, $message, $exception, $context = [])
    {
        try {
            $context = array_merge([
                'exception_message' => $exception->getMessage(),
                'exception_code' => $exception->getCode(),
                'exception_file' => $exception->getFile(),
                'exception_line' => $exception->getLine(),
                'exception_trace' => $exception->getTraceAsString(),
            ], $context);

            $this->logOperation($operation, $message, $context, 'error');
            
            // Report to Laravel's error handler
            try {
                report($exception);
            } catch (Exception $e) {
                // Ignore reporting errors
            }
            
        } catch (Exception $e) {
            // Silent fail - don't let error logging break the application
        }
    }

    // ================================================================
    //  SANITIZATION HELPER
    // ================================================================

    /**
     * Sanitize string input with error handling
     */
    private function sanitizeString(?string $value): ?string
    {
        try {
            if ($value === null) {
                return null;
            }
            
            $value = trim(strip_tags($value));
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            
        } catch (Exception $e) {
            $this->logError('SANITIZE', 'Failed to sanitize string', $e, ['value' => $value]);
            // Return original value as fallback
            return $value;
        }
    }

    // ================================================================
    //  INDEX
    // ================================================================

    /**
     * Display a listing of lock configurations.
     */
    public function index(): View
    {
        $operation = 'INDEX';
        $startTime = microtime(true);
        
        try {
            $this->logOperation($operation, 'Loading lock configurations');

            $configs = LockConfiguration::latest()->get();

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Lock configurations loaded successfully', [
                'count' => $configs->count(),
                'execution_time_ms' => $executionTime
            ]);

            return view('lock_configuration.index', [
                'configs'         => $configs,
                'availableFields' => $this->availableFields,
                'availableRoles'  => $this->availableRoles,
            ]);
            
        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to load lock configurations', $e, [
                'execution_time_ms' => $executionTime
            ]);

            // Return view with empty data and error message
            return view('lock_configuration.index', [
                'configs'         => [],
                'availableFields' => $this->availableFields,
                'availableRoles'  => $this->availableRoles,
                'error'           => 'Failed to load configurations. Please try again.'
            ]);
        }
    }

    // ================================================================
    //  CREATE
    // ================================================================

    /**
     * Show the form for creating a new lock configuration.
     */
    public function create(): View
    {
        $operation = 'CREATE_VIEW';
        
        try {
            $this->logOperation($operation, 'Loading create form');

            return view('lock_configuration.create', [
                'availableFields' => $this->availableFields,
                'availableRoles'  => $this->availableRoles,
                'defaults'        => collect(self::SCHEMA)->map(fn($s) => $s['default']),
            ]);
            
        } catch (Exception $e) {
            $this->logError($operation, 'Failed to load create form', $e);

            // Return view with error
            return view('lock_configuration.create', [
                'availableFields' => $this->availableFields,
                'availableRoles'  => $this->availableRoles,
                'defaults'        => collect(self::SCHEMA)->map(fn($s) => $s['default']),
                'error'           => 'Failed to load create form.'
            ]);
        }
    }

    // ================================================================
    //  STORE
    // ================================================================

    /**
     * Store a newly created lock configuration.
     */
    public function store(Request $request): RedirectResponse
    {
        $operation = 'STORE';
        $startTime = microtime(true);
        
        try {
            $this->logOperation($operation, 'Starting lock configuration creation', [
                'request_data' => $request->except(['_token'])
            ]);

            // ── Validation ──────────────────────────────────────────
            try {
                $validated = $request->validate([
                    'name'          => 'required|string|max:' . self::SCHEMA['name']['max'] . '|unique:lock_configuration,name',
                    'description'   => 'nullable|string',
                    'action_type'   => 'required|in:' . implode(',', self::SCHEMA['action_type']['allowed']),
                    'field_type'    => 'required|in:' . implode(',', self::SCHEMA['field_type']['allowed']),
                    'lock_for_type' => 'required|in:' . implode(',', self::SCHEMA['lock_for_type']['allowed']),
                ]);

                $this->logOperation($operation, 'Validation passed', [
                    'validated_data' => $validated
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Validation failed', $e, [
                    'request_data' => $request->except(['_token'])
                ]);

                return redirect()->back()
                    ->with('error', 'Validation failed: ' . $e->getMessage())
                    ->withInput();
            }

            DB::beginTransaction();
            $this->logOperation($operation, 'Database transaction started');

            // ── Process selected actions ────────────────────────────
            $selectedActions = [];
            try {
                if (in_array($request->action_type, ['restrict_selected', 'allow_selected'])) {
                    $selectedActions = array_values(array_filter($request->input('selected_actions', [])));
                    $this->logOperation($operation, 'Processed selected actions', [
                        'action_type'            => $request->action_type,
                        'selected_actions_count' => count($selectedActions),
                        'selected_actions'       => $selectedActions,
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process selected actions', $e);
                throw $e;
            }

            // ── Process selected fields ─────────────────────────────
            $selectedFields = [];
            try {
                if (in_array($request->field_type, ['restrict_selected', 'allow_selected'])) {
                    $selectedFields = array_values(array_filter($request->input('selected_fields', [])));
                    $this->logOperation($operation, 'Processed selected fields', [
                        'field_type'            => $request->field_type,
                        'selected_fields_count' => count($selectedFields),
                        'selected_fields'       => $selectedFields,
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process selected fields', $e);
                throw $e;
            }

            // ── Process roles ───────────────────────────────────────
            $roles = [];
            try {
                if (in_array($request->lock_for_type, ['all_roles_except', 'selected_roles'])) {
                    $roles = array_values(array_filter($request->input('roles', [])));
                    $this->logOperation($operation, 'Processed roles', [
                        'lock_for_type' => $request->lock_for_type,
                        'roles_count'   => count($roles),
                        'roles'         => $roles,
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process roles', $e);
                throw $e;
            }

            // ── Create configuration ────────────────────────────────
            try {
                $configuration = LockConfiguration::create([
                    'name'             => $this->sanitizeString($request->name),
                    'description'      => $request->description ? $this->sanitizeString($request->description) : null,
                    'module'           => self::SCHEMA['module']['default'],
                    'action_type'      => $request->action_type,
                    'selected_actions' => $selectedActions,
                    'field_type'       => $request->field_type,
                    'selected_fields'  => $selectedFields,
                    'lock_for_type'    => $request->lock_for_type,
                    'roles'            => $roles,
                    'status'           => self::SCHEMA['status']['default'],
                ]);

                $this->logOperation($operation, 'Configuration created in database', [
                    'configuration_id' => $configuration->id,
                    'name'             => $configuration->name,
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Failed to create configuration in database', $e);
                throw $e;
            }

            DB::commit();
            $this->logOperation($operation, 'Database transaction committed', [
                'configuration_id' => $configuration->id
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Lock configuration created successfully', [
                'configuration_id' => $configuration->id,
                'name'             => $configuration->name,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('success', 'Lock Configuration "' . $request->name . '" created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            $this->logOperation($operation, 'Database transaction rolled back', [], 'warning');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to create lock configuration', $e, [
                'execution_time_ms' => $executionTime,
                'request_data'      => $request->except(['_token']),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create lock configuration. Please try again.')
                ->withInput();
        }
    }

    // ================================================================
    //  EDIT
    // ================================================================

    /**
     * Show the form for editing the specified lock configuration.
     */
    public function edit(LockConfiguration $lockConfiguration): View
    {
        $operation = 'EDIT_VIEW';
        $startTime = microtime(true);
        
        try {
            $this->logOperation($operation, 'Loading edit form', [
                'configuration_id'   => $lockConfiguration->id,
                'configuration_name' => $lockConfiguration->name,
            ]);

            // Validate configuration data integrity
            $validationErrors = $this->validateConfig($lockConfiguration);
            if (!empty($validationErrors)) {
                $this->logOperation($operation, 'Configuration has validation issues', [
                    'errors' => $validationErrors
                ], 'warning');
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Edit form loaded successfully', [
                'configuration_id'  => $lockConfiguration->id,
                'execution_time_ms' => $executionTime,
            ]);

            return view('lock_configuration.edit', [
                'config'            => $lockConfiguration,
                'availableFields'   => $this->availableFields,
                'availableRoles'    => $this->availableRoles,
                'defaults'          => collect(self::SCHEMA)->map(fn($s) => $s['default']),
                'validation_errors' => $validationErrors,
            ]);

        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to load edit form', $e, [
                'configuration_id'  => $lockConfiguration->id,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('error', 'Failed to load edit form for configuration #' . $lockConfiguration->id);
        }
    }

    // ================================================================
    //  UPDATE
    // ================================================================

    /**
     * Update the specified lock configuration.
     */
    public function update(Request $request, LockConfiguration $lockConfiguration): RedirectResponse
    {
        $operation = 'UPDATE';
        $startTime = microtime(true);
        
        try {
            $this->logOperation($operation, 'Starting configuration update', [
                'configuration_id' => $lockConfiguration->id,
                'request_data'     => $request->except(['_token', '_method']),
            ]);

            // ── Validation ──────────────────────────────────────────
            try {
                $validated = $request->validate([
                    'name'          => 'required|string|max:' . self::SCHEMA['name']['max'] . '|unique:lock_configuration,name,' . $lockConfiguration->id,
                    'description'   => 'nullable|string',
                    'action_type'   => 'required|in:' . implode(',', self::SCHEMA['action_type']['allowed']),
                    'field_type'    => 'required|in:' . implode(',', self::SCHEMA['field_type']['allowed']),
                    'lock_for_type' => 'required|in:' . implode(',', self::SCHEMA['lock_for_type']['allowed']),
                ]);

                $this->logOperation($operation, 'Validation passed', [
                    'configuration_id' => $lockConfiguration->id,
                    'validated_data'   => $validated,
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Validation failed', $e, [
                    'configuration_id' => $lockConfiguration->id,
                    'request_data'     => $request->except(['_token', '_method']),
                ]);

                return redirect()->back()
                    ->with('error', 'Validation failed: ' . $e->getMessage())
                    ->withInput();
            }

            DB::beginTransaction();
            $this->logOperation($operation, 'Database transaction started', [
                'configuration_id' => $lockConfiguration->id
            ]);

            // ── Store old data for logging ──────────────────────────
            $oldData = $lockConfiguration->toArray();

            // ── Process selected actions ────────────────────────────
            $selectedActions = [];
            try {
                if (in_array($request->action_type, ['restrict_selected', 'allow_selected'])) {
                    $selectedActions = array_values(array_filter($request->input('selected_actions', [])));
                    $this->logOperation($operation, 'Processed selected actions', [
                        'configuration_id'       => $lockConfiguration->id,
                        'action_type'            => $request->action_type,
                        'selected_actions_count' => count($selectedActions),
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process selected actions', $e, [
                    'configuration_id' => $lockConfiguration->id
                ]);
                throw $e;
            }

            // ── Process selected fields ─────────────────────────────
            $selectedFields = [];
            try {
                if (in_array($request->field_type, ['restrict_selected', 'allow_selected'])) {
                    $selectedFields = array_values(array_filter($request->input('selected_fields', [])));
                    $this->logOperation($operation, 'Processed selected fields', [
                        'configuration_id'      => $lockConfiguration->id,
                        'field_type'            => $request->field_type,
                        'selected_fields_count' => count($selectedFields),
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process selected fields', $e, [
                    'configuration_id' => $lockConfiguration->id
                ]);
                throw $e;
            }

            // ── Process roles ───────────────────────────────────────
            $roles = [];
            try {
                if (in_array($request->lock_for_type, ['all_roles_except', 'selected_roles'])) {
                    $roles = array_values(array_filter($request->input('roles', [])));
                    $this->logOperation($operation, 'Processed roles', [
                        'configuration_id' => $lockConfiguration->id,
                        'lock_for_type'    => $request->lock_for_type,
                        'roles_count'      => count($roles),
                    ]);
                }
            } catch (Exception $e) {
                $this->logError($operation, 'Failed to process roles', $e, [
                    'configuration_id' => $lockConfiguration->id
                ]);
                throw $e;
            }

            // ── Update configuration ────────────────────────────────
            try {
                $lockConfiguration->update([
                    'name'             => $this->sanitizeString($request->name),
                    'description'      => $request->description ? $this->sanitizeString($request->description) : null,
                    'action_type'      => $request->action_type,
                    'selected_actions' => $selectedActions,
                    'field_type'       => $request->field_type,
                    'selected_fields'  => $selectedFields,
                    'lock_for_type'    => $request->lock_for_type,
                    'roles'            => $roles,
                ]);

                $this->logOperation($operation, 'Configuration updated in database', [
                    'configuration_id' => $lockConfiguration->id
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Failed to update configuration in database', $e, [
                    'configuration_id' => $lockConfiguration->id
                ]);
                throw $e;
            }

            DB::commit();
            $this->logOperation($operation, 'Database transaction committed', [
                'configuration_id' => $lockConfiguration->id
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Configuration updated successfully', [
                'configuration_id'  => $lockConfiguration->id,
                'old_data'          => $oldData,
                'new_data'          => $lockConfiguration->toArray(),
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('success', 'Lock Configuration "' . $lockConfiguration->name . '" updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            $this->logOperation($operation, 'Database transaction rolled back', [
                'configuration_id' => $lockConfiguration->id
            ], 'warning');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to update lock configuration', $e, [
                'configuration_id'  => $lockConfiguration->id,
                'execution_time_ms' => $executionTime,
                'request_data'      => $request->except(['_token', '_method']),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update lock configuration. Please try again.')
                ->withInput();
        }
    }

    // ================================================================
    //  DESTROY
    // ================================================================

    /**
     * Remove the specified lock configuration.
     */
    public function destroy(LockConfiguration $lockConfiguration): RedirectResponse
    {
        $operation = 'DESTROY';
        $startTime = microtime(true);
        
        try {
            $configId   = $lockConfiguration->id;
            $configName = $lockConfiguration->name;

            $this->logOperation($operation, 'Starting configuration deletion', [
                'configuration_id'   => $configId,
                'configuration_name' => $configName,
            ]);

            DB::beginTransaction();
            $this->logOperation($operation, 'Database transaction started', [
                'configuration_id' => $configId
            ]);

            // ── Delete the configuration ────────────────────────────
            try {
                $lockConfiguration->delete();
                
                $this->logOperation($operation, 'Configuration deleted from database', [
                    'configuration_id' => $configId
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Failed to delete configuration from database', $e, [
                    'configuration_id' => $configId
                ]);
                throw $e;
            }

            DB::commit();
            $this->logOperation($operation, 'Database transaction committed', [
                'configuration_id' => $configId
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Configuration deleted successfully', [
                'configuration_id'  => $configId,
                'name'              => $configName,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('success', 'Lock Configuration "' . $configName . '" deleted.');

        } catch (Exception $e) {
            DB::rollBack();
            $this->logOperation($operation, 'Database transaction rolled back', [
                'configuration_id' => $lockConfiguration->id ?? null
            ], 'warning');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to delete lock configuration', $e, [
                'configuration_id'  => $lockConfiguration->id ?? null,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('error', 'Failed to delete lock configuration. Please try again.');
        }
    }

    // ================================================================
    //  TOGGLE STATUS
    // ================================================================

    /**
     * Toggle the status of the specified lock configuration.
     */
    public function toggleStatus(LockConfiguration $lockConfiguration): RedirectResponse
    {
        $operation = 'TOGGLE_STATUS';
        $startTime = microtime(true);
        
        try {
            $configId  = $lockConfiguration->id;
            $oldStatus = $lockConfiguration->status;

            $this->logOperation($operation, 'Starting status toggle', [
                'configuration_id' => $configId,
                'current_status'   => $oldStatus,
            ]);

            DB::beginTransaction();
            $this->logOperation($operation, 'Database transaction started', [
                'configuration_id' => $configId
            ]);

            // ── Toggle status using SCHEMA allowed values ────────────
            try {
                $allowedStatuses = self::SCHEMA['status']['allowed'];
                $currentIndex    = array_search($oldStatus, $allowedStatuses);
                $newStatus       = $allowedStatuses[($currentIndex + 1) % count($allowedStatuses)];

                $lockConfiguration->update([
                    'status' => $newStatus,
                ]);

                $this->logOperation($operation, 'Status toggled in database', [
                    'configuration_id' => $configId,
                    'old_status'       => $oldStatus,
                    'new_status'       => $newStatus,
                ]);

            } catch (Exception $e) {
                $this->logError($operation, 'Failed to toggle status in database', $e, [
                    'configuration_id' => $configId
                ]);
                throw $e;
            }

            DB::commit();
            $this->logOperation($operation, 'Database transaction committed', [
                'configuration_id' => $configId
            ]);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logOperation($operation, 'Status toggled successfully', [
                'configuration_id'  => $configId,
                'old_status'        => $oldStatus,
                'new_status'        => $newStatus,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('success', 'Status changed to ' . $newStatus . ' for "' . $lockConfiguration->name . '".');

        } catch (Exception $e) {
            DB::rollBack();
            $this->logOperation($operation, 'Database transaction rolled back', [
                'configuration_id' => $lockConfiguration->id ?? null
            ], 'warning');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError($operation, 'Failed to toggle status', $e, [
                'configuration_id'  => $lockConfiguration->id ?? null,
                'execution_time_ms' => $executionTime,
            ]);

            return redirect()->route('lock_configuration.index')
                ->with('error', 'Failed to change status. Please try again.');
        }
    }

    // ================================================================
    //  VALIDATE CONFIG
    // ================================================================

    /**
     * Validate configuration data integrity
     */
    public function validateConfig(LockConfiguration $lockConfiguration): array
    {
        $operation = 'VALIDATE';
        $errors    = [];
        
        try {
            $configId = $lockConfiguration->id;
            
            $this->logOperation($operation, 'Starting configuration validation', [
                'configuration_id' => $configId
            ]);

            $config = $lockConfiguration->toArray();
            
            // Validate action type consistency
            if (in_array($config['action_type'], ['restrict_selected', 'allow_selected']) && empty($config['selected_actions'])) {
                $errors[] = 'Selected actions cannot be empty when action type is restrict_selected or allow_selected';
            }
            
            // Validate field type consistency
            if (in_array($config['field_type'], ['restrict_selected', 'allow_selected']) && empty($config['selected_fields'])) {
                $errors[] = 'Selected fields cannot be empty when field type is restrict_selected or allow_selected';
            }
            
            // Validate role consistency
            if (in_array($config['lock_for_type'], ['all_roles_except', 'selected_roles']) && empty($config['roles'])) {
                $errors[] = 'Roles cannot be empty when lock for type is all_roles_except or selected_roles';
            }

            // ── SCHEMA allowed values validate ──────────────────────
            if (!in_array($config['action_type'], self::SCHEMA['action_type']['allowed'])) {
                $errors[] = 'Invalid action_type value: ' . $config['action_type'];
            }

            if (!in_array($config['field_type'], self::SCHEMA['field_type']['allowed'])) {
                $errors[] = 'Invalid field_type value: ' . $config['field_type'];
            }

            if (!in_array($config['lock_for_type'], self::SCHEMA['lock_for_type']['allowed'])) {
                $errors[] = 'Invalid lock_for_type value: ' . $config['lock_for_type'];
            }

            if (!in_array($config['status'], self::SCHEMA['status']['allowed'])) {
                $errors[] = 'Invalid status value: ' . $config['status'];
            }
            
            if (!empty($errors)) {
                $this->logOperation($operation, 'Configuration validation failed', [
                    'configuration_id' => $configId,
                    'errors'           => $errors,
                ], 'warning');
            } else {
                $this->logOperation($operation, 'Configuration validation passed', [
                    'configuration_id' => $configId
                ]);
            }
            
        } catch (Exception $e) {
            $this->logError($operation, 'Error during configuration validation', $e, [
                'configuration_id' => $lockConfiguration->id ?? null
            ]);
            $errors[] = 'Failed to validate configuration: ' . $e->getMessage();
        }
        
        return $errors;
    }

    // ================================================================
    //  GET CONFIGURATIONS BY MODULE
    // ================================================================

    /**
     * Get configuration by module
     */
    public function getConfigurationsByModule(string $module): array
    {
        $operation = 'GET_BY_MODULE';
        
        try {
            $this->logOperation($operation, 'Fetching configurations by module', [
                'module' => $module
            ]);

            // Validate module against SCHEMA
            if (!in_array($module, self::SCHEMA['module']['allowed'])) {
                $this->logOperation($operation, 'Invalid module requested', [
                    'module'          => $module,
                    'allowed_modules' => self::SCHEMA['module']['allowed'],
                ], 'warning');
                return [];
            }

            $configs = LockConfiguration::where('module', $module)
                ->where('status', self::SCHEMA['status']['default'])
                ->get()
                ->toArray();

            $this->logOperation($operation, 'Configurations fetched successfully', [
                'module' => $module,
                'count'  => count($configs),
            ]);

            return $configs;
                
        } catch (Exception $e) {
            $this->logError($operation, 'Failed to fetch configurations by module', $e, [
                'module' => $module
            ]);
            return [];
        }
    }

    // ================================================================
    //  IS FIELD LOCKED
    // ================================================================

    /**
     * Check if field is locked for user role
     */
    public function isFieldLocked(string $field, string $userRole): bool
    {
        $operation = 'CHECK_FIELD_LOCK';
        
        try {
            // Validate field against SCHEMA
            if (!in_array($field, self::SCHEMA['selected_fields']['allowed'])) {
                $this->logOperation($operation, 'Invalid field requested', [
                    'field'          => $field,
                    'allowed_fields' => self::SCHEMA['selected_fields']['allowed'],
                ], 'warning');
                return false;
            }

            // Validate role against SCHEMA
            if (!in_array($userRole, self::SCHEMA['roles']['allowed'])) {
                $this->logOperation($operation, 'Invalid role requested', [
                    'user_role'     => $userRole,
                    'allowed_roles' => self::SCHEMA['roles']['allowed'],
                ], 'warning');
                return false;
            }

            $this->logOperation($operation, 'Checking if field is locked', [
                'field'     => $field,
                'user_role' => $userRole,
            ]);

            $configs = $this->getConfigurationsByModule(self::SCHEMA['module']['default']);
            
            foreach ($configs as $config) {
                $roleApplies = $this->doesRoleApply($config, $userRole);
                
                if (!$roleApplies) {
                    continue;
                }
                
                if ($this->isFieldAffected($config, $field)) {
                    $this->logOperation($operation, 'Field is locked by configuration', [
                        'field'     => $field,
                        'user_role' => $userRole,
                        'config_id' => $config['id'],
                    ]);
                    return true;
                }
            }
            
            $this->logOperation($operation, 'Field is not locked', [
                'field'     => $field,
                'user_role' => $userRole,
            ]);
            
            return false;
            
        } catch (Exception $e) {
            $this->logError($operation, 'Error checking field lock status', $e, [
                'field'     => $field,
                'user_role' => $userRole,
            ]);
            return false;
        }
    }

    // ================================================================
    //  DOES ROLE APPLY
    // ================================================================

    /**
     * Check if role applies to configuration
     */
    private function doesRoleApply(array $config, string $userRole): bool
    {
        try {
            switch ($config['lock_for_type']) {
                case 'all_roles':
                    return true;
                case 'all_roles_except':
                    return !in_array($userRole, $config['roles']);
                case 'selected_roles':
                    return in_array($userRole, $config['roles']);
                default:
                    return false;
            }
        } catch (Exception $e) {
            $this->logError('DOES_ROLE_APPLY', 'Error checking role application', $e, [
                'config'    => $config,
                'user_role' => $userRole,
            ]);
            return false;
        }
    }

    // ================================================================
    //  IS FIELD AFFECTED
    // ================================================================

    /**
     * Check if field is affected by configuration
     */
    private function isFieldAffected(array $config, string $field): bool
    {
        try {
            switch ($config['field_type']) {
                case 'restrict_all':
                    return true;
                case 'restrict_selected':
                    return in_array($field, $config['selected_fields']);
                case 'allow_selected':
                    return !in_array($field, $config['selected_fields']);
                default:
                    return false;
            }
        } catch (Exception $e) {
            $this->logError('IS_FIELD_AFFECTED', 'Error checking field affect', $e, [
                'config' => $config,
                'field'  => $field,
            ]);
            return false;
        }
    }
}