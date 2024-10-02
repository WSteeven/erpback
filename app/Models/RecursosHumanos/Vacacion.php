<?php

namespace App\Models\RecursosHumanos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Vacacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'rrhh_nomina_vacacions';
    protected $fillable = [

    ];
}
