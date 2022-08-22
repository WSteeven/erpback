<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['modelo' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);
        $rol = Role::create($request->all());
        return response()->json(['mensaje' => 'El rol ha sido creado con éxito', 'modelo' => $rol]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $rol)
    {
        return response()->json(['modelo' => $rol]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $rol)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);
        $rol->update($request->all());
        return response()->json(['mensaje' => 'El rol ha sido actualizado con éxito', 'modelo' => $rol]);
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
        return response()->json(['mensaje' => 'El rol ha sido eliminado con éxito', 'modelo' => $rol]);
    }
}
