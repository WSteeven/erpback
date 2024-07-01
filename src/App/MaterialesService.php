<?php

namespace Src\App;

use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\PreingresoMaterial;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

class MaterialesService
{
    // STOCK PERSONAL
    public function obtenerMaterialesEmpleado(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
        ]);

        $empleado_id = $request['empleado_id'];
        $results = MaterialEmpleado::filter()->where('empleado_id', $empleado_id)->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return $results;
        // return response()->json(compact('results'));
    }

    // TAREAS
    public function obtenerMaterialesEmpleadoTarea(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        $results = MaterialEmpleadoTarea::filter()->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return response()->json(compact('results'));
    }


    public function obtenerMaterialesEmpleadoIntervalo(int $empleado_id, string  $fecha_inicio, string $fecha_fin = null)
    {
        $fecha_fin = $fecha_fin != null ? $fecha_fin : Carbon::now();
        $results = [];
        $cont = 0;
        $materiales = MaterialEmpleado::with('audits')->where('empleado_id', $empleado_id)->whereHas('audits', function ($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
        })
            ->orderBy('detalle_producto_id', 'asc')
            ->get();
        foreach ($materiales as $material) {
            //se realiza el casteo de cada elemento
            foreach ($material->audits as $audit) {
                $row['fecha'] = $audit->created_at;
                $row['empleado'] = Empleado::extraerNombresApellidos(User::find($audit->user_id)->empleado);
                $row['detalle_producto_id'] = $material->detalle_producto_id;
                $row['detalle_producto'] = $material->detalle->descripcion;
                $row['evento'] = $audit->event;
                $row['movimiento'] = $audit->url;
                $row['cantidad_anterior'] = array_key_exists('cantidad_stock', $audit->old_values) ? $audit->old_values['cantidad_stock'] : 0;
                $row['cantidad_actual'] = array_key_exists('cantidad_stock', $audit->new_values) ? $audit->new_values['cantidad_stock'] : 0;
                $row['cantidad_afectada'] = abs($row['cantidad_anterior'] - $row['cantidad_actual']);
                $row['movimiento'] = $audit->url;
                $url =  parse_url($audit->url);
                // if (array_key_exists('query', $url)) {
                //     $row['URL'] = $url;
                // } else {
                $segmentos = explode('/', $url['path']);
                $segmentos = array_filter($segmentos);
                $id = end($segmentos);
                switch ($segmentos[2]) {
                    case 'preingresos':
                        $row['transaccion'] = 'PREINGRESO';
                        $row['descripcion'] = PreingresoMaterial::find($id)->observacion;
                        break;
                    case 'transacciones-ingresos':
                        $row['transaccion'] = 'INGRESO A BODEGA';
                        $row['descripcion'] = '';
                        break;
                    case 'transacciones-egresos':
                        $row['transaccion'] = 'EGRESO DE BODEGA';
                        $row['descripcion'] = '';
                        break;
                    case 'tareas':
                        $row['transaccion'] = 'TAREA';
                        $tarea =  Tarea::find($id);
                        $row['descripcion'] = $tarea ? $tarea?->titulo : Subtarea::find($id)?->titulo;
                        break;
                    default:
                        $row['segmentos'] = $segmentos;
                        $row['segmentos_id'] = $id;
                }
                // }

                $results[] = $row;
            }
        }
        // Log::channel('testing')->info('Log', ['results', $results]);

        return $results;
    }
}
