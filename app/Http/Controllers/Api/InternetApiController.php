<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternetPayment;
use App\Models\InternetUsageCheck;
use Illuminate\Http\Request;

class InternetApiController extends Controller
{
    public function payments(Request $request)
    {
        $query = InternetPayment::with('creator');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_internet', 'like', "%{$request->search}%")
                  ->orWhere('provider', 'like', "%{$request->search}%")
                  ->orWhere('pic', 'like', "%{$request->search}%");
            });
        }

        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'total' => $payments->total(),
            ],
        ]);
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'nama_internet' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_tenggang' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'status' => 'nullable|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;
        $payment = InternetPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan internet berhasil ditambahkan',
            'data' => $payment->load('creator'),
        ], 201);
    }

    public function updatePayment(Request $request, InternetPayment $internetPayment)
    {
        $validated = $request->validate([
            'nama_internet' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'masa_tenggang' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'status' => 'nullable|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $internetPayment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan internet berhasil diupdate',
            'data' => $internetPayment->load('creator'),
        ]);
    }

    public function destroyPayment(InternetPayment $internetPayment)
    {
        $internetPayment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan internet berhasil dihapus',
        ]);
    }

    public function stats()
    {
        $totalWifi = InternetPayment::count();
        $sudahDibayar = InternetPayment::where('status', 'lunas')->count();
        $jatuhTempo = InternetPayment::where('status', 'menunggu')
            ->where('masa_tenggang', '>=', now())
            ->count();
        $terlambat = InternetPayment::where('status', 'terlambat')
            ->orWhere(function ($q) {
                $q->where('status', 'menunggu')
                  ->where('masa_tenggang', '<', now());
            })
            ->count();
        $totalBiaya = InternetPayment::where('status', 'lunas')->sum('biaya');

        return response()->json([
            'success' => true,
            'data' => compact('totalWifi', 'sudahDibayar', 'jatuhTempo', 'terlambat', 'totalBiaya'),
        ]);
    }

    public function checks(Request $request)
    {
        $query = InternetUsageCheck::with('checker');

        if ($request->month && $request->year) {
            $query->whereMonth('tanggal', $request->month)
                  ->whereYear('tanggal', $request->year);
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
            'ruangan' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'penggunaan_wifi' => 'required|numeric|min:0',
            'penggunaan_ethernet' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['checked_by'] = $request->user()->id;
        $check = InternetUsageCheck::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengecekan usage internet berhasil dicatat',
            'data' => $check->load('checker'),
        ], 201);
    }

    public function destroyCheck(InternetUsageCheck $internetUsageCheck)
    {
        $internetUsageCheck->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengecekan berhasil dihapus',
        ]);
    }
}
