<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Favorita
 *
 * @method static where(string $string, $id)
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $favoritable
 * @method static Builder|Favorita newModelQuery()
 * @method static Builder|Favorita newQuery()
 * @method static Builder|Favorita query()
 * @mixin Eloquent
 */
class Favorita extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'rrhh_contratacion_vacante_favorita_usuario';
    protected $fillable = [
        'vacante_id',
        'user_id',
        'user_type',
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relación polimorfica
    public function favoritable()
    {
        return $this->morphTo();
    }


}
