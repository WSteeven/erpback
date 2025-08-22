<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ProductoVentaRequest;
use App\Http\Resources\Ventas\ProductoVentaResource;
use App\Imports\VentasClaro\ProductosVentasClaroImport;
use App\Models\Ventas\ProductoVenta;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class ProductoVentaController extends Controller
{
    private string $entidad = 'Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos_ventas')->only('index', 'show');
        $this->middleware('can:puede.crear.productos_ventas')->only('store');
        $this->middleware('can:puede.editar.productos_ventas')->only('update');
        $this->middleware('can:puede.eliminar.productos_ventas')->only('destroy');
    }


    public function index()
    {
        $results = ProductoVenta::ignoreRequest(['campos'])->filter()->orderBy('plan_id', 'asc')->get();
        $results = ProductoVentaResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * @throws Throwable
     * @throws ValidationException
     */
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


    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function storeLotes(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'file' => 'required|mimes:xls,xlsx'
            ]);
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }

            Excel::import(new ProductosVentasClaroImport($request->file->getClientOriginalName()), $request->file);
            $mensaje = 'Archivo subido exitosamente!';
            DB::commit();
            return response()->json(compact('mensaje'));
        }catch (Exception $e){
            DB::rollback();
            Log::channel('testing')->error('Log', ['ERROR al leer el archivo', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'file' => [$e->getMessage(), $e->getLine()],
            ]);
        }
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
