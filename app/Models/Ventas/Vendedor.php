<?php

namespace App\Models\Ventas;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;

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

    private static $whiteListFilter = [
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

            return $ventas->count() > $vendedor->modalidad->umbral_minimo;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['erorr en verificar ventas mensuales', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }


    public static function obtenerVentasConComision(Vendedor $vendedor, $fecha_inicio, $fecha_fin)
    {
        try {
            $total_comisiones = 0;
            $fecha_maxima  = Carbon::parse($fecha_fin);
            $ventas = Venta::whereMonth('fecha_activacion', $fecha_maxima->month)
                ->where('fecha_activacion', '<=', $fecha_maxima)
                ->where('vendedor_id', $vendedor->empleado_id)
                ->where('estado_activacion', Venta::ACTIVADO)
                ->orderBy('fecha_activacion')->get();
            Log::channel('testing')->info('Log', ['todas las ventas del vendedor?', $ventas]);
            $ventasSinComision = $ventas->count() > $vendedor->modalidad->umbral_minimo ?  $ventas->take($vendedor->modalidad->umbral_minimo) : $ventas;
            if (!empty($ventasSinComision)) $ventasSinComision = $ventasSinComision->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin]);
            $ventasConComision = $ventas->skip($vendedor->modalidad->umbral_minimo)->whereBetween('fecha_activacion', [$fecha_inicio, $fecha_fin]);
            Log::channel('testing')->info('Log', ['todas las ventas del vendedor SIN comision?', $ventasSinComision]);
            Log::channel('testing')->info('Log', ['todas las ventas del vendedor con comision?', $ventasConComision]);
            Log::channel('testing')->info('Log', ['estadisticas de ventas del vendedor?', $ventas->count(), count($ventasSinComision), $ventasConComision->count()]);
            foreach ($ventasConComision as $index => $venta) {
                [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
                $total_comisiones += $comision_valor;
            }

            return [$ventasConComision, $ventasSinComision, $total_comisiones];
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['erorr en obtenerVentasConComision', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
