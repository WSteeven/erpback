<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Models\MotivoPermisoEmpleado;
use Illuminate\Http\Request;

class MotivoPermisoEmpleadoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:puede.ver.motivo_permiso_empleado')->only('index', 'show');
        $this->middleware('can:puede.crear.motivo_permiso_empleado')->only('store');
        $this->middleware('can:puede.editar.motivo_permiso_empleado')->only('update');
        $this->middleware('can:puede.eliminar.motivo_permiso_empleado')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];

        $results = MotivoPermisoEmpleado::ignoreRequest(['campos'])->filter()->get();
       // $results = TipoSaldoResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $motivoPermisoEmpleado = new MotivoPermisoEmpleado();
        $motivoPermisoEmpleado->nombre = $request->nombre;
        $motivoPermisoEmpleado->save();
        return $motivoPermisoEmpleado;
    }

    public function update(Request $request, $id)
    {
        $motivoPermisoEmpleado = MotivoPermisoEmpleado::findOrFail($id);
        $motivoPermisoEmpleado->nombre = $request->nombre;
        $motivoPermisoEmpleado->save();
        return $motivoPermisoEmpleado;
    }

    public function destroy(Request $request, $id)
    {
        $motivoPermisoEmpleado = MotivoPermisoEmpleado::findOrFail($id);
        $motivoPermisoEmpleado->delete();
        return $motivoPermisoEmpleado;
    }
}
