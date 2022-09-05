<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ProductoEnPercha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "inventarios";
    const INVENTARIO = "INVENTARIO";
    const NODISPONIBLE = "NO DISPONIBLE";

    protected $fillable=[
        'producto_id',
        'condicion_id',
        'ubicacion_id',
        'propietario_id',
        'stock',
        'prestados',
        'estado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


}
