<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\RecursosHumanos\DiscapacidadUsuario;
use App\Models\RecursosHumanos\SeleccionContratacion\BancoPostulante;
use App\Models\RecursosHumanos\SeleccionContratacion\Favorita;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\RecursosHumanos\SeleccionContratacion\ReferenciaPersonal;
use App\Traits\UppercaseValuesTrait;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use OwenIt\Auditing\Models\Audit;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\User
 *
 * @method static where(string $string, $usuario_id)
 * @method static whereHas(string $string, $callback)
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read mixed $all_permissions
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User find($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static Builder|User whereTwoFactorSecret($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, BancoPostulante> $bancoPostulante
 * @property-read int|null $banco_postulante_count
 * @property-read Collection<int, Favorita> $favorita
 * @property-read int|null $favorita_count
 * @property-read Collection<int, Postulacion> $postulaciones
 * @property-read int|null $postulaciones_count
 * @property-read Collection<int, ReferenciaPersonal> $referencias
 * @property-read int|null $referencias_count
 * @mixin Eloquent
 */
class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use AuditableModel;
    use UppercaseValuesTrait;

    const BODEGA_TELCONET = 'BODEGA_TELCONET';

    const ROL_ACTIVOS_FIJOS = 'ACTIVOS FIJOS';
    const ROL_ADMINISTRADOR = 'ADMINISTRADOR';
    const ROL_ADMINISTRATIVO = 'ADMINISTRATIVO';
    const ROL_AUDITOR = 'AUDITOR_INTERNO';
    const ROL_COORDINADOR_BODEGA = 'COORDINADOR DE BODEGA';
    const ROL_BODEGA = 'BODEGA';
    const ROL_BODEGA_TELCONET = 'BODEGA TELCONET';
    const ROL_COMPRAS = 'COMPRAS';
    const ROL_CONTABILIDAD = 'CONTABILIDAD';
    const ROL_COORDINADOR = 'COORDINADOR';
    const ROL_COORDINADOR_BACKUP = 'COORDINADOR_BACKUP';
    const ROL_EMPLEADO = 'EMPLEADO';
    const ROL_EMPLEADO_SALIENTE = 'EMPLEADO_SALIENTE';
    const ROL_GERENTE = 'GERENTE';
    const ROL_GERENTE_PROCESOS = 'GERENTE_PROCESOS';
    const ROL_JEFE_TECNICO = 'JEFE TECNICO';
    const ROL_RECURSOS_HUMANOS = 'RECURSOS HUMANOS';
    const ROL_SUPERVISOR_TECNICO = 'SUPERVISOR_TECNICO';
    const ROL_FISCALIZADOR = 'FISCALIZADOR';
    const ROL_SSO = 'SEGURIDAD Y SALUD OCUPACIONAL';
    const ROL_TECNICO = 'TECNICO';
    const ROL_LIDER_DE_GRUPO = 'LIDER DE GRUPO';
    const ROL_AUTORIZADOR = 'AUTORIZADOR';
    const ROL_SECRETARIO = 'SECRETARIO';
    const ROL_CONSULTA = 'CONSULTA';
    const ROL_JEFE_DEPARTAMENTO = 'JEFE DE DEPARTAMENTO';
    const ROL_JEFE_COORDINACION_NEDETEL = 'JEFE_COORDINACION_NEDETEL';

    //Roles de administraci贸n
    const ROL_ADMINISTRADOR_FONDOS = 'ADMINISTRADOR FONDOS';
    const ROL_ADMINISTRADOR_VEHICULOS = 'ADMINISTRADOR_VEHICULOS';
    const ROL_ADMINISTRADOR_TICKETS_1 = 'ADMINISTRADOR TICKETS 1';
    const ROL_ADMINISTRADOR_TICKETS_2 = 'ADMINISTRADOR TICKETS 2';
    const ROL_ADMINISTRADOR_SISTEMA = 'ADMINISTRADOR SISTEMA';
    // Cargos
    const TECNICO_CABLISTA = 'T脡CNICO CABLISTA';
    const TECNICO_SECRETARIO = 'T脡CNICO SECRETARIO';
    const TECNICO_AYUDANTE = 'T脡CNICO AYUDANTE';
    const TECNICO_FUSIONADOR = 'TECNICO FUSIONADOR';

    const GERENCIA = 'GERENCIA';
    const JEFE_TECNICO = 'JEFE TECNICO';
    const COORDINADOR_TECNICO = 'COORDINADOR TECNICO';
    const TECNICO = 'TECNICO';

    // Modulo medico
    const ROL_MEDICO = 'MEDICO';
    //ventas claro
    const JEFE_VENTAS = 'JEFE_VENTAS';
    const SUPERVISOR_VENTAS = 'SUPERVISOR_VENTAS';
    const VENDEDOR = 'VENDEDOR';

    // Fondos Rotativos
    const COORDINADOR_CONTABILIDAD = 'COORDINADOR_CONTABILIDAD';

    // Modulo Vehiculos
    const CHOFER = 'CHOFER';
    const AYUDANTE_CHOFER = 'AYUDANTE_CHOFER';
    const MECANICO_GENERAL = 'MECANICO_GENERAL';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    /**
     * Relacion uno a muchos
     * Un usuario es solicitante de varias transacciones
     */
    /*public function transacciones()
    {
        return $this->hasMany(TransaccionesBodega::class, 'solicitante_id');
    } */

    // Relacion uno a uno
    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'usuario_id')->with('cargo', 'grupo', 'canton');
    }

    // Permite a vue acceder a los roles y permisos
    public function getAllPermissionsAttribute()
    {
        $permissions = [];
        $user = User::find(Auth::id());

        foreach (Permission::all() as $permission) {
            if ($user->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }

    /**
     * Este metodo no funciona, da errores.
     * Por favor BORRARLO
     */
    public function obtenerPermisos2($user_id)
    {
        $permissions = [];
        $user = User::find($user_id);

        foreach (Permission::all() as $permission) {
            if ($user->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
    
    /**
     * Mejorado el 21/11/2024
     */
    public function obtenerPermisos($user_id)
    {
        $permissions = [];
        $user = User::find($user_id);

        foreach (Permission::all() as $permission) {
            if ($user->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        $es_superadministrador = $user->hasRole(self::ROL_ADMINISTRADOR);
        return $es_superadministrador ? Permission::all()->pluck('name') : $permissions;
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos(): MorphMany
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function esTecnicoLider()
    {
        return $this->hasRole(User::ROL_LIDER_DE_GRUPO);
        // return $this->empleado->cargo === User::ROL_TECNICO_LIDER_DE_GRUPO;
    }

    public function favorita(): MorphMany
    {
        return $this->morphMany(Favorita::class, 'favoritable', 'user_type', 'user_id');
    }

    public function postulaciones(): MorphMany
    {
        return $this->morphMany(Postulacion::class, 'postulacionable', 'user_type', 'user_id');
    }

    public function bancoPostulante(): MorphMany
    {
        return $this->morphMany(BancoPostulante::class, 'bancable', 'user_type', 'user_id');
    }

    public function referencias(): MorphMany
    {
        return $this->morphMany(ReferenciaPersonal::class, 'referenciable', 'user_type', 'user_id');
    }
    public function discapacidades()
    {
        return $this->morphMany(DiscapacidadUsuario::class, 'discapacidadable', 'user_type', 'user_id');
    }
}
