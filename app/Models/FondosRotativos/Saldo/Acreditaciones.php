<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\TipoFondo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Acreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'acreditaciones';
    protected $fillable = [
        'id_tipo_fondo',
        'id_tipo_saldo',
        'id_usuario',
        'id_saldo',
        'fecha',
        'descripcion_acreditacion',
        'monto',
        'id_estado',
    ];
    private static $whiteListFilter = [
        'fecha',
        'id_estado',
    ];
    public function usuario()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user','canton');
    }
    public function estado(){
        return $this->hasOne(EstadoAcreditaciones::class, 'id', 'id_estado');
    }
    public function tipoSaldo(){
        return $this->hasOne(TipoSaldo::class, 'id', 'id_tipo_saldo');
    }
    public function tipo_fondo(){
        return $this->hasOne(TipoFondo::class, 'id', 'id_tipo_fondo');
    }
    public static function empaquetar($acreditaciones)
    {
        try {
            $results = [];
            $id = 0;
            $row = [];
            if (isset($acreditaciones)) {
                foreach ($acreditaciones as $acreditacion) {
                    $row['item'] = $id + 1;
                    $row['id'] = $acreditacion->id;
                    $row['fecha'] = $acreditacion->fecha;
                    $row['tipo_saldo'] = $acreditacion->tipoSaldo->descripcion;
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
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error Acreditaciones::empaquetar', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
        return $results;

    }
    public function saldoFondoRotativo()
    {
        return $this->morphMany(SaldosFondosRotativos::class, 'saldoable');
    }
}
