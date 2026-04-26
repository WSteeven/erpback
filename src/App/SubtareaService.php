<?php

namespace Src\App;

use App\Helpers\Filtros\FiltroSearchHelper;
use App\Models\Cliente;
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

    /*
     |--------------------------------------------------------------------------
     | MONITOR
     |--------------------------------------------------------------------------
     */
    if (!request('tarea_id') && $esCoordinador && !$esCoordinadorBackup && !$esJefeTecnico) {

        if ($search) {
            $query = $usuario->empleado->subtareasCoordinador();
        } else {
            $query = $usuario->empleado->subtareasCoordinador()
                ->ignoreRequest(['campos', 'paginate'])
                ->filter();
        }

        $filtros = [
            ['clave' => 'estado', 'valor' => request('estado')],
        ];

        $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);

        return buscarConAlgoliaFiltrado(
            Subtarea::class,
            $query,
            'id',
            $search,
            Constantes::PAGINATION_ITEMS_PER_PAGE,
            request('page'),
            !!$paginate,
            $filtros
        );
    }

    /*
     |--------------------------------------------------------------------------
     | CONTROL DE TAREAS
     |--------------------------------------------------------------------------
     */
    if ($search) {
        $query = Subtarea::where('estado', request('estado'));
    } else {
        $query = Subtarea::ignoreRequest(['campos', 'paginate'])
            ->filter()
            ->latest();
    }

    $filtros = [
        ['clave' => 'estado', 'valor' => request('estado')],
    ];

    /*
     |--------------------------------------------------------------------------
     | FILTRO GLOBAL POR CLIENTE (REGLA ESTRUCTURAL REAL)
     |--------------------------------------------------------------------------
     */
    if (!$esCoordinadorBackup && !$esJefeTecnico) {

        $empleadoId = $usuario->empleado->id;

        $clienteIds = Cliente::whereJsonContains('coordinadores', $empleadoId)
            ->pluck('id')
            ->toArray();

        if (!empty($clienteIds)) {

            // 🔐 APLICAR SEGURIDAD EN ELOQUENT
            $query->whereHas('tarea', function ($q) use ($clienteIds) {
                $q->whereIn('cliente_id', $clienteIds);
            });

            // Mantener también filtro para Algolia
            foreach ($clienteIds as $index => $clienteId) {
                $filtros[] = [
                    'clave' => 'cliente_id',
                    'valor' => $clienteId,
                    'operador' => $index === 0 ? 'AND' : 'OR'
                ];
            }

        } else {

            // 🔐 No tiene clientes → no ve nada
            $query->where('cliente_id', -1);

            $filtros[] = [
                'clave' => 'cliente_id',
                'valor' => -1,
                'operador' => 'AND'
            ];
        }
    }

    $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);

    return buscarConAlgoliaFiltrado(
        Subtarea::class,
        $query,
        'id',
        $search,
        Constantes::PAGINATION_ITEMS_PER_PAGE,
        request('page'),
        !!$paginate,
        $filtros
    );
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
