<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'customer_type',
        'customer_category',
        'customer_sub_category_id',
        'user_code',
         'assign_location',
        'name',
        'company_name',
        'display_name',
        'email',
        'phone_number',
        'pan',
        'additional_datas',
        'common_address',
        'remarks',
        'comments',
    ];

    protected $casts = [
        'additional_datas' => 'array',
        'assign_location'  => 'array',
        'common_address'   => 'array',
        'comments'        =>'array',
    ];

    // ── Accessors ────────────────────────────────────────────
    public function getFirstNameAttribute(): ?string
    {
        return $this->additional_datas['first_name'] ?? null;
    }

    public function getLastNameAttribute(): ?string
    {
        return $this->additional_datas['last_name'] ?? null;
    }

    public function getBillingAddressAttribute(): ?array
    {
        return $this->common_address['billing'] ?? null;
    }

    public function getShippingAddressAttribute(): ?array
    {
        return $this->common_address['shipping'] ?? null;
    }
    public function subCategory()
{
    return $this->belongsTo(\App\Models\UserSubCategory::class, 'customer_sub_category_id');
}
}