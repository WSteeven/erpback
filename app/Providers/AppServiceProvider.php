<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Src\App\Medico\SolicitudExamenService;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SolicitudExamenService::class, function ($app) {
            return new SolicitudExamenService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Cargar helper de verificacion del guard
        require_once  base_path('app/Helpers/helpers.php');

        Schema::defaultStringLength(191);
        Blade::withoutDoubleEncoding();
        Http::macro('defaultTimeout', function () {
            return Http::timeout(100040); // 120 segundos por defecto para todas las solicitudes
        });
    }
}
