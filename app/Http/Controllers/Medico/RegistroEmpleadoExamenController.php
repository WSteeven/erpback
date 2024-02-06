<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RegistroEmpleadoExamenRequest;
use App\Http\Resources\Medico\RegistroEmpleadoExamenResource;
use App\Models\Medico\RegistroEmpleadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RegistroEmpleadoExamenController extends Controller
{
    private $entidad = 'Registros de examenes de Empleado';

    public function __construct()
    {
        $this->middleware('can:puede.ver.registros_empleados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.registros_empleados_examenes')->only('store');
        $this->middleware('can:puede.editar.registros_empleados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.registros_empleados_examenes')->only('destroy');
    }

    public function index()
    {
        // $results = [];
        $results = RegistroEmpleadoExamen::ignoreRequest(['campos'])->filter()->get();
        $results = RegistroEmpleadoExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(RegistroEmpleadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            Log::channel('testing')->info('Log', ['Datos registro', $datos]);

            $proximoNumeroRegistro = RegistroEmpleadoExamen::where('tipo_proceso_examen', $datos['tipo_proceso_examen'])->where('empleado_id', $datos['empleado_id'])->count() + 1;
            $datos['numero_registro'] = $proximoNumeroRegistro;

            $registro_empleado_examen = RegistroEmpleadoExamen::create($datos);
            $modelo = new RegistroEmpleadoExamenResource($registro_empleado_examen);
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

    public function show(RegistroEmpleadoExamenRequest $request, RegistroEmpleadoExamen $registro_empleado_examen)
    {
        $modelo = new RegistroEmpleadoExamenResource($registro_empleado_examen);
        return response()->json(compact('modelo'));
    }


    public function update(RegistroEmpleadoExamenRequest $request, RegistroEmpleadoExamen $registro_empleado_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $registro_empleado_examen->update($datos);
            $modelo = new RegistroEmpleadoExamenResource($registro_empleado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de registro empleado examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(RegistroEmpleadoExamenRequest $request, RegistroEmpleadoExamen $registro_empleado_examen)
    {
        try {
            DB::beginTransaction();
            $registro_empleado_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de registro empleado examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
