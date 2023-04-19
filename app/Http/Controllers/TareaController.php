<?php

namespace App\Http\Controllers;

use App\Http\Requests\TareaRequest;
use App\Http\Resources\TareaResource;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\UbicacionTarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\SubtareaService;
use Src\Shared\Utils;
use stdClass;

class TareaController extends Controller
{
    private $entidad = 'Tarea';
    // private SubtareaService $subtareaService;

    public function __construct()
    {
        // $this->subtareaService = new SubtareaService();
    }

    public function listar()
    {
        $campos = explode(',', request('campos'));
        // $esCoordinador = Auth::user()->empleado->cargo->nombre == User::coor;
        $esCoordinador = User::find(Auth::id())->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = User::find(Auth::id())->hasRole(User::ROL_COORDINADOR_BACKUP);
        // $esJefeTecnico = User::find(Auth::id())->hasRole(User::ROL_JEFE_TECNICO);

        if (request('campos')) {
            if ($esCoordinadorBackup) return Tarea::ignoreRequest(['campos'])->filter()->latest()->get($campos);
            if ($esCoordinador) return Tarea::ignoreRequest(['campos'])->filter()->porCoordinador()->latest()->get($campos);
            else return Tarea::ignoreRequest(['campos'])->filter()->latest()->get($campos);
        } else {
            if ($esCoordinadorBackup) return Tarea::filter()->latest()->get();
            if ($esCoordinador) return Tarea::filter()->porCoordinador()->latest()->get();
            else return Tarea::filter()->latest()->get(); // Cualquier usuario en el sistema debe tener acceso a las tareas
            // if ($esCoordinador && $esJefeTecnico) $results = Tarea::filter()->get();
        }
    }

    /*********
     * Listar
     *********/
    public function index()
    {

        $results = $this->listar();
        $results = TareaResource::collection($results);
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

            // Establecer coordinador
            $esCoordinadorBackup = Auth::user()->hasRole(User::ROL_COORDINADOR_BACKUP);
            if ($esCoordinadorBackup) $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            else $datos['coordinador_id'] = Auth::user()->empleado->id;

            // Log::channel('testing')->info('Log', ['Datos de Tarea antes de guardar', $datos]);

            $modelo = Tarea::create($datos);
            // Log::channel('testing')->info('Log', ['Datos de Tarea despues de guardar', $datos]);

            // $subtarea = $datos['subtarea'];

            // Si la tarea no tiene subtareas, se crea una subtarea por defecto
            /* if (!$datos['tiene_subtareas'] && $subtarea) {
                Log::channel('testing')->info('Log', ['Datos de Tarea despues de guardar', 'dentro de if']);
                $tarea_id = $modelo->id;
                // Adpatacion de foreign keys para Subtarea
                $subtarea['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);
                $subtarea['tipo_trabajo_id'] = $subtarea['tipo_trabajo'];
                $subtarea['tarea_id'] = $tarea_id;
                $subtarea['grupo_id'] = $subtarea['grupo'];
                $subtarea['empleado_id'] = $subtarea['empleado'];
                $subtarea['fecha_inicio_trabajo'] = Carbon::parse($subtarea['fecha_inicio_trabajo'])->format('Y-m-d');
                $subtarea['fecha_hora_creacion'] = Carbon::now();
                $subtarea['estado'] = Subtarea::CREADO;

                // Primera subtarea
                $modeloSubtarea = Subtarea::create($subtarea);

                // Asignar
                $modeloSubtarea->estado = Subtarea::ASIGNADO;
                $modeloSubtarea->fecha_hora_asignacion = Carbon::now();

                // Agendar
                $modeloSubtarea->estado = Subtarea::AGENDADO;
                $modeloSubtarea->fecha_hora_agendado = Carbon::now();
                $modeloSubtarea->save();

                // event(new SubtareaEvent('Subtarea agendada!'));
            } */

            DB::commit();

            $modelo = new TareaResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
    // Log::channel('testing')->info('Log', ['Ubicacion', $ubicacionTarea]);

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
        if ($request->isMethod('patch')) {
            $tarea->update($request->except(['id']));
        }

        // Adaptacion de foreign keys
        // $datos = $request->validated();

        // $tarea->finalizado = $request->safe()->only(['finalizado'])['finalizado'];
        // $tarea->novedad = $request['novedad'];
        // $tarea->save();

        // Respuesta
        $modelo = new TareaResource($tarea->refresh());
        $mensaje = 'Tarea finalizada exitosamente'; //Utils::obtenerMensaje($this->entidad, 'update', false);
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
}
