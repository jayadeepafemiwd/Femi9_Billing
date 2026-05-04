<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $table = 'invoice_settings';

    protected $fillable = [
        'allow_editing_sent_invoice',
        'associate_expense_receipts',
        'invoice_order_number',
        'notify_online_payment',
        'include_payment_receipt_thank_you',
        'automate_thank_you_note',
        'invoice_qr_code_enabled',
        'hide_zero_value_line_items',
        'terms_and_conditions',
        'customer_notes',
    ];

    protected $casts = [
        'allow_editing_sent_invoice'        => 'boolean',
        'associate_expense_receipts'        => 'boolean',
        'notify_online_payment'             => 'boolean',
        'include_payment_receipt_thank_you' => 'boolean',
        'automate_thank_you_note'           => 'boolean',
        'invoice_qr_code_enabled'           => 'boolean',
        'hide_zero_value_line_items'        => 'boolean',
    ];

    /**
     * Always return the single settings row (creates if not exists).
     */
    public static function getInstance(): static
    {
        return static::firstOrCreate([], [
            'customer_notes' => 'Thanks for your business.',
        ]);
    }
}
