<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludResource;
use App\Imports\ExtensionCoverturaSaludImport;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud ;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class ExtensionCoverturaSaludController extends Controller
{
    private $entidad = 'Extension Covertura de Salud';
    public function __construct()
    {
        $this->middleware('can:puede.ver.extension_conyugal')->only('index', 'show');
        $this->middleware('can:puede.crear.extension_conyugal')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = ExtensionCoverturaSalud::ignoreRequest(['campos'])->filter()->get();
        $results = ExtensionCoverturaSaludResource::collection($results);
        return response()->json(compact('results'));
    }

    public function extension_covertura_salud_empleado(Request $request){
       $valor= ExtensionCoverturaSalud::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('aporte');
       return response()->json(compact('valor'));
    }

    public function store(ExtensionCoverturaSaludRequest $request)
    {
        try {
            $request->validated();
            $existe_prestamo = ExtensionCoverturaSalud::where('mes', $request->mes)->count();
            if ($existe_prestamo >0) {
                throw ValidationException::withMessages([
                    'mes' => ['Mes duplicado, ya registro listado de extension conyugal: '.$request->mes],
                ]);
            }
            $modelo = new ExtensionCoverturaSalud();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ExtensionCoverturaSalud $extensionCoverturaSalud)
    {
        $modelo = new ExtensionCoverturaSaludResource($extensionCoverturaSalud);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $extensionCoverturaSaludId)
    {
        $extensionCoverturaSalud = ExtensionCoverturaSalud::find($extensionCoverturaSaludId);
        $extensionCoverturaSalud->nombre = $request->nombre;
        $extensionCoverturaSalud->save();
        return $extensionCoverturaSalud;
    }

    public function destroy($extensionCoverturaSaludId)
    {
        $extensionCoverturaSalud = ExtensionCoverturaSalud::find($extensionCoverturaSaludId);
        $extensionCoverturaSalud->delete();
        return $extensionCoverturaSalud;
    }
    public function archivo_extension_conyugal(Request $request)
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
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'file' => [$e->getMessage(), $e->getLine()],
            ]);
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de prestamo hipotecario', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
