<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial
 *
 * @property int $id
 * @property int $num_cuota
 * @property string $fecha_vencimiento
 * @property string|null $fecha_pago
 * @property string $valor_couta
 * @property string $valor_pagado
 * @property string $valor_a_pagar
 * @property int $id_prestamo_empresarial
 * @property int $pago_couta
 * @property int $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial|null $prestamo_info
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereFechaPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereIdPrestamoEmpresarial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereNumCuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial wherePagoCouta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereValorAPagar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereValorCouta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlazoPrestamoEmpresarial whereValorPagado($value)
 * @mixin \Eloquent
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
        'valor_couta',
        'valor_pagado',
        'valor_a_pagar',
        'id_prestamo_empresarial',
        'pago_couta'
    ];

    private static array $whiteListFilter = [
        'id',
        'num_cuota',
        'fecha_pago',
        'valor_couta',
        'valor_pagado',
        'valor_a_pagar',
        'prestamo_empresarial',
        'pago_couta'
    ];
    public function prestamo_info()
    {
        return $this->hasOne(PrestamoEmpresarial::class, 'id','id_prestamo_empresarial');
    }

}
