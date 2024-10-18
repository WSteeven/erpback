<?php

namespace App\Models\RecursosHumanos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Planificador extends Model implements Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait, AuditableModel;
    protected $table = 'rrhh_planificadores';
    protected $fillable = [
        'empleado_id',
        'nombre',
        'completado',
    ];

}
