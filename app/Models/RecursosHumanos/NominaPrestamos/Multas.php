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
 * App\Models\RecursosHumanos\NominaPrestamos\Multas
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
 * @method static Builder|Multas acceptRequest(?array $request = null)
 * @method static Builder|Multas filter(?array $request = null)
 * @method static Builder|Multas ignoreRequest(?array $request = null)
 * @method static Builder|Multas newModelQuery()
 * @method static Builder|Multas newQuery()
 * @method static Builder|Multas query()
 * @method static Builder|Multas setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Multas setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Multas setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Multas whereAbreviatura($value)
 * @method static Builder|Multas whereCreatedAt($value)
 * @method static Builder|Multas whereId($value)
 * @method static Builder|Multas whereNombre($value)
 * @method static Builder|Multas whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Multas extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'multas';
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
