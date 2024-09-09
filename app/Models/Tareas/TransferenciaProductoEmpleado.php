<?php

namespace App\Models\Tareas;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Proyecto;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;

class TransferenciaProductoEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    const PENDIENTE = 'PENDIENTE';
    const COMPLETA = 'COMPLETA';
    const ANULADA = 'ANULADA';

    public $table = 'tar_transf_produc_emplea';
    public $fillable = [
        'justificacion',
        'causa_anulacion',
        // 'estado',
        'observacion_aut',
        'solicitante_id',
        'empleado_origen_id',
        'empleado_destino_id',
        'proyecto_origen_id',
        'proyecto_destino_id',
        'etapa_origen_id',
        'etapa_destino_id',
        'tarea_origen_id',
        'tarea_destino_id',
        'autorizacion_id',
        'autorizador_id',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    public function empleadoDestino()
    {
        return $this->belongsTo(Empleado::class, 'empleado_destino_id', 'id');
    }

    public function proyectoOrigen()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_origen_id', 'id');
    }

    public function proyectoDestino()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_destino_id', 'id');
    }

    public function etapaOrigen()
    {
        return $this->belongsTo(Etapa::class, 'etapa_origen_id', 'id');
    }

    public function etapaDestino()
    {
        return $this->belongsTo(Etapa::class, 'etapa_destino_id', 'id');
    }

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
        return $this->belongsToMany(DetalleProducto::class, 'tar_det_tran_prod_emp', 'transf_produc_emplea_id', 'detalle_producto_id')->withPivot('cantidad')->withTimestamps();
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /************
     * Funciones
     ************/
    public function listadoProductos() //int $id)
    {
        // $detalles = TransferenciaProductoEmpleado::find($id)->detallesTransferenciaProductoEmpleado()->get();
        $detalles = $this->detallesTransferenciaProductoEmpleado()->get();
        Log::channel('testing')->info('Log', compact('detalles'));
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            // $condicion= $detalle->pivot->condicion_id? Condicion::find($detalle->pivot->condicion_id):null;
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['serial'] = $detalle->serial;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['cliente_id'] = $this->cliente_id; ///$detalle->pivot->cliente_id;
            // $row['condiciones'] = $condicion?->nombre;
            $row['observacion'] = $detalle->pivot->observacion;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
}
