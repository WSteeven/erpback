<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DetalleExamenRequest;
use App\Http\Resources\Medico\DetalleExamenResource;
use App\Models\Medico\DetalleExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DetalleExamenController extends Controller
{
    private $entidad = 'Detalle Examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.detalles_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.detalles_examenes')->only('store');
        $this->middleware('can:puede.editar.detalles_examenes')->only('update');
        $this->middleware('can:puede.eliminar.detalles_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = DetalleExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(DetalleExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $detalle_examen = DetalleExamen::create($datos);
            $modelo = new DetalleExamenResource($detalle_examen);
            $this->tabla_roles($detalle_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de detalle de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(DetalleExamenRequest $request, DetalleExamen $detalle_examen)
    {
        $modelo = new DetalleExamenResource($detalle_examen);
        return response()->json(compact('modelo'));
    }


    public function update(DetalleExamenRequest $request, DetalleExamen $detalle_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $detalle_examen->update($datos);
            $modelo = new DetalleExamenResource($detalle_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de detalle de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(DetalleExamenRequest $request, DetalleExamen $detalle_examen)
    {
        try {
            DB::beginTransaction();
            $detalle_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de detalle de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

}
