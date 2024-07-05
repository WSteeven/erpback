<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class PrestamoEmpresarialController extends Controller
{
    private $entidad = 'Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.prestamo_empresarial')->only('destroy');
    }

    public function index(Request $request)
    {
        $results = [];


        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole([User::ROL_GERENTE, User::ROL_RECURSOS_HUMANOS, User::ROL_CONTABILIDAD, User::ROL_ADMINISTRADOR])) {
            $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
            $results = PrestamoEmpresarialResource::collection($results);
            return response()->json(compact('results'));
        } else {

            $results = PrestamoEmpresarial::where('solicitante', $usuario->empleado->id)->ignoreRequest(['campos'])->filter()->get();
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
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $prestamoEmpresarial = PrestamoEmpresarial::create($datos);
            $this->crear_plazos($prestamoEmpresarial, $request->plazos);
            $modelo = new PrestamoEmpresarialResource($prestamoEmpresarial);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al generra prestamo' => [$e->getMessage()],
            ]);
        }
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
        $deleted = PlazoPrestamoEmpresarial::where('id_prestamo_empresarial', $prestamoEmpresarial->id)->delete();
        $prestamoEmpresarial->delete();
        $modelo = $prestamoEmpresarial;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function deshabilitarPrestamo(Request $request)
    {
        $prestamoEmpresarial = PrestamoEmpresarial::where('id',$request->id)->first();
        $prestamoEmpresarial->motivo= $request->motivo;
        $prestamoEmpresarial->estado= 'INACTIVO';
        $prestamoEmpresarial->save();
        $prestamoEmpresarial->plazo_prestamo_empresarial_info()->update(['estado' => false]);
        $modelo = $prestamoEmpresarial;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function crear_plazos(PrestamoEmpresarial $prestamoEmpresarial, $plazos)
    {
        $plazosActualizados = collect($plazos)->map(function ($plazo) use ($prestamoEmpresarial) {
            return [
                'id_prestamo_empresarial' => $prestamoEmpresarial->id,
                'pago_couta' => false,
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' =>  $plazo['fecha_vencimiento'],
                'valor_couta' => $plazo['valor_couta'],
                'valor_a_pagar' => $plazo['valor_couta']

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
            list($mes, $anio) = explode('-',$request->mes);
            $results = PlazoPrestamoEmpresarial::
            join('prestamo_empresarial',  'plazo_prestamo_empresarial.id_prestamo_empresarial', '=','prestamo_empresarial.id')
            ->where('prestamo_empresarial.solicitante', $request->empleado)
            ->whereYear('fecha_vencimiento', $anio)
            ->whereMonth('fecha_vencimiento', $mes)
            ->where('pago_couta', 0)
            ->sum('valor_a_pagar');

        return response()->json(compact('results'));
    }
}
