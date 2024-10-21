<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\ArchivoSubtarea
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property string $tamanio_bytes
 * @property string|null $comentario
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereTamanioBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchivoSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_subtareas';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'comentario', 'subtarea_id'];

    private static $whiteListFilter = ['*'];
}
