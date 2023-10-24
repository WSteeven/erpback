<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Exports\CashAcreditacionSaldoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\AcreditacionSemanaRequest;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionResource;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionSemanaResource;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\AcreditacionSemana;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AcreditacionSemanaController extends Controller
{
    private $entidad = 'Acreditacion Semanal';
    public function __construct()
    {
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
    public function acreditacion_saldo_semana($id)
    {   $date = Carbon::now();
        $acreditaciones = [];
        $acreditacion_semana = AcreditacionSemana::where('id', $id)->first();
        $acreditacion_semana->acreditar = true;
        $acreditacion_semana->save();
        $valores_acreditar = ValorAcreditar::where('acreditacion_semana_id', $id)->with('acreditacion_semanal')->get();
        foreach ($valores_acreditar as $key => $acreditacion) {
            $acreditaciones[] = [
                'id_tipo_fondo' => 1,
                'id_tipo_saldo' => 1,
                'id_saldo' => '46848564',
                'id_usuario' => $acreditacion->empleado_id,
                'fecha' =>  $date->format('Y-m-d'),
                'descripcion_acreditacion' => $acreditacion->acreditacion_semanal->semana,
                'monto' => $acreditacion->monto_modificado,
                'id_estado'=>1,
                'created_at'=> $date,
                'updated_at'=> $date
            ];
        }
        Acreditaciones::insert($acreditaciones);
    }
    public function crear_cash_acreditacion_saldo($id)
    {
        $nombre_reporte = 'cash_acreditacion_saldo';
        $roles_pagos = ValorAcreditar::with(['acreditacion_semanal', 'umbral'])
            ->where('acreditacion_semana_id', $id)
            ->get();
            $results = ValorAcreditar::empaquetarCash($roles_pagos);
            $results = collect($results)->map(function ($elemento, $index) {
                $elemento['item'] = $index + 1;
                return $elemento;
            })->all();
            $reporte = ['reporte' => $results];
         $export_excel = new CashAcreditacionSaldoExport($reporte);
         return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }
    public function cortar_saldo()
    {
        try {
            $fechaActual = Carbon::now();
            $numeroSemana = $fechaActual->weekOfYear;
            $nombreSemana = "Fondo Rotativo Semana # " . $numeroSemana;
            $semana = AcreditacionSemana::where('semana', $nombreSemana)->get()->count();
            if ($semana > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Ya se ha acreditado saldos en esta semana '],
                ]);
            }
            DB::beginTransaction();
            $acreditacionsemana = new AcreditacionSemana();
            $acreditacionsemana->semana = $nombreSemana;
            $acreditacionsemana->save();
            $modelo = new AcreditacionSemanaResource($acreditacionsemana);
            $mensaje = 'Se ha generado  Acreditacion de la semana exitosamente';
            $saldosPorUsuario = DB::table('saldo_grupo')
                ->select('saldo_grupo.id_usuario', 'saldo_grupo.saldo_actual', 'fr_umbral_fondos_rotativos.valor_minimo')
                ->join('fr_umbral_fondos_rotativos', 'saldo_grupo.id_usuario', '=', 'fr_umbral_fondos_rotativos.empleado_id')
                ->groupBy('saldo_grupo.id_usuario')
                ->get();
            $acreditaciones = [];
            foreach ($saldosPorUsuario as $key => $empleado) {
                $valorRecibir = $empleado->valor_minimo - $empleado->saldo_actual;
                $numeroRedondeado = ceil($valorRecibir / 10) * 10;
                $acreditaciones[] = [
                    'empleado_id' => $empleado->id_usuario,
                    'acreditacion_semana_id' => $acreditacionsemana->id,
                    'monto_generado' => $numeroRedondeado,
                    'monto_modificado' => $numeroRedondeado,
                ];
            }
            ValorAcreditar::insert($acreditaciones);
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
}
