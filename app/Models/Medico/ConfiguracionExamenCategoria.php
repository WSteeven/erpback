<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\ConfiguracionExamenCategoria
 *
 * @property int $id
 * @property string $nombre
 * @property int $examen_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\Examen|null $examen
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria whereExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCategoria whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConfiguracionExamenCategoria extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_configuraciones_examenes_categorias';
    protected $fillable = [
        'nombre',
        'examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function examen()
    {
        return $this->hasOne(Examen::class, 'id', 'examen_id');
    }
}
