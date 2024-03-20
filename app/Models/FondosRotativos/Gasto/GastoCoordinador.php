<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Grupo;
use App\Models\Notificacion;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class GastoCoordinador extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
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
    public function motivoGasto()
    {
        return $this->hasOne(MotivoGasto::class, 'id', 'id_motivo');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user');
    }
    public function grupo()
    {
        return $this->hasOne(Grupo::class, 'id', 'id_grupo');
    }
    public function canton()
    {
        return $this->hasOne(Canton::class, 'id', 'id_lugar');
    }
    public function detalleMotivoGasto()
    {
        return $this->belongsToMany(MotivoGasto::class, 'detalle_motivo_gastos', 'id_gasto_coordinador', 'id_motivo_gasto');
    }
    public static function empaquetar($gastos)
    {
        try {
            $results = [];
            $id = 0;
            $row = [];
            foreach ($gastos as $gasto) {
                $row['fecha_gasto'] = $gasto->fecha_gasto;
                $row['lugar'] = $gasto->id_lugar;
                $row['grupo'] = $gasto->id_grupo;
                $row['grupo_info'] = $gasto->grupo->nombre;
                $row['motivo_info'] = self::obtenerNombresMotivos($gasto->detalleMotivoGasto);
                $row['motivo'] = $gasto->motivoGasto != null ? $gasto->motivoGasto?->pluck('id') : null;
                $row['lugar_info'] = $gasto?->canton->canton;
                $row['monto'] = $gasto->monto;
                $row['observacion'] = $gasto->observacion;
                $row['usuario'] = $gasto->id_usuario;
                $row['empleado_info'] = $gasto->empleado != null ? $gasto?->empleado?->nombres . ' ' . $gasto?->empleado?->apellidos : ' ';
                $results[$id] = $row;
                $id++;
            }

            return $results;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    private static function obtenerNombresMotivos($motivos)
    {
        $nombres = array();
        if (!is_null($motivos)) {
            foreach ($motivos as $motivo) {
                $nombres[] = $motivo['nombre'];
            }
            return implode(", ", $nombres);
        } else {
            return '';
        }
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
