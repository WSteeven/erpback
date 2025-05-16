<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\NodoRequest;
use App\Http\Resources\Tareas\NodoResource;
use App\Models\Tareas\Nodo;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class NodoController extends Controller
{
    private string $entidad = "Nodo";


    public function __construct()
    {
        $this->middleware('can:puede.ver.nodos')->only('index', 'show');
        $this->middleware('can:puede.crear.nodos')->only('store');
        $this->middleware('can:puede.editar.nodos')->only('update');
        $this->middleware('can:puede.eliminar.nodos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Nodo::filter()->get();
        $results = NodoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NodoRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(NodoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $nodo = Nodo::create($datos);

            $modelo = new NodoResource($nodo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e, 'store');
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Nodo $nodo
     * @return JsonResponse
     */
    public function show(Nodo $nodo)
    {
        $modelo = new NodoResource($nodo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NodoRequest $request
     * @param Nodo $nodo
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(NodoRequest $request, Nodo $nodo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $nodo->update($datos);

            $modelo = new NodoResource($nodo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e, 'update');
        }
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
        throw ValidationException::withMessages(['error'=>Utils::metodoNoDesarrollado()]);
    }
}
