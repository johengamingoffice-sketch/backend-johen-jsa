<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['category', 'asset', 'creator']);

        if ($request->category_id) {
            $query->where('payment_category_id', $request->category_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->asset_id) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->period) {
            $query->where('period', $request->period);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('vendor', 'like', "%{$request->search}%");
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
            'invoice_number' => 'required|string|max:255|unique:payments,invoice_number',
            'payment_category_id' => 'required|exists:payment_categories,id',
            'vendor' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'period' => 'nullable|string|max:50',
            'status' => 'nullable|in:lunas,belum_dibayar,tertunda,dibatalkan',
            'notes' => 'nullable|string',
            'asset_id' => 'nullable|exists:assets,id',
        ]);

        $validated['created_by'] = $request->user()->id;
        $payment = Payment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dicatat',
            'data' => $payment->load(['category', 'asset', 'creator']),
        ], 201);
    }

    public function show(Payment $payment)
    {
        return response()->json([
            'success' => true,
            'data' => $payment->load(['category', 'asset', 'creator']),
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255|unique:payments,invoice_number,' . $payment->id,
            'payment_category_id' => 'required|exists:payment_categories,id',
            'vendor' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'period' => 'nullable|string|max:50',
            'status' => 'nullable|in:lunas,belum_dibayar,tertunda,dibatalkan',
            'notes' => 'nullable|string',
            'asset_id' => 'nullable|exists:assets,id',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diupdate',
            'data' => $payment->load(['category', 'asset', 'creator']),
        ]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus',
        ]);
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);

        $path = $request->file('proof')->store('payment-proofs', 'public');
        $payment->update(['proof_file' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti bayar berhasil diupload',
            'data' => $payment,
        ]);
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:lunas,belum_dibayar,tertunda,dibatalkan',
            'payment_date' => 'required_if:status,lunas|nullable|date',
        ]);

        $payment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diupdate',
            'data' => $payment,
        ]);
    }

    public function statistics(Request $request)
    {
        $query = Payment::query();

        if ($request->year) {
            $query->whereYear('payment_date', $request->year);
        }

        if ($request->month) {
            $query->whereMonth('payment_date', $request->month);
        }

        $totalPaid = (clone $query)->where('status', 'lunas')->sum('amount');
        $totalUnpaid = (clone $query)->whereIn('status', ['belum_dibayar', 'tertunda'])->sum('amount');
        $byCategory = (clone $query)
            ->selectRaw('payment_category_id, SUM(amount) as total')
            ->groupBy('payment_category_id')
            ->with('category')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_paid' => $totalPaid,
                'total_unpaid' => $totalUnpaid,
                'by_category' => $byCategory,
                'count_paid' => (clone $query)->where('status', 'lunas')->count(),
                'count_unpaid' => (clone $query)->whereIn('status', ['belum_dibayar', 'tertunda'])->count(),
            ],
        ]);
    }

    public function upcoming(Request $request)
    {
        $payments = Payment::with(['category', 'asset'])
            ->whereIn('status', ['belum_dibayar', 'tertunda'])
            ->where(function ($q) {
                $q->whereNull('due_date')
                  ->orWhere('due_date', '>=', now()->subDays(7));
            })
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }
}
