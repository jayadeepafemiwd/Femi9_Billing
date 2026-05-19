<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    
    protected $authPasswordName = 'password';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function can_do(string $module, string $action = 'read'): bool
{
    if ($this->role === 'admin') return true;

    $actionColumn = match($action) {
        'create' => 'can_create',
        'edit'   => 'can_edit',
        'delete' => 'can_delete',
        default  => 'can_read',
    };

    return \DB::table('user_category_permissions')
        ->join('roles', 'roles.id', '=', 'user_category_permissions.role_id')
        ->where('roles.name', $this->role)
        ->where('user_category_permissions.module', $module)
        ->where("user_category_permissions.{$actionColumn}", 1)
        ->exists();
}
}