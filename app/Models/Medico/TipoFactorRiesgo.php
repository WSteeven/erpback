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
 * App\Models\Medico\TipoFactorRiesgo
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoFactorRiesgo acceptRequest(?array $request = null)
 * @method static Builder|TipoFactorRiesgo filter(?array $request = null)
 * @method static Builder|TipoFactorRiesgo ignoreRequest(?array $request = null)
 * @method static Builder|TipoFactorRiesgo newModelQuery()
 * @method static Builder|TipoFactorRiesgo newQuery()
 * @method static Builder|TipoFactorRiesgo query()
 * @method static Builder|TipoFactorRiesgo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoFactorRiesgo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoFactorRiesgo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoFactorRiesgo whereCreatedAt($value)
 * @method static Builder|TipoFactorRiesgo whereId($value)
 * @method static Builder|TipoFactorRiesgo whereNombre($value)
 * @method static Builder|TipoFactorRiesgo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    public const FISICO=1;
    public const MECANICO=2;
    public const QUIMICO=3;
    public const BIOLOGICO=4;
    public const ERGONOMICO=5;
    public const PSICOSOCIAL=6;

    protected $table = 'med_tipos_factores_riesgos';

    protected $fillable = [
        'nombre',
    ];
    private static array $whiteListFilter = ['*'];

    public function categorias()
    {
        return $this->hasMany(CategoriaFactorRiesgo::class);
    }
}
