<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\LicenciaEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ArchivoLicenciaEmpleadoResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\LicenciaEmpleadoResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\LicenciaEmpleado;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;

class LicenciaEmpleadoController extends Controller
{
    private $entidad = 'PERMISO_EMPLEADO';
    public function __construct()
    {
        $this->middleware('can:puede.ver.licencia_empleado')->only('index', 'show');
        $this->middleware('can:puede.crear.licencia_empleado')->only('store');
    }
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

        $archivoJSON =  GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_LICENCIA_EMPLEADO);
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
    public function index(Request $request)
    {
        $results = [];
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $results = LicenciaEmpleado::ignoreRequest(['campos'])->filter()->get();
        } else {
            $empleados = Empleado::where('jefe_id', Auth::user()->empleado->id)->get('id');
            $results = LicenciaEmpleado::ignoreRequest(['campos'])->filter()->WhereIn('empleado', $empleados->pluck('id'))->get();
        }
        $results = LicenciaEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function create(Request $request)
    {
        $permisoEmpleado = new LicenciaEmpleado();
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function store(LicenciaEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            if ($request->tieneDocumento == false) {
                throw ValidationException::withMessages([
                    '404' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            DB::beginTransaction();
            $datos['id_tipo_licencia'] =  $request->safe()->only(['tipo_licencia'])['tipo_licencia'];
            $datos['estado'] =  LicenciaEmpleado::PENDIENTE;
            $permisoEmpleado = LicenciaEmpleado::create($datos);
            $modelo = new LicenciaEmpleadoResource($permisoEmpleado);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(LicenciaEmpleado $licenciaEmpleado)
    {
        $modelo = new LicenciaEmpleadoResource($licenciaEmpleado);
        return response()->json(compact('modelo'), 200);
    }

    public function update(LicenciaEmpleadoRequest $request, $licenciaEmpleadoId)
    {
        $datos = $request->validated();
        $permisoEmpleado = LicenciaEmpleado::find($licenciaEmpleadoId);
        $permisoEmpleado->update($datos);
        $modelo = new LicenciaEmpleadoResource($permisoEmpleado);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy($licenciaEmpleadoId)
    {
        $permisoEmpleado = LicenciaEmpleado::find($licenciaEmpleadoId);
        $permisoEmpleado->delete();
        return $permisoEmpleado;
    }
}
