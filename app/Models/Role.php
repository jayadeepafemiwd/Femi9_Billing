<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Role extends Model
{
    protected $fillable = ['name', 'user_category_id'];
 
    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }
 
    public function permissions()
    {
        return $this->hasMany(UserCategoryPermission::class, 'role_id');
    }
}
 