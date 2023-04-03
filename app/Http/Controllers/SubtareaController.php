<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Http\Requests\SubtareaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\SubtareaEvent;
use App\Models\Empleado;
use App\Models\EmpleadoSubtarea;
use App\Models\GrupoSubtarea;
use App\Models\MovilizacionSubtarea;
use Src\App\SubtareaService;
use Illuminate\Http\Request;
use App\Models\Subtarea;
use App\Models\Tarea;
use Src\Shared\Utils;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;

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
        // Obtener parametros
        /*$estados = request('estados');
        $campos = explode(',', request('campos'));

        // Procesar
        if ($estados && request('campos')) return $this->servicio->obtenerFiltradosEstadosCampos($estados, $campos);
        elseif ($estados) return $this->servicio->obtenerFiltradosEstados($estados); */

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

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

        $modelo = Subtarea::create($datos);
        $empleados_adicionales = collect($datos['empleados_adicionales'])->map(fn ($empleado) => $empleado['id']);
        $modelo->empleados()->sync($empleados_adicionales);

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
        //$datos['tipo_subtarea_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
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
        // $request->isMethod('patch');
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
                $subtarea->empleado_id = null;
            } elseif ($request['modo_asignacion_trabajo'] == Subtarea::POR_EMPLEADO) {
                $subtarea->grupo_id = null;
                $subtarea->empleado_id = $request['empleado'];
            }
        }

        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        $mensaje = 'Subtarea reagendada exitosamente!';

        event(new SubtareaEvent('Subtarea agendada!', $subtarea, 1));

        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Subtarea $subtarea)
    {
        $subtarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    // Estados de las subtareas
    public function asignar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::ASIGNADO;
        $subtarea->fecha_hora_asignacion = Carbon::now();
        $subtarea->save();

        // event(new SubtareaEvent('Subtarea asignada!'));

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    // Estados de las subtareas
    public function agendar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::AGENDADO;
        $subtarea->fecha_hora_agendado = Carbon::now();
        $subtarea->save();

        event(new SubtareaEvent('Subtarea agendada!', $subtarea, 1));

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function realizar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea);

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function finalizar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::FINALIZADO;
        $subtarea->fecha_hora_finalizacion = Carbon::now();
        $subtarea->save();

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function pausar(Request $request, Subtarea $subtarea)
    {
        $motivo_pausa_id = $request['motivo_pausa_id'];
        $subtarea->estado = Subtarea::PAUSADO;
        $subtarea->save();
        //$subtarea->fecha_hora_pa = Carbon::now();

        $subtarea->pausasSubtarea()->create([
            'fecha_hora_pausa' => Carbon::now(),
            'motivo_pausa_id' => $motivo_pausa_id,
        ]);

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea);

        $modelo = new SubtareaResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function ejecutar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->fecha_hora_ejecucion = Carbon::now();
        $subtarea->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea);

        /* $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', Auth::user()->empleado->id)->where('fecha_hora_llegada', null)->orderBy('fecha_hora_salida', 'desc')->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->save();
        } */

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function reanudar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->save();

        $pausa = $subtarea->pausasSubtarea()->orderBy('fecha_hora_pausa', 'desc')->first();
        $pausa->fecha_hora_retorno = Carbon::now();
        $pausa->save();

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea);

        /* $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', Auth::user()->empleado->id)->where('fecha_hora_llegada', null)->orderBy('fecha_hora_salida', 'desc')->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->save();
        } */
    }

    public function corregir(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->save();
    }

    public function suspender(Request $request, Subtarea $subtarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];
        $subtarea->estado = Subtarea::SUSPENDIDO;
        $subtarea->save();

        $subtarea->motivoSuspendido()->attach([
            $motivo_suspendido_id => ['empleado_id' => Auth::user()->empleado->id]
        ]);

        $this->servicio->marcarTiempoLlegadaMovilizacion($subtarea);

        /* $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', Auth::user()->empleado->id)->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->save();
        } */
        // $subtarea->touch();
        return response()->json(['modelo' => new SubtareaResource($subtarea->refresh())]);
    }

    /* public function suspenderOld(Request $request, Subtarea $subtarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];

        $subtarea->estado = Subtarea::SUSPENDIDO;
        $subtarea->fecha_hora_suspendido = Carbon::now();
        $subtarea->motivo_suspendido_id = $motivo_suspendido_id;
        $subtarea->save();

        return response()->json(['modelo' => new SubtareaResource($subtarea->refresh())]);
    } */

    public function obtenerPausas(Subtarea $subtarea)
    {
        $results = $subtarea->pausasSubtarea->map(fn ($item) => [
            'fecha_hora_pausa' => $item->fecha_hora_pausa,
            'fecha_hora_retorno' => $item->fecha_hora_retorno,
            'tiempo_pausado' => $item->fecha_hora_retorno ? CarbonInterval::seconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)))->cascade()->forHumans() : null,
            'motivo' => $item->motivoPausa->motivo,
        ]);
        // 'tiempo_pausado' => $item->fecha_hora_retorno ? Utils::tiempoTranscurridoSeconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)), '') : null,

        return response()->json(compact('results'));
    }

    public function obtenerSuspendidos(Subtarea $subtarea)
    {
        $results = $subtarea->motivoSuspendido->map(fn ($item) => [
            'fecha_hora_suspendido' => Carbon::parse($item->pivot->created_at)->format('d-m-Y H:i:s'),
            'motivo' => $item->motivo,
            'empleado' => Empleado::extraerNombresApellidos(Empleado::find($item->pivot->empleado_id)),
        ]);
        // 'tiempo_pausado' => $item->fecha_hora_retorno ? Utils::tiempoTranscurridoSeconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)), '') : null,

        return response()->json(compact('results'));
    }

    public function cancelar(Request $request, Subtarea $subtarea)
    {
        $motivo_suspendido_id = $request['motivo_suspendido_id'];

        $subtarea->estado = Subtarea::CANCELADO;
        $subtarea->fecha_hora_cancelado = Carbon::now();
        $subtarea->motivo_cancelado_id = $motivo_suspendido_id;
        $subtarea->save();
        return response()->json(['modelo' => new SubtareaResource($subtarea->refresh())]);
    }

    public function reagendar(Request $request, Subtarea $subtarea)
    {
        // $nuevaFecha = $request['nueva_fecha'];

        $subtarea->estado = Subtarea::CREADO;
        $subtarea->fecha_hora_creacion = Carbon::now();
        $subtarea->save();

        event(new SubtareaEvent('Subtarea agendada!', $subtarea, 1));

        return response()->json(['modelo' => $subtarea->refresh()]);
    }
}
