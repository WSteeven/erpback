<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MotivoSuspendido
 *
 * @property int $id
 * @property string $motivo
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtarea> $subtarea
 * @property-read int|null $subtarea_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoSuspendido whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoSuspendido extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;
    protected $table = 'motivos_suspendidos';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    public function subtarea()
    {
        return $this->belongsToMany(Subtarea::class)->withPivot('empleado_id')->withTimestamps();
    }
}
