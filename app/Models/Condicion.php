<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Condicion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'condiciones_de_productos';
    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relacion uno a uno
     */
    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
    
    /**
     * Relación uno a muchos.
     * Una condicion puede estar en uno o muchos activos fijos.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }
}
