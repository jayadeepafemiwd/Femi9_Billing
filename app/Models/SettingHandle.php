<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingHandle extends Model
{
    protected $table = 'setting_handle';

    protected $fillable = [
        'process',
        'category_name',
        'config',
    ];

    protected $casts = [
        'config' => 'array'
    ];

    public function getConfigKeys(): array
    {
        $keys = [];

        if ($this->Config && is_array($this->Config)) {
            foreach ($this->Config as $key => $value) {
                // Format value for display
                $displayValue = $this->formatValue($value);

                $keys[$key] = [
                    'name' => $key,
                    'value' => $displayValue,
                    'raw_value' => $value,
                    'display_name' => $this->getDisplayName($key),
                    'description' => $this->getDescription($key, $value),
                    'value_type' => gettype($value)
                ];
            }
        }

        return $keys;
    }

    protected function formatValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        if ($value === null) {
            return 'NULL';
        }

        return (string)$value;
    }

    protected function getDisplayName(string $key): string
    {
        $displayNames = [
            'weight_unit' => 'Weight Unit',
            'decimal_rate' => 'Decimal Rate',
            'notify_email' => 'Notification Email',
            'barcode_field' => 'Barcode Field',
            'dimension_unit' => 'Dimension Unit',
            'track_landed_cost' => 'Track Landed Cost',
            'enable_price_lists' => 'Enable Price Lists',
            'duplicate_item_name' => 'Allow Duplicate Item Names',
            'enable_serial_number' => 'Enable Serial Numbers',
            'enhanced_item_search' => 'Enhanced Item Search',
            'inventory_start_date' => 'Inventory Start Date',
            'notify_reorder_point' => 'Notify at Reorder Point',
            'enable_batch_tracking' => 'Enable Batch Tracking',
            'enable_composite_items' => 'Enable Composite Items',
            'stock_prevention_level' => 'Stock Prevention Level',
            'show_out_of_stock_warning' => 'Show Out of Stock Warning'
        ];

        return $displayNames[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    protected function getDescription(string $key, $value): string
    {
        $descriptions = [
            'weight_unit' => 'Unit of measurement for weight',
            'decimal_rate' => 'Number of decimal places',
            'notify_email' => 'Email for notifications',
            'barcode_field' => 'Field used for barcode generation',
            'dimension_unit' => 'Unit for dimensions',
            'track_landed_cost' => 'Track additional costs in inventory'
        ];

        return $descriptions[$key] ?? "Setting for $key";
    }
}