<?php

namespace App\Http\Controllers;

use App\Models\ArchivoSeguimiento;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use Illuminate\Http\Request;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

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
            'subtarea_id' => 'required|numeric|integer',
        ]);

        $subtarea = Subtarea::find($request['subtarea_id']);
        /* $seguimiento = SeguimientoSubtarea::find($request['subtarea_id']); */

        if (!$subtarea) {
            throw ValidationException::withMessages([
                'subtarea' => ['La subtarea no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $modelo = $this->guardarArchivo($subtarea, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO);
        // $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);

        // return response()->json(['mensaje' => 'No se pudo subir!']);
    }

    /********************************
     * Se edita un archivo a la vez
     ********************************/
    public function update(Request $request, ArchivoSeguimiento $archivo_seguimiento)
    {
        $request->validate([
            'subtarea_id' => 'required|numeric|integer',
        ]);

        // $archivo_seguimiento->update($request->all());

        $seguimiento = SeguimientoSubtarea::find($request['subtarea_id']);

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

        $modelo = $this->guardarArchivo($seguimiento, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO);
        // $modelo = $guardarArchivo->execute();

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

    public function guardarArchivo(Subtarea $subtarea, Request $request, RutasStorage $ruta)
    {
        $archivo = $request->file('file');

        $path = $archivo->store($ruta->value);
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        return $subtarea->archivosSeguimiento()->create([
            'nombre' => $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo)
        ]);
    }
}
