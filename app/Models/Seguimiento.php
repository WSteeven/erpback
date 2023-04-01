<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Seguimiento extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'seguimientos';

    protected $fillable = [
        // 'regional',
        // 'atencion',
        // 'tipo_intervencion',
        // 'causa_intervencion',
        // 'fecha_reporte_problema',
        // 'hora_reporte_problema',
        // 'fecha_arribo',
        // 'hora_arribo',
        // 'fecha_fin_reparacion',
        // 'hora_fin_reparacion',
        // 'fecha_retiro_personal',
        // 'hora_retiro_personal',
        // 'tiempo_espera_adicional',
        // 'estacion_referencia_afectacion',
        // 'distancia_afectacion',
        // 'trabajo_realizado',
        'observaciones',
        'materiales_ocupados', // tarea
        'materiales_stock_ocupados',
        'materiales_devolucion',
        // 'subtarea_id',
    ];

    protected $casts = [
        // 'trabajo_realizado' => 'json',
        'observaciones' => 'json',
        'materiales_ocupados' => 'json',
        'materiales_stock_ocupados' => 'json',
        'materiales_devolucion' => 'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    public function trabajoRealizado()
    {
        return $this->hasMany(TrabajoRealizado::class);
    }
}
