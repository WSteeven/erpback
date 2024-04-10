<?php

namespace App\Http\Controllers\Vehiculos;

use App\Events\Vehiculos\NotificarOrdenInternaAlAdminVehiculos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\OrdenReparacionRequest;
use App\Http\Resources\Vehiculos\OrdenReparacionResource;
use App\Models\Vehiculos\OrdenReparacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class OrdenReparacionController extends Controller
{
    private $entidad = 'Orden de Reparación';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ordenes_reparaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.ordenes_reparaciones')->only('store');
        $this->middleware('can:puede.editar.ordenes_reparaciones')->only('update');
        $this->middleware('can:puede.eliminar.ordenes_reparaciones')->only('destroy');
    }

    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $results = OrdenReparacion::get($campos);
        $results = OrdenReparacionResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(OrdenReparacionRequest $request)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $orden = OrdenReparacion::create($datos);
            event(new NotificarOrdenInternaAlAdminVehiculos($orden));
            $modelo = new OrdenReparacionResource($orden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(OrdenReparacion $orden)
    {
        $modelo = new OrdenReparacionResource($orden);
        return response()->json(compact('modelo'));
    }

    public function update(OrdenReparacionRequest $request, OrdenReparacion $orden)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $orden->update($datos);
            event(new NotificarOrdenInternaAlAdminVehiculos($orden));
            $modelo = new OrdenReparacionResource($orden->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update', 'M');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
