<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleado;

use Illuminate\Http\Request;

class EstadoPermisoEmpleadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.estado_permiso_empleado')->only('index', 'show');
        $this->middleware('can:puede.crear.estado_permiso_empleado')->only('store');
        $this->middleware('can:puede.editar.estado_permiso_empleado')->only('update');
        $this->middleware('can:puede.eliminar.estado_permiso_empleado')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = EstadoPermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, EstadoPermisoEmpleado $permisoEmpleado)
    {
        return response()->json(compact('permisoEmpleado'));
    }
    public function store(Request $request)
    {
        $estadoPermisoEmpleado = new EstadoPermisoEmpleado();
        $estadoPermisoEmpleado->nombre = $request->nombre;
        $estadoPermisoEmpleado->save();
        return $estadoPermisoEmpleado;
    }
    public function update(Request $request, EstadoPermisoEmpleado $permisoEmpleado)
    {
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }
    public function destroy(Request $request, EstadoPermisoEmpleado $permisoEmpleado)
    {
        $permisoEmpleado->delete();
        return response()->json(compact('permisoEmpleado'));
    }
}
