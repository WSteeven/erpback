<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $table = 'cargos';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombres',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function toSearchableArray()
    {
        return [
            'nombres' => $this->nombres,
        ];
    }

    
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a uno (inversa).
     * Un cargo pertenece a un empleado.
     */
    public function empleados()
    {
        return $this->belongsTo(Empleado::class);
    }
}
