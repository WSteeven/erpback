<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
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
    use UppercaseValuesTrait;
    protected $table = 'transferencias_saldos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'observacion',
        'id_tarea',
        'estado',
        'comprobante',
        'fecha'
    ];
    public const APROBADO = 1;
    public const RECHAZADO = 2;
    public const PENDIENTE = 3;
    public const ANULADO = 4;

    protected $casts = [
        'es_devolucion' => 'boolean',
    ];

    public function empleadoEnvia()
    {
        return $this->belongsTo(Empleado::class, 'usuario_envia_id');
    }
    public function estadoViatico()
    {
        return $this->hasOne(EstadoViatico::class, 'id','estado');
    }
    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id','id_tarea');
    }
    public function empleadoRecibe()
    {
        return $this->belongsTo(Empleado::class, 'usuario_recibe_id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    public function saldoFondoRotativo()
    {
        return $this->morphMany(Saldo::class, 'saldoable');
    }
    private static $whiteListFilter = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'observacion',
        'id_tarea',
        'estado',
        'comprobante',
        'fecha'
    ];
}
