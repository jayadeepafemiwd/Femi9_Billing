<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\TransactionSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    // ── GET /locations ───────────────────────────────
    public function index()
    {
        $locations         = Location::orderBy('location_name')->get();
        $orgLogoUrl        = $this->getOrgLogoUrl();
        $transactionSeries = TransactionSeries::latest()->get();

        return view('locations.index', compact('locations', 'orgLogoUrl', 'transactionSeries'));
    }


    public function create()
{
    $locations = \App\Models\Location::whereNull('deleted_at')->get();
    return view('transaction-series.create', compact('locations'));
}


    // ── GET /locations/{id}/edit ─────────────────────
public function edit($id)
{
    try {
        $location = Location::findOrFail($id);

        if (request()->expectsJson() || request()->ajax()) {
            
            // transaction_series_id இப்போ json array
            $seriesIds = is_array($location->transaction_series_id)
                ? $location->transaction_series_id
                : ($location->transaction_series_id ? [$location->transaction_series_id] : []);

            // First series id-ஐ எடு name காட்ட
            $firstSeriesId   = $seriesIds[0] ?? null;
            $firstSeriesName = $firstSeriesId
                ? optional(TransactionSeries::find($firstSeriesId))->name
                : null;

            return response()->json([
                'success'                 => true,
                'id'                      => $location->id,
                'location_name'           => $location->location_name,
                'location_type'           => $location->location_type,
                'is_child'                => (bool) $location->is_child,
                'parent_location_id'      => $location->parent_location_id,
                'address_details'         => $location->address_details,
                'additional_data'         => $location->additional_data,
                'transaction_series_id'   => $firstSeriesId,
                'transaction_series_name' => $firstSeriesName,
            ]);
        }

        return redirect()->route('locations.index');

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Location not found'], 404);
    }
}
    // ── POST /locations ──────────────────────────────
   // ── POST /locations ──────────────────────────────
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'location_name'      => 'required|string|max:255',
            'location_type'      => 'required|in:business,warehouse',
            'parent_location_id' => 'nullable|exists:locations,id',
        ]);

        \DB::beginTransaction();  // ← ADD THIS

        $additionalData = $this->handleLogoUpload($request);

        // ── STEP 1: Resolve or create series ──────────
        $seriesId = $request->input('transaction_series_id') ?? null;

        if (!$seriesId && $request->has('new_series')) {
            $ns     = $request->input('new_series');
            $series = TransactionSeries::create([
                'name'          => $ns['name'],
                'series_data'   => collect($ns['series_data'])->map(fn($row) => [
            'module' => $row['module'],
            'prefix' => $row['prefix'] ?? '',
            'start'  => $row['starting_number'] ?? $row['start'] ?? '1', // ← fix
        ])->toArray(),
                'category_id'   => auth()->user()->category_id ?? 4,
                'category_name' => auth()->user()->category_name ?? 'stockiest',
                'is_default'    => 0,
                'location_id'   => null, // will update after location created
            ]);
            $seriesId = $series->id;
        }

        // ── STEP 2: Create location ────────────────────
   $location = Location::create([
    'location_name'         => $validated['location_name'],
    'location_type'         => $validated['location_type'],
    'is_child'              => $request->boolean('is_child'),
    'parent_location_id'    => $request->parent_location_id ?? null,
    'address_details'       => $request->address_details ?? null,
    'additional_data'       => !empty($additionalData) ? $additionalData : null,
    'transaction_series_id' => $seriesId ? [$seriesId] : null,  // ← array-ஆ store
]);

    // ── STEP 3: Link location_id back to series ────
