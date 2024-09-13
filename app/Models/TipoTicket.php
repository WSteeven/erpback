<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\TipoTicket
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property int|null $categoria_tipo_ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\CategoriaTipoTicket|null $categoriaTipoTicket
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereCategoriaTipoTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoTicket extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'tipos_tickets';
    protected $fillable = ['nombre', 'activo', 'categoria_tipo_ticket_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function categoriaTipoTicket()
    {
        return $this->belongsTo(CategoriaTipoTicket::class);
    }
}
