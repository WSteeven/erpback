<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoRolController extends Controller
{
    public function listarPermisos(Role $rol)
    {
        return response()->json(['rol' => $rol->name, 'permisos' => $rol->getPermissionNames()]);
    }

    public function asignarPermisosUsuario(Request $request)
    {
        $empleado = Empleado::find($request->empleado_id);
        switch ($request->tipo_sincronizacion) {
            case 'ASIGNAR':
                $empleado->user->givePermissionTo($request->permisos);
                break;
            case 'ELIMINAR':
                $empleado->user->permissions()->detach($request['permisos']);
                $empleado->user->forgetCachedPermissions();
                break;
            default:
                break;
        }
        return response()->json(['mensaje' => 'Se actualizaron los permisos del usuario', 'permisos' => $empleado->user->permissions]);
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
    public function crearPermisoRol(Request $request)
    {
        $roles = Role::whereIn('id', $request->roles)->get();
        $permisos = [];
        if ($request->permiso_personalizado) {
            $permiso = Permission::firstOrCreate(['name' => strtolower($request->name)])->syncRoles($roles);
            return response()->json(['mensaje' => 'Se creó un permiso exitosamente',  'permiso' => $permiso]);
        } else {
            if ($request->autorizar) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.autorizar.' . $request->name])->syncRoles($roles));
            if ($request->acceder) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.acceder.' . $request->name])->syncRoles($roles));
            if ($request->ver) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.ver.' . $request->name])->syncRoles($roles));
            if ($request->crear) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.crear.' . $request->name])->syncRoles($roles));
            if ($request->editar) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.editar.' . $request->name])->syncRoles($roles));
            if ($request->eliminar) array_push($permisos, Permission::firstOrCreate(['name' => 'puede.eliminar.' . $request->name])->syncRoles($roles));
            return response()->json(['mensaje' => 'Se crearon exitosamente los permisos',  'permisos' => $permisos]);
        }
    }
}
