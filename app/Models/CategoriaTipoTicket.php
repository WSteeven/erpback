<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\CategoriaTipoTicket
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $departamento_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $activo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Departamento|null $departamento
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaTipoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaTipoTicket extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'categorias_tipos_tickets';
    protected $fillable = ['nombre', 'activo', 'departamento_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
