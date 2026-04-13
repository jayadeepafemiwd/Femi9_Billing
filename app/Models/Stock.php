<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $table = 'stocks';

    protected $fillable = [
        'product_id',
       'location_id',
        'opening_stock',
        'stock_on_hand',
        'committed_stock',
        'available_stock',
        'value_per_unit',
        'total_value',
        'type',
    ];

    protected $casts = [
        'opening_stock'   => 'decimal:4',
        'stock_on_hand'   => 'decimal:4',
        'committed_stock' => 'decimal:4',
        'available_stock' => 'decimal:4',
        'value_per_unit'  => 'decimal:4',
        'total_value'     => 'decimal:4',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   public function location()
{
    return $this->belongsTo(Location::class, 'location_id'); // ← fix
}
}