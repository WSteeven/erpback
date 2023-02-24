<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Laravel\Scout\Searchable;

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
        'es_tecnico',
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
        'estado',
        'es_tecnico',
    ];

    const ACTIVO = 'ACTIVO';
    const INACTIVO = 'INACTIVO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
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
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transferencias
     */
    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }

    public function subtareas()
    {
        return $this->belongsToMany(Subtarea::class);
    }

    /**
     * Relación uno a uno.
     * Un empleado tiene solo un cargo.
     */
    public function cargo(){
        return $this->belongsTo(Cargo::class);
    }
}
