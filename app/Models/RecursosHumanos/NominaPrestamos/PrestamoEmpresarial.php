<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
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
 * App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial
 *
 * @property int $id
 * @property int $solicitante
 * @property string $fecha
 * @property string $monto
 * @property int|null $periodo_id
 * @property string|null $valor_utilidad
 * @property string $plazo
 * @property string $estado
 * @property string|null $motivo
 * @property int|null $id_solicitud_prestamo_empresarial
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read Periodo|null $periodo_info
 * @property-read Collection<int, PlazoPrestamoEmpresarial> $plazo_prestamo_empresarial_info
 * @property-read int|null $plazo_prestamo_empresarial_info_count
 * @property-read SolicitudPrestamoEmpresarial|null $solicitud_prestamo_empresarial_info
 * @method static Builder|PrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static Builder|PrestamoEmpresarial filter(?array $request = null)
 * @method static Builder|PrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static Builder|PrestamoEmpresarial newModelQuery()
 * @method static Builder|PrestamoEmpresarial newQuery()
 * @method static Builder|PrestamoEmpresarial query()
 * @method static Builder|PrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|PrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|PrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static Builder|PrestamoEmpresarial whereCreatedAt($value)
 * @method static Builder|PrestamoEmpresarial whereEstado($value)
 * @method static Builder|PrestamoEmpresarial whereFecha($value)
 * @method static Builder|PrestamoEmpresarial whereId($value)
 * @method static Builder|PrestamoEmpresarial whereIdSolicitudPrestamoEmpresarial($value)
 * @method static Builder|PrestamoEmpresarial whereMonto($value)
 * @method static Builder|PrestamoEmpresarial whereMotivo($value)
 * @method static Builder|PrestamoEmpresarial wherePeriodoId($value)
 * @method static Builder|PrestamoEmpresarial wherePlazo($value)
 * @method static Builder|PrestamoEmpresarial whereSolicitante($value)
 * @method static Builder|PrestamoEmpresarial whereUpdatedAt($value)
 * @method static Builder|PrestamoEmpresarial whereValorUtilidad($value)
 * @mixin Eloquent
 */
class PrestamoEmpresarial extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_empresarial';
    const ACTIVO ='ACTIVO';
    const INACTIVO ='INACTIVO';
    const FINALIZADO ='FINALIZADO';
    protected $fillable = [
        'solicitante',
        'fecha',
        'fecha_inicio_cobro',
        'monto',
        'periodo_id',
        'valor_utilidad',
        'plazo',
        'estado',
        'motivo',
        'id_solicitud_prestamo_empresarial'
    ];

    public function plazos()
    {
        return $this->hasMany(PlazoPrestamoEmpresarial::class, 'id_prestamo_empresarial', 'id');
    }
    public function plazo_prestamo_empresarial_info()
    {
        return $this->hasMany(PlazoPrestamoEmpresarial::class, 'id_prestamo_empresarial', 'id');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function solicitudPrestamoEmpresarial()
    {
        return $this->hasOne(SolicitudPrestamoEmpresarial::class, 'id', 'id_solicitud_prestamo_empresarial');
    }
    public function periodo_info(){
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    private static array $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'fecha_inicio_cobro',
        'monto',
        'periodo',
        'valor_utilidad',
        'forma_pago',
        'solicitud_prestamo_empresarial',
        'plazo',
        'estado'
    ];
}
