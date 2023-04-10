<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class MovilizacionSubtarea extends Model
{
    use HasFactory, Filterable;

    // Motivos de movilizacion
    const IDA_A_TRABAJO = 'IDA';
    const REGRESO_DE_TRABAJO = 'REGRESO';

    public $timestamps = false;
    protected $table = "movilizacion_subtarea";
    protected $fillable = [
        'fecha_hora_salida',
        'fecha_hora_llegada',
        'motivo',
        'latitud',
        'longitud',
        'latitud_llegada',
        'longitud_llegada',
        'estado_subtarea_llegada',
        'coordinador_registrante_llegada',
        'empleado_id',
        'subtarea_id'
    ];

    private static $whiteListFilter = [
        '*'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class);
    }

    public function subtarea() {
        return $this->belongsTo(Subtarea::class);
    }

    public function coordinadorRegistranteLlegada() {
        return $this->belongsTo(Empleado::class, 'coordinador_registrante_llegada', 'id');
    }
}
