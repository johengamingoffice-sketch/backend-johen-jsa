<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IplRukoPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IplRukoApiController extends Controller
{
    public function index(Request $request)
    {
        $query = IplRukoPayment::with('creator');

        if ($status = $request->status) {
            if ($status === 'terlambat') {
                $query->where(function ($q) {
                    $q->where('status', 'terlambat')
                        ->orWhere(function ($q2) {
                            $q2->where('status', 'menunggu')->whereDate('jatuh_tempo', '<', today());
                        });
                });
            } else {
                $query->where('status', $status);
            }
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('periode', 'like', "%{$search}%")
                    ->orWhere('tagihan', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'total' => $payments->total(),
                'per_page' => $payments->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string|max:50',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;
        $payment = IplRukoPayment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan IPL Ruko berhasil ditambahkan',
            'data' => $payment->load('creator'),
        ], 201);
    }

    public function show(IplRukoPayment $iplRukoPayment)
    {
        return response()->json([
            'success' => true,
            'data' => $iplRukoPayment->load('creator'),
        ]);
    }

    public function update(Request $request, IplRukoPayment $iplRukoPayment)
    {
        $validated = $request->validate([
            'periode' => 'required|string|max:50',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $iplRukoPayment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan IPL Ruko berhasil diperbarui',
            'data' => $iplRukoPayment->fresh()->load('creator'),
        ]);
    }

    public function destroy(IplRukoPayment $iplRukoPayment)
    {
        $iplRukoPayment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan IPL Ruko berhasil dihapus',
        ]);
    }

    public function markPaid(IplRukoPayment $iplRukoPayment)
    {
        $iplRukoPayment->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan IPL Ruko berhasil ditandai lunas',
            'data' => $iplRukoPayment->fresh()->load('creator'),
        ]);
    }

    public function generateYear(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2100',
            'tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'jatuh_tempo_hari' => 'required|integer|min:1|max:28',
        ]);

        $tahun = $request->tahun;
        $createdBy = $request->user()->id;
        $count = 0;

        $namaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $periode = $namaBulan[$bulan - 1] . ' ' . $tahun;
            $jatuhTempo = Carbon::create($tahun, $bulan, $request->jatuh_tempo_hari);

            $exists = IplRukoPayment::where('periode', $periode)->exists();
            if ($exists) continue;

            IplRukoPayment::create([
                'periode' => $periode,
                'tagihan' => $request->tagihan,
                'jatuh_tempo' => $jatuhTempo,
                'nominal' => $request->nominal,
                'status' => 'menunggu',
                'created_by' => $createdBy,
            ]);

            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil generate {$count} tagihan IPL Ruko untuk tahun {$tahun}",
            'data' => ['count' => $count, 'tahun' => $tahun],
        ]);
    }
}
