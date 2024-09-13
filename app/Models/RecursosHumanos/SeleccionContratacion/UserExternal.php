<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
    const POSTULANTE = 'POSTULANTE';
    const ROL_POSTULANTE = 'POSTULANTES';
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
    // Permite a vue acceder a los roles y permisos
    public function getAllPermissionsAttribute()
    {
        $permissions = [];
        $user = UserExternal::find(Auth::id());
        foreach (Permission::all() as $permission) {
            if ($user->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
    public function obtenerPermisos($user_id)
    {
        $permissions = [];
        $user = UserExternal::find($user_id);
        foreach (Permission::all() as $permission) {
            if ($user->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
        /**
     * Relacion uno a muchos
     * Un usuario es solicitante de varias transacciones
     */
    /*public function transacciones()
    {
        return $this->hasMany(TransaccionesBodega::class, 'solicitante_id');
    } */

    // Relacion uno a uno
    public function postulante()
    {
        return $this->hasOne(Postulante::class, 'usuario_external_id');
    }
}
