<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salesperson extends Model
{
    protected $table = 'salespersons';
    
    protected $fillable = ['user_id', 'name', 'email'];

    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class);
    }
}