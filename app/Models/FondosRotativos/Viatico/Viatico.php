<?php

namespace App\Models\FondosRotativos\Viatico;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Viatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'viaticos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_lugar',
        'fecha_viat',
        'id_tarea',
        'id_proyecto',
        'ruc',
        'factura',
        'proveedor',
        'aut_especial',
        'detalle',
        'sub_detalle',
        'cant',
        'valor_u',
        'total',
        'comprobante',
        'comprobante2',
        'observacion',
        'id_usuario',
        'estado',
        'detalle_estado',
        'fecha_ingreso',
        'fecha_proc',
        'transcriptor',
        'fecha_trans'

    ];

    private static $whiteListFilter = [
        'factura',
    ];
    public function detalle_info()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'detalle');
    }
    public function sub_detalle_info()
    {
        return $this->hasOne(SubDetalleViatico::class, 'id', 'sub_detalle');
    }
    public function aut_especial_user()
    {
        return $this->hasOne(User::class, 'id', 'aut_especial');
    }
    public function estado_info()
    {
        return $this->hasOne(EstadoViatico::class, 'id', 'estado');
    }
    public function proyecto_info()
    {
        return $this->hasOne(Proyecto::class, 'id', 'id_proyecto');
    }
    public function tarea_info()
    {
        return $this->hasOne(Tarea::class, 'id', 'id_tarea');
    }
}
