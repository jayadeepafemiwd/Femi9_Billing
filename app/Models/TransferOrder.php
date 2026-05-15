<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transfer_order_number',
        'date',
        'reason',
        'source_location_id',
        'destination_location_id',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(TransferOrderItem::class);
    }

    public function sourceLocation()
    {
        return $this->belongsTo(Location::class, 'source_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }
}