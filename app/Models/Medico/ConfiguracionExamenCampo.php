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
 * App\Models\Medico\ConfiguracionExamenCampo
 *
 * @property int $id
 * @property string $campo
 * @property string $unidad_medida
 * @property float $rango_superior
 * @property float $rango_inferior
 * @property int $configuracion_examen_categoria_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ConfiguracionExamenCategoria|null $configuracionExamenCategoria
 * @property-read mixed $rango_inferior_formateado
 * @property-read mixed $rango_superior_formateado
 * @method static Builder|ConfiguracionExamenCampo acceptRequest(?array $request = null)
 * @method static Builder|ConfiguracionExamenCampo filter(?array $request = null)
 * @method static Builder|ConfiguracionExamenCampo ignoreRequest(?array $request = null)
 * @method static Builder|ConfiguracionExamenCampo newModelQuery()
 * @method static Builder|ConfiguracionExamenCampo newQuery()
 * @method static Builder|ConfiguracionExamenCampo query()
 * @method static Builder|ConfiguracionExamenCampo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ConfiguracionExamenCampo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ConfiguracionExamenCampo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ConfiguracionExamenCampo whereCampo($value)
 * @method static Builder|ConfiguracionExamenCampo whereConfiguracionExamenCategoriaId($value)
 * @method static Builder|ConfiguracionExamenCampo whereCreatedAt($value)
 * @method static Builder|ConfiguracionExamenCampo whereId($value)
 * @method static Builder|ConfiguracionExamenCampo whereRangoInferior($value)
 * @method static Builder|ConfiguracionExamenCampo whereRangoSuperior($value)
 * @method static Builder|ConfiguracionExamenCampo whereUnidadMedida($value)
 * @method static Builder|ConfiguracionExamenCampo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConfiguracionExamenCampo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_configuraciones_examenes_campos';
    protected $fillable = [
        'campo',
        'unidad_medida',
        'rango_inferior',
        'rango_superior',
        'configuracion_examen_categoria_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function configuracionExamenCategoria()
    {
        return $this->hasOne(ConfiguracionExamenCategoria::class, 'id', 'configuracion_examen_categoria_id');
    }

    public function getRangoInferiorFormateadoAttribute()
    {
        return number_format($this->attributes['rango_inferior'], 2);
    }

    public function getRangoSuperiorFormateadoAttribute()
    {
        return number_format($this->attributes['rango_superior'], 2);
    }
}
