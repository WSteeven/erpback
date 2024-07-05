<?php

namespace App\Http\Controllers;

use App\Http\Resources\Externos\UserExternalInfoResource;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\UserExternalResource;
use App\Http\Resources\UserInfoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Src\App\RecursosHumanos\SeleccionContratacion\Oauth2Service;

class LoginSocialNetworkController extends Controller
{
    public function login(Request $request)
    {
        if ($request->boolean('oauth')) {
            // Entra aquí cuando es un inicio de sesión por aplicaciones de terceros como: facebook, google, twitter, linkedin
            Log::channel('testing')->info('Log', ['autenticacion de 3ros', request()->all()]);

            return response()->json(["mensaje" => "El usuario no esta activo"], 401);
            // $oauth2Service = new Oauth2Service($driver);

            // return response()->json(['url' => $oauth2Service->obtenerUrl()], 200);
        } else {
            // Entra aquí cuando es un inicio de sesión tradicional
            Log::channel('testing')->info('Log', ['login', $request->all()]);
            // Log::channel('testing')->info('Log', ['LoginSocialNetworkController::login', $driver, request()->all()]);
            $request->validate([
                'name' => 'required|string',
                'password' => 'required',
            ]);

            $user = UserExternal::where('name', $request['name'])->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    '404' => ['Usuario no registrado!'],
                ]);
            }
            // if ($user->updated_at->diffInDays(now()) >= 90) {
            //     throw ValidationException::withMessages([
            //         '412' => ['Ha expirado su contraseña, por favor cambiela!'],
            //     ])->status(412);
            // }

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Usuario o contraseña incorrectos'],
                ]);
            }

            // if ($user->empleado->estado) {
            if ($user) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $modelo = new UserExternalInfoResource($user);
                return response()->json([
                    'mensaje' => 'Usuario autenticado con éxito',
                    'access_token' => $token, 'token_type' => 'Bearer',
                    'user_type' => 'externo', 'modelo' => $modelo
                ], 200);
            }

            return response()->json(["mensaje" => "El usuario no esta activo"], 401);
        }
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
        $this->crearPostulante($user, $user_social);
        return $user;
    }
    private function crearPostulante(UserExternal $user, $user_social)
    {
        $name_socialite = explode(" ", $user_social->getName());
        $nombres = $name_socialite[0] . ' ' . $name_socialite[1];
        $apellidos = $name_socialite[2] . ' ' . $name_socialite[3];
        $user->postulante()->create([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'tipo_documento_identificacion' => '',
            'numero_documento_identificacion' => '',
            'telefono' => '',
        ]);
    }
    public function iniciarSesion(UserExternal $user, $password)
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Usuario o contraseña incorrectos'],
            ]);
        }
        Cache::put('autenticacion', $user->id);
        $externalUrl = 'http://localhost:8080/puestos-disponibles';
        return redirect()->away($externalUrl);
    }
    public function getDataFromSession()
    {
        $value = Cache::get('autenticacion');
        $user =  UserExternal::find($value);
        $modelo = new UserExternalResource($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'bearer', 'user_type' => 'postulante', 'modelo' => $modelo], 200);
    }
    public function logout(Request $request)
    {
        //Cache::pull('autenticacion');
        $request->user()->currentAccessToken()->delete();
    }
}
