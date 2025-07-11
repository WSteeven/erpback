<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AntecedenteGinecoObstetrico|null $antecedenteGinecoobstetrico
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read FichaPreocupacional|null $fichaPreocupacional
 * @method static Builder|AntecedentePersonal newModelQuery()
 * @method static Builder|AntecedentePersonal newQuery()
 * @method static Builder|AntecedentePersonal query()
 * @method static Builder|AntecedentePersonal whereCreatedAt($value)
 * @method static Builder|AntecedentePersonal whereFichaPreocupacionalId($value)
 * @method static Builder|AntecedentePersonal whereHijosMuertos($value)
 * @method static Builder|AntecedentePersonal whereHijosVivos($value)
 * @method static Builder|AntecedentePersonal whereId($value)
 * @method static Builder|AntecedentePersonal whereTieneMetodoPlanificacionFamiliar($value)
 * @method static Builder|AntecedentePersonal whereTipoMetodoPlanificacionFamiliar($value)
 * @method static Builder|AntecedentePersonal whereUpdatedAt($value)
 * @method static Builder|AntecedentePersonal whereVidaSexualActiva($value)
 * @mixin Eloquent
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
