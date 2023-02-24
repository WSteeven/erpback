<?php

namespace App\Http\Controllers;

use App\Models\ArchivoTrabajo;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;

class ArchivoTrabajoController extends Controller
{
    public function index()
    {
        // $subtarea = request('trabajo');

        // $results = ArchivoSubtarea::where('subtarea_id', $subtarea)->get();
        $results = ArchivoTrabajo::filter()->get();
        return response()->json(compact('results'));
    }


    public function store(Request $request)
    {
        $trabajo = Trabajo::find($request['trabajo']);
        $modelo = null;

        if ($trabajo && $request->hasFile('file')) {

            $guardarArchivo = new GuardarArchivo($trabajo, $request, RutasStorage::SUBTAREAS);
            $modelo = $guardarArchivo->execute();

            return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);
        }

        return response()->json(['mensaje' => 'No se pudo subir!']);
    }


    public function update(Request $request, ArchivoTrabajo $archivo_trabajo)
    {
        $archivo_trabajo->update($request->all());
        return response()->json(['modelo' => $archivo_trabajo->refresh(), 'mensaje' => 'Información actualizada exitosamente!']);
    }


    public function destroy(ArchivoTrabajo $archivo_trabajo)
    {
        if ($archivo_trabajo) {
            $eliminar = new EliminarArchivo($archivo_trabajo);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente!']);
    }
}
