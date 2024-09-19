<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\CategoriaExamen
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|CategoriaExamen acceptRequest(?array $request = null)
 * @method static Builder|CategoriaExamen filter(?array $request = null)
 * @method static Builder|CategoriaExamen ignoreRequest(?array $request = null)
 * @method static Builder|CategoriaExamen newModelQuery()
 * @method static Builder|CategoriaExamen newQuery()
 * @method static Builder|CategoriaExamen query()
 * @method static Builder|CategoriaExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CategoriaExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CategoriaExamen setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CategoriaExamen whereCreatedAt($value)
 * @method static Builder|CategoriaExamen whereId($value)
 * @method static Builder|CategoriaExamen whereNombre($value)
 * @method static Builder|CategoriaExamen whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoriaExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_categorias_examenes';
    protected $fillable = [
        'nombre',
    ];
}
