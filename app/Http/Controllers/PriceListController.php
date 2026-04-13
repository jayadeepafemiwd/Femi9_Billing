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
   public function create()
{
    $items = Product::all();
    $categories = \App\Models\UserCategory::whereNull('deleted_at')
        ->orderBy('sort_order')
        ->get(['id', 'name', 'location_label', 'assign_fix_location', 'country_id']);
    
    return view('products.pricelist', compact('items', 'categories'));
}
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'transaction_type' => 'required|in:sales,purchase,both',
            'price_list_type'  => 'required|in:all_items,individual_items',
            'description'      => 'nullable|string',
            'category_id'      => 'nullable|integer',
            'category_name'    => 'nullable|string|max:100',
            'access_permission' => 'nullable|boolean',
            'markup_type'      => 'nullable|in:markup,markdown',
            'percentage'       => 'nullable|numeric|min:0|max:100',
            'round_off'        => 'nullable|string',
            'pricing_scheme'   => 'nullable|in:unit,volume',
            'currency'         => 'nullable|string',
            'include_discount' => 'nullable|boolean',
        ]);

        PriceList::create([
            'name'             => $request->name,
            'transaction_type' => $request->transaction_type,
            'price_list_type'  => $request->price_list_type,
            'description'      => $request->description,
            'category_id'      => $request->category_id ?: null,
            'category_name'    => $request->category_name ?: null,
            'access_permission' => $request->boolean('access_permission'),
            'markup_type'      => $request->markup_type,
            'percentage'       => $request->percentage,
            'round_off'        => $request->round_off,
            'pricing_scheme'   => $request->pricing_scheme,
            'currency'         => $request->currency ?? 'INR',
            'include_discount' => $request->boolean('include_discount'),
        ]);

        return redirect()->route('price-lists.index')
                         ->with('success', 'Price list created successfully!');
    }

public function show($id)
{
    $priceList  = PriceList::findOrFail($id);
    $items      = Product::all();
    $categories = \App\Models\UserCategory::whereNull('deleted_at')
        ->orderBy('sort_order')
        ->get(['id', 'name', 'location_label', 'assign_fix_location', 'country_id']);

    return view('products.pricelist', compact('priceList', 'items', 'categories'));
}

   public function edit($id)
{
    $priceList = PriceList::findOrFail($id);
    $items      = Product::all();
    $categories = \App\Models\UserCategory::all(); // உங்கள் model name-க்கு ஏற்ப மாற்றவும்
    return view('products.pricelistedit', compact('priceList', 'items', 'categories'));
}
   public function update(Request $request, $id)
{
    $priceList = PriceList::findOrFail($id);

    $request->validate([
        'name'             => 'required|string|max:255',
        'transaction_type' => 'required|in:sales,purchase,both',
        'price_list_type'  => 'required|in:all_items,individual_items',
        'description'      => 'nullable|string',
        'category_id'      => 'nullable|integer',
        'category_name'    => 'nullable|string|max:100',
        'access_permission' => 'nullable|boolean',
        'markup_type'      => 'nullable|in:markup,markdown',
        'percentage'       => 'nullable|numeric|min:0|max:100',
        'round_off'        => 'nullable|string',
        'pricing_scheme'   => 'nullable|in:unit,volume',
        'currency'         => 'nullable|string',
        'include_discount' => 'nullable|boolean',
    ]);

    $priceList->update([
        'name'             => $request->name,
        'transaction_type' => $request->transaction_type,
        'price_list_type'  => $request->price_list_type,
        'description'      => $request->description,
        'category_id'      => $request->category_id ?: null,
        'category_name'    => $request->category_name ?: null,
        'access_permission' => $request->boolean('access_permission'),
        'markup_type'      => $request->markup_type,
        'percentage'       => $request->percentage,
        'round_off'        => $request->round_off,
        'pricing_scheme'   => $request->pricing_scheme,
        'currency'         => $request->currency ?? 'INR',
        'include_discount' => $request->boolean('include_discount'),
    ]);

    // ✅ History தேவையில்லை — Observer automatically handle பண்ணும்

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