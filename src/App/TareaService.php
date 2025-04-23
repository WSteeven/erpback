<?php

namespace Src\App;

use App\Exports\Tareas\ReporteMaterialExport;
use App\Exports\Tareas\ReporteMaterialLibroExport;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class TareaService
{
    public function transferirMaterialTareaAStockEmpleados(Tarea $tarea)
    {
        $materialesTarea = MaterialEmpleadoTarea::where('tarea_id', $tarea->id)->get();

        foreach ($materialesTarea as $material) {
            $this->sumarAgregarMaterialEmpleado($material->detalle_producto_id, $material->empleado_id, $material->cantidad_stock, $material->cliente_id);
            $this->restarMaterialEmpleadoTarea($material);
        }
    }

    private function restarMaterialEmpleadoTarea(MaterialEmpleadoTarea $material)
    {
        $material->devuelto = $material->cantidad_stock;
        $material->cantidad_stock = 0;
        $material->save();
    }

    private function sumarAgregarMaterialEmpleado(int $detalle_producto_id, int $empleado_id, int $cantidad_sumar, $cliente_id)
    {
        $material = MaterialEmpleado::where('detalle_producto_id', $detalle_producto_id)->where('empleado_id', $empleado_id)->where('cliente_id', $cliente_id)->first();

        if ($material) {
            $material->cantidad_stock += $cantidad_sumar;
            $material->despachado += $cantidad_sumar;
            $material->cliente_id = $cliente_id;
            $material->save();
        } else {
            MaterialEmpleado::create([
                'cantidad_stock' => $cantidad_sumar,
                'despachado' => $cantidad_sumar,
                'empleado_id' => $empleado_id,
                'detalle_producto_id' => $detalle_producto_id,
                'cliente_id' => $cliente_id,
            ]);
        }
    }

    public function obtenerTareasAsignadasEmpleado(int $empleado_id)
    {
        $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->groupBy('tarea_id')->pluck('tarea_id'); // ->disponible()
        $ignoreRequest = ['activas_empleado', 'empleado_id', 'formulario', 'campos'];

        /* if (request('sin_etapa')) {
            Tarea::whereIn('id', $tareas_ids)->estaActiva()->sinEtapa()->ignoreRequest([...$ignoreRequest, 'etapa_id'])->filter()->get();
        } */
        return Tarea::whereIn('id', $tareas_ids)->estaActiva()->ignoreRequest($ignoreRequest)->filter()->orderBy('id', 'desc')->get();
    }

    public function obtenerTareasAsignadasEmpleadoLuegoFinalizar(int $empleado_id)
    {
        $empleado = Empleado::find(request('empleado_id'));
        $grupo_id = $empleado->grupo_id;
        if ($grupo_id) {
            $tareas_ids = Subtarea::where(function ($q) use ($empleado_id, $grupo_id) {
                $q->where('empleado_id', $empleado_id)->orwhere('grupo_id', $grupo_id)->orWhere('empleados_designados', 'LIKE', '%' . $empleado_id . '%');
            })->groupBy('tarea_id')->pluck('tarea_id');
        } else {
            $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->orWhere('empleados_designados', 'LIKE', '%' . $empleado_id . '%')->groupBy('tarea_id')->pluck('tarea_id');
        }
        $ignoreRequest = ['activas_empleado', 'empleado_id', 'campos', 'formulario'];
        $results = Tarea::whereIn('id', $tareas_ids)->estaActiva()->orWhere(function ($query) use ($tareas_ids) {
            $query->whereIn('id', $tareas_ids)->where('finalizado', true)->disponibleUnaHoraFinalizar();
        })->ignoreRequest($ignoreRequest)->filter()->orderBy('id', 'desc')->get();

        return response()->json(compact('results'));
    }
    public function obtenerTareasAsignadasGrupoLuegoFinalizar(int $grupo_id)
    {
        $tareas_ids = Subtarea::where('grupo_id', $grupo_id)->groupBy('tarea_id')->pluck('tarea_id');
        $ignoreRequest = ['activas_empleado', 'empleado_id', 'campos', 'formulario'];

        return Tarea::whereIn('id', $tareas_ids)->estaActiva()->orWhere(function ($query) use ($tareas_ids) {
            $query->whereIn('id', $tareas_ids)->where('finalizado', true)->disponibleUnaHoraFinalizar();
        })->ignoreRequest($ignoreRequest)->filter()->orderBy('id', 'desc')->get();
    }

    public function puedeCrearMasTareas()
    {
        if (is_null(request('proyecto')) &&  is_null(request('etapa'))) return true;
        $total_tareas = Tarea::where('proyecto_id', request('proyecto'))->where('etapa_id', request('etapa'))->count();
        return $total_tareas === 0;
    }

    /**
     * Material de empleado ocupado
     */
    public function descargarReporteMateriales()
    {
        request()->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
        ]);

        $fecha_inicio = Carbon::parse(request('fecha_inicio'))->startOfDay();
        $fecha_fin = Carbon::parse(request('fecha_fin'))->endOfDay();

        $materialService = new MaterialesService();
        $reporte = $materialService->obtenerMaterialesEmpleadoIntervalo(request('empleado_id'), $fecha_inicio, $fecha_fin);
        $reporte = $this->quitarTareas($reporte);
        $noUsados = $materialService->obtenerMaterialesEmpleadoIntervaloNoOcupados(request('empleado_id'), $fecha_inicio);
        $seguimientoStock = $materialService->obtenerSeguimientoMaterialesEmpleadoResumen(request('empleado_id'), $fecha_inicio, $fecha_fin);

        $idsMaterialesEmpleadoOcupados = $reporte->pluck('auditable_id');

        $noUsadosOrdenado = $this->ordenarDescUnicos($noUsados);

        $noUsados = $noUsadosOrdenado->filter(function ($item) use ($idsMaterialesEmpleadoOcupados) {
            return !$idsMaterialesEmpleadoOcupados->contains($item['auditable_id']);
        });

        // $seguimientoStock = $this->mapearSeguimientoStock($seguimientoStock);
        Log::channel('testing')->info($seguimientoStock);
