<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assembly extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'assembly_number', 'composite_item_id', 'composite_item_name',
        'composite_item_sku', 'description', 'assembled_date',
        'quantity_to_assemble', 'location_id', 'associated_items',
        'associated_services', 'status', 'created_by',
    ];

    protected $casts = [
        'associated_items'    => 'array',
        'associated_services' => 'array',
        'assembled_date'      => 'date',
    ];

    public function compositeItem()
    {
        return $this->belongsTo(Product::class, 'composite_item_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}