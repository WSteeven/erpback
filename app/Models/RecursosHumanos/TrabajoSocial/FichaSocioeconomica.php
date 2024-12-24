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

class FichaSocioeconomica extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_fichas_socioeconomicas';
    protected $fillable = [
        'empleado_id',
        'lugar_nacimiento',
        'canton_id',
        'contacto_emergencia',
        'parentesco_contacto_emergencia',
        'telefono_contacto_emergencia',
        'problemas_ambiente_social_familiar',
        'observaciones_ambiente_social_familiar',
        'conocimientos',
        'capacitaciones',
        'imagen_rutagrama',
        'vias_transito_regular_trabajo',
        'conclusiones',
    ];
    protected $casts = [
        'problemas_ambiente_social_familiar'=>'array',
        'conocimientos'=>'array',
        'capacitaciones'=>'array',
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

    public function conyuge()
    {
        return $this->hasOne(Conyuge::class,'ficha_id', 'id');
    }

    public function hijos()
    {
        return $this->hasMany(Hijo::class, 'ficha_id', 'id');
    }

    public function experienciaPrevia()
    {
        return $this->hasOne(ExperienciaPrevia::class, 'ficha_id', 'id');
    }

    public function vivienda()
    {
        return $this->morphOne(Vivienda::class, 'viviendable', 'model_type', 'model_id');
    }

    public function situacionSocioeconomica()
    {
        return $this->hasOne(SituacionSocioeconomica::class ,'ficha_id', 'id');
    }

    public function composicionFamiliar()
    {
        return $this->morphMany(ComposicionFamiliar::class, 'composicionable', 'model_type', 'model_id');
    }

    public function salud()
    {
        return $this->morphOne(Salud::class, 'saludable', 'model_type', 'model_id');
    }
}
