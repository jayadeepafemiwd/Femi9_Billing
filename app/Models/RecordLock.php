<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordLock extends Model
{

    protected $table = 'record_locks';
    
    protected $fillable = [
        'lockable_type',
        'lockable_id',
        'is_locked',
        'locked_by_name',
        'locked_by_id',
        'locked_at',
        'lock_reason',
        'lock_config',
        'unlocked_by_name',
        'unlocked_by_id',
        'unlocked_at',
        'expires_at',
        'history'
    ];
    
    protected $casts = [
        'is_locked' => 'boolean',
        'lock_config' => 'array',
        'history' => 'array',
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    
    /**
     * Get the parent lockable model
     */
    public function lockable()
    {
        return $this->morphTo();
    }
    
    /**
     * Check if lock is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    /**
     * Check if lock belongs to specific user
     */
    public function isLockedBy($userId): bool
    {
        return $this->locked_by_id == $userId;
    }
    
    /**
     * Check if user can override lock
     */
    public function canOverride($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) return false;
        
        // Admin can always override
        if (isset($user->isAdmin) && $user->isAdmin) return true;
        
        // Check override roles from config
        $overrideRoles = $this->lock_config['override_roles'] ?? [];
        return in_array($user->role ?? '', $overrideRoles);
    }
    
    /**
     * Add history entry
     */
    public function addHistory($action, $user = null)
    {
        $user = $user ?? auth()->user();
        $history = $this->history ?? [];
        
        $history[] = [
            'action' => $action,
            'user_name' => $user->name ?? 'System',
            'user_id' => $user->id ?? null,
            'time' => now()->toDateTimeString(),
        ];
        
        $this->history = $history;
        $this->save();
    }
}