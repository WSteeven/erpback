<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PagoProveedores extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    public $table = 'cmp_pagos_proveedores';
    public $fillable = [
        'nombre',
        'realizador_id',
        'estado_bloqueado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado_bloqueado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    public function realizador()
    {
        return $this->belongsTo(Empleado::class, 'realizador_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(ItemPagoProveedores::class, 'pago_proveedor_id', 'id');
    }

    public static function listadoElementos(int $id)
    {
        // $elementos = 
        // Log::channel('testing')->info('Log', ['Error en metodo', $pago]);
    }
}
