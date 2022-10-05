<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaRequest;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\MarcaResource;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class MarcaController extends Controller
{
    private $entidad = 'Marca';
    public function __construct()
    {
        $this->middleware('can:puede.ver.marcas')->only('index', 'show');
        $this->middleware('can:puede.crear.marcas')->only('store');
        $this->middleware('can:puede.editar.marcas')->only('update');
        $this->middleware('can:puede.eliminar.marcas')->only('destroy');

    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $results = [];
        //Log::channel('testing')->info('Log', ['search', $search]);
        if ($search) {
            $marca = Marca::select('id')->where('nombre', 'LIKE', '%' . $search . '%')->first();
            //Log::channel('testing')->info('Log', ['marca', $marca->id]);
            if ($marca) $results = MarcaResource::collection(Marca::where('id', $marca->id)->get());
        } else {
            $results = MarcaResource::collection(Marca::all());
        }

        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(MarcaRequest $request)
    {
        //Respuesta
        $modelo = Marca::create($request->validated());
        $modelo = new MarcaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Marca $marca)
    {
        $modelo = new MarcaResource($marca);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(MarcaRequest $request, Marca  $marca)
    {

        //Respuesta
        $marca->update($request->validated());
        $modelo = new MarcaResource($marca->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
