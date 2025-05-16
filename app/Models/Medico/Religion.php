<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\Religion
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Religion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Religion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_religiones';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
