<?php

namespace App\Models;

use App\Http\Resources\EmpleadoResource;
use App\ModelFilters\SubtareasFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Src\App\WhereRelationLikeCondition\Subtarea\CodigoTareaWRLC;
use Src\App\WhereRelationLikeCondition\Subtarea\CantidadAdjuntosWRLC;
use Src\App\WhereRelationLikeCondition\Subtarea\FechaSolicitudWRLC;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use Src\App\WhereRelationLikeCondition\Subtarea\ProyectoWRLC;
use Src\App\WhereRelationLikeCondition\Tareas\GrupoWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoCoordinadorWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoTipoTrabajoWRLC;

/**
 * App\Models\Subtarea
 *
 * @property int $id
 * @property string $codigo_subtarea
 * @property string $titulo
 * @property string|null $descripcion_completa
 * @property string|null $observacion
 * @property string $estado
 * @property string $modo_asignacion_trabajo
 * @property string|null $fecha_hora_creacion
 * @property string|null $fecha_hora_asignacion
 * @property string|null $fecha_hora_agendado
 * @property string|null $fecha_hora_ejecucion
 * @property string|null $fecha_hora_realizado
 * @property string|null $fecha_hora_finalizacion
 * @property string|null $fecha_hora_suspendido
 * @property string|null $fecha_hora_cancelado
 * @property bool $es_dependiente
 * @property bool $es_ventana
 * @property string|null $fecha_inicio_trabajo
 * @property string|null $hora_inicio_trabajo
 * @property string|null $hora_fin_trabajo
 * @property array|null $empleados_designados
 * @property int|null $motivo_cancelado_id
 * @property int|null $subtarea_dependiente_id
 * @property int $tipo_trabajo_id
 * @property int $tarea_id
 * @property int|null $grupo_id
 * @property int|null $empleado_id
 * @property int|null $seguimiento_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tiempo_estimado
 * @property int|null $causa_intervencion_id
 * @property int|null $metraje_tendido
 * @property float $valor_alimentacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActividadRealizadaSeguimientoSubtarea> $actividadRealizadaSeguimientoSubtarea
 * @property-read int|null $actividad_realizada_seguimiento_subtarea_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArchivoSubtarea> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArchivoSeguimiento> $archivosSeguimiento
 * @property-read int|null $archivos_seguimiento_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\CausaIntervencion|null $causaIntervencion
 * @property-read \App\Models\Empleado|null $empleado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Empleado> $empleados
 * @property-read int|null $empleados_count
 * @property-read \App\Models\Grupo|null $grupo
 * @property-read \App\Models\Grupo|null $grupoResponsable
 * @property-read \App\Models\MotivoSuspendido|null $motivoCancelado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MotivoSuspendido> $motivoSuspendido
 * @property-read int|null $motivo_suspendido_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PausaSubtarea> $pausasSubtarea
 * @property-read int|null $pausas_subtarea_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SeguimientoMaterialSubtarea> $seguimientosMaterialesSubtareas
 * @property-read int|null $seguimientos_materiales_subtareas_count
 * @property-read Subtarea|null $subtarea
 * @property-read \App\Models\Tarea|null $tarea
 * @property-read \App\Models\TipoTrabajo|null $tipo_trabajo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TrabajoRealizado> $trabajosRealizados
 * @property-read int|null $trabajos_realizados_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea anterioresNoFinalizados()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea disponible()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea fechaActual()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea fechaFuturo()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea noEsStandby()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea noEstaRealizado()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea porCoordinador()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea subtareasCoordinador($coordinador)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereCausaIntervencionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereCodigoSubtarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereDescripcionCompleta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereEmpleadosDesignados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereEsDependiente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereEsVentana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraAgendado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraAsignacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraCancelado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraEjecucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraFinalizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaHoraSuspendido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereFechaInicioTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereHoraFinTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereHoraInicioTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereMetrajeTendido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereModoAsignacionTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereMotivoCanceladoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereSeguimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereSubtareaDependienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereTiempoEstimado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereTipoTrabajoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subtarea whereValorAlimentacion($value)
 * @mixin \Eloquent
 */
class Subtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait, Searchable; //, SubtareasFilter;

    const CREADO = 'CREADO';
    const ASIGNADO = 'ASIGNADO';
    const AGENDADO = 'AGENDADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    const SUSPENDIDO = 'SUSPENDIDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';

    // Modo de asignacion de trabajo
    const POR_GRUPO = 'POR_GRUPO';
    const POR_EMPLEADO = 'POR_EMPLEADO';

    protected $table = "subtareas";
    protected $fillable = [
        'codigo_subtarea',
        'titulo',
        'descripcion_completa',
        'observacion',
        'estado',
        'modo_asignacion_trabajo',
        'fecha_hora_creacion',
        'fecha_hora_asignacion',
        'fecha_hora_agendado',
        'fecha_hora_ejecucion',
        'fecha_hora_realizado',
        'fecha_hora_finalizacion',
        'fecha_hora_cancelado',
        'motivo_cancelado_id',
        'es_dependiente',
        'es_ventana',
        'fecha_inicio_trabajo',
        'hora_inicio_trabajo',
        'hora_fin_trabajo',
        'tiempo_estimado',
        'empleados_designados',
        'metraje_tendido',
        'valor_alimentacion',
        'tipo_trabajo_id',
        'tarea_id',
        'grupo_id',
        'empleado_id',
        'subtarea_dependiente_id',
        'coordinador_id',
        'seguimiento_id',
        'causa_intervencion_id',
    ];

    protected $casts = [
        'es_dependiente' => 'boolean',
        'es_ventana' => 'boolean',
        'tiene_subtrabajos' => 'boolean',
        'empleados_designados' => 'json',
    ];

    static $noFiltrar = ['codigo_tarea'];

    /*******************
     * Eloquent Filter
     *******************/
    private static $whiteListFilter = [
        '*',
        /* 'cliente.empresa.razon_social',
        'tipo_trabajo.descripcion', */
        // 'canton',
        'tarea.coordinador.nombres',
        // 'proyecto.codigo_proyecto',
        'cantidad_adjuntos',
        'tarea.fecha_solicitud',
        // 'grupo',
        //'tarea.codigo_tarea',
        //'proyecto.canton.canton'
    ];

    private $aliasListFilter = [
        /* 'cliente.empresa.razon_social' => 'cliente',
        */
        'tipo_trabajo.descripcion' => 'tipo_trabajo',
        'tarea.coordinador.nombres' => 'coordinador',
        //'tarea.codigo_tarea' => 'tarea',
        //'proyecto.canton.canton' => 'canton',
        // 'proyecto.codigo_proyecto' => 'proyecto',
        'tarea.fecha_solicitud' => 'fecha_solicitud',
        'grupo.nombre' => 'grupo',
        'grupo.region' => 'region',
    ];

    public function EloquentFilterCustomDetection(): array
    {
        return [
            /* TrabajoClienteWRLC::class,
            TrabajoFechaHoraCreacionWRLC::class,
            TrabajoCantonWRLC::class, */
            TrabajoTipoTrabajoWRLC::class,
            TrabajoCoordinadorWRLC::class,
            // ProyectoWRLC::class,
            CantidadAdjuntosWRLC::class,
            FechaSolicitudWRLC::class,
            GrupoWRLC::class,
            //CodigoTareaWRLC::class,
        ];
    }

    /*************************
     * Laravel Scout Search
     *************************/
    public function toSearchableArray()
    {
        $coordinador = $this->tarea?->coordinador;

        return [
            'codigo_subtarea' => $this->codigo_subtarea,
            'titulo' => $this->titulo,
            'grupo' => $this->grupoResponsable?->nombre,
            'coordinador' => $coordinador ? $coordinador->nombres . ' ' . $coordinador->apellidos : null,
        ];
    }

    /* public function setFechaInicioTrabajoAttribute($value)
    {
        $this->attributes['fecha_inicio_trabajo'] = (new Carbon($value))->format('Y-m-d');
    }

    public function getFechaInicioTrabajoAttribute($value)
    {
        $this->attributes['fecha_inicio_trabajo'] = Carbon::parse($value)->format('Y-m-d');
    } */
    public function attachMotivoSuspendido($id)
    {
        $this->motivoSuspendido()->attach($id);
        $this->touch();
    }

    // Relacion uno a muchos (inversa)
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    // Relacion uno a muchos (inversa)
    public function grupoResponsable(): BelongsTo
    {
        // Log::channel('testing')->info('Log', ['Coordinador: ', 'Dentro de la relacion ...']);
        return $this->belongsTo(Grupo::class, 'grupo_id', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    /* public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
        return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
    } */

    // Relacion uno a muchos (inversa)
    public function tipo_trabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    /**
     * Relación uno a muchos .
     * Una subtarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    // Relacion uno a muchos
    public function archivos()
    {
        return $this->hasMany(ArchivoSubtarea::class);
    }

    public function archivosSeguimiento()
    {
        return $this->hasMany(ArchivoSeguimiento::class, 'subtarea_id', 'id');
    }

    public function pausasSubtarea()
    {
        return $this->hasMany(PausaSubtarea::class);
    }

    public function motivoSuspendido()
    {
        return $this->belongsToMany(MotivoSuspendido::class)->withPivot('empleado_id')->withTimestamps();
    }

    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'subtarea_dependiente');
    }

    /* public function motivoSuspendido()
    {
        return $this->belongsTo(MotivoSuspendido::class);
    } */

    public function motivoCancelado()
    {
        return $this->belongsTo(MotivoSuspendido::class, 'motivo_cancelado_id', 'id');
    }

    public function causaIntervencion()
    {
        return $this->belongsTo(CausaIntervencion::class, 'causa_intervencion_id', 'id');
    }

    public function tecnicosPrincipales($empleados)
    {
        // return EmpleadoResource::collection(Empleado::whereIn('id', $ids)->get());
        // return Empleado::whereIn('id', $ids)->get()->map(fn ($item) => [

        return $empleados->map(fn($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function otrosTecnicos($empleados)
    {
        //$empleados->filter(fn($item) => $item->);
        return $empleados->map(fn($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class)->withPivot('es_responsable');
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function seguimientosMaterialesSubtareas()
    {
        return $this->hasMany(SeguimientoMaterialSubtarea::class);
    }

    public function trabajosRealizados()
    {
        return $this->hasMany(TrabajoRealizado::class);
    }

    // es lo mismo de arriba
    public function actividadRealizadaSeguimientoSubtarea()
    {
        return $this->hasMany(ActividadRealizadaSeguimientoSubtarea::class);
    }

    /*********
     * Scopes
     *********/
    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }
    public function scopeFechaActual($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '=', Carbon::today());
    }

    public function scopeFechaFuturo($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '>', Carbon::today());
    }

    public function scopeAnterioresNoFinalizados($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '<=', Carbon::today())->whereIn('estado', [Subtarea::AGENDADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO]);
    }

    public function scopeNoEstaRealizado($query)
    {
        return $query->where('estado', '!=', Subtarea::REALIZADO);
    }

    public function scopeNoEsStandby($query)
    {
        return $query->whereNotIn('tipo_trabajo_id', TipoTrabajo::select('id')->where('descripcion', 'STANDBY'));
    }

    public function scopeSubtareasCoordinador($query, $coordinador) //HasManyThrough
    {
        // return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
        // Log::channel('testing')->info('Log', ['Coordinador: ', $coordinador]);
        return DB::table('subtareas')->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->where('tareas.coordinador_id', $coordinador);
    }

    public function scopeDisponible($query)
    {
        return $query->whereNotIn('estado', [Subtarea::FINALIZADO, Subtarea::CANCELADO, Subtarea::SUSPENDIDO]);
    }
}
