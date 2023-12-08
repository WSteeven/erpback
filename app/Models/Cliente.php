<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Cliente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "clientes";
    protected $fillable = ['empresa_id', 'parroquia_id', 'requiere_bodega', 'estado', 'logo_url'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'requiere_bodega' => 'boolean',
        'estado' => 'boolean'
    ];


    const JEANPATRICIO = 1;
    const JPCONSTRUCRED = 5;

    private static $whiteListFilter = ['*'];

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    /**
     * Relacion uno a uno (inversa)
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
    /**
     * Relacion uno a muchos
     * Un cliente tiene varios codigos para varios productos
     */
    public function codigos()
    {
        return $this->hasOne(CodigoCliente::class);
    }

    /**
     * Relacion uno a muchos
     * Un cliente es propietario de muchos items del inventario
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    /**
     * Relacion muchos a muchos.
     * Un cliente tiene varios detalles_productos en inventario.
     */
    public function detalles(){
        return $this->belongsToMany(DetalleProducto::class, 'inventarios', 'cliente_id', 'detalle_id');
    }

    /**
     * Relación uno a muchos.
     * Un cliente tiene un producto al que se le hace un control de stock para estar pendiente de su reabastecimiento
     */
    public function controlStock()
    {
        return $this->hasMany(ControlStock::class);
    }
}
