<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'credit_note_number',
        'customer_id',
        'salesperson_id',
        'location',
        'warehouse_location',
        'reference_number',
        'credit_note_date',
        'subject',
        'price_list',
        'sub_total',
        'discount_percentage',
        'discount_amount',
        'tax_type',
        'tax_id',
        'tax_amount',
        'adjustment',
        'total',
        'customer_notes',
        'terms_and_conditions',
        'status',
        'pdf_template',
    ];

    protected $casts = [
        'credit_note_date'    => 'date',
        'sub_total'           => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount'     => 'decimal:2',
        'tax_amount'          => 'decimal:2',
        'adjustment'          => 'decimal:2',
        'total'               => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(Salesperson::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class)->orderBy('sort_order');
    }

    // ── Totals ─────────────────────────────────────────────────────────────────

    /**
     * Recalculate header totals from line items and save.
     */
    public function recalculateTotals(): void
    {
        $subTotal = $this->items()->sum('amount');

        $discountAmount = round($subTotal * ($this->discount_percentage / 100), 2);
        $taxableAmount  = $subTotal - $discountAmount;
        $total          = $taxableAmount + $this->adjustment;

        $this->update([
            'sub_total'       => $subTotal,
            'discount_amount' => $discountAmount,
            'total'           => $total,
        ]);
    }

    // ── Auto Number ────────────────────────────────────────────────────────────

  public static function generateNextNumber(): string
{
    $last = static::orderBy('id', 'desc')->value('credit_note_number');
    
    if (!$last) return 'CN-C00001';
    
    // Extract numeric part and increment
    preg_match('/(\d+)$/', $last, $matches);
    if (!$matches) return 'CN-C00001';
    
    $num    = (int)$matches[1] + 1;
    $prefix = preg_replace('/\d+$/', '', $last);
    
    return $prefix . str_pad($num, strlen($matches[1]), '0', STR_PAD_LEFT);
}

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeDraft($query)       { return $query->where('status', 'draft'); }
    public function scopeOpen($query)        { return $query->where('status', 'open'); }
    public function scopeVoid($query)        { return $query->where('status', 'void'); }
    public function scopeClosed($query)      { return $query->where('status', 'closed'); }
}