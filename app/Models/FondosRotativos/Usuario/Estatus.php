<?php

namespace App\Models\FondosRotativos\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FondosRotativos\Usuario\Estatus
 *
 * @property int $id
 * @property string $descripcion
 * @property string $transcriptor
 * @property string $fecha_trans
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereFechaTrans($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereTranscriptor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Estatus extends Model
{
    use HasFactory;
    protected $table = 'estatus';
    protected $primaryKey = 'id';
}
