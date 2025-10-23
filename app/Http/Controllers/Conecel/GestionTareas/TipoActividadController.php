<?php

namespace App\Http\Controllers\Conecel\GestionTareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Conecel\GestionTareas\TipoActividadRequest;
use App\Http\Resources\Conecel\GestionTareas\TipoActividadResource;
use App\Http\Resources\ProductoResource;
use App\Models\Conecel\GestionTareas\TipoActividad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoActividadController extends Controller
{
    private string $entidad = 'Tipo de Actividad';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_actividades_conecel')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_actividades_conecel')->only('store');
        $this->middleware('can:puede.editar.tipos_actividades_conecel')->only('update');
        $this->middleware('can:puede.eliminar.tipos_actividades_conecel')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = TipoActividad::filter()->get();
        $results = TipoActividadResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TipoActividadRequest $request
     * @return JsonResponse
     */
    public function store(TipoActividadRequest $request)
    {
        $datos = $request->validated();

        // Respuesta
        $modelo = TipoActividad::create($datos);
        $modelo = new ProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param TipoActividad $tipo
     * @return JsonResponse
     */
    public function show(TipoActividad $tipo)
    {
        $modelo = new TipoActividadResource($tipo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TipoActividad $tipo
     * @return JsonResponse
     */
    public function update(Request $request, TipoActividad $tipo)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();

        // Respuesta
        $tipo->update($datos);
        $modelo = new ProductoResource($tipo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }
}
