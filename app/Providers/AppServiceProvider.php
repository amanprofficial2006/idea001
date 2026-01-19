<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
    }
}
