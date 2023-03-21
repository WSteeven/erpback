<?php

namespace App\Models\FondosRotativos\Saldo;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Transferencias extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'transferencias_saldos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'id_tarea',
        'estado',
        'comprobante'
    ];
    public function usuario_envia()
    {
        return $this->belongsTo('App\Models\User', 'usuario_envia_id');
    }
    public function usuario_recibe()
    {
        return $this->belongsTo('App\Models\User', 'usuario_recibe_id');
    }
    private static $whiteListFilter = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'id_tarea',
        'estado',
        'comprobante'
    ];
}
