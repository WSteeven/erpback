<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Categoria
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Producto> $productos
 * @property-read int|null $productos_count
 * @method static Builder|Categoria acceptRequest(?array $request = null)
 * @method static Builder|Categoria filter(?array $request = null)
 * @method static Builder|Categoria ignoreRequest(?array $request = null)
 * @method static Builder|Categoria newModelQuery()
 * @method static Builder|Categoria newQuery()
 * @method static Builder|Categoria query()
 * @method static Builder|Categoria setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Categoria setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Categoria setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Categoria whereCreatedAt($value)
 * @method static Builder|Categoria whereId($value)
 * @method static Builder|Categoria whereNombre($value)
 * @method static Builder|Categoria whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Categoria extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable;
    use AuditableModel;
    protected $table = 'categorias';
	protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * Relacion uno a muchos.
     * Una categoría tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

}
