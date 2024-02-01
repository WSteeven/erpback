<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EstadoSolicitudExamenRequest;
use App\Http\Resources\Medico\EstadoSolicitudExamenResource;
use App\Models\Medico\EstadoSolicitudExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EstadoSolicitudExamenController extends Controller
{
    private $entidad = 'Estado de solicitud de Examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.estados_solicitudes_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.estados_solicitudes_examenes')->only('store');
        $this->middleware('can:puede.editar.estados_solicitudes_examenes')->only('update');
        $this->middleware('can:puede.eliminar.estados_solicitudes_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = EstadoSolicitudExamen::ignoreRequest(['campos'])->filter()->get();
        $results = EstadoSolicitudExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(EstadoSolicitudExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $estado_solicitud_examen = EstadoSolicitudExamen::create($datos);
            $modelo = new EstadoSolicitudExamenResource($estado_solicitud_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estado de solicitud de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EstadoSolicitudExamenRequest $request, EstadoSolicitudExamen $estado_solicitud_examen)
    {
        $modelo = new EstadoSolicitudExamenResource($estado_solicitud_examen);
        return response()->json(compact('modelo'));
    }


    public function update(EstadoSolicitudExamenRequest $request, EstadoSolicitudExamen $estado_solicitud_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $estado_solicitud_examen->update($datos);
            $modelo = new EstadoSolicitudExamenResource($estado_solicitud_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de estado de solicitud de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EstadoSolicitudExamenRequest $request, EstadoSolicitudExamen $estado_solicitud_examen)
    {
        try {
            DB::beginTransaction();
            $estado_solicitud_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de estado de solicitud de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
