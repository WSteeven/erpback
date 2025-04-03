<?php

namespace App\Models\RecursosHumanos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EmpleadoDelegado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = "rrhh_empleados_delegados";
    protected $fillable = [
        'empleado_id',
        'delegado_id',
        'fecha_hora_desde',
        'fecha_hora_hasta',
        'activo'
    ];

    protected $casts = [
        'activo'=>'boolean'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene el empleado delegado según la fecha y hora de inicio programada, en caso de no encontrar un registro, devuelve el id del empleado
     * @param int $empleado_id
     * @return int
     */
    public static function obtenerDelegado(int $empleado_id)
    {
//        Log::channel('testing')->info('Log', ['id_Recibido', $empleado_id]);
//        Log::channel('testing')->info('Log', ['Empleado autorizador', $empleado]);
//        Log::channel('testing')->info('Log', ['Delegacion encontrada', $delegacion]);

        $empleado = Empleado::find($empleado_id);
        $delegacion = $empleado->delegado?->where('fecha_hora_desde','<=', Carbon::now())->where('activo', true)->first();
        if($delegacion) return $delegacion->delegado_id;
        return $empleado_id;
    }
}
