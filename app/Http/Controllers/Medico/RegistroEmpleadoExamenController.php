<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RegistroEmpleadoExamenRequest;
use App\Http\Resources\Medico\RegistroEmpleadoExamenResource;
use App\Models\Medico\RegistroEmpleadoExamen;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $results = [];
        $results = RegistroEmpleadoExamen::ignoreRegistroEmpleadoExamenRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(RegistroEmpleadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $registro_empleado_examen = RegistroEmpleadoExamen::create($datos);
            $modelo = new RegistroEmpleadoExamenResource($registro_empleado_examen);
            $this->tabla_roles($registro_empleado_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
