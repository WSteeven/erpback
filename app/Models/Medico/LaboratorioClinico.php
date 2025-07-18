<?php

namespace App\Models\Medico;

use App\Models\Canton;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @method static Builder|LaboratorioClinico acceptRequest(?array $request = null)
 * @method static Builder|LaboratorioClinico filter(?array $request = null)
 * @method static Builder|LaboratorioClinico ignoreRequest(?array $request = null)
 * @method static Builder|LaboratorioClinico newModelQuery()
 * @method static Builder|LaboratorioClinico newQuery()
 * @method static Builder|LaboratorioClinico query()
 * @method static Builder|LaboratorioClinico setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|LaboratorioClinico setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|LaboratorioClinico setLoadInjectedDetection($load_default_detection)
 * @method static Builder|LaboratorioClinico whereActivo($value)
 * @method static Builder|LaboratorioClinico whereCantonId($value)
 * @method static Builder|LaboratorioClinico whereCelular($value)
 * @method static Builder|LaboratorioClinico whereCoordenadas($value)
 * @method static Builder|LaboratorioClinico whereCorreo($value)
 * @method static Builder|LaboratorioClinico whereCreatedAt($value)
 * @method static Builder|LaboratorioClinico whereDireccion($value)
 * @method static Builder|LaboratorioClinico whereId($value)
 * @method static Builder|LaboratorioClinico whereNombre($value)
 * @method static Builder|LaboratorioClinico whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = ['*'];

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
