<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ResultadoExamenRequest;
use App\Http\Resources\Medico\ResultadoExamenResource;
use App\Models\Medico\ResultadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\SolicitudExamenService;
use Src\Shared\Utils;

class ResultadoExamenController extends Controller
{
    private $entidad = 'Resultados de examenes';
    private SolicitudExamenService $solicitudExamenService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.resultados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.resultados_examenes')->only('store');
        $this->middleware('can:puede.editar.resultados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.resultados_examenes')->only('destroy');

        $this->solicitudExamenService = new SolicitudExamenService();
    }

    public function index()
    {
        $ids_examenes_solicitados = $this->solicitudExamenService->obtenerIdsExamenesSolicitados(request('registro_empleado_examen_id'));

        $results = ResultadoExamen::ignoreRequest(['campos', 'registro_empleado_examen_id'])->filter()->whereIn('examen_solicitado_id', $ids_examenes_solicitados)->get();
        $results = ResultadoExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ResultadoExamenRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();

            $datosMapeados = array_map(function ($resultadoExamen) {
                $resultadoExamen['configuracion_examen_campo_id'] = $resultadoExamen['configuracion_examen_campo'];
                $resultadoExamen['examen_solicitado_id'] = $resultadoExamen['examen_solicitado'];
                unset($resultadoExamen['configuracion_examen_campo']);
                unset($resultadoExamen['examen_solicitado']);
                return $resultadoExamen;
            }, $datos);

            $resultados_examenes = ResultadoExamen::insert($datosMapeados);

            // Convertir los modelos en recursos para la respuesta JSON
            // $modelos = ResultadoExamenResource::collection($resultados_examenes);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ResultadoExamenRequest $request, ResultadoExamen $resultado_examen)
    {
        $modelo = new ResultadoExamenResource($resultado_examen);
        return response()->json(compact('modelo'));
    }


    public function multipleUpdate(ResultadoExamenRequest $request)
    {
        try {
            DB::beginTransaction();

            $resultados_examenes = $request->validated();

            // consultar los resultados ya ingresados, y si existe actualizar resultado

            // los que no existen agregarlos

            // 'configuracion_examen_campo_id'
            // 'examen_solicitado_id'

            /* foreach ($resultados_examenes as $dato) {
                // Obtener el ID del registro que se actualizará y su resultado
                $id = $dato['id'];
                $resultado = $dato['resultado'];

                // Agregar el ID y el resultado al array de actualizaciones
                $actualizaciones[$id] = $resultado;
            }

            foreach ($actualizaciones as $id => $resultado) {
                ResultadoExamen::where('id', $id)->update(['resultado' => $resultado]);
            } */
            foreach ($resultados_examenes as $dato) {
                // Obtener el ID del registro que se actualizará y su resultado
                $id = $dato['id'];
                $resultado = $dato['resultado'];
                $observaciones = $dato['observaciones'];

                // Verificar si el registro ya existe en la base de datos
                $registroExistente = ResultadoExamen::find($id);
                Log::channel('testing')->info('Log', ['registroExistente', $registroExistente]);

                if ($registroExistente) {
                    // Si el registro existe, actualizar su resultado
                    $registroExistente->update(['resultado' => $resultado, 'observaciones' => $observaciones]);
                } else {
                    // Si el registro no existe, crear uno nuevo
                    ResultadoExamen::create([
                        'resultado' => $resultado,
                        'configuracion_examen_campo_id' => $dato['configuracion_examen_campo'],
                        'examen_solicitado_id' => $dato['examen_solicitado'],
                        'observaciones' => $dato['observaciones'],
                    ]);
                }
            }

            // $resultado_examen->update($datos);
            // $modelo = new ResultadoExamenResource($resultado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ResultadoExamenRequest $request, ResultadoExamen $resultado_examen)
    {
        try {
            DB::beginTransaction();
            $resultado_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
