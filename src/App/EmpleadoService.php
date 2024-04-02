<?php

namespace Src\App;

use App\Http\Resources\EmpleadoResource;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class EmpleadoService
{
    public function __construct()
    {
    }

    public function getUsersWithRoles($roles, $campos)
    {
        $idUsers = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->pluck('id');

        // return EmpleadoResource::collection(Empleado::whereIn('usuario_id', $idUsers)->get($campos));
        return Empleado::whereIn('usuario_id', $idUsers)->get($campos);

        // return $users;
    }
    /* BORRAR public function obtenerEmpleadosPorRol(string $rol)
    {
        $users_ids = User::select('id')->role($rol)->get()->map(fn ($id) => $id->id)->toArray();
        $empleados = Empleado::ignoreRequest(['rol'])->filter()->where('estado', true)->get();
        $results = $empleados->filter(fn ($empleado) => in_array($empleado->usuario_id, $users_ids))->flatten();
        EmpleadoResource::collection($results);
        return $results;
    } */

    public function obtenerPaginacion($offset)
    {
        $results = Empleado::where('id', '<>', 1)->where('estado', true)->simplePaginate($offset);
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerPaginacionTodos($offset)
    {
        $results = Empleado::where('id', '<>', 1)->simplePaginate($offset);
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerTodos()
    {
        $results = Empleado::ignoreRequest(['rol'])->filter()->where('id', '>', 1)->get(
            [
                'id',
                'identificacion',
                'nombres',
                'apellidos',
                'telefono',
                'jefe_id',
                'canton_id',
                'direccion',
                'fecha_nacimiento',
                'estado',
                'grupo_id',
                'cargo_id',
                'area_id',
                'departamento_id',
                'firma_url',
                'foto_url',
                'convencional',
                'telefono_empresa',
                'extension',
                'coordenadas',
                'casa_propia',
                'vive_con_discapacitados',
                'responsable_discapacitados',
                'area_id',
                'fecha_vinculacion',
                'tipo_contrato_id',
                'tiene_discapacidad',
                'observacion',
                'esta_en_rol_pago',
                'acumula_fondos_reserva',
                'realiza_factura',
                'usuario_id',
                'num_cuenta_bancaria',
                'salario',
                'supa'
            ]
        );
        // Log::channel('testing')->info('Log', ['Empleado', $results]);
        return EmpleadoResource::collection($results);
    }

    public function obtenerTodosCiertasColumnas($campos)
    {
        // Log::channel('testing')->info('Log', ['Campos #2: ', $campos]);
        $indice = array_search('responsable_departamento', $campos);
        if ($indice) unset($campos[$indice]);

        $results = Empleado::ignoreRequest(['campos', 'es_reporte__saldo_actual'])->filter()->where('id', '>', 1)->get($campos);
        $ids = $this->obtenerIdsResponsablesDepartamentos();

        if ($indice) {
            $results = $results->map(function ($empleado) use ($ids) {
                $empleado['responsable_departamento'] = in_array($empleado->id, $ids);
                return $empleado;
            });
        }

        return $results;
    }

    private function obtenerIdsResponsablesDepartamentos()
    {
        return Departamento::has('responsable')->pluck('responsable_id')->toArray();
    }

    public function obtenerIdsEmpleadosPorRol(string $rol)
    {
        // $usuario_ac->hasRole('RECURSOS HUMANOS')
        $idsUsuariosRRHH = User::role($rol)->pluck('id');
        return Empleado::whereIn('usuario_id', $idsUsuariosRRHH)->pluck('id');

        // return Departamento::where('nombre', 'RECURSOS HUMANOS')->pluck('responsable_id')->toArray();
    }

    public function obtenerTodosSinEstado()
    {
        $results = Empleado::ignoreRequest(['rol', 'campos', 'es_reporte__saldo_actual'])->filter()->where('id', '>', 1)->get();
        return EmpleadoResource::collection($results);
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicosPorGrupo(int $grupo)
    {
        return EmpleadoResource::collection(Empleado::where('grupo_id', $grupo)->where('estado', true)->get());
    }

    public function search(string $search)
    {
        return EmpleadoResource::collection(Empleado::search($search)->where('estado', true)->get());
    }

    /**
     * La función obtiene varios valores financieros relacionados con un empleado específico para un
     * sistema de fondos rotativos.
     *
     * @param Empleado $empleado Toma un objeto `Empleado` y recupera (saldo_inicial, acreditaciones,
     * gastos, saldo_actual, transferencias enviadas y recibidas) relacionados con ese empleado.
     *
     * @return array Se devuelve una serie de valores relacionados con las transacciones de fondos de un
     * empleado específico. La matriz incluye el estado activo del empleado, el nombre, el id, el
     * saldo inicial, el total de acreditaciones, los gastos totales, el total de transferencias
     * enviadas, el total de transferencias recibidas, el saldo actual, la diferencia calculada entre
     * el saldo inicial y las transacciones, y una bandera que indica si hay una inconsistencia. entre
     * la diferencia calculada y el saldo actual.
     */
    public function obtenerValoresFondosRotativosEmpleado(Empleado $empleado)
    {
        $row['activo'] = $empleado->estado;
        $row['empleado'] = $empleado->nombres . ' ' . $empleado->apellidos;
        $row['empleado_id'] = $empleado->id;
        $row['saldo_inicial'] = SaldoGrupo::where('id_usuario', $empleado->id)->where('fecha', '2023-03-31')->first()?->saldo_actual;
        $row['acreditaciones'] = Acreditaciones::where('id_usuario', $empleado->id)->where('id_estado', 1)->sum('monto');
        $row['gastos'] = Gasto::where('id_usuario', $empleado->id)->where('estado', 1)->sum('total');
        $row['transferencias_enviadas'] = Transferencias::where('usuario_envia_id', $empleado->id)->where('estado', 1)->sum('monto');
        $row['transferencias_recibidas'] = Transferencias::where('usuario_recibe_id', $empleado->id)->where('estado', 1)->sum('monto');
        $row['ajustes_saldos_positivo'] = AjusteSaldoFondoRotativo::where('destinatario_id', $empleado->id)->where('tipo', AjusteSaldoFondoRotativo::INGRESO)->sum('monto');
        $row['ajustes_saldos_negativo'] = AjusteSaldoFondoRotativo::where('destinatario_id', $empleado->id)->where('tipo', AjusteSaldoFondoRotativo::EGRESO)->sum('monto');
        $row['ajustes_saldos'] = $row['ajustes_saldos_positivo'] - $row['ajustes_saldos_negativo'];
        $row['saldo_actual'] = SaldoGrupo::where('id_usuario', $empleado->id)->orderBy('id', 'desc')->first()->saldo_actual;
        $row['diferencia'] = round(($row['saldo_inicial'] + $row['acreditaciones'] + $row['transferencias_recibidas']  - $row['gastos'] - $row['transferencias_enviadas']), 2); //la suma de ingresos menos egresos, al final este valor debe coincidir con saldo_actual
        $row['inconsistencia'] = $row['diferencia'] == $row['saldo_actual'] ? 'NO' : 'SI'; //false : true;
        return $row;
    }

    /**
     * La función "obtenerValoresFondosRotativos" recupera valores de fondos rotativos de los empleados
     * que tienen saldo.
     *
     * @return mixed Se están devolviendo una serie de valores por los fondos rotativos de los empleados.
     */
    public function obtenerValoresFondosRotativos()
    {
        try {
            $empleados = Empleado::has('saldo')->get();
            $results = [];
            foreach ($empleados as $index => $empleado) {
                $row = $this->obtenerValoresFondosRotativosEmpleado($empleado);
                $results[$index] = $row;
            }
            return $results;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * La función obtiene empleados con el último saldo de fondos rotatorios.
     *
     * @return mixed La función `obtenerEmpleadosConSaldoFondosRotativos()` está devolviendo una colección de
     * empleados que tienen el último saldo en su cuenta de rotación de fondos. La consulta filtra los
     * empleados según el `id` máximo en la tabla `saldo_grupo` para cada empleado y luego recupera
     * esos empleados usando el método `get()`.
     */
    public function obtenerEmpleadosConSaldoFondosRotativos()
    {
        $empleados = Empleado::whereHas('saldo', function ($query) {
            $query->whereRaw('id = (SELECT MAX(id) FROM saldo_grupo WHERE id_usuario = empleados.id)')
                ->where('saldo_actual', '<>', 0);
        })->ignoreRequest(['campos'])->filter()->get();

        return $empleados;
    }
    public static function eliminarUmbralFondosRotativos(Empleado $empleado)
    {
        $umbral = UmbralFondosRotativos::where('empleado_id', $empleado->id)->first();
        if ($umbral && !$empleado->estado) {
            $umbral->delete();
        }
    }
}
