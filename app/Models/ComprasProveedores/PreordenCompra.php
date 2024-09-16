<?php

namespace App\Models\ComprasProveedores;

use App\Events\ComprasProveedores\PreordenCreadaEvent;
use App\Events\ComprasProveedores\PreordenEvent;
use App\Models\Autorizacion;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Notificacion;
use App\Models\Pedido;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;

/**
 * App\Models\ComprasProveedores\PreordenCompra
 *
 * @method static find(mixed $preorden_id)
 * @property int $id
 * @property int|null $solicitante_id
 * @property int|null $pedido_id
 * @property int|null $autorizador_id
 * @property int|null $autorizacion_id
 * @property string $estado
 * @property string|null $causa_anulacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Autorizacion|null $autorizacion
 * @property-read Empleado|null $autorizador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Pedido|null $pedido
 * @property-read Empleado|null $solicitante
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereAutorizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra wherePedidoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreordenCompra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PreordenCompra extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'cmp_preordenes_compras';
    public $fillable = [
        'solicitante_id',
        'pedido_id',
        'autorizador_id',
        'autorizacion_id',
    ];

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
     * Una preorden tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'cmp_item_detalle_preorden_compra', 'preorden_id', 'detalle_id')
            ->withPivot('cantidad')->withTimestamps();
    }

    /**
     * Relación uno a uno
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una preorden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    /**
     * La función genera un mensaje para una preorden de compra generada automáticamente, que
     * incluye información sobre el solicitante y el autorizador.
     *
     * @param PreordenCompra $preorden El parámetro "preorden" es una instancia de la clase
     * "PreordenCompra". Representa una preorden de compra y contiene información como el ID de la
     * preorden, el nombre de la persona que lo solicitó (solicitante) y el nombre de la persona que lo
     * autorizó (autorizador).
     *
     * @return string $msg una cadena que incluye el número de preorden de compra, el nombre de la persona que
     * solicitó la preorden, el nombre de la persona que autorizó la preorden y un
     * mensaje para verificar y generar la orden de compra respectiva.
     */
    public static function generarMensajePreordenAutomatica(PreordenCompra $preorden)
    {
        return 'Preorden de compra N° ' . $preorden->id . ' generada automaticamente por el sistema, solicitada por ' . $preorden->solicitante->nombres . ' ' . $preorden->solicitante->apellidos . ' y autorizada por ' . $preorden->autorizador->nombres . ' ' . $preorden->autorizador->apellidos . '. Por favor verifica y genera la respectiva orden de compra';
    }
    public static function generarMensajePreordenAutomaticaControlStock(PreordenCompra $preorden)
    {
        return 'Preorden de compra N° ' . $preorden->id . ' generada automaticamente por el sistema mediante control de stock, solicitada por ' . $preorden->solicitante->nombres . ' ' . $preorden->solicitante->apellidos . ' y autorizada por ' . $preorden->autorizador->nombres . ' ' . $preorden->autorizador->apellidos . '. Por favor verifica y genera la respectiva orden de compra';
    }

    /**
     * La función "generarPreorden" crea una preorden anticipado para una compra en función de un pedido y
     * artículos determinados.
     *
     * @param Pedido pedido El parámetro "pedido" es un objeto que representa un pedido en el sistema.
     * Contiene información como la identificación del solicitante (solicitante), la identificación del
     * autorizador (autorizador) y la identificación de la autorización (autorización).
     * @param Array items El parámetro "elementos" es una matriz de elementos que se asociarán con la
     * preorden. Cada elemento de la matriz representa un detalle del pedido previo y debe contener la
     * información necesaria para crear el registro de detalle en la base de datos.
     */
    public static function generarPreorden($pedido, $items)
    {
        $url = '/preordenes-compras';
        try {
            DB::beginTransaction();
            $preorden = PreordenCompra::create([
                'solicitante_id' => $pedido->solicitante_id,
                'pedido_id' => $pedido->id,
                'autorizador_id' => $pedido->per_autoriza_id,
                'autorizacion_id' => $pedido->autorizacion_id,
            ]);

            // guardar los detalles en la preorden
            $preorden->detalles()->sync($items);

            $msg = self::generarMensajePreordenAutomatica($preorden);
            event(new PreordenCreadaEvent($msg, User::ROL_BODEGA, $url, $preorden, true));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al generar la preorden de compra', $e->getMessage(), $e->getLine()]);
        }
    }

    public static function generarPreordenControlStock($item){
        $url = '/preordenes-compras';
        try {
            $coordinadorBodega = User::whereHas("roles", function ($q) {
                $q->where("name", User::ROL_COORDINADOR_BODEGA);
            })->first();
            DB::beginTransaction();
            $preorden = PreordenCompra::create([
                'solicitante_id'=>$coordinadorBodega->empleado->id,
                'autorizador_id'=>$coordinadorBodega->empleado->id,
                'autorizacion_id'=>Autorizaciones::APROBADO
            ]);

            $preorden->detalles()->sync($item);

            $msg = self::generarMensajePreordenAutomaticaControlStock($preorden);
            event(new PreordenCreadaEvent($msg, User::ROL_BODEGA, $url, $preorden, true));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error en el método generarPreordenControlStock', $th->getMessage(), $th->getLine()]);
            throw $th;
        }
    }

    /**
     * La función "listadoProductos" recupera detalles de productos de una pre-orden de compra y los
     * devuelve en una matriz.
     *
     * @param int id El parámetro "id" es un número entero que representa el ID de una preorden de compra.
     *
     * @return una matriz de detalles del producto para una identificación de compra de pedido
     * anticipado determinada. Cada detalle de producto incluye el ID, el nombre, la descripción, la
     * categoría, la unidad de medida, el número de serie, la cantidad y los valores calculados para el
     * precio, el impuesto, el subtotal y el total.
     */
    public static function listadoProductos(int $id)
    {
        $detalles = PreordenCompra::find($id)->detalles()->get();
        $results = [];
        $row = [];
        foreach ($detalles as $index => $detalle) {
            $row['id'] = $detalle->id;
            $row['producto_id'] = $detalle->producto_id;
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

    public static function itemsPreordenesPendientes()
    {
        try {
            $results = [];
            $row = [];
            $ids_preordenes = PreordenCompra::where('estado', EstadoTransaccion::PENDIENTE)->get('id');
            $items = ItemDetallePreordenCompra::select('detalle_id', DB::raw('sum(cantidad) as total'))
                ->whereIn('preorden_id', $ids_preordenes)->groupBy('detalle_id')->orderBy('total', 'desc')
                ->get();
            foreach ($items as $index => $item) {
                $detalle = DetalleProducto::where('id', $item['detalle_id'])->first();
                $ids_preordenes =  ItemDetallePreordenCompra::where('detalle_id', $detalle->id)->get('id');
                $row['id'] = $index;
                $row['producto'] = $detalle->producto->nombre;
                $row['producto_id'] = $detalle->producto_id;
                $row['detalle_id'] = $item['detalle_id'];
                $row['descripcion'] = $detalle->descripcion;
                $row['unidad_medida'] = $detalle->producto->unidadMedida?->nombre;
                $row['preordenes'] = $ids_preordenes->pluck('id')->map(fn($value) => strval($value))->join(',');
                $row['cantidad'] = $item['total'];
                $results[$index] = $row;
            }

            return $results;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error: ', $th->getMessage(), $th->getLine()]);
            throw $th;
        }
    }

    public static function eliminarItemsConsolidacion($ids_detalle_id)
    {
        try {
            DB::beginTransaction();
            $ids_preordenes = PreordenCompra::where('estado', EstadoTransaccion::PENDIENTE)->get('id');
            ItemDetallePreordenCompra::whereIn('preorden_id', $ids_preordenes)->whereIn('detalle_id', $ids_detalle_id)->delete();
            self::verificarPreordenesPendientes();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function verificarPreordenesPendientes()
    {
        try {
            DB::beginTransaction();
            PreordenCompra::whereDoesntHave('detalles')->update(['estado' => EstadoTransaccion::ANULADA, 'causa_anulacion' => 'sin elementos, anulada por consolidacion de items']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
