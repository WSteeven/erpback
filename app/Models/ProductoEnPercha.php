<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ProductoEnPercha
 *
 * @property int $id
 * @property int $stock
 * @property int $ubicacion_id
 * @property int $inventario_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Inventario|null $inventario
 * @property-read \App\Models\Ubicacion|null $ubicacion
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereInventarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereUbicacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductoEnPercha whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductoEnPercha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "productos_en_perchas";
    
    protected $fillable=[
        'ubicacion_id',
        'inventario_id',
        'stock',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a muchos (inversa).
     * Uno o más productos en percha pertenecen a una ubicación.
     */
    public function ubicacion(){
        return $this->belongsTo(Ubicacion::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Un producto del inventario puede estar en muchas ubicaciones.
     */
    public function inventario(){
        return $this->belongsTo(Inventario::class);
    }

    /**
     * Controlar que la cantidad de los productos en percha no sea superior a la del item del inventario.
     */
    public static function controlarCantidadInventario($inventario_id){
        $cantidad = 0;
        $productos = ProductoEnPercha::where('inventario_id', $inventario_id)->get();

        if($productos->isEmpty()) return -1;
        foreach($productos as $producto){
            $cantidad+=$producto->stock;
        }
        return $cantidad;
    }

}
