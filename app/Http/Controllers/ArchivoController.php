<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Config\RutasStorage;

class ArchivoController extends Controller
{
    public function index(){
        $results = Archivo::filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request){
        $request->validate(['file'=>'required']);

        if(!$request->hasFile('file')){
            throw ValidationException::withMessages(['file' => ['Debe selecionar al menos un archivo.']]);
        }

    }

    public function guardarArchivoSeguimiento($entidad, Request $request, RutasStorage $ruta){
        
    }

}
