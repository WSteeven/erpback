<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\EmpleadoDelegadoRequest;
use App\Http\Resources\RecursosHumanos\EmpleadoDelegadoResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\EmpleadoDelegado;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EmpleadoDelegadoController extends Controller
{
    private string $entidad= 'EmpleadoDelegado';
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = EmpleadoDelegado::all();
        $results = EmpleadoDelegadoResource::collection($results);
         return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmpleadoDelegadoRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(EmpleadoDelegadoRequest $request)
    {
        $delegacion_activa = Auth::user()->empleado->delegado()->where('activo', true)->first();
        if($delegacion_activa){
            throw new Exception('Ya tienes una delegación activa, vence el '.$delegacion_activa->fecha_hora_hasta);
        }
        $delegacion = EmpleadoDelegado::create($request->validated());
        $modelo = new EmpleadoDelegadoResource($delegacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param EmpleadoDelegado $delegacion
     * @return JsonResponse
     */
    public function show(EmpleadoDelegado $delegacion)
    {
        $modelo = new EmpleadoDelegadoResource($delegacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function update()
    {
        throw ValidationException::withMessages(['error'=>Utils::metodoNoDesarrollado()]);
    }

    /**
     * Sobreescribimos el método para desactivar un empleado delegado.
     *
     * @param EmpleadoDelegado $delegacion
     * @return JsonResponse
     */
    public function destroy(EmpleadoDelegado $delegacion)
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);

    }

    /**
     * @throws ValidationException
     */
    public function desactivar(Empleado $empleado)
    {
        $delegacion = $empleado->delegado()->where('activo', true)->first();
        if(!$delegacion) throw ValidationException::withMessages(['NotFound' => 'No tienes una delegación activa']);
        $delegacion->activo = false;
        $delegacion->save();
        $mensaje = 'Delegación desactivada, a partir de ahora las tareas y tickets serán dirigidas a ti normalmente.';
        return response()->json(compact('mensaje'));

    }
}
