<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Http\Requests\SubtareaRequest;
use Illuminate\Support\Facades\Auth;
use App\Events\SubtareaEvent;
use Src\App\SubtareaService;
use Illuminate\Http\Request;
use Carbon\CarbonInterval;
use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use Src\Shared\Utils;
use Carbon\Carbon;

class SubtareaController extends Controller
{
    private $entidad = 'Subtarea';
    private SubtareaService $servicio;

    public function __construct()
    {
        $this->servicio = new SubtareaService();
    }

    public function list()
    {

        return $this->servicio->obtenerTodos();
    }

    public function index()
    {
        $results = $this->list();
        return response()->json(compact('results'));
    }

    public function store(SubtareaRequest $request)
    {
        $tarea_id = $request['tarea'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);
        $datos['subtarea_dependiente_id'] = $request->safe()->only(['subtarea_dependiente'])['subtarea_dependiente'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
        $datos['fecha_hora_creacion'] = Carbon::now();
        $datos['fecha_inicio_trabajo'] = Carbon::parse($request->safe()->only(['fecha_inicio_trabajo'])['fecha_inicio_trabajo'])->format('Y-m-d');
        $datos['empleados_designados'] = $request['empleados_designados'];

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

        $modelo = Subtarea::create($datos);

        $modelo = new SubtareaResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Subtarea $subtarea)
    {
        $modelo = new SubtareaResource($subtarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Las subareas no se pueden editar
     */
    public function update(SubtareaRequest $request, Subtarea $subtarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_subtarea_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $modo_asignacion_trabajo = $request->safe()->only(['modo_asignacion_trabajo'])['modo_asignacion_trabajo'];

        $modelo = $subtarea->refresh();
        $subtarea->empleados()->detach();
        $subtarea->grupos()->detach();

        // Respuesta
        $subtarea->update($datos);

        $modelo = new SubtareaResource($subtarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function actualizarFechasReagendar(Request $request, Subtarea $subtarea)
    {
        $request->validate([
            'fecha_inicio_trabajo' => 'required|string',
        ]);

        // Adaptacion de foreign keys
        $fechaInicioTrabajo = Carbon::parse($request['fecha_inicio_trabajo'])->format('Y-m-d');
        $horaInicioTrabajo = $request['hora_inicio_trabajo'];
        $horaFinTrabajo = $request['hora_fin_trabajo'];

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
                $subtarea->empleado_id = $request['empleado'];
                $subtarea->empleados_designados = $request['empleados_designados'];
            } elseif ($request['modo_asignacion_trabajo'] == Subtarea::POR_EMPLEADO) {
                $subtarea->grupo_id = null;
                $subtarea->empleado_id = $request['empleado'];
            }
        }

        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        $mensaje = 'Subtarea reagendada exitosamente!';

        // event(new SubtareaEvent('Subtarea agendada!', $subtarea, 1));

        return response()->json(compact('modelo', 'mensaje'));
    }

    /***********
     * Eliminar
     ***********/
    public function destroy(Subtarea $subtarea)
    {
        $subtarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function asignar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::ASIGNADO;
        $subtarea->fecha_hora_asignacion = Carbon::now();
        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function agendar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::AGENDADO;
        $subtarea->fecha_hora_agendado = Carbon::now();
        $subtarea->save();

        event(new SubtareaEvent($subtarea, User::ROL_TECNICO));

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function realizar(Request $request, Subtarea $subtarea)
    {
        // Validar si se puede realizar
        // $this->servicio->puedeRealizar($subtarea);

        $subtarea->estado = Subtarea::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->causa_intervencion_id = $request['causa_intervencion_id'];
        $subtarea->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea, $request);

        $modelo = new SubtareaResource($subtarea->refresh());
        event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo'));
    }

    public function ejecutar(Request $request, Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->fecha_hora_ejecucion = Carbon::now();
        $subtarea->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea, $request);

        $modelo = new SubtareaResource($subtarea->refresh());

        event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));

        return response()->json(compact('modelo'));
    }

    public function pausar(Request $request, Subtarea $subtarea)
    {
        $motivo_pausa_id = $request['motivo_pausa_id'];
        $subtarea->estado = Subtarea::PAUSADO;
        $subtarea->save();

        $subtarea->pausasSubtarea()->create([
            'fecha_hora_pausa' => Carbon::now(),
            'motivo_pausa_id' => $motivo_pausa_id,
        ]);

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea, $request);

        $modelo = new SubtareaResource($subtarea->refresh());
        event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo'));
    }

    public function reanudar(Request $request, Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->save();

        $pausa = $subtarea->pausasSubtarea()->orderBy('fecha_hora_pausa', 'desc')->first();
        $pausa->fecha_hora_retorno = Carbon::now();
        $pausa->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea, $request);

        $modelo = new SubtareaResource($subtarea->refresh());
        event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo'));
    }

    public function suspender(Request $request, Subtarea $subtarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];
        $subtarea->estado = Subtarea::SUSPENDIDO;
        $subtarea->save();

        $subtarea->motivoSuspendido()->attach([
            $motivo_suspendido_id => ['empleado_id' => Auth::user()->empleado->id]
        ]);

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea, $request);

        // event(new SubtareaEvent('Subtarea suspendida!', $subtarea, 1));

        $modelo = new SubtareaResource($subtarea->refresh());
        event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo'));
    }

    public function cancelar(Request $request, Subtarea $subtarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];

        $subtarea->estado = Subtarea::CANCELADO;
        $subtarea->fecha_hora_cancelado = Carbon::now();
        $subtarea->motivo_cancelado_id = $motivo_suspendido_id;
        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function reagendar(Request $request, Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::CREADO;
        $subtarea->fecha_hora_creacion = Carbon::now();
        $subtarea->save();

        // event(new SubtareaEvent('Subtarea agendada!', $subtarea, 1));

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function finalizar(Request $request, Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::FINALIZADO;
        $subtarea->fecha_hora_finalizacion = Carbon::now();
        $subtarea->causa_intervencion_id = $request['causa_intervencion_id'];
        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    /*******************
     * Obtener listados
     *******************/
    public function obtenerPausas(Subtarea $subtarea)
    {
        $results = $subtarea->pausasSubtarea->map(fn ($item) => [
            'fecha_hora_pausa' => $item->fecha_hora_pausa,
            'fecha_hora_retorno' => $item->fecha_hora_retorno,
            'tiempo_pausado' => $item->fecha_hora_retorno ? CarbonInterval::seconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)))->cascade()->forHumans() : null,
            'motivo' => $item->motivoPausa->motivo,
        ]);

        return response()->json(compact('results'));
    }

    public function obtenerSuspendidos(Subtarea $subtarea)
    {
        $results = $subtarea->motivoSuspendido->map(fn ($item) => [
            'fecha_hora_suspendido' => Carbon::parse($item->pivot->created_at)->format('d-m-Y H:i:s'),
            'motivo' => $item->motivo,
            'empleado' => Empleado::extraerNombresApellidos(Empleado::find($item->pivot->empleado_id)),
        ]);

        return response()->json(compact('results'));
    }

    /* public function puedeRealizar(Subtarea $subtarea) {
        return $this->servicio->puedeRealizar($subtarea);
    } */
}
