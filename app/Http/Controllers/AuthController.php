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

class AuthController extends Controller
{
    public function index()
    {
        $empleados = Auth::user();
        return response()->json(['modelo' => $empleados]);
    }

    public function registrar(UserRequest $request)
    {
        try {
            $request->validated();
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->nombres . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ])->assignRole($request->rol);
            $user->empleados()->create([
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e]);
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
            return response()->json(['mensaje' => 'Usuario o contraseña incorrectos']);
        }
        $user = User::where('email', $request['email'])->first();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function update(UserRequest $request, Empleado $empleado){
        $user = User::find($empleado->usuario_id);
        try{
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
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e]);
        }

        return response()->json(['mensaje' => 'El usuario ha sido actualizado con éxito', 'modelo' => new UserResource($user)]);
    }

    public function infouser(Request $request)
    {
        return $request->user();
    }
}
