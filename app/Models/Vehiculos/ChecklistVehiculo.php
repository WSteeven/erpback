<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ChecklistVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    public $table = 'veh_checklist_vehiculos';
    public $fillable = [
        'bitacora_id',
        'parabrisas',
        'limpiaparabrisas',
        'luces_interiores',
        'aire_acondicionado',
        'aceite_motor',
        'liquido_freno',
        'aceite_hidraulico',
        'liquido_refrigerante',
        'filtro_combustible',
        'bateria',
        'agua_plumas_radiador',
        'cables_conexiones',
        'luces_exteriores',
        'frenos',
        'amortiguadores',
        'llantas',
        'observacion_checklist_interior',
        'observacion_checklist_bajo_capo',
        'observacion_checklist_exterior',
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
