<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Support\Facades\Log;
use Src\App\WhereRelationLikeCondition\TrabajoCantonWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoClienteWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoCoordinadorWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoFechaHoraCreacionWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoProyectoWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoTareaWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoTipoTrabajoWRLC;

/**
 * App\Models\Trabajo
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
 * @property mixed|null $empleados_designados
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArchivoSubtarea> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\ClienteFinal|null $clienteFinal
 * @property-read \App\Models\Empleado|null $coordinador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Empleado> $empleados
 * @property-read int|null $empleados_count
 * @property-read \App\Models\Empleado|null $fiscalizador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grupo> $grupos
 * @property-read int|null $grupos_count
 * @property-read \App\Models\Proyecto|null $proyecto
 * @property-read \App\Models\Tarea|null $tarea
 * @property-read \App\Models\TipoTrabajo|null $tipo_trabajo
 * @property-read Trabajo|null $trabajoDependiente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereCausaIntervencionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereCodigoSubtarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereDescripcionCompleta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereEmpleadosDesignados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereEsDependiente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereEsVentana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraAgendado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraAsignacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraCancelado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraCreacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraEjecucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraFinalizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaHoraSuspendido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereFechaInicioTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereHoraFinTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereHoraInicioTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereMetrajeTendido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereModoAsignacionTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereMotivoCanceladoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereSeguimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereSubtareaDependienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereTiempoEstimado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereTipoTrabajoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trabajo whereValorAlimentacion($value)
 * @mixin \Eloquent
 */
class Trabajo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const CREADO = 'CREADO';
    const ASIGNADO = 'ASIGNADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    const SUSPENDIDO = 'SUSPENDIDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';

    // Modo de asignacion de trabajo
    const POR_GRUPO = 'POR_GRUPO';
    const POR_EMPLEADO = 'POR_EMPLEADO';

    const PARA_PROYECTO = 'PARA_PROYECTO';
    const PARA_CLIENTE_FINAL = 'PARA_CLIENTE_FINAL';

    protected $table = 'subtareas';
    protected $fillable = [
        'codigo_trabajo',
        'titulo',
        'descripcion_completa',
        'observacion',
        'estado',
        'modo_asignacion_trabajo',

        'fecha_hora_creacion',
        'fecha_hora_asignacion',
        'fecha_hora_ejecucion',
        'fecha_hora_realizado',
        'fecha_hora_finalizacion',
        'fecha_hora_suspendido',
        'causa_suspencion',
        'fecha_hora_cancelado',
        'causa_cancelacion',

        'es_dependiente',
        'es_ventana',
        'fecha_agendado',
        'hora_inicio_agendado',
        'hora_fin_agendado',

        'tipo_subtarea_id',
        'tarea_id',
        'trabajo_dependiente_id',
        'coordinador_id',
    ];

    protected $casts = [
        'es_dependiente' => 'boolean',
        'es_ventana' => 'boolean',
        'tiene_subtrabajos' => 'boolean',
    ];

    /*******************
     * Eloquent Filter
     *******************/
    private static array $whiteListFilter = [
        '*',
        'cliente.empresa.razon_social',
        'proyecto.codigo_proyecto',
        'tipo_trabajo.descripcion',
        'canton',
        'coordinador.nombres',
        'tarea.codigo_tarea',
        //'proyecto.canton.canton'
    ];

    private $aliasListFilter = [
        'cliente.empresa.razon_social' => 'cliente',
        'proyecto.codigo_proyecto' => 'proyecto',
        'tipo_trabajo.descripcion' => 'tipo_trabajo',
        'coordinador.nombres' => 'coordinador',
        'tarea.codigo_tarea' => 'tarea',
        //'proyecto.canton.canton' => 'canton',
    ];

    /*public function serializeRequestFilter($request)
    {
        $request['es_ventana'] = isset($request['es_ventana']) && $request['es_ventana']['like'] == '%true%' ? 1 : 0;
        return $request;
    }*/

    public function EloquentFilterCustomDetection(): array
    {
        return [
            TrabajoClienteWRLC::class,
            TrabajoProyectoWRLC::class,
            TrabajoTipoTrabajoWRLC::class,
            TrabajoFechaHoraCreacionWRLC::class,
            TrabajoCantonWRLC::class,
            TrabajoCoordinadorWRLC::class,
            TrabajoTareaWRLC::class,
        ];
    }

    /**************
     * RELACIONES
     **************/

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
        //return $this->hasOne(Cliente::class);
    }

    public function clienteFinal()
    {
        return $this->belongsTo(ClienteFinal::class);
    }

    // Relacion uno a muchos (inversa)
    public function fiscalizador()
    {
        return $this->belongsTo(Empleado::class, 'fiscalizador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    /*public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }*/

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

//    public function pausasTrabajo()
//    {
//        return $this->hasMany(PausaTrabajo::class);
//    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function trabajoDependiente()
    {
        // return $this->hasOne(Trabajo::class, 'id', 'trabajo_dependiente');
        return $this->hasOne(Trabajo::class, 'id', 'trabajo_dependiente_id');
    }

    public function tecnicosPrincipales($empleados)
    {
        // return EmpleadoResource::collection(Empleado::whereIn('id', $ids)->get());
        // return Empleado::whereIn('id', $ids)->get()->map(fn ($item) => [

        return $empleados->map(fn ($item) => [
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
        return $empleados->map(fn ($item) => [
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

    // Modo asignacion de trabajo empleados individuales
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class);
    }

    // Modo de asignación de trabajo por grupo
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class);
    }
}
