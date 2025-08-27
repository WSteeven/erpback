<?php

namespace App\Models\Sistema;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PlantillaBase extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'conf_plantillas';
    protected $fillable = ['nombre', 'url'];
    private static array $whiteListFilter = ['*'];


    /**
     *
     * Obtiene el primer registro completo que coincida con el nombre proporcionado para la plantilla,
     * si no se encuentra registro, lanza una excepción.
     *
     * @param string $nombre
     * @return mixed
     * @throws Exception
     */
    public static function obtenerPlantillaByNombre(string $nombre)
    {
        $plantilla = PlantillaBase::where('nombre', $nombre)->first();
        if (!$plantilla) throw new Exception('El archivo con el nombre proporcionado no existe');
        return $plantilla;
    }
}
