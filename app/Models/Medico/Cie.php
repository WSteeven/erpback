<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\Cie
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre_enfermedad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cie acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cie setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie whereNombreEnfermedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cie whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cie extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_cies';
    protected $fillable = [
        'codigo',
        'nombre_enfermedad',
    ];

    private static $whiteListFilter = ['*'];
}
