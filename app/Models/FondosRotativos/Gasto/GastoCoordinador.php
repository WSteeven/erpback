<?php

namespace App\Models\FondosRotativos\Gasto;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class GastoCoordinador extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'gastos_coordinador';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha_gasto',
        'id_lugar',
        'id_motivo',
        'monto',
        'observacion',
        'id_usuario',
    ];
    private static $whiteListFilter = [
        'fecha_gasto',
        'lugar',
        'id_motivo',
        'monto',
        'observacion',
        'id_usuario',
    ];
    public function motivo()
    {
        return $this->hasOne(MotivoGasto::class, 'id_motivo','id');
    }
    public function usuario()
    {
        return $this->hasOne(User::class, 'id_usuario','id');
    }
}
