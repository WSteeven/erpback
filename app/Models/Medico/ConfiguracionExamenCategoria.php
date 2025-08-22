<?php

namespace App\Models\Medico;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\ConfiguracionExamenCategoria
 *
 * @property int $id
 * @property string $nombre
 * @property int $examen_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Examen|null $examen
 * @method static Builder|ConfiguracionExamenCategoria acceptRequest(?array $request = null)
 * @method static Builder|ConfiguracionExamenCategoria filter(?array $request = null)
 * @method static Builder|ConfiguracionExamenCategoria ignoreRequest(?array $request = null)
 * @method static Builder|ConfiguracionExamenCategoria newModelQuery()
 * @method static Builder|ConfiguracionExamenCategoria newQuery()
 * @method static Builder|ConfiguracionExamenCategoria query()
 * @method static Builder|ConfiguracionExamenCategoria setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ConfiguracionExamenCategoria setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ConfiguracionExamenCategoria setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ConfiguracionExamenCategoria whereCreatedAt($value)
 * @method static Builder|ConfiguracionExamenCategoria whereExamenId($value)
 * @method static Builder|ConfiguracionExamenCategoria whereId($value)
 * @method static Builder|ConfiguracionExamenCategoria whereNombre($value)
 * @method static Builder|ConfiguracionExamenCategoria whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConfiguracionExamenCategoria extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_configuraciones_examenes_categorias';
    protected $fillable = [
        'nombre',
        'examen_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function examen()
    {
        return $this->hasOne(Examen::class, 'id', 'examen_id');
    }
}
