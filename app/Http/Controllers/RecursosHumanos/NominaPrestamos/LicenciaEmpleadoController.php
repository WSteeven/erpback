<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\RecursosHumanos\LicenciaEmpleadoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ArchivoLicenciaEmpleadoResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoResource;
use App\Models\RecursosHumanos\NominaPrestamos\LicenciaEmpleado;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\EmpleadoService;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;
use Throwable;

class LicenciaEmpleadoController extends Controller
{
    private string $entidad = 'Licencia del Empleado';
    public function __construct()
    {
        $this->middleware('can:puede.ver.licencia_empleado')->only('index', 'show');
        $this->middleware('can:puede.crear.licencia_empleado')->only('store');
    }

    /**
     * @throws ValidationException
     */
    public function archivo_licencia_empleado(Request $request)
    {
        $request->validate([
            'licencia_id' => 'required|numeric|integer',
        ]);
        $permiso_empleado = LicenciaEmpleado::find($request['licencia_id']);
        if (!$permiso_empleado) {
            throw ValidationException::withMessages([
                'permiso_empleado' => ['El permiso del empleado no existe'],
            ]);
        }
        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $archivoJSON = GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_LICENCIA_EMPLEADO, true, Auth::user()->empleado->id);
        $permiso_empleado->documento = $archivoJSON;
        $permiso_empleado->save();
        return response()->json(['modelo' => $permiso_empleado, 'mensaje' => 'Subido exitosamente!']);
    }

    public function index_archivo_licencia_empleado(Request $request)
    {
        $results = LicenciaEmpleado::where('id', $request->licencia_id)->get();
        $results = ArchivoLicenciaEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function index()
    {
        if (Auth::user()->hasRole('RECURSOS HUMANOS')) {
            $results = LicenciaEmpleado::ignoreRequest(['campos'])->filter()->get();
        } else {
            $ids_empleados = EmpleadoService::obtenerIdsEmpleadosOtroAutorizador();
            $results = LicenciaEmpleado::ignoreRequest(['campos'])->filter()->WhereIn('empleado', $ids_empleados)->get();
        }
        $results = LicenciaEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @param LicenciaEmpleadoRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(LicenciaEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            if (!$request->tieneDocumento) {
                throw ValidationException::withMessages([
                    '404' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            DB::beginTransaction();
            $datos['id_tipo_licencia'] = $request->safe()->only(['tipo_licencia'])['tipo_licencia'];
            $datos['estado'] = LicenciaEmpleado::PENDIENTE;
            $licenciaEmpleado = LicenciaEmpleado::create($datos);
            event(new LicenciaEmpleadoEvent($licenciaEmpleado));
            $modelo = new LicenciaEmpleadoResource($licenciaEmpleado);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(LicenciaEmpleado $licencia)
    {
        $modelo = new LicenciaEmpleadoResource($licencia);
        return response()->json(compact('modelo'));
    }

    public function update(LicenciaEmpleadoRequest $request,LicenciaEmpleado $licencia)
    {
//        Log::channel('testing')->info('Log', ['UPDATE', $licencia]);
//        Log::channel('testing')->info('Log', ['REQUEST', $request->all()]);
        $datos = $request->validated();
        $licencia->update($datos);
        event(new LicenciaEmpleadoEvent($licencia));
        $modelo = new LicenciaEmpleadoResource($licencia);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(LicenciaEmpleado $licencia)
    {
        $licencia->delete();
        return $licencia;
    }
}
