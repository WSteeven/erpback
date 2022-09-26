<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermisoRequest;
use App\Http\Resources\PermisoResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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
