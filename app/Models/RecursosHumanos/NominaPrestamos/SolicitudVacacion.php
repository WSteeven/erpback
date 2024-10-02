<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\Vacacion
 *
 * @method static ignoreRequest(string[] $array)
 * @method static where(string $string, mixed $empleado_id)
 * @method static create(mixed $datos)
 * @property int $id
 * @property int $empleado_id
 * @property int $periodo_id
 * @property string $derecho_vacaciones
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string|null $fecha_inicio_rango1_vacaciones
 * @property string|null $fecha_fin_rango1_vacaciones
 * @property string|null $fecha_inicio_rango2_vacaciones
 * @property string|null $fecha_fin_rango2_vacaciones
 * @property int $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $numero_rangos
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read Autorizacion|null $estado_info
 * @property-read Autorizacion|null $estado_permiso_info
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Periodo|null $periodo_info
 * @method static Builder|SolicitudVacacion acceptRequest(?array $request = null)
 * @method static Builder|SolicitudVacacion filter(?array $request = null)
 * @method static Builder|SolicitudVacacion newModelQuery()
 * @method static Builder|SolicitudVacacion newQuery()
 * @method static Builder|SolicitudVacacion query()
 * @method static Builder|SolicitudVacacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|SolicitudVacacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|SolicitudVacacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|SolicitudVacacion whereCreatedAt($value)
 * @method static Builder|SolicitudVacacion whereDerechoVacaciones($value)
 * @method static Builder|SolicitudVacacion whereEmpleadoId($value)
 * @method static Builder|SolicitudVacacion whereEstado($value)
 * @method static Builder|SolicitudVacacion whereFechaFin($value)
 * @method static Builder|SolicitudVacacion whereFechaFinRango1Vacaciones($value)
 * @method static Builder|SolicitudVacacion whereFechaFinRango2Vacaciones($value)
 * @method static Builder|SolicitudVacacion whereFechaInicio($value)
 * @method static Builder|SolicitudVacacion whereFechaInicioRango1Vacaciones($value)
 * @method static Builder|SolicitudVacacion whereFechaInicioRango2Vacaciones($value)
 * @method static Builder|SolicitudVacacion whereId($value)
 * @method static Builder|SolicitudVacacion whereNumeroRangos($value)
 * @method static Builder|SolicitudVacacion wherePeriodoId($value)
 * @method static Builder|SolicitudVacacion whereUpdatedAt($value)
 * @property string|null $funciones
 * @property int|null $reemplazo_id
 * @property-read Empleado|null $reemplazo
 * @method static Builder|SolicitudVacacion whereFunciones($value)
 * @method static Builder|SolicitudVacacion whereReemplazoId($value)
 * @mixin Eloquent
 */
class SolicitudVacacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_nomina_solicitudes_vacaciones';//'vacaciones';
    protected $fillable = [
        'empleado_id',
        'autorizador_id',
        'periodo_id',
        'numero_rangos',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'reemplazo_id',
        'funciones',
        'estado',
    ];

    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    private static array $whiteListFilter = [
        'id',
        'empleado',
        'periodo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'estado'
    ];
    public function estado_info(){
        return $this->hasOne(Autorizacion::class,'id','estado');
    }
    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('departamento','jefe');
    }
    public function periodo_info()
    {
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    public function estado_permiso_info()
    {
        return $this->belongsTo(Autorizacion::class, 'estado', 'id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function reemplazo()
    {
        return $this->belongsTo(Empleado::class, 'reemplazo_id', 'id');
    }
}
