<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\LicenciaEmpleado
 *
 * @property int $id
 * @property int $empleado
 * @property int $id_tipo_licencia
 * @property int $estado
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $justificacion
 * @property string $documento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read Autorizacion|null $estado_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @method static Builder|LicenciaEmpleado acceptRequest(?array $request = null)
 * @method static Builder|LicenciaEmpleado filter(?array $request = null)
 * @method static Builder|LicenciaEmpleado ignoreRequest(?array $request = null)
 * @method static Builder|LicenciaEmpleado newModelQuery()
 * @method static Builder|LicenciaEmpleado newQuery()
 * @method static Builder|LicenciaEmpleado query()
 * @method static Builder|LicenciaEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|LicenciaEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|LicenciaEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|LicenciaEmpleado whereCreatedAt($value)
 * @method static Builder|LicenciaEmpleado whereDocumento($value)
 * @method static Builder|LicenciaEmpleado whereEmpleado($value)
 * @method static Builder|LicenciaEmpleado whereEstado($value)
 * @method static Builder|LicenciaEmpleado whereFechaFin($value)
 * @method static Builder|LicenciaEmpleado whereFechaInicio($value)
 * @method static Builder|LicenciaEmpleado whereId($value)
 * @method static Builder|LicenciaEmpleado whereIdTipoLicencia($value)
 * @method static Builder|LicenciaEmpleado whereJustificacion($value)
 * @method static Builder|LicenciaEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LicenciaEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    protected $table = 'licencia_empleados';
    protected $fillable = [
        'empleado',
        'id_tipo_licencia',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'justificacion',
        'documento'
    ];

    private static array $whiteListFilter = [
        'id',
        'empleado',
        'tipo_licencia',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'justificacion',
        'documento'
    ];
    public function empleado_info(){
        return $this->hasOne(Empleado::class,'id', 'empleado');
    }
    public function estado_info(){
        return $this->hasOne(Autorizacion::class,'id', 'estado');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
