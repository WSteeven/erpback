<?php

namespace Src\App\Medico;

use App\Models\ConfiguracionGeneral;
use App\Models\Medico\CuestionarioPublico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use App\Models\Medico\TipoCuestionario;
use Illuminate\Support\Facades\Log;
use App\Models\Medico\Respuesta;
use App\Models\Medico\Pregunta;
use App\Models\Medico\Persona;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class ReporteCuestionarioPublicoService extends ReporteCuestionarioAbstract
{
    private $configuracion;

    public function __construct()
    {
        $this->configuracion = ConfiguracionGeneral::first();
    }

    public function reportesCuestionarios(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|string',
            'fecha_fin' => 'required|string',
            'tipo_cuestionario_id' => 'required|numeric|string|exists:med_tipos_cuestionarios,id',
            'link' => 'required|string',
        ]);

        // Parametros
        $tipo_cuestionario_id = $request['tipo_cuestionario_id'];
        $fecha_inicio = request('fecha_inicio');
        $fecha_fin = request('fecha_fin');

        try {
            $results = [];
            $personas = Persona::query()->tipoCuestionario($tipo_cuestionario_id)->where('nombre_empresa', $request['link'])->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->orderBy('primer_apellido', 'asc')->get();
            // $personas = $personas->filter(fn ($persona) => $persona->cuestionarioPublico()->whereYear('created_at', $request['anio'])->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $personas = $personas->filter(fn ($persona) => $persona->cuestionarioPublico()->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
                $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
            })->exists());

            // Tabla visualizar si llenaron el cuestionario
            $results = $personas->values()->map(fn ($persona) => [
                'id' => $persona->id,
                'empleado' => Persona::extraerNombresApellidos($persona),
                'finalizado' => true,
            ]);

            // Reportes xlsx
            if ($request->imprimir) {
                if ($request->formato == 'xlsx') {
                    $preguntas = Pregunta::select(['id', 'pregunta', 'codigo'])->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
                        $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
                    })->get();

                    $codigos = self::CODIGOS_ALCOHOL_DROGAS;

                    if ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS) {
                        $preguntas = $preguntas->map(function ($pregunta, $index) use ($codigos) {
                            $pregunta->codigo = $codigos[$index];
                            return $pregunta;
                        });
                    }

                    $configuracion = $this->configuracion;

                    if ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_PSICOSOCIAL) $reportes_empaquetado = $this->empaquetarPsicosocial($personas, $fecha_inicio, $fecha_fin, $tipo_cuestionario_id);
                    elseif ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS) $reportes_empaquetado = $this->empaquetarAlcoholDrogas($personas, $fecha_inicio, $fecha_fin, $tipo_cuestionario_id);

                    $reporte = compact('preguntas', 'reportes_empaquetado', 'configuracion');
                    return CuestionarioPisicosocialService::imprimir_reporte($reporte, $tipo_cuestionario_id);
                }

                if ($request->formato == 'txt') {
                    return $this->imprimirCuestionarioFPSICO();
                }
            }

            return response()->json(compact('results'));
        } catch (Exception $e) {
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de Cuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    // FPSICO 4.0
    public function imprimirCuestionarioFPSICO()
    {
        // Crear el contenido del archivo .txt
        /*  $empleados = Empleado::habilitado()
            ->where('salario', '!=', 0)
            ->orderBy('apellidos', 'asc')
            ->with('canton', 'area')
            ->get(); */
        $tipo_cuestionario_id = request('tipo_cuestionario_id');

        $personas = Persona::query()->tipoCuestionario($tipo_cuestionario_id)->orderBy('primer_apellido', 'asc')->get();
        $empleados = $personas->filter(fn ($persona) => $persona->cuestionarioPublico()->whereYear('created_at', request('anio'))->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->exists());

        $reportes_empaquetado = $this->empaquetarPsicosocial($empleados, request('fecha_inicio'), request('fecha_fin'), TipoCuestionario::CUESTIONARIO_PSICOSOCIAL);
        $datos = CuestionarioPisicosocialService::mapear_datos($reportes_empaquetado);
        $contenido = "";
        $contenido .= "a. Género\n";
        $contenido .= ">Mujer\n";
        $contenido .= ">Hombre\n";
        $contenido .= "b. Antigüedad en el puesto\n";
        $contenido .= ">Menor a un año\n";
        $contenido .= ">Entre 1 y 2 años\n";
        $contenido .= ">Entre 2 y 3 años\n";
        $contenido .= ">Entre 3 y 4 años\n";
        $contenido .= ">Entre 4 y 5 años\n";
        $contenido .= ">Entre 5 y 6 años\n";
        $contenido .= ">Entre 6 y 7 años\n";
        $contenido .= ">Entre 7 y 8 años\n";
        $contenido .= ">Entre 8 y 9 años\n";
        $contenido .= ">Entre 9 y 10 años\n";
        $contenido .= ">Mayor a 10 años\n";
        $contenido .= "****************************************\n";
        foreach ($datos as $dato) {
            $contenido .= implode("", $dato) . "\n";
        }
        // Generar el nombre del archivo
        $nombreArchivo = 'respuestas_cuestionario.txt';
        // Crear el archivo .txt
        $archivo = fopen($nombreArchivo, 'w');
        fwrite($archivo, $contenido);
        fclose($archivo);
        // Enviar el archivo como respuesta HTTP
        return Response::download($nombreArchivo)->deleteFileAfterSend(true);
    }

    public function empaquetarPsicosocial($empleados, $fecha_inicio, $fecha_fin, int $tipo_cuestionario_id) // Recibe Collection Empleados
    {
        $results = [];
        $cont = 0;
        foreach ($empleados as $result) {
            $cuestionarios = $this->obtenerCuestionarios($result->id, $fecha_inicio, $fecha_fin, $tipo_cuestionario_id);
            $row['id'] =  $result->id;
            $row['empleado'] = Persona::extraerNombresApellidos($result);
            $row['ciudad'] = $result->canton->canton;
            $row['provincia'] = $result->canton->provincia->provincia;
            $row['fecha_creacion'] = count($cuestionarios) > 0 ? Carbon::parse($cuestionarios[0]['fecha_creacion'])->format('d-m-Y H:i:s') : '';
            $row['fecha_creacion_respuesta'] = count($cuestionarios) > 0 ? Carbon::parse($cuestionarios[0]['fecha_creacion'])->format('d/m/Y H:i:s') : '';
            $row['cuestionario'] = $cuestionarios;
            $row['respuestas_concatenadas'] = $tipo_cuestionario_id === TipoCuestionario::CUESTIONARIO_PSICOSOCIAL ? $this->obtenerRespuestasConcatenadas($cuestionarios) : '';
            $row['area'] =  $result->area;
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
    }

    public function empaquetarAlcoholDrogas($personas, $fecha_inicio, $fecha_fin, int $tipo_cuestionario_id) // Recibe Collection
    {
        $results = [];
        $cont = 0;
        foreach ($personas as $persona) {
            $cuestionario = $this->obtenerCuestionarios($persona->id, $fecha_inicio, $fecha_fin, $tipo_cuestionario_id);

            $row['id'] =  $persona->id;
            $row['fecha_diagnostico'] = count($cuestionario) ? Carbon::parse($cuestionario[0]['fecha_creacion'])->format('d/m/Y') : '';
            $row['empleado'] = $persona->primer_apellido . ' ' .  $persona->primer_nombre;
            $row['cargo'] = $persona->cargo;
            $row['identificacion'] = $persona->identificacion;
            $row['anio_nacimiento'] = Carbon::parse($persona->fecha_nacimiento)->format('Y');
            $row['tipo_afiliacion_seguridad_social'] = 'PÚBLICA';
            $row['estado_civil'] = $persona->estadoCivil->nombre;
            $row['genero'] = $persona->genero === 'M' ? 'MASCULINO' : 'FEMENINO';
            $row['nivel_academico'] = $persona->nivel_academico;
            $row['numero_hijos'] = $persona->numero_hijos ?? '0';
            $row['autoidentificacion_etnica'] = $persona->autoidentificacion_etnica ?? 'MESTIZO';
            $row['discapacidad'] = $persona->discapacidad ? 'APLICA' : 'NO APLICA';
            $row['porcentaje_discapacidad'] = $persona->porcentaje_discapacidad;
            $row['trabajador_sustituto'] = $persona->trabajador_sustituto ? 'SI' : 'NO';
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
            $row['razon_social'] = $persona->nombre_empresa;
            $row['ruc'] = $persona->ruc;

            $results[$cont] = $row;
            $cont++;
        }

        $resultsCollect = collect($results);
        return $resultsCollect->filter(fn ($item) => !!$item['cuestionario']);
    }

    private function obtenerCuestionarios($persona_id, string $fecha_inicio, string $fecha_fin, int $tipo_cuestionario_id)
    {
        $respuesta_cuestionario = CuestionarioPublico::where('persona_id', $persona_id)->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->get();

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
