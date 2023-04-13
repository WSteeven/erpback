<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserInfoResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\User;
use Exception;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function index()
    {
        $results = User::where('id', '<>', 1)->orderBy('id', 'asc')->get();
        // Log::channel('testing')->info('Log', ['Resultados consultados: ', $results]);

        return response()->json(['modelo' => UserResource::collection($results)]);
    }
    public function listaUsuarios()
    {
        return response()->json(['results' => UserResource::collection(User::all()->except(1))]);
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
    public function resetearPassword(Request $request)
    {
       $user = User::where('name',strtoupper($request->nombreUsuario))->first();
        if (!$user) {
            throw ValidationException::withMessages([
                '404' => ['Usuario no registrado!'],
            ]);
        }
        Log::channel('testing')->info('Log', ['Usuario consultado: ', $user]);
        if (!$user || !Hash::check($request->password_old, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Usuario o contraseña incorrectos'],
            ]);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['mensaje' => 'La contraseña ha sido actualizada con éxito', 'modelo' => new UserResource($user)]);
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
    public function autorizationUser()
    {
        $user = Auth::user();
        $jefe = Empleado::where('id',$user->empleado->jefe_id)->first()->usuario_id;
        $users = User::role('AUTORIZADOR')->where('users.id', '!=', $user->id)->where('users.id','!=',$jefe)->orderby('users.name', 'asc')->get();
        return response()->json(['results' => UserInfoResource::collection($users)]);
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
        $contrasena_usuario =  Hash::make($request->input('password'));
        $users = User::where('remember_token', $code)->first();
        if ($users == null) {
            return response()->json('Correo no verificado',401);
        }
        $confirmation_code = ' ';
        $users->remember_token = $confirmation_code;
        $users->password = $contrasena_usuario;
        $users->save();
        return response()
            ->json('Contraseña Actualizada con exito');
    }
    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (!$user || !Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['La contraseña actual es incorrecta.'],
            ]);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['mensaje' => 'La contraseña ha sido actualizada con éxito', 'modelo' => new UserResource($user)]);
    }
}
