<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\DetalleViaticoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class DetalleViaticoController extends Controller
{
    private $entidad = 'sub_detalle_viatico';
    public function __construct()
    {
        $this->middleware('can:puede.ver.detalle_fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.detalle_fondo')->only('store');
        $this->middleware('can:puede.editar.detalle_fondo')->only('update');
        $this->middleware('can:puede.eliminar.detalle_fondo')->only('update');
    }
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];

        $results = DetalleViatico::ignoreRequest(['campos'])->filter()->get();
        $results = DetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(DetalleViatico $viatico)
    {
        $modelo = new DetalleViaticoResource($viatico);
        return response()->json(compact('modelo'), 200);
    }


    public function destroy(DetalleViatico $viatico)
    {
        $viatico->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));

    }
}
