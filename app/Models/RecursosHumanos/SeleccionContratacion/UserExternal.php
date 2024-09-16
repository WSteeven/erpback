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

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\UserExternal
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $token
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $all_permissions
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\Postulante|null $postulante
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserExternal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function postulaciones()
    {
        return $this->morphMany(Postulacion::class, 'postulacionable', 'user_type', 'user_id');
    }
    public function bancoPostulante()
    {
        return $this->morphMany(BancoPostulante::class, 'bancable', 'user_type', 'user_id');
    }
    public function referencias(): MorphMany
    {
        return $this->morphMany(ReferenciaPersonal::class, 'referenciable', 'user_type', 'user_id');
    }
}
