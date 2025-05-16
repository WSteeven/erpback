<?php

namespace App\Models\RecursosHumanos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\Banco
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Banco acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Banco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Banco setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Banco whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
