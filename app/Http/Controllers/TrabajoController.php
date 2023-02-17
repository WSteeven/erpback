<?php

namespace App\Http\Controllers;

use App\Events\SubtareaEvent;
use App\Http\Requests\TrabajoRequest;
use App\Http\Resources\TrabajoResource;
use App\Models\EmpleadoSubtarea;
use App\Models\GrupoSubtarea;
use App\Models\Trabajo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\App\TrabajoService;
use Src\Shared\Utils;

class TrabajoController extends Controller
{
    private $entidad = 'Trabajo';
    private TrabajoService $servicio;

    public function __construct()
    {
        $this->servicio = new TrabajoService();
    }

    public function list()
    {
        // Obtener parametros
        $estados = request('estados');
        $campos = explode(',', request('campos'));

        // Procesar
        if ($estados && request('campos')) return $this->servicio->obtenerFiltradosEstadosCampos($estados, $campos);
        elseif ($estados) return $this->servicio->obtenerFiltradosEstados($estados);

        return $this->servicio->obtenerTodos();
    }

    public function index()
    {
        $results = $this->list();
        return response()->json(compact('results'));
    }

    public function store(TrabajoRequest $request)
    {
        // $tarea_id = $request['tarea_id'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['trabajo_padre_id'] = $request->safe()->only(['trabajo_padre'])['trabajo_padre'];
        $datos['cliente_final_id'] = $request->safe()->only(['cliente_final'])['cliente_final'];
        $datos['coordinador_id'] = Auth::id();
        $datos['fiscalizador_id'] = $request->safe()->only(['fiscalizador'])['fiscalizador'];
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['trabajo_dependiente_id'] = $request->safe()->only(['trabajo_dependiente'])['trabajo_dependiente'];

        $datos['codigo_trabajo'] = 'TR' . Trabajo::latest('id')->first()?->id + 1;


        // $datos['codigo_subtarea'] = Trabajo::find($tarea_id)->codigo_tarea . '-' . (Trabajo::where('tarea_id', $tarea_id)->count() + 1);
        $datos['fecha_hora_creacion'] = Carbon::now();
        $modo_asignacion_trabajo = $request->safe()->only(['modo_asignacion_trabajo'])['modo_asignacion_trabajo'];

        // Calcular estados
        $datos['estado'] = Trabajo::CREADO;

        $modelo = Trabajo::create($datos);

        switch ($modo_asignacion_trabajo) {
            case Trabajo::POR_GRUPO:
                $grupos_seleccionados = $request->safe()->only(['grupos_seleccionados'])['grupos_seleccionados'];
                $grupos_seleccionados = collect($grupos_seleccionados)->map(function ($grupoSeleccionado) use ($modelo) {
                    return  [
                        'grupo_id' => $grupoSeleccionado['id'],
                        'subtarea_id' => $modelo->id,
                        'responsable' => $grupoSeleccionado['responsable'] ?? false,
                    ];
                });

                GrupoSubtarea::insert($grupos_seleccionados->toArray());
                break;
            case Trabajo::POR_EMPLEADO:
                $empleados_seleccionados = $request->safe()->only(['empleados_seleccionados'])['empleados_seleccionados'];
                $empleados_seleccionados = collect($empleados_seleccionados)->map(function ($empleadoSeleccionado) use ($modelo) {
                    return  [
                        'empleado_id' => $empleadoSeleccionado['id'],
                        'subtarea_id' => $modelo->id,
                        'responsable' => $empleadoSeleccionado['responsable'] ?? false,
                    ];
                });

                EmpleadoSubtarea::insert($empleados_seleccionados->toArray());
                break;
        }

        $modelo = new TrabajoResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Trabajo $subtarea)
    {
        $modelo = new TrabajoResource($subtarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TrabajoRequest $request, Trabajo $subtarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $modo_asignacion_trabajo = $request->safe()->only(['modo_asignacion_trabajo'])['modo_asignacion_trabajo'];

        $modelo = $subtarea->refresh();
        $subtarea->empleados()->detach();
        $subtarea->grupos()->detach();

        // Respuesta
        $subtarea->update($datos);

        switch ($modo_asignacion_trabajo) {
            case Trabajo::POR_GRUPO:
                $grupos_seleccionados = $request->safe()->only(['grupos_seleccionados'])['grupos_seleccionados'];
                $grupos_seleccionados = collect($grupos_seleccionados)->map(function ($grupoSeleccionado) use ($modelo) {
                    return  [
                        'grupo_id' => $grupoSeleccionado['id'],
                        'subtarea_id' => $modelo->id,
                        'responsable' => $grupoSeleccionado['responsable'] ?? false,
                    ];
                });

                GrupoSubtarea::insert($grupos_seleccionados->toArray());
                break;
            case Trabajo::POR_EMPLEADO:
                $empleados_seleccionados = $request->safe()->only(['empleados_seleccionados'])['empleados_seleccionados'];
                $empleados_seleccionados = collect($empleados_seleccionados)->map(function ($empleadoSeleccionado) use ($modelo) {
                    return  [
                        'empleado_id' => $empleadoSeleccionado['id'],
                        'subtarea_id' => $modelo->id,
                        'responsable' => $empleadoSeleccionado['responsable'] ?? false,
                    ];
                });

                EmpleadoSubtarea::insert($empleados_seleccionados->toArray());
                break;
        }

        $modelo = new TrabajoResource($subtarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        /* $tecnicosGrupoPrincipal = $request->safe()->only(['tecnicos_grupo_principal'])['tecnicos_grupo_principal'];
        if ($tecnicosGrupoPrincipal) {
            $tecnicosGrupoPrincipal = Utils::quitarEspaciosComasString($tecnicosGrupoPrincipal);
            $tecnicosGrupoPrincipal = Utils::convertirStringComasArray($tecnicosGrupoPrincipal);

            // Guardar id de tecnicos
            if (count($tecnicosGrupoPrincipal)) $modelo->empleados()->sync($tecnicosGrupoPrincipal);
        } else {
            $modelo->empleados()->sync([]);
        } */

        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Trabajo $subtarea)
    {
        $subtarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    // Estados de las subtareas
    public function asignar(Trabajo $subtarea)
    {
        $subtarea->estado = Trabajo::ASIGNADO;
        $subtarea->fecha_hora_asignacion = Carbon::now();
        $subtarea->save();

        event(new SubtareaEvent('Trabajo asignada!'));

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function realizar(Trabajo $subtarea)
    {
        $subtarea->estado = Trabajo::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->save();

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function finalizar(Trabajo $subtarea)
    {
        $subtarea->estado = Trabajo::FINALIZADO;
        $subtarea->fecha_hora_finalizacion = Carbon::now();
        $subtarea->save();

        $modelo = new TrabajoResource($subtarea->refresh());
        return response()->json(compact('modelo'));
    }

    public function pausar(Request $request, Trabajo $subtarea)
    {
        $motivo = $request['motivo'];
        $subtarea->estado = Trabajo::PAUSADO;
        $subtarea->save();
        //$subtarea->fecha_hora_pa = Carbon::now();

        $subtarea->pausasSubtarea()->create([
            'fecha_hora_pausa' => Carbon::now(),
            'motivo' => $motivo,
        ]);
    }

    public function ejecutar(Trabajo $subtarea)
    {
        $subtarea->estado = Trabajo::EJECUTANDO;
        $subtarea->fecha_hora_ejecucion = Carbon::now();
        $subtarea->save();

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function reanudar(Trabajo $subtarea)
    {
        $subtarea->estado = Trabajo::EJECUTANDO;
        $subtarea->save();

        $subtarea->pausasSubtarea()->update([
            'fecha_hora_retorno' => Carbon::now(),
        ]);
    }

    public function suspender(Request $request, Trabajo $subtarea)
    {
        $motivo = $request['motivo'];

        $subtarea->estado = Trabajo::SUSPENDIDO;
        $subtarea->fecha_hora_suspendido = Carbon::now();
        $subtarea->causa_suspencion = $motivo;
        $subtarea->save();

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function obtenerPausas(Trabajo $subtarea)
    {
        $results = $subtarea->pausasSubtarea->map(fn ($item) => [
            'fecha_hora_pausa' => $item->fecha_hora_pausa,
            'fecha_hora_retorno' => $item->fecha_hora_retorno,
            'tiempo_pausado' => $item->fecha_hora_retorno ? Utils::tiempoTranscurrido(Carbon::parse($item->fecha_hora_retorno)->diffInMinutes(Carbon::parse($item->fecha_hora_pausa)), '') : null,
            'motivo' => $item->motivo,
        ]);

        return response()->json(compact('results'));
    }

    public function cancelar(Request $request, Trabajo $subtarea)
    {
        $motivo = $request['motivo'];

        $subtarea->estado = Trabajo::CANCELADO;
        $subtarea->fecha_hora_cancelacion = Carbon::now();
        $subtarea->causa_cancelacion = $motivo;
        $subtarea->save();
        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function reagendar(Request $request, Trabajo $subtarea)
    {
        $nuevaFecha = $request['nueva_fecha'];

        $subtarea->estado = Trabajo::CREADO;
        $subtarea->fecha_hora_creacion = Carbon::now();
        $subtarea->save();
        return response()->json(['modelo' => $subtarea->refresh()]);
    }
}
