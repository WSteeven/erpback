<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\ArchivoSeguimiento
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property string $tamanio_bytes
 * @property int|null $seguimiento_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $subtarea_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereSeguimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereTamanioBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimiento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchivoSeguimiento extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_seguimientos';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'subtarea_id'];

    private static $whiteListFilter = ['*'];
}
