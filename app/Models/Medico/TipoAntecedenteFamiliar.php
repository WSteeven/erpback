<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TipoAntecedenteFamiliar extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const ENFERMEDAD_CARDIO_VASCULAR = 1;
    const ENFERMEDAD_METABOLICA = 2;
    const ENFERMEDAD_NEUROLOGICA = 3;
    const ONCOLOGICA = 4;
    const ENFERMEDAD_INFECIOSA = 5;
    const ENFERMEDAD_HEREDITARIA_CONGENITA = 6;
    const DISCAPACIDADES = 7;

    protected $table = 'med_tipos_antecedentes_familiares';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
