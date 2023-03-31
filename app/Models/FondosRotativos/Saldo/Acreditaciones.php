<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\TipoFondo;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Acreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'acreditaciones';
    protected $fillable = [
        'id_tipo_fondo',
        'id_tipo_saldo',
        'id_usuario',
        'id_saldo',
        'fecha',
        'descripcion_acreditacion',
        'monto',
    ];
    private static $whiteListFilter = [
        'fecha',
    ];
    public function usuario()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user');
    }
    public function tipo_saldo(){
        return $this->hasOne(TipoSaldo::class, 'id', 'id_tipo_saldo');
    }
    public function tipo_fondo(){
        return $this->hasOne(TipoFondo::class, 'id', 'id_tipo_fondo');
    }
    public static function empaquetar($acreditaciones)
    {

        $results = [];
        $id = 0;
        $row = [];
        if (isset($acreditaciones)) {
            foreach ($acreditaciones as $acreditacion) {
                $row['item'] = $id + 1;
                $row['id'] = $acreditacion->id;
                $row['fecha'] = $acreditacion->fecha;
                $row['tipo_saldo'] = $acreditacion->tipo_saldo->descripcion;
                $row['tipo_fondo'] = $acreditacion->tipo_fondo->descripcion;
                $row['usuario'] = $acreditacion->usuario->user;
                $row['cargo'] = $acreditacion->usuario->cargo==null?'':$acreditacion->usuario->cargo->nombre;
                $row['empleado'] = $acreditacion->usuario;
                $row['descripcion_acreditacion'] = $acreditacion->descripcion_acreditacion;
                $row['monto'] = $acreditacion->monto;
                $results[$id] = $row;
                $id++;
            }

        }
        return $results;

    }
}
