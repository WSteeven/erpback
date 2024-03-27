<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\VehiculoRequest;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\Vehiculos\Vehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class VehiculoController extends Controller
{
    private $entidad = 'Vehiculo';
    private $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.vehiculos')->only('store');
        $this->middleware('can:puede.editar.vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.vehiculos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $results = Vehiculo::get($campos);
        $results = VehiculoResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VehiculoRequest $request)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();

        //Respuesta
        try {
            $modelo = Vehiculo::create($datos);
            $modelo = new VehiculoResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'M');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al guardar vehiculo', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages(['Error' => Utils::obtenerMensajeError($ex)]);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        $modelo = new VehiculoResource($vehiculo);
        return response()->json(compact('modelo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(VehiculoRequest $request, Vehiculo $vehiculo)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();

        //Respuesta
        $vehiculo->update($datos);
        $modelo = new VehiculoResource($vehiculo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update', 'M');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, Vehiculo $vehiculo)
    {
        try {
            $results = $this->archivoService->listarArchivos($vehiculo);
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, Vehiculo $vehiculo)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($vehiculo, $request->file, RutasStorage::VEHICULOS->value . $vehiculo->placa);
            $mensaje = 'Archivo subido correctamente';

            return response()->json(compact('mensaje', 'modelo'), 200);
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'), 500);
        }
    }
}
