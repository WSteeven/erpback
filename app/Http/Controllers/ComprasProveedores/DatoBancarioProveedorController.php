<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\DatoBancarioProveedorRequest;
use App\Http\Resources\AuditResource;
use App\Http\Resources\ComprasProveedores\DatoBancarioProveedorResource;
use App\Models\ComprasProveedores\DatoBancarioProveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Audit;
use Src\Shared\Utils;

class DatoBancarioProveedorController extends Controller
{
    private $entidad = 'Dato bancario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.datos_bancarios_proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.datos_bancarios_proveedores')->only('store');
        $this->middleware('can:puede.editar.datos_bancarios_proveedores')->only('update');
        $this->middleware('can:puede.eliminar.datos_bancarios_proveedores')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = DatoBancarioProveedorResource::collection(DatoBancarioProveedor::filter()->get());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DatoBancarioProveedorRequest $request)
    {
        try {
            DB::beginTransaction();
            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['banco_id'] = $request->safe()->only(['banco'])['banco'];
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            if ($request->proveedor) $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];

            //Respuesta
            $modelo = DatoBancarioProveedor::create($datos);
            $modelo = new DatoBancarioProveedorResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje = '(' . $e->getLine() . ') Hubo un erorr: ' . $e->getMessage();
            return response()->json(compact('mensaje'), 500);
            //throw $th;
        }
    }


    /**
     * Consultar
     */
    public function show(DatoBancarioProveedor $dato)
    {
        $modelo = new DatoBancarioProveedorResource($dato);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(DatoBancarioProveedorRequest $request, DatoBancarioProveedor  $dato)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['banco_id'] = $request->safe()->only(['banco'])['banco'];
        $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
        if ($request->proveedor) $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];

        //Respuesta
        $dato->update($datos);
        $modelo = new DatoBancarioProveedorResource($dato->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(DatoBancarioProveedor $dato)
    {
        $dato->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function auditoria(Request $request)
    {
        if ($request->id) {
            $contactoProveedor = DatoBancarioProveedor::find($request->id);
            $results = $contactoProveedor->audits()->orderBy('updated_at', 'desc')->get();
        } else {
            $results = Audit::where('auditable_type', DatoBancarioProveedor::class)->with('user')->orderBy('created_at', 'desc')->get();
        }
        $results = AuditResource::collection($results);
        
        return response()->json(compact('results'));
        
    }
}
