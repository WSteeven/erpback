<?php

namespace App\Providers;

use App\Models\Inventario;
use App\Models\InventarioPrestamoTemporal;
use App\Models\Percha;
use App\Models\Piso;
use App\Models\PrestamoTemporal;
use App\Models\Producto;
use App\Observers\InventarioObserver;
use App\Observers\InventarioPrestamoTemporalObserver;
use App\Observers\PerchaObserver;
use App\Observers\PisoObserver;
use App\Observers\PrestamoTemporalObserver;
use App\Observers\ProductoObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
        Producto::observe(ProductoObserver::class);
        Percha::observe(PerchaObserver::class);
        // Piso::observe(PisoObserver::class);
        Inventario::observe(InventarioObserver::class);
        InventarioPrestamoTemporal::observe(InventarioPrestamoTemporalObserver::class);
        PrestamoTemporal::observe(PrestamoTemporalObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
