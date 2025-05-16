<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\DetalleExamen
 *
 * @property int $id
 * @property int $tipo_examen_id
 * @property int $categoria_examen_id
 * @property int $examen_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\CategoriaExamen|null $categoriaExamen
 * @property-read \App\Models\Medico\Examen|null $examen
 * @property-read \App\Models\Medico\TipoExamen|null $tipoExamen
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereCategoriaExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereTipoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_detalles_examenes';
    protected $fillable = [
        'tipo_examen_id',
        'categoria_examen_id',
        'examen_id',
    ];
    public function tipoExamen(){
        return $this->hasOne(TipoExamen::class,'id','tipo_examen_id');
    }
    public function categoriaExamen(){
        return $this->hasOne(CategoriaExamen::class,'id','categoria_examen_id');
    }
    public function examen(){
        return $this->hasOne(Examen::class,'id','examen_id');
    }
}
