<?php

namespace App\Models\Intranet;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Intranet\CategoriaNoticia
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaNoticia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaNoticia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'intra_categorias_noticias';
    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo'=>'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

}
