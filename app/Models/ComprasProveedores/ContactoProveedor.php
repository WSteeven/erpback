<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use App\Models\Proveedor;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ContactoProveedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'cmp_contactos_proveedores';
    protected $fillable = [
        "nombres",
        "apellidos",
        "celular",
        "ext",
        "correo",
        "tipo_contacto",
        "empresa_id",
        "proveedor_id",
    ];

    const TECNICO = 'TECNICO'; //contacto tecnico
    const FINANCIERO = 'FINANCIERO'; //contacto financiero
    const COMERCIAL = 'COMERCIAL'; //contacto comercial
    // const WEB = 'SITIO WEB'; //contacto SITIO WEB


    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

}
