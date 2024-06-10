<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use App\Models\RecursosHumanos\Banco;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DatoBancarioProveedor extends Model implements Auditable 
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'cmp_datos_bancarios_proveedores';

    protected $fillable =[
        'banco_id',
        'empresa_id',
        'tipo_cuenta',
        'numero_cuenta',
        'identificacion',
        'nombre_propietario',
    ];

    //Tipos de cuenta
    const AHORROS = 'AHORROS';
    const CORRIENTE= 'CORRIENTE';

    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function banco(){
        return $this->belongsTo(Banco::class);
    }




}
