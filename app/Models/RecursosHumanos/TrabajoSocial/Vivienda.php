<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Models\TrabajoSocial\FamiliaAcogiente;
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
        'numero_plantas',
        'material_paredes',
        'material_techo',
        'material_piso',
        'distribucion_vivienda',
        'comodidad_espacio_familiar',
        'numero_dormitorios',
        'numero_personas',
        'existe_hacinamiento', //boolean
        'existe_upc_cercano', //boolean
        'tiene_donde_evacuar', //boolean
        'otras_consideraciones',
        'imagen_croquis',
        'telefono',
        'coordenadas',
        'direccion',
        'referencia',
        'servicios_basicos',
        'model_id',
        'model_type',

        'amenaza_inundacion',
        'amenaza_deslaves',
        'otras_amenazas_previstas',
        'otras_amenazas',
        'existe_peligro_tsunami',
        'existe_peligro_lahares',
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

    public function familiaAcogiente()
    {
        return $this->hasOne(FamiliaAcogiente::class);
    }
}
