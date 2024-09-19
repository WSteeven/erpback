<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\UserExternal
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read mixed $all_permissions
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Postulante|null $postulante
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|UserExternal newModelQuery()
 * @method static Builder|UserExternal newQuery()
 * @method static Builder|UserExternal permission($permissions)
 * @method static Builder|UserExternal query()
 * @method static Builder|UserExternal firstOrCreate($key, $data)
 * @method static Builder|UserExternal create($data)
 * @method static Builder|UserExternal where(string $string, $usuario_id)
 * @method static Builder|UserExternal role($roles, $guard = null)
 * @method static Builder|UserExternal whereCreatedAt($value)
 * @method static Builder|UserExternal whereEmail($value)
 * @method static Builder|UserExternal whereEmailVerifiedAt($value)
 * @method static Builder|UserExternal find($value)
 * @method static Builder|UserExternal whereId($value)
 * @method static Builder|UserExternal whereName($value)
 * @method static Builder|UserExternal wherePassword($value)
 * @method static Builder|UserExternal whereRememberToken($value)
 * @method static Builder|UserExternal whereToken($value)
 * @method static Builder|UserExternal whereUpdatedAt($value)
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, BancoPostulante> $bancoPostulante
 * @property-read int|null $banco_postulante_count
 * @property-read Collection<int, Favorita> $favorita
 * @property-read int|null $favorita_count
 * @property-read Postulante|null $persona
 * @property-read Collection<int, Postulacion> $postulaciones
 * @property-read int|null $postulaciones_count
 * @property-read Collection<int, ReferenciaPersonal> $referencias
 * @property-read int|null $referencias_count
 * @mixin Eloquent
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
        'provider_id',
        'provider_name',
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
