<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSubmission;
use Illuminate\Http\Request;

class PaymentSubmissionApiController extends Controller
{
    public function tagihan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')
            ->whereIn('status', ['disetujui', 'jatuh_tempo']);

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
                'per_page' => $submissions->perPage(),
            ],
        ]);
    }

    public function pengajuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju', 'approver')
            ->where('pengaju_id', $request->user()->id);

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
                'per_page' => $submissions->perPage(),
            ],
        ]);
    }

    public function persetujuan(Request $request)
    {
        $query = PaymentSubmission::with('pengaju')->where('status', 'menunggu');

        if ($jenis = $request->jenis) {
            $query->where('jenis', $jenis);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $submissions->items(),
            'meta' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
                'per_page' => $submissions->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:255',
            'detail' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['pengaju_id'] = $request->user()->id;
        $validated['status'] = 'menunggu';
        $submission = PaymentSubmission::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pembayaran berhasil dikirim',
            'data' => $submission->load('pengaju'),
        ], 201);
    }

    public function show(PaymentSubmission $paymentSubmission)
    {
        return response()->json([
            'success' => true,
            'data' => $paymentSubmission->load('pengaju', 'approver'),
        ]);
    }

    public function approve(PaymentSubmission $paymentSubmission)
    {
        if ($paymentSubmission->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan sudah diproses',
            ], 422);
        }

        $paymentSubmission->update([
            'status' => 'disetujui',
            'approved_by' => request()->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pembayaran berhasil disetujui',
            'data' => $paymentSubmission->fresh()->load('pengaju', 'approver'),
        ]);
    }

    public function reject(Request $request, PaymentSubmission $paymentSubmission)
    {
        if ($paymentSubmission->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan sudah diproses',
            ], 422);
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $paymentSubmission->update([
            'status' => 'ditolak',
            'approved_by' => $request->user()->id,
            'keterangan' => $validated['keterangan'] ?? $paymentSubmission->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pembayaran berhasil ditolak',
            'data' => $paymentSubmission->fresh()->load('pengaju', 'approver'),
        ]);
    }

    public function uploadBukti(Request $request, PaymentSubmission $paymentSubmission)
    {
        $validated = $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');
        $paymentSubmission->update(['bukti' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'data' => $paymentSubmission->fresh()->load('pengaju'),
        ]);
    }

    public function markPaid(PaymentSubmission $paymentSubmission)
    {
        $paymentSubmission->update([
            'status' => 'lunas',
            'tgl_bayar' => today(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditandai lunas',
            'data' => $paymentSubmission->fresh()->load('pengaju', 'approver'),
        ]);
    }

    public function destroy(PaymentSubmission $paymentSubmission)
    {
        $paymentSubmission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pembayaran berhasil dihapus',
        ]);
    }
}
