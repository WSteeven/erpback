<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\RolPagoMesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\RolPagoMesRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoMesResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\RecursosHumanos\NominaPrestamos\PrestamoService;
use Src\Shared\Utils;

class RolPagoMesController extends Controller
{
    private $entidad = 'rol_pago';
    private $reporteService;
    private $nominaService;
    private $prestamoService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->nominaService = new NominaService();
        $this->prestamoService = new PrestamoService();
        $this->middleware('can:puede.ver.rol_pago_mes')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago_mes')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = RolPagoMes::ignoreRequest(['campos'])->filter()->get();
        $results = RolPagoMesResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(RolPagoMesRequest $request)
    {
        try {
            $datos = $request->validated();
            $existe_mes = RolPagoMes::where('mes', $request->mes)->where('es_quincena', '1')->get();
            if ($request->es_quincena == false && count($existe_mes) == 0) {
                throw ValidationException::withMessages([
                    '404' => ['Porfavor primero realice el Rol de Pagos de Quincena'],
                ]);
            }
            DB::beginTransaction();
            $rolPago = RolPagoMes::create($datos);
            $modelo = new RolPagoMesResource($rolPago);
            $this->tabla_roles($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de rol de pago' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(RolPagoMes $rolPago,  $rolPagoId)
    {
        $rolPago = RolPagoMes::find($rolPagoId);
        $modelo = new RolPagoMesResource($rolPago);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $rolPagoId)
    {
        $rolPago = RolPagoMes::find($rolPagoId);
        $rolPago->es_quincena = $request->es_quincena;
        $rolPago->save();
        $this->tabla_roles($rolPago, 'MODIFICAR');
        $modelo = new RolPagoMesResource($rolPago);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy($rolPagoId)
    {
        $rolPago = RolPagoMes::find($rolPagoId);
        $rolPago->delete();
        return $rolPago;
    }
    public function imprimir_rol_pago_general(Request $request, $rolPagoId)
    {
        try {
            $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
            $nombre_reporte = 'rol_pagos';
            // Fetch data with relationships
            /*$roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes','egreso_rol_pago','empleado_info'])
                ->where('rol_pago_id', $rolPagoId)
             //   ->join('empleados', 'rol_pago.empleado_id', '=', 'empleados.id') // Reemplaza 'empleado_id' con la clave real de la relación
         //       ->orderBy('empleados.apellidos') // Reemplaza 'apellido' con el nombre real de la columna de apellidos
                ->get();*/
            $roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago', 'empleado_info' => function ($query) {
                $query->orderBy('apellidos', 'asc');
            }])
                ->where('rol_pago_id', $rolPagoId)
                ->get();
            Log::channel('testing')->info('Log', ['roles de pago', $roles_pagos]);

            $reportes = $this->generate_report_data($roles_pagos);
            $vista = 'recursos-humanos.rol_pago_mes';
            $export_excel = new RolPagoMesExport($reportes);

            return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }

    private function generate_report_data($roles_pagos)
    {
        $es_quincena = RolPagoMes::where('mes', $roles_pagos[0]->mes)->where('es_quincena', '1')->first() != null ? true : false;
        $periodo = $this->obtenerPeriodo($roles_pagos[0]->mes, $es_quincena);
        $creador_rol_pago = Empleado::whereHas('user', function ($query) {
            $query->whereHas('permissions', function ($q) {
                $q->where('name', 'puede.elaborar.rol_pago');
            });
        })->first();
        $results = RolPago::empaquetarListado($roles_pagos);
        $column_names_egresos = $this->extract_column_names($results, 'egresos', 'descuento', 'nombre');
        $maxColumEgresosValue = max(array_column($results, 'egresos_cantidad_columna'));
        $column_names_ingresos = $this->extract_column_names($results, 'ingresos', 'concepto_ingreso_info', 'nombre');
        $maxColumIngresosValue = max(array_column($results, 'ingresos_cantidad_columna'));
        // Calculate the sum of specific columns from the main data array
        // Inicializa un array asociativo para almacenar las sumas
        $sumColumns = [
            'salario' => 0,
            'sueldo' => 0,
            'decimo_tercero' => 0,
            'decimo_cuarto' => 0,
            'fondos_reserva' => 0,
            'iess' => 0,
            'anticipo' => 0,
            'bonificacion' => 0,
            'bono_recurente' => 0,
            'total_ingreso' => 0,
            'prestamo_quirorafario' => 0,
            'prestamo_hipotecario' => 0,
            'extension_conyugal' => 0,
            'prestamo_empresarial' => 0,
            'supa' => 0,
            'total_egreso' => 0,
            'total' => 0,
        ];

        // Itera a través del array $results y suma los valores en las columnas
        foreach ($results as $item) {
            $sumColumns['salario'] += $item['salario'];
            $sumColumns['sueldo'] += $item['sueldo'];
            $sumColumns['decimo_tercero'] += $item['decimo_tercero'];
            $sumColumns['decimo_cuarto'] += $item['decimo_cuarto'];
            $sumColumns['fondos_reserva'] += $item['fondos_reserva'];
            $sumColumns['iess'] += $item['iess'];
            $sumColumns['anticipo'] += $item['anticipo'];
            $sumColumns['bonificacion'] += $item['bonificacion'];
            $sumColumns['bono_recurente'] += $item['bono_recurente'];
            $sumColumns['total_ingreso'] += $item['total_ingreso'];
            $sumColumns['prestamo_quirorafario'] += $item['prestamo_quirorafario'];
            $sumColumns['prestamo_hipotecario'] += $item['prestamo_hipotecario'];
            $sumColumns['extension_conyugal'] += $item['extension_conyugal'];
            $sumColumns['prestamo_empresarial'] += $item['prestamo_empresarial'];
            $sumColumns['supa'] += $item['supa'];
            $sumColumns['total_egreso'] += $item['total_egreso'];
            $sumColumns['total'] += $item['total'];
        }

        // El resultado deseado se encuentra ahora en el array $sumColumns

        return [
            'roles_pago' => $results,
            'periodo' => $periodo,
            'cantidad_columna_ingresos' => $maxColumIngresosValue,
            'cantidad_columna_egresos' => $maxColumEgresosValue,
            'columnas_ingresos' => array_unique($column_names_ingresos['ingresos']),
            'columnas_egresos' => array_unique($column_names_egresos['egresos']),
            'sumatoria' => $sumColumns,
            'creador_rol_pago' => $creador_rol_pago,
            'sumatoria_ingresos' => $this->calculate_column_sum($results, $maxColumIngresosValue, 'ingresos_cantidad_columna', 'ingresos'),
            'sumatoria_egresos' => $this->calculate_column_sum($results, $maxColumEgresosValue, 'egresos_cantidad_columna', 'egresos'),
        ];
    }

    private function extract_column_names($results, $key1, $key2, $columnName)
    {
        $column_names = ['egresos' => [], 'ingresos' => []];
        foreach ($results as $item) {
            if ($item[$key1 . '_cantidad_columna'] > 0) {
                foreach ($item[$key1] as $subitem) {
                    $column_names[$key1][] = $subitem[$key2][$columnName];
                }
            }
        }
        return $column_names;
    }
    private function calculate_column_sum($data, $maximo, $key_cantidad, $key1)
    {

        $totalMontoIngresos = array_map(

            function ($item) use ($maximo, $key_cantidad, $key1) {
                $monto = array();
                $i = 0;
                if ($item[$key_cantidad] > 0) {
                    foreach ($item[$key1] as $ingreso) {
                        $monto[$i] = $ingreso['monto'];
                        $i++;
                    }
                }
                if ($item[$key_cantidad] == 0) {
                    for ($j = 0; $j < $maximo; $j++) {
                        $monto[$j] = 0;
                    }
                }
                return $monto;
            },
            $data
        );
        $suma_monto = array_fill(0, $maximo, 0); // Inicializamos el arreglo de suma en ceros
        for ($i = 0; $i < $maximo - 1; $i++) {
            foreach ($totalMontoIngresos as $totalMonto) {
                $suma_monto[$i] += $totalMonto[$i]; // Sumamos el monto en la posición $i
            }
        }

        return $suma_monto;
    }
    /**
     * La función "tabla_roles" calcula e inserta datos de nómina para empleados activos en función de
     * varios factores, como salario, asignaciones, deducciones y préstamos.
     *
     * @param RolPagoMes rol El parámetro `` es una instancia de la clase `RolPagoMes`. Representa
     * un mes de nómina y contiene información como el mes y si es una nómina quincenal o no.
     *
     * @return La función no devuelve nada. Está insertando datos en la tabla de la base de datos
     * "RolPago" usando el método `insert()`.
     */
    private function tabla_roles(RolPagoMes $rol)
    {
        try {
            $empleados_activos = Empleado::where('estado', 1)->where('id', '>', 2)->where('esta_en_rol_pago', '1')->where('realiza_factura', '0')->get();
            $mes = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago = [];
            foreach ($empleados_activos as $empleado) {
                $this->nominaService->setEmpleado($empleado->id);
                $this->prestamoService->setEmpleado($empleado->id);
                // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
                $dias = $rol->es_quincena ? 15 : 30;
                $sueldo =  $this->nominaService->calcularSueldo($dias, $rol->es_quincena);
                $decimo_tercero =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva =  $rol->es_quincena ? 0 : $this->nominaService->calcularFondosReserva();
                $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess =  $rol->es_quincena ? 0 : $this->nominaService->calcularAporteIESS();
                $anticipo =  $rol->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal =  $rol->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $supa =  $rol->es_quincena ? 0 : $empleado->supa;
                $egreso = $rol->es_quincena ? 0 : ($iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $supa);
                $total = abs($ingresos) - $egreso;
                $roles_pago[] = [
                    'empleado_id' => $empleado->id,
                    'dias' => $dias,
                    'mes' => $rol->mes,
                    'sueldo' => $sueldo,
                    'decimo_tercero' => $decimo_tercero,
                    'decimo_cuarto' => $decimo_cuarto,
                    'fondos_reserva' => $fondos_reserva,
                    'total_ingreso' => $ingresos,
                    'iess' => $iess,
                    'anticipo' => $anticipo,
                    'prestamo_quirorafario' => $prestamo_quirorafario,
                    'prestamo_hipotecario' => $prestamo_hipotecario,
                    'extension_conyugal' => $extension_conyugal,
                    'prestamo_empresarial' => $prestamo_empresarial,
                    'total_egreso' => $egreso,
                    'total' => $total,
                    'rol_pago_id' => $rol->id,
                ];
            }
            RolPago::insert($roles_pago);
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['error', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar rol pago por empleado' => [$ex->getMessage()],
            ]);
        }
    }
    public function verificarTodasRolesFinalizadas(Request $request)
    {
        $rol_pago = RolPagoMes::find($request['rol_pago_id']);
        $totalSubrol_pagosNoFinalizadas = $rol_pago->rolPago()->whereIn('estado', [RolPago::EJECUTANDO, RolPago::REALIZADO])->count();
        $estan_finalizadas = $totalSubrol_pagosNoFinalizadas == 0;
        return response()->json(compact('estan_finalizadas'));
    }
    public function FinalizarRolPago(Request $request)
    {
        $rol_pago = RolPagoMes::find($request['rol_pago_id']);
        $rol_pago->finalizado = true;
        $rol_pago->save();
        $modelo = new RolPagoMesResource($rol_pago);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function obtenerPeriodo($mes, $es_quincena)
    {
        $periodo =  $es_quincena ? 'DEL 1 AL  15 ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat(' F Y') : 'DEL 1 AL ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat('t F Y');
        $periodo = strtoupper($periodo);
        return "PERIODO: $periodo";
    }
}
