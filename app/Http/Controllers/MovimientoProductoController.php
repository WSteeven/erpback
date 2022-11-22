<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimientoProductoRequest;
use App\Http\Resources\MovimientoProductoResource;
use App\Models\Inventario;
use App\Models\MovimientoProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class MovimientoProductoController extends Controller
{
    private $entidad = 'Movimiento de Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.movimientos_productos')->only('index', 'show');
        $this->middleware('can:puede.crear.movimientos_productos')->only('store');
        $this->middleware('can:puede.editar.movimientos_productos')->only('update');
        $this->middleware('can:puede.eliminar.movimientos_productos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = MovimientoProductoResource::collection(MovimientoProducto::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida en movimientos', $request->all()]);
        $item = Inventario::findOrFail($request->inventario_id);
        
        //Respuesta
        /* $modelo = MovimientoProducto::create([
            'inventario_id'=> $request->id,
            'transaccion_id'=>
            'cantidad'=>
            'precio_unitario'=>
            'saldo'=>
        ]); */
        // Log::channel('testing')->info('Log', ['Item encontrado en movimientos', $item]);

        $modelo = MovimientoProducto::create([
            'inventario_id'=> $item->id,
            'transaccion_id'=>$request->transaccion_id,
            'cantidad'=>$request->cantidad,
            'precio_unitario'=>$item->detalle->precio_compra,
            'saldo'=>$item->cantidad-$request->cantidad
            // $request->all()
        ]);
        Log::channel('testing')->info('Log', ['Modelo creado?', $modelo]);
        $modelo = new MovimientoProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(MovimientoProducto $movimiento)
    {
        $modelo = new MovimientoProductoResource($movimiento);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(MovimientoProductoRequest $request, MovimientoProducto $movimiento)
    {
        //Respuesta
        $movimiento->update($request->validated());
        $modelo = new MovimientoProductoResource($movimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(MovimientoProducto $movimiento)
    {
        $movimiento->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
