<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class VacacionController extends Controller
{
    private $entidad = 'Solicitud Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.vacacion')->only('index', 'show');
        $this->middleware('can:puede.crear.vacacion')->only('store');
        $this->middleware('can:puede.editar.vacacion')->only('update');
        $this->middleware('can:puede.eliminar.vacacion')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $results = Vacacion::ignoreRequest(['campos'])->filter()->get();
        } else {
              $empleados = Empleado::where('jefe_id', Auth::user()->empleado->id)->orWhere('id', Auth::user()->empleado->id)->get('id');
              $results = Vacacion::ignoreRequest(['campos'])->filter()->WhereIn('empleado_id', $empleados->pluck('id'))->get();
        }
        $results = VacacionResource::collection($results);

        return response()->json(compact('results'));
    }
    public function show(Request $request, Vacacion $Vacacion)
    {
        $modelo = new VacacionResource($Vacacion);
        return response()->json(compact('modelo'), 200);
    }
    public function descuentos_permiso(Request $request){
        $duracionEnDias = PermisoEmpleado::where('empleado_id', $request->empleado)->where('cargo_vacaciones',1)
        ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, fecha_hora_inicio, fecha_hora_fin)) as duracion')
        ->first();
        return $duracionEnDias;

    }
    public function store(VacacionRequest $request)
    {
        $datos = $request->validated();
        $datos['estado'] =  Vacacion::PENDIENTE;
        $vacacion = Vacacion::create($datos);
        $modelo = new VacacionResource($vacacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(VacacionRequest $request, Vacacion $Vacacion)
    {
        $datos = $request->validated();
        $datos['estado'] = $request->estado;
        $Vacacion->update($datos);
        $modelo = new VacacionResource($Vacacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $Vacacion;
    }
    public function destroy(Request $request, Vacacion $Vacacion)
    {
        $Vacacion->delete();
        return response()->json(compact('Vacacion'));
    }
}
