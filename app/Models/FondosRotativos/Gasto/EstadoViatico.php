<?php

namespace App\Models\FondosRotativos\Gasto;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\FondosRotativos\Gasto\EstadoViatico
 *
 * @property int $id
 * @property string $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|EstadoViatico newModelQuery()
 * @method static Builder|EstadoViatico newQuery()
 * @method static Builder|EstadoViatico query()
 * @method static Builder|EstadoViatico whereCreatedAt($value)
 * @method static Builder|EstadoViatico whereDescripcion($value)
 * @method static Builder|EstadoViatico whereId($value)
 * @method static Builder|EstadoViatico whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EstadoViatico extends Model
{
    use HasFactory;
    protected $table = 'estado_viatico';
    protected $primaryKey = 'id';

    const APROBADO = 'APROBADO';
    const APROBADO_ID = 1;
    const RECHAZADO = 'RECHAZADO';
    const RECHAZADO_ID = 2;
    const POR_APROBAR = 'POR APROBAR';
    const POR_APROBAR_ID = 3;
    const ANULADO = 'ANULADO';
    const ANULADO_ID = 4;
}
