<?php

namespace Src\App;

use App\Helpers\Filtros\FiltroSearchHelper;
use App\Models\Empleado;
use App\Models\MovilizacionSubtarea;
use App\Models\Subtarea;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Src\App\Sistema\PaginationService;
use Src\Config\Constantes;

class SubtareaService
{
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /*    public function guardarSubtarea(SubtareaRequest $request)
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
        }*/

    /*    public function obtenerFiltradosEstadosCampos($estados, $campos)
        {
            $estados = explode(',', $estados);
            $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->get($campos);
            // Log::channel('testing')->info('Log', ['subtareas filtradas por estado', $results]);
            return $results;
        }*/

/*    public function obtenerFiltradosEstados($estados)
    {
        $estados = explode(',', $estados);
        $results = Subtarea::ignoreRequest(['estados', 'campos'])->filter()->whereIn('estado', $estados)->orderBy('fecha_hora_asignacion', 'asc')->get();
        return $results;
    }*/

/*    public function obtenerPaginacion($offset)
    {
        $filter = Subtarea::ignoreRequest(['offset'])->filter()->orderBy('fecha_hora_creacion', 'desc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }*/

/*    public function obtenerAsignadasPaginacion(Empleado $empleado, $offset)
    {
        $filter = $empleado->subtareas()->ignoreRequest(['offset'])->filter()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->simplePaginate($offset);
        SubtareaResource::collection($filter);
        return $filter;
    }*/

    /*public function obtenerTodos()
    {
        $results = Subtarea::ignoreRequest(['estados'])->filter()->orderBy('fecha_hora_creacion', 'desc')->get();
        return SubtareaResource::collection($results);
    }*/

/*    public function obtenerTodosOld()
    {
        $usuario = Auth::user();
        $esCoordinador = $usuario->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = $usuario->hasRole(User::ROL_COORDINADOR_BACKUP);
        $esJefeTecnico = $usuario->hasRole(User::ROL_JEFE_TECNICO);

        // Monitor
        if (!request('tarea_id') && $esCoordinador && !$esCoordinadorBackup && $esJefeTecnico) {
            // $results = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos'])->filter()->latest()->get();
            $results = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos'])->filter()->get(); //->orderBy('fecha_hora_agendado', 'desc')->get();
            return SubtareaResource::collection($results);
        }

        // Control de tareas
        $results = Subtarea::ignoreRequest(['campos'])->filter()->latest()->get();
        return SubtareaResource::collection($results);
        // Log::channel('testing')->info('Log', ['if', 'Dentro de if']);
    }*/

    // ----> Aqui me quedé para hacer la paginacion <------
/*    public function obtenerTodosOldAntesAlgolia()
    {
        $usuario = Auth::user();
        $esCoordinador = $usuario->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = $usuario->hasRole(User::ROL_COORDINADOR_BACKUP);
        $esJefeTecnico = $usuario->hasRole(User::ROL_JEFE_TECNICO);

        $search = request('search');
        $paginate = request('paginate');

        // Monitor
        if (!request('tarea_id') && $esCoordinador && !$esCoordinadorBackup && !$esJefeTecnico) {
            if ($search) $query = $usuario->empleado->subtareasCoordinador()->search($search);
            else $query = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos', 'paginate'])->filter();

            if ($paginate) return $this->paginationService->paginate($query, 100, request('page'));
            else return $query->get();
        }

        // Control de tareas
        if ($search) $query = Subtarea::search($search)->where('estado', request('estado'));
        else $query = Subtarea::ignoreRequest(['campos', 'paginate'])->filter()->latest();

        Log::channel('testing')->info('Log', ['paginate', $paginate]);
        if ($paginate) return $this->paginationService->paginate($query, 100, request('page'));
        else return $query->get();
    }*/

    /**
     * @throws Exception
     */
    public function obtenerTodos()
    {
        $usuario = Auth::user();
        $esCoordinador = $usuario->hasRole(User::ROL_COORDINADOR);
        $esCoordinadorBackup = $usuario->hasRole(User::ROL_COORDINADOR_BACKUP);
        $esJefeTecnico = $usuario->hasRole(User::ROL_JEFE_TECNICO);

        $search = request('search');
        $paginate = request('paginate');

        // Monitor
        if (!request('tarea_id') && $esCoordinador && !$esCoordinadorBackup && !$esJefeTecnico) {
            if ($search) $query = $usuario->empleado->subtareasCoordinador();
            else $query = $usuario->empleado->subtareasCoordinador()->ignoreRequest(['campos', 'paginate'])->filter();

            // if ($paginate) return $this->paginationService->paginate($query, 100, request('page'));
            //else return $query->get();
            $filtros = [
                ['clave' => 'estado', 'valor' => request('estado')],
            ];
            $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);
//            Log::channel('testing')->info('Log', ['DENTRO', $filtros]);
            return buscarConAlgoliaFiltrado(Subtarea::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtros);
            // return TareaResource::collection($results);
        }

        // Control de tareas
        if ($search) $query = Subtarea::where('estado', request('estado'));
        else $query = Subtarea::ignoreRequest(['campos', 'paginate'])->filter()->latest();

        /*if ($paginate) return $this->paginationService->paginate($query, 100, request('page'));
        else return $query->get();*/
        $filtros = [
            ['clave' => 'estado', 'valor' => request('estado')],
        ];

        $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);
