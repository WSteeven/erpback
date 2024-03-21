<?php

namespace Src\App;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\Seguimiento;
use App\Models\SeguimientoSubtarea;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\TipoTrabajo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Src\Config\ClientesCorporativos;

class SubtareaService
{
    public function __construct()
    {
    }

    public function guardarSubtarea(SubtareaRequest $request)
    {
        $tarea_id = $request['tarea'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);
        $datos['subtarea_dependiente_id'] = $request->safe()->only(['subtarea_dependiente'])['subtarea_dependiente'];
        // $modo_asignacion_trabajo = $request->safe()->only(['modo_asignacion_trabajo'])['modo_asignacion_trabajo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
        $datos['fecha_hora_creacion'] = Carbon::now();

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

        return Subtarea::create($datos);
    }

    public function obtenerFiltradosEstadosCampos($estados, $campos)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
        // Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
        return $results;
    }

    public function obtenerFiltradosEstados($estados)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->orderBy('fecha_hora_asignacion', 'asc')->get();
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_creacion', 'desc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }

    /*public function obtenerTodos()
    {
        $results = Subtarea::ignoreRequest(['estados'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        return SubtareaResource::collection($results);
    }*/

    public function obtenerTodos()
    {
        $usuario = Auth::user();
        $esCoordinador = $usuario->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = $usuario->hasRole(User::ROL_COORDINADOR_BACKUP);

        // Monitor
        if (!request('tarea_id') && $esCoordinador && !$esCoordinadorBackup) {
            // $results = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos'])->filter()->latest()->get();
            $results = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos'])->filter()->get(); //->orderBy('fecha_hora_agendado', 'desc')->get();
            return SubtareaResource::collection($results);
        }

        // Control de tareas
        $results = Subtarea::ignoreRequest(['campos'])->filter()->latest()->get();
        return SubtareaResource::collection($results);
    }

    public function marcarTiempoLlegadaMovilizacion(Subtarea $subtarea, Request $request)
    {
        $idEmpleadoResponsable = $request['empleado_responsable_subtarea'];
        $idCoordinadorRegistranteLlegada = $request['coordinador_registrante_llegada'];

        $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', $idEmpleadoResponsable)->where('fecha_hora_llegada', null)->orderBy('fecha_hora_salida', 'desc')->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->coordinador_registrante_llegada = $idCoordinadorRegistranteLlegada;
            $movilizacion->estado_subtarea_llegada = $request['estado_subtarea_llegada'];
            $movilizacion->latitud_llegada = $request['latitud_llegada'];
            $movilizacion->longitud_llegada = $request['longitud_llegada'];
            $movilizacion->save();
        }
    }

    public function puedeRealizar(Subtarea $subtarea)
    {
        $ids = TipoTrabajo::where('descripcion', 'STANDBY')->pluck('id')->toArray();

        if (!in_array($subtarea->tipo_trabajo_id, $ids)) {
            if ($subtarea->trabajosRealizados->count() < 1)
                throw ValidationException::withMessages([
                    'pocas_actividades' => ['Ingrese al menos tres actividades en el formulario de seguimiento!'],
                ]);

            if ($subtarea->tarea->cliente_id == ClientesCorporativos::NEDETEL) {
                if ($subtarea->archivosSeguimiento->count() === 0)
                    throw ValidationException::withMessages([
                        'archivo_requerido' => ['Debe subir al menos un archivo en el formulario de seguimiento!'],
                    ]);
            }
        }
    }
}
