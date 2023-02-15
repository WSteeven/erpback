<?php

namespace App\Http\Controllers;

use App\Http\Requests\CargoRequest;
use App\Http\Resources\CargoResource;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CargoController extends Controller
{
    private $entidad = 'Cargo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.cargos')->only('index', 'show');
        $this->middleware('can:puede.crear.cargos')->only('store');
        $this->middleware('can:puede.editar.cargos')->only('update');
        $this->middleware('can:puede.eliminar.cargos')->only('update');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Cargo::filter()->get();
        $results = CargoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoRequest $request)
    {
        //Respuesta
        $modelo = Cargo::create($request->validated());
        $modelo = new CargoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Cargo $cargo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cargo $cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cargo $cargo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cargo $cargo)
    {
        //
    }
}
