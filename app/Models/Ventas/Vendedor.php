<?php

namespace App\Models\Ventas;

use App\Models\Empleado;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\Ventas\Vendedor
 *
 * @property int $empleado_id
 * @property int $modalidad_id
 * @property string|null $tipo_vendedor
 * @property int|null $jefe_inmediato_id
 * @property bool $activo
 * @property string|null $causa_desactivacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read Empleado|null $jefe_inmediato
 * @property-read Modalidad|null $modalidad
 * @property-read Collection<int, Venta> $ventas
 * @property-read int|null $ventas_count
 * @method static Builder|Vendedor acceptRequest(?array $request = null)
 * @method static Builder|Vendedor filter(?array $request = null)
 * @method static Builder|Vendedor ignoreRequest(?array $request = null)
 * @method static Builder|Vendedor newModelQuery()
 * @method static Builder|Vendedor newQuery()
 * @method static Builder|Vendedor query()
 * @method static Builder|Vendedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Vendedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Vendedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Vendedor whereActivo($value)
 * @method static Builder|Vendedor whereCausaDesactivacion($value)
 * @method static Builder|Vendedor whereCreatedAt($value)
 * @method static Builder|Vendedor whereEmpleadoId($value)
 * @method static Builder|Vendedor whereJefeInmediatoId($value)
 * @method static Builder|Vendedor whereModalidadId($value)
 * @method static Builder|Vendedor whereTipoVendedor($value)
 * @method static Builder|Vendedor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Vendedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_vendedores';
    protected $fillable = [
        'empleado_id',
        'modalidad_id',
        'tipo_vendedor',
        'jefe_inmediato_id',
        'activo',
        'causa_desactivacion'
    ];

    protected $primaryKey = 'empleado_id';
    //obtener la llave primaria
    public function getKeyName()
    {
        return 'empleado_id';
    }
    public $incrementing = false;


    const VENDEDOR = 'VENDEDOR';
    const JEFE_VENTAS = 'JEFE DE VENTAS';
    const SUPERVISOR_VENTAS = 'SUPERVISOR_VENTAS';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
        // return $this->belongsTo(Empleado::class, 'id', 'empleado_id')->with('canton');
    }
    public function modalidad()
    {
        return $this->hasOne(Modalidad::class, 'id', 'modalidad_id');
    }
    public function jefe_inmediato()
    {
        return $this->hasOne(Empleado::class, 'id', 'jefe_inmediato_id')->with('canton');
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    /********************************
     * FUNCIONES
     *******************************/

    /**
     * ESTE METODO ESTA EN DESUSO, POR FAVOR ELIMINARLO
     */
    /**
     * La función "verificarVentasMensuales" verifica si un vendedor ha alcanzado el umbral mínimo de
     * ventas para un mes hasta una fecha determinada.
     *
     * @param Vendedor $vendedor Una instancia de la clase Vendedor, que representa a un vendedor.
     * @param string $fecha El parámetro "fecha" es una fecha que representa la fecha maxima del mes del cual queremos verificar
     * las ventas mensuales.
     *
     * @return bool Devuelve verdadero si el recuento de ventas en el mes determinado es
     * mayor que el umbral mínimo definido en la modalidad del vendedor, y falso en caso contrario.
     */
    public static function verificarVentasMensuales(Vendedor $vendedor, $fecha)
    {
        try {
            $fecha_maxima  = Carbon::parse($fecha);
            $ventas = Venta::whereMonth('fecha_activacion', $fecha_maxima->month)
                ->where('fecha_activacion', '<=', $fecha_maxima)
                ->where('vendedor_id', $vendedor->empleado_id)
                ->where('estado_activacion', Venta::ACTIVADO)
                ->get();

            // Log::channel('testing')->info('Log', ['metodo alcanza umbral', $ventas, $vendedor->modalidad->umbral_minimo]);
            return $ventas->count() > $vendedor->modalidad->umbral_minimo;
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['erorr en verificar ventas mensuales', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    /**
     * La función calcula el número de ventas mensuales de un vendedor específico en función de una
     * fecha determinada.
     *
     * @param Carbon $fecha El parámetro "fecha" es una instancia de la clase Carbon, que representa una
     * fecha y hora. Se utiliza para especificar el mes y año para el cual se deben calcular las
     * ventas.
     * @param int $vendedor_id El parámetro `vendedor_id` representa el ID del vendedor o proveedor para
     * quien desea calcular la cantidad de ventas mensuales.
     *
     * @return La función `calcularCantidadVentasMensuales` devuelve una colección de ventas
     * (`) que cumplen con los criterios especificados. Las ventas se filtran según el año y
     * mes de la fecha de activación, el vendedor_id (id del vendedor) y el estado_activacion (estado
     * de activación). La función devuelve los datos de ventas filtrados como una colección.
     */
    public static function calcularCantidadVentasMensualesVendedor(Carbon $fecha, int $vendedor_id)
    {
        try {
            $ventas = Venta::whereYear('fecha_activacion', $fecha->year)->whereMonth('fecha_activacion', $fecha->month)
                ->where('vendedor_id', $vendedor_id)
                ->where('estado_activacion', Venta::ACTIVADO)
                ->get();
            return $ventas;
        } catch (Throwable $th) {
            throw $th;
        }
    }


    /**
     * ESTE METODO ESTA EN DESUSO, POR FAVOR ELIMINARLO
     */
    // public static function obtenerVentasConComision(Vendedor $vendedor, $fecha_inicio, $fecha_fin)
    // {
    //     try {
    //         $total_comisiones = 0;
    //         $fecha_maxima  = Carbon::parse($fecha_fin);
    //         $ventas = Venta::whereMonth('fecha_activacion', $fecha_maxima->month)
    //             ->where('fecha_activacion', '<=', $fecha_maxima)
    //             ->where('vendedor_id', $vendedor->empleado_id)
    //             ->where('estado_activacion', Venta::ACTIVADO)
    //             ->where('comisiona', true)
    //             ->orderBy('fecha_activacion')->get();
    //         Log::channel('testing')->info('Log', ['todas las ventas del vendedor?', $ventas]);
    //         $ventasConComision = $ventas->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin]);
    //         Log::channel('testing')->info('Log', ['todas las ventas del vendedor con comision?', $ventasConComision]);
    //         Log::channel('testing')->info('Log', ['estadisticas de ventas del vendedor?', $ventas->count(), $ventasConComision->count()]);
    //         foreach ($ventasConComision as $index => $venta) {
    //             [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
    //             $total_comisiones += $comision_valor;
    //         }

    //         return [$ventasConComision, $total_comisiones];
    //     } catch (\Throwable $th) {
    //         Log::channel('testing')->info('Log', ['erorr en obtenerVentasConComision', $th->getLine(), $th->getMessage()]);
    //         throw $th;
    //     }
    // }
}
