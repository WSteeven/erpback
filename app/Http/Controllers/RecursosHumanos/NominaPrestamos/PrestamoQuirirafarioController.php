<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoQuirorafarioRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoQuirorafarioResource;
use App\Imports\PrestamoQuirorafarioImport;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class PrestamoQuirirafarioController extends Controller
{
    private $entidad = 'Prestamo Quirorafario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_quirorafario')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_quirorafario')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PrestamoQuirorafario::ignoreRequest(['campos'])->filter()->get();
        $results = PrestamoQuirorafarioResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(PrestamoQuirorafarioRequest $request)
    {
        try {
            $request->validated();
            $existe_prestamo = PrestamoQuirorafario::where('mes', $request->mes)->count();
            Log::channel('testing')->info('Log', ['existe prestamo', $existe_prestamo]);

            if ($existe_prestamo >0) {
                throw ValidationException::withMessages([
                    'mes' => ['Mes duplicado, ya registro listado de prestamos hipotecarios del mes: '.$request->mes],
                ]);
            }
            $modelo = new PrestamoQuirorafario();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(PrestamoQuirorafario $prestamoQuirorafario)
    {
        $modelo = new PrestamoQuirorafarioResource($prestamoQuirorafario);
        return response()->json(compact('modelo'), 200);
    }
    public function prestamos_quirorafario_empleado(Request $request){
        $prestamo = PrestamoQuirorafario::where('empleado_id', $request->empleado)->where('mes', $request->mes)->sum('valor');
        return response()->json(compact('prestamo'));
     }
     public function archivo_prestamo_quirorafario(Request $request)
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
            Excel::import(new PrestamoQuirorafarioImport($request->mes), $request->file);
            return response()->json(['mensaje' => 'Subido exitosamente!']);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }


    public function update(Request $request, $prestamoQuirorafarioId)
    {
        $prestamoQuirorafario = PrestamoQuirorafario::find($prestamoQuirorafarioId);
        $prestamoQuirorafario->nombre = $request->nombre;
        $prestamoQuirorafario->save();
        return $prestamoQuirorafario;
    }

    public function destroy($prestamoQuirorafarioId)
    {
        $prestamoQuirorafario = PrestamoQuirorafario::find($prestamoQuirorafarioId);
        $prestamoQuirorafario->delete();
        return $prestamoQuirorafario;
    }
}
