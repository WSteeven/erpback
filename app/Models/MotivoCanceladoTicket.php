<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\MotivoCanceladoTicket
 *
 * @property int $id
 * @property string $motivo
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoCanceladoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoCanceladoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;
    protected $table = 'motivos_cancelados_tickets';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];
    private static $whiteListFilter = [
        '*',
    ];
}
