<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Document;
use Illuminate\Support\Facades\URL;

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
    public function boot(): void
    {
        View::composer('partials.sidebar', function ($view) {
            $pendingCount = Document::where('status', 'pending')->count();
            $view->with('pendingCount', $pendingCount);
        });
        if (app()->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
