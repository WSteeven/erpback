<?php

namespace App\Http\Controllers;

use App\Models\ArchivoTrabajo;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;
use Illuminate\Validation\ValidationException;

class ArchivoTrabajoController extends Controller
{
    public function index()
    {
        $results = ArchivoTrabajo::filter()->get();
        return response()->json(compact('results'));
    }


    public function store(Request $request)
    {
        $trabajo = Trabajo::find($request['trabajo_id']);

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

        $guardarArchivo = new GuardarArchivo($trabajo, $request, RutasStorage::TRABAJOS);
        $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);

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
