<?php

namespace App\Models\Tareas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class TipoCoordenada extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'tar_tipos_coordenadas';
    protected $fillable = [
        'nombre',
     ];


     public static function getTipoCoordenadaByNombre(?string $nombre=null)
      {
        if (!$nombre) {
            return null;
        }
        return self::where('nombre', $nombre)->first()->id ?? null;


     }
}
