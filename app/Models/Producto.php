<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Producto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "productos";

    protected $fillable = ["nombre", "categoria_id"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    public static function cantidadDetalles($id){
        $detalles = DetallesProducto::where('producto_id', $id)->get();
        $result = count($detalles);
        return $result;
    }

    /**
     * Relacion uno a muchos
     * Un producto tiene varios detalles
     */
    public function detalles()
    {
        return $this->hasMany(DetallesProducto::class);
    }

    //Un producto tiene varias imagenes
    public function imagenes(){
        return $this->hasMany(ImagenesProducto::class);
    }

    //Un producto tiene varios codigos de cliente
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }

    /**
     * Uno o varios productos pertenecen a una categoría
     */
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relacion uno a muchos
     * Un producto tiene varios codigos
     */
    public function codigos(){
        return $this->hasMany(CodigoCliente::class);
    }
}
