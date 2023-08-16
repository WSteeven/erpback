<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoHipotecarioRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoHipotecarioResource;
use App\Imports\PrestamoHipotecarioImport;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class PrestamoHipotecarioController extends Controller
{
    private $entidad = 'Prestamo Hipotecario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_hipotecario')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_hipotecario')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PrestamoHipotecario::ignoreRequest(['campos'])->filter()->get();
        $results = PrestamoHipotecarioResource::collection($results);
        return response()->json(compact('results'));
    }

    public function prestamos_hipotecario_empleado(Request $request)
    {
        $prestamo = PrestamoHipotecario::where('empleado_id', $request->empleado)->where('mes', $request->mes)->sum('valor');
        return response()->json(compact('prestamo'));
    }

    public function store(PrestamoHipotecarioRequest $request)
    {
        try {
            $request->validated();
            $existe_prestamo = PrestamoHipotecario::where('mes', $request->mes)->count();
            if ($existe_prestamo >0) {
                throw ValidationException::withMessages([
                    'mes' => ['Mes duplicado, ya registro listado de prestamos hipotecarios del mes: '.$request->mes],
                ]);
            }
            $modelo = new PrestamoHipotecario();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(PrestamoHipotecario $prestamoHipotecario)
    {
        $modelo = new PrestamoHipotecarioResource($prestamoHipotecario);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $prestamoHipotecarioId)
    {
        $prestamoHipotecario = PrestamoHipotecario::find($prestamoHipotecarioId);
        $prestamoHipotecario->nombre = $request->nombre;
        $prestamoHipotecario->save();
        return $prestamoHipotecario;
    }

    public function destroy($prestamoHipotecarioId)
    {
        $prestamoHipotecario = PrestamoHipotecario::find($prestamoHipotecarioId);
        $prestamoHipotecario->delete();
        return $prestamoHipotecario;
    }
    public function archivo_prestamo_hipotecario(Request $request)
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
            Excel::import(new PrestamoHipotecarioImport($request->mes), $request->file);
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
