<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermisoEmpleadoRequest;
use App\Models\PermisoEmpleado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class PermisoEmpleadoController extends Controller
{
    private $entidad = 'PERMISO_EMPLEADO';
    public function __construct()
    {
        $this->middleware('can:puede.ver.permiso_nomina')->only('index', 'show');
        $this->middleware('can:puede.crear.permiso_nomina')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
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
            DB::beginTransaction();
            $datos['motivo_id'] =  $request->safe()->only(['motivo'])['motivo'];
            $datos['estado_permiso_id'] =  PermisoEmpleado::PENDIENTE;
             //Convierte base 64 a url
             if ($request->justificacion) {
                $datos['justificacion'] = (new GuardarImagenIndividual($request->justificacion, RutasStorage::JUSTIFICACION_PERMISO_EMPLEADO))->execute();
            }
            $permisoEmpleado = PermisoEmpleado::create($datos);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }

    }

    public function show($permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        return $permisoEmpleado;
    }

    public function update(Request $request, $permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function destroy($permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->delete();
        return $permisoEmpleado;
    }

}
