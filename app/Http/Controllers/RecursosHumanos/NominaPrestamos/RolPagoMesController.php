<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\RolPagoExport;
use App\Exports\RolPagoMesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolPagoMesRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoMesResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Shared\Utils;

class RolPagoMesController extends Controller
{
    private $entidad = 'Rol de Pago';
    private $reporteService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
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
            /*  if (count($existe_mes) > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Rol de Mes ya esta creado, porfavor ingrese un mes diferente'],
                ]);
            }*/
            DB::beginTransaction();
            $rolPago = RolPagoMes::create($datos);
            $this->tabla_roles($rolPago, 'CREAR');
            $modelo = new RolPagoMesResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
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
            $roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes'])
                ->where('rol_pago_id', $rolPagoId)->get();

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
        $sumColumns = array_reduce($results, function ($carry, $item) {
            $carry['salario'] += $item['salario'];
            $carry['sueldo'] += $item['sueldo'];
            $carry['decimo_tercero'] += $item['decimo_tercero'];
            $carry['decimo_cuarto'] += $item['decimo_cuarto'];
            $carry['fondos_reserva'] += $item['fondos_reserva'];
            $carry['iess'] += $item['iess'];
            $carry['anticipo'] += $item['anticipo'];
            $carry['bonificacion'] += $item['bonificacion'];
            $carry['bono_recurente'] += $item['bono_recurente'];
            $carry['total_ingreso'] += $item['total_ingreso'];
            $carry['prestamo_quirorafario'] += $item['prestamo_quirorafario'];
            $carry['prestamo_hipotecario'] += $item['prestamo_hipotecario'];
            $carry['extension_conyugal'] += $item['extension_conyugal'];
            $carry['prestamo_empresarial'] += $item['prestamo_empresarial'];
            $carry['supa'] += $item['supa'];
            $carry['total_egreso'] += $item['total_egreso'];
            $carry['total'] += $item['total'];
            return $carry;
        }, [
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
        ]);
        return [
            'roles_pago' => $results,
            'periodo' => $periodo,
            'cantidad_columna_ingresos' => $maxColumIngresosValue,
            'cantidad_columna_egresos' => $maxColumEgresosValue,
            'columnas_ingresos' => array_unique($column_names_ingresos['ingresos']),
            'columnas_egresos' => array_unique($column_names_egresos['egresos']),
            'sumatoria' => $sumColumns,
            'creador_rol_pago' => $creador_rol_pago ,
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
    private function tabla_roles(RolPagoMes $rol, $tipo)
    {
        $empleados_activos = Empleado::where('estado', 1)->where('id', '!=', 1)->get();
        $sueldo_basico =  Rubros::find(2) != null ? Rubros::find(2)->valor_rubro : 0;
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        $porcentaje_anticipo = Rubros::find(4) != null ? Rubros::find(4)->valor_rubro / 100 : 0;
        $mes = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
        $prestamos_hipotecarios = PrestamoHipotecario::where('mes', $rol->mes)
            ->groupBy('empleado_id')
            ->select('empleado_id', DB::raw('SUM(valor) as total_valor'))
            ->get()
            ->pluck('total_valor', 'empleado_id');
        $prestamos_quirorafarios = PrestamoQuirorafario::where('mes', $mes)
            ->groupBy('empleado_id')
            ->select('empleado_id', DB::raw('SUM(valor) as total_valor'))
            ->get()
            ->pluck(DB::raw('SUM(valor)'), 'empleado_id');
        $permisos_sin_recuperar = DB::table('permiso_empleados')
            ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$mes])
            ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$mes])
            ->where('recupero', 0)
            ->groupBy('empleado_id')
            ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1)  as total_dias_permiso'))
            ->get()
            ->pluck('total_dias_permiso', 'empleado_id');
        $extenciones_salud = ExtensionCoverturaSalud::where('mes', $mes)
            ->groupBy('empleado_id')
            ->select('empleado_id', DB::raw('SUM(aporte) as total_valor'))
            ->get()
            ->pluck('total_valor', 'empleado_id');
        $prestamos_empresariales = PrestamoEmpresarial::where('estado', 'ACTIVO')
            ->whereRaw('DATE_FORMAT(plazos.fecha_vencimiento, "%Y-%m") <= ?', [$mes])
            ->join('plazo_prestamo_empresarial as plazos', 'prestamo_empresarial.id', '=', 'plazos.id_prestamo_empresarial')
            ->groupBy('prestamo_empresarial.id') // Agrupamos por el ID del préstamo empresarial
            ->select('solicitante', DB::raw('SUM(plazos.valor_a_pagar) as total_valor'))
            ->get()
            ->pluck('total_valor', 'solicitante');
        $roles_pago =  collect($empleados_activos)->map(function ($empleado) use ($rol, $sueldo_basico, $porcentaje_iess, $porcentaje_anticipo, $permisos_sin_recuperar,  $prestamos_hipotecarios, $prestamos_quirorafarios, $prestamos_empresariales, $extenciones_salud) {
            // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
            $dias_permiso_sin_recuperar = $permisos_sin_recuperar->has($empleado->id) ? $permisos_sin_recuperar[$empleado->id] : 0;
            $dias = 30;
            $salario = $empleado->salario;
            $decimo_tercero = 0;
            $decimo_cuarto = 0;
            $fondos_reserva = 0;
            $iess = 0;
            $anticipo = 0;
            $prestamo_quirorafario =  0;
            $prestamo_hipotecario =  0;
            $extension_conyugal =  0;
            $prestamo_empresarial =  0;
            $ingresos = 0;
            $egreso = 0;
            $sueldo = 0;
            if ($rol->es_quincena) {
                $sueldo = ($salario / 30) * ($dias - $dias_permiso_sin_recuperar);
                $sueldo = $sueldo *  $porcentaje_anticipo;
                $dias = 15;
                $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
            } else {
                $sueldo = ($salario / 30) * ($dias - $dias_permiso_sin_recuperar);
                $decimo_tercero = ($salario / 360) * $dias;
                $decimo_cuarto = ($sueldo_basico / 360) * $dias;
                $fondos_reserva = 0;
                $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess = ($sueldo) * $porcentaje_iess;
                $anticipo = $sueldo *  $porcentaje_anticipo;
                $prestamo_quirorafario = $prestamos_quirorafarios->has($empleado->id) ? $prestamos_quirorafarios[$empleado->id] : 0;
                $prestamo_hipotecario = $prestamos_hipotecarios->has($empleado->id) ? $prestamos_hipotecarios[$empleado->id] : 0;
                $extension_conyugal = $extenciones_salud->has($empleado->id) ? $extenciones_salud[$empleado->id] : 0;
                $prestamo_empresarial = $prestamos_empresariales->has($empleado->id) ? $prestamos_empresariales[$empleado->id] : 0;
                $supa = $empleado->supa;
                $egreso = $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $supa;
            }
            $total = abs($ingresos) - $egreso;
            return [
                'empleado_id' => $empleado->id,
                'dias' => $dias,
                'mes' => $rol->mes,
                'sueldo' =>  $sueldo,
                'decimo_tercero' =>  $decimo_tercero,
                'decimo_cuarto' =>  $decimo_cuarto,
                'fondos_reserva' =>  $fondos_reserva,
                'total_ingreso' =>  $ingresos,
                'iess' =>  $iess,
                'anticipo' =>  $anticipo,
                'prestamo_quirorafario' =>  $prestamo_quirorafario,
                'prestamo_hipotecario' =>  $prestamo_hipotecario,
                'extension_conyugal' =>  $extension_conyugal,
                'prestamo_empresarial' =>  $prestamo_empresarial,
                'total_egreso' =>  $egreso,
                'total' =>  $total,
                'rol_pago_id' => $rol->id,
            ];
        })->toArray();
        switch ($tipo) {
            case 'CREAR':
                RolPago::insert($roles_pago);
                break;
            case 'MODIFICAR':
                $actualizacionMasiva = collect($roles_pago)->map(function ($item) {
                    $empleadoId = $item['empleado_id'];
                    unset($item['empleado_id']);
                    return [
                        'data' => $item,
                        'empleado_id' => $empleadoId,
                    ];
                });

                foreach ($actualizacionMasiva as $actualizacion) {
                    $empleadoId = $actualizacion['empleado_id'];
                    $data = $actualizacion['data'];

                    RolPago::updateOrCreate(
                        ['empleado_id' => $empleadoId, 'mes' => $data['mes']],
                        $data
                    );
                }
                break;
            default:
                # code...
                break;
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
