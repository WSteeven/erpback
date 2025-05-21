<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\EstadoClaroRequest;
use App\Http\Resources\Ventas\EstadoClaroResource;
use App\Models\Ventas\EstadoClaro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EstadoClaroController extends Controller
{
    private string $entidad = 'Estado';
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = EstadoClaro::filter()->get();
        EstadoClaroResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EstadoClaroRequest $request
     * @return JsonResponse
     */
    public function store(EstadoClaroRequest $request)
    {
        //Respuesta
        $modelo = EstadoClaro::create($request->validated());
        $modelo = new EstadoClaroResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param EstadoClaro $estado
     * @return JsonResponse
     */
    public function show(EstadoClaro $estado)
    {
        $modelo = new EstadoClaroResource($estado);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EstadoClaroRequest $request
     * @param EstadoClaro $estado
     * @return JsonResponse
     */
    public function update(EstadoClaroRequest $request, EstadoClaro $estado)
    {
        //Respuesta
        $estado->update($request->validated());
        $modelo = new EstadoClaroResource($estado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
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
