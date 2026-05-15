<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentItem extends Model
{
    protected $fillable = [
        'inventory_adjustment_id',
        'item_id',              // ← இது products.id-ஐ point பண்றது
        'quantity_available',
        'new_quantity_on_hand',
        'quantity_adjusted',
        'current_value',
        'changed_value',
        'adjusted_value',
        'reporting_tags',
    ];

    protected $casts = [
        'quantity_available'   => 'decimal:4',
        'new_quantity_on_hand' => 'decimal:4',
        'quantity_adjusted'    => 'decimal:4',
        'current_value'        => 'decimal:2',
        'changed_value'        => 'decimal:2',
        'adjusted_value'       => 'decimal:2',
        'reporting_tags'       => 'array',
    ];

    public function adjustment()
    {
        return $this->belongsTo(InventoryAdjustment::class, 'inventory_adjustment_id');
    }

    // ← Product model-ஐ use பண்றோம், Item இல்ல
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    // alias — எங்கே item() call பண்ணாலும் work ஆகும்
    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}