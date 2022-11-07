<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaRequest;
use App\Http\Resources\EmpresaResource;
use App\Models\Empresa;
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

    public function list(){
        $results = Empresa::filter()->get();
        return EmpresaResource::collection($results);
    }
    /**
     * Listar
     */
    public function index()
    {
        // $results = EmpresaResource::collection(Empresa::all());
        // return response()->json(compact('results'));
        return response()->json(['results' => $this->list()]);
    }


    /**
     * Guardar
     */
    public function store(EmpresaRequest $request)
    {
        //Respuesta
        $modelo = Empresa::create($request->validated());
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
        //Respuesta
        $empresa->update($request->validated());
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
