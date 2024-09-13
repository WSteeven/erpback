<?php

namespace App\Models\Intranet;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Intranet\Noticia
 *
 * @property int $id
 * @property string $titulo
 * @property string $descripcion
 * @property int $autor_id
 * @property int|null $categoria_id
 * @property string|null $etiquetas
 * @property string|null $imagen_noticia
 * @property string $fecha_vencimiento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $autor
 * @property-read \App\Models\Intranet\CategoriaNoticia|null $categoria
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereAutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereEtiquetas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereImagenNoticia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Noticia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Noticia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'intra_noticias';
    protected $fillable = [
        'titulo',
        'descripcion',
        'autor_id',
        'categoria_id',
        'etiquetas',
        'imagen_noticia',
        'fecha_vencimiento',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function autor(){
        return $this->belongsTo(Empleado::class);
    }

    public function categoria(){
        return $this->belongsTo(CategoriaNoticia::class);
    }
}
