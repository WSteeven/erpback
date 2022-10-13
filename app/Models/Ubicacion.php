<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Ubicacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'ubicaciones';
    protected $fillable = ['codigo', 'percha_id', 'piso_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /**
     * Relacion uno a muchos (inversa)
     * Una percha tiene muchas ubicaciones
     */
    public function percha()
    {
        return $this->belongsTo(Percha::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Un ubicacion tiene varios pisos
     */
    public function piso()
    {
        return $this->belongsTo(Piso::class);
    }

    /**
     * Relación uno a muchos.
     * Una ubicación tiene muchos productos.
     */
    public function productosPercha()
    {
        return $this->hasMany(ProductoEnPercha::class);
    }

    /* Metodos */
    /**
     * Obtener la percha, fila y columna para generar el codigo de ubicacion
     */
    public static function obtenerCodigoUbicacionPerchaPiso($percha_id, $piso_id)
    {
        $percha = Percha::find($percha_id);
        $piso = Piso::find($piso_id);
        $codigo = $percha->sucursal_id . $percha->nombre . $piso->fila . $piso->columna;

        return $codigo;
    }
    public static function obtenerCodigoUbicacionPercha($percha_id)
    {
        $percha = Percha::find($percha_id);
        $codigo = $percha->sucursal_id . $percha->nombre;

        return $codigo;
    }
}
