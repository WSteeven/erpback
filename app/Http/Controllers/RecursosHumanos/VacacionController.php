<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\VacacionRequest;
use App\Http\Resources\RecursosHumanos\VacacionResource;
use App\Models\RecursosHumanos\Vacacion;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class VacacionController extends Controller
{
    private string $entidad = "Vacacion";

    public function __construct()
    {
        $this->middleware('can:puede.ver.vacaciones')->only('index', 'show');
        $this->middleware('can:puede.editar.vacaciones')->only('update');
        $this->middleware('can:puede.eliminar.vacaciones')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (request('tipo')) {
            $results = match (request('tipo')) {
                'PENDIENTES' => Vacacion::where('completadas', false)->get(),
                'REALIZADAS' => Vacacion::where('completadas', true)->get(),
            };
        } else {
            $results = Vacacion::ignoreRequest(['tipo'])->filter()->get();
        }
        $results = VacacionResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Display the specified resource.
     *
     * @param Vacacion $vacacion
     * @return JsonResponse
     */
    public function show(Vacacion $vacacion)
    {
        $modelo = new VacacionResource($vacacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VacacionRequest $request
     * @param Vacacion $vacacion
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function update(VacacionRequest $request, Vacacion $vacacion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $vacacion->update($datos);

            $modelo = new VacacionResource($vacacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        }catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'Actualizar '.$this->entidad);
        }
            return response()->json(compact('mensaje', 'modelo'));
    }

}
