<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidadRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidadResource;
use App\Models\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidad;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidadService;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;
use Throwable;

class EvaluacionPersonalidadController extends Controller
{

    private string $entidad = 'Evaluacion de Personalidad';

    public function __construct()
    {

    }

    public function index()
    {
        [, $user_type, $user] = ObtenerInstanciaUsuario::tipoUsuario();
        if ($user->hasRole(User::ROL_RECURSOS_HUMANOS)) {
            $results = EvaluacionPersonalidad::ignoreRequest(['campos'])->filter()->get();
        } else {

            if (request('user_id'))
                $results = EvaluacionPersonalidad::ignoreRequest(['campos'])->where('user_type', $user_type)->filter()->orderBy('id', 'desc')->get();
            else
                $results = EvaluacionPersonalidad::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        }
        $results = EvaluacionPersonalidadResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EvaluacionPersonalidadRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(EvaluacionPersonalidadRequest $request)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            //antes de guardar verificar si ya existe una evaluacion para esa postulacion y/o usuario
            $evaluacionRealizada = EvaluacionPersonalidadService::verificarExisteEvaluacionPostulacion($datos['postulacion_id'], true);
//            if ($evaluacionRealizada) throw new Exception('Ya existe una evaluación de personalidad para esta postulación. No se puede crear más.');
            $postulacion = Postulacion::find($datos['postulacion_id']);
            $postulacion->evaluacionPersonalidad()->update(['completado' => true, 'respuestas' => $datos['respuestas'], 'fecha_realizacion' => now()]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        $modelo = new EvaluacionPersonalidadResource($postulacion->evaluacionPersonalidad);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param EvaluacionPersonalidad $evaluacion
     * @return JsonResponse
     */
    public function show(EvaluacionPersonalidad $evaluacion)
    {
        $modelo = new EvaluacionPersonalidadResource($evaluacion);

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
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
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
