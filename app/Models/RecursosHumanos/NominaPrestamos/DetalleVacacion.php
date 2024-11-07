<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleVacacion extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_nomina_detalles_vacaciones';
    protected $fillable = [
      'vacacion_id',
      'fecha_inicio',
      'fecha_fin',
      'dias_utilizados',
      'vacacionable_id',
      'vacacionable_type',
      'observacion',
    ];

    public function vacacion()
    {
        return $this->belongsTo(Vacacion::class);
    }

    public function vacacionable(){
        return $this->morphTo();
    }

    public static function detalle()
    {

    }
}
