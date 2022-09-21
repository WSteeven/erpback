<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CodigoCliente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = "codigo_cliente";
    protected $fillable = ['cliente_id', 'producto_id', 'codigo'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    
    /**
     * Relacion uno a muchos (inversa)
     * Un producto tiene varios codigos
     */
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Un cliente tiene varios codigos para varios productos
     */
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
