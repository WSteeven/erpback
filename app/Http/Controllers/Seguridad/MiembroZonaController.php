<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Seguridad\MiembroZona;
use App\Models\Seguridad\RestriccionPrendaZona;
use Illuminate\Http\Request;
use Src\Config\Permisos;

class MiembroZonaController extends Controller
{
    public function __construct()
    {
        /* $this->middleware('can:' . Permisos::VER . 'miembros_zonas')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'miembros_zonas')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'miembros_zonas')->only('update'); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = MiembroZona::ignoreRequest([''])->filter()->latest()->get();
        $results = $results->map(fn($miembro_zona) => [
            'id' => $miembro_zona['id'],
            'empleado' => Empleado::extraerNombresApellidos($miembro_zona->empleado),
            'apellidos' => $miembro_zona->empleado->apellidos,
            'nombres' => $miembro_zona->empleado->nombres,
            'cargo' => $miembro_zona->empleado->cargo->nombre,
            'zona_id' => $miembro_zona['zona_id'],
            'empleado_id' => $miembro_zona['empleado_id'],
            'tiene_restriccion' => RestriccionPrendaZona::tieneRestriccion($miembro_zona['id']) ? '*Tiene acceso restringido' : null,
        ]);
        return response()->json(compact('results'));
    }
}
