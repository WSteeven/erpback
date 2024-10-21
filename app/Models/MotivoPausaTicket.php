<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MotivoPausaTicket
 *
 * @property int $id
 * @property string $motivo
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPausaTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoPausaTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;
    protected $table = 'motivos_pausas_tickets';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];
    private static $whiteListFilter = [
        '*',
    ];
}
