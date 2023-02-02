<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\App\SubtareaService;

class TrabajoAsignadoController extends Controller
{
    private SubtareaService $servicio;

    public function __construct()
    {
        $this->servicio = new SubtareaService();
    }   
    /**
     * Listar todas las subtareas o trabajos que han sido asignados a mi o a mi grupo
     * en caso de pertenecer a uno.
     */
    public function index()
    {
        $empleado = User::find(Auth::id())->empleado;
        $grupo_id = $empleado->grupo_id;

        if ($grupo_id) {
            return response()->json(['results' => $this->servicio->obtenerTrabajoAsignadoGrupo($grupo_id)]);
        } else {
            return response()->json(['results' => $this->servicio->obtenerTrabajoAsignadoEmpleado($empleado->id)]);
        }
    }
}
