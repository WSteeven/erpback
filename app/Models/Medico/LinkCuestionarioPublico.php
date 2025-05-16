<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\LinkCuestionarioPublico
 *
 * @property int $id
 * @property string $link
 * @property int $activo
 * @property int|null $cantidad_miembros
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico query()
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereCantidadMiembros($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LinkCuestionarioPublico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LinkCuestionarioPublico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_links_cuestionarios_publicos';
    protected $fillable = [
        'link',
        'activo',
        'cantidad_miembros',
    ];
    private static $whiteListFilter = ['*'];
}
