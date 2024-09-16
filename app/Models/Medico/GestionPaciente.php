<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\GestionPaciente
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente query()
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GestionPaciente setLoadInjectedDetection($load_default_detection)
 * @mixin \Eloquent
 */
class GestionPaciente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_';
}
