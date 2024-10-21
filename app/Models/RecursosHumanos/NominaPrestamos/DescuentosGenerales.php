<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $abreviatura
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago> $egreso_rol_pago
 * @property-read int|null $egreso_rol_pago_count
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales query()
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales whereAbreviatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DescuentosGenerales whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DescuentosGenerales extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'descuentos_generales';
    protected $fillable = [
        'nombre',
        'abreviatura'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'abreviatura'
    ];
    public function egreso_rol_pago()
    {
        return $this->morphMany(EgresoRolPago::class, 'descuento');
    }

}
