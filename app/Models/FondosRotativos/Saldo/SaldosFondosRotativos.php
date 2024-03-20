<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SaldosFondosRotativos extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_saldos_fondos_rotativos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'tipo_saldo',
        'empleado_id',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function saldoable()
    {
        return $this->morphTo();
    }
    public static function empaquetarListado($saldos, $tipo)
    {
        $results = [];
        $id = 0;
        $row = [];
        if (isset($saldos)) {
            switch ($tipo) {
                case 'todos':
                    foreach ($saldos as $saldo) {
                        if ($saldo->empleado->estado == 1 && $saldo->empleado->id != 1) {
                            $row['item'] = $id + 1;
                            $row['id'] = $saldo->id;
                            $row['fecha'] = $saldo->fecha;
                            $row['tipo_saldo'] = $saldo->id_tipo_saldo;
                            $row['usuario'] = $saldo->empleado_id;
                            $row['empleado_info'] = $saldo->empleado->user;
                            $row['cargo'] = $saldo->empleado->cargo != null ? $saldo->empleado->cargo->nombre : '';
                            $row['empleado'] = $saldo->empleado;
                            $row['localidad'] = $saldo->empleado->canton != null ? $saldo->empleado->canton->canton : '';
                            $row['saldo_anterior'] = $saldo->saldo_anterior;
                            $row['saldo_depositado'] = $saldo->saldo_depositado;
                            $row['saldo_actual'] = $saldo->saldo_actual;
                            $results[$id] = $row;
                            $id++;
                        }
                    }
                    usort($results, __CLASS__ . "::ordenarNombresApellidos");
                    break;
                case 'usuario':
                    $row['item'] = 1;
                    $row['id'] = $saldos->id;
                    $row['fecha'] = $saldos->fecha;
                    $row['tipo_saldo'] = $saldos->id_tipo_saldo;
                    $row['usuario'] = $saldos->empleado_id;
                    $row['empleado_info'] = $saldos->usuario->user;
                    $row['empleado'] = $saldos->empleado;
                    $row['cargo'] =  $saldos->empleado->cargo != null ? $saldos->empleado->cargo->nombre : '';
                    $row['localidad'] = $saldos->empleado->canton != null ? $saldos->empleado->canton->canton : '';
                    $row['descripcion_saldo'] = $saldos->descripcionSaldo;
                    $row['saldo_anterior'] = $saldos->saldo_anterior;
                    $row['saldo_depositado'] = $saldos->saldo_depositado;
                    $row['saldo_actual'] = $saldos->saldo_actual;
                    $row['fecha_inicio'] = $saldos->fecha_inicio;
                    $row['fecha_fin'] = $saldos->fecha_fin;
                    $results[$id] = $row;
                    break;
            }
        }
        return $results;
    }
    private static function  ordenarNombresApellidos($a, $b)
    {
        $nameA = $a['empleado']->apellidos . ' ' . $a['empleado']->nombres;
        $nameB = $b['empleado']->apellidos . ' ' . $b['empleado']->nombres;
        return strcmp($nameA, $nameB);
    }
}
