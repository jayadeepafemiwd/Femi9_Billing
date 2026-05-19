<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCategoryPermission extends Model
{
    protected $fillable = [
        'role_id', 
        'user_category_id',
        'module',
        'can_create',
        'can_read',
        'can_edit',
        'can_delete',
        'scope',
    ];

    protected $casts = [
        'can_create' => 'boolean',
        'can_read'   => 'boolean',
        'can_edit'   => 'boolean',
        'can_delete' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(UserCategory::class, 'user_category_id');
    }

    // ── Helper: get permissions for a category as keyed array ────
    // Usage: UserCategoryPermission::forCategory(3)
    // Returns: [ 'invoices' => [...], 'products' => [...], ... ]

    public static function forCategory(int $categoryId): array
    {
        return static::where('user_category_id', $categoryId)
            ->get()
            ->keyBy('module')
            ->map(fn($p) => [
                'can_create' => $p->can_create,
                'can_read'   => $p->can_read,
                'can_edit'   => $p->can_edit,
                'can_delete' => $p->can_delete,
                'scope'      => $p->scope,
            ])
            ->toArray();
    }

}