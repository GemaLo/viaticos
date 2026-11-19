<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Comision;
use App\Observers\ComisionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Comision::observe(ComisionObserver::class);

        if (app()->environment('production') && !app()->runningInConsole()) {
            if (!str_contains(config('app.url'), 'localhost')) {
                \URL::forceScheme('https');
            }
        }
    }

    public function register()
    {
        if (app()->environment('production')) {
            config(['app.asset_url' => null]);
        }
    }
}
