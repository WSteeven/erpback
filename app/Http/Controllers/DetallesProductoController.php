<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetallesProductoRequest;
use App\Http\Resources\DetallesProductoResource;
use App\Models\CodigoCliente;
use App\Models\DetallesProducto;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetallesProductoController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('can:puede.ver.DetallesProductos')->only('index', 'show');
        $this->middleware('can:puede.crear.DetallesProductos')->only('store');
        $this->middleware('can:puede.editar.DetallesProductos')->only('update');
        $this->middleware('can:puede.eliminar.DetallesProductos')->only('destroy');

    } */
    
    public function index()
    {
        $results = DetallesProductoResource::collectiond(DetallesProducto::all());
        return response()->json(compact('results'));
    }

    public function store(DetallesProductoRequest $request)
    {

        $DetallesProductoCreado = DetallesProducto::create($request->validated());
        /* $codigoCliente = CodigoCliente::create([
            "propietario_id" => 1,
            "DetallesProducto_id" => $DetallesProductoCreado->id,
            "codigo" => $this->generarCodigo($DetallesProductoCreado->id)
        ]); */
        return response()->json([
            'mensaje' => 'El DetallesProducto ha sido creado con éxito',
            'modelo' => new DetallesProductoResource($DetallesProductoCreado),
            /* "codigo" => $codigoCliente */
        ]);
    }



    public function show(DetallesProducto $DetallesProducto)
    {
        $modelo = new DetallesProductoResource($DetallesProducto);
        return response()->json(compact('modelo'));
    }


    public function update(DetallesProductoRequest $request, DetallesProducto $DetallesProducto)
    {
        $DetallesProducto->update($request->validated());
        return response()->json(['mensaje' => 'El DetallesProducto ha sido actualizado con éxito', 'modelo' => new DetallesProductoResource($DetallesProducto)]);
    }

    public function destroy(DetallesProducto $DetallesProducto)
    {
        $DetallesProducto->delete();
        return response()->json(['mensaje' => 'El DetallesProducto ha sido eliminado con éxito', 'modelo'=>new DetallesProductoResource($DetallesProducto)]);
    }
}
