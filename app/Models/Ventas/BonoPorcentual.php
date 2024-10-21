<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\BonoPorcentual
 *
 * @property int $id
 * @property int $porcentaje
 * @property string $comision
 * @property string $tipo_vendedor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ventas\BonoMensualCumplimiento> $bonosCumplimiento
 * @property-read int|null $bonos_cumplimiento_count
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual whereComision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual wherePorcentaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual whereTipoVendedor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoPorcentual whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BonoPorcentual extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_bonos_porcentuales';
    protected $fillable =['porcentaje','valor','tipo_vendedor'];
    private static $whiteListFilter = [
        '*',
    ];


    /**
     * Relacion polimorfica a un bono de cumplimiento.
     * Un bono puede tener uno o varios registos en un bono Mensual de cumplimiento.
     */
    public function bonosCumplimiento()
    {
        return $this->morphMany(BonoMensualCumplimiento::class, 'bonificable');
    }
}
