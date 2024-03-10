<?php

namespace App\Models\FondosRotativos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class AjusteSaldoFondoRotativo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'fr_ajustes_saldos';
    protected $fillable = [
        'solicitante_id',
        'destinatario_id',
        'autorizador_id',
        'motivo',
        'descripcion',
        'monto',
        'tipo',
    ];

    const INGRESO = 'Ingreso';
    const EGRESO = 'Egreso';

    private static $whiteListFilter = ['*'];

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo pertenecen a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo van dirigidos a un destinatario
     */
    public function destinatario()
    {
        return $this->belongsTo(Empleado::class, 'destinatario_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo pertenecen a un autorizador
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    public static function empaquetar($ajustessaldos)
    {
        try{
            $results = [];
            $id = 0;
            $row = [];
            foreach ($ajustessaldos as $ajustesaldo) {
                $row['id'] = $ajustesaldo->id;
                $row['num_registro'] = $id+1;
                $row['fecha'] = $ajustesaldo->created_at;
                $row['solicitante']= $ajustesaldo->solicitante->nombres.' '. $ajustesaldo->solicitante->apellidos;
                $row['destinatario']= $ajustesaldo->destinatario->nombres.' '. $ajustesaldo->destinatario->apellidos;
                $row['motivo']= $ajustesaldo->motivo;
                $row['descripcion']= $ajustesaldo->descripcion;
                $row['monto'] = $ajustesaldo->monto;
                $results[$id] = $row;
                $id++;
            }
            return $results;
        }catch(Exception $e){
            Log::channel('testing')->info('Log', ['error modelo', $e->getMessage(), $e->getLine()]);
        }


    }
}
