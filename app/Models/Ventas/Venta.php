<?php

namespace App\Models\Ventas;

use App\Mail\VentasClaro\EnviarMailVentaSuspendida;
use App\Models\Archivo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Ventas\Venta
 *
 * @property int $id
 * @property string $orden_id
 * @property string|null $orden_interna
 * @property int|null $supervisor_id
 * @property int|null $vendedor_id
 * @property int $producto_id
 * @property int|null $cliente_id
 * @property string|null $fecha_activacion
 * @property string $estado_activacion
 * @property string $forma_pago
 * @property int $comision_id
 * @property string $chargeback
 * @property string $comision_vendedor
 * @property int $comisiona
 * @property bool $activo
 * @property string|null $observacion
 * @property bool $primer_mes
 * @property string|null $fecha_pago_primer_mes
 * @property int $comision_pagada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ClienteClaro|null $cliente
 * @property-read Comision|null $comision
 * @property-read Collection<int, NovedadVenta> $novedadesVenta
 * @property-read int|null $novedades_venta_count
 * @property-read ProductoVenta|null $producto
 * @property-read Vendedor|null $supervisor
 * @property-read Vendedor|null $vendedor
 * @method static Builder|Venta acceptRequest(?array $request = null)
 * @method static Builder|Venta filter(?array $request = null)
 * @method static Builder|Venta ignoreRequest(?array $request = null)
 * @method static Builder|Venta newModelQuery()
 * @method static Builder|Venta newQuery()
 * @method static Builder|Venta query()
 * @method static Builder|Venta setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Venta setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Venta setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Venta whereActivo($value)
 * @method static Builder|Venta whereChargeback($value)
 * @method static Builder|Venta whereClienteId($value)
 * @method static Builder|Venta whereComisionId($value)
 * @method static Builder|Venta whereComisionPagada($value)
 * @method static Builder|Venta whereComisionVendedor($value)
 * @method static Builder|Venta whereComisiona($value)
 * @method static Builder|Venta whereCreatedAt($value)
 * @method static Builder|Venta whereEstadoActivacion($value)
 * @method static Builder|Venta whereFechaActivacion($value)
 * @method static Builder|Venta whereFechaPagoPrimerMes($value)
 * @method static Builder|Venta whereFormaPago($value)
 * @method static Builder|Venta whereId($value)
 * @method static Builder|Venta whereObservacion($value)
 * @method static Builder|Venta whereOrdenId($value)
 * @method static Builder|Venta whereOrdenInterna($value)
 * @method static Builder|Venta wherePrimerMes($value)
 * @method static Builder|Venta whereProductoId($value)
 * @method static Builder|Venta whereSupervisorId($value)
 * @method static Builder|Venta whereUpdatedAt($value)
 * @method static Builder|Venta whereVendedorId($value)
 * @mixin Eloquent
 */
class Venta  extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_ventas';
    protected $fillable = [
        'orden_id',
        'orden_interna',
        'supervisor_id',
        'vendedor_id',
        'producto_id',
        'fecha_activacion',
        'estado_activacion',
        'forma_pago',
        'comision_id',
        'chargeback',
        'comisiona',
        'comision_vendedor',
        'cliente_id',
        'activo',
        'observacion',
        'primer_mes',
        'fecha_pago_primer_mes',
        'comision_pagada',
    ];

    const ACTIVADO = 'ACTIVADO';
    const APROBADO = 'APROBADO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
        'primer_mes' => 'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

//    public function novedadesVenta()
//    {
//        return $this->hasMany(NovedadVenta::class);
//    }
    public function supervisor()
    {
        return $this->belongsTo(Vendedor::class, 'supervisor_id');
    }
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id'); //->with('empleado');
    }
    public function cliente()
    {
        return $this->hasOne(ClienteClaro::class, 'id', 'cliente_id');
    }
    public function producto()
    {
        return $this->hasOne(ProductoVenta::class, 'id', 'producto_id')->with('plan');
    }
    public function comision()
    {
        return $this->hasOne(Comision::class, 'id', 'comision_id');
    }
    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public static function empaquetarVentas($ventas)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($ventas as $venta) {
            $row['item'] = $id + 1;
            $row['vendedor'] =  $venta->vendedor->empleado->apellidos . ' ' . $venta->vendedor->empleado->nombres;
            $row['ciudad'] = $venta->vendedor->empleado->canton->canton;
            $row['codigo_orden'] =  $venta->orden_id;
            $row['identificacion'] =  $venta->vendedor->empleado->identificacion;
            $row['identificacion_cliente'] = $venta->cliente != null ? $venta->cliente->identificacion : '';
            $row['cliente'] =  $venta->cliente != null ? $venta->cliente->nombres . ' ' . $venta->cliente->apellidos : '';
            $row['venta'] = 1;
            $row['fecha_ingreso'] = $venta->created_at;
            $row['fecha_activacion'] =  $venta->fecha_activacion;
            $row['plan'] = $venta->producto->plan->nombre;
            $row['precio'] =  number_format($venta->producto->precio, 2, ',', '.');
            $row['forma_pago'] = $venta->forma_pago;
            $row['orden_interna'] = $venta->orden_interna;
            $results[$id] = $row;
            $id++;
        }
        return $results;
    }

    public static function enviarMailVendedor($vendedor_id, $supervisor_id, $venta)
    {
        $empleado = Empleado::find($vendedor_id);
        $supervisor = Empleado::find($supervisor_id);
        // Mail::to($empleado->user->email)->cc($supervisor->user->email)->send(new EnviarMailVentaSuspendida($venta)); //usar esta línea en producción
        Mail::to($empleado->user->email)->send(new EnviarMailVentaSuspendida($venta)); //pruebas
    }

    /**
     * La función "obtenerVentaComisiona" comprueba si un vendedor ha alcanzado el umbral mínimo de
     * ventas para comisión en función del número de ventas realizadas en el mes actual.
     *
     * @param int $vendedor_id El parámetro vendedor_id es el ID del vendedor (vendedor) del cual queremos
     * obtener la venta comisiona.
     *
     * @return Boolean Devuelve verdadero si el recuento de ventas realizadas por el
     * vendedor especificado en el mes actual es mayor o igual al umbral mínimo definido en la
     * modalidad de su vendedor. De lo contrario, devuelve falso.
     */
    public static function obtenerVentaComisiona(int $vendedor_id)
    {
        $vendedor = Vendedor::find($vendedor_id);
        $mes = Carbon::createFromFormat('Y-m-d', '2024-01-01')->format('m');
        // $mes = Carbon::now()->format('m');
        $suma = Venta::where(function ($query) use ($mes) {
            $query->whereMonth('fecha_activacion', $mes)
                ->whereMonth('created_at', $mes);
                // ->orWhereMonth('created_at', $mes);
        })->whereYear('created_at', date('Y'))
            ->where('vendedor_id', $vendedor_id)
            ->count();

        return $suma >= $vendedor->modalidad->umbral_minimo;
    }
}
