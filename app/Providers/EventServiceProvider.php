<?php

namespace App\Providers;

use App\Models\DetallePedidoProducto;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\Inventario;
use App\Models\InventarioPrestamoTemporal;
use App\Models\MovimientoProducto;
use App\Models\Percha;
use App\Models\PrestamoTemporal;
use App\Observers\DetalleObserver;
use App\Observers\DetallePedidoProductoObserver;
use App\Observers\DetalleProductoTransaccionObserver;
use App\Observers\FondosRotativos\Saldo\AcreditacionObserver;
use App\Observers\FondosRotativos\Saldo\GastosObserver;
use App\Observers\InventarioObserver;
use App\Observers\InventarioPrestamoTemporalObserver;
use App\Observers\MovimientoProductoObserver;
use App\Observers\PerchaObserver;
use App\Observers\PrestamoTemporalObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        'App\Events\SubtareaEvent' => [
            'App\Listeners\SubtareaListener',
        ],
        'App\Events\PedidoEvent' => [
            'App\Listeners\PedidoListener',
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
        // Producto::observe(ProductoObserver::class);
        DetalleProducto::observe(DetalleObserver::class);
        Percha::observe(PerchaObserver::class);
        // Piso::observe(PisoObserver::class);
        Inventario::observe(InventarioObserver::class);
        MovimientoProducto::observe(MovimientoProductoObserver::class);
        DetalleProductoTransaccion::observe(DetalleProductoTransaccionObserver::class);
        // TransaccionBodega::observe(TransaccionBodegaObserver::class);
        DetallePedidoProducto::observe(DetallePedidoProductoObserver::class);
        Gasto::observe(GastosObserver::class);
        Acreditaciones::observe(AcreditacionObserver::class);
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
