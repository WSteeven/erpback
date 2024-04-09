<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class FactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_factores_riesgos';
    protected $fillable = [
        'tipo_factor_riesgo_id',
        'categoria_factor_riesgo_id',
        'ficha_preocupacional_id',
    ];

    public function tipoFactorRiesgo(){
        return $this->hasOne(TipoFactorRiesgo::class,'id','tipo_factor_riesgo_id');
    }
    public function categoriaFactorRiesgo(){
        return $this->hasOne(CategoriaFactorRiesgo::class,'id','categoria_factor_riesgo_id');
    }
    public function fichaPreocupacional(){
        return $this->hasOne(FichaPreocupacional::class, 'id','ficha_preocupacional_id');
    }

}
