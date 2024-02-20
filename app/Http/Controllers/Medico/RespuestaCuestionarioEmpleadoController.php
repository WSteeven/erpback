<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RespuestaCuestionarioEmpleadoRequest;
use App\Http\Resources\Medico\RespuestaCuestionarioEmpleadoResource;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\CuestionarioPisicosocialService;
use Src\Shared\Utils;

class RespuestaCuestionarioEmpleadoController extends Controller
{
    private $entidad = 'Respuesta Cuestionario Empleado';

    public function __construct()
    {
        $this->middleware('can:puede.ver.respuestas_cuestionarios_empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.respuestas_cuestionarios_empleados')->only('store');
        $this->middleware('can:puede.editar.respuestas_cuestionarios_empleados')->only('update');
        $this->middleware('can:puede.eliminar.respuestas_cuestionarios_empleados')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = RespuestaCuestionarioEmpleado::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RespuestaCuestionarioEmpleadoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RespuestaCuestionarioEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $cuestionario_pisicosocial_service = new CuestionarioPisicosocialService($request->empleado_id);
            $cuestionario_pisicosocial_service->guardarCuestionario($request->cuestionario);
            $modelo = [];
            $mensaje = 'Gracias por completar el cuestionario.';// Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  RespuestaCuestionarioEmpleado  $respuesta_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function show(RespuestaCuestionarioEmpleado $respuesta_cuestionario_empleado)
    {
        $modelo = new RespuestaCuestionarioEmpleadoResource($respuesta_cuestionario_empleado);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RespuestaCuestionarioEmpleadoRequest  $RespuestaCuestionarioEmpleadoRequest
     * @param  RespuestaCuestionarioEmpleado  $respuesta_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function update(RespuestaCuestionarioEmpleadoRequest $request, RespuestaCuestionarioEmpleado $respuesta_cuestionario_empleado)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $respuesta_cuestionario_empleado->update($datos);
            $modelo = new RespuestaCuestionarioEmpleadoResource($respuesta_cuestionario_empleado->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de RespuestaCuestionarioEmpleado' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RespuestaCuestionarioEmpleado  $respuesta_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(RespuestaCuestionarioEmpleado $respuesta_cuestionario_empleado)
    {
        try {
            DB::beginTransaction();
            $respuesta_cuestionario_empleado->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de RespuestaCuestionarioEmpleado' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
