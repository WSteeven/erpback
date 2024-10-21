<?php

namespace App\Models\FondosRotativos\Gasto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FondosRotativos\Gasto\EstadoViatico
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoViatico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoViatico extends Model
{
    use HasFactory;
    protected $table = 'estado_viatico';
    protected $primaryKey = 'id';
}
