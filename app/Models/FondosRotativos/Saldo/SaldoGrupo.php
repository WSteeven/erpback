<?php

namespace App\Models\FondosRotativos\Saldo;

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
        return $this->hasOne(User::class, 'id','id_usuario');
    }
    public static function empaquetarListado($saldos)
    {
            $results = [];
        $id = 0;
        $row = [];
        foreach ($saldos as $saldo) {
            $row['id'] = $saldo->id;
            $row['fecha'] = $saldo->fecha;
            $row['tipo_saldo'] = $saldo->id_tipo_saldo;
            $row['usuario'] = $saldo->id_usuario;
            $row['usuario_info'] = $saldo->usuario;
            $row['descripcion_saldo'] = $saldo->descripcion_saldo;
            $row['saldo_anterior'] = $saldo->saldo_anterior;
            $row['saldo_depositado'] = $saldo->saldo_depositado;
            $row['saldo_actual'] = $saldo->saldo_actual;
            $row['fecha_inicio'] = $saldo->fecha_inicio;
            $row['fecha_fin'] = $saldo->fecha_fin;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
}
