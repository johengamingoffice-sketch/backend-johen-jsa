<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElectricitySetting;
use App\Models\ElectricityTokenCheck;
use App\Models\ElectricityTopup;
use Illuminate\Http\Request;

class ElectricityApiController extends Controller
{
    public function stats()
    {
        $setting = ElectricitySetting::firstOrCreate(['id' => 1], ['kapasitas_kwh' => 3000]);
        $lastTopup = ElectricityTopup::with('creator')->latest()->first();
        $lastCheck = ElectricityTokenCheck::with('checker')->latest()->first();
        $totalTerpakai = ElectricityTokenCheck::sum('terpakai');
        $sisaToken = $lastCheck?->sisa_kwh ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'setting' => $setting,
                'last_topup' => $lastTopup,
                'last_check' => $lastCheck,
                'total_terpakai' => $totalTerpakai,
                'sisa_token' => $sisaToken,
            ],
        ]);
    }

    public function topups(Request $request)
    {
        $query = ElectricityTopup::with('creator');

        if ($filter = $request->filter) {
            $query = match ($filter) {
                'harian' => $query->whereDate('created_at', today()),
                'mingguan' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                default => $query,
            };
        }

        $topups = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $topups->items(),
            'meta' => [
                'current_page' => $topups->currentPage(),
                'last_page' => $topups->lastPage(),
                'total' => $topups->total(),
                'per_page' => $topups->perPage(),
            ],
        ]);
    }

    public function storeTopup(Request $request)
    {
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'periode' => 'required|string|max:50',
            'jumlah_kwh' => 'required|numeric|min:0',
            'nominal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;
        $topup = ElectricityTopup::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Top up token berhasil dicatat',
            'data' => $topup->load('creator'),
        ], 201);
    }

    public function destroyTopup(ElectricityTopup $electricityTopup)
    {
        $electricityTopup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat top up berhasil dihapus',
        ]);
    }

    public function checks(Request $request)
    {
        $query = ElectricityTokenCheck::with('checker');

        if ($filter = $request->filter) {
            $query = match ($filter) {
                'harian' => $query->whereDate('created_at', today()),
                'mingguan' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                default => $query,
            };
        }

        $checks = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $checks->items(),
            'meta' => [
                'current_page' => $checks->currentPage(),
                'last_page' => $checks->lastPage(),
                'total' => $checks->total(),
            ],
        ]);
    }

    public function storeCheck(Request $request)
    {
        $validated = $request->validate([
            'tanggal_check' => 'required|date',
            'sisa_kwh' => 'required|numeric|min:0',
            'terpakai' => 'required|numeric|min:0',
            'status' => 'nullable|in:normal,rendah,habis',
            'catatan' => 'nullable|string',
        ]);

        $validated['checked_by'] = $request->user()->id;

        if (!isset($validated['status'])) {
            $validated['status'] = match (true) {
                $validated['sisa_kwh'] <= 0 => 'habis',
                $validated['sisa_kwh'] <= 500 => 'rendah',
                default => 'normal',
            };
        }

        $check = ElectricityTokenCheck::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengecekan token berhasil dicatat',
            'data' => $check->load('checker'),
        ], 201);
    }

    public function destroyCheck(ElectricityTokenCheck $electricityTokenCheck)
    {
        $electricityTokenCheck->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengecekan berhasil dihapus',
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'kapasitas_kwh' => 'required|numeric|min:0',
        ]);

        $setting = ElectricitySetting::firstOrCreate(
            ['id' => 1],
            ['kapasitas_kwh' => 3000]
        );

        $setting->update([
            'kapasitas_kwh' => $validated['kapasitas_kwh'],
            'updated_by' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan kapasitas token berhasil diupdate',
            'data' => $setting,
        ]);
    }
}
