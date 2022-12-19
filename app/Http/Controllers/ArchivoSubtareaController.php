<?php

namespace App\Http\Controllers;

use App\Models\ArchivoSubtarea;
use App\Models\Subtarea;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;

class ArchivoSubtareaController extends Controller
{
    public function index()
    {
        $subtarea = request('subtarea');

        $results = ArchivoSubtarea::where('subtarea_id', $subtarea)->get();
        return response()->json(compact('results'));
    }


    public function store(Request $request)
    {
        $subtarea = Subtarea::find($request['subtarea']);
        $modelo = null;

        if ($subtarea && $request->hasFile('file')) {

            $guardarArchivo = new GuardarArchivo($subtarea, $request, RutasStorage::SUBTAREAS);
            $modelo = $guardarArchivo->execute();

            return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);
        }

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
