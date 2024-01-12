<?php

namespace Src\App;

use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Subtarea;
use App\Models\Tarea;
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
        $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->groupBy('tarea_id')->pluck('tarea_id');
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
            $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->orwhere('grupo_id', $grupo_id)->groupBy('tarea_id')->pluck('tarea_id');
        } else {
            $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->groupBy('tarea_id')->pluck('tarea_id');
        }
        $ignoreRequest = ['activas_empleado', 'empleado_id', 'campos', 'formulario'];
        return Tarea::whereIn('id', $tareas_ids)->estaActiva()->orWhere(function ($query) {
            $query->where('finalizado', true)->disponibleUnaHoraFinalizar();
        })->ignoreRequest($ignoreRequest)->filter()->orderBy('id', 'desc')->get();
    }
    public function obtenerTareasAsignadasGrupoLuegoFinalizar(int $grupo_id)
    {
        $tareas_ids = Subtarea::where('grupo_id', $grupo_id)->groupBy('tarea_id')->pluck('tarea_id');
        $ignoreRequest = ['activas_empleado', 'empleado_id', 'campos', 'formulario'];

        return Tarea::whereIn('id', $tareas_ids)->estaActiva()->orWhere(function ($query) {
            $query->where('finalizado', true)->disponibleUnaHoraFinalizar();
        })->ignoreRequest($ignoreRequest)->filter()->orderBy('id', 'desc')->get();
    }

}
