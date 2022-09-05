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
        'tipo_fibra_id',
        'hilo_id',
        'punta_a',
        'punta_b',
        'punta_corte',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /* Un producto puede estar en muchas perchas en distintas ubicaciones */
    public function productosPercha()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }

    /* Uno o varios productos pertenecen a un nombre general */
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
