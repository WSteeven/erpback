<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ActividadPuestoTrabajoRequest;
use App\Http\Resources\Medico\ActividadPuestoTrabajoResource;
use App\Models\Medico\ActividadPuestoTrabajo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ActividadPuestoTrabajoController extends Controller
{
    private $entidad = 'Actividad de puesto de trabajo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.actividades_puestos_trabajos')->only('index', 'show');
        $this->middleware('can:puede.crear.actividades_puestos_trabajos')->only('store');
        $this->middleware('can:puede.editar.actividades_puestos_trabajos')->only('update');
        $this->middleware('can:puede.eliminar.actividades_puestos_trabajos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ActividadPuestoTrabajo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ActividadPuestoTrabajoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $actividad_puesto_trabajo = ActividadPuestoTrabajo::create($datos);
            $modelo = new ActividadPuestoTrabajoResource($actividad_puesto_trabajo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ActividadPuestoTrabajoRequest $request, ActividadPuestoTrabajo $actividad_puesto_trabajo)
    {
        $modelo = new ActividadPuestoTrabajoResource($actividad_puesto_trabajo);
        return response()->json(compact('modelo'));
    }


    public function update(ActividadPuestoTrabajoRequest $request, ActividadPuestoTrabajo $actividad_puesto_trabajo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $actividad_puesto_trabajo->update($datos);
            $modelo = new ActividadPuestoTrabajoResource($actividad_puesto_trabajo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ActividadPuestoTrabajoRequest $request, ActividadPuestoTrabajo $actividad_puesto_trabajo)
    {
        try {
            DB::beginTransaction();
            $actividad_puesto_trabajo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

}
