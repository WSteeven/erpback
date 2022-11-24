<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesRequest;
use App\Http\Requests\RolRequest;
use App\Http\Resources\RolesResource;
use App\Http\Resources\RolResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Src\Shared\Utils;

class RolController extends Controller
{
    private $entidad = 'Rol';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campos = explode(',', $request['campos']);
        $results = [];
        /* if($request['campos']){
            $results = Role::
        }else{ */
        $results = RolResource::collection(Role::all($campos));
        // }
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolRequest $request)
    {
        //Respuesta
        $modelo = Role::create($request->validated());
        $modelo = new RolResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $rol)
    {
        $modelo = new RolResource($rol);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RolesRequest  $request
     * @param  \Spatie\Permission\Models\Role  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(RolRequest $request, Role $rol)
    {
        //Respuesta
        $rol->update($request->validated());
        $modelo = new RolResource($rol->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $rol)
    {
        $rol->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
