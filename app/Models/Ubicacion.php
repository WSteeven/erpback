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
    protected $fillable = ['codigo','percha_id','piso_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    public function percha()
    {
        return $this->belongsTo(Percha::class);
    }

    public function inventario()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }

    /* Metodos */
    /**
     * Obtener la percha, fila y columna para generar el codigo de ubicacion
     */
    public static function obtenerCodigoUbicacion($percha_id, $piso_id)
    {
        $percha = Percha::find($percha_id);
        $piso = Piso::find($piso_id);
        $codigo = $percha->nombre . $piso->fila . $piso->columna;

        return $codigo;
    }
}
