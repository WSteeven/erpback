<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\PropietarioElemento
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento query()
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PropietarioElemento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PropietarioElemento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = "propietarios_elementos";

    protected $fillable = ['descripcion'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



}
