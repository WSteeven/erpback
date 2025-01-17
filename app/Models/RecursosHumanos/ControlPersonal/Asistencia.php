<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 *
 * @method static Builder|Asistencia  updateOrCreate($attributes, $values )
 */
class Asistencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_asistencias';

    // Campos permitidos para inserción masiva
    protected $fillable = [
        'empleado_id',
        'fecha',
        'hora_ingreso',
        'hora_salida',
        'hora_salida_almuerzo',
        'hora_entrada_almuerzo',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    // Relación con la tabla empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Casts para formatear automáticamente los valores
    protected $casts = [
        'fecha' => 'date:Y-m-d',
        'hora_ingreso' => 'datetime:H:i:s',
        'hora_salida' => 'datetime:H:i:s',
        'hora_salida_almuerzo' => 'datetime:H:i:s',
        'hora_entrada_almuerzo' => 'datetime:H:i:s',
    ];
}
