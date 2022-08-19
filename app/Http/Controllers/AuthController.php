<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /* public function verpermisos()
    {
        $user = Auth::user();
        return response()->json([
            'roles' => $user->getRoleNames(),
            'permisos' => $user->getPermissionsViaRoles(),
        ]);
    } */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        /* $user->empleados()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'sucursal_id' => '1'
        ]); */
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'mensaje' => 'Registro exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer'
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

    public function updateEmail(){
        
    }
    public function infouser(Request $request)
    {
        return $request->user();
    }
}
