<?php

namespace App\Http\Controllers;

use App\Events\TareaEvent;
use App\Http\Requests\TareaRequest;
use App\Http\Resources\TareaResource;
use App\Models\Empleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\Tareas\CentroCosto;
use App\Models\UbicacionTarea;
use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\SubtareaService;
use Src\Shared\Utils;
use stdClass;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\TareaService;
use Src\Config\ClientesCorporativos;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;

class TareaController extends Controller
{
    private $entidad = 'Tarea';
    private TareaService $tareaService;

    public function __construct()
    {
        $this->tareaService = new TareaService();
    }

    public function listar()
    {
        $campos = explode(',', request('campos'));
        $esCoordinador = User::find(Auth::id())->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = User::find(Auth::id())->hasRole(User::ROL_COORDINADOR_BACKUP);

        // mejorar codigo
        // Lista las tareas disponibles junto con las que hayan finalizado.
        // Las tareas finalizadas estan disponibles un dia luego de finalizarse
        /* if (request('formulario')) {
            return Tarea::ignoreRequest(['campos', 'formulario'])->filter()->where('finalizado', false)->orWhere(function ($query) {
                $query->where('finalizado', true)->disponibleUnaHoraFinalizar();
            })->latest()->get();
        } */

        if (request('formulario')) {
            /* return Tarea::ignoreRequest(['campos', 'formulario'])->filter()->where('finalizado', false)->orWhere(function ($query) {
                $query->where('finalizado', true)->disponibleUnaHoraFinalizar();
            })->latest()->get(); */
            return $this->tareaService->obtenerTareasAsignadasEmpleadoLuegoFinalizar(request('empleado_id'));
        }

        if (request('activas_empleado')) return $this->tareaService->obtenerTareasAsignadasEmpleado(request('empleado_id'));

        if (request('campos')) {
            if ($esCoordinadorBackup) return Tarea::ignoreRequest(['campos'])->filter()->latest()->get($campos);
            if ($esCoordinador) return Tarea::ignoreRequest(['campos'])->filter()->porCoordinador()->latest()->get($campos);
            else return Tarea::ignoreRequest(['campos'])->filter()->latest()->get($campos);
        } else {
            if ($esCoordinadorBackup) return Tarea::filter()->latest()->get();
            if ($esCoordinador) return Tarea::filter()->porCoordinador()->latest()->get();
            else return Tarea::filter()->latest()->get(); // Cualquier usuario en el sistema debe tener acceso a las tareas
        }
    }

