<?php

namespace App\Models\Medico;

use App\Models\Archivo;
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

/**
 * App\Models\Medico\SolicitudExamen
 *
 * @property int $id
 * @property string|null $observacion
 * @property string|null $observacion_autorizador
 * @property string $estado_solicitud_examen
 * @property int $registro_empleado_examen_id
 * @property int $canton_id
 * @property int $solicitante_id
 * @property int $autorizador_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $autorizador
 * @property-read Canton|null $canton
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\EstadoSolicitudExamen> $examenesSolicitados
 * @property-read int|null $examenes_solicitados_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @property-read Empleado|null $solicitante
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereAutorizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereEstadoSolicitudExamen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereObservacionAutorizador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /************
     * Funciones
     ************/
    public static function obtenerDescripcionOrdenCompra(SolicitudExamen $solicitudExamen)
    {
        $empleado = $solicitudExamen->registroEmpleadoExamen->empleado;
        $nombresEmpleado = Empleado::extraerNombresApellidos($empleado);
        $nombresJefe = $empleado->jefe ? Empleado::extraerNombresApellidos($empleado->jefe) : '(NO TIENE JEFE ASIGNADO)';
        $cargo = $empleado->cargo->nombre;
        $tipo_proceso_examen = $solicitudExamen->registroEmpleadoExamen->tipo_proceso_examen;
        $examenesSolicitados = $solicitudExamen->examenesSolicitados;
        return 'EXAMENES ' . $tipo_proceso_examen
            . ' PARA: ' . $nombresEmpleado
            . ', CON IDENTIFICACIÓN #' . $empleado->identificacion
            . ' Y CELULAR ' . $empleado->telefono
            . ', ' . $cargo . ' A CARGO DE ' . $nombresJefe . '. SE REALIZARÁ LOS EXÁMENES EL/LOS DIA(S) '
            . self::obtenerFechasExamenesEnTexto($examenesSolicitados)
            . ' EN EL/LOS LABORATORIO(S): ' . self::obtenerLaboratoriosEnTexto($examenesSolicitados)
            . ' EN LA CIUDAD DE ' . self::obtenerCantonesEnTexto($examenesSolicitados) . '.';
    }

    public static function obtenerDescripcionDetalleOrdenCompra(SolicitudExamen $solicitudExamen)
    {
        $examenesSolicitados = $solicitudExamen->examenesSolicitados;
        $empleado = $solicitudExamen->registroEmpleadoExamen->empleado;
        $nombresEmpleado = Empleado::extraerNombresApellidos($empleado);
        $nombresJefe = $empleado->jefe ? Empleado::extraerNombresApellidos($empleado->jefe) : '(NO TIENE JEFE ASIGNADO)';
        $cargo = $empleado->cargo->nombre;
        $tipo_proceso_examen = $solicitudExamen->registroEmpleadoExamen->tipo_proceso_examen;
        $mensaje = 'EXAMENES ' . $tipo_proceso_examen . ' PARA ' . $nombresEmpleado . ' EN EL CARGO DE ' . $cargo . ' A CARGO DE ' . $nombresJefe
            . '. LOS EXAMENES DE LABORATORIO SOLICITADOS SON: ' . self::obtenerTextoExamen($examenesSolicitados)
            . ' EN EL/LOS LABORATORIO(S): ' . self::obtenerLaboratoriosEnTexto($examenesSolicitados)
            . ' EN LA CIUDAD DE ' . self::obtenerCantonesEnTexto($examenesSolicitados) . '.';
        return $mensaje;
    }

    private static function obtenerTextoExamen($examenesSolicitados)
    {
        $listadoNombres = $examenesSolicitados->map(function ($examenSolicitado) {
            return $examenSolicitado->examen->nombre;
        })->toArray();

        return implode(', ', $listadoNombres); //->toArray());
    }

    private static function obtenerLaboratoriosEnTexto($examenesSolicitados)
    {
        $listadoNombres = $examenesSolicitados->map(function ($examenSolicitado) {
            return $examenSolicitado->laboratorioClinico->nombre;
        })->unique()->toArray();

        return implode(', ', $listadoNombres);
    }

    private static function obtenerCantonesEnTexto($examenesSolicitados)
    {
        $listadoNombres = $examenesSolicitados->map(function ($examenSolicitado) {
            return $examenSolicitado->laboratorioClinico->canton->canton;
        })->unique()->toArray();

        return implode(', ', $listadoNombres);
    }

    private static function obtenerFechasExamenesEnTexto($examenesSolicitados)
    {
        $listadoNombres = $examenesSolicitados->map(function ($examenSolicitado) {
            return Carbon::parse($examenSolicitado->fecha_hora_asistencia)->format('Y-m-d');
        })->unique()->toArray();

        return implode(', ', $listadoNombres);
    }

    public static function obtenerFechaMenorExamen($examenesSolicitados)
    {
        $fechaHora = $examenesSolicitados->sortBy('fecha_hora_asistencia')->first()->fecha_hora_asistencia;
        return Carbon::parse($fechaHora)->format('Y-m-d');
    }
}
