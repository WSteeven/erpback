<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Comision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_comisiones';
    protected $fillable = ['plan_id', 'forma_pago', 'comision'];
    private static $whiteListFilter = [
        '*',
    ];

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }


    /**
     * La función calcula la comisión en función del tipo de vendedor, el precio del producto y la tasa
     * de comisión.
     * 
     * @param int $idVendedor El id del vendedor (vendedor) para quien queremos calcular la comisión.
     * @param int $idProducto El parámetro "idProducto" es el ID del producto para el cual se debe calcular
     * la comisión.
     * @param string $forma_pago El parámetro "forma_pago" representa el método de pago de la venta. Podría ser
     * un valor de cadena como "efectivo", "tarjeta de crédito" o "transferencia bancaria".
     * 
     * @return comision la comisión calculada en base a los parámetros dados.
     */
    public static function calcularComision($idVendedor, $idProducto, $forma_pago)
    {
        $vendedor = Vendedor::find($idVendedor);
        $producto = ProductoVenta::find($idProducto);
        $comision = Comision::where('plan_id', $producto->plan_id)->where('forma_pago', $forma_pago)->where('tipo_vendedor', $vendedor->tipo_vendedor)->first();
        $valor_comision = floatval($comision != null ? $comision->comision : 0);
        return [$vendedor->tipo_vendedor == 'VENDEDOR' ?  ($producto->precio * $valor_comision) / 100 : $valor_comision, $comision];
    }
}
