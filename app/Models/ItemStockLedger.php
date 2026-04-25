<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemStockLedger extends Model
{
    public $timestamps = false;

    protected $table = 'item_stock_ledger';

    protected $fillable = [
        'item_id', 'location_id', 'variant_id',
        'transaction_type', 'transaction_date',
        'reference_type', 'reference_id',
        'qty_change', 'committed_change', 'unit_value',
        'stock_on_hand_after', 'committed_after', 'available_after',
        'notes', 'created_by',
    ];

    protected $casts = [
        'transaction_date'    => 'date',
        'qty_change'          => 'decimal:4',
        'stock_on_hand_after' => 'decimal:4',
        'available_after'     => 'decimal:4',
    ];
}