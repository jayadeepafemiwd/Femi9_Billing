<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LcLayerValue extends Model
{
    protected $table    = 'lc_layer_values';
    protected $fillable = ['layer_id', 'parent_value_id', 'value'];
    protected $casts    = ['parent_value_id' => 'integer'];

    public function layer()
    {
        return $this->belongsTo(LcLayer::class, 'layer_id');
    }

    public function parent()
    {
        return $this->belongsTo(LcLayerValue::class, 'parent_value_id');
    }

    public function children()
    {
        return $this->hasMany(LcLayerValue::class, 'parent_value_id');
    }
}