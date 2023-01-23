<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;

class Emergencia extends Model
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;

    protected $table = 'emergencias';

    protected $fillable = [
        'regional',
        'atencion',
        'tipo_intervencion',
        'causa_intervencion',
        'fecha_reporte_problema',
        'hora_reporte_problema',
        'fecha_arribo',
        'hora_arribo',
        'fecha_fin_reparacion',
        'hora_fin_reparacion',
        'fecha_retiro_personal',
        'hora_retiro_personal',
        'tiempo_espera_adicional',
        'estacion_referencia_afectacion',
        'distancia_afectacion',
        'trabajo_realizado',
        'observaciones',
        'materiales_ocupados',
        'subtarea_id',
    ];
}
