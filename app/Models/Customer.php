<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'customer_type',
        'customer_category',
        'user_code',
        'assign_location',
        'name',
        'company_name',
        'display_name',
        'email',
        'unused_credits',
        'phone_number',
        'pan',
        'additional_datas',
        'common_address',
        'comments',
        'remarks',
    ];

    protected $casts = [
        'assign_location'  => 'array',
        'additional_datas' => 'array',
        'common_address'   => 'array',
        'comments'         => 'array',
        'unused_credits'   => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Returns the label shown in dropdowns — prefer display_name, fallback to name.
     */
    public function getDropdownLabelAttribute(): string
    {
        return $this->display_name ?: ($this->name ?? '');
    }

    /**
     * Billing address from common_address JSON.
     */
    public function getBillingAddressAttribute(): ?array
    {
        return $this->common_address['billing'] ?? null;
    }

    /**
     * Shipping address from common_address JSON.
     */
    public function getShippingAddressAttribute(): ?array
    {
        return $this->common_address['shipping'] ?? null;
    }
}