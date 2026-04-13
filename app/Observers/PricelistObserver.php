<?php

namespace App\Observers;

use App\Models\PriceList;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class PriceListObserver
{
    private static $oldData = [];

    // ================================================================
    //  CREATING — skip (no old data needed)
    // ================================================================

    public function updating(PriceList $priceList): void
    {
        static::$oldData[$priceList->id] = $priceList->getOriginal();
    }

    public function updated(PriceList $priceList): void
    {
        $changed = array_keys($priceList->getChanges());
        $changed = array_filter($changed, fn($k) => !in_array($k, ['updated_at']));

        if (empty($changed)) return;

        $skipFields = ['updated_at', 'created_at', 'deleted_at'];

        $oldRaw = static::$oldData[$priceList->id] ?? [];
        $newRaw = $priceList->toArray();

        $oldDiff = [];
        $newDiff = [];

        foreach ($newRaw as $key => $newVal) {
            if (in_array($key, $skipFields)) continue;
            $oldVal = $oldRaw[$key] ?? null;
            $oldStr = is_array($oldVal) ? json_encode($oldVal) : (string)($oldVal ?? '');
            $newStr = is_array($newVal) ? json_encode($newVal) : (string)($newVal ?? '');
            if ($oldStr !== $newStr) {
                $oldDiff[$key] = $oldVal;
                $newDiff[$key] = $newVal;
            }
        }

        if (empty($newDiff)) return;

        History::create([
            'module'    => 'price_list',
            'action'    => 'update',
            'record_id' => $priceList->id,
            'user_id'   => Auth::id(),
            'old_data'  => $oldDiff,
            'new_data'  => $newDiff,
        ]);

        unset(static::$oldData[$priceList->id]);
    }

    public function created(PriceList $priceList): void
    {
        History::create([
            'module'    => 'price_list',
            'action'    => 'create',
            'record_id' => $priceList->id,
            'user_id'   => Auth::id(),
            'old_data'  => null,
            'new_data'  => [
                'name'             => $priceList->name,
                'transaction_type' => $priceList->transaction_type,
                'price_list_type'  => $priceList->price_list_type,
            ],
        ]);
    }

    public function deleted(PriceList $priceList): void
    {
        History::create([
            'module'    => 'price_list',
            'action'    => 'delete',
            'record_id' => $priceList->id,
            'user_id'   => Auth::id(),
            'old_data'  => [
                'name'             => $priceList->name,
                'transaction_type' => $priceList->transaction_type,
            ],
            'new_data'  => null,
        ]);
    }
}