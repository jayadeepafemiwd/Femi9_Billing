<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description',
        'parent_id', 'level',
        'portal_access',
        'visible_in_hierarchy',
        'country_id',             // lc_layer_values.id of selected country
        'assign_fix_location',    // lc_layers.id — UNIQUE across all categories
        'location_label',
        'sort_order',
    ];

    protected $casts = [
        'portal_access'        => 'boolean',
        'visible_in_hierarchy' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(UserCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(UserCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('portal_access', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function getAncestorsAttribute(): array
    {
        $ancestors = [];
        $current   = $this->parent;
        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }
        return $ancestors;
    }

    public function getBreadcrumbAttribute(): string
    {
        $chain   = collect($this->ancestors)->pluck('name')->toArray();
        $chain[] = $this->name;
        return implode(' → ', $chain);
    }

    public function recalculateDescendantLevels(): void
    {
        foreach ($this->children as $child) {
            $child->update(['level' => $this->level + 1]);
            $child->recalculateDescendantLevels();
        }
    }
}