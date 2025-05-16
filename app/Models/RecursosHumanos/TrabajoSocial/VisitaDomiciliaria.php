<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Models\Canton;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class VisitaDomiciliaria extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_visitas_domiciliarias';

    protected $fillable = [
        'empleado_id',
        'lugar_nacimiento',
        'canton_id',
        'contacto_emergencia',
        'parentesco_contacto_emergencia',
        'telefono_contacto_emergencia',
        'diagnostico_social',
        'imagen_genograma',
        'imagen_visita_domiciliaria',
        'observaciones',
    ];

    private static array $whiteListFilter = ['*'];

    /*******************************
     * Relaciones con otras tablas
     ******************************/
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function vivienda()
    {
        return $this->morphOne(Vivienda::class, 'viviendable', 'model_type', 'model_id');
    }

    public function composicionFamiliar()
    {
        return $this->morphMany(ComposicionFamiliar::class, 'composicionable', 'model_type', 'model_id');
    }

    public function salud()
    {
        return $this->morphOne(Salud::class, 'saludable', 'model_type', 'model_id');
    }


    public function economiaFamiliar()
    {
        return $this->hasOne(EconomiaFamiliar::class, 'visita_id', 'id');
    }
}
