<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ItemStockLedger;
use Illuminate\Http\Request;

class ItemStockLedgerController extends Controller
{
    
    public function create()
    {
        return view('stock_ledger.create');
    }

    // API — JSON data return பண்ண
    public function index(Request $request)
    {
        $query = ItemStockLedger::query()
            ->with(['item', 'location'])
            ->whereBetween('transaction_date', [
                $request->from_date,
                $request->to_date
            ]);

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $ledger = $query->orderBy('transaction_date')->orderBy('id')->get();

        return response()->json([
            'data'    => $ledger,
            'summary' => [
                'total_transactions' => $ledger->count(),
                'total_in'  => $ledger->where('qty_change', '>', 0)->sum('qty_change'),
                'total_out' => $ledger->where('qty_change', '<', 0)->sum('qty_change'),
                'net_change'=> $ledger->sum('qty_change'),
            ]
        ]);
    }
}