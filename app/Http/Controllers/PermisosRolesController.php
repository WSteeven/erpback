<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PermisosRolesController extends Controller
{
    public function listarPermisos(Role $rol){
        return response()->json(['rol'=>$rol->name, 'permisos'=>$rol->getPermissionNames()]);
    }

    public function asignarPermisos(Request $request, Role $rol){
        $request->validate(['permissions'=>'exists:permissions,id']);
        $rol->permissions()->sync($request->permissions);

        return response()->json(['mensaje'=>'Se actualizaron los permisos del rol','rol'=>$rol->name, 'permisos'=>$rol->getPermissionNames()]);
    }
}
