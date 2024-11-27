<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Asistencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;


    protected $table = 'rrhh_cp_asistencias'; // Nombre de la tabla

    // Campos permitidos para inserción masiva
    protected $fillable = [
        'empleado_id',
        'hora_ingreso',
        'hora_salida',
        'hora_salida_almuerzo',
        'hora_entrada_almuerzo',
    ];

    private static $whiteListFilter=[
        '*'
    ];

    // Relación con la tabla empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
