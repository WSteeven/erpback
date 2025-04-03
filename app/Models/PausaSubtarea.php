<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\PausaSubtarea
 *
 * @property int $id
 * @property string $fecha_hora_pausa
 * @property string|null $fecha_hora_retorno
 * @property int $motivo_pausa_id
 * @property int $subtarea_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\MotivoPausa|null $motivoPausa
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea whereFechaHoraPausa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea whereFechaHoraRetorno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea whereMotivoPausaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaSubtarea whereSubtareaId($value)
 * @mixin \Eloquent
 */
class PausaSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $timestamps = false;
    protected $table = "pausas_subtareas";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo_pausa_id',
        'subtarea_id'
    ];

    public function motivoPausa()
    {
        return $this->belongsTo(MotivoPausa::class);
    }
}
