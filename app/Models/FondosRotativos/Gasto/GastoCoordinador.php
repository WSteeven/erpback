<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\Grupo;
use App\Models\User;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class GastoCoordinador extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'gastos_coordinador';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha_gasto',
        'id_lugar',
        'id_grupo',
        'monto',
        'observacion',
        'id_usuario',
    ];
    private static $whiteListFilter = [
        'fecha_gasto',
        'lugar',
        'grupo',
        'monto',
        'observacion',
        'id_usuario',
    ];
    public function motivo_info()
    {
        return $this->hasOne(MotivoGasto::class, 'id','id_motivo');
    }
    public function usuario_info()
    {
        return $this->hasOne(User::class, 'id','id_usuario');
    }
    public function grupo_info()
    {
        return $this->hasOne(Grupo::class, 'id','id_grupo');
    }
    public function lugar_info()
    {
        return $this->hasOne(Canton::class, 'id','id_lugar');
    }
    public function detalle_motivo_info()
    {
        return $this->belongsToMany(MotivoGasto::class,'detalle_motivo_gastos','id_gasto_coordinador','id_motivo_gasto');
    }
    public static function empaquetar($gastos)
    {
        $results = [];
        $id = 0;
        $row = [];
        foreach ($gastos as $gasto) {
            $row['fecha_gasto']= $gasto->fecha_gasto;
            $row['lugar'] = $gasto->id_lugar;
            $row['grupo'] = $gasto->id_grupo;
            $row['grupo_info'] = $gasto->grupo_info->nombre;
            $row['motivo_info'] = $gasto->detalle_motivo_info != null ? $gasto->detalle_motivo_info:'';
            $row['motivo'] = $gasto->detalle_motivo_info != null ? $gasto->detalle_motivo_info->pluck('id'):null;
            $row['lugar_info'] = $gasto->lugar_info->canton;
            $row['monto'] = $gasto->monto;
            $row['observacion'] = $gasto->observacion;
            $row['usuario'] = $gasto->id_usuario;
            $row['usuario_info'] = $gasto->usuario_info->name;

            $results[$id] = $row;
            $id++;

        }
        return $results;

    }
}
