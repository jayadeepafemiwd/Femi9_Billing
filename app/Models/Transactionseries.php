<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSeries extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_series';

    protected $fillable = [
         'name',
    'location_id',  
    'series_data',
    'is_default',
    'category_id',
    'category_name',
    'created_by',
    ];

    protected $casts = [
        'series_data' => 'array',
         'location_id' => 'array',
        'is_default'  => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────

    /**
     * Many-to-many: locations that use this series
     */
    public function location()
{
    return $this->belongsTo(Location::class, 'location_id');
}

    /**
     * Creator user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helper ────────────────────────────────────────

    /**
     * Get formatted series data with preview
     * Returns: [['module'=>..., 'prefix'=>..., 'start'=>..., 'preview'=>...], ...]
     */
    public function getFormattedSeriesAttribute(): array
    {
        return collect($this->series_data)->map(function ($row) {
            return array_merge($row, [
                'preview' => ($row['prefix'] ?? '') . ($row['start'] ?? ''),
            ]);
        })->toArray();
    }
}