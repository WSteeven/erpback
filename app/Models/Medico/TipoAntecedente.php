<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TipoAntecedente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    const MASCULINO = 'MASCULINO';
    const FEMENINO = 'FEMENINO';
    protected $table = 'med_tipos_antecedentes';
    protected $fillable = [
        'nombre',
        'genero'
    ];

    private static $whiteListFilter = ['*'];
}
