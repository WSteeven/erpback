<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosLey;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class DescuentosLeyController extends Controller
{
    private $entidad = 'Descuento de Ley';

    public function __construct()
    {
        $this->middleware('can:puede.ver.descuento_ley')->only('index', 'show');
        $this->middleware('can:puede.crear.descuento_ley')->only('store');
        $this->middleware('can:puede.editar.descuento_ley')->only('update');
        $this->middleware('can:puede.eliminar.descuento_ley')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = DescuentosLey::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(DescuentosLey $descuento_ley)
    {
        $modelo = $descuento_ley;
        return response()->json(compact('modelo'));
    }
    public function store(Request $request)
    {
       $descuento_ley = new DescuentosLey();
       $descuento_ley->nombre = $request->nombre;
       $descuento_ley->save();
       $modelo = $descuento_ley;
       $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
       return response()->json(compact('mensaje', 'modelo'));    }
    public function update(Request $request, DescuentosLey $descuento_ley)
    {
       $descuento_ley->update($request->validate(['nombre'=> ['required', 'string']]));
       $modelo = $descuento_ley;
       $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
       return response()->json(compact('mensaje', 'modelo'));
    }
    public function destroy(DescuentosLey $descuento_ley)
    {
       $descuento_ley->delete();
       $modelo = $descuento_ley;
        return response()->json(compact('modelo'));
    }
}
