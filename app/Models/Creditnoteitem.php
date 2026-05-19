<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNoteItem extends Model
{
    protected $fillable = [
        'credit_note_id',
        'product_id',
        'item_name',
        'item_sku',
        'item_type',
        'unit',
        'account',
        'quantity',
        'rate',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'amount',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'quantity'            => 'decimal:4',
        'rate'                => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount'     => 'decimal:2',
        'tax_percentage'      => 'decimal:2',
        'tax_amount'          => 'decimal:2',
        'amount'              => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class);
    }

    /**
     * Links to your existing products table via product_id.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ── Auto-calculate on save ─────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::saving(function (CreditNoteItem $line) {
            $gross          = $line->quantity * $line->rate;
            $discountAmount = round($gross * ($line->discount_percentage / 100), 2);
            $taxable        = $gross - $discountAmount;
            $taxAmount      = round($taxable * ($line->tax_percentage / 100), 2);

            $line->discount_amount = $discountAmount;
            $line->tax_amount      = $taxAmount;
            $line->amount          = $taxable + $taxAmount;
        });
    }
}