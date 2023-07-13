<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermisoEmpleadoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PermisoEmpleadoResource;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
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
            DB::beginTransaction();
            $datos['motivo_id'] =  $request->safe()->only(['motivo'])['motivo'];
            $datos['estado_permiso_id'] =  PermisoEmpleado::PENDIENTE;
            //Convierte base 64 a url
            if ($request->justificacion) {
                $datos['justificacion'] = (new GuardarImagenIndividual($request->justificacion, RutasStorage::JUSTIFICACION_PERMISO_EMPLEADO))->execute();
            }
            $permisoEmpleado = PermisoEmpleado::create($datos);
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
      /**
     * It updates the status of the expense to 1, which means it is approved.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
   /* public function aprobar_gasto(Request $request)
    {
        $permisoEmpleado = PermisoEmpleado::where('id', $request->id)->first();
        $permisoEmpleado->estado = 1;
        $permisoEmpleado->detalle_estado = $request->detalle_estado;
        $permisoEmpleado->save();
        $notificacion = Notificacion::where('per_originador_id', $permisoEmpleado->id_usuario)
            ->where('per_destinatario_id', $permisoEmpleado->aut_especial)
            ->where('tipo_notificacion', 'PermisoEmpleado')
            ->where('leida', 0)
            ->whereDate('notificable_id', $permisoEmpleado->id)
            ->first();
        if ($notificacion != null) {
            $notificacion->leida = 1;
            $notificacion->save();
        }
        event(new FondoRotativoEvent($permisoEmpleado));
        return response()->json(['success' => 'Permiso autorizado correctamente']);
    }*/
    /**
     * It updates the status of the expense to 1, which means it is rejected.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
   /* public function rechazar_gasto(Request $request)
    {
        $gasto = Gasto::where('id', $request->id)->first();
        $gasto->estado = 2;
        $gasto->detalle_estado = $request->detalle_estado;
        $gasto->save();
        event(new FondoRotativoEvent($gasto));
        return response()->json(['success' => 'Gasto rechazado']);
    }
    public function anular_gasto(Request $request)
    {
        $gasto = Gasto::where('id', $request->id)->first();
        $gasto->estado = 4;
        $gasto->detalle_estado = $request->detalle_estado;
        $gasto->save();
        event(new FondoRotativoEvent($gasto));
        return response()->json(['success' => 'Gasto rechazado']);
    }*/
}
