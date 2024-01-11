<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\CashRolPagoExport;
use App\Exports\RolPagoGeneralExport;
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
use Maatwebsite\Excel\Facades\Excel;
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
    private $date;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->nominaService = new NominaService();
        $this->prestamoService = new PrestamoService();
        $this->middleware('can:puede.ver.rol_pago_mes')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago_mes')->only('store');
    }

    public function index(Request $request)
    {
        $results = RolPagoMes::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = RolPagoMesResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * La función de tienda en PHP se utiliza para crear un nuevo registro para el modelo RolPagoMes,
     * realizar comprobaciones de validación y manejar cualquier excepción que pueda ocurrir.
     *
     * @param RolPagoMesRequest request El parámetro  es una instancia de la clase
     * RolPagoMesRequest, que se utiliza para validar y recuperar los datos enviados en la solicitud HTTP.
     *
     * @return una respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     */
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
            Log::channel('testing')->info('Log', ['despues de tabla roles']);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . $e->getLine()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de rol de pago' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * La función "show" recupera un recurso "RolPagoMes" específico por su ID y lo devuelve como respuesta
     * JSON.
     *
     * @param RolPagoMes rolPago Este parámetro es una instancia del modelo `RolPagoMes`. Se utiliza para
     * recuperar el objeto `RolPagoMes` específico de la base de datos.
     * @param rolPagoId El parámetro `` es el ID del objeto `RolPagoMes` que desea recuperar y
     * mostrar.
     *
     * @return una respuesta JSON que contiene la variable "modelo", que es una instancia de la clase
     * "RolPagoMesResource".
     */
    public function show(RolPagoMes $rolPago,  $rolPagoId)
    {
        $rolPago = RolPagoMes::find($rolPagoId);
        $modelo = new RolPagoMesResource($rolPago);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * La función actualiza un objeto RolPagoMes con los datos de solicitud proporcionados y devuelve una
     * respuesta JSON con un mensaje y el objeto actualizado.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que representa la
     * solicitud HTTP realizada al servidor. Contiene información sobre la solicitud, como el método de
     * solicitud, encabezados y datos de entrada.
     * @param rolPagoId El parámetro "rolPagoId" es el ID del objeto "RolPagoMes" que necesita ser
     * actualizado.
     *
     * @return El código devuelve una respuesta JSON que contiene las variables "mensaje" y "modelo".
     */
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

    /**
     * La función destruye un objeto RolPagoMes eliminándolo de la base de datos.
     *
     * @param rolPagoId El parámetro `rolPagoId` es el ID del objeto `RolPagoMes` que debe eliminarse.
     *
     * @return La función `destroy` devuelve el objeto `RolPagoMes` eliminado.
     */
    public function destroy($rolPagoId)
    {
        $rolPago = RolPagoMes::find($rolPagoId);
        $rolPago->delete();
        return $rolPago;
    }
    /**
     * La función "imprimir_rol_pago_general" genera e imprime un informe general de nómina en PHP.
     *
     * @param Request request El parámetro `` es una instancia de la clase
     * `Illuminate\Http\Request`, que representa una solicitud HTTP. Contiene información sobre la
     * solicitud, como el método de solicitud, encabezados y datos de entrada.
     * @param rolPagoId El parámetro `` es el ID del rol_pago (nómina) para el cual desea generar
     * un informe. Se utiliza para recuperar el rol_pago específico de la base de datos.
     *
     * @return el resultado del método `imprimir_reporte` del objeto ``.
     */
    public function imprimir_rol_pago_general(Request $request, $rolPagoId)
    {
        try {
            $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
            $nombre_reporte = 'rol_pagos';
            // Fetch data with relationships
            $roles_pagos = RolPago::with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago'])
                ->where('rol_pago_id', $rolPagoId)
                ->get();
            $rol_pago =  RolPagoMes::where('id', $rolPagoId)->first();
            $es_quincena = $rol_pago->es_quincena;
            $reportes = $this->generate_report_data($roles_pagos, $rol_pago->nombre);
            $vista = $es_quincena ? 'recursos-humanos.rol_pago_quincena' : 'recursos-humanos.rol_pago_mes';
            $export_excel = new RolPagoMesExport($reportes, $es_quincena);
            $orientacion = $es_quincena ? 'portail' : 'landscape';
            $tipo_pagina = $es_quincena ? 'A4' : 'A2';
            return $this->reporteService->imprimir_reporte($tipo,  $tipo_pagina, $orientacion, $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
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
     * @param rolPagoId El parámetro "rolPagoId" es el ID del rol_pago (rol de pago) que debe enviarse.
     *
     * @return una respuesta JSON con un mensaje indicando que la nómina ha sido enviada exitosamente.
     */
    public function enviarRoles($rolPagoId)
    {
        $rolesPago = RolPago::where('rol_pago_id', $rolPagoId)->get();
        $empleado = Empleado::where('id', 26)->first();
        foreach ($rolesPago as $rol_pago) {
            $empleado = Empleado::where('id', $rol_pago->empleado_id)->first();
            $this->nominaService->enviar_rol_pago($rol_pago->id, $empleado);
        }
        $mensaje = 'Rol de pago enviado correctamente';
        return response()->json(compact('mensaje'));
    }

    /**
     * La función crea un informe de pago de rol en efectivo en formato Excel para un rolPagoId
     * determinado.
     *
     * @param rolPagoId El parámetro `rolPagoId` es el ID del rol_pago (nómina) para el que desea crear un
     * rol_pago de efectivo.
     *
     * @return una descarga de un archivo Excel.
     */
    public function crear_cash_rol_pago($rolPagoId)
    {
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
    }

    /**
     * La función "imprimir_reporte_general" genera un informe en PHP, en base a la solicitud proporcionada
     * y rolPagoId, y lo exporta en el formato especificado (excel u otro).
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP.
     * @param rolPagoId El parámetro `` es el ID del rolPago para el que desea generar un informe
     * general.
     *
     * @return el resultado del método `imprimir_reporte` del objeto `reporteService`.
     */
    public function imprimir_reporte_general(Request $request, $rolPagoId)
    {
        $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
        $nombre_reporte = 'rol_pagos_general';
        $roles_de_pago = RolPago::where('rol_pago_id', $rolPagoId)->with(['egreso_rol_pago.descuento', 'ingreso_rol_pago.concepto_ingreso_info', 'rolPagoMes', 'egreso_rol_pago'])->get();
        $sumatoria = RolPago::where('rol_pago_id', $rolPagoId)
            ->select(
                DB::raw('SUM(ROUND(decimo_tercero, 2)) as decimo_tercero'),
                DB::raw('SUM(ROUND(decimo_cuarto, 2))  as decimo_cuarto'),
                DB::raw('SUM(ROUND(fondos_reserva, 2))  as fondos_reserva'),
                DB::raw('SUM(ROUND(bonificacion, 2))  as bonificacion'),
                DB::raw('SUM(ROUND(total_ingreso, 2))  as total_ingreso'),
                DB::raw('SUM(ROUND(comisiones, 2))  as comisiones'),
                DB::raw('SUM(ROUND(iess, 2))  as iess'),
                DB::raw('SUM(ROUND(anticipo, 2))  as anticipo'),
                DB::raw('SUM(ROUND(prestamo_quirorafario, 2))  as prestamo_quirorafario'),
                DB::raw('SUM(ROUND(prestamo_hipotecario, 2))  as prestamo_hipotecario'),
                DB::raw('SUM(ROUND(extension_conyugal, 2))  as extension_conyugal'),
                DB::raw('SUM(ROUND(prestamo_empresarial, 2))  as prestamo_empresarial'),
                DB::raw('SUM(ROUND(bono_recurente, 2))  as bono_recurente'),
                DB::raw('SUM(ROUND(total_egreso, 2))  as total_egreso'),
                DB::raw('SUM(ROUND(total, 2)) as total'),
            )
            ->first();
        $results = RolPago::empaquetarListado($roles_de_pago);
        $column_names_ingresos = $this->extract_column_names($results, 'ingresos', 'concepto_ingreso_info', 'nombre');
        $column_names_ingresos =  array_unique($column_names_ingresos['ingresos']);
        $column_names_egresos = $this->extract_column_names($results, 'egresos', 'descuento', 'nombre');
        $column_names_egresos = array_unique($column_names_egresos['egresos']);
        $colum_ingreso_value = $this->colum_values($results,  $column_names_ingresos, 'ingresos', 'concepto_ingreso_info');
        $colum_egreso_value = $this->colum_values($results, $column_names_egresos, 'egresos', 'descuento');
        $rolPago = RolPagoMes::where('id', $rolPagoId)->first();
        $reportes = ['reporte' => $sumatoria, 'rolPago' => $rolPago, 'ingresos' => $this->sumatoria_llaves($colum_ingreso_value), 'egresos' => $this->sumatoria_llaves($colum_egreso_value)];
        $vista = 'recursos-humanos.reporte_general';
        $export_excel = new RolPagoGeneralExport($reportes);
        return $this->reporteService->imprimir_reporte($tipo, 'A4', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
    }
    /**
     * La función "generate_report_data" en PHP genera un informe procesando una serie de roles de pago y
     * extrayendo datos relevantes para su visualización.
     *
     * @param roles_pagos Una serie de datos de pago de roles.
     * @param nombre El parámetro "nombre" es una cadena que representa el nombre del informe.
     *
     * @return una matriz con las siguientes claves:
     */
    private function generate_report_data($roles_pagos, $nombre)
    {
        $es_quincena = RolPagoMes::where('mes', $roles_pagos[0]->mes)->where('es_quincena', '1')->first() != null ? true : false;
        $periodo = $this->obtenerPeriodo($roles_pagos[0]->mes, $es_quincena);
        $creador_rol_pago = Empleado::whereHas('user', function ($query) {
            $query->whereHas('permissions', function ($q) {
                $q->where('name', 'puede.elaborar.rol_pago');
            });
        })->first();
        $results = RolPago::empaquetarListado($roles_pagos);
        $results = collect($results)->map(function ($elemento, $index) {
            $elemento['item'] = $index + 1;
            return $elemento;
        })->all();
        $column_names_egresos = $this->extract_column_names($results, 'egresos', 'descuento', 'abreviatura');
        $column_names_ingresos = $this->extract_column_names($results, 'ingresos', 'concepto_ingreso_info', 'abreviatura');
        $columnas_ingresos =  array_unique($column_names_ingresos['ingresos']);
        $colum_ingreso_value = $this->colum_values($results, $columnas_ingresos, 'ingresos', 'concepto_ingreso_info', true);
        $columnas_egresos = array_unique($column_names_egresos['egresos']);
        $colum_egreso_value = $this->colum_values($results, $columnas_egresos, 'egresos', 'descuento', true);
        $maxColumEgresosValue = count($columnas_egresos);
        $maxColumIngresosValue = count($columnas_ingresos);



        // Calculate the sum of specific columns from the main data array
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
            'colum_ingreso_value' => $colum_ingreso_value,
            'colum_egreso_value' => $colum_egreso_value,
            'columnas_ingresos' => $columnas_ingresos,
            'columnas_egresos' =>  $columnas_egresos,
            'sumatoria' => $sumColumns,
            'nombre' => $nombre,
            'creador_rol_pago' => $creador_rol_pago,
            'sumatoria_ingresos' => $this->calculate_column_sum($results, $maxColumIngresosValue, 'ingresos_cantidad_columna', 'ingresos'),
            'sumatoria_egresos' => $this->sumatoria_llaves($colum_egreso_value),
        ];
    }
    /**
     * La función "sumatoria_keys" calcula la suma de valores de cada clave en una matriz asociativa.
     *
     * @param data El parámetro "datos" es una matriz que contiene pares clave-valor. Cada clave representa
     * una categoría o grupo y el valor correspondiente es una matriz de entradas. Cada entrada tiene un
     * campo "valor", que representa un valor numérico.
     *
     * @return un array asociativo llamado . Esta matriz contiene la suma del campo
     * "valor" para cada clave en la matriz .
     */
    private function sumatoria_llaves($data)
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
     * @param data El parámetro "datos" es una matriz de objetos. Cada objeto representa una fila de
     * datos.
     * @param column_name Una matriz que contiene los nombres de las columnas.
     * @param key1 El parámetro `` se utiliza para acceder a una clave específica en la matriz
     * ``. Probablemente se use para recuperar una submatriz u objeto anidado dentro de cada elemento
     * de la matriz ``.
     * @param key2 El parámetro  se utiliza para acceder a la propiedad "nombre" del elemento en la
     * matriz []. Se usa para determinar el valor de , que luego se usa para agrupar
     * los objetos en el arreglo .
     *
     * @return una matriz llamada .
     */
    private function colum_values($data, $column_name, $key1, $key2, $abreviatura = false)
    {
        // Creamos un arreglo para almacenar los objetos agrupados por descuento_id
        $groupedData = [];
        foreach ($data as $item) {
            // Recorremos el arreglo original y agrupamos los objetos por descuento_id
            foreach ($item[$key1] as $item) {
                $descuentoId = $abreviatura ? $item[$key2]->abreviatura : $item[$key2]->nombre;
                if (!isset($groupedData[$descuentoId])) {
                    $groupedData[$descuentoId] = [];
                }
                foreach ($column_name as $name) {
                    if ($name != $descuentoId) {
                        $groupedData[$name][] = ['id' => $item['id_rol_pago'], 'valor' => 0];
                    }
                    if ($name == $descuentoId) {
                        $groupedData[$descuentoId][] = ['id' => $item['id_rol_pago'], 'valor' => $item['monto']];
                    }
                }
            }
        }
        return $this->eliminar_duplicados($groupedData, 'id');
    }
    public function eliminar_duplicados(array $datos, $llave_buscar): array
    {
        $array_sin_duplicados = [];
        foreach ($datos as $clave => $objeto) {
            $array_sin_duplicados[$clave] = [];
            $serial_ant = null;
            if ($this->verificar_valor_cero_primera_posicion($objeto)) {
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
    function verificar_valor_cero_primera_posicion($array)
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
     * @param results Una serie de resultados de una consulta de base de datos. Cada elemento de la matriz
     * representa una fila de datos.
     * @param key1 El parámetro `key1` es una cadena que representa la clave utilizada para acceder a una
     * matriz dentro de la matriz ``.
     * @param key2 El parámetro `key2` se utiliza para acceder a una clave específica dentro de la matriz
     * ``.
     * @param columnName El parámetro `columnName` es una cadena que representa el nombre de la columna que
     * desea extraer de las matrices anidadas en la matriz ``.
     *
     * @return una serie de nombres de columnas. La matriz tiene dos claves, 'egresos' e 'ingresos', cada
     * una de las cuales contiene una matriz de nombres de columnas.
     */
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
    /**
     * La función calcula la suma de valores en una columna específica de una matriz, según ciertas
     * condiciones.
     *
     * @param data Una matriz de datos que contiene múltiples elementos.
     * @param maximo La variable `` representa el número máximo de iteraciones o elementos en un
     * bucle o matriz. Se utiliza en el bucle `for` para iterar un número específico de veces.
     * @param key_cantidad El parámetro `` se utiliza como clave para acceder al valor de
     * cantidad en la matriz ``. Se utiliza para comprobar si la cantidad es mayor que 0 o igual a
     * 0 para poder realizar ciertos cálculos.
     * @param key1 El parámetro `` se utiliza como clave para acceder a una matriz anidada dentro
     * de la matriz ``. Se utiliza en el bucle foreach para iterar sobre los elementos de la matriz
     * anidada.
     *
     * @return una matriz que contiene la suma de valores para cada clave única en los datos de entrada.
     */
    private function calculate_column_sum($data, $maximo, $key_cantidad, $key1)
    {
        $totalMontoIngresos = array_map(
            function ($item) use ($maximo, $key_cantidad, $key1, $data) {
                $monto = array();
                if ($item[$key_cantidad] > 0) {
                    foreach ($item[$key1] as $subitem) {
                        $monto[$subitem['descuento_id']] = $subitem['monto'];
                    }
                }
                if ($item[$key_cantidad] == 0) {
                    for ($j = 0; $j < $maximo; $j++) {
                        //   $monto[$j] = 0;
                    }
                }
                return $monto;
            },
            $data
        );
        $resultados = [];
        foreach ($totalMontoIngresos as $elemento) {
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
     * @param RolPagoMes rol El parámetro `` es una instancia de la clase `RolPagoMes`. Representa
     * un mes de nómina y contiene información como el mes y si es una nómina quincenal o no.
     *
     * @return La función no devuelve nada. Está insertando datos en la tabla de la base de datos
     * "RolPago" usando el método `insert()`.
     */
    private function tabla_roles(RolPagoMes $rol)
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
                ->orderBy('apellidos', 'asc')->get();
            $this->nominaService->setMes($mes);
            Log::channel('testing')->info('Log', ['luego de setMes', $mes]);
            $this->prestamoService->setMes($mes);
            Log::channel('testing')->info('Log', ['luego de setMes en prestamoService', $mes]);
            $roles_pago = [];
            foreach ($empleados_activos as $empleado) {
                $this->nominaService->setEmpleado($empleado->id);
                $this->prestamoService->setEmpleado($empleado->id);
                Log::channel('testing')->info('Log', ['luego de setEmpleado', $mes]);
                // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
                $diasTranscurridos = $rol->es_quincena ? 15 : 30;
                $dias = $this->nominaService->calcularDias($diasTranscurridos);
                Log::channel('testing')->info('Log', ['luego de calcular dias transcurridos', $mes]);
                // $salario = $this->nominaService->calcularSalario();
                $salario = $empleado->salario;
                $sueldo =  $this->nominaService->calcularSueldo($dias, $rol->es_quincena);
                $decimo_tercero =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva =  $rol->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess =  $rol->es_quincena ? 0 : $this->nominaService->calcularAporteIESS();
                $anticipo =  $rol->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal =  $rol->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $empleado->supa != null ? $empleado->supa : 0;
                $supa =  $rol->es_quincena ? 0 : $valor_supa;
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
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['error', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar rol pago por empleado' => [$ex->getMessage()],
            ]);
        }
    }
    /**
     * La función "actualizar_tabla_roles" calcula y actualiza la tabla roles_pago de los empleados en
     * función de diversos cálculos de salarios y deducciones.
     *
     * @param RolPagoMes rol El parámetro `` es una instancia de la clase `RolPagoMes`.
     */
    public function agregar_nuevos_empleados(RolPagoMes $rol)
    {
        try {
            // $rol_pago = RolPagoMes::find($rolPagoId);
            $mes_rol = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
            $final_mes = new Carbon($mes_rol);
            $ultimo_dia_mes = $final_mes->endOfMonth();
            $empleadosSinRolPago = Empleado::where('id', '>', 2)
                ->where('estado', true)
                ->where('esta_en_rol_pago', true)
                ->where('fecha_vinculacion', '<', $ultimo_dia_mes)
                ->where('salario', '!=', 0)
                ->whereDoesntHave('rolesPago')
                ->get();
            // Log::channel('testing')->info('Log', ['mes rol', $rol, $mes_rol, $final_mes, $ultimo_dia_mes]);
            // Log::channel('testing')->info('Log', ['empleados sin rol', $empleadosSinRolPago]);
            $mes = Carbon::createFromFormat('m-Y', $rol->mes)->format('Y-m');
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago = [];
            foreach ($empleadosSinRolPago as $empleado) {
                $this->nominaService->setEmpleado($empleado->id);
                $this->prestamoService->setEmpleado($empleado->id);
                $diasTranscurridos = $rol->es_quincena ? 15 : 30;
                $dias = $this->nominaService->calcularDias($diasTranscurridos);
                $salario = $this->nominaService->calcularSalario();
                // Calcular el número total de días de permiso dentro del mes seleccionado usando funciones de agregación
                $sueldo =  $this->nominaService->calcularSueldo($dias, $rol->es_quincena);
                $decimo_tercero =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto =  $rol->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva =  $rol->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva;
                $iess =  $rol->es_quincena ? 0 : $this->nominaService->calcularAporteIESS($dias);
                $anticipo =  $rol->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial =  $rol->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal =  $rol->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $empleado->supa != null ? $empleado->supa : 0;
                $supa =  $rol->es_quincena ? 0 : $valor_supa;
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
        $totalSubrol_pagosNoFinalizadas = $rol_pago->rolPago()->whereIn('estado', [RolPago::EJECUTANDO, RolPago::REALIZADO])->count();
        $estan_finalizadas = $totalSubrol_pagosNoFinalizadas == 0;
        return response()->json(compact('estan_finalizadas'));
    }
    public function actualizar_tabla_roles(RolPagoMes $rol_mes)
    {
        try {
            $mes = Carbon::createFromFormat('m-Y', $rol_mes->mes)->format('Y-m');
            $this->nominaService->setMes($mes);
            $this->prestamoService->setMes($mes);
            $roles_pago =  RolPago::where('rol_pago_id', $rol_mes->id)->get();
            foreach ($roles_pago as $key => $rol_pago) {
                $this->nominaService->setEmpleado($rol_pago->empleado_id);
                $this->prestamoService->setEmpleado($rol_pago->empleado_id);
                $this->nominaService->setRolPago($rol_mes);
                $dias = $rol_pago->dias;
                $salario = $this->nominaService->calcularSalario();
                $sueldo =  $this->nominaService->calcularSueldo($dias, $rol_mes->es_quincena);
                if ($rol_mes->es_quincena) {
                    $quincena = $this->nominaService->calcularSueldo($dias, $rol_mes->es_quincena);
                    if ($quincena !== $rol_pago->sueldo) {
                        $sueldo =  $rol_pago->sueldo;
                    }
                }
                $decimo_tercero =  $rol_mes->es_quincena ? 0 : $this->nominaService->calcularDecimo(3, $dias);
                $decimo_cuarto =  $rol_mes->es_quincena ? 0 : $this->nominaService->calcularDecimo(4, $dias);
                $fondos_reserva =  $rol_mes->es_quincena ? 0 : $this->nominaService->calcularFondosReserva($dias);
                $ingresos = $rol_mes->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $this->nominaService->obtener_total_ingresos();
                $iess =  $rol_mes->es_quincena ? 0 : $this->nominaService->calcularAporteIESS($dias);
                $anticipo =  $rol_mes->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario =  $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosQuirografarios();
                $prestamo_hipotecario =  $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosHipotecarios();
                $prestamo_empresarial =  $rol_mes->es_quincena ? 0 : $this->prestamoService->prestamosEmpresariales();
                $extension_conyugal =  $rol_mes->es_quincena ? 0 : $this->nominaService->extensionesCoberturaSalud();
                $valor_supa = $this->nominaService->getEmpleado()->supa != null ? $this->nominaService->getEmpleado()->supa : 0;
                $supa =  $rol_mes->es_quincena ? 0 : $valor_supa;
                $egreso = $rol_mes->es_quincena ? 0 : ($iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $this->nominaService->obtener_total_descuentos_multas() + $supa);
                $total = abs($ingresos) - $egreso;
                $rol_pago_mes_empleado =  RolPago::where('rol_pago_id', $rol_mes->id)->where('empleado_id', $rol_pago->empleado_id)->first();
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
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['error', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'Error al refrescar rol pago por empleado' => [$ex->getMessage()],
            ]);
        }
    }
    public function refrescar_rol_pago($rolPagoId)
    {
        $rol_pago = RolPagoMes::find($rolPagoId);
        // $this->agregar_nuevos_empleados($rol_pago);
        $this->actualizar_tabla_roles($rol_pago);
        $mensaje = "Rol de pago Actualizado Exitosamente";
        return response()->json(compact('mensaje'));
    }
    public function FinalizarRolPago(Request $request)
    {
        $rol_pago = RolPagoMes::find($request['rol_pago_id']);
        $rol_pago->finalizado = true;
        $rol_pago->save();
        $modelo = new RolPagoMesResource($rol_pago);
        if (!$rol_pago->es_quincena) {
            $mes = $rol_pago->mes;
            // Divide la fecha en mes y año
            list($month, $year) = explode('-', $mes);
            // Crea un objeto Carbon para representar la fecha en Laravel
            $date = \Carbon\Carbon::createFromDate($year, $month, 1);
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
        $periodo =  $es_quincena ? 'DEL 1 AL  15 ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat(' F Y') : 'DEL 1 AL ' . Carbon::createFromFormat('m-Y', $mes)->locale('es')->translatedFormat('t F Y');
        $periodo = strtoupper($periodo);
        return "PERIODO: $periodo";
    }
}
