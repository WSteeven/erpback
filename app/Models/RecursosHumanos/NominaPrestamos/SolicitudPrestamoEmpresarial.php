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
 * App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial
 *
 * @property int $id
 * @property int $solicitante
 * @property string $fecha
 * @property string $monto
 * @property string|null $plazo
 * @property int|null $periodo_id
 * @property string|null $valor_utilidad
 * @property string $motivo
 * @property string|null $observacion
 * @property int|null $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read Autorizacion|null $estado_info
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Periodo|null $periodo_info
 * @method static Builder|SolicitudPrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static Builder|SolicitudPrestamoEmpresarial filter(?array $request = null)
 * @method static Builder|SolicitudPrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static Builder|SolicitudPrestamoEmpresarial newModelQuery()
 * @method static Builder|SolicitudPrestamoEmpresarial newQuery()
 * @method static Builder|SolicitudPrestamoEmpresarial query()
 * @method static Builder|SolicitudPrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|SolicitudPrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|SolicitudPrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static Builder|SolicitudPrestamoEmpresarial whereCreatedAt($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereEstado($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereFecha($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereId($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereMonto($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereMotivo($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereObservacion($value)
 * @method static Builder|SolicitudPrestamoEmpresarial wherePeriodoId($value)
 * @method static Builder|SolicitudPrestamoEmpresarial wherePlazo($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereSolicitante($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereUpdatedAt($value)
 * @method static Builder|SolicitudPrestamoEmpresarial whereValorUtilidad($value)
 * @mixin Eloquent
 */
class SolicitudPrestamoEmpresarial extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'solicitud_prestamo_empresarial';
    protected $fillable = [
        'solicitante',
        'fecha',
        'monto',
        'periodo_id',
        'valor_utilidad',
        'plazo',
        'motivo',
        'observacion',
        'estado',
        'gestionada'
    ];

    protected $casts =[
        'gestionada'=>'boolean',
    ];

    public function estado_info()
    {
        return $this->hasOne(Autorizacion::class, 'id', 'estado');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function periodo_info(){
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    private static array $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'monto',
        'periodo',
        'valor_utilidad',
        'plazo',
        'motivo',
        'observacion',
        'estado'
    ];
}
