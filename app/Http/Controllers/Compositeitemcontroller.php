<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CompositeItemController extends Controller
{
    // ================================================================
    //  SCHEMA
    // ================================================================

    const SCHEMA = [
        'name'                       => ['type' => 'string',  'default' => null, 'max' => 255, 'nullable' => false],
        'type'                       => ['type' => 'string',  'default' => 'assembly_item', 'allowed' => ['assembly_item', 'kit_item']],
        'brand_id'                   => ['type' => 'integer', 'default' => null, 'nullable' => true],
        'unit'                       => ['type' => 'string',  'default' => null, 'max' => 50],
        'sku'                        => ['type' => 'string',  'default' => null, 'max' => 255, 'nullable' => true],
        'selling_price'              => ['type' => 'float',   'default' => null, 'min' => 0, 'nullable' => true],
        'cost_price'                 => ['type' => 'float',   'default' => null, 'min' => 0, 'nullable' => true],
        'track_inventory'            => ['type' => 'boolean', 'default' => true],
        'bin_location_tracking'      => ['type' => 'boolean', 'default' => false],
        'inventory_valuation_method' => ['type' => 'string',  'default' => null, 'allowed' => ['FIFO', 'Weighted Average'], 'nullable' => true],
        'reorder_point'              => ['type' => 'float',   'default' => null, 'min' => 0, 'nullable' => true],
        'is_returnable'              => ['type' => 'boolean', 'default' => true],
        'custom_field' => [
            'length'         => ['type' => 'float',  'default' => null, 'min' => 0, 'nullable' => true],
            'width'          => ['type' => 'float',  'default' => null, 'min' => 0, 'nullable' => true],
            'height'         => ['type' => 'float',  'default' => null, 'min' => 0, 'nullable' => true],
            'dimension_unit' => ['type' => 'string', 'default' => 'cm', 'allowed' => ['cm', 'm', 'in', 'ft', 'mm']],
            'weight'         => ['type' => 'float',  'default' => null, 'min' => 0, 'nullable' => true],
            'weight_unit'    => ['type' => 'string', 'default' => 'kg', 'allowed' => ['kg', 'g', 'lb', 'oz', 'mg']],
        ],
    ];

    // ================================================================
    //  LOGGING HELPERS
    // ================================================================

    private function logOp(string $operation, string $message, array $context = [], string $level = 'info'): void
    {
        $context = array_merge([
            'user_id'    => auth()->id(),
            'user_email' => auth()->user()->email ?? 'system',
            'ip_address' => request()->ip(),
            'timestamp'  => now()->toDateTimeString(),
        ], $context);

        Log::$level("[COMPOSITE][{$operation}] {$message}", $context);
    }

    private function logError(string $operation, string $message, \Throwable $e, array $context = []): void
    {
        $this->logOp($operation, $message, array_merge($context, [
            'exception_message' => $e->getMessage(),
            'exception_file'    => $e->getFile(),
            'exception_line'    => $e->getLine(),
        ]), 'error');

        report($e);
    }

    private function clearCache(?int $productId = null): void
    {
        try {
            Cache::forget('composite_items_list');
            if ($productId) Cache::forget("composite.{$productId}");
        } catch (\Exception $e) {
            $this->logError('CACHE_ERROR', 'Failed to clear cache', $e);
        }
    }

    // ================================================================
    //  SANITIZE HELPERS
    // ================================================================

    private function sanitizeData(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            if ($key === 'custom_field') continue;
            $schema = self::SCHEMA[$key] ?? null;
            if (!$schema) continue;

            if (($schema['nullable'] ?? false) && is_null($value)) {
                $sanitized[$key] = null;
                continue;
            }

            if (is_string($value)) {
                $value = htmlspecialchars(trim(strip_tags($value)), ENT_QUOTES, 'UTF-8');
            }

            $value = $this->castValue($value, $schema['type']);

            if (isset($schema['min']) && is_numeric($value)) {
                $value = max($schema['min'], $value);
            }

            if (isset($schema['allowed']) && !in_array($value, $schema['allowed'], true)) {
                $value = $schema['default'];
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }

    private function sanitizeCustomField(array $input): array
    {
        $sanitized = [];

        foreach (self::SCHEMA['custom_field'] as $key => $schema) {
            $value = $input[$key] ?? $schema['default'];

            if (($schema['nullable'] ?? false) && is_null($value)) {
                $sanitized[$key] = null;
                continue;
            }

            $value = $this->castValue($value, $schema['type']);

            if (isset($schema['min']) && is_numeric($value)) {
                $value = max($schema['min'], $value);
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
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'array'   => is_array($value) ? $value : [],
            default   => $value,
        };
    }

    // ================================================================
    //  IMAGE HELPERS
    // ================================================================

    private function uploadImage($file): string
    {
        $publicFolder = public_path('image/product_img');
        if (!is_dir($publicFolder)) {
            mkdir($publicFolder, 0755, true);
        }

        $mime    = $file->getMimeType();
        $srcPath = $file->getRealPath();

        $src = match(true) {
            str_contains($mime, 'png')  => imagecreatefrompng($srcPath),
            str_contains($mime, 'gif')  => imagecreatefromgif($srcPath),
            default                     => imagecreatefromjpeg($srcPath),
        };

        if (!$src) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($publicFolder, $filename);
            return 'image/product_img/' . $filename;
        }

        $origW  = imagesx($src);
        $origH  = imagesy($src);
        $maxDim = 1920;

        if ($origW > $maxDim || $origH > $maxDim) {
            $ratio   = min($maxDim / $origW, $maxDim / $origH);
            $newW    = (int) round($origW * $ratio);
            $newH    = (int) round($origH * $ratio);
            $resized = imagecreatetruecolor($newW, $newH);
            imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $resized;
        }

        $filename = time() . '_' . uniqid() . '.jpg';
        $destPath = $publicFolder . DIRECTORY_SEPARATOR . $filename;

        $quality = 85;
        ob_start();
        imagejpeg($src, null, $quality);
        $imgData = ob_get_clean();

        while (strlen($imgData) > 800 * 1024 && $quality > 50) {
            $quality -= 5;
            ob_start();
            imagejpeg($src, null, $quality);
            $imgData = ob_get_clean();
        }

        file_put_contents($destPath, $imgData);
        imagedestroy($src);

        return 'image/product_img/' . $filename;
    }

    private function deleteImage(string $path): void
    {
        try {
            if (str_starts_with($path, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $path));
            } else {
                $full = public_path($path);
                if (file_exists($full)) unlink($full);
            }
        } catch (\Exception $e) {
            $this->logError('IMAGE_DELETE', 'Failed to delete image', $e, ['path' => $path]);
        }
    }
private function getAvailableItems(): \Illuminate\Support\Collection
{
    return Product::where('item_type', 'item')
        ->where('type', 'goods')
        ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price', 'product_image', 'variants_data', 'item_variant_type')
        ->orderBy('name')
        ->get()
        ->flatMap(function ($product) {
            $imageData = is_string($product->product_image)
                ? json_decode($product->product_image, true)
                : ($product->product_image ?? []);

            if ($product->item_variant_type === 'contains_variants') {
                $variantsData = is_string($product->variants_data)
                    ? json_decode($product->variants_data, true)
                    : ($product->variants_data ?? []);

                $variants = $variantsData['variants'] ?? [];

                if (!empty($variants)) {
                    return collect($variants)->map(function ($variant) use ($product, $imageData) {
                        // ItemVariant table-ல் DB id எடு
                        $dbVariantId = \App\Models\ItemVariant::where('item_id', $product->id)
                            ->where('name', $variant['name'] ?? '')
                            ->value('id');

                        return [
                            // ── KEY FIX: "v_{id}" prefix use பண்ணு overlap தவிர்க்க ──
                            'id'            => $dbVariantId ? 'v_' . $dbVariantId : 'vp_' . $product->id . '_' . ($variant['name'] ?? ''),
                            'name'          => $product->name . ' (' . ($variant['name'] ?? '') . ')',
                            'sku'           => $variant['sku'] ?? $product->sku,
                            'unit'          => $product->unit,
                            'selling_price' => (float) ($variant['selling_price'] ?? 0),
                            'cost_price'    => (float) ($variant['cost_price'] ?? 0),
                            'product_image' => $imageData,
                            'is_variant'    => true,
                            'variant_db_id' => $dbVariantId,
                            'parent_id'     => $product->id,
                        ];
                    });
                }
            }

            return [[
                'id'            => $product->id,  // plain integer — no prefix
                'name'          => $product->name,
                'sku'           => $product->sku,
                'unit'          => $product->unit,
                'selling_price' => (float) ($product->selling_price ?? 0),
                'cost_price'    => (float) ($product->cost_price ?? 0),
                'product_image' => $imageData,
                'is_variant'    => false,
                'variant_db_id' => null,
                'parent_id'     => null,
            ]];
        })
        ->values();
}
    private function getAvailableServices(): \Illuminate\Support\Collection
    {
        return Product::where('item_type', 'item')
            ->where('type', 'service')
            ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price', 'product_image')
            ->orderBy('name')
            ->get()
            ->map(function ($p) {
                $imageData = is_string($p->product_image)
                    ? json_decode($p->product_image, true)
                    : ($p->product_image ?? []);
                return [
                    'id'            => $p->id,
                    'name'          => $p->name,
                    'sku'           => $p->sku,
                    'unit'          => $p->unit,
                    'selling_price' => (float) ($p->selling_price ?? 0),
                    'cost_price'    => (float) ($p->cost_price ?? 0),
                    'product_image' => $imageData,
                ];
            });
    }

    // ================================================================
    //  ASSOCIATE ITEMS PARSER
    // ================================================================

   private function parseAssociateItems(Request $request, array $existing = []): array
    {
        $raw = $request->input('associate_items_json');

        if (empty($raw)) {
            return $existing ?: [
                'items'    => [],
                'services' => [],
                'totals'   => ['selling_price' => 0, 'cost_price' => 0],
            ];
        }

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return ['items' => [], 'services' => [], 'totals' => ['selling_price' => 0, 'cost_price' => 0]];
        }

        $items    = [];
        $services = [];

        // ── Items ──
       foreach (($decoded['items'] ?? []) as $row) {
    $rawId     = $row['product_id'] ?? 0;
    $isVariant = is_string($rawId) && str_starts_with($rawId, 'v_');

    if ($isVariant) {
        // v_123 → 123
        $variantDbId = (int) str_replace('v_', '', $rawId);
        $itemVariant = \App\Models\ItemVariant::find($variantDbId);
        if (!$itemVariant) continue;

        $parent  = Product::find($itemVariant->item_id);
        $product = (object) [
            'id'            => $rawId,
            'name'          => ($parent->name ?? '') . ' (' . ($itemVariant->name ?? '') . ')',
            'sku'           => $itemVariant->sku,
            'unit'          => $parent->unit ?? null,
            'selling_price' => (float) ($itemVariant->selling_price ?? 0),
            'cost_price'    => (float) ($itemVariant->cost_price    ?? 0),
        ];
    } else {
        $productId = (int) $rawId;
        if (!$productId) continue;

        $product = Product::select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price')
                           ->find($productId);
        if (!$product) continue;
    }

    $qty = max(0, (float) ($row['quantity']      ?? 1));
    $sp  = max(0, (float) ($row['selling_price'] ?? $product->selling_price ?? 0));
    $cp  = max(0, (float) ($row['cost_price']    ?? $product->cost_price    ?? 0));

    $items[] = [
        'product_id'         => $rawId,   // v_123 or plain int
        'name'               => $product->name,
        'sku'                => $product->sku,
        'unit'               => $product->unit,
        'quantity'           => $qty,
        'selling_price'      => $sp,
        'cost_price'         => $cp,
        'line_total_selling' => round($qty * $sp, 2),
        'line_total_cost'    => round($qty * $cp, 2),
    ];
}
        // ── Services ──
        foreach (($decoded['services'] ?? []) as $row) {
            $productId = (int) ($row['product_id'] ?? 0);
            if (!$productId) continue;

            $service = Product::select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price')
                               ->find($productId);
            if (!$service) continue;

            $qty = max(0, (float) ($row['quantity']      ?? 1));
            $sp  = max(0, (float) ($row['selling_price'] ?? $service->selling_price ?? 0));
            $cp  = max(0, (float) ($row['cost_price']    ?? $service->cost_price    ?? 0));

            $services[] = [
                'product_id'         => $productId,
                'name'               => $service->name,
                'sku'                => $service->sku,
                'unit'               => $service->unit,
                'quantity'           => $qty,
                'selling_price'      => $sp,
                'cost_price'         => $cp,
                'line_total_selling' => round($qty * $sp, 2),
                'line_total_cost'    => round($qty * $cp, 2),
            ];
        }

        $totalSp = collect($items)->sum('line_total_selling') + collect($services)->sum('line_total_selling');
        $totalCp = collect($items)->sum('line_total_cost')    + collect($services)->sum('line_total_cost');

        return [
            'items'    => $items,
            'services' => $services,
            'totals'   => [
                'selling_price' => round($totalSp, 2),
                'cost_price'    => round($totalCp, 2),
            ],
        ];
    }

    // ================================================================
    //  VALIDATION RULES
    // ================================================================

    private function validationRules(?int $ignoreId = null): array
    {
        $skuUnique = 'nullable|string|unique:products,sku' . ($ignoreId ? ",{$ignoreId}" : '');

        return [
            'name'                        => ($ignoreId ? 'sometimes|' : '') . 'required|string|max:255',
            'type'                        => ($ignoreId ? 'sometimes|' : '') . 'required|in:assembly_item,kit_item',
            'unit'                        => ($ignoreId ? 'sometimes|' : '') . 'required|string|max:50',
            'sku'                         => $skuUnique,
            'brand_id'                    => 'nullable|exists:brands,id',
            'selling_price'               => 'nullable|numeric|min:0',
            'cost_price'                  => 'nullable|numeric|min:0',
            'is_returnable'               => 'nullable|boolean',
            'track_inventory'             => 'nullable|boolean',
            'bin_location_tracking'       => 'nullable|boolean',
            'inventory_valuation_method'  => 'nullable|in:FIFO,Weighted Average',
            'reorder_point'               => 'nullable|numeric|min:0',
            'sales_description'           => 'nullable|string',
            'purchase_description'        => 'nullable|string',
            'custom_field'                => 'nullable|array',
            'custom_field.length'         => 'nullable|numeric',
            'custom_field.width'          => 'nullable|numeric',
            'custom_field.height'         => 'nullable|numeric',
            'custom_field.dimension_unit' => 'nullable|in:cm,m,in,ft,mm',
            'custom_field.weight'         => 'nullable|numeric',
            'custom_field.weight_unit'    => 'nullable|in:kg,g,lb,oz,mg',
            'front_image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_front_image'          => 'nullable|boolean',
            'associate_items_json'        => 'nullable|string',
            'upc'                         => 'nullable|string|max:255',
            'mpn'                         => 'nullable|string|max:255',
            'ean'                         => 'nullable|string|max:255',
            'isbn'                        => 'nullable|string|max:255',
            'inventory_account_id'        => 'nullable|string',
            'preferred_vendor_id'         => 'nullable|string',
            'category_id'                 => 'nullable|integer',
            'category_name'               => 'nullable|string|max:255',
        ];
    }

    // ================================================================
    //  INDEX
    // ================================================================

    public function index(Request $request)
    {
        try {
            $query = Product::where('item_type', 'composite_item');

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(fn($q) => $q->where('name', 'LIKE', "%{$s}%")
                                          ->orWhere('sku', 'LIKE', "%{$s}%"));
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            $products = $query->latest()->paginate($request->per_page ?? 15);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'data' => $products]);
            }

            $customFields = collect();

            return view('products.composite.index', compact('products', 'customFields'));

        } catch (\Exception $e) {
            \Log::error('Composite index error: ' . $e->getMessage());

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $e->getMessage()], 500)
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ================================================================
    //  CREATE
    // ================================================================

    public function create()
    {
        try {
            $brands       = Brand::orderBy('name')->get();
            $customFields = \App\Models\AdditionalSetting::where('status', 'active')
                ->where('category_name', 'products')->get();

            $settingRow = \App\Models\SettingHandle::where('category_name', 'products')->first();
            $settings   = $settingRow?->config ?? [];

            // ── Shared helpers — variants flatten included ──
            $availableItems    = $this->getAvailableItems();
            $availableServices = $this->getAvailableServices();

            return view('products.composite.create',
                compact('brands', 'customFields', 'settings', 'availableItems', 'availableServices'));

        } catch (\Exception $e) {
            $this->logError('CREATE_VIEW', 'Failed to load form', $e);
            return redirect()->back()->with('error', 'Failed to load form');
        }
    }

    // ================================================================
    //  STORE
    // ================================================================

    public function store(Request $request)
    {
        $startTime      = microtime(true);
        $frontImagePath = null;

        try {
            $validator = Validator::make($request->all(), $this->validationRules());

            if ($validator->fails()) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                    : redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            if ($request->hasFile('front_image')) {
                $frontImagePath = $this->uploadImage($request->file('front_image'));
            }

            $brandName = $request->brand_id
                ? Brand::find($request->brand_id)?->name
                : null;

            $sanitized = $this->sanitizeData([
                'name'                       => $request->name,
                'type'                       => $request->type,
                'brand_id'                   => $request->brand_id,
                'unit'                       => $request->unit,
                'sku'                        => $request->sku,
                'selling_price'              => $request->selling_price,
                'cost_price'                 => $request->cost_price,
                'track_inventory'            => $request->boolean('track_inventory', true),
                'bin_location_tracking'      => $request->boolean('bin_location_tracking', false),
                'inventory_valuation_method' => $request->inventory_valuation_method,
                'reorder_point'              => $request->reorder_point,
                'is_returnable'              => $request->boolean('is_returnable', true),
            ]);

            $associateItemDetails = $this->parseAssociateItems($request);

            $additionalData = array_merge(
                $this->sanitizeCustomField($request->input('custom_field', [])),
                [
                    'upc'  => $request->upc,
                    'mpn'  => $request->mpn,
                    'ean'  => $request->ean,
                    'isbn' => $request->isbn,
                ],
                [
                    'account_details' => [
                        'inventory_account' => $request->inventory_account_id,
                        'preferred_vendor'  => $request->preferred_vendor_id,
                    ],
                    'description' => [
                        'sales_description'    => $request->sales_description,
                        'purchase_description' => $request->purchase_description,
                    ],
                    'category' => [
                        'id'   => $request->category_id ? (int) $request->category_id : null,
                        'name' => $request->category_name,
                    ],
                ]
            );

            $product = Product::create(array_merge($sanitized, [
                'item_type'              => 'composite_item',
                'item_variant_type'      => 'single',
                'brand'                  => $brandName,
                'product_image'          => json_encode(['front_image' => $frontImagePath]),
                'associate_item_details' => $associateItemDetails,
                'additional_data'        => $additionalData,
            ]));

            DB::commit();
            $this->clearCache();

            $this->logOp('STORE', 'Composite item created', [
                'product_id'        => $product->id,
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            ]);

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Composite item created successfully', 'data' => $product], 201)
                : redirect()->route('composite-items.index')->with('success', 'Composite item created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($frontImagePath) $this->deleteImage($frontImagePath);
            $this->logError('STORE', 'Failed to create', $e);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Failed to create composite item'], 500)
                : redirect()->back()->with('error', 'Failed to create: ' . $e->getMessage())->withInput();
        }
    }

    // ================================================================
    //  SHOW
    // ================================================================

    public function show(Request $request, $id)
    {
        try {
            $product  = Product::where('item_type', 'composite_item')->findOrFail($id);
            $products = Product::where('item_type', 'composite_item')->latest()->get();

            $associateItemDetails = $product->associate_item_details ?? [];

            $stockLocations = Location::orderBy('location_name')->get()->map(function ($loc) use ($product) {
                $stock = \App\Models\Stock::where('product_id', $product->id)
                    ->where('location_id', $loc->id)->whereNull('deleted_at')->first();
                return [
                    'id'             => $loc->id,
                    'location_name'  => $loc->location_name,
                    'location_type'  => $loc->location_type,
                    'stock_on_hand'  => $stock?->stock_on_hand  ?? 0,
                    'committed'      => $stock?->committed_stock ?? 0,
                    'available'      => $stock?->available_stock ?? 0,
                    'value_per_unit' => $stock?->value_per_unit  ?? 0,
                ];
            });

            $histories = \App\Models\History::where('module', 'product')
                ->where('record_id', $product->id)
                ->with('user')->latest()->get();

            $settingRow = \App\Models\SettingHandle::where('category_name', 'products')->first();
            $settings   = $settingRow?->config ?? [];

            return view('products.composite.show',
                compact('product', 'products', 'stockLocations', 'histories', 'settings', 'associateItemDetails'));

        } catch (\Exception $e) {
            $this->logError('SHOW', 'Failed to load', $e, ['id' => $id]);
            return redirect()->route('composite-items.index')->with('error', 'Composite item not found');
        }
    }

    // ================================================================
    //  EDIT
    // ================================================================

    public function edit($id)
    {
        try {
            $product = Product::where('item_type', 'composite_item')->findOrFail($id);

            $associateData    = $product->associate_item_details ?? [];
            $existingItems    = collect($associateData['items']    ?? []);
            $existingServices = collect($associateData['services'] ?? []);

            // ── Shared helpers — variants flatten included ──
            $availableItems    = $this->getAvailableItems();
            $availableServices = $this->getAvailableServices();

            $brands       = Brand::orderBy('name')->get();
            $customFields = \App\Models\AdditionalSetting::where('status', 'active')
                              ->where('category_name', 'products')->get();

            $settingRow = \App\Models\SettingHandle::where('category_name', 'products')->first();
            $settings   = $settingRow?->config ?? [];

            $additionalData = is_string($product->additional_data)
                ? json_decode($product->additional_data, true)
                : ($product->additional_data ?? []);

            $productImageData = is_string($product->product_image)
                ? json_decode($product->product_image, true)
                : ($product->product_image ?? []);

            $product->parsed_image      = $productImageData;
            $product->parsed_additional = $additionalData;

            return view('products.composite.edit', compact(
                'product',
                'availableItems',
                'availableServices',
                'brands',
                'customFields',
                'settings',
                'existingItems',
                'existingServices'
            ));

        } catch (\Exception $e) {
            $this->logError('EDIT_VIEW', 'Failed to load edit form', $e, ['id' => $id]);
            return redirect()->route('composite-items.index')->with('error', 'Failed to load edit form');
        }
    }

    // ================================================================
    //  UPDATE
    // ================================================================

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), $this->validationRules($id));
            if ($validator->fails()) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                    : redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            // ── existing additional_data decode ──
            $existingAdditional = $product->additional_data;
            if (is_string($existingAdditional)) {
                $existingAdditional = json_decode($existingAdditional, true) ?? [];
            }
            if (!is_array($existingAdditional)) {
                $existingAdditional = [];
            }

            $cf = $request->input('custom_field', []);

            $additionalData = array_merge($existingAdditional, [
                'length'         => $cf['length']         ?? null,
                'width'          => $cf['width']          ?? null,
                'height'         => $cf['height']         ?? null,
                'weight'         => $cf['weight']         ?? null,
                'dimension_unit' => $cf['dimension_unit'] ?? 'cm',
                'weight_unit'    => $cf['weight_unit']    ?? 'kg',
                'category' => [
                    'id'   => $request->category_id,
                    'name' => $request->category_name,
                ],
                'description' => [
                    'sales_description'    => $request->sales_description,
                    'purchase_description' => $request->purchase_description,
                ],
                'upc'  => $request->upc,
                'mpn'  => $request->mpn,
                'ean'  => $request->ean,
                'isbn' => $request->isbn,
                'account_details' => [
                    'inventory_account' => $request->inventory_account_id,
                    'preferred_vendor'  => $request->preferred_vendor_id,
                ],
            ]);

            // ── Image handle ──
            $imageData = $product->product_image;
            if (is_string($imageData)) {
                $imageData = json_decode($imageData, true) ?? [];
            }

            if ($request->input('remove_image') == '1') {
                if (!empty($imageData['front_image'])) {
                    $this->deleteImage($imageData['front_image']);
                }
                $imageData['front_image'] = null;
            }

            if ($request->hasFile('front_image')) {
                if (!empty($imageData['front_image'])) {
                    $this->deleteImage($imageData['front_image']);
                }
                $imageData['front_image'] = $this->uploadImage($request->file('front_image'));
            }

            // ── Associate items — variant support included ──
            $associateItemDetails = $this->parseAssociateItems($request,
                $product->associate_item_details ?? []
            );

            $brandName = $request->brand_id
                ? Brand::find($request->brand_id)?->name
                : null;

            $product->update([
                'name'                       => $request->name,
                'type'                       => $request->type,
                'sku'                        => $request->sku,
                'unit'                       => $request->unit,
                'selling_price'              => $request->selling_price,
                'cost_price'                 => $request->cost_price,
                'is_returnable'              => $request->boolean('is_returnable'),
                'brand_id'                   => $request->brand_id,
                'brand'                      => $brandName,
                'bin_location_tracking'      => $request->boolean('bin_location_tracking'),
                'inventory_valuation_method' => $request->inventory_valuation_method,
                'reorder_point'              => $request->reorder_point,
                'product_image'              => json_encode($imageData),
                'associate_item_details'     => $associateItemDetails,
                'additional_data'            => $additionalData,
            ]);

            DB::commit();
            $this->clearCache($id);

            $this->logOp('UPDATE', 'Composite item updated', ['product_id' => $id]);

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Updated successfully'])
                : redirect()->route('composite-items.index')->with('success', 'Updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('UPDATE', 'Failed to update', $e, ['id' => $id]);

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Failed to update'], 500)
                : redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage())->withInput();
        }
    }

    // ================================================================
    //  DESTROY
    // ================================================================

    public function destroy($id)
    {
        try {
            $product = Product::where('item_type', 'composite_item')->findOrFail($id);

            DB::beginTransaction();

            $imageData  = is_string($product->product_image)
                ? json_decode($product->product_image, true)
                : ($product->product_image ?? []);
            $frontImage = $imageData['front_image'] ?? null;
            if ($frontImage) $this->deleteImage($frontImage);

            $product->delete();

            DB::commit();
            $this->clearCache($id);

            return request()->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Deleted successfully'])
                : redirect()->route('composite-items.index')->with('success', 'Deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('DESTROY', 'Failed to delete', $e, ['id' => $id]);
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Failed to delete'], 500)
                : redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    // ================================================================
    //  RESTORE
    // ================================================================

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::withTrashed()->where('item_type', 'composite_item')->findOrFail($id);
            $product->restore();

            DB::commit();
            $this->clearCache($id);

            return request()->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Restored successfully'])
                : redirect()->route('composite-items.index')->with('success', 'Restored successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('RESTORE', 'Failed to restore', $e, ['id' => $id]);
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Failed to restore'], 500)
                : redirect()->back()->with('error', 'Failed to restore: ' . $e->getMessage());
        }
    }

    // ================================================================
    //  SEARCH PRODUCTS API
    //  GET /composite-items/search-products?q=abc&type=goods|service
    // ================================================================

    public function searchProducts(Request $request)
    {
        $q    = $request->input('q', '');
        $type = $request->input('type', 'goods');

        // goods type-க்கு variants-உம் include பண்ணு
        if ($type === 'goods') {
            $results = Product::where('item_type', 'item')
                ->where('type', 'goods')
                ->with('variants')
                ->where(fn($q2) => $q2->where('name', 'LIKE', "%{$q}%")->orWhere('sku', 'LIKE', "%{$q}%"))
                ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price')
                ->orderBy('name')
                ->limit(30)
                ->get()
                ->flatMap(function ($p) {
                    if ($p->variants && $p->variants->count() > 0) {
                        return $p->variants->map(fn($v) => [
                            'id'            => $v->id,
                            'name'          => $p->name . ' (' . $v->name . ')',
                            'sku'           => $v->sku ?? $p->sku,
                            'unit'          => $p->unit,
                            'selling_price' => (float) ($v->selling_price ?? 0),
                            'cost_price'    => (float) ($v->cost_price    ?? 0),
                        ]);
                    }
                    return [[
                        'id'            => $p->id,
                        'name'          => $p->name,
                        'sku'           => $p->sku,
                        'unit'          => $p->unit,
                        'selling_price' => (float) ($p->selling_price ?? 0),
                        'cost_price'    => (float) ($p->cost_price    ?? 0),
                    ]];
                })
                ->values();
        } else {
            $results = Product::where('item_type', 'item')
                ->where('type', $type)
                ->where(fn($q2) => $q2->where('name', 'LIKE', "%{$q}%")->orWhere('sku', 'LIKE', "%{$q}%"))
                ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price')
                ->orderBy('name')
                ->limit(30)
                ->get()
                ->map(fn($p) => [
                    'id'            => $p->id,
                    'name'          => $p->name,
                    'sku'           => $p->sku,
                    'unit'          => $p->unit,
                    'selling_price' => (float) ($p->selling_price ?? 0),
                    'cost_price'    => (float) ($p->cost_price    ?? 0),
                ]);
        }

        return response()->json(['success' => true, 'data' => $results]);
    }
}