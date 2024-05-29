<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DetalleResultadoExamenRequest;
use App\Http\Resources\Medico\DetalleResultadoExamenResource;
use App\Models\Medico\DetalleResultadoExamen;
use App\Models\Medico\ResultadoExamen;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class DetalleResultadoExamenController extends Controller
{
    private $entidad = 'Detalle Resultado Examen';
    private $archivoService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.detalles_resultados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.detalles_resultados_examenes')->only('store');
        $this->middleware('can:puede.editar.detalles_resultados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.detalles_resultados_examenes')->only('destroy');
        $this->archivoService = new ArchivoService();
    }

    public function index()
    {
        $results = [];
        $results = DetalleResultadoExamen::ignoreRequest(['campos'])->filter()->get();
        $results = DetalleResultadoExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(DetalleResultadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $detalle_resultado_examen = DetalleResultadoExamen::create($datos);
            $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen);

            // Log::channel('testing')->info('Log', ['resultados_examenes store', $datos['resultados_examenes']]);

            foreach ($datos['resultados_examenes'] as $resultado_examen) {
                // Log::channel('testing')->info('Log', ['resultado_examen store foreach', $resultado_examen]);
                ResultadoExamen::create([
                    'resultado' => $resultado_examen['resultado'],
                    'configuracion_examen_campo_id' => $resultado_examen['configuracion_examen_campo'],
                    // 'estado_solicitud_examen_id' => $resultado_examen['estado_solicitud_examen'],
                    'detalle_resultado_examen_id' => $detalle_resultado_examen->id,
                    'fecha_examen' => Carbon::now(),
                ]);
            }

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function show(DetalleResultadoExamen $detalle_resultado_examen)
    {
        $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen);
        return response()->json(compact('modelo'));
    }


    public function update(DetalleResultadoExamenRequest $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        try {


            DB::beginTransaction();

            // $resultadoEncontrado = ResultadoExamen::find(15); //where('id', 15)->first();
            // Log::channel('testing')->info('Log', ['resultado_examen encontrado 11', $resultadoEncontrado]);

            $datos = $request->validated();

            $detalle_resultado_examen->update($datos);

            $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            // Eliminar resultados examenes
            // $detalle_resultado_examen->resultadosExamenes()->delete();

            Log::channel('testing')->info('Log', ['resultados_examenes', $datos['resultados_examenes']]);
            foreach ($datos['resultados_examenes'] as $resultado_examen) {
                /*ResultadoExamen::create([
                    'resultado' => $resultado_examen['resultado'],
                    'configuracion_examen_campo_id' => $resultado_examen['configuracion_examen_campo'],
                    // 'estado_solicitud_examen_id' => $datos['estado_solicitud_examen'],
                    'detalle_resultado_examen_id' => $detalle_resultado_examen->id,
                    'fecha_examen' => Carbon::now(),
                ]);*/
                Log::channel('testing')->info('Log', ['resultado_examen store foreach', $resultado_examen]);
                // Log::channel('testing')->info('Log', ['resultado_examen store con flecha', $resultado_examen['id']]);
                // $sql = ResultadoExamen::where('id', 15)->toSql();
                // Log::channel('testing')->info('Log', ['sql', $sql]);

                if (isset($resultado_examen['id'])) {
                    $resultadoEncontrado = ResultadoExamen::find($resultado_examen['id']); //where('id', 15)->first();
                    Log::channel('testing')->info('Log', ['resultado_examen encontrado', $resultadoEncontrado]);

                    $resultadoEncontrado->resultado = $resultado_examen['resultado'];
                    $resultadoEncontrado->save();
                } else {
                    ResultadoExamen::create([
                        'resultado' => $resultado_examen['resultado'],
                        'configuracion_examen_campo_id' => $resultado_examen['configuracion_examen_campo'],
                        'detalle_resultado_examen_id' => $detalle_resultado_examen->id,
                        'fecha_examen' => Carbon::now(),
                    ]);
                }
            }

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function destroy(DetalleResultadoExamenRequest $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        try {
            DB::beginTransaction();
            $detalle_resultado_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de detalle de resultados  de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        try {
            $results = $this->archivoService->listarArchivos($detalle_resultado_examen);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($detalle_resultado_examen, $request->file, RutasStorage::TRANSFERENCIAS_PRODUCTOS_EMPLEADOS->value . '_' . $detalle_resultado_examen->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
