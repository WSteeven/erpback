<?php

namespace Src\App\Bodega;

use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;

class PedidoService
{
    public function __construct()
    {
    }

    public static function filtrarPedidosReporte($request)
    {
        $results = [];
        switch ($request->estado) {
            case 1: //pendientes
                $results = Pedido::where('autorizacion_id', Autorizaciones::PENDIENTE)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 2: //completos
                $results = Pedido::where('estado_id', EstadosTransacciones::COMPLETA)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 3: //parciales
                $results = Pedido::where('estado_id', EstadosTransacciones::PARCIAL)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            case 4: // anulados
                $results = Pedido::where('autorizacion_id', Autorizaciones::CANCELADO)
                    ->where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))
                    ->when($request->fecha_fin, function ($query) use ($request) {
                        $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                    })->get();
                break;
            default: //todos los estados
                $results = Pedido::where('created_at', '>=', date('Y-m-d', strtotime($request->fecha_inicio)))->when($request->fecha_fin, function ($query) use ($request) {
                    $query->whereBetween('created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))]);
                })->get();
        }
        return $results;
    }

    public static function empaquetarDatos($datos)  {
        $results = [];
        $cont =0;
        foreach($datos as $d){
            Log::channel('testing')->info('Log', ['Datos antes de empaquetar dentro del foreach', $d]);
            $row['pedido_id'] = $d['id'];
            $row['created_at'] = $d['created_at'];
            $row['justificacion'] = $d['justificacion'];
            $row['solicitante'] = $d['solicitante']->nombres . ' ' . $d['solicitante']->apellidos;
            $row['autorizacion'] = $d['autorizacion']->nombre;
            $row['autorizador'] = $d['autoriza']->nombres . ' ' .$d['autoriza']->apellidos;
            $row['estado'] = $d['estado']->nombre;
            $row['responsable'] = $d['responsable']->nombres . ' ' .$d['responsable']->apellidos;
            Log::channel('testing')->info('Log', ['Datos del listado de pedido', $d['estado']]);
            foreach($d->listadoProductos($d["id"]) as $p){
                $row['descripcion'] = $p['descripcion'];
                $row['serial'] = $p['serial'];
                $row['categoria'] = $p['categoria'];
                $row['cantidad'] = $p['cantidad'];
                $row['despachado'] = $p['despachado'];
            }
            $results[$cont]  =$row;
            $cont++;
        }
        
        Log::channel('testing')->info('Log', ['resultados empaquetados', $results]);
        return $results;
    }
}
