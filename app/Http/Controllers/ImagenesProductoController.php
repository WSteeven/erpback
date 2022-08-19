<?php

namespace App\Http\Controllers;

use App\Models\ImagenesProducto;
use Illuminate\Http\Request;

class ImagenesProductoController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => ImagenesProducto::all()]);
    }


    public function store(Request $request)
    {
        $request->validate(['url' => 'required|unique:imagenes_productos','producto_id'=>'required|exists:nombres_de_productos,id']);
        $imagenproducto = ImagenesProducto::create($request->all());

        return response()->json(['mensaje' => 'La imagen ha sido creada con éxito', 'modelo' => $imagenproducto]);
    }


    public function show(ImagenesProducto $imagenproducto)
    {
        return response()->json(['modelo' => $imagenproducto]);
    }


    public function update(Request $request, ImagenesProducto  $imagenproducto)
    {
        $request->validate(['url' => 'required|unique:imagenes_productos','producto_id'=>'required|exists:nombres_de_productos,id']);
        $imagenproducto->update($request->all());

        return response()->json(['mensaje' => 'La imagen ha sido actualizada con éxito', 'modelo' => $imagenproducto]);
    }


    public function destroy(ImagenesProducto $imagenproducto)
    {
        $imagenproducto->delete();

        return response()->json(['mensaje' => 'La imagen ha sido eliminada con éxito', 'modelo' => $imagenproducto]);
    }
}
