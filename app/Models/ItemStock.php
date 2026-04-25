<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStock extends Model
{
    use SoftDeletes;

    protected $table = 'item_stock';

    protected $fillable = [
        'item_id', 'location_id', 'variant_id',
        'opening_stock', 'stock_on_hand',
        'committed_stock', 'available_for_sale',
        'value_per_unit', 'total_value',
    ];

    protected $casts = [
        'opening_stock'    => 'decimal:4',
        'stock_on_hand'    => 'decimal:4',
        'committed_stock'  => 'decimal:4',
        'available_for_sale' => 'decimal:4',
        'value_per_unit'   => 'decimal:4',
        'total_value'      => 'decimal:4',
    ];

    public function product()  { return $this->belongsTo(Product::class, 'item_id'); }
    public function location() { return $this->belongsTo(Location::class); }
    public function variant()  { return $this->belongsTo(ItemVariant::class); }
}