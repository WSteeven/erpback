<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\ConfiguracionExamenCampo
 *
 * @property int $id
 * @property string $campo
 * @property string $unidad_medida
 * @property float $rango_superior
 * @property float $rango_inferior
 * @property int $configuracion_examen_categoria_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConfiguracionExamenCategoria|null $configuracionExamenCategoria
 * @property-read mixed $rango_inferior_formateado
 * @property-read mixed $rango_superior_formateado
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereCampo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereConfiguracionExamenCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereRangoInferior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereRangoSuperior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereUnidadMedida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionExamenCampo whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];

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
