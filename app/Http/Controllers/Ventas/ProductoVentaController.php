<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ProductoVentaRequest;
use App\Http\Resources\Ventas\ProductoVentaResource;
use App\Models\Ventas\ProductoVenta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ProductoVentaController extends Controller
{
    private $entidad = 'Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.productos_ventas')->only('store');
        $this->middleware('can:puede.editar.productos_ventas')->only('update');
        $this->middleware('can:puede.eliminar.productos_ventas')->only('destroy');
    }


    public function index(Request $request)
    {
        $results = [];
        $results = ProductoVenta::ignoreRequest(['campos'])->filter()->orderBy('plan_id', 'asc')->get();
        $results = ProductoVentaResource::collection($results);
        return response()->json(compact('results'));
    }
    
    
    
    public function store(ProductoVentaRequest $request)
    {
        try {
            DB::beginTransaction();
            $producto = ProductoVenta::create($request->validated());
            $modelo = new ProductoVentaResource($producto);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
    
    
    
    public function show(ProductoVenta $producto)
    {
        $modelo = new ProductoVentaResource($producto);
        return response()->json(compact('modelo'));
    }



    public function update(ProductoVentaRequest $request, ProductoVenta $producto)
    {
        try {
            DB::beginTransaction();
            $producto->update($request->validated());
            $modelo = new ProductoVentaResource($producto->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages([
                'Error al actualizar registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function destroy(Request $request, ProductoVenta $producto)
    {
        $producto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * desactivar
     */
    public function desactivar(ProductoVenta $producto)
    {
        $producto->activo = !$producto->activo;
        $producto->save();
        $modelo  = new ProductoVentaResource($producto->refresh());
        return response()->json(compact('modelo'));
    }
}
