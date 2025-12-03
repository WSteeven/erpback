<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecursosHumanos\CargoRequest;
use App\Http\Resources\CargoResource;
use App\Models\Cargo;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;

class CargoController extends Controller
{
    private string $entidad = 'Cargo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.cargos')->only('index', 'show');
        $this->middleware('can:puede.crear.cargos')->only('store');
        $this->middleware('can:puede.editar.cargos')->only('update');
        $this->middleware('can:puede.eliminar.cargos')->only('update');
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Cargo::filter()->get();
        $results = CargoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CargoRequest $request
     * @return JsonResponse
     */
    public function store(CargoRequest $request)
    {
        //Respuesta
        $modelo = Cargo::create($request->validated());
        $modelo = new CargoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Cargo $cargo
     * @return JsonResponse
     */
    public function show(Cargo $cargo)
    {
        $modelo = new CargoResource($cargo);
        return response()->json(compact('modelo'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param CargoRequest $request
     * @param Cargo $cargo
     * @return JsonResponse
     */
    public function update(CargoRequest $request, Cargo $cargo)
    {
        //Respuesta
        $cargo->update($request->validated());
        $modelo = new CargoResource($cargo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cargo $cargo
     * @return JsonResponse
     */
    public function destroy(Cargo $cargo)
    {
        $cargo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
