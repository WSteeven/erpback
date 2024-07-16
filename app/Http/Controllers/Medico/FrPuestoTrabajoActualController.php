<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FrPuestoTrabajoActualRequest;
use App\Http\Resources\Medico\FrPuestoTrabajoActualResource;
use App\Models\Medico\FrPuestoTrabajoActual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class FrPuestoTrabajoActualController extends Controller
{
    private $entidad = 'Actividad de puesto de trabajo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fr_puesto_trabajo_actual')->only('index', 'show');
        $this->middleware('can:puede.crear.fr_puesto_trabajo_actual')->only('store');
        $this->middleware('can:puede.editar.fr_puesto_trabajo_actual')->only('update');
        $this->middleware('can:puede.eliminar.fr_puesto_trabajo_actual')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = FrPuestoTrabajoActual::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(FrPuestoTrabajoActualRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $actividad_puesto_trabajo = FrPuestoTrabajoActual::create($datos);
            $modelo = new FrPuestoTrabajoActualResource($actividad_puesto_trabajo);
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

    public function show(FrPuestoTrabajoActualRequest $request, FrPuestoTrabajoActual $actividad_puesto_trabajo)
    {
        $modelo = new FrPuestoTrabajoActualResource($actividad_puesto_trabajo);
        return response()->json(compact('modelo'));
    }


    public function update(FrPuestoTrabajoActualRequest $request, FrPuestoTrabajoActual $actividad_puesto_trabajo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $actividad_puesto_trabajo->update($datos);
            $modelo = new FrPuestoTrabajoActualResource($actividad_puesto_trabajo->refresh());
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

    public function destroy(FrPuestoTrabajoActualRequest $request, FrPuestoTrabajoActual $actividad_puesto_trabajo)
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
