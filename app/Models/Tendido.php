<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tendido
 *
 * @property int $id
 * @property int|null $marca_inicial
 * @property int|null $marca_final
 * @property int $subtarea_id
 * @property int $bobina_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fibra|null $bobina
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RegistroTendido> $registrosTendidos
 * @property-read int|null $registros_tendidos_count
 * @property-read \App\Models\Subtarea|null $subtarea
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereBobinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereMarcaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereMarcaInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tendido whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tendido extends Model
{
    use HasFactory;
    protected $table = 'tendidos';
    protected $fillable = [
        'marca_inicial',
        'marca_final',
        'subtarea_id',
        'bobina_id',
    ];

    public function registrosTendidos()
    {
        return $this->hasMany(RegistroTendido::class);
    }

    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class);
    }

    public function bobina()
    {
        return $this->hasOne(Fibra::class);
    }
}
