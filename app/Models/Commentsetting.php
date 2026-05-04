<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CommentSetting extends Model
{
    protected $table = 'comment_settings';

    protected $fillable = ['module', 'configuration'];

    protected $casts = ['configuration' => 'array'];

    /*
     * Default config shape for any module.
     * Override per-module in the DB or via seeder.
     *
     *  enabled        — show/hide the Comments tab entirely
     *  max_length     — max characters allowed in one comment
     *  allow_delete   — can users delete their own comments?
     *  allow_html     — accept rich HTML or strip to plain text
     *  label          — tab label shown in the UI
     */
    public static function defaultConfig(): array
    {
        return [
            'enabled'      => true,
            'max_length'   => 5000,
            'allow_delete' => true,
            'allow_html'   => true,
            'label'        => 'Comments',
        ];
    }

    // ── Get config for a module (cached 10 min) ───────────────────────
    public static function configFor(string $module): array
    {
        return Cache::remember("comment_cfg_{$module}", 600, function () use ($module) {
            $row = static::where('module', $module)->first();
            return array_merge(
                static::defaultConfig(),
                $row?->configuration ?? []
            );
        });
    }

    // ── Flush cache when settings are updated ────────────────────────
    public static function boot()
    {
        parent::boot();
        static::saved(fn($m)   => Cache::forget("comment_cfg_{$m->module}"));
        static::deleted(fn($m) => Cache::forget("comment_cfg_{$m->module}"));
    }
}