//        Log::channel('testing')->info('Log', ['FUERA', $filtros]);
        return buscarConAlgoliaFiltrado(Subtarea::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtros);
    }

    public function marcarTiempoLlegadaMovilizacion(Subtarea $subtarea, Request $request)
    {
        $idEmpleadoResponsable = $request['empleado_responsable_subtarea'];
        $idCoordinadorRegistranteLlegada = $request['coordinador_registrante_llegada'];

        $movilizacion = MovilizacionSubtarea::where('subtarea_id', $subtarea->id)->where('empleado_id', $idEmpleadoResponsable)->whereNull('fecha_hora_llegada')->orderBy('fecha_hora_salida', 'desc')->first();

        if ($movilizacion) {
            $movilizacion->fecha_hora_llegada = Carbon::now();
            $movilizacion->coordinador_registrante_llegada = $idCoordinadorRegistranteLlegada;
            $movilizacion->estado_subtarea_llegada = $request['estado_subtarea_llegada'];
            $movilizacion->latitud_llegada = $request['latitud_llegada'];
            $movilizacion->longitud_llegada = $request['longitud_llegada'];
            $movilizacion->save();
        }
    }

/*    public function puedeRealizar(Subtarea $subtarea)
    {
        $ids = TipoTrabajo::where('descripcion', 'STANDBY')->pluck('id')->toArray();

        if (!in_array($subtarea->tipo_trabajo_id, $ids)) {
            /*if ($subtarea->trabajosRealizados->count() < 1)
                throw ValidationException::withMessages([
                    'pocas_actividades' => ['Ingrese al menos  actividades en el formulario de seguimiento!'],
                ]);*

            if ($subtarea->tarea->cliente_id == ClientesCorporativos::NEDETEL) {
                if ($subtarea->archivosSeguimiento->count() === 0)
                    throw ValidationException::withMessages([
                        'archivo_requerido' => ['Debe subir al menos un archivo en el formulario de seguimiento!'],
                    ]);
            }
        }
    }*/

    /**
     * @throws ValidationException
     */
    public function puedeIniciarHora(Subtarea $subtarea)
    {
        $horaInicio = Carbon::parse($subtarea->hora_inicio_trabajo)->format('H:i:s');

        if (Carbon::now()->format('H:i:s') < $horaInicio) // Si puede ejecutar en la fecha ya se valida en el resource
            throw ValidationException::withMessages([
                'hora_inicio_trabajo' => ['Debe esperar a que sean las ' . $subtarea->hora_inicio_trabajo . ' para ejecutar la subtarea'],
            ]);
    }

    /**
     * Obtiene el id de un empleado en base al grupo.
     *
     * Busca dentro del grupo indicado un empleado que tenga el rol `ROL_LIDER_DE_GRUPO`.
     * - Si se encuentra un líder de grupo, se retorna su `id`.
     * - Si no hay líder pero existen empleados en el grupo, se retorna el `id` del primer empleado.
     * - Si el grupo no tiene empleados, se lanza una excepción.
     *
     * @param int $grupoId Id del grupo a consultar.
     * @return int Id del empleado seleccionado.
     * @throws Exception Si no hay empleados asignados al grupo seleccionado.
     */
    public static function obtenerEmpleadoEnBaseAgrupo(int $grupoId)
    {
        $empleados = Empleado::where('grupo_id', $grupoId)->get();
        foreach ($empleados as $empleado) {
            if ($empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO)) {
                return $empleado->id;
            }
        }

        if ($empleados->count() < 1) throw new Exception('No hay empleados asignados al grupo seleccionado.');

        // Si no se encontró un líder de grupo, retornar el primer empleado del grupo
        return $empleados->first()->id;
    }

    /**
     * Verifica si una subtarea con el título dado ya ha sido creada.
     * Pondremos especial enfasis en separar las cadenas de numeros correspondientes al AID y al numero de actividad.
     * Ejm: JPC-G004GPON // INSTALACIONES UM CONECEL CLARO // 12-11-2025 // AID: 4404775 // ACTIVIDAD: 20004496280030
     * Donde aid=7 longitud
     * actividad=14 longitud
     *
     * @param string $titulo El título de la subtarea a verificar.
     * @return bool
     */
    public function verificarSubtareaCreada(string $titulo)
    {
        if (Subtarea::where('titulo', $titulo)->exists()) return true;

        $aid = null;
        $actividad = null;

        // Buscar AID de exactamente 7 dígitos (prioriza coincidencias etiquetadas)
        if (preg_match('/\bAID[:\s]*([0-9]{7})\b/i', $titulo, $m)) {
            $aid = $m[1];
        } elseif (preg_match('/\b([0-9]{7})\b/', $titulo, $m)) {
            $aid = $m[1];
        }

        // Buscar ACTIVIDAD de exactamente 14 dígitos (prioriza coincidencias etiquetadas)
        if (preg_match('/\bACTIVIDAD[:\s]*([0-9]{14})\b/i', $titulo, $m)) {
            $actividad = $m[1];
        } elseif (preg_match('/\b([0-9]{14})\b/', $titulo, $m)) {
            $actividad = $m[1];
        }

        if ($aid && $actividad) {
            $query = Subtarea::where(function ($q) use ($aid) {
                $q->orWhere('titulo', 'LIKE', "%AID: $aid%")
                    ->orWhere('titulo', 'LIKE', "%$aid%");
            })->where(function ($qu) use ($actividad) {
                $qu->orWhere('titulo', 'LIKE', "%ACTIVIDAD: $actividad%")
                    ->orWhere('titulo', 'LIKE', "%$actividad%");

            });
            if ($query->exists()) {
                return true;
            }
        }

        return false;
    }
}
