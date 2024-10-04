<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PlanVacacion extends Model implements  Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'rrhh_nomina_planes_vacaciones';
    protected $fillable = [

    ];
}
