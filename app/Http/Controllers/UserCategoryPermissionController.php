<?php

namespace App\Http\Controllers;

use App\Models\UserCategory;
use App\Models\UserCategoryPermission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserCategoryPermissionController extends Controller
{
    // ── All modules your app has ─────────────────────────────────
    // Edit this list to match your actual routes/features
    private const MODULES = [
        'customers',
        'invoices',
        'products',
        'leads',
        'contacts',
        'payments_records',
        'stock_ledger',
        'transaction_series',
        'transfer_orders',
        'assemblies',
        'locations',
        'reports',
        'user_categories',
        'user_sub_categories',
    ];

    // ── INDEX — show the permissions matrix page ─────────────────

// ── index() ──────────────────────────────────────────────────────
 
public function index()
{
    $roles = \App\Models\Role::with(['category', 'permissions'])
        ->latest()
        ->get();
 
    return view('admin.user-category-permissions.index', compact('roles'));
}

public function create()
{
    $categories = \App\Models\UserCategory::orderBy('level')->orderBy('sort_order')->get();
    $modules    = self::MODULES;
 
    return view('admin.user-category-permissions.create', compact('categories', 'modules'));
}


    // ── UPDATE (AJAX) — save one cell toggle ─────────────────────
    // POST /admin/user-category-permissions/update
    // Body: { category_id, module, field, value }
    // field = 'can_create' | 'can_read' | 'can_edit' | 'can_delete' | 'scope'

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:user_categories,id',
            'module'      => 'required|string|in:' . implode(',', self::MODULES),
            'field'       => 'required|in:can_create,can_read,can_edit,can_delete,scope',
            'value'       => 'required',
        ]);

        // updateOrCreate — if no row yet, create; else update
        $perm = UserCategoryPermission::updateOrCreate(
            [
                'user_category_id' => $validated['category_id'],
                'module'           => $validated['module'],
            ],
            [
                $validated['field'] => $validated['value'],
            ]
        );

        return response()->json([
            'success' => true,
            'data'    => $perm,
        ]);
    }

    // ── BULK SAVE — save entire category row at once ─────────────
    // POST /admin/user-category-permissions/bulk
    // Body: { category_id, permissions: { module: { can_create, can_read, ... } } }

    public function bulk(Request $request): JsonResponse
    {
        $request->validate([
            'category_id'          => 'required|exists:user_categories,id',
            'permissions'          => 'required|array',
            'permissions.*.module' => 'sometimes|string',
        ]);

        $categoryId  = $request->category_id;
        $permissions = $request->permissions; // array of { module, can_create, ... }

        foreach ($permissions as $perm) {
            UserCategoryPermission::updateOrCreate(
                [
                    'user_category_id' => $categoryId,
                    'module'           => $perm['module'],
                ],
                [
                    'can_create' => (bool) ($perm['can_create'] ?? false),
                    'can_read'   => (bool) ($perm['can_read']   ?? false),
                    'can_edit'   => (bool) ($perm['can_edit']   ?? false),
                    'can_delete' => (bool) ($perm['can_delete'] ?? false),
                    'scope'      => $perm['scope'] ?? 'none',
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Permissions saved.']);
    }


public function byCategory(int $categoryId)
{
    $category = \App\Models\UserCategory::findOrFail($categoryId);
 
    // Get latest role's permissions for this category (if any)
    $latestRole = \App\Models\Role::where('user_category_id', $categoryId)->latest()->first();
 
    $permissions = [];
    foreach (self::MODULES as $module) {
        $p = null;
        if ($latestRole) {
            $p = \App\Models\UserCategoryPermission::where('role_id', $latestRole->id)
                ->where('module', $module)
                ->first();
        }
        $permissions[$module] = [
            'scope'      => $p?->scope      ?? 'none',
            'can_create' => $p?->can_create ?? false,
            'can_read'   => $p?->can_read   ?? false,
            'can_edit'   => $p?->can_edit   ?? false,
            'can_delete' => $p?->can_delete ?? false,
        ];
    }
 
    return response()->json([
        'success'       => true,
        'category_name' => $category->name,
        'permissions'   => $permissions,
    ]);
}

// ── destroy() — delete a role ─────────────────────────────────────
public function destroy(int $id)
{
    $role = \App\Models\Role::findOrFail($id);
    $role->delete(); // permissions cascade delete automatically
 
    return response()->json(['success' => true]);
}
 
// ── getModules() — public static so blade can call it ─────────────
public static function getModules(): array
{
    return self::MODULES;
}
 
// ── STORE — save new role with permissions ────────────────────────
// POST /admin/user-category-permissions/store
public function store(Request $request)
{
    foreach ($request->permissions as $perm) {
        UserCategoryPermission::updateOrCreate(
            [
                'user_category_id' => $request->category_id,
                'module'           => $perm['module'],
            ],
            [
                'can_create' => (bool) ($perm['can_create'] ?? false),
                'can_read'   => (bool) ($perm['can_read']   ?? false),
                'can_edit'   => (bool) ($perm['can_edit']   ?? false),
                'can_delete' => (bool) ($perm['can_delete'] ?? false),
                'scope'      => $perm['scope'] ?? 'none',
            ]
        );
    }

    return response()->json(['success' => true]);
}
}