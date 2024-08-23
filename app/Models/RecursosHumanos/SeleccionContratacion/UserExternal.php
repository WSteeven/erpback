<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class UserExternal extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use AuditableModel;
    use UppercaseValuesTrait;
//    const POSTULANTE = 'POSTULANTE';
//    const ROL_POSTULANTE = 'POSTULANTES';
    protected $table = 'rrhh_users_externals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'status',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d h:i:s a',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    // Relacion uno a uno
    public function persona()
    {
        return $this->hasOne(Postulante::class, 'usuario_external_id');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * Obtiene las vacantes favoritas del usuario.
     *
     * Este método recupera las vacantes favoritas del usuario de la base de datos.
     * Utiliza Laravel's Eloquent ORM para establecer una relación polimórfica con el modelo Favorita.
     *
     * @return MorphMany
     * @return Collection|Favorita[]
     *
     * @see Favorita
     */
    public function favorita()
    {
        return $this->morphMany(Favorita::class, 'favoritable', 'user_type', 'user_id');
    }

    public function postulacion()
    {
        return $this->morphMany(Postulacion::class, 'postulacionable', 'user_type', 'user_id');
    }
    public function bancoPostulante()
    {
        return $this->morphMany(BancoPostulante::class, 'bancable', 'user_type', 'user_id');
    }
}
