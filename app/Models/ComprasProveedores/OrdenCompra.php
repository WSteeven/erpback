<?php

namespace App\Models\ComprasProveedores;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\CorreoEnviado;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Src\Shared\Utils;

class OrdenCompra extends Model implements Auditable
{
  use HasFactory;
  use AuditableModel;
  use Filterable;
  use UppercaseValuesTrait;

  public $table = 'cmp_ordenes_compras';
  public $fillable = [
    'codigo',
    'solicitante_id',
    'proveedor_id',
    'autorizador_id',
    'autorizacion_id',
    'observacion_aut',
    'preorden_id',
    'pedido_id',
    'tarea_id',
    'estado_id',
    'observacion_est',
    'descripcion',
    'forma',
    'tiempo',
    'fecha',
    'categorias',
    'iva',
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
   * Una orden de compra tiene varios productos asociados.
   */
  public function productos()
  {
    return $this->belongsToMany(Producto::class, 'cmp_item_detalle_orden_compra', 'orden_compra_id', 'producto_id')
      ->withPivot(['descripcion', 'cantidad', 'porcentaje_descuento', 'facturable', 'grava_iva', 'precio_unitario', 'iva', 'subtotal', 'total'])->withTimestamps();
  }
  /**
   * Relación uno a uno.
   * Una orden de compra se realiza unicamente a un proveedor.
   *
   */
  public function proveedor()
  {
    return $this->belongsTo(Proveedor::class);
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
   * Relación uno a uno.
   * Una orden de compra puede tener asociada una tarea
   */
  public function tarea()
  {
    return $this->belongsTo(Tarea::class);
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
   * Relacion polimorfica a una notificacion.
   * Una orden de compra puede tener una o varias notificaciones.
   */
  public function notificaciones()
  {
    return $this->morphMany(Notificacion::class, 'notificable');
  }

  /**
   * Relacion polimorfica a una notificacion.
   * Una orden de compra puede tener una o varias notificaciones.
   */
  public function correos()
  {
    return $this->morphMany(CorreoEnviado::class, 'notificable');
  }

  /**
   * Relación para obtener la ultima notificacion de un modelo dado.
   */
  public function latestNotificacion()
  {
    return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
  }

  /**
   * Relación uno a muchos.
   * Una orden de compra puede tener muchas novedades.
   */
  public function novdadesOrdenCompra()
  {
    return $this->hasMany(NovedadOrdenCompra::class);
  }

  /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos(){
      return $this->morphMany(Archivo::class, 'archivable');
  }

  /**
   * ______________________________________________________________________________________
   * FUNCIONES
   * ______________________________________________________________________________________
   */

  public static function listadoProductos(int $id)
  {
    $productos = OrdenCompra::find($id)->productos()->get();
    // Log::channel('testing')->info('Log', ['los productos consultados', $productos]);
    $results = [];
    $row = [];
    foreach ($productos as $index => $producto) {
      $row['id'] = $producto->id;
      $row['producto'] = $producto->nombre;
      $row['descripcion'] = Utils::mayusc($producto->pivot->descripcion);
      $row['categoria'] = $producto->categoria->nombre;
      $row['unidad_medida'] = $producto->unidadMedida->nombre;
      $row['cantidad'] = $producto->pivot->cantidad;
      $row['precio_unitario'] = $producto->pivot->precio_unitario;
      $row['porcentaje_descuento'] = $producto->pivot->porcentaje_descuento;
      $row['descuento'] = $producto->pivot->subtotal * $producto->pivot->porcentaje_descuento / 100;
      $row['iva'] = $producto->pivot->iva;
      $row['subtotal'] = $producto->pivot->subtotal;
      $row['total'] = $producto->pivot->total;
      $row['facturable'] = (bool)$producto->pivot->facturable;
      $row['grava_iva'] = (bool)$producto->pivot->grava_iva;
      $results[$index] = $row;
    }

    return $results;
  }
  /**
   * La función "obtenerSumaListado" calcula el subtotal, el total, el descuento y el impuesto (IVA) de
   * una lista de artículos en función del ID de orden de compra dado.
   *
   * @param int $id El parámetro "id" es el ID de la orden de compra.
   *
   * @return una matriz con los valores de subtotal, el total, el descuento y IVA.
   */
  public static function obtenerSumaListado($id)
  {
    $detalles = ItemDetalleOrdenCompra::where('orden_compra_id', $id)->get();
    $total = $detalles->sum('total');
    $subtotal = $detalles->sum('subtotal');
    $iva = $detalles->sum('iva');
    $descuento = $detalles->sum('descuento');

    return [$subtotal, $iva, $descuento, $total];
  }

  public static function guardarDetalles($orden, $items, $metodo)
  {
    // Log::channel('testing')->info('Log', ['Request :', $orden, $items]);
    try {
      DB::beginTransaction();
      $datos = array_map(function ($detalle) use ($metodo) {
        // Log::channel('testing')->info('Log', ['Detalle:', $detalle]);
        if ($metodo == 'crear') $producto = Producto::where('nombre', $detalle['nombre'])->first();
        // Log::channel('testing')->info('Log', ['Producto:', $producto]);
        return [
          'producto_id' => $metodo == 'crear' ? $producto->id : $detalle['id'],
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
      Log::channel('testing')->info('Log', ['Datos:', $datos]);
      $orden->productos()->sync($datos);

      Log::channel('testing')->info('Log', ['linea 241 :', $orden->productos()->count(), $orden->preorden_id]);
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

  public static function filtrarOrdenesEmpleado($request)
  {
    $results = OrdenCompra::where(function ($query) {
      $query->orWhere('solicitante_id', auth()->user()->empleado->id)
        ->orWhere('autorizador_id', auth()->user()->empleado->id);
    })->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
    // $results = OrdenCompra::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
    return $results;
  }

  /**
   * La función obtiene un código concatenando el año actual (2 digitos), el mes (dos digitos) y un código generado con una
   * longitud específica.
   *
   * @return string una cadena que consta del año actual menos el mes actual, seguida de un código generado
   * con una longitud de 3 dígitos.
   */
  public static function obtenerCodigo()
  {
    $mes = Carbon::now()->format('m');
    $suma = OrdenCompra::whereYear('created_at', date('Y'))->whereMonth('created_at', $mes)->count();
    return date('y') . '-' . $mes . Utils::generarCodigoConLongitud($suma + 1, 3);
  }
}
