<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use  App\Models\Puesto;
use  App\Models\HorarioPuesto;
use  App\Observers\PuestoObserver;
use  App\Observers\HorarioPuestoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        HorarioPuesto::observe(HorarioPuestoObserver::class);
        Puesto::observe(PuestoObserver::class);
    }
}
