<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetViewController extends Controller
{
    public function index(Request $request, ?string $category = null)
    {
        $categories = AssetCategory::active()->get();

        $query = Asset::with(['category', 'creator']);

        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($category) . '%']);
            });
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate(20);
        $selectedCategory = $category;

        return view('assets.index', compact('assets', 'categories', 'selectedCategory'));
    }
}
