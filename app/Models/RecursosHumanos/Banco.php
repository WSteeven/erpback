<?php

namespace App\Models\RecursosHumanos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Banco extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'bancos';
    protected $fillable = [
        'nombre',
        'codigo'
    ];

    private static $whiteListFilter = [
        'id',
        'codigo',
        'nombre',
    ];

    const PRODUBANCO = 'PRODUBANCO';


    public static function obtenerDatosBanco($nombre_banco = Banco::PRODUBANCO, $codigo_banco = '0036')
    {
        $banco = Banco::where('nombre', 'LIKE', '%' . $nombre_banco . '%')->orWhere('codigo', $codigo_banco)->first();
        if ($banco) return  $banco;
        else return  null;
    }
}
