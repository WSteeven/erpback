<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MotivoPausa
 *
 * @property int $id
 * @property string $motivo
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoPausa extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;
    protected $table = 'motivos_pausas';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
