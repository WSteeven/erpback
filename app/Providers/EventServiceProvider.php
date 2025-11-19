<?php

namespace App\Providers;

use App\Models\DetallePedidoProducto;
use App\Models\DetalleProducto;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Inventario;
use App\Models\Proveedor;
use App\Observers\DetalleObserver;
use App\Observers\DetallePedidoProductoObserver;
use App\Observers\FondosRotativos\Saldo\AcreditacionObserver;
use App\Observers\FondosRotativos\Saldo\GastosObserver;
use App\Observers\FondosRotativos\Saldo\TransferenciaObserver;
use App\Observers\InventarioObserver;
use App\Observers\ProveedorObserver;
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
        'App\Events\TareaEvent' => [
            'App\Listeners\TareaListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        DetalleProducto::observe(DetalleObserver::class);
        Inventario::observe(InventarioObserver::class);
        DetallePedidoProducto::observe(DetallePedidoProductoObserver::class);
        Gasto::observe(GastosObserver::class);
        Acreditaciones::observe(AcreditacionObserver::class);
        Transferencias::observe(TransferenciaObserver::class);


        /**
         * Compras y Proveedores
         */
        Proveedor::observe(ProveedorObserver::class);
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
