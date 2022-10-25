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
use Spatie\Permission\Models\Role;
use Src\Shared\Utils;

class EmpleadoController extends Controller
{
    private $entidad = 'Empleado';

    public function __construct()
    {
        $this->middleware('can:puede.ver.empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.empleados')->only('store');
        $this->middleware('can:puede.editar.empleados')->only('update');
        $this->middleware('can:puede.eliminar.empleados')->only('destroy');
    }

    public function list(Request $request)
    {
        $rol = $request['rol'];

        if ($rol) {
            $users_ids = User::select('id')->role($rol)->get()->map(fn ($id) => $id->id)->toArray();
            $empleados = Empleado::ignoreRequest(['rol'])->filter()->get();
            return EmpleadoResource::collection($empleados->filter(fn ($empleado) => in_array($empleado->usuario_id, $users_ids))->flatten());
        }
        return EmpleadoResource::collection(Empleado::filter()->get());
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        /* $results = EmpleadoResource::collection(Empleado::all()->except(1));
        return response()->json(compact('results')); */

        return response()->json(['results' => $this->list($request)]);
    }

    /**
     * Guardar
     */
    public function store(EmpleadoRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida: ', $request->all()]);
        //adaptacion de foreign keys
        $datos = $request->validated();
        $datos['jefe_id'] = $request->safe()->only(['jefe'])['jefe'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];

        Log::channel('testing')->info('Log', ['Datos validados', $datos]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $datos['nombres'] . ' ' . $datos['apellidos'],
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
        //Respuesta
        $empleado->update($request->validated());
        $empleado->user()->update(['status' => $request->estado == 'ACTIVO' ? true : false]);
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
        $grupo_id = $request['grupo'];
        if (!$grupo_id) {
            return response()->json(['mensaje' => 'Debe proporcionar un id de grupo']);
        }

        $results = EmpleadoResource::collection(Empleado::where('grupo_id', $grupo_id)->get());
        return response()->json(compact('results'));
    }
}
