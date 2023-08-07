<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\PreordenCompraRequest;
use App\Http\Resources\ComprasProveedores\PreordenCompraResource;
use App\Models\ComprasProveedores\PreordenCompra;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

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
    public function index(Request $request)
    {
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

    /**
     * Actualizar
     */
    public function update(PreordenCompraRequest $request, PreordenCompra $preorden)
    {
        Log::channel('testing')->info('Log', ['entro en el update del pedido', $request->all()]);
        Log::channel('testing')->info('Log', ['preorden para actualizar', $request->listadoProductos]);
        $detalles = array_map(function ($detalle) {
            return  [
                'detalle_id' => $detalle['id'],
                'cantidad' => $detalle['cantidad'],
            ];
        }, $request->listadoProductos);
        Log::channel('testing')->info('Log', ['preorden mapeada', $detalles]);
        try {
            DB::beginTransaction();
            foreach ($request->listadoProductos as $detalle) {
                Log::channel('testing')->info('Log', ['detalle', $detalle]);
            }
            $preorden->detalles()->sync($detalles);

            DB::commit();
            $modelo = new PreordenCompraResource($preorden->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Ocurrió un error al actualizar la preorden compra', $e->getMessage(), $e->getLine()]);
        }
    }

    /**
     * Consultar datos sin el método show
     */
    public function showPreview(PreordenCompra $preorden)
    {
        $modelo = new PreordenCompraResource($preorden);
        return response()->json(compact('modelo'));
    }
}
