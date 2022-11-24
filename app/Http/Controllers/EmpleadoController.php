<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;
use Src\Shared\Utils;

class EmpleadoController extends Controller
{
    private $entidad = 'Empleado';
    private EmpleadoService $servicio;

    public function __construct()
    {
        $this->servicio = new EmpleadoService();
        /*$this->middleware('can:puede.ver.empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.empleados')->only('store');
        $this->middleware('can:puede.editar.empleados')->only('update');
        $this->middleware('can:puede.eliminar.empleados')->only('destroy');*/
    }

    public function list()
    {
        // Obtener parametros
        $page = request('page');
        $offset = request('offset');
        $rol = request('rol');
        $search = request('search');
        $campos = explode(',', request('campos'));

        // Procesar
        if (request('campos')) {
            return $this->servicio->obtenerTodosCiertasColumnas($campos);
        } else
        if (auth()->user()->hasRole(User::ROL_RECURSOS_HUMANOS)) {
            if ($page) return $this->servicio->obtenerPaginacionTodos($offset);
            return $this->servicio->obtenerTodosSinEstado();
        } else {
            if ($page) return $this->servicio->obtenerPaginacion($offset);
            return $this->servicio->obtenerTodosSinEstado();
        }
        if ($search) return $this->servicio->search($search);
        if ($rol) return $this->servicio->obtenerEmpleadosPorRol($rol);
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
                'sucursal_id' => $datos['sucursal_id']
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()]);
        }

        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje'));
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
        Log::channel('testing')->info('Log', ['request recibida para update', $request->all()]);
        //Respuesta

        $empleado->update($request->validated());

        if (!is_null($request->password)) {
            Log::channel('testing')->info('Log', ['La contraseña es nula??', is_null($request->password)]);
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
}
