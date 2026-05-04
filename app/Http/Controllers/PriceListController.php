<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceList;
use App\Models\Product;

class PriceListController extends Controller
{
    public function index()
    {
        $priceLists = PriceList::latest()->get();
        return view('products.pricelistindex', compact('priceLists'));
    }

    // ── Helper: Flatten products + variants into a single list ────────────────
    private function getFlatItems()
    {
        $products = Product::all();
        $flatItems = [];

        foreach ($products as $product) {
            $variantsRaw = $product->variants_data;

            // Decode if it's a string
            if (is_string($variantsRaw)) {
                $variantsRaw = json_decode($variantsRaw, true);
            }

            $variants = $variantsRaw['variants'] ?? [];

            if (!empty($variants)) {
                // Product has variants → add each variant as a separate row
                foreach ($variants as $variant) {
                    $flatItems[] = [
                        'id'            => $product->id . '__' . ($variant['sku'] ?? $variant['name']),
                        'name'          => $product->name . ' - ' . $variant['name'],
                        'sku'           => $variant['sku'] ?? '',
                        'selling_price' => (float)($variant['selling_price'] ?? 0),
                        'cost_price'    => (float)($variant['cost_price'] ?? 0),
                        'variant_name'  => $variant['name'],
                        'parent_id'     => $product->id,
                    ];
                }
            } else {
                // No variants → add product directly
                $flatItems[] = [
                    'id'            => $product->id,
                    'name'          => $product->name,
                    'sku'           => $product->sku ?? '',
                    'selling_price' => (float)($product->selling_price ?? 0),
                    'cost_price'    => (float)($product->cost_price ?? 0),
                    'variant_name'  => null,
                    'parent_id'     => null,
                ];
            }
        }

        return $flatItems;
    }

