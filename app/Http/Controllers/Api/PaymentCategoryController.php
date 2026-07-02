<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;

class PaymentCategoryController extends Controller
{
    public function index()
    {
        $categories = PaymentCategory::withCount('payments')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = PaymentCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori pembayaran berhasil dibuat',
            'data' => $category,
        ], 201);
    }

    public function show(PaymentCategory $paymentCategory)
    {
        return response()->json([
            'success' => true,
            'data' => $paymentCategory->load('payments'),
        ]);
    }

    public function update(Request $request, PaymentCategory $paymentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $paymentCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori pembayaran berhasil diupdate',
            'data' => $paymentCategory,
        ]);
    }

    public function destroy(PaymentCategory $paymentCategory)
    {
        if ($paymentCategory->payments()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki data pembayaran',
            ], 409);
        }

        $paymentCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori pembayaran berhasil dihapus',
        ]);
    }
}
