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
 * @method static Builder|Vacacion acceptRequest(?array $request = null)
 * @method static Builder|Vacacion filter(?array $request = null)
 * @method static Builder|Vacacion newModelQuery()
 * @method static Builder|Vacacion newQuery()
 * @method static Builder|Vacacion query()
 * @method static Builder|Vacacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Vacacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Vacacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Vacacion whereCreatedAt($value)
 * @method static Builder|Vacacion whereDerechoVacaciones($value)
 * @method static Builder|Vacacion whereEmpleadoId($value)
 * @method static Builder|Vacacion whereEstado($value)
 * @method static Builder|Vacacion whereFechaFin($value)
 * @method static Builder|Vacacion whereFechaFinRango1Vacaciones($value)
 * @method static Builder|Vacacion whereFechaFinRango2Vacaciones($value)
 * @method static Builder|Vacacion whereFechaInicio($value)
 * @method static Builder|Vacacion whereFechaInicioRango1Vacaciones($value)
 * @method static Builder|Vacacion whereFechaInicioRango2Vacaciones($value)
 * @method static Builder|Vacacion whereId($value)
 * @method static Builder|Vacacion whereNumeroRangos($value)
 * @method static Builder|Vacacion wherePeriodoId($value)
 * @method static Builder|Vacacion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Vacacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'vacaciones';
    protected $fillable = [
        'empleado_id',
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
