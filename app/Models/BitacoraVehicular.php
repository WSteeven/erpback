<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class BitacoraVehicular extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'bitacora_vehiculos';
    protected $fillable = [
        'fecha',
        'hora_salida',
        'hora_llegada',
        'km_inicial',
        'km_final',
        'tanque_inicio',
        'tanque_final',
        'firmada',
        'chofer_id',
        'vehiculo_id',
    ];
    public $incrementing = true;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];
}
