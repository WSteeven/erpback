<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Producto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = "productos";

    protected $fillable = ["nombre", "categoria_id"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public static function cantidadDetalles($id)
    {
        $detalles = DetalleProducto::where('producto_id', $id)->get();
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


    //Un producto tiene varios codigos de cliente
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }

    /**
     * Uno o varios productos pertenecen a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

}