    public function create()
    {
        $items = $this->getFlatItems();

        $categories = \App\Models\UserCategory::whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'location_label', 'assign_fix_location', 'country_id']);

        return view('products.pricelist', compact('items', 'categories'));
    }

    // ── Helper: items[] array → JSON structure ────────────────────────────────
    private function buildItemsJson(Request $request, $allFlatItems): array
    {
        $unit       = [];
        $volume     = [];
        $items      = $request->input('items', []);
        $productMap = collect($allFlatItems)->keyBy('id');
        $txn        = $request->input('transaction_type', 'sales');

        foreach ($items as $row) {
            $itemId = $row['item_id'] ?? null;
            if (!$itemId) continue;

            $product   = $productMap->get($itemId);
            $salesRate = $product
                ? ($txn === 'purchase'
                    ? (float)($product['cost_price'] ?? 0)
                    : (float)($product['selling_price'] ?? 0))
                : null;

            $range = [
                'sales_rate'   => $salesRate,
                'start_qty'    => (isset($row['start_quantity']) && $row['start_quantity'] !== '')
                                    ? (int)$row['start_quantity'] : null,
                'end_qty'      => (isset($row['end_quantity']) && $row['end_quantity'] !== '')
                                    ? (int)$row['end_quantity'] : null,
                'custom_rate'  => (isset($row['custom_rate']) && $row['custom_rate'] !== '')
                                    ? (float)$row['custom_rate'] : 0,
            ];

            $scheme = $request->input('pricing_scheme', 'unit');

            if ($scheme === 'volume') {
                if (!isset($volume[$itemId])) {
                    $volume[$itemId] = [
                        'product_name' => $product['name'] ?? '',
                        'variant_name' => $product['variant_name'] ?? null,
                        'parent_id'    => $product['parent_id'] ?? null,
                        'ranges'       => [],
                    ];
                }
                $volume[$itemId]['ranges'][] = $range;
            } else {
                $unit[$itemId] = [
                    'product_name' => $product['name'] ?? '',
                    'variant_name' => $product['variant_name'] ?? null,
                    'parent_id'    => $product['parent_id'] ?? null,
                    'ranges'       => [$range],
                ];
            }
        }

        return [$unit ?: null, $volume ?: null];
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'transaction_type'  => 'required|in:sales,purchase,both',
            'price_list_type'   => 'required|in:all_items,individual_items',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable|integer',
            'category_name'     => 'nullable|string|max:100',
            'access_permission' => 'nullable|boolean',
            'markup_type'       => 'nullable|in:markup,markdown',
            'percentage'        => 'nullable|numeric|min:0|max:100',
            'round_off'         => 'nullable|string',
            'pricing_scheme'    => 'nullable|in:unit,volume',
            'currency'          => 'nullable|string',
            'include_discount'  => 'nullable|boolean',
        ]);

        $unitJson = $volumeJson = null;
        if ($request->input('price_list_type') === 'individual_items') {
            [$unitJson, $volumeJson] = $this->buildItemsJson($request, $this->getFlatItems());
        }

        PriceList::create([
            'name'                    => $request->name,
            'transaction_type'        => $request->transaction_type,
            'price_list_type'         => $request->price_list_type,
            'description'             => $request->description,
            'category_id'             => $request->category_id ?: null,
            'category_name'           => $request->category_name ?: null,
            'access_permission'       => $request->boolean('access_permission'),
            'markup_type'             => $request->markup_type,
            'percentage'              => $request->percentage,
            'round_off'               => $request->round_off,
            'pricing_scheme'          => $request->pricing_scheme,
            'currency'                => $request->currency ?? 'INR',
            'include_discount'        => $request->boolean('include_discount'),
            'individual_items_unit'   => $unitJson,
            'individual_items_volume' => $volumeJson,
        ]);

        return redirect()->route('price-lists.index')
                         ->with('success', 'Price list created successfully!');
    }

    public function show($id)
    {
        $priceList  = PriceList::findOrFail($id);
        $items      = $this->getFlatItems();
        $categories = \App\Models\UserCategory::whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'location_label', 'assign_fix_location', 'country_id']);

        return view('products.pricelist', compact('priceList', 'items', 'categories'));
    }

    public function edit($id)
    {
        $priceList  = PriceList::findOrFail($id);
        $items      = $this->getFlatItems();
        $categories = \App\Models\UserCategory::whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'location_label', 'assign_fix_location', 'country_id']);

        return view('products.pricelistedit', compact('priceList', 'items', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $priceList = PriceList::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'transaction_type'  => 'required|in:sales,purchase,both',
            'price_list_type'   => 'required|in:all_items,individual_items',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable|integer',
            'category_name'     => 'nullable|string|max:100',
            'access_permission' => 'nullable|boolean',
            'markup_type'       => 'nullable|in:markup,markdown',
            'percentage'        => 'nullable|numeric|min:0|max:100',
            'round_off'         => 'nullable|string',
            'pricing_scheme'    => 'nullable|in:unit,volume',
            'currency'          => 'nullable|string',
            'include_discount'  => 'nullable|boolean',
        ]);

        $unitJson = $volumeJson = null;
        if ($request->input('price_list_type') === 'individual_items') {
            [$unitJson, $volumeJson] = $this->buildItemsJson($request, $this->getFlatItems());
        }

        $priceList->update([
            'name'                    => $request->name,
            'transaction_type'        => $request->transaction_type,
            'price_list_type'         => $request->price_list_type,
            'description'             => $request->description,
            'category_id'             => $request->category_id ?: null,
            'category_name'           => $request->category_name ?: null,
            'access_permission'       => $request->boolean('access_permission'),
            'markup_type'             => $request->markup_type,
            'percentage'              => $request->percentage,
            'round_off'               => $request->round_off,
            'pricing_scheme'          => $request->pricing_scheme,
            'currency'                => $request->currency ?? 'INR',
            'include_discount'        => $request->boolean('include_discount'),
            'individual_items_unit'   => $unitJson,
            'individual_items_volume' => $volumeJson,
        ]);

        return redirect()->route('price-lists.index')
                         ->with('success', 'Price list updated successfully!');
    }

    public function destroy($id)
    {
        $priceList = PriceList::findOrFail($id);
        $priceList->delete();

        return redirect()->route('price-lists.index')
                         ->with('success', 'Price list deleted successfully!');
    }

    public function history($id)
    {
        $priceList = PriceList::withTrashed()->findOrFail($id);
        $histories = $priceList->histories()->with('user')->latest()->get();

        return view('products.pricelisthistory', compact('priceList', 'histories'));
    }
}