<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoEmpresarialRequest;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class PrestamoEmpresarialController extends Controller
{
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
        $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        return response()->json(compact('prestamoEmpresarial'));
    }
    public function store(PrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        $prestamoEmpresarial=PrestamoEmpresarial::create($datos);
        $this->crear_plazos($prestamoEmpresarial,$request->plazos);
        return $prestamoEmpresarial;
    }
    public function update(PrestamoEmpresarialRequest $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $prestamoEmpresarial->nombre = $request->nombre;
        $prestamoEmpresarial->save();
        return $prestamoEmpresarial;
    }
    public function destroy(Request $request, PrestamoEmpresarial $prestamoEmpresarial)
    {
        $prestamoEmpresarial->delete();
        return response()->json(compact('prestamoEmpresarial'));
    }
    public function crear_plazos(PrestamoEmpresarial $prestamoEmpresarial,$plazos)
    {
        $plazosActualizados = collect($plazos)->map(function ($plazo) use ($prestamoEmpresarial) {
            $fecha = Carbon::createFromFormat('d-m-Y', $plazo['fecha_pago']);
            return [
                'id_prestamo_empresarial' => $prestamoEmpresarial->id,
                'estado_couta' => false,
                'num_cuota'=> $plazo['num_cuota'],
                'fecha_pago' => $fecha->format('Y-m-d'),
                'valor_a_pagar'=>$plazo['valor_a_pagar']
            ];
        })->toArray();
        PlazoPrestamoEmpresarial::insert($plazosActualizados);

    }

}
