<?php

namespace App\Models\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class OrdenCompra extends Model implements Auditable
{
  use HasFactory;
  use AuditableModel;
  use Filterable;
  use UppercaseValuesTrait;

  public $table = 'cmp_ordenes_compras';
  public $fillable = [
    'solicitante_id',
    'proveedor_id',
    'autorizador_id',
    'autorizacion_id',
    'observacion_aut',
    'preorden_id',
    'pedido_id',
    'estado_id',
    'observacion_est',
    'descripcion',
    'forma',
    'tiempo',
    'fecha',
    'categorias',
  ];


  //Forma de pago
  const CONTADO = 'CONTADO';
  const CREDITO = 'CREDITO';

  //Tiempo de pago
  const SEMANAL = '7 DIAS';
  const QUINCENAL = '15 DIAS';
  const MES = '30 DIAS';

  protected $casts = [
    'created_at' => 'datetime:Y-m-d h:i:s a',
    'updated_at' => 'datetime:Y-m-d h:i:s a',
  ];

  private static $whiteListFilter = ['*'];

  /**
   * ______________________________________________________________________________________
   * RELACIONES CON OTRAS TABLAS
   * ______________________________________________________________________________________
   */

  /**
   * Relación muchos a muchos. 
   * Una orden de compra tiene varios detalles de productos.
   */
  public function detalles()
  {
    return $this->belongsToMany(DetalleProducto::class, 'cmp_item_detalle_orden_compra', 'orden_compra_id', 'detalle_id')
      ->withPivot(['cantidad', 'porcentaje_descuento', 'facturable', 'grava_iva', 'precio_unitario', 'iva', 'subtotal', 'total'])->withTimestamps();
  }
  /**
   * Relación uno a uno.
   * 
   */
  public function pedido()
  {
    return $this->belongsTo(Pedido::class);
  }

  /**
   * Relación uno a muchos (inversa).
   * Uno o varias ordenes de compra pertencen a un solicitante.
   */
  public function solicitante()
  {
    return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
  }

  /**
   * Relación uno a muchos (inversa).
   * Uno o varias ordenes de compra son autorizados por un empleado Jefe Inmediato.
   */
  public function autorizador()
  {
    return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
  }

  /**
   * Relación uno a uno(inversa).
   * Uno o varios ordenes de compra solo pueden tener una autorización.
   */
  public function autorizacion()
  {
    return $this->belongsTo(Autorizacion::class);
  }

  /**
   * Relación uno a uno(inversa).
   * Uno o varios ordenes de compra solo pueden tener un estado.
   */
  public function estado()
  {
    return $this->belongsTo(EstadoTransaccion::class);
  }

  /**
   * ______________________________________________________________________________________
   * FUNCIONES
   * ______________________________________________________________________________________
   */
  public static function listadoProductos(int $id)
  {
    $detalles = OrdenCompra::find($id)->detalles()->get();
    $results = [];
    $row = [];
    foreach ($detalles as $index => $detalle) {
      $row['id'] = $detalle->id;
      $row['producto'] = $detalle->producto->nombre;
      $row['descripcion'] = $detalle->descripcion;
      $row['categoria'] = $detalle->producto->categoria->nombre;
      $row['unidad_medida'] = $detalle->producto->unidadMedida->nombre;
      $row['serial'] = $detalle->serial;
      $row['cantidad'] = $detalle->pivot->cantidad;
      // $row['precio_unitario'] = 0;
      $row['iva'] = 0;
      $row['subtotal'] = 0;
      $row['total'] = 0;
      $results[$index] = $row;
    }

    return $results;
  }

  public static function guardarDetalles($orden, $items)
  {
    try {
      DB::beginTransaction();
      $datos = array_map(function ($detalle) {
        return [
          'detalle_id' => $detalle['id'],
          'cantidad' => $detalle['cantidad'],
          'porcentaje_descuento' => $detalle['porcentaje_descuento'],
          'facturable' => $detalle['facturable'],
          'grava_iva' => $detalle['grava_iva'],
          'precio_unitario' => array_key_exists('precio_unitario', $detalle) ? $detalle['precio_unitario'] : 0,
          'iva' => $detalle['iva'],
          'subtotal' => $detalle['subtotal'],
          'total' => $detalle['total'],
        ];
      }, $items);
      $orden->detalles()->sync($datos);

      Log::channel('testing')->info('Log', ['Request :', $orden->detalles()->count(), $orden->preorden_id]);
      if ($orden->detalles()->count() > 0 && $orden->preorden_id) {
        Log::channel('testing')->info('Log', ['Entre al if :', $orden->detalles()->count(), $orden->preorden_id]);
        $preorden = PreordenCompra::find($orden->preorden_id);
        $preorden->update(['estado' => EstadoTransaccion::COMPLETA]);
        $preorden->save();
        Log::channel('testing')->info('Log', ['fin del if :', $preorden]);
      }
      DB::commit();
    } catch (Exception $e) {
      Log::channel('testing')->info('Log', ['Error en metodo guardar detalles de orden de compras', $e->getMessage(), $e->getLine()]);
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }
}
