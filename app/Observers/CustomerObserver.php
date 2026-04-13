<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class CustomerObserver
{
    private static $oldData = [];

    public function updating(Customer $customer): void
    {
        static::$oldData[$customer->id] = $customer->getOriginal();
    }

  public function updated(Customer $customer): void
{
    $changed = array_keys($customer->getChanges());
    
    $changed = array_filter($changed, fn($k) => $k !== 'updated_at');

    if (empty($changed)) return;

    // ✅ Comments மட்டும் changed-ஆ இருந்தா — separately handle
    $onlyComments = array_filter($changed, fn($k) => $k === 'comments');
    $meaningfulChanges = array_filter($changed, fn($k) => $k !== 'comments');

    // ✅ Comment add/delete detect பண்ணு
    if (!empty($onlyComments) && empty($meaningfulChanges)) {
        $oldComments = collect(static::$oldData[$customer->id]['comments'] ?? []);
        $newComments = collect($customer->comments ?? []);

        // Add detect
        if ($newComments->count() > $oldComments->count()) {
            $added = $newComments->whereNotIn('id', $oldComments->pluck('id'))->first();
            if ($added) {
                History::create([
                    'module'    => 'customer',
                    'action'    => 'comment_added',
                    'record_id' => $customer->id,
                    'user_id'   => Auth::id(),
                    'old_data'  => null,
                    'new_data'  => [
                        'comment' => $added['content'],
                        'by'      => $added['user_name'] ?? '',
                        'at'      => $added['created_at'] ?? '',
                    ],
                ]);
            }
        }

        // Delete detect
        if ($newComments->count() < $oldComments->count()) {
            $deleted = $oldComments->whereNotIn('id', $newComments->pluck('id'))->first();
            if ($deleted) {
                History::create([
                    'module'    => 'customer',
                    'action'    => 'comment_deleted',
                    'record_id' => $customer->id,
                    'user_id'   => Auth::id(),
                    'old_data'  => [
                        'comment' => $deleted['content'],
                        'by'      => $deleted['user_name'] ?? '',
                        'at'      => $deleted['created_at'] ?? '',
                    ],
                    'new_data'  => null,
                ]);
            }
        }

        unset(static::$oldData[$customer->id]);
        return;
    }

    if (empty($meaningfulChanges)) return;

    // ✅ Normal update logic (existing code)
    $oldRaw = static::$oldData[$customer->id] ?? [];
    $newRaw = $customer->toArray();

    $jsonColumns = ['additional_datas', 'common_address'];

    $oldFlat = $this->flattenData($oldRaw, $jsonColumns);
    $newFlat = $this->flattenData($newRaw, $jsonColumns);

    foreach (['comments'] as $skip) {
        unset($oldFlat[$skip], $newFlat[$skip]);
    }

    $oldDiff = [];
    $newDiff = [];

    foreach ($newFlat as $key => $newVal) {
        $oldVal = $oldFlat[$key] ?? null;
        if ((string)$oldVal !== (string)$newVal) {
            $oldDiff[$key] = $oldVal;
            $newDiff[$key] = $newVal;
        }
    }

    foreach ($oldFlat as $key => $oldVal) {
        if (!array_key_exists($key, $newFlat)) {
            $oldDiff[$key] = $oldVal;
            $newDiff[$key] = null;
        }
    }

    if (empty($newDiff)) return;

    History::create([
        'module'    => 'customer',
        'action'    => 'update',
        'record_id' => $customer->id,
        'user_id'   => Auth::id(),
        'old_data'  => $oldDiff,
        'new_data'  => $newDiff,
    ]);

    unset(static::$oldData[$customer->id]);
}

    public function created(Customer $customer): void
    {
        History::create([
            'module'    => 'customer',
            'action'    => 'create',
            'record_id' => $customer->id,
            'user_id'   => Auth::id(),
            'old_data'  => null,
            'new_data'  => ['display_name' => $customer->display_name, 'email' => $customer->email],
        ]);
    }

    public function deleted(Customer $customer): void
    {
        History::create([
            'module'    => 'customer',
            'action'    => 'delete',
            'record_id' => $customer->id,
            'user_id'   => Auth::id(),
            'old_data'  => ['display_name' => $customer->display_name],
            'new_data'  => null,
        ]);
    }

    private function flattenData(array $data, array $jsonColumns): array
    {
        $flat = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $jsonColumns)) {
                $decoded = is_string($value)
                    ? json_decode($value, true)
                    : (is_array($value) ? $value : []);
                if (is_array($decoded)) {
                    foreach ($this->dotFlatten($decoded, $key) as $nKey => $nVal) {
                        $flat[$nKey] = $nVal;
                    }
                }
            } else {
                $flat[$key] = $value;
            }
        }
        return $flat;
    }

    private function dotFlatten(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '.' . $key : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->dotFlatten($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}