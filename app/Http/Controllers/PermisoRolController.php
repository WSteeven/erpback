<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class PermisoRolController extends Controller
{
    public function listarPermisos(Role $rol){
        return response()->json(['rol'=>$rol->name, 'permisos'=>$rol->getPermissionNames()]);
    }

    public function asignarPermisos(Request $request){
        $rol = Role::find($request->id_rol);
        $rol->permissions()->sync($request->permisos);
        return response()->json(['mensaje'=>'Se actualizaron los permisos del rol','rol'=>$rol->name, 'permisos'=>$rol->getPermissionNames()]);
    }
}
