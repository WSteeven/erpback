<?php

namespace App\Models\FondosRotativos\Permiso;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FondosRotativos\Permiso\Permiso
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Permiso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permiso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permiso query()
 * @mixin \Eloquent
 */
class Permiso extends Model
{
    use HasFactory;
    protected $table = 'permiso';
    protected $primaryKey = 'id';
}
