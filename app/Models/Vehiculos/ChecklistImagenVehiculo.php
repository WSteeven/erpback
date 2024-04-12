<?php

namespace App\Models\Vehiculos;

use App\Models\Vehiculos\BitacoraVehicular;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ChecklistImagenVehiculo extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;

    public $table = 'veh_checklist_imagenes_vehiculos';
    public $fillable = [
        'bitacora_id',
        'imagen_frontal',
        'imagen_trasera',
        'imagen_lateral_derecha',
        'imagen_lateral_izquierda',
        'imagen_tablero_km',
        'imagen_tablero_radio',
        'imagen_asientos',
        'imagen_accesorios',
    ];

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
    public function bitacora()
    {
        return $this->belongsTo(BitacoraVehicular::class);
    }
}
