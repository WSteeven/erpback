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
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\ConstanteVital
 *
 * @property int $id
 * @property string $presion_arterial
 * @property string $temperatura
 * @property int $frecuencia_cardiaca
 * @property string $saturacion_oxigeno
 * @property int $frecuencia_respiratoria
 * @property string $peso
 * @property string $talla
 * @property string $indice_masa_corporal
 * @property string $perimetro_abdominal
 * @property int $constante_vitalable_id
 * @property string $constante_vitalable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $constanteVitalable
 * @method static Builder|ConstanteVital newModelQuery()
 * @method static Builder|ConstanteVital newQuery()
 * @method static Builder|ConstanteVital query()
 * @method static Builder|ConstanteVital whereConstanteVitalableId($value)
 * @method static Builder|ConstanteVital whereConstanteVitalableType($value)
 * @method static Builder|ConstanteVital whereCreatedAt($value)
 * @method static Builder|ConstanteVital whereFrecuenciaCardiaca($value)
 * @method static Builder|ConstanteVital whereFrecuenciaRespiratoria($value)
 * @method static Builder|ConstanteVital whereId($value)
 * @method static Builder|ConstanteVital whereIndiceMasaCorporal($value)
 * @method static Builder|ConstanteVital wherePerimetroAbdominal($value)
 * @method static Builder|ConstanteVital wherePeso($value)
 * @method static Builder|ConstanteVital wherePresionArterial($value)
 * @method static Builder|ConstanteVital whereSaturacionOxigeno($value)
 * @method static Builder|ConstanteVital whereTalla($value)
 * @method static Builder|ConstanteVital whereTemperatura($value)
 * @method static Builder|ConstanteVital whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConstanteVital extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_constantes_vitales';
    protected $fillable = [
        'presion_arterial',
        'temperatura',
        'frecuencia_cardiaca',
        'saturacion_oxigeno',
        'frecuencia_respiratoria',
        'peso',
        'talla',
        'indice_masa_corporal',
        'perimetro_abdominal',
        'constante_vitalable_id',
        'constante_vitalable_type',
    ];


    public function constanteVitalable(){
        return $this->morphTo();
    }

}
