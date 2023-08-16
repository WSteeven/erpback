<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditResource;
use App\Http\Requests\ComprasProveedores\ContactoProveedorRequest;
use App\Http\Resources\ComprasProveedores\ContactoProveedorResource;
use App\Models\ComprasProveedores\ContactoProveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;
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
    public function index(Request $request){
        $results = ContactoProveedorResource::collection(ContactoProveedor::filter()->get());
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
    public function show(ContactoProveedor $contacto)
    {
        $modelo = new ContactoProveedorResource($contacto);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ContactoProveedorRequest $request, ContactoProveedor  $contacto)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];

        //Respuesta
        $contacto->update($datos);
        $modelo = new ContactoProveedorResource($contacto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(ContactoProveedor $contacto)
    {
        $contacto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function auditoria(Request $request){
        if($request->id){
            $contactoProveedor = ContactoProveedor::find($request->id);
            $results = $contactoProveedor->audits()->orderBy('updated_at', 'desc')->get(); 
        }else{
            $results = Audit::where('auditable_type', ContactoProveedor::class)->with('user')->orderBy('created_at', 'desc')->get();
        }
        $results = AuditResource::collection($results);
        // $contacto = ContactoProveedor::first();
        // $results['usuario que realiza'] = $contacto->audits()->with('user')->get();
        // $results['metadatos'] = $contacto->audits()->latest()->first()->getMetadata();
        // $results['modificados'] = $contacto->audits()->latest()->first()->getModified();
        // $results['modificados-json'] = $contacto->audits()->latest()->first()->getModified(true);
        // $results = $producto->audits; //obtiene todos los eventos de un registro
        // $results = $producto->audits()->with('user')->get(); //obtiene el usuario que hizo la evento
        // $results = $producto->audits()->latest()->first()->getMetadata(); //obtiene los metadatos de un evento
        // $results = $producto->audits()->latest()->first()->getModified(); //obtiene las propiedades modificadas del registro afectado
        return response()->json(compact('results'));
    }
}
