<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludResource;
use App\Imports\ExtensionCoverturaSaludImport;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class ExtensionCoverturaSaludController extends Controller
{
    private string $entidad = 'Extension Covertura de Salud';
    public function __construct()
    {
        $this->middleware('can:puede.ver.extension_conyugal')->only('index', 'show');
        $this->middleware('can:puede.crear.extension_conyugal')->only('store');
    }

    public function index()
    {
        $results = ExtensionCoverturaSalud::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = ExtensionCoverturaSaludResource::collection($results);
        return response()->json(compact('results'));
    }

    public function extensionCoberturaSaludEmpleado(Request $request)
    {
        $valor = ExtensionCoverturaSalud::where('empleado_id', $request->empleado)->where('mes', $request->mes)->sum('aporte');
        return response()->json(compact('valor'));
    }

    public function store(ExtensionCoverturaSaludRequest $request)
    {
        try {
            $request->validated();
            $existe_prestamo = ExtensionCoverturaSalud::where('mes', $request->mes)->count();
            if ($existe_prestamo > 0) {
                throw ValidationException::withMessages([
                    'mes' => ['Mes duplicado, ya registro listado de extension conyugal: ' . $request->mes],
                ]);
            }
            $modelo = new ExtensionCoverturaSalud();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $ex->getMessage(), $ex->getLine()]);
           throw Utils::obtenerMensajeErrorLanzable($ex, 'Problemas con el archivo proporcionado');
        }
    }

    public function show(ExtensionCoverturaSalud $extension)
    {
        $modelo = new ExtensionCoverturaSaludResource($extension);
        return response()->json(compact('modelo'));
    }

    public function update(Request $request, ExtensionCoverturaSalud $extension)
    {
        $extension->nombre = $request->nombre;
        $extension->save();
        return $extension;
    }

    public function destroy(ExtensionCoverturaSalud $extension)
    {
        $extension->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * @throws ValidationException
     */
    public function archivoExtensionConyugal(Request $request)
    {
        try {
            $this->validate($request, [
                'file' => 'required|mimes:xls,xlsx'
            ]);
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            Excel::import(new ExtensionCoverturaSaludImport($request->mes), $request->file);
            return response()->json(['mensaje' => 'Subido exitosamente!']);
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de prestamo hipotecario', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'file' => [$ex->getMessage(), $ex->getLine()],
            ]);
        }
    }
}
