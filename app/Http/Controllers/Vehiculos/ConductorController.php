<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\ConductorRequest;
use App\Http\Resources\Vehiculos\ConductorResource;
use App\Models\Vehiculos\Conductor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConductorController extends Controller
{
    private $entidad = 'Conductor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.conductores')->only('index', 'show');
        $this->middleware('can:puede.crear.conductores')->only('store');
        $this->middleware('can:puede.editar.conductores')->only('update');
        $this->middleware('can:puede.eliminar.conductores')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Conductor::filter()->get();
        $results = ConductorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConductorRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];

            $conductor = Conductor::create($datos);
            $modelo = new ConductorResource($conductor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar un conductor: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function show(Conductor $conductor)
    {
        $modelo = new ConductorResource($conductor);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function update(ConductorRequest $request, Conductor $conductor)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];

            $conductor->update($datos);
            $modelo = new ConductorResource($conductor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el conductor: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conductor $conductor)
    {
        //
    }
}
