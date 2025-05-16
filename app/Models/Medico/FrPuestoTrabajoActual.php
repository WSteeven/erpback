<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\Medico\FrPuestoTrabajoActual
 *
 * @property int $id
 * @property string $puesto_trabajo
 * @property string $actividad
 * @property int|null $tiempo_trabajo
 * @property string $medidas_preventivas
 * @property int $factor_riesgo_puesto_trabajable_id
 * @property string $factor_riesgo_puesto_trabajable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct> $detalleCategFactorRiesgoFrPuestoTrabAct
 * @property-read int|null $detalle_categ_factor_riesgo_fr_puesto_trab_act_count
 * @property-read Model|\Eloquent $factorRiesgoTrabajable
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereFactorRiesgoPuestoTrabajableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereFactorRiesgoPuestoTrabajableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereMedidasPreventivas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual wherePuestoTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereTiempoTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPuestoTrabajoActual whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrPuestoTrabajoActual extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_fr_puestos_trabajos_actuales';
    protected $fillable = [
        'puesto_trabajo',
        'actividad',
        'tiempo_trabajo', //nullable
        'medidas_preventivas',
        'factor_riesgo_puesto_trabajable_id',
        'factor_riesgo_puesto_trabajable_type',
    ];
    public function detalleCategFactorRiesgoFrPuestoTrabAct()
    {
        return $this->hasMany(DetalleCategFactorRiesgoFrPuestoTrabAct::class);
    }
    public function factorRiesgoTrabajable()
    {
        return $this->morphTo();
    }
}
