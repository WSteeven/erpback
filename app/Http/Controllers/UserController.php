<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\User;
use Exception;
use DateTime;

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
