<?php

namespace App\Models\ComprasProveedores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ProveedorInternacional extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'cmp_proveedores_internacionales';

    protected $fillable = [
        'nombre',
        'tipo', //persona juridica o natural
        'ruc',
        'pais_id',
        'direccion',
        'telefono',
        'correo',
        'sitio_web',
        'banco1',
        'numero_cuenta1',
        'codigo_swift1',
        'moneda1',
        'banco2',
        'numero_cuenta2',
        'codigo_swift2',
        'moneda2',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
