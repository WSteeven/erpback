<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Saldo\AcreditacionSemana
 *
 * @property int $id
 * @property string $semana
 * @property bool $acreditar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Saldo\ValorAcreditar> $valorAcreditar
 * @property-read int|null $valor_acreditar_count
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana whereAcreditar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana whereSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcreditacionSemana whereUpdatedAt($value)
 * @mixin \Eloquent
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
