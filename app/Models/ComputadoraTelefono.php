<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
