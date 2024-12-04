<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class FichaSocioeconomica extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_fichas_socioeconomicas';
    protected $fillable = [
        'empleado_id',
        // others fields

    ];

    private static array $whiteListFilter = ['*'];


    /*******************************
     * Relaciones con otras tablas
     ******************************/
    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }
}
