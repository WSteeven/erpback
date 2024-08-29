<?php

namespace Src\App;

use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\PreingresoMaterial;
use App\Models\SeguimientoMaterialStock;
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

        $results = collect($results)->map(fn($item, $index) => [
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

        $results = collect($results)->map(fn($item, $index) => [
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

        $materiales = MaterialEmpleado::with(['audits' => function ($query) use ($fecha_inicio, $fecha_fin) {
            $query->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
        }])->where('empleado_id', $empleado_id)->orderBy('detalle_producto_id', 'asc')->get();

        foreach ($materiales as $material) {
            // Se realiza el casteo de cada elemento
            foreach ($material->audits as $audit) {
                $row['fecha_hora'] = Carbon::parse($audit->created_at)->format('Y-m-d H:i:s');
                $row['empleado'] = Empleado::extraerNombresApellidos(User::find($audit->user_id)->empleado);
                $row['detalle_producto_id'] = $material->detalle_producto_id;
                $row['detalle_producto'] = $material->detalle->descripcion;
                $row['cliente'] = $material->cliente?->empresa?->razon_social;
                $row['evento'] = $audit->event;
                $row['auditable_id'] = $audit->auditable_id;
                $row['movimiento'] = $this->obtenerSegmentoRuta($audit->url);
                $row['cantidad_anterior'] = array_key_exists('cantidad_stock', $audit->old_values) ? $audit->old_values['cantidad_stock'] : 0;
                $row['cantidad_actual'] = array_key_exists('cantidad_stock', $audit->new_values) ? $audit->new_values['cantidad_stock'] : 0;
                $row['cantidad_afectada'] = abs($row['cantidad_anterior'] - $row['cantidad_actual']);
                // $row['movimiento'] = $audit->url;
                $url =  parse_url($audit->url);
                // if (array_key_exists('query', $url)) {
                //     $row['URL'] = $url;
                // } else {
                $segmentos = explode('/', $url['path']);
                $segmentos = array_filter($segmentos);
                $id = end($segmentos);
                /* Log::channel('testing')->info('Log', ['-> Url', $url]);
                Log::channel('testing')->info('Log', ['-> ID', $id]);
                Log::channel('testing')->info('Log', ['-> Auditable ID', $audit->auditable_id]);
                Log::channel('testing')->info('Log', ['-> Tarea', Tarea::find($audit->auditable_id)]);
                Log::channel('testing')->info('Log', ['-> Preingreso', PreingresoMaterial::find($audit->auditable_id)]); */

                $modelo = $segmentos[2];

                $entidad = $this->obtenerEntidad($modelo, $url);
                // Log::channel('testing')->info('Log', ['-> Entidad', $entidad]);
                $row['entidad_id'] = $entidad?->id;

                switch ($modelo) {
                    case 'preingresos':
                        $row['transaccion'] = 'PREINGRESO';
                        $row['descripcion'] = $entidad->observacion;
                        // $row['descripcion'] = PreingresoMaterial::find($id)->observacion;
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
                        $row['descripcion'] = $entidad->titulo;
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

        $eventos = ['created' => 'CREADO', 'updated' => 'ACTUALIZADO'];
        $results = collect($results)->map(function ($item) use ($eventos) {
            $item['evento'] = $eventos[$item['evento']];
            return $item;
        });

        $results = $results->sortByDesc('fecha_hora');

        return $results;
    }

    public function obtenerMaterialesEmpleadoIntervaloNoOcupados(int $empleado_id, string  $fecha_inicio) //8629
    {
        $results = [];

        $materiales = MaterialEmpleado::with(['audits' => function ($query) use ($fecha_inicio) {
            $query->where('updated_at', '<=', $fecha_inicio);
        }])->where('empleado_id', $empleado_id)->orderBy('detalle_producto_id', 'asc')->get();

        foreach ($materiales as $material) {
            // Se realiza el casteo de cada elemento
            foreach ($material->audits as $audit) {
                $row['fecha_hora'] = Carbon::parse($audit->created_at)->format('Y-m-d H:i:s');
                $row['empleado'] = Empleado::extraerNombresApellidos(User::find($audit->user_id)->empleado);
                $row['detalle_producto_id'] = $material->detalle_producto_id;
                $row['detalle_producto'] = $material->detalle->descripcion;
                $row['cliente'] = $material->cliente?->empresa?->razon_social;
                $row['evento'] = $audit->event;
                $row['auditable_id'] = $audit->auditable_id;
                $row['movimiento'] = $this->obtenerSegmentoRuta($audit->url);
                $row['cantidad_anterior'] = array_key_exists('cantidad_stock', $audit->old_values) ? $audit->old_values['cantidad_stock'] : 0;
                $row['cantidad_actual'] = array_key_exists('cantidad_stock', $audit->new_values) ? $audit->new_values['cantidad_stock'] : 0;
                $row['cantidad_afectada'] = abs($row['cantidad_anterior'] - $row['cantidad_actual']);
                $url =  parse_url($audit->url);
                $segmentos = explode('/', $url['path']);
                $segmentos = array_filter($segmentos);
                $id = end($segmentos);

                $modelo = $segmentos[2];

                $entidad = $this->obtenerEntidad($modelo, $url);
                $row['entidad_id'] = $entidad?->id;

                switch ($modelo) {
                    case 'preingresos':
                        $row['transaccion'] = 'PREINGRESO';
                        $row['descripcion'] = $entidad->observacion;
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
                        $row['descripcion'] = $entidad->titulo;
                        break;
                    default:
                        $row['segmentos'] = $segmentos;
                        $row['segmentos_id'] = $id;
                }
                // }

                $results[] = $row;
            }
        }

        $eventos = ['created' => 'CREADO', 'updated' => 'ACTUALIZADO'];
        $results = collect($results)->map(function ($item) use ($eventos) {
            $item['evento'] = $eventos[$item['evento']];
            return $item;
        });

        $results = $results->sortByDesc('fecha_hora');

        return $results;
    }

    private function obtenerEntidad(string $modelo, $url)
    {
        // Log::channel('testing')->info('Log', ['Modelo...', $modelo]);
        // Log::channel('testing')->info('Log', ['url...', $url]);
        $segmentos = explode('/', $url['path']);
        $endpoint = isset($segmentos[3]) ? $segmentos[3] : $segmentos[2];
        // Log::channel('testing')->info('Log', ['Endpoint ->...', $endpoint]);
        // Log::channel('testing')->info('Log', ['url []...', $url['path']]);
        switch ($modelo) {
            case 'tareas':
                if (isset($url['query'])) {
                    if ($endpoint == 'actualizar-cantidad-utilizada-stock' || $endpoint == 'actualizar-cantidad-utilizada-historial-stock') {
                        parse_str($url['query'], $queryParams);
                        $subtarea_id = $queryParams['subtarea_id'];
                        return Subtarea::find($subtarea_id)->tarea;
                    }
                } else {
                    if ($endpoint == 'tareas') {
                        return Tarea::find(end($segmentos));
                    }
                    // $segmentos = array_filter($segmentos);
                }
            case 'preingresos':
                return PreingresoMaterial::find(end($segmentos));
            default:
                return null; // end($segmentos);
        }
        // "App\\Models\\Tarea"
        // "path":"/api/tareas/actualizar-cantidad-utilizada-stock",
        // "query":"cantidad_anterior=0&cantidad_utilizada=1&cliente_id=3&detalle_producto_id=265&empleado_id=53&subtarea_id=8072"}] 
    }

    private function obtenerSegmentoRuta($url)
    {
        // Log::channel('testing')->info('Log', compact('url'));

        // Parseamos la URL para obtener los segmentos
        $parsedUrl = parse_url($url);
        // Log::channel('testing')->info('Log', compact('parsedUrl'));

        // Obtenemos la ruta de la URL
        $ruta = $parsedUrl['path'];
        // Log::channel('testing')->info('Log', compact('ruta'));

        // Obtenemos el nombre del segmento deseado
        $ruta = explode('/', $ruta);
        // $longitud = count($ruta);
        // Log::channel('testing')->info('Log', ['longitud' => $longitud]);
        // Log::channel('testing')->info('Log', ['-> item' => $ruta[3]]);
        // Log::channel('testing')->info('Log', ['****' => '********************************']);

        // $segmento = $longitud > 3 ? $ruta[3] : $ruta[1]; // last(explode('/', $ruta));
        // Log::channel('testing')->info('Log', compact('segmento'));

        // Retornamos el segmento encontrado
        array_shift($ruta);
        array_shift($ruta);
        return implode('/', $ruta);
    }

    public function obtenerSeguimientoMaterialesEmpleado(int $empleado_id, string  $fecha_inicio, string $fecha_fin = null)
    {
        return SeguimientoMaterialStock::where('empleado_id', $empleado_id)->where(function ($q) use ($fecha_inicio, $fecha_fin) {
            $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->orWhereBetween('updated_at', [$fecha_inicio, $fecha_fin]);
        })->get();
    }

    public function obtenerSeguimientoMaterialesEmpleadoBorrar(int $empleado_id, string $fecha_inicio, string $fecha_fin = null)
    {
        return SeguimientoMaterialStock::where('empleado_id', $empleado_id)
            ->where(function ($q) use ($fecha_inicio, $fecha_fin) {
                $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
                    ->orWhereBetween('updated_at', [$fecha_inicio, $fecha_fin]);
            })
            ->groupBy('tarea_id', 'detalle_producto_id') // Agrupar por empleado_id (puedes cambiarlo si necesitas agrupar por otro campo)
            ->selectRaw('*, SUM(cantidad_utilizada) as cantidad_utilizada')
            ->get();
    }
}
