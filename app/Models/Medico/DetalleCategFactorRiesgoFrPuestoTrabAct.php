<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleCategFactorRiesgoFrPuestoTrabAct extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_constantes_vitales';
    protected $fillable = [
        'categoria_factor_riesgo_id',
        'fr_puesto_trabajo_actual_id',
    ];

    public function CategriaFactorRiesgo(){
        return $this->hasOne(CategoriaFactorRiesgo::class,'categoria_factor_riesgo_id','id');
    }

    public function FrPuestoTrabajoActual(){
        return $this->hasOne(FrPuestoTrabajoActual::class,'fr_puesto_trabajo_actual_id','id');
    }

}
