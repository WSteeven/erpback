<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Archivo;
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
 * App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado
 *
 * @property mixed $empleado
 * @property int $id
 * @property int $empleado_id
 * @property int $tipo_permiso_id
 * @property string $fecha_hora_inicio
 * @property string $fecha_hora_fin
 * @property string|null $fecha_hora_reagendamiento
 * @property string|null $fecha_recuperacion
 * @property string|null $hora_recuperacion
 * @property string $justificacion
 * @property string|null $observacion
 * @property int $estado_permiso_id
 * @property string $documento
 * @property bool $cargo_vacaciones
 * @property bool $recupero
 * @property bool $aceptar_sugerencia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $estadoPermiso
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read MotivoPermisoEmpleado|null $tipoPermiso
 * @method static Builder|PermisoEmpleado acceptRequest(?array $request = null)
 * @method static Builder|PermisoEmpleado filter(?array $request = null)
 * @method static Builder|PermisoEmpleado ignoreRequest(?array $request = null)
 * @method static Builder|PermisoEmpleado newModelQuery()
 * @method static Builder|PermisoEmpleado newQuery()
 * @method static Builder|PermisoEmpleado query()
 * @method static Builder|PermisoEmpleado where(string $column, $value )
 * @method static Builder|PermisoEmpleado find($value)
 * @method static Builder|PermisoEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|PermisoEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|PermisoEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|PermisoEmpleado whereAceptarSugerencia($value)
 * @method static Builder|PermisoEmpleado whereCargoVacaciones($value)
 * @method static Builder|PermisoEmpleado whereCreatedAt($value)
 * @method static Builder|PermisoEmpleado whereDocumento($value)
 * @method static Builder|PermisoEmpleado whereEmpleadoId($value)
 * @method static Builder|PermisoEmpleado whereEstadoPermisoId($value)
 * @method static Builder|PermisoEmpleado whereFechaHoraFin($value)
 * @method static Builder|PermisoEmpleado whereFechaHoraInicio($value)
 * @method static Builder|PermisoEmpleado whereFechaHoraReagendamiento($value)
 * @method static Builder|PermisoEmpleado whereFechaRecuperacion($value)
 * @method static Builder|PermisoEmpleado whereHoraRecuperacion($value)
 * @method static Builder|PermisoEmpleado whereId($value)
 * @method static Builder|PermisoEmpleado whereJustificacion($value)
 * @method static Builder|PermisoEmpleado whereObservacion($value)
 * @method static Builder|PermisoEmpleado whereRecupero($value)
 * @method static Builder|PermisoEmpleado whereTipoPermisoId($value)
 * @method static Builder|PermisoEmpleado whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PermisoEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;


    protected $table = 'permiso_empleados';
    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    protected $fillable = [
        'tipo_permiso_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_recuperacion',
        'hora_recuperacion',
        'fecha_hora_reagendamiento',
        'justificacion',
        'observacion',
        'estado_permiso_id',
        'empleado_id',
        'cargo_vacaciones',
        'aceptar_sugerencia',
        'recupero',
        'documento'
    ];

    private static array $whiteListFilter = ['*'];
    protected $casts = [
        'cargo_vacaciones' => 'boolean',
        'aceptar_sugerencia' => 'boolean',
        'recupero' => 'boolean',
    ];
    public function tipoPermiso()
    {
        return $this->belongsTo(MotivoPermisoEmpleado::class, 'tipo_permiso_id', 'id');
    }
    public function estadoPermiso()
    {
        return $this->belongsTo(Autorizacion::class, 'estado_permiso_id', 'id');
    }
    public function empleado()
    {
//        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('departamento','jefe');
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
