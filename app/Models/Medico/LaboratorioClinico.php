<?php

namespace App\Models\Medico;

use App\Models\Canton;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\LaboratorioClinico
 *
 * @property int $id
 * @property string $nombre
 * @property string $direccion
 * @property string $celular
 * @property string $correo
 * @property string $coordenadas
 * @property bool $activo
 * @property int $canton_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico query()
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereCoordenadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaboratorioClinico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LaboratorioClinico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_laboratorios_clinicos';
    protected $fillable = [
        'nombre',
        'direccion',
        'celular',
        'correo',
        'coordenadas',
        'activo',
        'canton_id',
    ];

    private static $whiteListFilter = ['*'];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /*************
     * Relaciones
     *************/
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
}