    /*********
     * Listar
     *********/
    public function index()
    {
        $results = $this->listar();
        if (!request('campos')) $results = TareaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**********
     * Guardar - Coordinador
     **********/
    public function store(TareaRequest $request)
    {
        DB::beginTransaction();

        try {
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['cliente_final_id'] = $request->safe()->only(['cliente_final'])['cliente_final'];
            $datos['ruta_tarea_id'] = $request->safe()->only(['ruta_tarea'])['ruta_tarea'];
            $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            $datos['fiscalizador_id'] = $request->safe()->only(['fiscalizador'])['fiscalizador'];
            $datos['codigo_tarea'] = 'TR' . (Tarea::count() == 0 ? 1 : Tarea::latest('id')->first()->id + 1);
            $para_cliente_proyecto = $request['para_cliente_proyecto'];
            if ($request->centro_costo) $datos['centro_costo_id'] = $request->safe()->only(['centro_costo'])['centro_costo'];
            else $datos['centro_costo_id'] = $request->no_lleva_centro_costo ? null : CentroCosto::crearCentroCosto('TR' . (Tarea::count() == 0 ? 1 : Tarea::latest('id')->first()->id + 1), $request->cliente, false);

            // Establecer coordinador
            $esCoordinadorBackup = Auth::user()->hasRole(User::ROL_COORDINADOR_BACKUP);
            if ($esCoordinadorBackup && $para_cliente_proyecto === Tarea::PARA_CLIENTE_FINAL) $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            else $datos['coordinador_id'] = Auth::user()->empleado->id;

            // Log::channel('testing')->info('Log', ['Datos de Tarea antes de guardar', $datos]);

            $modelo = Tarea::create($datos);

            DB::commit();

            $modelo = new TareaResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            Log::channel('testing')->info('Log', ['Excepcion', $e]);
            DB::rollBack();
        }
    }

    /**
     * Consultar
     */
    public function show(Tarea $tarea)
    {
        $modelo = new TareaResource($tarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, Tarea $tarea)
    {
        DB::beginTransaction();

        try {
            if ($request->isMethod('patch')) {
                if ($request['imagen_informe']) {
                    $guardar_imagen = new GuardarImagenIndividual($request['imagen_informe'], RutasStorage::TAREAS);
                    $request['imagen_informe'] = $guardar_imagen->execute();
                }

                $actualizado = $tarea->update($request->except(['id']));

                if ($actualizado && $tarea->cliente_id != ClientesCorporativos::TELCONET) $this->tareaService->transferirMaterialTareaAStockEmpleados($tarea->refresh());
                // if ($actualizado) $this->tareaService->transferirMaterialTareaAStockEmpleados($tarea->refresh());
            }

            // Respuesta
            $modelo = new TareaResource($tarea->refresh());
            $mensaje = 'Tarea finalizada exitosamente';

            $destinatarios = DB::table('subtareas')->where('tarea_id', $tarea->id)->pluck('empleado_id');

            foreach ($destinatarios as $destinatario) {
                event(new TareaEvent($tarea, Auth::user()->empleado->id, $destinatario));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy', false);
        return response()->json(compact('mensaje'));
    }

    /**
     * Aqui ingresan únicamente aquellas tareas que no tienen subtareas
     */
    // creo q se va a borrar
    public function actualizarFechasReagendar(Request $request, Tarea $tarea)
    {
        $request->validate([
            'fecha_inicio_trabajo' => 'required|string',
            'grupo' => 'nullable|numeric|integer',
            'empleado' => 'nullable|numeric|integer',
        ]);

        // Adaptacion de foreign keys
        $fechaInicioTrabajo = Carbon::parse($request['fecha_inicio_trabajo'])->format('Y-m-d');
        $horaInicioTrabajo = $request['hora_inicio_trabajo'];
        $horaFinTrabajo = $request['hora_fin_trabajo'];

        $subtarea = $tarea->subtareas()->first();

        // Respuesta
        $subtarea->fecha_inicio_trabajo = $fechaInicioTrabajo;
        $subtarea->hora_inicio_trabajo = $horaInicioTrabajo;
        $subtarea->hora_fin_trabajo = $horaFinTrabajo;
        $subtarea->estado = Subtarea::AGENDADO;
        $subtarea->fecha_hora_agendado = Carbon::now();

        // Modificar designacion del trabajo
        if ($request['grupo'] || $request['empleado']) {
            $subtarea->modo_asignacion_trabajo = $request['modo_asignacion_trabajo'];

            if ($request['modo_asignacion_trabajo'] == Subtarea::POR_GRUPO) {
                $subtarea->grupo_id = $request['grupo'];
                $subtarea->empleado_id = null;
            } elseif ($request['modo_asignacion_trabajo'] == Subtarea::POR_EMPLEADO) {
                $subtarea->grupo_id = null;
                $subtarea->empleado_id = $request['empleado'];
            }
        }

        $subtarea->save();

        $modelo = new TareaResource($tarea->refresh());
        $mensaje = 'Tarea reagendada exitosamente!';

        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Aqui ingresan únicamente aquellas tareas que no tienen subtareas
     */
    // creo q se va a borrar
    public function cancelar(Request $request, Tarea $tarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];

        $subtarea = $tarea->subtareas()->first();

        $subtarea->estado = Subtarea::CANCELADO;
        $subtarea->fecha_hora_cancelado = Carbon::now();
        $subtarea->motivo_cancelado_id = $motivo_suspendido_id;
        $subtarea->save();

        $modelo = new TareaResource($tarea->refresh());
        $mensaje = 'Tarea reagendada exitosamente!';

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function verificarTodasSubtareasFinalizadas(Request $request)
    {
        $tarea = Tarea::find($request['tarea_id']);
        $totalSubtareasNoFinalizadas = $tarea->subtareas()->whereIn('estado', [Subtarea::AGENDADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO, Subtarea::SUSPENDIDO])->count();
        $estan_finalizadas = $totalSubtareasNoFinalizadas == 0;
        return response()->json(compact('estan_finalizadas'));
    }

    public function verificarMaterialTareaDevuelto()
    {
        // $idEmpleado = request('empleado_id');
        $idTarea = request('tarea_id');

        $materiales = MaterialEmpleadoTarea::where('tarea_id', $idTarea)->get();
        $materialesConStock = $materiales->filter(fn ($material) => $material->cantidad_stock > 0);
        $materiales_devueltos = $materialesConStock->count() == 0;
        //        Log::channel('testing')->info('Log', compact('materialesConStock'));
        //      Log::channel('testing')->info('Log', compact('materiales_devueltos'));
        return response()->json(compact('materiales_devueltos'));
    }

    /**
     * Coordinador A transfiere sus tareas activas a Coordinador B, usualmente porque coordinador A sale de vacaciones.
     */
    public function transferirMisTareasActivas(Request $request)
    {
        $request->validate([
            'actual_coordinador' => 'required|numeric|integer',
            'nuevo_coordinador' => 'required|numeric|integer',
        ]);

        $nuevoCoordinador = request('nuevo_coordinador');
        $actualCoordinador = request('actual_coordinador');

        $tareas = Empleado::find($actualCoordinador)->tareasCoordinador()->where('finalizado', false)->update(['coordinador_id' => $nuevoCoordinador]);

        // Log::channel('testing')->info('Log', compact('tareas'));
        return response()->json(['mensaje' => 'Transferencia de tareas realizada exitosamente!']);
    }
}
