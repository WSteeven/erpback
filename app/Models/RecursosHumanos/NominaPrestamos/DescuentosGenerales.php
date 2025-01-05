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
 * App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $abreviatura
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, EgresoRolPago> $egreso_rol_pago
 * @property-read int|null $egreso_rol_pago_count
 * @method static Builder|DescuentosGenerales acceptRequest(?array $request = null)
 * @method static Builder|DescuentosGenerales filter(?array $request = null)
 * @method static Builder|DescuentosGenerales ignoreRequest(?array $request = null)
 * @method static Builder|DescuentosGenerales newModelQuery()
 * @method static Builder|DescuentosGenerales newQuery()
 * @method static Builder|DescuentosGenerales query()
 * @method static Builder|DescuentosGenerales setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DescuentosGenerales setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DescuentosGenerales setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DescuentosGenerales whereAbreviatura($value)
 * @method static Builder|DescuentosGenerales whereCreatedAt($value)
 * @method static Builder|DescuentosGenerales whereId($value)
 * @method static Builder|DescuentosGenerales whereNombre($value)
 * @method static Builder|DescuentosGenerales whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
        'id',
        'nombre',
        'abreviatura'
    ];
    public function egreso_rol_pago()
    {
        return $this->morphMany(EgresoRolPago::class, 'descuento');
    }

}
