<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoQuirografarioRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoQuirografarioResource;
use App\Imports\PrestamoQuirografarioImport;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirografario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class PrestamoQuirografarioController extends Controller
{
    private string $entidad = 'Prestamo Quirorafario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamos_quirografarios')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamos_quirografarios')->only('store');
    }

    public function index()
    {
        $results = PrestamoQuirografario::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = PrestamoQuirografarioResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(PrestamoQuirografarioRequest $request)
    {
        try {
            $request->validated();
            $existe_prestamo = PrestamoQuirografario::where('mes', $request->mes)->count();
            Log::channel('testing')->info('Log', ['existe prestamo', $existe_prestamo]);

            if ($existe_prestamo >0) {
                throw ValidationException::withMessages([
                    'mes' => ['Mes duplicado, ya registro listado de prestamos hipotecarios del mes: '.$request->mes],
                ]);
            }
            $modelo = new PrestamoQuirografario();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Problemas al guardar el archivo');
        }
    }

    public function show(PrestamoQuirografario $prestamo)
    {
        $modelo = new PrestamoQuirografarioResource($prestamo);
        return response()->json(compact('modelo'));
    }
    public function prestamoQuirografarioEmpleado(Request $request){
        $prestamo = PrestamoQuirografario::where('empleado_id', $request->empleado)->where('mes', $request->mes)->sum('valor');
        return response()->json(compact('prestamo'));
     }
     public function archivoPrestamoQuirografario(Request $request)
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
            Excel::import(new PrestamoQuirografarioImport($request->mes), $request->file);
            return response()->json(['mensaje' => 'Subido exitosamente!']);
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$ex->getMessage()],
            ]);
        }
    }


    public function update(Request $request, PrestamoQuirografario $prestamo)
    {
        $prestamo->nombre = $request->nombre;
        $prestamo->save();
        return $prestamo;
    }

    public function destroy(PrestamoQuirografario $prestamo)
    {
        $prestamo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
