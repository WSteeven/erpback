<?php

namespace App\Models\Tareas;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Proyecto;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Tareas\TransferenciaProductoEmpleado
 *
 * @property int $id
 * @property string $justificacion
 * @property string|null $causa_anulacion
 * @property string $estado
 * @property string|null $observacion_aut
 * @property int $solicitante_id
 * @property int $empleado_origen_id
 * @property int $empleado_destino_id
 * @property int|null $proyecto_origen_id
 * @property int|null $proyecto_destino_id
 * @property int|null $etapa_origen_id
 * @property int|null $etapa_destino_id
 * @property int|null $tarea_origen_id
 * @property int|null $tarea_destino_id
 * @property int $autorizacion_id
 * @property int $autorizador_id
 * @property int|null $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Empleado|null $autorizador
 * @property-read Cliente|null $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DetalleProducto> $detallesTransferenciaProductoEmpleado
 * @property-read int|null $detalles_transferencia_producto_empleado_count
 * @property-read Empleado|null $empleadoDestino
 * @property-read \App\Models\Tareas\Etapa|null $etapaDestino
 * @property-read \App\Models\Tareas\Etapa|null $etapaOrigen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Proyecto|null $proyectoDestino
 * @property-read Proyecto|null $proyectoOrigen
 * @property-read Empleado|null $solicitante
 * @property-read Tarea|null $tareaDestino
 * @property-read Tarea|null $tareaOrigen
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereAutorizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereEmpleadoDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereEmpleadoOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereEtapaDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereEtapaOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereJustificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereObservacionAut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereProyectoDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereProyectoOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereTareaDestinoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereTareaOrigenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaProductoEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransferenciaProductoEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const PENDIENTE = 'PENDIENTE'; // 1
    const COMPLETA = 'COMPLETA'; // 2
    const ANULADA = 'ANULADA'; // 3

    public $table = 'tar_transf_produc_emplea';
    public $fillable = [
        'justificacion',
        'causa_anulacion',
        // 'estado',
        'observacion_aut',
        'solicitante_id',
        'empleado_origen_id',
        'empleado_destino_id',
        'proyecto_origen_id',
        'proyecto_destino_id',
        'etapa_origen_id',
        'etapa_destino_id',
        'tarea_origen_id',
        'tarea_destino_id',
        'autorizacion_id',
        'autorizador_id',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    public function empleadoOrigen()
    {
        return $this->belongsTo(Empleado::class, 'empleado_origen_id', 'id');
    }
    public function empleadoDestino()
    {
        return $this->belongsTo(Empleado::class, 'empleado_destino_id', 'id');
    }

    public function proyectoOrigen()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_origen_id', 'id');
    }

    public function proyectoDestino()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_destino_id', 'id');
    }

    public function etapaOrigen()
    {
        return $this->belongsTo(Etapa::class, 'etapa_origen_id', 'id');
    }

    public function etapaDestino()
    {
        return $this->belongsTo(Etapa::class, 'etapa_destino_id', 'id');
    }

    public function tareaOrigen()
    {
        return $this->belongsTo(Tarea::class, 'tarea_origen_id', 'id');
    }

    public function tareaDestino()
    {
        return $this->belongsTo(Tarea::class, 'tarea_destino_id', 'id');
    }

    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function detallesTransferenciaProductoEmpleado()
    {
        return $this->belongsToMany(DetalleProducto::class, 'tar_det_tran_prod_emp', 'transf_produc_emplea_id', 'detalle_producto_id')->withPivot('cantidad')->withTimestamps();
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /************
     * Funciones
     ************/
    public function listadoProductos() //int $id)
    {
        // $detalles = TransferenciaProductoEmpleado::find($id)->detallesTransferenciaProductoEmpleado()->get();
        $detalles = $this->detallesTransferenciaProductoEmpleado()->get();
        Log::channel('testing')->info('Log', compact('detalles'));
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            // $condicion= $detalle->pivot->condicion_id? Condicion::find($detalle->pivot->condicion_id):null;
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['serial'] = $detalle->serial;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['cliente_id'] = $this->cliente_id; ///$detalle->pivot->cliente_id;
            // $row['condiciones'] = $condicion?->nombre;
            $row['observacion'] = $detalle->pivot->observacion;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
}
