<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias'
        ]);

        Categoria::create($request->all());

        return response()->json(['mensaje' => 'Guardado exitosamente!']);
    }
}
