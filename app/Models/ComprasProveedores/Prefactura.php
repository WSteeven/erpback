<?php

namespace App\Models\ComprasProveedores;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Src\Shared\Utils;

/**
 * App\Models\ComprasProveedores\Prefactura
 *
 * @property int $id
 * @property string $codigo
 * @property int|null $solicitante_id
 * @property int|null $proforma_id
 * @property int|null $cliente_id
 * @property int|null $estado_id
 * @property string|null $causa_anulacion
 * @property string $descripcion
 * @property string|null $forma
 * @property string|null $tiempo
 * @property float $iva
 * @property float|null $descuento_general
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComprasProveedores\ItemDetallePrefactura> $detalles
 * @property-read int|null $detalles_count
 * @property-read EstadoTransaccion|null $estado
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\ComprasProveedores\Proforma|null $proforma
 * @property-read Empleado|null $solicitante
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura query()
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereDescuentoGeneral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereForma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereProformaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereTiempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prefactura whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Prefactura extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;

    public $table = 'cmp_prefacturas';
    public $fillable = [
        'codigo',
        'solicitante_id',
        'cliente_id',
        'estado_id',
        'proforma_id',
        'causa_anulacion',
        'descripcion',
        'descuento_general',
        'forma',
        'tiempo',
        'iva',
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
     * Relación uno a muchos.
     * Una prefactura tiene varios detalles
     */
    public function detalles()
    {
        return $this->hasMany(ItemDetallePrefactura::class);
    }
    /**
     * Relación uno a muchos (inversa).
     * Uno o varias prefacturas pertencen a un solicitante.
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relación uno a muchos (inversa).
     * Una prefactura pertence a una proforma.
     */
    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }


    /**
     * Relación uno a uno(inversa).
     * Uno o varios prefacturas solo pueden tener un estado.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * Relación uno a uno
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una prefactura puede tener una o varias notificaciones.
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

    public static function listadoProductos(int $id)
    {
        $detalles = Prefactura::find($id)->detalles()->get();
        $results = [];
        $row = [];
        foreach ($detalles as $index => $detalle) {
            $row['id'] = $detalle->id;
            $row['cantidad'] = $detalle->cantidad;
            $row['descripcion'] = $detalle->descripcion;
            $row['unidad_medida'] = $detalle->unidad_medida_id;
            $row['unidad_medida_id'] = $detalle->unidadMedida->nombre;
            $row['iva'] = $detalle->iva;
            $row['porcentaje_descuento'] = $detalle->porcentaje_descuento;
            $row['precio_unitario'] = $detalle->precio_unitario;
            $row['descuento'] = $detalle->descuento;
            $row['iva'] = $detalle->iva;
            $row['subtotal'] = $detalle->subtotal;
            $row['total'] = $detalle->total;
            $row['facturable'] = (bool)$detalle->facturable;
            $row['grava_iva'] = (bool)$detalle->grava_iva;
            $results[$index] = $row;
        }

        return $results;
    }
    public static function obtenerSumaListado($id)
    {
        $prefactura = Prefactura::find($id);
        $detalles = ItemDetallePrefactura::where('prefactura_id', $id)->get();
        $subtotal = $detalles->sum('subtotal');
        $descuento = $detalles->sum('descuento');
        $subtotal_con_impuestos = $detalles->where('grava_iva', true)->sum('subtotal') - $descuento;
        $subtotal_sin_impuestos = $detalles->where('grava_iva', false)->sum('subtotal');
        $iva = $subtotal_con_impuestos * $prefactura->iva / 100;
        $total = $subtotal_con_impuestos + $subtotal_sin_impuestos + $iva;

        return [$subtotal, $subtotal_con_impuestos, $subtotal_sin_impuestos, $iva, $descuento, $total];
    }
    public static function guardarDetalles($prefactura, $items)
    {
        Log::channel('testing')->info('Log', ['Datos recibidos :', $items]);
        try {
            DB::beginTransaction();
            $datos = array_map(function ($detalle) {
                return [
                    'cantidad' => $detalle['cantidad'],
                    'descripcion' => $detalle['descripcion'],
                    'facturable' => $detalle['facturable'],
                    'grava_iva' => $detalle['grava_iva'],
                    'iva' => $detalle['iva'],
                    'porcentaje_descuento' => $detalle['porcentaje_descuento'] || 0,
                    'descuento' => $detalle['descuento'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                    'total' => $detalle['total'],
                    'unidad_medida_id' => $detalle['unidad_medida'],
                ];
            }, $items);
            $prefactura->detalles()->delete();
            $prefactura->detalles()->createMany($datos);
            // foreach($datos as $item){
            // }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public static function filtrarPrefacturasEmpleado($request)
    {
        $results = Prefactura::where(function ($query) {
            $query->orWhere('solicitante_id', auth()->user()->empleado->id);
        })->ignoreRequest(['solicitante_id'])->filter()->orderBy('updated_at', 'desc')->get();
        return $results;
    }

    /**
     * La función obtiene un código concatenando el año actual (2 digitos), el mes (dos digitos) y un código generado con una
     * longitud específica.
     * 
     * @return string una cadena que consta del año actual - el mes actual, seguida de un código generado
     * con una longitud de 3 dígitos con formato (año-mes00#).
     */
    public static function obtenerCodigo()
    {
        $mes = Carbon::now()->format('m');
        $suma = Prefactura::whereYear('created_at', date('Y'))->whereMonth('created_at', $mes)->count();
        return date('y') . '-' . $mes . Utils::generarCodigoConLongitud($suma + 1, 3);
    }
}
