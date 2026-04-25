<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class InvoiceObserver
{
    private static $oldData = [];

    // ================================================================
    //  UPDATING — save old data before update
    // ================================================================
    public function updating(Invoice $invoice): void
    {
        $dirty = array_keys($invoice->getDirty());
        $dirty = array_filter($dirty, fn($k) => $k !== 'updated_at');

        // Status மட்டும் change ஆனா — separate handle பண்றோம்
        if ($dirty === ['status'] || $dirty == ['status']) {
            return;
        }

        static::$oldData[$invoice->id] = $invoice->getOriginal();
    }

    // ================================================================
    //  UPDATED — compare and save diff
    // ================================================================
    public function updated(Invoice $invoice): void
    {
        $changed = array_keys($invoice->getChanges());
        $changed = array_filter($changed, fn($k) => !in_array($k, ['updated_at']));

        if (empty($changed)) return;

        // ── Status only change → payment/status history ──
        $onlyStatus      = array_filter($changed, fn($k) => $k === 'status');
        $meaningfulChanges = array_filter($changed, fn($k) => $k !== 'status');

        if (!empty($onlyStatus) && empty($meaningfulChanges)) {
            $oldStatus = static::$oldData[$invoice->id]['status'] ?? null;
            $newStatus = $invoice->status;

            History::create([
                'module'    => 'invoice',
                'action'    => 'status_changed',
                'record_id' => $invoice->id,
                'user_id'   => Auth::id(),
                'old_data'  => ['status' => $oldStatus],
                'new_data'  => ['status' => $newStatus],
            ]);

            unset(static::$oldData[$invoice->id]);
            return;
        }

        if (empty($meaningfulChanges)) return;

        // ── Full diff compare ──
        $jsonColumns = ['items', 'extra_charges', 'billing_address', 'shipping_address'];

        $oldRaw = static::$oldData[$invoice->id] ?? [];
        $newRaw = $invoice->toArray();

        $oldFlat = $this->flattenData($oldRaw, $jsonColumns);
        $newFlat = $this->flattenData($newRaw, $jsonColumns);

        $oldDiff = [];
        $newDiff = [];

        foreach ($newFlat as $key => $newVal) {
            $oldVal = $oldFlat[$key] ?? null;
            $oldStr = is_array($oldVal) ? json_encode($oldVal) : (string)($oldVal ?? '');
            $newStr = is_array($newVal) ? json_encode($newVal) : (string)($newVal ?? '');

            if ($oldStr !== $newStr) {
                $oldDiff[$key] = $oldVal;
                $newDiff[$key] = $newVal;
            }
        }

        // Deleted keys check
        foreach ($oldFlat as $key => $oldVal) {
            if (!array_key_exists($key, $newFlat)) {
                $oldDiff[$key] = $oldVal;
                $newDiff[$key] = null;
            }
        }

        if (empty($newDiff)) return;

        History::create([
            'module'    => 'invoice',
            'action'    => 'update',
            'record_id' => $invoice->id,
            'user_id'   => Auth::id(),
            'old_data'  => $oldDiff,
            'new_data'  => $newDiff,
        ]);

        unset(static::$oldData[$invoice->id]);
    }

    // ================================================================
    //  CREATED
    // ================================================================
    public function created(Invoice $invoice): void
    {
        History::create([
            'module'    => 'invoice',
            'action'    => 'create',
            'record_id' => $invoice->id,
            'user_id'   => Auth::id(),
            'old_data'  => null,
            'new_data'  => [
                'invoice_number' => $invoice->invoice_number,
                'customer_id'    => $invoice->customer_id,
                'grand_total'    => $invoice->grand_total,
                'status'         => $invoice->status,
                'invoice_date'   => $invoice->invoice_date,
            ],
        ]);
    }

    // ================================================================
    //  DELETED
    // ================================================================
    public function deleted(Invoice $invoice): void
    {
        History::create([
            'module'    => 'invoice',
            'action'    => 'delete',
            'record_id' => $invoice->id,
            'user_id'   => Auth::id(),
            'old_data'  => [
                'invoice_number' => $invoice->invoice_number,
                'customer_id'    => $invoice->customer_id,
                'grand_total'    => $invoice->grand_total,
                'status'         => $invoice->status,
            ],
            'new_data'  => null,
        ]);
    }

    // ================================================================
    //  JSON FLATTEN HELPER
    // ================================================================
    private function flattenData(array $data, array $jsonColumns): array
    {
        $flat = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $jsonColumns)) {
                $decoded = is_string($value)
                    ? json_decode($value, true)
                    : (is_array($value) ? $value : []);

                if (is_array($decoded)) {
                    $nested = $this->dotFlatten($decoded, $key);
                    foreach ($nested as $nKey => $nVal) {
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