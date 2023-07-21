<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdenCompraResource;
use App\Models\OrdenCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdenCompraController extends Controller
{
    private $entidad = 'Orden de compra';
    /* public function __construct()
    {
        $this->middleware('can:puede.ver.pedidos')->only('index', 'show');
        $this->middleware('can:puede.crear.pedidos')->only('store');
        $this->middleware('can:puede.editar.pedidos')->only('update');
        $this->middleware('can:puede.eliminar.pedidos')->only('destroy');
    } */

    /**
     * Listar
     */
    public function index(Request $request){
        Log::channel('testing')->info('Log', ['Es empleado:', $request->all()]);
        $results = OrdenCompra::filter()->get();
        $results = OrdenCompraResource::collection($results);
        return response()->json(compact('results'));
    }

    
}
