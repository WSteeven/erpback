<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\IdentidadGenero
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero query()
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdentidadGenero whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IdentidadGenero extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_identidades_generos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
