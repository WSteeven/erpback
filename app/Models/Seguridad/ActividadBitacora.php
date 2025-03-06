<?php

namespace App\Models\Seguridad;

use App\Models\Archivo;
use App\Models\Intranet\TipoEvento;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ActividadBitacora extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_actividades_bitacora';
    protected $fillable = [
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'notificacion_inmediata',
        'actividad',
        'ubicacion',
        'fotografia_evidencia_1',
        'fotografia_evidencia_2',
        'medio_notificacion',
        'tipo_evento_bitacora_id',
        'bitacora_id',
    ];

    private static array $whiteListFilter = ['*'];

    protected $casts = [
        'notificacion_inmediata' => 'boolean',
    ];

    public function tipoEventoBitacora()
    {
        return $this->belongsTo(TipoEventoBitacora::class);
    }

    public function visitante()
    {
        return $this->hasOne(Visitante::class);
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
