<?php

namespace App\Http\Controllers\RecursosHumanos\Alimentacion;

use App\Exports\RecursosHumanos\Alimentacion\AlimentacionExport;
use App\Exports\RecursosHumanos\Alimentacion\CashAlimentacionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Alimentacion\AlimentacionRequest;
use App\Http\Resources\RecursosHumanos\Alimentacion\AlimentacionResource;
use App\Models\RecursosHumanos\Alimentacion\Alimentacion;
use App\Models\RecursosHumanos\Alimentacion\AsignarAlimentacion;
use App\Models\RecursosHumanos\Alimentacion\DetalleAlimentacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\FondosRotativos\ReportePdfExcelService;

class AlimentacionController extends Controller
{
    private $entidad = 'Alimentacion';
    private $reporteService;

    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();

        $this->middleware('can:puede.ver.alimentaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.alimentaciones')->only('store');
        $this->middleware('can:puede.editar.alimentaciones')->only('update');
        $this->middleware('can:puede.eliminar.alimentaciones')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = Alimentacion::filter()->get();
        $results = AlimentacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(AlimentacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $alimentacion = Alimentacion::create($datos);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new AlimentacionResource($alimentacion);
            $this->realizarCorte($modelo);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    /**
     * Consultar
     */
    public function show(Alimentacion $alimentacion)
    {
        $modelo = new AlimentacionResource($alimentacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(AlimentacionRequest $request, Alimentacion $alimentacion)
    {
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            //Creación de la alimentacion
            $alimentacion->update($datos);
            //Respuesta
            $modelo = new AlimentacionResource($alimentacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de alimentacion:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    private function realizarCorte(AlimentacionResource $alimentacion)
    {
        $asignaciones_detalle_alimentacion = AsignarAlimentacion::get();
        foreach ($asignaciones_detalle_alimentacion as $asignacion_detalle_alimentacion) {
            DetalleAlimentacion::create([
                'empleado_id' => $asignacion_detalle_alimentacion['empleado_id'],
                'valor_asignado' => $asignacion_detalle_alimentacion['valor_minimo'],
                'fecha_corte' => Carbon::now()->format('Y-m-d'),
                'alimentacion_id' => $alimentacion['id']
            ]);
        }
    }
    public function destroy(Request $request, Alimentacion $alimentacion)
    {
        $alimentacion->delete();
        return response()->json(compact('alimentacion'));
    }
    public function crear_cash_alimentacion($alimentacion_id)
    {
        $nombre_reporte = 'alimentacions_general';
        $alimentaciones = DetalleAlimentacion::with(['empleado', 'alimentacion'])
            ->where('alimentacion_id', $alimentacion_id)
            ->get();
        $results = DetalleAlimentacion::empaquetarCash($alimentaciones);
        $results = collect($results)->map(function ($elemento, $index) {
            $elemento['item'] = $index + 1;
            return $elemento;
        })->all();
        $reporte = ['reporte' => $results];
        $export_excel = new CashAlimentacionExport($reporte);
        return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }
    public function reporte_alimentacion(Request $request,$id){
        try {
            $tipo = $request->tipo == 'xlsx' ? 'excel' : $request->tipo;
            $nombre_reporte = 'acreditaciones';
            $valores_acreditar = DetalleAlimentacion::with(['empleado', 'alimentacion'])
            ->where('alimentacion_id', $id)
            ->get();
            $suma= str_replace(".", "", number_format($valores_acreditar->sum('valor_asignado'), 2, ',', '.'));
            $titulo= 'reporte de alimentacion';
            $vista = 'recursos-humanos.reporte_general_alimentacion' ;
            $reportes = DetalleAlimentacion::empaquetar($valores_acreditar);
            $reportes =compact('reportes','titulo','suma');
            $export_excel = new AlimentacionExport($reportes);
            $orientacion = 'portail';
            $tipo_pagina =  'A4' ;
            return $this->reporteService->imprimirReporte($tipo,  $tipo_pagina, $orientacion, $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }
    public function finalizarAsignacionAlimentacion(Request $request)
    {
        $alimentacion = Alimentacion::find($request['id']);
        $alimentacion->finalizado = true;
        $alimentacion->save();
        $modelo = new  AlimentacionResource($alimentacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }
}
