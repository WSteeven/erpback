<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\User;
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
    public function motivo_info()
    {
        return $this->hasOne(MotivoGasto::class, 'id','id_motivo');
    }
    public function usuario_info()
    {
        return $this->hasOne(User::class, 'id','id_usuario');
    }
    public function lugar_info()
    {
        return $this->hasOne(Canton::class, 'id','id_lugar');
    }
}
