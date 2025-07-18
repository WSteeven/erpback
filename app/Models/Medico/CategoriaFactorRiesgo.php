<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\CategoriaFactorRiesgo
 *
 * @property int $id
 * @property string $nombre
 * @property int $tipo_factor_riesgo_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoFactorRiesgo|null $tipo
 * @method static Builder|CategoriaFactorRiesgo acceptRequest(?array $request = null)
 * @method static Builder|CategoriaFactorRiesgo filter(?array $request = null)
 * @method static Builder|CategoriaFactorRiesgo ignoreRequest(?array $request = null)
 * @method static Builder|CategoriaFactorRiesgo newModelQuery()
 * @method static Builder|CategoriaFactorRiesgo newQuery()
 * @method static Builder|CategoriaFactorRiesgo query()
 * @method static Builder|CategoriaFactorRiesgo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CategoriaFactorRiesgo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CategoriaFactorRiesgo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CategoriaFactorRiesgo whereCreatedAt($value)
 * @method static Builder|CategoriaFactorRiesgo whereId($value)
 * @method static Builder|CategoriaFactorRiesgo whereNombre($value)
 * @method static Builder|CategoriaFactorRiesgo whereTipoFactorRiesgoId($value)
 * @method static Builder|CategoriaFactorRiesgo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoriaFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_categorias_factores_riesgos';
    protected $fillable = [
        'nombre',
        'tipo_factor_riesgo_id',
    ];
    private static array $whiteListFilter = ['*'];

    public function tipo()
    {
        return $this->hasOne(TipoFactorRiesgo::class, 'id','tipo_factor_riesgo_id');
    }
    public function detalleCategFactorRiesgoFrPuestoTrabAct()
    {
        return $this->belongsTo(DetalleCategFactorRiesgoFrPuestoTrabAct::class, 'categoria_factor_riesgo_id', 'id' );
    }
}
