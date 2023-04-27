<?php

namespace App\Http\Controllers;

use App\Models\ArchivoSeguimiento;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;
use Illuminate\Validation\ValidationException;

class ArchivoSeguimientoController extends Controller
{
    public function index()
    {
        $results = ArchivoSeguimiento::filter()->get();
        return response()->json(compact('results'));
    }


    /********************************
     * Se guarda un archivo a la vez
     ********************************/
    public function store(Request $request)
    {
        $request->validate([
            'seguimiento_id' => 'required|numeric|integer',
        ]);

        $seguimiento = Seguimiento::find($request['seguimiento_id']);

        if (!$seguimiento) {
            throw ValidationException::withMessages([
                'seguimiento' => ['El seguimiento no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $guardarArchivo = new GuardarArchivo($seguimiento, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO);
        $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);

        // return response()->json(['mensaje' => 'No se pudo subir!']);
    }

    /********************************
     * Se edita un archivo a la vez
     ********************************/
    public function update(Request $request, ArchivoSeguimiento $archivo_seguimiento)
    {
        $request->validate([
            'seguimiento_id' => 'required|numeric|integer',
        ]);

        // $archivo_seguimiento->update($request->all());

        $seguimiento = Seguimiento::find($request['seguimiento_id']);

        if (!$seguimiento) {
            throw ValidationException::withMessages([
                'seguimiento' => ['El seguimiento no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $guardarArchivo = new GuardarArchivo($seguimiento, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO);
        $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $archivo_seguimiento->refresh(), 'mensaje' => 'Información actualizada exitosamente!']);
    }


    public function destroy(ArchivoSeguimiento $archivo_seguimiento)
    {
        if ($archivo_seguimiento) {
            $eliminar = new EliminarArchivo($archivo_seguimiento);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente!']);
    }
}
