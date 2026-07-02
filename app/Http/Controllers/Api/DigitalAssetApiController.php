<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DigitalAsset;
use Illuminate\Http\Request;

class DigitalAssetApiController extends Controller
{
    public function index(Request $request)
    {
        $query = DigitalAsset::with('creator');

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
                $q->where('nama_aset', 'like', "%{$search}%")
                    ->orWhere('tagihan', 'like', "%{$search}%");
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
            'nama_aset' => 'required|string|max:255',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = $request->user()->id;
        $asset = DigitalAsset::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan aset digital berhasil ditambahkan',
            'data' => $asset->load('creator'),
        ], 201);
    }

    public function show(DigitalAsset $digitalAsset)
    {
        return response()->json([
            'success' => true,
            'data' => $digitalAsset->load('creator'),
        ]);
    }

    public function update(Request $request, DigitalAsset $digitalAsset)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:255',
            'tagihan' => 'required|string|max:255',
            'jatuh_tempo' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,menunggu,terlambat',
            'tgl_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $digitalAsset->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan aset digital berhasil diperbarui',
            'data' => $digitalAsset->fresh()->load('creator'),
        ]);
    }

    public function destroy(DigitalAsset $digitalAsset)
    {
        $digitalAsset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tagihan aset digital berhasil dihapus',
        ]);
    }

    public function markPaid(DigitalAsset $digitalAsset)
    {
        $digitalAsset->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tagihan aset digital berhasil ditandai lunas',
            'data' => $digitalAsset->fresh()->load('creator'),
        ]);
    }
}
