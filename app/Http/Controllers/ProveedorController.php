<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Http\Resources\ProveedorResource;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ProveedorController extends Controller
{
    private $entidad = 'Proveedor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.proveedores')->only('store');
        $this->middleware('can:puede.editar.proveedores')->only('update');
        $this->middleware('can:puede.eliminar.proveedores')->only('destroy');
    }
    /**
     * Listar
     */
    public function index()
    {
        $results = ProveedorResource::collection(Proveedor::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(ProveedorRequest $request)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['empresa_id']=$request->safe()->only(['empresa'])['empresa'];
        
        //Respuesta
        $modelo = Proveedor::create($datos);
        $modelo = new ProveedorResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Proveedor $proveedor)
    {
        $modelo = new ProveedorResource($proveedor);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ProveedorRequest $request, Proveedor  $proveedor)
    {
        //Respuesta
        $proveedor->update($request->validated());
        $modelo = new ProveedorResource($proveedor->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
