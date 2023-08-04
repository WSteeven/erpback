<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\PreordenCompraResource;
use App\Models\ComprasProveedores\PreordenCompra;
use Illuminate\Http\Request;

class PreordenCompraController extends Controller
{
    private $entidad = 'Preorden de compra';
    public function __construct()
    {
        $this->middleware('can:puede.ver.preordenes_compras')->only('index', 'show');
        $this->middleware('can:puede.crear.preordenes_compras')->only('store');
        $this->middleware('can:puede.editar.preordenes_compras')->only('update');
        $this->middleware('can:puede.eliminar.preordenes_compras')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request){
        $results = PreordenCompra::all();
        $results = PreordenCompraResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Consultar
     */
    public function show(PreordenCompra $preorden)
    {
        $modelo = new PreordenCompraResource($preorden);
        return response()->json(compact('modelo'));
    }

}
