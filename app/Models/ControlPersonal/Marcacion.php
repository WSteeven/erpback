<?php

namespace App\Models\ControlPersonal;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Src\App\WhereRelationLikeCondition\ControlPersonal\MarcacionEmpleadoWRLC;

class Marcacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_marcaciones';
    protected $fillable = [
        'empleado_id',
        'fecha',
        'marcaciones',
    ];
    protected $casts = [
        'marcaciones' => 'json',
    ];

    private static array $whiteListFilter = [
        '*',
        'empleado.nombres',
        'empleado.apellidos',
    ];
    private array $aliasListFilter = [
        'empleado.nombres' => 'empleado',
        'empleado.apellidos' => 'empleado',
    ];


    public function EloquentFilterCustomDetection()
    {
        return [MarcacionEmpleadoWRLC::class];
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
