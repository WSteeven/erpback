<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\GarajeRequest;
use App\Http\Resources\Vehiculos\GarajeResource;
use App\Models\Vehiculos\Garaje;
use Exception;
use Src\Shared\Utils;

class GarajeController extends Controller
{
    private $entidad = 'Garaje';
    public function __construct()
    {
        $this->middleware('can:puede.ver.garajes')->only('index', 'show');
        $this->middleware('can:puede.crear.garajes')->only('store');
        $this->middleware('can:puede.editar.garajes')->only('update');
        $this->middleware('can:puede.eliminar.garajes')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Garaje::filter()->orderBy('nombre', 'asc')->get();
        GarajeResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GarajeRequest $request)
    {
        //Respuesta
        $modelo = Garaje::create($request->validated());
        $modelo = new GarajeResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Garaje $garaje)
    {
        $modelo = new GarajeResource($garaje);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GarajeRequest $request, Garaje $garaje)
    {
        //Respuesta
        $garaje->update($request->validated());
        $modelo = new GarajeResource($garaje->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Garaje $garaje)
    {
        try {
            throw new Exception('Este metodo aún no ha sido configurado. Comunicate con el Dept. Informático');
        } catch (\Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
