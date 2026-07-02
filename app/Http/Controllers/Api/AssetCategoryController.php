<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::withCount('assets')->latest()->get();

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

        $category = AssetCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori aset berhasil dibuat',
            'data' => $category,
        ], 201);
    }

    public function show(AssetCategory $assetCategory)
    {
        return response()->json([
            'success' => true,
            'data' => $assetCategory->load('assets'),
        ]);
    }

    public function update(Request $request, AssetCategory $assetCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $assetCategory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori aset berhasil diupdate',
            'data' => $assetCategory,
        ]);
    }

    public function destroy(AssetCategory $assetCategory)
    {
        if ($assetCategory->assets()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki aset',
            ], 409);
        }

        $assetCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori aset berhasil dihapus',
        ]);
    }
}
