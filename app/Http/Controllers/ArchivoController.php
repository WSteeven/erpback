<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;

class ArchivoController extends Controller
{
    public function index()
    {
        $results = Archivo::filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        if ($request->entidad) {
            //
        }
        $request->validate(['file' => 'required']);

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages(['file' => ['Debe selecionar al menos un archivo.']]);
        }
    }

    public function destroy(Archivo $archivo)
    {
        if ($archivo) {
            $eliminar =  new EliminarArchivo($archivo);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente.']);
    }
}
