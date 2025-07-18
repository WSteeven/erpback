<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\ControlPersonal\AtrasoRequest;
use App\Http\Resources\ControlPersonal\AtrasoResource;
use App\Models\ControlPersonal\Atraso;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\App\EmpleadoService;
use Src\App\RecursosHumanos\ControlPersonal\AtrasosService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class AtrasoController extends Controller
{
    public AtrasosService $service;
    public string $entidad = 'Atraso';

    public function __construct()
    {
        $this->service = new AtrasosService();
        $this->middleware('can:puede.ver.atrasos')->only('index', 'show');
        $this->middleware('can:puede.editar.atrasos')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (auth()->user()->hasRole([User::ROL_RECURSOS_HUMANOS, User::ROL_ADMINISTRADOR, User::ROL_CONSULTA])) {
            $results = Atraso::filter()->orderBy('fecha_atraso', 'desc')->get();
        } else {
            $ids_empleados = EmpleadoService::obtenerIdsEmpleadosSubordinadosJefe();
            $results = Atraso::whereIn('empleado_id', $ids_empleados)->orWhere('empleado_id', auth()->user()->empleado->id)->filter()->orderBy('fecha_atraso', 'desc')->get();
        }

        $results = AtrasoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function store()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Atraso $atraso
     * @return JsonResponse
     */
    public function show(Atraso $atraso)
    {
        // Se marca como revisada solo si el jefe inmediato ha visto el registro de atraso
        if ($atraso->empleado->jefe_id === Auth::user()->empleado->id && !$atraso->revisado)
            $atraso->update(['revisado' => true]);

        $modelo = new AtrasoResource($atraso);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AtrasoRequest $request
     * @param Atraso $atraso
     * @return JsonResponse
     */
    public function update(AtrasoRequest $request, Atraso $atraso)
    {
        $datos = $request->validated();

        if ($datos['imagen_evidencia'] && Utils::esBase64($datos['imagen_evidencia'])) {
            $datos['imagen_evidencia'] = (new GuardarImagenIndividual($datos['imagen_evidencia'], RutasStorage::ATRASOS, $atraso->imagen_evidencia))->execute();
        } else {
            unset($datos['imagen_evidencia']);
        }

        $atraso->update($datos);
        $modelo = new AtrasoResource($atraso->refresh());
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
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }


    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function sincronizarAtrasos()
    {
        try {
            $this->service->sincronizarAtrasos();
            return response()->json(['message' => 'Atrasos sincronizados correctamente.']);
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'sincronizarAtrasos');
        }
    }


}
