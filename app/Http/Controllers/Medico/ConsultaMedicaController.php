<?php

namespace App\Http\Controllers\Medico;

use App\Events\Medico\DiasDescansoEvent;
use App\Http\Requests\Medico\ConsultaMedicaRequest;
use App\Http\Resources\Medico\ConsultaMedicaResource;
use App\Models\SSO\SolicitudDescuento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Medico\ConsultaMedica;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Medico\CitaMedica;
use App\Models\User;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Exception;
use Src\App\EmpleadoService;
use Src\App\Medico\PolymorphicMedicoModelsService;
use Throwable;

class ConsultaMedicaController extends Controller
{
    private $entidad = 'Consulta médica';
    private EmpleadoService $empleadoService;
    private PolymorphicMedicoModelsService $polymorphicMedicoModelsService;
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.consultas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.consultas_medicas')->only('store');
        $this->middleware('can:puede.editar.consultas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.consultas_medicas')->only('destroy');
        $this->empleadoService = new EmpleadoService();
        $this->polymorphicMedicoModelsService = new PolymorphicMedicoModelsService();
        $this->archivoService = new ArchivoService();
    }

    public function index()
    {
        $results = ConsultaMedica::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = ConsultaMedicaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     */
    public function store(ConsultaMedicaRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $datos = $request->validated();
            $consulta_medica = ConsultaMedica::create([
                'evolucion' => $datos['evolucion'],
                'examen_fisico' => $datos['examen_fisico'],
                'cita_medica_id' => $datos['cita_medica'],
                'registro_empleado_examen_id' => isset($datos['registro_empleado_examen']) ? $datos['registro_empleado_examen'] : null,
                'dado_alta' => $datos['dado_alta'],
                'dias_descanso' => $datos['dias_descanso'] ?? 0,
                'restricciones_alta' => $datos['restricciones_alta'],
                'observaciones_alta' => $datos['observaciones_alta'],
            ]);

            $consulta_medica->receta()->create([
                'rp' => $datos['receta']['rp'],
                'prescripcion' => $datos['receta']['prescripcion'],
            ]);

            foreach ($datos['diagnosticos'] as $diagnostico) {
                Log::channel('testing')->info('Log', ['diagn', $diagnostico]);
                $consulta_medica->diagnosticosCitaMedica()->create([
                    'recomendacion' => $diagnostico['recomendacion'],
                    'cie_id' => $diagnostico['cie'],
                ]);
            }

            $this->polymorphicMedicoModelsService->crearConstanteVital($consulta_medica, $request->constante_vital);

            // cita atendida
            if ($datos['cita_medica']) {
                $citaMedica = CitaMedica::find($datos['cita_medica']);
                $citaMedica->estado_cita_medica = CitaMedica::ATENDIDO;
                $citaMedica->save();
            }

            $modelo = new ConsultaMedicaResource($consulta_medica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            if ($datos['dias_descanso']) $this->notificarRecursosHumanos($consulta_medica);

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar ' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(ConsultaMedicaRequest $request, ConsultaMedica $consulta_medica)
    {
//        if ($request->isMethod('patch')) {
            $keys = $request->keys();
            unset($keys['id']);
            $consulta_medica->update($request->only($request->keys()));
//        }

//        $datos = $request->validated();
//        $consulta_medica->update([
//            'evolucion' => $datos['evolucion'],
//            'examen_fisico' => $datos['examen_fisico'],
//            'dado_alta' => $datos['dado_alta'],
//            'dias_descanso' => $datos['dias_descanso'] ?? 0,
//            'restricciones_alta' => $datos['restricciones_alta'],
//            'observaciones_alta' => $datos['observaciones_alta'],
//        ]);
        $this->polymorphicMedicoModelsService->crearConstanteVital($consulta_medica, $request->constante_vital);

        $modelo = new ConsultaMedicaResource($consulta_medica->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(ConsultaMedica $consulta_medica)
    {
        $modelo = new ConsultaMedicaResource($consulta_medica);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function destroy($consulta)
    {
        try {
            DB::beginTransaction();
            $consulta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    private function notificarRecursosHumanos($consulta_medica)
    {
        // $emisor = 1;
        $idEmisor = $this->empleadoService->obtenerIdsEmpleadosPorRol(User::ROL_MEDICO)[0];
        $idsDestinatarios = $this->empleadoService->obtenerIdsEmpleadosPorRol(User::ROL_RECURSOS_HUMANOS);

        if (!$idEmisor && !count($idsDestinatarios)) return;

        foreach ($idsDestinatarios as $destinatario) {
            event(new DiasDescansoEvent($consulta_medica, $idEmisor, $destinatario));
        }
    }

    public function indexFiles( ConsultaMedica $consulta_medica)
    {
        try {
            $results = $this->archivoService->listarArchivos($consulta_medica);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws ValidationException|Throwable
     * @throws Throwable
     */
    public function storeFiles(Request $request, ConsultaMedica $consulta_medica)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($consulta_medica, $request['file'], RutasStorage::CONSULTAS_MEDICAS->value, 'CONSULTAS MEDICAS');
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
