<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class BitacoraVehicular extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'bitacora_vehiculos';
    protected $fillable = [
        'fecha',
        'hora_salida',
        'hora_llegada',
        'km_inicial',
        'km_final',
        'tanque_inicio',
        'tanque_final',
        'firmada',
        'chofer_id',
        'vehiculo_id',
    ];
    public $incrementing = true;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function chofer(){
        return $this->belongsTo(Empleado::class);
    }
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    public static function crearBitacora($data)
    {
        $bitacora = new BitacoraVehicular([
            'fecha' => $data['fecha'],
            'hora_salida' => $data['hora_salida'],
            'hora_llegada' => $data['hora_llegada'],
            'km_inicial' => $data['km_inicial'],
            'km_final' => $data['km_final'],
            'tanque_inicio' => $data['tanque_inicio'],
            'tanque_final' => $data['tanque_final'],
            'firmada' => $data['firmada'],
            // 'chofer_id' => $data['chofer_id'],
            'vehiculo_id' => $data['vehiculo_id'],
        ]);

        return $bitacora;
    }
}