//        $stock_en_fecha_establecida = $materialService->obtenerMaterialesStockEnFechaEstablecida(request('empleado_id'), $fecha_inicio);

        $export = new ReporteMaterialLibroExport($reporte, $noUsados, $seguimientoStock); //, $stock_en_fecha_establecida);
        return Excel::download($export, 'reporte_materiales.xlsx');
    }

    /**
     * Ordena las auditorias de manera descendente por su campo fecha_hora y quita los duplicados
     * dejando al más reciente únicamente.
     */
    private function ordenarDescUnicos($results)
    {
        return $results->groupBy('auditable_id')->map(function ($item) {
            return $item->sortByDesc('fecha_hora')->first();
        })->values();
    }

    private function quitarTareas($usados)
    {
        return $usados->filter(function ($item) {
            return !str_contains($item['transaccion'], 'TAREA') && !str_contains($item['movimiento'], 'actualizar-materiales-empleados');
        });
    }

    /* private function mapearSeguimientoStock($seguimientoStock)
    {
        return $seguimientoStock->map(fn($seguimiento) => [
            'id' => $seguimiento->id,
            'cantidad_utilizada' => $seguimiento->cantidad_utilizada,
            'tarea' => $seguimiento->subtarea->tarea->codigo_tarea,
            'titulo_tarea' => $seguimiento->subtarea->tarea->titulo,
            'detalle_producto' => $seguimiento->detalleProducto->descripcion,
            'cliente' => $seguimiento->cliente?->empresa->razon_social,
            'fecha_hora' => Carbon::parse($seguimiento->created_at)->format('Y-m-d H:i:s'),
        ]);
    } */

    /* private function obtenerSumaPorTarea($seguimientoStock) {
        return $seguimientoStock->groupBy('tarea')->map(function ($item) {
            // $item['suma'] = $item->sum('cantidad_utilizada');
            return $item;
        }); //->values();
    } */
}
