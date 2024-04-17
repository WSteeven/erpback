<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class RiesgoAntecedenteEmpleoAnterior extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_riesgos_antecedentes_trabajos_anteriores';
    protected $fillable = [
        'tipo_riesgo_id',
        'antecedente_id',
    ];


    public function tipoRiesgo(){
        return $this->belongsTo(TipoFactorRiesgo::class);
    }
    public function antecedente(){
        return $this->belongsTo(AntecedenteTrabajoAnterior::class);
    }
}
