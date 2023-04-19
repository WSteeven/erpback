<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermisoRequest;
use App\Http\Resources\PermisoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Switch_;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Shared\Utils;

class PermisoController extends Controller
{
    private $entidad = 'Permiso';
    /**
     * Display a listing of all permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = PermisoResource::collection(Permission::all());
        return response()->json(compact('results'));
    }

    public function listarPermisos(Request $request)
    {
        $results = [];
        switch ($request['tipo']) {
            case 'ASIGNADOS':
                $results = Role::find($request['id_rol'])->permissions;
                break;
            case 'NO ASIGNADOS':
                $id_permisos = Role::find($request['id_rol'])->permissions->pluck('id')->toArray();
                $results = Permission::whereNotIn('id',$id_permisos)->get();
                break;
            default:
                $results = DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->get();
                break;
        }


        return response()->json(compact('results'));
    }

    public function asignarPermisos(Request $request, Role $rol){
        $request->validate(['permissions'=>'exists:permissions,id']);
        $rol->permissions()->sync($request->permissions);
        return response()->json(['mensaje'=>'Se actualizaron los permisos del rol','rol'=>$rol->name, 'permisos'=>$rol->getPermissionNames()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisoRequest $request)
    {
        //Respuesta
        $modelo = Permission::create($request->validated());
        $modelo = new PermisoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permiso)
    {
        $modelo = new PermisoResource($permiso);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PermisosRequest $request
     * @param  \Spatie\Permission\Models\Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function update(PermisoRequest $request, Permission $permiso)
    {
        //Respuesta
        $permiso->update($request->validated());
        $modelo = new PermisoResource($permiso->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permiso)
    {
        $permiso->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
