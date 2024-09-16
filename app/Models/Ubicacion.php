<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Ubicacion
 *
 * @property int $id
 * @property string $codigo
 * @property int $percha_id
 * @property int|null $piso_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Percha|null $percha
 * @property-read \App\Models\Piso|null $piso
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductoEnPercha> $productosPercha
 * @property-read int|null $productos_percha_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion wherePerchaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion wherePisoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ubicacion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
