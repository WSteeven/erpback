<?php

namespace Src\App\Bodega;

use App\Models\DetalleProductoTransaccion;
use Log;

class ProductoEmpleadoService
{
    public static function obtenerSumaReporteEgresos($data)
    {
        $results = [];
        foreach ($data as $d) {
            $items = DetalleProductoTransaccion::where('transaccion_id', $d->id)->get();
            foreach ($items as $item) {
                $detalleProductoId = $item->inventario->detalle->id;
                $propietario = $item->inventario->cliente->empresa->razon_social;

                // Llave única para identificar elementos
                $key = $detalleProductoId . '|' . $propietario;

                if (!isset($results[$key])) {
                    // Si el elemento no existe en el array, agregarlo
                    $results[$key] = [
                        'producto' => $item->inventario->detalle->producto->nombre,
                        'descripcion' => $item->inventario->detalle->descripcion,
                        'serial' => $item->inventario->detalle->serial,
                        'cliente' => $propietario,
                        'cantidad' => $item->cantidad_inicial, // Inicializar con la cantidad del ítem actual
                        'detalle_producto_id' => $item->inventario->detalle->id,
                        'cliente_id' => $item->inventario->cliente_id, //->id,
                    ];
                } else {
                    // Si el elemento ya existe, sumar la cantidad
                    $results[$key]['cantidad'] += $item->cantidad_inicial;
                }
            }
        }

        // Convertir resultados a un array indexado
        return array_values($results);
    }

    public static function obtenerSumaCantidadesProductos($productos_transferencias)
    {
        $results = [];
        foreach ($productos_transferencias as $item) {
            $detalleProductoId = $item['detalle_producto_id'];
            $propietario = $item['cliente_id'];

            // Llave única para identificar elementos
            $key = $detalleProductoId . '|' . $propietario;

            if (!isset($results[$key])) {
                // Si el elemento no existe en el array, agregarlo
                $results[$key] = [
                    'producto' => $item['producto'],
                    'descripcion' => $item['descripcion'],
                    'serial' => $item['serial'],
                    'cliente' => $item['cliente'],
                    'cantidad' => $item['cantidad'],
                    'detalle_producto_id' => $item['detalle_producto_id'],
                    'cliente_id' => $item['cliente_id'],
                ];
            } else {
                // Si el elemento ya existe, sumar la cantidad
                $results[$key]['cantidad'] += $item['cantidad'];
            }
        }

        // Convertir resultados a un array indexado
        return array_values($results);
    }

    public static function restarSumaCantidadesProductos($array1, $array2)
    {
        $results = [];

        // Convertir $array2 en un mapa clave => cantidad
        $mapArray2 = [];
        foreach ($array2 as $item) {
            $key = $item['detalle_producto_id'] . '|' . ($item['cliente_id'] ?? '');
            $mapArray2[$key] = $item['cantidad'];
        }

        // Recorrer $array1 y restar cantidades
        foreach ($array1 as $item) {
            $key = $item['detalle_producto_id'] . '|' . ($item['cliente_id'] ?? '');
            $cantidadRestante = $item['cantidad'] - ($mapArray2[$key] ?? 0);

            if ($cantidadRestante > 0) {
                $results[] = [
                    'producto' => $item['producto'],
                    'descripcion' => $item['descripcion'],
                    'serial' => $item['serial'],
                    'cliente' => $item['cliente'] ?? '',
                    'cantidad' => $cantidadRestante,
                    'detalle_producto_id' => $item['detalle_producto_id'],
                    'cliente_id' => $item['cliente_id'],
                ];
            }
        }

        return $results;
    }


    public static function restarSumaCantidadesProductosOld($array1, $array2)
    {
        $results = [];

        // Convertir $array2 en un mapa clave => cantidad
        $mapArray2 = [];
        foreach ($array2 as $item) {
            $key = $item['detalle_producto_id'] . '|' . $item['cliente_id'];
            $mapArray2[$key] = $item['cantidad'];
        }

        // Recorrer $array1 y restar cantidades
        foreach ($array1 as $item) {
            $key = $item['detalle_producto_id'] . '|' . $item['cliente_id'];
            $cantidadRestante = $item['cantidad'] - ($mapArray2[$key] ?? 0);

            if ($cantidadRestante > 0) {
                $results[] = [
                    'producto' => $item['producto'],
                    'descripcion' => $item['descripcion'],
                    'serial' => $item['serial'],
                    'cliente' => $item['cliente'],
                    'cantidad' => $cantidadRestante,
                    'detalle_producto_id' => $item['detalle_producto_id'],
                    'cliente_id' => $item['cliente_id'],
                ];
            }
        }

        return $results;
    }
}
