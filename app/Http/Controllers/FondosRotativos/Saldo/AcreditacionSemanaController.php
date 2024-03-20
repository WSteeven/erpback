<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Exports\CashAcreditacionSaldoExport;
use App\Exports\FondosRotativos\Saldos\AcreditacionSemanalExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\AcreditacionSemanaRequest;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionResource;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionSemanaResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\AcreditacionSemana;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\SaldosFondosRotativos;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\AcreditacionSemanalService;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\FondosRotativos\SaldoService;
use Src\Shared\Utils;

class AcreditacionSemanaController extends Controller
{
    private $entidad = 'Acreditacion Semanal';
    private $reporteService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();

        $this->middleware('can:puede.ver.acreditacion_semana')->only('index', 'show');
        $this->middleware('can:puede.crear.acreditacion_semana')->only('store');
    }
    /**
     * La función de índice recupera y filtra datos del modelo AcreditacionSemana y los devuelve como una
     * respuesta JSON.
     *
     * @param Request request El parámetro `Request ` en la función `index` es una instancia de la
     * clase Illuminate\Http\Request en Laravel. Representa la solicitud HTTP que se realiza al servidor.
     *
     * @return Un array que contiene los resultados del modelo AcreditacionSemana después de aplicar los
     * métodos ignoreRequest y filter, que luego se convierten a formato JSON y se devuelven como
     * respuesta.
     */
    public function index(Request $request)
    {
        $results = [];
        $results = AcreditacionSemana::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, AcreditacionSemana $descuentos_generales)
    {
        return response()->json(compact('descuentos_generales'));
    }
    /**
     * La función `store` en PHP maneja la creación de un nuevo registro en la tabla de la base de datos
     * `AcreditacionSemana` y devuelve una respuesta JSON con un mensaje de éxito o un mensaje de error si
     * ocurre una excepción.
     *
     * @param AcreditacionSemanaRequest request La función `store` que proporcionó se utiliza para
     * almacenar un nuevo registro `AcreditacionSemana` en la base de datos en función de los datos
     * proporcionados en la solicitud `AcreditacionSemanaRequest`. Aquí hay un desglose del código:
     *
     * @return La función `store` está devolviendo una respuesta JSON con los datos `mensaje` y `modelo`.
     * Si la operación es exitosa devolverá un mensaje y el modelo `AcreditacionSemanaResource` recién
     * creado. Si ocurre una excepción durante el proceso, devolverá una respuesta JSON con un mensaje de
     * error indicando que ocurrió un error al insertar el registro.
     */
    public function store(AcreditacionSemanaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $acreditacionsemana = AcreditacionSemana::create($datos);
            $modelo = new AcreditacionSemanaResource($acreditacionsemana);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * La función `actualizar` en este fragmento de código PHP actualiza un registro en la base de datos
     * utilizando los datos de la solicitud validada y devuelve una respuesta JSON con un mensaje y el
     * modelo actualizado.
     *
     * @param AcreditacionSemanaRequest request AcreditacionSemanaRequest : este parámetro es una
     * instancia de la clase AcreditacionSemanaRequest, que se utiliza para validar los datos de la
     * solicitud entrante antes de procesarlos más.
     * @param AcreditacionSemana acreditacionsemana El método `update` que proporcionó se utiliza para
     * actualizar un modelo `AcreditacionSemana` existente con los datos proporcionados en la solicitud
     * `AcreditacionSemanaRequest`. Aquí hay un desglose del proceso:
     *
     * @return El método `update` devuelve una respuesta JSON con los datos `mensaje` y `modelo`. La
     * variable `mensaje` contiene un mensaje obtenido usando el método `Utils::obtenerMensaje` para la
     * acción 'almacenar' sobre la entidad. La variable `modelo` contiene el recurso `AcreditacionSemana`
     * actualizado después de la operación de actualización.
     */
    public function update(AcreditacionSemanaRequest $request, AcreditacionSemana $acreditacionsemana)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $acreditacionsemana->update($datos);
            $modelo = new AcreditacionSemanaResource($acreditacionsemana->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * La función destruye una instancia específica de AcreditacionSemana y devuelve una respuesta JSON que
     * contiene la instancia eliminada.
     *
     * @param Request request El parámetro `` en la función `destroy` es una instancia de la clase
     * `Illuminate\Http\Request`. Representa la solicitud HTTP que se realiza al servidor. Este parámetro
     * le permite acceder a los datos enviados por el cliente, como entradas de formulario o parámetros de
     * consulta.
     * @param AcreditacionSemana acreditacionsemana El parámetro `acreditacionsemana` en la función
     * `destroy` es una instancia del modelo `AcreditacionSemana`. En esta función se utiliza para eliminar
     * el registro específico `AcreditacionSemana` de la base de datos. Después de eliminar el registro,
     * una respuesta JSON
     *
     * @return La función `destroy` elimina el registro `` y luego devuelve una
     * respuesta JSON que contiene el objeto `acreditacionsemana` eliminado.
     */
    public function destroy(Request $request, AcreditacionSemana $acreditacionsemana)
    {
        $acreditacionsemana->delete();
        return response()->json(compact('acreditacionsemana'));
    }
    /**
     * La función `acreditacionSaldoSemana` acredita saldos de la semana
     * registros en función de ciertas condiciones.
     *
     * @param int id El código que proporcionaste parece ser una función en PHP que se encarga de acreditar
     * un saldo para una semana determinada en función del ID proporcionado.
     */
    public function acreditacionSaldoSemana(int $id)
    {
        $date = Carbon::now();
        $acreditaciones = [];
        $acreditacion_semana = AcreditacionSemana::where('id', $id)->first();
        $acreditacion_semana->acreditar = true;
        $acreditacion_semana->save();
        $valores_acreditar = ValorAcreditar::where('acreditacion_semana_id', $id)->where('estado', 1)->where('empleado_id', '!=', 0)->with('acreditacion_semanal')->get();
        foreach ($valores_acreditar as $key => $acreditacion) {
            Acreditaciones::create(array(
                'id_tipo_fondo' => 1,
                'id_tipo_saldo' => 1,
                'id_saldo' => '',
                'id_usuario' => $acreditacion->empleado_id,
                'fecha' =>  $date->format('Y-m-d'),
                'descripcion_acreditacion' => $acreditacion->acreditacion_semanal->semana,
                'monto' => $acreditacion->monto_modificado,
                'id_estado' => 1,
                'created_at' => $date,
                'updated_at' => $date
            ));
        }
    }
    /**
     * La función crea un informe en formato Excel para el saldo de acreditación de efectivo en función de
     * criterios específicos.
     *
     * @param int id de acreditacion que genera un informe y lo
     * exporta a un archivo de Excel. La función `crearCashAcreditacionSaldo` toma un parámetro entero
     * `` que se utiliza para filtrar los datos.
     *
     * @return Se devuelve para su descarga un archivo Excel llamado 'cash_acreditacion_saldo.xlsx' que
     * contiene datos relacionados con los saldos de acreditación de efectivo para una semana específica
     * identificada por el parámetro .
     */
    public function crearCashAcreditacionSaldo(int $id)
    {
        $nombre_reporte = 'cash_acreditacion_saldo';
        $valores_acreditar = ValorAcreditar::with(['acreditacion_semanal', 'umbral'])
            ->where('acreditacion_semana_id', $id)
            ->where('estado', 1)
            ->get();
        $results = ValorAcreditar::empaquetarCash($valores_acreditar);
        $results = collect($results)->map(function ($elemento, $index) {
            $elemento['item'] = $index + 1;
            return $elemento;
        })->all();
        $reporte = ['reporte' => $results];
        $export_excel = new CashAcreditacionSaldoExport($reporte);
        return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }
    public function reporteAcreditacionSemanal(Request $request, $id)
    {
        try {
            $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
            $nombre_reporte = 'acreditaciones';
            // Fetch data with relationships
            $valores_acreditar = ValorAcreditar::with(['acreditacion_semanal', 'umbral'])
                ->where('acreditacion_semana_id', $id)
                ->where('estado', 1)
                ->get();
            $suma = str_replace(".", "", number_format($valores_acreditar->sum('monto_modificado'), 2, ',', '.'));
            $titulo = AcreditacionSemana::select('semana')->where('id', $id)->first()->semana;
            $vista = 'exports.reportes.acreditacion_semanal';
            $reportes = ValorAcreditar::empaquetar($valores_acreditar);
            $reportes = compact('reportes', 'titulo', 'suma');
            $export_excel = new AcreditacionSemanalExport($reportes);
            $orientacion = 'portail';
            $tipo_pagina =  'A4';
            return $this->reporteService->imprimir_reporte($tipo,  $tipo_pagina, $orientacion, $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }
    /**
     * La función "cortarSaldo" en PHP genera un registro de crédito semanal, asigna montos de crédito en
     * función de umbrales y maneja excepciones durante el proceso.
     *
     * @return La función `cortarSaldo` devuelve una respuesta JSON que contiene las variables `mensaje` y
     * `modelo` si el proceso es exitoso. Si ocurre una excepción, devolverá una respuesta JSON con un
     * mensaje de error.
     */
    public function cortarSaldo()
    {
        try {
            $fecha_actual = Carbon::now();
            $numero_semana = $fecha_actual->weekOfYear;
            $nombre_semana = "Fondo Rotativo Semana # " . $numero_semana;
            $semana = AcreditacionSemana::where('semana', $nombre_semana)->get()->count();
            if ($semana > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Ya se ha acreditado saldos en esta semana '],
                ]);
            }
            DB::beginTransaction();
            $acreditacion_semana = new AcreditacionSemana();
            $acreditacion_semana->semana = $nombre_semana;
            $acreditacion_semana->save();
            $modelo = new AcreditacionSemanaResource($acreditacion_semana);
            $mensaje = 'Se ha generado  Acreditacion de la semana exitosamente';
            $umbrales = UmbralFondosRotativos::where('empleado_id', '!=', 0)->get();
            $acreditaciones = AcreditacionSemanalService::asignarAcreditaciones($umbrales, $acreditacion_semana);
            $acreditacion_semana->valorAcreditar()->createMany($acreditaciones);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * La función `refrescarAcreditacionesSemana` actualiza las acreditaciones semanales para un ID de
     * semana determinado y devuelve un mensaje de éxito en formato JSON.
     *
     * @param acreditacion_semana_id La función `refrescarAcreditacionesSemana` toma como parámetro un
     * ``. Este ID se utiliza para encontrar un registro específico de
     * "AcreditacionSemana" de la base de datos. La función luego recupera todos los registros
     * `ValorAcreditar` asociados con
     *
     * @return La función `refrescarAcreditacionesSemana` está devolviendo una respuesta JSON con un
     * mensaje indicando que las acreditaciones se han actualizado correctamente. El mensaje
     * "Acreditaciones Actualizadas Exitosamente" se devuelve en formato JSON.
     */
    public function refrescarAcreditacionesSemana($acreditacion_semana_id)
    {
        $acreditacion_semana = AcreditacionSemana::find($acreditacion_semana_id);
        $acreditaciones_semanales = ValorAcreditar::where('acreditacion_semana_id', $acreditacion_semana_id)->get();
        AcreditacionSemanalService::refrescarAcreditacion($acreditaciones_semanales, $acreditacion_semana);
        $mensaje = "Acreditaciones Actualizadas Exitosamente";
        return response()->json(compact('mensaje'));
    }
}
