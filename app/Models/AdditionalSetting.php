<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalSetting extends Model
{
    use SoftDeletes;

    protected $table = 'additional_setting';

    protected $fillable = [
        'name',
        'category_name',      // NEW
        'data_type',
        'mandatory',
        'status',
        'additional_config',
    ];

    protected $casts = [
        'additional_config' => 'array',
    ];

    // ── Scope: filter by category ─────────────────────────────────
    public function scopeForModule($query, string $category)
    {
        return $query->where('status', 'active')
                     ->where('category_name', $category);
    }

    // Usage:
    // AdditionalSetting::forModule('products')->get()
    // AdditionalSetting::forModule('sales')->get()

    public function getConfigKeys(): array
    {
        $keys = [];
        if ($this->additional_config && is_array($this->additional_config)) {
            foreach ($this->additional_config as $key => $value) {
                $keys[$key] = [
                    'name'         => $key,
                    'value'        => $this->formatValue($value),
                    'raw_value'    => $value,
                    'display_name' => $this->getDisplayName($key),
                    'description'  => $this->getDescription($key, $value),
                    'value_type'   => gettype($value),
                ];
            }
        }
        return $keys;
    }

    protected function formatValue($value)
    {
        if (is_array($value)) {
            if (isset($value[0]['role_name'])) {
                return '[Roles: ' . collect($value)->pluck('role_name')->implode(', ') . ']';
            }
            return json_encode($value);
        }
        if (is_bool($value)) return $value ? 'Yes' : 'No';
        if ($value === null) return 'NULL';
        return (string) $value;
    }

    protected function getDisplayName(string $key): string
    {
        $names = [
            'access'             => 'Role Access',
            'help_text'          => 'Help Text',
            'created_at'         => 'Created Date',
            'updated_at'         => 'Updated Date',
            'privacy_pii'        => 'Privacy PII',
            'default_value'      => 'Default Value',
            'updated_by_name'    => 'Updated By',
            'access_updated_at'  => 'Access Updated Date',
        ];
        return $names[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    protected function getDescription(string $key, $value): string
    {
        $desc = [
            'access'        => 'Role-based access permissions',
            'help_text'     => 'Helper text for this field',
            'privacy_pii'   => 'Personally Identifiable Information flag',
            'default_value' => 'Default value when not specified',
        ];
        return $desc[$key] ?? "Configuration for $key";
    }
}