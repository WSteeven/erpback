<?php

namespace App\Models\FondosRotativos\Saldo;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Acreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'acreditaciones';
    protected $fillable = [
        'id_tipo_fondo',
        'id_tipo_saldo',
        'id_usuario',
        'fecha',
        'descripcion_saldo',
        'monto',
    ];
    private static $whiteListFilter = [
        'fecha',
    ];
    public function usuario()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_usuario');
    }
}
