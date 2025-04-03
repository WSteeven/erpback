<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Devolucion
 *
 * @property int $id
 * @property string $justificacion
 * @property int $solicitante_id
 * @property int|null $per_autoriza_id
 * @property int|null $autorizacion_id
 * @property string|null $observacion_aut
 * @property int|null $tarea_id
 * @property int|null $canton_id
 * @property int|null $sucursal_id
 * @property int|null $cliente_id
 * @property bool $pedido_automatico
 * @property bool $stock_personal
 * @property string|null $causa_anulacion
 * @property string $estado
 * @property string $estado_bodega
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $autoriza
 * @property-read \App\Models\Autorizacion|null $autorizacion
 * @property-read \App\Models\Canton|null $canton
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read \App\Models\Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Empleado|null $solicitante
 * @property-read \App\Models\Sucursal|null $sucursal
 * @property-read \App\Models\Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereEstadoBodega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereJustificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereObservacionAut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion wherePedidoAutomatico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion wherePerAutorizaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereStockPersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Devolucion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Devolucion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'devoluciones';
    public $fillable = [
        'justificacion',
        'solicitante_id',
        'tarea_id',
        'observacion_aut',
        'autorizacion_id',
        'per_autoriza_id',
        'canton_id',
        'sucursal_id',
        'stock_personal',
        'causa_anulacion',
        'estado',
        'estado_bodega',
        'pedido_automatico',
        'cliente_id',
        'incidente_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'stock_personal' => 'boolean',
        'pedido_automatico' => 'boolean',
    ];

    const CREADA = 'CREADA';
    const ANULADA = 'ANULADA';

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación muchos a muchos(inversa).
     * Una devolución tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_devolucion_producto', 'devolucion_id', 'detalle_id')
            ->withPivot('cantidad', 'devuelto', 'condicion_id', 'observacion')->withTimestamps();
    }


    /**
     * Relación uno a muchos(inversa).
     * Una devolución pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relación uno a muchos(inversa).
     * Una devolución pertenece a uno o ningun cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios devoluciones se realizan en una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias devoluciones pertenecen a una sucursal
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una o varias devoluciones pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    /**
     * Obtiene la ultima notificacion asociada a la devolucion.
     */
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
    /**
     * Obtener el listados de productos de una devolucion
     */
    public static function listadoProductos(int $id)
    {
        $detalles = Devolucion::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            $condicion = $detalle->pivot->condicion_id ? Condicion::find($detalle->pivot->condicion_id) : null;
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['serial'] = $detalle->serial;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['condiciones'] = $condicion?->nombre;
            $row['observacion'] = $detalle->pivot->observacion;
            $row['devuelto'] = $detalle->pivot->devuelto;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    public static function filtrarDevolucionesEmpleadoConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case Devolucion::CREADA:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->where('estado', Devolucion::CREADA)
                    ->simplePaginate($offset);
                return $results;
            case Devolucion::ANULADA:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->where('estado', Devolucion::ANULADA)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarDevolucionesBodegueroConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case Devolucion::CREADA:
                $results = Devolucion::where('estado', Devolucion::CREADA)->simplePaginate($offset);
                return $results;

            case Devolucion::ANULADA:
                $results = Devolucion::where('estado', '=', Devolucion::ANULADA)->simplePaginate($offset);
                return $results;
            default:
                $results = Devolucion::simplePaginate($offset);
                return $results;
        }
    }

    public static function obtenerCondicionListado($id)
    {
        $detalles = DetalleDevolucionProducto::where('devolucion_id', $id)->get();
        // $cuentaAntes = $detalles->count();
        $cuentaDespues = $detalles->unique('condicion_id')->count();

        // Log::channel('testing')->info('Log', ['listado', $cuentaDespues]);
        if ($cuentaDespues === 1) {
            //Si son de la misma condicion devuelve true y la condicion
            return [$cuentaDespues === 1, $detalles->first()->condicion_id];
        } else
            return [false, null]; //caso contrario devuelve false y null
    }
}
