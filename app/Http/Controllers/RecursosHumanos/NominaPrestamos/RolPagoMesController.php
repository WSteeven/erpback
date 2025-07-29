<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\CashRolPagoExport;
use App\Exports\RolPagoGeneralExport;
use App\Exports\RolPagoMesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\RolPagoMesRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoMesResource;
use App\Jobs\RecursosHumanos\EnviarRolPagoJob;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\RecursosHumanos\NominaPrestamos\PrestamoService;
use Src\App\SystemNotificationService;
use Src\Shared\Utils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class RolPagoMesController extends Controller
{
    private string $entidad = 'rol_pago';
    private ReportePdfExcelService $reporteService;
    private NominaService $nominaService;
    private PrestamoService $prestamoService;
    private string $date;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->nominaService = new NominaService();
        $this->prestamoService = new PrestamoService();
        $this->middleware('can:puede.ver.rol_pago_mes')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago_mes')->only('store');
        $this->middleware('can:puede.eliminar.rol_pago_mes')->only('destroy');
    }

    public function index()
    {
        $results = RolPagoMes::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = RolPagoMesResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * La función de tienda en PHP se utiliza para crear un nuevo registro para el modelo RolPagoMes,
     * realizar comprobaciones de validación y manejar cualquier excepción que pueda ocurrir.
     *
     * @param RolPagoMesRequest $request request El parámetro es una instancia de la clase
     * RolPagoMesRequest, que se utiliza para validar y recuperar los datos enviados en la solicitud HTTP.
     *
     * @return JsonResponse respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     * @throws Throwable
     */
    public function store(RolPagoMesRequest $request)
    {
        try {
            $datos = $request->validated();
            $existe_mes = RolPagoMes::where('mes', $request->mes)->where('es_quincena', '1')->get();
            if (!$request->es_quincena && count($existe_mes) == 0) {
                throw new Exception('Por favor primero realice el Rol de Pagos de Quincena');
            }
//            if (RolPagoMes::where('finalizado', false)->count() > 0) throw new Exception('Por favor asegurate de haber finalizado todos los roles de pagos anteriores para poder crear uno nuevo.');
            DB::beginTransaction();
            $rol = RolPagoMes::create($datos);
            $modelo = new RolPagoMesResource($rol);
            $this->crearRolIndividualMensualEmpleado($rol); //se crean los roles individuales para cada empleado
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['error store ', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * @throws Throwable|ValidationException
     */
//    public function importarRolPago(RolPagoMesRequest $request)
//    {
//        try {
//            DB::beginTransaction();
//            $datos = $request->validated();
//            $rol = RolPagoMes::create($datos);
//            $this->validate($request, [
//                'file' => 'required|mimes:xls,xlsx'
//            ]);
//            if (!$request->hasFile('file')) {
//                throw ValidationException::withMessages([
//                    'file' => ['Debe seleccionar al menos un archivo.'],
//                ]);
//            }
//            Excel::import(new RolPagoImport($request->mes, $rol), $request->file);
//            DB::commit();
//            return response()->json(['mensaje' => 'Subido exitosamente!']);
//        } catch (Exception $e) {
//            DB::rollBack();
//            throw ValidationException::withMessages([
//                'Error al insertar registro' => [$e->getMessage() . $e->getLine()],
//            ]);
//        }
//    }

    /**
     * La función "show" recupera un recurso "RolPagoMes" específico por su ID y lo devuelve como respuesta
     * JSON.
     *
     * @param RolPagoMes $rol
     * @return JsonResponse respuesta JSON que contiene la variable "modelo", que es una instancia de la clase
     * "RolPagoMesResource".
     */
    public function show(RolPagoMes $rol)
    {
        $modelo = new RolPagoMesResource($rol);
        return response()->json(compact('modelo'));
    }

    /**
     * La función actualiza un objeto RolPagoMes con los datos de solicitud proporcionados y devuelve una
     * respuesta JSON con un mensaje y el objeto actualizado.
     *
     * @param Request $request
     * @param RolPagoMes $rol
     * @return JsonResponse código devuelve una respuesta JSON que contiene las variables "mensaje" y "modelo".
     * @throws Throwable
     */
    public function update(Request $request, RolPagoMes $rol)
    {
        $rol->es_quincena = $request->es_quincena;
        $rol->save();
        $this->crearRolIndividualMensualEmpleado($rol);
        $modelo = new RolPagoMesResource($rol);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * La función destruye un objeto RolPagoMes eliminándolo de la base de datos.
     *
     * @param RolPagoMes $rol
     * @return JsonResponse función `destroy` devuelve el objeto `RolPagoMes` eliminado.
     */
    public function destroy(RolPagoMes $rol)
    {
        $rol->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * La función "imprimir_rol_pago_general" genera e imprime un informe general de nómina en PHP.
     *
     * @param Request $request
     * @param int $rolPagoId
     * @return BinaryFileResponse|Response el resultado del método `imprimir_reporte` del objeto ``.
     * @throws ValidationException
     */
    public function imprimirRolPagoGeneral(Request $request, int $rolPagoId)
    {
        try {
            $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
            $nombre_reporte = 'rol_pagos';
            // Fetch data with relationships
            $roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago'])
                ->where('rol_pago_id', $rolPagoId)
                ->get();
            $rol_pago = RolPagoMes::where('id', $rolPagoId)->first();
            $es_quincena = $rol_pago->es_quincena;
            $reportes = $this->generateReportData($roles_pagos, $rol_pago->nombre);
            $vista = $es_quincena ? 'recursos-humanos.rol_pago_quincena' : 'recursos-humanos.rol_pago_mes';
            // Log::channel('testing')->info('Log', ['191 - reportes', $es_quincena, $reportes]);
            $export_excel = new RolPagoMesExport($reportes, $es_quincena);
            $orientacion = $es_quincena ? 'portail' : 'landscape';
            $tipo_pagina = $es_quincena ? 'A4' : 'A2';
            return $this->reporteService->imprimirReporte($tipo, $tipo_pagina, $orientacion, $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error imprimirRolPagoGeneral', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * La función "enviarRoles" recupera una lista de roles de pago basada en un rolPagoId determinado,
     * recupera el empleado correspondiente para cada rol y luego envía el rol de pago al empleado
     * mediante nominaService.
     *
     * @param int $rolPagoId
     * @return JsonResponse respuesta JSON con un mensaje indicando que la nómina ha sido enviada exitosamente.
     * @throws Exception
     */
    public function enviarRoles(int $rolPagoId)
    {
        try {

            $roles = RolPago::where('rol_pago_id', $rolPagoId)->get();
            $enviados = [];
            $enCola = 0;

            foreach ($roles as $rol_pago) {
                $empleado = Empleado::find($rol_pago->empleado_id);

                // Validamos correo
                $email = strtolower($empleado->user->email ?? '');
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning("Correo inválido: $email para empleado ID {$empleado->id}");
                    continue;
                }

                //Enviamos a la cola
                EnviarRolPagoJob::dispatch($rol_pago)->onQueue('emails');
                $enviados[] = $email;
                $enCola++;
            }

            $mensaje = "Se han colocado $enCola roles para el pago en la cola de envio.";
            return response()->json(compact('mensaje', 'enviados'));
        } catch (Exception $e) {
            SystemNotificationService::sendExceptionErrorMailToSystemAdmin("Error con el método RolPagoMesController::enviarRoles: " . $e->getMessage());
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    public function enviarRolesOld(int $rolPagoId)
    {
        try {

            $exitos = [];
            $fallos = [];

            $roles = RolPago::where('rol_pago_id', $rolPagoId)->get();
            foreach ($roles as $rol_pago) {
                try {
                    $empleado = Empleado::where('id', $rol_pago->empleado_id)->first();
                    $this->nominaService->enviar_rol_pago($rol_pago, $empleado);
                    $exitos[] = $empleado->user->email;
                } catch (Exception $e) {
                    $fallos[] = $empleado->user->email;
                    SystemNotificationService::sendExceptionErrorMailToSystemAdmin("Error general: " . $rol_pago . ' <-> ' . $e->getMessage());
                }
            }

            $mensaje = count($fallos) ? "Se enviaron " . count($exitos) . " roles de pago con éxito y " . count($fallos) . " con fallos" : null;
            return response()->json(compact('mensaje', 'exitos', 'fallos'));
        } catch (Exception $e) {
            SystemNotificationService::sendExceptionErrorMailToSystemAdmin("Error con el método RolPagoMesController::enviarRoles: " . $e->getMessage());
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * La función crea un informe de pago de rol en efectivo en formato Excel para un rolPagoId
     * determinado.
     *
     * @param int $rolPagoId
     * @return BinaryFileResponse descarga de un archivo Excel.
     * @throws ValidationException
     */
    public function crearCashRolPago(int $rolPagoId)
    {
        try {

            $nombre_reporte = 'rol_pagos_general';
            $roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago'])
                ->where('rol_pago_id', $rolPagoId)
                ->get();
            $results = RolPago::empaquetarCash($roles_pagos);
            $results = collect($results)->map(function ($elemento, $index) {
                $elemento['item'] = $index + 1;
                return $elemento;
            })->all();
            $reporte = ['reporte' => $results];
            $export_excel = new CashRolPagoExport($reporte);
            return Excel::download($export_excel, $nombre_reporte . '.xlsx');
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'Se obtuvo un error en el método crearCashRolPago');
        }
    }

    /**
     * La función "imprimir_reporte_general" genera un informe en PHP, en base, a la solicitud proporcionada
     * y rolPagoId, y lo exporta en el formato especificado (excel u otro).
     *
     * @param Request $request
     * @param int $rolPagoId
     * @return Response|BinaryFileResponse resultado del método `imprimir_reporte` del objeto `reporteService`.
     * @throws Exception
     */
    public function imprimirReporteGeneral(Request $request, int $rolPagoId)
    {
        $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
        $nombre_reporte = 'rol_pagos_general';
        $roles_de_pago = RolPago::where('rol_pago_id', $rolPagoId)->with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago'])->get();
        $sumatoria = RolPago::where('rol_pago_id', $rolPagoId)
            ->select(
                DB::raw('SUM(ROUND(decimo_tercero, 2)) as decimo_tercero'),
//                DB::raw('SUM(ROUND(decimo_cuarto, 2))  as decimo_cuarto'),
//                DB::raw('SUM(ROUND(fondos_reserva, 2))  as fondos_reserva'),
//                DB::raw('SUM(ROUND(bonificacion, 2))  as bonificacion'),
//                DB::raw('SUM(ROUND(total_ingreso, 2))  as total_ingreso'),
//                DB::raw('SUM(ROUND(comisiones, 2))  as comisiones'),
//                DB::raw('SUM(ROUND(iess, 2))  as iess'),
//                DB::raw('SUM(ROUND(anticipo, 2))  as anticipo'),
//                DB::raw('SUM(ROUND(prestamo_quirorafario, 2))  as prestamo_quirorafario'),
//                DB::raw('SUM(ROUND(prestamo_hipotecario, 2))  as prestamo_hipotecario'),
//                DB::raw('SUM(ROUND(extension_conyugal, 2))  as extension_conyugal'),
//                DB::raw('SUM(ROUND(prestamo_empresarial, 2))  as prestamo_empresarial'),
//                DB::raw('SUM(ROUND(bono_recurente, 2))  as bono_recurente'),
//                DB::raw('SUM(ROUND(total_egreso, 2))  as total_egreso'),
//                DB::raw('SUM(ROUND(total, 2)) as total'),
            )
            ->first();
        $results = RolPago::empaquetarListado($roles_de_pago);
        $column_names_ingresos = $this->extractColumnNames($results, 'ingresos', 'concepto_ingreso_info', 'nombre');
        $column_names_ingresos = array_unique($column_names_ingresos['ingresos']);
        $column_names_egresos = $this->extractColumnNames($results, 'egresos', 'descuento', 'nombre');
        $column_names_egresos = array_unique($column_names_egresos['egresos']);
        $colum_ingreso_value = $this->columValues($results, $column_names_ingresos, 'ingresos', 'concepto_ingreso_info');
        $colum_egreso_value = $this->columValues($results, $column_names_egresos, 'egresos', 'descuento');
        $rol = RolPagoMes::where('id', $rolPagoId)->first();
        $reportes = ['reporte' => $sumatoria, 'rolPago' => $rol, 'ingresos' => $this->sumatoriaLlaves($colum_ingreso_value), 'egresos' => $this->sumatoriaLlaves($colum_egreso_value)];
        $vista = 'recursos-humanos.reporte_general';
        $export_excel = new RolPagoGeneralExport($reportes);
        return $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
    }

    /**
     * La función "generate_report_data" en PHP genera un informe procesando una serie de roles de pago y
     * extrayendo datos relevantes para su visualización.
     *
     * @param array|Collection $roles_pagos $roles_pagos Una serie de datos de pago de roles.
     * @param string $nombre
     * @return array matriz con las siguientes claves:
     */
    private function generateReportData(array|Collection $roles_pagos, string $nombre)
    {
        $es_quincena = RolPagoMes::where('mes', $roles_pagos[0]->mes)->where('es_quincena', '1')->first() != null;
        $periodo = $this->obtenerPeriodo($roles_pagos[0]->mes, $es_quincena);
        $departamento_gerencia = Departamento::where("nombre", Departamento::DEPARTAMENTO_GERENCIA)->first();
        $responsable_gerencia = $departamento_gerencia ? $departamento_gerencia->responsable : Empleado::find(117);
        $creador_rol_pago = User::role(User::ROL_RECURSOS_HUMANOS)->permission('puede.elaborar.rol_pago')->first()->empleado;
        $results = RolPago::empaquetarListado($roles_pagos);
        $results = collect($results)->map(function ($elemento, $index) {
            $elemento['item'] = $index + 1;
            return $elemento;
        })->all();
        $column_names_egresos = $this->extractColumnNames($results, 'egresos', 'descuento', 'abreviatura');
        $column_names_ingresos = $this->extractColumnNames($results, 'ingresos', 'concepto_ingreso_info', 'abreviatura');
        $columnas_ingresos = array_unique($column_names_ingresos['ingresos']);
        $colum_ingreso_value = $this->columValues($results, $columnas_ingresos, 'ingresos', 'concepto_ingreso_info', true);
        $columnas_egresos = array_unique($column_names_egresos['egresos']);
        $colum_egreso_value = $this->columValues($results, $columnas_egresos, 'egresos', 'descuento', true);
        $max_column_egresos_value = count($columnas_egresos);
        $max_column_ingresos_value = count($columnas_ingresos);


        // Calculate the sum of specific columns from the main data array
        $sum_columns = [
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
            $sum_columns['salario'] += $item['salario'];
            $sum_columns['sueldo'] += $item['sueldo'];
            $sum_columns['decimo_tercero'] += $item['decimo_tercero'];
            $sum_columns['decimo_cuarto'] += $item['decimo_cuarto'];
            $sum_columns['fondos_reserva'] += $item['fondos_reserva'];
            $sum_columns['iess'] += $item['iess'];
            $sum_columns['anticipo'] += $item['anticipo'];
            $sum_columns['bonificacion'] += $item['bonificacion'];
            $sum_columns['bono_recurente'] += $item['bono_recurente'];
            $sum_columns['total_ingreso'] += $item['total_ingreso'];
            $sum_columns['prestamo_quirorafario'] += $item['prestamo_quirorafario'];
            $sum_columns['prestamo_hipotecario'] += $item['prestamo_hipotecario'];
            $sum_columns['extension_conyugal'] += $item['extension_conyugal'];
            $sum_columns['prestamo_empresarial'] += $item['prestamo_empresarial'];
            $sum_columns['supa'] += $item['supa'];
            $sum_columns['total_egreso'] += $item['total_egreso'];
            $sum_columns['total'] += $item['total'];
        }
        // El resultado deseado se encuentra ahora en el array $sumColumns
        return [
            'roles_pago' => $results,
            'periodo' => $periodo,
            'cantidad_columna_ingresos' => $max_column_ingresos_value,
            'cantidad_columna_egresos' => $max_column_egresos_value,
            'colum_ingreso_value' => $colum_ingreso_value,
            'colum_egreso_value' => $colum_egreso_value,
            'columnas_ingresos' => $columnas_ingresos,
            'columnas_egresos' => $columnas_egresos,
            'sumatoria' => $sum_columns,
            'nombre' => $nombre,
            'creador_rol_pago' => $creador_rol_pago,
            'aprueba_rol_pago' => $responsable_gerencia,
            'sumatoria_ingresos' => $this->calculateColumnSum($results, $max_column_ingresos_value),
            'sumatoria_egresos' => $this->sumatoriaLlaves($colum_egreso_value),
        ];
    }

    /**
     * La función "sumatoria_keys" calcula la suma de valores de cada clave en una matriz asociativa.
     *
     * @param array $data
     * @return array array asociativo llamado `sumatoria_por_llaves`. Esta matriz contiene la suma del campo
     * "valor" para cada clave en la matriz `sumatoria_por_llaves`.
     */
    private function sumatoriaLlaves(array $data)
    {
        // Inicializa un arreglo asociativo para almacenar la sumatoria por llaves
        $sumatoria_por_llaves = [];

        // Itera a través de la estructura de datos y calcula la suma por llaves y campo "valor"
        foreach ($data as $key => $value) {
            $sumatoria = array_sum(array_map(function ($entry) {
                return floatval($entry["valor"]);
            }, $value));
            $sumatoria_por_llaves[$key] = $sumatoria;
        }
        return $sumatoria_por_llaves;
    }

    /**
     * La función `colum_values` toma una matriz de datos, nombres de columnas y dos claves, y agrupa los
     * datos por un nombre de columna específico.
     *
     * @param array $data
     * @param array $column_name
     * @param string $key1
     * @param string $key2
     * @param bool $abreviatura
     * @return array matriz llamada .
     */
    private function columValues(array $data, array $column_name, string $key1, string $key2, bool $abreviatura = false)
    {
        // Creamos un arreglo para almacenar los objetos agrupados por descuento_id
        $grouped_data = [];
        foreach ($data as $item) {
            // Recorremos el arreglo original y agrupamos los objetos por descuento_id
            foreach ($item[$key1] as $item) {
                $descuento_id = $abreviatura ? $item[$key2]->abreviatura : $item[$key2]->nombre;
                if (!isset($grouped_data[$descuento_id])) {
                    $grouped_data[$descuento_id] = [];
                }
                foreach ($column_name as $name) {
                    if ($name != $descuento_id) {
                        $grouped_data[$name][] = ['id' => $item['id_rol_pago'], 'valor' => 0];
                    }
                    if ($name == $descuento_id) {
                        $grouped_data[$descuento_id][] = ['id' => $item['id_rol_pago'], 'valor' => $item['monto']];
                    }
                }
            }
        }
        return $this->eliminarDuplicados($grouped_data);
    }

    public function eliminarDuplicados(array $datos): array
    {
        $array_sin_duplicados = [];
        foreach ($datos as $clave => $objeto) {
            $array_sin_duplicados[$clave] = [];
            $serial_ant = null;
            if ($this->verificarValorCeroPrimeraPosicion($objeto)) {
                rsort($objeto);
            }
            foreach ($objeto as $objeto_actual) {
                $serial_act = $clave . '' . $objeto_actual['id'];
                if ($objeto_actual['id'] !== 0 && $serial_act !== $serial_ant) {
                    $array_sin_duplicados[$clave][] = $objeto_actual;
                }
                $serial_ant = $clave . '' . $objeto_actual['id'];
            }
        }

        return $array_sin_duplicados;
    }

    function verificarValorCeroPrimeraPosicion($array)
    {
        if ($array[0]['valor'] == 0) {
            return true;
        }
        return false;
    }


    /**
     * La función extrae nombres de columnas de una matriz multidimensional basada en claves especificadas
     * y un nombre de columna.
     *
     * @param array $results
     * @param string $key1
     * @param string $key2
     * @param string $columnName
     * @return array|array[] serie de nombres de columnas. La matriz tiene dos claves, 'egresos' e 'ingresos', cada
     * una de las cuales contiene una matriz de nombres de columnas.
     */
    private function extractColumnNames(array $results, string $key1, string $key2, string $columnName)
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

    /**
     * La función calcula la suma de valores en una columna específica de una matriz, según ciertas
     * condiciones.
     *
     * @param $data
     * @param $maximo
     * @return array matriz que contiene la suma de valores para cada clave única en los datos de entrada.
     */
    private function calculateColumnSum($data, $maximo)
    {
        $total_monto_ingresos = array_map(
            function ($item) use ($maximo, $data) {
                $key1 = 'ingresos';
                $key_cantidad = 'ingresos_cantidad_columna';
                $monto = array();
                if ($item[$key_cantidad] > 0) {
                    foreach ($item[$key1] as $subitem) {
                        $monto[$subitem['descuento_id']] = $subitem['monto'];
                    }
                }
//                if ($item[$key_cantidad] == 0) {
//                    for ($j = 0; $j < $maximo; $j++) {
                //   $monto[$j] = 0;
//                    }
//                }
                return $monto;
            },
            $data
        );
        $resultados = [];
        foreach ($total_monto_ingresos as $elemento) {
            // Verifica si el elemento es un arreglo asociativo (objeto JSON)
            if (is_array($elemento) && count($elemento) > 0) {
                foreach ($elemento as $clave => $valor) {
                    // Verifica si la clave ya existe en los resultados
                    if (array_key_exists($clave, $resultados)) {
                        if ($clave != 0) {
                            // Si existe, suma el valor actual al valor existente
                            $resultados[$clave] += floatval($valor);
                        }
                    } else {
                        if ($clave != 0) {
                            // Si no existe, crea la clave y asigna el valor actual
                            $resultados[$clave] = floatval($valor);
                        }
                    }
                }
            }
        }
        $arreglo = $resultados;
        if (is_object($resultados)) {
            $arreglo = [$resultados];
        }
        return $arreglo;
    }

    /**
     * La función "tabla_roles" calcula e inserta datos de nómina para empleados activos en función de
     * varios factores, como salario, asignaciones, deducciones y préstamos.
     *
     * @param RolPagoMes $rol
     * @return void
     * @throws Throwable
     */
    private function crearRolIndividualMensualEmpleado(RolPagoMes $rol)
    {
        Log::channel('testing')->info('Log', ['rol', $rol]);
        try {
            $mes = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m'); // mes en formato yyyy-mm
            $mes_fecha = new Carbon($mes); //mes en instancia de fecha
            $ultimo_dia_mes = $mes_fecha->endOfMonth();
            $empleados_activos = Empleado::where('id', '>', 2)
                ->where('estado', true)
                ->where('esta_en_rol_pago', true)
                ->where('salario', '!=', 0)
                ->where('fecha_vinculacion', '<', $ultimo_dia_mes)
                ->orderBy('apellidos')->get();
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago = [];
            foreach ($empleados_activos as $empleado) {
                $this->nominaService->setEmpleado($empleado->id);
                $this->prestamoService->setEmpleado($empleado->id);
                // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
                $dias_transcurridos = $rol->es_quincena ? 15 : 30;
                $dias = $this->nominaService->calcularDias($dias_transcurridos);
                //$salario = $this->nominaService->calcularSalario();
                $salario = $empleado->salario;
                $sueldo = $this->nominaService->calcularSueldo($dias, $rol->es_quincena);
                $decimo_tercero = $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto = $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva = $rol->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess = $rol->es_quincena ? 0 : $this->nominaService->calcularAporteIESS();
                $anticipo = $rol->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario = $rol->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario = $rol->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial = $rol->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal = $rol->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $empleado->supa != null ? $empleado->supa : 0;
                $supa = $rol->es_quincena ? 0 : $valor_supa;
                $egreso = $rol->es_quincena ? 0 : ($iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $supa);
                $total = abs($ingresos) - $egreso;
                $roles_pago[] = [
                    'empleado_id' => $empleado->id,
                    'dias' => $dias,
                    'mes' => $rol->mes,
                    'salario' => $salario,
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
                    'supa' => $supa,
                    'total_egreso' => $egreso,
                    'total' => $total,
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                ];
            }
            $rol->rolPago()->createMany($roles_pago);
            if (!$rol->es_quincena) {
                // Aqui se registra los ingresos (vacaciones, bonificaciones, etc)
                $this->nominaService->registrarIngresosProgramados($rol);
                // Aquí se registra los egresos, solo en caso de que sea rol de fin de mes
                $this->nominaService->registrarEgresosProgramados($rol);
            }

        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['erro tablaRoles', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar rol pago por empleado' => [$ex->getMessage()],
            ]);
        }
    }


    /**
     * La función "actualizar_tabla_roles" calcula y actualiza la tabla roles_pago de los empleados en
     * función de diversos cálculos de salarios y deducciones.
     *
     * @param RolPagoMes $rol
     * @throws Throwable
     */
    public function agregarNuevosEmpleados(RolPagoMes $rol)
    {
        try {
            // $rol_pago = RolPagoMes::find($rolPagoId);
            $mes_rol = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
            $final_mes = new Carbon($mes_rol);
            $ultimo_dia_mes = $final_mes->endOfMonth();
            $empleados_sin_rol_pago = Empleado::where('id', '>', 2)
                ->where('estado', true)
                ->where('esta_en_rol_pago', true)
                ->where('fecha_vinculacion', '<', $ultimo_dia_mes)
                ->where('salario', '!=', 0)
                ->whereDoesntHave('rolesPago', function ($query) use ($rol) {
                    // Validar que no haya roles asociados al ID del rol actual
                    $query->where('rol_pago_id', $rol->id);
                })->get();
            // Log::channel('testing')->info('Log', ['mes rol', $rol, $mes_rol, $final_mes, $ultimo_dia_mes]);
            // Log::channel('testing')->info('Log', ['empleados sin rol', $empleadosSinRolPago]);
            $mes = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago = [];
            foreach ($empleados_sin_rol_pago as $empleado) {
                $this->nominaService->setEmpleado($empleado->id);
                $this->prestamoService->setEmpleado($empleado->id);
                $dias_transcurridos = $rol->es_quincena ? 15 : 30;
                $dias = $this->nominaService->calcularDias($dias_transcurridos);
                $salario = $this->nominaService->calcularSalario();
                // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
                $sueldo = $this->nominaService->calcularSueldo($dias, $rol->es_quincena);
                $decimo_tercero = $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto = $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva = $rol->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess = $rol->es_quincena ? 0 : $this->nominaService->calcularAporteIESS($dias);
                $anticipo = $rol->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario = $rol->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario = $rol->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial = $rol->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal = $rol->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $empleado->supa != null ? $empleado->supa : 0;
                $supa = $rol->es_quincena ? 0 : $valor_supa;
                $egreso = $rol->es_quincena ? 0 : ($iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $supa);
                $total = abs($ingresos) - $egreso;
                $roles_pago[] = [
                    'empleado_id' => $empleado->id,
                    'dias' => $dias,
                    'mes' => $rol->mes,
                    'salario' => $salario,
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
                    'supa' => $supa,
                    'total_egreso' => $egreso,
                    'total' => $total,
                    'rol_pago_id' => $rol->id,
                    'estado' => RolPago::EJECUTANDO,
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                ];
            }
            $rol->rolPago()->createMany($roles_pago);
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
        $total_subrol_pagos_no_finalizadas = $rol_pago->rolPago()->whereIn('estado', [RolPago::EJECUTANDO, RolPago::REALIZADO])->count();
        $estan_finalizadas = $total_subrol_pagos_no_finalizadas == 0;
        return response()->json(compact('estan_finalizadas'));
    }

    /**
     * @param RolPagoMes $rol_mes
     * @return void
     * @throws ValidationException
     */
    public function actualizarTablaRoles(RolPagoMes $rol_mes)
    {
        try {
            $mes = Carbon::createFromFormat('m-Y', $rol_mes->mes)->format('Y-m');
            Log::channel('testing')->info('Log', ['mes', $mes]);
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago = RolPago::where('rol_pago_id', $rol_mes->id)->get();
//            Log::channel('testing')->info('Log', ['roles_pago', $roles_pago]);
            foreach ($roles_pago as $rol_pago) {
                $this->nominaService->setEmpleado($rol_pago->empleado_id);
                $this->prestamoService->setEmpleado($rol_pago->empleado_id);
                $this->nominaService->setRolPago($rol_mes);
                $dias = $rol_pago->dias;
                $salario = $this->nominaService->calcularSalario();
                $sueldo = $this->nominaService->calcularSueldo($dias, $rol_mes->es_quincena);
                if ($rol_mes->es_quincena) {
                    $quincena = $this->nominaService->calcularSueldo($dias, $rol_mes->es_quincena);
                    if ($quincena !== $rol_pago->sueldo) {
                        $sueldo = $rol_pago->sueldo;
                    }
                }
                $decimo_tercero = $rol_mes->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto = $rol_mes->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva = $rol_mes->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol_mes->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $this->nominaService->obtener_total_ingresos();
                $iess = $rol_mes->es_quincena ? 0 : $this->nominaService->calcularAporteIESS($dias);
                $anticipo = $rol_mes->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario = $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario = $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial = $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal = $rol_mes->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $this->nominaService->getEmpleado()->supa != null ? $this->nominaService->getEmpleado()->supa : 0;
                $supa = $rol_mes->es_quincena ? 0 : $valor_supa;
                $egreso = $rol_mes->es_quincena ? 0 : ($iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $this->nominaService->obtener_total_descuentos_multas() + $supa);
                $total = abs($ingresos) - $egreso;
                $rol_pago_mes_empleado = RolPago::where('rol_pago_id', $rol_mes->id)->where('empleado_id', $rol_pago->empleado_id)->first();
                $rol_pago_mes_empleado->update(array(
                    'empleado_id' => $rol_pago->empleado_id,
                    'dias' => $dias,
                    'mes' => $rol_mes->mes,
                    'salario' => $salario,
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
                    'supa' => $supa,
                    'total_egreso' => $egreso,
                    'total' => $total,
                    'rol_pago_id' => $rol_mes->id,
                ));
            }
            if (!$rol_mes->es_quincena) {
                // Aqui se registra los ingresos (vacaciones, bonificaciones, etc)
                $this->nominaService->registrarIngresosProgramados($rol_mes);
                // Aquí se registra los egresos, solo en caso de que sea rol de fin de mes
                $this->nominaService->registrarEgresosProgramados($rol_mes);
            }
        } catch (Throwable|Exception $ex) {
            Log::channel('testing')->info('Log', ['error actualizarTablaRoles', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Error al refrescar rol pago por empleado');
        }
    }

    /**
     * @throws ValidationException
     */
    public function refrescarRolPago(RolPagoMes $rol)
    {
        // $this->agregar_nuevos_empleados($rol_pago);
        Log::channel('testing')->info('Log', ['RolPagoMes', $rol]);
        $this->actualizarTablaRoles($rol);
        $mensaje = "Rol de pago Actualizado Exitosamente";
        return response()->json(compact('mensaje'));
    }

    public function activarRolPago(RolPagoMes $rol)
    {
        $rol->finalizado = false;
        $rol->save();
        $modelo = new RolPagoMesResource($rol);
        $mensaje = 'Rol activado exitosamente';

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function finalizarRolPago(Request $request)
    {
        $rol_pago = RolPagoMes::find($request['rol_pago_id']);
        $rol_pago->finalizado = true;
        $rol_pago->save();
        // TODO: Revisar este codigo para finalizar algunas cosas que faltan
        // mandar a marcar como pagadas las vacaciones y los descuentos concernientes a este pago
//        $this->nominaService->actualizarIngresosProgramadosAlFinalizarRolPago($rol_pago);
        $this->nominaService->actualizarEgresosProgramadosAlFinalizarRolPago($rol_pago);


        $modelo = new RolPagoMesResource($rol_pago);
        if (!$rol_pago->es_quincena) {
            $mes = $rol_pago->mes;
            // Divide la fecha en mes y año
            list($month, $year) = explode('-', $mes);
            // Crea un objeto Carbon para representar la fecha en Laravel
            $date = Carbon::createFromDate($year, $month, 1);
            // Formatea la fecha en el formato deseado
            $mes = $date->format('Y-m');
            $this->prestamoService->setMes($mes);
            $this->prestamoService->pagarPrestamoEmpresarial();
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function obtenerPeriodo($mes, $es_quincena)
    {
        $periodo = $es_quincena ? 'DEL 1 AL  15 ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat(' F Y') : 'DEL 1 AL ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat('t F Y');
        $periodo = strtoupper($periodo);
        return "PERIODO: $periodo";
    }
}
