<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\SeguroVehicularRequest;
use App\Http\Resources\Vehiculos\SeguroVehicularResource;
use App\Models\Vehiculos\SeguroVehicular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class SeguroVehicularController extends Controller
{
    private $entidad = 'Seguro';
    public function __construct()
    {
        $this->middleware('can:puede.ver.seguros_vehiculares')->only('index', 'show');
        $this->middleware('can:puede.crear.seguros_vehiculares')->only('store');
        $this->middleware('can:puede.editar.seguros_vehiculares')->only('update');
        $this->middleware('can:puede.eliminar.seguros_vehiculares')->only('destroy');
    }

    public function index()
    {
        $results = SeguroVehicular::filter()->get();
        $results = SeguroVehicularResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(SeguroVehicularRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $seguro = SeguroVehicular::create($datos);
            $modelo = new SeguroVehicularResource($seguro);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar un seguro: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    public function show(SeguroVehicular $seguro)
    {
        $modelo = new SeguroVehicularResource($seguro);
        return response()->json(compact('modelo'));
    }

    public function update(SeguroVehicularRequest $request, SeguroVehicular $seguro)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $seguro->update($datos);
            $modelo = new SeguroVehicularResource($seguro->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el seguro: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }
}
