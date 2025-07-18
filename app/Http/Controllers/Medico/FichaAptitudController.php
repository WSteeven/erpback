<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaAptitudRequest;
use App\Http\Resources\Medico\FichaAptitudResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\FichaAptitud;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class FichaAptitudController extends Controller
{
    private string $entidad = 'Examen Ficha de aptitud';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this-> archivoService = new ArchivoService();

        $this->middleware('can:puede.ver.fichas_aptitudes')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_aptitudes')->only('store');
        $this->middleware('can:puede.editar.fichas_aptitudes')->only('update');
        $this->middleware('can:puede.eliminar.fichas_aptitudes')->only('destroy');
    }

    public function index()
    {
        $results = FichaAptitud::ignoreRequest(['campos'])->filter()->get();
        $results = FichaAptitudResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     */
    public function store(FichaAptitudRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();
            $datos['firmado_profesional_salud'] = true;
            $ficha_aptitud = FichaAptitud::create($datos);

            // opcionesRespuestasTipoEvaluacionMedicaRetiro
            foreach ($datos['opciones_respuestas_tipo_evaluacion_medica_retiro'] as $opcion) {
                $opcion['tipo_evaluacion_medica_retiro_id'] = $opcion['tipo_evaluacion_medica_retiro'];
                $ficha_aptitud->opcionesRespuestasTipoEvaluacionMedicaRetiro()->create($opcion);
            }

            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de ficha de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(FichaAptitud $ficha_aptitud)
    {
        $modelo = new FichaAptitudResource($ficha_aptitud);
        return response()->json(compact('modelo'));
    }


    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(FichaAptitudRequest $request, FichaAptitud $ficha_aptitud)
    {
        Log::channel('testing')->info('Dentro de update... ');
        try {
            Log::channel('testing')->info('Dentro de update try... ');
            // DB::beginTransaction();

            // if ($request->isMethod('patch')) {
            $keys = $request->keys();
            Log::channel('testing')->info('Dentro de update... ', [$keys]);
            unset($keys['id']);
            // Log::channel('testing')->info('Keys ' . $request->keys());
            // Log::channel('testing')->info('Keys only ' . $request->only($request->keys()));
            $ficha_aptitud->update($request->only($request->keys()));
            // }

            // $datos = $request->validated();
            // $ficha_aptitud->update($datos);
            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            // DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar el registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function destroy(FichaAptitud $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $ficha_aptitud->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al eliminar el registro' => [$e->getMessage()],
            ]);
        }
    }

    public function imprimirPDF(FichaAptitud $ficha_aptitud)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaAptitudResource($ficha_aptitud);
        $empleado = Empleado::find($ficha_aptitud->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_aptitud->profesional_salud_id);

        $respuestasTiposEvaluacionesMedicasRetiros = [
            ['SI', 'NO'],
            ['PRESUNTIVA', 'DEFINITIVA', 'NO APLICA'],
            ['SI', 'NO', 'NO APLICA'],
        ];

        $opcionesRespuestasTipoEvaluacionMedicaRetiro = TipoEvaluacionMedicaRetiro::all()->map(function ($tipo, $index) use ($respuestasTiposEvaluacionesMedicasRetiros, $ficha_aptitud) {
            if (!isset($respuestasTiposEvaluacionesMedicasRetiros[$index])) {
                Log::channel('testing')->warning('No hay respuestas definidas para índice de evaluación médica', [
                    'index' => $index,
                    'tipo_id' => $tipo->id,
                    'tipo_nombre' => $tipo->nombre
                ]);
            }

            return [
                'id' => $tipo->id,
                'nombre' => $tipo->nombre,
                'posibles_respuestas' => $respuestasTiposEvaluacionesMedicasRetiros[$index] ?? ['NO DEFINIDO'],
                'respuesta' => optional(
                    $ficha_aptitud->opcionesRespuestasTipoEvaluacionMedicaRetiro->first(
                        fn($opcion) => $opcion->tipo_evaluacion_medica_retiro_id === $tipo->id
                    )
                )->respuesta,
            ];
        });

        $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha_aptitud) {
            if ($tipo->id === $ficha_aptitud->tipo_aptitud_medica_laboral_id) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        });

        $datos = [
            'ficha_aptitud' => $resource->resolve(),
            'configuracion' => $configuracion,
            'empleado' => $empleado,
            'opcionesRespuestasTipoEvaluacionMedicaRetiro' => $opcionesRespuestasTipoEvaluacionMedicaRetiro,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
            'profesionalSalud' => $profesionalSalud,
            //'firmaProfesionalMedico' => 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->empleado->firma_url, 1))),
            'firmaProfesionalMedico' => Utils::urlToBase64(url($profesionalSalud->empleado->firma_url)) ,
            //'firmaPaciente' => 'data:image/png;base64,' . base64_encode(file_get_contents(substr($ficha_aptitud->registroEmpleadoExamen->empleado->firma_url, 1))),
//            'firmaPaciente' => Utils::urlToBase64(url($ficha_aptitud->registroEmpleadoExamen->empleado->firma_url)) ,
            'tipo_proceso_examen' => $ficha_aptitud->registroEmpleadoExamen->tipo_proceso_examen,
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_aptitud', $datos);
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

        /**
     * Listar archivos
     */
    public function indexFiles(FichaAptitud $ficha_aptitud)
    {
        try {
            $results = $this->archivoService->listarArchivos($ficha_aptitud);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws Throwable
     */
    public function storeFiles(Request $request, FichaAptitud $ficha_aptitud)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($ficha_aptitud, $request->file, RutasStorage::FICHAS_APTITUD->value . '_' . $ficha_aptitud->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
