<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\ConocimientoRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\ConocimientoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Conocimiento;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class ConocimientoController extends Controller
{
    private string $entidad = 'Conocimiento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_areas_conocimientos')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_areas_conocimientos')->only('store');
        $this->middleware('can:puede.editar.rrhh_areas_conocimientos')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_areas_conocimientos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Conocimiento::filter()->orderBy('nombre')->get();
        $results = ConocimientoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ConocimientoRequest $request
     * @return JsonResponse
     */
    public function store(ConocimientoRequest $request)
    {
        //Respuesta
        $modelo = Conocimiento::create($request->validated());
        $modelo = new ConocimientoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Conocimiento $conocimiento
     * @return JsonResponse
     */
    public function show(Conocimiento $conocimiento)
    {
        $modelo = new ConocimientoResource($conocimiento);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ConocimientoRequest $request
     * @param Conocimiento $conocimiento
     * @return JsonResponse
     */
    public function update(ConocimientoRequest $request, Conocimiento $conocimiento)
    {
        //Respuesta
        $conocimiento->update($request->validated());
        $modelo = new ConocimientoResource($conocimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Conocimiento $conocimiento
     * @return void
     * @throws ValidationException
     */
    public function destroy(Conocimiento $conocimiento)
    {
        try {
            throw new Exception('Este método no está disponible, por favor comunicate con el departamento de Informática');
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
