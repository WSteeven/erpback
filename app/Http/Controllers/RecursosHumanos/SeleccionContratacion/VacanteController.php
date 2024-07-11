<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\VacanteRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\VacanteResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class VacanteController extends Controller
{
    private $entidad = 'Vacante';

    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_vacantes')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_vacantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_vacantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_vacantes')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Vacante::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = VacanteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacanteRequest $request)
    {
        Log::channel('testing')->info('Log', ['request en store', $request->all()]);
        $modelo = [];
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Vacante $vacante)
    {
        $modelo = new VacanteResource($vacante);

        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacanteRequest $request, Vacante $vacante)
    {
        Log::channel('testing')->info('Log', ['request en store', $request->all()]);
        $modelo = [];
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacante $vacante)
    {
        try {
            throw new \Exception('Este método no está disponible, por favor comunicate con el departamento de Informática');
        } catch (\Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
