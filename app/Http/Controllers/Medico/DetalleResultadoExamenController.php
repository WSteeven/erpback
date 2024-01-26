<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DetalleResultadoExamenRequest;
use App\Http\Resources\Medico\DetalleResultadoExamenResource;
use App\Models\Medico\DetalleResultadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DetalleResultadoExamenController extends Controller
{
    private $entidad = 'Detalle Resultado Examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.detalles_resultados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.detalles_resultados_examenes')->only('store');
        $this->middleware('can:puede.editar.detalles_resultados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.detalles_resultados_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = DetalleResultadoExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(DetalleResultadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $detalle_resultado_examen = DetalleResultadoExamen::create($datos);
            $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen);
            $this->tabla_roles($detalle_resultado_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de detalle de resultados  de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(DetalleResultadoExamenRequest $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen);
        return response()->json(compact('modelo'));
    }


    public function update(DetalleResultadoExamenRequest $request, DetalleResultadoExamen $detalle_resultado_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $detalle_resultado_examen->update($datos);
            $modelo = new DetalleResultadoExamenResource($detalle_resultado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de detalle de resultados  de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
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

}
