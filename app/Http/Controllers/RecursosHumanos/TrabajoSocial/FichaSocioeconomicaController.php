<?php

namespace App\Http\Controllers\RecursosHumanos\TrabajoSocial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaRequest;
use App\Http\Resources\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class FichaSocioeconomicaController extends Controller
{
    private string $entidad = 'Ficha Socioeconomica';
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
//        $results = FichaSocioeconomica::filter()->get();
        $results = FichaSocioeconomica::all();
        $results = FichaSocioeconomicaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FichaSocioeconomicaRequest $request
     * @return JsonResponse
     */
    public function store(FichaSocioeconomicaRequest $request)
    {
        $datos = $request->validated();
        $modelo = FichaSocioeconomica::create($datos);
        $modelo = new FichaSocioeconomicaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param FichaSocioeconomica $ficha
     * @return JsonResponse
     */
    public function show(FichaSocioeconomica $ficha)
    {
        $modelo = new FichaSocioeconomicaResource($ficha);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FichaSocioeconomicaRequest $request
     * @param FichaSocioeconomica $ficha
     * @return JsonResponse
     */
    public function update(FichaSocioeconomicaRequest $request,FichaSocioeconomica $ficha)
    {
        $ficha->update($request->validated());
        $modelo = new FichaSocioeconomicaResource($ficha->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
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

    public function empleadoTieneFichaSocioeconomica(Empleado $empleado)
    {
        $result = $empleado->fichaSocioeconomica()->exists();
        return response()->json(compact('result'));
    }

    /**
     * @throws ValidationException
     */
    public function ultimaFichaEmpleado(Empleado $empleado)
    {
        if ($empleado->fichaSocioeconomica()->exists()) {
            $ficha = $empleado->fichaSocioeconomica()->first();
            $modelo = new FichaSocioeconomicaResource($ficha);
            return response()->json(compact('modelo'));
        } else throw ValidationException::withMessages(['NotFound' => 'El empleado aún no tiene una ficha socioeconomica registrada']);
    }
}
