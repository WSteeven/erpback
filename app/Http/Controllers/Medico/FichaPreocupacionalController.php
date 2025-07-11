<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\Medico\FichaPreocupacionalResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\RegistroEmpleadoExamen;
use App\Models\Medico\ResultadoExamen;
use App\Models\Medico\SolicitudExamen;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\Medico\FichaPreocupacionalService;
use Src\App\Medico\SolicitudExamenService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class FichaPreocupacionalController extends Controller
{
    private string $entidad = 'FichaPreocupacional';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();

        $this->middleware('can:puede.ver.fichas_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.fichas_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.fichas_preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = FichaPreocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(FichaPreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha_preocupacional = FichaPreocupacional::create($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->crearOActualizarDatosFichaPreocupacional($request);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getLine(), $e->getMessage()],
            ]);
        }
    }

    public function show(FichaPreocupacional $ficha_preocupacional)
    {
        $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
        return response()->json(compact('modelo'));
    }


    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $ficha_preocupacional->update($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->crearOActualizarDatosFichaPreocupacional($request);

            $modelo = new FichaPreocupacionalResource($ficha_preocupacional->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function destroy(FichaPreocupacional $ficha_preocupacional)
    {
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        try {
            DB::beginTransaction();
            $ficha_preocupacional->delete();
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                $mensaje => [$e->getMessage()],
            ]);
        }
    }

    public function consultarInformacionDefectoFicha()
    {
        request()->validate([
            'registro_empleado_examen_id' => 'required|numeric|integer|exists:med_registros_empleados_examenes,id'
        ]);

        $consulta_medica = ConsultaMedica::where('registro_empleado_examen_id', request('registro_empleado_examen_id'))->first();
        $cargo_id = RegistroEmpleadoExamen::find(request('registro_empleado_examen_id'))->empleado->cargo_id;
        // $consulta_medica = new ConsultaMedicaResource($consulta_medica);

        $modelo = [
            'motivo_consulta' => 'Ficha preocupacional',
            'recomendaciones_tratamiento' => $consulta_medica?->receta->rp . '/' . $consulta_medica?->receta->prescripcion,
            'enfermedad_actual' => $consulta_medica?->diagnosticosCitaMedica->map(fn($diagnostico) => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad)->implode(',', ' '),
            'cargo' => $cargo_id,
        ];

        return response()->json(compact('modelo'));
    }

    public function imprimirPDF(FichaPreocupacional $ficha_preocupacional)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaPreocupacionalResource($ficha_preocupacional);
        $empleado = Empleado::find($ficha_preocupacional->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_preocupacional->profesional_salud_id);
//        $idEmpleado = $empleado->id;
        $registro_empleado_examen_id = $ficha_preocupacional->registro_empleado_examen_id;

