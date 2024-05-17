<?php

namespace App\Http\Controllers;

use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
    public function handleCallback($driver)
    {
        $user_social = Socialite::driver($driver)->stateless()->user();
        $userDB = UserExternal::where('email', $user_social->email)->first();
        if ($userDB) {
            return $this->iniciarSesion($userDB, $user_social->id);
        } else {
            $user =  $this->registrar($user_social);
            return $this->iniciarSesion($user, $user_social->id);
        }
    }
    public function registrar($user_social)
    {
        $username =  explode("@", $user_social->email)[0];
        $user = UserExternal::create([
            'name' => $username,
            'email' =>  $user_social->email,
            'token' => $user_social->token,
            'password' => bcrypt($user_social->id),
        ]);
        $this->crearPostulante($user,$user_social);
        return $user;
    }
    private function crearPostulante(UserExternal $user, $user_social) {
        $name_socialite = explode(" ", $user_social->getName());
        $nombres = $name_socialite[0]. ' '.$name_socialite[1];
        $apellidos = $name_socialite[2]. ' '.$name_socialite[3];
        $user->postulante()->create([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'tipo_documento_identificacion' => '',
            'numero_documento_identificacion' => '',
            'telefono'=>'',
        ]);
    }
    public function iniciarSesion(UserExternal $user, $password)
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
    public function getDataFromSession()
    {
        $value = Cache::get('autenticacion');

        return response()->json(['value' => $value]);
    }
}
