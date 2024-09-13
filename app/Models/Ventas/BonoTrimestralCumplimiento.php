<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\BonoTrimestralCumplimiento
 *
 * @property int $id
 * @property int|null $vendedor_id
 * @property int $cant_ventas
 * @property string $trimestre
 * @property string $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereCantVentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereTrimestre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereValor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoTrimestralCumplimiento whereVendedorId($value)
 * @mixin \Eloquent
 */
class BonoTrimestralCumplimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_bonos_trimestrales_cumplimientos';
    protected $fillable = [
        'vendedor_id',
        'cant_ventas',
        'trimestre',
        'valor',
    ];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'id','vendedor_id')->with('empleado');
    }
}
