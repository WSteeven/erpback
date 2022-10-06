<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Span extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'spans';
    protected $fillable = ['nombre'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a uno.
     * Uno o más detalles de productos que sean fibra tienen un solo span
     */
    public function detalle()
    {
        return $this->hasOne(DetalleProducto::class);
    }
}
