<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'creator']);

        if ($request->category_id) {
            $query->byCategory($request->category_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('brand', 'like', "%{$request->search}%");
            });
        }

        $assets = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $assets->items(),
            'meta' => [
                'current_page' => $assets->currentPage(),
                'last_page' => $assets->lastPage(),
                'total' => $assets->total(),
                'per_page' => $assets->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:assets,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'status' => 'nullable|in:tersedia,dipinjam,dalam_perbaikan,dihapuskan',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;
        $asset = Asset::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil ditambahkan',
            'data' => $asset->load(['category', 'creator']),
        ], 201);
    }

    public function show(Asset $asset)
    {
        return response()->json([
            'success' => true,
            'data' => $asset->load(['category', 'creator', 'loans.employee', 'loans.approver', 'maintenances.creator']),
        ]);
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:assets,code,' . $asset->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'status' => 'nullable|in:tersedia,dipinjam,dalam_perbaikan,dihapuskan',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $asset->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil diupdate',
            'data' => $asset->load(['category', 'creator']),
        ]);
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil dihapus',
        ]);
    }

    public function uploadPhoto(Request $request, Asset $asset)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('photo')->store('assets', 'public');
        $asset->update(['photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto aset berhasil diupload',
            'data' => $asset,
        ]);
    }

    public function loans(Request $request, Asset $asset)
    {
        $loans = $asset->loans()
            ->with(['employee', 'approver'])
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $loans->items(),
            'meta' => [
                'current_page' => $loans->currentPage(),
                'last_page' => $loans->lastPage(),
                'total' => $loans->total(),
            ],
        ]);
    }

    public function storeLoan(Request $request, Asset $asset)
    {
        if ($asset->status !== 'tersedia') {
            return response()->json([
                'success' => false,
                'message' => 'Aset sedang tidak tersedia untuk dipinjam',
            ], 409);
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['asset_id'] = $asset->id;
        $validated['approved_by'] = $request->user()->id;
        $validated['status'] = 'dipinjam';

        $loan = AssetLoan::create($validated);

        $asset->update(['status' => 'dipinjam']);

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil dipinjamkan',
            'data' => $loan->load(['employee', 'approver']),
        ], 201);
    }

    public function returnLoan(Request $request, AssetLoan $assetLoan)
    {
        if ($assetLoan->status !== 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Aset ini sudah dikembalikan',
            ], 409);
        }

        $validated = $request->validate([
            'return_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'dikembalikan';

        $assetLoan->update($validated);

        $assetLoan->asset->update(['status' => 'tersedia']);

        return response()->json([
            'success' => true,
            'message' => 'Aset berhasil dikembalikan',
            'data' => $assetLoan->load(['employee', 'approver']),
        ]);
    }

    public function maintenances(Request $request, Asset $asset)
    {
        $maintenances = $asset->maintenances()
            ->with('creator')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $maintenances->items(),
            'meta' => [
                'current_page' => $maintenances->currentPage(),
                'last_page' => $maintenances->lastPage(),
                'total' => $maintenances->total(),
            ],
        ]);
    }

    public function storeMaintenance(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'notes' => 'nullable|string',
        ]);

        $validated['asset_id'] = $asset->id;
        $validated['created_by'] = $request->user()->id;

        if ($asset->status === 'tersedia') {
            $asset->update(['status' => 'dalam_perbaikan']);
        }

        $maintenance = AssetMaintenance::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pemeliharaan berhasil ditambahkan',
            'data' => $maintenance->load('creator'),
        ], 201);
    }
}
