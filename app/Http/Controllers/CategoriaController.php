<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('can:puede.ver.categorias')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias')->only('store');
        $this->middleware('can:puede.editar.categorias')->only('update');
        
    } */

    public function index()
    {
        $user = Auth::user();
        return response()->json([
            'modelo' => Categoria::all(), 
            'user'=>$user,
            //'roles'=>$user->getRoleNames()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $categoria = Categoria::create($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido creada con exito', 'modelo' => $categoria]);
    }

    public function show(Categoria $categoria)
    {
        return response()->json(['modelo' => $categoria]);
    }

    public function update(Request $request, Categoria  $categoria)
    {
        $request->validate(['nombre' => 'required|unique:categorias']);
        $categoria->update($request->all());

        return response()->json(['mensaje' => 'La categoría ha sido actualizada con exito', 'modelo' => $categoria]);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json(['mensaje' => 'La categoría ha sido eliminada con exito', 'modelo' => $categoria]);
    }
}
