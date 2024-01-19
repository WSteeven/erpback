<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ClienteClaro extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_clientes_claro';
    protected $fillable = ['identificacion', 'nombres', 'apellidos', 'direccion', 'telefono1', 'telefono2', 'activo'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];


    private static $whiteListFilter = [
        '*',
    ];
}
