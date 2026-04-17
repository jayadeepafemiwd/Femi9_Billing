<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Referral extends Model
{
    
    protected $fillable = ['name', 'email', 'phone', 'type'];
}
