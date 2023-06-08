<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactoProveedorRequest;
use App\Http\Resources\ContactoProveedorResource;
use App\Models\ContactoProveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ContactoProveedorController extends Controller
{
    private $entidad = 'Contacto de proveedor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.contactos_proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.contactos_proveedores')->only('store');
        $this->middleware('can:puede.editar.contactos_proveedores')->only('update');
        $this->middleware('can:puede.eliminar.contactos_proveedores')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(){
        $results = ContactoProveedorResource::collection(ContactoProveedor::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ContactoProveedorRequest $request)
    {
        try {
            DB::beginTransaction();
            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];

            //Respuesta
            $modelo = ContactoProveedor::create($datos);
            $modelo = new ContactoProveedorResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje = '('.$e->getLine().') Hubo un erorr: '. $e->getMessage();
            return response()->json(compact('mensaje'),500);
            //throw $th;
        }
    }


    /**
     * Consultar
     */
    public function show(ContactoProveedor $proveedor)
    {
        $modelo = new ContactoProveedorResource($proveedor);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ContactoProveedorRequest $request, ContactoProveedor  $proveedor)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];

        //Respuesta
        $proveedor->update($datos);
        $modelo = new ContactoProveedorResource($proveedor->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(ContactoProveedor $proveedor)
    {
        $proveedor->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
