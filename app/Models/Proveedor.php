<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Proveedor extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = "proveedores";
    protected $fillable = [
        "empresa_id",
        "estado",
        "sucursal",
        "parroquia_id",
        "direccion",
        "celular",
        "telefono",
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function parroquia(){
        return $this->belongsTo(Parroquia::class);
    }
    public function servicios_ofertados(){
        return $this->belongsToMany(OfertaProveedor::class, 'detalle_oferta_proveedor','oferta_id','proveedor_id')
        ->withTimestamps();
    }
    public function categorias_ofertadas(){
        return $this->belongsToMany(Categoria::class, 'detalle_categoria_proveedor','categoria_id','proveedor_id')
        ->withTimestamps();
    }

}
