<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

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
    const ROL_COORDINADOR_BODEGA = 'COORDINADOR DE BODEGA';
    const ROL_BODEGA = 'BODEGA';
    const ROL_BODEGA_TELCONET = 'BODEGA TELCONET';
    const ROL_COMPRAS = 'COMPRAS';
    const ROL_CONTABILIDAD = 'CONTABILIDAD';
    const ROL_COORDINADOR = 'COORDINADOR';
    const ROL_COORDINADOR_BACKUP = 'COORDINADOR_BACKUP';
    const ROL_EMPLEADO = 'EMPLEADO';
    const ROL_GERENTE = 'GERENTE';
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

    //Roles de administración
    const ROL_ADMINISTRADOR_FONDOS = 'ADMINISTRADOR FONDOS';
    const ROL_ADMINISTRADOR_VEHICULOS = 'ADMINISTRADOR_VEHICULOS';
    const ROL_ADMINISTRADOR_TICKETS_1 = 'ADMINISTRADOR TICKETS 1';
    const ROL_ADMINISTRADOR_TICKETS_2 = 'ADMINISTRADOR TICKETS 2';
    const ROL_ADMINISTRADOR_SISTEMA = 'ADMINISTRADOR SISTEMA';
    // Cargos
    const TECNICO_CABLISTA = 'TÉCNICO CABLISTA';
    const TECNICO_SECRETARIO = 'TÉCNICO SECRETARIO';
    const TECNICO_AYUDANTE = 'TÉCNICO AYUDANTE';
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
    
    //Fondos Rotativos
    const COORDINADOR_CONTABILIDAD = 'COORDINADOR_CONTABILIDAD';
    
    // Modulo Vehiculos
    const CHOFER = 'CHOFER';
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
    public function empleado()
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
    public function obtenerPermisos($user_id)
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

    public function esTecnicoLider()
    {
        return $this->hasRole(User::ROL_LIDER_DE_GRUPO);
        // return $this->empleado->cargo === User::ROL_TECNICO_LIDER_DE_GRUPO;
    }
}
