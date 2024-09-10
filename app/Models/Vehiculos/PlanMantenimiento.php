<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @method static where(string $string, $vehiculo_id)
 */
class PlanMantenimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'veh_planes_mantenimientos';
    protected $fillable = [
        'vehiculo_id',
        'servicio_id',
        'aplicar_desde',
        'aplicar_cada',
        'notificar_antes',
        'datos_adicionales',
        'activo',
    ];

    protected array $auditInclude = ['*'];

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function eliminarObsoletos($vehiculo_id, $ids_servicios)
    {
        $itemsNoEncontrados = PlanMantenimiento::where('vehiculo_id', $vehiculo_id)
            ->whereNotIn('servicio_id', $ids_servicios)->delete();
        Log::channel('testing')->info('Log', ['items para eliminar', $itemsNoEncontrados]);
    }
}
