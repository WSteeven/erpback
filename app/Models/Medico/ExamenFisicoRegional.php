<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\ExamenFisicoRegional
 *
 * @property int $id
 * @property int $categoria_examen_fisico_id
 * @property string|null $observacion
 * @property int $examen_fisico_regionalable_id
 * @property string $examen_fisico_regionalable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read CategoriaExamenFisico|null $categoriaexamenFisico
 * @property-read Model|Eloquent $examenFisicoRegionalable
 * @method static Builder|ExamenFisicoRegional newModelQuery()
 * @method static Builder|ExamenFisicoRegional newQuery()
 * @method static Builder|ExamenFisicoRegional query()
 * @method static Builder|ExamenFisicoRegional whereCategoriaExamenFisicoId($value)
 * @method static Builder|ExamenFisicoRegional whereCreatedAt($value)
 * @method static Builder|ExamenFisicoRegional whereExamenFisicoRegionalableId($value)
 * @method static Builder|ExamenFisicoRegional whereExamenFisicoRegionalableType($value)
 * @method static Builder|ExamenFisicoRegional whereId($value)
 * @method static Builder|ExamenFisicoRegional whereObservacion($value)
 * @method static Builder|ExamenFisicoRegional whereUpdatedAt($value)
 * @mixin Eloquent
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
