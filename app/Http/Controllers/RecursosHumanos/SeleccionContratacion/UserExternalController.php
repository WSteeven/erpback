<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\UserExternalResource;
use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;

class UserExternalController extends Controller
{
//    private string $entidad = 'Persona';

    public function __construct()
    {
        $this->middleware('check.user.logged.in');
        $this->middleware('can:puede.ver.usuarios_externos')->only('index', 'show');
    }

    public function show(UserExternal $user)
    {
        $modelo = new UserExternalResource($user);
        return response()->json(compact('modelo'));
    }

}
