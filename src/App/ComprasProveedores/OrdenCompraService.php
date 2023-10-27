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
use Src\Shared\Utils;

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
}
