<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\TanqueoRequest;
use App\Http\Resources\Vehiculos\TanqueoResource;
use App\Models\User;
use App\Models\Vehiculos\Tanqueo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class TanqueoController extends Controller
{
    private $entidad = 'Tanqueo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.tanqueos_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.tanqueos_vehiculos')->only('store');
        $this->middleware('can:puede.editar.tanqueos_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.tanqueos_vehiculos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR_VEHICULOS])) {
            $results = Tanqueo::orderBy('id', 'desc')->get($campos);
        } else {
            $results = Tanqueo::where('solicitante_id', auth()->user()->empleado->id)->orderBy('id', 'desc')->get($campos);
        }
        $results = TanqueoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'M');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tanqueo $tanqueo)
    {
        $modelo = new TanqueoResource($tanqueo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TanqueoRequest $request, Tanqueo $tanqueo)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        if ($datos['imagen_comprobante'] && Utils::esBase64($datos['imagen_comprobante'])) {
            Log::channel('testing')->info('Log', ['Grupo asignado ', $request['imagen_comprobante']]);
            $datos['imagen_comprobante'] = (new GuardarImagenIndividual($datos['imagen_comprobante'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        } else unset($datos['imagen_comprobante']);
        if ($datos['imagen_tablero'] && Utils::esBase64($datos['imagen_tablero'])) {
            $datos['imagen_tablero'] = (new GuardarImagenIndividual($datos['imagen_tablero'], RutasStorage::EVIDENCIAS_TANQUEOS_COMBUSTIBLES))->execute();
        } else unset($datos['imagen_tablero']);

        //Respuesta
        $tanqueo->update($datos);
        $modelo = new TanqueoResource($tanqueo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update', 'M');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tanqueo $tanqueo)
    {
        $tanqueo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
