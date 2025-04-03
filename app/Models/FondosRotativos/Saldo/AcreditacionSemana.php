<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Saldo\AcreditacionSemana
 *
 * @property int $id
 * @property string $semana
 * @property bool $acreditar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, ValorAcreditar> $valorAcreditar
 * @property-read int|null $valor_acreditar_count
 * @method static Builder|AcreditacionSemana acceptRequest(?array $request = null)
 * @method static Builder|AcreditacionSemana filter(?array $request = null)
 * @method static Builder|AcreditacionSemana ignoreRequest(?array $request = null)
 * @method static Builder|AcreditacionSemana newModelQuery()
 * @method static Builder|AcreditacionSemana newQuery()
 * @method static Builder|AcreditacionSemana query()
 * @method static Builder|AcreditacionSemana setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|AcreditacionSemana setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|AcreditacionSemana setLoadInjectedDetection($load_default_detection)
 * @method static Builder|AcreditacionSemana whereAcreditar($value)
 * @method static Builder|AcreditacionSemana whereCreatedAt($value)
 * @method static Builder|AcreditacionSemana whereId($value)
 * @method static Builder|AcreditacionSemana whereSemana($value)
 * @method static Builder|AcreditacionSemana whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AcreditacionSemana extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_acreditacion_semana';
    protected $primaryKey = 'id';
    protected $fillable = [
        'semana',
        'acreditar',
    ];
    private static $whiteListFilter = [
        'semana',
        'acreditar',
    ];
    protected $casts = [
        'acreditar' => 'boolean',
    ];

    public function  valorAcreditar(){
        return $this->hasMany(ValorAcreditar::class,'acreditacion_semana_id','id');
    }
}
