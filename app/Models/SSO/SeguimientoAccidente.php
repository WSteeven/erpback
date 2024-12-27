<?php

namespace App\Models\SSO;

use App\Models\Archivo;
use App\Models\Subtarea;
use App\Models\Tarea;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create(array $array)
 * @method static find(int $seguimiento_accidente_id)
 */
class SeguimientoAccidente extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'sso_seguimiento_accidentes';
    protected $fillable = [
        'condiciones_climatologicas',
        'condiciones_laborales',
        'autorizaciones_permisos_texto',
        'autorizaciones_permisos_foto',
        'se_notifica_riesgos_trabajo',
        'actividades_desarrolladas',
        'descripcion_amplia_accidente',
        'antes_accidente',
        'instantes_previos',
        'durante_accidente',
        'despues_accidente',
        'hipotesis_causa_accidente',
        'causas_inmediatas',
        'causas_basicas',
        'medidas_preventivas',
        'seguimiento_sso',
        'seguimiento_trabajo_social',
        'seguimiento_rrhh',
        'metodologia_utilizada',
        'subtarea_id',
        'accidente_id',
    ];

    private static array $whiteListFilter = ['*'];

    protected $casts = [
        'se_notifica_riesgos_trabajo' => 'boolean',
    ];

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class);
    }

    public function accidente()
    {
        return $this->belongsTo(Accidente::class);
    }
}
