<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\ConductorRequest;
use App\Http\Resources\Vehiculos\ConductorResource;
use App\Models\Vehiculos\Conductor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ConductorController extends Controller
{
    private $entidad = 'Conductor';
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $results = Conductor::all();
        $results = [];
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
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar un conductor: ' . $e->getMessage();
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conductor $conductor)
    {
        //
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
