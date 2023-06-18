<?php

namespace App\Models;

use App\Models\RecursosHumanos\Area;
use App\Models\RecursosHumanos\Banco;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Empleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, Searchable;

    protected $table = "empleados";
    protected $fillable = [
        'identificacion',
        'nombres',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'jefe_id',
        'canton_id',
        'estado',
        'grupo_id',
        'cargo_id',
        'departamento_id',
        'es_tecnico',
        'firma_url',
        'foto_url',
        'convencional',
        'telefono_empresa',
        'extension',
        'coordenadas',
        'casa_propia',
        'vive_con_discapacitados',
        'responsable_discapacitados',
    ];

    private static $whiteListFilter = [
        'id',
        'identificacion',
        'nombres',
        'apellidos',
        'telefono',
        'fecha_nacimiento',
        'jefe_id',
        'canton_id',
        'grupo_id',
        'cargo_id',
        'departamento_id',
        'estado',
        'es_tecnico',
    ];

    const ACTIVO = 'ACTIVO';
    const INACTIVO = 'INACTIVO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_responsable_grupo' => 'boolean',
        'estado' => 'boolean',
        'casa_propia' => 'boolean',
        'vive_con_discapacitados' => 'boolean',
        'responsable_discapacitados' => 'boolean',
    ];

    public function toSearchableArray()
    {
        return [
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'identificacion' => $this->identificacion,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    //Relacion uno a muchos polimorfica
    public function telefonos()
    {
        return $this->morphMany('App\Models\Telefono', 'telefonable');
    }

    /**
     * Obtiene el usuario que posee el perfil.
     */
    // Relacion uno a uno (inversa)
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    // Relacion muchos a muchos
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o más empleados pertenecen a una sede o canton.
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    // Relacion uno a uno
    public function jefe()
    {
        return $this->belongsTo(Empleado::class, 'jefe_id');
    }

    /**
     * Relación uno a muchos.
     * Un empleado tiene uno o muchos activos fijos en custodia.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol superior o igual a COORDINADOR puede autorizar todas las transacciones de sus empleados a cargo
     */
    public function autorizadas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol BODEGA puede entregar cualquier transaccion
     */
    public function atendidas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado puede retirar cualquier transaccion asignada
     */
    public function retiradas()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado puede hacer muchas devoluciones de materiales
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }

    /* public function subtareas()
    {
        return $this->belongsToMany(Subtarea::class);e
    } */
    /**
     * Relacion uno a muchos.
     * Un empleado BODEGUERO puede registrar muchos movimientos
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Realación muchos a muchos.
     * Un empleado registra varias bitacoras
     */
    public function bitacoras()
    {
        return $this->belongsToMany(Vehiculo::class, 'bitacora_vehiculos', 'chofer_id', 'vehiculo_id')
            ->withPivot('fecha', 'hora_salida', 'hora_llegada', 'km_inicial', 'km_final', 'tanque_inicio', 'tanque_final', 'firmada')->withTimestamps();
    }
    public function ultimaBitacora(){
        return $this->hasOne(BitacoraVehicular::class, 'chofer_id', 'id')->latestOfMany();
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transferencias
     */
    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'responsable_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Un empleado tiene solo un cargo.
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
    /**
     * Relación uno a uno.
     * Un empleado pertenece a una sola area.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    /**
     * Relación uno a uno.
     * Un empleado tiene un solo estado civil.
     */
    public function estadoCivil()
    {
        return $this->belongsTo(EstadoCivil::class);
    }
        /**
     * Relación uno a uno.
     * Un empleado tiene uncuente aen un banco.
     */
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
         /**
     * Relación uno a uno.
     * Un empleado tiene solo tipo de contrato
     */
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class);
    }
         /**
     * Relación uno a uno.
     * Un empleado tiene solo tipo de sangre
     */



    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function tareasCoordinador()
    {
        return $this->hasMany(Tarea::class, 'coordinador_id');
    }

    public function subtareasCoordinador(): HasManyThrough
    {
        return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
        //Log::channel('testing')->info('Log', ['Coordinador: ', $coordinador]);
        //return DB::table('subtareas')->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->where('tareas.coordinador_id', 3);
    }

    public static function extraerNombresApellidos(Empleado $empleado)
    {
        // if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }
}
