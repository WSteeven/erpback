<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
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
    use UppercaseValuesTrait;
    const APROBADO = 1;
    const RECHAZADO = 2;
    const PENDIENTE = 3;
    const ANULADO = 4;
    protected $table = 'gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_lugar',
        'fecha_viat',
        'id_tarea',
        'id_subtarea',
        'id_proyecto',
        'ruc',
        'factura',
        'num_comprobante',
        'aut_especial',
        'detalle',
        'cantidad',
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
        'fecha_viat',
        'id_tarea',
        'subdetalle',
        'id_proyecto',
        'ruc',
        'factura',
        'aut_especial',
        'detalle',
        'cantidad',
        'valor_u',
        'total',
        'comprobante',
        'comprobante2',
        'observacion',
        'id_usuario',
        'estado',
        'detalle_estado',
        'usuario',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function detalle_info()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'detalle');
    }
    public function sub_detalle_info()
    {
        return $this->belongsToMany(SubDetalleViatico::class,'subdetalle_gastos', 'gasto_id', 'subdetalle_gasto_id');
    }


    public function aut_especial_user()
    {
        return $this->hasOne(Empleado::class, 'id', 'aut_especial');
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
    public function subtarea_info()
    {
        return $this->hasOne(Subtarea::class, 'id', 'id_subtarea');
    }
    public function lugar_info()
    {
        return $this->hasOne(Canton::class, 'id', 'id_lugar');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user') ;
    }
    public function detalle_estado()
    {
        return $this->hasOne(EstadoViatico::class, 'id', 'detalle_estado');
    }
    public function gasto_vehiculo_info()
    {
        return $this->hasOne(GastoVehiculo::class, 'id_gasto', 'id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public static function empaquetar($gastos)
    {
        $results = [];
        $id = 0;
        $row = [];
        foreach ($gastos as $gasto) {
            $row['fecha']= $gasto->fecha_viat;
            $row['empleado_info']= $gasto->empleado_info->user;
            $row['usuario'] = $gasto->empleado_info;
            $row['autorizador'] = $gasto->aut_especial_user->nombres . ' ' . $gasto->aut_especial_user->apellidos;
            $row['grupo'] =$gasto->empleado_info->grupo==null?'':$gasto->empleado_info->grupo->descripcion;
            $row['tarea'] = $gasto->tarea_info;
            $row['proyecto'] = $gasto->proyecto_info;
            $row['detalle'] = $gasto->detalle_info == null ? 'SIN DETALLE' : $gasto->detalle_info->descripcion;
            $row['sub_detalle'] = $gasto->sub_detalle_info;
            $row['sub_detalle_desc'] = $gasto->detalle_info == null ? 'SIN DETALLE' : $gasto->detalle_info->descripcion.': '.Gasto::subdetalle_inform($gasto->sub_detalle_info->toArray());
            $row['observacion'] = $gasto->observacion;
            $row['detalle_estado'] = $gasto->detalle_estado;
            $row['total']= $gasto->total;
            $results[$id] = $row;
            $id++;

        }
        return $results;

    }
    private static function subdetalle_inform($subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle['descripcion'];
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
}
