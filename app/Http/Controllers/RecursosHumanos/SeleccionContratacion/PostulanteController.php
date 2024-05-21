<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\PostulanteRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\UserExternalResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PostulanteController extends Controller
{
    private $entidad = 'Postulante';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostulanteRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $username =  explode("@", $datos['email'])[0];
            $user = UserExternal::create([
                'name' => $username,
                'email' => $datos['email'],
                'token' => null,
                'password' => bcrypt($datos['password']),
            ]);
            $datos['usuario_external_id'] = $user->id;
             Postulante::create($datos);
            $token = $user->createToken('auth_token')->plainTextToken;
            $modelo_user = new UserExternalResource($user);
            $postData = ['access_token' => $token, 'token_type' => 'bearer', 'modelo' => $modelo_user];
            Cache::put('autenticacion', $postData);
            DB::commit();
            return response()->json(['mensaje' => 'Usuario autenticado con éxito', 'access_token' => $token, 'token_type' => 'bearer', 'modelo' => $modelo_user], 200);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al registrar' => [$e->getMessage()],
            ]);
        }
    }
}
