<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserInfoResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('name', $request['name'])->first();
        // Log::channel('testing')->info('Log', ['Diferencia de dias: ' . $user->updated_at->diffInDays(now())]);
        if (!$user) {
            throw ValidationException::withMessages([
                '404' => ['Usuario no registrado!'],
            ]);
        }

        if ($user->updated_at->diffInDays(now()) >= 90) {
            throw ValidationException::withMessages([
                '412' => ['Ha expirado su contraseña, por favor restablecerla!'],
            ])->status(412);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Usuario o contraseña incorrectos'],
            ]);
        }

        if ($user->empleado->estado) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $modelo = new UserInfoResource($user);
            return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'Bearer', 'modelo' => $modelo], 200);
        }

        return response()->json(["mensaje" => "El usuario no esta activo"], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        /*Auth::guard('web')->logout();

        $user = User::find(Auth::id());
        $user->tokens()->where('id', $user->id)->delete();
        if ($request->session()) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();
        } else {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(["mensaje" => "Sesión finalizada"], 200); */
    }
}
