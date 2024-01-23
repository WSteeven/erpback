<?php

namespace App\Models\Medicos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class GestionPaciente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_';

    // Contantes
    const INGRESO = 'INGRESO';
    const OCUPACIONALES = 'OCUPACIONALES';
    const REINGRESO = 'REINGRESO';
    const SALIDA = 'SALIDA';
}
