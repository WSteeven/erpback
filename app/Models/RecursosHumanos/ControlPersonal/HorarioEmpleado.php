<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class HorarioEmpleado extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;

    protected $table = 'rrhh_cp_horarios_empleados';
    protected $fillable = [
        'empleado_id',
        'horario_id',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function horarioLaboral()
    {
        return $this->belongsTo(HorarioLaboral::class, 'horario_id');
    }


}
