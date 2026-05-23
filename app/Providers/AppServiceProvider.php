<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Share Tahun Ajaran Aktif ke semua view
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $tahunAktif = \App\Models\TahunAjaran::where('status', 'aktif')->first();
            $view->with('tahunAktifGlobal', $tahunAktif);
        });

        if (app()->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
