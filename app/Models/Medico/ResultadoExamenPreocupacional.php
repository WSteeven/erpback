<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\ResultadoExamenPreocupacional
 *
 * @property int $id
 * @property int $tiempo
 * @property string $resultados
 * @property string $genero
 * @property int $antecedente_personal_id
 * @property int $ficha_preocupacional_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\AntecedentePersonal|null $antecedentePersonal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereAntecedentePersonalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereFichaPreocupacionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereResultados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereTiempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoExamenPreocupacional whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResultadoExamenPreocupacional extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_examenes_preocupacionales';
    protected $fillable = [
        'nombre',
        'tiempo',
        'resultados',
        'genero',
        'antecedente_personal_id',
        'ficha_preocupacional_id'
    ];
    public function antecedentePersonal(){
        return $this->hasOne(AntecedentePersonal::class,'id','antecedente_personal_id');
    }

}
