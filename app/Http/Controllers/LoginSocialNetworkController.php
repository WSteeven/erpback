<?php

namespace App\Http\Controllers;

use App\Http\Resources\Externos\UserExternalInfoResource;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\UserExternalResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialNetworkController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if ($request->boolean('oauth')) {
            // Entra aquí cuando es un inicio de sesión por aplicaciones de terceros como: facebook, google, twitter, linkedin
            Log::channel('testing')->info('Log', ['autenticacion de 3ros', request()->all()]);

            // $oauth2Service = new Oauth2Service($driver);

            // return response()->json(['url' => $oauth2Service->obtenerUrl()], 200);
        } else {
            // Entra aquí cuando es un inicio de sesión tradicional
//            Log::channel('testing')->info('Log', ['login', $request->all()]);
            // Log::channel('testing')->info('Log', ['LoginSocialNetworkController::login', $driver, request()->all()]);
            $request->validate([
                'name' => 'required|email:rfc,dns',
                'password' => 'required',
            ]);

            $user = UserExternal::where('email', $request['name'])->first();
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

            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Usuario o contraseña incorrectos'],
                ]);
            }

            // if ($user->empleado->estado) {
            $token = $user->createToken('auth_token')->plainTextToken;
            $modelo = new UserExternalInfoResource($user);
            return response()->json([
                'mensaje' => 'Usuario autenticado con éxito',
                'access_token' => $token, 'token_type' => 'Bearer',
                'user_type' => 'externo', 'modelo' => $modelo
            ]);

        }
        return response()->json(["mensaje" => "El usuario no esta activo"], 401);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error al autenticar', $e->getMessage()]);
            return redirect()->to(env('SPA_URL', 'https://firstred.jpconstrucred.com') . '/error-login?message=authentication_failed');
        }
        // Verificamos si el usuario existe y tiene un correo válido
        if (!$socialUser || !$socialUser->getEmail()) {
            return redirect()->to(env('SPA_URL', 'https://firstred.jpconstrucred.com') . '/error-login?message=authentication_canceled');
        }
        // Buscar o crear el usuario en tu base de datos
        $user = UserExternal::firstOrCreate(
            [
                'email' => $socialUser->getEmail()
            ],
            [
                'name' => $socialUser->getName(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
            ]
        );
        $datos['usuario_external_id'] = $user->id;
        $datos['nombres'] = $socialUser->getName();
        $datos['apellidos'] = "";
        $datos['correo_personal'] = $socialUser->getEmail();
        // Buscamos el postulante y lo creamos si no existe
        $existe_postulante = Postulante::where('usuario_external_id', $user->id)->exists();
        if (!$existe_postulante) Postulante::create($datos);


        Log::channel('testing')->info('Log', ['User autenticado', $user, $socialUser]);
        // Generar el token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        $queryParams = http_build_query([
//           'message'=> 'Usuario autenticado con éxito',
            'token' => $token,
//           'token_type' => 'bearer',
            'user' => json_encode(new UserExternalResource($user)),
        ]);

        // Redirigir con el token generado (por ejemplo, a una página del frontend que reciba el token)
//        return redirect()->to(env('SPA_URL') . '/login-success?token=' . $token);
        return redirect()->to(env('SPA_URL') . '/login-success?' . $queryParams);
    }

    /**
     * @throws ValidationException
     */
    public function handleCallback($driver)
    {
        $user_social = Socialite::driver($driver)->stateless()->user();
        $user_db = UserExternal::where('email', $user_social->email)->first();
        if ($user_db) {
            return $this->iniciarSesion($user_db, $user_social->id);
        } else {
            $user = $this->registrar($user_social);
            return $this->iniciarSesion($user, $user_social->id);
        }
    }

    public function registrar($user_social)
    {
        $username = explode("@", $user_social->email)[0];
        $user = UserExternal::create([
            'name' => $username,
            'email' => $user_social->email,
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
        $user->persona()->create([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'tipo_documento_identificacion' => '',
            'numero_documento_identificacion' => '',
            'telefono' => '',
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function iniciarSesion(UserExternal $user, $password)
    {
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Usuario o contraseña incorrectos'],
            ]);
        }
        Cache::put('autenticacion', $user->id);
        $external_url = 'http://localhost:8080/puestos-disponibles';
        return redirect()->away($external_url);
    }

    public function getDataFromSession()
    {
        $value = Cache::get('autenticacion');
        $user = UserExternal::find($value);
        $modelo = new UserExternalResource($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'bearer', 'user_type' => 'postulante', 'modelo' => $modelo]);
    }

    public function logout(Request $request)
    {
        //Cache::pull('autenticacion');
        $request->user()->currentAccessToken()->delete();
    }
}
