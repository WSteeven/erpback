<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\OrientacionSexual
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrientacionSexual whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrientacionSexual extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_orientaciones_sexuales';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
