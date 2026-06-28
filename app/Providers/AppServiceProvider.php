<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('create-data', fn(User $user) => $user->canCreateData());
        Gate::define('update-data', fn(User $user) => $user->canUpdateData());
        Gate::define('delete-data', fn(User $user) => $user->canDeleteData());
        Gate::define('view-all', fn(User $user) => $user->canViewAll());

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
