<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EstadoExamenRequest;
use App\Http\Resources\Medico\EstadoExamenResource;
use App\Models\Medico\EstadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EstadoExamenController extends Controller
{
    private $entidad = 'Estado de Examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.estados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.estados_examenes')->only('store');
        $this->middleware('can:puede.editar.estados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.estados_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = EstadoExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(EstadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $estado_examen = EstadoExamen::create($datos);
            $modelo = new EstadoExamenResource($estado_examen);
            $this->tabla_roles($estado_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EstadoExamenRequest $request, EstadoExamen $estado_examen)
    {
        $modelo = new EstadoExamenResource($estado_examen);
        return response()->json(compact('modelo'));
    }


    public function update(EstadoExamenRequest $request, EstadoExamen $estado_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $estado_examen->update($datos);
            $modelo = new EstadoExamenResource($estado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de estado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EstadoExamenRequest $request, EstadoExamen $estado_examen)
    {
        try {
            DB::beginTransaction();
            $estado_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de estado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
