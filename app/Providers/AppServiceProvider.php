<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\Organisation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $relativePath = config('services.firebase.credentials');

        if ($relativePath) {
            $fullPath = base_path($relativePath);

            if (File::exists($fullPath)) {
                putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $fullPath);
            }
        }

        // Share organisation data with all views
        View::composer('*', function ($view) {
            $organisation = Organisation::first();
            $view->with('organisation', $organisation);
        });
    }
}
