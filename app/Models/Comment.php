<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'module',
        'record_id',
        'content',
        'user_id',
        'user_name',
    ];

    // ── Allowed modules ───────────────────────────────────────────────
    const MODULES = ['customer', 'product', 'invoice'];

    // ── Relationships ─────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────
    public function scopeForModule($query, string $module, int $recordId)
    {
        return $query->where('module', $module)
                     ->where('record_id', $recordId);
    }

    // ── Formatted timestamp for frontend ─────────────────────────────
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at
                    ->setTimezone('Asia/Kolkata')
                    ->format('d/m/Y h:i A');
    }

    // ── API shape used by CommentsController ─────────────────────────
    public function toApiArray(): array
    {
        return [
            'id'         => $this->id,
            'content'    => $this->content,
            'user_id'    => $this->user_id,
            'user_name'  => $this->user_name ?? ($this->user?->name ?? 'User'),
            'created_at' => $this->formatted_time,
        ];
    }
}