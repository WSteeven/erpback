<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\UserResource;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Grupo;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;
use Src\Shared\Utils;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class EmpleadoController extends Controller
{
    private $entidad = 'Empleado';
    private EmpleadoService $servicio;
    private $reporteService;


    public function __construct()
    {
        $this->servicio = new EmpleadoService();
        $this->reporteService = new ReportePdfExcelService();

        $this->middleware('can:puede.ver.empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.empleados')->only('store');
        $this->middleware('can:puede.editar.empleados')->only('update');
        $this->middleware('can:puede.eliminar.empleados')->only('destroy');
    }

    public function list()
    {
        // Obtener parametros
        $rol = explode(',', request('rol'));
        $search = request('search');
        $campos = request('campos') ? explode(',', request('campos')) : '*';

        $user = User::find(auth()->id());

        // Devuelve al  empleado resposanble del departamento que se pase como parametro
        if (request('es_responsable_departamento')) {
            $idResponsable = Departamento::find(request('departamento_id'))->responsable_id;
            if ($idResponsable) {
                return Empleado::where('id', $idResponsable)->get($campos);
            } else return [];
        }

        // Si es de RRHH devuelve incluso de inactivos
        if ($user->hasRole([User::ROL_RECURSOS_HUMANOS])) {
            return $this->servicio->obtenerTodosSinEstado();
        }
        if ($user->hasRole([User::ROL_COORDINADOR, User::COORDINADOR_TECNICO, User::ROL_COORDINADOR_BACKUP, User::ROL_COORDINADOR_BODEGA]) && request('es_reporte__saldo_actual')) {
            return Empleado::where('jefe_id', Auth::user()->empleado->id)->get($campos);
        }



        // Procesar respuesta
        if (request('rol')) return $this->servicio->getUsersWithRoles($rol, $campos); // EmpleadoResource::collection(Empleado::whereIn('usuario_id', User::role($rol)->pluck('id'))->get());
        if (request('campos')) return $this->servicio->obtenerTodosCiertasColumnas($campos);
        if ($search) return $this->servicio->search($search);

        return $this->servicio->obtenerTodos();
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = $this->list();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(EmpleadoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['cargo_id'] = $request->safe()->only(['cargo'])['cargo'];
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];

        if ($datos['foto_url']) {
            $datos['foto_url'] = (new GuardarImagenIndividual($datos['foto_url'], RutasStorage::FOTOS_PERFILES))->execute();
        }
        if ($datos['firma_url']) {
            $datos['firma_url'] = (new GuardarImagenIndividual($datos['firma_url'], RutasStorage::FIRMAS))->execute();
        }
        try {
            DB::beginTransaction();
            $username = $this->generarNombreUsuario($datos);
            $email = $username . '@' . explode("@", $datos['email'])[1];
            $user = User::create([
                'name' => $username,
                'email' => $datos['email'],
                'password' => bcrypt($datos['password']),
            ])->assignRole($datos['roles']);
            $datos['usuario_id'] = $user->id;
            $user->empleado()->create([
                'nombres' => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'identificacion' => $datos['identificacion'],
                'telefono' => $datos['telefono'],
                'fecha_nacimiento' => new DateTime($datos['fecha_nacimiento']),
                'jefe_id' => $datos['jefe_id'],
                'canton_id' => $datos['canton_id'],
                'cargo_id' => $datos['cargo_id'],
                'coordenadas' => $datos['coordenadas'],
                'departamento_id' => $datos['departamento_id'],
                'grupo_id' => $datos['grupo_id'],
                'firma_url' => $datos['firma_url'],
                'tipo_sangre' => $datos['tipo_sangre'],
                'direccion' => $datos['direccion'],
                'estado_civil_id' => $datos['estado_civil_id'],
                'correo_personal' => $datos['correo_personal'],
                'area_id' => $datos['area_id'],
                'num_cuenta_bancaria' => $datos['num_cuenta_bancaria'],
                'salario' => $datos['salario'],
                'fecha_ingreso' => $datos['fecha_ingreso'],
                'fecha_vinculacion' => $datos['fecha_ingreso'],
                'fecha_salida' => $datos['fecha_salida'] ? $datos['fecha_salida'] : null,
                'tipo_contrato_id' => $datos['tipo_contrato_id'],
                'tiene_discapacidad' => $datos['tiene_discapacidad'],
                'observacion' => $datos['observacion'],
                'nivel_academico' => $datos['nivel_academico'],
                'supa' => $datos['supa'],
                'talla_zapato' => $datos['talla_zapato'],
                'talla_camisa' => $datos['talla_camisa'],
                'talla_guantes' => $datos['talla_guantes'],
                'talla_pantalon' => $datos['talla_pantalon'],
                'banco' => $datos['banco'],
                'genero' => $datos['genero'],
                'esta_en_rol_pago' => $datos['esta_en_rol_pago'],
                'acumula_fondos_reserva' => $datos['acumula_fondos_reserva'],
                'realiza_factura' => $datos['realiza_factura'],
            ]);

            //$esResponsableGrupo = $request->safe()->only(['es_responsable_grupo'])['es_responsable_grupo'];
            //$grupo = Grupo::find($datos['grupo_id']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // Log::channel('testing')->info('Log', ['ERROR', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()]);
        }

        $modelo = new EmpleadoResource($user->empleado);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }
    public function datos_empleado($id)
    {
        $empleado = Empleado::find($id);
        return response()->json(compact('empleado'));
    }
    public function HabilitaEmpleado(Request $request)
    {
        $empleado = Empleado::find($request->id);
        $empleado->estado = $request->estado == 'true' ? 1 : 0;
        $empleado->save();
        $modelo = $empleado;
        return response()->json(compact('modelo'));
    }
    public function existeResponsableGrupo(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|numeric|integer',
        ]);

        $responsableActualGrupo = Empleado::where('grupo_id', $request['grupo_id'])->where('es_responsable_grupo', true)->first();

        if ($responsableActualGrupo) {
            throw ValidationException::withMessages([
                'responsable_grupo' => ['Ya existe un empleado designado como responsable del grupo. ¿Desea reemplazarlo por el empleado actual?'],
            ]);
        }

        return response()->isOk();
    }

    /**
     * Consultar
     */
    public function show(Empleado $empleado)
    {
        // Log::channel('testing')->info('Log', ['Consultaste un empleado', $empleado]);
        $modelo = new EmpleadoResource($empleado);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(EmpleadoRequest $request, Empleado  $empleado)
    {
        // Log::channel('testing')->info('Log', ['request recibida para update', $request->all()]);
        //Respuesta
        $datos = $request->validated();
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['cargo_id'] = $request->safe()->only(['cargo'])['cargo'];
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        if ($datos['foto_url'] && Utils::esBase64($datos['foto_url'])) {
            $datos['foto_url'] = (new GuardarImagenIndividual($datos['foto_url'], RutasStorage::FOTOS_PERFILES))->execute();
        } else {
            unset($datos['foto_url']);
        }
        if ($datos['firma_url'] && Utils::esBase64($datos['firma_url'])) {
            $datos['firma_url'] = (new GuardarImagenIndividual($datos['firma_url'], RutasStorage::FIRMAS))->execute();
        } else {
            unset($datos['firma_url']);
        }

        $empleado->update($datos);
        $empleado->user->syncRoles($datos['roles']);

        if (!is_null($request->password)) {
            // Log::channel('testing')->info('Log', ['La contraseña es nula??', is_null($request->password)]);
            $empleado->user()->update([
                /*'name' => $request->usuario,
                'email' => $request->email,*/
                'password' => bcrypt($request->password),
            ]);
        }

        $empleado->user()->update([
            'name' => $request->usuario,
            'email' => $request->email,
        ]);

        // $empleado->user()->update(['status' => $request->estado === 'ACTIVO' ? true : false]);
        $modelo = new EmpleadoResource($empleado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicos(Request $request)
    {
        $request->validate(['grupo' => 'required|numeric|integer']);
        $grupo = $request['grupo'];

        $results = $this->servicio->obtenerTecnicosPorGrupo($grupo);
        return response()->json(compact('results'));
    }


    public function designarLiderGrupo(Request $request, Empleado $empleado)
    {
        $request->validate([
            'grupo' => 'required|numeric|integer',
        ]);

        if ($empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO) && $empleado->grupo_id == $request['grupo']) {
            throw ValidationException::withMessages([
                '403' => ['No puede designar a la misma persona'],
            ]);
        }

        if ($empleado->grupo_id != $request['grupo']) {
            throw ValidationException::withMessages([
                '403' => ['No se puede designar como líder porque no pertence al grupo seleccionado.'],
            ]);
        }

        DB::transaction(function () use ($request, $empleado) {
            // Buscar lider del grupo actual
            $empleadosGrupoActual = Empleado::where('grupo_id', $request['grupo'])->get();
            $liderActual = $empleadosGrupoActual->filter(fn ($item) => $item->user->hasRole(User::ROL_LIDER_DE_GRUPO));

            if ($liderActual) $liderActual->first()->user->removeRole(User::ROL_LIDER_DE_GRUPO);
            // if ($liderActual[0]) $liderActual[0]->user->removeRole(User::ROL_LIDER_DE_GRUPO);

            $empleado->user->assignRole(User::ROL_LIDER_DE_GRUPO);
        });

        // Empleados
        /* $empleados = Grupo::find($request['grupo'])->empleados;
        $jefeActual = null;

        // Buscar lider de cuadrilla actual
        foreach ($empleados as $empleado) {
            $esTecnico = $empleado->user->hasRole(User::ROL_TECNICO);
            $empleado->cargo = $empleado;

            $es_lider = in_array(User::ROL_TECNICO_JEFE_CUADRILLA, $empleado->user->getRoleNames()->toArray()); //()->with(User::ROL_TECNICO_JEFE_CUADRILLA)->first();

            if ($es_lider) $jefeActual = $empleado->user;
        }

        $nuevoJefe = User::find(Empleado::find($request['nuevo_jefe'])->usuario_id);

        if (Empleado::find($request['nuevo_jefe'])->grupo_id !== $request['grupo']) {
            throw ValidationException::withMessages([
                'error_grupo' => ['Este empleado no pertenece al grupo seleccionado. Asígnelo y vuelva a intentar.'],
            ]);
        }

        // Validar que nuevo jefe no sea secretario
        if (in_array(User::ROL_TECNICO_SECRETARIO, $nuevoJefe->getRoleNames()->toArray())) {
            throw ValidationException::withMessages([
                'secretario' => ['Este empleado tiene rol de Secretario de cuadrilla. Cámbielo y vuelva a intentar.'],
            ]);
        } */

        // Quitar rol de jefe al actual
        /*$nuevosRolesAnteriorJefe = $jefeActual->getRoleNames()->filter(fn ($item) => $item !== User::ROL_LIDER_DE_GRUPO);
        $jefeActual->syncRoles($nuevosRolesAnteriorJefe);

        // Agregar rol de jefe a nuevo jefed
        $nuevosRolesNuevoJefe = $nuevoJefe->getRoleNames()->push(User::ROL_LIDER_DE_GRUPO);
        $nuevoJefe->syncRoles($nuevosRolesNuevoJefe);*/

        $modelo = new EmpleadoResource($empleado->refresh());
        $mensaje = 'Nuevo líder de grupo designado exitosamente!';
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function designarSecretarioGrupo(Request $request)
    {
        /* $request->validate([
            'grupo' => 'required|numeric|integer',
            'nuevo_jefe' => 'required|numeric|integer',
        ]);

        // Empleados
        $empleados = Grupo::find($request['grupo'])->empleados;
        $secretarioActual = null;

        // Buscar secretario actual
        foreach ($empleados as $empleado) {
            $es_secretario = in_array(User::ROL_TECNICO_SECRETARIO, $empleado->user->getRoleNames()->toArray()); //()->with(User::ROL_TECNICO_JEFE_CUADRILLA)->first();
            if ($es_secretario) $secretarioActual = $empleado->user;
        }

        $nuevoJefe = User::find(Empleado::find($request['nuevo_jefe'])->usuario_id);

        Log::channel('testing')->info('Log', ['Grupo empleado ', Empleado::find($request['nuevo_jefe'])->grupo_id]);
        Log::channel('testing')->info('Log', ['Grupo asignado ', $request['grupo']]);

        // Validar que el empleado pertenezca al grupo seleccionado
        if (Empleado::find($request['nuevo_jefe'])->grupo_id !== $request['grupo']) {
            throw ValidationException::withMessages([
                'error_grupo' => ['Este empleado no pertenece al grupo seleccionado. Asígnelo y vuelva a intentar.'],
            ]);
        }

        // Validar que nuevo jefe no sea secretario
        if (in_array(User::ROL_TECNICO_JEFE_CUADRILLA, $nuevoJefe->getRoleNames()->toArray())) {
            throw ValidationException::withMessages([
                'jefe_cuadrilla' => ['Este empleado tiene rol de Jefe de cuadrilla. Cámbielo y vuelva a intentar.'],
            ]);
        }

        // Quitar rol de secretario al actual
        $nuevosRolesAnteriorJefe = $secretarioActual->getRoleNames()->filter(fn ($item) => $item !== User::ROL_TECNICO_SECRETARIO);
        $secretarioActual->syncRoles($nuevosRolesAnteriorJefe);

        // Agregar rol de secretario a nuevo secretario
        $nuevosRolesNuevoJefe = $nuevoJefe->getRoleNames()->push(User::ROL_TECNICO_SECRETARIO);
        $nuevoJefe->syncRoles($nuevosRolesNuevoJefe);

        return response()->json(['mensaje' => 'Nuevo secretario asignado exitosamente!']); */
    }
    public function empleadosRoles(Request $request)
    {
        $results = [];
        $roles = [];
        if (!is_null($request->roles)) {
            $roles = explode(',', $request->roles);
            $results = UserResource::collection(User::role($roles)->with('empleado')->whereHas('empleado', function ($query) {
                $query->where('estado', true);
            })->get());
        }
        return response()->json(compact('results'));
    }
    public function empleadoPermisos(Request $request)
    {
        $permisos = [];
        $results = [];
        if (!is_null($request->permisos)) {
            $permisos = explode(',', $request->permisos);
            $permisos_consultados = Permission::whereIn('name', $permisos)->get();
            $results = UserResource::collection(User::permission($permisos_consultados)->with('empleado')->get());
        }
        return response()->json(compact('results'));
    }
    public function imprimir_reporte_general_empleado()
    {
        $reportes = Empleado::where('estado', 1)
            ->where('id', '>', 2)
            ->where('esta_en_rol_pago', '1')
            ->where('realiza_factura', '0')
            ->where('salario', '!=', 0)
            ->orderBy('area_id', 'asc')
            ->orderBy('apellidos', 'asc')
            ->get();
        $results = Empleado::empaquetarListado($reportes);
        $nombre_reporte = 'lista_empleados';
        $vista = 'recursos-humanos.empleados';
        return $this->reporteService->imprimir_reporte('pdf', 'A4', 'landscape', compact('results'), $nombre_reporte, $vista, null);
    }
    /**
     * La función genera un nombre de usuario único basado en el nombre de pila y verifica si ya existe
     * en la base de datos.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP. Contiene información como los campos de entrada
     * enviados en un formulario o los parámetros pasados en la URL.
     *
     * @return el nombre de usuario generado.
     */
    function generarNombreUsuario($request)
    {
        $nombreUsuario = $request['usuario'];
        $nombres = str_replace('ñ', 'n', $request['nombres']);
        $apellidos = str_replace('ñ', 'n', $request['apellidos']);
        // Comprobamos si el nombre de usuario ya existe
        $query = User::where('name', $nombreUsuario)->get();
        $username = $nombreUsuario;
        $inicio_username = '';
        if ($query->count() > 0) {
            // Separamos el nombre y el apellido en dos cadenas
            $nombre = explode(" ", $nombres);
            $apellido = explode(" ", $apellidos);
            $inicio_username = $nombre[1][0];
            $username = $nombre[0][0] . $inicio_username . $apellido[0];
            $contador = 1;
            while (User::where('name',  $username)->count() > 0) {
                if ($contador <= strlen($nombre[0])) {
                    $inicio_username .= $nombre[0][$contador];
                    $username = $inicio_username . $nombre[1][0] . $apellido[0];
                    $contador++;
                }
            }
        }
        return $username;
    }
    function obtenerNombreUsuario(Request $request)
    {
        $datos = $request->validate(['nombres' => 'required', 'string', 'apellidos' => 'required|string', 'usuario' => 'required|string']);
        $username = $this->generarNombreUsuario($datos);
        return response()->json(compact('username'));
    }

    function obtenerEmpleadosFondosRotativos(Request $request)
    {
        try {
            Log::channel('testing')->info('Log', ['request', $request->all()]);
            $empleados = Empleado::has('gastos')->get();
            Log::channel('testing')->info('Log', ['encontrados', $empleados]);
            Log::channel('testing')->info('Log', ['resource', EmpleadoResource::collection($empleados)]);

            $results = EmpleadoResource::collection($empleados);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en obtenerEmpleadosFondosRotativos', $th->getMessage(), $th->getLine()]);
            throw new ValidationException($th->getMessage());
        }
        response()->json(compact('results'));
    }
}
