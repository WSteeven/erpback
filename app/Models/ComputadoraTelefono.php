<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComputadoraTelefono
 *
 * @property int $detalle_id
 * @property int $memoria_id
 * @property int $disco_id
 * @property int $procesador_id
 * @property string|null $imei
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleProducto $detalle
 * @property-read \App\Models\Disco $disco
 * @property-read \App\Models\Ram $memoria
 * @property-read \App\Models\Procesador $procesador
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereDiscoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereMemoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereProcesadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComputadoraTelefono whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ComputadoraTelefono extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use AuditableModel;


    protected $table='computadoras_telefonos';
    protected $fillable=[
        'detalle_id',
        'memoria_id',
        'disco_id',
        'procesador_id',
        'imei',
    ];

    //obtener la llave primaria
    public function getKeyName()
    {
        return 'detalle_id';
    }


    /**
     * Relación uno a uno.
     * Una computadora o telefono es un detalle de producto.
     */
    public function detalle(){
        return $this->belongsTo(DetalleProducto::class, 'detalle_id');
    }

    /**
     * Relación uno a muchos(inversa).
     * Una computadora o telefono tiene una ram.
     */
    public function memoria(){
        return $this->belongsTo(Ram::class);
    }

    /**
     * Relación uno a muchos(inversa).
     * Una computadora o telefono tiene un disco.
     */
    public function disco(){
        return $this->belongsTo(Disco::class);
    }
    /**
     * Relación uno a muchos(inversa).
     * Una computadora o telefono tiene un procesador.
     */
    public function procesador(){
        return $this->belongsTo(Procesador::class);
    }

}
