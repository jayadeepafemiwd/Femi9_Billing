<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'transaction_type',
        'category_id',
        'category_name',
        'access_permission',
        'price_list_type',
        'description',
        'markup_type',
        'percentage',
        'round_off',
        'pricing_scheme',
        'currency',
        'include_discount',
    ];

    protected $casts = [
        'include_discount' => 'boolean',
        'percentage'       => 'decimal:2',
    ];
    public function histories()
{
    return $this->hasMany(\App\Models\History::class, 'record_id')
                ->where('module', 'price_list');
}
}