<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\PermisoEmpleado;
use Illuminate\Http\Request;

class PermisoEmpleadoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:puede.ver.permiso_nomina')->only('index', 'show');
        $this->middleware('can:puede.crear.permiso_nomina')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function create(Request $request)
    {
        $permisoEmpleado = new PermisoEmpleado();
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function store(Request $request)
    {
        $permisoEmpleado = new PermisoEmpleado();
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function show($permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        return $permisoEmpleado;
    }

    public function update(Request $request, $permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function destroy($permisoEmpleadoId)
    {
        $permisoEmpleado = PermisoEmpleado::find($permisoEmpleadoId);
        $permisoEmpleado->delete();
        return $permisoEmpleado;
    }

}
