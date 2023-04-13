<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Http\Resources\TrabajoResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\TrabajoAsignadoService;

class TrabajoAsignadoController extends Controller
{
    private TrabajoAsignadoService $servicio;

    public function __construct()
    {
        $this->servicio = new TrabajoAsignadoService();
    }
    /**
     * Listar todas las subtareas o subtareas que han sido asignados a mi o a mi grupo
     * en caso de pertenecer a uno.
     */
    public function index()
    {
        $empleado = Auth::user()->empleado;
        $grupo_id = $empleado->grupo_id;

        $results = [];

        if (request('estado') == 'PROXIMO') {
            if ($grupo_id) array_push($results, ...$this->servicio->obtenerFuturoTrabajoAsignadoGrupo($empleado));
            array_push($results, ...$this->servicio->obtenerFuturoTrabajoAsignadoEmpleado($empleado));

        } else {
            if ($grupo_id) array_push($results, ...$this->servicio->obtenerTrabajoAtrasadoAgendadoGrupo($empleado));
            array_push($results, ...$this->servicio->obtenerTrabajoAsignadoEmpleado($empleado));
        }

        $results = SubtareaResource::collection($results);
        return response()->json(compact('results'));
    }
}
