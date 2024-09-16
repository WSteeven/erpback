<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\TipoTransaccion
 *
 * @method static where(string $string, string $EGRESO)
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoTransaccion newModelQuery()
 * @method static Builder|TipoTransaccion newQuery()
 * @method static Builder|TipoTransaccion query()
 * @method static Builder|TipoTransaccion whereCreatedAt($value)
 * @method static Builder|TipoTransaccion whereId($value)
 * @method static Builder|TipoTransaccion whereNombre($value)
 * @method static Builder|TipoTransaccion whereUpdatedAt($value)
 * @property-read Collection<int, \App\Models\Motivo> $motivos
 * @property-read int|null $motivos_count
 * @mixin Eloquent
 */
class TipoTransaccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table="tipos_transacciones";
    protected $fillable = ['nombre'];

    const INGRESO = 'INGRESO';
    const EGRESO = 'EGRESO';
    const TRANSFERENCIA = 'TRANSFERENCIA';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a muchos
     * Un tipo de transaccion tiene varios subtipos
     */
    public function motivos(){
        return $this->hasMany(Motivo::class);
    }
}
