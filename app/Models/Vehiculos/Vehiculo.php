<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Vehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'vehiculos';
    protected $fillable = [
        'placa',
        'num_chasis',
        'num_motor',
        'anio_fabricacion',
        'cilindraje',
        'rendimiento',
        'traccion',
        'aire_acondicionado',
        'capacidad_tanque',
        'modelo_id',
        'combustible_id',
    ];

    //Tracciones
    const SENCILLA_DELANTERA='4X2 FWD';
    const SENCILLA_TRASERA='4X2 RWD';
    const AWD='AWD';
    const FOUR_WD='4WD';
    const TODOTERRENO='4X4';
    
    //Transmisiones
    // const MANUAL='MANUAL';
    // const AUTOMATICA='AUTOMATICA';
    // const SECUENCIAL='SECUENCIAL';
    // const CVT='CONITNUA VARIABLE (CVT)';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'aire_acondicionado' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     */
    public function combustible()
    {
        return $this->belongsTo(Combustible::class);
    }

    /**
     * Relación uno a muchos (inversa).
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
    /**
     * Realación muchos a muchos.
     * Un vehículo tiene varias bitacoras
     */
    public function bitacoras(){
        return $this->belongsToMany(Empleado::class, 'bitacora_vehiculos', 'vehiculo_id', 'chofer_id')
        ->withPivot('fecha','hora_salida','hora_llegada', 'km_inicial', 'km_final','tanque_inicio', 'tanque_final', 'firmada')->withTimestamps();
    }
}
