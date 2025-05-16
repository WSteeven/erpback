<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\ModalidadRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\ModalidadResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Modalidad;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;

class ModalidadController extends Controller
{
    private string $entidad = 'Modalidad';
    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_modalidades_trabajo')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_modalidades_trabajo')->only('store');
        $this->middleware('can:puede.editar.rrhh_modalidades_trabajo')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_modalidades_trabajo')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Modalidad::filter()->orderBy('nombre')->get();
        $results = ModalidadResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ModalidadRequest $request
     * @return JsonResponse
     */
    public function store(ModalidadRequest $request)
    {
        //Respuesta
        $modelo = Modalidad::create($request->validated());
        $modelo = new ModalidadResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Modalidad $modalidad
     * @return JsonResponse
     */
    public function show(Modalidad $modalidad)
    {
        $modelo = new ModalidadResource($modalidad);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ModalidadRequest $request
     * @param Modalidad $modalidad
     * @return JsonResponse
     */
    public function update(ModalidadRequest $request, Modalidad $modalidad)
    {
        //Respuesta
        $modalidad->update($request->validated());
        $modelo = new ModalidadResource($modalidad->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Modalidad $modalidad
     * @return JsonResponse
     */
    public function destroy(Modalidad $modalidad)
    {
        $modalidad->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
