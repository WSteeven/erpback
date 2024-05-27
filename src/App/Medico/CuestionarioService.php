<?php

namespace Src\App\Medico;

use App\Http\Resources\Medico\CuestionarioEmpleadoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use App\Models\Medico\TipoCuestionario;
use Illuminate\Support\Facades\Log;
use App\Models\Medico\Pregunta;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Exception;

class CuestionarioService
{
    public function reportesCuestionarios(Request $request)
    {
        $request->validate([
            'anio' => 'required|string',
            'tipo_cuestionario_id' => 'required|numeric|string|exists:med_tipos_cuestionarios,id',
        ]);

        $tipo_cuestionario_id = $request['tipo_cuestionario_id'];

        try {
            $results = [];
            $empleados = Empleado::habilitado()
                ->where('salario', '!=', 0)
                ->orderBy('apellidos', 'asc')
                ->with('canton', 'area')
                ->get();

            $empleados = $empleados->filter(fn ($empleado) => $empleado->respuestaCuestionarioEmpleado()->whereYear('created_at', $request['anio'])->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
                $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
            })->exists());

            Log::channel('testing')->info('Log', ['EF: ', $empleados]);

            // Tabla visualizar si llenaron el cuestionario
            $results = $empleados->values()->map(fn ($empleado) => [
                'id' => $empleado->id,
                'empleado' => Empleado::extraerNombresApellidos($empleado),
                'finalizado' => true,
            ]);

            //CuestionarioEmpleadoResource::collection($empleados); // verificar si hay cuestioanrio

            // Reportes xlsx
            if ($request->imprimir) {
                $preguntas = Pregunta::select(['id', 'pregunta', 'codigo'])->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
                    $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
                })->get();

                $codigos = [
                    4,
                    4.1,
                    4.2,
                    4.3,
                    4.4,
                    4.5,
                ];

                if ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS) {
                    $preguntas = $preguntas->map(function ($pregunta, $index) use ($codigos) {
                        $pregunta->codigo = $codigos[$index];
                        return $pregunta;
                    });
                }

                // Log::channel('testing')->info('Log', ['Preguntas: ', $preguntas]);
                if ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_PSICOSOCIAL) $reportes_empaquetado = RespuestaCuestionarioEmpleado::empaquetar($empleados, $request['anio'], $tipo_cuestionario_id);
                elseif ($tipo_cuestionario_id == TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS) $reportes_empaquetado = RespuestaCuestionarioEmpleado::empaquetarAlcoholDrogas($empleados, $request['anio'], $tipo_cuestionario_id);

                $configuracion = ConfiguracionGeneral::first();
                $reporte = compact('preguntas', 'reportes_empaquetado', 'configuracion');
                return CuestionarioPisicosocialService::imprimir_reporte($reporte, $tipo_cuestionario_id);
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
        $empleados = Empleado::habilitado()
            ->where('salario', '!=', 0)
            ->orderBy('apellidos', 'asc')
            ->with('canton', 'area')
            ->get();
        $reportes_empaquetado = RespuestaCuestionarioEmpleado::empaquetar($empleados, request('anio'), TipoCuestionario::CUESTIONARIO_PSICOSOCIAL);
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
}
