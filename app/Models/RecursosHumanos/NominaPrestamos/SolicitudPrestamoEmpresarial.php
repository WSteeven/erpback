<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read Autorizacion|null $estado_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\Periodo|null $periodo_info
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial wherePeriodoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial wherePlazo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereSolicitante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudPrestamoEmpresarial whereValorUtilidad($value)
 * @mixin \Eloquent
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
        'estado'
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

    private static $whiteListFilter = [
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
