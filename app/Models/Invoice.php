<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'referral_id',
        'location',
        'order_number',
        'invoice_date',
         'gst_data',
        'terms',
        'due_date',
        'salesperson',
        'subject',
        'warehouse_location',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'tax_type',
        'tax_percent',
        'tax_amount',
        'adjustment',
        'grand_total',
        'customer_notes',
        'terms_conditions',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function locationDetails()
{
    return $this->belongsTo(\App\Models\Location::class, 'location');
}

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Auto generate next invoice number: INV-000001, INV-000002 ...
    public static function generateInvoiceNumber(): string
    {
        $last   = self::latest('id')->first();
        $next   = $last ? ((int) substr($last->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
