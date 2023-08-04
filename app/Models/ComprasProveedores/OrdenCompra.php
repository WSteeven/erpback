<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class OrdenCompra extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'cmp_ordenes_compras';
    public $fillable = [
      'solicitante_id',  
      'proveedor_id',  
      'autorizador_id',  
      'autorizacion_id',  
      'observacion_aut',   
      'estado_id',  
      'observacion_est',   
      'descripcion',  
      'forma',  
      'tiempo',  
      'fecha',  
      'categorias',   
    ];


    //Forma de pago
    const CONTADO = 'CONTADO';
    const CREDITO = 'CREDITO';

    //Tiempo de pago
    CONST SEMANAL = '7 DIAS';
    CONST QUINCENAL = '15 DIAS';
    CONST MES = '30 DIAS';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

}
