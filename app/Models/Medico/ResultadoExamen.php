<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;

class ResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_examenes';
    protected $fillable = [
        'resultado',
        'fecha_examen',
        'configuracion_examen_campo_id',
        // 'estado_solicitud_examen_id',
        'detalle_resultado_examen_id',
    ];

    public function configuracionExamenCampo()
    {
        return $this->hasOne(ConfiguracionExamenCampo::class); //, 'id', 'configuracion_examen_id');
    }

    /*public function estadoSolicitudExamen()
    {
        return $this->hasOne(EstadoSolicitudExamen::class); //, 'id', 'empleado_id');
    }*/

    public function detalleResultadoExamen()
    {
        return $this->belongsTo(DetalleResultadoExamen::class);
    }
}
