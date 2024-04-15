<?php

namespace Src\App\ComprasProveedores;

use App\Http\Requests\ComprasProveedores\OrdenCompraRequest;
use App\Http\Resources\ComprasProveedores\OrdenCompraResource;
use App\Http\Resources\ComprasProveedores\ProveedorResource;
use App\Models\Autorizacion;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Producto;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;
use Src\Shared\Utils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenCompraService
{
    protected $validador;
    public function __construct()
    {
        $this->validador = new OrdenCompraRequest();
    }

    public static function generarPdf(OrdenCompra $orden_compra, $guardar, $descargar)
    {
        try {
            $configuracion = ConfiguracionGeneral::first();
            if (!$orden_compra->proveedor_id) throw new Exception('Debes ingresar un proveedor en la Orden de Compra para poder imprimir');
            $proveedor = new ProveedorResource(Proveedor::find($orden_compra->proveedor_id));
            $empleado_solicita = Empleado::find($orden_compra->solicitante_id);
            $orden = new OrdenCompraResource($orden_compra);

            //aplanar collections
            $orden = $orden->resolve();
            $proveedor = $proveedor->resolve();
            $valor = Utils::obtenerValorMonetarioTexto($orden['sum_total']);
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
                // Log::channel('testing')->info('Log', ['RUTA donde se almacenó la orden de compra', $ruta]);

                if ($descargar) {
                    return Storage::download($ruta, $filename);
                } else {
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

    public function crearOrdenCompra(array $datos, array $items)
    {
        try {
            // $this->validador->setData($datos);
            // if ($this->validador->fails()) throw new \Exception($this->validador->errors()->first());
            DB::beginTransaction();
            $orden = OrdenCompra::create($datos);
            $this->guardarDetalles($orden, $items);
            DB::commit();
            return $orden;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function guardarDetalles($orden, $items)
  {
    try {
      DB::beginTransaction();
      $datos = array_map(function ($detalle) {
        // if ($metodo == 'crear') {
        if (array_key_exists('nombre', $detalle)) $producto = Producto::where('nombre', $detalle['nombre'])->first();
        else $producto = Producto::where('nombre', $detalle['producto'])->first();
        // }
        return [
          'producto_id' => array_key_exists('producto_id', $detalle) ? $detalle['producto_id'] : $producto->id,
          'descripcion' => $detalle['descripcion'] ? Utils::mayusc($detalle['descripcion']) : $detalle['producto'],
          'cantidad' => $detalle['cantidad'],
          'porcentaje_descuento' => array_key_exists('porcentaje_descuento', $detalle) ? $detalle['porcentaje_descuento'] : 0,
          'facturable' => $detalle['facturable'],
          'grava_iva' => $detalle['grava_iva'],
          'precio_unitario' => array_key_exists('precio_unitario', $detalle) ? $detalle['precio_unitario'] : 0,
          'iva' => $detalle['iva'],
          'subtotal' => $detalle['subtotal'],
          'total' => $detalle['total'],
        ];
      }, $items);
      $orden->productos()->sync($datos);
      $orden->auditSync('productos', $datos);
      /**
       * Auditar modelos relacionados con laravel-auditing
       */
      // https://laravel-auditing.com/guide/audit-custom.html
      // $article->auditAttach('categories', $category);
      // $orden->auditSync('productos', $datos);
      // $orden->auditDetach('productos', $datos);

      // aquí se modifica el estado de la preorden de compra
      if ($orden->productos()->count() > 0 && $orden->preorden_id) {
        $preorden = PreordenCompra::find($orden->preorden_id);
        $preorden->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion
        $preorden->estado = EstadoTransaccion::COMPLETA;
        $preorden->save();
      }
      DB::commit();
    } catch (Exception $e) {
      Log::channel('testing')->info('Log', ['Error en metodo guardar productos de orden de compras', $e->getMessage(), $e->getLine()]);
      throw new Exception($e->getMessage());
    }
  }

    public static function obtenerDashboard(Request $request)
    {
        $results = [];
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        $ordenes = OrdenCompra::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->when($request->empleado, function ($query) use ($request) {
                $query->where('solicitante_id', $request->empleado);
            })
            ->when($request->proveedor && $request->tipo == 'PROVEEDOR', function ($query) use ($request) {
                if ($request->proveedor != null) $query->where('proveedor_id', $request->proveedor);
            })
            // ->where('solicitante_id', $request->empleado)
            ->get();
        // Log::channel('testing')->info('Log', ['request:', $request->empleado]);
        // Log::channel('testing')->info('Log', ['obtener ordenes por estados:', $fecha_inicio, $fecha_fin, $ordenes]);

        switch ($request->tipo) {
            case 'ESTADO':
                $results = self::dividirOrdenesPorEstados($ordenes);
                break;
            case 'PROVEEDOR':
                $results = self::dividirOrdenesPorProveedores($ordenes);
                break;
            default:
                // Log::channel('testing')->info('Log', ['Entró en default, se lanzará un error']);
                throw new Exception('Error al filtrar las ordenes de compras para el dashboard');
        }



        return $results;
    }

    public static function dividirOrdenesPorEstados($ordenes)
    {
        $results = [];
        $pendientes = $ordenes->filter(function ($orden) {
            return $orden->autorizacion_id == Autorizaciones::PENDIENTE;
        });
        $aprobadas = $ordenes->filter(function ($orden) {
            return $orden->autorizacion_id == Autorizaciones::APROBADO;
        });
        $revisadas = $ordenes->filter(function ($orden) {
            return $orden->estado_id == 2 && $orden->realizada == false;
        });
        $realizadas = $ordenes->filter(function ($orden) {
            return $orden->realizada == true;
        });
        $pagadas = $ordenes->filter(function ($orden) {
            return $orden->pagada == true;
        });
        $anuladas = $ordenes->filter(function ($orden) {
            return $orden->autorizacion_id == Autorizaciones::CANCELADO || $orden->estado_id == EstadosTransacciones::ANULADA;
        });
        $todas = OrdenCompraResource::collection($ordenes);
        $cant_ordenes_creadas = $ordenes->count();
        $cant_ordenes_pendientes = $pendientes->count();
        $cant_ordenes_aprobadas = $aprobadas->count();
        $cant_ordenes_revisadas = $revisadas->count();
        $cant_ordenes_realizadas = $realizadas->count();
        $cant_ordenes_pagadas = $pagadas->count();
        $cant_ordenes_anuladas = $anuladas->count();
        $tituloGrafico = "Ordenes de Compra";

        $graficos = [];

        //Configuramos el primer gráfico
        $graficoCreadas = new Collection([
            'id' => 1,
            'identificador' => 'CREADAS',
            'encabezado' => 'Estados de autorización de las ordenes de compras',
            'labels' => [Autorizacion::PENDIENTE, Autorizacion::APROBADO, Autorizacion::CANCELADO],
            'datasets' => [
                [
                    'backgroundColor' => Utils::colorDefault(),
                    'label' => $tituloGrafico,
                    'data' => [$pendientes->count(), $aprobadas->count(), $anuladas->count()],
                ]
            ],
        ]);
        array_push($graficos, $graficoCreadas);

        //Configuramos el segundo gráfico
        $graficoAprobadas = new Collection([
            'id' => 2,
            'identificador' => 'APROBADAS',
            'encabezado' => 'Estados de ordenes de compras aprobadas',
            'labels' => ['REVISADAS', 'PENDIENTES DE REVISAR', 'REALIZADAS', 'PAGADAS'],
            'datasets' => [
                [
                    'backgroundColor' => Utils::coloresAleatorios(),
                    'label' => $tituloGrafico,
                    'data' => [$revisadas->count(), ($aprobadas->count() - $revisadas->count() - $realizadas->count()), $realizadas->count(), $pagadas->count()],
                ]
            ],
        ]);
        array_push($graficos, $graficoAprobadas);

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
    /** KPIS
     * pie de proveedores a los que se les ha comprado, con sus respectivas ordenes de compra,
     * al darle clic que muestre los estados de las ordenes de compra de dichos proveedores
     */
    public static function dividirOrdenesPorProveedores($ordenes)
    {
        $results = [];
        //filtramos las ordenes de compras revisadas y realizadas en adelante
        $todas_sin_proveedor = $ordenes->filter(function ($orden) {
            return $orden->proveedor_id == null;
        });
        $todas = $ordenes;
        // $todas = $ordenes->filter(function ($orden) {
        //     return $orden->proveedor_id != null;
        // });
        $pendientes = $ordenes->filter(function ($orden) {
            return $orden->proveedor_id != null && $orden->estado_id == 1;
        });
        $revisadas = $ordenes->filter(function ($orden) {
            return $orden->proveedor_id != null && $orden->estado_id == 2 && $orden->realizada == false;
        });
        $realizadas = $ordenes->filter(function ($orden) {
            return $orden->proveedor_id != null && $orden->realizada == true && !$orden->pagada;
        });
        $pagadas = $ordenes->filter(function ($orden) {
            return $orden->proveedor_id != null && $orden->pagada == true;
        });
        $anuladas = $ordenes->filter(function ($orden) {
            // return $orden->proveedor_id != null && ($orden->autorizacion_id == Autorizaciones::CANCELADO || $orden->estado_id == EstadosTransacciones::ANULADA);
            return $orden->estado_id == 4;
        });
        // Log::channel('testing')->info('Log', ['pendientes:', $pendientes->count()]);
        // Log::channel('testing')->info('Log', ['revisadas:', $revisadas->count()]);
        // Log::channel('testing')->info('Log', ['realizadas:', $realizadas->count()]);
        // Log::channel('testing')->info('Log', ['pagadas:', $pagadas->count()]);
        // Log::channel('testing')->info('Log', ['anuladas:', $anuladas->count(), $ordenes->filter(function ($orden) {
        //     return $orden->estado_id == 4;
        // })]);
        $todas = OrdenCompraResource::collection($todas);
        $cant_ordenes_sin_proveedor = $todas_sin_proveedor->count();
        $cant_ordenes_proveedores = $todas->count() - $todas_sin_proveedor->count();
        $cant_ordenes_pendientes = $pendientes->count();
        $cant_ordenes_revisadas = $revisadas->count();
        $cant_ordenes_realizadas = $realizadas->count();
        $cant_ordenes_pagadas = $pagadas->count();
        $cant_ordenes_anuladas = $anuladas->count();
        $tituloGrafico = "Ordenes de Compra";

        $graficos = [];

        //Configuramos el primer gráfico
        $graficoCreadas = new Collection([
            'id' => 1,
            'identificador' => 'PROVEEDORES',
            'encabezado' => 'Estados de las ordenes de compras que tienen proveedor',
            'labels' => [
                'REVISADAS',
                'PENDIENTES DE REVISAR',
                'REALIZADAS',
                'PAGADAS',
                'ANULADAS'
            ],
            'datasets' => [
                [
                    'backgroundColor' => Utils::coloresAleatorios(),
                    'label' => $tituloGrafico,
                    'data' => [
                        $revisadas->count(),
                        $todas->count() - $revisadas->count() - $realizadas->count() - $anuladas->count() - $pagadas->count(), //pendientes de revisar
                        $realizadas->count(), //realizadas
                        $pagadas->count(), //pagadas
                        $anuladas->count() //anuladas
                    ],
                ]
            ],
        ]);
        array_push($graficos, $graficoCreadas);
        /**
         * // Configuramos el segundo gráfico
         * $graficoAprobadas = new Collection([
         * 'id' => 2,
         * 'identificador' => 'APROBADAS',
         * 'encabezado' => 'Estados de ordenes de compras aprobadas',
         * 'labels' => ['REVISADAS', 'PENDIENTES DE REVISAR', 'REALIZADAS', 'PAGADAS'],
         * 'datasets' => [
         * [
         * 'backgroundColor' => Utils::coloresAleatorios(),
         * 'label' => $tituloGrafico,
         * 'data' => [$revisadas->count(), $aprobadas->count() - $revisadas->count(), $realizadas->count(), $pagadas->count()],
         * ]
         * ],
         * ]);
         * array_push($graficos, $graficoAprobadas);
         */

        return compact(
            'graficos',
            'todas',
            'pendientes',
            'revisadas',
            'realizadas',
            'pagadas',
            'anuladas',
            'cant_ordenes_sin_proveedor',
            'cant_ordenes_proveedores',
            'cant_ordenes_pendientes',
            'cant_ordenes_revisadas',
            'cant_ordenes_realizadas',
            'cant_ordenes_pagadas',
            'cant_ordenes_anuladas',
        );
    }
}
