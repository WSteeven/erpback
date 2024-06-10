<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class AccidenteEnfermedadLaboral extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_accidentes_enfermedades_laborales';
    protected $fillable = [
        'tipo',
        'observacion',
        'calificado_iss',
        'instituto_seguridad_social',
        'fecha',
        'accidentable_id',
        'accidentable_type',
    ];

    const ACCIDENTE_TRABAJO = 'ACCIDENTE DE TRABAJO';
    const ENFERMEDAD_PROFESIONAL = 'ENFERMEDAD PROFESIONAL';

    // Relación polimórfica
    public function accidentable()
    {
        return $this->morphTo();
    }
}
