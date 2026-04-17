<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_sub_categories';  // ← இது இருக்கா?

    protected $fillable = [
    'user_category_id',
    'name',
    'description',
    'status',
    'target_amount',
    'reference',
    'coupon',
];
    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }
}