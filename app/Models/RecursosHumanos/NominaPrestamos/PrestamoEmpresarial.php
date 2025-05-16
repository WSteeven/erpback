<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\Periodo|null $periodo_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial> $plazo_prestamo_empresarial_info
 * @property-read int|null $plazo_prestamo_empresarial_info_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial|null $solicitud_prestamo_empresarial_info
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereIdSolicitudPrestamoEmpresarial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial wherePeriodoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial wherePlazo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereSolicitante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoEmpresarial whereValorUtilidad($value)
 * @mixin \Eloquent
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
        'monto',
        'periodo_id',
        'valor_utilidad',
        'plazo',
        'estado',
        'id_solicitud_prestamo_empresarial'
    ];

    public function plazo_prestamo_empresarial_info()
    {
        return $this->hasMany(PlazoPrestamoEmpresarial::class, 'id_prestamo_empresarial', 'id');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function solicitud_prestamo_empresarial_info()
    {
        return $this->hasOne(SolicitudPrestamoEmpresarial::class, 'id', 'id_solicitud_prestamo_empresarial');
    }
    public function periodo_info(){
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    private static $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'monto',
        'periodo',
        'valor_utilidad',
        'forma_pago',
        'solicitud_prestamo_empresarial',
        'plazo',
        'estado'
    ];
}
