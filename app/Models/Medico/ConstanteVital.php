<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $constanteVitalable
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereConstanteVitalableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereConstanteVitalableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereFrecuenciaCardiaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereFrecuenciaRespiratoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereIndiceMasaCorporal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital wherePerimetroAbdominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital wherePresionArterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereSaturacionOxigeno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereTalla($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereTemperatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConstanteVital whereUpdatedAt($value)
 * @mixin \Eloquent
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
