<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\Medico\RevisionActualOrganoSistema
 *
 * @property int $id
 * @property int $organo_id
 * @property string $descripcion
 * @property int $revisionable_id
 * @property string $revisionable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\SistemaOrganico|null $organoSistema
 * @property-read Model|\Eloquent $revisionable
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema query()
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereOrganoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereRevisionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereRevisionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RevisionActualOrganoSistema whereUpdatedAt($value)
 * @mixin \Eloquent
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
