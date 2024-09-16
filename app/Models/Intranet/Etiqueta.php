<?php

namespace App\Models\Intranet;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Intranet\Etiqueta
 *
 * @property int $id
 * @property int $categoria_id
 * @property string $nombre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Intranet\CategoriaNoticia|null $categoria
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etiqueta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Etiqueta extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'intra_etiquetas';
    protected $fillable = [
        'categoria_id',
        'nombre',
        'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo'=>'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function categoria(){
        return $this->belongsTo(CategoriaNoticia::class);
    }

}
