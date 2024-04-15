<?php

namespace App\Models\Medico;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Notificacion;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SolicitudExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_solicitudes_examenes';
    protected $fillable = [
        'observacion',
        'observacion_autorizador',
        'registro_empleado_examen_id',
        'estado_solicitud_examen',
        'canton_id',
        'solicitante_id',
        'autorizador_id',
    ];

    // Estados solicitudes examenes
    const PENDIENTE = 'PENDIENTE';
    const SOLICITADO = 'SOLICITADO';
    const APROBADO_POR_COMPRAS = 'APROBADO_POR_COMPRAS';
    const RESULTADOS = 'RESULTADOS';
    const DIAGNOSTICO_REALIZADO = 'DIAGNOSTICO_REALIZADO';

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function examenesSolicitados()
    {
        return $this->hasMany(EstadoSolicitudExamen::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /************
     * Funciones
     ************/
    public static function obtenerDescripcionOrdenCompra(SolicitudExamen $solicitudExamen){
        $empleado = $solicitudExamen->registroEmpleadoExamen->empleado;
        $nombresEmpleado = Empleado::extraerNombresApellidos($empleado);
        $nombresJefe = $empleado->jefe ? Empleado::extraerNombresApellidos($empleado->jefe) : '(NO TIENE JEFE ASIGNADO)';
        $cargo = $empleado->cargo->nombre;
        $tipo_proceso_examen = $solicitudExamen->registroEmpleadoExamen->tipo_proceso_examen;
        return 'EXAMENES ' . $tipo_proceso_examen . ' PARA ' . $nombresEmpleado . ' EN EL CARGO DE ' . $cargo . ' A CARGO DE ' . $nombresJefe . '.'; //' CON FECHA DE REALIZACIÓN  ';
    }

    public static function obtenerDescripcionDetalleOrdenCompra(SolicitudExamen $solicitudExamen) {
        $examenesSolicitados = $solicitudExamen->examenesSolicitados;
        // Log::channel('testing')->info('Log', ['examenesSolicitados', $examenesSolicitados]);
        $empleado = $solicitudExamen->registroEmpleadoExamen->empleado;
        $nombresEmpleado = Empleado::extraerNombresApellidos($empleado);
        $nombresJefe = $empleado->jefe ? Empleado::extraerNombresApellidos($empleado->jefe) : '(NO TIENE JEFE ASIGNADO)';
        $cargo = $empleado->cargo->nombre;
        $tipo_proceso_examen = $solicitudExamen->registroEmpleadoExamen->tipo_proceso_examen;
        $mensaje = 'EXAMENES ' . $tipo_proceso_examen . ' PARA ' . $nombresEmpleado . ' EN EL CARGO DE ' . $cargo . ' A CARGO DE ' . $nombresJefe . '. LOS EXAMENES DE LABORATORIO SOLICITADOS SON: ' . self::obtenerTextoExamen($examenesSolicitados);
        // Log::channel('testing')->info('Log', ['examenesSolicitados', $mensaje]);
        return $mensaje;
    }

    private static function obtenerTextoExamen($examenesSolicitados) {
        $listadoNombres = $examenesSolicitados->map(function($examenSolicitado) {

            return $examenSolicitado->examen->nombre;
        })->toArray();

        return implode(' ', $listadoNombres); //->toArray());
    }

    public static function obtenerFechaMenorExamen($examenesSolicitados) {
        $fechaHora = $examenesSolicitados->sortBy('fecha_hora_asistencia')->first()->fecha_hora_asistencia;
        return Carbon::parse($fechaHora)->format('Y-m-d');
    }
}
