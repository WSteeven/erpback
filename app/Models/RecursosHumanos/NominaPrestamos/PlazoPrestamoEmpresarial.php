<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

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
 * App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial
 *
 * @property int $id
 * @property int $num_cuota
 * @property string $fecha_vencimiento
 * @property string|null $fecha_pago
 * @property string $valor_cuota
 * @property string $valor_pagado
 * @property string $valor_a_pagar
 * @property int $id_prestamo_empresarial
 * @property int $pago_cuota
 * @property int $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read PrestamoEmpresarial|null $prestamo_info
 * @method static Builder|PlazoPrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static Builder|PlazoPrestamoEmpresarial filter(?array $request = null)
 * @method static Builder|PlazoPrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static Builder|PlazoPrestamoEmpresarial newModelQuery()
 * @method static Builder|PlazoPrestamoEmpresarial newQuery()
 * @method static Builder|PlazoPrestamoEmpresarial query()
 * @method static Builder|PlazoPrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|PlazoPrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|PlazoPrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static Builder|PlazoPrestamoEmpresarial whereCreatedAt($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereEstado($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereFechaPago($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereFechaVencimiento($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereId($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereIdPrestamoEmpresarial($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereNumCuota($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereUpdatedAt($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereValorAPagar($value)
 * @method static Builder|PlazoPrestamoEmpresarial whereValorPagado($value)
 * @mixin Eloquent
 */
class PlazoPrestamoEmpresarial extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'plazo_prestamo_empresarial';
    protected $fillable = [
        'num_cuota',
        'fecha_pago',
        'valor_cuota',
        'valor_pagado',
        'valor_a_pagar',
        'comentario',
        'id_prestamo_empresarial',
        'pago_cuota'
    ];

    private static array $whiteListFilter = ['*'];
    public function prestamo_info()
    {
        return $this->hasOne(PrestamoEmpresarial::class, 'id','id_prestamo_empresarial');
    }

}
