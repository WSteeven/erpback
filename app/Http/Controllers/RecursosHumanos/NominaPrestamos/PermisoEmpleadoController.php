<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\PermisoEmpleadoEvent;
use App\Events\PermisoNotificacionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PermisoEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PermisoEmpleadoResource;
use App\Models\Autorizacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\EmpleadoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class PermisoEmpleadoController extends Controller
{
    private string $entidad = 'Permiso';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.permiso_nomina')->only('index', 'show');
        $this->middleware('can:puede.crear.permiso_nomina')->only('store');
    }

    /**
     * @throws Exception|Throwable
     */
    public function archivoPermisoEmpleado(Request $request)
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

        // $archivoJSON =  GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_PERMISO_EMPLEADO, true, Auth::user()->empleado->id);
        // $permiso_empleado->documento = $archivoJSON;
        // $permiso_empleado->save();
        $modelo = $this->archivoService->guardarArchivo($permiso_empleado, $request->file, RutasStorage::DOCUMENTOS_PERMISO_EMPLEADO->value . auth()->user()->empleado->identificacion);


        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);
    }

    /**
     * @throws Exception
     */
    public function indexArchivoPermisoEmpleado(Request $request)
    {
        $permiso = PermisoEmpleado::where('id', $request->permiso_id)->first();
        $request['permiso_id'] = null;
        $results = $this->archivoService->listarArchivos($permiso);
        return response()->json(compact('results'));
    }

    public function index()
    {
        if (auth()->user()->hasRole('RECURSOS HUMANOS')) {
            $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
        } else {
           $ids_empleados = EmpleadoService::obtenerIdsEmpleadosOtroAutorizador();
            $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->WhereIn('empleado_id', $ids_empleados)->get();
        }
        $results = PermisoEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

//    public function create(Request $request)
//    {
//        $permisoEmpleado = new PermisoEmpleado();
//        $permisoEmpleado->nombre = $request->nombre;
//        $permisoEmpleado->save();
//        return $permisoEmpleado;
//    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(PermisoEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['tipo_permiso_id'] = $request->safe()->only(['tipo_permiso'])['tipo_permiso'];
            $datos['estado_permiso_id'] = PermisoEmpleado::PENDIENTE;
            $permiso = PermisoEmpleado::create($datos);
            event(new PermisoEmpleadoEvent($permiso));
            $modelo = new PermisoEmpleadoResource($permiso);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    public function show(PermisoEmpleado $permiso)
    {
        $modelo = new PermisoEmpleadoResource($permiso);

        return response()->json(compact('modelo'));
    }

    /**
     * @throws Exception|Throwable
     */
    public function update(PermisoEmpleadoRequest $request, PermisoEmpleado $permiso)
    {
        $autorizacion_id_old = $permiso->estado_permiso_id;
        $datos = $request->validated();
        $datos['estado_permiso_id'] = $request->safe()->only(['estado'])['estado'];
        $permiso->update($datos);
        if ($permiso->estado_permiso_id !== $autorizacion_id_old) {
            event(new PermisoEmpleadoEvent($permiso));
            if ($datos['estado_permiso_id'] == Autorizacion::APROBADO_ID) {
                event(new PermisoNotificacionEvent($permiso));
            }
        }
        $modelo = new PermisoEmpleadoResource($permiso);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(PermisoEmpleado $permiso)
    {
        $permiso->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function permisosSinRecuperar(Request $request)
    {
        $mes = Carbon::createFromFormat('m-Y', $request->mes)->format('Y-m');

        // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
        $total_dias_permiso = DB::table('permiso_empleados')
            ->selectRaw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso')
            ->where('empleado_id', $request->empleado)
            ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$mes])
            ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$mes])
            ->where('recupero', 0)
            ->value('total_dias_permiso');

        return response()->json(compact('total_dias_permiso'));
    }
}
