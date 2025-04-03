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
 * App\Models\Medico\AntecedentePersonal
 *
 * @property int $id
 * @property int $vida_sexual_activa
 * @property int $tiene_metodo_planificacion_familiar
 * @property string|null $tipo_metodo_planificacion_familiar
 * @property int $hijos_vivos
 * @property int $hijos_muertos
 * @property int $ficha_preocupacional_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\AntecedenteGinecoObstetrico|null $antecedenteGinecoobstetrico
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\FichaPreocupacional|null $fichaPreocupacional
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal query()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereFichaPreocupacionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereHijosMuertos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereHijosVivos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereTieneMetodoPlanificacionFamiliar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereTipoMetodoPlanificacionFamiliar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedentePersonal whereVidaSexualActiva($value)
 * @mixin \Eloquent
 */
class AntecedentePersonal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_personales';
    protected $fillable = [
        'vida_sexual_activa',
        'hijos_vivos',
        'hijos_muertos',
        'tiene_metodo_planificacion_familiar',
        'tipo_metodo_planificacion_familiar',
        'ficha_preocupacional_id',
    ];
    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class, 'id', 'ficha_preocupacional_id');
    }
    public function antecedenteGinecoobstetrico()
    {
        return $this->hasOne(AntecedenteGinecoObstetrico::class, 'antecedente_personal_id', 'id');
    }
}
