<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\TanqueoRequest;
use App\Http\Resources\Vehiculos\TanqueoResource;
use App\Models\User;
use App\Models\Vehiculos\Tanqueo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\Vehiculos\TanqueoVehiculoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class TanqueoController extends Controller
{
    private string $entidad = 'Tanqueo';
    private TanqueoVehiculoService $servicio;
    public function __construct()
    {
        $this->servicio = new TanqueoVehiculoService();
        $this->middleware('can:puede.ver.tanqueos_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.tanqueos_vehiculos')->only('store');
        $this->middleware('can:puede.editar.tanqueos_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.tanqueos_vehiculos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR_VEHICULOS])) {
            $results = Tanqueo::orderBy('id', 'desc')->filter()->get($campos);
        } else {
            $results = Tanqueo::where('solicitante_id', auth()->user()->empleado->id)->orderBy('id', 'desc')->get($campos);
        }
        $results = TanqueoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TanqueoRequest $request
     * @return JsonResponse
     */
    public function store(TanqueoRequest $request)
    {
        $datos = $request->validated();
        if ($datos['imagen_comprobante']) {
            $datos['imagen_comprobante'] = (new GuardarImagenIndividual($datos['imagen_comprobante'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        }
        if ($datos['imagen_tablero']) {
            $datos['imagen_tablero'] = (new GuardarImagenIndividual($datos['imagen_tablero'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        }

        try {
            DB::beginTransaction();
            $tanqueo = Tanqueo::create($datos);
            $modelo = new TanqueoResource($tanqueo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Tanqueo $tanqueo
     * @return JsonResponse
     */
    public function show(Tanqueo $tanqueo)
    {
        $modelo = new TanqueoResource($tanqueo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TanqueoRequest $request
     * @param Tanqueo $tanqueo
     * @return JsonResponse
     */
    public function update(TanqueoRequest $request, Tanqueo $tanqueo)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        if ($datos['imagen_comprobante'] && Utils::esBase64($datos['imagen_comprobante'])) {
            $datos['imagen_comprobante'] = (new GuardarImagenIndividual($datos['imagen_comprobante'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        } else unset($datos['imagen_comprobante']);
        if ($datos['imagen_tablero'] && Utils::esBase64($datos['imagen_tablero'])) {
            $datos['imagen_tablero'] = (new GuardarImagenIndividual($datos['imagen_tablero'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        } else unset($datos['imagen_tablero']);

        //Respuesta
        $tanqueo->update($datos);
        $modelo = new TanqueoResource($tanqueo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tanqueo $tanqueo
     * @return JsonResponse
     */
    public function destroy(Tanqueo $tanqueo)
    {
        $tanqueo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function reporteCombustibles(Request $request)
    {
        $results = $this->servicio->dashboard($request);

        return response()->json(compact('results'));
    }
}
