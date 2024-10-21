<?php

namespace App\Models\ComprasProveedores;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\CorreoEnviado;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Tarea;
use App\Models\UnidadMedida;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Src\Shared\Utils;
use Throwable;

/**
 * App\Models\ComprasProveedores\OrdenCompra
 *
 * @method static ignoreRequest(string[] $array)
 * @property int $id
 * @property string $codigo
 * @property int|null $solicitante_id
 * @property int|null $proveedor_id
 * @property int|null $autorizador_id
 * @property int|null $autorizacion_id
 * @property string|null $causa_anulacion
 * @property int|null $preorden_id
 * @property int|null $pedido_id
 * @property string|null $observacion_aut
 * @property int|null $estado_id
 * @property string|null $observacion_est
 * @property string $descripcion
 * @property string|null $forma
 * @property string|null $tiempo
 * @property string $fecha
 * @property bool $revisada_compras
 * @property string|null $observacion_compras
 * @property bool $realizada
 * @property bool $pagada
 * @property string|null $observacion_realizada
 * @property string|null $categorias
 * @property float $iva
 * @property int|null $tarea_id
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Empleado|null $autorizador
 * @property-read Collection<int, CorreoEnviado> $correos
 * @property-read int|null $correos_count
 * @property-read Collection<int, ItemDetalleOrdenCompra> $detalles
 * @property-read int|null $detalles_count
 * @property-read EstadoTransaccion|null $estado
 * @property-read Notificacion|null $latestNotificacion
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Collection<int, NovedadOrdenCompra> $novedadesOrdenCompra
 * @property-read int|null $novedades_orden_compra_count
 * @property-read Pedido|null $pedido
 * @property-read Collection<int, Producto> $productos
 * @property-read int|null $productos_count
 * @property-read Proveedor|null $proveedor
 * @property-read Empleado|null $solicitante
 * @property-read Tarea|null $tarea
 * @method static Builder|OrdenCompra acceptRequest(?array $request = null)
 * @method static Builder|OrdenCompra filter(?array $request = null)
 * @method static Builder|OrdenCompra newModelQuery()
 * @method static Builder|OrdenCompra newQuery()
 * @method static Builder|OrdenCompra query()
 * @method static Builder|OrdenCompra setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|OrdenCompra setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|OrdenCompra setLoadInjectedDetection($load_default_detection)
 * @method static Builder|OrdenCompra whereAutorizacionId($value)
 * @method static Builder|OrdenCompra whereAutorizadorId($value)
 * @method static Builder|OrdenCompra whereCategorias($value)
 * @method static Builder|OrdenCompra whereCausaAnulacion($value)
 * @method static Builder|OrdenCompra whereCodigo($value)
 * @method static Builder|OrdenCompra whereCreatedAt($value)
 * @method static Builder|OrdenCompra whereDescripcion($value)
 * @method static Builder|OrdenCompra whereEstadoId($value)
 * @method static Builder|OrdenCompra whereFecha($value)
 * @method static Builder|OrdenCompra whereFile($value)
 * @method static Builder|OrdenCompra whereForma($value)
 * @method static Builder|OrdenCompra whereId($value)
 * @method static Builder|OrdenCompra whereIva($value)
 * @method static Builder|OrdenCompra whereObservacionAut($value)
 * @method static Builder|OrdenCompra whereObservacionCompras($value)
 * @method static Builder|OrdenCompra whereObservacionEst($value)
 * @method static Builder|OrdenCompra whereObservacionRealizada($value)
 * @method static Builder|OrdenCompra wherePagada($value)
 * @method static Builder|OrdenCompra wherePedidoId($value)
 * @method static Builder|OrdenCompra wherePreordenId($value)
 * @method static Builder|OrdenCompra whereProveedorId($value)
 * @method static Builder|OrdenCompra whereRealizada($value)
 * @method static Builder|OrdenCompra whereRevisadaCompras($value)
 * @method static Builder|OrdenCompra whereSolicitanteId($value)
 * @method static Builder|OrdenCompra whereTareaId($value)
 * @method static Builder|OrdenCompra whereTiempo($value)
 * @method static Builder|OrdenCompra whereUpdatedAt($value)
 * @property int|null $proveedor_internacional_id
 * @property-read \App\Models\ComprasProveedores\ProveedorInternacional|null $proveedorInternacional
 * @method static Builder|OrdenCompra whereProveedorInternacionalId($value)
 * @mixin Eloquent
 */
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
        'proveedor_internacional_id',
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
        'realizada' => 'boolean',
        'pagada' => 'boolean',
        'revisada_compras' => 'boolean',
        'completada' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

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
            ->withPivot(['id', 'descripcion', 'unidad_medida_id', 'cantidad', 'porcentaje_descuento', 'facturable', 'grava_iva', 'precio_unitario', 'iva', 'subtotal', 'total'])->withTimestamps();
    }

    public function detalles()
    {
        return $this->hasMany(ItemDetalleOrdenCompra::class);
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

    public function proveedorInternacional()
    {
        return $this->belongsTo(ProveedorInternacional::class);
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
     * Relación para obtener la última notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relación uno a muchos.
     * Una orden de compra puede tener muchas novedades.
     */
    public function novedadesOrdenCompra()
    {
        return $this->hasMany(NovedadOrdenCompra::class);
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
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
        $results = [];
        $row = [];
        foreach ($productos as $index => $producto) {
            $row['id'] = $producto->pivot->id;
            $row['producto'] = $producto->nombre;
            $row['producto_id'] = $producto->id;
            $row['descripcion'] = Utils::mayusc($producto->pivot->descripcion);
            $row['categoria'] = $producto->categoria->nombre;
            $row['unidad_medida'] = $producto->pivot->unidad_medida_id ? $producto->pivot->unidad_medida_id : $producto->unidadMedida->nombre;
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
     * @return array una matriz con los valores de subtotal, el total, el descuento y IVA.
     */
    public static function obtenerSumaListado(int $id)
    {
        $orden = OrdenCompra::find($id);
        $detalles = ItemDetalleOrdenCompra::where('orden_compra_id', $id)->get();
        $subtotal = $detalles->sum('subtotal');
        $descuento = $detalles->sum('descuento');
        $subtotal_con_impuestos = $detalles->where('grava_iva', true)->sum('subtotal') - $descuento;
        $subtotal_sin_impuestos = $detalles->where('grava_iva', false)->sum('subtotal');
        $iva = $subtotal_con_impuestos * $orden->iva / 100;
        $total = $subtotal_con_impuestos + $subtotal_sin_impuestos + $iva;

        return [$subtotal, $subtotal_con_impuestos, $subtotal_sin_impuestos, $iva, $descuento, $total];
    }

    /**
     * @throws Throwable
     */
    public static function guardarDetalles($orden, $items, $metodo)
    {
        try {
            DB::beginTransaction();
            $datos = array_map(function ($detalle) use ($metodo) {
                if (array_key_exists('nombre', $detalle)) $producto = Producto::where('nombre', $detalle['nombre'])->first();
                else $producto = Producto::where('nombre', $detalle['producto'])->first();
                // }
                return [
                    'producto_id' => array_key_exists('producto_id', $detalle) ? $detalle['producto_id'] : $producto->id,
                    'descripcion' => $detalle['descripcion'] ? Utils::mayusc($detalle['descripcion']) : $detalle['producto'],
                    'cantidad' => $detalle['cantidad'],
                    'unidad_medida_id' => is_int($detalle['unidad_medida']) ? $detalle['unidad_medida'] : UnidadMedida::where('nombre', $detalle['unidad_medida'])->first()->id,
                    'porcentaje_descuento' => array_key_exists('porcentaje_descuento', $detalle) ? $detalle['porcentaje_descuento'] : 0,
                    'descuento' => $detalle['descuento'],
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

    public static function filtrarOrdenesEmpleado()
    {
        return OrdenCompra::where(function ($query) {
            $query->orWhere('solicitante_id', auth()->user()->empleado->id)
                ->orWhere('autorizador_id', auth()->user()->empleado->id);
        })->ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->orderBy('id', 'desc')->get();
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
