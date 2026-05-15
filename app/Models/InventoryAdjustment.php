<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'mode',
        'date',
        'account',
        'reason',
        'location_id',
        'description',
        'product_rules',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Available accounts grouped by category (matches Zoho UI)
    public static array $accounts = [
        'Cost Of Goods Sold' => [
            'Cost of Goods Sold',
            'Job Costing',
            'Labor',
            'Materials',
            'Service sold',
            'Subcontractor',
        ],
        'Other Current Asset' => [
            'Advance Tax',
            'Employee Advance',
            'Goods In Transit',
            'Prepaid Expenses',
            'TDS Receivable',
        ],
        'Cash' => [
            'Petty Cash',
            'Undeposited Funds',
        ],
        'Fixed Asset' => [
            'Furniture and Equipment',
        ],
        'Other Current Liability' => [
            'Employee Reimbursements',
            'Opening Balance Adjustments',
        ],
    ];

    // Available reasons
    public static array $reasons = [
        'Damaged goods',
        'Donated goods',
        'Goods in transit damaged',
        'Loss of stock',
        'Obsolete inventory',
        'Stolen goods',
        'Stock count adjustment',
        'Other',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryAdjustmentItem::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isQuantityMode(): bool
    {
        return $this->mode === 'quantity';
    }
}