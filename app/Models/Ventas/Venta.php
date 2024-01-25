<?php

namespace App\Models\Ventas;

use App\Models\Archivo;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Models\Ventas\Vendedor;
use Illuminate\Support\Facades\Mail;

class Venta  extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_ventas';
    protected $fillable = [
        'orden_id', 'orden_interna',
        'supervisor_id',
        'vendedor_id',
        'producto_id',
        'fecha_activacion',
        'estado_activacion',
        'forma_pago',
        'comision_id',
        'chargeback',
        'comision_vendedor',
        'cliente_id',
        'activo',
        'observacion',
        'primer_mes',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
        'primer_mes' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function novedadesVenta()
    {
        return $this->hasMany(NovedadVenta::class);
    }
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

    public static function enviarMailVendedor($vendedor_id, $supervisor_id, $cliente_id){
        $empleado = Empleado::find($vendedor_id);
        $supervisor= Empleado::find($supervisor_id);
        Mail::to($empleado->user->email)->cc($supervisor->user->email)->send(new  );
    }
}
