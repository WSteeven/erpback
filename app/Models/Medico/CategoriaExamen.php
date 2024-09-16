<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\CategoriaExamen
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_categorias_examenes';
    protected $fillable = [
        'nombre',
    ];
}
