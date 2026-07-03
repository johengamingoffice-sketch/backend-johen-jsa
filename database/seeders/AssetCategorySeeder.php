<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kendaraan', 'description' => 'Kendaraan operasional perusahaan'],
            ['name' => 'SIM Card', 'description' => 'Kartu SIM dan paket data'],
            ['name' => 'Peralatan Kantor', 'description' => 'Peralatan dan perlengkapan kantor'],
            ['name' => 'Asset Ruko', 'description' => 'Aset properti ruko'],
        ];

        foreach ($categories as $cat) {
            AssetCategory::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description'], 'is_active' => true]
            );
        }
    }
}
