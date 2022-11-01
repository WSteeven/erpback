<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PrestamoTemporal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    public $table = 'prestamos_temporales';
    public $fillable = [
        'fecha_salida',
        'fecha_devolucion',
        'observacion',
        'solicitante_id',
        'per_entrega_id',
        'per_recibe_id',
        'estado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const PENDIENTE = 'PENDIENTE';
    const DEVUELTO = 'DEVUELTO';

    /**
     * Relación muchos a muchos 
     */
    public function detalles(){
        return $this->belongsToMany(Inventario::class, 'inventario_prestamo_detalles', 'prestamo_id', 'inventario_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function solicitante(){
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function entrega(){
        return $this->belongsTo(Empleado::class, 'per_entrega_id', 'id');
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function recibe(){
        return $this->belongsTo(Empleado::class, 'per_recibe_id', 'id');
    }

}
