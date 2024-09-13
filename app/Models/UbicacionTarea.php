<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\UbicacionTarea
 *
 * @property int $id
 * @property string $parroquia
 * @property string $direccion
 * @property string $referencias
 * @property string $coordenadas
 * @property int $provincia_id
 * @property int $canton_id
 * @property int $tarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Canton|null $canton
 * @property-read \App\Models\Provincia|null $provincia
 * @property-read UbicacionTarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereCoordenadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereParroquia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereProvinciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereReferencias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UbicacionTarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UbicacionTarea extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    protected $table = 'ubicaciones_tareas';
    protected $fillable = [
        'parroquia',
        'direccion',
        'referencias',
        'coordenadas',
        'provincia_id',
        'canton_id',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function tarea()
    {
        return $this->hasOne(UbicacionTarea::class);
    }
}
