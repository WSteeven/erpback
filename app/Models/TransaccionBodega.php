<?php

namespace App\Models;

use App\Events\Bodega\PedidoCreadoEvent;
use App\Models\ActivosFijos\ActivoFijo;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;
use Src\Config\MotivosTransaccionesBodega;
use Throwable;


/**
 * App\Models\TransaccionBodega
 *
 * @property int $id
 * @property string|null $justificacion
 * @property Comprobante|null $comprobante
 * @property string|null $proveedor
 * @property string|null $fecha_limite
 * @property string|null $observacion_aut
 * @property string|null $observacion_est
 * @property int $solicitante_id
 * @property int|null $responsable_id
 * @property int|null $motivo_id
 * @property int|null $proyecto_id
 * @property int|null $etapa_id
 * @property int|null $tarea_id
 * @property int|null $devolucion_id
 * @property int|null $pedido_id
 * @property int|null $transferencia_id
 * @property int|null $sucursal_id
 * @property int|null $cliente_id
 * @property int|null $per_autoriza_id
 * @property int|null $per_atiende_id
 * @property int|null $per_retira_id
 * @property int|null $autorizacion_id
 * @property int|null $estado_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $codigo_permiso_traslado
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Empleado|null $atiende
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $autoriza
 * @property-read Autorizacion|null $autorizacion
 * @property-read Cliente|null $cliente
 * @property-read Collection<int, DetalleProductoTransaccion> $detallesTransaccion
 * @property-read int|null $detalles_transaccion_count
 * @property-read Devolucion|null $devolucion
 * @property-read EstadoTransaccion|null $estado
 * @property-read Collection<int, Inventario> $items
 * @property-read int|null $items_count
 * @property-read Notificacion|null $latestNotificacion
 * @property-read Motivo|null $motivo
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Pedido|null $pedido
 * @property-read Empleado|null $responsable
 * @property-read Empleado|null $retira
 * @property-read Empleado|null $solicitante
 * @property-read Sucursal|null $sucursal
 * @property-read Tarea|null $tarea
 * @property-read TipoTransaccion|null $tipo
 * @property-read Transferencia|null $transferencia
 * @method static Builder|TransaccionBodega acceptRequest(?array $request = null)
 * @method static Builder|TransaccionBodega filter(?array $request = null)
 * @method static Builder|TransaccionBodega ignoreRequest(?array $request = null)
 * @method static Builder|TransaccionBodega newModelQuery()
 * @method static Builder|TransaccionBodega newQuery()
 * @method static Builder|TransaccionBodega query()
 * @method static Builder|TransaccionBodega setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TransaccionBodega setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TransaccionBodega setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TransaccionBodega whereAutorizacionId($value)
 * @method static Builder|TransaccionBodega whereClienteId($value)
 * @method static Builder|TransaccionBodega whereCodigoPermisoTraslado($value)
 * @method static Builder|TransaccionBodega whereComprobante($value)
 * @method static Builder|TransaccionBodega whereCreatedAt($value)
 * @method static Builder|TransaccionBodega whereDevolucionId($value)
 * @method static Builder|TransaccionBodega whereEstadoId($value)
 * @method static Builder|TransaccionBodega whereEtapaId($value)
 * @method static Builder|TransaccionBodega whereFechaLimite($value)
 * @method static Builder|TransaccionBodega whereId($value)
 * @method static Builder|TransaccionBodega whereJustificacion($value)
 * @method static Builder|TransaccionBodega whereMotivoId($value)
 * @method static Builder|TransaccionBodega whereObservacionAut($value)
 * @method static Builder|TransaccionBodega whereObservacionEst($value)
 * @method static Builder|TransaccionBodega wherePedidoId($value)
 * @method static Builder|TransaccionBodega wherePerAtiendeId($value)
 * @method static Builder|TransaccionBodega wherePerAutorizaId($value)
 * @method static Builder|TransaccionBodega wherePerRetiraId($value)
 * @method static Builder|TransaccionBodega whereProveedor($value)
 * @method static Builder|TransaccionBodega whereProyectoId($value)
 * @method static Builder|TransaccionBodega whereResponsableId($value)
 * @method static Builder|TransaccionBodega whereSolicitanteId($value)
 * @method static Builder|TransaccionBodega whereSucursalId($value)
 * @method static Builder|TransaccionBodega whereTareaId($value)
 * @method static Builder|TransaccionBodega whereTransferenciaId($value)
 * @method static Builder|TransaccionBodega whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TransaccionBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;
    use Searchable;

    const PENDIENTE = 'PENDIENTE';
    const ACEPTADA = 'ACEPTADA';
    const PARCIAL = 'PARCIAL';
    const RECHAZADA = 'RECHAZADA';

    /***********************
     * Constantes archivos
     ***********************/
    const JUSTIFICATIVO_USO = 'JUSTIFICATIVO USO';
    const ACTA_ENTREGA_RECEPCION = 'ACTA ENTREGA RECEPCION';

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
        'proyecto_id',
        'etapa_id',
        'tarea_id',
        'tipo_id',
        'sucursal_id',
        'cliente_id',
        'per_autoriza_id',
        'per_atiende_id',
        'per_retira_id',
        'autorizacion_id',
        'proveedor',
        'estado_id',
        'codigo_permiso_traslado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'justificacion' => $this->justificacion,
            'devolucion_id' => $this->devolucion_id,
            'pedido_id' => $this->pedido_id,
            'transferencia_id' => $this->transferencia_id,
            'comprobante' => $this->comprobante,
        ];
    }

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


    //Una transaccion tiene varios productos solicitados
    public function items()
    {
        return $this->belongsToMany(Inventario::class, 'detalle_producto_transaccion', 'transaccion_id', 'inventario_id')
            ->withPivot(['cantidad_inicial', 'recibido'])
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
     * Relación polimorfica a una notificación.
     * Una transaccion puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
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
     * @param int $id The id of the transaction
     *
     * @return array <code>{</code>
     *     "id": 1,
     *     "producto": "CAMARA",
     *     "detalle_id": 1,
     *     "descripcion": "CAMARA",
     *     "categoria": "CAMARA",
     *     "condiciones": "BUENO",
     *     "cantidad": 1,
     */
    public static function listadoProductos(int $id)
    {
        $items = TransaccionBodega::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            $detalle_producto_transaccion = DetalleProductoTransaccion::withSum('devoluciones', 'cantidad')
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
            $row['recibido'] = $item->pivot->recibido;
            $row['pendiente'] = $item->pivot->cantidad_inicial - $item->pivot->recibido;
            $row['despachado'] = $item->pivot->cantidad_final;
            $row['devuelto'] = $detalle_producto_transaccion->devoluciones_sum_cantidad;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    public static function listadoProductosArmamento(int $id)
    {
        $armamentos = self::listadoProductos($id);
        return collect($armamentos)->filter(fn($producto) => $producto['categoria'] === 'ARMAS DE FUEGO');
    }




    // Registro de materiales despachados en materiales_empleado_tarea

    /**
     * @throws Throwable
     */
    public static function asignarMateriales(TransaccionBodega $transaccion)
    {
        try {
            DB::beginTransaction();
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get(); //detalle_producto_transaccion
            foreach ($detalles as $detalle) {
                $detalle_transaccion = DetalleProductoTransaccion::find($detalle['id']);
                if ($detalle) {
                    $valor = $detalle_transaccion->cantidad_inicial - $detalle_transaccion->recibido;
                    $detalle_transaccion->recibido += $valor;
                    $detalle_transaccion->save();
                    $item_inventario = Inventario::find($detalle['inventario_id']);

                    // Si es material para tarea
                    if ($transaccion->tarea_id) { // Si el pedido se realizó para una tarea, hagase lo siguiente.
                        MaterialEmpleadoTarea::cargarMaterialEmpleadoTarea($item_inventario->detalle_id, $transaccion->responsable_id, $transaccion->tarea_id, $valor, $transaccion->cliente_id, $transaccion->proyecto_id, $transaccion->etapa_id);
                    } else {
                        // Stock personal
                        MaterialEmpleado::cargarMaterialEmpleado($item_inventario->detalle_id, $transaccion->responsable_id, $valor, $transaccion->cliente_id);
                        ActivoFijo::cargarComoActivo($item_inventario->detalle, $transaccion->cliente_id);
                        ActivoFijo::notificarEntregaActivos($item_inventario->detalle, $transaccion);
                    }
                } else throw new Exception('No se encontró el detalleProductoTransaccion ' . $detalle);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage() . ' ' . $e->getLine());
        }
    }

    public static function restarDespachoPedido($pedido_id, $detalle_id, $cantidad)
    {
        $detalle_pedido = DetallePedidoProducto::where('pedido_id', $pedido_id)->where('detalle_id', $detalle_id)->first();
        $detalle_pedido->despachado -= $cantidad;
        $detalle_pedido->save();
    }

    /**
     * Funcion para actualizar el pedido y su listado en cada egreso.
     * @throws Throwable
     */
    public static function actualizarPedido($transaccion)
    {
        Log::channel('testing')->info('Log', ['Estamos en el metodo de actualizar pedido, la transaccion de egreso es: ', $transaccion]);
        $url_pedido = '/pedidos';
        $estado_completa = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $estado_parcial = EstadoTransaccion::where('nombre', EstadoTransaccion::PARCIAL)->first();
        try {
            $pedido = Pedido::find($transaccion->pedido_id);
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get(); //detalle_producto_transaccion
            // Log::channel('testing')->info('Log', ['Detalles despachados en el egreso son: ', $detalles]);
            foreach ($detalles as $detalle) { //filtra los detalles que se despacharon en el egreso
                $item_inventario = Inventario::find($detalle['inventario_id']);
                Log::channel('testing')->info('Log', ['El item del inventario despacchado es: ', $item_inventario]);
                $detalle_pedido = DetallePedidoProducto::where('pedido_id', $pedido->id)->where('detalle_id', $item_inventario->detalle_id)->first();
                Log::channel('testing')->info('Log', ['El detallePedido encontrado es: ', $detalle_pedido]);
                if ($detalle_pedido) {
                    $detalle_pedido->despachado = $detalle_pedido->despachado + $detalle['cantidad_inicial']; //actualiza la cantidad de despachado del detalle_pedido_producto
                    $detalle_pedido->save(); // Despues de guardar se llama al observer DetallePedidoProductoObserver
                } else {
                    Log::channel('testing')->info('Log', ['Entro al else, supongo que no hay detalle: ', $detalle_pedido]);

                    // Log::channel('testing')->info('Log', ['DetalleProducto: ', $d]);
                    // $ids_detalles = DetalleProducto::where('producto_id', $d->producto_id)->get('id'); //ids relacionados que pertenecen al mismo producto_id
                    // Log::channel('testing')->info('Log', ['Todos los DetalleProductos hermanos del detalle despachado: ', DetalleProducto::where('producto_id', $d->producto_id)->get()]);

                    $detalle_pedido = DetallePedidoProducto::create([
                        'detalle_id' => $item_inventario->detalle_id,
                        'pedido_id' => $pedido->id,
                        'cantidad' => $detalle['cantidad_inicial'],
                        'despachado' => $detalle['cantidad_inicial'],
                        'solicitante_id' => auth()->user()->empleado->id
                    ]);

                    // $detallePedido = DetallePedidoProducto::where('pedido_id', $pedido->id)->whereIn('detalle_id', $ids_detalles)->first();
                    Log::channel('testing')->info('Log', ['El detallePedido que se a creado es', $detalle_pedido]);
                    // $detallePedido->despachado = $detallePedido->despachado + $detalle['cantidad_inicial'];
                    // $detallePedido->save();
                }
            }

            //aqui se lanza la notificacion dependiendo si el pedido está completo o parcial //ojo con esto porque no se está ejecutando en el flujo correcto, primero se ejecuta esto y luego el observer; y debe ser al contrario.
            if ($pedido->estado_id === $estado_completa->id) {
                $msg = 'El pedido que realizaste ha sido atendido en bodega y está completado';
                event(new PedidoCreadoEvent($msg, $url_pedido, $pedido, $transaccion->per_atiende_id, $pedido->solicitante_id, true));
            }
            if ($pedido->estado_id === $estado_parcial->id) {
                $msg = 'El pedido que realizaste ha sido atendido en bodega de manera parcial.';
                event(new PedidoCreadoEvent($msg, $url_pedido, $pedido, $transaccion->per_atiende_id, $pedido->solicitante_id, true));
            }
            Log::channel('testing')->info('Log', ['Estado del pedido es: ', $pedido->estado_id]);
        } catch (Throwable|Exception $e) {
            Log::channel('testing')->info('Log', ['[exception]:', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /**
     * This function verifies if a given reason for a material transaction matches the specified type
     * and ID.
     *
     * @param int $id
     * @param int $tipo
     * @param string|MotivosTransaccionesBodega $motivo
     * @return bool boolean value indicating whether the id parameter matches the id of the Motivo object
     * that has the given nombre and tipo_transaccion_id parameters.
     */
    public static function verificarEgresoLiquidacionMateriales(int $id, int $tipo, string|MotivosTransaccionesBodega $motivo)
    {
        $motivo_seleccionado = Motivo::where('nombre', $motivo)->where('tipo_transaccion_id', $tipo)->first();
        return $motivo_seleccionado->id === $id;
    }

    /**
     * La función `verificarTransferenciaEnEgreso` verifica si existe una transacción de transferencia
     * en una transacción de egreso.
     *
     * @param int $id El ID de la transacción actual que se está verificando.
     * @param int $transferencia_id El parámetro `transferencia_id` es el ID de una transferencia.
     *
     * @return boolean Si la variable transacción no es nula devolverá verdadero. De lo
     * contrario, devolverá falso.
     */
    public static function verificarTransferenciaEnEgreso(int $id, int $transferencia_id)
    {
        $tipo_transaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        $ids_motivos = Motivo::where('tipo_transaccion_id', $tipo_transaccion->id)->get('id');
        $transaccion = TransaccionBodega::whereIn('motivo_id', $ids_motivos)->where('id', '<>', $id)->where('transferencia_id', $transferencia_id)->first();
        if ($transaccion) return true;
        else return false;
    }

    /**
     * La función "verificarMotivosEgreso" verifica si un determinado ID está presente en un conjunto
     * de motivos de salida que no generan recibo.
     *
     * @param int $id El parámetro "id" es el ID del motivo de egreso que necesita ser verificado.
     *
     * @return bool un valor booleano que indica si el ID proporcionado está presente en el conjunto de
     * motivos de alta que no generan un comprobante.
     */
    public static function verificarMotivosEgreso(int $id)
    {
        $motivos_egreso_no_generan_comprobante = [
            ['id' => 15, 'nombre' => 'VENTA'],
            ['id' => 18, 'nombre' => 'DESTRUCCION'],
            ['id' => 23, 'nombre' => 'EGRESO TRANSFERENCIA ENTRE BODEGAS'],
            ['id' => 24, 'nombre' => 'EGRESO POR LIQUIDACION DE MATERIALES'],
            ['id' => 25, 'nombre' => 'AJUSTE DE EGRESO POR REGULARIZACION'],
            ['id' => 28, 'nombre' => 'ROBO'],
        ];
        $ids_motivos = array_column($motivos_egreso_no_generan_comprobante, 'id');

        return in_array($id, $ids_motivos);
    }


    /**
     * If the product has a serial number and is active, then set it to inactive
     *
     * @param int $id
     */
    public static function desactivarDetalle(int $id)
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
     * @param DetalleProducto $detalle
     */
    public static function activarDetalle(DetalleProducto $detalle)
    {
        // $detalle = DetalleProducto::find($id);
        if (!$detalle->activo) {
            $detalle->activo = true;
            $detalle->save();
        }
    }

    public static function obtenerDatosReporteIngresos($data)
    {
        $results = [];
        $cont = 0;
        foreach ($data as $d) {
            $items = DetalleProductoTransaccion::where('transaccion_id', $d->id)->get();
            foreach ($items as $item) {
                $row['inventario_id'] = $item->inventario_id;
                $row['descripcion'] = $item->inventario->detalle->descripcion;
                $row['serial'] = $item->inventario->detalle->serial;
                $row['fecha'] = $item->created_at;
                $row['estado'] = $item->inventario->condicion->nombre;
                $row['propietario'] = $item->inventario->cliente->empresa->razon_social;
                $row['bodega'] = $item->inventario->sucursal->lugar;
                $row['solicitante'] = $item->transaccion->solicitante->nombres . ' ' . $item->transaccion->solicitante->apellidos;
                $row['per_atiende'] = $item->transaccion->atiende->nombres . ' ' . $item->transaccion->atiende->apellidos;
                $row['transaccion_id'] = $item->transaccion_id;
                $row['justificacion'] = $item->transaccion->justificacion;
                $row['cantidad'] = $item->cantidad_inicial;
                $results[$cont] = $row;
                $cont++;
            }
        }
        // Log::channel('testing')->info('Log', ['Registros ingresos', $results]);
        return $results;
    }

    public static function obtenerDatosReporteResponsable($data, $categorias)
    {
        $results = [];
        $cont = 0;
        foreach ($data as $d) {
            $items = DetalleProductoTransaccion::where('transaccion_id', $d->id)->get();
            foreach ($items as $item) {
                if(in_array($item->inventario->detalle->producto->categoria_id, $categorias)) {
                    //datos para los encabezados
                    $row['fecha_despacho'] = $d->created_at;
                    $row['justificacion'] = $d->justificacion;
                    $row['autorizador'] = Empleado::extraerNombresApellidos($d->autoriza);
                    $row['solicitante'] = Empleado::extraerNombresApellidos($d->solicitante);
                    $row['cliente'] = $d->cliente->empresa->razon_social;
                    $row['responsable'] = Empleado::extraerNombresApellidos($d->responsable);
                    $row['sucursal'] = $d->sucursal->lugar;
                    $row['motivo'] = $d->motivo->nombre;

                    //datos para la tabla
                    $row['producto'] = $item->inventario->detalle->producto->nombre;
                    $row['descripcion'] = $item->inventario->detalle->descripcion;
//                $row['serial'] = $item->inventario->detalle->serial; //normalmente uniformes y epps no tienen serial
//                Log::channel('testing')->info('Log', ['variable', $d->comprobante()->first()->updated_at]);
                    $row['fecha'] = $d->comprobante()->first()->updated_at;
                    $row['categoria'] = $item->inventario->detalle->producto->categoria->nombre;
                    $row['condicion'] = $item->inventario->condicion->nombre;
                    $row['despachado'] = $item->recibido ==0? $item->cantidad_inicial : $item->recibido;
                    $row['transaccion_id']= $item->transaccion_id;


                    $results[$cont] = $row;
                    $cont++;
                }
            }
        }
        return $results;
    }

    public static function obtenerDatosReporteEgresos($data)
    {
        $results = [];
        $cont = 0;
        foreach ($data as $d) {
            $items = DetalleProductoTransaccion::where('transaccion_id', $d->id)->get();
            foreach ($items as $item) {
                $row['inventario_id'] = $item->inventario_id;
                $row['descripcion'] = $item->inventario->detalle->descripcion;
                $row['serial'] = $item->inventario->detalle->serial;
                $row['fecha'] = $item->created_at;
                $row['estado'] = $item->inventario->condicion->nombre;
                $row['propietario'] = $item->inventario->cliente->empresa->razon_social;
                $row['bodega'] = $item->inventario->sucursal->lugar;
                $row['responsable'] = $item->transaccion->responsable->nombres . ' ' . $item->transaccion->responsable->apellidos;
                $row['per_atiende'] = $item->transaccion->atiende->nombres . ' ' . $item->transaccion->atiende->apellidos;
                $row['transaccion_id'] = $item->transaccion_id;
                $row['justificacion'] = $item->transaccion->justificacion;
                $row['cantidad'] = $item->cantidad_inicial;
                $results[$cont] = $row;
                $cont++;
            }
        }
        // Log::channel('testing')->info('Log', ['Registros egresos', $results]);
        return $results;
    }

    /**
     * Función para obtener todas las columnas de la tabla.
     */
    /* public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    } */
}
