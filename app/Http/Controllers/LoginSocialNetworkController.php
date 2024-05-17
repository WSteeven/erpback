<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Src\App\RecursosHumanos\SeleccionContratacion\Oauth2Service;

class LoginSocialNetworkController extends Controller
{
    public function login($driver)
    {
        $oauth2Service = new Oauth2Service($driver);
        return response()->json(['url' => $oauth2Service->obtenerUrl()], 200);
    }
    public function handleCallback($driver, Request $request)
    {
        $user_social = Socialite::driver($driver)->stateless()->user();
        $userDB = User::where('email', $user_social->email)->first();
        if ($userDB) {
            return $this->iniciarSesion($userDB, $user_social->id, $request);
        } else {
            $user =  $this->registrar($user_social);
            return $this->iniciarSesion($user, $user_social->id, $request);
        }
    }
    public function registrar($user_social)
    {
        $username =  explode("@", $user_social->email)[0];
        $user = User::create([
            'name' => $username,
            'email' =>  $user_social->email,
            'password' => bcrypt($user_social->id),
        ]);
        return $user;
    }
    public function iniciarSesion(User $user, $password, Request $request)
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Usuario o contraseña incorrectos'],
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $modelo = $user;
        $postData = ['access_token' => $token, 'token_type' => 'bearer', 'modelo' => $modelo];
        Cache::put('autenticacion', $postData);
        $externalUrl = 'http://localhost:8080/puestos-disponibles';
        // Redireccionar al usuario a la página externa
        return redirect()->away($externalUrl);
    }
    public function getDataFromSession(Request $request)
    {
        $value = Cache::get('autenticacion');
        return response()->json(['value' => $value]);
    }
}
