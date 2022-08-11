<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = "productos";
    // estado
    const ACTIVO = "ACTIVO";
    const INACTIVO = "INACTIVO";


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'codigo_barras',
        'nombre_id',
        'descripcion',
        'modelo_id',
        'precio',
        'serial',
        'categoria_id',
        'estado'
    ];

    /* Un producto puede estar en muchas perchas en distintas ubicaciones */
    public function productosPercha()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }

    /* Un producto especifico pertenece a un nombre general */
    public function nombre()
    {
        return $this->belongsTo(NombresProductos::class);
    }

    /* Uno o varios productos pertenecen a una misma categoria */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /* Un producto tiene un solo modelo */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
