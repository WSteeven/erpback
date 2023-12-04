<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class DescuentosGeneralesController extends Controller
{
    private $entidad = 'Descuentos Generales';

    public function __construct()
    {
        $this->middleware('can:puede.ver.descuentos_generales')->only('index', 'show');
        $this->middleware('can:puede.crear.descuentos_generales')->only('store');
        $this->middleware('can:puede.editar.descuentos_generales')->only('update');
        $this->middleware('can:puede.eliminar.descuentos_generales')->only('destroy');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = DescuentosGenerales::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(DescuentosGenerales $descuento_general)
    {
        $modelo = $descuento_general;
        return response()->json(compact('modelo'));
    }
    public function store(Request $request)
    {
        $descuento_general = new DescuentosGenerales();
        $descuento_general->nombre = $request->nombre;
        $descuento_general->save();
        $modelo = $descuento_general;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(Request $request, DescuentosGenerales $descuento_general)
    {

        $descuento_general->update($request->validate(['nombre'=> ['required', 'string']]));
        $modelo = $descuento_general;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function destroy(Request $request, DescuentosGenerales $descuento_general)
    {
        $descuento_general->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
