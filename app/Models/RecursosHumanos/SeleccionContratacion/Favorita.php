<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
    public function favoritable() //actividad => activid + able
    {
        return $this->morphTo();
    }


}
