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
 * App\Models\Medico\DetalleCategFactorRiesgoFrPuestoTrabAct
 *
 * @property int $id
 * @property int $categoria_factor_riesgo_id
 * @property int $fr_puesto_trabajo_actual_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoriaFactorRiesgo|null $CategriaFactorRiesgo
 * @property-read FrPuestoTrabajoActual|null $FrPuestoTrabajoActual
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct newModelQuery()
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct newQuery()
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct query()
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereCategoriaFactorRiesgoId($value)
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereCreatedAt($value)
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereFrPuestoTrabajoActualId($value)
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereId($value)
 * @method static Builder|DetalleCategFactorRiesgoFrPuestoTrabAct whereUpdatedAt($value)
 * @mixin Eloquent
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
