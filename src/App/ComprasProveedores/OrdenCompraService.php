<?php

namespace Src\App\ComprasProveedores;

use App\Http\Resources\ComprasProveedores\OrdenCompraResource;
use App\Http\Resources\ComprasProveedores\ProveedorResource;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;
use Illuminate\Database\Eloquent\Collection;

class OrdenCompraService
{
    public function __construct()
    {
        //
    }

    public static function generarPdf(OrdenCompra $orden_compra, $guardar, $descargar)
    {
        try {
            $configuracion = ConfiguracionGeneral::first();
            $proveedor = new ProveedorResource(Proveedor::find($orden_compra->proveedor_id));
            $empleado_solicita = Empleado::find($orden_compra->solicitante_id);
            $orden = new OrdenCompraResource($orden_compra);

            //aplanar collections
            $orden = $orden->resolve();
            $proveedor = $proveedor->resolve();
            $valor = Utils::obtenerValorMonetarioTexto($orden['sum_total']);
            Log::channel('testing')->info('Log', ['Balor a enviar', $orden['sum_total']]);
            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['orden' => $orden, 'proveedor' => $proveedor, 'empleado_solicita' => $empleado_solicita]]);
            $pdf = Pdf::loadView('compras_proveedores.orden_compra', compact(['orden', 'proveedor', 'empleado_solicita', 'valor', 'configuracion']));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output(); //se genera el pdf

            //segun la variable guardar, se guarda en el sistema y se registra en la base de datos el nuevo nombre o se envia al front nomás
            if ($guardar) {
                $filename = 'orden_' . $orden['id'] . '_' . time() . '.pdf'; //se le da un nombre al archivo
                $ruta = 'public' . DIRECTORY_SEPARATOR . 'compras' . DIRECTORY_SEPARATOR . 'ordenes_compras' . DIRECTORY_SEPARATOR . $filename;
                //Se guarda el pdf
                Storage::put($ruta, $file);
                //Se actualiza la ruta en la orden de compra
                $orden_compra->file = $ruta;
                $orden_compra->save();
                Log::channel('testing')->info('Log', ['RUTA donde se almacenó la orden de compra', $ruta]);

                if ($descargar) {
                    Log::channel('testing')->info('Log', ['Descargar orden de compra', $ruta]);
                    return Storage::download($ruta, $filename);
                } else {
                    Log::channel('testing')->info('Log', ['NO Descargar orden de compra', $ruta]);
                    return $ruta;
                }
            } else {
                return $file;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR OrdenCompraService', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    public static function obtenerOrdenesDeComprasPorEstados($request){
        $results = [];
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $ordenes = OrdenCompra::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
        Log::channel('testing')->info('Log', ['obtener ordenes por estados:', $ordenes, $fecha_inicio, $fecha_fin]);

        $results = self::dividirOrdenesPorEstados($ordenes);



        return $results;
    }

    public static function dividirOrdenesPorEstados($ordenes){
        $results=[];
        Log::channel('testing')->info('Log', ['dividir ordenes por estados:', $ordenes]);
        $pendientes = $ordenes->filter(function($orden){
            return $orden->autorizacion_id == Autorizaciones::PENDIENTE;
        });
        $aprobadas = $ordenes->filter(function($orden){
            return $orden->autorizacion_id == Autorizaciones::APROBADO;
        });
        Log::channel('testing')->info('Log', ['antes de ordenes revisadas:', $ordenes]);
        $revisadas= $ordenes->filter(function($orden){
            return $orden->estado_id == 2;
        });
        Log::channel('testing')->info('Log', ['despues de ordenes revisadas:', $revisadas]);
        $realizadas=$ordenes->filter(function($orden){
            return $orden->realizada == true;
        });
        $pagadas = $ordenes->filter(function($orden){
            return $orden->pagada == true;
        });
        $anuladas= $ordenes->filter(function($orden){
            return $orden->autorizacion_id == Autorizaciones::CANCELADO || $orden->estado_id==EstadosTransacciones::ANULADA;
        });
        $todas = $ordenes;
        $cant_ordenes_creadas = $ordenes->count();
        $cant_ordenes_pendientes = $pendientes->count();
        $cant_ordenes_aprobadas = $aprobadas->count();
        $cant_ordenes_revisadas = $revisadas->count();
        $cant_ordenes_realizadas = $realizadas->count();
        $cant_ordenes_pagadas = $pagadas->count();
        $cant_ordenes_anuladas = $anuladas->count();

        $graficos = [];

        $graficoCreadas =new Collection([
            'labels'=> $labels,
            'datasets'=>[
               ['backgroundColor' =>$colores,
                    'label'=> $label,
                    'data' => $valores,
               ]
            ],
        ]);
        array_push($graficos, $grafico);

        return compact(
            'graficos',
            'todas',
            'pendientes',
            'aprobadas',
            'revisadas',
            'realizadas',
            'pagadas',
            'anuladas',
            'cant_ordenes_creadas',
            'cant_ordenes_pendientes',
            'cant_ordenes_aprobadas',
            'cant_ordenes_revisadas',
            'cant_ordenes_realizadas',
            'cant_ordenes_pagadas',
            'cant_ordenes_anuladas',
        );
    }

}
