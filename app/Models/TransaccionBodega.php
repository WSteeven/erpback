<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Raw;

class TransaccionBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'comprobante',
        'fecha_limite',
        'solicitante_id',
        'subtipo_id',
        'tarea_id',
        'subtarea_id',
        'sucursal_id',
        'per_autoriza_id',
        'per_atiende_id',
        'per_retira_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = ['*'];


    /*************************************
     * RELACIONES
     * ***********************************
     */
    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    public function autorizaciones()
    {
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizacion_transaccion', 'transaccion_id', 'autorizacion_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    public function estados()
    {
        return $this->belongsToMany(EstadoTransaccion::class, 'tiempo_estado_transaccion', 'transaccion_id', 'estado_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    //Una transaccion tiene varios productos solicitados
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_producto_transaccion', 'transaccion_id', 'detalle_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
    }
    /**
     * Relación uno a muchos.
     * Una transaccion tiene varios detalle_producto_transaccion.
     */
    public function detalleTransaccion()
    {
        return $this->hasMany(DetalleProductoTransaccion::class);
    }
    /**
     * Relación uno a muchos(inversa).
     * Una transacción de ingreso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación uno a muchos(inversa).
     * Una transacción pertenece a una sola subtarea
     */
    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una o varias transacciones pertenece a un solicitante 
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function atiende()
    {
        return $this->belongsTo(Empleado::class, 'per_atiende_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function retira()
    {
        return $this->belongsTo(Empleado::class, 'per_retira_id', 'id');
    }

    /* Una o varias transacciones tienen un solo tipo de transaccion*/
    public function subtipo()
    {
        return $this->belongsTo(SubtipoTransaccion::class);
    }
    /**
     * Obtener los movimientos para la transaccion
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }



    /****************************************
     * Funciones
     ****************************************
     */
    /**
     * Obtener la ultima autorizacion de una transaccion 
     */
    public static function ultimaAutorizacion($id)
    {
        $autorizaciones = TransaccionBodega::find($id)->autorizaciones()->get();
        $autorizacion = $autorizaciones->first();
        return $autorizacion;
    }
    /**
     * Obtener el ultimo estado de una transaccion 
     */
    public static function ultimoEstado($id)
    {
        $observaciones = TransaccionBodega::find($id)->estados()->get();
        $observacion = $observaciones->first();
        return $observacion;
    }

    /**
     * Obtener el listado de productos de una transaccion
     */
    public static function listadoProductos($id)
    {
        $detalles = TransaccionBodega::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidades'] = $detalle->pivot->cantidad_inicial;
            $row['despachado'] = $detalle->pivot->cantidad_final;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    /**
     * Esta función esta en "DESUSO"
     * Filtra las transacciones para enviarlas de acuerdo al estado seleccionado, valor que se recibe desde los tabsOptions del front.
     * @param Collection $transacciones
     * @param String $estado
     * 
     * @return Collection $transacciones listado de transacciones filtradas
     */
    public static function filtrarTransacciones($transacciones, $estado)
    {
        switch ($estado) {
            case 'ESPERA':
                return $transacciones->filter(fn ($transaccion) => self::ultimaAutorizacion($transaccion->id)->nombre === 'PENDIENTE'); // TransaccionBodega::ultimaAutorizacion($transaccion->id)->nombre === 'PENDIENTE');
            case 'PARCIAL':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === request('estado'));
            case 'PENDIENTE':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === request('estado') && self::ultimaAutorizacion($transaccion->id)->nombre === 'APROBADO');
            case 'COMPLETA':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === $estado);
            default:
                return $transacciones;
        }
    }



    /**
     * Función para obtener todas las columnas de la tabla.
     */
    /* public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    } */


    /* Filtros con paginación */
    public static function filtrarTransaccionesEmpleadoConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesCoordinadorConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="COMPLETA")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;

                /* $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO) */
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesBodegueroConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->simplePaginate($offset);
                return $results;
        }
    }
    /* Filtros sin paginación */
    public static function filtrarTransaccionesEmpleadoSinPaginacion($estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->filter()->get();
                return $results;
        }
    }
    public static function filtrarTransaccionesCoordinadorSinPaginacion($estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->filter()->get();
                return $results;
        }
    }
    public static function filtrarTransaccionesBodegueroSinPaginacion($estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            default:
                // Log::channel('testing')->info('Log', ['Estoy en el default y el estado es', $estado]);
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                    ->join('subtipos_transacciones', 'transacciones_bodega.subtipo_id', '=', 'subtipos_transacciones.id')
                    ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                    ->filter()->get();
                return $results;
        }
    }
}
