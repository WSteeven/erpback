<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoFactorRiesgo
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFactorRiesgo whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = ['*'];
}