//        $respuestasTiposEvaluacionesMedicasRetiros = [
//            ['SI', 'NO'],
//            ['PRESUNTIVA', 'DEFINITIVA', 'NO APLICA'],
//            ['SI', 'NO', 'NO APLICA'],
//        ];

        // Historial
        /* $consultasMedicas = ConsultaMedica::whereHas('registroEmpleadoExamen', function ($query) use ($idEmpleado) {
            $query->where('empleado_id', $idEmpleado);
        })->orWhereHas('citaMedica', function ($query) use ($idEmpleado) {
            $query->where('paciente_id', $idEmpleado);
        })->latest()->get(); */

        // Solo de la ficha actual
        $consultasMedicas = ConsultaMedica::where('registro_empleado_examen_id', $registro_empleado_examen_id)->latest()->get();

        Log::channel('testing')->info('Log', ['empleado', $empleado]);

        $consultasMedicasMapeado = $consultasMedicas->map(function ($consulta) {
            return [
                'observacion' => $consulta->observacion,
                'diagnosticos' => $consulta->diagnosticosCitaMedica->map(function ($diagnostico) {
                    return [
                        'recomendacion' => $diagnostico->recomendacion,
                        'cie' => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad,
                        'pre' => null,
                        'def' => 'x',
                    ];
                }),
            ];
        });


        /* $opcionesRespuestasTipoEvaluacionMedicaRetiro = TipoEvaluacionMedicaRetiro::all()->map(function ($tipo, $index) use ($respuestasTiposEvaluacionesMedicasRetiros, $ficha_aptitud) {
            return [
                'id' => $tipo->id,
                'nombre' => $tipo->nombre,
                'posibles_respuestas' => $respuestasTiposEvaluacionesMedicasRetiros[$index],
                'respuesta' => $ficha_preocupacional->opcionesRespuestasTipoEvaluacionMedicaRetiro->first(fn ($opcion) => $opcion->tipo_evaluacion_medica_retiro_id === $tipo->id)->respuesta,
            ];
        }); */

        /* $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha_aptitud) {
            if ($tipo->id === $ficha_aptitud->tipo_aptitud_medica_laboral_id) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        }); */

        $ficha = $resource->resolve();

        $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha) {
            if ($tipo->id === $ficha['aptitud_medica']['tipo_aptitud_id']) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        });

        // $ficha['tipos_aptitudes_medicas_laborales'] = $tipos_aptitudes_medicas_laborales;

        $ficha['consultas_medicas'] = $consultasMedicasMapeado;
        $ficha['recomendaciones_tratamiento'] = count($consultasMedicas) ? $consultasMedicas[0]?->receta->rp . ' / ' . $consultasMedicas[0]?->receta->prescripcion : $ficha_preocupacional->recomendaciones_tratamiento;
        $ficha['resultados_examenes'] = $this->consultarResultadosExamenes($registro_empleado_examen_id);
        $ficha['observaciones_aptitud_medica'] = 'observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica';

        $ficha['accidentes_trabajo'] = [
            'especificar' => 'IESS',
            'calificado_iess' => true,
            'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'fecha' => Carbon::parse('2024-04-20'),
        ];

        $ficha['enfermedades_profesionales'] = [
            'especificar' => 'IESS',
            'calificado_iess' => false,
            'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'fecha' => Carbon::parse('2024-03-24'),
        ];

        $ficha['observaciones_examen_fisico_regional'] = $this->mapearObservacionesExamenFisicoRegional($ficha['examenes_fisicos_regionales']); //'observaciones_examen_fisico_regional observaciones_examen_fisico_regional observaciones_examen_fisico_regional';
        $ficha['examenes_fisicos_regionales'] = $ficha['examenes_fisicos_regionales']->pluck('categoria_examen_fisico_id')->toArray();

        $datos = [
            'ficha_preocupacional' => $ficha,
            'configuracion' => $configuracion,
            'empleado' => new EmpleadoResource($empleado),
            'profesionalSalud' => $profesionalSalud,
            'tipo_proceso_examen' => $ficha_preocupacional->registroEmpleadoExamen->tipo_proceso_examen,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
            'fecha_creacion' => Carbon::parse($ficha_preocupacional->created_at)->format('Y-m-d'),
            'hora_creacion' => Carbon::parse($ficha_preocupacional->created_at)->format('H:i:s'),
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_preocupacional', $datos);
            $pdf->setPaper('A4');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();

            return $pdf->output();
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'));
        }
    }

    private function consultarResultadosExamenes(int $registro_empleado_examen_id)
    {
        $examenes_solicitados = collect([]);
        $solicitudesExamenes = SolicitudExamen::where('registro_empleado_examen_id', $registro_empleado_examen_id)->where('estado_solicitud_examen', SolicitudExamen::SOLICITADO)->latest()->get();
        $resultadosExamenesRegistrados = $this->consultarResultadosExamenesRegistrados($registro_empleado_examen_id);
        Log::channel('testing')->info('Log', ['resultadosExamenesRegistrados', $resultadosExamenesRegistrados]);

        foreach ($solicitudesExamenes as $solicitudExamen) {
            foreach ($solicitudExamen->examenesSolicitados as $examenSolicitado) {
                // Log::channel('testing')->info('Log', ['consultarResultadosExamenes', $examenSolicitado->examen->nombre]);
                $examenes_solicitados->push([
                    'examen' => $examenSolicitado->examen->nombre,
                    'fecha_asistencia' => Carbon::parse($examenSolicitado->fecha_hora_asistencia)->format('Y-m-d'),
                    'resultados' => $this->filtrarResultadosExamenesRegistradosPorIdExamenSolicitado($resultadosExamenesRegistrados, $examenSolicitado->id),
                ]);
            }
        }

        Log::channel('testing')->info('Log', ['examenes_solicitados', $examenes_solicitados]);
        return $examenes_solicitados;
    }

    private function consultarResultadosExamenesRegistrados(int $registro_empleado_examen_id)
    {
        $solicitudExamenService = new SolicitudExamenService();
        $ids_examenes_solicitados = $solicitudExamenService->obtenerIdsExamenesSolicitados($registro_empleado_examen_id);

        return ResultadoExamen::ignoreRequest(['campos', 'registro_empleado_examen_id'])->filter()->whereIn('examen_solicitado_id', $ids_examenes_solicitados)->get();
    }

    private function filtrarResultadosExamenesRegistradosPorIdExamenSolicitado(Collection $resultadosExamenesRegistrados, int $examen_solicitado_id)
    {
        return $resultadosExamenesRegistrados->filter(fn($resultado_examen_registrado) => $resultado_examen_registrado->examen_solicitado_id == $examen_solicitado_id)->map(fn($resultado_examen_registrado) => [
            'resultado' => $resultado_examen_registrado->resultado,
            'configuracion_examen_campo' => $resultado_examen_registrado->configuracionExamenCampo->campo,
            'unidad_medida' => $resultado_examen_registrado->configuracionExamenCampo->unidad_medida,
            'observaciones' => $resultado_examen_registrado->observaciones,
        ]);
    }

    private function mapearObservacionesExamenFisicoRegional($observaciones_examen_fisico_regional)
    {
        return $observaciones_examen_fisico_regional->map(fn($item) => [
            'categoria' => CategoriaExamenFisico::find($item['categoria_examen_fisico_id'])->nombre,
            'observacion' => $item['observacion'],
        ]); //->toArray();
    }

    /**
     * Listar archivos
     */
    public function indexFiles(FichaPreocupacional $ficha_preocupacional)
    {
        try {
            $results = $this->archivoService->listarArchivos($ficha_preocupacional);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws Throwable
     */
    public function storeFiles(Request $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($ficha_preocupacional, $request->file, RutasStorage::FICHAS_PREOCUPACIONALES->value . '_' . $ficha_preocupacional->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
