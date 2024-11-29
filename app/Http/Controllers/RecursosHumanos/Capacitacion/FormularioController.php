<?php

namespace App\Http\Controllers\RecursosHumanos\Capacitacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Capacitacion\FormularioRequest;
use App\Http\Resources\RecursosHumanos\Capacitacion\FormularioResource;
use App\Models\RecursosHumanos\Capacitacion\Formulario;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;

class FormularioController extends Controller
{
    private string $entidad = 'Formulario';
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Formulario::all();
        $results = FormularioResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FormularioRequest $request
     * @return JsonResponse
     */
    public function store(FormularioRequest $request)
    {
        $modelo = Formulario::create($request->validated());
        $modelo = new FormularioResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Formulario $formulario
     * @return JsonResponse
     */
    public function show(Formulario $formulario)
    {
        $modelo = new FormularioResource($formulario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormularioRequest $request
     * @param Formulario $formulario
     * @return JsonResponse
     */
    public function update(FormularioRequest $request, Formulario $formulario)
    {
        //Respuesta
        $formulario->update($request->validated());
        $modelo = new FormularioResource($formulario->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Formulario $formulario
     * @return JsonResponse
     */
    public function destroy(Formulario $formulario)
    {
        $formulario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
