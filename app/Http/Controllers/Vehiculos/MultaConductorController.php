<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\MultaConductorRequest;
use App\Http\Resources\Vehiculos\MultaConductorResource;
use App\Models\Vehiculos\MultaConductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class MultaConductorController extends Controller
{
    private $entidad = 'Multa';
    public function __construct()
    {
        $this->middleware('can:puede.ver.multas_conductores')->only('index', 'show');
        $this->middleware('can:puede.crear.multas_conductores')->only('store');
        $this->middleware('can:puede.editar.multas_conductores')->only('update');
        $this->middleware('can:puede.eliminar.multas_conductores')->only('destroy');
    }


    public function index()
    {
        $results = MultaConductor::filter()->get();
        $results = MultaConductorResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(MultaConductorRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];

            $multa = MultaConductor::create($datos);
            $modelo = new MultaConductorResource($multa);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar la multa del conductor: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }


    public function show(MultaConductor $multa)
    {
        $modelo = new MultaConductorResource($multa);
        return response()->json(compact('modelo'));
    }

    public function update(MultaConductorRequest $request, MultaConductor $multa)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];

            $multa->update($datos);
            $modelo = new MultaConductorResource($multa);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar la multa del conductor: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }
}
