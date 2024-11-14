<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\VacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
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
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(/* VacacionRequest $request */)
    {
        throw ValidationException::withMessages([Utils::metodoNoDesarrollado()]);

//        try {
//            DB::beginTransaction();
//            $datos = $request->validated();
//
//            $vacacion = Vacacion::create($datos);
//
//            $modelo = new VacacionResource($vacacion);
//            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
//            DB::commit();
//        } catch (Throwable $th) {
//            DB::rollBack();
//            throw Utils::obtenerMensajeErrorLanzable($th, 'Guardar Vacacion ' . $this->entidad);
//        }
//        return response()->json(compact('mensaje', 'modelo'));
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
        $opto_pago_old = $vacacion->opto_pago;

        $modelo = [];

        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $vacacion->update($datos);
            // Verificamos si cambió el valor de opto_pago para lanzar el mecanismo de que ese pago se realice en Rol de Pagos
            if($opto_pago_old != $vacacion->opto_pago && $vacacion->opto_pago){
                /* TODO: Elaborar el mecanismo para obtener ese pago en el rol de pagos... */

            }


            $modelo = new VacacionResource($vacacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'Actualizar ' . $this->entidad);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

}
