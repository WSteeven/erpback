<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Gasto\AutorizadorDirectoRequest;
use App\Http\Resources\FondosRotativos\Gasto\AutorizadorDirectoResource;
use App\Models\FondosRotativos\Gasto\AutorizadorDirecto;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Shared\Utils;
use Throwable;

class AutorizadorDirectoController extends Controller
{
    private string $entidad = 'Autorizador Directo';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = AutorizadorDirecto::filter()->get();
        $results = AutorizadorDirectoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AutorizadorDirectoRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(AutorizadorDirectoRequest $request)
    {
        try {
            DB::beginTransaction();
            if (AutorizadorDirecto::where('empleado_id', $request->empleado_id)->where('activo', true)->exists())
                throw new Exception('Ya se cuenta con una autorización directa para este empleado, desactiva o modifica dicha autorización');
            $autorizador = AutorizadorDirecto::create($request->validated());
            $modelo = new AutorizadorDirectoResource($autorizador);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param AutorizadorDirecto $autorizador
     * @return JsonResponse
     */
    public function show(AutorizadorDirecto $autorizador)
    {
        $modelo = new AutorizadorDirectoResource($autorizador);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AutorizadorDirectoRequest $request
     * @param AutorizadorDirecto $autorizador
     * @return JsonResponse
     */
    public function update(AutorizadorDirectoRequest $request, AutorizadorDirecto $autorizador)
    {
        $autorizador->update($request->validated());
        $modelo = new AutorizadorDirectoResource($autorizador->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AutorizadorDirecto $autorizador
     * @return JsonResponse
     */
    public function destroy(AutorizadorDirecto $autorizador)
    {
        $autorizador->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
