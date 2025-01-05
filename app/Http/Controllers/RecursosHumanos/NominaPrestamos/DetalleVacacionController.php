<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\DetalleVacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\DetalleVacacionResource;
use App\Models\RecursosHumanos\NominaPrestamos\DetalleVacacion;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class DetalleVacacionController extends Controller
{
    private string $entidad = "Registro de Vacacion";
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = DetalleVacacion::filter()->get();
        $results = DetalleVacacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DetalleVacacionRequest $request
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function store(DetalleVacacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $detalle = DetalleVacacion::create($datos);
            $modelo = new DetalleVacacionResource($detalle);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        }catch (Exception $exception){
            throw Utils::obtenerMensajeErrorLanzable($exception, 'Guardar Detalle Vacación');
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param DetalleVacacion $detalle
     * @return JsonResponse
     */
    public function show(DetalleVacacion $detalle)
    {
        $modelo = new DetalleVacacionResource($detalle);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DetalleVacacionRequest $request
     * @param DetalleVacacion $detalle
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function update(DetalleVacacionRequest $request, DetalleVacacion $detalle)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $detalle->update($datos);

            $modelo = new DetalleVacacionResource($detalle->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        }catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'Actualizar '.$this->entidad);
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
        throw  ValidationException::withMessages(['error'=>'Método no configurado']);
    }
}
