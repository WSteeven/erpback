<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PrestamoEmpresarialController extends Controller
{
    private $entidad = 'Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.prestamo_empresarial')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];


        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('GERENTE') ||  $usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
            $results = PrestamoEmpresarialResource::collection($results);
            return response()->json(compact('results'));
        } else {
            /*
            $results = PrestamoEmpresarial::where('solicitante', $usuario->id)->ignoreRequest(['campos'])->filter()->get();
            $results = PrestamoEmpresarial::collection($results);*/
            $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
            $results = PrestamoEmpresarialResource::collection($results);
            return response()->json(compact('results'));
        }



        return response()->json(compact('results'));
    }
    public function show(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $modelo = new PrestamoEmpresarialResource($prestamoEmpresarial);
        return response()->json(compact('modelo'), 200);
    }
    public function store(PrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        $prestamoEmpresarial = PrestamoEmpresarial::create($datos);
        $this->crear_plazos($prestamoEmpresarial, $request->plazos);
        $modelo = new PrestamoEmpresarialResource($prestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(PrestamoEmpresarialRequest $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $datos = $request->validated();
        $prestamoEmpresarial->update($datos);
        $this->modificar_plazo($prestamoEmpresarial, $request->plazos);
        $modelo = new PrestamoEmpresarialResource($prestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function destroy(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $prestamoEmpresarial->delete();
        return response()->json(compact('prestamoEmpresarial'));
    }
    public function crear_plazos(PrestamoEmpresarial $prestamoEmpresarial, $plazos)
    {
        $plazosActualizados = collect($plazos)->map(function ($plazo) use ($prestamoEmpresarial) {
            $fecha = Carbon::createFromFormat('d-m-Y', $plazo['fecha_vencimiento']);
            return [
                'id_prestamo_empresarial' => $prestamoEmpresarial->id,
                'pago_couta' => false,
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $fecha->format('Y-m-d'),
                'valor_a_pagar' => $plazo['valor_a_pagar']
            ];
        })->toArray();
        PlazoPrestamoEmpresarial::insert($plazosActualizados);
    }
    public function modificar_plazo(PrestamoEmpresarial $prestamoEmpresarial, $plazos)
    {
        // Crear un arreglo con los datos actualizados para cada plazo
        $plazosActualizados = collect($plazos)->map(function ($plazo) {
            return [
                'id' =>  $plazo['id'],
                'pago_couta' =>  $plazo['pago_couta'],
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $plazo['fecha_vencimiento'],
                'fecha_pago' => $plazo['fecha_pago'],
                'valor_a_pagar' => $plazo['valor_a_pagar']
            ];
        })->toArray();
        // Realizar la actualización masiva
        foreach ($plazosActualizados as $plazoActualizado) {
            $id = $plazoActualizado['id'];
            PlazoPrestamoEmpresarial::where('id', $id)->update($plazoActualizado);
        }
    }
    public function obtener_prestamo_empleado(Request $request)
    {
        $results = PrestamoEmpresarial::where('solicitante', $request->empleado)
            ->where('estado', 'ACTIVO')
            ->whereRaw('DATE_FORMAT(plazos.fecha_vencimiento, "%Y-%m") <= ?', [$request->mes])
            ->join('plazo_prestamo_empresarial as plazos', 'prestamo_empresarial.id', '=', 'plazos.id_prestamo_empresarial')
            ->sum('plazos.valor_a_pagar');
        return response()->json(compact('results'));
    }
}
