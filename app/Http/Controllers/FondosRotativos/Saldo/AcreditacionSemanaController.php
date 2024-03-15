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
    public function destroy(Request $request, AcreditacionSemana $acreditacionsemana)
    {
        $acreditacionsemana->delete();
        return response()->json(compact('acreditacionsemana'));
    }
    public function acreditacionSaldoSemana($id)
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
            /*  $acreditaciones[] = [
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
            ];*/
        }
        //  $acreditacion_semana->valor_acreditar()->createMany($acreditaciones);

    }
    public function crearCashAcreditacionSaldo($id)
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

    public function refrescarAcreditacionesSemana($acreditacion_semana_id)
    {
        $acreditacion_semana = AcreditacionSemana::find($acreditacion_semana_id);
        $acreditaciones_semanales = ValorAcreditar::where('acreditacion_semana_id', $acreditacion_semana_id)->get();
        AcreditacionSemanalService::refrescarAcreditacion($acreditaciones_semanales, $acreditacion_semana);
        $mensaje = "Acreditaciones Actualizadas Exitosamente";
        return response()->json(compact('mensaje'));
    }
}
