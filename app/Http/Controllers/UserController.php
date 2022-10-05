<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Empleado;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['modelo' => UserResource::collection(User::all()->except(1))]);
    }

    public function store(UserRequest $request)
    {
        try {
            $request->validated();
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->nombres . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ])->assignRole($request->roles);
            $user->empleado()->create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'identificacion' => $request->identificacion,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => new DateTime($request->fecha_nacimiento),
                'jefe_id' => $request->jefe_id,
                'sucursal_id' => $request->sucursal_id,
                'grupo_id' => $request->grupo_id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'mensaje' => 'Registro exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'modelo' => new UserResource($user)
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['mensaje' => 'Usuario o contraseña incorrectos'], 401);
        }
        
        $user = User::where('email', $request['email'])->where('status', true)->first();
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'Bearer'], 200);
        }

        return response()->json(["mensaje"=>"El usuario no esta activo"], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function show(Empleado $empleado)
    {
        $user = User::find($empleado->usuario_id);
        return response()->json(['modelo' => new UserResource($user)]);
    }

    public function update(UserRequest $request, Empleado $empleado)
    {
        $user = User::find($empleado->usuario_id);
        try {
            $request->validated();
            DB::beginTransaction();
            $user->update([
                'name' => $request->nombres . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->empleados()->update([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'identificacion' => $request->identificacion,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => new DateTime($request->fecha_nacimiento),
                'jefe_id' => $request->jefe_id,
                'sucursal_id' => $request->sucursal_id,
                'grupo_id' => $request->grupo_id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro', "excepción" => $e]);
        }

        return response()->json(['mensaje' => 'El empleado ha sido actualizado con éxito', 'modelo' => new UserResource($user)]);
    }
}
