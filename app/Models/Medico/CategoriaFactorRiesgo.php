<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\CategoriaFactorRiesgo
 *
 * @property int $id
 * @property string $nombre
 * @property int $tipo_factor_riesgo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\TipoFactorRiesgo|null $tipo
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo whereTipoFactorRiesgoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaFactorRiesgo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_categorias_factores_riesgos';
    protected $fillable = [
        'nombre',
        'tipo_factor_riesgo_id',
    ];
    private static $whiteListFilter = ['*'];

    public function tipo()
    {
        return $this->hasOne(TipoFactorRiesgo::class);
    }
}
