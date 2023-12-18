<?php

namespace App\Models\Tareas;

use App\Models\Autorizacion;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TransferenciaProductoEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const PENDIENTE = 'PENDIENTE';
    const COMPLETA = 'COMPLETA';
    const ANULADA = 'ANULADA';

    public $table = 'tar_transferencias_productos_empleados';
    public $fillable = [
        'justificacion',
        'causa_anulacion',
        'estado',
        'observacion_aut',
        'solicitante_id',
        'empleado_origen_id',
        'empleado_destino_id',
        'tarea_origen_id',
        'tarea_destino_id',
        'autorizacion_id',
        'autorizador_id',
    ];

    private static $whiteListFilter = ['*'];

    public function tareaOrigen()
    {
        return $this->belongsTo(Tarea::class, 'tarea_origen_id', 'id');
    }

    public function tareaDestino()
    {
        return $this->belongsTo(Tarea::class, 'tarea_destino_id', 'id');
    }

    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function detallesTransferenciaProductoEmpleado()
    {
        return $this->belongsToMany(DetalleProducto::class, 'tar_transf_produc_emplea', 'transferencia_producto_empleado_id', 'detalle_producto_id')->withTimestamps();
    }
}
