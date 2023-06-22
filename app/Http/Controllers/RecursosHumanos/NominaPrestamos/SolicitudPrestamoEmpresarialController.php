<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolicitudPrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class SolicitudPrestamoEmpresarialController extends Controller
{
    private $entidad = 'Solicitud Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.solicitud_prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.solicitud_prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.solicitud_prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.solicitud_prestamo_empresarial')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = SolicitudPrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
        $results = SolicitudPrestamoEmpresarialResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        return response()->json(compact('modelo'), 200);
    }
    public function store(SolicitudPrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        $SolicitudPrestamoEmpresarial = SolicitudPrestamoEmpresarial::create($datos);
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $datos = $request->validated();
        $datos['estado'] = $request->estado;
        $SolicitudPrestamoEmpresarial->update($datos);
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $SolicitudPrestamoEmpresarial;
    }
    public function destroy(Request $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $SolicitudPrestamoEmpresarial->delete();
        return response()->json(compact('SolicitudPrestamoEmpresarial'));
    }
    public function aprobar_prestamo_empresarial(SolicitudPrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        //  Log::channel('testing')->info('Log', ['datos', $datos]);
        // $datos['estado'] = 2;
        $SolicitudPrestamoEmpresarial = SolicitudPrestamoEmpresarial::where('id', $request->id)->first();
        $SolicitudPrestamoEmpresarial->update($datos);
        $PrestamoEmpresarial = new PrestamoEmpresarial();
        $PrestamoEmpresarial->solicitante = $request->solicitante;
        $PrestamoEmpresarial->fecha = $request->fecha;
        $PrestamoEmpresarial->monto = $request->monto;
        $PrestamoEmpresarial->id_forma_pago = 1;
        $PrestamoEmpresarial->plazo = $request->plazo;
        $PrestamoEmpresarial->estado = $request->estado;
        $PrestamoEmpresarial->save();
        $this->tabla_plazos($PrestamoEmpresarial);
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $SolicitudPrestamoEmpresarial;
    }
    public function tabla_plazos( PrestamoEmpresarial $prestamo)
    {
        $valor_cuota = !is_null($prestamo->monto) ? $prestamo->monto : 0;
        $plazo_prestamo = !is_null($prestamo->plazo) ? $prestamo->plazo : 0;
        $valor_pago = $valor_cuota / $plazo_prestamo;
        if ($valor_pago <= 200) {
            for ($index = 1; $index <= $prestamo->plazo; $index++) {
                $plazo = [
                    'num_cuota' => $index,
                    'fecha_vencimiento' => $this->calcular_fechas($index, 'meses',$prestamo),
                    'valor_a_pagar' => number_format($valor_cuota / $plazo_prestamo, 2),
                    'pago_couta' => false,
                ];
                array_push($prestamo['plazos'], $plazo);
            }
            Log::channel('testing')->info('Log', ['plazos', $prestamo['plazos']]);
        }
    }
    public function calcular_fechas($cuota, $plazo, PrestamoEmpresarial $prestamo)
    {
        $day = 1000 * 60 * 60 * 24;
        $week = 7 * $day;
        $month = 4 * $week;
        $year = 12 * $month;
        $partes = explode('-', $prestamo->fecha);
        $fechaActual = new \DateTime(
            $partes[2] . '-' . $partes[1] . '-' . $partes[0]
        );
        switch ($plazo) {
            case 'dias':
                $fechaActual->modify('+' . $cuota . ' day');
                break;
            case 'semanas':
                $fechaActual->modify('+' . $cuota . ' week');
                break;
            case 'meses':
                $fechaActual->setDate($fechaActual->format('Y'), $fechaActual->format('m') + $cuota, 30);
                break;
            case 'anios':
                $fechaActual->modify('+' . $cuota . ' year');
                break;
        }
        $fechaFormateada = $fechaActual->format('d-m-Y');
        return $fechaFormateada;
    }
}
