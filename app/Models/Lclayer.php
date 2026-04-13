<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LcLayer extends Model
{
    protected $table    = 'lc_layers';
    protected $fillable = ['name', 'is_global', 'depth', 'parent_value_id'];
    protected $casts    = ['is_global' => 'boolean', 'depth' => 'integer', 'parent_value_id' => 'integer'];
    public function values()
    {
        return $this->hasMany(LcLayerValue::class, 'layer_id');
    }
}