<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

  protected $fillable = [
    'name', 'brand_id', 'brand', 'type', 'item_type',  // ← item_type add பண்ணுங்க
    'item_variant_type',
    'unit', 'sku',
    'selling_price', 'sales_description',
    'cost_price', 'purchase_description',
    'track_inventory', 'bin_location_tracking',
    'inventory_valuation_method', 'reorder_point',
    'is_returnable',
    'product_image',
    'additional_data',
    'variants_data',
    'associate_item_details',  
    'opening_stock', 
    'access_product', 
];

protected $casts = [
    'selling_price'     => 'decimal:2',
    'cost_price'        => 'decimal:2',
    'reorder_point'     => 'decimal:2',
    'track_inventory'   => 'boolean',
    'bin_location_tracking' => 'boolean',
    'is_returnable'     => 'boolean',
    'additional_data'   => 'array',
    'variants_data'         => 'array',  // ← JSON auto array
    'associate_item_details'=> 'array',
    'access_product' => 'boolean',
    ];
    // Scopes for easy filtering
    public function scopeGoods($query)
    {
        return $query->where('type', 'goods');
    }

    public function scopeService($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeTracked($query)
    {
        return $query->where('track_inventory', true);
    }
    //Stock

    // Product.php la add pannunga:
public function stocks()
{
    return $this->hasMany(\App\Models\Stock::class);
}

public function stockAtLocation(int $locationId)
{
    return $this->stocks()->where('location_id', $locationId)->first();
}
    /**
     * Get custom field text
     */
    public function getCustomFieldTextAttribute()
    {
        return $this->custom_field['text'] ?? $this->custom_field ?? '';
    }

    /**
     * Get all images from custom_field
     */
    public function getImagesAttribute()
    {
        return $this->custom_field['images'] ?? [];
    }

    /**
     * Get front image URL
     */
    public function getFrontImageUrlAttribute()
    {
        $images = $this->images;
        $frontImage = $images['front_image'] ?? null;
        
        return $frontImage ? asset('image/product_img/' . $frontImage) : null;
    }

    /**
     * Get rear image URL
     */
    public function getRearImageUrlAttribute()
    {
        $images = $this->images;
        $rearImage = $images['rear_image'] ?? null;
        
        return $rearImage ? asset('image/product_img/' . $rearImage) : null;
    }

    /**
     * Get other images URLs
     */
    public function getOtherImagesUrlAttribute()
    {
        $images = $this->images;
        $otherImages = $images['other_images'] ?? [];
        
        return collect($otherImages)->map(function ($path) {
            return asset('image/product_img/' . $path);
        })->toArray();
    }

    /**
     * Get all image URLs with labels
     */
    public function getAllImagesWithLabelsAttribute()
    {
        $images = $this->images;
        $result = [];
        
        if (isset($images['front_image'])) {
            $result['front'] = [
                'path' => $images['front_image'],
                'url' => asset('storage/' . $images['front_image']),
                'label' => 'Front View'
            ];
        }
        
        if (isset($images['rear_image'])) {
            $result['rear'] = [
                'path' => $images['rear_image'],
                'url' => asset('storage/' . $images['rear_image']),
                'label' => 'Rear View'
            ];
        }
        
        if (isset($images['other_images']) && is_array($images['other_images'])) {
            foreach ($images['other_images'] as $index => $path) {
                $result['other_' . $index] = [
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'label' => 'Other Image ' . ($index + 1)
                ];
            }
        }
        
        return $result;
    }

    /**
     * Check if product has any images
     */
    public function getHasImagesAttribute()
    {
        return !empty($this->images);
    }

    /**
     * Get primary image URL (front first, then rear, then first other)
     */
    public function getPrimaryImageUrlAttribute()
    {
        $images = $this->images;
        
        if (isset($images['front_image'])) {
            return asset('storage/' . $images['front_image']);
        }
        
        if (isset($images['rear_image'])) {
            return asset('storage/' . $images['rear_image']);
        }
        
        if (isset($images['other_images']) && !empty($images['other_images'])) {
            return asset('storage/' . $images['other_images'][0]);
        }
        
        // Return default image if no images
        return asset('images/no-image.png');
    }

    /**
     * Get image count
     */
    public function getImageCountAttribute()
    {
        $images = $this->images;
        $count = 0;
        
        if (isset($images['front_image'])) $count++;
        if (isset($images['rear_image'])) $count++;
        if (isset($images['other_images'])) $count += count($images['other_images']);
        
        return $count;
    }

    /**
     * Get fulfilment details as array
     */
    public function getFulfilmentDetailsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Set fulfilment details as JSON
     */
    public function setFulfilmentDetailsAttribute($value)
    {
        $this->attributes['fulfilment_details'] = $value ? json_encode($value) : null;
    }

    /**
     * Get dimensions formatted string
     */
    public function getDimensionsFormattedAttribute()
    {
        $details = $this->fulfilment_details;
        
        if (!$details) {
            return null;
        }
        
        $length = $details['length'] ?? 0;
        $width = $details['width'] ?? 0;
        $height = $details['height'] ?? 0;
        $unit = $details['dimension_unit'] ?? 'cm';
        
        return "{$length} × {$width} × {$height} {$unit}";
    }

    /**
     * Get weight formatted string
     */
    public function getWeightFormattedAttribute()
    {
        $details = $this->fulfilment_details;
        
        if (!$details || !isset($details['weight'])) {
            return null;
        }
        
        $weight = $details['weight'];
        $unit = $details['weight_unit'] ?? 'kg';
        
        return "{$weight} {$unit}";
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Delete images when product is force deleted
        static::forceDeleted(function ($product) {
            $images = $product->images;
            
            foreach ($images as $image) {
                if (is_array($image)) {
                    foreach ($image as $img) {
                        Storage::disk('public')->delete($img);
                    }
                } else {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }
        // ── Relationships ──────────────────────────────────────────────────────────
 
    public function creditNoteItems(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'product_id');
    }
 
    // ── Helpers ────────────────────────────────────────────────────────────────
 
    /**
     * Get the account from additional_data JSON.
     * Adjust the key to match your actual JSON structure.
     */
    public function getAccountAttribute(): ?string
    {
        return $this->additional_data['account'] ?? null;
    }
 
    /**
     * Get sales description from additional_data JSON.
     */
    public function getSalesDescriptionAttribute(): ?string
    {
        return $this->additional_data['description']['sales'] ?? null;
    }
 
    /**
     * Snapshot data to copy into a credit note line item.
     */
    public function toCreditNoteLineSnapshot(): array
    {
        return [
            'product_id' => $this->id,
            'item_name'  => $this->name,
            'item_sku'   => $this->sku,
            'item_type'  => $this->type,
            'unit'       => $this->unit,
            'rate'       => $this->selling_price ?? 0,
            'account'    => $this->account,
            'description'=> $this->sales_description,
        ];
    }

}