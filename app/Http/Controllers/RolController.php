<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesRequest;
use App\Http\Requests\RolRequest;
use App\Http\Resources\RolesResource;
use App\Http\Resources\RolResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $page = $request['page'];
        $results = [];

        $results = Role::all();
        $results = RolResource::collection($results);
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
        $request->validated();
        // $datos['guard_name'] = 'web';
        Log::channel('testing')->info('Log', ['Request ', $request->all()]);
        $modelo = Role::create($request->all());
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
    public function show($id_rol)
    {
        $rol = Role::findOrFail($id_rol);
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
        $request->validated();
        Log::channel('testing')->info('Log', ['Request ', $request->all()]);
        $rol = Role::findById($request->id);
        Log::channel('testing')->info('Log', ['Rol ', $rol]);
        $rol->update($request->all());
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
    public function destroy($id)
    {
        $rol = Role::findOrFail($id);
        $rol->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
