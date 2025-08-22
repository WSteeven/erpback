<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BaseComisionRequest;
use App\Http\Resources\Ventas\BaseComisionResource;
use App\Models\Ventas\BaseComision;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class BaseComisionController extends Controller
{
    private string $entidad = 'Comision';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = BaseComision::ignoreRequest(['campos'])->filter()->get();
        $results = BaseComisionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BaseComisionRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(BaseComisionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            if (BaseComision::where('modalidad_id', $request->modalidad_id)->exists())
                throw new Exception("Ya existe una base comisional para la modalidad seleccionado. Por favor modifica la existente");

            $base = BaseComision::create($datos);

            $modelo = new BaseComisionResource($base);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param BaseComision $base
     * @return JsonResponse
     */
    public function show(BaseComision $base)
    {
        $modelo = new BaseComisionResource($base);

        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BaseComisionRequest $request
     * @param BaseComision $base
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(BaseComisionRequest $request, BaseComision $base)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $base->update($datos);
            $modelo = new BaseComisionResource($base->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
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
