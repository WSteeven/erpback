<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Src\App\Medico\CuestionarioPisicosocialService;

class RespuestaCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_respuestas_cuestionarios_empleados';
    protected $fillable = [
        'cuestionario_id',
        'respuesta',
        'empleado_id',
        'respuesta_texto',
    ];
    private static $whiteListFilter = ['*'];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id')->with('pregunta');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public static function empaquetar($empleados, $anio, int $tipo_cuestionario_id) // Recibe Collection Empleados
    {
        Log::channel('testing')->info('Log', ['respuesta_cuestionario', 'empaquetar']);
        $results = [];
        $cont = 0;
        foreach ($empleados as $result) {
            $cuestionarios = RespuestaCuestionarioEmpleado::obtenerCuestionarios($result->id, $anio, $tipo_cuestionario_id);
            $row['id'] =  $result->id;
            $row['empleado'] = $result->apellidos . ' ' .  $result->nombres;
            $row['ciudad'] = $result->canton->canton;
            $row['provincia'] = $result->canton->provincia->provincia;
            $row['fecha_creacion'] = count($cuestionarios) > 0 ? Carbon::parse($cuestionarios[0]['fecha_creacion'])->format('d-m-Y H:i:s') : '';
            $row['fecha_creacion_respuesta'] = count($cuestionarios) > 0 ? Carbon::parse($cuestionarios[0]['fecha_creacion'])->format('d/m/Y H:i:s') : '';
            $row['cuestionario'] = $cuestionarios;
            $row['respuestas_concatenadas'] = $tipo_cuestionario_id === TipoCuestionario::CUESTIONARIO_PSICOSOCIAL ? RespuestaCuestionarioEmpleado::obtenerRespuestasConcatenadas($cuestionarios) : '';
            $row['area'] =  $result->area->nombre;
            $row['nivel_academico'] = $result->nivel_academico;
            $row['edad'] = Carbon::now()->diffInYears($result->fecha_nacimiento) . ' AÑOS';
            $row['antiguedad'] = Carbon::now()->diffInYears($result->fecha_vinculacion) . ' AÑOS';
            $row['codigo_antigueda_genero'] = CuestionarioPisicosocialService::obtener_codigo_genero($result->genero) . CuestionarioPisicosocialService::obtener_codigo_antiguedad_empleado(Carbon::now()->diffInYears($result->fecha_vinculacion));
            $row['genero'] = $result->genero === 'M' ? 'MASCULINO' : 'FEMENINO';
            $results[$cont] = $row;
            $cont++;
        }

        $resultsCollect = collect($results);
        return $resultsCollect->filter(fn ($item) => !!$item['cuestionario']);

        // return $results;
    }

    public static function empaquetarAlcoholDrogas($empleados, $anio, int $tipo_cuestionario_id) // Recibe Collection Empleados
    {
        $results = [];
        $cont = 0;
        foreach ($empleados as $empleado) {
            Log::channel('testing')->info('Log', ['Cuestionario DROGAS', 'SDA']);
            $cuestionario = RespuestaCuestionarioEmpleado::obtenerCuestionarios($empleado->id, $anio, $tipo_cuestionario_id);
            Log::channel('testing')->info('Log', ['Cuestionario DROGAS', $cuestionario]);

            $row['id'] =  $empleado->id;
            $row['fecha_diagnostico'] = count($cuestionario) ? Carbon::parse($cuestionario[0]['fecha_creacion'])->format('d/m/Y') : '';
            $row['empleado'] = $empleado->apellidos . ' ' .  $empleado->nombres;
            $row['cargo'] = $empleado->cargo?->nombre;
            $row['identificacion'] = $empleado->identificacion;
            $row['anio_nacimiento'] = Carbon::parse($empleado->fecha_nacimiento)->format('Y');
            $row['tipo_afiliacion_seguridad_social'] = 'PÚBLICA';
            $row['estado_civil'] = $empleado->estadoCivil?->nombre;
            $row['genero'] = $empleado->genero === 'M' ? 'MASCULINO' : 'FEMENINO';
            $row['nivel_academico'] = $empleado->nivel_academico;
            $row['numero_hijos'] = $empleado->numero_hijos ?? '0';
            $row['autoidentificacion_etnica'] = $empleado->autoidentificacion_etnica ?? 'MESTIZO';
            $row['discapacidad'] = $empleado->tiene_discapacidad ? 'APLICA' : 'NO APLICA';
            $row['porcentaje_discapacidad'] = self::obtenerStringTiposDiscapacidades($empleado->tiposDiscapacidades);
            $row['trabajador_sustituto'] = $empleado->trabajador_sustituto ? 'SI' : 'NO';
            $row['enfermedades_preexistentes'] = 'NO DIAGNOSTICADA';
            $row['principal_droga_consume'] = $cuestionario[0]['respuesta']['valor'];
            $row['otra_droga_especifique'] = $cuestionario[1]['respuesta_texto'];
            $row['otra_droga_consume'] = $cuestionario[2]['respuesta']['valor'];
            $row['frecuencia_consumo'] = $cuestionario[3]['respuesta']['valor'];
            $row['reconoce_tener_problema_consumo'] = $cuestionario[4]['respuesta']['valor'];
            $row['factores_psicosociales_consumo'] = $cuestionario[5]['respuesta']['valor'];
            $row['tratamiento'] = 'NO APLICA';
            $row['personal_recibio_capacitacion'] = 'SI';
            $row['cuenta_con_examen_preocupacional'] = 'SI';
            $row['cuestionario'] = $cuestionario;

            /* $row['ciudad'] = $empleado->canton->canton;
            $row['provincia'] = $empleado->canton->provincia->provincia;
            $row['fecha_creacion_respuesta'] = count($cuestionario) > 0 ? Carbon::parse($cuestionario[0]['fecha_creacion'])->format('d/m/Y H:i:s') : '';
            $row['respuestas_concatenadas'] = $tipo_cuestionario_id === TipoCuestionario::CUESTIONARIO_PSICOSOCIAL ? RespuestaCuestionarioEmpleado::obtenerRespuestasConcatenadas($cuestionario) : '';
            $row['area'] =  $empleado->area->nombre;
            $row['edad'] = Carbon::now()->diffInYears($empleado->fecha_nacimiento) . ' AÑOS';
            $row['antiguedad'] = Carbon::now()->diffInYears($empleado->fecha_vinculacion) . ' AÑOS';
            $row['codigo_antigueda_genero'] = CuestionarioPisicosocialService::obtener_codigo_genero($empleado->genero) . CuestionarioPisicosocialService::obtener_codigo_antiguedad_empleado(Carbon::now()->diffInYears($empleado->fecha_vinculacion)); */
            $results[$cont] = $row;
            $cont++;
        }

        $resultsCollect = collect($results);
        return $resultsCollect->filter(fn ($item) => !!$item['cuestionario']);

        // return $results;
    }

    private static function obtenerStringTiposDiscapacidades($tiposDiscapacidades)
    {
        $tipos = $tiposDiscapacidades->map(fn ($tipo_discapacidad) => $tipo_discapacidad->pivot->porcentaje . '% DE DISCAPACIDAD ' . strtoupper($tipo_discapacidad->nombre));
        return implode(', ', $tipos->toArray());
    }

    private static function obtenerRespuestasConcatenadas($cuestionario)
    {
        $respuestas_concatenadas = '';
        foreach ($cuestionario as $key => $value) {
            $respuestas_concatenadas .= $value['respuesta']['valor'];
        }
        return $respuestas_concatenadas;
    }
    private static function obtenerCuestionarios($empleado_id, string $anio, int $tipo_cuestionario_id)
    {
        Log::channel('testing')->info('Log', ['respuesta_cuestionario', 'bebe']);
        $respuesta_cuestionario = RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->whereYear('created_at', $anio)->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->get();
        Log::channel('testing')->info('Log', ['respuesta_cuestionario', $respuesta_cuestionario]);

        if ($respuesta_cuestionario) {
            $cuestionarios = $respuesta_cuestionario->map(function ($cuestionario) {
                $respuesta = Respuesta::find($cuestionario['cuestionario']['respuesta_id']);
                $new_cuestionario = [
                    "pregunta_id" => $cuestionario['cuestionario']['pregunta_id'],
                    'respuesta' => $respuesta,
                    'respuesta_texto' => $cuestionario->respuesta_texto,
                    'fecha_creacion' => $cuestionario['created_at']
                ];
                return $new_cuestionario;
            });
            return $cuestionarios;
        }

        return null;
    }
}
