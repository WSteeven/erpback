<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class ConfiguracionCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_configuraciones_cuestionarios_empleados';
    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin'
    ];
    private static $whiteListFilter = ['*'];


}
