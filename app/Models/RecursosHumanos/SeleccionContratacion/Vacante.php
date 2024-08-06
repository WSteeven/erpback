<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class Vacante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_contratacion_vacantes';

    //Agregar la modalidad

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_caducidad',
        'imagen_referencia',
        'imagen_publicidad',
        'anios_experiencia',
        'areas_conocimiento',
        'numero_postulantes',
        'tipo_puesto_id',
        'publicante_id',
        'solicitud_id',
        'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuesto::class, 'id', 'tipo_puesto_id');
    }

    public function publicante()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPersonal::class);
    }

    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    public function favorita()
    {
        $user_id = null;
        $user_type = null;

        Log::channel('testing')->info('Log', ['usuario: ', Auth::guard('web')->check()]);
        Log::channel('testing')->info('Log', ['usuario externo: ', Auth::guard('user_external')->check()]);
        Log::channel('testing')->info('Log', ['usuario sam: ', Auth::guard('sanctum')->check()]);
        Log::channel('testing')->info('Log', ['usuario limpio: ', auth()->user()]);
        Log::channel('testing')->info('Log', ['usuario limpio: ', auth()->guard('user_external')->user()]);
        // Determina el tipo de usuario autenticado
       if (Auth::guard('web')->check()) {
            $user_id = Auth::guard('web')->user()->id;
            $user_type = \App\Models\User::class;
        } elseif (Auth::guard('user_external')->check()) {
            $user_id = Auth::guard('user_external')->user()->id;
            $user_type = \App\Models\UserExternal::class;
        }

        // Si hay un usuario autenticado, retorna una instancia de la relación
        return $this->hasOne(Favorita::class, 'vacante_id')
            ->where('user_id', $user_id)
            ->where('user_type', $user_type);
    }

}
