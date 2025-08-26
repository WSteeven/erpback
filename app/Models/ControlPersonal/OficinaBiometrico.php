<?php

namespace App\Models\ControlPersonal;

use App\Models\Canton;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class OficinaBiometrico extends Model implements  Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_cp_oficinas_biometricos';
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'latitud',
        'longitud',
        'direccion_ip',
        'puerto', //opcional
        'canton_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];


    private static array $whiteListFilter = ['*'];

    public function canton()
    {
        return $this->belongsTo(Canton::class, 'canton_id');
    }
}
