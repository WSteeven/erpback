<?php

namespace App\Models\RecursosHumanos;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\Banco
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Banco acceptRequest(?array $request = null)
 * @method static Builder|Banco filter(?array $request = null)
 * @method static Builder|Banco ignoreRequest(?array $request = null)
 * @method static Builder|Banco newModelQuery()
 * @method static Builder|Banco newQuery()
 * @method static Builder|Banco query()
 * @method static Builder|Banco setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Banco setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Banco setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Banco whereCodigo($value)
 * @method static Builder|Banco whereCreatedAt($value)
 * @method static Builder|Banco whereId($value)
 * @method static Builder|Banco whereNombre($value)
 * @method static Builder|Banco whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
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
