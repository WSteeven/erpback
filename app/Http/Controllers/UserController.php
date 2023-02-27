<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\User;
use Exception;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $results = User::where('id', '<>', 1)->orderBy('id', 'asc')->get();
        Log::channel('testing')->info('Log', ['Resultados consultados: ', $results]);

        return response()->json(['modelo' => UserResource::collection($results)]);
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
    public function recuperarPassword(Request $request){
        $email = $request->input('email');
        $usuario = User::where('email', $email)->first();
        if($usuario){
            $username =  explode("@", $email)[0];
            $confirmation_code = Str::random(9);
            $credenciales =[
                'email' => $email,
                'username' =>  $username,
                'confirmation_code' => $confirmation_code
            ];
        $usuario->remember_token = $confirmation_code;
        $usuario->save();
        Mail::send('email.recoveryPassword',$credenciales, function($msj) use($email,$username){
            $msj->to($email,$username);
            $msj->subject('Recuperacion de Contraseña de JPCONSTRUCRED');
        });
        return response()
        ->json('Porfavor revise su codigo de confirmacion en su  Correo Institucional ');
        }
        else {
            return response()->json('Correo Institucional no existe',401);
        }
    }
    public function updateContrasenaRecovery (Request $request){
        $code = $request->input('code');
        $contrasena_usuario =  Hash::make($request->input('contrasena_usuario'));
        $users = User::where('confirmation_code', $code)->first();
        if ($users == null) {
            return response()->json('Correo no verificado',401);
        }
        $confirmation_code = ' ';
        $users->confirmation_code = $confirmation_code;
        $users->password = $contrasena_usuario;
        return response()
            ->json('Contraseña Actualizada con exito');
    }
}
