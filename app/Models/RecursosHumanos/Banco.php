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


    /**
     * La función obtiene datos bancarios basándose en el nombre del banco proporcionado como entrada.
     * 
     * @param string|null $nombre es una cadena o nulo. Si el parámetro es nulo, la función devuelve nulo. 
     * Si el parámetro es una cadena, consulta el modelo de Banco para encontrar un registro que coincida 
     * con el nombre proporcionado.
     * 
     * @return mixed Si el parámetro de entrada `` es `null`, entonces se devolverá `null`. De
     * lo contrario, se devolverá el primer registro de Banco coincidente encontrado.
     */
    public static function obtenerDatosBanco(string|null $nombre)
    {
        if (is_null($nombre))
            return null;
        else {
            $banco = Banco::where('nombre', 'LIKE', '%' . $nombre . '%')
                // ->orWhere('codigo', $codigo_banco)
                ->first();
            return  $banco;
        }
    }
}
