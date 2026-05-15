<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferOrderItem extends Model
{
    protected $fillable = [
        'transfer_order_id',
        'product_id',
        'variant_id',
        'quantity',
        'source_stock',
        'destination_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}