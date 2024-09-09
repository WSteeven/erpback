<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Licencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'veh_licencias';
    protected $fillable = [
        'tipo_licencia',
        'inicio_vigencia',
        'fin_vigencia',
        'conductor_id'
    ];

    public function conductor()
    {
        return $this->belongsTo(Conductor::class);
    }

    public static function eliminarObsoletos($conductor_id, $tiposEncontrados)
    {
        $itemsNoEncontrados = Licencia::where('conductor_id', $conductor_id)
            ->whereNotIn('tipo_licencia', $tiposEncontrados)
            ->delete();
        Log::channel('testing')->info('Log', ['Eliminados', $itemsNoEncontrados]);
    }
}
