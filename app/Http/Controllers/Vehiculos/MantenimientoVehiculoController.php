<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\MantenimientoVehiculoRequest;
use App\Http\Resources\Vehiculos\MantenimientoVehiculoResource;
use App\Models\Vehiculos\MantenimientoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\App\ArchivoService;
use Src\Shared\Utils;

class MantenimientoVehiculoController extends Controller
{
    private $entidad = 'Mantenimiento';
    private $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.mantenimientos_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.mantenimientos_vehiculos')->only('store');
        $this->middleware('can:puede.editar.mantenimientos_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.mantenimientos_vehiculos')->only('destroy');
    }

    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        //  $results = Vehiculo::get($campos);
        $results = MantenimientoVehiculo::filter()->get();
        $results = MantenimientoVehiculoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function show(MantenimientoVehiculo $mantenimiento)
    {
        $modelo = new MantenimientoVehiculoResource($mantenimiento);
        return response()->json(compact('modelo'));
    }

    public function update(MantenimientoVehiculoRequest $request, MantenimientoVehiculo $mantenimiento)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();

        //Respuesta
        $mantenimiento->update($datos);
        $modelo = new MantenimientoVehiculoResource($mantenimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update', 'M');

        return response()->json(compact('mensaje', 'modelo'));
    }




}
