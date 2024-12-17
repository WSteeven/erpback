<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class Vivienda extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_viviendas';
    protected $fillable = [
        'empleado_id',
        'tipo',
        'material_paredes',
        'material_techo',
        'material_piso',
        'distribucion_vivienda',
        'comodidad_espacio_familiar',
        'numero_dormitorios',
        'existe_hacinamiento', //boolean
        'existe_upc_cercano', //boolean
        'otras_consideraciones',
        'imagen_croquis',
        'telefono',
        'coordenadas',
        'direccion',
        'referencia',
        'servicios_basicos',
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'servicios_basicos' => 'json',
        'distribucion_vivienda' => 'json',
        'existe_hacinamiento' => 'boolean',
        'existe_upc_cercano' => 'boolean',
    ];


    public function viviendable()
    {
        return $this->morphTo();
    }
}
