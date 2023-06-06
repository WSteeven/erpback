<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class EmpresaController extends Controller
{
    private $entidad = 'Empresa';
    public function __construct()
    {
        $this->middleware('can:puede.ver.empresas')->only('index', 'show');
        $this->middleware('can:puede.crear.empresas')->only('store');
        $this->middleware('can:puede.editar.empresas')->only('update');
        $this->middleware('can:puede.eliminar.empresas')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = [];

        $results = Empresa::filter()->get();
        $results = EmpresaResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(EmpresaRequest $request)
    {
        // Adaptación de foreign keys
        $datos = $request->validated();
        $datos['canton_id']=$request->safe()->only(['canton'])['canton'];
        //Respuesta
        $modelo = Empresa::create($datos);
        $modelo = new EmpresaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Empresa $empresa)
    {
        $modelo = new EmpresaResource($empresa);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(EmpresaRequest $request, Empresa  $empresa)
    {
        // Adaptación de foreign keys
        Log::channel('testing')->info('Log', ['Antes de validar', $request->all()]);
        $datos = $request->validated();
        Log::channel('testing')->info('Log', ['Despues de validar', $request->all()]);
        $datos['canton_id']=$request->safe()->only(['canton'])['canton'];
        
        //Respuesta
        $empresa->update($datos);
        $modelo = new EmpresaResource($empresa->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
