<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\CategoriaOfertaProveedorRequest;
use App\Http\Resources\ComprasProveedores\CategoriaOfertaProveedorResource;
use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class CategoriaOfertaProveedorController extends Controller
{
    private $entidad = 'Categoria';
    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias_ofertas')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias_ofertas')->only('store');
        $this->middleware('can:puede.editar.categorias_ofertas')->only('update');
        $this->middleware('can:puede.eliminar.categorias_ofertas')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = CategoriaOfertaProveedor::all();
        $results = CategoriaOfertaProveedorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(CategoriaOfertaProveedorRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_oferta_id'] = $request->safe()->only(['tipo_oferta'])['tipo_oferta'];

        // Respuesta
        $modelo  = CategoriaOfertaProveedor::create($datos);
        $modelo = new CategoriaOfertaProveedorResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(CategoriaOfertaProveedor $categoria)
    {
        $modelo = new CategoriaOfertaProveedorResource($categoria);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(CategoriaOfertaProveedorRequest $request, CategoriaOfertaProveedor $categoria)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_oferta_id'] = $request->safe()->only(['tipo_oferta'])['tipo_oferta'];

        // Respuesta
        $categoria->update($datos);
        $modelo = new CategoriaOfertaProveedorResource($categoria->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
}
