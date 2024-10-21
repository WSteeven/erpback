<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;


/**
 * App\Models\Ventas\Bono
 *
 * @property int $id
 * @property int $cant_ventas
 * @property int $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ventas\BonoMensualCumplimiento> $bonosCumplimiento
 * @property-read int|null $bonos_cumplimiento_count
 * @method static \Illuminate\Database\Eloquent\Builder|Bono acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bono newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bono query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bono setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono whereCantVentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bono whereValor($value)
 * @mixin \Eloquent
 */
class Bono extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_bonos';
    protected $fillable = ['cant_ventas', 'valor'];
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
