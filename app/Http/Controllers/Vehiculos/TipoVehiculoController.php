<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\TipoVehiculoRequest;
use App\Http\Resources\Vehiculos\TipoVehiculoResource;
use App\Models\Vehiculos\TipoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoVehiculoController extends Controller
{
    private $entidad = 'Tipo de Vehículo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_vehiculos')->only('store');
        $this->middleware('can:puede.editar.tipos_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.tipos_vehiculos')->only('destroy');
    }

    public function index()
    {
        $results = TipoVehiculo::filter()->orderBy('nombre', 'asc')->get();
        $results = TipoVehiculoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(TipoVehiculoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $tipo = TipoVehiculo::create($datos);
            $modelo = new TipoVehiculoResource($tipo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'Error al guardar: ')]);
        }
    }

    public function show(TipoVehiculo $tipo)
    {
        $modelo = new TipoVehiculoResource($tipo);
        return response()->json(compact('modelo'));
    }


    public function update(TipoVehiculoRequest $request, TipoVehiculo $tipo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $tipo->update($datos);
            $modelo = new TipoVehiculoResource($tipo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'Error al actualizar: ')]);
        }
    }

    public function destroy(TipoVehiculo $tipo)
    {
        if ($tipo->vehiculos()->count() == 0) {
            $tipo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            return response()->json(compact('mensaje'));
        }
        throw ValidationException::withMessages(['Error' => 'No se puede eliminar este ítem, por favor verifica que esté registrado en algún vehículo.']);
    }
}
