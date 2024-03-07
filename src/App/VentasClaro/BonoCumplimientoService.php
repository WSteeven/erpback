<?php

namespace Src\App\VentasClaro;

use App\Models\Ventas\Bono;
use App\Models\Ventas\BonoMensualCumplimiento;
use App\Models\Ventas\BonoPorcentual;
use App\Models\Ventas\UmbralVenta;
use App\Models\Ventas\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BonoCumplimientoService
{
    public function __construct()
    {
    }

    public static function calcularBonosMensuales(Carbon $fecha)
    {
        try {
            DB::beginTransaction();
            $vendedores = Vendedor::all();
            foreach ($vendedores as $key => $vendedor) {
                // $suma_ventas = $this->calcular_cantidad_ventas($mes, $vendedor->empleado_id);
                $suma_ventas = Vendedor::calcularCantidadVentasMensualesVendedor($fecha, $vendedor->empleado_id)->count();
                if ($suma_ventas > 0) {
                    $bono = self::calcularBono($suma_ventas, $vendedor);
                    if ($bono) {
                        if ($vendedor->tipo_vendedor === "VENDEDOR") {
                            $valor_bono = $bono !== null ?  $bono->valor : 0;
                        } else {
                            $valor_bono = $bono !== null ?  $bono->comision : 0;
                        }
                        BonoMensualCumplimiento::crearBonoCumplimiento($bono, $vendedor->empleado_id, $suma_ventas, $fecha, $valor_bono);
                    }
                } //else Log::channel('testing')->info('Log', ['El vendedor no tiene ventas realizadas, no se calcula nada', $vendedor, $suma_ventas]);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error en calcularBonosMensuales', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }


    /**
     * La función `calcularBono` calcula una bonificación para un Vendedor en función de su desempeño de ventas y
     * tipo de vendedor.
     * 
     * @param int $suma_ventas La suma total de las ventas realizadas por un vendedor específico. 
     * Este valor se utiliza para determinar la bonificación a la que es elegible el vendedor 
     * en función de su desempeño en ventas.
     * @param Vendedor $vendedor Una instancia de la clase Vendedor.
     * 
     * @return Bono|BonoPorcentual  Devuelve el bono calculado en base a los parámetros
     * de entrada del monto total de ventas, y el objeto `Vendedor`. La lógica de cálculo del bono 
     * varía dependiendo del tipo de vendedor - si el vendedor es del tipo  "VENDEDOR", recupera una instancia de `App\Models\Ventas\Bono`,
     * caso contrario recupera una instancia de `App\Models\Ventas\BonoPorcentual`.
     */
    public static function calcularBono(int $suma_ventas, Vendedor $vendedor)
    {
        try {
            DB::beginTransaction();
            $bono = null;
            if ($vendedor->tipo_vendedor == "VENDEDOR") {
                $bono =  Bono::where('cant_ventas', '<=', $suma_ventas)->first();
            } else {
                $meta_ventas = UmbralVenta::where('vendedor_id', $vendedor->id)->first();
                $cantidad_ventas = $meta_ventas == !null ? $meta_ventas->cantidad_ventas : 1;
                $porcentaje =  $suma_ventas / $cantidad_ventas;
                $bono = BonoPorcentual::where('porcentaje', '<=', ($porcentaje * 100))->where('porcentaje', '>', 0)->orderBy('id', 'desc')->first();
            }
            DB::commit();
            return $bono;
        } catch (Throwable $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error en calcularBono', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }
}
