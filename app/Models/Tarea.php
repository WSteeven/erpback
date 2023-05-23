<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Support\Facades\Auth;

class Tarea extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, UppercaseValuesTrait;

    const PARA_PROYECTO = 'PARA_PROYECTO';
    const PARA_CLIENTE_FINAL = 'PARA_CLIENTE_FINAL';

    const CORREO = 'CORREO';
    const LLAMADA = 'LLAMADA';
    const CHAT = 'CHAT';

    // ubicacionTrabajo
    const CLIENTE_FINAL = 'CLIENTE_FINAL';
    const RUTA = 'RUTA';

    protected $table = 'tareas';
    protected $fillable = [
        'codigo_tarea',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'titulo',
        'para_cliente_proyecto',
        'ubicacion_trabajo',
        'medio_notificacion',
        'observacion',
        'novedad',
        'imagen_informe',
        'finalizado',
        'proyecto_id',
        'coordinador_id',
        'fiscalizador_id',
        'cliente_id',
        'cliente_final_id',
        'ruta_tarea_id',
    ];

    protected $casts = ['finalizado' => 'boolean'];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function fiscalizador()
    {
        return $this->belongsTo(Empleado::class, 'fiscalizador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede una o varias devoluciones
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede uno o varios traspasos
     */
    public function traspasos()
    {
        return $this->hasMany(Traspaso::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }


    // Relacion uno a uno (inversa)
    public function ubicacionTarea()
    {
        // return $this->belongsTo(UbicacionTarea::class);
        return $this->hasOne(UbicacionTarea::class);
    }

    // Relacion uno a uno (inversa)
    public function clienteFinal()
    {
        return $this->belongsTo(ClienteFinal::class);
    }

    public function rutaTarea()
    {
        return $this->belongsTo(RutaTarea::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function esPrimeraAsignacion($subtarea_id)
    {
        $subtareaEncontrada = $this->subtareas()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->first();
        return $subtareaEncontrada?->id == $subtarea_id;
    }


    /**
     * Relación uno a muchos .
     * Una tarea puede uno o varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /*********
     * Scopes
     *********/
    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }

    public function scopeOrderByAgendadoDesc($query)
    {
        return $query->orderBy('fecha_hora_agendado', 'desc');
    }
}
