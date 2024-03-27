<?php

namespace App\Http\Controllers\Vehiculos;

use App\Events\Vehiculos\NotificarMultaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\MultaConductorRequest;
use App\Http\Resources\Vehiculos\MultaConductorResource;
use App\Models\Vehiculos\Conductor;
use App\Models\Vehiculos\MultaConductor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $results = MultaConductor::filter()->orderBy('id', 'desc')->get();
        $results = MultaConductorResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(MultaConductorRequest $request)
    {
        Log::channel('testing')->info('Log', ['request', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];
            // throw new Exception("error");
            $multa = MultaConductor::create($datos);
            if ($multa->descontable) event(new NotificarMultaEvent($multa));
            $modelo = new MultaConductorResource($multa);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            //Se modifican los puntos del conductor segun la multa registrada
            $conductor = Conductor::find($multa->empleado_id);
            $conductor->puntos = $conductor->puntos - $multa->puntos;
            $conductor->save();
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

    public function destroy(MultaConductor $multa){
        //Primero aumentamos los puntos de la multa eliminada a la licencia de la persona.
        $conductor = Conductor::find($multa->empleado_id);
        $conductor->puntos = $conductor->puntos + $multa->puntos;
        $conductor->save();

        $multa->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function pagar(Request $request, MultaConductor $multa)
    {
        $request->validate([
            'fecha_pago' => ['required', 'string'],
            'comentario' => ['nullable', 'string'],
        ]);
        if (!$multa->estado) {
            $multa->estado = true;
            $multa->fecha_pago = date('Y-m-d', strtotime($request['fecha_pago']));
            $multa->comentario = $request['comentario'];
            $multa->save();

            // //Esto se refresca cuando se guarda cambios del conductor
            // $conductor = Conductor::find($multa->empleado_id);
            // $conductor->puntos = $conductor->puntos - $multa->puntos;
            // $conductor->save();
        }

        $modelo = new MultaConductorResource($multa->refresh());
        $mensaje = 'Multa actualizada correctamente';
        return response()->json(compact('modelo', 'mensaje'));
    }
}
