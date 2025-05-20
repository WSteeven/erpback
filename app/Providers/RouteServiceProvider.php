<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api_publica.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/tareas')
                ->group(base_path('routes/api_tareas.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/vehiculos')
                ->group(base_path('routes/api_vehiculos.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/compras')
                ->group(base_path('routes/api_compras_proveedores.php'));

            Route::middleware('api')
                ->prefix('api/appenate')
                ->group(base_path('routes/api_appenate.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/tickets')
                ->group(base_path('routes/api_tickets.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/fondos-rotativos')
                ->group(base_path('routes/api_fondos_rotativos.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/recursos-humanos')
                ->group(base_path('routes/rrhh/api_recursos_humanos.php'));
            Route::middleware(['api', 'auth:sanctum'])
                ->prefix('api/trabajo-social')
                ->group(base_path('routes/rrhh/api_trabajo_social.php'));
            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/seleccion-contratacion')
                ->group(base_path('routes/rrhh/api_seleccion_contratacion_personal.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/medico')
                ->group(base_path('routes/api_medicos.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/ventas-claro')
                ->group(base_path('routes/api_ventas_claro.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/activos-fijos')
                ->group(base_path('routes/api_activos_fijos.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/intranet')
                ->group(base_path('routes/api_intranet.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/sso')
                ->group(base_path('routes/api_sso.php'));

            Route::middleware('api', 'auth:sanctum')
                ->prefix('api/seguridad')
                ->group(base_path('routes/api_seguridad.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });
    }
}
