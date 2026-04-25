<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceListItem extends Model
{
    protected $fillable = [
        'price_list_id',
        'item_id',
        'start_quantity',
        'end_quantity',
        'custom_rate',
        'ranges',
    ];

    protected $casts = [
        'start_quantity' => 'decimal:2',
        'end_quantity'   => 'decimal:2',
        'custom_rate'    => 'decimal:2',
         'old_data' => 'array',
    'new_data' => 'array',
    'ranges' => 'array',
    ];

    // PriceListItem → Product
    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    // PriceListItem → PriceList
    public function priceList()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }
}