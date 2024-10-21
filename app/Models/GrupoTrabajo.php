<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\GrupoTrabajo
 *
 * @property int $id
 * @property bool $es_responsable
 * @property int $grupo_id
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereEsResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GrupoTrabajo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GrupoTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'grupo_trabajo';
    protected $fillable = ['es_responsable', 'grupo_id', 'subtarea_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_responsable' => 'boolean',
    ];
}
