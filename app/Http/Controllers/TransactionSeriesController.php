<?php

namespace App\Http\Controllers;

use App\Models\TransactionSeries;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionSeriesController extends Controller
{
    // ── GET /transaction-series ──────────────────────────────
    public function index()
    {
        $series = TransactionSeries::latest()->get();

        if (request()->wantsJson()) {
            return response()->json($series);
        }

        return view('transaction-series.index', compact('series'));
    }

    // ── GET /transaction-series/create ──────────────────────
  // App\Http\Controllers\TransactionSeriesController.php

public function create()
{
    $locations = \App\Models\Location::all(); // already irukkum

    // ✅ இதை add பண்ணுங்க
    $categories = \App\Models\UserCategory::all()
        ->map(function($cat) {
            return (object)[
                'id'             => $cat->id,
                'name'           => $cat->name,
                'location_label' => $cat->location_label ?? null,
            ];
        });

    return view('transaction-series.create', compact('locations', 'categories'));
}
    // ── POST /transaction-series ─────────────────────────────
    //
    // ⭐ KEY POINT:
    //   Series is saved WITHOUT location_id here.
    //   location_id is assigned later when Location is saved/updated
    //   via LocationController::syncTransactionSeries().
    //
 public function store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'series' => 'required|array|min:1',
    ]);

    \DB::beginTransaction();
    try {
        $ts = TransactionSeries::create([
            'name'          => $request->name,
            'location_id'   => null,
            'is_default'    => false,
            'category_id'   => $request->input('category_id'),
            'category_name' => $request->input('category_name'),
            'series_data'   => collect($request->series)->map(fn($row) => [
                'module' => $row['module'],
                'prefix' => $row['prefix'] ?? '',
                'start'  => $row['start'] ?? $row['starting_number'] ?? '1',
            ])->toArray(),
            'created_by' => Auth::id(),
        ]);

        // ── இங்கே போடுங்க ──────────────────────────
        $locationIds = $request->input('location_ids', []);
        if ($request->input('location_id')) {
            $locationIds = [$request->input('location_id')];
        }

        if (!empty($locationIds)) {
            \DB::table('transaction_series')
                ->where('id', $ts->id)
                ->update(['location_id' => json_encode(array_map('intval', $locationIds))]);

           foreach ($locationIds as $locId) {
    // Model use பண்ணாம raw query
    $locationRow = \DB::table('locations')
        ->where('id', $locId)
        ->whereNull('deleted_at')
        ->first();

    if (!$locationRow) continue;

    $existingSeriesIds = [];
    if (!empty($locationRow->transaction_series_id)) {
        $decoded = json_decode($locationRow->transaction_series_id, true);
        $existingSeriesIds = is_array($decoded) ? $decoded : [];
    }

    if (!in_array($ts->id, $existingSeriesIds)) {
        $existingSeriesIds[] = $ts->id;
    }

    \DB::table('locations')
        ->where('id', $locId)
        ->update([
            'transaction_series_id' => json_encode(array_values($existingSeriesIds)),
            'updated_at' => now(),
        ]);
}
        }
        // ────────────────────────────────────────────

        \DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaction series created successfully',
            'id'      => $ts->id,
            'name'    => $ts->name,
            'data'    => $ts->fresh(),
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Series store error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}
    // ── GET /transaction-series/{id} ─────────────────────────
    public function show(TransactionSeries $transactionSeries)
    {
        return response()->json($transactionSeries);
    }

    // ── GET /transaction-series/{id}/edit ────────────────────
    public function edit(TransactionSeries $transactionSeries)
    {
        $locations = Location::whereNull('deleted_at')->get();
        return view('transaction-series.edit', [
            'transactionSeries' => $transactionSeries,
            'locations'         => $locations,
        ]);
    }

    // ── PUT /transaction-series/{id} ─────────────────────────
    public function update(Request $request, TransactionSeries $transactionSeries)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'series' => 'required|array|min:1',
    ]);

    \DB::beginTransaction();
    try {
        // ── Series data update ──
        $transactionSeries->update([
            'name'        => $request->name,
            'series_data' => collect($request->series)->map(fn($row) => [
                'module' => $row['module'],
                'prefix' => $row['prefix'] ?? '',
                'start'  => $row['start'] ?? $row['starting_number'] ?? '1',
            ])->toArray(),
        ]);

        // ── Location IDs handle ──
        $newLocationIds = array_map('intval', $request->input('location_ids', []));

        if (!empty($newLocationIds)) {
            // transaction_series table — location_id json update
            $existingLocIds = is_array($transactionSeries->location_id)
                ? $transactionSeries->location_id : [];

            $mergedLocIds = array_values(array_unique(
                array_merge($existingLocIds, $newLocationIds)
            ));

           \DB::table('transaction_series')
    ->where('id', $transactionSeries->id)
    ->update(['location_id' => json_encode($mergedLocIds)]);

            // locations table — ஒவ்வொரு location-லயும் series id add
            foreach ($newLocationIds as $locId) {
             $locationRow = \DB::table('locations')->where('id', $locId)->first();
if (!$locationRow) continue;
$existingSeriesIds = [];
if (!empty($locationRow->transaction_series_id)) {
    $decoded = json_decode($locationRow->transaction_series_id, true);
    $existingSeriesIds = is_array($decoded) ? $decoded : [];
}
                if (!in_array($transactionSeries->id, $existingSeriesIds)) {
                    $existingSeriesIds[] = $transactionSeries->id;
                  \DB::table('locations')
    ->where('id', $locId)
    ->update([
        'transaction_series_id' => json_encode(array_values($existingSeriesIds))
    ]);
                }
            }
        }

        \DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaction series updated successfully',
            'data'    => $transactionSeries->fresh(),
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Series update error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}

public function byLocationCategory(Request $request)
{
    $locationId = $request->query('location_id');
    $category   = $request->query('category');

    $series = \App\Models\TransactionSeries::whereNull('deleted_at')
        ->where(function($q) use ($category) {
            $q->whereNull('category_name')
              ->orWhere('category_name', $category);
        })
        ->get()
        ->filter(function($s) use ($locationId) {
            if (!$locationId) return true;
            
            $locationIds = is_string($s->location_id)
                ? json_decode($s->location_id, true)
                : ($s->location_id ?? []);
            
            // Empty location = all locations-க்கும் valid
            if (empty($locationIds)) return true;
            
            return in_array($locationId, $locationIds);
        })
        ->values()
        ->map(function($s) {
            $data = is_string($s->series_data)
                ? json_decode($s->series_data, true)
                : ($s->series_data ?? []);
            
            // Credit Note series மட்டும் filter
            $cnSeries = collect($data)->filter(fn($d) => 
                isset($d['module']) && strtolower($d['module']) === 'credit_note'
            )->values();
            
            if ($cnSeries->isEmpty()) return null;
            
            return [
                'id'         => $s->id,
                'name'       => $s->name,
                'is_default' => $s->is_default,
                'series'     => $cnSeries,
            ];
        })
        ->filter()
        ->values();

    return response()->json($series);
}
    // ── DELETE /transaction-series/{id} ──────────────────────
    public function destroy(TransactionSeries $transactionSeries)
    {
        $transactionSeries->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction series deleted successfully',
        ]);
    }
    public function list()
{
    $series = TransactionSeries::orderBy('name')->get(['id', 'name']);
    return response()->json(['series' => $series]);
}
}