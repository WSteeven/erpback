<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\NovedadOrdenCompraRequest;
use App\Http\Resources\ComprasProveedores\NovedadOrdenCompraResource;
use App\Models\ComprasProveedores\NovedadOrdenCompra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class NovedadOrdenCompraController extends Controller
{
    private $entidad = 'Novedad';

    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = NovedadOrdenCompra::filter()->latest()->get();
        $results = NovedadOrdenCompraResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NovedadOrdenCompraRequest $request)
    {
        $datos = $request->validated();
        $datos['orden_compra_id'] = $datos['orden_compra'];

        if ($datos['fotografia']) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_NOVEDADES_ORDENES_COMPRAS))->execute();

        $modelo = new NovedadOrdenCompra();
        $datos['fecha_hora'] = Carbon::parse($datos['fecha_hora'])->format('Y-m-d H:i:s');
        $modelo->fill($datos);
        $modelo->save();

        $modelo = new NovedadOrdenCompraResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComprasProveedores\NovedadOrdenCompra  $novedadOrdenCompra
     * @return \Illuminate\Http\Response
     */
    public function show(NovedadOrdenCompra $novedadOrdenCompra)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComprasProveedores\NovedadOrdenCompra  $novedadOrdenCompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NovedadOrdenCompra $novedadOrdenCompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComprasProveedores\NovedadOrdenCompra  $novedadOrdenCompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(NovedadOrdenCompra $novedadOrdenCompra)
    {
        //
    }


}
