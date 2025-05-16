<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct
 *
 * @property int $id
 * @property int $categoria_factor_riesgo_id
 * @property int $fr_puesto_trabajo_actual_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\CategoriaFactorRiesgo|null $CategriaFactorRiesgo
 * @property-read \App\Models\Medico\FrPuestoTrabajoActual|null $FrPuestoTrabajoActual
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereCategoriaFactorRiesgoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereFrPuestoTrabajoActualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleCategFactorRiesgoFrPuestoTrabAct extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_detalle_categ_factor_riesgo_fr_puesto_trab_acts';
    protected $fillable = [
        'categoria_factor_riesgo_id',
        'fr_puesto_trabajo_actual_id',
    ];

    public function CategriaFactorRiesgo(){
        return $this->hasOne(CategoriaFactorRiesgo::class,'categoria_factor_riesgo_id','id');
    }

    public function FrPuestoTrabajoActual(){
        return $this->belongsTo(FrPuestoTrabajoActual::class,'fr_puesto_trabajo_actual_id','id');
    }

}
