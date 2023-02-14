<?php

namespace App\Models\FondosRotativos\Viatico;

use App\Models\FondosRotativos\Saldo\TipoSaldo;
use App\Models\User;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SaldoGrupo extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'saldo_grupo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'id_tipo_saldo',
        'id_saldo',
        'id_tipo_fondo',
        'descripcion_saldo',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
    ];
    private static $whiteListFilter = [
        'fecha_inicio',
    ];
    public function tipo_saldo()
    {
        return $this->hasOne(TipoSaldo::class, 'id','id_tipo_saldo');
    }
    public function tipo_fondo()
    {
        return $this->hasOne(TipoFondo::class, 'id','id_tipo_fondo');
    }
    public function estatus()
    {
        return $this->hasOne(EstadoViatico::class, 'id','id_estatus');
    }
    public function usuario()
    {
        return $this->hasOne(User::class, 'id','id_usuario');
    }
}
