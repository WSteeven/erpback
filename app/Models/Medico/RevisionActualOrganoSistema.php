<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\Medico\RevisionActualOrganoSistema
 *
 * @property int $id
 * @property int $organo_id
 * @property string $descripcion
 * @property int $revisionable_id
 * @property string $revisionable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read SistemaOrganico|null $organoSistema
 * @property-read Model|Eloquent $revisionable
 * @method static Builder|RevisionActualOrganoSistema newModelQuery()
 * @method static Builder|RevisionActualOrganoSistema newQuery()
 * @method static Builder|RevisionActualOrganoSistema query()
 * @method static Builder|RevisionActualOrganoSistema whereCreatedAt($value)
 * @method static Builder|RevisionActualOrganoSistema whereDescripcion($value)
 * @method static Builder|RevisionActualOrganoSistema whereId($value)
 * @method static Builder|RevisionActualOrganoSistema whereOrganoId($value)
 * @method static Builder|RevisionActualOrganoSistema whereRevisionableId($value)
 * @method static Builder|RevisionActualOrganoSistema whereRevisionableType($value)
 * @method static Builder|RevisionActualOrganoSistema whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RevisionActualOrganoSistema extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_revisiones_actuales_organos_sistemas';
    protected $fillable = [
        'organo_id',
        'descripcion',
        'revisionable_id',
        'revisionable_type',
    ];

    public function organoSistema()
    {
        return $this->hasOne(SistemaOrganico::class);
    }

    public function revisionable()
    {
        return $this->morphTo();
    }
}
