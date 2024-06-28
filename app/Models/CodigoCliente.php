<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CodigoCliente extends Model // implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    // use AuditableModel;

    protected $table = "codigo_cliente";
    protected $fillable = ['nombre_cliente','cliente_id', 'detalle_id', 'codigo'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    
    private static $whiteListFilter = [
        '*',
    ];
    
    /**
     * Relacion uno a muchos (inversa)
     * Un codigo pertenece a un detalle
     */
    public function detalle(){
        return $this->belongsTo(DetalleProducto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Un cliente tiene varios codigos para varios productos
     */
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
