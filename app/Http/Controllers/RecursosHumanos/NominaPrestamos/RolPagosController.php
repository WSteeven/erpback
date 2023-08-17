<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use Algolia\AlgoliaSearch\Http\Psr7\Request as Psr7Request;
use App\Exports\RolPagoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ArchivoRolPagoResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;

class RolPagosController extends Controller
{
    private $entidad = 'Rol_de_pagos';
    private $reporteService;
    private $nominaService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->nominaService = new NominaService();
        $this->middleware('can:puede.ver.rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = RolPago::ignoreRequest(['campos'])->filter()->get();
        $results = RolPagoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function archivo_rol_pago_empleado(Request $request)
    {
        $request->validate([
            'rol_pago_id' => 'required|numeric|integer',
        ]);
        $rolpago = RolPago::find($request['rol_pago_id']);
        if (!$rolpago) {
            throw ValidationException::withMessages([
                'rolpago' => ['El permiso del empleado no existe'],
            ]);
        }
        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $archivoJSON =  GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_ROL_EMPLEADO, true);
        $rolpago->rol_firmado = $archivoJSON;
        $rolpago->estado = RolPago::FINALIZADO;
        $rolpago->save();
        return response()->json(['modelo' => $rolpago, 'mensaje' => 'Subido exitosamente!']);
    }
    public function index_archivo_rol_pago_empleado(Request $request)
    {
        $results = RolPago::where('id', $request->rol_pago_id)->get();
        $results = ArchivoRolPagoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function store(RolPagoRequest $request)
    {
        try {
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
            DB::beginTransaction();
            $rolPago = RolPago::create($datos);
            foreach ($request->ingresos as $ingreso) {
                $this->GuardarIngresos($ingreso, $rolPago);
            }
            foreach ($request->egresos as $egreso) {
                $this->GuardarEgresos($egreso, $rolPago);
            }
            $modelo = new RolPagoResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function GuardarIngresos($ingreso, $rolPago)
    {
        $datos = $ingreso;
        $datos['id_rol_pago'] =  $rolPago->id;
        DB::beginTransaction();
        $rolPago = IngresoRolPago::create($datos);
        DB::commit();
    }
    private function GuardarEgresos($egreso, $rolPago)
    {
        $datos = $egreso;
        $datos['id_rol_pago'] =  $rolPago->id;
        DB::beginTransaction();
        $id_descuento = $datos['id_descuento'];
        $entidad = null;
        switch ($datos['tipo']) {
            case 'DESCUENTO_GENERAL':
                $entidad = DescuentosGenerales::find($id_descuento);
                break;
            case 'MULTA':
                $entidad = Multas::find($id_descuento);
                break;
            default:
                break;
        }
        $rolPago = EgresoRolPago::crearEgresoRol($datos['id_rol_pago'], $datos['monto'], $entidad);
        DB::commit();
    }
    public function show(RolPago $rolPago)
    {
        $modelo = new RolPagoResource($rolPago);
        return response()->json(compact('modelo'), 200);
    }
    public function nivel_endeudamiento(Request $request)
    {
        $empleado = Empleado::where('id', $request->empleado)->first();
        $date = Carbon::now();
        $mes = $date->format('m-Y');
        $salario =  $empleado->salario;
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        $supa = $empleado->supa;
        $prestamo_quirorafario = PrestamoQuirorafario::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('valor');
        $prestamo_hipotecario = PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('valor');
        $extension_conyugal = ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('aporte');
        $sueldo = $salario;
        Log::channel('testing')->info('Log', ['sueldo', $sueldo]);

        $iess = ($sueldo) * $porcentaje_iess;
        $total_descuento =  round(($supa + $prestamo_hipotecario + $extension_conyugal + $prestamo_quirorafario + $iess), 2);
        $porcentaje_endeudamiento = round(($total_descuento / $sueldo), 2) * 100;
        $porcentaje_endeudamiento = ($porcentaje_endeudamiento);

        $results = [
            'total_descuento' => $total_descuento,
            'porcentaje' => $porcentaje_endeudamiento,
            'mensaje' => $porcentaje_endeudamiento > 40 ? 'NIVEL DE ENDEUDAMIENTO SUPERA EL 40%' : ''
        ];
        return response()->json(compact('results'));
    }

    public function update(RolPagoRequest $request, $rolPagoId): JsonResponse
    {
        $datos = $request->validated();
        $rolPago = RolPago::findOrFail($rolPagoId);
        $rolPago->update($datos);

        $this->guardarIngresosYEgresos($request, $rolPago);

        $modelo = new RolPagoResource($rolPago->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    private function guardarIngresosYEgresos(RolPagoRequest $request, RolPago $rolPago): void
    {
        if (!empty($request->ingresos)) {
            foreach ($request->ingresos as $ingreso) {
                $this->GuardarIngresos($ingreso, $rolPago);
            }
        }

        if (!empty($request->egresos)) {
            foreach ($request->egresos as $egreso) {
                $this->GuardarEgresos($egreso, $rolPago);
            }
        }
    }

    public function destroy($rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $rolPago->delete();
        return $rolPago;
    }
    public function cambiar_estado(Request $request, $rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $estado_mensaje = '';
        switch ($request->estado) {
            case RolPago::EJECUTANDO:
                $estado_mensaje = ' Ejecutado el Rol de Pagos';
                break;
            case RolPago::REALIZADO:
                $estado_mensaje = ' Realizado el Rol de Pagos';
                break;
            case  RolPago::CANCELADO:
                break;
            case RolPago::FINALIZADO:
                break;
        }
        $rolPago->estado = $request->estado;
        $rolPago->save();
        $modelo = new RolPagoResource($rolPago->refresh());
        $mensaje = 'Se ha ' . $estado_mensaje;
        return response()->json(compact('modelo', 'mensaje'));
    }
    public function imprimir_rol_pago($rolPagoId)
    {
        try {
            $nombre_reporte = 'rol_pagos';
            $roles_pagos = RolPago::where('id', $rolPagoId)->get();
            $results = RolPago::empaquetarListado($roles_pagos);
            $reportes =  ['roles_pago' => $results];
            $vista = 'recursos-humanos.rol_pagos';
            $export_excel = new RolPagoExport($reportes);
            return $this->reporteService->imprimir_reporte('pdf', 'A5', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }
    public function actualizar_masivo(Request $request)
    {
        // Realizar la actualización masiva
        RolPago::where('rol_pago_id',  $request->rol_pago_id)->where('estado', RolPago::CREADO)
            ->update(['estado' => RolPago::EJECUTANDO]);
        return response()->json(['mensaje' => 'Se ha comenzado a ejecutar todo el rol']);
    }

}
