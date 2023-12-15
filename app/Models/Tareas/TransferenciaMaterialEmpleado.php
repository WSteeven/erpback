<?php

namespace App\Models\Tareas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TransferenciaMaterialEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const PENDIENTE = 'PENDIENTE';
    const COMPLETA = 'COMPLETA';
    const ANULADA = 'ANULADA';

    public $table = 'tar_transferencias_materiales_empleados';
    public $fillable = [
        'justificacion',
        'solicitante_id',
        'tarea_id',
        'etapa_id',
        'proyecto_id',
        'observacion_aut',
        'autorizacion_id',
        'per_autoriza_id',
        'canton_id',
        'sucursal_id',
        'stock_personal',
        'causa_anulacion',
        'estado',
        'estado_bodega',
        'pedido_automatico',
    ];
}
