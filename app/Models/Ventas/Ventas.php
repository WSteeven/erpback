<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Ventas  extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_ventas';
    protected $fillable =['orden_id','orden_interna','vendedor_id','producto_id','fecha_activacion','estado_activacion','forma_pago','comision_id','chargeback','comision_vendedor','cliente_id'];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->hasOne(Vendedor::class,'id','vendedor_id')->with('empleado');
    }
    public function cliente(){
        return $this->hasOne(ClienteClaro::class,'id','cliente_id');
    }
    public function producto(){
        return $this->hasOne(ProductoVentas::class,'id','producto_id')->with('plan');
    }
    public function comision(){
        return $this->hasOne(Comisiones::class,'id','comision_id');
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
            $row['identificacion_cliente'] =  $venta->cliente->identificacion;
            $row['cliente'] =  $venta->cliente->nombres.' '. $venta->cliente->apellidos;
            $row['venta'] = 1;
            $row['fecha_ingreso'] = $venta->created_at;
            $row['fecha_activacion'] =  $venta->fecha_activacion;
            $row['plan'] = $venta->producto->plan->nombre;
            $row['precio'] =  number_format($venta->producto->precio, 2, ',', '.');
            $row['forma_pago'] = $venta->forma_pago;
            $row['orden_interna'] =$venta->orden_interna;
            $results[$id] = $row;
            $id++;
        }
        return $results;
    }
}
