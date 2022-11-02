<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InventarioPrestamoTemporal extends Pivot
{
    use HasFactory;
    protected $table = 'inventario_prestamo_temporal';
    public $fillable = [
        'prestamo_id',
        'inventario_id',
        'cantidad',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
