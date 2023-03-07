<?php

namespace App\Http\Controllers;

use App\Models\ArchivoSubtarea;
use App\Models\Subtarea;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;
use Illuminate\Validation\ValidationException;

class ArchivoSubtareaController extends Controller
{
    public function index()
    {
        $results = ArchivoSubtarea::filter()->get();
        return response()->json(compact('results'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
        ]);

        $trabajo = Subtarea::find($request['subtarea_id']);

        if (!$trabajo) {
            throw ValidationException::withMessages([
                'trabajo' => ['El trabajo no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $guardarArchivo = new GuardarArchivo($trabajo, $request, RutasStorage::SUBTAREAS);
        $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);

        return response()->json(['mensaje' => 'No se pudo subir!']);
    }


    public function update(Request $request, ArchivoSubtarea $archivo_subtarea)
    {
        $archivo_subtarea->update($request->all());
        return response()->json(['modelo' => $archivo_subtarea->refresh(), 'mensaje' => 'Información actualizada exitosamente!']);
    }


    public function destroy(ArchivoSubtarea $archivo_subtarea)
    {
        if ($archivo_subtarea) {
            $eliminar = new EliminarArchivo($archivo_subtarea);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente!']);
    }
}
