<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class CompositeItem extends Model
{
    use HasFactory, SoftDeletes;

    // ── Same 'products' table — item_type = 'composite_item' ──────
    protected $table = 'products';

    // ================================================================
    //  FILLABLE
    // ================================================================

    protected $fillable = [
        'user_id',
        'name',
        'item_type',
        'type',
        'product_image',
        'brand_id',
        'brand',
        'item_variant_type',
        'unit',
        'sku',
        'associate_item_details',
        'quantity',
        'selling_price',
        'cost_price',
        'track_inventory',
        'bin_location_tracking',
        'inventory_valuation_method',
        'reorder_point',
        'opening_stock',
        'is_returnable',
        'additional_data',
        'variants_data',
    ];

    // ================================================================
    //  CASTS
    // ================================================================

    protected $casts = [
        'selling_price'          => 'float',
        'cost_price'             => 'float',
        'reorder_point'          => 'float',
        'opening_stock'          => 'float',
        'quantity'               => 'float',
        'track_inventory'        => 'boolean',
        'bin_location_tracking'  => 'boolean',
        'is_returnable'          => 'boolean',
        'additional_data'        => 'array',
        'variants_data'          => 'array',
        'associate_item_details' => 'array',
        'product_image'          => 'array',
    ];

    protected $dates = ['deleted_at'];

    // ================================================================
    //  BOOT — Global scope: always composite_item only
    // ================================================================

    protected static function booted(): void
    {
        static::addGlobalScope('composite', function (Builder $builder) {
            $builder->where('item_type', 'composite_item');
        });

        static::creating(function (CompositeItem $model) {
            $model->item_type         = 'composite_item';
            $model->item_variant_type = 'single';
        });
    }

    // ================================================================
    //  CONSTANTS
    // ================================================================

    const TYPE_ASSEMBLY = 'assembly_item';
    const TYPE_KIT      = 'kit_item';
    

      public function assemblies()
    {
        return $this->hasMany(Assembly::class);
    }
    // ================================================================
    //  SCOPES
    // ================================================================

    public function scopeAssembly(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_ASSEMBLY);
    }

    public function scopeKit(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_KIT);
    }

    public function scopeTracked(Builder $query): Builder
    {
        return $query->where('track_inventory', true);
    }

    // ================================================================
    //  ACCESSOR — Front image URL
    // ================================================================

    public function getFrontImageUrlAttribute(): ?string
    {
        $image = $this->product_image;

        if (empty($image['front_image'])) {
            return null;
        }

        $path = $image['front_image'];

        if (str_starts_with($path, 'storage:')) {
            return \Storage::disk('public')->url(str_replace('storage:', '', $path));
        }

        return asset($path);
    }

    // ================================================================
    //  ACCESSORS — associate_item_details
    // ================================================================

    public function getAssociateItemsAttribute(): array
    {
        return $this->associate_item_details['items'] ?? [];
    }

    public function getAssociateServicesAttribute(): array
    {
        return $this->associate_item_details['services'] ?? [];
    }

    public function getAssociateTotalSellingAttribute(): float
    {
        return (float) ($this->associate_item_details['totals']['selling_price'] ?? 0);
    }

    public function getAssociateTotalCostAttribute(): float
    {
        return (float) ($this->associate_item_details['totals']['cost_price'] ?? 0);
    }

    // Items + Services combined
    public function getAllAssociateRowsAttribute(): array
    {
        $items    = collect($this->associate_items)->map(fn($i) => array_merge($i, ['row_type' => 'item']));
        $services = collect($this->associate_services)->map(fn($s) => array_merge($s, ['row_type' => 'service']));

        return $items->merge($services)->values()->toArray();
    }

    // ================================================================
    //  ACCESSORS — additional_data
    // ================================================================

    public function getDimensionsAttribute(): array
    {
        $d = $this->additional_data ?? [];
        return [
            'length'         => $d['length']         ?? null,
            'width'          => $d['width']          ?? null,
            'height'         => $d['height']         ?? null,
            'dimension_unit' => $d['dimension_unit'] ?? 'cm',
        ];
    }

    public function getWeightInfoAttribute(): array
    {
        $d = $this->additional_data ?? [];
        return [
            'weight'      => $d['weight']      ?? null,
            'weight_unit' => $d['weight_unit'] ?? 'kg',
        ];
    }

    public function getUpcAttribute(): ?string
    {
        return $this->additional_data['upc'] ?? null;
    }

    public function getMpnAttribute(): ?string
    {
        return $this->additional_data['mpn'] ?? null;
    }

    public function getEanAttribute(): ?string
    {
        return $this->additional_data['ean'] ?? null;
    }

    public function getIsbnAttribute(): ?string
    {
        return $this->additional_data['isbn'] ?? null;
    }

    public function getManufacturerNameAttribute(): ?string
    {
        return $this->additional_data['manufacturer'] ?? null;
    }

    public function getSalesDescriptionAttribute(): ?string
    {
        return $this->additional_data['description']['sales_description'] ?? null;
    }

    public function getPurchaseDescriptionAttribute(): ?string
    {
        return $this->additional_data['description']['purchase_description'] ?? null;
    }

    public function getInventoryAccountAttribute(): ?string
    {
        return $this->additional_data['account_details']['inventory_account'] ?? null;
    }

    public function getPreferredVendorIdAttribute(): ?string
    {
        return $this->additional_data['account_details']['preferred_vendor'] ?? null;
    }

    public function getCategoryInfoAttribute(): array
    {
        return $this->additional_data['category'] ?? ['id' => null, 'name' => null];
    }

    public function getCategoryNameAttribute(): ?string
    {
        return $this->additional_data['category']['name'] ?? null;
    }

    public function getSpecificTryValueAttribute(): ?string
    {
        return $this->additional_data['specific_try'] ?? null;
    }

    // ================================================================
    //  HELPERS
    // ================================================================

    public function isAssembly(): bool
    {
        return $this->type === self::TYPE_ASSEMBLY;
    }

    public function isKit(): bool
    {
        return $this->type === self::TYPE_KIT;
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_ASSEMBLY => 'Assembly Item',
            self::TYPE_KIT      => 'Kit Item',
            default             => ucfirst($this->type ?? ''),
        };
    }

    // ================================================================
    //  RELATIONSHIPS
    // ================================================================

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function stocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function histories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(History::class, 'record_id')
                    ->where('module', 'product')
                    ->latest();
    }

    // associate_items-la irukka product_id-la irundhu Product records
    public function componentProducts(): \Illuminate\Database\Eloquent\Collection
    {
        $ids = collect($this->associate_items)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($ids)) {
            return collect();
        }

        return Product::whereIn('id', $ids)
            ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price', 'product_image')
            ->get()
            ->keyBy('id');
    }

    // associate_services-la irukka product_id-la irundhu Product records
    public function componentServices(): \Illuminate\Database\Eloquent\Collection
    {
        $ids = collect($this->associate_services)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($ids)) {
            return collect();
        }

        return Product::whereIn('id', $ids)
            ->select('id', 'name', 'sku', 'unit', 'selling_price', 'cost_price')
            ->get()
            ->keyBy('id');
    }

    // ================================================================
    //  STOCK HELPERS
    // ================================================================

    public function getTotalStockOnHandAttribute(): float
    {
        return (float) $this->stocks()->whereNull('deleted_at')->sum('stock_on_hand');
    }

    public function getTotalAvailableStockAttribute(): float
    {
        return (float) $this->stocks()->whereNull('deleted_at')->sum('available_stock');
    }

    public function getTotalCommittedStockAttribute(): float
    {
        return (float) $this->stocks()->whereNull('deleted_at')->sum('committed_stock');
    }
}