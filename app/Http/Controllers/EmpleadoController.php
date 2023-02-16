<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\UserResource;
use App\Models\Empleado;
use App\Models\Grupo;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\EmpleadoService;
use Src\Shared\Utils;

class EmpleadoController extends Controller
{
    private $entidad = 'Empleado';
    private EmpleadoService $servicio;

    public function __construct()
    {
        $this->servicio = new EmpleadoService();
        $this->middleware('can:puede.ver.empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.empleados')->only('store');
        $this->middleware('can:puede.editar.empleados')->only('update');
        $this->middleware('can:puede.eliminar.empleados')->only('destroy');
    }

    public function list()
    {
        // Obtener parametros
        $page = request('page');
        $offset = request('offset');
        $rol = request('rol');
        $search = request('search');
        $campos = explode(',', request('campos'));

        $user = User::find(auth()->id());

        if ($user->hasRole(User::ROL_RECURSOS_HUMANOS)) {
            if ($page) return $this->servicio->obtenerPaginacionTodos($offset);
            return $this->servicio->obtenerTodosSinEstado();
        }

        // return $this->servicio->obtenerTodosSinEstado();

        // Procesar respuesta
        if (request('campos')) return $this->servicio->obtenerTodosCiertasColumnas($campos);
        if ($page) return $this->servicio->obtenerPaginacion($offset);
        if ($rol) return $this->servicio->obtenerEmpleadosPorRol($rol);
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
        // Log::channel('testing')->info('Log', ['Request recibida: ', $request->all()]);
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['cargo_id'] = $request->safe()->only(['cargo'])['cargo'];

        // Log::channel('testing')->info('Log', ['Datos validados', $datos]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $datos['usuario'],
                'email' => $datos['email'],
                'password' => bcrypt($datos['password']),
            ])->assignRole($datos['roles']);
            $datos['usuario_id'] = $user->id;
            Log::channel('testing')->info('Log', ['Datos validados 2:', $datos]);
            $user->empleado()->create([
                'nombres' => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'identificacion' => $datos['identificacion'],
                'telefono' => $datos['telefono'],
                'fecha_nacimiento' => new DateTime($datos['fecha_nacimiento']),
                'jefe_id' => $datos['jefe_id'],
                'sucursal_id' => $datos['sucursal_id'],
                'cargo_id' => $datos['cargo_id']
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()]);
        }

        $modelo = new UserResource($user->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Empleado $empleado)
    {
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
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];

        $empleado->update($datos);

        if (!is_null($request->password)) {
            // Log::channel('testing')->info('Log', ['La contraseña es nula??', is_null($request->password)]);
            $empleado->user()->update([
                'name' => $request->usuario,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        }

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


    public function intercambiarJefeCuadrilla(Request $request)
    {
        $request->validate([
            'grupo' => 'required|numeric|integer',
            'nuevo_jefe' => 'required|numeric|integer',
        ]);

        // Empleados
        $empleados = Grupo::find($request['grupo'])->empleados;
        $jefeActual = null;

        // Buscar lider de cuadrilla actual
        foreach ($empleados as $empleado) {
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
        }

        // Quitar rol de jefe al actual
        $nuevosRolesAnteriorJefe = $jefeActual->getRoleNames()->filter(fn ($item) => $item !== User::ROL_TECNICO_JEFE_CUADRILLA);
        $jefeActual->syncRoles($nuevosRolesAnteriorJefe);

        // Agregar rol de jefe a nuevo jefed
        $nuevosRolesNuevoJefe = $nuevoJefe->getRoleNames()->push(User::ROL_TECNICO_JEFE_CUADRILLA);
        $nuevoJefe->syncRoles($nuevosRolesNuevoJefe);

        return response()->json(['mensaje' => 'Nuevo jefe de cuadrilla asignado exitosamente']);
    }

    public function intercambiarSecretarioCuadrilla(Request $request)
    {
        $request->validate([
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

        return response()->json(['mensaje' => 'Nuevo secretario asignado exitosamente!']);
    }
}
