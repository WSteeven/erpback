<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\PermisoEmpleadoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PermisoEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ArchivoPermisoEmpleadoResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PermisoEmpleadoResource;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\User;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\Queries\WhereIn;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;

class PermisoEmpleadoController extends Controller
{
    private $entidad = 'PERMISO_EMPLEADO';
    public function __construct()
    {
        $this->middleware('can:puede.ver.permiso_nomina')->only('index', 'show');
        $this->middleware('can:puede.crear.permiso_nomina')->only('store');
    }
    public function archivo_permiso_empleado(Request $request)
    {
        $request->validate([
            'permiso_id' => 'required|numeric|integer',
        ]);
        $permiso_empleado = PermisoEmpleado::find($request['permiso_id']);
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

        $archivoJSON =  GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_PERMISO_EMPLEADO,true,Auth::user()->empleado->id);
        $permiso_empleado->documento = $archivoJSON;
        $permiso_empleado->save();
        return response()->json(['modelo' => $permiso_empleado, 'mensaje' => 'Subido exitosamente!']);
    }
    public function index_archivo_permiso_empleado(Request $request)
    {
        $results = PermisoEmpleado::where('id', $request->permiso_id)->get();
        $results = ArchivoPermisoEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function index(Request $request)
    {
        $results = [];
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
        } else {
            $empleados = Empleado::where('jefe_id', Auth::user()->empleado->id)->orWhere('id', Auth::user()->empleado->id)->get('id');
            $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->WhereIn('empleado_id', $empleados->pluck('id'))->get();
        }
        $results = PermisoEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function create(Request $request)
    {
        $permisoEmpleado = new PermisoEmpleado();
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function store(PermisoEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            if ($request->tieneDocumento == false) {
                throw ValidationException::withMessages([
                    '404' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }
            DB::beginTransaction();
            $datos['tipo_permiso_id'] =  $request->safe()->only(['tipo_permiso'])['tipo_permiso'];
            $datos['estado_permiso_id'] =  PermisoEmpleado::PENDIENTE;
            $permisoEmpleado = PermisoEmpleado::create($datos);
            event(new PermisoEmpleadoEvent($permisoEmpleado));
            $modelo = new PermisoEmpleadoResource($permisoEmpleado);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(PermisoEmpleado $permisoEmpleado)
    {
        $modelo = new PermisoEmpleadoResource($permisoEmpleado);
        return response()->json(compact('modelo'), 200);
    }

    public function update(PermisoEmpleadoRequest $request, $permisoEmpleadoId)
    {
        $datos = $request->validated();
        $datos['estado_permiso_id'] = $request->safe()->only(['estado'])['estado'];
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->update($datos);
        event(new PermisoEmpleadoEvent($permisoEmpleado));
        $modelo = new PermisoEmpleadoResource($permisoEmpleado);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy($permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->delete();
        return $permisoEmpleado;
    }
    public function permisos_sin_recuperar(Request $request)
{
    $mes = Carbon::createFromFormat('m-Y', $request->mes)->format('Y-m');

    // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
    $totalDiasPermiso = DB::table('permiso_empleados')
        ->selectRaw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso')
        ->where('empleado_id', $request->empleado)
        ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$mes])
        ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$mes])
        ->where('recupero',0)
        ->value('total_dias_permiso');

    return response()->json(compact('totalDiasPermiso'));
}
}
