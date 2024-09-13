<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Propietario
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario query()
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propietario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Propietario extends Model
{
    use HasFactory;
}
