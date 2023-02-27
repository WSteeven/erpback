<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fibra extends Model
{
    use HasFactory;
    protected $table = 'fibras';
    protected $fillable = [
        'detalle_id',
        'span_id',
        'tipo_fibra_id',
        'hilo_id',
        'punta_inicial',
        'punta_final',
        'custodia',
    ];

    /**
     * Relacion uno a uno (inversa).
     * Una fibra es un detalle de producto
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalle_id');
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 span
     */
    public function span()
    {
        return $this->belongsTo(Span::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 tipo de fibra
     */
    public function tipo_fibra()
    {
        return $this->belongsTo(TipoFibra::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 hilo
     */
    public function hilo()
    {
        return $this->belongsTo(Hilo::class);
    }
}
