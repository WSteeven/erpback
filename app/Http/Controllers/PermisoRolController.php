<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoRolController extends Controller
{
    public function listarPermisos(Role $rol)
    {
        return response()->json(['rol' => $rol->name, 'permisos' => $rol->getPermissionNames()]);
    }

    public function asignarPermisos(Request $request)
    {
        $rol = Role::find($request->id_rol);
        $permisos = $rol->permissions->pluck('id')->toArray();
        $request_permisos = $request['permisos'];
        $resultado = array_diff($request_permisos, $permisos);
        switch ($request['tipo_sincronizacion']) {
            case 'ASIGNAR':
                $rol->permissions()->attach($resultado);
                $rol->forgetCachedPermissions();
                $rol->load('permissions');
                break;
            case 'ELIMINAR':
                $rol->permissions()->detach($request_permisos);
                $rol->forgetCachedPermissions();
                $rol->load('permissions');
                break;
            default:
                break;
        }
        return response()->json(['mensaje' => 'Se actualizaron los permisos del rol', 'rol' => $rol->name, 'permisos' => $rol->getPermissionNames()]);
    }
    public function crearPermisoRol(Request $request){
        $permiso = Permission::firstOrCreate(['name' => $request->name])->syncRoles($request->roles);
        return response()->json(['mensaje' => 'Se creo permiso exitosamente',  'permisos' => $permiso]);
    }
}
