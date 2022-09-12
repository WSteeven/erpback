<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermisosRequest;
use App\Http\Resources\PermisosResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Src\Shared\Utils;

class PermissionController extends Controller
{
    private $entidad = 'Permiso';
    /**
     * Display a listing of all permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = PermisosResource::collection(Permission::all());
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisosRequest $request)
    {
        //Respuesta
        $modelo = Permission::create($request->validated());
        $modelo = new PermisosResource($modelo);
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
        $modelo = new PermisosResource($permiso);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PermisosRequest $request
     * @param  \Spatie\Permission\Models\Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function update(PermisosRequest $request, Permission $permiso)
    {
        //Respuesta
        $permiso->update($request->validated());
        $modelo = new PermisosResource($permiso->refresh());
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
