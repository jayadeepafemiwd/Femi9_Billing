<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $table = 'locations';

    protected $fillable = [
        'location_name',
        'location_type',
        'is_child',
        'parent_location_id',
        'address_details',
        'default_series_id',
        'additional_data',
        'transaction_series_id',
        'default_series_id',
        'created_by',
    ];

    protected $casts = [
        'address_details' => 'array',
        'additional_data' => 'array',
        'transaction_series_id' => 'array',
        'is_child'        => 'boolean',
    ];

    // Transaction Series (many-to-many)
public function getTransactionSeriesListAttribute()
{
    $ids = $this->transaction_series_id ?? [];
    if (empty($ids)) return collect();
    return TransactionSeries::whereIn('id', $ids)->get();
}
    // Parent location
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_location_id');
    }

    // Child locations
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_location_id');
    }

    // Stocks
    public function stocks()
    {
        return $this->hasMany(\App\Models\Stock::class, 'location_id');
    }

    // Creator
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
    
}