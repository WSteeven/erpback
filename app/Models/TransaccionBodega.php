<?php

namespace App\Models;

use App\Events\PedidoAutorizadoEvent;
use App\Events\PedidoCreadoEvent;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Log;

class TransaccionBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    const PENDIENTE = 'PENDIENTE';
    const ACEPTADA = 'ACEPTADA';
    const RECHAZADA = 'RECHAZADA';

    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'comprobante',
        'fecha_limite',
        'observacion_aut',
        'observacion_est',
        'solicitante_id',
        'responsable_id',
        'motivo_id',
        'devolucion_id',
        'pedido_id',
        'transferencia_id',
        'tarea_id',
        'tipo_id',
        'sucursal_id',
        'cliente_id',
        'per_autoriza_id',
        'per_atiende_id',
        'per_retira_id',
        'autorizacion_id',
        'estado_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = ['*'];


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a uno (inversa).
     * Una transaccion tiene un estado a la vez.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * Relación uno a uno (inversa).
     * Una transaccion tiene una autorizacion a la vez.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    /* public function autorizaciones()
    {
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizacion_transaccion', 'transaccion_id', 'autorizacion_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    } */

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    /* public function estados()
    {
        return $this->belongsToMany(EstadoTransaccion::class, 'tiempo_estado_transaccion', 'transaccion_id', 'estado_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    } */

    //Una transaccion tiene varios productos solicitados
    public function items()
    {
        return $this->belongsToMany(Inventario::class, 'detalle_producto_transaccion', 'transaccion_id', 'inventario_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
    }
    /**
     * Relación uno a muchos.
     * Una transaccion tiene varios detalle_producto_transaccion.
     */
    public function detallesTransaccion()
    {
        return $this->hasMany(DetalleProductoTransaccion::class, 'id');
    }
    /* Una o varias transacciones tienen un solo motivo*/
    public function motivo()
    {
        return $this->belongsTo(Motivo::class);
    }
    /**
     * Relación uno a muchos(inversa).
     * Una transacción de EGRESO pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación uno a muchos(inversa).
     * Una transacción pertenece a un solo tipo
     */
    public function tipo()
    {
        return $this->belongsTo(TipoTransaccion::class);
    }
    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una o varias transacciones pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function atiende()
    {
        return $this->belongsTo(Empleado::class, 'per_atiende_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function retira()
    {
        return $this->belongsTo(Empleado::class, 'per_retira_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    /**
     * Relación uno a muchos (inversa).
     * Una o varias transacciones pertenecen a un pedido.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'id');
    }
    /**
     * Relación uno a muchos (inversa).
     * Una o varias transacciones pertenecen a una devolución.
     */
    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class);
    }
    /**
     * Relación uno a muchos (inversa).
     * Una o varias transacciones pertenecen a una transferencia.
     */
    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Una transacción puede tener 0 o 1 comprobante
     */
    public function comprobante()
    {
        return $this->hasOne(Comprobante::class, 'transaccion_id');
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    public static function obtenerComprobante($transaccion_id)
    {
        return Comprobante::where('transaccion_id', $transaccion_id)->first();
    }

    /**
     * It gets the items of a transaction, then it gets the sum of the items returned and the sum of
     * the items dispatched, then it returns an array with the data.
     * </code>
     *
     * @param id The id of the transaction
     *
     * @return <code>{
     *     "id": 1,
     *     "producto": "CAMARA",
     *     "detalle_id": 1,
     *     "descripcion": "CAMARA",
     *     "categoria": "CAMARA",
     *     "condiciones": "BUENO",
     *     "cantidad": 1,
     */
    public static function listadoProductos($id)
    {
        $items = TransaccionBodega::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            $detalleProductoTransaccion = DetalleProductoTransaccion::withSum('devoluciones', 'cantidad')
                ->where('transaccion_id', $item->pivot->transaccion_id)
                ->where('inventario_id', $item->pivot->inventario_id)->first();
            $row['id'] = $item->id;
            $row['producto'] = $item->detalle->producto->nombre;
            $row['detalle_id'] = $item->detalle->id;
            $row['descripcion'] = $item->detalle->descripcion;
            $row['categoria'] = $item->detalle->producto->categoria->nombre;
            $row['serial'] = $item->detalle->serial;
            $row['condiciones'] = $item->condicion->nombre;
            $row['cantidad'] = $item->pivot->cantidad_inicial;
            $row['despachado'] = $item->pivot->cantidad_final;
            $row['devuelto'] = $detalleProductoTransaccion->devoluciones_sum_cantidad;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    /**
     * It takes an array of objects, and returns an array of objects
     *
     * @param results
     *
     * @return <code>array:1 [▼
     *   0 =&gt; array:2 [▼
     *     "id" =&gt; 1
     *     "detalles" =&gt; array:1 [▼
     *       0 =&gt; array:2 [▼
     *         "id" =&gt; 1
     *         "producto"
     */
    public static function listadoProductosTarea($results)
    {
        $listado = [];
        $id = 0;
        $row = [];
        foreach ($results as $result) {
            foreach ($result->detalles as $detalle) {
            }
        }

        return $listado;
    }

    public static function asignarMateriales(TransaccionBodega $transaccion)
    {
        try {
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get(); //detalle_producto_transaccion
            foreach ($detalles as $detalle) {
                $itemInventario = Inventario::find($detalle['inventario_id']);

                // Si es material para tarea
                if ($transaccion->tarea_id) { // Si el pedido se realizó para una tarea, hagase lo siguiente.
                    $material = MaterialEmpleadoTarea::where('detalle_producto_id', $itemInventario->detalle_id)
                        ->where('tarea_id', $transaccion->tarea_id)
                        ->where('empleado_id', $transaccion->responsable_id)
                        ->first();

                    if ($material) {
                        $material->cantidad_stock += $detalle['cantidad_inicial'];
                        $material->save();
                    } else {
                        $esFibra = !!Fibra::where('detalle_id', $itemInventario->detalle_id)->first();

                        MaterialEmpleadoTarea::create([
                            'cantidad_stock' => $detalle['cantidad_inicial'],
                            'tarea_id' => $transaccion->tarea_id,
                            'empleado_id' => $transaccion->responsable_id,
                            'detalle_producto_id' => $itemInventario->detalle_id,
                            'es_fibra' => $esFibra, // Pendiente de obtener
                        ]);
                    }
                } else {
                    // Stock personal
                    $material = MaterialEmpleado::where('detalle_producto_id', $itemInventario->detalle_id)
                        ->where('empleado_id', $transaccion->responsable_id)
                        ->first();

                    Log::channel('testing')->info('Log', compact('itemInventario'));
                    Log::channel('testing')->info('Log', compact('transaccion'));

                    if ($material) {
                        $material->cantidad_stock += $detalle['cantidad_inicial'];
                        $material->save();
                    } else {
                        $esFibra = !!Fibra::where('detalle_id', $itemInventario->detalle_id)->first();

                        MaterialEmpleado::create([
                            'cantidad_stock' => $detalle['cantidad_inicial'],
                            'empleado_id' => $transaccion->responsable_id,
                            'detalle_producto_id' => $itemInventario->detalle_id,
                            'es_fibra' => $esFibra,
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            //
        }
    }

    /**
     * Funcion para actualizar el pedido y su listado en cada egreso.
     */
    public static function actualizarPedido($transaccion)
    {
        $url_pedido = '/pedidos';
        $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $estadoParcial = EstadoTransaccion::where('nombre', EstadoTransaccion::PARCIAL)->first();
        try {
            $pedido = Pedido::find($transaccion->pedido_id);
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get(); //detalle_producto_transaccion
            foreach ($detalles as $detalle) {
                $itemInventario = Inventario::find($detalle['inventario_id']);
                // Log::channel('testing')->info('Log', ['item del inventario es:', $itemInventario]);
                $detallePedido = DetallePedidoProducto::where('pedido_id', $pedido->id)->where('detalle_id', $itemInventario->detalle_id)->first();
                // Log::channel('testing')->info('Log', ['detalle es:', $detallePedido]);
                // Log::channel('testing')->info('Log', ['detalle despachado es:', $detallePedido->despachado]);
                // Log::channel('testing')->info('Log', ['Pedido:', $pedido]);
                // Log::channel('testing')->info('Log', ['Detalle producto es:', DetalleProducto::find($detallePedido->detalle_id)]);
                $detallePedido->despachado = $detallePedido->despachado + $detalle['cantidad_inicial']; //actualiza la cantidad de despachado del detalle_pedido_producto
                $detallePedido->save(); // Despues de guardar se llama al observer DetallePedidoProductoObserver

                // aqui va
            }

            //aqui se lanza la notificacion dependiendo si el pedido está completo o parcial
            if ($pedido->estado_id === $estadoCompleta->id) {
                $msg = 'El pedido que realizaste ha sido atendido en bodega y está completado';
                event(new PedidoCreadoEvent($msg, $url_pedido, $pedido, $transaccion->per_atiende_id, $pedido->solicitante_id));
            }
            if ($pedido->estado_id === $estadoParcial->id) {
                $msg = 'El pedido que realizaste ha sido atendido en bodega de manera parcial.';
                event(new PedidoCreadoEvent($msg, $url_pedido, $pedido, $transaccion->per_atiende_id, $pedido->solicitante_id));
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['[exception]:', $e->getMessage(), $e->getLine()]);
        }
    }


    /**
     * If the product has a serial number and is active, then set it to inactive
     *
     * @param id The id of the product
     */
    public static function desactivarDetalle($id)
    {
        $detalle = DetalleProducto::find($id);
        if ($detalle->serial && $detalle->activo) {
            $detalle->activo = false;
            $detalle->save();
        }
    }
    /**
     * It finds a record in the database, and if it's not active, it sets it to active and saves it
     *
     * @param id The id of the model you want to update.
     */
    public function activarDetalle($id)
    {
        $detalle = DetalleProducto::find($id);
        if (!$detalle->activo) {
            $detalle->activo = true;
            $detalle->save();
        }
    }


    /**
     * Función para obtener todas las columnas de la tabla.
     */
    /* public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    } */
}
