<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Piso extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;
    
    protected $table = 'pisos';
    protected $fillable = ['fila','columna'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = [
        '*',
    ];


    /* public function perchas()
    {
        return $this->belongsToMany(Percha::class);
    } */

    /**
     * Relacion uno a muchos
     * Varias ubicaciones en un piso
     */
    public function ubicaciones(){
        return $this->hasMany(Ubicacion::class);
    }
}
