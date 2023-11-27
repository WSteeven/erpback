<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\PreordenCompraRequest;
use App\Http\Resources\ComprasProveedores\PreordenCompraResource;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Models\EstadoTransaccion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Config\Autorizaciones;
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
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS, User::ROL_COORDINADOR_BODEGA]))
            $results = PreordenCompra::ignoreRequest(['solicitante_id'])->filter()->orderBy('updated_at', 'desc')->get();
        else
            $results = PreordenCompra::filter()->get();
        $results = PreordenCompraResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Crear preordenes de compras.
     * Este metodo solo se usa para crear manualmente una preorden de compra y consolidando otra.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $items = collect($request->all())->map(function ($item) {
                return [
                    'detalle_id' => $item['detalle_id'],
                    'cantidad' => $item['cantidad'],
                ];
            });
            //Primero se elimina de las preordenes los items que ya se van a crear
            PreordenCompra::eliminarItemsConsolidacion($items->pluck('detalle_id'));
            $preorden = PreordenCompra::create([
                'solicitante_id' => auth()->user()->empleado->id,
                'pedido_id' => null,
                'autorizador_id' => auth()->user()->empleado->id,
                'autorizacion_id' => Autorizaciones::APROBADO,
            ]);

            // guardar los detalles en la preorden
            $preorden->detalles()->sync($items);
            $preorden->auditSync('detalles', $items);

            $modelo = new PreordenCompraResource($preorden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage() . '. ' . $e->getLine();
        }
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
        try {
            $detalles = array_map(function ($detalle) {
                return  [
                    'detalle_id' => $detalle['id'],
                    'cantidad' => $detalle['cantidad'],
                ];
            }, $request->listadoProductos);
            DB::beginTransaction();
            $preorden->detalles()->sync($detalles);
            $preorden->auditSync('detalles', $detalles);

            DB::commit();
            $modelo = new PreordenCompraResource($preorden->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage() . '. ' . $e->getLine();
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

    /**
     * Anular una preorden de compra
     */
    public function anular(Request $request, PreordenCompra $preorden)
    {
        $request->validate(['motivo' => ['required', 'string']]);
        $preorden->causa_anulacion = $request['motivo'];
        $preorden->estado = EstadoTransaccion::ANULADA;
        $preorden->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion en caso de que esté vigente
        $preorden->save();

        $modelo = new PreordenCompraResource($preorden->refresh());
        return response()->json(compact('modelo'));
    }

    public function consolidar()
    {
        $results = [];
        try {
            $results = PreordenCompra::itemsPreordenesPendientes();
        } catch (Exception $e) {
            return response()->json(compact('results'));
        }
        return response()->json(compact('results'));
    }
}
