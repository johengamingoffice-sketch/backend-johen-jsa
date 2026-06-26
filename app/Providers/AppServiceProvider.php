<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('assets', function () {
            $manifestPath = public_path('build/manifest.json');

            if (!file_exists($manifestPath)) {
                return '<!-- Build not found. Run: npm run build -->';
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
            $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile = $manifest['resources/js/app.js']['file'] ?? null;

            $html = '';

            if ($cssFile) {
                $html .= '<link rel="stylesheet" href="' . asset('build/' . $cssFile) . '">';
            }

            if ($jsFile) {
                $html .= '<script type="module" src="' . asset('build/' . $jsFile) . '"></script>';
            }

            return $html;
        });
    }
}
