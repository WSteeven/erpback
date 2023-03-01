<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Gasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'gastos';
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
        'detalle_estado'
    ];

    private static $whiteListFilter = [
        'factura',
    ];
    public function detalle_info()
    {
        return $this->hasOne(DetalleGasto::class, 'id', 'detalle');
    }
    public function sub_detalle_info()
    {
        return $this->hasOne(SubDetalleGasto::class, 'id', 'sub_detalle');
    }
    public function aut_especial_user()
    {
        return $this->hasOne(User::class, 'id', 'aut_especial');
    }
    public function estado_info()
    {
        return $this->hasOne(EstadoGasto::class, 'id', 'estado');
    }
    public function proyecto_info()
    {
        return $this->hasOne(Proyecto::class, 'id', 'id_proyecto');
    }
    public function tarea_info()
    {
        return $this->hasOne(Tarea::class, 'id', 'id_tarea');
    }
    public function lugar_info()
    {
        return $this->hasOne(LugarGasto::class, 'id', 'id_lugar');
    }
    public function usuario_info()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario')->with('empleado');
    }
    public static function empaquetar($gastos)
    {
        $results = [];
        $id = 0;
        $row = [];
        foreach ($gastos as $gasto) {
            $row['fecha']= $gasto->fecha_viat;
            $row['usuario_info']= $gasto->usuario_info;
            $row['usuario'] = $gasto->usuario_info->empleado;
            $row['grupo'] = $gasto->usuario_info->empleado->grupo==null?'':$gasto->usuario_info->empleado->grupo->descripcion;
            $row['tarea'] = $gasto->tarea_info;
            $row['detalle'] = $gasto->detalle_info;
            $row['sub_detalle'] = $gasto->sub_detalle_info;
            $row['observacion'] = $gasto->observacion;
            $row['detalle_estado'] = $gasto->detalle_estado;
            $row['total']= $gasto->total;
            $results[$id] = $row;
            $id++;

        }
        return $results;

    }
}