if ($seriesId) {
    $series = TransactionSeries::find($seriesId);
    if ($series) {
        $existingLocIds = is_array($series->location_id)
            ? $series->location_id : [];

        if (!in_array($location->id, $existingLocIds)) {
            $existingLocIds[] = $location->id;
        }

        // ← Raw update with json_encode
        \DB::table('transaction_series')
            ->where('id', $seriesId)
            ->update([
                'location_id' => json_encode(array_values($existingLocIds))
            ]);
    }
}
        \DB::commit();  // ← ADD THIS

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Location created successfully',
                'id'      => $location->id,
             'data' => $location->fresh(),
            ]);
        }

        return redirect()->route('locations.index')->with('success', 'Location created successfully');

    } catch (\Exception $e) {
        \DB::rollBack();  // ← ADD THIS
        Log::error('Location store error: ' . $e->getMessage());

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return back()->with('error', 'Failed to create location');
    }
}

    // ── PUT /locations/{id} ──────────────────────────
  public function update(Request $request, Location $location)
{
    try {
        $validated = $request->validate([
            'location_name'      => 'required|string|max:255',
            'location_type'      => 'required|in:business,warehouse',
            'parent_location_id' => 'nullable|exists:locations,id',
        ]);

        \DB::beginTransaction();

        $additionalData = $this->handleLogoUpload($request);

        if (!empty($additionalData)) {
            $this->deleteExistingLogo($location);
        } else {
            $additionalData = $location->additional_data ?? [];
        }

        if ($request->boolean('logo_removed')) {
            $this->deleteExistingLogo($location);
            unset($additionalData['location_image']);
        }

        // ── STEP 1: Resolve series ID ──────────────────
        $seriesId = $request->input('transaction_series_id') ?? null;

        // Existing series ids from location (json array)
        $existingSeriesIds = is_array($location->transaction_series_id)
            ? $location->transaction_series_id
            : ($location->transaction_series_id ? [$location->transaction_series_id] : []);

        if (!$seriesId && !empty($existingSeriesIds)) {
            $seriesId = $existingSeriesIds[0];
        }

        // New series create பண்றீங்களா?
        if (!$seriesId && $request->has('new_series')) {
            $ns     = $request->input('new_series');
            $series = TransactionSeries::create([
                'name'          => $ns['name'],
                'series_data'   => collect($ns['series_data'])->map(fn($row) => [
                    'module' => $row['module'],
                    'prefix' => $row['prefix'] ?? '',
                    'start'  => $row['starting_number'] ?? $row['start'] ?? '1',
                ])->toArray(),
                'category_id'   => auth()->user()->category_id ?? 4,
                'category_name' => auth()->user()->category_name ?? 'stockiest',
                'is_default'    => 0,
                'location_id'   => json_encode([$location->id]),
            ]);
            $seriesId = $series->id;
        }

        // ── STEP 2: Update location ────────────────────
        // Series ids array build
        $newSeriesIds = $existingSeriesIds;
        if ($seriesId && !in_array($seriesId, $newSeriesIds)) {
            $newSeriesIds[] = (int) $seriesId;
        }

        $location->update([
            'location_name'         => $validated['location_name'],
            'location_type'         => $validated['location_type'],
            'is_child'              => $request->boolean('is_child'),
            'parent_location_id'    => $request->parent_location_id ?? null,
            'address_details'       => $request->address_details ?? null,
            'additional_data'       => !empty($additionalData) ? $additionalData : null,
            'transaction_series_id' => !empty($newSeriesIds) ? $newSeriesIds : null,
        ]);

        // ── STEP 3: Link location_id back to series ────
        if ($seriesId) {
            $series = TransactionSeries::find($seriesId);
            if ($series) {
                $existingLocIds = is_array($series->location_id)
                    ? $series->location_id : [];

                if (!in_array($location->id, $existingLocIds)) {
                    $existingLocIds[] = $location->id;
                }

                // JSON encode பண்ணி raw update பண்ணு
                \DB::table('transaction_series')
                    ->where('id', $seriesId)
                    ->update([
                        'location_id' => json_encode(array_values($existingLocIds))
                    ]);
            }
        }

        \DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data'    => $location->fresh(),
            ]);
        }

        return redirect()->route('locations.index')->with('success', 'Location updated successfully');

    } catch (\Exception $e) {
        \DB::rollBack();
        Log::error('Location update error: ' . $e->getMessage());

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return back()->with('error', 'Failed to update location');
    }
}
    // ── DELETE /locations/{id} ───────────────────────
    public function destroy(Request $request, Location $location)
    {
        try {
            $productCount = $location->stocks()->count();

            if ($productCount > 0) {
                $msg = "Cannot delete. {$productCount} product(s) are assigned to this location.";

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }

                return back()->with('error', $msg);
            }

            // ⭐ Detach pivot rows before delete
           TransactionSeries::where('location_id', $location->id)
    ->update(['location_id' => null]);

            $this->deleteExistingLogo($location);
            $location->delete();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Location deleted successfully']);
            }

            return redirect()->route('locations.index')->with('success', 'Location deleted successfully');

        } catch (\Exception $e) {
            Log::error('Location destroy error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to delete location');
        }
    }

    // ── Private Helpers ───────────────────────────────

    /**
     * ⭐ Sync transaction series pivot table
     * Request expects:
     *   transaction_series_ids: [1, 2, 3]   (array of series IDs)
     *   default_series_id: 1                 (which one is default)
     */
   
    private function getOrgLogoUrl(): ?string
    {
        try {
            return asset('images/org_logo.png');
        } catch (\Exception $e) {
            Log::warning('Could not load org logo: ' . $e->getMessage());
            return null;
        }
    }

    private function handleLogoUpload(Request $request): array
    {
        $additionalData = [];
        $imgData        = $request->input('location_image');

        if (empty($imgData) || empty($imgData['data'])) {
            return $additionalData;
        }

        if (!str_contains($imgData['data'], 'base64,')) {
            Log::warning('Invalid base64 logo data received');
            return $additionalData;
        }

        try {
            $base64String = explode('base64,', $imgData['data'])[1];

            $mimeType  = $imgData['mime_type'] ?? 'image/jpeg';
            $extension = match ($mimeType) {
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };

            $folderPath = public_path('image/location_logo_img');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $uniqueFileName = uniqid('loc_', true) . '.' . $extension;
            $fullFilePath   = $folderPath . DIRECTORY_SEPARATOR . $uniqueFileName;

            $decoded = base64_decode($base64String);
            if ($decoded === false) {
                Log::error('Base64 decode failed for logo upload');
                return $additionalData;
            }

            file_put_contents($fullFilePath, $decoded);

            $additionalData['location_image'] = [
                'path'      => 'image/location_logo_img/' . $uniqueFileName,
                'url'       => asset('image/location_logo_img/' . $uniqueFileName),
                'file_name' => $imgData['file_name'] ?? 'logo.' . $extension,
                'mime_type' => $mimeType,
            ];

            Log::info('Logo saved to: ' . $fullFilePath);

        } catch (\Exception $e) {
            Log::error('Logo upload failed: ' . $e->getMessage());
        }

        return $additionalData;
    }

    private function deleteExistingLogo(Location $location): void
    {
        $additionalData = $location->additional_data ?? [];

        if (!empty($additionalData['location_image']['path'])) {
            $fullPath = public_path($additionalData['location_image']['path']);

            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('Deleted logo file: ' . $fullPath);
            }
        }
    }
}