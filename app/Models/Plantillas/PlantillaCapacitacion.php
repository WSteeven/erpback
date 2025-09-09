<?php

namespace App\Models\Plantillas;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PlantillaCapacitacion extends Model
{
    use HasFactory;

    protected $table = 'cap_plantilla_capacitaciones';

    protected $fillable = [
        'tema',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'modalidad',
        'capacitador_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Relación: el capacitador es un empleado
    public function capacitador()
    {
        return $this->belongsTo(Empleado::class, 'capacitador_id');
    }

    //  Relación: asistentes (muchos a muchos con empleados)
    public function asistentes()
    {
        return $this->belongsToMany(Empleado::class, 'cap_capacitacion_empleado')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    //  Calcular duración automáticamente (horas:minutos)
    public function getDuracionAttribute()
    {
        if (!$this->hora_inicio || !$this->hora_fin) {
            return null;
        }

        $inicio = Carbon::parse($this->hora_inicio);
        $fin = Carbon::parse($this->hora_fin);

        return $inicio->diff($fin)->format('%h horas %i minutos');
    }
}
