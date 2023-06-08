<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ContactoProveedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = 'contactos_proveedores';
    protected $fillable = [
        "nombres",
        "apellidos",
        "celular",
        "ext",
        "correo",
        "tipo_contacto",
        "proveedor_id",
    ];

    const TECNICO = 'TECNICO'; //contacto tecnico
    const FINANCIERO = 'FINANCIERO'; //contacto financiero

}
