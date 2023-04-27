<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\TipoSaldo;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Viatico\TipoFondo;
use App\Models\User;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SaldoGrupo extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'saldo_grupo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
    ];
    private static $whiteListFilter = [
        'fecha_inicio',
    ];
    public function usuario()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user');
    }
    public static function empaquetarCombinado($arreglo){
        $results = [];
        $id = 0;
        $row = [];
        if (isset($arreglo)) {
            foreach ($arreglo as $saldo) {
                $row['item'] = $id + 1;
                $row['fecha'] = isset($saldo->fecha_viat)? date("d-m-Y", strtotime( $saldo->fecha_viat)):date('d-m-Y', strtotime($saldo['fecha']));
                $row['descripcion'] = '';
                $row['ingreso'] = 0;
                $row['gasto'] = 0;
                $row['saldo'] = 0;
                $results[$id] = $row;
                $id++;
            }
        }
        return $results;
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
                        if ($saldo->usuario->estado == 1 && $saldo->usuario->user->id != 1) {
                            $row['item'] = $id + 1;
                            $row['id'] = $saldo->id;
                            $row['fecha'] = $saldo->fecha;
                            $row['tipo_saldo'] = $saldo->id_tipo_saldo;
                            $row['usuario'] = $saldo->id_usuario;
                            $row['empleado_info'] = $saldo->usuario->user;
                            $row['cargo'] = $saldo->usuario->cargo != null ? $saldo->usuario->cargo->nombre : '';
                            $row['empleado'] = $saldo->usuario;
                            $row['localidad'] = $saldo->usuario->canton != null ? $saldo->usuario->canton->canton : '';
                            $row['descripcion_saldo'] = $saldo->descripcion_saldo;
                            $row['saldo_anterior'] = $saldo->saldo_anterior;
                            $row['saldo_depositado'] = $saldo->saldo_depositado;
                            $row['saldo_actual'] = $saldo->saldo_actual;
                            $row['fecha_inicio'] = $saldo->fecha_inicio;
                            $row['fecha_fin'] = $saldo->fecha_fin;
                            $results[$id] = $row;
                            $id++;
                        }
                    }
                    usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");
                    break;
                case 'usuario':
                    $row['item'] = 1;
                    $row['id'] = $saldos->id;
                    $row['fecha'] = $saldos->fecha;
                    $row['tipo_saldo'] = $saldos->id_tipo_saldo;
                    $row['usuario'] = $saldos->id_usuario;
                    $row['empleado_info'] = $saldos->usuario->user;
                    $row['empleado'] = $saldos->usuario;
                    $row['cargo'] =  $saldos->usuario->cargo != null ? $saldos->usuario->cargo->nombre : '';
                    $row['localidad'] = $saldos->usuario->canton != null ? $saldos->usuario->canton->canton : '';
                    $row['descripcion_saldo'] = $saldos->descripcion_saldo;
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
    static function  ordenar_por_nombres_apellidos($a, $b)
    {
        $nameA = $a['empleado']->apellidos . ' ' . $a['empleado']->nombres;
        $nameB = $b['empleado']->apellidos . ' ' . $b['empleado']->nombres;
        return strcmp($nameA, $nameB);
    }
}
