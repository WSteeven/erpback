<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Src\Config\EstadosTransacciones;

/**
 * App\Models\Pedido
 *
 * @property int $id
 * @property string $justificacion
 * @property string|null $fecha_limite
 * @property string|null $observacion_aut
 * @property string|null $observacion_est
 * @property int|null $solicitante_id
 * @property int|null $responsable_id
 * @property int|null $autorizacion_id
 * @property string|null $causa_anulacion
 * @property int|null $per_autoriza_id
 * @property int|null $proyecto_id
 * @property int|null $etapa_id
 * @property int|null $tarea_id
 * @property int|null $sucursal_id
 * @property int|null $estado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cliente_id
 * @property int|null $per_retira_id
 * @property string|null $evidencia1
 * @property string|null $evidencia2
 * @property string|null $observacion_bodega
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $autoriza
 * @property-read \App\Models\Autorizacion|null $autorizacion
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read \App\Models\EstadoTransaccion|null $estado
 * @property-read \App\Models\Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Empleado|null $responsable
 * @property-read \App\Models\Empleado|null $retira
 * @property-read \App\Models\Empleado|null $solicitante
 * @property-read \App\Models\Sucursal|null $sucursal
 * @property-read \App\Models\Tarea|null $tarea
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereEvidencia1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereEvidencia2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereFechaLimite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereJustificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereObservacionAut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereObservacionBodega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereObservacionEst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido wherePerAutorizaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido wherePerRetiraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pedido whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pedido extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'pedidos';
    public $fillable = [
        'justificacion',
        'fecha_limite',
        'observacion_bodega',
        'observacion_aut',
        'observacion_est',
        'solicitante_id',
        'responsable_id',
        'autorizacion_id',
        'per_autoriza_id',
        'sucursal_id',
        'estado_id',
        'evidencia1',
        'evidencia2',
        'per_retira_id',
        'cliente_id',
        'proyecto_id',
        'etapa_id',
        'tarea_id',
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
     * Relación muchos a muchos(inversa).
     * Un pedido tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_pedido_producto', 'pedido_id', 'detalle_id')
            ->withPivot('cantidad', 'despachado', 'solicitante_id')->withTimestamps();
    }


    /**
     * Relación uno a muchos(inversa).
     * Un pedido pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios pedidos pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos tienen un responsable
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son retirados por una persona
     */
    public function retira()
    {
        return $this->belongsTo(Empleado::class, 'per_retira_id', 'id');
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
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
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
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * Relación uno a muchos.
     * Un pedido esta en varias trasacciones.
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
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
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de un pedido
     */
    public static function listadoProductos(int $id)
    {
        $detalles = Pedido::find($id)->detalles()->get();
        $results = [];
        $solicitante = null;
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            if ($detalle->pivot->solicitante_id) $solicitante = Empleado::find($detalle->pivot->solicitante_id);
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['serial'] = $detalle->serial;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['despachado'] = $detalle->pivot->despachado;
            $row['solicitante'] = $solicitante?->nombres . ' ' . $solicitante?->apellidos;
            $row['solicitante_id'] = $solicitante?->id;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
    public static function estadoOC($id)
    {
        $orden = OrdenCompra::where('pedido_id', $id)->orderBy('created_at', 'desc')->first();
        if ($orden) {
            // return $orden->estado->nombre;
            return $orden->estado->nombre . '. ' . ($orden->realizada ? 'REALIZADA' : 'PENDIENTE DE REALIZAR');
        } else
            return '';
    }
}
