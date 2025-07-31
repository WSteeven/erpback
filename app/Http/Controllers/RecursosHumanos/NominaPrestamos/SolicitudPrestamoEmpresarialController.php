<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\SolicitudPrestamoEvent;
use App\Events\SolicitudPrestamoGerenciaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class SolicitudPrestamoEmpresarialController extends Controller
{
    private string $entidad = 'Solicitud Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.solicitud_prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.solicitud_prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.solicitud_prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.solicitud_prestamo_empresarial')->only('destroy');
    }

    public function index()
    {
        $usuario = Auth::user();
        if ($usuario->hasRole('GERENTE') ||  $usuario->hasRole('RECURSOS HUMANOS')) {
            $results = SolicitudPrestamoEmpresarial::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        } else {
            $results = SolicitudPrestamoEmpresarial::where('solicitante', $usuario->empleado->id)->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        }
        $results = SolicitudPrestamoEmpresarialResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(SolicitudPrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        $rubro = Rubros::where('nombre_rubro', 'Sueldo Basico')->first();
        $empleado = Auth::user()->empleado;
        $fechaActual = Carbon::now();
        $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
        $diff = $fechaActual->diff($fechaIngreso);
        if($diff->y <1){
            throw ValidationException::withMessages([
                '404' => ['Solo se puede solicitar prestamos una vez cumplido 1 año de trabajo'],
            ]);
        }
        $sbu_doble = $rubro->valor_rubro * 2;
        if ($request->monto >= $sbu_doble) {
            throw ValidationException::withMessages([
                '404' => ['Solo se permite prestamo menor o igual a 2 SBU ($' . ($rubro->valor_rubro * 2) . ')'],
            ]);
        }
        $solicitud = SolicitudPrestamoEmpresarial::create($datos);

        event(new SolicitudPrestamoEvent($solicitud));
        if ($solicitud->estado == 4) {
            event(new SolicitudPrestamoGerenciaEvent($solicitud));
        }

        $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(SolicitudPrestamoEmpresarial $solicitud)
    {
        $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function update(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $solicitud)
    {
        switch ($request->estado) {
            case 2: // Aprobado
                return $this->aprobar_prestamo_empresarial($request, $solicitud);
            case 3: // Cancelado
                return  $this->rechazar_prestamo_empresarial($request, $solicitud);
            case 4: // Validado
                return $this->validar_prestamo_empresarial($request, $solicitud);
            default:
                $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
                $mensaje = 'No se reconoce el estado proporcionado, sin modificaciones';
                return response()->json(compact('mensaje', 'modelo'));
        }
    }

    /**
     * @throws Throwable
     */
    public function validar_prestamo_empresarial(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $solicitud)
    {
        $datos = $request->validated();
        $solicitud->update($datos);
        event(new SolicitudPrestamoEvent($solicitud));
        event(new SolicitudPrestamoGerenciaEvent($solicitud));
        $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(SolicitudPrestamoEmpresarial $solicitud)
    {
        $solicitud->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function aprobar_prestamo_empresarial(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $solicitud)
    {
        $datos = $request->validated();
        $solicitud->update($datos);
        $prestamoEmpresarial = new PrestamoEmpresarial();
        $prestamoEmpresarial->solicitante = $request->solicitante;
        $prestamoEmpresarial->fecha = $request->fecha;
        $prestamoEmpresarial->monto = $request->monto;
        $prestamoEmpresarial->plazo = $request->plazo;
        $prestamoEmpresarial->estado = PrestamoEmpresarial::ACTIVO;
        $prestamoEmpresarial->periodo_id = $request->periodo_id;
        $prestamoEmpresarial->valor_utilidad = $request->valor_utilidad;
        $prestamoEmpresarial->id_solicitud_prestamo_empresarial = $solicitud->id;
        $prestamoEmpresarial->save();
        event(new SolicitudPrestamoEvent($solicitud));
        $this->tabla_plazos($prestamoEmpresarial);
        $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
        $mensaje = "Solicitud de Prestamo a sido Aprobada";
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws Throwable
     */
    public function rechazar_prestamo_empresarial(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $solicitud)
    {
        $datos = $request->validated();
        $solicitud->update($datos);
        event(new SolicitudPrestamoEvent($solicitud));
        $modelo = new SolicitudPrestamoEmpresarialResource($solicitud);
        $mensaje = "Solicitud de Prestamo a sido Rechazada"; //Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws Exception
     */
    public function tabla_plazos(PrestamoEmpresarial $prestamo)
    {
        $valor_cuota = !is_null($prestamo->monto) ? $prestamo->monto : 0;
        $plazo_prestamo = !is_null($prestamo->plazo) ? $prestamo->plazo : 0;
        $plazos  = [];
        $valor_utilidad = !is_null($prestamo->valor_utilidad) ? $prestamo->valor_utilidad : 0;

        for ($index = 1; $index <= $prestamo->plazo; $index++) {
            $valor_cuota = number_format($valor_cuota / $plazo_prestamo, 2);
            if ($valor_utilidad != 0) {
                $valor_cuota -= ($valor_utilidad / $plazo_prestamo);
            }
            $plazo = [
                'num_cuota' => $index,
                'fecha_vencimiento' => $this->calcular_fechas($index, 'meses', $prestamo),
                'valor_cuota' => $valor_cuota,
                'valor_pagado' => 0,
                'valor_a_pagar' => 0,
                'pago_cuota' => false,
            ];
            $plazos[] = $plazo;
        }
        if ($valor_utilidad != 0) {
            $periodo = Periodo::where('id', $prestamo->periodo_id)->first();
            $nombrePeriodo = $periodo->nombre;
            $anio = explode('-', $nombrePeriodo)[0];
            $indice =  (int)$prestamo->plazo + 1;
            $plazo = [
                'num_cuota' =>  $indice,
                'fecha_vencimiento' => '30-04-' . $anio,
                'valor_cuota' =>  $valor_utilidad,
                'valor_pagado' => 0,
                'valor_a_pagar' => 0,
                'pago_cuota' => false,
            ];
            $plazos[] = $plazo;
        }
        $this->crear_plazos($prestamo, $plazos);
    }

    /**
     * @throws Exception
     */
    public function calcular_fechas($cuota, $plazo, PrestamoEmpresarial $prestamo)
    {
        $partes = explode('-', $prestamo->fecha);
        $fechaActual = new DateTime(
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
                $mes = (int)$fechaActual->format('m') + $cuota;
                $fechaActual->setDate($fechaActual->format('Y'), (int)$fechaActual->format('m') + $cuota, 30);
                if ($mes == 14) {
                    $fechaActual->modify('-1 day');
                }
                break;
            case 'anios':
                $fechaActual->modify('+' . $cuota . ' year');
                break;
        }
        return $fechaActual->format('d-m-Y');
    }

    public function crear_plazos(PrestamoEmpresarial $prestamoEmpresarial, $plazos)
    {
        $plazosActualizados = collect($plazos)->map(function ($plazo) use ($prestamoEmpresarial) {
            $fecha = Carbon::createFromFormat('d-m-Y', $plazo['fecha_vencimiento']);
            return [
                'id_prestamo_empresarial' => $prestamoEmpresarial->id,
                'pago_cuota' => false,
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $fecha->format('Y-m-d'),
                'valor_cuota' => $plazo['valor_cuota'],
                'valor_pagado' => $plazo['valor_pagado'],
                'valor_a_pagar' => $plazo['valor_a_pagar']
            ];
        })->toArray();
        PlazoPrestamoEmpresarial::insert($plazosActualizados);
    }

}
