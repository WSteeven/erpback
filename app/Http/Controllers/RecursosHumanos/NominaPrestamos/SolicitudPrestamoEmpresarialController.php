<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\RecursosHumanos\NotificarSolicitudPrestamoAprobadaRecursosHumanosEvent;
use App\Events\SolicitudPrestamoEvent;
use App\Events\SolicitudPrestamoGerenciaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Carbon\Carbon;
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
        if ($usuario->hasRole('GERENTE') || $usuario->hasRole('RECURSOS HUMANOS')) {
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
        if ($diff->y < 1) {
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
                return $this->rechazar_prestamo_empresarial($request, $solicitud);
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
//        $prestamoEmpresarial = new PrestamoEmpresarial();
//        $prestamoEmpresarial->solicitante = $request->solicitante;
//        $prestamoEmpresarial->fecha = $request->fecha;
//        $prestamoEmpresarial->monto = $request->monto;
//        $prestamoEmpresarial->plazo = $request->plazo;
//        $prestamoEmpresarial->estado = PrestamoEmpresarial::ACTIVO;
//        $prestamoEmpresarial->periodo_id = $request->periodo_id;
//        $prestamoEmpresarial->valor_utilidad = $request->valor_utilidad;
//        $prestamoEmpresarial->id_solicitud_prestamo_empresarial = $solicitud->id;
//        $prestamoEmpresarial->save();
        event(new SolicitudPrestamoEvent($solicitud));
        event(new NotificarSolicitudPrestamoAprobadaRecursosHumanosEvent($solicitud));


        //$this->tabla_plazos($prestamoEmpresarial);
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
    /*public function tabla_plazos(PrestamoEmpresarial $prestamo)
    {
        $monto = $prestamo->monto ?? 0;
        $plazo = $prestamo->plazo ?? 0;
        $valor_utilidad = $prestamo->valor_utilidad ?? 0;

        if ($plazo ===0) throw new Exception('El préstamo no tiene plazo definido');

        $valor_base_cuota =  $monto / $plazo;
        $descuento_utilidad = $valor_utilidad != 0?  $valor_utilidad/$plazo : 0;
        $valor_cuota = round($valor_base_cuota-$descuento_utilidad, 2);

        $plazos = collect(range(1, $plazo))->map(function ($numeroCuota) use ($prestamo,$valor_cuota) {
            return $this->crearPlazoCuota($numeroCuota, $valor_cuota, $this->calcularFechaVencimiento($numeroCuota, 'meses', $prestamo));
        })->toArray();

        if ($valor_utilidad > 0) {
            $anio =$this->obtenerAnioDesdePeriodo($prestamo);
            $plazos[] = $this->crearPlazoCuota($plazo+1, round($valor_utilidad,2), "$anio-04-30");
        }

        $this->guardarPlazos($prestamo, $plazos);
    }*/

    /*protected function crearPlazoCuota(int $numCuota, float $valorCuota, string $fechaVencimiento): array
    {
        return [
            'num_cuota' => $numCuota,
            'fecha_vencimiento' => $fechaVencimiento,
            'valor_cuota' => $valorCuota,
            'valor_pagado' => 0,
            'valor_a_pagar' => 0,
            'pago_cuota' => false,
        ];
    }*/

    /*protected function obtenerAnioDesdePeriodo(PrestamoEmpresarial $prestamo): string
    {
        $periodo = Periodo::find($prestamo->periodo_id);
        $nombre = $periodo?->nombre;
        return explode('-', $nombre)[0] ?? now()->year;
    }*/

    /*protected function calcularFechaVencimiento(int $cuota, string $unidad, PrestamoEmpresarial $prestamo): string
    {
        $fecha = Carbon::parse($prestamo->fecha); // $prestamo->fecha es en formato Y-m-d

        match ($unidad) {
            'dias' => $fecha->addDays($cuota),
            'semanas' => $fecha->addWeeks($cuota),
            'meses' => $fecha->addMonthsNoOverflow($cuota),
            'anios' => $fecha->addYears($cuota),
            default => throw new InvalidArgumentException("Unidad de plazo inválida: $unidad"),
        };

        return $fecha->format('Y-m-d');
    }*/

    /* protected function guardarPlazos(PrestamoEmpresarial $prestamo, array $plazos): void
    {
        $data = collect($plazos)->map(function ($plazo) use ($prestamo) {
            return [
                'id_prestamo_empresarial' => $prestamo->id,
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $plazo['fecha_vencimiento'],
                'valor_cuota' => $plazo['valor_cuota'],
                'valor_pagado' => $plazo['valor_pagado'],
                'valor_a_pagar' => $plazo['valor_a_pagar'],
                'pago_cuota' => $plazo['pago_cuota'],
            ];
        })->toArray();

        PlazoPrestamoEmpresarial::insert($data);
    } */

}
