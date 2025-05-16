<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\ExamenFisicoRegional
 *
 * @property int $id
 * @property int $categoria_examen_fisico_id
 * @property string|null $observacion
 * @property int $examen_fisico_regionalable_id
 * @property string $examen_fisico_regionalable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\CategoriaExamenFisico|null $categoriaexamenFisico
 * @property-read Model|\Eloquent $examenFisicoRegionalable
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereCategoriaExamenFisicoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereExamenFisicoRegionalableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereExamenFisicoRegionalableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenFisicoRegional whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamenFisicoRegional extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_examenes_fisicos_regionales';
    protected $fillable = [
        'categoria_examen_fisico_id',
        'observacion',
        'examen_fisico_regionalable_id',
        'examen_fisico_regionalable_type',
    ];

    public function categoriaexamenFisico(){
        return $this->hasOne(CategoriaExamenFisico::class,'id','categoria_examen_fisico_id');
    }

    public function examenFisicoRegionalable(){
        return $this->morphTo();
    }

}
