<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    private static $oldData = [];

   public function updating(Product $product): void
{
    // opening_stock மட்டும் change ஆகுதா check பண்ணு
    $dirty = array_keys($product->getDirty());
    $dirty = array_filter($dirty, fn($k) => $k !== 'updated_at');
    
    // Only opening_stock — observer skip பண்ணணும்
    if ($dirty === ['opening_stock'] || $dirty == ['opening_stock']) {
        return; // saveOpeningStock() already History create பண்றது
    }
    
    static::$oldData[$product->id] = $product->getOriginal();
}

public function updated(Product $product): void
{
    $changed = array_keys($product->getChanges());
    $changed = array_filter($changed, fn($k) => !in_array($k, ['updated_at', 'opening_stock']));
    
    // opening_stock மட்டும் changed — skip
    if (empty($changed)) return;
    
    // ... rest of your existing code
    // ✅ Opening stock மட்டும் changed-ஆ இருந்தா — separately handle
    $onlyOpeningStock = array_filter($changed, fn($k) => $k === 'opening_stock');
    $meaningfulChanges = array_filter($changed, fn($k) => $k !== 'opening_stock');

    if (!empty($onlyOpeningStock) && empty($meaningfulChanges)) {
        $oldStock = static::$oldData[$product->id]['opening_stock'] ?? 0;
        $newStock = $product->opening_stock;

        History::create([
            'module'    => 'product',
            'action'    => 'stock_updated',
            'record_id' => $product->id,
            'user_id'   => Auth::id(),
            'old_data'  => ['opening_stock' => $oldStock],
            'new_data'  => ['opening_stock' => $newStock],
        ]);

        unset(static::$oldData[$product->id]);
        return;
    }

    if (empty($meaningfulChanges)) return;

    // ✅ Normal update logic
    $oldRaw = static::$oldData[$product->id] ?? [];
    $newRaw = $product->toArray();

    $jsonColumns = ['additional_data', 'product_image', 'variants_data', 'associate_item_details'];

    $oldFlat = $this->flattenData($oldRaw, $jsonColumns);
    $newFlat = $this->flattenData($newRaw, $jsonColumns);

    $oldDiff = [];
    $newDiff = [];

    // foreach ($newFlat as $key => $newVal) {
    //     $oldVal = $oldFlat[$key] ?? null;
    //     if ((string)$oldVal !== (string)$newVal) {
    //         $oldDiff[$key] = $oldVal;
    //         $newDiff[$key] = $newVal;
    //     }
    // }

    foreach ($newFlat as $key => $newVal) {
    $oldVal = $oldFlat[$key] ?? null;
    $oldStr = is_array($oldVal) ? json_encode($oldVal) : (string)($oldVal ?? '');
    $newStr = is_array($newVal) ? json_encode($newVal) : (string)($newVal ?? '');
    if ($oldStr !== $newStr) {
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
        'module'    => 'product',
        'action'    => 'update',
        'record_id' => $product->id,
        'user_id'   => Auth::id(),
        'old_data'  => $oldDiff,
        'new_data'  => $newDiff,
    ]);

    unset(static::$oldData[$product->id]);
}
    // ================================================================
    // JSON flatten helper
    // ================================================================
    private function flattenData(array $data, array $jsonColumns): array
    {
        $flat = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $jsonColumns)) {
                // JSON column — decode pannurom
                $decoded = is_string($value)
                    ? json_decode($value, true)
                    : (is_array($value) ? $value : []);

                if (is_array($decoded)) {
                    // Nested arrays — dot notation flatten
                    $nested = $this->dotFlatten($decoded, $key);
                    foreach ($nested as $nKey => $nVal) {
                        $flat[$nKey] = $nVal;
                    }
                }
            }
            elseif ($key === 'associate_item_details') {
            $decoded = is_string($value)
                ? json_decode($value, true)
                : (is_array($value) ? $value : []);

            $allItems = array_merge(
                $decoded['items']    ?? [],
                $decoded['services'] ?? []
            );

            foreach ($allItems as $item) {
                $itemName = $item['name'] ?? 'unknown';
                foreach ($item as $field => $val) {
                    if (in_array($field, ['product_id', 'line_total_cost', 'line_total_selling'])) continue;
                    $flat["associate_item_details.{$itemName}.{$field}"] = $val;
                }
            }
} else {
                // Normal column
                $flat[$key] = $value;
            }
        }

        return $flat;
    }

    // Recursive dot flatten — nested array → flat key
    // Example: additional_data.description.sales_description
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

   public function created(Product $product): void
{
    History::create([
        'module'    => 'product',
        'action'    => 'create',
        'record_id' => $product->id,
        'user_id'   => Auth::id(),
        'old_data'  => null,
        'new_data'  => [
            'name' => $product->name,
            'sku'  => $product->sku,
            'type' => $product->type,
        ],
    ]);
}

  public function deleted(Product $product): void
{
    History::create([
        'module'    => 'product',
        'action'    => 'delete',
        'record_id' => $product->id,
        'user_id'   => Auth::id(),
        'old_data'  => [
            'name' => $product->name,
            'sku'  => $product->sku,
        ],
        'new_data'  => null,
    ]);
}
}
